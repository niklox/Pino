<?php//session_start();require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.UserGroup.php'; require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.UserGroup.php';require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Address.php'; require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Address.php';require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Privileges.php'; require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Privileges.php';require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Nodes.php'; require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Nodes.php';DBcnx();htmlStart();include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/head.php';include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/menu.php';if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];if(isset($_REQUEST["rid"]))$rid = $_REQUEST["rid"]; // rootidif(isset($_REQUEST["nid"]))$nid = $_REQUEST["nid"];	// nodeidif(isset($_REQUEST["pid"]))$pid = $_REQUEST["pid"];	// parentid$URL = "/trms-admin/structure.php";define("MAXLEVEL","20");$maxlevel = 20;$positions = 30;$parentid = 0;$totalnodes = 0;print "<div id=\"content\">\n";if(isset($admin)){	global $action, $rid, $nid, $pid;	if( UserHasPrivilege($admin->getID(), 3) ){	if($action == "editNode") 		editNode($nid);	else if ($action == "saveNode")		saveNode($nid);	else if ($action == "createNode")		createNode();	else if ($action == "deleteNode")		deleteNode($nid);	else		defaultAction();	}else{		print "User has not privilege: 3." . PrivilegeGetName(3);	}}else{	print "Please login!";}htmlEnd();function defaultAction(){	global $rid, $nid, $URL, $admin ;	if($rid)$root = NodeGetByID($rid);		print	"<div class=\"stdbox_600\">\n";	if( UserHasPrivilege($admin->getID(), 17 ) ){		print	"<form>\n<input type=\"hidden\" id=\"action\" name=\"action\" value=\"createNode\">\n" .				"<input type=\"input\" id=\"nodename\" name=\"nodename\" size=\"30\"/>\n";		if(!$rid){			print	"<input type=\"submit\" value=\"Create new structure\">\n" .					"<input type=\"hidden\" name=\"pid\" value=\"0\"/>\n";		}		else{			print	"<input type=\"submit\" value=\"Create childnode for " . MTextGet($root->getNodeNameTextID()) . "\">\n" .					"<input type=\"hidden\" name=\"pid\" value=\"" . $root->getID() . "\"/>\n";		}		print	"</form>\n";	}	print	"</div>\n";	if(!$rid)				print	"<div id=\"list_head\">Structures</div>\n";	else		print	"<div id=\"list_head\">Structure: " . MTextGet($root->getNodeNameTextID()) . "</div>\n";	print	"<div id=\"list_body\">";		if( UserHasPrivilege($admin->getID(), 26) || UserHasPriviledgeForLanguage($admin->getID(), TermosGetCurrentLanguage() ) ){			if(!$rid){										print "<ul class=\"nodelist\">\n";				$root = NodeGetAllRoots();				while($root = NodeGetNext($root)){					if( UserHasPrivilege($admin->getID(), 23 ) == false && $root->getID() != WEBROOT ) continue;					print	"<li>" . 							"<a href=\"" . $URL . "?rid=" . $root->getID() . "\"><img src=\"images/structure_mini.gif\" border=\"0\" alt=\"show structure\"/></a>&nbsp; ". 							"<a href=\"" . $URL. "?action=editNode&nid=" . $root->getID() . "\">" . MTextGet($root->getNodeNameTextID()) . "</a>\n" . 							"</li>\n";				}				print "</ul>\n";					}		else if($rid) renderNodeTree($rid, 0);	}else{		$language = LanguageGetByID(TermosGetCurrentLanguage());		print "No privilege to edit texts in " . $language->getName();		}	print "</div>\n";}function renderNodeTree($nid, $level){	global $URL, $totalnodes;	$add = MTextGet("addcontent");		$level++;	if($level < MAXLEVEL){		$node = NodeGetAllChildren($nid);		print '<ul id="nodelist_'. $nid . '" class="nodelist">';		while($node = NodeGetNext($node)){			$totalnodes++;			print '<li class="lev_' . $level . '">' . $node->getPosition() . '. <a href="'.$URL.'?action=editNode&nid=' . $node->getID() . '">'. MTextGet($node->getNodeNameTextID()) . ' &nbsp;('.$node->getPermalink().')</a>';						if(NodeHasChildren($node->getID())){				print ' &nbsp;<img id="foldout_' . $node->getID() . 'x'.$level.'" class="foldoutimg" src="/trms-admin/images/arrow_13.gif"/>';				renderNodeTree($node->getID(), $level);			}			print '</li>';		}		print '</ul>';				}else { print '<ul><li>Totalnodes: ' . $totalnodes . '</li></ul>'; }		//print '<ul><li>Totalnodes: ' . $totalnodes . '</li></ul>';}function renderNodeTreeOption($nid, $level){	global $maxlevel, $parentid;		$level++;	if($level < $maxlevel){		$node = NodeGetAllChildren($nid);				while($node = NodeGetNext($node)){			print "<option value=\"" . $node->getID();			if($parentid == $node->getID())				print "\" selected>";			else				print "\">\n";						for($i=0; $i<$level; $i++)print "- ";						print $node->getName() . "</option>\n"; 						if(NodeHasChildren($node->getID()))				renderNodeTreeOption($node->getID(), $level);			}	  }else return;}function editNode($nid){	global $dbcnx, $positions, $parentid, $admin;	$node = NodeGetByID($nid);				print	"<div class=\"stdbox_600\">\n";	if( UserHasPrivilege($admin->getID(), 17 ) ){	print	"<form>\n<input type=\"hidden\" id=\"action\" name=\"action\" value=\"createNode\">\n" .			"<input type=\"input\" id=\"nodename\" name=\"nodename\" size=\"30\"/>\n" .			"<input type=\"submit\" value=\"Create childnode for " . MTextGet($node->getNodeNameTextID()) . "\">\n" .			"<input type=\"hidden\" name=\"pid\" value=\"" . $node->getID() . "\"/>\n" .			"</form>\n" ;	}	print	"</div>\n";	//NodeGetFirstParentID($nid)	print	"<form>" .			"<input type=\"hidden\" name=\"action\" value=\"saveNode\"/>\n" .			"<input type=\"hidden\" name=\"nid\" value=\"" . $nid . "\"/>\n";	print	"<div id=\"box_head_600\">Node: " . MTextGet($node->getNodeNameTextID()) . "</div>\n";	print	"<div id=\"box_body_600\" class=\"clearfix\">"; //		print	"<div class=\"boxes\">\n";	/*	 *	Nodename	 */	print	"Nodename<br/><input type=\"text\" name=\"nodename\" id=\"nodename\" value=\"" . MTextGet($node->getNodeNameTextID()) . "\" size=\"30\"/><br/>\n";	/*	 *	Permalink	 */	print	"Permalink<br/><input type=\"text\" name=\"nodepermalink\" id=\"nodepermalink\" value=\"" . MTextGet($node->getPermalinkTextID()) . "\" size=\"30\"/><br/>\n";	/*	 *	Position	 */		print	"Position<br/><select name=\"position\">\n";			for($i=0; $i<$positions; $i++){		if($i == $node->getPosition())		print "<option value=\"".$i."\" SELECTED>".$i."</option>\n";		else		print "<option value=\"".$i."\">".$i."</option>\n";	}	print	"</select><br/>\n";	/*	 *	Nodetype	 */	print "Nodetype<br/>\n<select name=\"nodetype\" id=\"nodetype\">\n<option value=\"0\">" . MTextGet("noPageTypeID") . "</option>\n";	$nodetypes = NodeTypeGetAll();		while($nodetypes = NodeTypeGetNext($nodetypes)){		if($node->getNodeTypeID() == $nodetypes->getID())			print "<option value=\"". $nodetypes->getID() ."\" selected>".  MTextGet($nodetypes->getNodeTypeNameTextID()) . "</option>\n";		else			print "<option value=\"". $nodetypes->getID() ."\">".  MTextGet($nodetypes->getNodeTypeNameTextID()) . "</option>\n";	}	print	"</select><br/><br/>\n";	/*	 *	Languages	 */	print	"Visible for languages<br/>\n<select name=\"languages[]\" id=\"languages\" class=\"multibox\" size=\"6\" multiple>\n";		$language = LanguageGetAll();	while($language = LanguageGetNext($language)){				print "<option value=\"" . $language->getID();				if( NodeHasLanguage($node->getID(), $language->getID()) )			print "\" selected>";  		else			print "\">";		print MTextGet($language->getLanguageNameTextID()) . "</option>\n";	}	print	"</select><br/><br/>\n";		/*	 *	Usergoups	 */	print	"Hidden for usergroups<br/>\n<select name=\"usergroup[]\" id=\"usergroup\" class=\"multibox\" size=\"9\" multiple>\n";		$usergroup = UserGroupGetAll();	while($usergroup = UserGroupGetNext($usergroup )){				print "<option value=\"" . $usergroup->getID();		if( NodeHasUserGroup($node->getID(), $usergroup->getID()) )		print "\" selected>";		else		print "\">";		print MTextGet($usergroup->getUserGroupNameTextID()) . "</option>\n";	}	print	"</select><br/>\n";	print	"</div>\n";	print	"<div class=\"boxes\">\n";	/*	 *	Nodeparent	 */	print  "<b>" . $node->getName() . "</b> " . MTextGet("nodeIsChildTo") . "<br/><select name=\"pid\" id=\"\" class=\"multibox_wide\" size=\"25\">\n";	$parentid = NodeGetFirstParentID($node->getID());	$root = NodeGetAllRoots();	while($root = NodeGetNext($root)){		print "<option value=\"" . $root->getID();				if($parentid == $root->getID())			print "\" selected>" . MTextGet($root->getNodeNameTextID()) . "</option>\n";		else			print "\">";		print MTextGet($root->getNodeNameTextID()) . "</option>\n";		renderNodeTreeOption($root->getID(), 0);	}	print	"</select><br/><br/>\n";	print	"<input type=\"submit\" value=\"Save node\"/> ";	print	"<input type=\"button\" value=\"Delete node\" onclick=\"deleteNode(".$node->getID().",'". MTextGet($node->getNodeNameTextID()) ."')\"/>";		print	"</div>\n";		print	"</div>\n";	print	"</form>\n";}function saveNode($nid){		global $dbcnx, $admin;	$node = NodeGetByID($nid);		if(isset($_REQUEST["nodename"]))$nodename = $_REQUEST["nodename"];	if(isset($_REQUEST["nodepermalink"]))$nodepermalink = $_REQUEST["nodepermalink"];	if(isset($_REQUEST["position"]))$position = $_REQUEST["position"];	if(isset($_REQUEST["nodetype"]))$nodetype = $_REQUEST["nodetype"];	if(isset($_REQUEST["languages"]))$languages = $_REQUEST["languages"];	if(isset($_REQUEST["usergroup"]))$usergroup = $_REQUEST["usergroup"];	if(isset($_REQUEST["pid"]))$parentid = $_REQUEST["pid"];	if($parentid == $nid){				print "A node cannot be a parent to itself!<br/> <a href=\"structure.php?action=editNode&nid=".$nid."\">Please edit &#187;</a>";	}else{		$mtext = MTextGetMTextForLanguage($node->getNodeNameTextID(), TermosGetCurrentLanguage());		$mtext->setTextContent($nodename);		MTextUpdateTextContent($mtext);		if( UserHasPrivilege($admin->getID(), 17 ) ){					$mtext = MTextGetMTextForLanguage($node->getPermalinkTextID(), TermosGetCurrentLanguage());			$mtext->setTextContent($nodepermalink);			MTextUpdateTextContentCopyAllLanguages($mtext);			$node->setPosition($position);			$node->setNodeTypeID($nodetype);			NodeSave($node);			$sqlstr = "DELETE FROM PageCountries WHERE PageID = " . $nid;			mysqli_query($dbcnx, $sqlstr);			for($a=0;$a<count($languages);$a++){				$sqlstr = "INSERT INTO PageCountries(PageID, CountryID) VALUES(". $nid .", ". $languages[$a] .")";				mysqli_query($dbcnx, $sqlstr);			}			$sqlstr = "DELETE FROM PartnerCategoryPages WHERE PageID = " . $nid;			mysqli_query($dbcnx, $sqlstr);			for($a=0;$a<count($usergroup);$a++){				$sqlstr = "INSERT INTO PartnerCategoryPages(PartnerCategoryID, PageID) VALUES(".  $usergroup[$a] .", ". $nid .")";				mysqli_query($dbcnx, $sqlstr);			}						$sqlstr = "DELETE FROM PageChildren WHERE ChildPageID = " . $nid;			mysqli_query($dbcnx, $sqlstr);			$sqlstr = "INSERT INTO PageChildren(PageID, ChildPageID) VALUES(".  $parentid .", ". $nid .")";			mysqli_query($dbcnx, $sqlstr);				}		editNode($nid);		}		}function createNode(){	global $dbcnx, $pid;	if(isset($_REQUEST["nodename"]))$nodename = $_REQUEST["nodename"];	$mtext = MTextNewInCategory("pageTexts", $nodename);	MTextUpdateTextContentCopyAllLanguages($mtext);	$mtext2 = MTextNewInCategory("pageTexts", "");	MTextUpdateTextContentCopyAllLanguages($mtext2);	$node = new Node;	$node->setNodeNameTextID($mtext->getID());	$node->setPermalinkTextID($mtext2->getID());	NodeSave($node);	$value = TermosGetCounterValue("PageID");	if($pid > 0){		$sqlstr = "INSERT INTO PageChildren(PageID, ChildPageID) VALUES(".  $pid .", ". $value .")";		mysqli_query($dbcnx, $sqlstr);	}		editNode($value);}function deleteNode($nid){	$childnodes = NodeGetAllChildren($nid);	if(NodeHasChildren($nid)){		print "<script>alert('This node has children! \\nMove or delete the childnodes first!');</script>";		editNode($nid);	}	else{	 		NodeDelete($nid);		defaultAction();	}}function htmlStart(){    print	"<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n" .			"<html>\n" .			"<head>\n" .			"<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>\n" .			"<title>Termos Structures</title>\n" .			"<link rel=\"stylesheet\" href=\"css/termosadmin.css\"/>\n" .			"<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js\"></script>\n" .			"<script type=\"text/javascript\" src=\"/trms-admin/js/structure_admin.js\"></script>\n" .			"<script>\n" .			"function deleteNode(nid, nodename){\n" .			"	if(confirm('" . MTextGet("deleteNode") . "  ' + nodename + '?'))\n" .			"	location.href = 'structure.php?action=deleteNode&nid=' + nid;\n" .			"}\n" .			"</script>" .			"</head>\n" .			"<body>\n";}function htmlEnd(){	    print	"</div>\n" .			"</body>\n" .			"</html>\n";}?>
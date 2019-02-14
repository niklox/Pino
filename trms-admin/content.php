<?php

require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.UserGroup.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.UserGroup.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Address.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Address.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Privileges.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Privileges.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Nodes.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Nodes.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Content.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Image.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Image.php';
DBcnx();
htmlStart();

include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/menu.php';

if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["cid"]))$cid = $_REQUEST["cid"]; // contentid
if(isset($_REQUEST["nid"]))$nid = $_REQUEST["nid"];	// parentid
if(isset($_REQUEST["rid"]))$rid = $_REQUEST["rid"];	// parentid
if(isset($_REQUEST["tmplid"]))$tmplid = $_REQUEST["tmplid"]; // templateid

define("URL","/trms-admin/content.php");

print "<div id=\"content\">\n";

if(isset($admin))
{
	global $action, $cid, $nid, $tmplid;
	
	if( UserHasPrivilege($admin->getID(), 10) ){

	if($action == "editContent") 
		editContent($cid, $nid);
	else if ($action == "selectTemplate")
		selectTemplate($nid);
	else if ($action == "createContent")
		createContent($nid, $tmplid);
	else if ($action == "saveTextContent")
		saveTextContent($cid, $nid);
	else if ($action == "saveTextContentToAllLanguages")
		saveTextContentToAllLanguages($cid, $nid);
	else if ($action == "saveMetaInformation")
		saveMetaInformation($cid, $nid);
	else if ($action == "deleteContent")
		deleteContent($cid);
	else
		defaultAction();

	}else{
		print "No permission";
	}
}
else
{
	print "Please login!";
}

htmlEnd();

function defaultAction(){

	global $admin, $rid, $nid;

	print	'<div class="stdbox_600" >' . //class=\"contentbox\"
			'<select class="std_select" name="rootNodes" id="rootNodes">' .
			'<option value="0">'.MTextGet('selectStructure').'</option>';

	$roots = NodeGetAllRoots();
	while($roots = (NodeGetNext($roots))){

		print '<option value="'. $roots->getID() .'" '.($rid == $roots->getID() ? "selected" : "").'>' . $roots->getName() . '</option>';
	}
	print	'</select>';

	print	'<select class="std_select" name="nodeTree" id="nodeTree">';

	if($rid > 0){
		
		print	'<option value="0">'. MTextGet('selectNode') .'</option>';
		PrintNodeTreeOptions($rid, 0, 0, 0, 1);
	}
	else
	print	'<option value="0">'. MTextGet('selectStructureFirst').'</option>';	
	print	'</select>';
	print	' <input type="button" class="std_button" id="displayTree" value="' . MTextGet('displayNodeTree') . '">';
	
	print '</div>';
	print	"<div id=\"list_head\">" .
			"<form>" . 
			"<input type=\"hidden\" name=\"action\" id=\"action\" value=\"selectTemplate\">" . 
			"<input type=\"hidden\" name=\"nid\" id=\"nid\" value=\"\">" . 
			"<input type=\"submit\" class=\"wht\" value=\"" . MTextGet("createContent") . "\">" . 
			"</form>" . 
			"</div>" .
			"<div id=\"list_body\"></div>";
}

function editContent($cid, $nid){

	//var $position_in_node;

	$content = ContentGetByID($cid);

	//print	"<div id=\"imagedialog\"><div>";

	
	print	'<div class="contentbox">' . 
			'<div class="contentboxhead">Content: '. strip_tags($content->getTitle()) .
			'<form id="contentTexts" method="post">' .
			'</div>';
			
	print	'<div class="contentboxinner">'. 
	
			'<input type="button" class="meta" value="'. MTextGet("saveToAllLanguages") .'" onclick="saveToAllLanguages()"/>'. 
			'<input type="submit" class="meta" value="'. MTextGet("saveContentTexts") .'"/>'.
			'<input type="button" class="meta" value="Reload" onclick="location.href=\'content.php?action=editContent&cid='.$cid.'&nid='.$nid.'\'">' . 
			'<input type="button" class="meta" id="delete" value="'. MTextGet("deleteContent") . '" onclick="deleteContent('.$content->getID().')"/>' .
			'<input type="button" class="meta" id="templatedescr" value="'. MTextGet("templatedescription") .'"/> ' .
			'<h5 class="texthead_u">Texts</h5> ' .
		
			'<input type="hidden" name="action" id="action" value="saveTextContent"/>' .
			'<input type="hidden" name="cid" id="cid" value="'.$content->getID().'"/>'. 
			'<input type="hidden" name="pos" id="pos" value=""/>'. 
			'<input type="hidden" name="nid" id="nid" value="'.$nid.'"/>';	

	$mtext = MTextGetMTextByLanguage($content->getTitleTextID(), TermosGetDefaultLanguage());
	print	'<h5 class="texthead"><a href="mtext.php?action=edit&textid='.$mtext->getID().'&textcatid='.$mtext->getTextCategoryID().'">Title</a></h5>' .
			'<input type="text" class="wideinput" name="' . $content->getTitleTextID() . '" id="'. $content->getTitleTextID() .'" value="'.$content->getTitle().'"/>';
	
	$mtext = MTextGetMTextByLanguage($content->getPermalinkTextID(), TermosGetDefaultLanguage());
	print	'<h5 class="texthead"><a href="mtext.php?action=edit&textid='.$mtext->getID().'&textcatid='.$mtext->getTextCategoryID().'">Permalink</a></h5>';
	/* to automaticly create a permalink we use the title and urlencode it  */
	print	'<input type="text"  class="wideinput" name="' . $content->getPermalinkTextID() .'" id="'. $content->getPermalinkTextID() .'" value="' . super_urlencode(strip_tags(MTextGet($content->getTitleTextID()))) .'"/><br/>';
	
	$mtext = MTextGetMTextByLanguage($content->getContentTextID(), TermosGetDefaultLanguage());
	print	'<h5 class="texthead"><a href="mtext.php?action=edit&textid='.$mtext->getID().'&textcatid='.$mtext->getTextCategoryID().'">Text</a></h5>' .
			'<textarea class="widetext" name="'. $content->getContentTextID() .'" id="'. $content->getContentTextID() .'" />'. $content->getContentText() .'</textarea><br/>';
	
	$mtext = MTextGetMTextByLanguage($content->getTagsTextID(), TermosGetDefaultLanguage());
	print	'<h5 class="texthead"><a href="mtext.php?action=edit&textid='.$mtext->getID().'&textcatid='.$mtext->getTextCategoryID().'">Tags</a></h5>';
	print	'<textarea class="widetext" name="'. $content->getTagsTextID() .'" id="'. $content->getTagsTextID() .'">' . $content->getTags() . '</textarea><br/>';

	$contenttext = ContentTextGetAllForContent($content->getID());
	
	$i = 1;
	while($contenttext = ContentTextGetNext($contenttext)){
		
		$mtext = MTextGetMTextByLanguage($contenttext->getTextID(), TermosGetDefaultLanguage());
		print	'<h5 class="texthead"><a href="mtext.php?action=edit&textid='.$mtext->getID().'&textcatid='.$mtext->getTextCategoryID().'" target="_new">'.($content->getTemplateID() == 37?"Block":"Text").' '.$i.'</a></h5>';
		print	'<textarea name="'. $contenttext->getTextID() .'" id="'. $contenttext->getTextID() . '" class="widetext">'. MTextGet($contenttext->getTextID()) . '</textarea><br/>';
		$i++;	
	}

	print	'<span class="btnright">'.
			'<input type="button" class="meta" id="templatedescription" value="'. MTextGet("templatedescription") .'"/>'. 
			'<input type="button" class="meta" value="'. MTextGet("saveToAllLanguages") .'" onclick="saveToAllLanguages()"/>'. 
			'<input type="submit" class="meta" value="'. MTextGet("saveContentTexts") .'"/>'.
			'</span>' .
			"</form>\n" ;
	/*		
	print	"<div id=\"imagebox\">\n".
			"<h5 class=\"texthead_u\">Images</h5>\n";

			$template = TemplateGetByID($content->getTemplateID());
			
			for($i=1;$i<$template->getImages()+1;$i++){

				print	"<div id=\"imgbox".$i."\" class=\"contentimagebox\">\n";
				print	"<div class=\"contentimagehead\"><h5 class=\"texthead\"># ".$i . "</h5>" .
						//" <input type=\"button\" class=\"addcontent\" id=\"addbtn".$i."\" value=\"+\">".
						//" <input type=\"button\" class=\"addcontent\" id=\"delbtn".$i."\" value=\"-\">".
						"</div>";
				print	"	<div id=\"image_".$i."\" class=\"contentimage\">";
						DisplayImage($cid, $i);
				print	"</div>\n";
				print	"</div>\n";
			}
	
	//print	"<input type=\"button\" id=\"addImage\" value=\"".MTextGet("addImage")."\">\n";

	print	"</div>\n";
	
	*/
	print	"</div>\n";
	print	"</div>\n";
	
	print 	'<div class="imagecolumn">'.
			'<div class="contentright">';
	
	print		'<h5 class="texthead_u">Images</h5>';

			$template = TemplateGetByID($content->getTemplateID());
			
			for($i=1;$i<$template->getImages()+1;$i++){
			
				print	'<div id="imgbox'.$i.'" class="contentimagebox">'. //
						'	<div class="contentimagehead"><h5 class="texthead"># '.$i . '</h5></div>'.
						'	<div id="image_'.$i.'" class="contentimage">';
						ImageGetII_SMALL($cid, $i, "");
				print	'	</div>';
				print	'</div>';
			}
	
	print 	'</div>'.
			'</div>';

	print	'<div class="metacolumn">' . 
			'<form>' . 
			'<input type="hidden" name="action" value="saveMetaInformation"/>'.
			'<input type="hidden" name="cid" value="'.$content->getID().'"/>' .
			'<input type="hidden" name="nid" value="'.$nid.'"/>'.
			'<input type="hidden" name="tmplid" id="tmplid" value="'.$content->getTemplateID().'"/>';

	print	'<div id="content_extra" class="contentright">'; 
	print	'<h5 class="texthead_u">' . MTextGet("metaInformation") . '</h5>';
	print	'<div class="dateline">' . MTextGet("contentID") . ': <span class="boxright">' . $content->getID() . '</span></div>';
	print	'<div class="dateline">' . MTextGet("currentNode") . ': <span class="boxright">' . NodeGetName($nid) . '</span></div>';
	
	if($content->getTemplateID() == 9 || $content->getTemplateID() == 19)
	print	'<div class="dateline">' . MTextGet("externalid") . ': <span class="boxright"><input type="text" name="externalid" class="meta" id="externalid" value="' . $content->getExternalID() . '" size="24"/></span></div>';

	if($content->getTemplateID() == 9 || $content->getTemplateID() == 19){
		
		print	'<div class="dateline">' . MTextGet("price") . ':<span class="boxright"><input type="text" class="meta" name="price" id="price" value="' . $content->getValue() . '" size="5"/></span></div>';
	}
	
	print	'<div class="dateline">' . ($content->getTemplateID()==9?MTextGet("soldout"):MTextGet("commentable")) . '<span class="boxright"><input type="checkbox" name="flag" id="flag" value="1" '.($content->getFlag()== 1 ? " checked" : "").'/></span></div>';

	
	print	'<div class="dateline">' . MTextGet("templatename") . ':<span class="boxright"><select name="templateid" class="meta" id="templateid">';
	
	$templates = TemplateGetAll();
	while($templates = TemplateGetNext($templates)){
		
		if($templates->getID() == $content->getTemplateID())
		print	'<option value="'.$templates->getID().'" selected>'. TemplateGetName($templates->getID()).'</option>';
		else
		print	'<option value="'.$templates->getID().'">'. TemplateGetName($templates->getID()).'</option>';

	}
	print	'</select></span></div>';
	
	if($content->getTemplateID() == 16){
		print	'<div class="dateline">' . MTextGet("ipnumber") . ': <span class="boxright">'. $content->getExternalID() .'</span></div>';
	}

	print	'<div class="divider"></div>';
			if($nid == $content->getDefaultNodeID()){
				print	'<div class="boxline">' . MTextGet("defaultNode") . ':<span class="boxright">';
				print	'<select id="defaultnode" name="defaultnode" class="meta">';
					PrintRootNodesOptions($content->getID(), $content->getDefaultNodeID() , 1);
				print	'</select>';
				print	'</span></div>';
			}else{
				print	'<div class="dateline">' . MTextGet("defaultNode") .':';
				print	'<input type="hidden" name="defaultnode" id="defaultnode" value="'.$content->getDefaultNodeID().'"/>'; 
				print	'<span class="boxright">' . NodeGetName($content->getDefaultNodeID()) . '</span>';
				print	'</div>';
			}
	
	print	'<div class="divider"></div>';
	print	'<div class="dateline">' . MTextGet("createdDate") . ':<span class="boxright">' .
			'<input type="text" class="dateinput" name="createddate" id="createddate" value="'.date('Y-m-d', strtotime($content->getCreatedDate())).'"/>' . 
			'<input type="text" class="timeinput" name="createdtime" id="createdtime" value="'.date('H:i:s', strtotime($content->getCreatedDate())).'"/>' . 
			'</span></div>';
	
	print	'<div class="dateline">'. MTextGet("archiveDate") . ':<span class="boxright">' .
			'<input type="text" class="dateinput" name="archivedate" id="archivedate" value="'.date('Y-m-d', strtotime($content->getArchiveDate())).'"/>' .
			'<input type="text" class="timeinput" name="archivetime" id="archivetime" value="'.date('H:i:s', strtotime($content->getArchiveDate())).'"/>' .
			'</span></div>';

	print	'<div class="dateline">'. MTextGet("startDate")  . ':<span class="boxright">' .
			'<input type="text" class="dateinput" name="startdate" id="startdate" value="'.date('Y-m-d', strtotime($content->getStartDate())).'"/>' .
			'<input type="text" class="timeinput" name="starttime" id="starttime" value="'.date('H:i:s', strtotime($content->getStartDate())).'"/>' .
			'</span></div>';
	
	print	'<div class="dateline">'. MTextGet("endDate") . ':</span><span class="boxright">' .
			'<input type="text" class="dateinput" name="enddate" id="enddate" value="'.date('Y-m-d', strtotime($content->getEndDate())).'"/>' .
			'<input type="text" class="timeinput" name="endtime" id="endtime" value="'.date('H:i:s', strtotime($content->getEndDate())).'"/>' .
			'</span></div>';
	
	print	'<div class="divider"></div>';
	
	print	'<div class="dateline">'. MTextGet("positionInNode") . ':<span class="boxright">';
	
	$position_in_node = ContentHasNode($cid, $nid);
	
	print	'<select class="metashort" name="position">';
			for($i=0; $i<50; $i++)
			print	'<option value="'. $i .'"' . ( $position_in_node == $i ? " selected" : " ") . '>'. $i .'</option>';
	print	'</select>';
	
	print	'</span></div>';
	
	

	
	print	MTextGet("attachedToNode") . ':<br/>';
	print	'<select id="nodebox" name="nodes[]" multiple size="12" class="metanodebox">'; //class="metanodebox"
			PrintRootNodesOptions($content->getID(), $content->getDefaultNodeID(),  2);
	print	'</select>';
	
	//</div>';
	
	print	'<div class="divider"></div>';
	
	
	print	'<div class="dateline">' . MTextGet("latestSaveBy") . ':<span class="boxright">' . UserGetName($content->getAuthorID()) . '</span></div>';

	print	'<div class="dateline"><span class="btnright"><input type="submit" value="'. MTextGet("saveContentMeta") . '"></span></div>';
	
	
	
	print	'</div>';
	

	
	print	'</form>'; 
	print	'<div id="content_forms" class="contentright">';
	print	'<h5 id="openforms" class="texthead_u_link">' . MTextGet("showforms") . ' &#187;</h5>';
	print	'</div>'; 
	
	print	'</div>';
	

}

function saveTextContent($cid, $nid){
	global $admin;
	foreach($_REQUEST as $key => $value){
			if(strstr($key, "contentTexts")){
			$mtext = MTextGetMTextForLanguage($key, TermosGetCurrentLanguage());
			$mtext->setTextContent($value);
			MTextUpdateTextContent($mtext);
		}
	} 
	if( $content = ContentGetByID($cid) ) {
		$content->setAuthorID($admin->getID());
		ContentSave($content);
	}

	editContent($cid,$nid);
}

function saveTextContentToAllLanguages($cid, $nid){
	
	global $admin;
	foreach($_REQUEST as $key => $value){
			if(strstr($key, "contentTexts")){
			$mtext = MTextGetMTextForLanguage($key, TermosGetCurrentLanguage());
			$mtext->setTextContent($value);
			MTextUpdateTextContentCopyAllLanguages($mtext);
		}
	} 
	if( $content = ContentGetByID($cid) ) {
		$content->setAuthorID($admin->getID());
		ContentSave($content);
	}

	editContent($cid,$nid);
}


function saveMetaInformation($cid, $nid){
	global $admin;
	if(isset($_REQUEST["defaultnode"]))$defaultnode = $_REQUEST["defaultnode"];
	if(isset($_REQUEST["templateid"]))$templateid = $_REQUEST["templateid"];
	if(isset($_REQUEST["externalid"]))$externalid = $_REQUEST["externalid"];
	if(isset($_REQUEST["price"]))$price = $_REQUEST["price"];
	if(isset($_REQUEST["subtypeid"]))$subtypeid = $_REQUEST["subtypeid"];
	if(isset($_REQUEST["createddate"]))$createddate = $_REQUEST["createddate"];
	if(isset($_REQUEST["archivedate"]))$archivedate = $_REQUEST["archivedate"];
	if(isset($_REQUEST["startdate"]))$startdate = $_REQUEST["startdate"];
	if(isset($_REQUEST["enddate"]))$enddate = $_REQUEST["enddate"];
	if(isset($_REQUEST["createdtime"]))$createdtime = $_REQUEST["createdtime"];
	if(isset($_REQUEST["archivetime"]))$archivetime = $_REQUEST["archivetime"];
	if(isset($_REQUEST["starttime"]))$starttime = $_REQUEST["starttime"];
	if(isset($_REQUEST["endtime"]))$endtime = $_REQUEST["endtime"];
	if(isset($_REQUEST["flag"]))$flag = $_REQUEST["flag"]; else $flag = 0;
	if(isset($_REQUEST["position"]))$position = $_REQUEST["position"]; else $position = 0;

	$created = $createddate . " " . $createdtime;
	$archive = $archivedate . " " . $archivetime;
	$start = $startdate . " " . $starttime;
	$end = $enddate . " " . $endtime;

	/*
	print "Save Meta Info<br/>";
	foreach($_REQUEST as $key => $value){ 
		
		print $key . ": " . $value . "<br/>";
	}
	*/
	if( $content = ContentGetByID($cid) ) {

		// Update nodes

		if(isset($_REQUEST["nodes"]))$nodes = $_REQUEST["nodes"];

		ContentDetachNodes($cid);
		for ($a = 0; $a < count($nodes); $a++ ) {

				// As an option is formatted like <option value="NID,POS">NODENAME</option>
				if(strpos($nodes[$a], ",")){
					$nodeid = substr($nodes[$a], 0, strpos($nodes[$a], ","));
					$pos = substr($nodes[$a], strpos($nodes[$a], ",")+1 , strlen($nodes[$a]));
					ContentAddNodeAndPosition($cid,$nodeid,$pos);
				}
				else print ContentAddNodeAndPosition($cid, $nodes[$a], 0);
		}
		if($a < 1)
		ContentAddNodeAndPosition($cid, $content->getDefaultNode(), 0);

		// Upddate position in this node
		ContentUpdatePositionAtNode($cid, $nid, $position);

		// Update defaultnode and position
		if($content->getDefaultNodeID() != $defaultnode){
			//ContentDeleteNode($cid, $content->getDefaultNodeID());
			ContentAddNodeAndPosition($cid, $defaultnode, $position);
		}

		// Update the instance
		$content->setDefaultNodeID($defaultnode);
		$content->setTemplateID($templateid);
		$content->setCreatedDate($created);
		$content->setArchiveDate($archive);
		$content->setStartDate($start);
		$content->setEndDate($end);
		$content->setFlag($flag);
		$content->setValue($subtypeid);
		$content->setExternalID($externalid);
		$content->setValue($price);
		$content->setAuthorID($admin->getID());
		ContentSave($content);
		
		editContent($cid,$nid);
	}
	else print "No Content with ID =" . $cid;
	
}

function selectTemplate($nid){
	
	print	"<div class=\"head\"><form onsubmit=\"return checkSelection()\">". MTextGet("selectTemplate")  . 
			"<div id=\"selectedtemplate\" class=\"templateselect\">" . MTextGet("select-template") . "</div>\n" .
			"<input type=\"hidden\" name=\"action\" value=\"createContent\"/>\n" . 
			"<input type=\"hidden\" id=\"tmplid\" name=\"tmplid\" value=\"0\"/>\n" . 
			"<input type=\"hidden\" id=\"nid\" name=\"nid\" value=\"" . $nid ."\"/>\n" .
			"<input type=\"submit\" class=\"selectbtn\" value=\"Create content\"/>\n" .
			"</form>\n" .
			"</div>\n";

	print	"<div class=\"stdbox\">";
	
	$template = TemplateGetAll();
	
	while($template = TemplateGetNext($template)){
		print "<div class=\"boxes_white\" onclick=\"setSelectedTemplate(" . $template->getID() . ", '" . $template->getName(). "')\">";
		print $template->getName() . "<br/>";

		if($image = ImageGetByHandleAndLanguage($template->getImageHandle(), -1))
		ImageCreateTag($image, 1000, 1);

		print "</div>\n";
	}
	
	print	"</div>\n";
}

function createContent($nid, $tmplid){
	global $admin;

	// LOCK TABLES
	
	$tmpid = TermosGetCounterValue("PageContentID");

	ContentUpdateNode($tmpid+1, $nid);
	
	// Create extra texts
	$template = TemplateGetByID($tmplid);

	for($i=0; $i<$template->getTexts(); $i++){
		
		$mtext = MTextNewInCategory("contentTexts", "no text");
		MTextUpdateTextContentCopyAllLanguages($mtext);
		
		$contenttext = new ContentText;
		$contenttext->setContentID($tmpid+1);
		$contenttext->setTextID($mtext->getID());
		$contenttext->setPosition($i+1);
		ContentTextSave($contenttext);
	}

	$ttextid = MTextNewInCategory("contentTexts", "Content ID:");
	$ctextid = MTextNewInCategory("contentTexts", "no text");
	$tagtextid = MTextNewInCategory("contentTexts", "no text");
	$ptextid = MTextNewInCategory("contentTexts", "no text");

	MTextUpdateTextContentCopyAllLanguages($ttextid);
	MTextUpdateTextContentCopyAllLanguages($ctextid);
	MTextUpdateTextContentCopyAllLanguages($tagtextid);
	MTextUpdateTextContentCopyAllLanguages($ptextid);
	
	$content = new Content;
	$content->setID(0);
	$content->setDefaultNodeID($nid);
	$content->setCreatedDate( date("Y-m-d H:i:s") );
	$content->setTitleTextID($ttextid->getID());
	$content->setContentTextID($ctextid->getID());
	$content->setArchiveFlag(0);
	$content->setAuthorID($admin->getID());
	$content->setArchiveDate(date('Y-m-d H:i:s', strtotime('+10 year')));
	$content->setPosition(0);
	$content->setTagsTextID($tagtextid->getID());
	$content->setTemplateID($tmplid);
	$content->setPermalinkTextID($ptextid->getID());
	$content->setFlag(0);
	$content->setCounter(0);
	$content->setExternalID("");
	$content->setValue("");
	$content->setNumericValue("");
	$content->setStatus(0);
	$content->setStartDate(date("Y-m-d H:i:s"));
	$content->setEndDate(date("Y-m-d H:i:s", strtotime('+10 year')));

	ContentSave($content);

	// UNLOCK TABLES

	editContent($tmpid+1, $nid);
}

function deleteContent($cid){
	
	ContentDelete($cid);
	defaultAction();
}


function htmlStart(){
/*
    print	"<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n" .
			"<html>\n" .
			"<head>\n" .
			"<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>\n" .
			"<title>Termos Content</title>\n" .
			"<link rel=\"stylesheet\" href=\"css/termosadmin.css\"/>\n" .
			"<link rel=\"stylesheet\" href=\"https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/base/jquery-ui.css\" type=\"text/css\" media=\"all\" />\n" .
			"<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js\"></script>\n" .
			"<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js\"></script>\n" .
			"<script type=\"text/javascript\" src=\"/trms-admin/js/content_admin.js\"></script>\n" .
			"<script type=\"text/javascript\" src=\"/trms-admin/js/jquery.form.js\"></script>\n" .
			"<script>\n" .
			"function deleteContent(cid){\n" .
			"	if(confirm('" . MTextGet("reallyDeleteContent") . "?'))\n" .
			"	location.href = 'content.php?action=deleteContent&cid=' + cid;\n" .
			"}\n" .
			"function saveToAllLanguages(){\n" .
			"	if(confirm('" . MTextGet("saveToAllLanguages") . "?')){\n" .
			"	document.getElementById('action').value = 'saveTextContentToAllLanguages'; \n" .
			"	document.getElementById('contentTexts').submit();\n" .
			"	}else{\n" .
			"	document.getElementById('action').value = 'saveTextContent';\n" .
			"	}\n" .
			"}\n" .
			
			"</script>\n" .
			"</head>\n" .
			"<body>\n";
		*/
		
	 print	'<!DOCTYPE html>' .
			'<html>' .
			'<head>' .
			'<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' .
			'<title>Edit Content</title>' .
			'<link rel="stylesheet" href="css/termosadmin.css"/>' .
			
			'<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/base/jquery-ui.css" type="text/css" media="all" />' .
			'<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>' .
			'<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js"></script>' .
			
			/*'<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>' .
			'<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">'.
			'<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>'.*/
			
			'<script type="text/javascript" src="/trms-admin/js/content_admin.js"></script>' .
			'<script type="text/javascript" src="/trms-admin/js/jquery.form.js"></script>' .
			
			'<script>' .
			'function deleteContent(cid){' .
			'	if(confirm("' . MTextGet("reallyDeleteContent") . '?"))' .
			'	location.href = "content.php?action=deleteContent&cid=" + cid;' .
			'}' .
			'function saveToAllLanguages(){' .
			'	if(confirm("' . MTextGet("saveToAllLanguages") . '?")){' .
			'	document.getElementById("action").value = "saveTextContentToAllLanguages"; ' .
			'	document.getElementById("contentTexts").submit(); ' .
			'	}else{' .
			'	document.getElementById("action").value = "saveTextContent";' .
			'	}' .
			'}' .
			'function createContentText(){' .
			'	if(confirm("' . MTextGet("addContentText") . '?")){' .
			'	document.getElementById("action").value = "createContentText"; ' .
			'	document.getElementById("contentTexts").submit();' .
			'	}else{' .
			'	document.getElementById("action").value = "editContent";' .
			'	}' .
			'}' .
			'function deleteContentText(cid, pos){' .
			'	if(confirm("' . MTextGet("deleteContentText") . '?" + cid + " " + pos)){' .
			'	document.getElementById("action").value = "deleteContentText"; ' .
			'	document.getElementById("pos").value = pos;' .
			'	document.getElementById("contentTexts").submit();' .
			'	}else{' .
			'	document.getElementById("action").value = "editContent";' .
			'	}' .
			'}' .
			'</script>' .
			'</head>' .
			'<body>';
}

function htmlEnd(){
	
    print	"</div>\n" .
			"</body>\n" .
			"</html>\n";
}

?>
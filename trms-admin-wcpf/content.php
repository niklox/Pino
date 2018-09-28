<?php
//session_start();
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
if(isset($_REQUEST["pos"]))$pos = $_REQUEST["pos"]; // position
if(isset($_REQUEST["nid"]))$nid = $_REQUEST["nid"];	// parentid
if(isset($_REQUEST["rid"]))$rid = $_REQUEST["rid"];	// parentid
if(isset($_REQUEST["tmplid"]))$tmplid = $_REQUEST["tmplid"]; // templateid

define("URL","/trms-admin/content.php");

print '<div id="content">';

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
		else if ($action == "createContentText")
			 createContentText($cid, $nid);
		else if ($action == "deleteContentText")
			 deleteContentText($cid, $pos, $nid);
		else if ($action == "deleteContent")
			deleteContent($cid);
		else
			defaultAction();

	}else{
		print "User has not privilege: 10." . PrivilegeGetName(10);
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
	print	'</select> ';

	print	'<select class="std_select" name="nodeTree" id="nodeTree">';

	if($rid > 0){
		
		print	"<option value=\"0\">". MTextGet('selectNode') ."</option>\n";
		PrintNodeTreeOptions($rid, 0, 0, 0, 1);
	}
	else
	print	'<option value="0">'. MTextGet('selectStructureFirst').'</option>' ;	
	
	print	'</select> ';
		
	print	'<input class="std_button" type="button" id="displayTree" value="' . MTextGet('displayNodeTree') . '">';

	print 	'</div>';

	print	'<div id="list_head">' .
			'<form>' . 
			'<input type="hidden" name="action" id="action" value="selectTemplate">' . 
			'<input type="hidden" name="nid" id="nid" value="">' . 
			'<input type="submit" class="std_button_border" value="' . MTextGet("createContent") . '">' . 
			'</form>' . 
			'</div>' .
			'<div id="list_body"></div>';
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
	
			'<input type="button" class="greybtn" value="'. MTextGet("addcontenttext") .'" onclick="createContentText()" />'.
			'<input type="button" class="greybtn" value="'. MTextGet("saveToAllLanguages") .'" onclick="saveToAllLanguages()"/>'. 
			'<input type="submit" class="greybtn" value="'. MTextGet("saveContentTexts") .'"/>'.
			'<input type="button" class="greybtn" value="Reload" onclick="location.href=\'content.php?action=editContent&cid='.$cid.'&nid='.$nid.'\'">' . 
			'<input type="button" class="greybtn" id="delete" value="'. MTextGet("deleteContent") . '" onclick="deleteContent('.$content->getID().')"/> &nbsp;' .
			'<input type="button" class="greybtn" id="templatedescr" value="'. MTextGet("templatedescription") .'"/> ' .
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
		
		if($content->getTemplateID() == 37 && $i > 2){
			
			print	'<span class="texthead"><a href="mtext.php?action=edit&textid='.$mtext->getID().'&textcatid='.$mtext->getTextCategoryID().'" target="_new">'.($content->getTemplateID() == 37?"Block":"Text").' '.$i.'</a></span>';
			
			$blocktypeid = $contenttext->getFlag();
			
			print	'<select class="blocktype" id="blocktype_'.$contenttext->getContentID().'x'.$contenttext->getPosition().'">' .
					'<option value="0" '.($blocktypeid == 0?"selected":"").'>Choose blocktype</option>'.
					'<option value="1" '.($blocktypeid == 1?"selected":"").'>Textblock</options>'.
					'<option value="2" '.($blocktypeid == 2?"selected":"").'>Imagebreaker 100%</option>'.
					'<option value="3" '.($blocktypeid == 3?"selected":"").'>Headerbreaker</option>' .
					'<option value="4" '.($blocktypeid == 4?"selected":"").'>Quotebreaker</option>' .
					'<option value="5" '.($blocktypeid == 5?"selected":"").'>Image in textblock</option>' .
					'</select>';
					 
			print 	'<select class="blocktypeoption" id="blocktypeoption_'.$contenttext->getContentID().'x'.$contenttext->getPosition().'">';
			if($blocktypeid == 0)
			print	'<option value="0">Choose blocktypeoption</option>'.
					'<option>Choose blocktype first!</option>';
			else	
			Print_BlocktypeOptions($blocktypeid, $contenttext->getFlagII());
			
			print 	'</select>';
			
		}
		else
		print	'<h5 class="texthead"><a href="mtext.php?action=edit&textid='.$mtext->getID().'&textcatid='.$mtext->getTextCategoryID().'" target="_new">'.($content->getTemplateID() == 37?"Block":"Text").' '.$i.'</a> '. 
		//'<input type="button" value="'.MTextGet("deleteText").'" onclick="deleteContentText('.$content->getID().','.$i.')"/>'.
		'</h5>';
	
		print	'<textarea name="'. $contenttext->getTextID() .'" id="'. $contenttext->getTextID() . '" class="widetext">'. MTextGet($contenttext->getTextID()) . '</textarea><br/>';
		$i++;	
	}

	print	'<span class="btnright">'.
			'<input type="button" class="greybtn" value="'. MTextGet("addcontenttext") .'" onclick="createContentText()" />'.
			'<input type="button" class="greybtn" id="templatedescription" value="'. MTextGet("templatedescription") .'"/>'. 
			'<input type="button" class="greybtn" value="'. MTextGet("saveToAllLanguages") .'" onclick="saveToAllLanguages()"/>'. 
			'<input type="submit" class="greybtn" value="'. MTextGet("saveContentTexts") .'"/>'.
			'</span>' .
			"</form>\n" ;

	print	'</div>';
	print	'</div>';
	
	
	print 	'<div class="imagecolumn">'.
			'<div class="contentright">';
	
	print		'<h5 class="texthead_u">Images</h5>';

			$template = TemplateGetByID($content->getTemplateID());
			
			for($i=1;$i<$template->getImages()+1;$i++){
			
				print	'<div id="imgbox'.$i.'" class="contentimagebox">'. //
						'	<div class="contentimagehead"><h5 class="texthead"># '.$i . '</h5></div>'.
						'	<div id="image_'.$i.'" class="contentimage">';
						if($template->getID() > 36)
						ImageGetII_SMALL($cid, $i, "");
						else
						DisplayImage($cid, $i);
				print	'	</div>';
				print	'</div>';
			}
			

	print 	'</div>'.
			'</div>';
			
	/**
	 *
	 * META Information column
	 *
	 */
	
	print	'<div class="metacolumn">' . 
			'<form>' . 
			'<input type="hidden" name="action" value="saveMetaInformation"/>' .
			'<input type="hidden" name="cid" value="'.$content->getID().'"/>' .
			'<input type="hidden" name="nid" value="'.$nid.'"/>' .
			'<input type="hidden" name="tmplid" id="tmplid"  value="'.$content->getTemplateID().'"/>' ;

	print	'<div id="content_extra" class="contentright">'; 
	print	'<h5 class="texthead_u">' . MTextGet("metaInformation") . '</h5>';
	print	'<p class="dateline">' . MTextGet("contentID") . ': <span class="boxright">' . $content->getID() . '</span></p>'; //
	print	'<p class="dateline">' . MTextGet("currentNode") . ': <span class="boxright">' . NodeGetName($nid) . '</span></p>';

	print	'<p class="dateline">' . MTextGet("templatename") . '<span class="boxright"><select name="templateid" id="templateid">';
	
	$templates = TemplateGetAll();
	while($templates = TemplateGetNext($templates)){
		
		if($templates->getID() == $content->getTemplateID())
		print	'<option value="'.$templates->getID().'" selected>'. TemplateGetName($templates->getID()).'</option>';
		else
		print	'<option value="'.$templates->getID().'">'. TemplateGetName($templates->getID()).'</option>';

	}
	print	'</select></span></p>';
	
	if( $content->getTemplateID() == 37 || $content->getTemplateID() == 38 || $content->getTemplateID() == 49){
		print	'<p class="dateline">' . MTextGet("subtype") . 
				'<span class="boxright"><select name="subtypeid" id="subtypeid">';
		print	'<option value="0">'. MTextGet("selectSubtype").'</option>';
		print	'<option value="showsiblings" '.($content->getValue()=="showsiblings"?"SELECTED":"").'>'. MTextGet("showsiblings") . '</option>';
		print	'<option value="showchildren" '.($content->getValue()=="showchildren"?"SELECTED":"").'>'. MTextGet("showchildren") . '</option>';
		print	'<option value="showparents" '.($content->getValue()=="showparents"?"SELECTED":"").'>'. MTextGet("showparents") . '</option>';
		print	'<option value="showchildtextlinks" '.($content->getValue()=="showchildtextlinks"?"SELECTED":"").'>'. MTextGet("showchildtextlinks") . '</option>';
		print	'<option value="showsiblingtextlinks" '.($content->getValue()=="showsiblingtextlinks"?"SELECTED":"").'>'. MTextGet("showsiblingtextlinks") . '</option>';


		print	'</select></span></p>';

	}
	
	if( $content->getTemplateID() == 51 ){
		print	'<p class="dateline">' . MTextGet("subtype") . 
				'<span class="boxright"><select name="subtypeid" id="subtypeid">';
		print	'<option value="0">'. MTextGet("selectSubtype").'</option>';
		print	'<option value="showtopimage" '.($content->getValue()=="showtopimage"?"SELECTED":"").'>'. MTextGet("showtopimage") . '</option>';
		print	'<option value="showtoporange" '.($content->getValue()=="showtoporange"?"SELECTED":"").'>'. MTextGet("showtoporange") . '</option>';
		print	'<option value="showtopnavy" '.($content->getValue()=="showtopnavy"?"SELECTED":"").'>'. MTextGet("showtopnavy") . '</option>';
		

		print	'</select></span></p>';

	}
	
	if( $content->getTemplateID() == 27 || $content->getTemplateID() == 19 || $content->getTemplateID() == 24 || $content->getTemplateID() == 15 || $content->getTemplateID() == 35){
		print	'<p class="dateline">' . MTextGet("subtype") . 
				'<span class="boxright"><select name="subtypeid" id="subtypeid">';
		print	'<option value="0">'. MTextGet("selectSubtype").'</option>';
		print	'<option value="topvideo" '.($content->getValue()=="topvideo"?"SELECTED":"").'>'. MTextGet("hasTopVideo") . '</option>';
		print	'<option value="topimage" '.($content->getValue()=="topimage"?"SELECTED":"").'>'. MTextGet("hasTopImage") . '</option>';
		print	'<option value="topgallery" '.($content->getValue()=="topgallery"?"SELECTED":"").'>'. MTextGet("hasTopGallery") . '</option>';
		print	'<option value="topquiz" '.($content->getValue()=="topquiz"?"SELECTED":"").'>'. MTextGet("hasTopQuiz") . '</option>';
		
		print	'</select></span></p>';

		print	'<p class="dateline">' . MTextGet("showdefault") . '<span class="boxright"><input type="checkbox" name="showdefault" id="showdefault" value="2" '.($content->getFlag() & 2 ? " checked" : "").'/></span></p>';
	}

	if( $content->getTemplateID() == 29 || $content->getTemplateID() == 30  || $content->getTemplateID() == 34 || $content->getTemplateID() == 36 ){
		print	'<p class="dateline">' . MTextGet("subtype") . 
				'<span class="boxright"><select name="subtypeid" id="subtypeid">';
		print	'<option value="0">'. MTextGet("selectSubtype").'</option>';
		print	'<option value="imggallery" '.($content->getValue()=="imggallery"?"SELECTED":"").'>'. MTextGet("hasImgGallery") . '</option>';
		print	'<option value="topimage" '.($content->getValue()=="topimage"?"SELECTED":"").'>'. MTextGet("hasTopImage") . '</option>';
		print	'</select></span></p>';

	}
	// On the standard and the nominatetemplate

	if($content->getTemplateID() == 27 || $content->getTemplateID() == 19 || $content->getTemplateID() == 24 || $content->getTemplateID() == 15 || $content->getTemplateID() == 35){

		print	'<p class="dateline">'  . MTextGet("chooseheader") . 
				'<span class="boxright">' .
				'<select name="headerclass" id="headerclass">' .
				'<option value="0">'.MTextGet("choose").'</option>' .
				'<option value="orangegradient" ' . ($content->getExternalID() == "orangegradient"?"SELECTED":"" ).'>orangegradient</option>' .
				'<option value="lightblue"  '. ($content->getExternalID() == "lightblue"?"SELECTED":"" ).'>lightblue</option>' .
				'<option value="clearred"  '. ($content->getExternalID() == "clearred"?"SELECTED":"" ).'>clearred</option>' .
				'<option value="brightorange"  '. ($content->getExternalID() == "brightorange"?"SELECTED":"") .'>brightorange</option>' .
				'<option value="deepgreen" '. ($content->getExternalID() == "deepgreen"?"SELECTED":"") .'>deepgreen</option>' .
				'<option value="cerisetop" '. ($content->getExternalID() == "cerisetop"?"SELECTED":"") .'>cerise</option>' .
				'<option value="royal" '. ($content->getExternalID() == "royal"?"SELECTED":"") .'>royal</option>' .
				'<option value="aqua" '. ($content->getExternalID() == "aqua"?"SELECTED":"") .'>aqua</option>' .
				'</select>' .
				'</span>' .
				'</p>';
	}

	if($content->getTemplateID() == 29 || $content->getTemplateID() == 30 || $content->getTemplateID() == 36){

		print	'<p class="dateline">'  . MTextGet("choosecolor") . 
				'<span class="boxright">' .
				'<select name="headerclass" id="headerclass">' .
				'<option value="0">'.MTextGet("choose").'</option>' .
				'<option value="orange" ' . ($content->getExternalID() == "orange"?"SELECTED":"" ).'>orange</option>' .
				'<option value="cerise"  '. ($content->getExternalID() == "cerise"?"SELECTED":"" ).'>cerise</option>' .
				'<option value="green"  '. ($content->getExternalID() == "green"?"SELECTED":"" ).'>green</option>' .
				
				'</select>' .
				'</span>' .
				'</p>';

	}
	


	// For template 23 ToDo we use the value flag for issuestatus 
	if($content->getTemplateID() == 23 ){
		print	'<p class="dateline">' . MTextGet("statusflag") . 
				'<span class="boxright"><select name="progresstatus" id="progresstatus">';
		print	'<option value="0" '.($content->getValue()=="0"?"SELECTED":"").'>'. MTextGet("statusnotstarted").'</option>';
		print	'<option value="1" '.($content->getValue()=="1"?"SELECTED":"").'>'. MTextGet("statusinprogress") . '</option>';
		print	'<option value="2" '.($content->getValue()=="2"?"SELECTED":"").'>'. MTextGet("statusfinished") . '</option>';
		print	'</select></span></p>';
	}
	
	//print	'<p class="dateline">' . MTextGet("viewed") . ': <span class="boxright">' . $content->getFlagII() . '</span></p>';

			if($nid == $content->getDefaultNodeID()){
				print	'<p class="boxline">' . MTextGet("defaultNode") . ':<br/>';
				print	'<select id="defaultnode" name="defaultnode"  style="width:215px">';
					PrintRootNodesOptions($content->getID(), $content->getDefaultNodeID() , 1);
				print	'</select>';
				print	'</p>';
			}else{
				print	'<p class="dateline">' . MTextGet("defaultNode") .':';
				print	'<input type="hidden" name="defaultnode" id="defaultnode" value="'.$content->getDefaultNodeID().'"/>'; 
				print	'<span class="boxright">' . NodeGetName($content->getDefaultNodeID()) . '</span>';
				print	'</p>';
			}
	
	
	print	'<p class="dateline">' . MTextGet("createdDate") . ': <span class="boxright">' .
			'<input type="text" class="dateinput" name="createddate" id="createddate" value="'.date('Y-m-d', strtotime($content->getCreatedDate())).'"/>' . 
			'<input type="text" class="timeinput" name="createdtime" id="createdtime" value="'.date('H:i:s', strtotime($content->getCreatedDate())).'"/>' . 
			'</span></p>';
	
	print	'<p class="dateline">' . MTextGet("archiveDate") . ': <span class="boxright">' .
			'<input type="text" class="dateinput" name="archivedate" id="archivedate" value="'.date('Y-m-d', strtotime($content->getArchiveDate())).'"/>' .
			'<input type="text" class="timeinput" name="archivetime" id="archivetime" value="'.date('H:i:s', strtotime($content->getArchiveDate())).'"/>' .
			'</span></p>';

	print	'<p class="dateline">' . MTextGet("startDate") . ':  <span class="boxright">' . 
			'<input type="text" class="dateinput" name="startdate" id="startdate" value="'.date('Y-m-d', strtotime($content->getStartDate())).'"/>' .
			'<input type="text" class="timeinput" name="starttime" id="starttime" value="'.date('H:i:s', strtotime($content->getStartDate())).'"/>' .
			'</span></p>';
	
	print	'<p class="dateline">' . MTextGet("endDate") . ':  <span class="boxright">' .
			'<input type="text" class="dateinput" name="enddate" id="enddate" value="'.date('Y-m-d', strtotime($content->getEndDate())).'"/>' .
			'<input type="text" class="timeinput" name="endtime" id="endtime" value="'.date('H:i:s', strtotime($content->getEndDate())).'"/>' .
			'</span></p>';

	print	'<p class="dateline">'. MTextGet("positionInNode") . ':<span class="boxright">';
	
	$position_in_node = ContentHasNode($cid, $nid);
	
	//print $cid .' ' .$nid. ' ' . $position_in_node;
	
	print	'<select name="position">';
			for($i=0; $i<30; $i++)
			print	'<option value="'. $i .'"' . ( $position_in_node == $i ? " selected" : " ") . '>'. $i .'</option>';
	print	'</select>';
	
	print	'</span></p>';

	print	'<p class="dateline">' . MTextGet("commentable") . '<span class="boxright"><input type="checkbox" name="flag" id="flag" value="1" '.($content->getFlag() & 1 ? " checked" : "").'/></span></p>';
	print	'<p class="dateline">' . MTextGet("showonfirst") . '<span class="boxright"><input type="checkbox" name="status" id="status" value="1" '.($content->getStatus() & 1 ? " checked" : "").'/></span></p>';

	print	'<p class="dateline">'. MTextGet("positionOnFirst") . ':<span class="boxright">';
	print	'<select name="positionfirst">';
			for($i=0; $i<30; $i++)
			print	'<option value="'. $i .'"' . ( $content->getPosition() == $i ? " selected" : " ") . '>'. $i .'</option>';
	print	'</select>';
	print	'</span></p>';
	
	print	'<p class="dateline">' . MTextGet("showonmap") . '<span class="boxright"><input type="checkbox" name="flagII" id="flagII" value="1" '.($content->getFlagII() & 1 ? " checked" : "").'/></span></p>';
	
	
	if($content->getTemplateID() == 27 || $content->getTemplateID() == 37 || $content->getTemplateID() == 38 || $content->getTemplateID() == 39 || $content->getTemplateID() == 40 || $content->getTemplateID() == 41 || $content->getTemplateID() == 43 || $content->getTemplateID() == 44){

		print	'<p class="dateline">'  . MTextGet("typeofmarker") . 
				'<span class="boxright">' .
				'<select name="typeofmarker" id="typeofmarker">' .
				'<option value="0">'.MTextGet("choose").'</option>' .
				'<option value="nominees" ' . ($content->getExternalID() == "nominees"?"SELECTED":"" ).'>Nominees</option>' .
				'<option value="childrightheroes"  '. ($content->getExternalID() == "childrightheroes"?"SELECTED":"" ).'>Child Right Heroes</option>' .
				'<option value="jury"  '. ($content->getExternalID() == "jury"?"SELECTED":"" ).'>Jurymembers</option>' .
				'<option value="globalfriend"  '. ($content->getExternalID() == "globalfriend"?"SELECTED":"" ).'>Global Friend</option>' .
				'<option value="crambassador"  '. ($content->getExternalID() == "crambassador"?"SELECTED":"" ).'>CR Ambassador</option>' .
				'</select>' .
				'</span>' .
				'</p>';
	}
	
	print 	'<p class="dateline">'. MTextGet("lat") .'<br/><input type="text" class="" name="latitude" id="latitude" value="'.$content->getLat().'" size="35"/></p>';
	print 	'<p class="dateline">'. MTextGet("long") .'<br/><input type="text" class="" name="longitude" id="longitude" value="'.$content->getLng().'" size="35"/></p>';
	print 	'<p class="dateline"><input type="button" class="greybtn" id="displaymap" value="Display map"/></p>';
	print 	'<p class="dateline"></p>';
	
	print	'<p class="boxline">' . MTextGet("attachedToNode") . ':<br/>';
	print	'<select id="nodebox" name="nodes[]" multiple size="12" style="width:215px">';
			PrintRootNodesOptions($content->getID(), $content->getDefaultNodeID(),  2);
	print	'</select></p>';
	print	'<p class="dateline">' . MTextGet("latestSaveBy") . ':<span class="boxright">' . UserGetName($content->getAuthorID()) . '</span></p>';

	print	'<p class="dateline"><span class="btnright"><input type="submit" class="greybtn" value="'. MTextGet("saveContentMeta") . '"></span></p>';
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
		$content->setArchiveDate(date('Y-m-d H:i:s'));
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
		$content->setArchiveDate(date('Y-m-d H:i:s'));
		$content->setAuthorID($admin->getID());
		ContentSave($content);
	}

	editContent($cid,$nid);
}


function saveMetaInformation($cid, $nid){
	global $admin;
	if(isset($_REQUEST["defaultnode"]))$defaultnode = $_REQUEST["defaultnode"];
	if(isset($_REQUEST["templateid"]))$templateid = $_REQUEST["templateid"];
	if(isset($_REQUEST["subtypeid"]))$subtypeid = $_REQUEST["subtypeid"];
	if(isset($_REQUEST["progresstatus"]))$progresstatus = $_REQUEST["progresstatus"];
	if(isset($_REQUEST["createddate"]))$createddate = $_REQUEST["createddate"];
	if(isset($_REQUEST["archivedate"]))$archivedate = $_REQUEST["archivedate"];
	if(isset($_REQUEST["startdate"]))$startdate = $_REQUEST["startdate"];
	if(isset($_REQUEST["enddate"]))$enddate = $_REQUEST["enddate"];
	if(isset($_REQUEST["createdtime"]))$createdtime = $_REQUEST["createdtime"];
	if(isset($_REQUEST["archivetime"]))$archivetime = $_REQUEST["archivetime"];
	if(isset($_REQUEST["starttime"]))$starttime = $_REQUEST["starttime"];
	if(isset($_REQUEST["endtime"]))$endtime = $_REQUEST["endtime"];
	if(isset($_REQUEST["flag"]))$flag = $_REQUEST["flag"]; else $flag = 0;
	if(isset($_REQUEST["flagII"]))$flagII = $_REQUEST["flagII"]; else $flagII = 0;
	if(isset($_REQUEST["showdefault"]))$showdefault= $_REQUEST["showdefault"]; else $showdefault = 0;
	if(isset($_REQUEST["position"]))$position = $_REQUEST["position"]; else $position = 0;
	if(isset($_REQUEST["status"]))$status = $_REQUEST["status"]; else $status = 0;
	if(isset($_REQUEST["positionfirst"]))$positionfirst = $_REQUEST["positionfirst"]; else $positionfirst = 0;
	
	if(isset($_REQUEST["latitude"]))$latitude = $_REQUEST["latitude"];
	if(isset($_REQUEST["longitude"]))$longitude = $_REQUEST["longitude"];

	//if(isset($_REQUEST["headerclass"]))$header = $_REQUEST["headerclass"]; else $header = 0;
	
	if(isset($_REQUEST["typeofmarker"]))$typeofmarker = $_REQUEST["typeofmarker"]; else $typeofmarker = 0;
	
	$flagvalue = 0;


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
		ContentAddNodeAndPosition($cid, $content->getDefaultNode(), $position);

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

		$content->setExternalID($typeofmarker);

		//$content->setArchiveDate($archive);
		$content->setArchiveDate(date('Y-m-d H:i:s'));
		$content->setStartDate($start);
		$content->setEndDate($end);
		
		$content->setPosition($positionfirst);
		$content->setStatus($status);

		/*
			BITWISE ONFLAG
			1 Commentable
			2 Show on default
			4 ...
			8 ...

		*/

		if($flag == 1)
			$flagvalue += 1;
		if($showdefault == 2)
			$flagvalue += 2;

		$content->setFlag($flagvalue);
		
		// FlagII first bit used for Show on Map
		$content->setFlagII($flagII);
		$content->setLat($latitude);
		$content->setLng($longitude);
		
		if(	$content->getTemplateID() == 10 || 
			$content->getTemplateID() == 11 || 
			$content->getTemplateID() == 12 || 
			$content->getTemplateID() == 27 || 
			$content->getTemplateID() == 29 || 
			$content->getTemplateID() == 30 || 
			$content->getTemplateID() == 19 || 
			$content->getTemplateID() == 24 || 
			$content->getTemplateID() == 15 || 
			$content->getTemplateID() == 34 || 
			$content->getTemplateID() == 35 || 
			$content->getTemplateID() == 36	||
			$content->getTemplateID() == 37 ||
			$content->getTemplateID() == 38 ||
			$content->getTemplateID() == 49 ||
			$content->getTemplateID() == 51
			
			)
		$content->setValue($subtypeid);
		else if($content->getTemplateID() == 23)
		$content->setValue($progresstatus);
		
		$content->setAuthorID($admin->getID());
		ContentSave($content);
		
		editContent($cid,$nid);
	}
	else print "No Content with ID =" . $cid;
	
}

function selectTemplate($nid){
	
	print	'<div class="head"><form onsubmit="return checkSelection()">'. MTextGet("selectTemplate")  . 
			'<div id="selectedtemplate" class="templateselect">' . MTextGet("Selected") . '</div>' .
			'<input type="hidden" name="action" value="createContent"/>' . 
			'<input type="hidden" id="tmplid" name="tmplid" value="0"/>' . 
			'<input type="hidden" id="nid" name="nid" value="' . $nid .'"/>' .
			'<input type="submit" class="selectbtn" value="Create content"/>' .
			'</form>' .
			'</div>';

	print	'<div class="stdbox">';
	
	$template = TemplateGetAll();
	
	while($template = TemplateGetNext($template)){
		print "<div class=\"boxes_white\" onclick=\"setSelectedTemplate(" . $template->getID() . ", '" . $template->getName(). "')\">";
		print $template->getName() . "<br/>";

		//if($image = ImageGetByHandleAndLanguage($template->getImageHandle(), -1))
		//ImageCreateTag($image, 1000, 1);

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
	$content->setFlagII(0);
	
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

function createContentText($cid, $nid){

		$mtext = MTextNewInCategory("contentTexts", "no text");
		MTextUpdateTextContentCopyAllLanguages($mtext);

		$currenttexts = ContentTextGetAllForContent($cid);

		$contenttext = new ContentText;
		$contenttext->setContentID($cid);
		$contenttext->setTextID($mtext->getID());
		$contenttext->setPosition( mysqli_num_rows($currenttexts->getDBRows()) + 1 );
		ContentTextSave($contenttext);

		editContent($cid, $nid);

}

function deleteContentText($cid, $pos, $nid){
	ContentTextDelete($cid, $pos);
	editContent($cid, $nid);
}

function deleteContent($cid){
	
	ContentDelete($cid);
	defaultAction();
}


function htmlStart(){

   // print	'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' .
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
			'<script type="text/javascript" src="/trms-admin/js/structure_admin.js"></script>' .
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
	
    print	'<div id="overlay"></div>'.
    		'<div id="mapcontainer">'.
    		'<div id="closeoverlay">x</div>'.
    		'<div id="mapcanvas"></div>'.
			'</div>' ;
    		
    print	'<script>'.
    		
    		'</script>';
		//	'<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0W0rJN2DORr00dPwBaaueJ9lxxCF2_Ew" 
		//		async defer></script>';
	print	'<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANMI_RvV_nHDdhvGUrA3fH28etn2N81jk" 
				async defer></script>';
				
				
	print 	'</div>'.
			'</body>' .
			'</html>';
}

?>
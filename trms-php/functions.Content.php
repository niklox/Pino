<?php

/*
mysql> desc PageContent;
+-------------------------+-------------+------+-----+---------+-------+
| Field                   | Type        | Null | Key | Default | Extra |
+-------------------------+-------------+------+-----+---------+-------+
| PageContentID           | int(11)     | YES  |     | NULL    |       |
| PageContentPageID       | int(11)     | YES  |     | NULL    |       |	Node default
| PageContentCreatedDate  | datetime    | YES  |     | NULL    |       |
| PageContentTitleTextID  | varchar(20) | YES  |     | NULL    |       |	Title
| PageContentBodyTextID   | varchar(20) | YES  |     | NULL    |       |	
| PageContentArchiveFlag  | int(11)     | YES  |     | NULL    |       |	flag
| PageContentAuthorID     | int(11)     | YES  |     | NULL    |       |
| PageContentArchiveDate  | datetime    | YES  |     | NULL    |       |
| PageContentPosition     | int(11)     | YES  |     | NULL    |       |	
| PageContentIntroTextID  | varchar(20) | YES  |     | NULL    |       |	Tags
| PageContentTypeID       | int(11)     | YES  |     | NULL    |       |	TemplateID
| PageContentExtraTextID  | varchar(20) | YES  |     | NULL    |       |	Permalink
| PageContentFlag         | int(11)     | YES  |     | NULL    |       |	Counter
| PageContentFlagII       | int(11)     | YES  |     | NULL    |       |	flag
| PageContentExternalID   | varchar(50) | YES  |     | NULL    |       |	
| PageContentValue        | varchar(20) | YES  |     | NULL    |       |
| PageContentNumericValue | varchar(20) | YES  |     | NULL    |       |	pris
| PageContentStatus       | int(11)     | YES  |     | NULL    |       |
| PageContentStartDate    | datetime    | YES  |     | NULL    |       |
| PageContentEndDate      | datetime    | YES  |     | NULL    |       |
+-------------------------+-------------+------+-----+---------+-------+
20 rows in set (0.01 sec)

Associera artiklar (Subjectclass ?)
Kommentera artiklar (Discussions ?)
*/

define("CONTENT_SELECT", "SELECT PageContent.PageContentID, PageContent.PageContentPageID, PageContent.PageContentCreatedDate, PageContent.PageContentTitleTextID, PageContent.PageContentBodyTextID, PageContent.PageContentArchiveFlag, PageContent.PageContentAuthorID, PageContent.PageContentArchiveDate, PageContent.PageContentPosition, PageContent.PageContentIntroTextID, PageContent.PageContentTypeID, PageContent.PageContentExtraTextID, PageContent.PageContentFlag, PageContent.PageContentFlagII, PageContent.PageContentExternalID, PageContent.PageContentValue, PageContent.PageContentNumericValue, PageContent.PageContentStatus, PageContent.PageContentStartDate, PageContent.PageContentEndDate FROM PageContent ");

define("CONTENT_INSERT", "INSERT INTO PageContent (PageContentID, PageContentPageID, PageContentCreatedDate, PageContentTitleTextID, PageContentBodyTextID, PageContentArchiveFlag, PageContentAuthorID, PageContentArchiveDate, PageContentPosition, PageContentIntroTextID, PageContentTypeID, PageContentExtraTextID, PageContentFlag, PageContentFlagII, PageContentExternalID, PageContentValue, PageContentNumericValue, PageContentStatus, PageContentStartDate, PageContentEndDate)");

function ContentSetAllFromRow($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['PageContentID']);
		$instance->setDefaultNodeID($row['PageContentPageID']);
		$instance->setCreatedDate($row['PageContentCreatedDate']);
		$instance->setTitleTextID($row['PageContentTitleTextID']);
		$instance->setContentTextID($row['PageContentBodyTextID']);
		$instance->setArchiveFlag($row['PageContentArchiveFlag']);
		$instance->setAuthorID($row['PageContentAuthorID']);
		$instance->setArchiveDate($row['PageContentArchiveDate']);
		$instance->setPosition($row['PageContentPosition']);
		$instance->setTagsTextID($row['PageContentIntroTextID']);
		$instance->setTemplateID($row['PageContentTypeID']);
		$instance->setPermalinkTextID($row['PageContentExtraTextID']);
		$instance->setFlag($row['PageContentFlag']);
		$instance->setCounter($row['PageContentFlagII']);
		$instance->setExternalID($row['PageContentExternalID']);
		$instance->setValue($row['PageContentValue']);
		$instance->setNumericValue($row['PageContentNumericValue']);
		$instance->setStatus($row['PageContentStatus']);
		$instance->setStartDate($row['PageContentStartDate']);
		$instance->setContentTextID($row['PageContentBodyTextID']);
		$instance->setEndDate($row['PageContentEndDate']);
		return $instance;
	}
}

function ContentGetNext($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['PageContentID']);
		$instance->setDefaultNodeID($row['PageContentPageID']);
		$instance->setCreatedDate($row['PageContentCreatedDate']);
		$instance->setTitleTextID($row['PageContentTitleTextID']);
		$instance->setContentTextID($row['PageContentBodyTextID']);
		$instance->setArchiveFlag($row['PageContentArchiveFlag']);
		$instance->setAuthorID($row['PageContentAuthorID']);
		$instance->setArchiveDate($row['PageContentArchiveDate']);
		$instance->setPosition($row['PageContentPosition']);
		$instance->setTagsTextID($row['PageContentIntroTextID']);
		$instance->setTemplateID($row['PageContentTypeID']);
		$instance->setPermalinkTextID($row['PageContentExtraTextID']);
		$instance->setFlag($row['PageContentFlag']);
		$instance->setCounter($row['PageContentFlagII']);
		$instance->setExternalID($row['PageContentExternalID']);
		$instance->setValue($row['PageContentValue']);
		$instance->setNumericValue($row['PageContentNumericValue']);
		$instance->setStatus($row['PageContentStatus']);
		$instance->setStartDate($row['PageContentStartDate']);
		$instance->setContentTextID($row['PageContentBodyTextID']);
		$instance->setEndDate($row['PageContentEndDate']);
		return $instance;
	}
}

function ContentGetByID($cid){
	global $dbcnx;
	
	$instance = new Content;
	$sqlstr = CONTENT_SELECT . " WHERE PageContentID = " . $cid;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function ContentGetByID();<br/> ");
	}
	$instance = ContentSetAllFromRow($instance);
	return $instance;
}

function ContentGetIDByPermalink($permalink){
	global $dbcnx;

	$sqlstr = "SELECT PageContentID FROM PageContent, Texts WHERE TextContent = '" . $permalink . "' AND PageContentExtraTextID = TextID";
	$result = @mysqli_query($dbcnx, $sqlstr);
	if(mysqli_num_rows($result) == 0)
		return 0;
	else{
		// return mysqli_result($row,0,0); replaced 180115 ///
		$row = mysqli_fetch_assoc($result);
		return $row['PageContentID'];
	}
		
}

function ContentGetFirstInNode($nid){
	global $dbcnx;

	$sqlstr = CONTENT_SELECT . ", PageContentPages WHERE PageContentPages.PageID=".$nid." AND PageContentPages.PageContentID=PageContent.PageContentID ORDER BY PageContentPages.Position";

	$row = @mysqli_query($dbcnx, $sqlstr);
	if(mysqli_num_rows($row) == 0){
		
		if($childnodeid = NodeGetFirstChildID($nid))
			ContentGetFirstInNode($childnodeid);
		else
			return NULL;
	}
	else{
		
		$instance->setDBrows($row);

		if(!$instance->getDBrows()){
			exit(" Error in function ContentGetFirstInNode();<br/> " . mysqli_error($dbcnx) );
		}
		$instance = ContentSetAllFromRow($instance);
		return $instance;
	}
}

function ContentGetFirstContentIDInNode($nid){
	global $dbcnx;

	$sqlstr = "SELECT DISTINCT PageContent.PageContentID FROM PageContent, PageContentPages WHERE PageContentPages.PageID=".$nid." AND PageContentPages.PageContentID=PageContent.PageContentID ORDER BY PageContentPages.Position";

	$result = @mysqli_query($dbcnx, $sqlstr);

	if(mysqli_num_rows($result) == 0){
		
		$childnodeid = NodeGetFirstChildID($nid);
		if($childnodeid > 0)
			return ContentGetFirstContentIDInNode($childnodeid);
		else
			return 0;
	}
	else{
		//return mysql_result($row,0,0);
		$row = mysqli_fetch_assoc($result);
		return $row['PageContentID'];
	}
}


function ContentGetAllInNode($nodeid){
	global $dbcnx;

	$instance = new Content;

	$sql = CONTENT_SELECT . ", PageContentPages WHERE PageContentPages.PageID=" . $nodeid . " AND PageContentPages.PageContentID=PageContent.PageContentID AND PageContent.PageContentArchiveFlag=0 ORDER BY PageContentPages.Position, PageContent.PageContentCreatedDate DESC";
	
	$row = @mysqli_query($dbcnx, $sql);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function ContentGetAllInNode();<br/> " . mysqli_error($dbcnx) . "<br/>" . $sql);
	}
	return $instance;

}

function ContentGetEverythingInNode($nodeid){

	$sql = CONTENT_SELECT . ", PageContentPages WHERE PageContentPages.PageID=" . $nodeid . " AND PageContentPages.PageContentID=PageContent.PageContentID ORDER BY PageContentPages.Position";

}

function ContentGetAllInNodeTitleOrdered($nodeid){

	$sql = CONTENT_SELECT . ", PageContentPages, Texts WHERE PageContentPages.PageID=" . $nodeid . " AND PageContentPages.PageContentID=PageContent.PageContentID AND PageContent.PageContentArchiveFlag=0 AND Texts.TextID=PageContent.PageContentTitleTextID ORDER BY Texts.TextContent, PageContentPages.Position";
		
}

function ContentSave($instance){
	
	global $dbcnx;

	if($instance->getID() > 0){
		
		$sql = "UPDATE PageContent SET PageContentID=" . $instance->getID() .", PageContentPageID = ". $instance->getDefaultNodeID() . ", PageContentCreatedDate = '" . $instance->getCreatedDate() . "', PageContentTitleTextID='" . $instance->getTitleTextID() . "', PageContentBodyTextID='" . $instance->getContentTextID() . "', PageContentArchiveFlag=" . $instance->getArchiveFlag() . ", PageContentAuthorID=" . $instance->getAuthorID() . ", PageContentArchiveDate='" . $instance->getArchiveDate() . "', PageContentPosition=". $instance->getPosition() . ", PageContentIntroTextID='" . $instance->getTagsTextID() . "', PageContentTypeID=" . $instance->getTemplateID() . ", PageContentExtraTextID='" . $instance->getPermalinkTextID() ."', PageContentFlag=" . $instance->getFlag() . ", PageContentFlagII=" . $instance->getCounter() . ", PageContentExternalID=\"" . $instance->getExternalID() . "\", PageContentValue=\"" . $instance->getValue() . "\", PageContentNumericValue=\"" . $instance->getNumericValue() . "\", PageContentStatus=" . $instance->getStatus() .", PageContentStartDate='" . $instance->getStartDate() . "', PageContentEndDate='" . $instance->getEndDate() . "' WHERE PageContentID = ". $instance->getID() ."";
		
	}else{

		$value = TermosGetCounterValue("PageContentID");
		TermosSetCounterValue("PageContentID", ++$value);
		$instance->setID($value);

		$sql = CONTENT_INSERT .
			" VALUES(".$instance->getID().",".$instance->getDefaultNodeID().",'".$instance->getCreatedDate()."','".$instance->getTitleTextID()."','".$instance->getContentTextID()."',".$instance->getArchiveFlag().",".$instance->getAuthorID().",'".$instance->getArchiveDate()."',".$instance->getPosition().",'".$instance->getTagsTextID()."',".$instance->getTemplateID().",'".$instance->getPermalinkTextID()."',".$instance->getFlag().",".$instance->getCounter().",'".$instance->getExternalID()."','".$instance->getValue()."','".$instance->getNumericValue()."',".$instance->getStatus().",'".$instance->getStartDate()."','".$instance->getEndDate()."')";
	  }
	  //print $sql;
	mysqli_query($dbcnx, $sql);

	return $instance->getID();

}

function ContentUpdateNode($cid, $nid)
{
	global $dbcnx;

	$sql = "DELETE FROM PageContentPages WHERE PageContentID=".$cid." AND PageID=".$nid;
	mysqli_query($dbcnx, $sql);

	$sql = "INSERT INTO PageContentPages (PageContentID, PageID, Position) VALUES (".$cid.", ".$nid.", 0)";
	mysqli_query($dbcnx, $sql);
	
}

function ContentUpdateNodeAndPosition($cid, $nid, $pos)
{
	global $dbcnx;

	$sql = "DELETE FROM PageContentPages WHERE PageContentID=".$cid." AND PageID=".$nid;
	mysqli_query($dbcnx, $sql);
			 
	$sql = "INSERT INTO PageContentPages (PageContentID, PageID, Position) VALUES (".$cid.", ".$nid.", ".$pos.")";
	mysqli_query($dbcnx, $sql);
}

function ContentDelete($cid){
		global $dbcnx;

		$content = ContentGetByID($cid);

		$sql = "DELETE FROM PageContentDocuments WHERE PageContentID=" . $cid;
		mysqli_query($dbcnx, $sql);
		$sql = "DELETE FROM PageContentForms WHERE PageContentID=" . $cid;
		mysqli_query($dbcnx, $sql);
		$sql = "DELETE FROM PageContentQuestions WHERE PageContentID=" . $cid;
		mysqli_query($dbcnx, $sql);
		$sql = "DELETE FROM PageContentLinks WHERE PageContentID=" . $cid;
		mysqli_query($dbcnx, $sql);

		MTextDelete($content->getTitleTextID());
		MTextDelete($content->getContentTextID());
		MTextDelete($content->getTagsTextID());
		MTextDelete($content->getPermalinkTextID());

		$contenttext = ContentTextGetAllForContent($content->getID());

		while($contenttext = ContentTextGetNext($contenttext)){
			MTextDelete($contenttext->getTextID());
		}

		$sql = "DELETE FROM PageContentTexts WHERE PageContentTextPageContentID=" . $cid;
		mysqli_query($dbcnx, $sql);
		$sql = "DELETE FROM PageContentLists WHERE PageContentListPageContentID=" . $cid;
		mysqli_query($dbcnx, $sql);
		$sql = "DELETE FROM PageContentImageLinks WHERE PageContentID=" . $cid;
		mysqli_query($dbcnx, $sql);
		$sql = "DELETE FROM PageContentPages WHERE PageContentID=" . $cid;
		mysqli_query($dbcnx, $sql);
		$sql = "DELETE FROM PageContentCountries WHERE PageContentID=" . $cid;
		mysqli_query($dbcnx, $sql);
		$sql = "DELETE FROM PageContentImages WHERE PageContentID=" . $cid;
		mysqli_query($dbcnx, $sql);
		$sql = "DELETE FROM PageContent WHERE PageContentID=" . $cid;
		mysqli_query($dbcnx, $sql);
}

function ContentHasNode($cid, $nid){
	global $dbcnx;

	$sql = "SELECT PageContentID, PageID, Position FROM PageContentPages WHERE PageContentID=".$cid." AND PageID=".$nid;

	$result = @mysqli_query($dbcnx, $sql);
	if(mysqli_num_rows($result) == 0)
		return -1;
	else{
		// return mysql_result($row,0,2); removed 20180115 ////
		$row = mysqli_fetch_assoc($result);
		return $row['Position'];
	}
}

function ContentGetAllWhereImageAppears($imghandle){

	global $dbcnx;

	$instance = new Content;

	$sql = CONTENT_SELECT . ", PageContentImages WHERE PageContentImages.ImageHandle='" . $imghandle ."' AND PageContent.PageContentID=PageContentImages.PageContentID";
	
	$row = @mysqli_query($dbcnx, $sql);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function ContentGetAllWhereImageAppears;<br/> " . mysqli_error($dbcnx) . "<br/>" . $sql);
	}
	return $instance;
}

function PrintNodeTreeOptions($rid, $cid, $level, $default_nid, $mode){

	$level++;
	if($level < MAXLEVELS){

		$node = NodeGetAllChildren($rid);
		
		while($node = NodeGetNext($node)){

			if($mode == 0)
				print "<option value=\"" . $node->getID() . "\" ". ( ContentHasNode($cid, $node->getID()) > -1 ? "selected" : "" ) .">";
			else if($mode == 1)
				print "<option value=\"" . $node->getID() . "\" ". ( $default_nid == $node->getID() ? "selected" : "" ) .">";
			else if($mode == 2){
				$pos = ContentHasNode($cid, $node->getID());
				if($pos > -1)
					print "<option value=\"".$node->getID().",".$pos."\" selected>";
				else
					print "<option value=\"".$node->getID()."\">";
			}

			for($i=0; $i<$level; $i++)print "- ";
			print $node->getName() . ($node->getID() == $default_nid ? " - default" : "") . "</option>\n"; 
			
			if(NodeHasChildren($node->getID()))
				 PrintNodeTreeOptions($node->getID(), $cid, $level, $default_nid, $mode);
			}
	  }else return;
}

function PrintRootNodesOptions($cid, $default_nid, $mode){

	$roots = NodeGetAllRoots();
	while($roots = NodeGetNext($roots)){

		if($mode == 0)
			print "<option value=\"".$roots->getID()."\" ". ( ContentHasNode($cid, $roots->getID()) > -1 ? "selected" : "" ) ."\">" .  $roots->getName() . "</option>\n";
		else if($mode == 1)
			print "<option value=\"".$roots->getID()."\" ". ( $default_nid == $roots->getID() ? "selected" : "" ) ."\">" .  $roots->getName() . "</option>\n";
		else if($mode == 2){
			$pos = ContentHasNode($cid, $roots->getID());
			if($pos > -1)
				print "<option value=\"".$roots->getID().",".$pos."\" selected>".$roots->getName()."</option>\n";
			else
				print "<option value=\"".$roots->getID()."\">".$roots->getName()."</option>\n";
		}

		PrintNodeTreeOptions($roots->getID(), $cid, 0, $default_nid, $mode);
	}
}

function PrintNodeTreeWithContent($nid, $level){ 

	$level++;
	if($level < MAXLEVELS){

		$node = NodeGetAllChildren($nid);
		while($node = NodeGetNext($node)){
			
			print	"<li class=\"node_" . $level . "\">" . $node->getName() . "</li>\n";
			
			$content = ContentGetAllInNode( $node->getID() ); 
			while($content = ContentGetNext($content)){
				print	"<li class=\"cont_" . $level . "\"><a href=\"" . CONTENT_ADMIN .  "?action=editContent&cid=". $content->getID() . "&nid=" . $node->getID() . "\"><img class=\"iconimg\" src=\"images/icon_content.gif\"/></a>" . strip_tags($content->getTitle()) . "</li>\n";
			}
			
			if(NodeHasChildren($node->getID())){
				PrintNodeTreeWithContent($node->getID(), $level);
			}
		}

	}else return;
}

function ContentDetachNodes($cid){
	global $dbcnx;
	$sqlstr = "DELETE FROM PageContentPages WHERE  PageContentID  = " . $cid;
	mysqli_query($dbcnx, $sqlstr);
}


function ContentAddNodeAndPosition($cid, $nid, $pos){
	global $dbcnx;
	$sqlstr = "INSERT INTO PageContentPages VALUES(".$cid.",".$nid.",".$pos.")";
	mysqli_query($dbcnx, $sqlstr);
}

function ContentUpdatePositionAtNode($cid, $nid, $pos){
	global $dbcnx;
	$sqlstr = "UPDATE PageContentPages SET Position = ".$pos." WHERE PageContentID = ".$cid." AND PageID =" .$nid;
	mysqli_query($dbcnx, $sqlstr);
}

function ContentDeleteNode($cid, $nid){
	global $dbcnx;
	$sqlstr = "DELETE FROM PageContentPages WHERE  PageContentID  = " . $cid . " AND PageID =" . $nid;
	mysqli_query($dbcnx, $sqlstr);
}

function ContentGetAllWithTemplate($tmplid){
	global $dbcnx;
	$instance = new Content;
	$sqlstr = CONTENT_SELECT . " WHERE PageContentTypeID=" . $tmplid;

	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function ContentGetAllWithTemplate();<br/> " . mysqli_error($dbcnx) );
	}
	return $instance;
}

function ContentCountAllWithTemplate($tmplid){
	global $dbcnx;

	$sqlstr = CONTENT_SELECT . " WHERE PageContentTypeID=" . $tmplid;
	$row = @mysqli_query($dbcnx, $sqlstr);

	return mysqli_num_rows($row);
}

function ContentGetLastUpdated($limit){
	global $dbcnx;
	$instance = new Content;
	$sql = CONTENT_SELECT . " WHERE PageContentPageID != 404 ORDER BY PageContentArchiveDate DESC LIMIT " . $limit;

	$row = @mysqli_query($dbcnx, $sql);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function ContentGetLastUpdated();<br/> " . mysqli_error($dbcnx) );
	}
	return $instance;

}
/*
mysql> desc PageContentLists;
+-----------+---------+------+-----+---------+-------+
| Field     | Type    | Null | Key | Default | Extra |
+-----------+---------+------+-----+---------+-------+
| ContentID | int(11) | YES  |     | 0       |       |
| NodeID    | int(11) | YES  |     | 0       |       |
| Position  | int(11) | YES  |     | 0       |       |
+-----------+---------+------+-----+---------+-------+
*/

function ContentGetList($cid, $pos){
	
	global $dbcnx;
	$sql = "SELECT ContentID, NodeID, Position FROM PageContentLists WHERE ContentID=" . $cid . " AND Position=" . $pos;
	
	$result = @mysqli_query($dbcnx, $sql);

	if(mysqli_num_rows($result) > 0){
		// return mysql_result($row,0,1); removed 180115 // 
		$row = mysqli_fetch_assoc($result);
		return $row['NodeID'];
	}
	else
		return 0;
	
}

function ContentDeleteList($cid){
	global $dbcnx;
	$sql = "DELETE FROM PageContentLists WHERE ContentID = " . $cid;
	
	mysqli_query($dbcnx, $sql);
}

function ContentSaveList($cid, $nid, $pos){
	global $dbcnx;
	
	ContentDeleteList($cid);
	$sql = "INSERT INTO PageContentLists VALUES(".$cid.",".$nid.",".$pos.")";
	mysqli_query($dbcnx, $sql);
}


/*
mysql> desc PageContentTexts;
+------------------------------+----------+------+-----+---------+-------+
| Field                        | Type     | Null | Key | Default | Extra |
+------------------------------+----------+------+-----+---------+-------+
| PageContentTextPageContentID | int(11)  | YES  |     | NULL    |       |
| PageContentTextTextID        | char(20) | YES  |     | NULL    |       |
| PageContentTextPosition      | int(11)  | YES  |     | NULL    |       |
+------------------------------+----------+------+-----+---------+-------+
3 rows in set (0.01 sec)
*/

define("CONTENTTEXT_SELECT","SELECT PageContentTexts.PageContentTextPageContentID, PageContentTexts.PageContentTextTextID, PageContentTexts.PageContentTextPosition FROM PageContentTexts");

function ContentTextSetAllFromRow($instance){

	if( $row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setContentID($row['PageContentTextPageContentID']);
		$instance->setTextID($row['PageContentTextTextID']);
		$instance->setPosition($row['PageContentTextPosition']);
		return $instance;
	}
}

function ContentTextGetNext($instance){
	
	if( $row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setContentID($row['PageContentTextPageContentID']);
		$instance->setTextID($row['PageContentTextTextID']);
		$instance->setPosition($row['PageContentTextPosition']);
		return $instance;
	}
}

function ContentTextGetAllForContent($cid){
	global $dbcnx;
	$instance = new ContentText;

	$sql = CONTENTTEXT_SELECT . " WHERE PageContentTextPageContentID = ". $cid . " ORDER BY PageContentTextPosition";
	$row = @mysqli_query($dbcnx, $sql);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function ContentTextGetAllForContent();<br/> " . mysqli_error($dbcnx) );
	}
	return $instance;
}

function ContentTextGet($cid, $pos){
	global $dbcnx;

	$instance = new ContentText;

	$sql = CONTENTTEXT_SELECT . " WHERE PageContentTextPageContentID=".$cid." AND PageContentTextPosition=" . $pos;
	
	$row = @mysqli_query($dbcnx, $sql);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function ContentTextGet();<br/> " . mysqli_error($dbcnx) );
	}
	$instance = ContentTextSetAllFromRow($instance);
	return $instance;
}


function ContentTextSave($contenttext){
	global $dbcnx;

	$sql = "DELETE FROM PageContentTexts WHERE PageContentTextPageContentID=". $contenttext->getContentID() . " AND PageContentTextPosition=" . $contenttext->getPosition(); 
	mysqli_query($dbcnx, $sql);
	$sql = "INSERT INTO PageContentTexts VALUES (". $contenttext->getContentID() .", '". $contenttext->getTextID() ."', ". $contenttext->getPosition() .")";
	mysqli_query($dbcnx, $sql);
}



//function PageContentTextSetAllFromRow(PageContentText* pageContentText)
//function PageContentText* PageContentTextNew()

/*
mysql> desc PageContentTypes;     
+----------------------------+--------------+------+-----+---------+-------+
| Field                      | Type         | Null | Key | Default | Extra |
+----------------------------+--------------+------+-----+---------+-------+
| PageContentTypeID          | int(11)      | YES  |     | NULL    |       |
| PageContentTypeNameTextID  | char(20)     | YES  |     | NULL    |       |
| PageContentTypeDescTextID  | char(20)     | YES  |     | NULL    |       |
| PageContentTypeTexts       | int(11)      | YES  |     | NULL    |       |
| PageContentTypeImages      | int(11)      | YES  |     | NULL    |       |
| PageContentTypeImageHandle | varchar(100) | YES  |     | NULL    |       |
| PageContentTypeStatus      | int(11)      | YES  |     | NULL    |       |
+----------------------------+--------------+------+-----+---------+-------+
7 rows in set (0.00 sec)

*/

define("TEMPLATE_SELECT", "SELECT PageContentTypes.PageContentTypeID, PageContentTypes.PageContentTypeNameTextID, PageContentTypes.PageContentTypeDescTextID, PageContentTypes.PageContentTypeTexts, PageContentTypes.PageContentTypeImages, PageContentTypes.PageContentTypeImageHandle, PageContentTypes.PageContentTypeStatus FROM PageContentTypes");

define("TEMPLATE_INSERT", "INSERT INTO PageContentTypes (PageContentTypeID, PageContentTypeNameTextID, PageContentTypeDescTextID, PageContentTypeTexts, PageContentTypeImages, PageContentTypeImageHandle, PageContentTypeStatus)");

function TemplateSetAllFromRow($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['PageContentTypeID']);
		$instance->setNameTextID($row['PageContentTypeNameTextID']);
		$instance->setDescriptionTextID($row['PageContentTypeDescTextID']);
		$instance->setTexts($row['PageContentTypeTexts']);
		$instance->setImages($row['PageContentTypeImages']);
		$instance->setImageHandle($row['PageContentTypeImageHandle']);
		$instance->setStatus($row['PageContentTypeStatus']);
		return $instance;
	}
}

function TemplateGetNext($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['PageContentTypeID']);
		$instance->setNameTextID($row['PageContentTypeNameTextID']);
		$instance->setDescriptionTextID($row['PageContentTypeDescTextID']);
		$instance->setTexts($row['PageContentTypeTexts']);
		$instance->setImages($row['PageContentTypeImages']);
		$instance->setImageHandle($row['PageContentTypeImageHandle']);
		$instance->setStatus($row['PageContentTypeStatus']);
		return $instance;
	}
}

function TemplateGetByID($tid){
	global $dbcnx;
	
	$instance = new Template;
	$sqlstr = TEMPLATE_SELECT . " WHERE PageContentTypeID = " . $tid;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function TemplateGetByID();<br/> " . mysqli_error($dbcnx) );
	}
	$instance = TemplateSetAllFromRow($instance);
	return $instance;
}

function TemplateGetAll(){
	global $dbcnx;
	
	$instance = new Template;
	$sqlstr = TEMPLATE_SELECT . ' ORDER BY PageContentTypeID';
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function TemplateGetAll();<br/> " . mysqli_error($dbcnx) );
	}
	return $instance;
}

function TemplateGetName($tmplid){
		
	if($template = TemplateGetByID($tmplid)){
		return MTextGet($template->getNameTextID());
	}else{
		return "noname";
	}
}

function TemplateSave($template){

	global $dbcnx;

	if($template->getID() > 0){
		
		$sql = "UPDATE PageContentTypes SET PageContentTypeNameTextID='".$template->getNameTextID()."', PageContentTypeDescTextID='".$template->getDescriptionTextID()."', PageContentTypeTexts=".$template->getTexts().", PageContentTypeImages=".$template->getImages().", PageContentTypeImageHandle='".$template->getImageHandle()."', PageContentTypeStatus=".$template->getStatus()." WHERE PageContentTypeID=". $template->getID();
		
		mysqli_query($dbcnx, $sql);

	}else{

		$value = TermosGetCounterValue("TemplateID");
		TermosSetCounterValue("TemplateID", ++$value);
		$template->setID($value);

		$sql = TEMPLATE_INSERT . " VALUES (".$template->getID().",'".$template->getNameTextID()."','".$template->getDescriptionTextID()."',".$template->getTexts(). ",".$template->getImages().",'".$template->getImageHandle()."',".$template->getStatus().")"; 
		mysqli_query($dbcnx, $sql);
	}
}

function TemplateDelete($templateid){
	global $dbcnx;
	
	$template = TemplateGetByID($templateid);
	MTextDelete($template->getNameTextID());
	MTextDelete($template->getDescriptionTextID());

	$sql = "DELETE FROM PageContentTypes WHERE PageContentTypeID=" .$templateid;
	mysqli_query($dbcnx, $sql);

}


/*
mysql> desc PageContentImageLink;
+---------------+--------------+------+-----+---------+-------+
| Field         | Type         | Null | Key | Default | Extra |
+---------------+--------------+------+-----+---------+-------+
| PageContentID | int(11)      | YES  |     | NULL    |       |
| ImagePosition | int(11)      | YES  |     | NULL    |       |
| Target        | int(11)      | YES  |     | NULL    |       |
| URL           | varchar(250) | YES  |     | NULL    |       |
| Language      | int(11)      | YES  |     | NULL    |       |
+---------------+--------------+------+-----+---------+-------+
5 rows in set (0.00 sec)
*/

define("IMAGELINK_SELECT", "SELECT PageContentImageLink.PageContentID, PageContentImageLink.ImagePosition,  PageContentImageLink.Target, PageContentImageLink.URL, PageContentImageLink.Title, PageContentImageLink.Language FROM PageContentImageLink ");

define("IMAGELINK_INSERT", "INSERT INTO PageContentImageLink (PageContentID, ImagePosition, Target, URL, Title, Language) ");

function ImageLinkSetAllFromRow($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setContentID($row['PageContentID']);
		$instance->setPosition($row['ImagePosition']);
		$instance->setTarget($row['Target']);
		$instance->setURL($row['URL']);
		$instance->setTitle($row['Title']);
		$instance->setLanguage($row['Language']);
		return $instance;
	}
}

function ImageLinkGet($cid, $pos){

	global $dbcnx;
	
	$instance = new ContentImageLink;
	$sqlstr = IMAGELINK_SELECT . " WHERE PageContentID = " . $cid . " AND ImagePosition = " . $pos;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(" Error in function ImageLinkGet();<br/> " . mysqli_error($dbcnx) );
	}
	$instance = ImageLinkSetAllFromRow($instance);
	return $instance;
}


function ImageLinkSave($instance){
	global $dbcnx;
	
	$sql = "DELETE FROM PageContentImageLink WHERE PageContentID = " . $instance->getContentID() . " AND ImagePosition = " . $instance->getPosition();
	mysqli_query($dbcnx, $sql);
	$sql = IMAGELINK_INSERT . " VALUES(". $instance->getContentID() .",". $instance->getPosition() .",". $instance->getTarget() .",'". $instance->getURL() ."','". $instance->getTitle() ."',". $instance->getLanguage() .")";
	//print $sql;
	
	mysqli_query($dbcnx, $sql);


}

function ImageLinkDelete($cid, $pos){

	global $dbcnx;
	$sql = "DELETE FROM PageContentImageLink WHERE PageContentID = " . $cid . " AND ImagePosition = " . $pos;
	
	mysqli_query($dbcnx, $sql);

}


?>
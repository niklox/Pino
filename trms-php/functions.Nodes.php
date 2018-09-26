<?php
/*
Nodes used to be Pages and we still have the DBtable like this
mysql> desc Page;
+-------------------------+----------+------+-----+---------------------+-------+
| Field                   | Type     | Null | Key | Default             | Extra |
+-------------------------+----------+------+-----+---------------------+-------+
| PageID                  | int(11)  | YES  |     | NULL                |       |
| PageNameTextID          | char(20) | YES  |     | NULL                |       |
| PagePosition            | int(11)  | YES  |     | NULL                |       |
| PageShowDefaultLanguage | int(11)  | YES  |     | NULL                |       |
| PageTypeID              | int(11)  | YES  |     | 0                   |       |
| PageCreatedDate         | datetime | YES  |     | 1970-01-01 00:00:00 |       |
| PageArchiveDate         | datetime | YES  |     | 2020-01-01 00:00:00 |       |
| PagePermalinkTextID     | char(20) | YES  |     | NULL                |       |
+-------------------------+----------+------+-----+---------------------+-------+
*/

$node_select = "SELECT DISTINCT Page.PageID, Page.PageNameTextID, Page.PagePosition, Page.PageShowDefaultLanguage, Page.PageTypeID, Page.PageCreatedDate, Page.PageArchiveDate, Page.PagePermalinkTextID FROM Page";

$node_insert = "INSERT INTO Page (PageID, PageNameTextID, PagePosition, PageShowDefaultLanguage, PageTypeID, PageCreatedDate, PageArchiveDate, PagePermalinkTextID) ";

// Map the data into object
function NodeSetAllFromRow($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['PageID']);
		$instance->setNodeNameTextID($row['PageNameTextID']);
		$instance->setPosition($row['PagePosition']);
		$instance->setFlag($row['PageShowDefaultLanguage']);
		$instance->setNodeTypeID($row['PageTypeID']);
		$instance->setCreatedDate($row['PageCreatedDate']);
		$instance->setArchiveDate($row['PageArchiveDate']);
		$instance->setPermalinkTextID($row['PagePermalinkTextID']);

		return $instance;
	}
}

// Go to the next row
function NodeGetNext($instance){

	if($row = mysqli_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['PageID']);
		$instance->setNodeNameTextID($row['PageNameTextID']);
		$instance->setPosition($row['PagePosition']);
		$instance->setFlag($row['PageShowDefaultLanguage']);
		$instance->setNodeTypeID($row['PageTypeID']);
		$instance->setCreatedDate($row['PageCreatedDate']);
		$instance->setArchiveDate($row['PageArchiveDate']);
		$instance->setPermalinkTextID($row['PagePermalinkTextID']);
		return $instance;
	}
}

function NodeGetByID($nodeid){
	global $dbcnx, $node_select;
	
	$instance = new Node;
	$sqlstr = $node_select . " WHERE Page.PageID=" . $nodeid;
	
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(' Error in function NodeGetByID(); <br/> ' . mysqli_error($dbcnx) );
	}

	$instance = NodeSetAllFromRow($instance);

	return $instance;

}

function NodeGetIDByPermalink($permalink){
	global $dbcnx;

	$sqlstr = "SELECT PageID FROM Page, Texts WHERE TextContent = '" . $permalink . "' AND TextContent != '' AND PagePermalinkTextID = TextID";

	$result = @mysqli_query($dbcnx, $sqlstr);
	if(mysqli_num_rows($result) == 0)
		return 0;
	else{
		// return mysqli_result($row,0,0); replaced 180115 ///
		$row = mysqli_fetch_assoc($result);
		return $row['PageID'];
	}
}

function NodeGetAllChildren($nodeid){
	global $dbcnx, $node_select;
	
	$instance = new Node;
	$sqlstr = $node_select . ", PageChildren WHERE PageChildren.PageID=" . $nodeid . " AND Page.PageID=PageChildren.ChildPageID ORDER BY Page.PagePosition";

	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(' Error in function NodeGetAllChildren(); <br/> ' . mysqli_error($dbcnx) . "<br/>" . $sqlstr);
	}

	return $instance;
}

function NodeGetAllChildrenTitleOrdered($nodeid, $languageid){
	global $dbcnx, $node_select;

	$instance = new Node;
	
	$sqlstr = $node_select . ", PageChildren, Texts WHERE PageChildren.PageID=" . $nodeid . " AND Page.PageID=PageChildren.ChildPageID AND Texts.TextID=Page.PageNameTextID AND Texts.LanguageID = " . $languageid . " ORDER BY Texts.TextContent, Page.PagePosition";

	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(' Error in function NodeGetAllChildren(); <br/> ' . mysqli_error($dbcnx) );
	}

	return $instance;
}

function NodeGetFirstChild($nodeid){
	global $dbcnx, $node_select;
	
	$instance = new Node;
	$sqlstr = $node_select . ", PageChildren WHERE PageChildren.PageID=" . $nodeid . " AND Page.PageID=PageChildren.ChildPageID ORDER BY Page.PagePosition LIMIT 1";

	$row = @mysqli_query($dbcnx, $sqlstr);

	if(mysqli_num_rows($row) == 0){
		return NULL;
	}else{
		$instance->setDBrows($row);
		if(!$instance->getDBrows()){
			exit(' Error in function NodeGetFirstChild(); <br/> ' . mysqli_error($dbcnx) );
		}
		return $instance;
	}
	
}

function NodeGetFirstChildID($nodeid){
	global $dbcnx, $node_select;
	
	$instance = new Node;
	$sqlstr = "SELECT DISTINCT Page.PageID FROM Page, PageChildren WHERE PageChildren.PageID=" . $nodeid . " AND Page.PageID=PageChildren.ChildPageID ORDER BY Page.PagePosition LIMIT 1";

	$result = @mysqli_query($dbcnx, $sqlstr);
	if(mysqli_num_rows($result) == 0){
		return 0;
	}else{
		// return mysqli_result($row,0,0); replaced 180115 ///
		$row = mysqli_fetch_assoc($result);
		return $row['PageID'];
	}
}

function NodeGetFirstParentID($nodeid){
	global $dbcnx;
	
	$sqlstr = "SELECT PageID FROM PageChildren WHERE ChildPageID=" . $nodeid;

	$result = @mysqli_query($dbcnx, $sqlstr);
	
	if(mysqli_num_rows($result) == 0){
		return 0;
	}else{
		// return mysqli_result($row,0,0); replaced 180115 ///
		$row = mysqli_fetch_assoc($result);
		return $row['PageID'];
	}
}

function NodeGetPath($nid){
		
		$path[0] = $nid;
		$i = 1;
		$id = $nid;

		while(($id = NodeGetFirstParentID($id)) > 0 && $id != WEBROOT){
			$path[$i] = $id;
			$i++;
		}
		
		$j=0;
		for($i=count($path)-1 ;$i>-1; $i--){
			$searchpath[$j] = $path[$i];
			$j++;
		}
		return $searchpath;
}


function NodeHasChildren($nodeid){
	global $dbcnx;

	$sqlstr ="SELECT * FROM PageChildren WHERE PageID=" . $nodeid;
	$row = @mysqli_query($dbcnx, $sqlstr);
	if(mysqli_num_rows($row) == 0)
		return false;
	else
		return true;
}

function NodeHasLanguage($nodeid, $languageid){
	global $dbcnx;

	$sqlstr ="SELECT * FROM PageCountries WHERE PageID=" . $nodeid . " AND CountryID=" . $languageid;
	$row = @mysqli_query($dbcnx, $sqlstr);
	if(mysqli_num_rows($row) == 0)
		return false;
	else
		return true;
}
 
function NodeHasUsergroup($nodeid, $usergroupid){
	global $dbcnx;

	$sqlstr ="SELECT * FROM PartnerCategoryPages WHERE PartnerCategoryID=" . $usergroupid . " AND PageID=" . $nodeid;
	$row = @mysqli_query($dbcnx, $sqlstr);
	if(mysqli_num_rows($row) == 0)
		return false;
	else
		return true;
}


function NodeSave($node){

	global $dbcnx, $node_insert;

	if($node->getID() > 0){

		$sql = "UPDATE Page SET PageID=".$node->getID().", PageNameTextID='".$node->getNodeNameTextID()."', PagePosition=".$node->getPosition().", PageShowDefaultLanguage=".$node->getFlag().", PageTypeID=".$node->getNodeTypeID().", PageCreatedDate='".$node->getCreatedDate()."', PageArchiveDate='".$node->getArchiveDate()."', PagePermalinkTextID='" . $node->getPermalinkTextID() . "' WHERE PageID=". $node->getID();

		mysql_query($sql, $dbcnx);
	
	}else{

		$value = TermosGetCounterValue("PageID");
		TermosSetCounterValue("PageID", ++$value);
		$node->setID($value);
		$node->setCreatedDate( date("Y-m-d h:i:s") );

		$sql = $node_insert . "VALUES (".$node->getID().",'".$node->getNodeNameTextID()."',".$node->getPosition().",".$node->getFlag(). ",".$node->getNodeTypeID().",'".$node->getCreatedDate()."','".$node->getArchiveDate()."','" . $node->getPermalinkTextID() . "')"; 

		mysql_query($sql, $dbcnx);
	}

}

function NodeDelete($nodeid){
	global $dbcnx;

	$node = NodeGetByID($nodeid);
	MTextDelete($node->getNodeNameTextID());
	MTextDelete($node->getPermalinkTextID());

	$sqlstr = "DELETE FROM PageCountries WHERE PageID = " . $nodeid;
	mysql_query($sqlstr, $dbcnx);
	$sqlstr = "DELETE FROM PartnerCategoryPages WHERE PageID = " . $nodeid;
	mysql_query($sqlstr, $dbcnx);
	$sqlstr = "DELETE FROM PageChildren WHERE ChildPageID = " . $nodeid;
	mysql_query($sqlstr, $dbcnx);
	$sqlstr = "DELETE FROM Page WHERE PageID = " . $nodeid;
	mysql_query($sqlstr, $dbcnx);

}

function NodeGetAllRoots(){

	global $dbcnx, $node_select;

	$instance = new Node;
	$sqlstr = $node_select . " WHERE NOT EXISTS (SELECT PageID FROM PageChildren WHERE Page.PageID = PageChildren.ChildPageID) ORDER BY Page.PageID";

	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(' Error in function NodeGetAllRoots(); <br/> ' . mysqli_error($dbcnx) );
	}

	return $instance;

}

function PrintJSONodeTreeOptions($nid, $level){

	$level++;
	if($level < MAXLEVELS){

		$node = NodeGetAllChildren($nid);
		$counter = 0;
		
		while($node = NodeGetNext($node)){

			//if( $level > 1  || ( $level == 1 && $counter > 0) ) print ",";
				
			print ",{\"optionValue\":".$node->getID().",\"optionDisplay\":\"";
			
			for($i=0; $i<$level; $i++){if($i>0)print "- ";}
			
			print $node->getName()."\"}\n";
			
			$counter++;

			if(NodeHasChildren($node->getID()))
				 PrintJSONodeTreeOptions($node->getID(), $level);
			}

	}else return;
}

function PrintJSONodeTreePermalinks($nid, $level){

	$level++;
	if($level < MAXLEVELS){

		$node = NodeGetAllChildren($nid);
		$counter = 0;
		
		while($node = NodeGetNext($node)){

			//if( $level > 1  || ( $level == 1 && $counter > 0) ) print ",";
				
			print ",{\"optionValue\":\"".$node->getPermalink()."\",\"optionDisplay\":\"";
			
			for($i=0; $i<$level; $i++){if($i>0)print "- ";}
			
			print $node->getName()."\"}\n";
			
			$counter++;

			if(NodeHasChildren($node->getID()))
				 PrintJSONodeTreePermalinks($node->getID(), $level);
			}

	}else return;
}

function NodeGetName($nid){
		
	if($node = NodeGetByID($nid)){
		return MTextGet($node->getNodeNameTextID());
	}else{
		return "noname";
	}
}

function NodeGetPermalink($nid){
		
	if($node = NodeGetByID($nid)){
		return MTextGet($node->getPermalinkTextID());
	}else{
		return "noname";
	}
}

function NodeIsHiddenForUserGroup($nid,$ugid){
	global $dbcnx;

	$sql = "SELECT PageID From PartnerCategoryPages WHERE PageID=".$nid. " AND PartnerCategoryID=". $ugid;
	$row = @mysql_query($sql, $dbcnx);

	if(mysqli_num_rows($row) == 0)
		return false;
	else
		return true;

}


/*
mysql> desc PageTypes;
+--------------------+----------+------+-----+---------+-------+
| Field              | Type     | Null | Key | Default | Extra |
+--------------------+----------+------+-----+---------+-------+
| PageTypeID         | int(11)  | YES  |     | NULL    |       |
| PageTypeNameTextID | char(20) | YES  |     | NULL    |       |
+--------------------+----------+------+-----+---------+-------+
*/


$nodetype_select = "SELECT DISTINCT PageTypes.PageTypeID, PageTypes.PageTypeNameTextID FROM PageTypes";
$nodetype_insert = "INSERT INTO PageTypes (PageTypeID, PageTypeNameTextID) ";

// Map the data into object
function NodeTypeSetAllFromRow($instance){

	if($row = mysql_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['PageTypeID']);
		$instance->setNodeTypeNameTextID($row['PageTypeNameTextID']);
		
		return $instance;
	}
}

// Go to the next row
function NodeTypeGetNext($instance){

	if($row = mysql_fetch_array($instance->getDBrows()) ){
		$instance->setID($row['PageTypeID']);
		$instance->setNodeTypeNameTextID($row['PageTypeNameTextID']);
		
		return $instance;
	}
}

function NodeTypeGetAll(){
	global $dbcnx, $nodetype_select;
	
	$instance = new NodeType;
	$sqlstr = $nodetype_select;
	$row = @mysqli_query($dbcnx, $sqlstr);
	$instance->setDBrows($row);

	if(!$instance->getDBrows()){
		exit(' Error in function NodeTypeGetAll(); <br/> ' . mysqli_error($dbcnx) );
	}

	return $instance;
}
?>

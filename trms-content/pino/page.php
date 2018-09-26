<?php
DBcnx();
$thisrequest = parse_request($_SERVER["REQUEST_URI"]);
$thiscontent = ContentGetByID($thisrequest[0]);
$currentnode = NodeGetByID($thisrequest[1]);

$currentuser = UserGetUserByID(TermosGetCurrentUserID());

//////////////////////////



/*/$thiscontent = ContentGetByID(526);

print $thiscontent->getTitleTextID() . '<br/>';
print $thiscontent->getCreatedDate() . '<br/>';
print $thiscontent->getTemplateID() . '<br/>';
print $thiscontent->getTagsTextID() . '<br/>';

print $currentnode->getID() . '<br/>';
//print TermosGetCurrentUserID();


$anode = NodeGetFirstChildID(51);

print $anode;

/////////////////////////*/

get_template('/head.php');
get_template('/template_' . $thiscontent->getTemplateID() . '.php');
get_template('/foot.php');

?>


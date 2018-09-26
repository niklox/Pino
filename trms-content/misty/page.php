<?php
DBcnx();
$thisrequest = parse_request($_SERVER["REQUEST_URI"]);
$thiscontent = ContentGetByID($thisrequest[0]);
$currentnode = NodeGetByID($thisrequest[1]);

$currentuser = UserGetUserByID(TermosGetCurrentUserID());

get_template('/head.php');
get_template('/template_' . $thiscontent->getTemplateID() . '.php');
get_template('/foot.php');

?>


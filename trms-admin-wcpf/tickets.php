<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Privileges.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Privileges.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
DBcnx();

htmlStart();

include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/menu.php';

print "<div id=\"content\">\n";


if(isset($admin))
{
	global $action, $textid, $languageid, $textcategoryid;

	if( UserHasPrivilege($admin->getID(), 19) ){
		defaultAction();
	}else{
		print "User has not privilege: 19." . PrivilegeGetName(19);
	}
}
else
{
	print "Please login!";
}

htmlEnd();


function defaultAction(){
	global  $dbcnx;
	
	$sql = "SELECT * FROM AwardCeremony  ORDER BY DateAndTime DESC";
	$result = @mysqli_query($dbcnx, @sql);
	//$result = mysql_fetch_array($result);

	print "<div>\n";

	print	"<table width=\"100%\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\">";
	print "<tr><td>Order reference</td><td>Date/Time</td><td>Attending</td><td>Fullname</td><td>Address</td><td>Zip City</td><td>Phone</td><td>Email</td><td>Comment</td><td>Tickettype</td><td>Adults</td><td>Children</td><td>Payment</td><td>Currency</td><td>Amount</td><td>Verified</td></tr>\n";

	while ($row = mysqli_fetch_array($result)) {
		$counter++;

		if($counter % 2 == 1) print "<tr style=\"background-color:#EFEFEF\">"; else print "<tr>";
		print "<td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td><td>".$row[5]."</td><td>".$row[6]."</td><td>".$row[7]."</td><td>".$row[8]."</td><td>".$row[9]."</td><td>".$row[10]."</td><td>".$row[11]."</td><td>".$row[12]."</td><td>".$row[13]."</td><td>".$row[14]."</td><td>".$row[15]."</td></tr>\n";
	}
	print	"</table>\n";

	print "</div>\n";

}


function htmlStart(){

	print 
		  "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n" .
		  "<html>\n" .
		  "<head>\n" .
		  "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>\n" .
		  "<title>Termos MText</title>\n" .
		  "<link rel=\"stylesheet\" href=\"css/termosadmin.css\"/>\n" .
		  "<script>\n" .
		  "function deleteMTextForLanguage(mtextid, languageid, language, textcatid){\n" .
		  "	if(confirm('" . MTextGet("deleteMTextForLang") . "  ' + language + '?'))\n" .
		  "	location.href = 'mtext.php?action=deletemtextlanguage&textid=' + mtextid + '&languageid=' + languageid + '&textcatid=' + textcatid;\n" .
		  "}\n" .
		  "function deleteMText(mtextid, textcatid){\n" .
		  "	if(confirm('" . MTextGet("deleteMText") . "  ' + mtextid + '?'))\n" .
		  "	location.href = 'mtext.php?action=deletemtext&textid=' + mtextid + '&textcatid=' + textcatid;\n" .
		  "}\n" .
		  "</script>" .
		  "</head>\n" .
		  "<body>\n";
	}

function htmlEnd(){
	
	print "</div>" .
		  "</body>\n" .
		  "</html>\n";
}



?>

<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.UserGroup.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.UserGroup.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Content.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Privileges.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Privileges.php';
DBcnx();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<title>Termos login</title>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>

<script type="text/javascript" src="/trms-admin/js/dashboard.js"></script>
<link rel="stylesheet" href="css/termosadmin.css"/>

</head>
<body>
<?php
include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/trms-admin/menu.php';

if(isset($admin)){
	
	print	"<div id=\"content\">" . TermosGetCurrentLanguage() ;

	//if( UserHasPrivilege($admin->getID(), 42) ){


	print	"<form action=\"setlanguage.php\">\n" .
			"<input type=\"hidden\" name=\"returnpath\" id=\"returnpath\" value=\"" . $_SERVER["SCRIPT_NAME"] . "\"/>" .
			MTextGet("currentlanguage") .":&nbsp; <select name=\"languageid\" id=\"languageid=\">\n" .
			"<option value=\"". TermosGetDefaultLanguage() ."\">". MTextGet("selectlanguage"). "</option>\n" ;
			$language = LanguageGetAll();

			while($language = LanguageGetNext($language)){
				if($language->getID() == TermosGetCurrentLanguage())
				print "<option value=\"".$language->getID()."\" selected>". $language->getName() ."</option>\n";
				else
				print "<option value=\"".$language->getID()."\">". $language->getName() ."</option>\n";
			}
	print	"</select>\n" ;
	print	" &nbsp;&nbsp;<input type=\"submit\" value=\"". MTextGet("setlanguage") ."\"/>\n".
	
			"</form>\n";

	//}
	print	"<div>";
	print	"<div class=\"dashboardcolumn\">";

	print	"<div class=\"dashboardbox\">\n" .
			"	<div class=\"dashboardbox_head\">Quicklinks</div>\n" .
			"	<div class=\"dashboardbox_body\">" ;
	
	print	"<p>Manual for the external <a href=\"https://docs.google.com/document/pub?id=1LnSLgt7fXEH1X5NX_wfCTZX0CV2M8kXCNev42YM_m_0\">administrators &#187;</a></p>" .
			"<p>Award ceremony invitation  <a href=\"/trms-admin/tickets.php\">Answers/Payments &#187;</a></p>";
	
	print	"	</div>\n" .
			"</div>";
	/*
	//	last logins
	print	"<div class=\"dashboardbox\">\n" .
			"	<div class=\"dashboardbox_head\">Last logins</div>\n" .
			"	<div class=\"dashboardbox_body\">" .
			"	<table class=\"stdtable\">" ;
	
	
	$lastusers = UserGetLastLogins(5);
	while($lastusers = UserGetNext($lastusers)){
		print	"<tr><td>" . $lastusers->getFullname() . "</td><td>". MTextGet(UserGetGroupTextID($lastusers->getUserGroupID())) ."</td><td>" . $lastusers->getLastdate() . "</td><td>" . $lastusers->getCounter() . "</td></tr>";
	}
	print	"	</table>\n";
	print	"	</div>\n" .
			"</div>";

	print	"</div>\n";
	
	
	print	"<div class=\"dashboardcolumn\">";
	
	
*/

	// firstpage mobile content
	print	"<div class=\"dashboardbox\">\n" .
			"	<div class=\"dashboardbox_head\">Tagged for mobile first</div>\n" .
			"	<div class=\"dashboardbox_body\">" .
			"	<table class=\"stdtable\">" ;
	$content = ContentGetAllForFirstPage();
	while($content = ContentGetNext($content)){

		print	'<tr><td><a href="/trms-admin/content.php?action=editContent&cid='.$content->getID().'&nid='.$content->getDefaultNodeID().'">' .strip_tags($content->getTitle()) . '</a></td><td>' . $content->getArchiveDate() . '</td><td>' . UserGetName($content->getAuthorID()) . '</td></tr>';

	}
	print	"</table>\n";
	print	"	</div>\n";
	print	"</div>\n";

	// last updated content
	print	"<div class=\"dashboardbox\">\n" .
			"	<div class=\"dashboardbox_head\">Last updated content</div>\n" .
			"	<div class=\"dashboardbox_body\">" .
			"	<table class=\"stdtable\">" ;
	$content = ContentGetLastUpdated(10);
	while($content = ContentGetNext($content)){

		print	'<tr><td><a href="/'.$content->getPermalink().'.html">' .strip_tags($content->getTitle()) . '</a></td><td>' . $content->getArchiveDate() . '</td><td>' . UserGetName($content->getAuthorID()) . '</td></tr>';

	}
	print	"</table>\n";
	print	"	</div>\n";
	print	"</div>\n";
	
	

		// WCP project
	print	"<div class=\"dashboardbox\">\n" .
			"	<div class=\"dashboardbox_head\">WCP project</div>\n" .
			"	<div id=\"highlighttask\" class=\"dashboardbox_body\">\n" .
			"	<table class=\"stdtable\">\n" .
			"	<tr><td><u>Issue</u></td><td><u>Start</ul></td><td><u>Last edited</ul></td><td><u>Status</u></td></tr>";
			
	$content = ContentGetAllInNode(404);
	while($content = ContentGetNext($content)){

		print	"<tr><td><a id=\"viewissue_".$content->getID()."\" href=\"#\">" .strip_tags($content->getTitle()) . "</a></td><td>".substr($content->getStartDate(),0,10). "</td><td>".substr($content->getArchiveDate(),0,10)."</td><td>";
		
		switch($content->getValue()){
			case 0:
				print $content->getValue();
			break;

			case 1:
				print $content->getValue();

			break;

			case 2:
				print $content->getValue();
			break;
		}
		
		print "</td></tr>\n";

	}
	print	"</table>\n";
	print	"	</div>\n";

	print	"</div>\n";
	print "</div>\n";
}
else
{
	print "<div id=\"content\">";
	print "Please login";
	print "</div>\n";
}
?>
</body>
</html>
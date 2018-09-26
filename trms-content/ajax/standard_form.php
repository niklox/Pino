<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Forms.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Forms.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
DBcnx();
if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["formid"]))$formid = $_REQUEST["formid"];
if(isset($_REQUEST["answertype"]))$answertype = $_REQUEST["answertype"];

if($action == "saveAnswer")
	saveAnswer($formid);
else if($action == "confirmAnswer")
	confirmAnswer($formid);
else
	defaultAction($formid);


function defaultAction($formid){
	global $answertype;
	$error = 0;
	$votes = 0;

	print	"<table class=\"widetable\">";
	print	"<tr><td colspan=\"2\"><h4>". MTextGet("checkInput") . "</h4></td></tr>";

	foreach($_REQUEST as $key => $value){

		if(substr($key, 0,2) == "n_"){

			if( FormInputOptionGetInputTypeID($key) == 9)

				if($value > 0)
					print "<tr><td>" . FormInputOptionGetLabel($key) . "</td><td> " . CountryGet($value) . "</td></tr>";
				else{
					print "<tr><td><span style=\"color:red\">" . FormInputOptionGetLabel($key) . "</span></td><td> Select country! </td></tr>";
					$error++;
			}
			else{

				if(!strlen($value) && strstr( FormInputOptionGetLabel($key), "*") ){
					print "<tr><td><span style=\"color:red\">" . FormInputOptionGetLabel($key) . "</span></td><td> Input is missing!  </td></tr>";
					$error++;
				}else{
					print "<tr><td>" . FormInputOptionGetLabel($key) . "</td><td>" . $value . " </td></tr>";

				}
			}
		}
	}

	print "<tr><td class=\"lastd\" colspan=\"2\"><input type=\"button\" class=\"gv_btn\" id=\"editform\" value=\"Return and edit\"/>\n";

	if($error == 0)
	print " <input type=\"button\" class=\"gv_btn\" id=\"formsubmit\" value=\"". MTextGet("submitForm") ."\"/>\n";

	print	"</td></tr></table>";
}

function saveAnswer($formid){

	$formanswer = new FormAnswer;
	$forminputanswer = new FormInputAnswer;
	$forminputoption = new FormInputOption;

	$formanswer->setID(0);
	$formanswer->setFormID($formid);
	$formanswer->setUserID(0);
	$formanswer->setTypeID(0);
	$formanswer->setDateTime( date("Y-m-d H:i:s") );
	$formanswer->setStatus(1);
	$formanswer->setIP($_SERVER["REMOTE_ADDR"]);

	FormAnswerSave($formanswer);

	foreach($_REQUEST as $key => $value){
		if(substr($key, 0,2) == "n_"){

			$forminputoption = FormInputOptionGetByName($key);

			$forminputanswer->setFormAnswerID(TermosGetCounterValue("FormAnswer"));
			$forminputanswer->setFormID($formid);
			$forminputanswer->setFormInputID($forminputoption->getFormItemID());
			$forminputanswer->setFormInputOptionID(substr($key, 2));
			$forminputanswer->setAnswerText($value);
			FormInputAnswerSave($forminputanswer);
		}
	}
	confirmAnswer($formid);
}

function confirmAnswer($formid){

	print	"<div id=\"confirmsubmit\"><h3>" . MTextGet("thanksForVoting") . " </h3><input type=\"button\" class=\"gv_btn\" name=\"closeconf\" id=\"closeconf\" value=\"". MTextGet("close") . "\"/></div>";

}

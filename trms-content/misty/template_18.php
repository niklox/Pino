<!-- START Template 18 -->
<!-- Markup your HTML here please! -->
<div id="content">

		<div id="leftclmn">
			<?php ImageGet($thiscontent->getID(), 1, "") ?>
		</div>

		<div id="rightclmn">
			<div id="<?php $thiscontent->getTextID(1) ?>"><?php $thiscontent->getText(1) ?> </div>
		<p>
<?php
		if(isset($_REQUEST["confirm"]))
		print	"<h4 class=\"stdheader\">Tack!</h4>";
		else{
		$formhandle = FormHasContent($thiscontent->getID());
		if($formhandle > -1)
			$form = FormGetFormByHandle($formhandle);

		$forminput = FormInputGetAllForForm($form->getID());

		print	"<form name=\"pinocontact\" id=\"pinocontact\" action=\"/trms-content/contactform.php\" method=\"post\" enctype=\"multipart/form-data\">";
		print	"<input type=\"hidden\" name=\"formid\" id=\"formid\" value=\"".$form->getID()."\"/>";
		print	"<input type=\"hidden\" name=\"returnpath\" id=\"returnpath\" value=\"/page.php?cid=".$thiscontent->getID()."&confirm=1\"/>";

		while( $forminput = FormInputGetNext($forminput) ){
			FormInputPrint($forminput->getID(), "standard", 0, 1 );
			print "<br/>\n";
		}

		print "<label for\"file\">Bifoga fil: (.jpg .jpeg .png .gif)</label><input type=\"file\" id=\"file\" name=\"file\" size=\"42\"/><br/><br/>";
		print	'Kontrollkod: <img src="/trms-content/wcp/captcha.php" align="absmiddle"/><br/>';
		print	'Skriv in koden i denna ruta: <input type="text" name="code" size="8"/><br/><br/>';
		print "<input class=\"send\" type=\"reset\" value=\"".MTextGet("reset")."\"/> <input class=\"send\" type=\"submit\" value=\"".MTextGet("send")."\"/>";

		print	"</form>";
		}


?>
</p>
		</div>





</div>
<!-- END Template 18 -->

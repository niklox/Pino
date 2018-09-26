
	<div id="dottedbot"><img src="/trms-content/misty/images/dottedblue.png"/></div>
	<div id="overlay_login">&nbsp;</div>
	<div id="confirmation_std"></div>
	<div id="foot"><?php ImagePrint("pino_pagefoot", "") ?></div>
	<div id="loginlabel">
<?php
	if(isset($currentuser)) print "LOGOUT"; else print "LOGIN";
?>
	</div>

</div>

<map name="flagmap">
  <area shape="rect" coords="0,0,23,13" href="/trms-admin/setlanguage.php?languageid=1&returnpath=/page.php?nid=<?php print $currentnode->getID()?>&cid=<?php print $thiscontent->getID() ?>" alt="Swedish" />
  <area shape="rect" coords="24,0,47,13" href="/trms-admin/setlanguage.php?languageid=11&returnpath=/page.php?nid=<?php print $currentnode->getID()?>&cid=<?php  print $thiscontent->getID() ?>" alt="Japaneese" />
  <area shape="rect" coords="48,0,70,13" href="/trms-admin/setlanguage.php?languageid=5&returnpath=/page.php?nid=<?php print $currentnode->getID()?>&cid=<?php  print $thiscontent->getID() ?>" alt="English" />
</map>
<?php print "<input type=\"hidden\" id=\"cid\" value=\"" . $thiscontent->getID() . "\" />"; print "<input type=\"hidden\" id=\"tmplid\" value=\"" . $thiscontent->getTemplateID() . "\" />"; ?>

</body>
</html>

<?php
session_start();

if(TermosGetCurrentUserID() != 0 && $admin = UserGetUserByID(TermosGetCurrentUserID()))
{
	print '<div id="pagehead">' .
		  '	<div id="pagehead_left">Termos <span class="other">CMS</span><span class="currentlanguage">Current language is: '. LanguageName(TermosGetCurrentLanguage()).'</span></div>'.
		  '	<div id="pagehead_right">' .
		  ' <input type="button" class="dark" value="Log out ' . $admin->getFullname() . '" onclick="location.href=\'/trms-admin/logout.php\'"></div>' .
		  '</div>';

		// MTextGet("loginOK"). ' ' . $admin->getFullname() . ' '. MTextGet("youarein") . "\n" .
}
else
{
	print 	'<div id="pagehead">'.
			'<div id="pagehead_left">Termos<span class="other">CMS</span></div>'.
			'<div id="pagehead_right">'.
			'</div>'.
			'</div>';
}
?>

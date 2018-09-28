<?php
session_start();
if($admin = UserGetUserByID(TermosGetCurrentUserID())) 
{
	print "<div id=\"pagehead\">\n" .
		  "		<div id=\"pagehead_left\">Termos <span class=\"other\">CMS</span><span class=\"currentlanguage\">Current language is: ". LanguageName(TermosGetCurrentLanguage())."</span></div>\n" .
		  "		<div id=\"pagehead_right\">" .
		  "		<input type=\"button\" class=\"dark\" value=\"Log out " . $admin->getFullname() . "\" onclick=\"location.href='/trms-admin/logout.php'\"></div>\n" .
		  "</div>\n";

		// MTextGet("loginOK"). ' ' . $admin->getFullname() . ' '. MTextGet("youarein") . "\n" .
}
else
{
	
?>
	<div id="pagehead">
		<div id="pagehead_left">Termos <span class="other">CMS</span></div>
		<div id="pagehead_right"> 
		<form action="/trms-admin/logincheck.php" method="post"/>
		Email: <input type="text" name="AccountName" id="AccountName" size="28"/>
		Password: <input type="password" name="Password" id="Password" size="25"/>
		<input type="submit" class="dark" id="Loginbtn" value="Login"/><br/>
		</form>
		</div>
	</div>
<?php
}
?>

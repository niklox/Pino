<?php
if(isset($admin)){
?>
<ul id="adminmenu">
	<?php
	if(strstr($_SERVER["SCRIPT_NAME"], "login"))
	print "<li><a class=\"on\" href=\"/trms-admin/index.php\">Dashboard</a></li>\n";
	else
	print "<li><a href=\"/trms-admin/index.php\">Dashboard</a></li>\n";
	
	if(strstr($_SERVER["SCRIPT_NAME"], "users") || strstr($_SERVER["SCRIPT_NAME"], "usergroups") || strstr($_SERVER["SCRIPT_NAME"], "organisations") )
	print "<li><a class=\"on\" href=\"/trms-admin/users.php\">Users</a></li>\n";
	else
	print "<li><a href=\"/trms-admin/users.php\">Users</a></li>\n";

	if(strstr($_SERVER["SCRIPT_NAME"], "content") || strstr($_SERVER["SCRIPT_NAME"], "structure") || strstr($_SERVER["SCRIPT_NAME"], "templates"))
	print "<li><a class=\"on\" href=\"/trms-admin/content.php?rid=21\">Content & Structures</a></li>\n";
	else
	print "<li><a href=\"/trms-admin/content.php?rid=21\">Content & Structure</a></li>\n";

	if(strstr($_SERVER["SCRIPT_NAME"], "images"))
	print "<li><a class=\"on\" href=\"/trms-admin/images.php\">Images</a></li>\n";
	else
	print "<li><a href=\"/trms-admin/images.php\">Images</a></li>\n";

	if(strstr($_SERVER["SCRIPT_NAME"], "forms"))
	print "<li><a class=\"on\" href=\"/trms-admin/forms.php\">Forms</a></li>\n";
	else
	print "<li><a href=\"/trms-admin/forms.php\">Forms</a></li>\n";

	if(strstr($_SERVER["SCRIPT_NAME"], "discussions"))
	print "<li><a class=\"on\" href=\"/trms-admin/discussions.php\">Discussions</a></li>\n";
	else
	print "<li><a href=\"/trms-admin/discussions.php\">Discussions</a></li>\n";

	if(strstr($_SERVER["SCRIPT_NAME"], "mtext") || strstr($_SERVER["SCRIPT_NAME"], "languages") || strstr($_SERVER["SCRIPT_NAME"], "parameters"))
	print "<li><a class=\"on\" href=\"/trms-admin/mtext.php\">MTexts</a></li>\n";
	else
	print "<li><a href=\"/trms-admin/mtext.php\">MTexts</a></li>\n";
	
	if( UserHasPrivilege($admin->getID(), 43) ){
		if(strstr($_SERVER["SCRIPT_NAME"], "globaldb"))
		print "<li><a class=\"on\" href=\"/trms-admin/globaldb.php\">GFDB</a></li>\n";
		else
		print "<li><a href=\"/trms-admin/globaldb.php\">GFDB</a></li>\n";
	
	
	if(strstr($_SERVER["SCRIPT_NAME"], "donations"))
	print "<li><a class=\"on\" href=\"/trms-admin/donations.php\">Donation</a></li>\n";
	else
	print "<li><a href=\"/trms-admin/donations.php\">Donation</a></li>\n";
	
	}

	?>
</ul>
<?php
if(strstr($_SERVER["SCRIPT_NAME"], "users") || strstr($_SERVER["SCRIPT_NAME"], "usergroups") || strstr($_SERVER["SCRIPT_NAME"], "organisations") || strstr($_SERVER["SCRIPT_NAME"], "privileges")){
?>

<ul id="adminsubmenu">
	<li><a href="/trms-admin/users.php">Users</a></li>
	<li><a href="/trms-admin/usergroups.php">Groups</a></li>
	<li><a href="/trms-admin/organisations.php">Organisations</a></li>
	<li><a href="/trms-admin/privileges.php">Privileges</a></li>
</ul>

<?php
}
if(strstr($_SERVER["SCRIPT_NAME"], "content") || strstr($_SERVER["SCRIPT_NAME"], "structure") || strstr($_SERVER["SCRIPT_NAME"], "templates")){
?>
<ul id="adminsubmenu">
	<li><a href="/trms-admin/content.php?rid=<?php echo WEBROOT; ?>">Content</a></li>
	<li><a href="/trms-admin/structure.php">Structures</a></li>
	<li><a href="/trms-admin/templates.php">Templates</a></li>
</ul>
<?php
}
if( strstr($_SERVER["SCRIPT_NAME"], "mtext") || strstr($_SERVER["SCRIPT_NAME"], "languages") || strstr($_SERVER["SCRIPT_NAME"], "parameters") ){
?>
<ul id="adminsubmenu">
	<li><a href="/trms-admin/mtext.php">MTexts</a></li>
	<li><a href="">TextCategories</a></li>
	<li><a href="/trms-admin/languages.php">Languages</a></li>
	<li><a href="/trms-admin/parameters.php">Parameters</a></li>
</ul>
<?php
}

if( strstr($_SERVER["SCRIPT_NAME"], "images") ){
?>
<ul id="adminsubmenu">
	<li><a href="/trms-admin/images.php">Imagecategories</a></li>
</ul>
<?php
}

if( strstr($_SERVER["SCRIPT_NAME"], "forms") || strstr($_SERVER["SCRIPT_NAME"], "formanswers") ){
?>

<ul id="adminsubmenu">
	<li><a href="/trms-admin/forms.php">Forms</a></li>
	<li><a href="/trms-admin/formanswers.php">Formanswers</a></li>
</ul>

<?php
}


if( strstr($_SERVER["SCRIPT_NAME"], "discussions")  ){
?>

<ul id="adminsubmenu">
	<li><a href="/trms-admin/discussions.php">Current</a></li>
</ul>


<?php

}

if( strstr($_SERVER["SCRIPT_NAME"], "globaldb")  ){
?>

<ul id="adminsubmenu">
	<li><a href="/trms-admin/globaldb.php">Global DB</a></li>
	<li><a href="/trms-admin/globaldb_topics.php">Topics</a></li>
	<li><a href="/trms-admin/globaldb_countries.php">Countries</a></li>
</ul>

<?php
}



}
?>
<!DOCTYPE html>
<html>
<head>
<title> Pino the bear <?php print $currentnode->getName() ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

<link rel="stylesheet" href="/trms-content/pino/css/misty-mod.css"/>
<link rel="stylesheet" href="/trms-content/pino/css/pino.css"/>

<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<?php
	if(isset($currentuser)){
		print	"<!-- this is the admin scripts -->\n" .
				"<script type=\"text/javascript\" src=\"/trms-content/js/generic_pino.js\"></script>\n" .
				"<script type=\"text/javascript\" src=\"/trms-admin/js/jquery.form.js\"></script>\n" ;
		}
?>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.css" type="text/css" media="all" />
<script type="text/javascript" src="/trms-content/js/site-generic.js"></script>
</head>




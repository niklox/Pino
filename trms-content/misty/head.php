<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title> <?php print $currentnode->getName() ?></title>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js"></script>
<?php
	if(isset($currentuser)){
		print	"<!-- this is the admin scripts -->\n" .
				"<script type=\"text/javascript\" src=\"/trms-content/js/generic_pino.js\"></script>\n" .
				"<script type=\"text/javascript\" src=\"/trms-admin/js/jquery.form.js\"></script>\n" ;
		}
?>
	<script type="text/javascript" src="/trms-content/misty/js/misty.js"></script>
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/base/jquery-ui.css" type="text/css" media="all" />
	<link rel="stylesheet" href="/trms-content/misty/css/misty.css"/>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-8277315-6']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body class="main">

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/sv_SE/all.js#xfbml=1&appId=275809749113825";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

	<div id="page">
		<div id="topofhead"><div class="fb-like" data-href="http://www.facebook.com/pages/Pino/121548821232980" data-send="false" data-layout="button_count" data-width="40" data-show-faces="false" data-font="arial"></div>&nbsp;<a href="/contact-english"><img src="/trms-content/misty/images/unionjack.png" width="20" height="12"/></a></div>
		<div id="head"><?php ImagePrint("pinohead-thin", "") ?></div>
		<div id="dottedtop"><img src="/trms-content/misty/images/dottedblue.png"/></div>
		<div id="menu">
		<ul id="nav">
<?php
		$nodepath = NodeGetPath($currentnode->getID());
		$node = NodeGetAllChildren(WEBROOT);

		while($node = NodeGetNext($node)){
			if($nodepath[0] == $node->getID())
				print "<li style=\"text-decoration:underline\"><a href=\"/" . $node->getPermalink()  . "\"  class=\"current\">" . NodeGetName($node->getID()) ."</a>";
			else
				print "<li><a href=\"/" . $node->getPermalink()  . "\">" . NodeGetName($node->getID()) ."</a>";


			print "</li>";


		}

		print '</ul>';


			if(NodeHasChildren($nodepath[0])){
				print '<ul class="submenu">';
				$subnodes = NodeGetAllChildren($nodepath[0]);
				while($subnodes = NodeGetNext($subnodes)){
					if($nodepath[1] == $subnodes->getID())
					print "<li style=\"text-decoration:underline\"><a href=\"/" . $subnodes->getPermalink()  . "\">" . NodeGetName($subnodes->getID()) ."</a></li>\n";
					else
					print "<li><a href=\"/" . $subnodes->getPermalink()  . "\">" . NodeGetName($subnodes->getID()) ."</a></li>\n";

				}
				print "</ul>\n";

			}


?>
		</div>

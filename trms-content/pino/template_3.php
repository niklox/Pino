<!-- START Template 3 -->
<?php

print '<body class="main">';

get_template('/template_navigation.php');

$text = ContentTextGet($thiscontent->getID(),1);
print 	'<section class="block green">'.
		'<div class="blockcontent" id="'.$text->getTextID() .'">'. $text->getText() .'</div>'.
		'<div class="blockcontent narrow green">';
		ImageGetII_SMALL($thiscontent->getID(), 1, "");
print	'</div>'.
		'</section>';

$text = ContentTextGet($thiscontent->getID(),2);
print 	'<section class="block">'.
		'<div class="blockcontent narrow">';
		ImageGetII_MEDIUM($thiscontent->getID(), 2, "");
print 	'</div>'.
		'<div class="blockcontent" id="'.$text->getTextID() .'">'. $text->getText() .'</div>';
print 	'</section>';

print 	'<section class="block blue"><div class="blockcontent wide"><h3>Instagram: pinothebear</h3>';
?>
		<script type="text/javascript" src="/trms-content/pino/js/instafeed.min.js"></script>
		<script type="text/javascript">
    	var feed = new Instafeed({
    		get: 'user',
    		template: '<a href="{{link}}" target="_blank"><img src="{{image}}"/></a>',
    		sortBy: 'most-recent',
    		limit: 20,
    		userId: 4466654418, // pinothebear
    		accessToken:'4466654418.d28635c.6e8f25afb61947569fd9ca956cc41f17'
    		
    		//userId: 1303305789, // katelieri
    		//accessToken:'1303305789.f7e4156.08e10c3f73814486a95a18fc375df58c'
    	});
    	feed.run();
		</script>
				
<div id="instafeed"></div>
<?php
print 	'</div></section>';

$text = ContentTextGet($thiscontent->getID(),3);
print 	'<section class="block">';

print 	'<div class="blockcontent narrow" id="'.$text->getTextID() .'">'. $text->getText() .'</div>'.
		'<div class="blockcontent">';
		ImageGetII_MEDIUM($thiscontent->getID(), 3, "");
print	'</div>'.
		'</section>';

$text = ContentTextGet($thiscontent->getID(),4);
print 	'<section class="block grey">'. $text->getText() .'</section>';
$text = ContentTextGet($thiscontent->getID(),5);
print 	'<section class="block">'. $text->getText() .'</section>';
$text = ContentTextGet($thiscontent->getID(),6);
print 	'<section class="block red">'. $text->getText() .'</section>';
$text = ContentTextGet($thiscontent->getID(),7);
print 	'<section class="block">'. $text->getText() .'</section>';
$text = ContentTextGet($thiscontent->getID(),8);
print 	'<section class="block light-green">'. $text->getText() .'</section>';
$text = ContentTextGet($thiscontent->getID(),7);
print 	'<section class="block">'. $text->getText() .'</section>';








//ImageGet($thiscontent->getID(), 1, ""); // $thiscontent->getTextID(1) 
//print '<div id="wrapper">';		
//print '<div id="'.$text->getTextID() .'">'. $text->getText() .'</div>';
//print '</div>';		
?>

<!--  END template 3  -->

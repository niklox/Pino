<!-- START Template 2 -->
<?php

print '<body class="main">';

$text = ContentTextGet($thiscontent->getID(),1);

//ImageGet($thiscontent->getID(), 1, ""); // $thiscontent->getTextID(1) 
print '<div id="wrapper">';		
print '<div id="'.$text->getTextID() .'">'. $text->getText() .'</div>';
print '</div>';		
?>

<!--  END template 2  -->

<?php

session_start();

$md5 = md5(microtime() * mktime());
$string = substr($md5,0,5);
$_SESSION['key'] = md5($string);


$img = imagecreatefrompng("captcha.png");

$black = imagecolorallocate($img, 0, 0, 0);
$line = imagecolorallocate($img,233,0,0);

imageline($img,0,0,39,29,$line);
imageline($img,40,0,64,29,$line);

imagestring($img, 6, 7, 2, $string, $black);

header('Content-type: image/png');
imagepng($img);
imagedestroy($img);
?> 

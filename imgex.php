<?php
/**
 * Resize image given a height and width and return raw image data.
 *
 * Note : You can add more supported image formats adding more parameters to the switch statement.
 *
 * @param type $file filepath
 * @param type $w width in px
 * @param type $h height in px
 * @param type $crop Crop or not
 * @return type
 */
function resize_image($file, $w, $h, $crop=false) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    
    //if(!isset($h) || $h === 0 || $h === NULL || $h === -1)
    //$h = $w*$r;  
    
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
        	print 'ståedes';
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
        	print 'liggande';
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    
    //Get file extension
    $exploding = explode(".",$file);
    $ext = end($exploding);
    
    switch($ext){
        case "png":
            $src = imagecreatefrompng($file);
        break;
        case "jpeg":
        case "jpg":
            $src = imagecreatefromjpeg($file);
        break;
        case "gif":
            $src = imagecreatefromgif($file);
        break;
        default:
            $src = imagecreatefromjpeg($file);
        break;
    }
    
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	return $dst;
}


$filename = "./pinohelikopter.png";
$resizedFilename = "./images/pinokopter.png";

// resize the image with 300x300
$imgData = resize_image($filename, 600, 612);
// save the image on the given filename
imagepng($imgData, $resizedFilename, 1);
// or according to the original format, use another method
// imagejpeg($imgData, $resizedFilename);
// imagegif($imgData, $resizedFilename);

print 'resize from: <img src="pinohelikopter.png"> to this: <img src="/images/pinokopter.png">';

?>
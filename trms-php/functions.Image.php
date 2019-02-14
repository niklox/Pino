<?php/*mysql> desc Images;+-----------------+--------------+------+-----+---------+-------+| Field           | Type         | Null | Key | Default | Extra |+-----------------+--------------+------+-----+---------+-------+| ImageID         | int(11)      | YES  |     | NULL    |       || ImageSizeX      | int(11)      | YES  |     | NULL    |       || ImageSizeY      | int(11)      | YES  |     | NULL    |       || ImageIconSizeX  | int(11)      | YES  |     | NULL    |       || ImageIconSizeY  | int(11)      | YES  |     | NULL    |       || ImageFormat     | varchar(4)   | YES  |     | NULL    |       || ImageIconFormat | varchar(4)   | YES  |     | NULL    |       || ImageHandle     | varchar(20)  | YES  |     | NULL    |       || ImageLanguageID | int(11)      | YES  |     | NULL    |       || ImageAltTextID  | varchar(100) | YES  |     | NULL    |       |+-----------------+--------------+------+-----+---------+-------+10 rows in set (0.01 sec)*/define("IMAGE_SELECT", "SELECT Images.ImageID, Images.ImageSizeX, Images.ImageSizeY, Images.ImageIconSizeX, Images.ImageIconSizeY, Images.ImageFormat, Images.ImageIconFormat, Images.ImageHandle, Images.ImageLanguageID, Images.ImageAltTextID FROM Images");define("IMAGE_INSERT", "INSERT INTO Images (ImageID, ImageSizeX, ImageSizeY, ImageIconSizeX, ImageIconSizeY, ImageFormat, ImageIconFormat, ImageHandle, ImageLanguageID, ImageAltTextID) ");// Map the data into objectfunction ImageSetAllFromRow($instance){		if($row = mysqli_fetch_array($instance->getDBrows()) ){		$instance->setID($row['ImageID']);		$instance->setSizeX($row['ImageSizeX']);		$instance->setSizeY($row['ImageSizeY']);		$instance->setIconSizeX($row['ImageIconSizeX']);		$instance->setIconSizeY($row['ImageIconSizeY']);		$instance->setFormat($row['ImageFormat']);		$instance->setIconFormat($row['ImageIconFormat']);		$instance->setHandle($row['ImageHandle']);		$instance->setLanguageID($row['ImageLanguageID']);		$instance->setAltTextID($row['ImageAltTextID']);				return $instance;	}}// Go to the next rowfunction ImageGetNext($instance){	if($row = mysqli_fetch_array($instance->getDBrows()) ){		$instance->setID($row['ImageID']);		$instance->setSizeX($row['ImageSizeX']);		$instance->setSizeY($row['ImageSizeY']);		$instance->setIconSizeX($row['ImageIconSizeX']);		$instance->setIconSizeY($row['ImageIconSizeY']);		$instance->setFormat($row['ImageFormat']);		$instance->setIconFormat($row['ImageIconFormat']);		$instance->setHandle($row['ImageHandle']);		$instance->setLanguageID($row['ImageLanguageID']);		$instance->setAltTextID($row['ImageAltTextID']);		return $instance;	}}function ImageGetByID($imgid){	global $dbcnx;		$instance = new Image;	$sqlstr = IMAGE_SELECT . " WHERE Images.ImageID=" . $imgid;// . " AND ImageLanguageID=0";	//$sqlstr = IMAGECATEGORY_SELECT . " WHERE ImageCategories.ImageCategoryID=" . $imgcatid;		$row = @mysqli_query($dbcnx, $sqlstr);	$instance->setDBrows($row);	if(!$instance->getDBrows()){		exit(' Error in function ImageGetByID(); <br/> ' . $sqlstr .'<br/>' . mysqli_error($dbcnx) );	}	//$instance = ImageSetAllFromRow($instance);	return $instance;}function ImageCheckForLanguageImages($imgid){	global $dbcnx;	$instance = new Image;	$sqlstr = IMAGE_SELECT . " WHERE Images.ImageID=" . $imgid;	$row = @mysqli_query($dbcnx, $sqlstr);	if(mysqli_num_rows($row) > 0)		return mysqli_num_rows($row);	else		return 0;}function ImageGetByIDAndLanguage($imgid, $languageid){	global $dbcnx;	$instance = new Image;	$sqlstr = IMAGE_SELECT . " WHERE Images.ImageID=" . $imgid . " AND ImageLanguageID=" . $languageid; 		$row = @mysqli_query($dbcnx, $sqlstr);	$instance->setDBrows($row);	if(!$instance->getDBrows()){		exit(' Error in function ImageGetByIDAndLanguage(); <br/> ' . $sqlstr .'<br/>' . mysqli_error($dbcnx) );	}	$instance = ImageSetAllFromRow($instance);	return $instance;}function ImageGetByHandleAndLanguage($imghandle, $languageid){	global $dbcnx;	$instance = new Image;	$sqlstr = IMAGE_SELECT . " WHERE Images.ImageHandle='" . $imghandle . "' AND ImageLanguageID=" . $languageid; 		$row = @mysqli_query($dbcnx, $sqlstr);	$instance->setDBrows($row);	if(!$instance->getDBrows()){		exit(' Error in function ImageGetByHandleAndLanguage(); <br/> ' . $sqlstr .'<br/>' . mysqli_error($dbcnx) );	}		$instance = ImageSetAllFromRow($instance);	return $instance;}function ImageGetByHandleCheckLanguage($imghandle, $languageid){	global $dbcnx;	$instance = new Image;	$sqlstr = IMAGE_SELECT . " WHERE Images.ImageHandle='" . $imghandle . "' AND ImageLanguageID=" . $languageid; 	$row = @mysqli_query($dbcnx, $sqlstr);	if(mysqli_num_rows($row) == 0){		$sqlstr = IMAGE_SELECT . " WHERE Images.ImageHandle='" . $imghandle . "' AND ImageLanguageID = -1"; 		$row = @mysqli_query($dbcnx, $sqlstr);	}		$instance->setDBrows($row);	if(!$instance->getDBrows()){		exit(' Error in function ImageGetByHandleAndLanguage(); <br/> ' . $sqlstr .'<br/>' . mysqli_error($dbcnx) );	}		$instance = ImageSetAllFromRow($instance);	return $instance;}function ImageHasLanguage($imgid, $languageid){	global $dbcnx;	$sqlstr = IMAGE_SELECT . " WHERE Images.ImageID=" . $imgid . " AND ImageLanguageID=" . $languageid; 	$row = @mysqli_query($dbcnx, $sqlstr);	if(mysqli_num_rows($row) == 0)		return false;	else		return true;}function ImageInsertLanguage($imageid,$langid){	global $dbcnx;	$image = ImageGetByID($imageid); 	$image = ImageGetNext($image);		$sql = IMAGE_INSERT . "VALUES (". $imageid .",0,0,0,0,'','','" . $image->getHandle() . "', " . $langid . ",'".$image->getAltTextID()."')";	mysqli_query($dbcnx, $sql);			//$sql = 'INSERT INTO Texts VALUES("'.$mtext->getID().'",' .$mtext->getTextCategoryID().','.$langid.', '.$mtext->getTextPosition().', "'.addslashes($mtext->getTextContent()).'")';}function ImageGetAllInCategory($imgcatid, $allanguages){	global $dbcnx;	$instance = new Image;	if($allanguages > 0)	$sqlstr = IMAGE_SELECT . ", ImageCategoryValues WHERE ImageCategoryValues.ImageCategoryID=" . $imgcatid . " AND ImageCategoryValues.ImageID=Images.ImageID ORDER BY Images.ImageID";	else	$sqlstr = IMAGE_SELECT . ", ImageCategoryValues WHERE ImageCategoryValues.ImageCategoryID=" . $imgcatid . " AND ImageCategoryValues.ImageID=Images.ImageID AND (Images.ImageLanguageID = 0 OR Images.ImageLanguageID = -1) ORDER BY Images.ImageID";	$row = @mysqli_query($dbcnx, $sqlstr);	$instance->setDBrows($row);	if(!$instance->getDBrows()){		exit(' Error in function ImageGetAllInCategory(); <br/> ' . mysqli_error($dbcnx) );	}	return $instance;}function ImageGetAllInCategoryOrderByHandle($imgcatid, $allanguages){	global $dbcnx;	$instance = new Image;	if($allanguages > 0)	$sqlstr = IMAGE_SELECT . ", ImageCategoryValues WHERE ImageCategoryValues.ImageCategoryID=" . $imgcatid . " AND ImageCategoryValues.ImageID=Images.ImageID ORDER BY Images.ImageHandle";	else	$sqlstr = IMAGE_SELECT . ", ImageCategoryValues WHERE ImageCategoryValues.ImageCategoryID=" . $imgcatid . " AND ImageCategoryValues.ImageID=Images.ImageID AND (Images.ImageLanguageID = 0 OR Images.ImageLanguageID = -1) ORDER BY Images.ImageHandle";	$row = @mysqli_query($dbcnx, $sqlstr);	$instance->setDBrows($row);	if(!$instance->getDBrows()){		exit(' Error in function ImageGetAllInCategory(); <br/> ' . mysqli_error($dbcnx) );	}	return $instance;}function ImageGet($cid, $pos, $css){		$handle = ContentHasImageAtPosition($cid, $pos);	if( $handle > -1 && ($imglink = ImageLinkGet($cid, $pos) ) ){		$image = ImageGetByHandleAndLanguage($handle, -1);		print	"<a href=\"/". $imglink->getURL() ."\" ".($imglink->getTarget() == 1?"target='_blank'":"") .">";		ImageCreateTagWithCSS($image, 1000, 0, $css);		print	"</a>";		}	else if( $handle > -1 ){		$image = ImageGetByHandleAndLanguage($handle, -1);		ImageCreateTagWithCSS($image, 1000, 0, $css);	}}function ImageGetII($cid, $pos, $css){		$handle = ContentHasImageAtPosition($cid, $pos);		// If there is no specific image for the current language the return value from 	// the function below will be -1 which will be the case for 99% of all imagepositions 		$languageid = ImageCheckForLanguage($handle, TermosGetCurrentLanguage());		if( $handle > -1 && ($imglink = ImageLinkGet($cid, $pos) ) ){		$image = ImageGetByHandleAndLanguage($handle, $languageid);		print	"<a href=\"/". $imglink->getURL() ."\" ".($imglink->getTarget() == 1?"target='_blank'":"") .">";		ImageCreateTagWithCSSII($image, 1000, 0, $css);		print	"</a>";		}	else if( $handle > -1 ){		$image = ImageGetByHandleAndLanguage($handle, $languageid);		ImageCreateTagWithCSSII($image, 1000, 0, $css);	}}function ImageGetWithIDNoSize($cid, $pos, $css, $id){		$handle = ContentHasImageAtPosition($cid, $pos);	if( $handle > -1 && ($imglink = ImageLinkGet($cid, $pos) ) ){		$image = ImageGetByHandleAndLanguage($handle, -1);		print	"<a href=\"/". $imglink->getURL() ."\">";		ImageCreateTagWithCSSAndIDNoSize($image, IMGMASK, 0, $css);		print	"</a>";		}	else if( $handle > -1 ){		$image = ImageGetByHandleAndLanguage($handle, -1);		ImageCreateTagWithCSSAndIDNoSize($image, IMGMASK, 0, $css, $id);	}}function ImageGetWithIDNoSizeII($cid, $pos, $css, $id, $size){		$handle = ContentHasImageAtPosition($cid, $pos);	if( $handle > -1 && ($imglink = ImageLinkGet($cid, $pos) ) ){		$image = ImageGetByHandleAndLanguage($handle, -1);		print	"<a href=\"/". $imglink->getURL() ."\">";		ImageCreateTagWithCSSAndIDNoSize($image, IMGMASK, $size, $css);		print	"</a>";		}	else if( $handle > -1 ){		$image = ImageGetByHandleAndLanguage($handle, -1);		ImageCreateTagWithCSSAndIDNoSize($image, IMGMASK, $size, $css, $id);	}}function ImageCreateTagWithCSSAndIDNoSize($image, $mask, $icon, $class, $id){	if($icon)		print '<img class="'.$class.'" id="'.$id.'" src="' . IMAGEDIR . ImageCreateFileName($image, $mask, $icon) . '"  alt="' . $image->getAltText() . '" />';	else		print '<img class="'.$class.'" id="'.$id.'" src="' . IMAGEDIR . ImageCreateFileName($image, $mask, $icon) . '" alt="' . $image->getAltText() . '" />';}function ImageCheckForLanguage($imagehandle, $languageid){	global $dbcnx;	$sql = IMAGE_SELECT . ' WHERE ImageHandle = "' . $imagehandle . '" AND ImageLanguageID = ' . $languageid; 		$result = @mysqli_query($dbcnx, $sql);	if(!mysqli_num_rows($result))		return -1;	else{		// return mysql_result($row,0,8); removed 180117 //		$row = mysqli_fetch_assoc($result);		return $row['ImageLanguageID'];	}}function ImageGetII_FOCUS($cid, $pos, $textid){	$css = "";	$handle = ContentHasImageAtPosition($cid, $pos);		// If there is no specific image for the current language the return value from 	// the function below will be -1 which will be the case for 99% of all imagepositions 		$languageid = ImageCheckForLanguage($handle, TermosGetCurrentLanguage());	$image = ImageGetByHandleAndLanguage($handle, $languageid);	print	'<a id="slideshow_'.$cid.'x'.$pos.'" data-textid="'.$textid.'" href="#">';	ImageCreateTagWithCSSII($image, 100, 0, $css);	print	'</a>';	}function ImageGetII_LARGE($cid, $pos, $css){		$handle = ContentHasImageAtPosition($cid, $pos);		// If there is no specific image for the current language the return value from 	// the function below will be -1 which will be the case for 99% of all imagepositions 		$languageid = ImageCheckForLanguage($handle, TermosGetCurrentLanguage());	if( $handle > -1 && ($imglink = ImageLinkGet($cid, $pos) ) ){		$image = ImageGetByHandleAndLanguage($handle, $languageid);		print	"<a href=\"/". $imglink->getURL() ."\" ".($imglink->getTarget() == 1?"target='_blank'":"") .">";		ImageCreateTagWithCSSII($image, 100, 3, $css);		print	"</a>";		}	else if( $handle > -1 ){		$image = ImageGetByHandleAndLanguage($handle, $languageid);		ImageCreateTagWithCSSII($image, 100, 3, $css);	}}function ImageGetII_MEDIUM($cid, $pos, $css){		$handle = ContentHasImageAtPosition($cid, $pos);		// If there is no specific image for the current language the return value from 	// the function below will be -1 which will be the case for 99% of all imagepositions 		$languageid = ImageCheckForLanguage($handle, TermosGetCurrentLanguage());	if( $handle > -1 && ($imglink = ImageLinkGet($cid, $pos) ) && $css !== "noimagelink"){		$image = ImageGetByHandleAndLanguage($handle, $languageid);		print	"<a href=\"/". $imglink->getURL() ."\" ".($imglink->getTarget() == 1?"target='_blank'":"") .">";		ImageCreateTagWithCSSII($image, 100, 2, $css);		print	"</a>";	}	else if( $handle > -1 ){		$image = ImageGetByHandleAndLanguage($handle, $languageid);		ImageCreateTagWithCSSII($image, 100, 2, $css);	}}function ImageGetII_SMALL($cid, $pos, $css){		$handle = ContentHasImageAtPosition($cid, $pos);		// If there is no specific image for the current language the return value from 	// the function below will be -1 which will be the case for 99% of all imagepositions 		$languageid = ImageCheckForLanguage($handle, TermosGetCurrentLanguage());	if( $handle > -1 && ($imglink = ImageLinkGet($cid, $pos) ) ){		$image = ImageGetByHandleAndLanguage($handle, $languageid);		print	"<a href=\"/". $imglink->getURL() ."\" ".($imglink->getTarget() == 1?"target='_blank'":"") .">";		ImageCreateTagWithCSSII($image, 100, 1, $css);		print	"</a>";		}	else if( $handle > -1 ){		$image = ImageGetByHandleAndLanguage($handle, $languageid);		ImageCreateTagWithCSSII($image, 100, 1, $css);	}}// THUMBNAIL and SMALL are the same image function ImageGetII_THUMBNAIL($cid, $pos, $css){		$handle = ContentHasImageAtPosition($cid, $pos);		// If there is no specific image for the current language the return value from 	// the function below will be -1 which will be the case for 99% of all imagepositions 		$languageid = ImageCheckForLanguage($handle, TermosGetCurrentLanguage());	if( $handle > -1 && ($imglink = ImageLinkGet($cid, $pos) ) ){		$image = ImageGetByHandleAndLanguage($handle, $languageid);		print	"<a href=\"/". $imglink->getURL() ."\" ".($imglink->getTarget() == 1?"target='_blank'":"") .">";		ImageCreateTagWithCSSII($image, 100, 1, $css);		print	"</a>";		}	else if( $handle > -1 ){		$image = ImageGetByHandleAndLanguage($handle, $languageid);		ImageCreateTagWithCSSII($image, 100, 1, $css);	}}function ImageGetByLanguage($cid, $pos, $css, $langid){		$handle = ContentHasImageAtPosition($cid, $pos);	if( $handle > -1 && ($imglink = ImageLinkGet($cid, $pos) ) ){		$image = ImageGetByHandleCheckLanguage($handle, $langid);		print	"<a href=\"/". $imglink->getURL() ."\" ".($imglink->getTarget() == 1?"target='_blank'":"") .">";		ImageCreateTagWithCSS($image, 1000, 0, $css);		print	"</a>";		}	else if( $handle > -1 ){		$image = ImageGetByHandleCheckLanguage($handle, $langid);		ImageCreateTagWithCSS($image, 1000, 0, $css);	}}function ImageGetIcon($cid, $pos, $css){		$handle = ContentHasImageAtPosition($cid, $pos);	if( $handle > -1 && ($imglink = ImageLinkGet($cid, $pos) ) ){		$image = ImageGetByHandleAndLanguage($handle, -1);		print	"<a href=\"/". $imglink->getURL() ."\" ".($imglink->getTarget() == 1?"target='_blank'":"") .">";		ImageCreateTagWithCSS($image, 1000, 1, $css);		print	"</a>";		}	else if( $handle > -1 ){		$image = ImageGetByHandleAndLanguage($handle, -1);		ImageCreateTagWithCSS($image, 1000, 1, $css);	}}/*function ImageGet($cid, $pos, $css){	$handle = ContentHasImageAtPosition($cid, $pos);	if( $handle > -1 ){		$image = ImageGetByHandleAndLanguage($handle, -1);		return ImageCreateTagWithCSS($image, 1000, 0, $css);	}	}*/function ImagePrint($handle, $css){	if( $handle > -1 ){		$image = ImageGetByHandleAndLanguage($handle, -1);		return ImageCreateTagWithCSS($image, 1000, 0, $css);	}}function ImagePrintThumb($handle, $css){	if( $handle > -1 ){		$image = ImageGetByHandleAndLanguage($handle, -1);		return ImageCreateTagWithCSS($image, 1000, 1, $css);	}}function ImagePrintII_MEDIUM($handle, $css){		if( $handle > -1 ){		$image = ImageGetByHandleAndLanguage($handle, -1);		return ImageCreateTagWithCSSII($image, 1000, 2, $css);	}}function ImageGetCategoryID($imageid){	global $dbcnx;	$sqlstr = "SELECT ImageCategoryID FROM ImageCategoryValues WHERE ImageID = " . $imageid;	$result = @mysqli_query($dbcnx, $sqlstr);	if(mysqli_num_rows($result) == 0)		return 0;	else{		// return mysql_result($row,0,0); removed 180117 //		$row = mysqli_fetch_assoc($result);		return $row['ImageCategoryID'];	}}function EscapeImgHandle($str){	$str = strtolower($str);	$search = array('å','ä','ö','Å','Ä','Ö',' ','&','/','?','=');	$replace = array('a','a','o','a','a','o','-','x','x','x','x');	$finalstr = str_replace($search, $replace, $str);	return $finalstr;}function ImageCreateFileName($image, $mask, $icon){	$imageid = sprintf("%06d", $image->getID());	if($mask > 0 && $image->getID() <= $mask){	//print "asd";		$filename = ($icon == 1 ? 'T'  : 'I') . $imageid . "_" .$image->getLanguageID() .".". ($icon == 1 ? $image->getIconFormat() : $image->getFormat()); 	}else{			if($icon == 1)		$filename = EscapeImgHandle($image->getHandle()) ."_T" . $imageid . "_" .$image->getLanguageID() ."." . strtolower($image->getFormat());		else if($icon == 2)		$filename = EscapeImgHandle($image->getHandle()) ."_M" . $imageid . "_" .$image->getLanguageID() ."." . strtolower($image->getFormat());		else if($icon == 3)		$filename = EscapeImgHandle($image->getHandle()) ."_S" . $imageid . "_" .$image->getLanguageID() ."." . strtolower($image->getFormat());		else		$filename = EscapeImgHandle($image->getHandle()) ."_I" . $imageid . "_" .$image->getLanguageID() ."." . strtolower($image->getFormat());				//$filename = EscapeImgHandle($image->getHandle()) ."_". ($icon == 1 ? 'T'  : 'I') . $imageid . "_" .$image->getLanguageID() .".". ($icon == 1 ? strtolower($image->getIconFormat()) : strtolower($image->getFormat())); 	}	return $filename;}function ImageCreateFileNameNoExtension($image, $mask, $icon){	$imageid = sprintf("%06d", $image->getID());	if($mask > 0 && $image->getID() <= $mask){		$filename = ($icon == 1 ? 'T'  : 'I') . $imageid . "_" .$image->getLanguageID() ."."; 	}else{				if($icon == 1)		$filename = EscapeImgHandle($image->getHandle()) ."_T" . $imageid . "_" .$image->getLanguageID() ."."; 		else if($icon == 2)		$filename = EscapeImgHandle($image->getHandle()) ."_M" . $imageid . "_" .$image->getLanguageID() ."."; 		else if($icon == 3)		$filename = EscapeImgHandle($image->getHandle()) ."_S" . $imageid . "_" .$image->getLanguageID() ."."; 		else		$filename = EscapeImgHandle($image->getHandle()) ."_I" . $imageid . "_" .$image->getLanguageID() ."."; 				//$filename = EscapeImgHandle($image->getHandle()) ."_". ($icon == 1 ? 'T'  : 'I') . $imageid . "_" .$image->getLanguageID() ."."; 	}	return $filename;}function ImageCreateTag($image, $mask, $icon){	// to be developed further	if($icon)		print "<img src=\"" . IMAGEDIR . ImageCreateFileName($image, $mask, $icon) . "\" width=\"" . $image->getIconSizeX() . "\" height=\"" . $image->getIconSizeY() . "\" alt=\"" . $image->getAltText() . "\" />";	else		print "<img src=\"" . IMAGEDIR . ImageCreateFileName($image, $mask, $icon) . "\" width=\"" . $image->getSizeX() . "\" height=\"" . $image->getSizeY() . "\" alt=\"" . $image->getAltText() . "\" />";}function ImageCreateTagWithCSS($image, $mask, $icon, $class){	// to be developed further	if($icon)		print "<img class=\"".$class."\" src=\"" . IMAGEDIR . ImageCreateFileName($image, $mask, $icon) . "\" width=\"" . $image->getIconSizeX() . "\" height=\"" . $image->getIconSizeY() . "\" alt=\"" . $image->getAltText() . "\" />";	else		print "<img class=\"".$class."\" src=\"" . IMAGEDIR . ImageCreateFileName($image, $mask, $icon) . "\" width=\"" . $image->getSizeX() . "\" height=\"" . $image->getSizeY() . "\" alt=\"" . $image->getAltText() . "\" />";}function ImageCreateTagWithCSSII($image, $mask, $icon, $class){	// This creates image tag witout width and size		print '<img class="'.$class.'" src="' . IMAGEDIR . ImageCreateFileName($image, $mask, $icon) . '" alt="' . $image->getAltText() . '" />';}//function ImageGetAll();//function ImageHasCategory();//function DecodeImageFilename();// Spara bild på annat språkfunction ImageSave($image, $langid){	global $dbcnx;	if($image->getID() > 0){		$sql = "UPDATE Images SET ImageID=".$image->getID().", ImageSizeX=".$image->getSizeX().", ImageSizeY=".$image->getSizeY().", ImageIconSizeX=".$image->getIconSizeX().", ImageIconSizeY=".$image->getIconSizeY().", ImageFormat='".$image->getFormat()."', ImageIconFormat='".$image->getIconFormat()."', ImageHandle='".$image->getHandle()."', ImageLanguageID=" .$image->getLanguageID(). ", ImageAltTextID='".$image->getAltTextID()."' WHERE ImageID=". $image->getID(). " AND ImageLanguageID=" . $langid;				mysqli_query($dbcnx, $sql);	}else{		$value = TermosGetCounterValue("ImageID");		TermosSetCounterValue("ImageID", ++$value);		$image->setID($value);		$sql = IMAGE_INSERT . "VALUES (".$image->getID().",".$image->getSizeX().",".$image->getSizeY().",".$image->getIconSizeX(). ",".$image->getIconSizeY().",'".$image->getFormat()."','".$image->getIconFormat()."','" . $image->getHandle() . "', " . $image->getLanguageID() . ",'" . $image->getAltTextID() . "')"; 		mysqli_query($dbcnx, $sql);	}}function ImageDelete($imageid){	global $dbcnx;	$image = ImageGetByID($imageid);	//MTextDelete($image->getAltTextID());	print $image->getAltTextID();		$sqlstr = "DELETE FROM Images WHERE ImageID=" . $imageid;	mysqli_query($dbcnx, $sqlstr);	$sqlstr = "DELETE FROM ImageCategoryValues WHERE ImageID=" . $imageid;	mysqli_query($dbcnx, $sqlstr);}function ImageDeleteLanguage($imageid, $languageid){		global $dbcnx;	$image = ImageGetByID($imageid);	//MTextDeleteLanguage($image->getAltTextID(), $languageid);	print $image->getAltTextID();	//if($image->getLanguageID() == -1)		$sqlstr = "DELETE FROM Images WHERE ImageID=" . $imageid . " AND ImageLanguageID=" . $languageid;	mysqli_query($dbcnx, $sqlstr);	}function ImageAddCategory($imageid, $imgcatid){	global $dbcnx;	$sqlstr = "DELETE FROM ImageCategoryValues WHERE ImageID=". $imageid . " AND ImageCategoryID=". $imgcatid;	mysqli_query($dbcnx, $sqlstr);	$sqlstr = "INSERT INTO ImageCategoryValues VALUES(". $imageid . "," . $imgcatid . ")";	mysqli_query($dbcnx, $sqlstr);}function ImageGetNextInCategory($imageid, $imagecatid){	global $dbcnx;	$sql = "SELECT ImageID FROM ImageCategoryValues WHERE ImageCategoryID=" .$imagecatid . " AND ImageID > " . $imageid . " ORDER BY ImageID LIMIT 1";	$result = @mysqli_query($dbcnx, $sql);	if(mysqli_num_rows($result) > 0){				// return mysql_result($row, 0); removed 180117 //		$row = mysqli_fetch_assoc($result);		return $row['ImageID'];	}}function ImageGetPreviousInCategory($imageid, $imagecatid){	global $dbcnx;	$sql = "SELECT ImageID FROM ImageCategoryValues WHERE ImageCategoryID=" .$imagecatid . " AND ImageID < " . $imageid . " ORDER BY ImageID DESC LIMIT 1";	$result = @mysqli_query($dbcnx, $sql);	if(mysqli_num_rows($result) > 0){		// return mysql_result($row, 0); removed 180117 //		$row = mysqli_fetch_assoc($result);		return $row['ImageID'];	}}function ImageAddToContent($cid, $imghandle, $imgposition){	global $dbcnx;	$sqlstr = "DELETE FROM PageContentImages WHERE PageContentID=". $cid . " AND Position=" .$imgposition;	mysqli_query($dbcnx, $sqlstr);	$sqlstr = "INSERT INTO PageContentImages VALUES(". $cid . ",'" . $imghandle . "',".$imgposition.", 0)";	mysqli_query($dbcnx, $sqlstr);}function ImageDeleteFromContent($cid, $imghandle, $imgposition){	global $dbcnx;	$sqlstr = "DELETE FROM PageContentImages WHERE PageContentID=". $cid . " AND ImageHandle='". $imghandle . "' AND Position=" .$imgposition;	mysqli_query($dbcnx, $sqlstr);}function DisplayImage($cid, $pos){	$imagehandle = ContentHasImageAtPosition($cid, $pos);	if($imagehandle != -1){		$image = ImageGetByHandleAndLanguage($imagehandle, -1);		ImageCreateTag($image, 1000, 0);		//print $imagehandle;	}else print "no image";}function DisplayImageThumbnail($cid, $pos){	$imagehandle = ContentHasImageAtPosition($cid, $pos);	if($imagehandle != -1){		$image = ImageGetByHandleAndLanguage($imagehandle, -1);		ImageCreateTag($image, 1000, 1);		//print $imagehandle;	}else print "no image";}function ContentHasImageAtPosition($cid, $pos){	global $dbcnx;	$sql = "SELECT PageContentID, ImageHandle, Position FROM PageContentImages WHERE PageContentID=".$cid." AND Position=".$pos;	$result = @mysqli_query($dbcnx, $sql);	if(mysqli_num_rows($result) == 0)		return -1;	else{		// return mysql_result($row,0,1); removed 180117 //		$row = mysqli_fetch_assoc($result);		return $row['ImageHandle'];	}}function ContentHasImageAtPosition2($cid, $pos){	global $dbcnx;	$sql = "SELECT PageContentID, ImageHandle, Position FROM PageContentImages WHERE PageContentID=".$cid." AND Position=".$pos;	$row = @mysqli_query($dbcnx, $sql);	if(mysqli_num_rows($row) == 0)		return false;	else		return true;}function ImageHandleExists($imghandle){	global $dbcnx;	$sqlstr ="SELECT ImageHandle FROM Images WHERE ImageHandle='" . $imghandle . "'";	$row = @mysqli_query($dbcnx, $sqlstr);	if(mysqli_num_rows($row) == 0)		return false;	else		return true;}/*mysql> desc ImageCategories;+-------------------------+-------------+------+-----+---------+-------+| Field                   | Type        | Null | Key | Default | Extra |+-------------------------+-------------+------+-----+---------+-------+| ImageCategoryID         | int(11)     | YES  |     | NULL    |       || ImageCategoryNameTextID | varchar(20) | YES  |     | NULL    |       || ImageCategoryPosition   | int(11)     | YES  |     | NULL    |       |+-------------------------+-------------+------+-----+---------+-------+3 rows in set (0.01 sec)mysql> desc ImageCategoryValues;+-----------------+---------+------+-----+---------+-------+| Field           | Type    | Null | Key | Default | Extra |+-----------------+---------+------+-----+---------+-------+| ImageID         | int(11) | YES  |     | NULL    |       || ImageCategoryID | int(11) | YES  |     | NULL    |       |+-----------------+---------+------+-----+---------+-------+2 rows in set (0.01 sec)*/define("IMAGECATEGORY_SELECT", "SELECT ImageCategories.ImageCategoryID, ImageCategories.ImageCategoryNameTextID, ImageCategories.ImageCategoryPosition FROM ImageCategories");define("IMAGECATEGORY_INSERT", "INSERT INTO ImageCategories (ImageCategoryID, ImageCategoryNameTextID, ImageCategoryPosition) ");function ImageCategorySetAllFromRow($instance){	if($row = mysqli_fetch_array($instance->getDBrows()) ){		$instance->setID($row['ImageCategoryID']);		$instance->setNameTextID($row['ImageCategoryNameTextID']);		$instance->setPosition($row['ImageCategoryPosition']);		return $instance;	}}function ImageCategoryGetNext($instance){		if($row = mysqli_fetch_array($instance->getDBrows()) ){		$instance->setID($row['ImageCategoryID']);		$instance->setNameTextID($row['ImageCategoryNameTextID']);		$instance->setPosition($row['ImageCategoryPosition']);		return $instance;	}}function ImageCategoryGetAll(){	global $dbcnx;		$instance = new ImageCategory;	$sqlstr = IMAGECATEGORY_SELECT . " ORDER BY ImageCategoryPosition" ;	$row = @mysqli_query($dbcnx, $sqlstr);	$instance->setDBrows($row);	if(!$instance->getDBrows()){		exit(' Error in function ImageCategoryGetAll(); <br/> ' . mysqli_error($dbcnx) );	}	return $instance;}function ImageCategoryGetAllOrderByName(){	global $dbcnx;	$instance = new ImageCategory;	//$sqlstr = "SELECT DISTINCT I.ImageCategoryID, I.ImageCategoryNameTextID, I.ImageCategoryPosition from ImageCategories AS I, Texts AS T WHERE T.TextID=I.ImageCategoryNameTextID AND T.LanguageID = " . TermosGetCurrentLanguage() . " ORDER BY T.TextContent, I.ImageCategoryPosition" ;	$sqlstr = "SELECT DISTINCT I.ImageCategoryID, I.ImageCategoryNameTextID, I.ImageCategoryPosition from ImageCategories AS I, Texts AS T WHERE T.TextID=I.ImageCategoryNameTextID ORDER BY T.TextContent, I.ImageCategoryPosition" ;		$row = @mysqli_query($dbcnx, $sqlstr);	$instance->setDBrows($row);	if(!$instance->getDBrows()){		exit(' Error in function ImageCategoryGetAllOrderByName(); <br/> ' . mysqli_error($dbcnx) );	}	return $instance;}function ImageCategoryGetByID($imgcatid){	global $dbcnx;	$instance = new ImageCategory;	$sqlstr = IMAGECATEGORY_SELECT . " WHERE ImageCategories.ImageCategoryID=" . $imgcatid;	$row = @mysqli_query($dbcnx, $sqlstr);	$instance->setDBrows($row);	if(!$instance->getDBrows()){		exit(' Error in function ImageCategoryGetByID(); <br/> ' . $sqlstr .  '<br/>' . mysqli_error($dbcnx) );	}	$instance = ImageCategorySetAllFromRow($instance);	return $instance;}function ImageCategorySave($imagecategory){		global $dbcnx;	if($imagecategory->getID() > 0){		$sql = "UPDATE ImageCategories SET ImageCategoryNameTextID='". $imagecategory->getNameTextID(). "', ImageCategoryPosition=" . $imagecategory->getPosition() . " WHERE ImageCategoryID=" . $imagecategory->getID(); 		mysqli_query($dbcnx, $sql);	}else{		$value = TermosGetCounterValue("ImageCategoryID");		TermosSetCounterValue("ImageCategoryID", ++$value);		$imagecategory->setID($value);		$sql = IMAGECATEGORY_INSERT . "VALUES(". $imagecategory->getID() .",'" . $imagecategory->getNameTextID() . "'," . $imagecategory->getPosition() . ")";		mysqli_query($dbcnx, $sql);	}}function ImageCategoryHasImages($imagecatid){	global $dbcnx;	$sql = "SELECT ImageCategoryID FROM ImageCategoryValues WHERE ImageCategoryID=" . $imagecatid;		$row = @mysqli_query($dbcnx, $sql);	if(mysqli_num_rows($row) == 0)		return false;	else		return true;}function ImageCategoryDelete($imagecatid){	global $dbcnx;		$imagecategory = ImageCategoryGetByID($imagecatid);	MTextDelete($imagecategory->getNameTextID());	$sql = "DELETE FROM ImageCategories WHERE ImageCategoryID=" .$imagecatid;	mysqli_query($dbcnx, $sql);}?>
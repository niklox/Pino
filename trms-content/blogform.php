<?
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Content.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Content.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Image.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Image.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.SimpleImage.php';

if(isset($_REQUEST["listid"]))$listid = $_REQUEST["listid"];
if(isset($_REQUEST["imgcatid"]))$imgcatid = $_REQUEST["imgcatid"];
if(isset($_REQUEST["contentid"]))$contentid = $_REQUEST["contentid"];
if(isset($_REQUEST["currentuserid"]))$currentuserid = $_REQUEST["currentuserid"];
if(isset($_REQUEST["bloghead"]))$bloghead = $_REQUEST["bloghead"];
if(isset($_REQUEST["blogtext"]))$blogtext = $_REQUEST["blogtext"];
if(isset($_REQUEST["blogsign"]))$blogsign = $_REQUEST["blogsign"];

	$tmpid = TermosGetCounterValue("PageContentID");
	$tmpid++;
	ContentUpdateNode($tmpid, $listid);

	
	// Skapa bloggtexten	
	$mtext = MTextNewInCategory("contentTexts", $blogtext);
	MTextUpdateTextContentCopyAllLanguages($mtext);
	
	$blogtext = new ContentText;
	$blogtext->setContentID($tmpid);
	$blogtext->setTextID($mtext->getID());
	$blogtext->setPosition(1);
	ContentTextSave($blogtext);

	// Skapa blogsign
	$mtext = MTextNewInCategory("contentTexts", $blogsign);
	MTextUpdateTextContentCopyAllLanguages($mtext);
	
	$blogsign = new ContentText;
	$blogsign->setContentID($tmpid);
	$blogsign->setTextID($mtext->getID());
	$blogsign->setPosition(2);
	ContentTextSave($blogsign);
	

	$ttextid = MTextNewInCategory("contentTexts", $bloghead);
	$ctextid = MTextNewInCategory("contentTexts", "no text");
	$tagtextid = MTextNewInCategory("contentTexts", "no text");
	$ptextid = MTextNewInCategory("contentTexts", "no text");

	MTextUpdateTextContentCopyAllLanguages($ttextid);
	MTextUpdateTextContentCopyAllLanguages($ctextid);
	MTextUpdateTextContentCopyAllLanguages($tagtextid);
	MTextUpdateTextContentCopyAllLanguages($ptextid);
	
	$blogpost = new Content;
	$blogpost->setID(0);
	$blogpost->setDefaultNodeID($listid);
	$blogpost->setCreatedDate( date("Y-m-d H:i:s") );
	$blogpost->setTitleTextID($ttextid->getID());
	$blogpost->setContentTextID($ctextid->getID());
	$blogpost->setArchiveFlag(0);
	$blogpost->setAuthorID($currentuserid);
	$blogpost->setArchiveDate(date('Y-m-d H:i:s', strtotime('+10 year')));
	$blogpost->setPosition(0);
	$blogpost->setTagsTextID($tagtextid->getID());
	$blogpost->setTemplateID(BLOGPOSTTEMPLATE);
	$blogpost->setPermalinkTextID($ptextid->getID());
	$blogpost->setFlag(1);
	$blogpost->setCounter(0);
	$blogpost->setExternalID($_SERVER['REMOTE_ADDR']);
	$blogpost->setValue("");
	$blogpost->setNumericValue("");
	$blogpost->setStatus(0);
	$blogpost->setStartDate(date("Y-m-d H:i:s"));
	$blogpost->setEndDate(date("Y-m-d H:i:s", strtotime('+10 year')));

	$blogpostid = ContentSave($blogpost);

	if ( $_FILES["file"]["size"] > 0 ) {
		
		if ( $_FILES["file"]["size"] < IMAGE_MAX_FILESIZE ){
			if( $_FILES["file"]["type"] == "image/gif" || $_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/jpg" || $_FILES["file"]["type"] == "image/pjpeg" || $_FILES["file"]["type"] == "image/png") {

			$imagetmpid = TermosGetCounterValue("ImageID");
			$imagetmpid++;
			$image = new Image;

			$image->setID($imagetmpid);
			$image->setHandle("blogimage_" . $blogpostid);
			$image->setLanguageID(-1);
			
			$imagedata = getimagesize($_FILES["file"]["tmp_name"]);
			$image->setSizeX($imagedata[0]);
			$image->setSizeY($imagedata[1]);

			switch( $imagedata[2] ){
				case 1:
					$image->setFormat("gif");
					$image->setIconFormat("gif");
				break;
				case 2:
					$image->setFormat("jpeg");
					$image->setIconFormat("jpeg");
					break;
				case 3:
					$image->setFormat("png");
					$image->setIconFormat("png");
			}

			move_uploaded_file($_FILES["file"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"] . IMAGEDIR . ImageCreateFilenameNoExtension($image,IMGMASK,0) . $image->getFormat());
			
			// adjustImageSizeAndOrientation() is defined in termoscommon // 
			$filename = adjustImageSizeAndOrientation($_SERVER["DOCUMENT_ROOT"] . IMAGEDIR . ImageCreateFilenameNoExtension($image,IMGMASK,0) . $image->getFormat(), 350, 400);

			list($width_new, $height_new) = getimagesize($filename);

			$image->setSizeX($width_new);
			$image->setSizeY($height_new);

			// If make icon
			{
				$thumbnail = new SimpleImage();
				
				$thumbnail->load($_SERVER["DOCUMENT_ROOT"] . IMAGEDIR . ImageCreateFilenameNoExtension($image,IMGMASK,0) . $image->getFormat());
				if($iconwidth > 0)
					$thumbnail->resizeToWidth($iconwidth);
				else
					$thumbnail->resizeToWidth(50);
				$thumbnail->save($_SERVER["DOCUMENT_ROOT"]. IMAGEDIR . ImageCreateFilenameNoExtension($image,IMGMASK,1) . $image->getFormat());

				$image->setIconSizeX($thumbnail->getWidth());
				$image->setIconSizeY($thumbnail->getHeight());
			}

			ImageAddCategory($image->getID(), $imgcatid);
			ImageAddToContent($tmpid, $image->getHandle(), 1);

			$alttext = MTextNewInCategory("imageTexts", "Blogimage_" . $tmpid . "_" . $imagetmpid);
			MTextUpdateTextContentCopyAllLanguages($alttext);

			$image->setAltTextID($alttext->getID());

			$image->setID(0);
			ImageSave($image, 0);

			}else print MTextGet("fileTypeNotSupported");
		}else  print MTextGet("fileToLarge");
	}

	print "<script>location.href = '/page.php?cid=".$contentid."'</script>"

?>
<?
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Forms.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Forms.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Texts.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Texts.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Image.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Image.php';

if(isset($_REQUEST["formid"]))$formid = $_REQUEST["formid"]; 
if(isset($_REQUEST["returnpath"]))$returnpath = $_REQUEST["returnpath"]; 

if($form = FormGetForm($formid)){

	
	if (md5($_REQUEST['code']) == $_SESSION['key']){
	
	$imgcatid = 28;
	$formanswer = new FormAnswer;
	$forminputanswer = new FormInputAnswer;
	$forminputoption = new FormInputOption;

	$formanswer->setID(0);
	$formanswer->setFormID($formid);
	$formanswer->setUserID(0); 
	$formanswer->setTypeID(0);
	$formanswer->setDateTime( date("Y-m-d H:i:s") );
	$formanswer->setStatus(1);
	$formanswer->setIP($_SERVER["REMOTE_ADDR"]);

	$formanswerid = FormAnswerSave($formanswer);

	foreach($_REQUEST as $key => $value){
		if(substr($key, 0,2) == "n_"){

			if($key == "n_1358") $from_mail = $value;

			//print $key . " " . $value ."<br/>";

			$forminputoption = FormInputOptionGetByName($key);

			$mailcontent .= MTextGet($forminputoption->getTextID());
			$mailcontent .= ": " . $value . "\r\n";

			$forminputanswer->setFormAnswerID(TermosGetCounterValue("FormAnswer"));
			$forminputanswer->setFormID($formid);
			$forminputanswer->setFormInputID($forminputoption->getFormItemID());
			$forminputanswer->setFormInputOptionID(substr($key, 2));
			$forminputanswer->setAnswerText($value);

			FormInputAnswerSave($forminputanswer);
		}
	}

	if ( $_FILES["file"]["size"] > 0 ) {
		
		if ( $_FILES["file"]["size"] < IMAGE_MAX_FILESIZE ){
			if( $_FILES["file"]["type"] == "image/gif" || $_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/pjpeg" || $_FILES["file"]["type"] == "image/png") {

			$imagetmpid = TermosGetCounterValue("ImageID");
			$imagetmpid++;
			$image = new Image;

			$image->setID($imagetmpid);
			$image->setHandle("contactid_" . $formanswerid);
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

			ImageAddCategory($image->getID(), $imgcatid);
			
			$alttext = MTextNewInCategory("imageTexts", "contactid_" . $formanswerid);
			MTextUpdateTextContentCopyAllLanguages($alttext);

			$image->setAltTextID($alttext->getID());

			$image->setID(0);
			$imagewasuploaded = ImageSave($image, 0);

			}else print MTextGet("fileTypeNotSupported");
		}else  print MTextGet("fileToLarge");
	}

	$subject = MTextGet($form->getNameTextID());
	$from_name = "Pino kontakt";
	//$from_mail = "application@pino.se";
	
	if($imagewasuploaded > 0){
		
		$filename = ImageCreateFilenameNoExtension($image,IMGMASK,0).$image->getFormat();
		$path = $_SERVER['DOCUMENT_ROOT'].IMAGEDIR;
		
		mail_attachment($filename, $path, $form->getRecipient(), $from_mail, $from_name, "", $subject, $mailcontent);
	}else{
		
		$headers = "From: ".$from_name." <". $from_mail .">\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
		mail($form->getRecipient(), $subject, $mailcontent, $headers);
	}
	
	print	"<script>location.href='".$returnpath."';</script>";


	} else {

	print "Your controlcode is wrong! Please return and check your <a href=\"javascript:history.go(-1)\">input</a>!";
	
	}
}

function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {
    $file = $path.$filename;
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));
    $uid = md5(uniqid(time()));
    $name = basename($file);
    $header = "From: ".$from_name." <".$from_mail.">\r\n";
   // $header .= "Reply-To: ".$replyto."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-type:text/plain;  charset=UTF-8\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $header .= $message."\r\n\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use different content types here
    $header .= "Content-Transfer-Encoding: base64\r\n";
    $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
    $header .= $content."\r\n\r\n";
    $header .= "--".$uid."--";
    if (mail($mailto, $subject, "", $header)) {
       // echo "mail send ... OK"; // or use booleans here
		return 1;
    } else {
        //echo "mail send ... ERROR!";
		return 0;
    }
}




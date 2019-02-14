<?php

require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termosdefine.php';
/*
function TermosGetCurrentUserID(){

	if(isset($_COOKIE['TermosCurrentUserID'])){

		// Check if the user has a valid password set
		if($user = UserGetUserByID($_COOKIE['TermosCurrentUserID']))
		{
			if(TermosGetCurrentUserPasswd() == $user->getPassword() )
				return $_COOKIE['TermosCurrentUserID'];
			else
				return 0;
		}
	}
	else
		return 0;
}
*/
function TermosGetCurrentUserID(){
		
	if(isset($_SESSION['TermosCurrentUserID'])){
	
		// Check if the user has a valid password set
		if($user = UserGetUserByID($_SESSION['TermosCurrentUserID']))
		{
			if(TermosGetCurrentUserPasswd() == $user->getPassword() )
				return $_SESSION['TermosCurrentUserID'];
			else
				return 0;
		}
	}
	else
		return 0;
}
/*
function TermosGetCurrentUserPasswd(){

	if(isset($_COOKIE['TermosCurrentUserPasswd']))
		return $_COOKIE['TermosCurrentUserPasswd'];
	else
		return 0;
}
*/
function TermosGetCurrentUserPasswd(){

	if(isset($_SESSION['TermosCurrentUserPasswd']))
		return $_SESSION['TermosCurrentUserPasswd'];
	else
		return 0;
}

function TermosGetCurrentLanguage(){

	if(isset($_COOKIE['TermosCurrentLanguage']))
		return $_COOKIE['TermosCurrentLanguage'];
	else
		return TermosGetDefaultLanguage();
}

function TermosGetDefaultLanguage(){

	global $dbcnx;
	$sql = "SELECT ParameterValue FROM Parameters WHERE ParameterName = 'defaultLanguage'";
	$result = @mysqli_query($dbcnx,$sql);

	if(!$result){
		exit(' Error in function TermosGetDefaultLanguage()' . mysqli_error($result) );
	}

	// return mysql_result($result, 0); replaced 180115 ///
	$row = mysqli_fetch_assoc($result);
	return $row['ParameterValue'];
}

function TermosGetCounterValue($name){
	global $dbcnx;
	
	$sql = "SELECT ParameterValue FROM Parameters WHERE ParameterName = '" . $name . "Counter'";
	$result = @mysqli_query($dbcnx, $sql);

	if(!$result){
		exit(' Error in function TermosGetCounterValue('. $name .') ' . mysqli_error($dbcnx) );
	}

	// return mysql_result($result, 0);
	$row = mysqli_fetch_assoc($result);
	return $row['ParameterValue'];
}

function TermosGetParameterValue($name){
	global $dbcnx;
	
	$sql = "SELECT ParameterValue FROM Parameters WHERE ParameterName = '" . $name . "'";
	$result = @mysqli_query($dbcnx, $sql);

	if(!$result){
		exit(' Error in function TermosGetParameterValue('. $name .') ' . mysqli_error($dbcnx) );
	}

	// return mysql_result($result, 0);
	$row = mysqli_fetch_assoc($result);
	return $row['ParameterValue'];
}

function TermosSetCounterValue($name, $value){

	global $dbcnx;
	$sql = "UPDATE Parameters SET ParameterValue = '". $value . "' WHERE ParameterName = '" . $name . "Counter'";
	mysqli_query($dbcnx, $sql);

}

function PrintGenericSelect($sql, $cssclass, $id, $string, $flag){
	global $dbcnx;

	print	"<select id=\"".$id."\" name=\"".$id."\" class=\"".$cssclass."\">\n";
	print	"<option value=\"0\">".$string."</value>\n";

	$result = @mysqli_query($dbcnx, $sql);

	while($row = mysqli_fetch_array($result)){
		if($flag == $row[0])
		print	"<option value=\"". $row[0] ."\" selected>". $row[1] ."</option>";
		else
		print	"<option value=\"". $row[0] ."\">". $row[1] ."</option>";
	}

	print	"</select>\n";

}

function CountryGetName($countryid){
	
	global $dbcnx;
	
	if($countryid > 0 && $countryid != "" && $countryid != "NULL"){

	$sql = "SELECT short_name FROM country_t  WHERE country_id = " . $countryid;
	
	$result = @mysqli_query($dbcnx, $sql);

	if(!$result){
		exit(' Error in function CountryGetName('. $countryid .') ' . mysqli_error($dbcnx) );
	}

	//return mysql_result($result, 0);
	$row = mysqli_fetch_assoc($result);
	return $row['short_name'];
	}else return "no country id";
	 
}
/*
function addEmail($emailaddress){
	global $dbcnx;

	$sql ="SELECT * FROM NewsLetterEmail WHERE Email = '". $emailaddress ."'";
	$row = @mysqli_query($dbcnx,$sql);

	if(mysql_num_rows($row) > 0)
		return $emailaddress . " has already been registered";
	else{
		$sql ="INSERT INTO NewsLetterEmail VALUES('".$emailaddress."','".date('Y-m-d H:i:s')."')";
		mysql_query($sql);
		return $emailaddress . " has now been added to our mailinglist. Thank you!";
	}

}
*/
function _mirrorImage ( $imgsrc){
    $width = imagesx ( $imgsrc );
    $height = imagesy ( $imgsrc );

    $src_x = $width -1;
    $src_y = 0;
    $src_width = -$width;
    $src_height = $height;

    $imgdest = imagecreatetruecolor ( $width, $height );

    if ( imagecopyresampled ( $imgdest, $imgsrc, 0, 0, $src_x, $src_y, $width, $height, $src_width, $src_height ) )
    {
        return $imgdest;
    }

    return $imgsrc;
}


function adjustImageSizeAndOrientation($full_filename, $maxwidth, $maxheight){

	$exif = exif_read_data($full_filename);
    if($exif && isset($exif['Orientation'])) {
        $orientation = $exif['Orientation'];
        if($orientation != 1){

          $img = imagecreatefromjpeg($full_filename);

            $mirror = false;
            $deg    = 0;

            switch ($orientation) {
              case 2:
                $mirror = true;
                break;
              case 3:
                $deg = 180;
                break;
              case 4:
                $deg = 180;
                $mirror = true;
                break;
              case 5:
                $deg = 270;
                $mirror = true;
                break;
              case 6:
                $deg = 270;
                break;
              case 7:
                $deg = 90;
                $mirror = true;
                break;
              case 8:
                $deg = 90;
                break;
            }
            if ($deg) $img = imagerotate($img, $deg, 0);
			if ($mirror) $img = _mirrorImage($img);
			imagejpeg($img, $full_filename, 95);
			}
		 }

		$filename = $full_filename;

		// Get new dimensions
		list($width_orig, $height_orig) = getimagesize($filename);
		$ratio_orig = $width_orig/$height_orig;

		// Portrait
		if($ratio_orig <= 1 && $height_orig > $maxheight){ $height = $maxheight; $x = $height/$height_orig; $width = $width_orig * $x;}

		// Landscape
		else if($ratio_orig >= 1 && $width_orig > $maxwidth){ $width = $maxwidth; $x = $width/$width_orig; $height = $height_orig * $x;}

		// No need to resize
		else{$width = $width_orig; $height = $height_orig;}

		// Resample
		$image_p = imagecreatetruecolor($width, $height);
		$image = imagecreatefromjpeg($filename);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

		// Output
		imagejpeg($image_p, $full_filename, 100);
		return $full_filename;
}

	/**
	 *	parse_request(string $_SERVER["REQUEST_URI"]);
	 *	All unmatched request defaults to homepage
	 *	A request like http://www.thesite.com/the-file.html | Matches a content with the permalink "the-file" under its defaultnode
	 *	A request like http://www.thesite.com/any-node/the-file.html | Matches a content with the permalink "the-file" under the node "any-node"
	 *	A request with http://www.thesite.com/any-node | Matches the first content under the node "any-node"
	 *	A request with http://www.thesite.com/index.php?cid=12&pid=122
	 */

function parse_request($req_uri){

	/**
	 * Check if it is a root request to / or a request to the index.php file
	 */

	if( strstr($req_uri, ".php")  || strstr($req_uri, "/?") || $req_uri == "/"){ //

		if(isset($_REQUEST["cid"]) && !isset($_REQUEST["nid"])){
			$cid = $_REQUEST["cid"];
			$content = ContentGetByID($cid);
			$nid = $content->getDefaultNodeID();
		}
		else if(isset($_REQUEST["cid"]) && isset($_REQUEST["nid"])){
			$cid = $_REQUEST["cid"];
			$nid = $_REQUEST["nid"];
		}
		else if(!isset($_REQUEST["cid"]) && isset($_REQUEST["nid"])){
			$nid = $_REQUEST["nid"];
			$cid = ContentGetFirstContentIDInNode($nid);
		}
		else {
			$cid = HOMEPAGE;
			$nid = HOMENODE;
		}

		$request[0] = $cid;
		$request[1] = $nid;
		return $request;
	}

	/**
	 * Check if it is a permalink identifier request that can be matched to a content
	 */

	else if( strstr($req_uri, ".html" ) ){

		// First trim the leading slash
		$str = explode("/", $req_uri, 2);
		$reqstr = explode("/",$str[1]);

		// changed 180115 //////////////////
		for($i=0; $i<count($reqstr); $i++){
			if(!strlen($reqstr[$i]))break;

			if(strstr($reqstr[$i], ".html"))
				$content_slug = explode(".", $reqstr[$i]);
			else
				$node_slug = $reqstr[$i];
		}


		$cid = ContentGetIDByPermalink($content_slug[0]);
		if($cid != 0){
			$content = ContentGetByID($cid);
			$nid = NodeGetIDByPermalink($node_slug);

			if($nid == 0)
			$nid = $content->getDefaultNodeID();
		}
		else{
			$cid = HOMEPAGE;
			$nid = HOMENODE;
		}

		$request[0] = $cid;
		$request[1] = $nid;
		return $request;
	}

	/**
	 * Check if it is a request that can be matched against any node
	 */

	else{

		// First trim the leading slash
		$str = explode("/", $req_uri, 2);
		$reqstr = explode("/",$str[1]);

		// changed 180115 //////////////////
		for($i=0; $i<count($reqstr); $i++){
			$node_slug = $reqstr[$i];
		}

		$nid = NodeGetIDByPermalink($node_slug);
		if($nid == 0){
			$cid = HOMEPAGE;
			$nid = HOMENODE;
		}else{
			$cid = ContentGetFirstContentIDInNode($nid);
		}

		$request[0] = $cid;
		$request[1] = $nid;
		return $request;
	}
}

function get_template($template){
	global $thiscontent, $currentnode, $currentuser, $nodepath;
	if ( file_exists( TEMPLATES . $template))
		require_once(TEMPLATES . $template);
	else
		print $template . " can not be found in directory " . TEMPLATES;
}

function get_path_template($template){
	global $thiscontent, $currentnode, $currentuser;
	if ( file_exists($_SERVER['DOCUMENT_ROOT'] . $template))
		require_once($_SERVER['DOCUMENT_ROOT'] . $template);
	else
		print $template . " can not be found in directory " . TEMPLATEPATH;
}

function is_mobile($useragent){

	if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|crios|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
		return true;
	else 
		return false;
}



/**
*
*  PHP validate email
*  http://www.webtoolkit.info/
*
**/
 
function isValidEmail($email){
	
	$pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
	return preg_match($pattern, $email);
}


function super_urlencode($string){
	
	$string = trim($string);
	$string = strtolower($string);
	$string = str_replace(array('Å', 'Ä', 'Ö', 'å', 'ä', 'ö', ' ', '\'', '"'), array('a', 'a', 'o', 'a', 'a', 'o', '-', '',''), $string);
	//$string = ereg_replace("[^a-z0-9-]", "", $string); removed 180120 undefined function
	//$string = ereg_replace("[-]+", "-", $string);
	
	$string = preg_replace("[^a-z0-9-]", "", $string);
//	$string = preg_replace("[-]+", "-", $string); removed 180121 seems to work without it

	return $string;
}




?>

<?php
	
session_start();
define("TERMOS_PATH", dirname(__FILE__) . "/");
define("TERMOS_CONTENT", "trms-content");
require(TERMOS_PATH . "trmshead.php");
define("TEMPLATES", TERMOS_PATH . TERMOS_CONTENT . CURRENT_THEME);


//DBcnx();

//$dbcnx = mysqli_connect("localhost", "Pino", "PNd=34JW=", "Pino");


//$result = mysqli_query($dbcnx, "SELECT * FROM Texts;");

//$con = ContentGetByID(526);

//print $con->getTitleTextID() . '<br/>';
//print $con->getCreatedDate() . '<br/>';
//print $con->getTemplateID() . '<br/>';
//print $con->getTagsTextID() . '<br/>';
/*
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo "id: " . $row["TextID"]. " - Name: " . $row["TextContent"]. "<br>";
    }
} else {
    echo "0 results";
}
*/

require(TEMPLATES . "/page.php");


?>



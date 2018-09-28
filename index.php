<?php
	
session_start();
define("TERMOS_PATH", dirname(__FILE__) . "/");
define("TERMOS_CONTENT", "trms-content");
require(TERMOS_PATH . "trmshead.php");
define("TEMPLATES", TERMOS_PATH . TERMOS_CONTENT . CURRENT_THEME);

//define("TEMPLATES", TERMOS_PATH . TERMOS_CONTENT . "/pino");

require(TEMPLATES . "/page.php");

?>



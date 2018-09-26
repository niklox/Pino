<?php

/**
 *  DB connection
 *
 */
 
 
function DBcnx(){
 	global $dbcnx;
 	
	$dbcnx = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
	mysqli_set_charset($dbcnx,"utf8mb4");

	// Check connection
	if (mysqli_connect_errno()){
  		echo "Failed to connect to MySQL: " . mysqli_connect_error();
  		exit();
	}

}

?>
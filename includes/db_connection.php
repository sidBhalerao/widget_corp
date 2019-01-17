<?php
	//making all the database variable constant.
	define("DB_SERVER", "localhost");
	define("DB_USER", "root" );
	define("DB_PASS", "");
	define("DB_NAME", "widget_cms");
	//1. Create a Database connection
	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	//echo "Database connected successfully...";
	// test if Connection occured.
	if(mysqli_connect_errno()){
		die("Database connection failed.." . 
			mysqli_connect_errno() . 
			" (" . mysqli_connect_errno() . ")"  );
	}
?>
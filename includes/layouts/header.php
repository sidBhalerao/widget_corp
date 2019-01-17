<?php 
	if(!isset($layout_contex)){
	$layout_contex = "public"; 
}?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title> Widget Corp <?php if($layout_contex == "admin"){ echo "Admin" ; } ?></title>
<link rel="stylesheet" type="text/css" href="stylesheets/public.css">
</head>

<body>
	<div id="header">
		<h1> Widget Corp <?php if($layout_contex == "admin"){ echo "Admin" ; } ?> </h1>
	</div>
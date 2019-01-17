<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php") ?>
<?php require_once("../includes/functions.php") ?>
<?php require_once("../includes/validation_function.php"); ?>

<?php 
	if(isset($_POST['submit'])){

	$menu_name = mysql_prep($_POST["menu_name"]); //Escape all string using 'mysql_prep()' function. 
	$position = (int) $_POST["position"];
	$visible = (int) $_POST["visible"];

	//validations
	$required_fields = array("menu_name", "position", "visible");
	validate_presences($required_fields);

	$field_with_max_lengths = array("menu_name" => 30);
	validate_max_lengths($field_with_max_lengths);

	if(!empty($errors)){
		$_SESSION["errors"] = $errors;
		redirect_to("new_subject.php");
	}
	//2. Perform a query...
	$query = "INSERT INTO subject1(menu_name,position, visible) 
			  values('{$menu_name}','{$position}','{$visible}')";
	$result = mysqli_query($connection, $query);
	
	if($result){
		//success
		$_SESSION["message"] = "Subject Created..";
		redirect_to("manage_content.php");
	}else{
		//Failure
		$_SESSION["message"] = "subject creation failed..";
		redirect_to("new_subject.php");
	}
	}else {
		//This is probably a GET request.
		redirect_to("new_subject.php");
	}
?>

<?php
 	if(isset($connection)){ mysqli_close($connection); }
?>

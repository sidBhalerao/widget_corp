<?php  require_once("../includes/session.php") ?>
<?php  require_once("../includes/db_connection.php") ?>
<?php  require_once("../includes/functions.php") ?>
<?php  require_once("../includes/validation_function.php") ?>

<?php 
	$username = "";
	if(isset($_POST['submit'])){
	//Process the form	
	//validations
	$required_fields = array("username", "password");
	validate_presences($required_fields);

	if(empty($errors)){
		//Attempt login
		$username = $_POST["username"];
		$password = $_POST["password"];
		//redirect_to("admin.php"); 
	
		$found_admin = attempt_login($username, $password);
		if($found_admin){
			//success
			//Mark admin as logged In.
			//It is secure to use _SESSION[], instead of _COOCKIE[]. Because 'session' shows only ID and 'coockie' show whole username. So do not use _COOCKIE[].
			$_SESSION["admin_id"] = $found_admin["id"]; 
			$_SESSION["username"] = $found_admin["username"]; 
			redirect_to("admin.php");
		}else{
			//Failure
			$_SESSION["message"] = "Username/Password not found.";
		}

	}
}else{
	//This is probably a GET request.
} // end: if(isset($_POST['submit']))
?>

<?php $layout_contex = "admin"; ?>
<?php  include("../includes/layouts/header.php") ?>

<div id="main">
	<div id="navigation">
		&nbsp;
	</div>
	<div id="page">
		<?php echo message(); ?>
		<?php echo form_errors($errors); ?>

		<h2>Admin Login </h2>
		<form action="login.php" method="post">
			<p>Username:
				<input type="text" name="username" value="<?php echo htmlentities($username);?>" />
			</p>
			<p>Password:
				<input type="password" name="password" value="" />
			</p>
			<input type="submit" name="submit" value="Submit" />
		</form>
			
	</div>
</div>
<?php include("../includes/layouts/footer.php") ?>

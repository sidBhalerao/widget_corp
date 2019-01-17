<?php  require_once("../includes/session.php") ?>
<?php  require_once("../includes/functions.php") ?>

<?php 
	//Method 1 : Simple logout
	//session_start();
	$_SESSION["admin_id"] = null;
	$_SESSION["username"] = null;
	redirect_to(login.php);
?>



<?php 
	/*Method 2 : Destroy the session
	  //assumes nothing else in session to keep

	  session_start();
	  $_SESSION = array();
	  if(isset($_COOKIE[session_name()])){
		setcoockie(session_name(), '', time()-42000, '/');
	  }
	  session_destroy();
	  redirect_to("login.php");
	*/
?>
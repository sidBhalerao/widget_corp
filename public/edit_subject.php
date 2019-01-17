<?php  require_once("../includes/session.php") ?>
<?php  require_once("../includes/db_connection.php") ?>
<?php  require_once("../includes/functions.php") ?>
<?php require_once("../includes/validation_function.php"); ?>

<?php find_selected_page(); ?>
<?php 
	if(!$current_subject){
		//subject ID is missing or Invalid or
		//subject couldn't be found in database
		redirect_to("manage_content.php");
	}
?>

<?php 
	if(isset($_POST['submit'])){
	//validations
	$required_fields = array("menu_name", "position", "visible");
	validate_presences($required_fields);

	$field_with_max_lengths = array("menu_name" => 30);
	validate_max_lengths($field_with_max_lengths);

	if(empty($errors)){
	
	//Perform Update
	$id = $current_subject["id"];	
	$menu_name = mysql_prep($_POST["menu_name"]); //Escape all string using 'mysql_prep()' function. 
	$position = (int) $_POST["position"];
	$visible = (int) $_POST["visible"];
	//2. Perform a query...
	$query = "UPDATE subject1 SET menu_name='{$menu_name}' , position= {$position} , visible= {$visible} WHERE id = {$id} LIMIT 1 "; 
	$result = mysqli_query($connection, $query);
	
	if($result && mysqli_affected_rows($connection) >= 0){
		//success
		$_SESSION["message"] = "Subject Updated..";
		redirect_to("manage_content.php");
	}else{
		//Failure
		$message = "subject updation failed..";
		
	}
	}
	}else {
		//This is probably a GET request.		
	}//end of : if(isset($_POST['submit']))  loop.
?>
<?php $layout_contex = "admin"; ?>
<?php  include("../includes/layouts/header.php") ?>

<div id="main">
	<div id="navigation">
		<?php echo naviation($current_subject, $current_page); ?>
	</div>
	<div id="page">
		<?php //$message is just a variable, doesn't use the SESSION
			if(!empty($message)){
				echo "<div class=\"message\">" . htmlentities($message) . "</div>";
			} 
		?>
		<?php echo form_errors($errors); ?>

		<h2>Edit Subject: <?php echo htmlentities($current_subject["menu_name"]); ?> </h2>
		<form action="edit_subject.php?subject=<?php echo urlencode($current_subject["id"]); ?>" method="post">
			<p>Menu Name:
				<input type="text" name="menu_name" value="<?php echo htmlentities($current_subject["menu_name"]); ?>" />
			</p>
			<p>Position:
				<select name="position">
				<?php
					$Subject_set = find_all_subjects(false);
					$subject_count = mysqli_num_rows($Subject_set);
					for ($count=1; $count <= ($subject_count); $count++) { 
						echo "<option value=\"{$count}\"";
						if($current_subject["position"] == $count){
							echo "selected";
					}
						echo ">{$count}</option>";
					}
				?>
				</select>
			</p>
			<p>Visible:
				<input type="radio" name="visible" value="0" <?php 
					if($current_subject["visible"]==0) { echo "checked"; } ?> /> NO
				&nbsp
				<input type="radio" name="visible" value="1" <?php 
					if($current_subject["visible"]==1) { echo "checked"; } ?> /> YES
			</p>
			<input type="submit" name="submit" value="Edit Subject" />
		</form>
		<br />
		<a href="manage_content.php">Cancel</a>	
		&nbsp;
		&nbsp;
		<a href="delete_subject.php?subject=<?php echo urlencode($current_subject["id"]); ?>" onclick= "return confirm('Are you sure?');">Delete Subject</a>	
	</div>
</div>                                                                                                                    
<?php 
	include("../includes/layouts/footer.php") 
?>
<?php  require_once("../includes/session.php") ?>
<?php  require_once("../includes/db_connection.php") ?>
<?php  require_once("../includes/functions.php") ?>
<?php require_once("../includes/validation_function.php"); ?>

<?php find_selected_page(); ?>
<?php 
	if(!$current_subject){
	//	subject ID is missing or Invalid or
	//	subject couldn't be found in database
		redirect_to("manage_content.php");
	}
?>
<?php 
	if(isset($_POST['submit'])){

	$id = $current_page["id"];
	$menu_name = mysql_prep($_POST["menu_name"]);
	$position = (int) $_POST["position"];
	$visible = (int) $_POST["visible"];
	//be sure to escape the content
	$content = mysql_prep($_POST["content"]);
	
	//validations
	$required_fields = array("menu_name", "position", "visible","content");
	validate_presences($required_fields);

	$field_with_max_lengths = array("menu_name" => 30);
	validate_max_lengths($field_with_max_lengths);

	if(empty($errors)){
		//Perform update
	$id = $current_page["id"];	
	$menu_name = mysql_prep($_POST["menu_name"]); //Escape all string using 'mysql_prep()' function. 
	$position = (int) $_POST["position"];
	$visible = (int) $_POST["visible"];
	$content = mysql_prep($_POST["content"]);
		//2. Perform a query...
	$query = "UPDATE pages SET menu_name='{$menu_name}', position='{$position}',visible='{$visible}', content='{$content}' WHERE id ={$id} LIMIT 1"; 
	$result = mysqli_query($connection, $query);
	
	if($result && mysqli_affected_rows($connection)==1){
		//success
		$_SESSION["message"] = "Page Updated..";
		redirect_to("manage_content.php?page={$id}");
	}else{
		//Failure
		$_SESSION["message"] = "Page creation failed..";
	}
	}
	}else {
		//This is probably a GET request.
	}
?>

<?php $layout_contex = "admin"; ?>
<?php  include("../includes/layouts/header.php") ?>

<div id="main">
	<div id="navigation">
		<?php echo naviation($current_subject, $current_page); ?>
	</div>
	<div id="page">
		<?php echo message(); ?>
		<?php echo form_errors($errors); ?>

		<h2>Edit Page: <?php echo htmlentities($current_page["menu_name"]); ?> </h2>
		<form action="edit_subject.php?subject=<?php echo urlencode($current_page["id"]); ?>" method="post">
			<p>Menu Name:
				<input type="text" name="menu_name" value="<?php echo htmlentities($current_page["menu_name"]); ?>" />
			</p>
			<p>Position: 
				<select name="position">
				<?php
					$page_set = find_pages_for_subject($current_page["subject_id"]);
					$page_count = mysqli_num_rows($page_set);
					for ($count=1; $count <= ($page_count); $count++) { 
						echo "<option value=\"{$count}\"";
						if($current_page["position"] == $count){
							echo "selected";
					}
						echo ">{$count}</option>";
					}
				?>
				</select>
			</p>
			<p>Visible:
				<input type="radio" name="visible" value="0" <?php 
					if($current_page["visible"]==0) { echo "checked"; } ?> /> NO
				&nbsp
				<input type="radio" name="visible" value="1" <?php 
					if($current_page["visible"]==1) { echo "checked"; } ?> /> YES
			</p>
			<p>Content:<br />
				<textarea name="content" rows="20" cols="80"><?php echo htmlentities($current_page["content"]); ?></textarea>
			</p>
			<input type="submit" name="submit" value="Edit Page" />
		</form>
		<br />
		<a href="manage_content.php">Cancel</a>	
		&nbsp;
		&nbsp;
		<a href="delete_page.php?subject=<?php echo urlencode($current_page["id"]); ?>" onclick= "return confirm('Are you sure?');">Delete Page</a>	
	</div>
</div>                                                                                                                    
<?php 
	include("../includes/layouts/footer.php")
?>
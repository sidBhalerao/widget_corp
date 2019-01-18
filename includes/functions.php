<?php

function redirect_to($new_location){
	header("Location: ". $new_location);
	exit;
}

function mysql_prep($string){
	global $connection;

	$escaped_string =  mysqli_real_escape_string($connection, $string);
	return $escaped_string;
}
function confirm_query($result_set){
	if(!$result_set){
		die("Database query failed.");
	}
}

function form_errors($errors = array()){
	$output = "";
	if(!empty($errors)){
		$output .="<div class=\"error\">";
		$output .="Please fix the following errors: ";
		$output .="<ul>";
		foreach ($errors as $key => $error) {
			$output .= "<li>";
			$output .= htmlentities($error) ;
			$output .= "</li>";
		}
		$output .= "</ul>";
		$output .= "</div>";
	}
	return $output; 
}

function find_all_subjects($public=true){
	global $connection;

	$query  = "SELECT * ";
	$query .="FROM subject1 "; 
	if($public){
		$query .= "WHERE visible = 1 ";
	}
	$query .= "ORDER BY position ASC ";
	$subject_set = mysqli_query($connection, $query);
	confirm_query($subject_set);
	return $subject_set;
}

function find_pages_for_subjects($subject_id, $public=true){
	global $connection;
	$safe_subject_id = mysqli_real_escape_string($connection, $subject_id);

	$query  = "SELECT * ";
	$query .= "FROM pages ";
	$query .= "WHERE subject_id = {$safe_subject_id} ";
	if($public){
		$query .= "AND visible = 1 ";
	}
	$query .= "ORDER BY position ASC ";
	$page_set = mysqli_query($connection, $query);
	confirm_query($page_set);
	return $page_set;
}

function find_all_admins(){
	global $connection;

	$query  = "SELECT * ";
	$query .="FROM admins "; 
	$query .= "ORDER BY username ASC ";
	$admin_set = mysqli_query($connection, $query);
	confirm_query($admin_set);
	return $admin_set;
}

function find_subject_by_id($subject_id, $public=true){
	global $connection;

	$safe_subject_id = mysqli_real_escape_string($connection, $subject_id);

	$query = "SELECT * ";
	$query .="FROM subject1 ";
	$query .="WHERE id = {$safe_subject_id} ";
	if($public){ 
		$query .= "AND visible = 1 ";
	}
	$query .= "LIMIT 1 ";
	$subject_set = mysqli_query($connection, $query);
	confirm_query($subject_set);
	if($subject = mysqli_fetch_assoc($subject_set)){
		return $subject;	
	}else{
		return null;
	}
	
}

function find_admin_by_id($admin_id){
	global $connection;

	$safe_admin_id = mysqli_real_escape_string($connection, $admin_id);

	$query = "SELECT * ";
	$query .="FROM admins ";
	$query .="WHERE id = {$safe_admin_id} ";
	$query .= "LIMIT 1 ";
	$admin_set = mysqli_query($connection, $query);
	confirm_query($admin_set);
	if($admin = mysqli_fetch_assoc($admin_set)){
		return $admin;	
	}else{
		return null;
	}
	
}
function find_admin_by_username($username){
	global $connection;

	$safe_username = mysqli_real_escape_string($connection, $username);

	$query = "SELECT * ";
	$query .="FROM admins ";
	$query .="WHERE username = '{$safe_username}' ";
	$query .= "LIMIT 1 ";
	$admin_set = mysqli_query($connection, $query);
	confirm_query($admin_set);
	if($admin = mysqli_fetch_assoc($admin_set)){
		return $admin;	
	}else{
		return null;
	}
	
}

function find_page_by_id($page_id, $public=true){
	global $connection;

	$safe_page_id = mysqli_real_escape_string($connection, $page_id);

	$query = "SELECT * ";
	$query .="FROM pages ";
	$query .="WHERE id = {$safe_page_id} ";
	if($public){ 
		$query .= "AND visible = 1 ";
	}
	$query .= "LIMIT 1 ";
	$page_set = mysqli_query($connection, $query);
	confirm_query($page_set);
	if($page = mysqli_fetch_assoc($page_set)){
		return $page;	
	}else{
		return null;
	}
	
}
function find_default_page_for_subject($subject_id){
	$page_set = find_pages_for_subjects($subject_id);
	if($first_page = mysqli_fetch_assoc($page_set)){
		return $first_page;	
	}else{
		return null;
	}
}

function find_selected_page($public=false){
	global $current_subject;
	global $current_page;

	if(isset($_GET["subject"])){
		$current_subject = find_subject_by_id($_GET["subject"], $public);
		if($current_subject && $public){
		$current_page = find_default_page_for_subject($current_subject["id"]); 
	}else{
		$current_page = null;
	} 
	}elseif (isset($_GET["page"])) {
		$current_subject = null ;
		$current_page = find_page_by_id($_GET["page"], $public);
	}else{
		$current_subject = null;
		$current_page = null;
	}
}

//Navigation takes 2 arguments.
//The current subject array or null. 
//The current page array or null.
function naviation($subject_array, $page_array){
	$output = "<ul class=\"subject1\">";
	$subject_set = find_all_subjects(false); 
	while ($subject = mysqli_fetch_assoc($subject_set)) {  
		$output .= "<li";
			if($subject_array && $subject["id"] == $subject_array["id"] ){
				$output .= " class=\"selected\"";
			}
		$output .= ">"; 
		$output .= "<a href=\"manage_content.php?subject= ";	
		$output .= urlencode($subject["id"]); 
		$output .= "\">";
		$output .= htmlentities($subject["menu_name"]) ; 
		$output .= "</a>";
			
		$page_set = find_pages_for_subjects($subject["id"],false);
			
		$output .= "<ul class=\"pages\">";
		while ($page = mysqli_fetch_assoc($page_set)) {   
		$output .= "<li";
		if($page_array && $page["id"] == $page_array["id"]){
		$output .= " class=\"selected\"";
		}
		$output .= ">"; 
		$output .= "<a href=\"manage_content.php?page= ";
		$output .= urlencode($page["id"]);
		$output .= "\">";
		$output .= htmlentities($page["menu_name"]) ; 
		$output .= "</a></li>";
		}
					
		mysqli_free_result($page_set); 
		$output .= "</ul></li>";
		}
		mysqli_free_result($subject_set); 
	    $output .= "</ul>";
	    return $output; 
}

function public_naviation($subject_array, $page_array){
	$output = "<ul class=\"subject1\">";
	
	$subject_set = find_all_subjects(); 

	while ($subject = mysqli_fetch_assoc($subject_set)) {  
		
	$output .= "<li";
		if($subject_array && $subject["id"] == $subject_array["id"] ){
		$output .= " class=\"selected\"";
		}
		$output .= ">"; 
		$output .= "<a href=\"index.php?subject= ";	
		$output .= urlencode($subject["id"]); 
		$output .= "\">";
		$output .= htmlentities($subject["menu_name"]) ; 
		$output .= "</a>";
			
		if($subject_array["id"] == $subject["id"] || $page_array["subject_id"] == $subject["id"]){	
		$page_set = find_pages_for_subjects($subject["id"]);
		$output .= "<ul class=\"pages\">";
		while ($page = mysqli_fetch_assoc($page_set)) {   
		$output .= "<li";
		if($page_array && $page["id"] == $page_array["id"]){
		$output .= " class=\"selected\"";
		}
		$output .= ">"; 
		$output .= "<a href=\"index.php?page= ";
		$output .= urlencode($page["id"]);
		$output .= "\">";
		$output .= htmlentities($page["menu_name"]) ; 
		$output .= "</a></li>";
		}
		$output .= "</ul>";		
		mysqli_free_result($page_set);
		} 
		$output .= "</li>"; //end of subject </li>
		}
		mysqli_free_result($subject_set); 
	    $output .= "</ul>";
	    return $output; 
}

function password_encrypt($password){
	//Tells PHP to use Blowfish using $2y with a cost 10(means, it runs 10 times.), diffrnt cost generate different result.
	// $hash_format = "$2y$10$";
	// // Blowfish character should be 22 or more otherwise existing hash_passwrd and crypted password could not be same. 
	// $salt_length = 22 ;
	// $salt = generate_salt($salt_length);
	// $format_and_salt = $hash_format . $salt ;
	// $hash = crypt($password, $format_and_salt);
	//$hash = password_hash($password, PASSWORD_DEFAULT);
	return $password;
}
// function generate_salt($length){
// 	//Not 100% unique, not 100% random, but good enough for a salt
// 	//MD5 return 35 characters
// 	$unique_random_string = md5(uniqid(mt_rand(), true));

// 	//valid characters for a salt are [a-zA-Z0-9./]
// 	$base64_string = base64_encode($unique_random_string);

// 	//But not '+' which is valid in base64 encoding
// 	$modified_base64_string = str_replace('+', '.', $base64_string);

// 	//Truncate string to the correct length
// 	$salt = substr($modified_base64_string, 0, $length);

// 	return $salt;
// }
function password_check($password, $existing_hash){
	//existing hash contains format and salt at start
	// var_dump($password); 
	// echo "<br />";
	// echo $existing_hash;
	// echo "<br />"; 
	// echo "<br />";

	// $hash1 = crypt($password, $existing_hash);
	// var_dump($hash1);
	// echo "<br />";
	// echo "hashed: ".$hash1 . "<br />";
	// echo "sub : <br />" ;
	// $hash2 = substr_replace($hash1 ,"",-10) . "<br />" ;
	// var_dump($hash2);

	// $hash3 = rtrim($hash2) . "<br />" ; 
	// echo  ($hash3) ;
	// var_dump($hash3);
	// $is_correct = password_verify($password, $existing_hash);
	// if($is_correct){
	// 	return true;
	// 	// echo "<br />";
	// 	// echo $existing_hash;
	// 	// echo  $hash2 ;		
	// }else{
	// 	return false;
		
	// }
	return true;
}

function attempt_login($username, $password){
	$admin = find_admin_by_username($username);
	if($admin){
		//admin found. Now check the password
		// echo $password . "<br />";
		if(password_check($password, $admin["hashed_password"])){
			//Password matches.
			return $admin;
		}else{
			//Password does not match.
			return false;
		}
	}else{
		//admin not found.
		return false;
	}
}
function logged_in(){
	return isset($_SESSION['admin_id']);
}
function confirm_logged_in(){
	if(!logged_in()){
		redirect_to("login.php");
	}
}
?>
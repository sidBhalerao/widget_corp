<?php

	$errors = array();

	function fieldname_as_text($fieldname){
		$fieldname = str_replace("_", " ", $fieldname);
		$fieldname = ucfirst($fieldname);
		return $fieldname;
	}

	//presence
	//use trim() so empty spaces don't count.
	//function has_presence($value){
	//return isset($value) || $value != "";	
	//}
		
	function validate_presences($required_fields){
		global $errors;
		//expects an associate array.
		foreach ($required_fields as $field ) {
			$value = trim($_POST[$field]);
			//echo $value;	
			if(empty($value)){
				$errors[$field] = fieldname_as_text($field) . " Can't be blank";  //ucfirst will uppercase the first letter.
			}
		}
	}	
		
	function has_max_length($value, $max){
	return strlen($value) <= $max;		
	}

	function validate_max_lengths($field_with_max_lengths){
		global $errors;
		//expects an associate array.
		foreach ($field_with_max_lengths as $field => $max) {
			$value = trim($_POST[$field]);
			if(!has_max_length($value, $max)){
				$errors[$field] = fieldname_as_text($field) . " is too long";
			}
		}
	}
	//Inclusion in a set	
	function has_inclusion_in($value, $set){
	return in_array($value, $set);
	}

	
?>

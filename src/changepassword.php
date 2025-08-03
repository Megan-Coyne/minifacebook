<?php
	require "session_auth.php";
	require "database.php";
	$username= $_REQUEST["username"];
	$newpassword = $_POST['newpassword'];
	$nocsrftoken = $_POST["nocsrftoken"];
	if(!isset($nocsrftoken) or ($nocsrftoken!= $_SESSION['nocsrftoken'])){
		echo "<script>alert('Cross-site request forgery is detected!');</script>";
		header("Refresh:0; url=logout.php");
		die();
	}
	if (isset($username) AND isset($newpassword)) {
		if(strlen($newpassword) < 8){
			echo "<h4> Error: password too short";
			exit();
		}elseif(!preg_match("/^(?=.*[!@$%^&])(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).+$/",$newpassword)){
			echo "<script>alert('Error: password does not follow complexity rules. Password must have at least 8 characters with 1 special symbol !@$%^& 1 number, 1 lowercase, and 1 UPPERCASE');</script>";
			header("Refresh:0; url=changepasswordform.php");
			exit();
		}elseif (changepassword($username,$newpassword)) {
			echo "<h4>The new password has been set.</h4>";
			// echo "DEBUG:changepassword.php ->Got: username=$username;newpassword=$newpassword\n<br>";
		}else{
			echo "<h4>Error: Cannot change the password.</h4>";
		}
	}else{
		echo "No provided username/password to change.";
		exit();
	}
?>

<a href="index.php">Home</a> | <a href="logout.php">Logout</a>
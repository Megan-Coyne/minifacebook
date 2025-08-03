<!--add new user to the database -->

<?php
	require "database.php";
	$username= $_REQUEST["username"];
	$password = $_POST['password'];
	if (isset($username) AND isset($password)) {
		if(empty($username) OR empty($password)){
			echo "<h4> Error: one or more fields empty";
			exit();
		//add preg_match for username to ensure format
		}elseif(!preg_match("/^[a-zA-z0-9,_%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{3,}$/",$username)){
			echo "<script>alert('username must be a valid email');</script>";
			header("Refresh:0 url=registrationform.php");
			exit();
		}elseif(strlen($password) < 8){
			echo "<script>alert('Error: password too short');</script>";
			header("Refresh:0 url=registrationform.php");
			exit();
		}elseif(!preg_match("/^(?=.*[!@$%^&])(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).+$/",$password)){
			echo "<script>alert('Error: password does not follow complexity rules. Password must have at least 8 characters with 1 special symbol !@$%^& 1 number, 1 lowercase, and 1 UPPERCASE');</script>";
			header("Refresh:0 url=registrationform.php");
			exit();
		}elseif(addnewuser($username,$password)) {
			echo "<h4>The new user has been created.</h4>";
			// echo "DEBUG:addnewuser.php ->Got: username=$username;password=$password\n<br>";
		}else{
			echo "<script>alert('Error: unspecified error, cannot create new user');</script>";
			header("Refresh:0 url=registrationfrom.php");
		}
	}else{
		echo "No provided username or password.";
		exit();
	}


?>
<a href="index.php">Home</a> | <a href="logout.php">Logout</a>

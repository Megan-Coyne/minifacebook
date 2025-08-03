<?php
	$lifetime = 15* 60;
	$path = "/";
	$domain = ".minifacebook.com";
	$secure = TRUE;
	$httponly = TRUE; 
	session_set_cookie_params($lifetime,$path, $domain, $secure, $httponly);
	session_start(); 
	$mysqli = new mysqli("localhost","team_12", "secad", "facebook_users"); 
	if($mysqli->connect_errno){
		printf("database connection error: %s\n", $mysqli->connect_error);
		exit();
	}  
	if(isset($_POST["username"]) and isset($_POST["password"]) ){ 
		if (securechecklogin($_POST["username"],$_POST["password"])) {
			// echo "<script>alert('secure check login working.');</script>";
			$_SESSION["logged"] = TRUE;
			// echo "DEBUG: Logged = " . (isset($_SESSION["logged"]) ? $_SESSION["logged"] : "NOT SET") . "<br>";
			$_SESSION["username"] = $_POST["username"];	
			$_SESSION["browser"] = $_SERVER["HTTP_USER_AGENT"];	
		}else{
			echo "<script>alert('Invalid username/password');</script>";
			session_destroy();
			header("Refresh:0; url=form.php");
			die();
		}
	}
	if (!isset($_SESSION["logged"]) or $_SESSION["logged"] != TRUE) {
		echo "<script>alert('you have not logged in. Please login first');</script>";
		header("Refresh:0 url=form.php");
		die();
	}
	if ($_SESSION["browser"] != $_SERVER["HTTP_USER_AGENT"]	){
		echo "<script>alert('Session hijacking is detected!');</script>";
		header("Refresh:0; url=form.php");
		die();
	}
  		function securechecklogin($username, $password) {
		global $mysqli;
		$prepared_sql = "SELECT * FROM users WHERE username= ? AND password=md5(?);";
		if(!$stmt = $mysqli->prepare($prepared_sql)) {
			echo "Prepared Statement Error";
		}
		$stmt->bind_param("ss", $username,$password);
		if(!$stmt -> execute()) echo "Execute Error";
		if(!$stmt->store_result()) echo "store_result Error";
		$result = $stmt;
		if($result->num_rows == 1){
			return TRUE;
		}
		return FALSE;
  	}
?>
<h2> Welcome <?php echo htmlentities($_SESSION["username"]); ?> !</h2> 
<a href="logout.php">Logout</a>
<a href="changepasswordform.php">Change password</a>
<a href="postform.php">Post</a>
<a href="view.php">View posts and comment</a>
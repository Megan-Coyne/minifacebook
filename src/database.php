<?php
	
	$lifetime = 15* 60;
	$path = "/";
	$domain = "";
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
		$username = sanitize_input($_POST["username"]); 
		$password = sanitize_input($_POST["password"]);
		if (changepassword($username,$password)) { // if breaks, change variables back to $_POST["variable"]
			$_SESSION['logged'] = TRUE;
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
  		
  		function changepassword($username, $newpassword) {
		global $mysqli;
		$prepared_sql = "UPDATE users SET password=md5(?) WHERE username= ?;";
		// echo "DEBUG>prepared_sql = $prepared_sql\n";
		if(!$stmt = $mysqli->prepare($prepared_sql)){
			return FALSE;
		}
		$stmt->bind_param("ss", $newpassword,$username);
		if(!$stmt->execute()){
			return FALSE;
		}
		return TRUE;
  	}

  	function addnewuser($username, $newpassword) {
		global $mysqli;
        $prepared_sql = "INSERT INTO users (username, password, superuser) VALUES (?, md5(?), 0);";
		// echo "DEBUG>prepared_sql = $prepared_sql\n";
		if(!$stmt = $mysqli->prepare($prepared_sql)){
			return FALSE;
		}
		$stmt->bind_param("ss", $username,$newpassword,);
		if(!$stmt->execute()) return FALSE;
		return TRUE;
  	}

	  function save_post($username, $unused_file_path, $caption) {
		global $mysqli;
		$prepared_sql = "INSERT INTO posts (owner, caption) VALUES (?, ?);";
		// echo "DEBUG: Prepared SQL = $prepared_sql<br>";
	
		if (!$stmt = $mysqli->prepare($prepared_sql)) {
			// echo "DEBUG: Prepare failed - " . $mysqli->error . "<br>";
			return FALSE;
		}
	
		$stmt->bind_param("ss", $username, $caption);
		
		if (!$stmt->execute()) {
			// echo "DEBUG: Execute failed - " . $stmt->error . "<br>";
			return FALSE;
		}
	
		// echo "DEBUG: Post inserted successfully<br>";
		return TRUE;
	}
	
	function get_posts() {
		global $mysqli;
		$result = $mysqli->query("SELECT id, owner, caption, created_at FROM posts ORDER BY created_at DESC");
		return $result->fetch_all(MYSQLI_ASSOC);
	}

  	function sanitize_input($input) {
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		return $input;
	}
	//fucntion to check superuser for accessing special pages
?>
<h2> Welcome <?php echo htmlentities($_SESSION["username"]); ?> !</h2> 
<a href="logout.php">Logout</a>
<a href="changepasswordform.php">Change password</a>
<a href="postform.php">Post</a>
<a href="view.php">View posts and comment</a>
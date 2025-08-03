<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Superusers</title>
</head>
<body>
  <h1>Registered Superuser List</h1>

  <?php
    require 'database.php';
    require 'session_auth.php';
    
    //validate that user is superuser before access
    $user = $_SESSION['username'];

    $check_sql = "SELECT superuser FROM users WHERE username =?";
    if(!$check_stmt = $mysqli->prepare($check_sql)){
      echo "Prepared Statement error";
      exit();
    }
    $check_stmt->bind_param("s", $user);
    if(!$check_stmt->execute()){
      echo "execute fail";
      exit();
    }
    if(!$check_stmt->bind_result($superuser)){
      echo "binding failed";
      exit();
    }

    $check_stmt->fetch();
    if($superuser !=1){
      echo "<script>alert(Error: You must be a registered superuser to access this page.); </script>";
      header("Refresh:0; url=logout.php");
      exit();
    }
    $check_stmt->close();

    // just printing users, does not need to get any user input 
    $prepared_sql = "SELECT username FROM users WHERE superuser = '1';";
    //ensure that the database.php file exists and the $mysqli variable is defined there
    if(!$stmt = $mysqli->prepare($prepared_sql)){
      echo "Prepared Statement Error";
      exit();
    }

    if(!$stmt->execute()){
     echo "Execute failed ";
     exit();
    }
    
    if(!$stmt->bind_result($username)){
     echo "Binding failed ";
     exit();
    }

    //this will bind each row with the variables
    $num_rows = 0;
    while($stmt->fetch()){
      echo "<div class='user'>" . htmlentities($username) ."</div>";
      $num_rows++;
    }
    if($num_rows==0){
      echo "<h4> No superusers found";
    }
  ?>

</body>
</html>



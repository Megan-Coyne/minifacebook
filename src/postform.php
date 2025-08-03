<?php
require "session_auth.php";
$rand = bin2hex(openssl_random_pseudo_bytes(16));
$_SESSION["nocsrftoken"] = $rand;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post a Message</title>
</head>
<body>
    <h1>By Team 12</h1>
    <p>Current time: <?php echo date("Y-m-d h:i:sa"); ?></p>

    <form action="post.php" method="POST">
        <input type="hidden" name="nocsrftoken" value="<?php echo htmlentities($_SESSION['nocsrftoken']); ?>">

        <label for="caption">Message:</label>
        <input type="text" name="caption" id="caption" required><br><br>

        <input type="submit" value="Post">
    </form>

    <a href="index.php">Home</a> | <a href="logout.php">Logout</a>
</body>
</html>

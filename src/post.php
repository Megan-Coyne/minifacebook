<?php
require "session_auth.php";
require "database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_SESSION["username"];
    $caption = $_POST["caption"];
    $nocsrftoken = $_POST["nocsrftoken"];

    // echo "DEBUG: Username = $username<br>";
    // echo "DEBUG: Caption = $caption<br>";
    // echo "DEBUG: CSRF Token = $nocsrftoken<br>";

    // CSRF Check
    if (!isset($nocsrftoken) || $nocsrftoken !== $_SESSION['nocsrftoken']) {
        echo "<script>alert('Cross-site request forgery detected!');</script>";
        header("Refresh:0; url=logout.php");
        exit();
    }

    // Save the post (no photo)
    if (save_post($username, null, $caption)) {
        echo "<h4>Message posted successfully!</h4>";
    } else {
        echo "<h4>Error saving post to the database.</h4>";
    }
}

?>

<a href="index.php">Home</a> | <a href="logout.php">Logout</a>

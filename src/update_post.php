<?php
require "session_auth.php";
require "database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check CSRF token
    $nocsrftoken = $_POST["nocsrftoken"];
	if(!isset($nocsrftoken) or ($nocsrftoken!= $_SESSION['nocsrftoken'])){
		echo "<script>alert('Cross-site request forgery is detected!');</script>";
		header("Refresh:0; url=logout.php");
		die();
	}
    $post_id = intval($_POST["post_id"]);
    $username = $_SESSION["username"];
    $action = $_POST["action"];

    // Verify ownership
    $stmt = $mysqli->prepare("SELECT owner FROM posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        echo "Post not found.";
        exit();
    }
    $post = $result->fetch_assoc();
    if ($post["owner"] !== $username) {
        echo "Unauthorized access.";
        exit();
    }

    if ($action === "Update") {
        $new_caption = $_POST["caption"];
        $stmt = $mysqli->prepare("UPDATE posts SET caption = ? WHERE id = ?");
        $stmt->bind_param("si", $new_caption, $post_id);
        if ($stmt->execute()) {
            echo "<script>alert('Post updated successfully.');</script>";
        } else {
            echo "<script>alert('Failed to update post.');</script>";
        }
    } elseif ($action === "Delete") {
        $stmt = $mysqli->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $post_id);
        if ($stmt->execute()) {
            echo "<script>alert('Post deleted successfully.');</script>";
        } else {
            echo "<script>alert('Failed to delete post.');</script>";
        }
    }

    header("Refresh:0; url=view.php");
    exit();
}
?>

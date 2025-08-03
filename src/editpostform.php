<?php
require "session_auth.php";
require "database.php";

if (!isset($_GET["post_id"])) {
    echo "No post selected.";
    exit();
}

$post_id = intval($_GET["post_id"]);
$username = $_SESSION["username"];

// Fetch the post
$prepared_sql = "SELECT id, owner, caption FROM posts WHERE id = ?";
$stmt = $mysqli->prepare($prepared_sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Post not found.";
    exit();
}

$post = $result->fetch_assoc();

// Ensure user owns the post
if ($post['owner'] !== $username) {
    echo "You do not have permission to edit this post.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Post</title></head>
<body>

<?php
  require "session_auth.php";
  $rand=bin2hex(openssl_random_pseudo_bytes(16));
  $_SESSION["nocsrftoken"] =$rand;

  echo "Current time: " . date("Y-m-d h:i:sa")
?>
    <h2>Edit Your Post</h2>
    <form method="POST" action="update_post.php">
        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
        <input type="hidden" name="nocsrftoken" value="<?php echo $rand; ?>" />
        <textarea name="caption" rows="5" cols="60"><?php echo htmlentities($post['caption']); ?></textarea><br><br>
        <input type="submit" name="action" value="Update">
        <input type="submit" name="action" value="Delete" onclick="return confirm('Are you sure you want to delete this post?');">
    </form>
    <br>
    <a href="view.php">Back to Posts</a>
</body>
</html>

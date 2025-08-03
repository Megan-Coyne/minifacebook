<?php
require "session_auth.php";
require "database.php";

if (!isset($_GET["post_id"])) {
    echo "No post selected.";
    exit();
}

$post_id = intval($_GET["post_id"]);
$username = $_SESSION["username"];

// Get the post
$stmt = $mysqli->prepare("SELECT posts.id, posts.owner, posts.caption, posts.created_at FROM posts WHERE posts.id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$post_result = $stmt->get_result();

if ($post_result->num_rows === 0) {
    echo "Post not found.";
    exit();
}
$post = $post_result->fetch_assoc();

// Handle new comment submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["comment_content"])) {
    $comment_content = trim($_POST["comment_content"]);
    if (!empty($comment_content)) {
        $stmt = $mysqli->prepare("INSERT INTO comments (post_id, commenter, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $post_id, $username, $comment_content);
        $stmt->execute();
        header("Location: comment.php?post_id=" . $post_id);
        exit();
    }
}

// Fetch comments
$stmt = $mysqli->prepare("SELECT commenter, content, created_at FROM comments WHERE post_id = ? ORDER BY created_at ASC");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$comments_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head><title>Comments</title></head>
<body>
    <h2>Post by <?php echo htmlentities($post["owner"]); ?>:</h2>
    <p><strong>Caption:</strong> <?php echo htmlentities($post["caption"]); ?></p>
    <p><em>Posted at <?php echo $post["created_at"]; ?></em></p>
    <hr>

    <h3>Comments:</h3>
    <?php if ($comments_result->num_rows === 0): ?>
        <p>No comments yet. Be the first to comment!</p>
    <?php else: ?>
        <?php while ($comment = $comments_result->fetch_assoc()): ?>
            <div style="border:1px solid #ccc; padding:8px; margin-bottom:10px;">
                <strong><?php echo htmlentities($comment["commenter"]); ?></strong>:
                <p><?php echo htmlentities($comment["content"]); ?></p>
                <small><em><?php echo $comment["created_at"]; ?></em></small>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>

    <hr>
    <h3>Leave a Comment:</h3>
    <form method="POST" action="">
        <textarea name="comment_content" rows="4" cols="60" required></textarea><br><br>
        <input type="submit" value="Post Comment">
    </form>

    <br><a href="view.php">← Back to Posts</a>
</body>
</html>

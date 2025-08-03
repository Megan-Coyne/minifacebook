<?php
require "session_auth.php";
require "database.php";

$username = $_SESSION["username"];
$all_posts = get_posts(); // returns an array of ['owner', 'caption', 'created_at']

$user_posts = [];
$other_posts = [];

foreach ($all_posts as $post) {
    if ($post['owner'] === $username) {
        $user_posts[] = $post;
    } else {
        $other_posts[] = $post;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Posts</title>
</head>
<body>

    <hr>
    <h3>Your Posts</h3>
    <?php if (count($user_posts) === 0): ?>
        <p>You haven't posted anything yet.</p>
    <?php else: ?>
        <?php foreach ($user_posts as $post): ?>
            <div style="border: 1px solid black; padding: 10px; margin: 5px;">
                <strong>You</strong> at <?php echo $post['created_at']; ?><br>
                <?php echo htmlentities($post['caption']); ?><br><br>

                <!-- Edit Button -->
                <form action="editpostform.php" method="GET" style="display:inline;">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <input type="submit" value="Edit">
                </form>

                <!-- View & Comment Button -->
                <form action="comment.php" method="GET" style="display:inline;">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <input type="submit" value="View & Comment">
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <hr>
    <h3>Other Users' Posts</h3>
    <?php if (count($other_posts) === 0): ?>
        <p>No posts from other users yet.</p>
    <?php else: ?>
        <?php foreach ($other_posts as $post): ?>
            <div style="border: 1px solid gray; padding: 10px; margin: 5px;">
                <strong><?php echo htmlentities($post['owner']); ?></strong> at <?php echo $post['created_at']; ?><br>
                <?php echo htmlentities($post['caption']); ?><br><br>

                <!-- View & Comment Button -->
                <form action="comment.php" method="GET" style="display:inline;">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <input type="submit" value="View & Comment">
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>

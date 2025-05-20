<?php
// index.php
require 'config.php';

// Fetch all posts ordered by newest first
$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Simple Blog</title>
</head>
<body>
    <h1>My Simple Blog</h1>
    <a href="create.php">Create New Post</a>
    <hr>

    <?php if (count($posts) === 0): ?>
        <p>No posts yet. Be the first to <a href="create.php">write one</a>!</p>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <article>
                <h2><?= htmlspecialchars($post['title']) ?></h2>
                <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                <small>Posted on <?= htmlspecialchars($post['created_at']) ?></small>
            </article>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>

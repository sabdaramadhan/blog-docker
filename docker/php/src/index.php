<?php
// index.php

// Database connection settings
$host = 'mysql';          // Docker service name for MySQL
$db   = 'blog_db';
$user = 'bloguser';
$pass = 'blogpassword';
$charset = 'utf8mb4';

// Set up DSN and options for PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Fetch all posts ordered by newest first
    $stmt = $pdo->query("SELECT id, title, content, created_at FROM posts ORDER BY created_at DESC");
    $posts = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>My Blog</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

</head>
<body>

<div class="container mt-5">
  <h1 class="mb-4 text-center">My Blog</h1>

  <?php if (count($posts) === 0): ?>
    <p class="text-center">No posts found.</p>
  <?php else: ?>
    <div class="list-group">
      <?php foreach ($posts as $post): ?>
        <div class="list-group-item mb-3">
          <h5><?= htmlspecialchars($post['title']) ?></h5>
          <small class="text-muted"><?= htmlspecialchars($post['created_at']) ?></small>
          <p class="mt-2"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
          <a href="edit.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-warning">Edit</a>

          <!-- Delete form -->
          <form method="post" action="delete.php" onsubmit="return confirm('Are you sure you want to delete this post?');" class="d-inline">
            <input type="hidden" name="id" value="<?= (int)$post['id'] ?>">
            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
          </form>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
  <div class="mb-4 text-center">
    <a href="create.php" class="btn btn-success">Create New Post</a>
  </div>

</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

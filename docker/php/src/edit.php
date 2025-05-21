<?php
// edit.php

// Database connection settings
$host = 'mysql';
$db   = 'blog_db';
$user = 'bloguser';
$pass = 'blogpassword';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}

// Get post ID from GET parameter
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid post ID.");
}
$id = (int)$_GET['id'];

// Initialize variables
$title = '';
$content = '';
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    // Validate
    if ($title === '') {
        $errors[] = "Title is required.";
    }
    if ($content === '') {
        $errors[] = "Content is required.";
    }

    if (empty($errors)) {
        // Update the post
        $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $stmt->execute([$title, $content, $id]);

        // Redirect back to index.php
        header("Location: index.php");
        exit;
    }
} else {
    // Load existing post data for the form
    $stmt = $pdo->prepare("SELECT title, content FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch();

    if (!$post) {
        die("Post not found.");
    }

    $title = $post['title'];
    $content = $post['content'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Post</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<div class="container mt-5">
  <h1 class="mb-4">Edit Post</h1>

  <?php if ($errors): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($errors as $error): ?>
          <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" action="edit.php?id=<?= $id ?>" novalidate>
    <div class="mb-3">
      <label for="title" class="form-label">Title</label>
      <input 
        type="text" 
        class="form-control" 
        id="title" 
        name="title" 
        value="<?= htmlspecialchars($title) ?>" 
        required
      >
    </div>

    <div class="mb-3">
      <label for="content" class="form-label">Content</label>
      <textarea 
        class="form-control" 
        id="content" 
        name="content" 
        rows="6" 
        required
      ><?= htmlspecialchars($content) ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Update Post</button>
    <a href="index.php" class="btn btn-secondary ms-2">Cancel</a>
  </form>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
// create.php

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

$title = '';
$content = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    // Validate inputs
    if ($title === '') {
        $errors[] = "Title is required.";
    }
    if ($content === '') {
        $errors[] = "Content is required.";
    }

    if (empty($errors)) {
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);

            $stmt = $pdo->prepare("INSERT INTO posts (title, content, created_at) VALUES (?, ?, NOW())");
            $stmt->execute([$title, $content]);

            // Redirect to index.php after successful insert
            header("Location: index.php");
            exit;

        } catch (PDOException $e) {
            $errors[] = "Database error: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Create New Post</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<div class="container mt-5">
  <h1 class="mb-4">Create New Post</h1>

  <?php if ($errors): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($errors as $error): ?>
          <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" action="create.php" novalidate>
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

    <button type="submit" class="btn btn-primary">Create Post</button>
    <a href="index.php" class="btn btn-secondary ms-2">Cancel</a>
  </form>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
// create.php
require 'config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title === '' || $content === '') {
        $error = "Please fill in both title and content.";
    } else {
        // Insert new post safely using prepared statement
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$title, $content]);
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create New Post</title>
</head>
<body>
    <h1>Create New Post</h1>
    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="create.php">
        <label>Title:<br>
            <input type="text" name="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
        </label><br><br>

        <label>Content:<br>
            <textarea name="content" rows="10" cols="50" required><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
        </label><br><br>

        <button type="submit">Publish</button>
    </form>

    <p><a href="index.php">Back to blog</a></p>
</body>
</html>
 
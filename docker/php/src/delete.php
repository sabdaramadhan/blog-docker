<?php
// delete.php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    // Database connection settings
    $host = 'mysql'; // usually 'localhost' if not using Docker
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

        // Prepare and execute delete query
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$id]);

        // Redirect back to index.php after deletion
        header("Location: index.php");
        exit;

    } catch (PDOException $e) {
        die("Error deleting post: " . htmlspecialchars($e->getMessage()));
    }
} else {
    // Invalid access
    header("Location: index.php");
    exit;
}
?>
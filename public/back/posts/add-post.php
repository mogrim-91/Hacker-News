<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

// Check if user got here properly.
if (!isset($_SESSION['loggedIn'])) {
    redirect('/public/front/users/gui-ls-login.php');
}

// Check if forms are set and sanitize.
if (isset($_POST['title'], $_POST['article'], $_POST['link'])) {
    $title = trim(filter_var($_POST['title'], FILTER_SANITIZE_STRING));
    $article = trim(filter_var($_POST['article'], FILTER_SANITIZE_SPECIAL_CHARS));
    $link = trim(filter_var($_POST['link'], FILTER_SANITIZE_STRING));

    if (empty($title) || empty($article) || empty($link)) {
        echo 'Fill in all the fields, please.';
        exit();
    }

    $userId = $_SESSION['loggedIn']['id'];
    $createdAt = date('Ymd H:i:s');

    // Insert into SQLite database.
    $statement = $pdo->prepare('INSERT INTO posts (title, description, link, created_at, user_id)
    VALUES (:title, :description, :link, :created_at, :user_id)');

    $statement->bindParam(':title', $title, PDO::PARAM_STR);
    $statement->bindParam(':description', $article, PDO::PARAM_STR);
    $statement->bindParam(':link', $link, PDO::PARAM_STR);
    $statement->bindParam(':user_id', $userId, PDO::PARAM_STR);
    $statement->bindParam(':created_at', $createdAt, PDO::PARAM_STR);
    $statement->execute();
}
redirect('/../../public/index.php');

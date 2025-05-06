<?php

require_once '../config/db.php';

require_once '../migration/01_category.php';

require_once '../migration/02_books.php';



if (!$_SESSION['role'] === 'admin') {
    header('HTTP/1.0 403 Forbidden');
    echo "Доступ запрещён. Только администраторы могут добавлять книги.";
    exit;
}


$message = '';
$title = $author = $description = '';
$created_at = date('Y-m-d');
$category_id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $category_id = $_POST['category'];
    $description = trim($_POST['description']);
    $created_at = $_POST['created_at'];

    if ($title === '' || $author === '' || $category_id === '' || $description === '' || $created_at === '') {
        $message = 'Пожалуйста, заполните все поля.';
    } elseif (!preg_match('/^[a-zA-ZА-Яа-я0-9\s]+$/u', $title)) {
        $message = 'Название книги содержит недопустимые символы.';
    } elseif (!strtotime($created_at)) {
        $message = 'Некорректная дата.';
    } else {
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM books
            WHERE title = ? AND author = ? AND category_id = ? AND description = ? AND created_at = ?
        ");
        $stmt->execute([$title, $author, $category_id, $description, $created_at]);

        if ($stmt->fetchColumn() > 0) {
            $message = 'Книга с такими данными уже существует.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO books (title, author, category_id, description, created_at)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([$title, $author, $category_id, $description, $created_at]);
                $message = 'Книга успешно добавлена!';
                $title = $author = $description = '';
                $category_id = '';
                $created_at = date('Y-m-d');
            } catch (PDOException $e) {
                $message = 'Ошибка при добавлении книги.';
            }
        }
    }
}

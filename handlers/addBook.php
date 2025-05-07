<?php

require_once '../config/db.php';

require_once '../migration/01_category.php';

require_once '../migration/02_books.php';


/**
 * Проверяет роль пользователя.
 * Только администратор может добавлять книги.
 */
if (!$_SESSION['role'] === 'admin') {
    header('HTTP/1.0 403 Forbidden');
    echo "Доступ запрещён. Только администраторы могут добавлять книги.";
    exit;
}

// Инициализация переменных
$message = '';
$title = $author = $description = '';
$created_at = date('Y-m-d');
$category_id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /**
     * Получает данные из формы.
     * @var string $title Название книги.
     * @var string $author Автор книги.
     * @var int $category_id ID категории книги.
     * @var string $description Описание книги.
     * @var string $created_at Дата создания книги.
     */
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $category_id = $_POST['category'];
    $description = trim($_POST['description']);
    $created_at = $_POST['created_at'];

     // Проверка на заполненность всех полей
    if ($title === '' || $author === '' || $category_id === '' || $description === '' || $created_at === '') {
        $message = 'Пожалуйста, заполните все поля.';

    // Проверка на допустимые символы в названии книги
    } elseif (!preg_match('/^[a-zA-ZА-Яа-я0-9\s]+$/u', $title)) {
        $message = 'Название книги содержит недопустимые символы.';

    // Проверка корректности дат
    } elseif (!strtotime($created_at)) {
        $message = 'Некорректная дата.';

    // Проверка на дублирование книги
    } else {
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM books
            WHERE title = ? AND author = ? AND category_id = ? AND description = ? AND created_at = ?
        ");
        $stmt->execute([$title, $author, $category_id, $description, $created_at]);

        if ($stmt->fetchColumn() > 0) {
            $message = 'Книга с такими данными уже существует.';
        } else {
                /**
                 * Выполняет добавление книги в базу данных.
                 */
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

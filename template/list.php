<?php

require_once '../config/db.php';


$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;

/**
 * проверка корректности ID книги
 * @var int $id ID книги, переданный через GET-параметр.
 */

if (!$id || !is_numeric($id)) {
    echo "Некорректный ID книги.";
    exit;
}


try {
    /**
     * запрос для получения информации о книге
     * @var PDOStatement $stmt Результат выполнения SQL-запроса.
     */
    $stmt = $pdo->prepare("
        SELECT books.*, categories.name as category_name
        FROM books
        JOIN categories ON books.category_id = categories.id
        WHERE books.id = ?
    ");
    //передача ID книги
    $stmt->execute([$id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);


    if (!$book) {
        echo "Книга не найдена.";
        exit;
    }
} catch (PDOException $e) {
    echo "Ошибка при загрузке книги: ";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<h2><?= htmlspecialchars($book['title']) ?></h2>
<p><strong>Автор:</strong> <?= htmlspecialchars($book['author']) ?></p>
<p><strong>Категория:</strong> <?= htmlspecialchars($book['category_name']) ?></p>
<p><strong>Описание:</strong> <?= htmlspecialchars($book['description']) ?></p>
<p><strong>Дата добавления:</strong> <?= htmlspecialchars($book['created_at']) ?></p>

<a href="index.php?route=index">Назад</a>

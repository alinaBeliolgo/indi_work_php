<?php 

require_once '../config/db.php';

// Проверка роли пользователя (только администратор может удалять книги)
if (!$_SESSION['role'] === 'admin') {
    header('HTTP/1.0 403 Forbidden');
    echo "Доступ запрещён. Только администраторы могут удалять книги.";
    exit;
}

//удаление книги из базы данных по id, если корркектный id
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
    $stmt->execute([$id]);
    
    if ($stmt->execute()) {
        header('Location: index.php?route=index');
        exit;
    } else {
        echo "Ошибка при удалении книги.";
    }
};
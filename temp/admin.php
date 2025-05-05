<?php


session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php?route=login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
</head>
<body>
    <h1>Добро пожаловать, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
    <ul>
        <li><a href="index.php?route=add_event">Добавить событие</a></li>
        <li><a href="index.php?route=manage_users">Управление пользователями</a></li>
        <li><a href="index.php?route=logout">Выйти</a></li>
    </ul>
</body>
</html>
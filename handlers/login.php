<?php

require_once '../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /**
     * Получает данные из формы авторизации.
     */
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    /**
     * Получает данные пользователя.
     */
    $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Проверка, найден ли пользователь и совпадает ли пароль
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        header('Location: index.php?route=index');
        exit;
    } else {
        $error = 'Неверное имя пользователя или пароль';
    }
}

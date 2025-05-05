<?php

require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    if (empty($username) || empty($password) || empty($confirmPassword)) {
        $error = 'Пожалуйста, заполните все поля.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Пароли не совпадают.';
    } else {
        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hashedPassword]);

            header('Location: /login.php');
            exit;
        } catch (PDOException $e) {
            $error = 'Ошибка регистрации: имя пользователя уже занято.';
        }
    }
}

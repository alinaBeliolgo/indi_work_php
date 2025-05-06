<?php

require_once '../config/db.php';

$error = '';

// Получение данных из формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    //проверка еслт заполнены все поля
    if ($username === '' || $email === '' || $password === '') {
        $error = 'Пожалуйста, заполните все поля';
    } else {
        //если имя пользователя или email уже существуют в базе данных
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $error = 'Пользователь с таким именем или email уже существует';
        } else {
            //хъеширование пароля
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user';

            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $success = $stmt->execute([$username, $email, $hashedPassword, $role]);

           //сохранение данных в сессии
            if ($success) {
                $_SESSION['user'] = $username;
                $_SESSION['role'] = $role;
                $_SESSION['user_id'] = $pdo->lastInsertId();

                header('Location: index.php?route=index');
                exit;
            } else {
                $error = 'Ошибка при регистрации. Попробуйте ещё раз.';
            }
        }
    }
}

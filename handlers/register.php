<?php

require_once '../config/db.php';

$error = '';

/**
 *Получает данные из формы регистрации.
 * @var string $username Имя пользователя.
 * @var string $email Электронная почта пользователя.
 * @var string $password Пароль пользователя.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $email === '' || $password === '') {
        $error = 'Пожалуйста, заполните все поля';
    } else {
        /**
         * Проверяет, существует ли пользователь с таким именем или email.
         */
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $error = 'Пользователь с таким именем или email уже существует';
        } else {
            //хъеширование пароля
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user';
            
            /**
             * Выполняет добавление нового пользователя в базу данных.
             */
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $success = $stmt->execute([$username, $email, $hashedPassword, $role]);

        
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

<?php

require_once '../config/db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);

    if (empty($username) || empty($newPassword) || empty($confirmPassword)) {
        $error = 'Пожалуйста, заполните все поля.';
    } elseif ($newPassword !== $confirmPassword) {
        $error = 'Пароли не совпадают.';
    } else {
        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
            $stmt->execute([$hashedPassword, $username]);

            if ($stmt->rowCount() > 0) {
                $success = 'Пароль успешно обновлён.';
            } else {
                $error = 'Пользователь не найден.';
            }
        } catch (PDOException $e) {
            $error = 'Ошибка базы данных.';
        }
    }
}

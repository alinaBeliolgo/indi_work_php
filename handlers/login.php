<?php

require_once '../config/db.php';


session_start();

$route = $_GET['route'] ?? '';

if ($route === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = 'Пожалуйста, заполните все поля.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header('Location: index.php');
                exit;
            } else {
                $error = 'Неверное имя пользователя или пароль.';
            }
        } catch (PDOException $e) {
            $error = 'Ошибка базы данных. Попробуйте позже.';
        }
    }
} elseif (!isset($_SESSION['user_id']) && $route !== 'login') {
    header('Location: index.php?route=login');
    exit;
}

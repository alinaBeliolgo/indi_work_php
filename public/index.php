<?php


session_start();

require_once '../config/db.php';

$route = $_GET['route'] ?? 'index';

$template = '../template/layout.php';

// Маршруты, доступные без авторизации
$publicRoutes = ['login', 'register', 'reset-password'];

// Если пользователь авторизован и пытается зайти на публичный маршрут, 
// перенаправляем на главную
if (!empty($_SESSION['user']) && in_array($route, $publicRoutes)) {
    header('Location: index.php?route=index');
    exit;
}

// Если пользователь не авторизован и пытается зайти на закрытый маршрут, 
// перенаправляем на страницу вход
if (empty($_SESSION['user']) && !in_array($route, $publicRoutes)) {
    header('Location: index.php?route=login');
    exit;
}

switch ($route) {
    case 'index':
        $content = '../template/index.php';
        break;

    case 'add':
        // Добавление книги (только для администратора)
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            exit('Доступ запрещён');
        }
        require_once '../handlers/addBook.php';
        $content = '../template/add.php';
        break;

    case 'list':
        $content = '../template/list.php';
        break;

    case 'delete':
        // Удаление книги (только для администратора)
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            exit('Доступ запрещён');
        }
        $content = '../handlers/delete.php';
        break;

    case 'login':
        // Страница входа
        require_once '../handlers/login.php';
        $content = '../template/login.php';
        break;


    case 'register':
        // Страница регистрации
        require_once '../handlers/register.php';
        $content = '../template/register.php';
        break;

    case 'logout':
        // Выход из системы
        session_destroy();
        header('Location: index.php?route=login');
        exit;

    default:
        http_response_code(404);
        $content = '../template/404.php';
        break;
}

require_once $template;

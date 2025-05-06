<?php
session_start();
require_once '../config/db.php';

$route = $_GET['route'] ?? 'index';
$template = '../temp/layout.php';

$publicRoutes = ['login', 'register', 'reset-password'];

if (!empty($_SESSION['user']) && in_array($route, $publicRoutes)) {
    header('Location: index.php?route=index');
    exit;
}

if (empty($_SESSION['user']) && !in_array($route, $publicRoutes)) {
    header('Location: index.php?route=login');
    exit;
}

switch ($route) {
    case 'index':
        $content = '../temp/index.php';
        break;

    case 'add':
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            exit('Доступ запрещён');
        }
        require_once '../handlers/addBook.php';
        $content = '../temp/add.php';
        break;

    case 'list':
        $content = '../temp/list.php';
        break;

    case 'delete':
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            exit('Доступ запрещён');
        }
        $content = '../handlers/delete.php';
        break;

    case 'login':
        require_once '../handlers/login.php';
        $content = '../temp/login.php';
        break;


    case 'register':
        require_once '../handlers/register.php';
        $content = '../temp/register.php';
        break;

    case 'logout':
        session_destroy();
        header('Location: index.php?route=login');
        exit;

    default:
        http_response_code(404);
        $content = '../temp/404.php';
        break;
}

require_once $template;

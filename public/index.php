<?php

require_once '../config/db.php';

$route = $_GET['route'] ?? 'index';

$template = '../temp/layout.php';



switch ($route) {
    case 'index':
        $content = '../temp/index.php';
        break;
    case 'login':
        $content = '../temp/login.php';
        break;
    case 'register':
        $content = '../temp/register.php';
        break;
        //надо добавить 
    case 'reset_password':
        $content = '../temp/reset_password.php';
        break;
    case 'admin':
        $content = '../temp/admin.php';
        break;
    case 'add_event':
        $content = '../temp/add_event.php';
        break;
    case 'search':
        $content = '../temp/search.php';
        break;
    case 'user_events':
        $content = '../handlers/user_events.php';
        break;
    case 'list':
        $content = '../temp/list.php';
        break;
    case 'delete':
        $content = '../handlers/delete.php';
        break;
    default:
        http_response_code(404);
        $content = '../temp/404.php';
        break;
}


require_once $template;
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'События'; ?></title>
</head>
<body>
    <header>
        <h1><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Добро пожаловать'; ?></h1>
        <nav>
            <a href="index.php?route=index">Главная</a> |
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="index.php?route=add">Добавить событие</a> |
                <a href="index.php?route=list">Список событий</a> |
                <a href="index.php?route=logout">Выход</a>
            <?php else: ?>
                <a href="index.php?route=login">Вход</a> |
                <a href="index.php?route=register">Регистрация</a> |
                <a href="index.php?route=reset_password">Сброс пароля</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <?php
        if (file_exists($content)) {
            require_once $content;
        } else {
            echo "<p>Ошибка: файл шаблона не найден.</p>";
        }
        ?>
    </main>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Система управления событиями. Все права защищены.</p>
    </footer>
</body>
</html>
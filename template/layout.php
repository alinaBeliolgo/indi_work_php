<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style/style.css">
    <title>Книжный</title>
</head>
<body>
    <header>

        <?php if (!empty($_SESSION['user'])): ?>
            <h1>Книжный</h1>
            <a href="index.php?route=index">Главная</a>
            <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="index.php?route=add">Добавить книгу</a>
            <?php endif; ?>
            <a href="index.php?route=logout">Выйти (<?php echo htmlspecialchars($_SESSION['user']) ?>)</a>
        <?php endif; ?>
    </header>

    <main>
        <?php require $content; ?>
    </main>

    <footer>
        <p>&copy; Библиотека <?= date('Y') ?></p>
    </footer>
</body>
</html>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
</head>
<body>
    <h1>Вход</h1>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" action="index.php?route=login">
        <label for="username">Имя пользователя:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Войти</button>
    </form>
    <p>У вас нет аккаунтa? <a href="index.php?route=register">Зарегистрироваться</a></p>
</body>
</html>

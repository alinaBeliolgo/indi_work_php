<!--Шаблон страницы регистрации-->

<!DOCTYPE html>
<html>
<head>
    <title>Регистрация</title>
</head>
<body>
    <h1>Регистрация</h1>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post" action="index.php?route=register">
        <label>Имя пользователя: <input type="text" name="username" required></label><br>
        <label>Email: <input type="email" name="email" required></label><br>
        <label>Пароль: <input type="password" name="password" required></label><br>
        <button type="submit">Зарегистрироваться</button>
    </form>
    <p>Уже есть аккаунт? <a href="index.php?route=login">Войдите</a></p>
</body>
</html>

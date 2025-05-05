<?php

require_once '../handlers/register.php';

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
</head>
<body>
    <h1>Регистрация</h1>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="username">Имя пользователя:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <label for="password">Пароль:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <label for="confirm_password">Подтвердите пароль:</label>
        <input type="password" name="confirm_password" id="confirm_password" required>
        <br>
        <button type="submit">Зарегистрироваться</button>
    </form>
</body>
</html>
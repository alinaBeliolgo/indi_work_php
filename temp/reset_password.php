<?php

require_once '../handlers/reset_password.php';

?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Восстановление пароля</title>
</head>
<body>
    <h1>Восстановление пароля</h1>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php elseif (!empty($success)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="username">Имя пользователя:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <label for="new_password">Новый пароль:</label>
        <input type="password" name="new_password" id="new_password" required>
        <br>
        <label for="confirm_password">Подтвердите пароль:</label>
        <input type="password" name="confirm_password" id="confirm_password" required>
        <br>
        <button type="submit">Сбросить пароль</button>
    </form>
</body>
</html>
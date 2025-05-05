<?php 

require_once '../config/db.php';
require_once '../database/eventsbase.db';
require_once '../vendor/autoload.php';

$redis = new Predis\Client();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title']);
    $organizer = trim($_POST['organizer']);
    $location = trim($_POST['location']);
    $description = trim($_POST['description']);
    $event_date = $_POST['event_date'];

    if (empty($title) || empty($organizer) || empty($location) || empty($description) || $event_date === '') {
        $message = 'Пожалуйста, заполните все поля.';

    } elseif (!preg_match('/^[a-zA-ZА-Яа-я0-9\s]+$/u', $title)) {
        $message = 'Название события содержит недопустимые символы.';

    } elseif (!strtotime($event_date)) {
        $message = 'Некорректная дата события.';

    } else {
        $stmt = $pdo->prepare("
            SELECT COUNT(*) 
            FROM events 
            WHERE title = ? 
              AND organizer = ? 
              AND location = ? 
              AND description = ? 
              AND event_date = ?
        ");

        $stmt->execute([$title, $organizer, $location, $description, $event_date]);
        if ($stmt->fetchColumn() > 0) {
            $message = 'Событие с такими данными уже существует.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO events (title, organizer, location, description, event_date) VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([$title, $organizer, $location, $description, $event_date]);
                $message = 'Событие успешно добавлено!';

                $redis->del('events_list'); 

                $title = $organizer = $description = $location = '';
                $event_date = date('Y-m-d');
            } catch (PDOException $e) {
                $message = 'Ошибка при добавлении события.';
            }
        }
    }
} else {
    $title = $organizer = $description = $location = '';
    $event_date = date('Y-m-d');
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить событие</title>
</head>
<body>
    <h1>Добавить событие</h1>

    <?php if ($message !== ''): ?>
        <p style="color: <?= strpos($message, 'успешно') !== false ? 'green' : 'red' ?>;">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="post">
        <label>Название:
            <input type="text" name="title" required value="<?= htmlspecialchars($title) ?>">
        </label><br>

        <label>Организатор:
            <input type="text" name="organizer" required value="<?= htmlspecialchars($organizer) ?>">
        </label><br>

        <label>Место проведения:
            <input type="text" name="location" required value="<?= htmlspecialchars($location) ?>">
        </label><br>

        <label>Описание:
            <textarea name="description"><?= htmlspecialchars($description) ?></textarea>
        </label><br>

        <label>Дата события:
            <input type="date" name="event_date" value="<?= htmlspecialchars($event_date) ?>">
        </label><br>

        <button type="submit">Сохранить</button>
    </form>

    <p><a href="../public/index.php">Назад</a></p>
</body>
</html>
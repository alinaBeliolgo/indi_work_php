<?php
require_once '../config/db.php';
require_once '../vendor/autoload.php';
require '../handlers/search.php';

$author = isset($_GET['author']) ? trim($_GET['author']) : '';
?>

<h1>Поиск событий по названию</h1>
<form method="get" action="index.php">
    <input type="hidden" name="route" value="search">
    <label>Введите название события:
        <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" required>
    </label>
    <button type="submit">Искать</button>
</form>

<?php if (!empty($title)): ?>
    <?php if (empty($events)): ?>
        <p>События с названием "<?= htmlspecialchars($title) ?>" не найдены.</p>
    <?php else: ?>
        <h2>Результаты поиска:</h2>
        <ul>
            <?php foreach ($events as $event): ?>
                <li>
                    <strong><?= htmlspecialchars($event['title']) ?></strong><br>
                    Место: <?= htmlspecialchars($event['location']) ?><br>
                    Дата: <?= htmlspecialchars($event['event_date']) ?><br>
                    <a href="details.php?id=<?= $event['id'] ?>">Подробнее</a> | 
                    <a href="delete.php?id=<?= $event['id'] ?>">Удалить</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
<?php endif; ?>
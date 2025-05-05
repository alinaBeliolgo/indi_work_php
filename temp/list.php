<?php

require_once '../config/db.php';


$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;

if (!$id || !is_numeric($id)) {
    echo "Некорректный ID события.";
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT *
        FROM events
        WHERE id = ?
    ");
    $stmt->execute([$id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        echo "Событие не найдено.";
        exit;
    }
} catch (PDOException) {
    echo "Ошибка при загрузке события";
    exit;
}
?>

<!-- HTML-код для отображения информации о событии -->
<h2><?= htmlspecialchars($event['title']) ?></h2>
<p><strong>Место проведения:</strong> <?= htmlspecialchars($event['location']) ?></p>
<p><strong>Дата события:</strong> <?= htmlspecialchars($event['event_date']) ?></p>
<p><strong>Описание:</strong> <?= nl2br(htmlspecialchars($event['description'])) ?></p>
<p><strong>Дата добавления:</strong> <?= htmlspecialchars($event['created_at']) ?></p>

<a href="index.php?route=index">Назад</a>
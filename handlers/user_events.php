<?php

require_once '../config/db.php';
require_once '../vendor/autoload.php';

$userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($userId <= 0) {
    echo "Не указан пользователь.";
    exit;
}

try {
    $redis = new Predis\Client();
    $cacheKey = "user_events_" . $userId;

    $events = $redis->get($cacheKey);

    if ($events) {
        $events = json_decode($events, true);
    } else {
        $stmt = $pdo->prepare("
            SELECT events.title, events.description, events.date, events.time, events.location
            FROM events
            WHERE events.user_id = ?
        ");
        $stmt->execute([$userId]);
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $redis->set($cacheKey, json_encode($events));
        $redis->expire($cacheKey, 3600); // Устанавливаем время жизни кеша (1 час)
    }

    if (empty($events)) {
        echo "<p>События для пользователя с ID $userId не найдены.</p>";
    } else {
        echo "<h2>События пользователя с ID $userId:</h2>";
        echo "<ul>";
        foreach ($events as $event) {
            echo "<li>
                    <strong>" . htmlspecialchars($event['title']) . "</strong><br>
                    Описание: " . htmlspecialchars($event['description']) . "<br>
                    Дата: " . htmlspecialchars($event['date']) . "<br>
                    Время: " . htmlspecialchars($event['time']) . "<br>
                    Место: " . htmlspecialchars($event['location']) . "
                  </li>";
        }
        echo "</ul>";
    }
} catch (Exception $e) {
    echo "Ошибка"; 
}
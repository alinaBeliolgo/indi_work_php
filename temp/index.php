<?php

require_once '../config/db.php';
require_once '../vendor/autoload.php';

$events = [];
$useRedis = true;

try {
    $route = isset($_GET['route']) ? $_GET['route'] : '';

    echo "<form method='get' action='index.php'>
            <input type='hidden' name='route' value='search'>
            <label>Введите название события:
                <input type='text' name='title' required>
            </label>
            <button type='submit'>Найти события</button>
          </form>";

    if ($route === 'search') {
        $title = isset($_GET['title']) ? trim($_GET['title']) : '';

        if (empty($title)) {
            echo "<p>Не указано название события.</p>";
            exit;
        }

        if ($useRedis) {
            $redis = new Predis\Client();
            $cacheKey = "event_title_" . md5($title);

            $events = $redis->get($cacheKey);

            if ($events) {
                $events = json_decode($events, true);
            } else {
                // Если данных нет в Redis, запрос к SQLite
                $stmt = $pdo->prepare("
                    SELECT events.title, events.description, events.location, events.event_date
                    FROM events
                    WHERE events.title LIKE ?
                ");
                $stmt->execute(["%$title%"]);
                $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Сохранение результата в Redis
                $redis->set($cacheKey, json_encode($events));
                $redis->expire($cacheKey, 3600); // Устанавливаем время жизни кеша (1 час)
            }
        } else {
            // Если Redis не используется
            $stmt = $pdo->prepare("
                SELECT events.title, events.description, events.location, events.event_date
                FROM events
                WHERE events.title LIKE ?
            ");
            $stmt->execute(["%$title%"]);
            $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        if (empty($events)) {
            echo "<p>События с названием '$title' не найдены.</p>";
        } else {
            echo "<h2>События с названием: " . htmlspecialchars($title) . "</h2>";
            echo "<ul>";
            foreach ($events as $event) {
                echo "<li>
                        <strong>" . htmlspecialchars($event['title']) . "</strong><br>
                        Место: " . htmlspecialchars($event['location']) . "<br>
                        Дата: " . htmlspecialchars($event['event_date']) . "<br>
                        Описание: " . htmlspecialchars($event['description']) . "
                      </li>";
            }
            echo "</ul>";
        }
        exit;
    }

    // Основной код для отображения всех событий
    if ($useRedis) {
        $redis = new Predis\Client();
        $cached = $redis->get("events_list");
        if ($cached) {
            $events = json_decode($cached, true);
        } else {
            $stmt = $pdo->query("
                SELECT events.id, events.title, events.location, events.event_date, events.created_at
                FROM events
            ");
            $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $redis->set("events_list", json_encode($events));
        }
    } else {
        $stmt = $pdo->query("
            SELECT events.id, events.title, events.location, events.event_date, events.created_at
            FROM events
        ");
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    if (empty($events)) {
        echo "<p>Нет доступных событий.</p>";
    } else {
        echo "<ul>";
        foreach ($events as $event) {
            echo "<li>
                    <strong>" . htmlspecialchars($event['title']) . "</strong> — Место: " . htmlspecialchars($event['location']) . 
                    " (Дата: " . htmlspecialchars($event['event_date']) . ", Создано: " . htmlspecialchars($event['created_at']) . ")
                    <a href='index.php?route=list&id=" . $event['id'] . "'>Подробнее</a> |
                    <a href='index.php?route=delete&id=" . $event['id'] . "'>Удалить</a>
                  </li>";
        }
        echo "</ul>";
    }
} catch (Exception $e) {
    echo "Ошибка";
}
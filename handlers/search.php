<?php
require_once '../config/db.php';
require_once '../vendor/autoload.php';

$title = isset($_GET['title']) ? trim($_GET['title']) : '';
$events = [];


if (!empty($title)) {
    try {
        $stmt = $pdo->prepare("
    SELECT id, title, location, event_date, created_at
    FROM events
    WHERE title LIKE ?
    ");
        $stmt->execute(['%' . $title . '%']); 
        return $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Ошибка при выполнении поиска";
        exit;
    }
}


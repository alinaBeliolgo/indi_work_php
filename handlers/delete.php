<?php 

require_once '../config/db.php';
require_once '../vendor/autoload.php';

$redis = new Predis\Client();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
    $stmt->execute([$id]);
    
    if ($stmt->rowCount() > 0) {
        $redis->del('events_list');
        header('Location: index.php?route=list'); // Перенаправление на список событий
        exit;
    } else {
        echo "Ошибка при удалении события.";
    }
};
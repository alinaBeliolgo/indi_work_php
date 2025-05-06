<?php

// Подключение к базе данных SQLite
$config = require_once __DIR__ . DIRECTORY_SEPARATOR . 'config.php';

$dsn = $config['root'] . DIRECTORY_SEPARATOR .  $config['dsn'];

$driver = $config['driver'];


try {
    //подключения к базе данных с использованием PDO
    $pdo = new PDO($driver . $dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Создание таблиц при отсутствии
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        email TEXT NOT NULL UNIQUE,
        password_hash TEXT NOT NULL,
        role TEXT NOT NULL DEFAULT 'user',
        reset_token TEXT,
        reset_token_expires_at DATETIME,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

// Создание таблицы книг
    $pdo->exec("CREATE TABLE IF NOT EXISTS books (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        author TEXT NOT NULL,
        category_id INTEGER NOT NULL,
        description TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES categories(id)
    )");

}catch (PDOException $e) {
    die("Ошибка 500: cвяжитесь с администратором сайта.");
}
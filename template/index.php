<?php

require_once '../config/db.php';

try {
/**
     * Выполняет SQL-запрос для получения списка книг с их категориями.
     * @var PDOStatement $stmt Результат выполнения SQL-запроса.
     */
    $stmt = $pdo->query("
        SELECT books.id, books.title, books.author, books.created_at, categories.name as category_name
        FROM books
        JOIN categories ON books.category_id = categories.id
    ");

    /**
     * Проверяет роль пользователя и отображает список книг.
     * Если роль пользователя — 'admin', добавляется возможность удаления книг.
     */
    if ($_SESSION['role'] === 'admin') {
        echo "<ul>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            /**
             * @var array $row Ассоциативный массив с данными о книге.
             */
            echo "<li>
                    <strong>" . htmlspecialchars($row['title']) . "</strong> — " . htmlspecialchars($row['author']) .
                " (Категория: " . htmlspecialchars($row['category_name']) . ", Дата: " . htmlspecialchars($row['created_at']) . ")
                    <a class=\"info\" href=\"index.php?route=list&id=" . $row['id'] . "\">Подробнее</a> |
                    <a class=\"delete\" href=\"index.php?route=delete&id=" . $row['id'] . "\" onclick=\"return confirm('Точно удалить?')\">Удалить</a>
                  </li>";
        }
        
        echo "</ul>";
    } else {
        /**
         * отображается список книг без возможности удаления
         * @var array $row Ассоциативный массив с данными о книге.
         */
        echo "<ul>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<li>
                    <strong>" . htmlspecialchars($row['title']) . "</strong> — " . htmlspecialchars($row['author']) .
                " (Категория: " . htmlspecialchars($row['category_name']) . ", Дата: " . htmlspecialchars($row['created_at']) . ")
                    <a href='index.php?route=list&id=" . $row['id'] . "'>Подробнее</a>
                  </li>";
        }
        echo "</ul>";
    }
} catch (PDOException $e) {
    echo "Ошибка при загрузке списка книг";
}

<?php

$host = '127.0.0.1';
$port = 3306;
$dbname = 'laravel_form_builder';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host={$host};port={$port};dbname={$dbname}", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

$tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
$dump = "SET FOREIGN_KEY_CHECKS = 0;\n\n";

foreach ($tables as $table) {
    $create = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(PDO::FETCH_ASSOC);
    $dump .= "DROP TABLE IF EXISTS `{$table}`;\n";
    $dump .= $create['Create Table'] . ";\n\n";

    $rows = $pdo->query("SELECT * FROM `{$table}`");
    foreach ($rows as $row) {
        $values = array_map(function ($value) use ($pdo) {
            if ($value === null) {
                return 'NULL';
            }
            return $pdo->quote($value);
        }, $row);
        $columns = array_map(function ($col) {
            return "`{$col}`";
        }, array_keys($row));
        $dump .= "INSERT INTO `{$table}` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n";
    }
    $dump .= "\n";
}

$dump .= "SET FOREIGN_KEY_CHECKS = 1;\n";
file_put_contents(__DIR__ . '/../database/dump.sql', $dump);
echo "Dump written to database/dump.sql\n";

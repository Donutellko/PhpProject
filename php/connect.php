<?php

$host = 'localhost';        // адрес сервера 

$database = 'id13661830_kitclicker';    // имя базы данных
$user = 'id13661830_root';              // имя пользователя
$password = 'z-}[&@tXiz9x]Fn6';                 // пароль

$database = 'exchange';     // имя базы данных
$user = 'exchange';         // имя пользователя
$password = 'exchange';     // пароль

$charset = 'utf8mb4';       // кодировка


$dsn = "mysql:host=$host;dbname=$database;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::ATTR_EMULATE_PREPARES   => true, /* to bind parameter twice */
];
try {
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
<?php

$host = 'localhost';        // адрес сервера 
$database = 'exchange';     // имя базы данных
$user = 'exchange';         // имя пользователя
$password = 'exchange';     // пароль 

$link = mysqli_connect($host, $user, $password, $database) 
    or die("Ошибка подключения БД: " . mysqli_error($link));
    
?>
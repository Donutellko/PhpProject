<?php
session_start();
$_SESSION["stage"]="start";
//Главная страница, на ней можно выбрать игру или график
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница | Эпсилон-Биржа</title>

	<link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico" />
	<link rel="stylesheet" href="css/main.css" type="text/css" media="all" />
</head>
<body>
    <header><?php include("php/header_login.php")?></header>
    <section><?php include("php/bargains_hot.php")?></section>
    <footer></footer>
</body>
</html>
<?php include("php/init.php") ?>

<?php
if (empty($_SESSION['customer_id'])) {
    header('Location: ' . 'index.php');
    exit;
}
?>
<html>
<head>
    <?php include("php/head_commons.php") ?>
    <title>Кабинет пользователя | <?php echo $APP_NAME ?></title>
</head>

<body>
<div id="wrapper">

    <?php include("php/header.php") ?>

    <article>

        <div>
            <a href="cabinet.php" class="w3-btn w3-border">Вернуться</a>
        </div>

        <h2>Настройки аккаунта</h2>
        <?php

        ?>
    </article>
</div>
</body>
</html>
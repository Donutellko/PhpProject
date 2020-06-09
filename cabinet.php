<?php include("php/init.php") ?>

<?php
if (empty($_SESSION['customer_id'])) {
    header('Location: ' . 'index.php');
    exit;
} else if ($_SESSION['role'] == 'ADMIN') {
    header('Location: ' . 'admin.php');
    exit;
}
?>

<head>
    <?php include("php/head_commons.php") ?>
    <title>Кабинет пользователя | <?php echo $APP_NAME ?></title>
</head>

<body>
    <div id="wrapper">

        <?php include("php/header.php") ?>

        <article>

            <div>
                <a href="create.php" class="w3-btn w3-green">Добавить предложение</a>
                <a href="setting.php" class="w3-btn w3-border">Настройки</a>
                <a href="" class="w3-btn w3-border">Пополнить счёт</a>
                <a href="" class="w3-btn w3-border">Вывести деньги</a>
                <a href="" class="w3-btn w3-border">История операций</a>
            </div>

            <h2>Информация об аккаунте</h2>
            <?php

            ?>

            <h2>Ваши текущие сделки</h2>

            <?php
                $bargains = get_bargains_by_owner($_SESSION['customer_id'], false);
                include "php/bargains_list.php";
            ?>

            <h2>Ваши закрытые сделки</h2>

            <?php
                $bargains = get_bargains_by_owner($_SESSION['customer_id'], true);
                include "php/bargains_list.php";
            ?>

            
        </article>


        <?php include("php/footer.php") ?>

    </div>
</body>

</html>
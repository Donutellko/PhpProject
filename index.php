<?php include("php/init.php") ?>

<!--?php print_r($_SESSION) ?> -->

<head>
    <?php include("php/head_commons.php") ?>
    <title>Главная страница | <?php echo $APP_NAME ?></title>
</head>

<body>
    <div id="wrapper">

        <?php include("php/header.php") ?>

        <article>
            <?php $bargains = mysqli_query($link, "select * from exchange.bargain;"); ?>
            <?php include("php/bargains_list.php") ?>
        </article>


        <?php include("php/footer.php") ?>

    </div>
</body>

</html>
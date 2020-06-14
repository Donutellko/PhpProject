<?php include("php/init.php") ?>

<!--?php print_r($_SESSION) ?> -->

<!DOCTYPE html>
<html lang="ru">

<head>
    <?php include("php/head_commons.php") ?>
    <title>Главная страница | <?php echo $APP_NAME ?></title>
</head>

<body>
    <div id="wrapper">

        <?php include("php/header.php") ?>

        <article>
            <?php $offers = get_offers() ?>
            <?php include("php/offers_list.php") ?>
        </article>


        <?php include("php/footer.php") ?>

    </div>
</body>

</html>
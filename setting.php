<?php include("php/init.php") ?>

<?php
if (empty($_SESSION['customer_id'])) {
    header('Location: ' . 'index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
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

        <form action="">
            <label>Email:
                <input type="text" class="w3-input" value="<?php echo $_SESSION['email'] ?>">
            </label>

            <button type="submit">Сохранить изменения</button>
        </form>
    </article>
</div>
</body>
</html>
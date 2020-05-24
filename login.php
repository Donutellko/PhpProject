<?php include("php/init.php") ?>

<?php

if (isset($_POST['login']) && isset($_POST['password'])) {
    $query = "SELECT project.login('" . $_POST['login'] . "','" . $_POST['password'] . "') login";
    $result = mysqli_query($link, $query);
} else if (isset($_SESSION['username'])) {
    $redirect = $CONTEXT_ROOT . (isset($_GET['redirect']) ? $_GET['redirect'] : '/cabinet.php');
    header('Location: ' . $redirect);
    exit();
}

?>

<head>
    <?php include("php/head_commons.php") ?>
    <title>Вход и регистрация | <?php echo $APP_NAME ?></title>
</head>

<body>

    <div id="wrapper">

        <?php include("php/header.php") ?>

        <article>
            <div class="form-wrapper">

                <?php
                if (!isset($_GET['register'])) {
                ?>
                    <form id="login-form" action="login.php" method="post" class="w3-card-4 w3-container w3-padding">

                        <label>Логин:</label>
                        <input type="text" name="username" class="w3-input">

                        <label>Пароль:</label>
                        <input type="text" name="password" class="w3-input">


                        <button id="login-submit" type="submit" class="w3-button">Войти</button>
                    </form>

                    <div class="w3-margin-top">
                        Ещё нет аккаунта?
                        <a href="?register">Зарегистрироваться</a>
                    </div>
                <?php
                } else {
                ?>
                    <form id="register-form" action="register.php" method="post" class="w3-card-4 w3-container w3-padding"> 

                        <label>Логин:</label>
                        <input type="text" name="username">

                        <label>ФИО:</label>
                        <input type="text" name="fullname">

                        <label>Пароль:</label>
                        <input type="text" name="password">

                        <button id="register-submit" type="submit" class="w3-button">Зарегистрироваться</button>
                    </form>
                <?php
                }

                ?>
            </div>
        </article>

        <?php include("php/footer.php") ?>
</body>

</html>
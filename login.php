<?php include("php/init.php") ?>

<?php

if (isset($_POST['email']) && isset($_POST['password'])) {
    $customer_id = login($_POST['email'], $_POST['password']);
    if ($customer_id > 0) {
        $customer = get_customer_by_id($customer_id);

        $_SESSION['customer_id'] = $customer_id;
        $_SESSION['email'] = $customer->email;
        $_SESSION['fullname'] = $customer->fullname;
    } else {
        $error = 'Неверный логин или пароль.';
    }
}

if (isset($_SESSION['email'])) {
    $redirect = isset($_GET['redirect'])? urldecode($_GET['redirect']) : 'cabinet.php';
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

                        <label>Эл.почта:</label>
                        <input type="text" name="email" class="w3-input">

                        <label>Пароль:</label>
                        <input type="password" name="password" class="w3-input">


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
                        
                        <label>ФИО:</label>
                        <input type="text" name="fullname" class="w3-input">
                        
                        <label>Город:</label>
                        <input type="addre" name="city" class="w3-input">

                        <label>Номер телефона:</label>
                        <input type="tel" name="email" class="w3-input">

                        <label>Эл.почта:</label>
                        <input type="text" name="email" class="w3-input">

                        <label>Пароль:</label>
                        <input type="password" name="password" class="w3-input">

                        <label>Повторите пароль:</label>
                        <input type="password" name="password" class="w3-input">

                        <button id="register-submit" type="submit" class="w3-button">Зарегистрироваться</button>
                    </form>
                <?php
                }

                ?>
            </div>
        </article>

        <?php include("php/footer.php") ?>
</body>
<?php
if (isset($error)) {
    echo '<script>alert("' . $error . '");</script>';
}
?>

</html>
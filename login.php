<?php include("php/init.php") ?>

<?php

if (isset($_SESSION['email']) && empty($_SESSION['confirm_code'])) {
    $redirect = isset($_GET['redirect']) ? urldecode($_GET['redirect']) : 'cabinet.php';
    header('Location: ' . $redirect);
    exit();
}

if (!empty($_SESSION['confirm_code'] && !isset($_GET['confirm']))) {
    header('Location: ?confirm');
}

$is_register = isset($_GET['register']);
$is_confirm = isset($_GET['confirm']);
$is_resend = isset($_GET['resend']);
$is_login = !($is_register || $is_confirm || $is_resend);

if ($is_login) {
    include "php/login.php";
} else if ($is_confirm) {
    if (empty($_SESSION['confirm_code'])) {
        $error = 'Вы уже подтвердили свой адрес почты, как Вы оказались на этой странице?';
    } else if (isset($_POST['confirm_code'])) {
        if (empty($_POST['confirm_code'])) {
            $error = 'Введите код!';
        } else if ($_POST['confirm_code'] == $_SESSION['confirm_code']) {
            confirm_email($_SESSION['email']);
            $_SESSION['confirm_code'] = null;
            header('Location: cabinet.php');
        } else {
            $error = 'Неправильный код!';
        }
    }
} else if ($is_resend) {
    $resend_result = send_confirmation($_SESSION['email'], $_SESSION['confirm_code']);
    $error = $resend_result;
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
            if ($is_login) {
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
            } else if ($is_register) {
                ?>
                <form id="register-form" action="api/register.php" method="post" onsubmit="registerUser(); return false;" class="w3-card-4 w3-container w3-padding">

                    <label>ФИО:</label>
                    <input type="text" name="fullname" class="w3-input">

                    <label>Город:</label>
                    <input type="text" name="city" class="w3-input">

                    <label>Эл.почта:</label>
                    <input type="email" name="email" class="w3-input">

                    <label>Пароль:</label>
                    <input type="password" id="password" name="password" class="w3-input" onchange="checkPassword()">

                    <label>Повторите пароль:</label>
                    <input type="password" id="password-repeat" class="w3-input" onchange="checkPassword()">

                    <button id="register-submit" type="submit" class="w3-button">Зарегистрироваться</button>
                </form>

                <div class="w3-margin-top">
                    Уже есть аккаунт?
                    <a href="?">Войти</a>
                </div>
                <?php
            } else if ($is_confirm) {
                ?>
                <form id="confirm-form" action="" method="post" class="w3-card-4 w3-container w3-padding">

                    <label>Код подтверждения из письма, отправленного на ваш адрес <?php echo $_SESSION['email'] ?></label>
                    <input type="text" name="confirm_code" class="w3-input">

                    <button id="register-submit" type="submit" class="w3-button">Завершить регистрацию</button>
                </form>

                <div class="w3-margin-top">
                    Не пришло письмо?
                    <a href="?resend">Послать снова</a>
                </div>
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

if ($is_register) {
    echo '<script src="js/register.js"></script>';
}
?>

</html>
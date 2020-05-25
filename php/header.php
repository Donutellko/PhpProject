
<header class="w3-container w3-red w3-padding">
    <div class="header-content">

        <a class="logo" href=".">
            <img src="img/logo_white.png" alt="">
        </a>

        <a href="." class="sitename w3-xlarge w3-margin-left">Эпсилон-Биржа</a>

        <div class="divider"></div>

        <div class="login w3-button w3-white-text">
            <?php
            if (!isset($_SESSION["customer_id"])) {
            ?>
                <a href="login.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']) ?>">
                    Войти
                </a>
            <?php
            } else {
            ?>
                <a href="cabinet.php">
                    <?php echo $_SESSION["fullname"] ?>
                </a>
            <?php
            }
            ?>
        </div>
    </div>
</header>
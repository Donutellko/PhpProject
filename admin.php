<?php

include 'php/init.php';

$is_users = isset($_GET['users']);
$is_user = isset($_GET['user']);
$is_bargains = isset($_GET['bargains']);
$is_bargain = isset($_GET['bargain']);

if ($is_user && isset($_POST['id'])) {
    update_customer($_POST);
} else if ($is_bargain && isset($_POST['id'])) {
    update_bargain($_POST);
}
?>

<head>
    <?php include("php/head_commons.php") ?>
    <title>Админка | <?php echo $APP_NAME ?></title>
</head>
<body>

<div id="wrapper">

    <?php include("php/header.php") ?>

    <article>
        <div>
            <a href="?users" class="w3-btn w3-border <?php echo $is_users ? 'w3-grey' : '' ?>">Пользователи</a>
            <a href="?bargains" class="w3-btn w3-border <?php echo $is_bargains ? 'w3-grey' : '' ?>">Сделки</a>
        </div>

        <div>
            <?php
            if ($is_users) {
                $users = get_customers();

                foreach ($users as $user) {
                    ?>
                    <a href="?user=<?php echo $user->id ?>" style="text-decoration: none">
                        <div class='w3-card-4'>
                            <h4>
                                <?php echo 'Имя: ' . $user->fullname ?>
                            </h4>
                            <p><?php echo 'id: ' . $user->id
                                    . '; role: ' . $user->role
                                    . '; email: ' . $user->email
                                    . '; confirm-code: ' . $user->confirm_code
                                ?></p>
                        </div>
                    </a>
                    <?php
                }
            } else if ($is_user) {
                echo '<form method="post">';
                $user = get_customer_by_id($_GET['user']);
                echo '<h2>Имя: ' . $user->fullname . '</h2>';
                foreach (((array)$user) as $field => $value) {
                    echo '<label style="display: inline-block; min-width: 150pt">' . $field . ' :  </label>';
                    echo '<input name="' . $field . '" value="' . $value . '"><br>';
                }
                echo '<button type="submit">Сохранить</button></form>';

            } else if ($is_bargains) {
                $bargains = get_bargains();

                foreach ($bargains as $bargain) {
                    ?>
                    <a href="?bargain=<?php echo $bargain->id ?>" style="text-decoration: none">
                        <div class='w3-card-4'>
                            <h4 class=''><?php echo ($bargain->is_sell ? "[Продажа] " : "[Покупка] ") . $bargain->title ?></h4>
                            <p class=''><?php echo $bargain->descr ?></p>
                        </div>
                    </a>
                    <?php
                }
            } else if ($is_bargain) {
                echo '<form method="post">';
                $bargain = get_bargain_only_by_id($_GET['bargain']);
                echo '<h2>' . ($bargain->is_sell ? "[Продажа] " : "[Покупка] ") . $bargain->title . '</h2>';
                foreach (((array)$bargain) as $field => $value) {
                    echo '<label style="display: inline-block; min-width: 150pt">' . $field . ' :  </label>';
                    echo '<input name="' . $field . '" value="' . $value . '"><br>';
                }
                echo '<button type="submit">Сохранить</button></form>';
            }
            ?>
        </div>
    </article>

    <?php include("php/footer.php") ?>
</div>
</body>

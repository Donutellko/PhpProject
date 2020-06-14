<?php
include("php/init.php");

$bargain = get_bargain_by_id($_GET['id']);
if (empty($bargain)) {
    include('php/404.php');
}
$seller = get_offer_by_id($bargain->offer_seller_id);
$buyer = get_offer_by_id($bargain->offer_buyer_id);

$customer_id = or_else($_SESSION, 'customer_id');
$customer_role = or_else($_SESSION, 'role');

$customer_seller = get_customer_by_id($seller->customer_owner_id);
$customer_buyer = get_customer_by_id($buyer->customer_owner_id);

if ($customer_id != $seller->customer_owner_id
    && $customer_id != $buyer->customer_owner_id
    && $customer_role != 'ADMIN'
    && $customer_role != 'ASSISTANT') {
    include('php/404.php');
}

if (!empty($_POST['message'])) {
    add_bargain_messages($bargain, $_SESSION['customer_id'], $_POST['message']);
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <?php include("php/head_commons.php") ?>
    <title>Сделка | <?php echo $APP_NAME ?></title>
</head>

<body>
<div id="wrapper">

    <?php include("php/header.php") ?>

    <article>
        <h2>Сделка</h2>
        <?php
        echo "<p>Продавец: $customer_seller->fullname (email: $customer_seller->email) "
            . "<a class='w3-btn w3-border' href='offer.php?id=$seller->id'>Предложение продавца</a></p>";
        echo "<p>Покупатель: $customer_buyer->fullname (email: $customer_buyer->email) "
            . "<a class='w3-btn w3-border' href='offer.php?id=$buyer->id'>Предложение покупателя</a> </p>";
        $assistant_info = empty($assistant) ? "ещё не найден" : "$assistant->fullname (email: $customer_buyer->email)";
        echo "<p>Брокер: $assistant_info</p>";

        echo "<h2>Чат:</h2>";

        $messages = get_bargain_messages($bargain);
        foreach($messages as $message) {
            echo "<p><b>$message->fullname</b>: "
                . "<span>$message->text</span></p>";
        }
        ?>
        <form method="post">
            <input class="w3-border w3-padding" name="message">
            <button class="w3-btn w3-green" type="submit">Отправить</button>
            <button class="w3-btn w3-border" onclick="location.href = location.href">Обновить</button>
        </form>
        <?php

        ?>
    </article>
</div>
</body>
</html>

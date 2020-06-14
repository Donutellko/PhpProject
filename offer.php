<?php
include("php/init.php");

$offer = get_offer_by_id($_GET['id']);
if (!isset($offer->id)) {
    include('php/404.php');
}

$customer_owner = get_customer_by_id($offer->customer_owner_id);
$matching_offers = search_offers(null, $offer->item_id, !$offer->is_sell, null);

$user_is_owner = !empty($_SESSION['customer_id']) && $offer->customer_owner_id == $_SESSION['customer_id'];

if (isset($offer->offer_target_id)) {
    $target = get_offer_by_id($offer->offer_target_id);
    if ($target->offer_target_id == $offer->id) {
        $bargain = get_bargain($offer, $target);
    }
}

if (isset($_GET['accept']) && empty($bargain)) {
    $target = get_offer_by_id($_GET['accept']);
    if ($offer->offer_target_id != $target->id) {
        accept_offer($offer, $target);
        $offer->offer_tafget_id = $target->id;
        if ($target->offer_target_id == $offer->id) {
            $bargain = create_bargain($offer, $target);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <?php include("php/head_commons.php") ?>
    <title><?php echo $offer->title ?> | <?php echo $APP_NAME ?></title>
</head>

<body>
<div id="wrapper">

    <?php include("php/header.php") ?>

    <article>
        <?php
        if (!isset($offer)) {
            ?>
            <div class="label">
                <p>Сделка с таким идентификатором не найдена. </p>
                <p>Вероятно, она была удалена или никогда не существовала.</p>
            </div>
            <?php
        } else {
            ?>
            <h2> <?php echo $offer->title; ?> </h2>
            <?php if ($offer->is_closed) echo '<h3 class="w3-red">Сделка закрыта.</h3>' ?>
            <div class="descr w3-margin-bottom"> <?php echo $offer->descr; ?> </div>
            <div class="addinfo">
                <h3> Информация о сделке: </h3>
                <p>Размещено: <?php echo $offer->created; ?></p>
                <p>Цена: <?php echo $offer->price; ?>руб. </p>
            </div>
            <?php

            if ($user_is_owner || isset($_SESSION['role']) && $_SESSION['role'] == 'ADMIN') {
                ?>
                <h3>Управление сделкой:</h3>
                <form method="post">
                    <label class="w3-margin-top">
                        <input type="checkbox" name="is_closed" value="<?php echo $offer->is_closed ?>">
                        Закрыть сделку
                    </label>
                    <br>
                    <button class="w3-btn w3-border w3-margin-top">Применить</button>
                </form>
                <?php
            } else if (isset($_SESSION['role'])) {
                ?>
                <div>
                    <h2>Создать ответное предложение</h2>
                    <a href='create.php?offer_target=<?php echo $offer->id ?>' class='w3-btn w3-green w3-margin-top'>
                        Создать новое
                    </a>
                    <br>
                    <br>
                    <?php
                    if (isset($_SESSION['customer_id'])) {
                        $propositions = search_offers(null, $offer->item_id, !$offer->is_sell, $_SESSION['customer_id']);
                        if (count($propositions) > 0) {
                            echo "<form action='' method='post' style=' display: flex;'>";
                            echo "<select name='' id='' class='w3-select' style='flex-grow: 1'>";
                            foreach ($propositions as $prop) {
                                echo "<option value='$prop->id'>$prop->title</option>";
                            }
                            echo "</select>";
                            echo "<button class='w3-input w3-green' type='submit'>Предложить</button>";
                            echo "</form>";
                        }
                    }
                    ?>
                </div>
                <?php
            } else {
                ?>
                <p class='w3-margin-top'>
                    <b><a href="login.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']) ?>">Авторизуйтесь</a>,
                        чтобы создать ответное предложение.</b>
                </p>
                <?php
            }

            if ($user_is_owner || (or_else($_SESSION, 'role') == 'ADMIN')) {
                ?>
                <h3>Встречные предложения:</h3>
                <p class="w3-margin-bottom">Информация о выбранной ставке видна только создателю сделки, её
                    брокеру и модераторам. Остальные пользователи видят только последнюю, лидирующую, ставку.</p>
                <?php
                if (count($matching_offers)) {
                    foreach ($matching_offers as $matching_offer) {
                        echo "<div class='bet w3-card-4 w3-margin-top'>";

                        $is_targeted_by = $matching_offer->offer_target_id == $offer->id;
                        $is_targets_to = $offer->offer_target_id == $matching_offer->id;

                        if (isset($bargain)) {
                            echo "<a class='w3-button w3-green' href='bargain.php?id=$bargain->id'>Сделка в прогрессе</a> ";
                        } else if ($is_targets_to) {
                            echo "<b class='w3-button w3-yellow'>Предложение отправлено</b> ";
                        } else if ($is_targeted_by) {
                            echo "<a class='w3-btn w3-green' href='offer.php?id=$offer->id&accept=$matching_offer->id'>Принять предложение</a> ";
                        } else {
                            echo "<a class='w3-btn w3-border' href='offer.php?id=$offer->id&accept=$matching_offer->id'>Отправить предложение</a> ";
                        }
                        echo ' ' . $offer->created . ': '
                            . "<b><a class='' href='offer.php?id=$matching_offer->id'>$matching_offer->title</a></b> "
                            . $offer->price . 'руб.'
                            . "</div>";
                    }
                } else {
                    ?>
                    <div class="label">Ставок нет.</div>
                    <?php
                }
            } else if (!empty($_SESSION['id'])) {
                ?>
                <a href="bet.php?id=<?php echo $offer->id ?>"></a>
                <?php
            }

            echo "<h2>Другие предложения</h2>";
            $offers = search_offers(null, $offer->item_id, $offer->is_sell);
            $exclude_offers_id = $offer->id;
            include 'php/offers_list.php';
        }
        ?>
    </article>

    <?php include("php/footer.php") ?>

</div>
</body>

</html>
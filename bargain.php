<?php include("php/init.php") ?>

<!--?php print_r($_SESSION) ?> -->

<?php

$bargain = get_bargain_by_id($_GET['id']);
if (!isset($bargain->id)) {
    http_response_code(404);
    include('php/404.php');
    exit;
}

$customer_owner = get_customer_by_id($bargain->customer_owner_id);
$assistant = get_assistant_by_id($bargain->assistant_id);
$bets = get_bets_by_bargain_id($bargain->id);

$user_is_owner = !empty($_SESSION['customer_id']) && $bargain->customer_owner_id == $_SESSION['customer_id'];
$user_is_assistant = !empty($_SESSION['customer_id']) && $bargain->assistant_id == $_SESSION['customer_id'];

if ($_POST) {

}
?>


<head>
    <?php include("php/head_commons.php") ?>
    <title><?php echo $bargain->title ?> | <?php echo $APP_NAME ?></title>
</head>

<body>
<div id="wrapper">

    <?php include("php/header.php") ?>

    <article>
        <?php
        if (!isset($bargain)) {
            ?>
            <div class="label">
                <p>Сделка с таким идентификатором не найдена. </p>
                <p>Вероятно, она была удалена или никогда не существовала.</p>
            </div>
            <?php
        } else {
            ?>
            <h2> <?php echo $bargain->title; ?> </h2>
            <?php if ($bargain->is_closed) echo '<h3 class="w3-red">Сделка закрыта.</h3>' ?>
            <div class="descr w3-margin-bottom"> <?php echo $bargain->descr; ?> </div>
            <div class="addinfo">
                <h3> Информация о сделке: </h3>
                <p>Размещено: <?php echo $bargain->created; ?></p>
                <p>Начальная ставка: <?php echo $bargain->start_bet; ?>руб. </p>
                <p>Текущая
                    ставка: <?php echo(empty($bets) ? 'Отсутствует' : '<b>' . $bets[0]->amount . 'руб. </b>'); ?> </p>
                <p>Ставка брокера: <?php echo $assistant->rate - 0; ?>%</p>
            </div>
            <?php

            if ($user_is_owner || isset($_SESSION['role']) && $_SESSION['role'] == 'ADMIN') {
                ?>
                <h3>Управление сделкой:</h3>
                <form method="post">
                    <label class="w3-margin-top">
                        <input type="checkbox" name="is_closed" value="<?php echo $bargain->is_closed ?>">
                        Закрыть сделку
                    </label>
                    <br>
                    <button class="w3-btn w3-border w3-margin-top">Применить</button>
                </form>
                <?php
            } else if (isset($_SESSION['role'])) {
                ?>
                <a href='create.php?bargain_target=<?php echo $bargain->id ?>' class='w3-btn w3-green w3-margin-top'>
                    Создать ответное предложение
                </a>
                <?php
            } else {
                ?>
                <p class='w3-margin-top'>
                    <b><a href="login.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']) ?>">Авторизуйтесь</a>, чтобы создать ответное предложение.</b>
                </p>
                <?php
            }

            if ($user_is_owner || $user_is_assistant || (or_else($_SESSION, 'role') == 'ADMIN')) {
                ?>
                <h3>Ставки:</h3>
                <p class="w3-margin-bottom">Информация обо всех совершённых ставках видна только создателю сделки, её
                    брокеру и модераторам.
                    Остальные пользователи видят только последнюю, лидирующую, ставку.</p>
                <?php
                if (count($bets)) {
                    foreach ($bets as $bet) {
                        ?>
                        <div class="bet">
                            <?php
                            echo "<a class='w3-btn w3-green' href='bargain.php?id=$bargain->id&complete=$bet->id'>Заключить сделку </a>";
                            echo $bet->created . ': ';
                            echo '<b>' . $bet->amount . 'руб. </b>';
                            echo(empty($bet->comment) ? 'Сообщение отсутствует.' : 'Сообщение: ' . $bet->comment);
                            ?>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="label">Ставок нет.</div>
                    <?php
                }
            } else if (!empty($_SESSION['id'])) {
                ?>
                <a href="bet.php?id=<?php echo $bargain->id ?>"></a>
                <?php
            }
        }
        ?>
    </article>

    <?php include("php/footer.php") ?>

</div>
</body>

</html>
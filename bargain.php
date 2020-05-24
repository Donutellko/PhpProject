<?php include("php/init.php") ?>

<!--?php print_r($_SESSION) ?> -->

<?php

$bargains = mysqli_query($link, "select * from exchange.bargain where id=" . ($_GET['id'] - 0) . ";");
if ($bargains->num_rows == 1) {
    $bargain = mysqli_fetch_array($bargains);
    $query = "select * 
                from exchange.customer 
                where id=" . $bargain['customer_owner_id'];
    $customer_owner = mysqli_fetch_array(mysqli_query($link, $query));

    $query = "select * 
                from exchange.assistant 
                    join exchange.customer using(id)
                where id=" . $bargain['assistant_id'];
    $assistant = mysqli_fetch_array(mysqli_query($link, $query));

    $query = "select * 
                from exchange.bargain_bet
                where bargain_id=" . $bargain['id'] . "
                order by amount " . ($bargain['is_sell'] > 0 ? 'desc' : 'asc');
    $bets = mysqli_query($link, $query);

    if ($bets->num_rows > 0) {
        $bet = mysqli_fetch_array($bets);
        // print_r($bet);
        echo $bet == null;
    }

    $user_is_owner = !empty($_SESSION['user_id']) && $bargain['customer_owner_id'] != $_SESSION['user_id'];
    $user_is_asistant = !empty($_SESSION['user_id']) && $bargain['customer_assistant_id'] != $_SESSION['user_id'];
}
?>


<head>
    <?php include("php/head_commons.php") ?>
    <title><?php echo $bargain['title'] ?> | <?php echo $APP_NAME ?></title>
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
                <h2> <?php echo $bargain['title']; ?> </h2>
                <div class="descr w3-margin-bottom"> <?php echo $bargain['descr']; ?> </div>
                <div class="addinfo">
                    <h4> Информация о сделке: </h4>
                    <p>Размещено: <?php echo $bargain['created']; ?></p>
                    <p>Начальная ставка: <?php echo $bargain['start_bet']; ?>руб. </p>
                    <p>Текущая ставка: <?php echo (empty($bet) ?  'Отсутствует' : '<b>' . $bet['amount'] . 'руб. </b>'); ?> </p>
                    <p>Ставка брокера: <?php echo $assistant['rate'] - 0; ?>%</p>
                </div>
                <?php

                if (true || $user_is_owner || $user_is_assistant) {
                ?>
                    <h2>Ставки:</h2>
                    <p class="w3-margin-bottom">Информация обо всех совершённых ставках видна только создателю сделки, её брокеру и модераторам. 
                    Остальные пользователи видят только последнюю, лидирующую, ставку.</p>
                    <?php
                    if (isset($bet)) {
                        do {
                            ?>
                                <div class="bet">
                                    <?php 
                                        echo $bet['created'] . ': ';
                                        echo '<b>' . $bet['amount'] . 'руб. </b>' ;
                                        echo (empty($bet['comment']) ? 'Сообщение отсутствует.' : 'Сообщение: ' . $bet['comment']); 
                                    ?> 
                                </div>
                            <?php
                        } while ($bet = mysqli_fetch_array($bets));
                    } else {
                            ?>
                                <div class="label">Ставок нет.</h2>
                            <?php
                    }
                }
            }
            ?>
        </article>

        <?php include("php/footer.php") ?>

    </div>
</body>

</html>

<?php
$link->close();
?>
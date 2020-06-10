<div class="bargains-list">

    <?php

    if (count($bargains) > 0) {

        foreach ($bargains as $bargain) {
            ?><div class='bargain w3-card-4'>
                    <h4 class=''><?php echo $bargain->title ?></h4>
                    <p class=''><?php echo $bargain->descr ?></p>
                    <p></p>
                    <a class="w3-btn w3-red" href="bargain.php?id=<?php echo $bargain->id ?>">
                        <?php
                        if ($_SESSION['customer_id'] == $bargain->customer_owner_id) echo 'Открыть';
                        else if ($_SESSION['customer_id'] == $bargain->assistant_id) echo 'Открыть';
                            else echo $bargain->is_sell ? "Покупка" : "Продажа" ;
                        ?>
                    </a>
                </div>
            <?php
        }
    } else {
        ?>
        <div class="label">Сейчас нет доступных сделок по заданным критериям. Зайдите позже или добавьте свою!</div>
    <?php
    }
    ?>
</div>
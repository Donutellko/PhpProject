<div class="bargains-list">

    <?php

    if (count($bargains) > 0) {

        foreach ($bargains as $bargain) {
            ?>
            <a class='bargain w3-card-4' href="bargain.php?id=<?php echo $bargain->id ?>">

                    <h4 class=''><?php echo $bargain->title ?></h4>
                    <p class=''><?php echo $bargain->descr ?></p>
                    <p></p>
                    <span class="w3-btn w3-red">
                        <?php
                        if ($_SESSION['customer_id'] == $bargain->customer_owner_id) echo 'Открыть';
                        else if ($_SESSION['customer_id'] == $bargain->assistant_id) echo 'Открыть';
                            else echo $bargain->is_sell ? "Покупка" : "Продажа" ;
                        ?>
                    </span>
            </a>
            <?php
        }
    } else {
        ?>
        <div class="label">Сейчас нет доступных сделок по заданным критериям. Зайдите позже или добавьте свою!</div>
    <?php
    }
    ?>
</div>
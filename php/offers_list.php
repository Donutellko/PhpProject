<div class="offers-list">

    <?php

    $displayed_offers = 0;

    foreach ($offers as $offer) {
        if (isset($exclude_offers_id) && $offer->id == $exclude_offers_id) continue;
        $displayed_offers++;
        ?>
        <a class='offer w3-card-4' href="offer.php?id=<?php echo $offer->id ?>">

            <h4 class=''><?php echo $offer->title ?></h4>
            <p class=''><?php echo $offer->descr ?></p>
            <p></p>
            <span class="w3-btn w3-red">
            <?php
            if (or_else($_SESSION, 'customer_id', '') == $offer->customer_owner_id) echo 'Открыть';
            else echo $offer->is_sell ? "Купить" : "Продать";
            ?>
        </span>
        </a>
        <?php
    }

    if ($displayed_offers == 0) {
        ?>
        <div class="label">Сейчас нет доступных сделок по заданным критериям. Зайдите позже или добавьте свою!</div>
        <?php
    }
    ?>
</div>
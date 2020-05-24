<div class="bargains-list">

    <?php

    if ($bargains->num_rows > 0) {

        while ($row = mysqli_fetch_array($bargains)) {
            $bargain = (object) $row;

            ?><div class='bargain w3-card-4'>
                    <h4 class=''><?php echo $bargain->title ?></h4>
                    <p class=''><?php echo $bargain->descr ?></p>
                    <p></p>
                    <a class="w3-btn w3-red" href="bargain.php?id=<?php echo $bargain->id ?>">
                        <?php echo $bargain->is_sell ? "Купить" : "Предложить" ?>
                    </a>
                </div>
            <?php
        }
    } else {
        ?>
        <div class="label">Сейчас нет доступных сделок. Зайдите позже или добавьте свою!</div>
    <?php
    }

    $link->close();
    ?>
</div>
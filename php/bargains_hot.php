<?php
include("connect.php"); // $link

$result = mysqli_query($link, "select * from exchange.bargain;");

if ($result->num_rows > 0) {

    while ($row = mysqli_fetch_array($result)) {
        $bargain = (object) $row;

        echo "<div class='bargain'>
            <h3>$bargain->title</h3>
            <p>$bargain->descr</p>
            <p></p>
            <button>" . ($bargain->is_sell ? "Купить" : "Предложить") .  "</button>
        </div>
        ";
    }


} else {
    echo `<div>Сейчас нет доступных сделок. Зайдите позже или добавьте свою!</div>`;
}

$link->close();

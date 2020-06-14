<?php

include "init_api.php";

$offers = search_offers($_GET);

$category_id = or_else($_GET, 'category', '');
$item_id = or_else($_GET, 'item', '');
$is_sell = or_else($_GET, 'is_sell', '');

$offers = search_offers($category_id, $item_id, $is_sell);

echo json_encode($offers);
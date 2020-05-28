<?php

include "init_api.php";

$category_id = or_else($_GET, 'category', '');
$item_id = or_else($_GET, 'item', '');

$bargains = search_bargains(['category' => $category_id, 'item' => $item_id]);

echo json_encode($bargains);
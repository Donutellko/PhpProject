<?php

include "init_api.php";

$category_id = or_else($_GET, 'category', '');

$items = get_items_by_category($category_id);

echo json_encode($items);
<?php

include 'php/init.php';

$is_users = isset($_GET['users']);
$is_user = isset($_GET['user']);
$is_bargains = isset($_GET['bargains']);
$is_bargain = isset($_GET['bargain']);
$is_categories = isset($_GET['categories']);
$is_items = isset($_GET['items']);

if ($is_user && isset($_POST['id'])) {
    update_customer($_POST);
} else if ($is_bargain && isset($_POST['id'])) {
    update_bargain($_POST);
} else if ($is_categories && isset($_POST['id'])) {
    update_category($_POST);
} else if ($is_items && isset($_POST['id'])) {
    update_item($_POST);
}
?>

<head>
    <?php include("php/head_commons.php") ?>
    <title>Админка | <?php echo $APP_NAME ?></title>
</head>
<body>

<div id="wrapper">

    <?php include("php/header.php") ?>

    <article>
        <div>
            <a href="?users" class="w3-btn w3-border <?php echo $is_users || $is_user ? 'w3-grey' : '' ?>">Пользователи</a>
            <a href="?bargains" class="w3-btn w3-border <?php echo $is_bargains || $is_bargain ? 'w3-grey' : '' ?>">Сделки</a>
            <a href="?categories" class="w3-btn w3-border <?php echo $is_categories || $is_items ? 'w3-grey' : '' ?>">Категории и товары</a>
        </div>

        <div>
            <?php
            if ($is_users) {
                echo "<h1>Список пользователей</h1>";
                $users = get_customers();
                foreach ($users as $user) {
                    ?>
                    <a href="?user=<?php echo $user->id ?>" style="text-decoration: none">
                        <div class='w3-card-4 w3-padding w3-margin-top'>
                            <h4>
                                <?php echo 'Имя: ' . $user->fullname ?>
                            </h4>
                            <p><?php echo 'id: ' . $user->id
                                    . '; role: ' . $user->role
                                    . '; email: ' . $user->email
                                    . '; confirm-code: ' . $user->confirm_code
                                ?></p>
                        </div>
                    </a>
                    <?php
                }
            } else if ($is_user) {
                echo '<form method="post">';
                $user = get_customer_by_id($_GET['user']);
                echo '<h2>Пользователь: ' . $user->fullname . '</h2>';
                foreach (((array)$user) as $field => $value) {
                    echo '<label style="display: inline-block; min-width: 150pt">' . $field . ' :  </label>';
                    echo '<input name="' . $field . '" value="' . $value . '"><br>';
                }
                echo '<button type="submit">Сохранить</button></form>';

            } else if ($is_bargains) {
                echo "<h1>Список сделок</h1>";
                $bargains = get_bargains();

                foreach ($bargains as $bargain) {
                    ?>
                    <a href="?bargain=<?php echo $bargain->id ?>" style="text-decoration: none">
                        <div class='w3-card-4 w3-padding w3-margin-top'>
                            <h4 class=''><?php echo ($bargain->is_sell ? "[Продажа] " : "[Покупка] ") . $bargain->title ?></h4>
                            <p class=''><?php echo '<b>владелец: ' . $bargain->owner_fullname . '</b>' ?></p>
                            <p class=''><?php echo $bargain->descr ?></p>
                        </div>
                    </a>
                    <?php
                }
            } else if ($is_bargain) {
                echo '<form method="post">';
                $bargain = get_bargain_only_by_id($_GET['bargain']);
                echo '<h2>' . ($bargain->is_sell ? "[Продажа] " : "[Покупка] ") . $bargain->title . '</h2>';
                foreach (((array)$bargain) as $field => $value) {
                    echo '<label style="display: inline-block; min-width: 150pt">' . $field . ' :  </label>';
                    echo '<input name="' . $field . '" value="' . $value . '"><br>';
                }
                echo '<button type="submit">Сохранить</button></form>';
            } else if ($is_categories) {
                echo "<h1>Список категорий</h1>";
                $categories = get_categories();
                array_push($categories, (object)['id' => count($categories) * 10, 'title' => 'Создать категорию', 'descr' => '']);
                foreach ($categories as $category) {
                    echo '<form method="post" class="w3-card-4 w3-padding w3-margin-top">';
                    echo "<a href='?items=$category->id'><h2>$category->title</h2></a>";
                    foreach (((array)$category) as $field => $value) {
                        echo '<label style="display: inline-block; min-width: 150pt">' . $field . ' :  </label>';
                        echo '<input name="' . $field . '" value="' . $value . '"><br>';
                    }
                    echo '<button type="submit">Сохранить</button></form>';
                    echo "</a>";
                }
                echo "<p class='w3-margin-top'>Чтобы удалить категорию, очистите её название и нажмите Сохранить</p>";
            } else if ($is_items) {
                $category_id = $_GET['items'];
                $category = get_category_by_id($category_id);
                echo "<h1>Список товаров категории '$category->title'</h1>";
                $items = get_items_by_category($category_id);
                $new_id = count($items) > 0 ? $items[count($items) - 1]->id + 10 : $category_id * 1000 + 10;
                array_push($items, (object)['id' => $new_id, 'title' => 'Создать товар', 'category_id' => $category_id, 'title_long' => '']);
                foreach ($items as $item) {
                    echo "<form method='post' class='w3-card-4 w3-padding w3-margin-top'>";
                    echo '<h2>' . $item->title . '</h2>';
                    foreach (((array)$item) as $field => $value) {
                        echo '<label style="display: inline-block; min-width: 150pt">' . $field . ' :  </label>';
                        echo '<input name="' . $field . '" value="' . $value . '"><br>';
                    }
                    echo '<button type="submit">Сохранить</button></form>';
                }
                echo "<p class='w3-margin-top'>Чтобы удалить товар, очистите название и нажмите Сохранить</p>";
            }
            ?>
        </div>
    </article>

    <?php include("php/footer.php") ?>
</div>
</body>

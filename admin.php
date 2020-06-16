<?php

include 'php/init.php';

$is_users = isset($_GET['users']);
$is_user = isset($_GET['user']);
$is_offers = isset($_GET['offers']);
$is_offer = isset($_GET['offer']);
$is_categories = isset($_GET['categories']);
$is_items = isset($_GET['items']);
$is_stats = isset($_GET['stats']);

if ($is_user && isset($_POST['id'])) {
    update_customer($_POST);
} else if ($is_offer && isset($_POST['id'])) {
    update_offer($_POST);
} else if ($is_categories && isset($_POST['id'])) {
    update_category($_POST);
} else if ($is_items && isset($_POST['id'])) {
    update_item($_POST);
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <?php include("php/head_commons.php") ?>
    <title>Админка | <?php echo $APP_NAME ?></title>
</head>
<body>

<div id="wrapper">

    <?php include("php/header.php") ?>

    <article>
        <div>
            <a href="?users"
               class="w3-btn w3-border <?php echo $is_users || $is_user ? 'w3-grey' : '' ?>">Пользователи</a>
            <a href="?offers" class="w3-btn w3-border <?php echo $is_offers || $is_offer ? 'w3-grey' : '' ?>">Сделки</a>
            <a href="?categories" class="w3-btn w3-border <?php echo $is_categories || $is_items ? 'w3-grey' : '' ?>">Категории
                и товары</a>
            <a href="?stats" class="w3-btn w3-border <?php echo $is_stats ? 'w3-grey' : '' ?>">Графички</a>
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

            } else if ($is_offers) {
                echo "<h1>Список сделок</h1>";
                $offers = get_offers();

                foreach ($offers as $offer) {
                    ?>
                    <a href="?offer=<?php echo $offer->id ?>" style="text-decoration: none">
                        <div class='w3-card-4 w3-padding w3-margin-top'>
                            <h4 class=''><?php echo ($offer->is_sell ? "[Продать] " : "[Купить] ") . $offer->title ?></h4>
                            <p class=''><?php echo '<b>владелец: ' . $offer->owner_fullname . '</b>' ?></p>
                            <p class=''><?php echo $offer->descr ?></p>
                        </div>
                    </a>
                    <?php
                }

                echo "<h2>Сделки, ожидающие рассмотрения</h2>";
                $bargains = get_bargains_no_assistant();
                foreach ($bargains as $bargain) {
                    echo "<div class='bet w3-card-4 w3-margin-top'>";
                    echo "<a class='w3-button w3-green' href='bargain.php?id=$bargain->id'>Сделка в прогрессе</a> ";
                    echo "</div>";
                }
            } else if ($is_offer) {
                echo '<form method="post">';
                $offer = get_offer_only_by_id($_GET['offer']);
                echo '<h2>' . ($offer->is_sell ? "[Продать] " : "[Купить] ") . $offer->title . '</h2>';
                foreach (((array)$offer) as $field => $value) {
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
            } else if ($is_stats) {

                echo "<div id='chart1' style='height: 300pt'></div>";
                echo "<script src='https://cdn.anychart.com/js/latest/anychart-bundle.min.js'></script>";
            }
            ?>
        </div>
    </article>

    <?php include("php/footer.php") ?>
</div>
<?php
if ($is_stats) {
//    echo "<script src='js/stats.js'></script>"
    ?>
    <script>
        anychart.onDocumentLoad(function () {
            var chart = anychart.column()
            chart.data({
                header: ["#", "Покупка", "Продажа", "Начатых сделок", "Совершённых сделок"],
                rows: <?php
                    $stat_sell = get_stat_sell();
                    $stat_buy = get_stat_buy();
                    $stat_bargain = get_stat_bargain();
                    $stat_completed = get_stat_completed();

                    $stats = array();
                    foreach ($stat_sell as $stat) $stats[$stat->date] = array($stat->date, 0, 0, 0, 0);
                    foreach ($stat_buy as $stat) $stats[$stat->date] = array($stat->date, 0, 0, 0, 0);
                    foreach ($stat_bargain as $stat) $stats[$stat->date] = array($stat->date, 0, 0, 0, 0);
                    foreach ($stat_completed as $stat) $stats[$stat->date] = array($stat->date, 0, 0, 0, 0);

                    ksort($stats);

                    foreach ($stat_sell as $stat) $stats[$stat->date][1] = $stat->cnt;
                    foreach ($stat_buy as $stat) $stats[$stat->date][2] = $stat->cnt;
                    foreach ($stat_bargain as $stat) $stats[$stat->date][3] = $stat->cnt;
                    foreach ($stat_completed as $stat) $stats[$stat->date][4] = $stat->cnt;

                    $result = array();
                    foreach ($stats as $stat) {
                        array_push($result, $stat);
                    }

                    echo json_encode($result);
                ?>
            });
            chart.title("График количества объявлений по дням");
            chart.legend(true);
            chart.container("chart1").draw();
        });
    </script>
    <?php
}
?>
</body>
</html>
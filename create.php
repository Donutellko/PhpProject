<?php include("php/init.php") ?>

<?php
if (empty($_SESSION['customer_id'])) {
    header('Location: ' . 'index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //
} elseif (empty($_POST['title'])
    || empty($_POST['descr'])
    || empty($_POST['start_bet'])
    || empty($_POST['item_id'])
    || empty($_POST['time_end'])
    || empty($_POST['future'])
    || empty($_POST['is_sell'])
) {
    $label = "Заполните все поля!";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST['customer_owner_id'] = $_SESSION['customer_id'];
    $_POST['assistant_id'] = get_random_assistant()->id;
    create_bargain($_POST);
    header('Location: ' . 'cabinet.php');
}
?>
<head>
    <?php include("php/head_commons.php") ?>
    <title>Создание сделки | Кабинет пользователя | <?php echo $APP_NAME ?></title>
</head>

<body>
<div id="wrapper">

    <?php include("php/header.php") ?>

    <article class="w3-padding">

        <div>
            <a href="cabinet.php" class="w3-btn w3-border">Вернуться</a>
        </div>

        <h2>Создание сделки</h2>
        <form id="create-form" action="" method="POST" class="w3-container" onsubmit="return create();">

            <div class="w3-margin-bottom">
                <label class="w3-margin-right">
                    <input class="w3-radio" type="radio" name="is_sell" value="true" checked>
                    Продажа
                </label>

                <label>
                    <input class="w3-radio" type="radio" name="is_sell" value="false">
                    Покупка
                </label>
            </div>

            <label class="w3-margin-top">
                Заголовок
                <input type="text" class="w3-input w3-margin-top" name="title" id="title"
                       maxlength="70" placeholder="Заголовок сделки (до 70 символов)">
            </label>

            <div class="w3-margin" style="display: flex; justify-content: space-around; flex-wrap: wrap">
                <label>
                    <p class="w3-margin-bottom">Категория товара</p>
                    <select id="category" class="w3-input w3-border" style="width: 200pt" onchange="setItems()">
                        <option value="" disabled selected>-- Выберите категорию --</option>
                        <?php
                        $categories = get_categories();
                        foreach ($categories as $category) {
                            echo '<option value="' . $category->id . '">' . $category->title . '</option>';
                        }
                        ?>
                    </select>
                </label>

                <label>
                    <p class="w3-margin-bottom">Товар</p>
                    <select id="item" class="w3-input w3-border" name="item_id" style="width: 200pt" disabled>

                    </select>
                </label>
            </div>

            <label class="w3-margin-bottom" for="descr">Описание</label>
            <textarea class="w3-input w3-border w3-margin-bottom" name="descr" id="descr"></textarea>

            <label class="w3-margin-bottom" style="display: block">
                <input id="start-bet" type="number" name="start_bet" class="w3-border" style="width: 120pt" value="1000">
                Начальная ставка
            </label>

            <label class="w3-margin-bottom" style="display: block">
                <input id="time-end" type="date" name="time_end" class="w3-border" style="width: 120pt" value="">
                Окончание приёма ставок
            </label>

            <label class="w3-margin-bottom" style="display: block">
                <input id="future" type="date" name="future" class="w3-border" style="width: 120pt">
                Осуществление обмена (фьючерс)
            </label>

            <?php

            ?>

            <?php
            if (isset($label)) {
                echo "<p class='w3-red w3-margin-bottom'>$label</p>";
            }
            ?>

            <button class="w3-button w3-border">Создать</button>
        </form>
    </article>
</div>
<script src="js/create.js"></script>
</body>
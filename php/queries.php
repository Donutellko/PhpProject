<?php

function login($email, $password) {
    global $pdo;
    $stmt = $pdo->prepare("select exchange.login(?,?) customer_id;");
    $stmt->execute([$email, $password]);
    return $stmt->fetch()->customer_id ;
}

function register($data) {
    global $pdo;
    $stmt = $pdo->prepare("select exchange.register(:email, :password, :fullname, :city) customer_id;");
    $stmt->execute($data);
    return $stmt->fetch()->customer_id;
}

function confirm_email($email) {
    global $pdo;
    $stmt = $pdo->prepare("update exchange.customer set confirm_code = null where email = ?;");
    $stmt->execute([$email]);
}

function get_customer_by_email($email) {
    global $pdo;
    $stmt = $pdo->prepare("select * from customer where lower(email) = lower(?);");
    $stmt->execute([$email]);
    return (object) $stmt->fetch();
}

function get_customer_by_id($id) {
    global $pdo;
    $stmt = $pdo->prepare("select * from customer where id = ?;");
    $stmt->execute([$id]);
    return (object) $stmt->fetch();
}

function get_assistant_by_id($id) {
    global $pdo;
    $stmt = $pdo->prepare("select * from assistant where id = ?;");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

$select_bargain = "select bargain.*,
    c.id as category_id, c.title as category_title,
    i.id as item_id, i.title as item_title, i.title_long as item_title_long 
from bargain
join item i on i.id = bargain.item_id
join category c on c.id = i.category_id
";

// получить информацию о сделке
function get_bargain_by_id($id) {
    global $pdo, $select_bargain;
    $stmt = $pdo->prepare($select_bargain . " where bargain.id = ?;");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// получить чистую запись о сделке без внешних полей
function get_bargain_only_by_id($id) {
    global $pdo;
    $stmt = $pdo->prepare("select * from bargain where bargain.id = ?;");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function get_bargains($is_closed = false) {
    global $pdo;
    $stmt = $pdo->prepare("select * from bargain where is_closed = ?;");
    $stmt->execute([$is_closed]);
    return $stmt->fetchAll();
}

function get_bargains_by_owner($owner_id, $is_closed = false) {
    global $pdo;
    $stmt = $pdo->prepare("select * from bargain where customer_owner_id = ? and is_closed = ?;");
    $stmt->execute([$owner_id, $is_closed]);
    return $stmt->fetchAll();
}

function search_bargains($filters) {
    global $pdo, $select_bargain;
    $stmt = $pdo->prepare($select_bargain .
     "where 
        ((:category = '') or (c.id = :category)) 
        and ((:item = '') or (i.id = :item))
        and (is_closed = false)");
    $stmt->execute($filters);
    return $stmt->fetchAll();
}

function get_bets_by_bargain_id($id) {
    global $pdo;
    $stmt = $pdo->prepare("select * from bargain_bet where bargain_id = ?;");
    $stmt->execute([$id]);
    return $stmt->fetchAll();
}

function get_bets_by_customer_id($id) {
    global $pdo;
    $stmt = $pdo->prepare("select * from bargain_bet where customer_id = ?;");
    $stmt->execute([$id]);
    return $stmt->fetchAll();
}

function get_categories() {
    global $pdo;
    $stmt = $pdo->prepare("select * from category");
    $stmt->execute();
    return $stmt->fetchAll();
}

function get_category_by_id($id) {
    global $pdo;
    $stmt = $pdo->prepare("select * from category where id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function get_items_by_category($id) {
    global $pdo;
    $stmt = $pdo->prepare("select * from item where category_id = ?;");
    $stmt->execute([$id]);
    return $stmt->fetchAll();
}

function create_bargain($info) {
    global $pdo;
    $stmt = $pdo->prepare("
        insert into bargain ( customer_owner_id,  assistant_id,  item_id,  future,  time_end,  is_sell,  start_bet,  title,  descr)
                     values (:customer_owner_id, :assistant_id, :item_id, :future, :time_end, :is_sell, :start_bet, :title, :descr)");
     $stmt->execute($info);
}

function get_random_assistant() {
    global $pdo;
    $stmt = $pdo->prepare("select * from assistant where active=1");
    $stmt->execute();
    $assistants = $stmt->fetchAll();
    $rand = array_rand($assistants);
    return $assistants[$rand];
}

function get_customers() {
    global $pdo;
    $stmt = $pdo->prepare("select * from customer left join assistant using(id)");
    $stmt->execute();
    return $stmt->fetchAll();
}

function update_customer($data) {
    global $pdo;
    $stmt = $pdo->prepare("update customer 
            set email = :email, fullname = :fullname, balance = :balance, blocked = :blocked,
            confirm_code = :confirm_code, role = :role, is_broker = :is_broker, password_hash = :password_hash
            where id = :id");
    $stmt->execute($data);
}

function update_bargain($data) {
    global $pdo;
    $stmt = $pdo->prepare("update bargain 
            set id = :id, item_id = :item_id, customer_owner_id = :customer_owner_id, assistant_id = :assistant_id, 
                future = :future, created = :created, time_end = :time_end, is_sell = :is_sell, 
                is_closed = :is_closed, start_bet = :start_bet, title = :title, descr = :descr
            where id = :id");
    $stmt->execute($data);
}

function update_category($data) {
    global $pdo;
    if (empty($data['title'])) {
        $tokens = ['id' => $data['id']];
        $stmt = $pdo->prepare("delete from item where category_id = :id; delete from category where id = :id");
        $stmt->execute($tokens);
    } else {
        $stmt = $pdo->prepare("insert into category(id, title, descr)
            values (:id, :title, :descr)
            on duplicate key update title = :title, descr = :descr");
        $stmt->execute($data);
    }
}

function update_item($data) {
    global $pdo;
    if (empty($data['title'])) {
        $stmt = $pdo->prepare("delete from item where id = ?");
        $stmt->execute([$data['id']]);
    } else {
        $stmt = $pdo->prepare("insert into item (id, category_id, title, title_long)
            values (:id, :category_id, :title, :title_long)
            on duplicate key update category_id = :category_id, title = :title, title_long = :title_long");
        $stmt->execute($data);
    }
}
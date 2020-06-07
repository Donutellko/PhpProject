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

function get_bargain_by_id($id) {
    global $pdo, $select_bargain;
    $stmt = $pdo->prepare($select_bargain . " where bargain.id = ?;");
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
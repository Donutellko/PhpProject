<?php

function login($email, $password) {
    global $pdo;
    $stmt = $pdo->prepare("select login(?,?) customer_id;");
    $stmt->execute([$email, $password]);
    return $stmt->fetch()->customer_id ;
}

function register($data) {
    global $pdo;
    $stmt = $pdo->prepare("select register(:email, :password, :fullname, :city) customer_id;");
    $stmt->execute($data);
    return $stmt->fetch()->customer_id;
}

function confirm_email($email) {
    global $pdo;
    $stmt = $pdo->prepare("update customer set confirm_code = null where email = ?;");
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

$select_offer = "select offer.*,
    c.id as category_id, c.title as category_title,
    i.id as item_id, i.title as item_title, i.title_long as item_title_long,
    owner.email as owner_email, owner.fullname as owner_fullname
from offer
join item i on i.id = offer.item_id
join category c on c.id = i.category_id
join customer owner on owner.id = offer.customer_owner_id
";

// получить информацию о сделке
function get_offer_by_id($id) {
    global $pdo, $select_offer;
    $stmt = $pdo->prepare($select_offer . " where offer.id = ?;");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// получить чистую запись о сделке без внешних полей
function get_offer_only_by_id($id) {
    global $pdo;
    $stmt = $pdo->prepare("select * from offer where offer.id = ?;");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function get_offers($is_closed = false) {
    global $pdo, $select_offer;
    $stmt = $pdo->prepare($select_offer ." where is_closed = ?;");
    $stmt->execute([$is_closed]);
    return $stmt->fetchAll();
}

function get_offers_by_owner($owner_id, $is_closed = false) {
    global $pdo;
    $stmt = $pdo->prepare("select * from offer where customer_owner_id = ? and is_closed = ?;");
    $stmt->execute([$owner_id, $is_closed]);
    return $stmt->fetchAll();
}

function search_offers($category_id, $item_id, $is_sell, $owner_id = null) {
    global $pdo, $select_offer;

    $filters = ['category' => $category_id, 'item' => $item_id, 'is_sell' => $is_sell, 'owner_id' => $owner_id];
    $stmt = $pdo->prepare($select_offer .
        "where 
        ((:category is null) or (c.id = :category)) 
        and ((:item is null) or (i.id = :item))
        and (:is_sell is null or is_sell = false xor :is_sell = '1')
        and (:owner_id is null or customer_owner_id = :owner_id)
        and (is_closed = false)");
    $stmt->execute($filters);
    return $stmt->fetchAll();
}

function accept_offer($offer, $target) {
    global $pdo;
    $offer->offer_target_id = $target->id;
    $stmt = $pdo->prepare("update offer set offer_target_id = :target_id where id = :offer_id");
    $stmt->execute(['offer_id' => $offer->id ,'target_id' => $target->id]);
}


function create_bargain($offer1, $offer2, $open = true) {
    global $pdo;
    $seller = $offer1->is_sell ? $offer1 : $offer2;
    $buyer = $offer1->is_sell ? $offer2 : $offer1;
    $stmt = $pdo->prepare("insert into bargain (offer_seller_id, offer_buyer_id) 
        values (:seller, :buyer_id) 
        on duplicate key update is_open = true");
    $stmt->execute(['seller' => $seller->id ,'buyer' => $buyer->id]);
    return get_bargain($seller, $buyer);
}

function get_bargain_by_id($id) {
    global $pdo;
    $stmt = $pdo->prepare("select * from bargain where id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}


function get_bargains_no_assistant() {
    global $pdo;
    $stmt = $pdo->prepare("select * from bargain where assistant_id is null");
    $stmt->execute();
    return $stmt->fetchAll();
}

function get_bargain($offer1, $offer2) {
    global $pdo;
    $seller = $offer1->is_sell ? $offer1 : $offer2;
    $buyer = $offer1->is_sell ? $offer2 : $offer1;

    $stmt = $pdo->prepare("select * from bargain where offer_seller_id = :seller and offer_buyer_id = :buyer");
    $stmt->execute(['seller' => $seller->id ,'buyer' => $buyer->id]);
    return $stmt->fetch();
}

function get_bargain_messages($bargain) {
    global $pdo;
    $stmt = $pdo->prepare("select m.*, customer.fullname as fullname
                from bargain_message as m join customer on m.author_id = customer.id
                where m.bargain_id = ?
                order by m.created");
    $stmt->execute([$bargain->id]);
    return $stmt->fetchAll();
}

function add_bargain_messages($bargain, $author_id, $text) {
    global $pdo;
    $stmt = $pdo->prepare("insert into bargain_message (bargain_id, author_id, text)
                values (:bargain_id, :author_id, :text)");
    $stmt->execute(['bargain_id' => $bargain->id ,'author_id' => $author_id, 'text' => $text]);
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

function create_offer($info) {
    global $pdo;
    $stmt = $pdo->prepare("
        insert into offer ( customer_owner_id,  item_id,  future,  time_end,  is_sell,  price,  title,  descr, offer_target_id)
                   values (:customer_owner_id, :item_id, :future, :time_end, :is_sell, :price, :title, :descr, :offer_target)");
     $stmt->execute($info);
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
            confirm_code = :confirm_code, role = :role, password_hash = :password_hash
            where id = :id");
    $stmt->execute($data);
}

function update_offer($data) {
    global $pdo;
    $stmt = $pdo->prepare("update offer 
            set id = :id, item_id = :item_id, customer_owner_id = :customer_owner_id, 
                future = :future, created = :created, time_end = :time_end, is_sell = :is_sell, 
                is_closed = :is_closed, price = :price, title = :title, descr = :descr
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

function get_stat_sell() {
    global $pdo;
    $stmt = $pdo->query("select date(created) as date, count(*) as cnt from offer where is_sell = true group by date(created)");
    return $stmt->fetchAll();
}

function get_stat_buy() {
    global $pdo;
    $stmt = $pdo->query("select date(created) as date, count(*) as cnt from offer where is_sell = false group by date(created)");
    return $stmt->fetchAll();
}

function get_stat_bargain() {
    global $pdo;
    $stmt = $pdo->query("select date(created) as date, count(*) as cnt from bargain group by date(created)");
    return $stmt->fetchAll();
}

function get_stat_completed() {
    global $pdo;
    $stmt = $pdo->query("select date(exec_end) as date, count(*) as cnt from bargain_completed group by date(exec_end)");
    return $stmt->fetchAll();
}
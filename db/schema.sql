/* =============================================== */
/* CREATE DATABASE */
/* =============================================== */

drop database exchange;

CREATE DATABASE exchange
  CHARACTER SET utf8
  COLLATE utf8_general_ci;

USE exchange;
/*
*/

/*
drop table withdrawal;
drop table offer_transaction;
drop table bargain;
drop table offer;
drop table item;
drop table category;
drop table broker;
drop table customer;
*/

/* =============================================== */
/* CREATE A SCHEMA AND FILL IT WITH SOME TEST DATA */
/* =============================================== */

/* a user that can create offers and bet */
create table customer (
    id int,
    fullname varchar(70) not null,
    email varchar(70) not null,
    balance bigint default 0,
    password_hash varchar(255) not null,
    blocked boolean default false,
    confirm_code int default (1000 + 10000 * rand()), /* empty if is confirmed*/
    role varchar(70) not null default 'CUSTOMER',
    primary key (id),
    unique (email)
);
insert into customer (id, fullname, email, balance, password_hash, role, confirm_code) values
  (1, 'Админище', 'admin', 0, SHA1('admin'), 'ADMIN', null)
, (10, 'Баффет', 'buffet.u@edu.spbstu.ru', 10000000 * 100, SHA1('12345'), 'ASSISTANT', null)
;

insert into customer (id, fullname, email, balance, password_hash, blocked, confirm_code) values
  (5, 'Донат', 'shergalis.dv@edu.spbstu.ru', 150 * 100, SHA1('12345'), FALSE, null)
, (6, 'Филипп', 'shergalis.fv@edu.spbstu.ru', 100000 * 100, SHA1('12345'), FALSE, null)
;


/* assistaint (broker) is a user that is moderating other user's offers */
create table assistant (
    id int,  /* equal to customer.id */
    rate decimal(4, 4) not null default 0.005,   /* fee that assistant receives from each offer: from 0.0001 to 0.9999 of offer sum */
    active boolean not null default true,        /* is assistant ready to assist */
    primary key (id),
    foreign key (id) references customer (id)
);
insert into assistant (id, rate) values
  (10, 0.01)
;



/* category of common items (e.g. food, devices... or 'other') */
create table category (
    id int,
    title varchar(70) not null,
    descr varchar(255) not null default '',
    primary key (id)
);
insert into category (id, title, descr) values
  (0, 'Другое', 'Товары, не подпадающие под конкретную категорию')
, (10, 'Продовольствие', 'Сырьё для производства пищевых товаров (мука, крупы, овощи...)')
, (20, 'Стройматериалы', 'Товары для промышленного строительства, домашнего ремонта и т.п.')
;




/* a common item (e.g. potatoes) */
create table item (
    id int,
    category_id int not null default 0,
    title varchar(25) not null,
    title_long varchar(70) null,
    primary key (id),
    foreign key (category_id) references category (id)
);

insert into item (id, category_id, title, title_long) values
        /* 25: +++++++++++++++++++++++++ 70: ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
  (    0, 0,  'Другое', 'Другой товар')
, (10010, 10, 'Картофель бел. Азерб.', 'Картофель белый Азербайджан')
, (10020, 10, 'Капуста белокачанная', 'Капуста белокочанная (Brássica olerácea)')
, (20010, 20, 'Арматура 10мм x 50м', 'Стеклопластиковая арматура Армопласт 10 мм 50 м арт.507000636')
, (20020, 20, 'Арматура 12мм x 3м', 'Стеклопластиковая арматура Армопласт 12 мм 3 м. Арт. 507000629')
;




/* information about offer */
create table offer (
    id int,
    item_id int not null default 0,                          /* id of some common item (e.g. potatoes ) */
    customer_owner_id int not null,                          /* id of customer that created the offer */

    future timestamp null,                                   /* date at which the offer should be done */
    created datetime not null default current_timestamp,     /* creation date */
    time_end datetime null,                                  /* betting deadline */
    is_sell boolean not null default true,                   /* if owner is selling, otherwise is buying */
    is_closed boolean not null default false,                   /* deleted or done */
    price int not null default 100,                      /* minimal/maximal sum of bet */
    offer_target_id int null,

    title varchar(70),
    descr text(4000),

    primary key (id),
    foreign key (customer_owner_id) references customer (id),
    foreign key (offer_target_id) references offer (id),
    foreign key (item_id) references item (id),
    check (price >= 100)
);
insert into offer (id, item_id, customer_owner_id, future, created, time_end, is_sell, is_closed, price, title, descr)
  values
  (1, 10010, 5, NULL, current_timestamp, current_timestamp, FALSE, FALSE, 100, 'Продам картошку.', 'Продам свежую картошку.'),
  (2, 10010, 6, NULL, current_timestamp, current_timestamp, FALSE, FALSE, 100, 'Куплю картошку.', 'Куплю свежую картошку.')
;


/* info about a bet */
create table bargain (
    id int not null auto_increment,
    created datetime not null default current_timestamp,
    assistant_id int null,
    offer_seller_id int not null,
    offer_buyer_id int not null,

    primary key (id),
    unique (offer_seller_id, offer_buyer_id),
    foreign key (assistant_id) references assistant (id),
    foreign key (offer_seller_id) references offer (id),
    foreign key (offer_buyer_id) references offer (id)
);
insert into bargain (id, offer_seller_id, offer_buyer_id, assistant_id)
  values (1, 1, 2, 10)
;

create table bargain_message (
    id int not null auto_increment,
    bargain_id int not null,
    author_id int not null,
    text varchar(4000) not null,
    created datetime not null default current_timestamp,

    primary key (id),
    foreign key (bargain_id) references bargain (id),
    foreign key (author_id) references customer (id)
);
insert into bargain_message (bargain_id, author_id, text)
values (1, 10, 'Приветики!');
;




/* created when offer has been finished */
/* a future is a offer_transaction that has confirmation_time  */
create table bargain_completed (
    id int,
    time_begin datetime not null,      /* initiation time */
    time_end datetime null,            /* when confirmed by assistant */
    exec_end datetime null,            /* execution time (if is future) */
    bargain_id int not null,

    amount int not null,               /* in currency minor units */
    fee int not null,                  /* goes to assistant */
    total int not null,                /* total = amount - fee */

    primary key (id),
    foreign key (bargain_id) references bargain(id)
);





/* receipt of a completed offer for archive */
create table receipt (
    id int,
    offer_transaction_id int null,
    executed datetime null,            /* execution time (if is future) */
    offer_title int not null,

    sender_id int not null,
    sender_fullname int not null,
    recipient_id int not null,
    recipient_fullname int not null,
    assistant_id int not null,         /* receives fee */
    assistant_fullname int not null,

    amount int not null,               /* in currency minor units */
    fee int not null,                  /* goes to assistant */
    total int not null,                /* total = amount - fee */

    primary key (id),
    index (sender_id),
    index (recipient_id),
    index (assistant_id),
    check (id is null or id = offer_transaction_id),
    foreign key (offer_transaction_id) references bargain_completed (id)
);


/* getting money to the real world */
create table bank_transaction (
    id int,
    time_begin timestamp not null,     /* initiation time */
    customer_id int null,              /* id of a customer */
    customer_fullname int not null,    /* fullname of a customer */
    bank_account varchar(24) not null, /* bank account */
    incoming boolean not null,         /* false for withdrawal */

    amount int not null,               /* amount in minorUnits */
    fee int not null,                  /* goes to us, if outgoing */
    total int not null,                /* total = amount - fee */

    primary key (id),
    foreign key (customer_id) references customer(id)
    -- constraint min_sum check (amount > 10 * 100), /* min 10 roubles */
    -- constraint total_sum check (amount  - fee = total),
    -- constraint fee_incoming check (incoming and fee = 0 or not incoming and fee >= 0)
);


delimiter $$
--
-- create function `login`
--
create function login(_email varchar(255), _password varchar(255))
    returns int
    sql security invoker
begin
    declare _id int;
    set _id = -1;
    select customer.id
    into _id
    from exchange.customer customer
    where customer.email = _email
      and customer.password_hash = sha1(_password);

    return _id;
end
$$


delimiter $$
--
-- create function `register`
--
create function register(_email varchar(255), _password varchar(255), _fullname varchar(255), _city varchar(255))
    returns int
    sql security invoker
begin
    declare _id int;
    set _id = -1;
    select max(customer.id) + 1
        into _id
        from exchange.customer customer;

    insert into customer (id, fullname, email, balance, password_hash, blocked)
                values (_id, _fullname, _email, 0, sha1(_password), false);

    return _id;
end
$$


/* =============================================== */
/* FULL CLEAR */
/* =============================================== */

delete from bargain_message where 1 = 1;
delete from bargain where 1 = 1;
delete from offer where 1 = 1;

delete from assistant where 1 = 1;
delete from customer where 1 = 1;

delete from item where 1 = 1;
delete from category where 1 = 1;



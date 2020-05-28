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
drop table bargain_transaction;
drop table bargain_bet;
drop table bargain;
drop table item;
drop table category;
drop table broker;
drop table customer;
*/

/* =============================================== */
/* CREATE A SCHEMA AND FILL IT WITH SOME TEST DATA */
/* =============================================== */

/* a user that can create bargains and bet */
create table customer (
    id int,
    fullname varchar(70) not null,
    email varchar(70) not null,
    balance bigint default 0,
    is_broker bool default false,       /* customer is also an assistant/broker */
    password_hash varchar(255) not null,
    blocked boolean default false,
    primary key (id)
);
insert into customer (id, fullname, email, balance, is_broker, password_hash, blocked) values 
  (1, 'Донат', 'shergalis.dv@edu.spbstu.ru', 150 * 100, FALSE, SHA1('12345'), FALSE)
, (2, 'Филипп', 'shergalis.fv@edu.spbstu.ru', 100000 * 100, FALSE, SHA1('12345'), FALSE)
, (3, 'Баффет', 'buffet.u@edu.spbstu.ru', 10000000 * 100, TRUE, SHA1('12345'), FALSE)
;


/* assistaint (broker) is a user that is moderating other user's bargains */
create table assistant (
    id int,  /* equal to customer.id */
    rate decimal(4, 4) not null default 0.005,   /* fee that assistant receives from each bargain: from 0.0001 to 0.9999 of bargain sum */
    active boolean not null default true,        /* is assistant ready to assist */
    primary key (id),
    foreign key (id) references customer (id)
);
insert into assistant (id, rate) values 
  (3, 0.01)
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




/* information about bargain */
create table bargain (
    id int,
    item_id int not null default 0,                          /* id of some common item (e.g. potatoes ) */
    customer_owner_id int not null,                          /* id of customer that created the bargain */
    assistant_id int not null,                               /* randomly chosen within active assistants */

    future timestamp null,                                   /* date at which the bargain should be done */
    created datetime not null default current_timestamp,     /* creation date */
    time_end datetime null,                                  /* betting deadline */
    is_sell boolean not null default true,                   /* if owner is selling, otherwise is buying */
    is_closed boolean not null default false,                   /* deleted or done */
    start_bet int not null default 100,                      /* minimal/maximal sum of bet */

    title varchar(70),
    descr text(4000),

    primary key (id),
    foreign key (customer_owner_id) references customer (id),
    foreign key (assistant_id) references assistant (id),
    foreign key (item_id) references item (id),
    check (start_bet >= 100)
);
insert into bargain (id, item_id, customer_owner_id, assistant_id, future, created, time_end, is_sell, is_closed, start_bet, title, descr)
  values 
  (1, 10010, 1, 3, NULL, current_timestamp, current_timestamp, FALSE, FALSE, 100, 'Продам картоплю.', 'Картопля свежая.')
;



/* info about a bet */
create table bargain_bet (
    id int,
    is_chosen boolean not null default false, 
    created datetime not null default current_timestamp,
    bargain_id int not null,
    customer_id int not null,       /* customer who makes bet */
    amount int not null,            /* sum of money that customer offers */
    comment varchar(255) null,      /* message to bargain owner */

    primary key (id),
    foreign key (customer_id) references customer (id),
    foreign key (bargain_id) references bargain (id)
);
insert into bargain_bet (id, created, bargain_id, customer_id, amount)
  values (1, current_timestamp, 1, 2, 100)
;




/* created when bargain has been finished */
/* a future is a bargain_transaction that has confirmation_time  */
create table bargain_completed (
    id int,
    time_begin datetime not null,      /* initiation time */
    time_end datetime null,            /* when confirmed by assistant */
    exec_end datetime null,            /* execution time (if is future) */
    bargain_bet_id int not null, 
                                      
    amount int not null,               /* in currency minor units */
    fee int not null,                  /* goes to assistant */
    total int not null,                /* total = amount - fee */

    primary key (id),
    foreign key (bargain_bet_id) references bargain_bet(id)
);

  



/* receipt of a completed bargain for archive */
create table receipt ( 
    id int,
    bargain_transaction_id int null,
    executed datetime null,            /* execution time (if is future) */
    bargain_title int not null, 

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
    check (id is null or id = bargain_transaction_id),
    foreign key (bargain_transaction_id) references bargain_completed (id)
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


DELIMITER $$
--
-- Create function `login`
--
CREATE FUNCTION login (_email varchar(255), _password varchar(255))
RETURNS int
SQL SECURITY INVOKER
BEGIN
  DECLARE customer_id int;
  set customer_id = -1;
  SELECT
    customer.id INTO customer_id
  FROM exchange.customer customer
  WHERE customer.email = _email
  AND customer.password_hash = SHA1(_password);

  RETURN customer_id;
END
$$


/* =============================================== */
/* FULL CLEAR */
/* =============================================== */

delete from bargain_bet where 1 = 1;
delete from bargain where 1 = 1;

delete from assistant where 1 = 1;
delete from customer where 1 = 1;

delete from item where 1 = 1;
delete from category where 1 = 1;



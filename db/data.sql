/* INITAL DATA */

/* =============================================== */
/* FULL CLEAR */
/* =============================================== */
SET FOREIGN_KEY_CHECKS=0;

delete from bargain_message where 1 = 1;
delete from bargain where 1 = 1;
delete from offer where 1 = 1;

delete from assistant where 1 = 1;
delete from customer where 1 = 1;

delete from item where 1 = 1;
delete from category where 1 = 1;

SET FOREIGN_KEY_CHECKS=1;



/* =============================================== */
/* FILL */
/* =============================================== */


insert into customer (id, fullname, email, balance, password_hash, role, confirm_code) values
(1, 'Админище', 'admin', 0, SHA1('admin'), 'ADMIN', null)
, (10, 'Баффет', 'buffet.u@edu.spbstu.ru', 10000000 * 100, SHA1('12345'), 'ASSISTANT', null)
;

insert into customer (id, fullname, email, balance, password_hash, blocked, confirm_code) values
(5, 'Донат', 'shergalis.dv@edu.spbstu.ru', 150 * 100, SHA1('12345'), FALSE, null)
  , (6, 'Филипп', 'shergalis.fv@edu.spbstu.ru', 100000 * 100, SHA1('12345'), FALSE, null)
;

insert into assistant (id, rate)
  values (10, 0.01);
  
insert into category (id, title, descr)
  values 
  (1, 'Другое', 'Товары, не подпадающие под конкретную категорию')
, (10, 'Продовольствие', 'Сырьё для производства пищевых товаров (мука, крупы, овощи...)')
, (20, 'Стройматериалы', 'Товары для промышленного строительства, домашнего ремонта и т.п.')
;


insert into item (id, category_id, title, title_long) 
values    /* 25: +++++++++++++++++++++++++ 70: ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    (    1, 1,  'Другое', 'Другой товар')
  , (10010, 10, 'Картофель бел. Азерб.',      'Картофель белый Азербайджан')
  , (10020, 10, 'Капуста белокачанная',       'Капуста белокочанная (Brássica olerácea)')
  , (10030, 10, 'Баклажаны',                  'Баклажаны. Баклажанчики. Что про них сказать?')
  , (20010, 20, 'Арматура 10мм x 50м',        'Стеклопластиковая арматура Армопласт 10 мм 50 м арт.507000636')
  , (20020, 20, 'Арматура 12мм x 3м',         'Стеклопластиковая арматура Армопласт 12 мм 3 м. Арт. 507000629')
;

insert into offer (id, item_id, customer_owner_id, future, created, time_end, is_sell, is_closed, price, title, descr)
values
(1, 10010, 5, NULL, current_timestamp - 10, current_timestamp, true, FALSE, 100, 'Продам картошку.', 'Продам свежую картошку.'),
(2, 10010, 6, NULL, current_timestamp, current_timestamp, false, FALSE, 100, 'Куплю картошку.', 'Куплю свежую картошку.'),
(3, 20010, 5, NULL, current_timestamp, current_timestamp, true, false, 1000, 'Арматура в количестве 100000 штук', 'Завалялась арматура после оборудования дачного участка туалетом. Самовывоз из деревни Гадюкино.'),
(4, 10030, 6, NULL, current_timestamp, current_timestamp + 100, false, false, 1000, 'Хочу баклажан', 'Ребятки, выручайте, нужно 800кг баклажанов для совершения постироничной фотосессии.'),
(5, 10010, 5, NULL, current_timestamp - INTERVAL 1 DAY, current_timestamp, true, FALSE, 100, 'Продам картошку.', 'Продам свежую картошку.'),
(6, 10010, 5, NULL, current_timestamp - INTERVAL 1 DAY, current_timestamp, true, FALSE, 100, 'Продам картошку.', 'Продам свежую картошку.'),
(7, 10010, 5, NULL, current_timestamp - INTERVAL 2 DAY, current_timestamp, true, FALSE, 100, 'Продам картошку.', 'Продам свежую картошку.'),
(8, 10010, 5, NULL, current_timestamp - INTERVAL 2 DAY, current_timestamp, false, FALSE, 100, 'Продам картошку.', 'Продам свежую картошку.'),
(9, 10010, 5, NULL, current_timestamp - INTERVAL 2 DAY, current_timestamp, true, FALSE, 100, 'Продам картошку.', 'Продам свежую картошку.')
;

insert into bargain (created, offer_seller_id, offer_buyer_id, assistant_id)
values (current_timestamp, 1, 2, 10)
;


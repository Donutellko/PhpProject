/* INITAL DATA */

/* =============================================== */
/*  CLEAR */
/* =============================================== */

delete from bargain_bet;
delete from bargain;

delete from assistant;
delete from customer;

delete from item;
delete from category;


/* =============================================== */
/* FILL */
/* =============================================== */


insert into customer (id, fullname, email, balance, is_broker, password_hash, blocked)
  values (1, 'Донат', 'shergalis.dv@edu.spbstu.ru', 150 * 100, FALSE, '12345', FALSE),
  (2, 'Филипп', 'shergalis.fv@edu.spbstu.ru', 100000 * 100, FALSE, '12345', FALSE),
  (3, 'Баффет', 'buffet.u@edu.spbstu.ru', 10000000 * 100, TRUE, '12345', FALSE)
;


insert into assistant (id, rate)
  values (3, 0.01);
  
insert into category (id, title, descr)
  values 
  (0, 'Другое', 'Товары, не подпадающие под конкретную категорию')
, (10, 'Продовольствие', 'Сырьё для производства пищевых товаров (мука, крупы, овощи...)')
, (20, 'Стройматериалы', 'Товары для промышленного строительства, домашнего ремонта и т.п.')
;


insert into item (id, category_id, title, title_long) 
values    /* 25: +++++++++++++++++++++++++ 70: ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    (    0, 0,  'Другое', 'Другой товар')
  , (10010, 10, 'Картофель бел. Азерб.',      'Картофель белый Азербайджан')
  , (10020, 10, 'Капуста белокачанная',       'Капуста белокочанная (Brássica olerácea)')
  , (10030, 10, 'Баклажаны',                  'Баклажаны. Баклажанчики. Что про них сказать?')
  , (20010, 20, 'Арматура 10мм x 50м',        'Стеклопластиковая арматура Армопласт 10 мм 50 м арт.507000636')
  , (20020, 20, 'Арматура 12мм x 3м',         'Стеклопластиковая арматура Армопласт 12 мм 3 м. Арт. 507000629')
;


insert into bargain (id, item_id, customer_owner_id, assistant_id, future, created, time_end, is_sell, is_closed, start_bet, title, descr)
  values 
  (1, 10010, 1, 3, NULL, current_timestamp, current_timestamp, FALSE, true, 100, 'Прода м картоплю 100кг.', 'Картопля свежая.'),
  (2, 10010, 1, 3, NULL, current_timestamp, current_timestamp, FALSE, true, 100, 'Продам ещё картоплю 5кг.', 'Завалялась ещё картопля, оказуется.'),
  (3, 10030, 2, 3, NULL, current_timestamp, current_timestamp, true, false, 100, 'Хочу баклажан', 'Ребятки, выручайте, нужно 800кг баклажанов.')
;

  
insert into bargain_bet (id, created, bargain_id, customer_id, amount)
  values (1, current_timestamp, 1, 2, 100);


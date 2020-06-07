<?php

include "init_api.php";

$allset = true;

if (empty($_POST['email'])) $allset = false;
if (empty($_POST['password'])) $allset = false;
if (empty($_POST['fullname'])) $allset = false;
if (empty($_POST['city'])) $allset = false;

if ($allset) {
    $tryfind = get_customer_by_email(strtolower($_POST['email']));
    if (isset($tryfind->id)) {
        error('Пользователь с таким e-mail уже зарегистрирован.');
    }

    $customer_id = register($_POST);
    if ($customer_id > 0) {
        $customer = get_customer_by_id($customer_id);

        send_confirmation($customer->email, $customer->confirm_code);

        set_session($customer);

        echo json_encode(['id' => $customer_id, 'message' => 'Успешная регистрация']);
        exit();
    } else {
        error('Неверный логин или пароль.');
    }
} else {
    error('Информация не заполнена!.');
}



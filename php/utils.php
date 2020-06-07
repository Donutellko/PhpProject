<?php

function or_else($array, $index, $default) {
    return isset($array[$index]) ? $array[$index] : $default;
}

function send_confirmation($email, $code) {
    return mail($email, 'Регистрация в Эпсилон-Бирже',
        "Для завершения регистрации на бирже Вам нужно ввести следующий код: " . $code);
}

function set_session($customer) {
    $_SESSION['customer_id'] = $customer->id;
    $_SESSION['email'] = $customer->email;
    $_SESSION['fullname'] = $customer->fullname;
    $_SESSION['confirm_code'] = $customer->confirm_code;
}
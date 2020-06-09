<?php

if (isset($_POST['email']) && isset($_POST['password'])) {
    $customer_id = login($_POST['email'], $_POST['password']);
    if ($customer_id >= 0) {
        $customer = get_customer_by_id($customer_id);

        set_session($customer);
        header('Location: cabinet.php');
    } else {
        $error = 'Неверный логин или пароль.';
    }
}
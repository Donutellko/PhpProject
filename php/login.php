<?php

if (isset($_POST['email']) && isset($_POST['password'])) {
    $customer_id = login($_POST['email'], $_POST['password']);
    if ($customer_id >= 0) {
        $customer = get_customer_by_id($customer_id);

        set_session($customer);
        $redirect = isset($_GET['redirect']) ? urldecode($_GET['redirect']) : 'cabinet.php';
        header('Location: ' . $redirect);
    } else {
        $error = 'Неверный логин или пароль.';
    }
}
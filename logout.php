<?php include("php/init.php") ?>

<?php

$_SESSION['customer_id'] = null;
$_SESSION['email'] = null;
$_SESSION['fullname'] = null;

$redirect = isset($_GET['redirect'])? urldecode($_GET['redirect']) : '.';
header('Location: ' . $redirect);
exit();

?>
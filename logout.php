<?php

include("php/init.php");

reset_session();

$redirect = isset($_GET['redirect'])? urldecode($_GET['redirect']) : '.';
header('Location: ' . $redirect);
exit();

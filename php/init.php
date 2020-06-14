<?php
session_start();

$APP_NAME = "Эпсилон-Биржа";
$CONTEXT_ROOT = $_SERVER['CONTEXT_PREFIX'];

include("connect.php");
include("queries.php");
include("utils.php");

if (!empty($_SESSION['confirm_code']) && strpos($_SERVER['REQUEST_URI'], 'login') < 0) {
    header('Location: login.php');
    exit();
}
<?php
session_start();
$_SESSION["stage"] = "start";

$APP_NAME = "Эпсилон-Биржа";
$CONTEXT_ROOT = $_SERVER['CONTEXT_PREFIX'];

include("connect.php");
include("queries.php");
include("utils.php");

?>

<!DOCTYPE html>
<html lang="ru">
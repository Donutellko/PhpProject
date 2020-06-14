<?php
include "init_api.php";

$commands = file_get_contents("../db/schema.sql");
$stmt = $pdo->query($commands);
$stmt->execute();

$commands = file_get_contents("../db/data.sql");
$stmt = $pdo->query($commands);
$stmt->execute();
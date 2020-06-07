<?php

session_start();

include "../php/connect.php";
include "../php/queries.php";
include "../php/utils.php";

header('Content-Type: application/json');

function error($message) {
    http_response_code(400);
    echo json_encode(['message' => $message]);
    exit();
}
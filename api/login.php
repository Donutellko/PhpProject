<?php

include "init_api.php";

include "../php/login.php";

if (isset($error)) {
    error($error);
} else {
    http_response_code(200);
}
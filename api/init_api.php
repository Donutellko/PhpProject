<?php

include "../php/init.php";

function or_else($array, $index, $default) {
    return isset($array[$index]) ? $array[$index] : $default;
}
<?php
session_start();
use function PHPSTORM_META\type;

include_once "../../../connect.php";
include_once "../functions.php";

if (checkItems() == 1) {
    echo 1;
} else {
    echo 0;
}

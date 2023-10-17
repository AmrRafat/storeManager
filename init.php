<?php
// Includes
//---------
include 'connect.php';

// ===============================================
// Routes
// ------
$tpl = 'includes/templates/'; // Templates Dir
$func = 'includes/functions/'; // Functions Dir
$css = 'layout/css/'; // Css Dir
$js = 'layout/js/'; // Js Dir
$langDir = 'includes/langs/'; // Langs Dir

// ===============================================
//Include the header and other files
include $func . "functions.php";
include $tpl . "header.php";
// Include navbar to all except no navbar var
if (!isset($noNavbar)) {
    include $tpl . "navbar.php";
}

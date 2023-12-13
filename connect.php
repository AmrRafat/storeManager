<?php

$host = 'localhost'; //mysql host name
$user = 'root'; //mysql username
$pass = ''; //mysql password
$db = ''; //mysql database
$port = '****'; //mysql port number
$dsn = "mysql:host=$host;dbname=$db;port=$port;charset=UTF8";

try {
    $con = new PDO($dsn, $user, $pass);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $con->query('USE store'); // Make sure to update this with db name in your sql server after you create the db only
} catch (PDOException $e) {
    echo 'Failed to connect ' . $e->getMessage();
}

// Please, import the db from the file called 'store.sql'

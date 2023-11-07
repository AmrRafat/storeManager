<?php

$host = 'localhost'; //mysql host name
$user = 'root'; //mysql username
$pass = ''; //mysql password
$db = ''; //mysql database
$port = 3307;
$dsn = "mysql:host=$host;dbname=$db;port=$port;charset=UTF8";

try {
    $con = new PDO($dsn, $user, $pass);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connection is perfect" . "<br>";
    $con->query('USE store');
    // echo $con->query('select database()')->fetchColumn();
} catch (PDOException $e) {
    echo 'Failed to connect ' . $e->getMessage();
}

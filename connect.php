<?php

$dsn = "mysql:host/localhost;dbname=store;port="; // Put the port number for your database
$user = "root";
$pass = "";
$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);

try {
    $con = new PDO($dsn, $user, $pass, $options);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connection is perfect" . "<br>";
    $con->query('USE store');
    // echo $con->query('select database()')->fetchColumn();
} catch (PDOException $e) {
    echo 'Failed to connect ' . $e->getMessage();
}

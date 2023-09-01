<?php
$dns = 'mysql:host=localhost;dbname=maktabati';
$user = 'root';
$pass = '';

$option = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
    PDO::ATTR_EMULATE_PREPARES => false
);
try {
    $con = new PDO($dns, $user, $pass, $option);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo 'You Are Connected to Database </br>';
} catch (PDOException $e) {
    echo 'Failed To Connect' . $e->getMessage() . '</br>';
}

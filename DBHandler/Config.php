<?php
/**
 * Created by PhpStorm.
 * User: mdjul
 * Date: 07/03/2017
 * Time: 10:50
 */
$host = 'grocery-robot.com';
$username = 'robotgro-compare';
$password = 'Zack123@';
$database = 'grocery-robot-compare';
try{
    $dbconfig = mysqli_connect($host,$username,$password,$database) or die("Database Error");
}catch(mysqli_sql_exception $e){
    echo $e;
}
?>
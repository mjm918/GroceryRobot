<?php
/**
 * Created by PhpStorm.
 * User: mdjul
 * Date: 07/03/2017
 * Time: 10:50
 */
$host = '127.0.0.1';
$username = 'robotgro';
$password = 'Zack123@';
$database = 'robotgro_compare';
$port = "3306";
try{

    $dbconfig = mysqli_connect($host,$username,$password,$database,$port) or die("Database Error");

}catch(mysqli_sql_exception $e){

    echo $e;
}
?>


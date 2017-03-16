<?php
/**
 * Created by PhpStorm.
 * User: mdjul
 * Date: 15/03/2017
 * Time: 21:17
 */
require_once "Config.php";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = $_POST['email'];

    $query = mysqli_query($dbconfig,"update gr_cart set active_status = '0' where user_email='$email'");
    if($query){
        $arr = array(
            'message'=>'Successfully cleared',
            'key'=>'0'
        );
        echo json_encode($arr);
    }
}
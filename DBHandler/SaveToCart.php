<?php
/**
 * Created by PhpStorm.
 * User: mdjul
 * Date: 14/03/2017
 * Time: 21:41
 */
require_once "Config.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = $_POST['email'];
    $id = $_POST['id'];
    $quan = $_POST['quan'];
    $uniq = $_POST['uniq'];

    $check = mysqli_query($dbconfig,"select * from gr_cart where item_id='$id' and user_email='$email' and active_status='1'");
    if(mysqli_num_rows($check)==1){
        $query = mysqli_query($dbconfig,"UPDATE gr_cart set item_quantity=item_quantity+'$quan' where 
                                                            item_id= '$id' and 
                                                            user_email= '$email' and 
                                                            active_status='1'");
        if($query){
            $arr = array(
                'message'=>'Successfully added to cart',
                'key'=>'1'
            );
            echo json_encode($arr);
        }else{
            $arr = array(
                'message'=>'Error adding to cart. Please try again',
                'key'=>'0'
            );
            echo json_encode($arr);
        }

    }else{
        $query = mysqli_query($dbconfig,"insert into gr_cart (id,user_email,item_unique_code,item_id,item_quantity,active_status)
                                     values (NULL,'$email','$uniq','$id','$quan','1')");
        if($query){
            $arr = array(
                'message'=>'Successfully added to cart',
                'key'=>'1'
            );
            echo json_encode($arr);
        }else{
            $arr = array(
                'message'=>'Error adding to cart. Please try again',
                'key'=>'0'
            );
            echo json_encode($arr);
        }
    }
}
?>
<?php
/**
 * Created by PhpStorm.
 * User: mdjul
 * Date: 15/03/2017
 * Time: 02:58
 */
session_start();
require_once "Config.php";

echo '<p style="font-weight:700;font-size:20px;font-family: Calibri;">Sheng Siong</p>';
$email = $_COOKIE['cookiee'];
$cold = mysqli_query($dbconfig,"SELECT * FROM gr_cart WHERE item_unique_code LIKE 'shengsiong%' AND active_status = '1' AND user_email = '$email'");

if(mysqli_num_rows($cold)>0){
    while($cold_row = mysqli_fetch_array($cold)){
        $uid = $cold_row['item_unique_code'];
        $id = $cold_row['item_id'];
        $count = mysqli_query($dbconfig,"SELECT SUM(item_quantity) AS total FROM gr_cart WHERE item_id='$id' AND active_status = '1' AND user_email = '$email'");
        $count_row = $count->fetch_assoc();
        $total_item = $count_row['total'];

        $weight_q = mysqli_query($dbconfig,"SELECT * FROM gr_item WHERE id = '$id'");
        $weight_row = $weight_q->fetch_assoc();
        $weight_w = $weight_row['weight'];
        $name_w = $weight_row['product_name'];
        $price_w = $weight_row['product_price'];
        echo '
               <hr>
               <p style="font-family: Calibri">Name : '.$name_w.'<strong>  Weight : '.$weight_w.'</strong></p>
               <p style="font-family: Calibri">Quantity : '.$total_item.'<strong>  Price : '.$price_w.'</strong></p>
          ';
    }
}
$cold_cat = $cold->fetch_assoc();
$category = $cold_cat['product_category'];
$compare = mysqli_query($dbconfig,"select * from gr_cart where item_unique_code NOT LIKE 'shengsiong%' and active_status='1' and user_email='$email'");
while($compare_row = mysqli_fetch_array($compare)){
    $compare_id = $compare_row['item_id'];
    $selectFromGr = mysqli_query($dbconfig,"select * from gr_item where id='$compare_id'");
    $row = $selectFromGr->fetch_assoc();
    $compare_name = $row['product_name'];
    $compare_price = $row['product_price'];

    $split = str_split($compare_name,6);
    $first = $split[0];
    $second = $split[1];

    $selectFromCold = mysqli_query($dbconfig,"SELECT * FROM gr_item g WHERE
                                                                        g.outlet_name != 'shengsion' AND
                                                                        g.product_name LIKE '%$first%' AND
                                                                        g.product_name LIKE '%$second%'");
    $row_cold = $selectFromCold->fetch_assoc();
    $compare_price_cold = $row_cold['product_price'];
    if(strpos($compare_price_cold,'$') !== false){
        $replace = str_replace('$','',$compare_price_cold);
    }else{
        $replace = str_replace('RM','',$compare_price_cold);
    }
    $suggest_price_float = floatval($replace);
    $less = $compare_price-($suggest_price_float*0.20);
    //$high = $compare_price+($suggest_price_float*0.20);

    if($suggest_price_float>$less){
        $name_c = $row_cold['product_name'];
        $weight_c = $row_cold['weight'];
        echo '
               <hr>
               <p>Compare</p>
               <p style="font-family: Calibri">Name : '.$name_c.'<strong>  Weight : '.$weight_c.'</strong></p>
               <p style="font-family: Calibri"><strong>  Price : $ '.$suggest_price_float.'</strong></p>
          ';
    }
}
?>
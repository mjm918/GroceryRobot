<?php
/**
 * Created by PhpStorm.
 * User: mdjul
 * Date: 06/03/2017
 * Time: 21:13
 */
require "DBHandler/Config.php";
session_start();

$food_category = mysqli_query($dbconfig,"SELECT DISTINCT category_name FROM gr_item");
//Pagination and main page load [START]
if (isset($_GET["page"])) { $requested_page  = $_GET["page"]; } else { $requested_page=1; };

$basic_limit = 30;
$disable = "";
$current_page = ($requested_page-1)*$basic_limit;

if($current_page == 0){$disable = "display:none;";}

//Select store and show result [START]
if(isset($_GET['store'])){
    $store = $_GET['store'];
    $_SESSION['temp'] = "&store=".$store;

    if (isset($_GET["page"])) { $requested_page  = $_GET["page"]; } else { $requested_page=1; };

    $current_page = ($requested_page-1)*$basic_limit;

    $products = mysqli_query($dbconfig,"SELECT * FROM gr_item WHERE outlet_name='$store' LIMIT $current_page,$basic_limit");

    $page_count = mysqli_query($dbconfig,"SELECT COUNT(*) AS total FROM gr_item WHERE outlet_name='$store'");
    $row_page_count = $page_count->fetch_assoc();
    $last_page = round(intval($row_page_count['total'])/30);
}
//Select store and show result [END]

//Select category and show result [START]
else if(isset($_GET['category'])){
    $category = $_GET['category'];
    $_SESSION['temp'] = "&category=".$category;
    if (isset($_GET["page"])) { $requested_page  = $_GET["page"]; } else { $requested_page=1; };

    $current_page = ($requested_page-1)*$basic_limit;

    $products = mysqli_query($dbconfig,"SELECT * FROM gr_item WHERE REPLACE (category_name,' ','') = REPLACE('$category',' ','') LIMIT $current_page,$basic_limit");

    $page_count = mysqli_query($dbconfig,"SELECT COUNT(*) AS total FROM gr_item WHERE REPLACE (category_name,' ','') = REPLACE('$category',' ','')");
    $row_page_count = $page_count->fetch_assoc();
    $last_page = round(intval($row_page_count['total'])/30);
}
//Select category and show result [END]
else{
    $products = mysqli_query($dbconfig,"SELECT * FROM gr_item LIMIT $current_page,$basic_limit");

    $page_count = mysqli_query($dbconfig,"SELECT COUNT(*) AS total FROM gr_item");
    $row_page_count = $page_count->fetch_assoc();
    $last_page = round(intval($row_page_count['total'])/30);
}
//Pagination and main page load [END]


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<!--    <link href="assets/midp.ico" rel="shortcut icon" type="image/x-icon" />-->
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/bootstrap-social.css">
    <link rel="stylesheet" href="CSS/bootstrap.css">
    <link rel="stylesheet" href="CSS/font-awesome.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

    <script src="JS/Main.js" type="text/javascript"></script>
    <script src="JS/jquery.js" type="text/javascript"></script>

    <script type="text/javascript">
        var query = window.location.search.substring(1)

        if(query.length) {
            if(window.history != undefined && window.history.pushState != undefined) {
                window.history.pushState({}, document.title, window.location.pathname);
            }
        }
    </script>
    <style>
        .productbox {
            background-color:#ffffff;
            margin-bottom: 10px;
            -webkit-box-shadow: 0 8px 6px -6px  #999;
            -moz-box-shadow: 0 8px 6px -6px  #999;
            box-shadow: 0 8px 6px -6px #999;
            border: 1px solid;
            border-radius: 8px;
            border-color: gainsboro;
        }

        .producttitle {
            max-height: 50px;
            font-size: 14px;
            font-family: "Times New Roman";
            padding:5px 0 5px 0;
        }

        .productprice {
            font-family: Verdana;
            border-top:1px solid #dadada;
            padding-top:5px;
        }

        .pricetext {
            font-weight:bold;
            font-size:1.4em;
        }

        .item {
            padding-top:20px;
            position:relative;
            display:inline-block;
        }
        .notify-badge{
            display:block;
            -webkit-transform: rotate(45deg);
            -moz-transform: rotate(45deg);
            filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);

            position: absolute;
            right:-20px;
            top:10px;
            background:red;
            text-align: center;
            border-radius: 30px 30px 30px 30px;
            color:white;
            padding:5px 10px;
            font-size:20px;
        }

        .scrollable-menu {
            height: auto;
            max-height: 200px;
            overflow-x: hidden;
        }
    </style>

    <title>Grocery Robot</title>
</head>
<?php include "Header.php";?>
<body>
<div style="padding-top: 70px" class="container">
    <div class="row">
        <div class="col-sm-9 col-md-6 col-lg-8">
            <div class="col-sm-3 col-md-3">
                <div class="form-group">
                    <div class="dropdown">
<!--                        Store dropdown menu-->
                        <button class="btn btn-block btn-default dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">Choose store
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu scrollable-menu" role="menu" aria-labelledby="menu1">
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="index.php">All stores</a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?store=shengsiong">Siong Seng</a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?store=fairprice">FairPrice</a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?store=coldstorage">Cold Storage</a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?store=tesco-my">TESCO (Johor Bahru Malaysia)</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 col-md-4">
                <div class="form-group">
                    <div class="dropdown">
                        <button class="btn btn-block btn-default dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">Foods category
                            <span class="caret"></span></button>
<!--                        Foods category dropdown menu-->
                        <ul class="dropdown-menu scrollable-menu" role="menu" aria-labelledby="menu1">
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="index.php">All stores</a></li>
                            <?php
                                while ($row_category = mysqli_fetch_array($food_category)){
                                    echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?category='.$row_category['category_name'].'">'.$row_category['category_name'].'</a></li>';
                                }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 col-md-5">
                <form class="form-inline">
                    <div class="form-group mx-sm-3">
                        <input type="text" class="form-control" placeholder="Search by name">
                    </div>
                    <input value="Search" type="submit" class="btn btn-default" style="background-color: darkslategray;color: white">
                </form>
            </div>
        </div>
        <!--        -->
        <!--        -->
        <!--        this is pagination   [START]-->
        <!--        -->
        <!--        -->
        <?php
            if($_GET['category']=="" && $_GET['store']==""){
                echo '<div id="page" style="margin-top: -20px;" class="col-sm-6 col-md-6 col-lg-4">
                        <div class="col-sm-3 col-md-12">
                            <ul class="pagination pagination-sm">';
                for($index = 1; $index<5; $index++){
                    echo '<li><a href="index.php?page='.$index.'">'.$index.'</a></li>';
                }
                echo '
                        <li><a href="#">..</a></li>
                        <li><a href="#">..</a></li>
                     ';
                echo '<li><a href="index.php?page='.$last_page.'">'.$last_page.'</a></li>';
                echo '<li style="'.$disable.'"><a href="index.php?page='.($requested_page-1).'">Previous</a></li>';
                echo '<li><a href="index.php?page='.($requested_page+1).'">Next</a></li>';
                echo '
                            </ul>
                            </div>
                        </div>
                     ';
            }else{
                echo '<div id="page" style="margin-top: -20px;" class="col-sm-6 col-md-6 col-lg-4">
                        <div class="col-sm-3 col-md-12">
                            <ul class="pagination pagination-sm">';
                for($index = 1; $index<5; $index++){
                    echo '<li><a href="index.php?page='.$index.$_SESSION['temp'].'">'.$index.'</a></li>';
                }
                echo '
                        <li><a href="#">..</a></li>
                        <li><a href="#">..</a></li>
                     ';
                echo '<li><a href="index.php?page='.$last_page.$_SESSION['temp'].'">'.$last_page.'</a></li>';
                echo '<li style="'.$disable.'"><a href="index.php?page='.($requested_page-1).$_SESSION['temp'].'">Previous</a></li>';
                echo '<li><a href="index.php?page='.($requested_page+1).$_SESSION['temp'].'">Next</a></li>';
                echo '
                            </ul>
                            </div>
                        </div>
                     ';
            }
        ?>
        <!--        -->
        <!--        -->
        <!--        this is pagination   [END]-->
        <!--        -->
        <!--        -->
    </div>
    <div id="content" style="margin-top: 20px" class="row">
        <div class="col-sm-9 col-md-6 col-lg-8">
            <!--        Items start-->
            <!--        Items start-->
            <!--        Items start-->
            <?php
                while($row_product = mysqli_fetch_array($products)){
                    echo '<div style="height: 220px" class="col-md-4 column productbox">
                <div class="item">
<!--                    <span class="notify-badge">Offer!</span>-->
                    <img style="width: 100px;height: 100px;" src="'.$row_product['product_image'].'" class="img-responsive">
                </div>
                <div class="producttitle">'.substr($row_product['product_name'],0,20).'.....<p style="float: right">'.$row_product['weight'].'</p></div>
                <div class="productprice">
                    <div class="pull-right">
                        <button type="button" class="btn btn-primary btn-sm">
                            <span class="glyphicon glyphicon-shopping-cart"></span>+
                        </button>
                    </div>
                <div class="pricetext">'.$row_product['product_price'].'</div></div>
            </div>';
                }
            ?>
<!--            Items END-->
<!--            Items END-->
<!--            Items END-->
        </div>
<!--        Here will be Comparison-->
<!--        Here will be Comparison-->
<!--        Here will be Comparison-->
        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="col-sm-3 col-md-12">
                <div class="well">

                </div>
                <div class="well">

                </div>
                <div class="well">

                </div>
                <div class="well">

                </div>
            </div>
        </div>
        <!--        Here will be Comparison END-->
        <!--        Here will be Comparison END-->
        <!--        Here will be Comparison END-->
    </div>
</div>
</body>
</html>

<?php session_start(); ?>
<?php
/**
 * Created by PhpStorm.
 * User: mdjul
 * Date: 06/03/2017
 * Time: 21:13
 */

require "DBHandler/Config.php";

$food_category = mysqli_query($dbconfig,"SELECT DISTINCT category_name FROM gr_item");
//Pagination and main page load [START]
if (isset($_GET["page"])) { $requested_page  = $_GET["page"]; } else { $requested_page=1; };

$basic_limit = 30;
$disable = "";
$current_page = ($requested_page-1)*$basic_limit;

if($current_page == 0){$disable = "display:none;";}

if(isset($_GET['search'])){
    $q = $_GET['query'];

    $_SESSION['temp'] = "&search=".$q;

    if (isset($_GET["page"])) { $requested_page  = $_GET["page"]; } else { $requested_page=1; };

    $current_page = ($requested_page-1)*$basic_limit;

    $products_search = mysqli_query($dbconfig,"SELECT * FROM gr_item g WHERE g.product_name LIKE '%$q%'");

    $page_count = mysqli_query($dbconfig,"SELECT COUNT(*) AS total FROM gr_item g WHERE g.product_name LIKE '%$q%'");
    $row_page_count = $page_count->fetch_assoc();
    $last_page = round(intval($row_page_count['total'])/30);
}

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
//Add to CART
if(isset($_POST['add'])){

}
//Add to CART
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
//        var query = window.location.search.substring(1)
//
//        if(query.length) {
//            if(window.history != undefined && window.history.pushState != undefined) {
//                window.history.pushState({}, document.title, window.location.pathname);
//            }
//        }
    </script>


    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "ca-pub-3712189946123514",
            enable_page_level_ads: true
        });
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
<div style="padding-top: 70px;padding-bottom: 100px" class="container">
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
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="index.php">All categories</a></li>
                            <?php
                                while ($row_category = mysqli_fetch_array($food_category)){
                                    echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?category='.$row_category['category_name'].'">'.$row_category['category_name'].'</a></li>';
                                }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div style="margin-bottom: 10px" class="col-sm-3 col-md-5">
                <form action="index.php" method="get" class="form-inline">
                    <div class="form-group mx-sm-3">
                        <input id="query" name="query" type="text" class="form-control" placeholder="Search by name">
                    </div>
                    <input id="search" name="search" value="Search" type="submit" class="btn btn-default" style="background-color: darkslategray;color: white">
                </form>
            </div>
        </div>
        <!--        -->
        <!--        -->
        <!--        this is pagination   [START]-->
        <!--        -->
        <!--        -->
        <?php
            if($_GET['category']=="" && $_GET['store']=="" && $_GET['search']==""){
                echo '<div id="page" style="margin-top: -20px;font-family: Calibri;" class="col-sm-6 col-md-6 col-lg-4">
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
                if($_GET['search']!=""){
                    while($row_product_search = mysqli_fetch_array($products_search)){
                        echo '<a href="#">
            <div data-uniq = "'.$row_product_search['unique_code'].'" data-id="'.$row_product_search['id'].'" data-name="'.$row_product_search['product_name'].'" data-category="'.$row_product_search['category_name'].'" data-outlet="'.$row_product_search['outlet_name'].'" data-price="'.$row_product_search['product_price'].'" data-image="'.$row_product_search['product_image'].'" style="margin-right:10px;height: 220px;width:220px;color:darkslategray;font-family: Calibri;" class="col-md-4 column productbox">
                <div class="item">
<!--                    <span class="notify-badge">Offer!</span>-->
                    <img style="width: 100px;height: 100px;" src="'.$row_product_search['product_image'].'" class="img-responsive">
                </div>
                <div class="producttitle">'.substr($row_product_search['product_name'],0,15).'.....<p style="float: right">'.$row_product_search['weight'].'</p></div>
                <div class="productprice">
                    <div class="pull-right">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <span class="glyphicon glyphicon-shopping-cart"></span>+
                        </button>
                    </div>
                <div class="pricetext">'.$row_product_search['product_price'].'</div></div>
            </div></a>
          
            ';
                    }
                }else{
                    while($row_product = mysqli_fetch_array($products)){
                        echo '<a href="#">
            <div data-uniq = "'.$row_product['unique_code'].'" data-id="'.$row_product['id'].'" data-name="'.$row_product['product_name'].'" data-category="'.$row_product['category_name'].'" data-outlet="'.$row_product['outlet_name'].'" data-price="'.$row_product['product_price'].'" data-image="'.$row_product['product_image'].'" style="margin-right:10px;height: 220px;width:220px;color:darkslategray;font-family: Calibri;" class="col-md-4 column productbox">
                <div class="item">
<!--                    <span class="notify-badge">Offer!</span>-->
                    <img style="width: 100px;height: 100px;" src="'.$row_product['product_image'].'" class="img-responsive">
                </div>
                <div class="producttitle">'.substr($row_product['product_name'],0,15).'.....<p style="float: right">'.$row_product['weight'].'</p></div>
                <div class="productprice">
                    <div class="pull-right">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <span class="glyphicon glyphicon-shopping-cart"></span>+
                        </button>
                    </div>
                <div class="pricetext">'.$row_product['product_price'].'</div></div>
            </div></a>
          
            ';
                    }
                }
            ?>
<!--            Items END-->
<!--            Items END-->
<!--            Items END-->
        </div>
        <!--            MODAL-->
        <!--            MODAL-->
        <!--            MODAL-->
        <link rel="stylesheet" type="text/css" href="Semantic/semantic.min.css">
        <script
                src="https://code.jquery.com/jquery-3.1.1.min.js"
                integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
                crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="Semantic/semantic.min.js"></script>

        <script type="text/javascript">
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {

            }else{
                $(document).ready(function(){
                    $(".col-md-4.column.productbox").click(function(){
                        var uid = $(this).attr("data-uniq");
                        var id = $(this).attr("data-id");
                        var name = $(this).attr("data-name");
                        var category = $(this).attr("data-category");
                        var outlet = $(this).attr("data-outlet");
                        var price = $(this).attr("data-price");
                        var image = $(this).attr("data-image");
                        var t_img = document.getElementById("modal_img");
                        t_img.src = image;
                        document.getElementById("itemName").innerHTML = name;
                        document.getElementById("outletName").innerHTML = outlet;
                        document.getElementById("itemPrice").innerHTML = price;
                        document.getElementById("itemCategory").innerHTML = category;
                        document.getElementById("itemID").value=id;
                    });
                    $(".item").click(function () {
                        $(".ui.modal").modal("show");
                    });
                });
            }
        </script>

        <div style="height: 400px" class="ui modal">
            <i class="close icon"></i>
            <div id="itemName" class="header">
                Name
            </div>
            <div class="image content">
                <div class="ui medium image">
                    <img id="modal_img" style="width: 200px;height:200px;" src="">
                </div>
                <div class="description">
                    <div style="color: grey" class="ui header">Outlet : <strong><p id="outletName">OUTLET</p></strong></div>
                    <div style="color: grey" class="ui header">Price : <strong><p id="itemPrice">PRICE</p></strong></div>
                    <p style="color: grey">Category : <strong><p id="itemCategory">CATEGORY</p></strong></p>
                </div>

                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                <script>
                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                            ['Date', 'Price'],
                            <?php
                            $chart = mysqli_query($dbconfig,"SELECT created_date,product_price FROM gr_item WHERE unique_code='coldstorage7-UP6S' order by created_date asc");
                             while($row_chart = mysqli_fetch_array($chart)){
                                 echo "['".date('M j', strtotime($row_chart['created_date']))."', ".intval($row_chart["product_price"])."],";
                             }
                            ?>
//                            ['01-03-2013',  10.00],
//                            ['01-03-2014',  11.70],
//                            ['01-03-2015',  6.60],
//                            ['01-03-2016',  10.30],
                        ]);

                        var options = {
                            title: 'Price difference',
                            hAxis: {title: 'Date',  titleTextStyle: {color: '#333'}},
                            vAxis: {minValue: 0}
                        };

                        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
                        chart.draw(data, options);
                    }
                </script>
                <div id="chart_div" class="pull-left" style="width: 400px; height: 100px;"></div>
            </div>
            <div class="input-group pull-left" style="width:15%;margin-left: 100px">
									               	<span class="input-group-btn">
									                  	<button class="btn btn-white btn-minuse" type="button">-</button>
									               	</span>
                <input type="text" class="form-control no-padding add-color text-center height-25" min="1" maxlength="3" value="1">
                <span class="input-group-btn">
									                  	<button class="btn btn-red btn-pluss" type="button">+</button>
									               	</span>
            </div>
            <script>
                $('.btn-minuse').on('click', function(){
                    $(this).parent().siblings('input').val(parseInt($(this).parent().siblings('input').val()) - 1)
                });

                $('.btn-pluss').on('click', function(){
                    $(this).parent().siblings('input').val(parseInt($(this).parent().siblings('input').val()) + 1)
                });

            </script>

            <form action="index.php" method="post">
                <input hidden value="" id="itemID" name="itemID" type="text">
                <input id="add" name="add" value="Add to cart" type="submit" class="btn btn-success pull-right" style="margin-right:50px;">
            </form>
        </div>
        <!--            MODAL-->
        <!--            MODAL-->
        <!--            MODAL-->
<!--        Here will be Comparison-->
<!--        Here will be Comparison-->
<!--        Here will be Comparison-->
        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="col-sm-3 col-md-12">
                <div style="background-color: #4C7591;color:white" class="well">
                    <p style="font-weight:700;font-size:20px;font-family: Calibri"> Siong Seng</p>
                    <hr>
                    <p style="font-family: Calibri">Name<strong>Weight</strong></p>
                    <p style="font-family: Calibri">Quantity<strong>Price</strong></p>
                    <hr>
                    <p style="float:right;font-family: Calibri;font-size: 15px">Price<strong>DOLLAR</strong></p>
                    <hr>
                </div>
                <div style="background-color: #EE2E24;color:white" class="well">
                    <p style="font-weight:700;font-size:20px;font-family: Calibri">FairPrice</p>
                    <hr>
                    <p style="font-family: Calibri">Name<strong>Weight</strong></p>
                    <p style="font-family: Calibri">Quantity<strong>Price</strong></p>
                    <hr>
                    <p style="float:right;font-family: Calibri;font-size: 15px">Price<strong>DOLLAR</strong></p>
                    <hr>
                </div>
                <div style="background-color: #0DAF4A;color:white" class="well">
                    <p style="font-weight:700;font-size:20px;font-family: Calibri;">Cold Storage</p>
                    <hr>
                    <p style="font-family: Calibri">Name<strong>Weight</strong></p>
                    <p style="font-family: Calibri">Quantity<strong>Price</strong></p>
                    <hr>
                    <p style="float:right;font-family: Calibri;font-size: 15px">Price<strong>DOLLAR</strong></p>
                    <hr>
                </div>
                <div style="background-color: #0855A0;color:white" class="well">
                    <p style="font-weight:700;font-size:20px;font-family: Calibri;">TESCO (Johor Bahru Malaysia)</p>
                    <hr>
                    <p style="font-family: Calibri">Name<strong>Weight</strong></p>
                    <p style="font-family: Calibri">Quantity<strong>Price</strong></p>
                    <hr>
                    <p style="float:right;font-family: Calibri;font-size: 15px">Price<strong>DOLLAR</strong></p>
                    <hr>
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

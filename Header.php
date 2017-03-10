<?php
/**
 * Created by PhpStorm.
 * User: mdjul
 * Date: 06/03/2017
 * Time: 23:50
 */
require "DBHandler/Config.php";
//require "DBHandler/init.php";
session_start();

//FACEBOOK SDK   [START]
//$permissions = array(); // optional
//$helper = $fb->getRedirectLoginHelper();
//$accessToken = $helper->getAccessToken();
//
//
//if (isset($accessToken)) {
//
//    $url = "https://graph.facebook.com/v2.5/me?fields=id,name,gender,email,picture,cover&access_token={$accessToken}";
//    $headers = array("Content-type: application/json");
//
//
//    $ch = curl_init();
//    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//    curl_setopt($ch, CURLOPT_URL, $url);
//    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//    curl_setopt($ch, CURLOPT_COOKIEJAR,'cookie.txt');
//    curl_setopt($ch, CURLOPT_COOKIEFILE,'cookie.txt');
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//
//    $st=curl_exec($ch);
//    $result=json_decode($st,TRUE);
//    echo "My name: ".$result['name'];
//    echo "<img src=".$result['cover']['source'].">";
//
//}

//FACEBOOK SDK   [END]
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/bootstrap-social.css">
    <link rel="stylesheet" href="CSS/bootstrap.css">
    <link rel="stylesheet" href="CSS/font-awesome.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="JS/Main.js" type="text/javascript"></script>
    <script src="JS/jquery.js" type="text/javascript"></script>

</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation" id="slide-nav">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-toggle">
                <span class="sr-only">Grocery Robot</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
<!--            <a href="#" class="navbar-left"><img src="Assets/grocery.png" width="60" height="50"></a>-->
            <a style="color: white;font-weight: 500;font-size: x-large;" class="navbar-brand" href="index.php">Grocery Robot</a>
        </div>
        <div id="slidemenu">
<!--            --><?php //if (!isset($accessToken)):
//                $loginUrl = $helper->getLoginUrl('http://grocery-robot.com/TestFlight/', $permissions);
//                ?>
            <form class="navbar-form navbar-right" role="form">
                <a href="" class="btn btn-block btn-social btn-facebook">
                    <span class="fa fa-facebook"></span><span style="margin-left: 25px">Login with Facebook</span>
                </a>
            </form>
<!--            --><?php //endif;?>
            <ul class="nav navbar-nav">
                <li class="active"><a href="index.php">Grocompare</a></li>
                <li><a href="#about">Blog</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        //stick in the fixed 100% height behind the navbar but don't wrap it
        $('#slide-nav.navbar-inverse').after($('<div class="inverse" id="navbar-height-col"></div>'));

        $('#slide-nav.navbar-default').after($('<div id="navbar-height-col"></div>'));

        // Enter your ids or classes
        var toggler = '.navbar-toggle';
        var pagewrapper = '#page-content';
        var navigationwrapper = '.navbar-header';
        var menuwidth = '100%'; // the menu inside the slide menu itself
        var slidewidth = '80%';
        var menuneg = '-100%';
        var slideneg = '-80%';

        $("#slide-nav").on("click", toggler, function (e) {

            var selected = $(this).hasClass('slide-active');

            $('#slidemenu').stop().animate({
                left: selected ? menuneg : '0px'
            });

            $('#navbar-height-col').stop().animate({
                left: selected ? slideneg : '0px'
            });

            $(pagewrapper).stop().animate({
                left: selected ? '0px' : slidewidth
            });

            $(navigationwrapper).stop().animate({
                left: selected ? '0px' : slidewidth
            });

            $(this).toggleClass('slide-active', !selected);
            $('#slidemenu').toggleClass('slide-active');

            $('#page-content, .navbar, body, .navbar-header').toggleClass('slide-active');

        });

        var selected = '#slidemenu, #page-content, body, .navbar, .navbar-header';

        $(window).on("resize", function () {

            if ($(window).width() > 767 && $('.navbar-toggle').is(':hidden')) {
                $(selected).removeClass('slide-active');
            }
        });
    });
</script>
</body>
</html>

<?php
/**
 * Created by PhpStorm.
 * User: mdjul
 * Date: 06/03/2017
 * Time: 23:50
 */
error_reporting(0);
require "DBHandler/Config.php";
session_start();
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $img = $_POST['img'];
    $bday = $_POST['bday'];
    $edu = $_POST['edu'];
    $ht = $_POST['ht'];
    $loc = $_POST['loc'];
    $work = $_POST['work'];
    $query = mysqli_query($dbconfig,"INSERT INTO user_data (id,fb_id,fb_name,img,fb_email,fb_firstname,fb_lastname,fb_bday,fb_edu,fb_home,fb_location,fb_work)
SELECT * FROM (SELECT NULL,'$id','$name','$img','$email','$fname','$lname','$bday','$edu','$ht','$loc','$work')
AS tmp WHERE NOT EXISTS (SELECT fb_email FROM user_data WHERE fb_email='$email') LIMIT 1");

}

?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml" lang="en">
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

    <script type="text/javascript">
        window.setInterval(function() {
        $(document).ready(function () {
            var button = document.getElementById("btn");
            if(localStorage.hasOwnProperty("email")){
                console.log(localStorage.getItem("email"));
                button.style.display = "none";
            }
        });
        }, 30);
    </script>

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

            <fb:login-button  size="large" autologoutlink="true" scope="public_profile,email" onlogin="checkLoginState();" id="btn" style="background-color: Transparent;margin-top:10px" class="navbar-form navbar-right" role="button">
            </fb:login-button>

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
    //FACEBOOK SDK   [START]
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '147468312440100',
            xfbml      : true,
            version    : 'v2.8'
        });
        FB.AppEvents.logPageView();
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    function checkLoginState() {
        FB.getLoginStatus(function(response) {
            statusChangeCallback(response);
        });
    }

    function statusChangeCallback(response) {
        console.log('statusChangeCallback');
        console.log(response);

        if (response.status === 'connected') {
            // Logged into your app and Facebook.
            testAPI();
        } else {
            FB.login(function(response) {
                if (response.authResponse){
                    testAPI(response)
                } else {
                    console.log('Auth cancelled.')
                }
            }, { scope: 'email' });
        }
    }


    // Here we run a very simple test of the Graph API after login is
    // successful.  See statusChangeCallback() for when this call is made.
    function testAPI() {
        FB.login(
            function(response) {
                if (response.authResponse) {
                    FB.api('/me',{"fields":"id,name,email,first_name,last_name,birthday,education,hometown,location,work"}, function(response) {
                        //var im = document.getElementById("image").setAttribute("src", "http://graph.facebook.com/" + response.id + "/picture?type=normal");


                        var img_link = "http://graph.facebook.com/" + response.id + "/picture?type=normal";

                        var hr = new XMLHttpRequest();

                        var url = "Header.php";
                        //"firstname="+response.id+"&lastname="+response.na
                        var vars = "id="+response.id+"&name="+response.name+"&fname="+response.first_name+"&lname="+response.last_name+"&img="+img_link+
                            "&bday="+response.birthday+"&edu="+response.education[0].school.name+"&ht="+response.hometown.name+"&loc="+response.location.name+"&work="+response.work[0].name+"&email="+response.email;
                        hr.open("POST", url, true);
                        hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                        hr.onreadystatechange = function() {
                            if(hr.readyState == 4 && hr.status == 200) {
                                var return_data = hr.responseText;
                            }
                        }
                        localStorage.setItem("email",response.email);
                        localStorage.setItem("id",response.id);

                        hr.send(vars); // Actually execute the request
                    });
                } else {
                    console.log('User cancelled login or did not fully authorize.');
                }
            },
            {scope:'email,user_birthday,user_education_history,user_hometown,user_location,user_work_history'}
        );
    }
    function logHimOut(){
        FB.logout(function(response) {

        });
    }
    //FACEBOOK SDK   [END]
</script>
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
<?php
/**
 * Created by PhpStorm.
 * User: mdjul
 * Date: 10/03/2017
 * Time: 17:54
 */
echo phpversion();

phpinfo();
?>

xmlns:fb="http://www.facebook.com/2008/fbml"

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

$_SESSION['email'] = $email;
$_SESSION['id'] = $id;
}












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

                        hr.send(vars); // Actually execute the request
                        window.location.reload();
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
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation" id="slide-nav">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-toggle">
                <span id="font" class="sr-only">Grocery Robot</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a style="color: white;font-weight: 500;font-size: x-large;" class="navbar-brand" href="index.php">Grocery Robot</a>
        </div>
        <div id="slidemenu">
            <?php
            if($_SESSION['id']!=""){
                echo ' <img class="pull-right" style="width: 40px;height: 40px;border-radius: 4px;margin-top: 5px" src="http://graph.facebook.com/'.$_SESSION["id"].'/picture?type=normal" id = "image">';
            }else{
                echo '<fb:login-button autologoutlink="true" scope="public_profile,email" onlogin="checkLoginState();" id="btn" style="background-color: Transparent;" class="navbar-form navbar-right" role="button">
            </fb:login-button>';
            }
            ?>

            <!--            <a id="btn_after_login" style="text-decoration: none;" href="" class="btn btn-block btn-social btn-facebook">-->
            <!--                <span class="fa fa-facebook"></span><span style="margin-left: 25px">Login with Facebook</span>-->
            <!--            </a>-->
            <!--            -->
        </div>
        <ul class="nav navbar-nav">
            <li class="active"><a id="font" href="index.php">Grocompare</a></li>
            <li><a id="font" href="#about">Blog</a></li>
            <li><a id="font" href="#about">About</a></li>
            <li><a id="font" href="#contact">Contact</a></li>
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
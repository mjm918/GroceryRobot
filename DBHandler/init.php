<?php
/**
 * Created by PhpStorm.
 * User: mdjul
 * Date: 12/03/2017
 * Time: 00:55
 */
//include "../vendor/autoload.php";
//use Facebook\Facebook;
//
//$fb = new Facebook([
//    'app_id' => '{147468312440100}', // Replace {app-id} with your app id
//    'app_secret' => '{cdf3dfede3507396345277a82a1acd80}',
//    'default_graph_version' => 'v2.8',
//]);
//$helper = $fb->getRedirectLoginHelper();
//
//$permissions = ['email']; // Optional permissions
//$loginUrl = $helper->getLoginUrl('http://grocery-robot.com/TestFlight/DBHandler/callback.php', $permissions);
//echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
echo $_SESSION['id'].$_SESSION['email']."<=";
?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <title>Document</title>
</head>
<body>
<script>
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
                        var im = document.getElementById("image").setAttribute("src", "http://graph.facebook.com/" + response.id + "/picture?type=normal");

                        var img_link = "http://graph.facebook.com/" + response.id + "/picture?type=normal";

                        var hr = new XMLHttpRequest();

                        var url = "init.php";
                        //"firstname="+response.id+"&lastname="+response.na
var vars = "id="+response.id+"&name="+response.name+"&fname="+response.first_name+"&lname="+response.last_name+"&img="+img_link+
"&bday="+response.birthday+"&edu="+response.education[0].school.name+"&ht="+response.hometown.name+"&loc="+response.location.name+"&work="+response.work[0].name+"&email="+response.email;
                        hr.open("POST", url, true);
                        hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                        hr.onreadystatechange = function() {
                            if(hr.readyState == 4 && hr.status == 200) {
                                var return_data = hr.responseText;
                                document.getElementById("status").innerHTML = return_data;
                            }
                        }

                        hr.send(vars); // Actually execute the request
                        document.getElementById("status").innerHTML = "processing...";

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
            document.getElementById("status").innerHTML = "logged out";
        });
    }
</script>

<?php
require_once "Config.php";
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
    $query = mysqli_query($dbconfig,"insert into user_data (id,fb_id,fb_name,img,fb_email,fb_firstname,fb_lastname,fb_bday,fb_edu,fb_home,fb_location,fb_work)
    values (NULL,'$id','$name','$img','$email','$fname','$lname','$bday','$edu','$ht','$loc','$work')");

    if($query){
        echo 'Success';
    }else{
        echo 'Failed';
    }
}
?>

<fb:login-button autologoutlink="true" scope="public_profile,email" onlogin="checkLoginState();">
</fb:login-button>

<img id = "image">

<div id="status">
</div>
<button onclick="logHimOut()">logout</button>
</body>
</html>

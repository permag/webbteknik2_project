<?php 

require 'src/facebook.php';
require_once('model/Database.php');
require_once('fb.php');

if ($user) {
  $_SESSION['activeUserId'] = $user_profile['id'];

  //db
  $db = new Database();
  $db->createDatabase('sqlite:data/sillyPlayDB.sqlite');
  $stmt = $db->select('SELECT * FROM Alster WHERE externalUserId = :externalUserId', array(':externalUserId' => $_SESSION['activeUserId']));
}

?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <meta content='width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;' name='viewport' />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/basic.css">
    <link rel="stylesheet" type="text/css" href="css/layout.css">
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="http://code.jquery.com/jquery-1.8.2.js"></script>
    <script src="//connect.facebook.net/en_US/all.js"></script>
    <title>sillyPlay</title>

  </head>
  <body>

    <!-- JAVASCRIPT LOGIN -->
    <div id="fb-root"></div>
    <script>
      // Additional JS functions here
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '431206723613188', // App ID
          channelUrl : 'http://localhost/dev/webbteknik2_project/', // Channel File
          status     : true, // check login status
          cookie     : true, // enable cookies to allow the server to access the session
          xfbml      : true  // parse XFBML
        });

        // Additional init code here
        FB.getLoginStatus(function(response) {
            if (response.status === 'connected') {
                // connected
            } else if (response.status === 'not_authorized') {
                // not_authorized
                login();
            } else {
                // not_logged_in
               // login();
            }

        });

        $(function(){
          $('#loginLink').click(function(e){
            login();
            e.preventDefault();
          });
          $('#logoutLink').click(function(e){
            logout();
            e.preventDefault();
          });
        });

      };

      // Load the SDK Asynchronously
      (function(d){
         var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement('script'); js.id = id; js.async = true;
         js.src = "//connect.facebook.net/en_US/all.js";
         ref.parentNode.insertBefore(js, ref);
       }(document));



      function login() {
          FB.login(function(response) {
              if (response.authResponse) {
                  // connected
                  testAPI();
              } else {
                  // cancelled
              }
          });
      }

      function testAPI() {
          console.log('Welcome!  Fetching your information.... ');
          FB.api('/me', function(response) {
              console.log('Good to see you, ' + response.name + '. id: ' + response.id);
          });
          window.location = 'index.php';
      }

      function logout() {
        FB.logout(function(response) {
          // user is now logged out
          window.location = 'index.php';
        });
      }

    </script>
    <!-- END LOGIN -->

<!-- NAVBAR -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="./">sillyPlay</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <?php
              if ($user) {
            ?>
              <li class="active"><a href="index.php">Home</a></li>
              <li class=""><a href="main.php">Create</a></li>
              <li class=""><a href="global.php">Global</a></li>
            <?php
              }
              ?>
            </ul>
          </div>

        </div>
      </div>
    </nav>

    <div class="container main">


      <h1>sillyPlay</h1>
      <div class="continer pull-right">
        <h4>My stuff</h4>
        <div>
          <?php
            while ($row = $stmt->fetch()) {
              $alsterId = $row['alsterId'];
              $alsterUrl = $row['alsterUrl'];
              echo '<a href="share/?id='. $alsterId .'"><img src="alster/'. $alsterUrl .'" width="100" /></a>';
           }
          ?>
        </div>
      </div>

      <?php if ($user): ?>
        <?php
          if ($user) {
            echo '<img src="https://graph.facebook.com/'.$user.'/picture">';
            echo $user_profile['first_name'] . ' ' . $user_profile['last_name'];
          }
        ?>
        <a href="#" id="logoutLink">Logout</a>
      <?php endif ?>

      <?php if ($user): ?>


      <?php else: ?>
        <strong><em>Login using your Facebook account: </em></strong>
        <a href="#" id="loginLink">Login</a>
      <?php endif ?>
    </div>

    <!-- JavaScript files -->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
  </body>
</html>

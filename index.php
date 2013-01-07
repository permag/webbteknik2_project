<?php 

require 'src/facebook.php';
require_once('model/Database.php');
require_once('fb.php');

if ($user) {
  $_SESSION['activeUserId'] = $user_profile['id'];

  //db
  $db = new Database();
  $db->createDatabase('sqlite:data/sillyPlayDB.sqlite');
  $stmt = $db->select('SELECT * FROM Alster WHERE externalUserId = :externalUserId ORDER BY alsterId DESC', array(':externalUserId' => $_SESSION['activeUserId']));
}

?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <!-- <meta content='width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;' name='viewport' /> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/basic.css">
    <link rel="stylesheet" type="text/css" href="css/layout.css">
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="http://code.jquery.com/jquery-1.8.2.js"></script>
    <!-- <script src="//connect.facebook.net/en_US/all.js"></script> -->
    <title>sillyPlay</title>

  </head>
  <body>

    <!-- FB LOGIN -->
    <div id="fb-root"></div>

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
      <?php
      if ($user) {
        ?>
        <h4>My stuff <span id="deleteAlsterSpan"><a href="#" id="deleteAlster"><p class="icon-trash" title="Toggle trash can"></p></a></span></h4>
        <div id="trash">Drag and drop here to delete</div>
        <div id="myStuff">
        <?php
          $myAlsterId = 0;
            while ($row = $stmt->fetch()) {
              $myAlsterId++;
              $alsterId = $row['alsterId'];
              $alsterUrl = $row['alsterUrl'];
              echo '<a href="share/?id='. $alsterId .'" id="myAlster'.$myAlsterId.'" class="myAlster"><img src="alster/'. $alsterUrl .'" width="100" /></a>';
            }
        ?>
        </div>
        <?php
       }
      ?>
      </div>

        <?php
          if ($user) {
            echo '<img src="https://graph.facebook.com/'.$user.'/picture" class="profilePic">';
            echo '<span class="profileName">'.$user_profile['first_name'] . ' ' . $user_profile['last_name'].'</span>';
            echo '<button id="logoutLink" class="btn btn-mini">Logout</button>';
            echo '<a href="main.php" id="createButton" class="btn btn-large btn-primary">Start creating!</a>';
          }
        ?>

      <?php if (!$user): ?>
        <strong><em>Login using your Facebook account: </em></strong>
        <button id="loginLink" class="btn">Login</button>
      <?php endif ?>
    </div>

    <!-- JavaScript files -->
    <script src="js/fb_login.js"></script>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <script src="js/jquery.masonry.min.js"></script>
    <script src="js/home.js"></script>
  </body>
</html>

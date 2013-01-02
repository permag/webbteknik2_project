<?php
// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '431206723613188',
  'secret' => '05949e9ad0b4f4dd85a67f5fc06996d0',
));


// Get User ID
$user = $facebook->getUser();

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}
?>
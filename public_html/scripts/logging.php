<?php

// Functions:
// login($uname, $pword, $remember) - log in
// logout() - log out

// inlcudes
require_once('utility.php');
require_once('databaseConnection.php');

// function: log in
function login($uname, $pword, $remember) { // $uname is already 'clean'
 global $dbConnection;
 session_start(); // start php session
 $pword = hashPassword($pword);
 $result = mysqli_query($dbConnection, "SELECT userID FROM users WHERE userName='$uname' AND password='$pword'");
 if($result && mysqli_num_rows($result) == 1) { // success
  $uID = mysqli_fetch_row($result);
  session_regenerate_id();
  $_SESSION['USER'] = $uname;
  $_SESSION['USERID'] = $uID[0];
  session_write_close();

  if($remember) { // save cookie password if remember enabled
   // use old cookie password to allow remembering on mutliple devices
   $result = mysqli_query($dbConnection, "SELECT cookiePassword FROM users WHERE userName='$uname'");
   if($result && mysqli_num_rows($result) < 1) { // cookie password not set
    $cpass = randomAlphanumeric(64); // generate cookie password
    mysqli_query($dbConnection, "UPDATE users SET cookiePassword='$cpass' WHERE userName='$uname'");
   } else {
    $row = mysqli_fetch_row($result);
    $cpass = $row[0];
   }
   $expire = time() + 2592000; // good for one month
   setcookie('username', $uname, $expire); // remember me
   setcookie('cookiepass', $cpass, $expire);
  } else { // clear any cookies possibly present
   setcookie('username', '', time()); // don't remember
   setcookie('cookiepass', '', time());
  }

  return 1;
 }
 return 0;
}

// function: log out
function logout() {
 global $dbConnection;
 session_start(); // start php session
 unset($_SESSION['USER']); // end
 unset($_SESSION['USERID']); // session
 setcookie('username', '', time() - 1); // remove cookies
 setcookie('cookiepass', '', time() - 1);
 $cblock = randomAlphanumeric(64); // new random cookie password
 mysqli_query($dbConnection, "UPDATE users SET cookiePassword='$cblock' WHERE userName='$uname'"); // prevent cookie login
}

?>

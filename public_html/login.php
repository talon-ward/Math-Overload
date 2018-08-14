<?php

 // includes
 require_once('scripts/utility.php');
 require_once('scripts/logging.php');

 $success = 0;
 if($_POST['sButton'] == 'Sign In') { // log in  
  $uname = stringClean($_POST['uname']);
  $pword = $_POST['pword'];
  $remember = $_POST['rememberMe'];
  $success = login($uname, $pword, $remember);
  if(!$success) {
   setcookie('error-signin', 1, time() + 60);
  }
 } elseif($_POST['sButton'] == 'Register') { // create new account
  require_once('scripts/registration.php');
  $error = 0;
  $uname = stringClean($_POST['uname']);
  if($uname != $_POST['uname']) {
   setcookie('error-register-uname-chars', 1, time() + 60);
   $error = 1;
  }
  if(strlen($uname) > 32) {
   setcookie('error-register-uname-length', 1, time() + 60);
   $error = 1;
  }
  $pword = $_POST['pword'];
  if($pword != $_POST['cpword']) {
   setcookie('error-register-pass', 1, time() + 60);
   $error = 1;
  }
  $email = stringClean($_POST['email']);

  if($error == 0) {
   addMember($uname, $pword, $email);
   $success = login($uname, $pword, 0); // log in automatically
  } else {
   setcookie('error-register-failure', 1, time() + 60);
  }
 }

 if($success) {
  if(array_key_exists('loginRedirect', $_COOKIE)) {
   $page = $_COOKIE['loginRedirect'];
   setcookie('loginRedirect', 'index.php', time());
   header("location: $page");
  } else {
   header('location: index.php');
  }
 } else {
  echo mysqli_error();
  header('location: signin.php');
 }

?>

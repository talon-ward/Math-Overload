<?php include('scripts/initialize.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
 <head>
  <title>Math Overload - Sign In</title>

  <meta http-quiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="description" content="A free Mathematics site that presents the material in an effective and fun way." />

  <link rel="stylesheet" href="styles/default.css" />

 </head>
 <body>
  <?php include('scripts/header.php'); ?>

  <div class="page-wrapper">
   <?php include('scripts/page-header.php'); ?>
    <div class="sign-in" id="sign-in">
     <form method="post" action="login.php">
      <table>
       <?php

        $register = 0;
        if(isset($_COOKIE['error-signin']) && $_COOKIE['error-signin'] == 1) {
         echo "<tr><td colspan='2'><font color='cc3300'>There was an error signing in.</font></td></tr>";
         setcookie('error-signin', 0, time());
        }

        if(isset($_COOKIE['error-register-failure']) && $_COOKIE['error-register-failure'] == 1) {
         echo "<tr><td colspan='2'><font color='cc3300'>Registration Error: Most likely a duplicate username.</font></td></tr>";
         setcookie('error-register-failure', 0, time());
        }

       ?>
       <tr>
        <td class="prompt">User Name:</td><td><input type="text" name="uname" /></td>
       </tr>
       <tr>
        <td class="prompt">Password:</td><td><input type="password" name="pword" /></td>
       </tr>
       <tr>
        <td colspan="2"><input type="checkbox" name="rememberMe" value="1" /> Remember me</td>
       </tr>
       <tr>
        <td colspan="2"><input type="submit" name="sButton" value="Sign In" /></td>
       </tr>
      </table>
     </form>
    </div>
    <div class="sign-in-hide expand-click" id="sign-in-hide">
     <a href="#" onClick="document.getElementById('sign-in').style.display='none'; document.getElementById('sign-in-hide').style.display='none'; document.getElementById('register').style.display='block'; document.getElementById('register-hide').style.display='block';">[click here to register]</a>
    </div>
    <div class="register" id="register">
     <form method="post" action="login.php">
      <table>
       <?php

        if(isset($_COOKIE['error-register-uname-chars']) && $_COOKIE['error-register-uname-chars'] == 1) {
         echo "<tr><td colspan='2'><font color='cc3300'>User name can't contain funky characters.</font></td></tr>";
         setcookie('error-register-uname-chars', 0, time());
         $register = 1;
        }

        if(isset($_COOKIE['error-register-uname-length']) && $_COOKIE['error-register-uname-length'] == 1) {
         echo "<tr><td colspan='2'><font color='cc3300'>User name can't be more than 32 characters.</font></td></tr>";
         setcookie('error-register-uname-length', 0, time());
         $register = 1;
        }

       ?>
       <tr>
        <td class="prompt">Choose a user name:</td><td><input type="text" name="uname" /></td>
       </tr>
       <tr class="prompt">
        <td>Pick a password:</td><td><input type="password" name="pword" /></td>
       </tr>
       <?php

        if(isset($_COOKIE['error-register-pass']) && $_COOKIE['error-register-pass'] == 1) {
         echo "<tr><td colspan='2'><font color='cc3300'>Passwords did not match.</font></td></tr>";
         setcookie('error-register-pass', 0, time());
         $register = 1;
        }

       ?>
       <tr class="prompt">
        <td>Confirm password:</td><td><input type="password" name="cpword" /></td>
       </tr>
       <tr class="prompt">
        <td>Enter email:</td><td><input type="text" name="email" /></td>
       </tr>
       <tr>
        <td colspan="2"><input type="submit" name="sButton" value="Register" /></td>
       </tr>
      </table>
     </form>
    </div>
    <div class="register-hide expand-click" id="register-hide">
     <a href="#" onClick="document.getElementById('sign-in').style.display='block'; document.getElementById('sign-in-hide').style.display='block'; document.getElementById('register').style.display='none'; document.getElementById('register-hide').style.display='none';">[click here to sign in]</a>
    </div>
  </div>

  <?php
   if($register==1) {
    echo "<script type='text/javascript'>document.getElementById('sign-in').style.display='none'; document.getElementById('sign-in-hide').style.display='none'; document.getElementById('register').style.display='block'; document.getElementById('register-hide').style.display='block';</script>";
   }
  ?>

  <?php include('scripts/footer.php'); ?>
 </body>
</html>

<?php include('scripts/initialize.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
 <head>
  <title>Math Overload - Alter Profile</title>

  <meta http-quiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="description" content="A free Mathematics site that presents the material in an effective and fun way." />

  <link rel="stylesheet" href="styles/default.css" />

 </head>
 <body>
  <?php

   include('scripts/header.php');
   forceLogin('profile.php');

  ?>

  <div class="page-wrapper">
  <?php

   include('scripts/page-header.php');

  ?>
   <p>Change email/password.</p>
  </div>

  <?php include('scripts/footer.php'); ?>
 </body>
</html>

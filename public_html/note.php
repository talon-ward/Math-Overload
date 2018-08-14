<?php include('scripts/initialize.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
 <head>
  <title>Math Overload - Note</title>

  <meta http-quiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="description" content="A free Mathematics site that presents the material in an effective and fun way." />

  <link rel="stylesheet" href="styles/default.css" />

  <?php include('scripts/latex.php'); ?>

 </head>
 <body>
  <?php include('scripts/header.php'); ?>

  <div class="page-wrapper">
   <?php

    include('scripts/page-header.php');
    require_once('scripts/mo-notes.php');

    if(isset($_GET['noteID'])) {
     $noteID = $_GET['noteID'];
    } else {
     echo "<meta http-equiv=\"Refresh\" content=\"0; URL=notes.php\" \>\n";
    }

   ?>

   <div  class="view-note">
    <?php echo file_get_contents("notes/$noteID.html"); ?>
   </div>

  </div>

  <?php include('scripts/footer.php'); ?>
 </body>
</html>

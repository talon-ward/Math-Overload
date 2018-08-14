<?php include('scripts/initialize.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
 <head>
  <title>Math Overload - Notes</title>

  <meta http-quiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="description" content="A free Mathematics site that presents the material in an effective and fun way." />

  <link rel="stylesheet" href="styles/default.css" />

 </head>
 <body>
  <?php include('scripts/header.php'); ?>

  <div class="page-wrapper">
   <?php

    include('scripts/page-header.php');

    require_once('scripts/mo-notes.php');

    $courseArray = getNoteInfo();
    echo "<div class=\"collapse-list-container\">\n";
    echo " <ul>\n";
    foreach($courseArray as $courseName => $sectionArray) {
     echo "  <li>$courseName</li>\n";
     echo "  <ul>\n";
     foreach($sectionArray as $sectionName => $noteArray) {
      echo "   <li>$sectionName</li>\n";
      echo "   <ul>\n";
      foreach($noteArray as $noteID => $noteName) {
       echo "    <li><a href=\"note.php?noteID=$noteID\">$noteName</a></li>\n";
      }
      echo "   </ul>\n";
     }
     echo "  </ul>\n";
    }
    echo " </ul>\n";
    echo "</div>\n";

   ?>
   <p>One day this list will be collapsible.</p>
  </div>

  <?php include('scripts/footer.php'); ?>
 </body>
</html>

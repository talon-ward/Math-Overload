<?php include('scripts/initialize.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
 <head>
  <title>Math Overload - Free Advanced Mathematics Assisted Self-Instruction Online</title>

  <meta http-quiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="description" content="A free Mathematics site that presents the material in an effective and fun way." />

  <link rel="stylesheet" href="styles/default.css" />

 </head>
 <body>
  <?php include('scripts/header.php'); ?>

  <div class="home-wrapper">
   <div class="home-logo"></div>

   <div class="home-search">
    <form method="get" action="#">
     <input type="text" name="q" id="q" onKeyUp="setSearchTimer()" autocomplete="off" onBlur="hideSearch()" onFocus="setSearchVars(); unhideSearch()" />
     <input type="hidden" name="t" id="t" value="link" />
     <input type="hidden" name="d" id="d" value="12" />
     <input type="hidden" name="i" id="i" value="q" />
    </form>
    <div id="search-output" class="search-box"></div>
   </div>

   <div class="home-menu"><a href="courses.php">Courses</a> | <a href="progress.php">Progress</a> | <a href="study.php">Study</a> | <a href="notes.php">Notes</a> | <a href="exercises.php">Exercises</a></div>

   <?php

    if(isset($userID) && $userID == 1) {
     echo "<div class='admin'>Welcome, Talon.<br /><br />\n";
     echo "Administrative tasks:<br />\n";
     echo "<ul><li><a href='createnote.php'>Create a note.</a></li>\n";
     echo "<li><a href='createquestion.php'>Create a question.</a></li>\n";
     echo "<li><a href='createexercise.php'>Create an exercise.</a></li></ul>\n";
     echo "Don't click this unless you really mean it: <br />\n";
     echo "<ul><li><a href='searchrebuild.php'>Rebuild search database.</a></li></ul></div>\n";
    }

   ?>

   <?php include('scripts/footer.php'); ?>
  </div>

 </body>
</html>

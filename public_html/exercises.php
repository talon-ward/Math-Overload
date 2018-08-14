<?php include('scripts/initialize.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
 <head>
  <title>Math Overload - Exercises</title>

  <meta http-quiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="description" content="A free Mathematics site that presents the material in an effective and fun way." />

  <link rel="stylesheet" href="styles/default.css" />

 </head>
 <body>
  <?php

   include('scripts/header.php');
   forcelogin('exercises.php');

  ?>

  <div class="page-wrapper">
   <?php

    include('scripts/page-header.php');
    require_once('scripts/mo-exercises.php');

   ?>
   <p><b>Open Exercises</b>:</p>
   <?php

    $openExercises = getOpenExercises($userID);
    if($openExercises != -1) {
     echo "<table>";
     foreach($openExercises as $exerciseID => $description) {
      echo "<tr><td class=\"exercise-view\">[<a href=\"exercise.php?exerciseID=$exerciseID\">view</a>]</td><td class=\"exercise-description\">$description</td></tr>";
     }
     echo "</table>";
    } else {
     echo "No open exercises.<br />";
    }

    echo "<div class=\"expand-click\">[<a href=\"addexercise.php\">add exercise</a>]</div>";

   ?>
  </div>

  <?php include('scripts/footer.php'); ?>
 </body>
</html>

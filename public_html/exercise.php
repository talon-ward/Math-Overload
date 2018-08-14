<?php include('scripts/initialize.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
 <head>
  <title>Math Overload - Exercise</title>

  <meta http-quiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="description" content="A free Mathematics site that presents the material in an effective and fun way." />

  <link rel="stylesheet" href="styles/default.css" />

  <?php include('scripts/latex.php'); ?>

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

    if(isset($_GET['exerciseID'])) {
     $exerciseID = $_GET['exerciseID'];
    } else {
     echo "<meta http-equiv=\"Refresh\" content=\"0; URL=exercises.php\" \>\n";
    }

   ?>

   <div  class="exercise">
    <?php echo file_get_contents("exercises/problems/$exerciseID.html"); ?>
   </div>

   <div class="show-answer expand-click" id="show-div"><a href="#" onClick="document.getElementById('show-div').style.display='none'; document.getElementById('solution-div').style.display='block';">[show solution]</a></div>
   <div class="study-answer" id="solution-div">
    <div class="exercise">
     <?php echo file_get_contents("exercises/solutions/$exerciseID.html") ?>
    </div>
    <div>
    <?
     if(!isOpenExercise($userID, $exerciseID)) {
      echo "This exercise is not open for you. Would you like to <a href=\"addexercise.php?exerciseID=$exerciseID\">open it</a>?\n";
     } else {
    ?>
     Based on this solution, I would rate my answer as an
     <form method="post" action="gradeexercise.php">
      <input type="radio" name="grade" value="4" />A |
      <input type="radio" name="grade" value="3" />B |
      <input type="radio" name="grade" value="2" />C |
      <input type="radio" name="grade" value="1" />D |
      <input type="radio" name="grade" value="0" />F
      <?php echo "<input type=\"hidden\" name=\"exerciseID\" value=\"$exerciseID\" />"; ?>
      <br /><br />
      <input type="submit" value="Grade">
     </form>
     <?
      }
     ?>
    </div>
   </div>
  </div>

  <?php include('scripts/footer.php'); ?>
 </body>
</html>

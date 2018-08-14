<?php include('scripts/initialize.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
 <head>
  <title>Math Overload - Answer</title>

  <meta http-quiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="description" content="A free Mathematics site that presents the material in an effective and fun way." />

  <link rel="stylesheet" href="styles/default.css" />

  <?php include('scripts/latex.php'); ?>

 </head>
 <body>
  <?php

   include('scripts/header.php');
   forceLogin('index.php');

  ?>

  <div class="page-wrapper">
   <?php

    include('scripts/page-header.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');

    require_once('scripts/mo-study.php');

    // get conceptID
    if(array_key_exists('conceptID', $_POST)) {
     $conceptID = $_POST['conceptID'];
    }

    if(!array_key_exists('allowedcID', $_SESSION) || $_SESSION['allowedcID'] != $conceptID) {
     echo "<p>It would appear you have already answered the question recently or have accessed the page from an invalid source.\n</p>";
    } else {
     if(array_key_exists('answer', $_POST) && $_POST['answer'] == 'right') { // Good job!
      $isCorrect = 1;
      $nImages = 1;
      $n = rand(0, $nImages - 1);
      echo "<br /><br /><img alt=\"Correct answer!\" src=\"images/correct-answer-$n.gif\" />\n" .
           "<br /><br /><br />\n" .
           "You answered the question correctly! <a href=\"study.php\">Continue studying</a>.\n<br /><br />\n";
     } else {
      $isCorrect = 0;
      echo "<br />That answer is incorrect.<br /><br />\n";
      $noteID = getNote($conceptID);
      if($noteID > 0) {
       echo "<div class=\"study-review\">\n";
       echo file_get_contents("notes/$noteID.html");
       echo "</div>\n";
      }
     }

     $buttonPressed = 1;
     if(array_key_exists('answerSubmit', $_POST) && $_POST['answerSubmit'] == 'I don\'t know') {
      $buttonPressed = 0;
     }
     if(array_key_exists('answerSubmit', $_POST) && $_POST['answerSubmit'] == 'I got this!') {
      $buttonPressed = 2;
     }

     $time = time();
     updateMemoryStrength($userID, $conceptID, $isCorrect, $buttonPressed, $time);
     $_SESSION['allowedcID'] = -1;
    }

   ?>

  </div>

  <?php include('scripts/footer.php'); ?>
 </body>
</html>

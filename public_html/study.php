<?php include('scripts/initialize.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
 <head>
  <title>Math Overload - Study</title>

  <meta http-quiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="description" content="A free Mathematics site that presents the material in an effective and fun way." />

  <link rel="stylesheet" href="styles/default.css" />

  <?php include('scripts/latex.php'); ?>

 </head>
 <body>
  <?php

   include('scripts/header.php');
   forceLogin('study.php');

  ?>

  <div class="page-wrapper">
   <?php

    include('scripts/page-header.php');

    require_once('scripts/mo-study.php');

    $qArray = getQuestion($userID);

    if($qArray == -1) {
     echo "<div class=\"centered\">There is no material for you to study at the moment.<br />Please try again later.</div>\n</div>\n";
     include('scripts/footer.php');
     echo "</body>\n</html>";
     die();
    }

    $_SESSION['allowedcID'] = $qArray[6];

    $noteID = getInterrupt($userID, $qArray[6]);
    if($noteID > 0) {
     echo "<div id=\"interrupt\">\n" .
          "<div class=\"expand-click\" id=\"show-int\"><a href=\"#\" onClick=\"document.getElementById('interrupt').style.display='none'; document.getElementById('show-int').style.display='none';\">[hide]</a></div>";
     echo "<div class=\"long-text\">";
     echo file_get_contents("notes/$noteID.html");
     echo "</div></div>\n";
    }

    $question = $qArray[0];
    echo "<div class=\"study-question\">$question</div>\n"

   ?>

   <div class="show-answer expand-click" id="show-div"><a href="#" onClick="document.getElementById('show-div').style.display='none'; document.getElementById('answer-div').style.display='block';">[show options]</a></div>

   <div class="study-answer" id="answer-div">
    <form method="post" action="studyanswer.php">
     <?php

     echo "<input type=\"hidden\" name=\"conceptID\" value=\"$qArray[6]\" />\n";

     ?>
     <table>
     <?php

      $rperm = range(1, 5); // Generate a random
      shuffle($rperm); // permutation of the answers.
      foreach($rperm as $index) {
       if(array_key_exists($index, $qArray)) {
        echo "<tr>\n";
        if($index == 1) { // correct answer
         echo " <td><input type=\"radio\" name=\"answer\" value=\"right\" /></td>\n";
        } else {
         echo " <td><input type=\"radio\" name=\"answer\" value=\"wrong\" /></td>\n";
        }
        echo " <td><br />$qArray[$index]<br /><br /></td>\n" .
             "</tr>\n";
       }
      }

     ?>
     </table>
     <input type="submit" name="answerSubmit" value="I don't know" />
     <input type="submit" name="answerSubmit" value="I had to peek" />
     <input type="submit" name="answerSubmit" value="I got this!" />
    </form>
   </div>

  </div>

  <?php include('scripts/footer.php'); ?>
 </body>
</html>

<?php

 echo "<script type=\"text/javascript\" src=\"scripts/search.jss\"></script>";
 echo "<div class=\"toplinks linklist\">";
 if($isLoggedIn) {
  $uname = $_SESSION['USER'];
  echo " (<a href=\"profile.php\" class=\"username\">$uname</a>) <a href=\"logout.php\">Log out</a>";
 } else {
  echo " <a href=\"signin.php\">Sign in</a>";
 }
 echo "|<a href=\"feedback.php\">Feedback</a>" .
      "</div>";

?>

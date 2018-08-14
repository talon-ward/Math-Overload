<?php

// includes
require_once('scripts/authentication.php');
require_once('scripts/mo-exercises.php');

if(isset($_GET['exerciseID'])) {
 $exerciseID = $_GET['exerciseID'];
 if(isset($userID)) {
  $result = openExercise($userID, $exerciseID);
 }
} else {
 openNewExercise($userID);
}

// automatically redirect to exercises page
header('location: exercises.php');

?>

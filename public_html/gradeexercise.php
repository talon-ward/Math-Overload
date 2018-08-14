<?php

// includes
require_once('scripts/authentication.php');
require_once('scripts/mo-exercises.php');

if(isset($_POST['exerciseID'])) {
 $exerciseID = $_POST['exerciseID'];

 if(isset($_POST['grade'])) {
  $grade = (int)($_POST['grade']);

  if(isOpenExercise($userID, $exerciseID) && 0 <= $grade && 4 >= $grade) {
   updateExerciseStrength($userID, $exerciseID, $grade, time());
  }
 }
}

// automatically redirect to exercises page
header('location: exercises.php');

?>

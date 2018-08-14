<?php

// Functions:
// getOpenExercises($userID) - returns array of { exerciseID => description }
// openNewExercise($userID) - returns -1 on failure, 1 on success

// includes
require_once('utility.php');
require_once('databaseConnection.php');

$ADD_EXERCISE_SUCCESS = 0;
$ADD_EXERCISE_OPEN = 1;
$ADD_EXERCISE_PENDING = 2;
$ADD_EXERCISE_PREREQS = 3;



// function: returns array of pairs { exerciseID, description }
function getOpenExercises($userID) {
 global $dbConnection;

 $openExercises = array();

 $result = mysqli_query($dbConnection, "SELECT exerciseID FROM openexercises WHERE userID='$userID'");
 if(!$result || mysqli_num_rows($result) == 0) {
  return -1;
 }
 while($row = mysqli_fetch_row($result)) {
  $exerciseID = $row[0];

  $result2 = mysqli_query($dbConnection, "SELECT description FROM exercises WHERE exerciseID='$exerciseID'");
  if(!$result || mysqli_num_rows($result) < 1) {
   continue;
  }
  $row2 = mysqli_fetch_row($result2);
  $description = $row2[0];

  $openExercises[$exerciseID] = $description;
 }

 return $openExercises;
}



// function: returns exerciseID for new material based on current courses
function findNewExercise($userID) {
 global $dbConnection;
 $result = mysqli_query($dbConnection, "SELECT exerciseID FROM eligibleexercises WHERE userID='$userID' ORDER BY RAND()");
 if(!$result) { // no eligible exercises
  return -1;
 }
 while($row = mysqli_fetch_row($result)) {
  $exerciseID = $row[0];

  // check if exercise already seen
  $result2 = mysqli_query($dbConnection, "SELECT 1 FROM userexercises WHERE userID='$userID' AND exerciseID='$exerciseID'");
  if($result2 && (mysqli_num_rows($result2) == 0)) {
   return $exerciseID;
  } else {
   mysqli_query($dbConnection, "DELETE FROM eligibleexercises WHERE userID='$userID' AND exerciseID='$exerciseID'");
  }
 }

 return -1;
}



//function: returns ADD_EXERCISE_OPEN if already open, ADD_EXERCISE_PENDING if pending review, ADD_EXERCISE_PREREQS if prereqs not met, ADD_EXERCISE_SUCCESS on success.
function openExercise($userID, $exerciseID) {
 global $dbConnection;

 $result = mysqli_query($dbConnection, "SELECT 1 FROM eligibleexercises WHERE userID='$userID' AND exerciseID='$exerciseID'");
 if(mysqli_num_rows($result) > 0) { // eligible so skip everything else
  mysqli_query($dbConnection, "INSERT INTO openexercises (userID, exerciseID) VALUES ('$userID', '$exerciseID')");
  mysqli_query($dbConnection, "DELETE FROM eligibleexercises WHERE userID='$userID' and exerciseID='$exerciseID'");
  return $ADD_EXERCISE_SUCCESS;
 }

 // check if exercise already open
 $result = mysqli_query($dbConnection, "SELECT 1 FROM openexercises WHERE userID='$userID' AND exerciseID='$exerciseID'");
 if(mysqli_num_rows($result) > 0) { // already open
  return $ADD_EXERCISE_OPEN;
 }

 // check if exercise pending review
 $result = mysqli_query($dbConnection, "SELECT nextReview FROM userexercises WHERE userID='$userID' AND exerciseID='$exerciseID'");
 $now = time();
 while($row = mysqli_fetch_row($result)) {
  $nextReview = $row[0];
  if($nextReview > $now) { // pending review
   return $ADD_EXERCISE_PENDING;
  }
 }

 // check if prereqs met
 $result = mysqli_query($dbConnection, "SELECT prereqID FROM exerciseprereqs WHERE exerciseID='$exerciseID'");
 while($row = mysqli_fetch_row($result)) {
  $prereqID = $row[0];
  $result2 = mysqli_query($dbConnection, "SELECT strength FROM userconcepts WHERE userID='$userID' AND conceptID='$prereqID'");
  if(mysqli_num_rows($result2) < 1) { // prereq not met
   return $ADD_EXERCISE_PREREQ;
  }
 }

 // exercise not open, not pending, prereqs met
 mysqli_query($dbConnection, "INSERT INTO openexercises (userID, exerciseID) VALUES ('$userID', '$exerciseID')");
 mysqli_query($dbConnection, "DELETE FROM eligibleexercises WHERE userID='$userID' and exerciseID='$exerciseID'");
 return $ADD_EXERCISE_SUCCESS;
}



//function: returns -1 on failure, 1 on success
function openNewExercise($userID) {
 global $dbConnection;

 $result = mysqli_query($dbConnection, "SELECT exerciseID FROM openexercises WHERE userID='$userID'");
 $openExercises = array();
 $limit = mysqli_num_rows($result) + 1; // number of open exercises plus one
 while($row = mysqli_fetch_row($result)) { // get open exercises
  $openExercises[] = $row[0];
 }

 $result = mysqli_query($dbConnection, "SELECT exerciseID, nextReview FROM userexercises WHERE userID='$userID' ORDER BY nextReview ASC LIMIT $limit");
 $now = time();
 while($row = mysqli_fetch_row($result)) {
  $exerciseID = $row[0];
  $nextReview = $row[1];

  if(empty($nextReview) || $nextReview > $now) {
   break; // break while
  }

  foreach($openExercises as $eID) {
   if($eID == $exerciseID) { // exercise already open
    continue 2; // continue while to next exerciseID
   }
  }

  // exercise is up for review and not already open
  mysqli_query($dbConnection, "INSERT INTO openexercises (userID, exerciseID) VALUES ('$userID', '$exerciseID')");
  return 1;
 }

 // no exercise is up for review that isn't already open
 $exerciseID = findNewExercise($userID);
 if($exerciseID < 0) {
  return -1;
 }

 mysqli_query($dbConnection, "INSERT INTO openexercises (userID, exerciseID) VALUES ('$userID', '$exerciseID')");
 mysqli_query($dbConnection, "DELETE FROM eligibleexercises WHERE userID='$userID' and exerciseID='$exerciseID'");
 return 1;
}



// function: updates exercise strength based on grade
function updateExerciseStrength($userID, $exerciseID, $grade, $time) {
 global $dbConnection;

 $userID = stringClean($userID);
 $exerciseID = stringClean($exerciseID);

 // get old strength value
 $result = mysqli_query($dbConnection, "SELECT strength, lastSeen FROM userexercises WHERE userID='$userID' AND exerciseID='$exerciseID'");
 if($result && mysqli_num_rows($result) == 1) { // success
  $row = mysqli_fetch_row($result);
  $oldStrength = $row[0];
  $oldTime = $row[1];

  $dt = $time - $oldTime;

  // calculate factor based on probability
  $learningFactor = 5.929215229*sqrt($dt/$oldStrength);

  // adjust factor based on grade
  $learningFactor = ($grade - 2)*$learningfactor/2;

  // replace strength value with scaled strength value and update times in database
  $strength = $learningFactor * $oldStrength;
  if($strength < 820041) { // prevent it from asking too soon
   $strength = 820041;
  }
  $nextReview = round($strength * 0.105360516) + $time;
  mysqli_query($dbConnection, "UPDATE userexercises SET strength='$strength', lastSeen='$time', nextReview='$nextReview' WHERE userID='$userID' AND exerciseID='$exerciseID'");
 } else { // if no old strength then new exposure
  $strength = 820041;
  $nextReview = round($strength * 0.105360516) + $time;
  mysqli_query($dbConnection, "INSERT INTO userexercises (userID, exerciseID, strength, lastSeen, nextReview) VALUES ('$userID', '$exerciseID', '$strength', '$time', '$nextReview')");
 }

 // remove from openexercises
 mysqli_query($dbConnection, "DELETE FROM openexercises WHERE userID='$userID' AND exerciseID='$exerciseID'");
}

function isOpenExercise($userID, $exerciseID) {
 global $dbConnection;

 $result = mysqli_query($dbConnection, "SELECT 1 FROM openexercises WHERE userID='$userID' AND exerciseID='$exerciseID'");

 if($result && mysqli_num_rows($result) > 0) { // is open
  return true;
 }
 return false;
}

?>

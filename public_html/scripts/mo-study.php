<?php

// Functions:
// getQuestion($userID) - returns array { 0=>question, 1=>correct answer, 2-5=>incorrect answers, 6=>conceptID } or -1 on fail
// updateMemoryStrength($userID, $conceptID, $isCorrect, $buttonPressed, $time) - updates memory strength based on answer
// getInterrupt($userID, $conceptID) - returns noteID of interrupt or 0 if no interrupt

// includes
require_once('utility.php');
require_once('databaseConnection.php');



// function: returns array { 0=>question, 1=>correct answer, 2-5=>incorrect answers, 6=>conceptID } or -1 on fail
function getQuestion($userID) {
 global $dbConnection;

 // find previously seen concept to be reviewed soonest
 $review = mysqli_query($dbConnection, "SELECT MIN(nextReview) FROM userconcepts WHERE userID='$userID'");
 if($review) { // success
  $reviewItem = mysqli_fetch_row($review);
  $nextReview = $reviewItem[0];
  if(empty($nextReview) || $nextReview > time()) { // too soon - introduce new item instead
   $conceptID = findNewConcept($userID);
   if($conceptID < 0) { // no eligible concepts
    return -1;
   }
  } else { // time to review old concept
   $result = mysqli_query($dbConnection, "SELECT conceptID FROM userconcepts WHERE userID='$userID' AND nextReview='$nextReview'");
   if(!$result) { // this would be weird
    return -1;
   }
   $conceptItem = mysqli_fetch_row($result);
   $conceptID = $conceptItem[0];
  }
  $time = time();
 } else { // failure
  return -1;
 }

 // select random questionID
 $questionIDs = mysqli_query($dbConnection, "SELECT questionID FROM questions WHERE conceptID='$conceptID' ORDER BY RAND()"); // order by rand: slow but few entries
 if(!$questionIDs) { // no questions for concept
  return -1;
 }
 $qID = mysqli_fetch_array($questionIDs);
 $questionID = $qID['questionID'];

 // get question and correct answer
 $questions = mysqli_query($dbConnection, "SELECT questionText, answer FROM questions WHERE questionID='$questionID'");
 if(!$questions) { // failure
  return -1;
 }
 $question = mysqli_fetch_array($questions);
 $questionArray = array();
 $questionArray[0] = stringDirty($question['questionText']);
 $questionArray[1] = stringDirty($question['answer']);

 // get random incorrect answers
 $wronganswers = mysqli_query($dbConnection, "SELECT wrongAnswer FROM questionwronganswers WHERE questionID='$questionID' ORDER BY RAND()"); // order by rand: slow but few entries
 if($wronganswers) { // success
  for($i = 2; $i <= 5; ++$i) {
   if($wa = mysqli_fetch_row($wronganswers)) {
    $questionArray[$i] = stringDirty($wa[0]);
   } else {
    break;
   }
  }
 }

 $questionArray[6] = $conceptID;

 return $questionArray;
}



// function: adds concepts to eligibleconcepts that have conceptID as a prerequisite, are not already in eligible concepts, and have all prerequisites met
function checkEligibility($userID, $conceptID) {
 global $dbConnection;

 // get list of eligible concepts
 $eligibleConcepts = array();
 $result = mysqli_query($dbConnection, "SELECT conceptID FROM eligibleconcepts WHERE userID='$userID'");
 if($result) {
  if(mysqli_num_rows($result) > 0) { // success
   while($row = mysqli_fetch_array($result)) {
    $eligibleConcepts[] = $row[0];
   }
  }
 }

 // get list of selected courses
 $result = mysqli_query($dbConnection, "SELECT courseID FROM usercourses WHERE userID='$userID'");
 if(!$result) {
  break;
 }
 $courses = array();
 while($row = mysqli_fetch_row($result)) {
  $courses[] = $row[0];
 }

 $result = mysqli_query($dbConnection, "SELECT conceptID FROM conceptprereqs WHERE prereqID='$conceptID'");
 if($result) {
  while($row = mysqli_fetch_row($result)) {
   $concept = $row[0];

   // check if already in eligibleconcepts
   foreach($eligibleConcepts as $eConcept) {
    if($eConcept == $concept) { // already on list
     continue 2;
    }
   }

   // check if user has selected a course that uses this concept
   foreach($courses as $course) {
    $result2 = mysqli_query($dbConnection, "SELECT courseID FROM courseconcepts WHERE conceptID='$concept'");
    if($result2) {
     while($row2 = mysqli_fetch_row($result2)) {
      if($row2[0] == $course) { // user signed up for course that uses this concept
       break 2; // continue with checks
      }
     }
    }
    continue 2; // no course that uses this concept matches a selected course 
   }

   // check to see if all prereqs are met for concept
   $result2 = mysqli_query($dbConnection, "SELECT prereqID FROM conceptprereqs WHERE conceptID='$concept'");
   while($row2 = mysqli_fetch_row($result2)) {
    $prereq = $row2[0];
    $result3 = mysqli_query($dbConnection, "SELECT strength FROM userconcepts WHERE userID='$userID' AND conceptID='$prereq'");
    if(!$result3 || mysqli_num_rows($result3) == 0) { // prereq not met
     continue 2; // continue to next concept
    }
   }

   // concept is not already on list and all prerequisites are met
   mysqli_query($dbConnection, "INSERT INTO eligibleconcepts (userID, conceptID) VALUES ('$userID', '$concept')");
  }
 }


 // check for eligible exercises
 $result = mysqli_query($dbConnection, "SELECT exerciseID FROM exerciseprereqs WHERE prereqID='$conceptID'");
 if($result) {
  while($row = mysqli_fetch_row($result)) {
   $exerciseID = $row[0];

   // check if exercise already seen
   $result2 = mysqli_query($dbConnection, "SELECT 1 FROM userexercises WHERE userID='$userID' AND exerciseID='$exerciseID'");
   if($result2 && mysqli_num_rows($result2) > 0) { // already seen
    continue; // contine while to check next exerciseID
   }

   // check if all prereqs met
   $result2 = mysqli_query($dbConnection, "SELECT prereqID FROM exerciseprereqs WHERE exerciseID='$exerciseID'");
   if($result2) {
    while($row = mysqli_fetch_row($result2)) {
     $prereqID = $row[0];

     $result3 = mysqli_query($dbConnection, "SELECT 1 FROM userconcepts WHERE userID='$userID' AND conceptID='$prereqID'");
     if(!$result3 || mysqli_num_rows($result3) <= 0) { // prereq not met
      continue 2; // continue while to check next exerciseID
     }
    }
    // exercise not already seen and all prereqs met
    mysqli_query($dbConnection, "INSERT INTO eligibleexercises (userID, exerciseID) VALUES ('$userID', '$exerciseID')"); 
   }
  }
 }
}



// function: updates memory strength based on answer
function updateMemoryStrength($userID, $conceptID, $isCorrect, $buttonPressed, $time) {
 global $dbConnection;

 $userID = stringClean($userID);
 $conceptID = stringClean($conceptID);

 // get old strength value
 $result = mysqli_query($dbConnection, "SELECT strength, lastSeen FROM userconcepts WHERE userID='$userID' AND conceptID='$conceptID'");
 if($result && mysqli_num_rows($result) == 1) { // success
  $row = mysqli_fetch_row($result);
  $oldStrength = $row[0];
  $oldTime = $row[1];

  // compute probability from old strength
  $dt = $time - $oldTime;
  //$prob = exp(-$dt/$oldStrength);

  // calculate factor based on probability
  $learningFactor = 5.929215229*sqrt($dt/$oldStrength);

  // adjust factor based on correct/incorrect answer and confidence button
  if($buttonPressed == 1) {
   $learningFactor = $learningFactor/2;
  }
  if($buttonPressed == 0) {
   $learningFactor = 0;
  }
  $learningFactor = $learningFactor + 1;
  if($isCorrect == 0) {
   $learningFactor = 1/$learningFactor;
  }

  // replace strength value with scaled strength value and update times in database
  $strength = $learningFactor * $oldStrength;
  if($strength < 1) { // prevent it from asking a whole bunch of times in a row if got wrong a whole bunch of times in a row
   $strength = 1;
  }
  $nextReview = round($strength * 0.105360516) + $time;
  mysqli_query($dbConnection, "UPDATE userconcepts SET strength='$strength', lastSeen='$time', nextReview='$nextReview' WHERE userID='$userID' AND conceptID='$conceptID'");
 } else { // if no old strength then new exposure
  $learningFactor = 36;
  if($buttonPressed == 1) {
   $learningFactor = ($learningFactor + 3)/4;
  }
  if($buttonPressed == 0) {
   $learningFactor = ($learningFactor + 15)/16;
  }
  if($isCorrect == 0) {
   $learningFactor = 1;
  }
  $strength = $learningFactor;
  $nextReview = round($strength * 0.105360516) + $time;
  mysqli_query($dbConnection, "INSERT INTO userconcepts (userID, conceptID, strength, lastSeen, nextReview) VALUES ('$userID', '$conceptID', '$strength', '$time', '$nextReview')");

  // check to see if new material unlocked
  checkEligibility($userID, $conceptID);
 }
}



// function: returns conceptID for new material based on current courses
function findNewConcept($userID) {
 global $dbConnection;
 $result = mysqli_query($dbConnection, "SELECT conceptID FROM eligibleconcepts WHERE userID='$userID' ORDER BY RAND()");
 if(!$result) {
  return -1;
 }
 while($row = mysqli_fetch_row($result)) {
  $conceptID = $row[0];

  // check if concept already seen
  $result2 = mysqli_query($dbConnection, "SELECT 1 FROM userconcepts WHERE userID='$userID' AND conceptID='$conceptID'");
  if($result2 && (mysqli_num_rows($result2) == 0)) {
   return $conceptID;
  } else {
   mysqli_query($dbConnection, "DELETE FROM eligibleconcepts WHERE userID='$userID' AND conceptID='$conceptID'");
  }
 }

 return -1;
}



// function: returns noteID associated with conceptID
function getNote($conceptID) {
 global $dbConnection;

 $result = mysqli_query($dbConnection, "SELECT noteID FROM conceptnotes WHERE conceptID='$conceptID'");
 if($result && mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_row($result);
  return $row[0];
 }

 return 0;
}



// function: returns noteID of interrupt or 0 if no interrupt
function getInterrupt($userID, $conceptID) {
 global $dbConnection;

 $result = mysqli_query($dbConnection, "SELECT strength FROM userconcepts WHERE userID='$userID' AND conceptID='$conceptID'");
 if($result && mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_row($result);
  if($row[0] > 5) {
   return 0;
  }
 }

 return getNote($conceptID);
}

?>

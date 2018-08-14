<?php

// Functions:
// userCourses($userID) - returns array of "courseID" => (courseName, courseDescription, selected)
// courseIDs() - returns numeric array of all courseIDs
// saveCourseSelection($userID, $courseIDs) - save course selection

// includes
require_once('utility.php');
require_once('databaseConnection.php');



// function: returns array of "courseID" => (courseName, courseDescription, selected)
function userCourses($userID) {
 global $dbConnection;
 $eCourses = array();

 // get list of offered courses
 $result = mysqli_query($dbConnection, "SELECT courseID, courseName, courseDescription FROM courses");
 if(!$result) {
  return 0;
 }

 // for each course
 while($row = mysqli_fetch_row($result)) {
  $courseID = $row[0];
  $courseName = $row[1];
  $courseDescription = $row[2];

  // check to see if student has already selected course
  $result2 = mysqli_query($dbConnection, "SELECT 1 FROM usercourses WHERE userID='$userID' AND courseID='$courseID'");
  $selected = 0;
  if($result2 && mysqli_num_rows($result2) > 0) {
   $selected = 1;
  }

  // store course information in array
  $eCourses["$courseID"] = array($courseName, $courseDescription, $selected);
 }

 return $eCourses;
}


// function: returns numeric array of all courseIDs
function courseIDs() {
 global $dbConnection;
 $courseIDs = array();
 $result = mysqli_query($dbConnection, "SELECT courseID FROM courses");
 if($result) {
  while($row = mysqli_fetch_row($result)) {
   $courseIDs[] = $row[0];
  }
 }
 return $courseIDs;
}



// function: save course selection
function saveCourseSelection($userID, $courseIDs) {
 global $dbConnection;

 // delete current usercourse entries
 $result = mysqli_query($dbConnection, "DELETE FROM usercourses WHERE userID='$userID'");
 if(!$result) { // failure
  return 0;
 }

 foreach($courseIDs as $courseID) {

  // insert usercourse entry
  $result = mysqli_query($dbConnection, "INSERT INTO usercourses (userID, courseID) VALUES ('$userID', '$courseID')");

  // rebuild concept eligibility table
  mysqli_query("DELETE FROM eligibleconcepts WHERE userID='$userID'");
  $result2 = mysqli_query($dbConnection, "SELECT conceptID FROM courseconcepts WHERE courseID='$courseID'");
  if(!$result2) { // no conepts
   continue;
  }
  while($row2 = mysqli_fetch_row($result2)) {
   $conceptID = $row2[0];

   // check if user has already seen concept
   $result3 = mysqli_query($dbConnection, "SELECT 1 FROM userconcepts WHERE userID='$userID' AND conceptID='$conceptID'");
   if(!$result3 || ($result3 && mysqli_num_rows($result3) > 0)) { // already seen so skip
    continue;
   }

   // check if all prereqs have been seen
   $result3 = mysqli_query($dbConnection, "SELECT prereqID FROM conceptprereqs WHERE conceptID='$conceptID'");
   if($result3) { // if fails assume prereqs not met
    $prereqsMet = 1;
    while($row3 = mysqli_fetch_row($result3)) {
     $prereqID = $row3[0];

     // check if prereq has been seen
     $result4 = mysqli_query($dbConnection, "SELECT 1 from userconcepts WHERE userID='$userID' AND conceptID='$prereqID'");
     if(!$result4 || ($result4 && mysqli_num_rows($result4) == 0)) { // prereq not met
      $prereqsMet = 0;
      break;
     }
    }

    if($prereqsMet == 1) { // passed all the checks and is eligible
     $result4 = mysqli_query($dbConnection, "INSERT INTO eligibleconcepts (userID, conceptID) VALUES ('$userID', '$conceptID')");
    }
   }
  }
 }

  // rebuild exercise eligibility table
  mysqli_query($dbConnection, "DELETE FROM usereligibility WHERE userID='$userID'");
  // gather list of open exercises
  $openExercises = array();
  $result = mysqli_query($dbConnection, "SELECT exerciseID FROM openexercises WHERE userID='$userID'");
  while($row = mysqli_fetch_row($result)) {
   $openExercises[] = $row[0];
  }
  $result = mysqli_query($dbConnection, "SELECT exerciseID FROM exercises"); // list of exercises
  if($result) {
   while($row = mysqli_fetch_row($result)) {
    $exerciseID = $row[0];
    // check if open
    foreach($openExercises as $oeID) {
     if($oeID == $exerciseID) { // already open
      continue 2; // continue while to next exerciseID
     }
    }
    // check if already seen
    $result2 = mysqli_query($dbConnection, "SELECT 1 FROM userexercises WHERE userID='$userID' AND exerciseID='$exerciseID'");
    if($result2 && mysqli_num_rows($result2) > 0) { // already seen
     continue; // continue while to next exerciseID
    }
    // check if prereqs met
    $result2 = mysqli_query($dbConnection, "SELECT prereqID FROM exerciseprereqs WHERE exerciseID='$exerciseID'"); // list of prereqs
    if($result2) {
     $prereqsMet = 1;
     while($row2 = mysqli_fetch_row($result2)) {
      $prereqID = $row2[0];
      $result3 = mysqli_query($dbConnection, "SELECT 1 FROM userconcepts WHERE userID='$userID' and conceptID='$prereqID'");
      if(!$result3 || mysqli_num_rows($result3) == 0) { // prereq not met
       $prereqsMet = 0;
       break; // break while through prereqs
      }
     }
     if($prereqsMet == 1) { // exercise is eligible
      mysqli_query($dbConnection, "INSERT INTO eligibleexercises (userID, exerciseID) VALUES ('$userID', '$exerciseID')");
     }
    }
   }
  }

}

?>

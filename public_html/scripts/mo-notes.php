<?php

// Functions:
// userCourses($userID) - returns array of "courseID" => (courseName, courseDescription, selected)
// courseIDs() - returns numeric array of all courseIDs
// saveCourseSelection($userID, $courseIDs) - save course selection

// includes
require_once('utility.php');
require_once('databaseConnection.php');



function getNoteInfo() {
 global $dbConnection;

 // for each course
 $result = mysqli_query($dbConnection, "SELECT courseID, courseName FROM courses");
 $courses = array();
 while($row = mysqli_fetch_row($result)) {
  $courseID = $row[0];
  $courseName = $row[1];

  // for each section in each course
  $result2 = mysqli_query($dbConnection, "SELECT sectionID, sectionName FROM sections WHERE courseID='$courseID'");
  $sections = array();
  while($row2 = mysqli_fetch_row($result2)) {
   $sectionID = $row2[0];
   $sectionName = $row2[1];

   $result3 = mysqli_query($dbConnection, "SELECT noteID FROM sectionnotes WHERE sectionID='$sectionID'");
   $notes = array();
   while($row3 = mysqli_fetch_row($result3)) {
    $noteID = $row3[0];

    $result4 = mysqli_query($dbConnection, "SELECT noteName FROM notes WHERE noteID='$noteID'");
    $row4 = mysqli_fetch_row($result4);
    $noteName = $row4[0];

    $notes["$noteID"] = $noteName;
   }

   $sections["$sectionName"] = $notes;
  }

  $courses["$courseName"] = $sections;
 }

 return $courses;

}

?>

<?php

// includes
require_once('scripts/authentication.php');
require_once('scripts/mo-courses.php');

// get list of courseIDs
$courses = courseIDs();

// check each course to see if it is selected
$courseIDs = array();
foreach($courses as $courseID) {
 if(array_key_exists("checkCourse$courseID", $_POST) && $_POST["checkCourse$courseID"] == 1) {
  $courseIDs[] = $courseID;
 }
}

// save selected courses
saveCourseSelection($userID, $courseIDs);

// automatically redirect to study page
header('location: study.php');

?>

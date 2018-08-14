<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('scripts/utility.php');
require_once('scripts/mo-search.php');

// Get search parameters
$t = $_GET["t"]; // type -- link or id
$d = $_GET["d"]; // domain -- binary 000000 concepts, courses, exercises, notes, prereqs, questions
$q = $_GET["q"]; // query -- search input

$response = "";

$tokens = tokenize($q);
$results = searchDB($d, $tokens);

if($t == "id") {
 foreach($results as $resultInfo) {
  $domain = $resultInfo[0];
  $id = $resultInfo[1];
  $description = $resultInfo[2];
  if($response != "") {
    $response .= "<hr />\n";
  }

  $response .= "<a href=\"#\" name=\"$id\">";
  switch($domain) {
   case $DOMAIN_CONCEPTS:
    $response .= "Concept | ";
    break;
   case $DOMAIN_COURSES:
    $response .= "Course | ";
    break;
   case $DOMAIN_EXERCISES:
    $response .= "Exercise | ";
    break;
   case $DOMAIN_NOTES:
    $response .= "Note | ";
    break;
   case $DOMAIN_PREREQS:
    $response .= "Prereq | ";
    break;
   case $DOMAIN_QUESTIONS:
    $response .= "Question | ";
    break;
  }
  $response .= "$description</a><br />\n";
 }
} else {
 foreach($results as $resultInfo) {
  $domain = $resultInfo[0];
  $id = $resultInfo[1];
  $description = $resultInfo[2];
  if($response != "") {
    $response .= "<hr />\n";
  }
  switch($domain) {
   case $DOMAIN_CONCEPTS:
    $response .= "<div class='search-item'>";
    $response .= "<a href=\"#\">Concept | ";
    break;
   case $DOMAIN_COURSES:
    $response .= "<div class='search-item'>";
    $response .= "<a href=\"#\">Course | ";
    break;
   case $DOMAIN_EXERCISES:
    $response .= "<div class='search-item' onMouseDown=\"document.location.href='exercise.php?exerciseID=$id'\">";
    $response .= "<a href=\"exercise.php?exerciseID=$id\" onFocus=\"document.location.href='exercise.php?exerciseID=$id'\">Exercise | ";
    break;
   case $DOMAIN_NOTES:
    $response .= "<div class='search-item' onMouseDown=\"document.location.href='note.php?noteID=$id'\">";
    $response .= "<a href=\"note.php?noteID=$id\" onFocus=\"document.location.href='note.php?noteID=$id'\">Note | ";
    break;
   case $DOMAIN_PREREQS:
    $response .= "<div class='search-item'>";
    $response .= "<a href=\"#\">Prereq | ";
    break;
   case $DOMAIN_QUESTIONS:
    $response .= "<div class='search-item'>";
    $response .= "<a href=\"#\">Question | ";
    break;
  }
  $response .= "$description</a></div>\n";
 }
}

if($response == "") {
 $response = "No results found.";
}

echo $response;

?>

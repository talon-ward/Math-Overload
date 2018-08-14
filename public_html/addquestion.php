<?php

// includes
require_once('scripts/authentication.php');
require_once('scripts/mo-add.php');

if($userID != 1) {
 echo("<h1>You are not authorized to add questions.</h1><br />");
 exit("Unauthorized to add questions.\n");
}

// automatically redirect to add question page
header('location: addquestion.php');

?>

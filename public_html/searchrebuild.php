<?php

// includes
require_once('scripts/authentication.php');
require_once('scripts/mo-search.php');

if(isset($userID) && $userID == 1) {
 echo "Rebuilding search database...\n";
 rebuildSearch();
 echo "Done.\n";
} else{
 echo("<h1>You are not authorized to rebuild the search database.</h1><br />");
 exit("Unauthorized to rebuild search.\n");
}

// automatically redirect to main page
//header('location: index.php');

?>

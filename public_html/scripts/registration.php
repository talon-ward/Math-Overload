<?php

// includes
require_once('utility.php');
require_once('databaseConnection.php');

// function: check usernamer availability
function isUsernameAvailable($uname) { // $uname is already 'clean'
 global $dbConnection;
 $result = mysqli_query($dbConnection, "SELECT userName FROM users WHERE userName='$uname'");
 return ($result && mysqli_num_rows($result) == 0);
}

// function: add new member
function addMember($uname, $pword, $email) { // $uname and $email are already 'clean'
 global $dbConnection;
 $pword = hashPassword($pword); // hash password
 $cpass = randomAlphanumeric(64);
 $rpass = randomAlphanumeric(64);
 $rexp  = time();
 mysqli_query($dbConnection, "INSERT INTO users (userName, email, password, cookiePassword, recoverPassword, recoverExpiration) VALUES ('$uname', '$email', '$pword', '$cpass', '$rpass', '$rexp')");
}

?>

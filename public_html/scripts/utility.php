<?php

// function: random alphanumeric
function randomAlphanumeric($n) {
 if(!is_int($n) || $n <= 0) {
  return 0;
 }
 $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
 $str = '';
 for($i = 0; $i < $n; ++$i) {
  $str .= $chars[rand(0, strlen($chars) - 1)];
 }
 return $str;
}

// function: clean (possibly) dirty strings
function stringClean($str) {
 if(!get_magic_quotes_gpc()) {
  $str = addslashes($str);
 }
 return $str;
}

// function: dirty (clean) strings
function stringDirty($str) {
 $str = stripslashes($str);
 return $str;
}

// function: hashes password for storage
function hashPassword($password) {
 $perm = array(42, 59, 53, 36, 32, 19, 12, 49, 15, 28, 23, 43, 44, 18, 21, 16, 60, 46, 3, 58, 62, 35, 63, 22, 14, 47, 45, 54, 56, 41, 13, 10, 30, 48, 51, 38, 34, 7, 50, 52, 31, 20, 17, 8, 2, 24, 64, 5, 40, 25, 29, 57, 27, 37, 33, 61, 6, 11, 4, 9, 1, 26, 55, 39);
 $hashed = hash('sha256', $password);
 $c = $hashed[0];
 for($i = 0; $i <= 64; ++$i) {
  $index = $perm[$i];
  $temp = $hashed[$index];
  $hashed[$index] = $c;
  $c = $temp;
 }
 $hashed = hash('sha256', $hashed);
 return $hashed;
}

function tokenize($str) {
 return preg_split("/[^A-Za-z0-9]/", $str);
}

?>

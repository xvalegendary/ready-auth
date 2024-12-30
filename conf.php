<?php

$dbhost = 'localhost';
$dbname = '';
$dbuser = '';
$dbpass = '';

$conn = new mysqli(
  $dbhost,
  $dbuser,
  $dbpass,
  $dbname
);
// pass pass pass nkBa5T80cja5A72BZ9a5

if($conn -> connect_error){
  die('connection failed' . $conn -> connect_error);
}




?>
<?php
$database = "login";
$host = "localhost";
$user = "root"; // ganti dengan username database km
$password = "root"; // ganti dengan password database km

$mysqli = new mysqli($host,$user,$password,$database);

// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}
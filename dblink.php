<?php

$servername = "sql204.infinityfree.com";
$username   = "if0_42155087";
$password   = "nvluniversity1";
$database   = "if0_42155087_learnmate";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Error connecting to database: " . mysqli_connect_error());
}

?>
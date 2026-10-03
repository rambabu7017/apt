<?php

$servername = "localhost";
$username = "jobapp";
$password = "JobApp@123";
$database = "job_recommendation";

$conn = new mysqli(
    $servername,
    $username,
    $password,
    $database
);

if ($conn->connect_error) {

    die("Database connection failed: " . $conn->connect_error);

}

?>

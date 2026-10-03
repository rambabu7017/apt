<?php

session_start();

include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];


/* Get all available skills */

$sql = "
SELECT id, skill_name
FROM skills
ORDER BY skill_name
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Edit Skills | SkillMatch
    </title>

    <link rel="stylesheet"
          href="style.css">

</head>


<body>


<!-- ================= NAVBAR ================= -->

<header class="navbar">

    <div class="logo">
        SkillMatch
    </div>


    <nav>

        <a href="index.php">
            Home
        </a>

        <a href="skills.php">
            My Skills
        </a>

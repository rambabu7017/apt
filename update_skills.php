<?php

session_start();

include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$skills = $_POST['skills'] ?? [];


/* Remove old skills */

$sql = "DELETE FROM user_skills
        WHERE user_id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $user_id);

$stmt->execute();

$stmt->close();


/* Add updated skills */

if (!empty($skills)) {

    $sql = "INSERT INTO user_skills (user_id, skill_id)
            VALUES (?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL prepare failed: " . $conn->error);
    }

    foreach ($skills as $skill_id) {

        $stmt->bind_param("ii", $user_id, $skill_id);

        $stmt->execute();
    }

    $stmt->close();
}


$conn->close();


/* After update, go to My Skills */

header("Location: my_skills.php");

exit();

?>

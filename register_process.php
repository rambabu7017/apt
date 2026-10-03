<?php

session_start();

include 'db.php';


/* ================= GET FORM DATA ================= */

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$gender = $_POST['gender'] ?? '';

$skills = $_POST['skills'] ?? [];


/* ================= BASIC VALIDATION ================= */

if (
    empty($name) ||
    empty($email) ||
    empty($password) ||
    empty($gender)
) {
    die("Please fill all required fields.");
}


/* ================= CHECK EMAIL ================= */

$check_sql = "
SELECT id
FROM users
WHERE email = ?
";

$check_stmt = $conn->prepare($check_sql);

if (!$check_stmt) {
    die("SQL prepare failed: " . $conn->error);
}

$check_stmt->bind_param("s", $email);

$check_stmt->execute();

$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {

    $check_stmt->close();

    die("
        <h2>Email already registered</h2>
        <p>Please use a different email address.</p>
        <a href='register.php'>Go Back</a>
    ");
}

$check_stmt->close();


/* ================= INSERT USER ================= */

$sql = "
INSERT INTO users
(name, email, password, gender)
VALUES (?, ?, ?, ?)
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL prepare failed: " . $conn->error);
}

$stmt->bind_param(
    "ssss",
    $name,
    $email,
    $password,
    $gender
);

if (!$stmt->execute()) {

    die("Registration failed: " . $stmt->error);
}


/* Get newly created user ID */

$user_id = $stmt->insert_id;

$stmt->close();


/* ================= SAVE SELECTED SKILLS ================= */

if (!empty($skills)) {

    $skill_sql = "
    INSERT INTO user_skills
    (user_id, skill_id)
    VALUES (?, ?)
    ";

    $skill_stmt = $conn->prepare($skill_sql);

    if (!$skill_stmt) {
        die("Skill SQL prepare failed: " . $conn->error);
    }

    foreach ($skills as $skill_id) {

        $skill_id = (int)$skill_id;

        $skill_stmt->bind_param(
            "ii",
            $user_id,
            $skill_id
        );

        if (!$skill_stmt->execute()) {

            die("Skill insertion failed: " . $skill_stmt->error);
        }
    }

    $skill_stmt->close();
}


/* ================= SESSION ================= */

$_SESSION['user_id'] = $user_id;

$_SESSION['user_name'] = $name;


/* ================= SUCCESS PAGE ================= */

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Registration Successful | JobMatch</title>

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {

    font-family: Arial, Helvetica, sans-serif;

    min-height: 100vh;

    background: #F5F9FD;

    display: flex;

    align-items: center;

    justify-content: center;

    padding: 20px;
}

.success-card {

    width: 100%;

    max-width: 520px;

    background: white;

    border-radius: 20px;

    padding: 45px;

    text-align: center;

    box-shadow:
        0 20px 55px
        rgba(7, 28, 47, 0.12);
}

.success-icon {

    width: 70px;

    height: 70px;

    margin: 0 auto 22px;

    border-radius: 50%;

    background: #E6F6FC;

    color: #1679AD;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 30px;

    font-weight: 800;
}

h1 {

    color: #102A43;

    font-size: 28px;

    margin-bottom: 12px;
}

.message {

    color: #718797;

    font-size: 13px;

    line-height: 1.7;

    margin-bottom: 28px;
}

.welcome {

    color: #263F50;

    font-size: 14px;

    font-weight: 700;

    margin-bottom: 25px;
}

.button {

    display: inline-block;

    padding: 13px 28px;

    border-radius: 9px;

    background:
        linear-gradient(
            135deg,
            #0B3552,
            #1679AD
        );

    color: white;

    text-decoration: none;

    font-size: 12px;

    font-weight: 800;
}

.button:hover {

    opacity: 0.92;
}

</style>

</head>


<body>


<div class="success-card">


    <div class="success-icon">
        ✓
    </div>


    <h1>
        Registration Successful
    </h1>


    <p class="message">

        Your profile has been created successfully.
        Your selected skills have been saved.

    </p>


    <p class="welcome">

        Welcome, <?php echo htmlspecialchars($name); ?>!

    </p>


    <a
        href="recommend.php"
        class="button">

        Find My Job Matches

    </a>


</div>


</body>

</html>

<?php

$conn->close();

?>

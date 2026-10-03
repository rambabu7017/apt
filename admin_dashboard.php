<?php
session_start();
include "db.php";

/* Admin Login */
if (isset($_POST['admin_login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT id, username, password FROM admins WHERE username = ?"
    );

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $admin = $result->fetch_assoc();

        if ($password === $admin['password']) {

            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];

            header("Location: admin_dashboard.php");
            exit();

        } else {
            $error = "Invalid password";
        }

    } else {
        $error = "Invalid username";
    }
}


/* Admin Logout */
if (isset($_GET['logout'])) {

    session_destroy();

    header("Location: admin_dashboard.php");
    exit();
}


/* Dashboard Data */

if (isset($_SESSION['admin_id'])) {

    $users_count = $conn->query(
        "SELECT COUNT(*) AS total FROM users"
    )->fetch_assoc()['total'];

    $skills_count = $conn->query(
        "SELECT COUNT(*) AS total FROM skills"
    )->fetch_assoc()['total'];

    $jobs_count = $conn->query(
        "SELECT COUNT(*) AS total FROM jobs"
    )->fetch_assoc()['total'];

    $users = $conn->query(
        "SELECT id, name, email, gender FROM users ORDER BY id DESC"
    );

    $skills = $conn->query(
        "SELECT id, skill_name FROM skills ORDER BY skill_name ASC"
    );

    $jobs = $conn->query(
        "SELECT id, job_title, company, description
         FROM jobs
         ORDER BY id DESC"
    );
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Admin Dashboard - JobMatch</title>

<style>

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
}

body {
    background: #f4f6f9;
    color: #222;
}

/* Login */

.login-container {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.login-box {
    background: white;
    width: 380px;
    padding: 35px;
    border-radius: 15px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.12);
}

.login-box h1 {
    text-align: center;
    margin-bottom: 10px;
}

.login-box p {
    text-align: center;
    color: #666;
    margin-bottom: 25px;
}

.login-box input {
    width: 100%;
    padding: 13px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
}

.login-box button {
    width: 100%;
    padding: 13px;
    border: none;
    border-radius: 8px;
    background: #2563eb;
    color: white;
    font-size: 16px;
    cursor: pointer;
}

.error {
    color: red;
    text-align: center;
    margin-bottom: 15px;
}

/* Dashboard */

.navbar {
    background: #111827;
    color: white;
    padding: 18px 35px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navbar h2 {
    font-size: 22px;
}

.navbar a {
    color: white;
    text-decoration: none;
    background: #dc2626;
    padding: 9px 15px;
    border-radius: 6px;
}

.container {
    width: 92%;
    max-width: 1200px;
    margin: 30px auto;
}

.welcome {
    margin-bottom: 25px;
}

.cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 35px;
}

.card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.08);
}

.card h3 {
    color: #555;
    margin-bottom: 10px;
}

.card .number {
    font-size: 32px;
    font-weight: bold;
}

/* Sections */

.section {
    background: white;
    padding: 25px;
    margin-bottom: 30px;
    border-radius: 12px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.07);
}

.section h2 {
    margin-bottom: 20px;
}

/* Tables */

.table-container {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}

th {
    background: #f1f5f9;
}

@media (max-width: 700px) {

    .cards {
        grid-template-columns: 1fr;
    }

    .navbar {
        padding: 15px;
    }

    .container {
        width: 95%;
    }

}

</style>

</head>

<body>


<?php if (!isset($_SESSION['admin_id'])): ?>

<!-- Admin Login -->

<div class="login-container">

    <div class="login-box">

        <h1>Admin Login</h1>

        <p>JobMatch Administration</p>

        <?php if (isset($error)): ?>

            <div class="error">
                <?php echo htmlspecialchars($error); ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <input
                type="text"
                name="username"
                placeholder="Username"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Password"
                required
            >

            <button type="submit" name="admin_login">
                Login
            </button>

        </form>

    </div>

</div>


<?php else: ?>


<!-- Dashboard -->

<nav class="navbar">

    <h2>JobMatch Admin Dashboard</h2>

    <a href="admin_dashboard.php?logout=1">
        Logout
    </a>

</nav>


<div class="container">

    <div class="welcome">

        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></h1>

        <p>Manage and view application data.</p>

    </div>


    <!-- Dashboard Cards -->

    <div class="cards">

        <div class="card">

            <h3>Total Users</h3>

            <div class="number">
                <?php echo $users_count; ?>
            </div>

        </div>


        <div class="card">

            <h3>Total Skills</h3>

            <div class="number">
                <?php echo $skills_count; ?>
            </div>

        </div>


        <div class="card">

            <h3>Total Jobs</h3>

            <div class="number">
                <?php echo $jobs_count; ?>
            </div>

        </div>

    </div>


    <!-- Users -->

    <div class="section">

        <h2>Registered Users</h2>

        <div class="table-container">

            <table>

                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Gender</th>
                </tr>

                <?php while ($user = $users->fetch_assoc()): ?>

                <tr>

                    <td>
                        <?php echo $user['id']; ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($user['name']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($user['email']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($user['gender']); ?>
                    </td>

                </tr>

                <?php endwhile; ?>

            </table>

        </div>

    </div>


    <!-- Skills -->

    <div class="section">

        <h2>Available Skills</h2>

        <div class="table-container">

            <table>

                <tr>
                    <th>ID</th>
                    <th>Skill Name</th>
                </tr>

                <?php while ($skill = $skills->fetch_assoc()): ?>

                <tr>

                    <td>
                        <?php echo $skill['id']; ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($skill['skill_name']); ?>
                    </td>

                </tr>

                <?php endwhile; ?>

            </table>

        </div>

    </div>


    <!-- Jobs -->

    <div class="section">

        <h2>Available Jobs</h2>

        <div class="table-container">

            <table>

                <tr>
                    <th>ID</th>
                    <th>Job Title</th>
                    <th>Company</th>
                    <th>Description</th>
                </tr>

                <?php while ($job = $jobs->fetch_assoc()): ?>

                <tr>

                    <td>
                        <?php echo $job['id']; ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($job['job_title']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($job['company']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($job['description']); ?>
                    </td>

                </tr>

                <?php endwhile; ?>

            </table>

        </div>

    </div>

</div>


<?php endif; ?>

</body>

</html>

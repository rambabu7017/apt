<?php

session_start();

include 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT id, name, password
        FROM users
        WHERE email = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html>
<head>

    <title>Login Status - JobMatch</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #F5F9FC;
            color: #061A2F;
            min-height: 100vh;
        }

        /* Navbar */

        .navbar {
            height: 65px;
            background: #061A2F;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 7%;
        }

        .logo {
            color: white;
            font-size: 23px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .logo span {
            color: #5DB8F5;
        }

        /* Main */

        .main {
            min-height: calc(100vh - 65px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .status-card {
            width: 100%;
            max-width: 560px;
            background: white;
            border-radius: 20px;
            padding: 45px;
            text-align: center;
            box-shadow: 0 15px 40px rgba(6, 26, 47, 0.10);
            border: 1px solid #E2EDF5;
        }

        .icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 22px;
            border-radius: 50%;
            background: #E8F4FC;
            color: #1677B8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 34px;
            font-weight: bold;
        }

        .badge {
            display: inline-block;
            background: #E8F4FC;
            color: #0A2A43;
            border: 1.5px solid #061A2F;
            border-radius: 30px;
            padding: 7px 15px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 18px;
        }

        h2 {
            font-size: 30px;
            margin-bottom: 12px;
        }

        .welcome {
            font-size: 18px;
            color: #1677B8;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .message {
            color: #526575;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 28px;
        }

        .button {
            display: inline-block;
            text-decoration: none;
            background: #061A2F;
            color: white;
            padding: 13px 25px;
            border-radius: 9px;
            font-weight: bold;
            border: 2px solid #061A2F;
        }

        .button:hover {
            background: #0A2A43;
        }

        .error {
            color: #B42318;
            font-size: 16px;
            margin-bottom: 25px;
        }

        .back {
            display: inline-block;
            text-decoration: none;
            color: #0A2A43;
            font-weight: bold;
            padding: 12px 22px;
            border: 2px solid #0A2A43;
            border-radius: 9px;
        }

        @media (max-width: 600px) {

            .status-card {
                padding: 35px 25px;
            }

            h2 {
                font-size: 25px;
            }

        }

    </style>

</head>

<body>

    <nav class="navbar">

        <div class="logo">
            Job<span>Match</span>
        </div>

    </nav>


    <main class="main">

        <div class="status-card">

            <?php

            if ($result->num_rows == 1) {

                $user = $result->fetch_assoc();

                if ($password === $user['password']) {

                    session_regenerate_id(true);

                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];

                    echo "<div class='icon'>✓</div>";

                    echo "<div class='badge'>LOGIN SUCCESSFUL</div>";

                    echo "<h2>Welcome Back!</h2>";

                    echo "<p class='welcome'>"
                        . htmlspecialchars($user['name'])
                        . "</p>";

                    echo "<p class='message'>
                            Your account has been successfully verified.
                            You can now explore job opportunities that match your skills.
                          </p>";

                    echo "<a class='button' href='recommend.php'>
                            View Recommended Jobs
                          </a>";

                } else {

                    echo "<div class='icon'>!</div>";

                    echo "<div class='badge'>LOGIN FAILED</div>";

                    echo "<h2>Invalid Password</h2>";

                    echo "<p class='error'>
                            The password you entered is incorrect.
                          </p>";

                    echo "<a class='back' href='login.php'>
                            Try Again
                          </a>";
                }

            } else {

                echo "<div class='icon'>!</div>";

                echo "<div class='badge'>LOGIN FAILED</div>";

                echo "<h2>User Not Found</h2>";

                echo "<p class='error'>
                        No account was found with this email address.
                      </p>";

                echo "<a class='back' href='login.php'>
                        Back to Login
                      </a>";
            }

            $stmt->close();

            $conn->close();

            ?>

        </div>

    </main>

</body>
</html>

<?php

session_start();

include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$sql = "
SELECT
    s.skill_name
FROM user_skills us
JOIN skills s
    ON us.skill_id = s.id
WHERE us.user_id = ?
ORDER BY s.skill_name
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $user_id);

if (!$stmt->execute()) {
    die("SQL execute failed: " . $stmt->error);
}

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>My Skills | JobMatch</title>

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {

    font-family: Arial, Helvetica, sans-serif;

    background:
        linear-gradient(
            135deg,
            #F5F9FD,
            #EEF6FC,
            #F8FBFE
        );

    color: #102A43;

    min-height: 100vh;
}

/* NAVBAR */

.navbar {

    height: 72px;

    background: #071C2F;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 0 6%;

}

.brand {

    display: flex;

    align-items: center;

    gap: 11px;
}

.brand-mark {

    width: 36px;
    height: 36px;

    border-radius: 10px;

    background:
        linear-gradient(
            135deg,
            #5CC8FF,
            #258BC4
        );

    display: flex;

    align-items: center;

    justify-content: center;

    color: white;

    font-size: 17px;

    font-weight: 800;
}

.brand-name {

    color: white;

    font-size: 20px;

    font-weight: 800;
}

.brand-name span {

    color: #62C8FF;
}

.nav-actions {

    display: flex;

    align-items: center;

    gap: 10px;
}

.nav-btn {

    text-decoration: none;

    padding: 9px 15px;

    border-radius: 8px;

    font-size: 12px;

    font-weight: 700;
}

.matches-btn {

    background: #E8F6FE;

    color: #0B4162;
}

.logout-btn {

    color: #D9E6EF;

    border: 1px solid #385267;
}

/* MAIN */

.page {

    max-width: 1050px;

    margin: auto;

    padding: 45px 24px 80px;
}

/* HEADER */

.header {

    background:
        linear-gradient(
            120deg,
            #071C2F,
            #0B3552
        );

    border-radius: 24px;

    padding: 40px 45px;

    margin-bottom: 25px;

    box-shadow:
        0 18px 45px
        rgba(7,28,47,0.14);
}

.header-label {

    color: #74D2FF;

    font-size: 10px;

    font-weight: 800;

    letter-spacing: 1.6px;

    margin-bottom: 12px;
}

.header h1 {

    color: white;

    font-size: 34px;

    margin-bottom: 10px;
}

.header h1 span {

    color: #62C8FF;
}

.header p {

    color: #B9CFDE;

    font-size: 13px;

    line-height: 1.7;

    max-width: 650px;
}

/* PROFILE */

.profile-strip {

    background: white;

    border: 1px solid #DCE8F0;

    border-radius: 16px;

    padding: 18px 22px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-bottom: 28px;

    box-shadow:
        0 7px 25px
        rgba(7,28,47,0.055);
}

.profile-left {

    display: flex;

    align-items: center;

    gap: 13px;
}

.avatar {

    width: 43px;
    height: 43px;

    border-radius: 12px;

    background:
        linear-gradient(
            135deg,
            #DDF3FF,
            #BCE5F8
        );

    color: #0B5B85;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 17px;

    font-weight: 800;
}

.profile-text small {

    display: block;

    color: #8295A3;

    font-size: 9px;

    font-weight: 800;

    letter-spacing: 1px;

    margin-bottom: 4px;
}

.profile-text strong {

    font-size: 14px;

    color: #102A43;
}

.skill-count {

    color: #24739A;

    font-size: 11px;

    font-weight: 700;
}

/* SKILLS CARD */

.skills-card {

    background: white;

    border: 1px solid #DCE8F0;

    border-radius: 19px;

    padding: 28px;

    box-shadow:
        0 8px 28px
        rgba(7,28,47,0.055);
}

.section-top {

    display: flex;

    align-items: flex-end;

    justify-content: space-between;

    margin-bottom: 24px;
}

.section-top h2 {

    font-size: 21px;

    color: #102A43;
}

.section-top p {

    color: #718797;

    font-size: 11px;

    margin-top: 5px;
}

.edit-btn {

    text-decoration: none;

    background: #071C2F;

    color: white;

    padding: 10px 16px;

    border-radius: 8px;

    font-size: 11px;

    font-weight: 700;
}

/* SKILLS */

.skills-grid {

    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 12px;
}

.skill-card {

    min-height: 48px;

    padding: 11px 13px;

    border-radius: 10px;

    border: 1px solid #D6E7F0;

    background:
        linear-gradient(
            135deg,
            #F9FCFE,
            #EEF7FC
        );

    display: flex;

    align-items: center;

    gap: 10px;

    color: #23475D;

    font-size: 11px;

    font-weight: 700;

    transition: 0.2s;
}

.skill-card:hover {

    transform: translateY(-2px);

    border-color: #B7D8E8;

    box-shadow:
        0 7px 18px
        rgba(7,28,47,0.06);
}

.skill-icon {

    width: 25px;
    height: 25px;

    border-radius: 7px;

    background: #DDF3FF;

    color: #1679AD;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 11px;

    font-weight: 800;

    flex-shrink: 0;
}

/* EMPTY */

.empty {

    text-align: center;

    padding: 60px 20px;
}

.empty-icon {

    width: 60px;
    height: 60px;

    border-radius: 16px;

    background: #E8F6FD;

    color: #1679AD;

    display: flex;

    align-items: center;

    justify-content: center;

    margin: auto auto 16px;

    font-size: 25px;

    font-weight: 800;
}

.empty h2 {

    font-size: 20px;

    margin-bottom: 8px;
}

.empty p {

    color: #718492;

    font-size: 12px;

    margin-bottom: 18px;
}

/* MOBILE */

@media (max-width: 750px) {

    .skills-grid {

        grid-template-columns:
            repeat(2, 1fr);
    }
}

@media (max-width: 600px) {

    .navbar {

        padding: 0 18px;
    }

    .brand-name {

        font-size: 18px;
    }

    .nav-btn {

        padding: 8px 10px;

        font-size: 10px;
    }

    .page {

        padding:
            25px 14px 55px;
    }

    .header {

        padding: 30px 25px;

        border-radius: 19px;
    }

    .header h1 {

        font-size: 28px;
    }

    .profile-strip {

        padding: 15px;
    }

    .skill-count {

        display: none;
    }

    .skills-card {

        padding: 20px;
    }

    .section-top {

        align-items: flex-start;

        gap: 15px;
    }

    .skills-grid {

        grid-template-columns: 1fr;
    }
}

</style>

</head>

<body>

<!-- NAVBAR -->

<nav class="navbar">

    <div class="brand">

        <div class="brand-mark">
            J
        </div>

        <div class="brand-name">
            Job<span>Match</span>
        </div>

    </div>

    <div class="nav-actions">

        <a
            href="recommend.php"
            class="nav-btn matches-btn">

            Find Job Matches

        </a>

        <a
            href="logout.php"
            class="nav-btn logout-btn">

            Logout

        </a>

    </div>

</nav>


<!-- MAIN -->

<main class="page">


    <!-- HEADER -->

    <section class="header">

        <div class="header-label">
            YOUR PROFESSIONAL PROFILE
        </div>

        <h1>
            My <span>Skills</span>
        </h1>

        <p>
            View and manage the technical skills
            connected to your JobMatch profile.
            Keeping your skills updated helps us
            find relevant career opportunities.
        </p>

    </section>


    <!-- PROFILE -->

    <section class="profile-strip">

        <div class="profile-left">

            <div class="avatar">

                <?php

                echo strtoupper(
                    substr($user_name, 0, 1)
                );

                ?>

            </div>

            <div class="profile-text">

                <small>
                    SKILLS PROFILE
                </small>

                <strong>

                    <?php

                    echo htmlspecialchars(
                        $user_name
                    );

                    ?>

                </strong>

            </div>

        </div>


        <div class="skill-count">

            <?php

            echo $result->num_rows;

            ?>

            skills selected

        </div>

    </section>


    <!-- SKILLS -->

    <section class="skills-card">

        <div class="section-top">

            <div>

                <h2>
                    Selected Skills
                </h2>

                <p>
                    Your current technical skill set
                </p>

            </div>


            <a
                href="skills.php"
                class="edit-btn">

                Edit My Skills

            </a>

        </div>


        <?php

        if ($result->num_rows > 0) {

            echo "<div class='skills-grid'>";

            while ($skill = $result->fetch_assoc()) {

                echo "<div class='skill-card'>";

                    echo "<div class='skill-icon'>";
                        echo "✓";
                    echo "</div>";

                    echo htmlspecialchars(
                        $skill['skill_name']
                    );

                echo "</div>";
            }

            echo "</div>";

        } else {

            echo "<div class='empty'>";

                echo "<div class='empty-icon'>";
                    echo "+";
                echo "</div>";

                echo "<h2>";
                    echo "No Skills Added Yet";
                echo "</h2>";

                echo "<p>";
                    echo "Add your technical skills to ";
                    echo "receive personalized job matches.";
                echo "</p>";

                echo "<a";
                    echo " href='skills.php'";
                    echo " class='edit-btn'>";
                    echo "Add Skills";
                echo "</a>";

            echo "</div>";
        }

        ?>

    </section>

</main>

</body>

</html>

<?php

$stmt->close();

$conn->close();

?>

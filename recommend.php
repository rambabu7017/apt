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
    j.id,
    j.job_title,
    j.company,
    j.description,

    COUNT(jrs.skill_id) AS total_required_skills,

    COUNT(us.skill_id) AS matching_skills,

    ROUND(
        (COUNT(us.skill_id) * 100.0) / COUNT(jrs.skill_id),
        2
    ) AS match_percentage,

    GROUP_CONCAT(
        s.skill_name
        ORDER BY s.skill_name
        SEPARATOR ', '
    ) AS required_skill_names,

    GROUP_CONCAT(
        CASE
            WHEN us.skill_id IS NOT NULL
            THEN s.skill_name
        END
        ORDER BY s.skill_name
        SEPARATOR ', '
    ) AS matching_skill_names

FROM jobs j

JOIN job_required_skills jrs
    ON j.id = jrs.job_id

JOIN skills s
    ON s.id = jrs.skill_id

LEFT JOIN user_skills us
    ON us.skill_id = jrs.skill_id
    AND us.user_id = ?

GROUP BY
    j.id,
    j.job_title,
    j.company,
    j.description

ORDER BY
    match_percentage DESC
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

    <title>Career Matches | JobMatch</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background:
                linear-gradient(
                    135deg,
                    #F5F9FD 0%,
                    #EEF6FC 50%,
                    #F8FBFE 100%
                );

            color: #102A43;
            min-height: 100vh;
        }

        /* =====================================================
           NAVBAR
        ===================================================== */

        .navbar {
            height: 72px;

            background: #071C2F;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 6%;

            border-bottom:
                1px solid rgba(255,255,255,0.08);

            position: sticky;
            top: 0;
            z-index: 100;
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

            box-shadow:
                0 5px 18px
                rgba(48,167,226,0.25);
        }

        .brand-name {
            color: white;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.3px;
        }

        .brand-name span {
            color: #62C8FF;
        }

        .nav-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .nav-btn {
            text-decoration: none;
            padding: 9px 15px;
            border-radius: 8px;

            font-size: 12px;
            font-weight: 700;

            transition: 0.25s;
        }

        .skills-btn {
            background: #E8F6FE;
            color: #0B4162;
        }

        .skills-btn:hover {
            background: #D6EFFB;
        }

        .logout-btn {
            color: #D9E6EF;
            border: 1px solid #385267;
        }

        .logout-btn:hover {
            background: #102D45;
            color: white;
        }

        /* =====================================================
           PAGE
        ===================================================== */

        .page {
            max-width: 1220px;
            margin: auto;
            padding: 45px 24px 80px;
        }

        /* =====================================================
           HERO
        ===================================================== */

        .hero {
            position: relative;

            background:
                linear-gradient(
                    120deg,
                    #071C2F,
                    #0B3552
                );

            border-radius: 24px;

            padding: 42px 45px;

            overflow: hidden;

            margin-bottom: 30px;

            box-shadow:
                0 18px 45px
                rgba(7,28,47,0.14);
        }

        .hero::before {
            content: "";

            position: absolute;

            width: 270px;
            height: 270px;

            border-radius: 50%;

            background:
                rgba(77,190,239,0.10);

            right: -80px;
            top: -100px;
        }

        .hero::after {
            content: "";

            position: absolute;

            width: 180px;
            height: 180px;

            border-radius: 50%;

            border:
                1px solid
                rgba(108,210,255,0.15);

            right: 90px;
            bottom: -100px;
        }

        .hero-content {
            position: relative;
            z-index: 2;

            max-width: 760px;
        }

        .hero-label {
            display: inline-flex;
            align-items: center;
            gap: 7px;

            color: #74D2FF;

            font-size: 10px;
            font-weight: 800;

            letter-spacing: 1.6px;

            margin-bottom: 14px;
        }

        .hero-label::before {
            content: "";

            width: 7px;
            height: 7px;

            border-radius: 50%;

            background: #61C9F5;
        }

        .hero h1 {
            color: white;

            font-size: 39px;
            line-height: 1.15;

            letter-spacing: -1px;

            margin-bottom: 13px;
        }

        .hero h1 span {
            color: #62C8FF;
        }

        .hero-description {
            color: #B9CFDE;

            font-size: 14px;
            line-height: 1.8;

            max-width: 670px;
        }

        /* =====================================================
           PROFILE STRIP
        ===================================================== */

        .profile-strip {
            background: white;

            border:
                1px solid #DCE8F0;

            border-radius: 16px;

            padding: 19px 22px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            margin-bottom: 30px;

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

        .status {
            display: flex;
            align-items: center;
            gap: 7px;

            color: #24739A;

            font-size: 11px;
            font-weight: 700;
        }

        .status-dot {
            width: 8px;
            height: 8px;

            border-radius: 50%;

            background: #48A8D3;

            box-shadow:
                0 0 0 4px #E7F5FC;
        }

        /* =====================================================
           SECTION HEADING
        ===================================================== */

        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;

            margin-bottom: 18px;
        }

        .section-heading h2 {
            font-size: 21px;
            color: #102A43;
        }

        .section-heading p {
            color: #718797;
            font-size: 12px;
            margin-top: 5px;
        }

        .results-label {
            color: #668090;
            font-size: 11px;
            font-weight: 700;
        }

        /* =====================================================
           JOB GRID
        ===================================================== */

        .jobs-grid {
            display: grid;

            grid-template-columns:
                repeat(2, minmax(0, 1fr));

            gap: 22px;
        }

        /* =====================================================
           JOB CARD
        ===================================================== */

        .job-card {
            position: relative;

            background: white;

            border:
                1px solid #DCE8F0;

            border-radius: 19px;

            padding: 27px;

            overflow: hidden;

            box-shadow:
                0 8px 28px
                rgba(7,28,47,0.055);

            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease,
                border-color 0.25s ease;
        }

        .job-card:hover {
            transform: translateY(-5px);

            border-color: #BFDCEB;

            box-shadow:
                0 18px 40px
                rgba(7,28,47,0.10);
        }

        .job-card::before {
            content: "";

            position: absolute;

            top: 0;
            left: 0;

            width: 4px;
            height: 100%;

            background:
                linear-gradient(
                    #5CC8FF,
                    #1879B1
                );
        }

        /* =====================================================
           JOB TOP
        ===================================================== */

        .job-top {
            display: flex;

            justify-content: space-between;

            gap: 20px;

            margin-bottom: 22px;
        }

        .company-avatar {
            width: 45px;
            height: 45px;

            border-radius: 12px;

            background: #EEF7FC;

            border:
                1px solid #D6EAF4;

            display: flex;
            align-items: center;
            justify-content: center;

            color: #176C98;

            font-size: 15px;
            font-weight: 800;

            margin-bottom: 12px;
        }

        .job-title {
            font-size: 20px;
            color: #102A43;

            line-height: 1.3;

            margin-bottom: 5px;
        }

        .company-name {
            color: #2380B1;

            font-size: 12px;
            font-weight: 700;
        }

        /* =====================================================
           MATCH SCORE
        ===================================================== */

        .score {
            width: 76px;
            height: 76px;

            flex-shrink: 0;

            border-radius: 50%;

            background:
                conic-gradient(
                    #2997CF
                    <?php echo "0deg"; ?>,
                    #2997CF
                    <?php echo "0deg"; ?>,
                    #E8F0F4
                    <?php echo "360deg"; ?>
                );

            position: relative;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .score::before {
            content: "";

            position: absolute;

            width: 61px;
            height: 61px;

            background: white;

            border-radius: 50%;
        }

        .score-content {
            position: relative;
            z-index: 2;

            text-align: center;
        }

        .score-number {
            display: block;

            font-size: 16px;
            font-weight: 800;

            color: #102A43;
        }

        .score-label {
            display: block;

            font-size: 7px;

            color: #748897;

            font-weight: 800;

            letter-spacing: 0.7px;
        }

        /* =====================================================
           DESCRIPTION
        ===================================================== */

        .description {
            color: #607685;

            font-size: 13px;

            line-height: 1.7;

            padding-bottom: 21px;

            border-bottom:
                1px solid #E7EEF3;

            margin-bottom: 20px;
        }

        /* =====================================================
           SKILLS
        ===================================================== */

        .skill-section {
            margin-bottom: 19px;
        }

        .skill-heading {
            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 10px;
        }

        .skill-heading span:first-child {
            font-size: 10px;

            text-transform: uppercase;

            letter-spacing: 0.9px;

            font-weight: 800;

            color: #344F61;
        }

        .skill-heading span:last-child {
            font-size: 10px;

            color: #8294A1;

            font-weight: 700;
        }

        .skills {
            display: flex;

            flex-wrap: wrap;

            gap: 7px;
        }

        .skill {
            padding: 5px 10px;

            border-radius: 7px;

            font-size: 10px;

            font-weight: 700;
        }

        .required {
            background: #F5F8FA;

            border:
                1px solid #D9E3E9;

            color: #4A6170;
        }

        .matching {
            background: #E8F6FD;

            border:
                1px solid #BFE2F2;

            color: #0B668F;
        }

        .no-match {
            color: #8799A5;

            font-size: 11px;
        }

        /* =====================================================
           PROGRESS
        ===================================================== */

        .progress-area {
            margin-top: 2px;

            margin-bottom: 20px;
        }

        .progress-top {
            display: flex;

            justify-content: space-between;

            margin-bottom: 7px;
        }

        .progress-top span {
            font-size: 10px;

            color: #718491;

            font-weight: 700;
        }

        .progress-bar {
            height: 6px;

            width: 100%;

            background: #E8EFF3;

            border-radius: 20px;

            overflow: hidden;
        }

        .progress-fill {
            height: 100%;

            background:
                linear-gradient(
                    90deg,
                    #4EB9E8,
                    #1675AA
                );

            border-radius: 20px;
        }

        /* =====================================================
           FOOTER
        ===================================================== */

        .card-footer {
            border-top:
                1px solid #E7EEF3;

            padding-top: 17px;

            display: flex;

            justify-content: space-between;

            align-items: center;
        }

        .matched-count {
            color: #687E8D;

            font-size: 11px;
        }

        .matched-count strong {
            color: #102A43;

            font-size: 12px;
        }

        .match-status {
            padding: 6px 10px;

            border-radius: 6px;

            background: #EDF7FB;

            color: #1A7099;

            font-size: 9px;

            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: 0.5px;
        }

        /* =====================================================
           EMPTY STATE
        ===================================================== */

        .empty {
            background: white;

            border:
                1px solid #DCE8F0;

            border-radius: 18px;

            padding: 65px 25px;

            text-align: center;

            box-shadow:
                0 8px 28px
                rgba(7,28,47,0.05);
        }

        .empty-icon {
            width: 62px;
            height: 62px;

            border-radius: 17px;

            background: #E8F6FD;

            color: #1679AD;

            display: flex;
            align-items: center;
            justify-content: center;

            margin: auto auto 17px;

            font-size: 25px;
            font-weight: 800;
        }

        .empty h2 {
            font-size: 20px;

            margin-bottom: 8px;
        }

        .empty p {
            color: #718492;

            font-size: 13px;
        }

        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 850px) {

            .jobs-grid {
                grid-template-columns: 1fr;
            }

            .hero h1 {
                font-size: 32px;
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
                padding: 25px 14px 55px;
            }

            .hero {
                padding: 30px 25px;
                border-radius: 19px;
            }

            .hero h1 {
                font-size: 28px;
            }

            .hero-description {
                font-size: 13px;
            }

            .profile-strip {
                padding: 15px;
            }

            .status {
                display: none;
            }

            .job-card {
                padding: 23px;
            }

            .job-title {
                font-size: 18px;
            }

            .section-heading {
                align-items: flex-start;
            }

            .results-label {
                display: none;
            }

        }

    </style>

</head>

<body>


<!-- =====================================================
     NAVBAR
===================================================== -->

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

        <a href="my_skills.php"
           class="nav-btn skills-btn">
            My Skills
        </a>

        <a href="logout.php"
           class="nav-btn logout-btn">
            Logout
        </a>

    </div>

</nav>


<!-- =====================================================
     PAGE
===================================================== -->

<main class="page">


    <!-- HERO -->

    <section class="hero">

        <div class="hero-content">

            <div class="hero-label">
                PERSONALIZED CAREER DISCOVERY
            </div>

            <h1>
                Find roles that match
                <span>your skills.</span>
            </h1>

            <p class="hero-description">
                Your profile has been compared with the
                required skills for available roles. Explore
                each opportunity and see exactly where your
                skills match.
            </p>

        </div>

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
                    CURRENT PROFILE
                </small>

                <strong>
                    <?php
                    echo htmlspecialchars($user_name);
                    ?>
                </strong>

            </div>

        </div>


        <div class="status">

            <span class="status-dot"></span>

            Skills profile active

        </div>

    </section>


    <!-- SECTION TITLE -->

    <div class="section-heading">

        <div>

            <h2>
                Career Opportunities
            </h2>

            <p>
                Compare required skills with your current profile.
            </p>

        </div>

        <span class="results-label">
            LIVE SKILL ANALYSIS
        </span>

    </div>


    <!-- =================================================
         JOB RESULTS
    ================================================= -->

    <?php

    if ($result->num_rows > 0) {

        echo "<div class='jobs-grid'>";


        while ($job = $result->fetch_assoc()) {


            $percentage =
                (float) $job['match_percentage'];


            /*
             * Convert percentage into a visual
             * circle angle.
             */

            $angle =
                ($percentage / 100) * 360;


            /*
             * Initials for company avatar.
             */

            $company =
                $job['company'];

            $company_initial =
                strtoupper(
                    substr($company, 0, 1)
                );


            /*
             * Descriptive status only.
             */

            if ($percentage >= 75) {

                $status_text =
                    "Strong Match";

            } elseif ($percentage >= 50) {

                $status_text =
                    "Good Match";

            } else {

                $status_text =
                    "Partial Match";
            }


            echo "<article class='job-card'>";


            /* =================================================
               JOB TOP
            ================================================= */

            echo "<div class='job-top'>";

                echo "<div>";

                    echo "<div class='company-avatar'>"
                        . htmlspecialchars(
                            $company_initial
                        )
                        . "</div>";

                    echo "<h3 class='job-title'>"
                        . htmlspecialchars(
                            $job['job_title']
                        )
                        . "</h3>";

                    echo "<div class='company-name'>"
                        . htmlspecialchars(
                            $job['company']
                        )
                        . "</div>";

                echo "</div>";


                /* MATCH SCORE */

                echo "<div class='score'
                       style='background:
                       conic-gradient(
                           #2997CF 0deg,
                           #2997CF "
                           . $angle .
                           "deg,
                           #E8F0F4 "
                           . $angle .
                           "deg,
                           #E8F0F4 360deg
                       );'>";

                    echo "<div class='score-content'>";

                        echo "<span class='score-number'>"
                            . $percentage .
                            "%</span>";

                        echo "<span class='score-label'>
                                MATCH
                              </span>";

                    echo "</div>";

                echo "</div>";

            echo "</div>";


            /* =================================================
               DESCRIPTION
            ================================================= */

            echo "<p class='description'>"
                . htmlspecialchars(
                    $job['description']
                )
                . "</p>";


            /* =================================================
               REQUIRED SKILLS
            ================================================= */

            echo "<div class='skill-section'>";

                echo "<div class='skill-heading'>";

                    echo "<span>
                            Required Skills
                          </span>";

                    echo "<span>"
                        . $job['total_required_skills']
                        . " skills
                          </span>";

                echo "</div>";


                echo "<div class='skills'>";


                $required_skills =
                    explode(
                        ", ",
                        $job['required_skill_names']
                    );


                foreach ($required_skills as $skill) {

                    echo "<span class='skill required'>"
                        . htmlspecialchars($skill)
                        . "</span>";

                }


                echo "</div>";

            echo "</div>";


            /* =================================================
               MATCHING SKILLS
            ================================================= */

            echo "<div class='skill-section'>";

                echo "<div class='skill-heading'>";

                    echo "<span>
                            Your Matching Skills
                          </span>";

                    echo "<span>"
                        . $job['matching_skills']
                        . " matched
                          </span>";

                echo "</div>";


                if (!empty(
                    $job['matching_skill_names']
                )) {

                    echo "<div class='skills'>";


                    $matching_skills =
                        explode(
                            ", ",
                            $job['matching_skill_names']
                        );


                    foreach (
                        $matching_skills
                        as $skill
                    ) {

                        echo "<span class='skill matching'>"
                            . htmlspecialchars($skill)
                            . "</span>";

                    }


                    echo "</div>";

                } else {

                    echo "<div class='no-match'>
                            No matching skills yet.
                          </div>";

                }

            echo "</div>";


            /* =================================================
               PROGRESS
            ================================================= */

            echo "<div class='progress-area'>";

                echo "<div class='progress-top'>";

                    echo "<span>
                            Skill compatibility
                          </span>";

                    echo "<span>"
                        . $percentage .
                        "%
                          </span>";

                echo "</div>";


                echo "<div class='progress-bar'>";

                    echo "<div
                            class='progress-fill'
                            style='width:"
                            . $percentage .
                            "%;'>
                          </div>";

                echo "</div>";

            echo "</div>";


            /* =================================================
               FOOTER
            ================================================= */

            echo "<div class='card-footer'>";

                echo "<div class='matched-count'>";

                    echo "<strong>"
                        . $job['matching_skills']
                        . "</strong>";

                    echo " of ";

                    echo "<strong>"
                        . $job['total_required_skills']
                        . "</strong>";

                    echo " required skills matched";

                echo "</div>";


                echo "<div class='match-status'>"
                    . $status_text
                    . "</div>";

            echo "</div>";


            echo "</article>";

        }


        echo "</div>";

    } else {


        /* =================================================
           EMPTY STATE
        ================================================= */

        echo "<div class='empty'>";

            echo "<div class='empty-icon'>
                    +
                  </div>";

            echo "<h2>
                    No Career Matches Available
                  </h2>";

            echo "<p>
                    Add more skills to your profile to
                    explore available opportunities.
                  </p>";

        echo "</div>";

    }


    $stmt->close();

    $conn->close();

    ?>

</main>

</body>

</html>

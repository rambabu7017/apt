<?php

session_start();

include 'db.php';

$sql = "SELECT id, skill_name FROM skills ORDER BY skill_name";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Create Profile | JobMatch</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {

            font-family: Arial, Helvetica, sans-serif;

            background: #F5F9FD;

            min-height: 100vh;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 25px;
        }

        .container {

            width: 100%;

            max-width: 1180px;

            min-height: 720px;

            background: white;

            border-radius: 22px;

            overflow: hidden;

            display: grid;

            grid-template-columns: 43% 57%;

            box-shadow:
                0 20px 55px
                rgba(7, 28, 47, 0.12);
        }


        /* ================= LEFT PANEL ================= */

        .left-panel {

            background:
                linear-gradient(
                    145deg,
                    #071C2F,
                    #092B4A,
                    #0B4168
                );

            padding: 60px 45px;

            color: white;

            position: relative;

            overflow: hidden;
        }

        .left-panel::before {

            content: "";

            position: absolute;

            width: 330px;

            height: 330px;

            border-radius: 50%;

            background:
                rgba(50, 165, 225, 0.10);

            right: -150px;

            bottom: -130px;
        }

        .left-panel::after {

            content: "";

            position: absolute;

            width: 180px;

            height: 180px;

            border-radius: 50%;

            border:
                1px solid
                rgba(98, 200, 255, 0.13);

            right: 50px;

            top: -80px;
        }

        .left-content {

            position: relative;

            z-index: 2;
        }

        .system-label {

            color: #74D2FF;

            font-size: 11px;

            font-weight: 800;

            letter-spacing: 1.4px;

            margin-bottom: 45px;
        }

        .left-panel h1 {

            font-size: 39px;

            line-height: 1.15;

            margin-bottom: 22px;

            letter-spacing: -1px;
        }

        .left-panel h1 span {

            color: #38A9F0;
        }

        .left-description {

            color: #C3D7E6;

            font-size: 14px;

            line-height: 1.8;

            max-width: 430px;

            margin-bottom: 42px;
        }


        /* ================= STEPS ================= */

        .steps {

            display: flex;

            flex-direction: column;

            gap: 25px;
        }

        .step {

            display: flex;

            align-items: center;

            gap: 17px;
        }

        .step-number {

            width: 48px;

            height: 48px;

            border-radius: 12px;

            background:
                rgba(255,255,255,0.90);

            color: #17344A;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 13px;

            font-weight: 800;

            flex-shrink: 0;
        }

        .step-text h3 {

            font-size: 14px;

            margin-bottom: 5px;

            color: white;
        }

        .step-text p {

            color: #AFC7D8;

            font-size: 11px;

            line-height: 1.5;
        }


        /* ================= RIGHT PANEL ================= */

        .right-panel {

            padding: 42px 55px;

            overflow-y: auto;

            max-height: 720px;
        }

        .right-panel h2 {

            font-size: 30px;

            color: #102A43;

            margin-bottom: 6px;
        }

        .subtitle {

            color: #718797;

            font-size: 13px;

            margin-bottom: 28px;
        }


        /* ================= FORM ================= */

        .form-group {

            margin-bottom: 18px;
        }

        .form-group label {

            display: block;

            color: #263F50;

            font-size: 12px;

            font-weight: 700;

            margin-bottom: 7px;
        }

        .form-group input,
        .form-group select {

            width: 100%;

            height: 45px;

            padding: 0 14px;

            border:
                1px solid
                #D5E1E8;

            border-radius: 9px;

            background: #FCFEFF;

            color: #263F50;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            font-size: 12px;

            outline: none;

            transition: 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus {

            border-color: #52B6DF;

            box-shadow:
                0 0 0 3px
                rgba(82,182,223,0.10);

            background: white;
        }

        .form-group select {

            cursor: pointer;
        }


        /* ================= SKILLS HEADER ================= */

        .skills-header {

            display: flex;

            align-items: flex-end;

            justify-content: space-between;

            margin-top: 27px;

            margin-bottom: 13px;
        }

        .skills-title h3 {

            color: #263F50;

            font-size: 18px;

            margin-bottom: 5px;
        }

        .skills-title p {

            color: #8496A3;

            font-size: 11px;
        }

        .multiple-text {

            color: #247CA6;

            font-size: 9px;

            font-weight: 800;

            letter-spacing: 0.5px;
        }


        /* ================= SKILLS CONTAINER ================= */

        .skills-container {

            border:
                1px solid
                #DCE8F0;

            border-radius: 12px;

            padding: 14px;

            background: #FBFDFE;

            max-height: 270px;

            overflow-y: auto;
        }

        .skills-grid {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 9px;
        }

        .skill-option {

            position: relative;
        }


        /* Hide normal checkbox */

        .skill-option input {

            position: absolute;

            opacity: 0;

            pointer-events: none;
        }


        /* ================= SKILL CARD ================= */

        .skill-label {

            min-height: 44px;

            padding: 8px 9px;

            border:
                1px solid
                #D8E5EC;

            border-radius: 8px;

            background: white;

            display: flex;

            align-items: center;

            gap: 7px;

            cursor: pointer;

            color: #355063;

            font-size: 10px;

            font-weight: 700;

            transition: 0.2s ease;

            user-select: none;
        }

        .skill-label:hover {

            border-color: #8AC9E3;

            background: #F3FAFD;

            transform:
                translateY(-1px);
        }


        /* ================= CHECKBOX ================= */

        .checkbox-box {

            width: 17px;

            height: 17px;

            border:
                1.5px solid
                #B8CBD7;

            border-radius: 5px;

            background: white;

            display: flex;

            align-items: center;

            justify-content: center;

            color: transparent;

            font-size: 10px;

            font-weight: 800;

            flex-shrink: 0;

            transition: 0.2s ease;
        }


        /* ================= SELECTED ================= */

        .skill-option input:checked
        + .skill-label {

            background:
                linear-gradient(
                    135deg,
                    #EEF9FE,
                    #E5F5FC
                );

            border-color: #58B8DE;

            color: #0B668F;

            box-shadow:
                0 3px 9px
                rgba(42,151,207,0.08);
        }

        .skill-option input:checked
        + .skill-label
        .checkbox-box {

            background: #1679AD;

            border-color: #1679AD;

            color: white;
        }


        /* ================= REGISTER BUTTON ================= */

        .register-btn {

            width: 100%;

            height: 46px;

            border: none;

            border-radius: 9px;

            margin-top: 22px;

            background:
                linear-gradient(
                    135deg,
                    #0B3552,
                    #1679AD
                );

            color: white;

            font-size: 12px;

            font-weight: 800;

            cursor: pointer;

            box-shadow:
                0 7px 18px
                rgba(22,121,173,0.18);

            transition: 0.2s;
        }

        .register-btn:hover {

            transform:
                translateY(-1px);

            box-shadow:
                0 10px 22px
                rgba(22,121,173,0.24);
        }


        /* ================= LOGIN ================= */

        .login-link {

            text-align: center;

            margin-top: 17px;

            color: #7A8E9C;

            font-size: 11px;
        }

        .login-link a {

            color: #1679AD;

            font-weight: 700;

            text-decoration: none;
        }

        .login-link a:hover {

            text-decoration: underline;
        }


        /* ================= MOBILE ================= */

        @media (max-width: 900px) {

            .container {

                grid-template-columns: 1fr;
            }

            .left-panel {

                padding: 40px 30px;
            }

            .system-label {

                margin-bottom: 25px;
            }

            .left-panel h1 {

                font-size: 32px;
            }

            .steps {

                display: none;
            }

            .right-panel {

                max-height: none;
            }
        }


        @media (max-width: 600px) {

            body {

                padding: 12px;
            }

            .left-panel {

                padding: 30px 24px;
            }

            .left-description {

                margin-bottom: 0;
            }

            .right-panel {

                padding: 30px 22px;
            }

            .right-panel h2 {

                font-size: 26px;
            }

            .skills-grid {

                grid-template-columns:
                    repeat(2, 1fr);
            }
        }

    </style>

</head>


<body>


<div class="container">


    <!-- ================= LEFT PANEL ================= -->

    <section class="left-panel">

        <div class="left-content">


            <div class="system-label">

                JOB RECOMMENDATION SYSTEM

            </div>


            <h1>

                Build Your Profile.<br>

                <span>Find Your Match.</span>

            </h1>


            <p class="left-description">

                Create your profile and tell us about
                your technical skills. Your skills will
                help the system identify job opportunities
                that match your profile.

            </p>


            <div class="steps">


                <div class="step">

                    <div class="step-number">
                        01
                    </div>

                    <div class="step-text">

                        <h3>
                            Choose Your Skills
                        </h3>

                        <p>
                            Select the technologies and skills you know.
                        </p>

                    </div>

                </div>


                <div class="step">

                    <div class="step-number">
                        02
                    </div>

                    <div class="step-text">

                        <h3>
                            Build Your Profile
                        </h3>

                        <p>
                            Your selected skills become part of your profile.
                        </p>

                    </div>

                </div>


                <div class="step">

                    <div class="step-number">
                        03
                    </div>

                    <div class="step-text">

                        <h3>
                            Discover Job Matches
                        </h3>

                        <p>
                            See jobs based on how closely your skills match.
                        </p>

                    </div>

                </div>


            </div>

        </div>

    </section>



    <!-- ================= RIGHT PANEL ================= -->

    <section class="right-panel">


        <h2>
            Create Your Profile
        </h2>


        <p class="subtitle">
            Enter your details and select your skills below.
        </p>


        <form
            action="register_process.php"
            method="POST">


            <!-- NAME -->

            <div class="form-group">

                <label for="name">
                    Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Enter your name"
                    required
                >

            </div>


            <!-- EMAIL -->

            <div class="form-group">

                <label for="email">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                >

            </div>


            <!-- PASSWORD -->

            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Create a password"
                    required
                >

            </div>


            <!-- GENDER -->

            <div class="form-group">

                <label for="gender">
                    Gender
                </label>

                <select
                    name="gender"
                    id="gender"
                    required
                >

                    <option
                        value=""
                        disabled
                        selected
                    >
                        Select your gender
                    </option>

                    <option value="Male">
                        Male
                    </option>

                    <option value="Female">
                        Female
                    </option>

                    <option value="Other">
                        Other
                    </option>

                    <option value="Prefer not to say">
                        Prefer not to say
                    </option>

                </select>

            </div>


            <!-- SELECT SKILLS -->

            <div class="skills-header">


                <div class="skills-title">

                    <h3>
                        Select Your Skills
                    </h3>

                    <p>
                        Select all skills that apply to you.
                    </p>

                </div>


                <div class="multiple-text">

                    MULTIPLE SELECTION

                </div>


            </div>


            <!-- SKILLS -->

            <div class="skills-container">


                <div class="skills-grid">


                    <?php

                    if ($result->num_rows > 0) {

                        while (
                            $skill = $result->fetch_assoc()
                        ) {

                    ?>


                        <div class="skill-option">


                            <input
                                type="checkbox"

                                id="skill_<?php
                                    echo $skill['id'];
                                ?>"

                                name="skills[]"

                                value="<?php
                                    echo $skill['id'];
                                ?>"
                            >


                            <label
                                class="skill-label"

                                for="skill_<?php
                                    echo $skill['id'];
                                ?>"
                            >


                                <span class="checkbox-box">
                                    ✓
                                </span>


                                <span>

                                    <?php

                                    echo htmlspecialchars(
                                        $skill['skill_name']
                                    );

                                    ?>

                                </span>


                            </label>


                        </div>


                    <?php

                        }

                    }

                    ?>


                </div>


            </div>


            <!-- REGISTER BUTTON -->

            <button
                type="submit"
                class="register-btn">

                Create My Profile

            </button>


        </form>


        <!-- LOGIN -->

        <div class="login-link">

            <span>
                Already have an account?
            </span>

            <a href="login.php">
                Login here
            </a>

        </div>


    </section>


</div>


</body>

</html>


<?php

$conn->close();

?>

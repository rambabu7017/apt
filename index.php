<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>JobMatch | Skill-Based Job Recommendation</title>

    <style>

        /* ================= GENERAL ================= */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: #F5F9FC;
            color: #061A2F;
        }


        /* ================= NAVBAR ================= */

        .navbar {
            height: 72px;
            background: #061A2F;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 7%;
        }

        .logo {
            color: #FFFFFF;
            font-size: 25px;
            font-weight: 700;
        }

        .logo span {
            color: #1677B8;
        }

        .nav-links {
            display: flex;
            gap: 32px;
        }

        .nav-links a {
            color: #E8F4FC;
            text-decoration: none;

            font-size: 15px;
            font-weight: 600;

            transition: 0.3s;
        }

        .nav-links a:hover {
            color: #8EDCFF;
        }


        /* ================= HERO ================= */

        .hero {
            width: 86%;
            max-width: 1250px;

            margin: 78px auto 75px;

            display: flex;
            align-items: center;

            gap: 55px;
        }

        .hero-content {
            width: 50%;
        }


        /* ================= BADGE ================= */

        .badge {
            display: inline-block;

            background: #E8F4FC;
            color: #0A2A43;

            padding: 10px 18px;

            border-radius: 30px;

            border: 1.5px solid #061A2F;

            font-size: 12px;
            font-weight: 700;

            letter-spacing: 1px;

            margin-bottom: 25px;
        }


        /* ================= HERO TITLE ================= */

        .hero-content h1 {
            font-size: 54px;
            line-height: 1.08;

            color: #061A2F;

            margin-bottom: 25px;

            letter-spacing: -1.5px;
        }

        .hero-content h1 span {
            color: #1677B8;
        }


        /* ================= HERO DESCRIPTION ================= */

        .hero-content p {
            max-width: 600px;

            color: #526575;

            font-size: 17px;
            line-height: 1.7;

            margin-bottom: 32px;
        }


        /* ================= HERO BUTTONS ================= */

        .buttons {
            display: flex;
            gap: 15px;
        }

        .btn-primary {
            display: inline-block;

            background: #061A2F;
            color: #FFFFFF;

            padding: 14px 27px;

            border-radius: 8px;

            text-decoration: none;

            font-size: 15px;
            font-weight: 700;

            border: 2px solid #061A2F;

            transition: 0.3s;
        }

        .btn-primary:hover {
            background: #1677B8;
            border-color: #1677B8;
        }


        /* ================= LOGIN BUTTON ================= */

        .btn-secondary {
            display: inline-block;

            background: #E8F4FC;
            color: #0A2A43;

            padding: 14px 27px;

            border-radius: 8px;

            border: 2px solid #061A2F;

            text-decoration: none;

            font-size: 15px;
            font-weight: 700;

            transition: 0.3s;
        }

        .btn-secondary:hover {
            background: #061A2F;
            color: #FFFFFF;

            border-color: #061A2F;
        }


        /* ================= HERO IMAGE ================= */

        .hero-image {
            width: 50%;
            height: 435px;

            position: relative;

            overflow: hidden;

            border-radius: 18px;

            box-shadow:
                0 20px 45px rgba(6, 26, 47, 0.18);
        }

        .hero-image img {
            width: 100%;
            height: 100%;

            object-fit: cover;

            display: block;
        }


        /* ================= SKILLS CARD ================= */

        .skills-card {
            position: absolute;

            top: 28px;
            right: 18px;

            width: 175px;

            background: #FFFFFF;

            padding: 20px;

            border-radius: 15px;

            box-shadow:
                0 12px 30px rgba(6, 26, 47, 0.18);
        }

        .skills-card h3 {
            color: #061A2F;

            font-size: 14px;

            margin-bottom: 13px;
        }

        .skill {
            display: inline-block;

            background: #E8F4FC;
            color: #0A2A43;

            padding: 7px 9px;

            border-radius: 5px;

            font-size: 11px;
            font-weight: 600;

            margin: 3px;
        }


        /* ================= JOB MATCH CARD ================= */

        .match-card {
            position: absolute;

            left: 25px;
            bottom: 25px;

            width: 235px;

            background: #FFFFFF;

            padding: 20px;

            border-radius: 15px;

            box-shadow:
                0 12px 30px rgba(6, 26, 47, 0.18);
        }

        .match-label {
            color: #526575;

            font-size: 11px;
            font-weight: 700;

            letter-spacing: 0.8px;

            margin-bottom: 10px;
        }

        .match-card h3 {
            color: #061A2F;

            font-size: 18px;

            margin-bottom: 14px;
        }

        .match-line {
            width: 100%;
            height: 7px;

            background: #E8F4FC;

            border-radius: 10px;

            overflow: hidden;

            margin-bottom: 9px;
        }

        .match-line span {
            display: block;

            width: 82%;
            height: 100%;

            background: #1677B8;

            border-radius: 10px;
        }

        .match-text {
            color: #526575;

            font-size: 11px;
        }


        /* ================= THREE INFORMATION CARDS ================= */

        .info-section {
            width: 86%;
            max-width: 1250px;

            margin: 0 auto 45px;

            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 22px;
        }

        .info-card {
            background: #FFFFFF;

            padding: 28px;

            min-height: 175px;

            border-radius: 15px;

            border: 1px solid #E8F4FC;

            box-shadow:
                0 8px 25px rgba(6, 26, 47, 0.07);

            transition: 0.3s;
        }

        .info-card:hover {
            transform: translateY(-4px);

            box-shadow:
                0 14px 30px rgba(6, 26, 47, 0.12);
        }

        .info-number {
            color: #1677B8;

            font-size: 12px;
            font-weight: 700;

            letter-spacing: 0.8px;

            margin-bottom: 25px;
        }

        .info-card h3 {
            color: #061A2F;

            font-size: 19px;

            margin-bottom: 12px;
        }

        .info-card p {
            color: #526575;

            font-size: 14px;

            line-height: 1.6;
        }


        /* ================= CREATE YOUR PROFILE ================= */

        .create-profile {
            width: 86%;
            max-width: 1250px;

            margin: 0 auto 65px;

            padding: 48px 55px;

            background:
                linear-gradient(
                    135deg,
                    #061A2F,
                    #0A2A43
                );

            border-radius: 20px;

            border: 1px solid rgba(232, 244, 252, 0.15);

            box-shadow:
                0 18px 40px rgba(6, 26, 47, 0.16);

            text-align: center;
        }

        .create-profile h2 {
            color: #FFFFFF;

            font-size: 31px;

            margin-bottom: 12px;
        }

        .create-profile p {
            color: #DFF4FF;

            font-size: 15px;

            line-height: 1.6;

            margin-bottom: 28px;
        }


        /* ================= CREATE PROFILE BUTTON ================= */

        .create-profile-btn {
            display: inline-block;

            background: #DFF4FF;
            color: #061A2F;

            padding: 14px 30px;

            border-radius: 8px;

            text-decoration: none;

            font-size: 15px;
            font-weight: 700;

            border: 1px solid #FFFFFF;

            box-shadow:
                0 5px 15px rgba(0, 0, 0, 0.10);

            transition: all 0.3s ease;
        }

        .create-profile-btn:hover {
            background: #FFFFFF;
            color: #1677B8;

            transform: translateY(-2px);

            box-shadow:
                0 8px 20px rgba(0, 0, 0, 0.16);
        }


        /* ================= FOOTER ================= */

        footer {
            background: #061A2F;

            color: #E8F4FC;

            text-align: center;

            padding: 21px;

            font-size: 13px;
        }


        /* ================= RESPONSIVE ================= */

        @media (max-width: 900px) {

            .hero {
                flex-direction: column;
            }

            .hero-content,
            .hero-image {
                width: 100%;
            }

            .hero-content h1 {
                font-size: 43px;
            }

            .info-section {
                grid-template-columns: 1fr;
            }

            .create-profile {
                padding: 40px 30px;
            }
        }


        @media (max-width: 600px) {

            .navbar {
                padding: 0 5%;
            }

            .nav-links {
                gap: 15px;
            }

            .nav-links a {
                font-size: 13px;
            }

            .hero {
                width: 90%;
            }

            .hero-content h1 {
                font-size: 36px;
            }

            .hero-image {
                height: 300px;
            }

            .skills-card {
                width: 145px;
            }

            .match-card {
                width: 200px;
            }

            .info-section,
            .create-profile {
                width: 90%;
            }

            .create-profile h2 {
                font-size: 25px;
            }

        }

    </style>

</head>


<body>


    <!-- ================= NAVBAR ================= -->

    <nav class="navbar">

        <div class="logo">
            Job<span>Match</span>
        </div>

        <div class="nav-links">

            <a href="register.php">Register</a>

            <a href="login.php">Login</a>

        </div>

    </nav>


    <!-- ================= HERO ================= -->

    <section class="hero">

        <div class="hero-content">

            <div class="badge">
                SKILL-BASED JOB RECOMMENDATION
            </div>

            <h1>
                Your Skills.<br>
                <span>Your Opportunities.</span>
            </h1>

            <p>
                Discover job opportunities that match your
                technical skills. Build your skill profile,
                analyze job requirements, and explore
                personalized recommendations.
            </p>

            <div class="buttons">

                <a href="register.php" class="btn-primary">
                    Get Started
                </a>

                <a href="login.php" class="btn-secondary">
                    Login
                </a>

            </div>

        </div>


        <!-- ================= HERO IMAGE ================= -->

        <div class="hero-image">

            <img
                src="https://images.unsplash.com/photo-1603201667230-bd139210db18?auto=format&fit=crop&fm=jpg&q=85&w=1200"
                alt="Professionals collaborating in a modern office"
            >


            <!-- YOUR SKILLS -->

            <div class="skills-card">

                <h3>
                    Your Skills
                </h3>

                <span class="skill">AWS</span>
                <span class="skill">Python</span>
                <span class="skill">MySQL</span>
                <span class="skill">Git</span>
                <span class="skill">Docker</span>

            </div>


            <!-- JOB MATCH -->

            <div class="match-card">

                <div class="match-label">
                    JOB MATCH
                </div>

                <h3>
                    Software Developer
                </h3>

                <div class="match-line">
                    <span></span>
                </div>

                <div class="match-text">
                    Strong skill match
                </div>

            </div>

        </div>

    </section>


    <!-- ================= THREE INFORMATION CARDS ================= -->

    <section class="info-section">


        <div class="info-card">

            <div class="info-number">
                01 / PROFILE
            </div>

            <h3>
                Create Your Profile
            </h3>

            <p>
                Register your account and select the technical
                skills you currently have.
            </p>

        </div>


        <div class="info-card">

            <div class="info-number">
                02 / ANALYZE
            </div>

            <h3>
                Analyze Your Skills
            </h3>

            <p>
                The system compares your selected skills with
                the skills required by available jobs.
            </p>

        </div>


        <div class="info-card">

            <div class="info-number">
                03 / DISCOVER
            </div>

            <h3>
                Get Recommendations
            </h3>

            <p>
                View jobs with matching skills and a calculated
                match percentage.
            </p>

        </div>


    </section>


    <!-- ================= CREATE YOUR PROFILE ================= -->

    <section class="create-profile">

        <h2>
            Ready to Find Your Match?
        </h2>

        <p>
            Build your skill profile and discover suitable
            job opportunities.
        </p>

        <a
            href="register.php"
            class="create-profile-btn"
        >
            Create Your Profile
        </a>

    </section>


    <!-- ================= FOOTER ================= -->

    <footer>

        © 2026 JobMatch | Skill-Based Job Recommendation System

    </footer>


</body>

</html>

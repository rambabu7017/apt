<!DOCTYPE html>
<html>
<head>

    <title>Login - JobMatch</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #F5F9FC;
            color: #0A2A43;
        }

        /* NAVBAR */

        .navbar {
            height: 72px;
            background: #061A2F;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 7%;
        }

        .logo {
            color: white;
            font-size: 23px;
            font-weight: 700;
        }

        .logo span {
            color: #63B8EA;
        }

        .nav-link {
            color: #DFF4FF;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .nav-link:hover {
            color: white;
        }


        /* MAIN */

        .main-container {
            min-height: calc(100vh - 72px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 45px 20px;
        }

        .login-layout {
            width: 100%;
            max-width: 950px;

            display: grid;
            grid-template-columns: 1fr 1fr;

            background: white;
            border-radius: 22px;
            overflow: hidden;

            box-shadow:
                0 18px 50px rgba(6, 26, 47, 0.10);

            border: 1px solid #E1EDF5;
        }


        /* IMAGE SIDE */

        .image-panel {
            position: relative;
            min-height: 560px;
            overflow: hidden;
            background: #061A2F;
        }

        .image-panel img {
            width: 100%;
            height: 100%;
            min-height: 560px;

            object-fit: cover;
            display: block;
        }

        .image-overlay {
            position: absolute;
            inset: 0;

            padding: 50px 42px;

            display: flex;
            flex-direction: column;
            justify-content: flex-end;

            background:
                linear-gradient(
                    to top,
                    rgba(6, 26, 47, 0.96) 0%,
                    rgba(6, 26, 47, 0.65) 45%,
                    rgba(6, 26, 47, 0.08) 100%
                );

            color: white;
        }

        .image-badge {
            width: fit-content;

            padding: 7px 13px;
            margin-bottom: 18px;

            border-radius: 20px;

            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.25);

            color: #DFF4FF;

            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .image-overlay h1 {
            margin: 0 0 14px;

            font-size: 31px;
            line-height: 1.18;

            letter-spacing: -0.7px;
        }

        .image-overlay h1 span {
            color: #63B8EA;
        }

        .image-overlay p {
            max-width: 330px;

            margin: 0;

            color: #D1E0EA;

            font-size: 13px;
            line-height: 1.6;
        }


        /* FORM SIDE */

        .form-panel {
            padding: 55px 60px;

            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-heading {
            margin-bottom: 32px;
        }

        .form-heading h2 {
            margin: 0 0 8px;

            color: #061A2F;

            font-size: 28px;
        }

        .form-heading p {
            margin: 0;

            color: #71818D;

            font-size: 14px;
        }


        /* FORM */

        .form-group {
            margin-bottom: 21px;
        }

        .form-group label {
            display: block;

            margin-bottom: 8px;

            color: #0A2A43;

            font-size: 13px;
            font-weight: 700;
        }

        .form-group input {
            width: 100%;
            height: 48px;

            padding: 0 14px;

            border: 1px solid #D3E1EA;
            border-radius: 9px;

            background: #FBFDFF;

            color: #0A2A43;

            font-size: 14px;

            outline: none;

            transition: 0.2s;
        }

        .form-group input:focus {
            border-color: #1677B8;

            box-shadow:
                0 0 0 3px rgba(22,119,184,0.08);

            background: white;
        }


        /* LOGIN BUTTON */

        .login-btn {
            width: 100%;
            height: 49px;

            margin-top: 8px;

            background: #061A2F;

            color: white;

            border: 2px solid #061A2F;
            border-radius: 9px;

            font-size: 14px;
            font-weight: 700;

            cursor: pointer;

            transition: 0.2s;
        }

        .login-btn:hover {
            background: #0A2A43;

            transform: translateY(-1px);
        }


        /* REGISTER LINK */

        .register-link {
            text-align: center;

            margin-top: 24px;
            padding-top: 20px;

            border-top: 1px solid #E7EEF3;

            font-size: 13px;
        }

        .register-link span {
            color: #71818D;
        }

        .register-link a {
            color: #1677B8;

            text-decoration: none;

            font-weight: 700;

            margin-left: 5px;
        }

        .register-link a:hover {
            text-decoration: underline;
        }


        /* RESPONSIVE */

        @media (max-width: 750px) {

            .login-layout {
                grid-template-columns: 1fr;

                max-width: 520px;
            }

            .image-panel {
                min-height: 350px;
            }

            .image-panel img {
                min-height: 350px;
            }

            .image-overlay {
                padding: 35px 30px;
            }

            .image-overlay h1 {
                font-size: 27px;
            }

            .form-panel {
                padding: 38px 30px;
            }

        }


        @media (max-width: 450px) {

            .navbar {
                padding: 0 5%;
            }

            .nav-link {
                font-size: 12px;
            }

            .main-container {
                padding: 25px 12px;
            }

            .image-panel {
                min-height: 310px;
            }

            .image-panel img {
                min-height: 310px;
            }

            .form-panel {
                padding: 30px 22px;
            }

        }

    </style>

</head>

<body>


<!-- NAVBAR -->

<nav class="navbar">

    <div class="logo">
        Job<span>Match</span>
    </div>

    <a href="register.php" class="nav-link">
        Create Account
    </a>

</nav>


<!-- MAIN -->

<div class="main-container">

    <div class="login-layout">


        <!-- LEFT IMAGE -->

        <div class="image-panel">

            <img
                src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&fm=jpg&q=85&w=1000"
                alt="Professionals collaborating in a modern office"
            >

            <div class="image-overlay">

                <div class="image-badge">
                    JOBMATCH
                </div>

                <h1>
                    Find Opportunities<br>
                    That Match Your <span>Skills.</span>
                </h1>

                <p>
                    Sign in and discover job opportunities
                    based on your technical skills.
                </p>

            </div>

        </div>


        <!-- RIGHT LOGIN FORM -->

        <div class="form-panel">

            <div class="form-heading">

                <h2>Sign In</h2>

                <p>
                    Enter your account details to continue.
                </p>

            </div>


            <form action="login_process.php" method="POST">


                <div class="form-group">

                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        placeholder="Enter your email address"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Password</label>

                    <input
                        type="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="login-btn"
                >
                    Login to JobMatch
                </button>


            </form>


            <div class="register-link">

                <span>Don't have an account?</span>

                <a href="register.php">
                    Create a new account
                </a>

            </div>

        </div>

    </div>

</div>


</body>
</html>

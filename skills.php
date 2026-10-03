<?php

session_start();

include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT skill_id FROM user_skills WHERE user_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

$user_skills = [];

while ($row = $result->fetch_assoc()) {
    $user_skills[] = $row['skill_id'];
}

$stmt->close();

$sql = "SELECT id, skill_name FROM skills ORDER BY skill_name";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Edit Skills | JobMatch</title>

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


/* ================= NAVBAR ================= */

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

.my-skills-btn {

    background: #E8F6FE;

    color: #0B4162;
}

.logout-btn {

    color: #D9E6EF;

    border: 1px solid #385267;
}

.logout-btn:hover {

    background: #102D45;

    color: white;
}


/* ================= PAGE ================= */

.page {

    max-width: 1050px;

    margin: auto;

    padding: 40px 24px 70px;
}


/* ================= HEADER ================= */

.header {

    background:
        linear-gradient(
            120deg,
            #071C2F,
            #0B3552
        );

    border-radius: 22px;

    padding: 35px 40px;

    margin-bottom: 24px;

    box-shadow:
        0 16px 40px
        rgba(7,28,47,0.13);
}

.header-label {

    color: #74D2FF;

    font-size: 10px;

    font-weight: 800;

    letter-spacing: 1.5px;

    margin-bottom: 10px;
}

.header h1 {

    color: white;

    font-size: 32px;

    margin-bottom: 9px;
}

.header h1 span {

    color: #62C8FF;
}

.header p {

    color: #B9CFDE;

    font-size: 12px;

    line-height: 1.7;

    max-width: 650px;
}


/* ================= SKILLS CARD ================= */

.skills-card {

    background: white;

    border: 1px solid #DCE8F0;

    border-radius: 19px;

    padding: 28px;

    box-shadow:
        0 8px 28px
        rgba(7,28,47,0.055);
}


/* ================= SECTION HEADING ================= */

.section-heading {

    display: flex;

    align-items: flex-end;

    justify-content: space-between;

    margin-bottom: 22px;
}

.section-heading h2 {

    font-size: 21px;

    color: #102A43;
}

.section-heading p {

    color: #718797;

    font-size: 11px;

    margin-top: 5px;
}

.selected-info {

    background: #EEF8FD;

    color: #1679AD;

    padding: 8px 12px;

    border-radius: 8px;

    font-size: 10px;

    font-weight: 700;
}


/* ================= SKILL GRID ================= */

.skills-grid {

    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 13px;
}


/* ================= SKILL OPTION ================= */

.skill-option {

    position: relative;
}


/* Hide normal checkbox */

.skill-option input {

    position: absolute;

    opacity: 0;

    pointer-events: none;
}


/* ================= SKILL LABEL ================= */

.skill-label {

    min-height: 58px;

    padding: 11px 14px;

    border:
        1px solid
        #D8E7EF;

    border-radius: 12px;

    background:
        linear-gradient(
            135deg,
            #FFFFFF,
            #F7FBFD
        );

    display: flex;

    align-items: center;

    gap: 11px;

    cursor: pointer;

    color: #29495B;

    font-size: 11px;

    font-weight: 700;

    transition: all 0.2s ease;

    user-select: none;
}


/* Hover */

.skill-label:hover {

    border-color: #91CDE4;

    background: #F4FBFE;

    transform:
        translateY(-2px);

    box-shadow:
        0 7px 18px
        rgba(7,28,47,0.07);
}


/* ================= CHECKBOX ================= */

.checkbox-box {

    width: 21px;

    height: 21px;

    border:
        1.5px solid
        #B8CBD7;

    border-radius: 6px;

    background: white;

    display: flex;

    align-items: center;

    justify-content: center;

    color: transparent;

    font-size: 12px;

    font-weight: 800;

    flex-shrink: 0;

    transition:
        all 0.2s ease;
}


/* ================= SELECTED CARD ================= */

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
        0 5px 15px
        rgba(42,151,207,0.10);
}


/* ================= SELECTED CHECKBOX ================= */

.skill-option input:checked
+ .skill-label
.checkbox-box {

    background: #1679AD;

    border-color: #1679AD;

    color: white;
}


/* ================= BUTTON ================= */

.action-area {

    margin-top: 27px;

    padding-top: 20px;

    border-top:
        1px solid
        #E5EEF3;

    display: flex;

    justify-content: flex-end;
}

.update-btn {

    border: none;

    background:
        linear-gradient(
            135deg,
            #0B3552,
            #1679AD
        );

    color: white;

    padding: 12px 24px;

    border-radius: 9px;

    font-size: 12px;

    font-weight: 800;

    cursor: pointer;

    box-shadow:
        0 7px 18px
        rgba(22,121,173,0.20);

    transition: all 0.2s ease;
}

.update-btn:hover {

    transform:
        translateY(-2px);

    box-shadow:
        0 10px 22px
        rgba(22,121,173,0.25);
}


/* ================= MOBILE ================= */

@media (max-width: 800px) {

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

        padding:
            28px 24px;

        border-radius: 18px;
    }

    .header h1 {

        font-size: 27px;
    }

    .skills-card {

        padding: 20px;
    }

    .section-heading {

        align-items: flex-start;

        gap: 12px;
    }

    .skills-grid {

        grid-template-columns: 1fr;
    }

}

</style>

</head>


<body>


<!-- ================= NAVBAR ================= -->

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
            href="my_skills.php"
            class="nav-btn my-skills-btn">

            My Skills

        </a>


        <a
            href="logout.php"
            class="nav-btn logout-btn">

            Logout

        </a>


    </div>


</nav>



<!-- ================= MAIN ================= -->

<main class="page">


    <!-- HEADER -->

    <section class="header">

        <div class="header-label">

            PROFILE CUSTOMIZATION

        </div>


        <h1>

            Edit Your <span>Skills</span>

        </h1>


        <p>

            Select the technical skills you currently
            have. Your selected skills will be used
            to find relevant job opportunities.

        </p>

    </section>



    <!-- SKILLS -->

    <section class="skills-card">


        <div class="section-heading">


            <div>

                <h2>

                    Choose Your Skills

                </h2>


                <p>

                    Select all skills that match your experience

                </p>

            </div>


            <div class="selected-info">

                <?php

                echo count($user_skills);

                ?>

                Selected

            </div>


        </div>



        <!-- FORM -->

        <form
            action="update_skills.php"
            method="POST">


            <div class="skills-grid">


                <?php

                if ($result->num_rows > 0) {

                    while ($skill = $result->fetch_assoc()) {

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

                            <?php

                            if (
                                in_array(
                                    $skill['id'],
                                    $user_skills
                                )
                            ) {

                                echo "checked";

                            }

                            ?>
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



            <!-- UPDATE -->

            <div class="action-area">


                <button
                    type="submit"
                    class="update-btn">

                    Update My Skills

                </button>


            </div>


        </form>


    </section>


</main>


</body>

</html>

<?php

$conn->close();

?>

<?php

session_start();

include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$search = $_GET['search'] ?? "";


/* ================= JOB RECOMMENDATION QUERY ================= */

$sql = "
SELECT
    j.id,
    j.job_title,
    j.company,
    j.description,

    COUNT(DISTINCT jrs.skill_id) AS total_required_skills,

    COUNT(
        DISTINCT CASE
            WHEN us.skill_id IS NOT NULL
            THEN jrs.skill_id
        END
    ) AS matching_skills,

    ROUND(
        (
            COUNT(
                DISTINCT CASE
                    WHEN us.skill_id IS NOT NULL
                    THEN jrs.skill_id
                END
            ) * 100.0
        )
        /
        COUNT(DISTINCT jrs.skill_id),
        2
    ) AS match_percentage

FROM jobs j

JOIN job_required_skills jrs
    ON j.id = jrs.job_id

LEFT JOIN user_sk

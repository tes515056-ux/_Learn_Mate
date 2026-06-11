<?php
session_start();
include '../../dblink.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$lesson_id = (int)($_GET['id'] ?? 0);
$course_id = (int)($_GET['course_id'] ?? 0);

if ($lesson_id == 0) {
    die("Invalid lesson");
}

/*
|----------------------------
| Insert Save (avoid duplicate)
|----------------------------
*/

mysqli_query(
    $conn,
    "INSERT IGNORE INTO saved_lessons (user_id, lesson_id)
     VALUES ($user_id, $lesson_id)"
);

header("Location: course_lessons.php?course_id=$course_id");
exit;
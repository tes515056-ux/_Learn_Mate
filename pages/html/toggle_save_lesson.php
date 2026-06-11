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
|--------------------------------------------------------------------------
| Check if already saved
|--------------------------------------------------------------------------
*/

$check = mysqli_query(
    $conn,
    "SELECT id 
     FROM saved_lessons
     WHERE user_id = $user_id
     AND lesson_id = $lesson_id
     LIMIT 1"
);

if (mysqli_num_rows($check) > 0) {

    /*
    |--------------------------
    | UNSAVE
    |--------------------------
    */

    mysqli_query(
        $conn,
        "DELETE FROM saved_lessons
         WHERE user_id = $user_id
         AND lesson_id = $lesson_id"
    );

} else {

    mysqli_query(
        $conn,
        "INSERT INTO saved_lessons (user_id, lesson_id)
         VALUES ($user_id, $lesson_id)"
    );
}

/*
|--------------------------------------------------------------------------
| Redirect back
|--------------------------------------------------------------------------
*/

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
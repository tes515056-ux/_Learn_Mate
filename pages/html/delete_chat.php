<?php

session_start();
include '../../dblink.php';

$user_id = $_SESSION['user_id'];

$id = (int)$_GET['id'];

mysqli_query(
    $conn,
    "DELETE FROM ai_chat_history
     WHERE id = $id
     AND user_id = $user_id"
);

header("Location: chat_history.php");
exit;
<?php
session_start();

require_once "config.php";

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    exit("User is not logged in.");
}

if (!isset($_SESSION["users_id"]) || !is_int($_SESSION["users_id"])) {
    exit("Invalid or missing user ID.");
}

$user_id = $_SESSION["users_id"];

if (isset($_POST["roomName"])) {
    $roomName = $_POST["roomName"];
    
    $sql = "INSERT INTO rooms (user_id, room_name) VALUES (?, ?)";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "is", $user_id, $roomName);
        if (mysqli_stmt_execute($stmt)) {
            echo "success";
        } else {
            echo "error";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "error";
    }
} else {
    echo "error";
}

mysqli_close($link);

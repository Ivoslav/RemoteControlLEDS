<?php
session_start();

require_once "config.php";

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    exit("Unauthorized access");
}

if (!isset($_POST["room"])) {
    exit("Room name not provided");
}

$roomName = $_POST["room"];

$user_id = $_SESSION["users_id"];

$sql = "DELETE FROM rooms WHERE user_id = ? AND room_name = ?";

if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "is", $param_user_id, $param_room_name);

    $param_user_id = $user_id;
    $param_room_name = $roomName;

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "error";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($link);

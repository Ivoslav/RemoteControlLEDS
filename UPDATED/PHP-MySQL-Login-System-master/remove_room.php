<?php
session_start();

// Include config file
require_once "config.php";

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    exit("Unauthorized access");
}

// Check if room name is provided
if (!isset($_POST["room"])) {
    exit("Room name not provided");
}

// Get room name from POST data
$roomName = $_POST["room"];

// Get user ID from session
$user_id = $_SESSION["users_id"];

// Prepare a delete statement
$sql = "DELETE FROM rooms WHERE user_id = ? AND room_name = ?";

if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "is", $param_user_id, $param_room_name);

    // Set parameters
    $param_user_id = $user_id;
    $param_room_name = $roomName;

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        echo "success"; // Room successfully removed
    } else {
        echo "error"; // Failed to remove room
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

// Close connection
mysqli_close($link);
?>

<?php
session_start();

require_once "config.php";

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    // Handle unauthenticated access
    exit("User is not logged in.");
}

// Check if the user ID session variable is set and is an integer
if (!isset($_SESSION["users_id"]) || !is_int($_SESSION["users_id"])) {
    // Handle invalid or missing user ID
    exit("Invalid or missing user ID.");
}

$user_id = $_SESSION["users_id"];

// Check if roomName is provided in the POST data
if (isset($_POST["roomName"])) {
    $roomName = $_POST["roomName"];
    
    // Insert the room into the database
    $sql = "INSERT INTO rooms (user_id, room_name) VALUES (?, ?)";
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind parameters and execute the statement
        mysqli_stmt_bind_param($stmt, "is", $user_id, $roomName);
        if (mysqli_stmt_execute($stmt)) {
            echo "success"; // Return success message to JavaScript
        } else {
            echo "error"; // Return error message to JavaScript
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "error"; // Return error message if prepare fails
    }
} else {
    echo "error"; // Return error message if roomName is not provided
}

mysqli_close($link);

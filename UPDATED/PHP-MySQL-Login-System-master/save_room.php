<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include 'config.php';  // Include your config file

$roomName = $_POST['roomName'];
$userId = $_POST['userId'];

// Prepare an SQL statement
$stmt = $link->prepare("INSERT INTO rooms (user_id, room_name, is_on, created_at) VALUES (?, ?, ?, NOW())");

// Bind parameters and execute the statement
$stmt->execute([$userId, $roomName, 1]);

echo "Room '" . $roomName . "' uploaded successfully.";
?>

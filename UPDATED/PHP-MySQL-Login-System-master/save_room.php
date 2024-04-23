<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include 'config.php';

$roomName = $_POST['roomName'];
$userId = $_POST['userId'];

$stmt = $link->prepare("INSERT INTO rooms (user_id, room_name, is_on, created_at) VALUES (?, ?, ?, NOW())");

$stmt->execute([$userId, $roomName, 1]);

echo "Room '" . $roomName . "' uploaded successfully.";
?>

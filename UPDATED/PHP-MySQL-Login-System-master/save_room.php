<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["userId"]) && isset($_POST["roomName"])) {
    $userId = $_POST["userId"];
    $roomName = $_POST["roomName"];

    // Prepare and execute SQL statement to insert room data
    $stmt = $pdo->prepare("INSERT INTO rooms (user_id, room_name, is_on, created_at) VALUES (?, ?, 1, NOW())");
    $stmt->execute([$userId, $roomName]);
}
?>

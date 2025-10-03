<?php
session_start();
include_once("db.php");

if (!isset($_SESSION['user_id'])) exit;

$current_user_id = (int)$_SESSION['user_id'];
$receiver_id = isset($_POST['receiver_id']) ? (int)$_POST['receiver_id'] : 0;
$message = trim($_POST['message'] ?? '');

if ($message !== "") {
    $sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $current_user_id, $receiver_id, $message);
    $stmt->execute();
}
?>
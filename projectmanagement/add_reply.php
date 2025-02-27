<?php
// add_reply.php
require_once 'db_connection.php';

$taskId = $_POST['taskId'];
$parentId = $_POST['parentId'];
$message = $_POST['message'];
// Remove newlines from message
$message = str_replace(["\r\n", "\n", "\r"], ' ', $message);
$userId = $_SESSION['user_id']; // Assuming you have user session

$sql = "INSERT INTO comments (task_id, parent_id, user_id, message) VALUES (?, ?, ?, ?)";
$stmt = $mysqlconn->prepare($sql);
$stmt->bind_param("iiis", $taskId, $parentId, $userId, $message);
$stmt->execute();
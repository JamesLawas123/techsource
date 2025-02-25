<?php
include('../conn/db.php');
session_start();

function addComment($mysqlconn, $taskId, $userId, $message, $subject = '', $parentId = null) {
    // Remove the empty message validation
    if (empty($message) && empty($_FILES['attachment']['name'])) {
        return ['status' => 'error', 'message' => 'Either message or attachment is required'];
    }

    $filePath = '';
    // Handle file upload
    if (!empty($_FILES['attachment']['name'])) {
        $uploadDir = '../uploaded_attachments/files/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Remove the timestamp prefix
        $fileName = basename($_FILES['attachment']['name']);
        $filePath = $uploadDir . $fileName;
        
        if (!move_uploaded_file($_FILES['attachment']['tmp_name'], $filePath)) {
            return ['status' => 'error', 'message' => 'File upload failed'];
        }
    }

    $subject = mysqli_real_escape_string($mysqlconn, $subject);
    $message = mysqli_real_escape_string($mysqlconn, $message);
    // Remove newlines from the message
    $message = str_replace(["\r\n", "\n", "\r"], ' ', $message);
    $datetimecreated = date('Y-m-d H:i:s');
    $type = $parentId ? 'reply' : 'comment';

    // Modify SQL to include file_data
    $sql = "INSERT INTO pm_threadtb 
            (taskid, createdbyid, datetimecreated, subject, message, type, parent_id, file_data) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $mysqlconn->prepare($sql);
    if (!$stmt) {
        return ['status' => 'error', 'message' => 'Database error'];
    }

    $stmt->bind_param("iissssis", $taskId, $userId, $datetimecreated, $subject, $message, $type, $parentId, $filePath);
    
    if ($stmt->execute()) {
        return ['status' => 'success', 'message' => 'Comment added successfully'];
    } else {
        return ['status' => 'error', 'message' => 'Failed to add comment'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = isset($_POST['taskId']) ? intval($_POST['taskId']) : 0;
    $userId = $_SESSION['userid'] ?? 0;
    $message = $_POST['message'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $parentId = isset($_POST['parent_comment_id']) ? intval($_POST['parent_comment_id']) : null;

    if ($userId === 0) {
        die('User not logged in');
    }

    $result = addComment($mysqlconn, $taskId, $userId, $message, $subject, $parentId);
    
    if ($result['status'] === 'success') {
        header('Location: test.php?taskId=' . $taskId);
        exit();
    } else {
        echo $result['message'];
    }
}
?>
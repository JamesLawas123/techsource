<?php
include('../conn/db.php');
session_start();

function addComment($mysqlconn, $taskId, $userId, $message, $subject = '', $parentId = null) {
    if (empty($message)) {
        return ['status' => 'error', 'message' => 'Message cannot be empty'];
    }

    $subject = mysqli_real_escape_string($mysqlconn, $subject);
    $message = mysqli_real_escape_string($mysqlconn, $message);
    $datetimecreated = date('Y-m-d H:i:s');
    $type = $parentId ? 'reply' : 'comment'; // Set type to "reply" if parentId exists, else "comment"

    // Modify SQL to include parent_id and type
    $sql = "INSERT INTO pm_threadtb 
            (taskid, createdbyid, datetimecreated, subject, message, type, parent_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $mysqlconn->prepare($sql);
    if (!$stmt) {
        return ['status' => 'error', 'message' => 'Database error'];
    }

    $stmt->bind_param("iissssi", $taskId, $userId, $datetimecreated, $subject, $message, $type, $parentId);
    
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
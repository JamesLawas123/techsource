<?php
include('../conn/db.php');

function addComment($mysqlconn, $taskId, $userId, $message, $subject = '') {
    // Validate inputs
    if (empty($message)) {
        return ['status' => 'error', 'message' => 'Message cannot be empty'];
    }

    // Prepare data
    $subject = mysqli_real_escape_string($mysqlconn, $subject);
    $message = mysqli_real_escape_string($mysqlconn, $message);
    $datetimecreated = date('Y-m-d H:i:s');

    // Prepare SQL statement
    $sql = "INSERT INTO pm_threadtb 
            (taskid, createdbyid, datetimecreated, subject, message) 
            VALUES (?, ?, ?, ?, ?)";
    
    // Use prepared statement
    $stmt = $mysqlconn->prepare($sql);
    if (!$stmt) {
        return ['status' => 'error', 'message' => 'Database error'];
    }

    $stmt->bind_param("iisss", $taskId, $userId, $datetimecreated, $subject, $message);
    
    if ($stmt->execute()) {
        return ['status' => 'success', 'message' => 'Comment added successfully'];
    } else {
        return ['status' => 'error', 'message' => 'Failed to add comment'];
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = isset($_POST['taskId']) ? intval($_POST['taskId']) : 0; // Get taskId from POST
    $userId = 1; // Replace with actual user ID from session
    $message = $_POST['message'] ?? '';
    $subject = $_POST['subject'] ?? ''; // Get subject from form

    $result = addComment($mysqlconn, $taskId, $userId, $message, $subject);
    
    if ($result['status'] === 'success') {
        header('Location: test.php?taskId=' . $taskId); // Add taskId to redirect URL
        exit();
    } else {
        // Handle error - you could set a session error message and redirect
        echo $result['message'];
    }
}
?>
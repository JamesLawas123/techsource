<?php
include('../conn/db.php');
session_start();

function addComment($mysqlconn, $taskId, $userId, $message, $subject = '', $parentId = null, $file = null) {
    $filePath = '';
    if ($file && $file['error'] === UPLOAD_ERR_OK) {
        // Use absolute path instead of relative
        $uploadDir = __DIR__ . '/uploadspm/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Determine file type directory
        $fileType = mime_content_type($file['tmp_name']);
        if (strpos($fileType, 'image/') === 0) {
            $typeDir = 'images/';
        } else if (strpos($fileType, 'application/') === 0) {
            $typeDir = 'docfiles/';
        } else {
            $typeDir = '';
        }

        // Create type-specific directory if it doesn't exist
        if ($typeDir && !is_dir($uploadDir . $typeDir)) {
            if (!mkdir($uploadDir . $typeDir, 0755, true)) {
                return ['status' => 'error', 'message' => 'Failed to create type-specific directory'];
            }
        }

        $fileName = basename($file['name']);
        $filePath = $uploadDir . $typeDir . $fileName;
        
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            return ['status' => 'error', 'message' => 'File upload failed'];
        }

        // Store relative path in database
        $filePath = 'uploadspm/' . $typeDir . $fileName;
    }

    // Skip empty messages without files
    if (empty($message) && empty($filePath)) {
        return ['status' => 'skip', 'message' => 'Empty message and no file'];
    }

    $subject = mysqli_real_escape_string($mysqlconn, $subject);
    $message = mysqli_real_escape_string($mysqlconn, $message);
    $datetimecreated = date('Y-m-d H:i:s');
    $type = $parentId ? 'reply' : 'comment';

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

    $success = false;

    // Handle message with or without files
    if (!empty($message) || empty($_FILES['attachment']['name'][0])) {
        $result = addComment($mysqlconn, $taskId, $userId, $message, $subject, $parentId);
        $success = ($result['status'] === 'success');
    }

    // Handle multiple file uploads as separate comments
    if (!empty($_FILES['attachment']['name'][0])) {
        foreach ($_FILES['attachment']['name'] as $key => $value) {
            if ($_FILES['attachment']['error'][$key] === UPLOAD_ERR_OK) {
                $file = [
                    'name' => $_FILES['attachment']['name'][$key],
                    'type' => $_FILES['attachment']['type'][$key],
                    'tmp_name' => $_FILES['attachment']['tmp_name'][$key],
                    'error' => $_FILES['attachment']['error'][$key],
                    'size' => $_FILES['attachment']['size'][$key],
                ];
                
                $result = addComment($mysqlconn, $taskId, $userId, '', $subject, $parentId, $file);
                if ($result['status'] === 'success') {
                    $success = true;
                }
            }
        }
    }

    if ($success) {
        header('Location: test.php?taskId=' . $taskId);
        exit();
    } else {
        echo "Failed to add comment or upload files";
    }
}
?>
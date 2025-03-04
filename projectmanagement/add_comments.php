<?php
include('../conn/db.php');
session_start();

function addComment($mysqlconn, $taskId, $userId, $message, $subject = '', $parentId = null, $files = null) {
    $filePath = ''; // Single string to store all file paths
    
    // Handle multiple files
    if ($files) {
        $uploadDir = __DIR__ . '/uploadspm/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filePaths = []; // Temporary array to collect paths
        foreach ($files['name'] as $key => $fileName) {
            if ($files['error'][$key] === UPLOAD_ERR_OK) {
                // Determine file type directory
                $fileType = mime_content_type($files['tmp_name'][$key]);
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
                        continue; // Skip this file if directory creation fails
                    }
                }

                $safeFileName = basename($fileName);
                $filePath = $uploadDir . $typeDir . $safeFileName;
                
                if (move_uploaded_file($files['tmp_name'][$key], $filePath)) {
                    // Store relative path
                    $filePaths[] = 'uploadspm/' . $typeDir . $safeFileName;
                }
            }
        }
        
        // Join all file paths with a comma separator
        $filePath = !empty($filePaths) ? implode(',', $filePaths) : '';
    }

    // Skip if no message and no files
    if (empty($message) && empty($filePath)) {
        return ['status' => 'skip', 'message' => 'Empty message and no files'];
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

    $result = addComment(
        $mysqlconn, 
        $taskId, 
        $userId, 
        $message, 
        $subject, 
        $parentId, 
        !empty($_FILES['attachment']) ? $_FILES['attachment'] : null
    );

    if ($result['status'] === 'success') {
        header('Location: threadPage.php?taskId=' . $taskId);
        exit();
    } else {
        echo "Failed to add comment or upload files";
    }
}
?>
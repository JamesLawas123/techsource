<?php
include('../conn/db.php');
session_start();

// Set header for JSON response
header('Content-Type: application/json');

function uploadFile($mysqlconn, $taskId, $userId, $file) {
    try {
        // Create uploads directory if it doesn't exist
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                throw new Exception('Failed to create upload directory');
            }
        }

        // Generate unique filename
        $fileName = uniqid() . '_' . basename($file['name']);
        $filePath = $uploadDir . $fileName;

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new Exception('File move operation failed');
        }

        // Insert file record into database with type 'file'
        $sql = "INSERT INTO pm_threadtb 
                (taskid, createdbyid, datetimecreated, type, file_data) 
                VALUES (?, ?, NOW(), 'file', ?)";
        $stmt = $mysqlconn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Database prepare statement failed');
        }

        $stmt->bind_param("iis", $taskId, $userId, $filePath);

        if ($stmt->execute()) {
            return ['status' => 'success', 'filename' => $filePath];
        } else {
            unlink($filePath); // Delete the file if DB insert fails
            throw new Exception('Database execution failed');
        }
    } catch (Exception $e) {
        // Clean up if any error occurs
        if (isset($filePath) && file_exists($filePath)) {
            unlink($filePath);
        }
        throw $e;
    }
}

try {
    // Check request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Validate file upload
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('No file uploaded or file upload error');
    }

    // Validate required parameters
    if (!isset($_POST['taskId']) || !isset($_SESSION['userid'])) {
        throw new Exception('Missing required parameters');
    }

    $taskId = intval($_POST['taskId']);
    $userId = $_SESSION['userid'];

    // Process file upload
    $result = uploadFile($mysqlconn, $taskId, $userId, $_FILES['file']);
    echo json_encode($result);
} catch (Exception $e) {
    // Handle errors
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
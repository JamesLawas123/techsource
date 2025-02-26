<?php
include('../conn/db.php');
session_start();

// Set header for JSON response
header('Content-Type: application/json');

function uploadFile($mysqlconn, $taskId, $userId, $file) {
    try {
        // Use absolute path instead of relative
        $uploadDir = __DIR__ . '/uploadspm/';
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                throw new Exception('Failed to create upload directory');
            }
        }

        // Determine file type directory
        $fileType = mime_content_type($file['tmp_name']);
        if (strpos($fileType, 'image/') === 0) {
            $typeDir = 'images/';
        } else if (strpos($fileType, 'application/') === 0) {
            $typeDir = 'docfiles/';
        } else if ($fileType === 'application/octet-stream' || empty($fileType)) {
            $typeDir = 'others/';
        } else {
            $typeDir = 'others/';
        }

        // Create type-specific directory if it doesn't exist
        if ($typeDir && !is_dir($uploadDir . $typeDir)) {
            if (!mkdir($uploadDir . $typeDir, 0755, true)) {
                throw new Exception('Failed to create type-specific directory');
            }
        }

        // Use original filename without unique prefix
        $fileName = basename($file['name']);
        $filePath = $uploadDir . $typeDir . $fileName;

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new Exception('File move operation failed');
        }

        // Store relative path in database
        $dbFilePath = 'uploadspm/' . $typeDir . $fileName;
        
        // Get task subject from the form
        $taskSubject = $_POST['taskSubjectUp2'];

        // Update SQL to include subject
        $sql = "INSERT INTO pm_threadtb 
                (taskid, createdbyid, datetimecreated, type, file_data, subject) 
                VALUES (?, ?, NOW(), 'file', ?, ?)";
        $stmt = $mysqlconn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Database prepare statement failed');
        }

        $stmt->bind_param("iiss", $taskId, $userId, $dbFilePath, $taskSubject);

        if ($stmt->execute()) {
            return ['status' => 'success', 'filename' => $dbFilePath];
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
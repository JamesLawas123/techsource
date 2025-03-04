<?php
include('../conn/db.php');
session_start();

// Set header for JSON response
header('Content-Type: application/json');

// Enable error reporting but prevent output
error_reporting(E_ALL);
ini_set('display_errors', 0);

function uploadFiles($mysqlconn, $taskId, $userId, $files) {
    try {
        $uploadDir = __DIR__ . '/uploadspm/';
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                throw new Exception('Failed to create upload directory');
            }
        }

        $uploadedFiles = [];
        $filePaths = [];

        // Process each file
        foreach ($files['name'] as $key => $fileName) {
            if ($files['error'][$key] !== UPLOAD_ERR_OK) {
                continue;
            }

            // Determine file type directory
            $fileType = mime_content_type($files['tmp_name'][$key]);
            if (strpos($fileType, 'image/') === 0) {
                $typeDir = 'images/';
            } else if (strpos($fileType, 'application/') === 0) {
                $typeDir = 'docfiles/';
            } else {
                $typeDir = 'others/';
            }

            // Create type-specific directory if it doesn't exist
            if (!is_dir($uploadDir . $typeDir)) {
                if (!mkdir($uploadDir . $typeDir, 0755, true)) {
                    throw new Exception('Failed to create type-specific directory');
                }
            }

            // Use original filename
            $safeFileName = basename($fileName);
            $filePath = $uploadDir . $typeDir . $safeFileName;
            
            // If file exists, append number to filename
            $counter = 1;
            $fileInfo = pathinfo($safeFileName);
            $originalName = $fileInfo['filename'];
            $extension = isset($fileInfo['extension']) ? '.' . $fileInfo['extension'] : '';
            
            while (file_exists($filePath)) {
                $safeFileName = $originalName . '_' . $counter . $extension;
                $filePath = $uploadDir . $typeDir . $safeFileName;
                $counter++;
            }

            // Move uploaded file
            if (!move_uploaded_file($files['tmp_name'][$key], $filePath)) {
                throw new Exception('File move operation failed for ' . $fileName);
            }

            // Store relative path
            $dbFilePath = 'uploadspm/' . $typeDir . $safeFileName;
            $filePaths[] = $dbFilePath;
            $uploadedFiles[] = [
                'name' => $safeFileName,
                'path' => $dbFilePath
            ];
        }

        if (empty($filePaths)) {
            throw new Exception('No files were successfully uploaded');
        }

        // Store all file paths as a comma-separated string
        $filePathString = implode(',', $filePaths);

        // Insert single record with all file paths
        $sql = "INSERT INTO pm_threadtb 
                (taskid, createdbyid, datetimecreated, type, file_data, subject) 
                VALUES (?, ?, NOW(), 'file', ?, ?)";
        
        $stmt = $mysqlconn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Database prepare statement failed: ' . mysqli_error($mysqlconn));
        }

        $subject = isset($_POST['taskSubjectUp2']) ? $_POST['taskSubjectUp2'] : '';
        $stmt->bind_param("iiss", $taskId, $userId, $filePathString, $subject);

        if (!$stmt->execute()) {
            throw new Exception('Database execution failed: ' . $stmt->error);
        }

        return [
            'status' => 'success',
            'message' => 'Files uploaded successfully',
            'files' => $uploadedFiles
        ];

    } catch (Exception $e) {
        // Clean up any uploaded files if an error occurs
        if (!empty($uploadedFiles)) {
            foreach ($uploadedFiles as $file) {
                $fullPath = __DIR__ . '/' . $file['path'];
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }
        }
        throw $e;
    }
}

try {
    // Validate request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Validate file upload
    if (!isset($_FILES['attachFile']) || empty($_FILES['attachFile']['name'][0])) {
        throw new Exception('No files uploaded');
    }

    // Validate file count
    if (count($_FILES['attachFile']['name']) > 10) {
        throw new Exception('Maximum 10 files can be uploaded at once');
    }

    // Validate required parameters
    $taskId = isset($_POST['taskId']) ? intval($_POST['taskId']) : 0;
    $userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;

    if (!$taskId || !$userId) {
        throw new Exception('Missing required parameters');
    }

    // Process file uploads
    $result = uploadFiles($mysqlconn, $taskId, $userId, $_FILES['attachFile']);
    echo json_encode($result);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
<?php
require_once('../conn/db.php');
session_start();

// Set header for JSON response
header('Content-Type: application/json');

// Enable error reporting but prevent output
error_reporting(E_ALL);
ini_set('display_errors', 0);

function uploadFiles($mysqlconn, $taskId, $userId, $files) {
    // Skip validation for included usage
    if (!isset($files['name']) || empty($files['name'][0])) {
        return [
            'status' => 'success',
            'message' => 'No files to upload',
            'files' => []
        ];
    }

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

        if (!empty($filePaths)) {
            $filePathString = implode(',', $filePaths);
            
            $sql = "INSERT INTO pm_threadtb 
                    (taskid, createdbyid, datetimecreated, type, file_data) 
                    VALUES (?, ?, NOW(), 'file', ?)";
            
            $stmt = $mysqlconn->prepare($sql);
            $stmt->bind_param("iis", $taskId, $userId, $filePathString);
            $stmt->execute();
        }

        return [
            'status' => 'success',
            'message' => 'Files uploaded successfully',
            'files' => $uploadedFiles
        ];

    } catch (Exception $e) {
        error_log("File upload error: " . $e->getMessage());
        return [
            'status' => 'error',
            'message' => $e->getMessage(),
            'files' => []
        ];
    }
}

// Only process direct API calls
if ($_SERVER['SCRIPT_NAME'] === __FILE__) {
    header('Content-Type: application/json');
    try {
        if (!isset($_POST['taskId']) || !isset($_POST['userId'])) {
            throw new Exception('Missing required parameters');
        }

        $result = uploadFiles($conn, $_POST['taskId'], $_POST['userId'], $_FILES['attachFile']);
        echo json_encode($result);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
}
?>
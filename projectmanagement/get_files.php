<?php
// Include database connection
require_once('../includes/config.php');

// Check if taskId is provided
if (!isset($_GET['taskId'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Task ID is required'
    ]);
    exit;
}

$taskId = intval($_GET['taskId']);

try {
    // Verify database connection
    if (!$mysqlconn) {
        throw new Exception("Database connection failed");
    }

    // Query to get files associated with the task
    $query = "SELECT t.id, t.file_data, t.datetimecreated, t.userid, 
              CONCAT(u.user_firstname, ' ', u.user_lastname) as username
              FROM pm_threadtb t
              LEFT JOIN sys_usertb u ON t.userid = u.id
              WHERE t.taskid = ? AND t.file_data IS NOT NULL AND t.file_data != ''
              ORDER BY t.datetimecreated DESC";
    
    $stmt = mysqli_prepare($mysqlconn, $query);
    if (!$stmt) {
        throw new Exception("Query preparation failed: " . mysqli_error($mysqlconn));
    }

    mysqli_stmt_bind_param($stmt, 'i', $taskId);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Query execution failed: " . mysqli_stmt_error($stmt));
    }

    $result = mysqli_stmt_get_result($stmt);
    if ($result === false) {
        throw new Exception("Failed to get result set: " . mysqli_error($mysqlconn));
    }
    
    $files = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Get file size if file exists
        $filePath = $row['file_data'];
        $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
        
        $files[] = [
            'id' => $row['id'],
            'file_data' => $row['file_data'],
            'file_size' => $fileSize,
            'datetimecreated' => $row['datetimecreated'],
            'username' => $row['username'],
            // Allow deletion only for file owner or admin
            'can_delete' => isset($_SESSION['user_id']) && 
                          ($_SESSION['user_id'] == $row['userid'] || $_SESSION['user_role'] == 'admin')
        ];
    }
    
    mysqli_stmt_close($stmt);
    
    echo json_encode([
        'success' => true,
        'files' => $files
    ]);

} catch (Exception $e) {
    error_log("Error in get_files.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error retrieving files: ' . $e->getMessage()
    ]);
}
?>
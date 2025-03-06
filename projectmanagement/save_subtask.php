<?php
include('../check_session.php');
$conn = connectionDB();

// Ensure clean output buffer at start
if (ob_get_length()) ob_clean();

// Initialize response array
$response = array(
    'success' => false,
    'message' => '',
    'debug' => '' // For development purposes
);

try {
    // Validate required fields
    $required_fields = array(
        'subtaskSubject' => 'Subject',
        'subtaskPriority' => 'Priority Level',
        'subtaskAssignee' => 'Assignee',
        'subtaskTargetDate' => 'Target Date',
        'subtaskStartDate' => 'Start Date'
    );

    foreach ($required_fields as $field => $label) {
        if (empty($_POST[$field])) {
            throw new Exception($label . " is required");
        }
    }

    // Sanitize inputs
    $subject = mysqli_real_escape_string($conn, $_POST['subtaskSubject']);
    $priority = mysqli_real_escape_string($conn, $_POST['subtaskPriority']);
    $assignee = mysqli_real_escape_string($conn, $_POST['subtaskAssignee']);
    $targetDate = mysqli_real_escape_string($conn, $_POST['subtaskTargetDate']);
    $startDate = mysqli_real_escape_string($conn, $_POST['subtaskStartDate']);
    $endDate = mysqli_real_escape_string($conn, $_POST['subtaskEndDate']);
    $description = mysqli_real_escape_string($conn, $_POST['subtaskDescription']);
    $parentTaskId = mysqli_real_escape_string($conn, $_POST['parentTaskId']);
    $userId = $_SESSION['userid']; // Get current logged in user's ID
    error_log("Current User ID: " . $userId); // Add this line to log the user ID
    $response['debug_userid'] = $userId; // Add this line to include userId in response

    // Get current timestamp
    $currentDateTime = date('Y-m-d H:i:s');

    // Insert query
    $query = "INSERT INTO pm_projecttasktb (
        subject,
        description,
        deadline,
        startdate,
        enddate,
        priorityid,
        statusid,
        createdbyid,
        datetimecreated,
        assignee,
        parent_id,
        istask,
        type
    ) VALUES (
        '$subject',
        '$description',
        '$targetDate',
        '$startDate',
        '$endDate',
        '$priority',
        '$userId',
        '$userId',
        '$currentDateTime',
        '$assignee',
        '$parentTaskId',
        '0',
        'subtask'
    )";

    if (mysqli_query($conn, $query)) {
        $response['success'] = true;
        $response['message'] = "Subtask created successfully";
        $response['last_id'] = mysqli_insert_id($conn);
    } else {
        throw new Exception("Error creating subtask: " . mysqli_error($conn));
    }

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    $response['debug'] = error_get_last(); // For development purposes
}

// Close database connection
mysqli_close($conn);

// Set headers
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// Ensure there's no whitespace or other output before the JSON
echo json_encode($response);
exit;
?>
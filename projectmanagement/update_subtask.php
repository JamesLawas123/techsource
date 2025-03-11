<?php
include('../check_session.php');
$moduleid = 4;
include('proxy.php');
$conn = connectionDB();

// Ensure clean output buffer
if (ob_get_length()) ob_clean();

// Initialize response array
$response = array(
    'success' => false,
    'message' => ''
);

try {
    // Get task ID
    $taskId = isset($_POST['id']) ? mysqli_real_escape_string($conn, $_POST['id']) : null;
    if (!$taskId) {
        throw new Exception("Task ID is required");
    }

    // Get and process assignees
    $assignees = isset($_POST['taskAssigneeUp2']) ? $_POST['taskAssigneeUp2'] : array();
    $assigneeString = !empty($assignees) ? implode(',', array_map('intval', $assignees)) : '';

    // Prepare other fields for update
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $deadline = mysqli_real_escape_string($conn, $_POST['deadline']);
    $startdate = mysqli_real_escape_string($conn, $_POST['startdate']);
    $enddate = mysqli_real_escape_string($conn, $_POST['enddate']);
    $projectid = mysqli_real_escape_string($conn, $_POST['projectid']);
    $classificationid = mysqli_real_escape_string($conn, $_POST['classificationid']);
    $priorityid = mysqli_real_escape_string($conn, $_POST['priorityid']);
    $statusid = mysqli_real_escape_string($conn, $_POST['statusid']);

    // Update query
    $update_query = "UPDATE pm_projecttasktb SET 
        subject = '$subject',
        description = '$description',
        deadline = " . ($deadline ? "'$deadline'" : "NULL") . ",
        startdate = " . ($startdate ? "'$startdate'" : "NULL") . ",
        enddate = " . ($enddate ? "'$enddate'" : "NULL") . ",
        projectid = '$projectid',
        classificationid = '$classificationid',
        priorityid = '$priorityid',
        statusid = '$statusid',
        assignee = " . ($assigneeString ? "'$assigneeString'" : "NULL") . "
        WHERE id = '$taskId'";

    if (mysqli_query($conn, $update_query)) {
        $response['success'] = true;
        $response['message'] = "Task updated successfully";
    } else {
        throw new Exception("Error updating task: " . mysqli_error($conn));
    }

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

// Close database connection
mysqli_close($conn);

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>
<?php
include('../check_session.php');
$moduleid = 4;
include('proxy.php');
$conn = connectionDB();

// Initialize response array
$response = array(
    'success' => false,
    'message' => ''
);

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

// Debug the raw POST data
error_log("Raw POST data: " . print_r($_POST, true));

// Get form data
$id = isset($_POST['id']) ? mysqli_real_escape_string($conn, $_POST['id']) : '';
$subtaskUserid = isset($_POST['subtaskUserid']) ? mysqli_real_escape_string($conn, $_POST['subtaskUserid']) : '';
$parent_id = isset($_POST['parent_id']) ? mysqli_real_escape_string($conn, $_POST['parent_id']) : '';
$projectid = isset($_POST['projectid']) ? mysqli_real_escape_string($conn, $_POST['projectid']) : '';
$classificationid = isset($_POST['classificationid']) ? mysqli_real_escape_string($conn, $_POST['classificationid']) : '';
$priorityid = isset($_POST['priorityid']) ? mysqli_real_escape_string($conn, $_POST['priorityid']) : '';
$subject = isset($_POST['subject']) ? mysqli_real_escape_string($conn, $_POST['subject']) : '';
$description = isset($_POST['description']) ? mysqli_real_escape_string($conn, $_POST['description']) : '';
$deadline = isset($_POST['deadline']) && !empty($_POST['deadline']) && $_POST['deadline'] != '0000-00-00' ? mysqli_real_escape_string($conn, $_POST['deadline']) : NULL;
$startdate = isset($_POST['startdate']) && !empty($_POST['startdate']) && $_POST['startdate'] != '0000-00-00' ? mysqli_real_escape_string($conn, $_POST['startdate']) : NULL;
$enddate = isset($_POST['enddate']) && !empty($_POST['enddate']) && $_POST['enddate'] != '0000-00-00' ? mysqli_real_escape_string($conn, $_POST['enddate']) : NULL;
$statusid = isset($_POST['statusid']) ? mysqli_real_escape_string($conn, $_POST['statusid']) : '';

// Properly handle the assignees array
$assignees = isset($_POST['taskAssigneeUp2']) && is_array($_POST['taskAssigneeUp2']) 
    ? $_POST['taskAssigneeUp2'] 
    : (isset($_POST['taskAssigneeUp2']) ? array($_POST['taskAssigneeUp2']) : array());

error_log("Processed assignees array: " . print_r($assignees, true));

// Validate required fields
if (empty($id)) {
    $response['message'] = 'Task ID is required';
    echo json_encode($response);
    exit;
}

if (empty($subject)) {
    $response['message'] = 'Subject is required';
    echo json_encode($response);
    exit;
}

// Format date fields for database - FIX NULL HANDLING
$deadlineFormatted = ($deadline) ? "'$deadline'" : "NULL";
$startdateFormatted = ($startdate) ? "'$startdate'" : "NULL";
$enddateFormatted = ($enddate) ? "'$enddate'" : "NULL";

// Update the task - Removed lastupdated and lastupdatedby fields
$updateQuery = "UPDATE pm_projecttasktb SET 
                projectid = " . ($projectid ? "'$projectid'" : "NULL") . ",
                classificationid = " . ($classificationid ? "'$classificationid'" : "NULL") . ",
                priorityid = " . ($priorityid ? "'$priorityid'" : "NULL") . ",
                subject = '$subject',
                description = " . ($description ? "'$description'" : "NULL") . ",
                deadline = $deadlineFormatted,
                startdate = $startdateFormatted,
                enddate = $enddateFormatted,
                statusid = " . ($statusid ? "'$statusid'" : "NULL");

// Include parent_id only if it exists and should be updated
if (!empty($parent_id)) {
    $updateQuery .= ", parent_id = '$parent_id'";
}

$updateQuery .= " WHERE id = '$id'";

$result = mysqli_query($conn, $updateQuery);

if (!$result) {
    $response['message'] = 'Error updating task: ' . mysqli_error($conn);
    echo json_encode($response);
    exit;
}

// Handle assignees - first delete existing assignees
$deleteAssigneesQuery = "DELETE FROM pm_taskassigneetb WHERE taskid = '$id'";
error_log("Delete query: " . $deleteAssigneesQuery);
$deleteResult = mysqli_query($conn, $deleteAssigneesQuery);

if (!$deleteResult) {
    error_log("Delete assignees error: " . mysqli_error($conn));
    $response['message'] = 'Error updating assignees: ' . mysqli_error($conn);
    echo json_encode($response);
    exit;
}

// Insert new assignees
$assigneeSuccess = true;
$assigneeErrors = [];

if (!empty($assignees)) {
    foreach ($assignees as $assignee) {
        if (empty($assignee)) continue; // Skip empty assignees
        
        $assignee = mysqli_real_escape_string($conn, $assignee);
        $insertAssigneeQuery = "INSERT INTO pm_taskassigneetb (taskid, assigneeid) 
                               VALUES ('$id', '$assignee')";
        error_log("Insert assignee query: " . $insertAssigneeQuery);
        
        $assigneeResult = mysqli_query($conn, $insertAssigneeQuery);
        
        if (!$assigneeResult) {
            $assigneeSuccess = false;
            $assigneeErrors[] = mysqli_error($conn);
            error_log("Insert assignee error: " . mysqli_error($conn));
        } else {
            error_log("Successfully inserted assignee: " . $assignee);
        }
    }
}

if (!$assigneeSuccess) {
    $response['message'] = 'Task updated but there was an error updating assignees: ' . implode("; ", $assigneeErrors);
    $response['success'] = true; // Task was updated, so partial success
    $response['partial'] = true; // Indicate it was a partial success
} else {
    $response['success'] = true;
    $response['message'] = 'Task updated successfully';
}

error_log("Final response: " . print_r($response, true));
echo json_encode($response);
exit;
?>
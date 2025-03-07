<?php
include('../check_session.php');
$conn = connectionDB();

$response = array('success' => false, 'message' => '');

if (isset($_POST['taskId'])) {
    $taskId = mysqli_real_escape_string($conn, $_POST['taskId']);
    
    // Update the statusid to 5 instead of deleting
    $query = "UPDATE pm_projecttasktb SET statusid = 5 WHERE id = '$taskId'";
    
    if (mysqli_query($conn, $query)) {
        $response['success'] = true;
        $response['message'] = 'Task deleted successfully';
    } else {
        $response['message'] = 'Error updating task status: ' . mysqli_error($conn);
    }
} else {
    $response['message'] = 'Task ID not provided';
}

echo json_encode($response);
?>
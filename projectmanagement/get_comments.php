<?php
include('../conn/db.php');


$taskId = $_GET['taskId'];
$response = ['messages' => []];

$sql = "SELECT t.*, u.username 
        FROM pm_threadtb t
        JOIN sys_usertb u ON t.createdbyid = u.id
        WHERE t.taskid = $taskId
        ORDER BY t.datetimecreated DESC";
$result = mysqli_query($mysqlconn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $response['messages'][] = [
        'username' => $row['username'],
        'message' => $row['message'],
        'datetimecreated' => time_elapsed_string($row['datetimecreated'])
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
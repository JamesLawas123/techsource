<?php
include('../conn/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = mysqli_real_escape_string($mysqlconn, $_POST['subject']);
    $message = mysqli_real_escape_string($mysqlconn, $_POST['message']);
    $createdbyid = 1; // Replace with actual user ID from session
    $datetimecreated = date('Y-m-d H:i:s');
    $taskid = 1; // Replace with actual task ID if needed

    $sql = "INSERT INTO pm_threadtb (taskid, createdbyid, datetimecreated, subject, message) 
            VALUES ('$taskid', '$createdbyid', '$datetimecreated', '$subject', '$message')";
    
    if (mysqli_query($mysqlconn, $sql)) {
        header('Location: test.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($mysqlconn);
    }
}
?>
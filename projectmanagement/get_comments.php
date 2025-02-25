<?php
include('../conn/db.php');

function time_elapsed_string($datetime) {
    $now = time();
    $created = strtotime($datetime);
    $diff = $now - $created;

    if ($diff < 60) {
        return 'just now';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' minutes ago';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' hours ago';
    } else {
        return floor($diff / 86400) . ' days ago';
    }
}

function buildCommentTree($comments, $parentId = null) {
    $branch = array();
    foreach ($comments as $comment) {
        if ($comment['parent_id'] == $parentId) {
            $children = buildCommentTree($comments, $comment['id']);
            if ($children) {
                $comment['replies'] = $children;
            }
            $branch[] = $comment;
        }
    }
    return $branch;
}

$task_id = isset($_GET['taskId']) ? intval($_GET['taskId']) : 0;

$sql = "SELECT t.*, u.username 
        FROM pm_threadtb t
        JOIN sys_usertb u ON t.createdbyid = u.id
        WHERE t.taskid = $task_id
        ORDER BY t.datetimecreated DESC";

$result = mysqli_query($mysqlconn, $sql);

$comments = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $comments[] = array(
            'id' => $row['id'],
            'parent_id' => $row['parent_id'],
            'username' => $row['username'],
            'message' => $row['message'],
            'time' => time_elapsed_string($row['datetimecreated']),
            'file_data' => $row['file_data'] // Add this line to include file data
        );
    }
}

// Build hierarchical comment tree
$commentTree = buildCommentTree($comments);

header('Content-Type: application/json');
echo json_encode($commentTree);
?>
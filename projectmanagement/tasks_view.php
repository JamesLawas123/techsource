<?php
require_once('../conn/db.php');
require_once('../check_session.php');

// Get task ID from AJAX request
$taskId = $_GET['taskId'] ?? 0;
$userid = $_SESSION['userid'] ?? 0;
$myusergroupid = $_SESSION['usergroupid'] ?? 0;

if (!$mysqlconn) {
    echo '<div class="alert alert-danger">Database connection failed</div>';
    exit;
}

// Modify query to filter by parent task ID
$myquery = "SELECT DISTINCT pm_projecttasktb.id,pm_projecttasktb.subject,pm_projecttasktb.description,pm_projecttasktb.deadline,
                   pm_projecttasktb.startdate,pm_projecttasktb.enddate,pm_projecttasktb.classificationid,
                   pm_projecttasktb.statusid,pm_projecttasktb.priorityid,pm_projecttasktb.createdbyid,pm_projecttasktb.datetimecreated,
                   pm_projecttasktb.assignee,pm_projecttasktb.projectid,
                   sys_taskclassificationtb.classification,sys_taskstatustb.statusname,sys_priorityleveltb.priorityname,
                   sys_projecttb.projectname,pm_projecttasktb.percentdone
            FROM pm_projecttasktb
            LEFT JOIN sys_taskclassificationtb ON sys_taskclassificationtb.id=pm_projecttasktb.classificationid
            LEFT JOIN sys_taskstatustb ON sys_taskstatustb.id=pm_projecttasktb.statusid
            LEFT JOIN sys_priorityleveltb ON sys_priorityleveltb.id=pm_projecttasktb.priorityid
            LEFT JOIN sys_projecttb ON sys_projecttb.id=pm_projecttasktb.projectid
            ORDER BY pm_projecttasktb.datetimecreated ASC";
?>

<div style="max-height: 400px; overflow-y: auto;">
    <?php
    $myresult = mysqli_query($mysqlconn, $myquery);
    
    if (!$myresult) {
        echo '<div class="alert alert-danger">Error executing query: ' . mysqli_error($mysqlconn) . '</div>';
    } else if (mysqli_num_rows($myresult) == 0) {
        echo '<div class="alert alert-info">No subtasks found for this task.</div>';
    } else {
    ?>
        <table class="table table-striped table-bordered table-hover" id="dataTables-subtasks">
            <thead>
                <tr>
                    <th></th>
                    <th>Tracker</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Subject</th>
                    <th>Assignee</th>
                    <th>Project</th>
                    <th>Deadline</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $counter = 1;
            
            while($row = mysqli_fetch_assoc($myresult)){
                $taskId = $row['id'];    
                $myTaskStatusid = $row['statusid'];    
                $myTaskDeadline = $row['deadline'];    
                $myTaskStartdate = $row['startdate'];        
                $myTaskEnddate = $row['enddate'];    
                $myTaskPercentage = $row['percentdone'];    
                
                if($myTaskPercentage == 1){
                    $myEvaluation = "Exact Delivery";
                }elseif($myTaskPercentage == 2){
                    $myEvaluation = "Late Delivery";
                }elseif($myTaskPercentage == 3){
                    $myEvaluation = "Ahead Delivery";
                }else{
                    $myEvaluation = "In-progress";
                }
                
                if($myTaskStatusid == 6){
                    $myClass = 'success';
                }elseif($myTaskStatusid == 2){
                    $myClass = 'info';
                }elseif($myTaskStatusid == 3){
                    $myClass = 'warning';
                }else{
                    $myClass = 'danger';
                }
                ?>
                <tr onclick="showUpdateTask('<?php echo $taskId;?>');" class="<?php echo $myClass; ?>" style="cursor: pointer;">
                    <td><?php echo $counter;?></td>
                    <td><?php echo $row['classification'];?></td>
                    <td><?php echo $row['statusname'];?></td>
                    <td><?php echo $row['priorityname'];?></td>
                    <td><?php echo $row['subject'];?></td>
                    <td>
                        <?php
                        $count = 0;
                        $myqueryxx2 = "SELECT pm_taskassigneetb.taskid,pm_taskassigneetb.assigneeid,
                                    sys_usertb.user_firstname,sys_usertb.user_lastname
                                    FROM pm_taskassigneetb
                                    LEFT JOIN sys_usertb ON sys_usertb.id=pm_taskassigneetb.assigneeid
                                    WHERE pm_taskassigneetb.taskid = '$taskId' ";
                        $myresultxx2 = mysqli_query($mysqlconn, $myqueryxx2);
                        while($rowxx2 = mysqli_fetch_assoc($myresultxx2)){
                            if ($count++ > 0) echo ",";
                            echo $rowxx2['user_firstname'];
                        }
                        ?>
                    </td>
                    <td><?php echo $row['projectname'];?></td>
                    <td><?php echo $row['deadline'];?></td>
                    <td><?php echo $myEvaluation;?></td>
                </tr>
                <?php
                $counter++;
            }
            ?>
            </tbody>
        </table>
    <?php
    }
    ?>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable if table exists
    if ($('#dataTables-subtasks').length) {
        $('#dataTables-subtasks').DataTable({
            "pageLength": 10,
            "order": [[0, "asc"]],
            "responsive": true
        });
    }
});

function showUpdateTask(taskId) {
    window.open('threadPage.php?taskId=' + taskId, '_blank');
}
</script>
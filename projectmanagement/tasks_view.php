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
            WHERE pm_projecttasktb.parent_id = '$taskId' 
            AND pm_projecttasktb.type = 'subtask'
            ORDER BY pm_projecttasktb.datetimecreated ASC";
?>

<!-- Simple table with vertical scroll -->
<div>
    <?php
    $myresult = mysqli_query($mysqlconn, $myquery);
    
    if (!$myresult) {
        echo '<div class="alert alert-danger">Error executing query: ' . mysqli_error($mysqlconn) . '</div>';
    } else if (mysqli_num_rows($myresult) == 0) {
        echo '<div class="alert alert-info">No subtasks found for this task.</div>';
    } else {
    ?>
        <div style="overflow-y: auto; max-height: 400px;">
            <table id="subtasksTable" class="table table-striped table-bordered table-hover" style="width: 100%;">
                <thead>
                    <tr>
                        <th width="3%">#</th>
                        <th width="8%">Tracker</th>
                        <th width="8%">Status</th>
                        <th width="8%">Priority</th>
                        <th width="25%">Subject</th>
                        <th width="15%">Assignee</th>
                        <th width="15%">Project</th>
                        <th width="8%">Deadline</th>
                       
                        <th width="5%">Actions</th>
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
                    <tr class="<?php echo $myClass; ?>">
                        <td><?php echo $counter;?></td>
                        <td><?php echo $row['classification'];?></td>
                        <td><?php echo $row['statusname'];?></td>
                        <td><?php echo $row['priorityname'];?></td>
                        <td><?php echo $row['subject'];?></td>
                        <td>
                            <?php
                            $assigneeNames = [];
                            $assignees = explode(',', $row['assignee']);
                            
                            foreach ($assignees as $assigneeId) {
                                if (empty($assigneeId)) continue;
                                
                                $myqueryxx2 = "SELECT CONCAT(user_firstname, ' ', user_lastname) AS full_name
                                            FROM sys_usertb 
                                            WHERE id = '" . mysqli_real_escape_string($mysqlconn, $assigneeId) . "'";
                                $myresultxx2 = mysqli_query($mysqlconn, $myqueryxx2);
                                
                                if ($myresultxx2 && mysqli_num_rows($myresultxx2) > 0) {
                                    $rowxx2 = mysqli_fetch_assoc($myresultxx2);
                                    $assigneeNames[] = $rowxx2['full_name'];
                                }
                            }
                            
                            echo !empty($assigneeNames) ? implode(', ', $assigneeNames) : "Unassigned";
                            ?>
                        </td>
                        <td><?php echo $row['projectname'];?></td>
                        <td><?php echo date('m/d/Y', strtotime($row['deadline']));?></td>
                        
                        <td>
                            <div class="btn-group" style="display: flex; gap: 4px;">
                                <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#updateSubtaskModal" onclick="loadSubtaskModal('<?php echo $taskId; ?>')">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-xs" onclick="showUpdateTask('<?php echo $taskId;?>');">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php
                    $counter++;
                }
                ?>
                </tbody>
            </table>
        </div>
    <?php
    }
    ?>
</div>

<!-- Add this modal container -->
<div class="modal fade" id="updateSubtaskModal" tabindex="-1" role="dialog" aria-labelledby="updateSubtaskModalLabel" aria-hidden="true">
    <!-- Modal content will be loaded here -->
</div>

<script>
$(document).ready(function() {
   
});

function showUpdateTask(taskId) {
    window.open('threadPage.php?taskId=' + taskId, '_blank');
}

function loadSubtaskModal(taskId) {
    $('#updateSubtaskModal').load('modal_updatesubtask.php?taskId=' + taskId, function() {
        $('#updateSubtaskModal').modal('show');
    });
}
</script>
<?php
date_default_timezone_set('Asia/Manila');
$nowdateunix = date("Y-m-d h:i:s",time());	
require('../conn/db.php');
$conn = connectionDB();
$user_id=strtoupper($_GET['user_id']);
$mygroup=strtoupper($_GET['mygroup']);

?>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="dataTables-tasktbtickets">
		<thead>
			<tr>
				<th>Task ID</th>
				<th>Tracker</th>
				<th>Status</th>
				<th>Priority</th>
				<th>Asignee</th>
				<th>Subject</th>
				<th>Project</th>
				<th>Deadline(expected)</th>
				<!--<th>Duration</th>-->
				<th>Note</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		$conn = connectionDB();
		$counter = 1;
		$param = " ";

		if($mygroup == 3){
			$param = " ";
		}else{
			$param = " AND pm_taskassigneetb.assigneeid = $user_id ";
		}
		
		$myquery = "SELECT DISTINCT pm_projecttasktb.id,pm_projecttasktb.srn_id,pm_projecttasktb.subject,pm_projecttasktb.description,pm_projecttasktb.deadline,
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
					LEFT JOIN pm_taskassigneetb ON pm_projecttasktb.id = pm_taskassigneetb.taskid
					WHERE pm_projecttasktb.istask = '2' AND pm_projecttasktb.statusid IN ('1','3','4','5')
					$param
					ORDER BY pm_projecttasktb.datetimecreated ASC";
		$myresult = mysqli_query($conn, $myquery);
		while($row = mysqli_fetch_assoc($myresult)){
			$taskId = $row['id'];	
			$myTaskStatusid = $row['statusid'];	
			$myTaskDeadline = $row['deadline'];	
			$myTaskStartdate = $row['startdate'];		
			$myTaskEnddate = $row['enddate'];	
			$myTaskPercentage = $row['percentdone'];	
			if($myTaskPercentage == 1){
				$myEvaluation = "Exact Delivery";	//exact
			}elseif($myTaskPercentage == 2){
				$myEvaluation = "Late Delivery";	//late
			}elseif($myTaskPercentage == 3){
				$myEvaluation = "Ahead Delivery";	//aheadoftime
			}else{
				$myEvaluation = "In-progress";	//aheadoftime
			}
			/*------get hours---------*/
			$ts1 = strtotime($myTaskStartdate);
			// $ts2 = strtotime($nowdateunix);
			if(($myTaskEnddate == null)||($myTaskEnddate == '1900-01-1')){$ts2 = strtotime($nowdateunix);}else{$ts2 = strtotime($myTaskEnddate);}

			// $seconds_diff = $ts2 - $ts1;
			// $seconds_diff = date("d",$ts2 - $ts1); 
			if($ts1 > $ts2){
				$seconds_diff = date("d",$ts1 - $ts2); 
			}elseif($ts1 < $ts2){
				$seconds_diff = date("d",$ts2 - $ts1); 
			}
			 // from today
			if($myTaskStatusid == 6){$myClass = 'success';}elseif($myTaskStatusid == 2){$myClass = 'info';}elseif($myTaskStatusid == 3){$myClass = 'warning';}else{$myClass = 'danger';}
		?>
			<tr class="<?php echo $myClass; ?>">
				<td><?php echo $row['srn_id'];?></td>
				<td><?php echo $row['classification'];?></td>
				<td><?php echo $row['statusname'];?></td>
				<td><?php echo $row['priorityname'];?></td>
				<td>
					<?php
						$count = 0;
						$myqueryxx2 = "SELECT pm_taskassigneetb.taskid,pm_taskassigneetb.assigneeid,
									sys_usertb.user_firstname,sys_usertb.user_lastname
									FROM pm_taskassigneetb
									LEFT JOIN sys_usertb ON sys_usertb.id=pm_taskassigneetb.assigneeid
									WHERE pm_taskassigneetb.taskid = '$taskId' ";
						$myresultxx2 = mysqli_query($conn, $myqueryxx2);
						while($rowxx2 = mysqli_fetch_assoc($myresultxx2)){
							if ($count++ > 0) echo ",";
							echo $rowxx2['user_firstname'];
						}
					?>
				</td>
				<td><?php echo $row['subject'];?></td>
				<td><?php echo $row['projectname'];?></td>
				<td><?php echo $row['deadline'];?></td>
				<!--<td><?php echo $seconds_diff." days";?></td>-->
				<td><?php echo $myEvaluation;?></td>
				<td>
					<div class="btn-group">
						<button type="button" class="btn btn-xs btn-info" onclick="showUpdateTask('<?php echo $taskId;?>');" data-toggle="modal" data-target="#myModal_updateTask">
							<i class="ace-icon fa fa-pencil bigger-120"></i> Edit
						</button>
						<a href="threadPage.php?taskId=<?php echo $taskId; ?>" class="btn btn-xs btn-danger">
							<i class="ace-icon fa fa-arrow-right icon-on-right"></i> Open Thread
						</a>
					</div>
				</td>
			</tr>
	
		</tbody>
			<?php
			$counter ++;
			}
		?>
	</table>
</div>

<script type="text/javascript">
 $(document).ready(function() {
       $('#dataTables-tasktbtickets').DataTable({
            "aaSorting": [],
			"scrollCollapse": true,	
			"autoWidth": false,
			"responsive": true,
			"bSort" : true,
			"lengthMenu": [ [10, 25, 50, 100, 200, 300, 400, 500], [10, 25, 50, 100, 200, 300, 400, 500] ]
        });
	});
</script>
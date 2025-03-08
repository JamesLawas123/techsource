<?php
date_default_timezone_set('Asia/Manila');
$nowdateunix = date("Y-m-d h:i:s",time());	
include "blocks/header.php";
$moduleid=4;
include('proxy.php');
// $myusergroupid;

?>
<!DOCTYPE html>
<link rel="../stylesheet" href="../css/bootstrapValidator.min.css"/> 
<style>
.input-xs {
    height: 22px;
    padding: 0px 1px;
    font-size: 10px;
    line-height: 1; //If Placeholder of the input is moved up, rem/modify this.
    border-radius: 0px;
    }
</style>
<html lang="en">
	<body class="no-skin">
		
		<div class="row">
            <div class="col-lg-12">
				<div id="myModal_createTask" class="modal container fade" data-width="760" data-replace="true" style="display: none;" data-modal-overflow="true" data-backdrop="false"></div>	
				<div id="myModal_updateTask" class="modal container fade" data-width="760" data-replace="true" style="display: none;" data-modal-overflow="true" data-backdrop="false"></div>	
				<div id="insert_openThread" class="modal container fade" data-width="760" data-replace="true" style="display: none;" data-modal-overflow="true" data-backdrop="false"></div>	
				<div id="showopenCreateThread" class="modal container fade" data-width="760" data-replace="true" style="display: none;" data-modal-overflow="true" data-backdrop="false"></div>	
				<input type="hidden" class="form-control" placeholder="Subject" id="user_id" name="user_id" value="<?php echo $userid;?>"/>
				<input type="hidden" class="form-control" placeholder="Subject" id="mygroup" name="mygroup" value="<?php echo $myusergroupid;?>"/>
			</div>
		</div>
		<div class="main-container ace-save-state" id="main-container">
			<?php include "blocks/navigation.php";?>

			<div class="main-content">
				<div class="main-content-inner">
					<div class="breadcrumbs ace-save-state" id="breadcrumbs">
						<ul class="breadcrumb">
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="../index.php">Home</a>
							</li>
							<li>Task Management </li>
							<li class="active"><button class="btn btn-purple input-xs" onclick="showcreatetask(0);" data-toggle="modal"  data-target="#myModal_createTask" <?php if($authoritystatusidz == 2){echo "disabled";}?>><i class="ace-icon fa fa-floppy-o bigger-120"></i>Create task</button></li>
						</ul><!-- /.breadcrumb -->

					</div>

					<div class="page-content">
						<div class="row">
							<div class="col-xs-12">
								
							</div>
							<div class="col-xs-12">
								<ul class="nav nav-tabs" id="tabUL">
									<li class="active"><a href="#taskfrompm" data-toggle="tab">Assignement</a></li>
									<li><a href="#taskfrompmdone" data-toggle="tab">Assignement(Done)</a></li>
									<li><a href="#taskfrompmreject" data-toggle="tab">Assignement(Rejected)</a></li>
									<li><a href="#taskfromticket" data-toggle="tab">Ticket</a></li>
									<li><a href="#taskfromticketdone" data-toggle="tab">Ticket(Done)</a></li>
									<li><a href="#taskfromticketreject" data-toggle="tab">Ticket(Reject)</a></li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane fade in active" id="taskfrompm">
										<div class="table-responsive">
											<table class="table table-striped table-bordered table-hover" id="dataTables-tasktb">
												<thead>
													<tr>
														<th></th>
														<th>Tracker</th>
														<th>Status</th>
														<th>Priority</th>
														<th>Subject</th>
														<th>Assignee</th>
														<th>Project</th>
														<th>Deadline(expected)</th>
														<!--<th>Duration</th>-->
														<th>Note</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
												<?php 
												$conn = connectionDB();
												$counter = 1;
												
												if($myusergroupid == 3){
													// $myquery2 = "SELECT taskid FROM pm_taskassigneetb WHERE assigneeid = '$userid' ";
													// $myresult2 = mysqli_query($conn, $myquery2);
													// while($row2 = mysqli_fetch_assoc($myresult2)){
														//$mytaskId = $row2['taskid'];
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
																	WHERE pm_projecttasktb.istask = '1' AND pm_projecttasktb.statusid IN ('1','3','4','5')
																	ORDER BY pm_projecttasktb.datetimecreated ASC";
														$myresult = mysqli_query($conn, $myquery);
													// }
												}else{
													$myquery2 = "SELECT taskid FROM pm_taskassigneetb WHERE assigneeid = '$userid' ";
													$myresult2 = mysqli_query($conn, $myquery2);
													while($row2 = mysqli_fetch_assoc($myresult2)){
														$mytaskId = $row2['taskid'];
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
																WHERE pm_projecttasktb.istask = '1' AND pm_projecttasktb.id = $mytaskId AND pm_projecttasktb.statusid IN ('1','3','4','5')
																ORDER BY pm_projecttasktb.datetimecreated ASC";
													$myresult = mysqli_query($conn, $myquery);
													}
												}
												
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
													if(($myTaskEnddate == null)||($myTaskEnddate == '1900-01-01')){$ts2 = strtotime($nowdateunix);}else{$ts2 = strtotime($myTaskEnddate);echo "trueee";}

													// $seconds_diff = $ts2 - $ts1;
													// $seconds_diff = date("d",$ts2 - $ts1); 
													// $seconds_diff = $ts2 - $ts1;
													if($ts1 > $ts2){
														$seconds_diff = date("d",$ts1 - $ts2); 
													}elseif($ts1 < $ts2){
														$seconds_diff = date("d",$ts2 - $ts1); 
													}
													 // from today
													if($myTaskStatusid == 6){$myClass = 'success';}elseif($myTaskStatusid == 2){$myClass = 'info';}elseif($myTaskStatusid == 3){$myClass = 'warning';}else{$myClass = 'danger';}
												?>
													<tr class="<?php echo $myClass; ?>">
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
																$myresultxx2 = mysqli_query($conn, $myqueryxx2);
																while($rowxx2 = mysqli_fetch_assoc($myresultxx2)){
																	if ($count++ > 0) echo ",";
																	echo $rowxx2['user_firstname'];
																}
															?>
														</td>
														<td><?php echo $row['projectname'];?></td>
														<td><?php
																echo $row['deadline'];
																// echo "deadline:".$row['deadline']."</br>";
																// echo "startdate:".$row['startdate']."</br>";
																// echo "enddate:".$row['enddate']."</br>";
														?></td>
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
									</div>
									<div class="tab-pane fade in" id="taskfrompmdone">
										<div id="insert_assignmentdone"></div>					                    	
										<div id="insertbody_assignmentdone"></div>
									</div>
									<div class="tab-pane fade in" id="taskfrompmreject">
										<div id="insert_taskreject"></div>					                    	
										<div id="insertbody_taskreject"></div>
									</div>
									<div class="tab-pane fade in" id="taskfromticket">
									
										<div id="insert_tickets"></div>					                    	
										<div id="insertbody_tickets"></div>
									</div>
									<div class="tab-pane fade in" id="taskfromticketdone">
										<div id="insert_taskdone"></div>					                    	
										<div id="insertbody_taskdone"></div>
									</div>
									<div class="tab-pane fade in" id="taskfromticketreject">
										<div id="insert_ticketreject"></div>					                    	
										<div id="insertbody_ticketreject"></div>
									</div>
								</div>
								
								
								
							</div>
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<?php include "blocks/footer.php"; ?>

			
		</div><!-- /.main-container -->

		
	</body>
</html>
<script src="../assets/customjs/projectmanagement.js"></script>

<!-- Validator-->
<script type="text/javascript" src="../assets/customjs/bootstrapValidator.min.js"></script>  
<script type="text/javascript" src="../assets/customjs/bootstrapValidator.js"></script> 
<!-- inline scripts related to this page -->
<script type="text/javascript">
 $(document).ready(function() {
        $('#dataTables-tasktb').DataTable({
            "aaSorting": [],
			"scrollCollapse": true,	
			"autoWidth": true,
			"responsive": true,
			"bSort" : true,
			"lengthMenu": [ [10, 25, 50, 100, 200, 300, 400, 500], [10, 25, 50, 100, 200, 300, 400, 500] ]
        });
	});
	$(document).ready(function() {
		// navigation click actions	
		$('.scroll-link').on('click', function(event){
			event.preventDefault();
			var sectionID = $(this).attr("data-id");
			scrollToID('#' + sectionID, 750);
		});	
		
		$('#tabUL a[href="#taskfrompm"]').on('click', function(event){				
				//gototop(); 
		});
	

		//-----------store tab values to has-----------------
		$('#tabUL a').click(function (e) {
			e.preventDefault();
			$(this).tab('show');
		});

		// store the currently selected tab in the hash value
		$("ul.nav-tabs > li > a").on("shown.bs.tab", function (e) {
			var id = $(e.target).attr("href").substr(1);
			window.location.hash = id;
		});
    
		 // on load of the page: switch to the currently selected tab
		var hash = window.location.hash;
		$('#tabUL a[href="' + hash + '"]').tab('show');
		if(hash == "#taskfrompmdone") {
			showtasksfrompmdone();
		}
		if(hash == "#taskfromticket") {
			showtasksfromticket();
		}
		if(hash == "#taskfromticketdone") {
			showtasksticketdone();
		}
		if(hash == "#taskfrompmreject") {
			showtasksfrompmreject();
		}
		if(hash == "#taskfromticketreject") {
			showtasksticketreject();
		}
		
		
		
	});
</script>
<?php
require('../conn/db.php');
$conn = connectionDB();
$moduleid=1;

$nowdateunix = date("Y-m-d",time());	

function alphanumericAndSpace( $string ) {
   return preg_replace( "/[^,;a-zA-Z0-9 _-]|[,; ]$/s", "", $string );
}


$ticketUserid=strtoupper($_GET['ticketUserid']);
$ticketProjectOwner=strtoupper($_GET['ticketProjectOwner']);
$ticketClassification=strtoupper($_GET['ticketClassification']);
$ticketPriority=strtoupper($_GET['ticketPriority']);
$ticketSubject=urldecode($_GET['ticketSubject']);
$ticketTargetDate=strtoupper($_GET['ticketTargetDate']);
$description=urldecode($_GET['description']);


$ticketAssignee=null;
$ticketStartDate="1900-01-01";
$ticketEndDate="1900-01-01";
$ticketIdentification = 2;	//task is from pm
if (($description == '')||($description == null)){$description="NULL";}


$myqueryxxup1 = "SELECT statusid FROM sys_projecttb WHERE id = '$ticketProjectOwner'";
$myresultxxup1 = mysqli_query($conn, $myqueryxxup1);
while($row66 = mysqli_fetch_assoc($myresultxxup1)){
	$statusidnix=$row66['statusid'];
}
	

// if($statusidnix <> 6){

/*-------for audit trails--------*/
$auditRemarks1 = "REGISTER NEW TICKET"." | ".$ticketSubject;
$auditRemarks2 = "ATTEMPT TO REGISTER NEW TICKET, FAILED"." | ".$ticketSubject;
$auditRemarks3 = "ATTEMPT TO REGISTER NEW TICKET, DUPLICATE"." | ".$ticketSubject;

$myquery = "SELECT subject FROM pm_projecttasktb WHERE subject = '$ticketSubject' AND projectid = '$ticketProjectOwner' AND classificationid = '$ticketClassification' LIMIT 1";
$myresult = mysqli_query($conn, $myquery);
$num_rows = mysqli_num_rows($myresult);

	if($num_rows == 0){
		
		$myqueryxx = "INSERT INTO pm_projecttasktb (createdbyid,classificationid,priorityid,subject,deadline,description,projectid,istask,startdate,enddate)
					VALUES ('$ticketUserid','$ticketClassification','$ticketPriority','$ticketSubject','$ticketTargetDate','$description','$ticketProjectOwner','$ticketIdentification','$ticketStartDate','$ticketEndDate')";
		$myresultxx = mysqli_query($conn, $myqueryxx);
		
		if($myresultxx){
			if($statusidnix == 1){
			$myqueryxxup = "UPDATE sys_projecttb
									SET statusid = '3'
									WHERE id = '$ticketProjectOwner'";
			$myresultxxup = mysqli_query($conn, $myqueryxxup);	
			}elseif($statusidnix == 6){
			$myqueryxxup = "UPDATE sys_projecttb
									SET statusid = '3'
									WHERE id = '$ticketProjectOwner'";
			$myresultxxup = mysqli_query($conn, $myqueryxxup);	
			}
			
			$queryaud1 = "INSERT INTO `sys_audit` (module,remarks,userid) VALUES ('$moduleid','$auditRemarks1','$ticketUserid')";
			$resaud1 = mysqli_query($conn, $queryaud1);
			echo "<div class='alert alert-info fade in'>";
			echo "New Ticket has been saved!";   		
			echo "</div>";
		}else{
			$queryaud1 = "INSERT INTO `sys_audit` (module,remarks,userid) VALUES ('$moduleid','$auditRemarks2','$ticketUserid')";
			$resaud1 = mysqli_query($conn, $queryaud1);
			echo "<div class='alert alert-danger fade in'>";
			echo "Failed to save information!";   		
			echo "</div>";
		}
	}else{
		$queryaud1 = "INSERT INTO `sys_audit` (module,remarks,userid) VALUES ('$moduleid','$auditRemarks3','$ticketUserid')";
		$resaud1 = mysqli_query($conn, $queryaud1);
		
		echo "<div class='alert alert-danger fade in'>";
		echo "Duplicate Entry, Please check the details again.";   		
		echo "</div>";
	}
// }else{
	// echo "<div class='alert alert-danger fade in'>";
	// echo "Project has been closed, to proceed adding new task for this, call your PM.";   		
	// echo "</div>";
// }
?>

<script type="text/javascript">
	setTimeout(function(){location.reload();}, 1000);
</script>
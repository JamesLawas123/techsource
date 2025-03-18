<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Dashboard - RedBel</title>
		<!-- Favicon -->
		
		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->
		<link rel="stylesheet" href="assets/css/ace-skins.min.css" />
		<link rel="stylesheet" href="assets/css/ace-rtl.min.css" />

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="assets/js/ace-extra.min.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
	</head>

	<body class="no-skin">
		<?php include "blocks/header.php"; ?>
		<?php $conn = connectionDB(); ?>

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
							<li class="active">Dashboard</li>
						</ul><!-- /.breadcrumb -->

						<div class="nav-search" id="nav-search">
							<form class="form-search">
								<span class="input-icon">
									<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
									<i class="ace-icon fa fa-search nav-search-icon"></i>
								</span>
							</form>
						</div><!-- /.nav-search -->
					</div>

					<div class="page-content">
						<div class="page-header">
							<h1>
								Dashboard
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									overview &amp; stats
								</small>
							</h1>
						</div>
						
						<div class="row">
							<div class="col-xs-12">
								<div class="row">
									<div class="space-6"></div>
									<div class="col-sm-6 infobox-container">
										
										<div class="infobox infobox-green">
											<?php 
												// $myquery = "SELECT COUNT(id) AS totaltickets FROM pm_projecttasktb WHERE istask = '2'";
												// $myresult = mysqli_query($conn, $myquery);
												// while($row = mysqli_fetch_assoc($myresult)){
												// 	$totaltickets = $row['totaltickets'];
												// }
											?>
											<div class="infobox-icon">
												<i class="ace-icon fa fa-comments"></i>
											</div>

											<div class="infobox-data">
												<span class="infobox-data-number">100</span>
												<div class="infobox-content">Total Tickets</div>
											</div>

											<!--<div class="stat stat-success">8%</div>-->
										</div>

										<div class="infobox infobox-blue">
											<?php 
												// $myquery = "SELECT COUNT(id) AS totalassignment FROM pm_projecttasktb WHERE istask = '1'";
												// $myresult = mysqli_query($conn, $myquery);
												// while($row = mysqli_fetch_assoc($myresult)){
												// 	$totalassignment = $row['totalassignment'];
												// }
											?>
											<div class="infobox-icon">
												<i class="ace-icon fa fa-twitter"></i>
											</div>

											<div class="infobox-data">
												<span class="infobox-data-number">100</span>
												<div class="infobox-content">Total Assignment</div>
											</div>
										</div>

										<div class="infobox infobox-pink">
											<?php 
												// $myquery = "SELECT COUNT(id) AS totalongoingtickets FROM pm_projecttasktb WHERE istask = '2' AND statusid IN ('1','3','4') ";
												// $myresult = mysqli_query($conn, $myquery);
												// while($row = mysqli_fetch_assoc($myresult)){
												// 	$totalongoingtickets = $row['totalongoingtickets'];
												// 	$percentongoingtickets = ($totalongoingtickets/$totaltickets)*100;
												// }
											?>
											<div class="infobox-progress">
												<div class="easy-pie-chart percentage" data-percent="50" data-size="55">
													<span class="percent">50</span>% 
													<?php 
													// echo round($percentongoingtickets, 2); 
													?>
												</div>
											</div>
											<div class="infobox-data">
												<span class="infobox-text">&nbsp;&nbsp;&nbsp;&nbsp;Ongoing</span>

												<div class="infobox-content">
													<span class="bigger-110">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tickets</span>
												</div>
											</div>
										</div>

										<div class="infobox infobox-red">
											<?php 
												// $myquery = "SELECT COUNT(id) AS totalongoingassignment FROM pm_projecttasktb WHERE istask = '1' AND statusid IN ('1','3','4') ";
												// $myresult = mysqli_query($conn, $myquery);
												// while($row = mysqli_fetch_assoc($myresult)){
												// 	$totalongoingassignment = $row['totalongoingassignment'];
												// 	$percentongoingassignment = ($totalongoingassignment/$totalassignment)*100;
												// }
											?>
											<div class="infobox-progress">
												<div class="easy-pie-chart percentage" data-percent="10>" data-size="55">
													<span class="percent">10</span>%
												</div>
											</div>

											<div class="infobox-data">
												<span class="infobox-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ongoing</span>

												<div class="infobox-content">
													<span class="bigger-110">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Assignment</span>
												</div>
											</div>
										</div>

										<div class="infobox infobox-orange2">
											<?php 
												// $myquery = "SELECT COUNT(id) AS totalrejectticket FROM pm_projecttasktb WHERE istask = '2' AND statusid IN ('2','5') ";
												// $myresult = mysqli_query($conn, $myquery);
												// while($row = mysqli_fetch_assoc($myresult)){
												// 	$totalrejectticket = $row['totalrejectticket'];
												// 	$percentrejectedticket = ($totalrejectticket/$totaltickets)*100;
												// }
											?>
											<div class="infobox-progress">
												<div class="easy-pie-chart percentage" data-percent="100" data-size="55">
													<span class="percent">100</span>%
												</div>
											</div>

											<div class="infobox-data">
												<span class="infobox-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reject</span>

												<div class="infobox-content">
													<span class="bigger-110">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tickets</span>
												</div>
											</div>
										</div>

										<div class="infobox infobox-blue2">
											<?php 
												// $myquery = "SELECT COUNT(id) AS totalrejectassignment FROM pm_projecttasktb WHERE istask = '1' AND statusid IN ('2','5') ";
												// $myresult = mysqli_query($conn, $myquery);
												// while($row = mysqli_fetch_assoc($myresult)){
												// 	$totalrejectassignment = $row['totalrejectassignment'];
												// 	$percentrejectassignment = ($totalrejectassignment/$totalassignment)*100;
												// }
											?>
											<div class="infobox-progress">
												<div class="easy-pie-chart percentage" data-percent="100" data-size="55">
													<span class="percent">50</span>%
												</div>
											</div>

											<div class="infobox-data">
												<span class="infobox-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reject</span>

												<div class="infobox-content">
													<span class="bigger-110">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Assignment</span>
												</div>
											</div>
										</div>

										<div class="space-6"></div>

										<div class="infobox infobox-green infobox-large infobox-dark">
											<?php 
												// $myquery = "SELECT COUNT(id) AS totaldoneticket FROM pm_projecttasktb WHERE istask = '2' AND statusid IN ('6') ";
												// $myresult = mysqli_query($conn, $myquery);
												// while($row = mysqli_fetch_assoc($myresult)){
												// 	$totaldoneticket = $row['totaldoneticket'];
												// 	$percentdoneticket = ($totaldoneticket/$totaltickets)*100;
												// }
											?>
											<div class="infobox-progress">
												<div class="easy-pie-chart percentage" data-percent="20" data-size="55">
													<span class="percent">20</span>%
												</div>
											</div>

											<div class="infobox-data">
												<div class="infobox-content">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Task</div>
												<div class="infobox-content">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Closed</div>
											</div>
										</div>

										<div class="infobox infobox-blue infobox-large infobox-dark">
											<?php 
												// $myquery = "SELECT COUNT(id) AS totaldoneassignment FROM pm_projecttasktb WHERE istask = '1' AND statusid IN ('6') ";
												// $myresult = mysqli_query($conn, $myquery);
												// while($row = mysqli_fetch_assoc($myresult)){
												// 	$totaldoneassignment = $row['totaldoneassignment'];
												// 	$percentdoneassignment = ($totaldoneassignment/$totalassignment)*100;
												// }
											?>
											<div class="infobox-progress">
												<div class="easy-pie-chart percentage" data-percent="20" data-size="55">
													<span class="percent">20</span>%
												</div>
											</div>

											<div class="infobox-data">
												<div class="infobox-content">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Assignment</div>
												<div class="infobox-content">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Closed</div>
											</div>
										</div>

										
									</div>
									<div class="row">
										<div class="col-sm-5">
											<div class="widget-box transparent">
												<div class="widget-header widget-header-flat">
													<h4 class="widget-title lighter">
														<i class="ace-icon fa fa-star orange"></i>
														Classification Status
													</h4>
												</div>

												<div class="widget-body">
													<div class="widget-main no-padding">
														<table class="table table-bordered table-striped">
															<thead class="thin-border-bottom">
																<tr>
																	<th>
																		<i class="ace-icon fa fa-caret-right blue"></i>Classification
																	</th>

																	<th>
																		<i class="ace-icon fa fa-caret-right blue"></i>Total Count
																	</th>

																	<th class="hidden-480">
																		<i class="ace-icon fa fa-caret-right blue"></i>Ongoing
																	</th>
																	
																	<th class="hidden-480">
																		<i class="ace-icon fa fa-caret-right blue"></i>Closed
																	</th>
																</tr>
															</thead>

															<tbody>
																<?php
																	$myquerya = "SELECT classification,id
																				FROM sys_taskclassificationtb";
																	$myresulta = mysqli_query($conn, $myquerya);
																		while($rowa = mysqli_fetch_assoc($myresulta)){
																			$classificationName = $rowa['classification'];
																			$classificationNameId = $rowa['id'];
																	
																?>
																<tr>
																	<td><?php echo $classificationName; ?></td>
																	<?php 
																		$myquery = "SELECT COUNT(id) AS countfeature
																					FROM pm_projecttasktb
																					WHERE classificationid = '$classificationNameId' AND statusid IN ('1','2','3','4','5','6') ";
																		$myresult = mysqli_query($conn, $myquery);
																		while($row = mysqli_fetch_assoc($myresult)){
																			$totalfeature = $row['countfeature'];
																		}
																	
																		$myquery2 = "SELECT COUNT(id) AS countfeaturenotdone
																					FROM pm_projecttasktb
																					WHERE classificationid = '$classificationNameId' AND statusid IN ('1','2','3','4','5') ";
																		$myresult2 = mysqli_query($conn, $myquery2);
																		while($row2 = mysqli_fetch_assoc($myresult2)){
																			$totalnotdone = $row2['countfeaturenotdone'];
																		}
																		
																		$myquery3 = "SELECT COUNT(id) AS countfeaturedone
																					FROM pm_projecttasktb
																					WHERE classificationid = '$classificationNameId' AND statusid IN ('6') ";
																		$myresult3 = mysqli_query($conn, $myquery3);
																		while($row3 = mysqli_fetch_assoc($myresult3)){
																			$totaldone = $row3['countfeaturedone'];
																		}
																	?>

																	<td>
																		<center><b class="green"><?php echo $totalfeature; ?></b></center>
																	</td>

																	<td class="hidden-480">
																		<center><b class="red"><?php echo $totalnotdone; ?></b></center>
																	</td>
																	
																	<td class="hidden-480">
																		<span class="label label-info arrowed-right arrowed-in"><?php echo $totaldone; ?></span>
																	</td>
																</tr>
																<?php 
																		}
																		?>
															</tbody>
														</table>
													</div><!-- /.widget-main -->
												</div><!-- /.widget-body -->
											</div><!-- /.widget-box -->
										</div><!-- /.col -->
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


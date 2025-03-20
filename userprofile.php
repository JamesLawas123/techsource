<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>User Profile</title>
		<!-- Favicon -->
		<link rel="icon" type="image/png" href="assets/images/favicon/favicon-32x32.png" />
		
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
		<style>
			.infobox-container {
				display: flex;
				flex-direction: column;
				gap: 20px;
			}

			.infobox-row {
				display: flex;
				flex-wrap: wrap;
				gap: 10px;
				justify-content: flex-start;
			}

			.infobox {
				flex: 0 0 calc(33.33% - 10px); /* Makes 3 boxes per row with gap */
				margin: 0 !important;
				min-width: 200px;
			}

			/* Ensure the total counter takes full width */
			.infobox.total-counter {
				flex: 0 0 100%;
				margin-bottom: 10px !important;
			}

			/* Add these new styles */
			.infobox-link {
				text-decoration: none !important;
				color: inherit !important;
				cursor: pointer;
				display: block;
			}

			.infobox-link:hover .infobox {
				transform: translateY(-2px);
				box-shadow: 0 4px 8px rgba(0,0,0,0.1);
				transition: all 0.3s ease;
			}

			.infobox {
				transition: all 0.3s ease;
				/* ... your existing infobox styles ... */
			}
		</style>
		<script>
		function handleInfoboxClick(type, status) {
			// For development - just log the click
			console.log('Clicked:', type, 'with status:', status);
			// Prevent default navigation
			return false;
		}
		</script>
	</head>

	<body class="no-skin">
		<?php include "blocks/header.php"; ?>
		<?php $conn = connectionDB(); ?>
		<?php
		// Check if a session is not already started
		if (session_status() === PHP_SESSION_NONE) {
			session_start(); // Start the session to access session variables
		}

		// Use a default value of 0 if 'userid' is not set in the session
		$user_id = $_SESSION['userid'] ?? 0;

		if ($user_id) {
			// Fetch user details from the database
			$userDetailsSql = "SELECT * FROM sys_usertb WHERE id = " . intval($user_id);
			$userDetailsResult = mysqli_query($conn, $userDetailsSql);

			if ($userDetailsResult && mysqli_num_rows($userDetailsResult) > 0) {
				$user = mysqli_fetch_assoc($userDetailsResult);
			} else {
				$user = [];
			}
		} else {
			// Handle the case where the user is not logged in
			$user = [];
		}
		?>

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
							<li class="active">User Profile</li>
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
                            User Profile
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									my profile
								</small>
							</h1>
						</div>

                        
						
						<div class="row">
							<div class="col-sm-5">
								<div class="widget-box transparent">
	
									<div class="row">
										<!-- Left Column - Task Details -->
										<div class="col-md-10">
											<div class="page-header">
												<div class="user-info-row" style="display: flex; align-items: center; gap: 8px;">
													<div class="user-info-container" style="display: flex; align-items: center; gap: 8px; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
														<img src="<?php echo htmlspecialchars($user['photo'] ?? 'assets/images/avatars/user.png'); ?>" alt="User Photo" style="width: 50px; height: 50px; border-radius: 50%;">
													</div>
													<h4 class="widget-title lighter">
														<i class="ace-icon fa fa-star orange"></i>
														<span class="editable" id="username"><?php echo htmlspecialchars($user['username'] ?? 'N/A'); ?></span>
													</h4>
												</div>
											</div>

											<div class="profile-user-info profile-user-info-striped">
                                                


                                                <div class="profile-info-row">
													<div class="profile-info-name"> User ID </div>
													<div class="profile-info-value">
														<span class="editable" id="user_id"><?php echo htmlspecialchars($user['id'] ?? 'N/A'); ?></span>
													</div>
												</div>

												<div class="profile-info-row">
													<div class="profile-info-name"> Fullname </div>
													<div class="profile-info-value">
														<span class="editable" id="fullname">
															<?php 
																$fullname = trim(($user['user_firstname'] ?? '') . ' , ' . ($user['user_lastname'] ?? ''));
																echo htmlspecialchars($fullname ?: 'N/A'); 
															?>
														</span>
													</div>
												</div>
                                                <div class="profile-info-row">
													<div class="profile-info-name"> Email </div>
													<div class="profile-info-value">
														<span class="editable" id="email"><?php echo htmlspecialchars($user['emailadd'] ?? 'N/A'); ?></span>
													</div>
												</div>
												<hr class="hr-8 dotted">
												<div class="profile-info-row">
													<div class="profile-info-name"> Company </div>
													<div class="profile-info-value">
														<?php 
														$companyName = 'N/A';
														if (!empty($user['id'])) {
															$company_sql = "
																SELECT c.clientname 
																FROM sys_usertb u
																LEFT JOIN sys_clienttb c ON u.companyid = c.id
																WHERE u.id = " . intval($user['id']);
															$company_result = mysqli_query($conn, $company_sql);
															
															if ($company_result && mysqli_num_rows($company_result) > 0) {
																$company = mysqli_fetch_assoc($company_result);
																$companyName = htmlspecialchars($company['clientname']);  
															}
														}
														echo '<span class="editable" id="company">' . $companyName . '</span>';
														?>
													</div>
												</div>

												<div class="profile-info-row">
													<div class="profile-info-name"> User Level</div>
													<div class="profile-info-value">
														<?php 
														$userLevelName = 'N/A';
														if (!empty($user['id'])) {
															$user_level_sql = "
																SELECT l.levelname 
																FROM sys_usertb u
																LEFT JOIN sys_userleveltb l ON u.user_levelid = l.id
																WHERE u.id = " . intval($user['id']);
															$user_level_result = mysqli_query($conn, $user_level_sql);
															
															if ($user_level_result && mysqli_num_rows($user_level_result) > 0) {
																$user_level = mysqli_fetch_assoc($user_level_result);
																$userLevelName = htmlspecialchars($user_level['levelname']);  
															}
														}
														echo '<span class="editable" id="user_level">' . $userLevelName . '</span>';
														?>
													</div>
												</div>

												<div class="profile-info-row">
													<div class="profile-info-name"> User Group</div>
													<div class="profile-info-value">
														<?php 
														$userGroupName = 'N/A';
														if (!empty($user['id'])) {
															$user_group_sql = "
																SELECT g.groupname 
																FROM sys_usertb u
																LEFT JOIN sys_usergrouptb g ON u.user_groupid = g.id
																WHERE u.id = " . intval($user['id']);
															$user_group_result = mysqli_query($conn, $user_group_sql);
															
															if ($user_group_result && mysqli_num_rows($user_group_result) > 0) {
																$user_group = mysqli_fetch_assoc($user_group_result);
																$userGroupName = htmlspecialchars($user_group['groupname']);  
															}
														}
														echo '<span class="editable" id="user_group">' . $userGroupName . '</span>';
														?>
													</div>
												</div>

												
												

												
											</div>
										</div>
									</div>
								</div><!-- /.widget-box -->
							</div><!-- /.col -->


                            
							<div class="col-sm-6 infobox-container">
								<!-- First Row - Tickets Section -->
								<div class="infobox-container">
									<!-- Total Tickets Counter -->
									<a href="#" onclick="return handleInfoboxClick('tickets', 'all')" class="infobox-link">
										<div class="infobox infobox-green total-counter">
											<div class="infobox-icon">
												<i class="ace-icon fa fa-ticket"></i>
											</div>
											<div class="infobox-data">
												<?php
												// Get total tickets created by the user where istask = 2
												$tickets_sql = "SELECT COUNT(*) as ticket_count 
																FROM pm_projecttasktb 
																WHERE createdbyid = " . intval($user_id) . "
																AND istask = 2";
												$tickets_result = mysqli_query($conn, $tickets_sql);
												$ticket_count = 0;
												
												if ($tickets_result && mysqli_num_rows($tickets_result) > 0) {
													$tickets = mysqli_fetch_assoc($tickets_result);
													$ticket_count = $tickets['ticket_count'];
												}
												?>
												<span class="infobox-data-number"><?php echo $ticket_count; ?></span>
												<div class="infobox-content">Total Tickets</div>
											</div>
										</div>
									</a>

									<!-- Ticket Status Boxes -->
									<div class="infobox-row">
										<!-- New -->
										<a href="#" onclick="return handleInfoboxClick('tickets', '1')" class="infobox-link">
											<div class="infobox infobox-blue">
												<div class="infobox-progress">
													<div class="easy-pie-chart percentage" data-percent="100" data-size="55">
														<?php
														// Get count of tickets with statusid = 1 (New)
														$new_sql = "SELECT COUNT(*) as new_count 
																	FROM pm_projecttasktb 
																	WHERE createdbyid = " . intval($user_id) . "
																	AND statusid = 1
																	AND istask = 2";
														$new_result = mysqli_query($conn, $new_sql);
														$new_count = 0;
														
														if ($new_result && mysqli_num_rows($new_result) > 0) {
															$new = mysqli_fetch_assoc($new_result);
															$new_count = $new['new_count'];
														}
														?>
														<span class="percent"><?php echo $new_count; ?></span>
													</div>
												</div>
												<div class="infobox-data">
													<span class="infobox-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;New</span>
													<div class="infobox-content">
														<span class="bigger-110">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tickets</span>
													</div>
												</div>
											</div>
										</a>

										<!-- In Progress -->
										<a href="#" onclick="return handleInfoboxClick('tickets', '3')" class="infobox-link">
											<div class="infobox infobox-pink">
												<div class="infobox-progress">
													<div class="easy-pie-chart percentage" data-percent="50" data-size="55">
														<?php
														// Get count of tickets with statusid = 3 (InProg)
														$in_progress_sql = "SELECT COUNT(*) as in_progress_count 
																		  FROM pm_projecttasktb 
																		  WHERE createdbyid = " . intval($user_id) . "
																		  AND statusid = 3
																		  AND istask = 2";
														$in_progress_result = mysqli_query($conn, $in_progress_sql);
														$in_progress_count = 0;
														
														if ($in_progress_result && mysqli_num_rows($in_progress_result) > 0) {
															$in_progress = mysqli_fetch_assoc($in_progress_result);
															$in_progress_count = $in_progress['in_progress_count'];
														}
														?>
														<span class="percent"><?php echo $in_progress_count; ?></span>
													</div>
												</div>
												<div class="infobox-data">
													<span class="infobox-text">&nbsp;&nbsp;&nbsp;&nbsp;In Progress</span>
													<div class="infobox-content">
														<span class="bigger-110">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tickets</span>
													</div>
												</div>
											</div>
										</a>

										<!-- Pending -->
										<a href="#" onclick="return handleInfoboxClick('tickets', '4')" class="infobox-link">
											<div class="infobox infobox-orange2">
												<div class="infobox-progress">
													<div class="easy-pie-chart percentage" data-percent="100" data-size="55">
														<?php
														// Get count of tickets with statusid = 4 (Pending)
														$pending_sql = "SELECT COUNT(*) as pending_count 
																		  FROM pm_projecttasktb 
																		  WHERE createdbyid = " . intval($user_id) . "
																		  AND statusid = 4
																		  AND istask = 2";
														$pending_result = mysqli_query($conn, $pending_sql);
														$pending_count = 0;
														
														if ($pending_result && mysqli_num_rows($pending_result) > 0) {
															$pending = mysqli_fetch_assoc($pending_result);
															$pending_count = $pending['pending_count'];
														}
														?>
														<span class="percent"><?php echo $pending_count; ?></span>
													</div>
												</div>
												<div class="infobox-data">
													<span class="infobox-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pending</span>
													<div class="infobox-content">
														<span class="bigger-110">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tickets</span>
													</div>
												</div>
											</div>
										</a>

										<!-- Done -->
										<a href="#" onclick="return handleInfoboxClick('tickets', '6')" class="infobox-link">
											<div class="infobox infobox-green">
												<div class="infobox-progress">
													<div class="easy-pie-chart percentage" data-percent="100" data-size="55">
														<?php
														// Get count of tickets with statusid = 6 (Done)
														$done_sql = "SELECT COUNT(*) as done_count 
																	FROM pm_projecttasktb 
																	WHERE createdbyid = " . intval($user_id) . "
																	AND statusid = 6
																	AND istask = 2";
														$done_result = mysqli_query($conn, $done_sql);
														$done_count = 0;
														
														if ($done_result && mysqli_num_rows($done_result) > 0) {
															$done = mysqli_fetch_assoc($done_result);
															$done_count = $done['done_count'];
														}
														?>
														<span class="percent"><?php echo $done_count; ?></span>
													</div>
												</div>
												<div class="infobox-data">
													<span class="infobox-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Done</span>
													<div class="infobox-content">
														<span class="bigger-110">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tickets</span>
													</div>
												</div>
											</div>
										</a>

										<!-- Rejected -->
										<a href="#" onclick="return handleInfoboxClick('tickets', '2')" class="infobox-link">
											<div class="infobox infobox-orange2">
												<div class="infobox-progress">
													<div class="easy-pie-chart percentage" data-percent="100" data-size="55">
														<?php
														// Get count of tickets with statusid = 2 (Rejected)
														$reject_sql = "SELECT COUNT(*) as reject_count 
																	 FROM pm_projecttasktb 
																	 WHERE createdbyid = " . intval($user_id) . "
																	 AND statusid = 2
																	 AND istask = 2";
														$reject_result = mysqli_query($conn, $reject_sql);
														$reject_count = 0;
														
														if ($reject_result && mysqli_num_rows($reject_result) > 0) {
															$reject = mysqli_fetch_assoc($reject_result);
															$reject_count = $reject['reject_count'];
														}
														?>
														<span class="percent"><?php echo $reject_count; ?></span>
													</div>
												</div>
												<div class="infobox-data">
													<span class="infobox-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reject</span>
													<div class="infobox-content">
														<span class="bigger-110">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tickets</span>
													</div>
												</div>
											</div>
										</a>

										<!-- Cancelled -->
										<a href="#" onclick="return handleInfoboxClick('tickets', '5')" class="infobox-link">
											<div class="infobox infobox-orange2">
												<div class="infobox-progress">
													<div class="easy-pie-chart percentage" data-percent="100" data-size="55">
														<?php
														// Get count of tickets with statusid = 5 (Cancelled)
														$cancelled_sql = "SELECT COUNT(*) as cancelled_count 
																		FROM pm_projecttasktb 
																		WHERE createdbyid = " . intval($user_id) . "
																		AND statusid = 5
																		AND istask = 2";
														$cancelled_result = mysqli_query($conn, $cancelled_sql);
														$cancelled_count = 0;
														
														if ($cancelled_result && mysqli_num_rows($cancelled_result) > 0) {
															$cancelled = mysqli_fetch_assoc($cancelled_result);
															$cancelled_count = $cancelled['cancelled_count'];
														}
														?>
														<span class="percent"><?php echo $cancelled_count; ?></span>
													</div>
												</div>
												<div class="infobox-data">
													<span class="infobox-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cancelled</span>
													<div class="infobox-content">
														<span class="bigger-110">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tickets</span>
													</div>
												</div>
											</div>
										</a>
									</div>

									<!-- Total Assignments Counter -->
									<a href="#" onclick="return handleInfoboxClick('assignments', 'all')" class="infobox-link">
										<div class="infobox infobox-blue total-counter">
											<div class="infobox-icon">
												<i class="ace-icon fa fa-tasks"></i>
											</div>
											<div class="infobox-data">
												<?php
												// Get total assignments created by the user where istask = 1
												$assignments_sql = "SELECT COUNT(*) as assignment_count 
																FROM pm_projecttasktb 
																WHERE createdbyid = " . intval($user_id) . "
																AND istask = 1";
												$assignments_result = mysqli_query($conn, $assignments_sql);
												$assignment_count = 0;
												
												if ($assignments_result && mysqli_num_rows($assignments_result) > 0) {
													$assignments = mysqli_fetch_assoc($assignments_result);
													$assignment_count = $assignments['assignment_count'];
												}
												?>
												<span class="infobox-data-number"><?php echo $assignment_count; ?></span>
												<div class="infobox-content">Total Assignment</div>
											</div>
										</div>
									</a>

									<!-- Assignment Status Boxes -->
									<div class="infobox-row">
										<!-- New -->
										<a href="#" onclick="return handleInfoboxClick('assignments', '1')" class="infobox-link">
											<div class="infobox infobox-blue">
												<div class="infobox-progress">
													<div class="easy-pie-chart percentage" data-percent="100" data-size="55">
														<?php
														// Get count of assignments with statusid = 1 (New)
														$assign_new_sql = "SELECT COUNT(*) as new_count 
																	 FROM pm_projecttasktb 
																	 WHERE createdbyid = " . intval($user_id) . "
																	 AND statusid = 1
																	 AND istask = 1";
														$assign_new_result = mysqli_query($conn, $assign_new_sql);
														$assign_new_count = 0;
														
														if ($assign_new_result && mysqli_num_rows($assign_new_result) > 0) {
															$assign_new = mysqli_fetch_assoc($assign_new_result);
															$assign_new_count = $assign_new['new_count'];
														}
														?>
														<span class="percent"><?php echo $assign_new_count; ?></span>
													</div>
												</div>
												<div class="infobox-data">
													<span class="infobox-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;New</span>
													<div class="infobox-content">
														<span class="bigger-110">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Assignments</span>
													</div>
												</div>
											</div>
										</a>

										<!-- In Progress -->
										<a href="#" onclick="return handleInfoboxClick('assignments', '3')" class="infobox-link">
											<div class="infobox infobox-pink">
												<div class="infobox-progress">
													<div class="easy-pie-chart percentage" data-percent="50" data-size="55">
														<?php
														// Get count of assignments with statusid = 3 (InProg)
														$assign_in_progress_sql = "SELECT COUNT(*) as in_progress_count 
																	 FROM pm_projecttasktb 
																	 WHERE createdbyid = " . intval($user_id) . "
																	 AND statusid = 3
																	 AND istask = 1";
														$assign_in_progress_result = mysqli_query($conn, $assign_in_progress_sql);
														$assign_in_progress_count = 0;
														
														if ($assign_in_progress_result && mysqli_num_rows($assign_in_progress_result) > 0) {
															$assign_in_progress = mysqli_fetch_assoc($assign_in_progress_result);
															$assign_in_progress_count = $assign_in_progress['in_progress_count'];
														}
														?>
														<span class="percent"><?php echo $assign_in_progress_count; ?></span>
													</div>
												</div>
												<div class="infobox-data">
													<span class="infobox-text">&nbsp;&nbsp;&nbsp;&nbsp;In Progress</span>
													<div class="infobox-content">
														<span class="bigger-110">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Assignments</span>
													</div>
												</div>
											</div>
										</a>

										<!-- Pending -->
										<a href="#" onclick="return handleInfoboxClick('assignments', '4')" class="infobox-link">
											<div class="infobox infobox-orange2">
												<div class="infobox-progress">
													<div class="easy-pie-chart percentage" data-percent="100" data-size="55">
														<?php
														// Get count of assignments with statusid = 4 (Pending)
														$assign_pending_sql = "SELECT COUNT(*) as pending_count 
																	 FROM pm_projecttasktb 
																	 WHERE createdbyid = " . intval($user_id) . "
																	 AND statusid = 4
																	 AND istask = 1";
														$assign_pending_result = mysqli_query($conn, $assign_pending_sql);
														$assign_pending_count = 0;
														
														if ($assign_pending_result && mysqli_num_rows($assign_pending_result) > 0) {
															$assign_pending = mysqli_fetch_assoc($assign_pending_result);
															$assign_pending_count = $assign_pending['pending_count'];
														}
														?>
														<span class="percent"><?php echo $assign_pending_count; ?></span>
													</div>
												</div>
												<div class="infobox-data">
													<span class="infobox-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pending</span>
													<div class="infobox-content">
														<span class="bigger-110">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Assignments</span>
													</div>
												</div>
											</div>
										</a>

										<!-- Done -->
										<a href="#" onclick="return handleInfoboxClick('assignments', '6')" class="infobox-link">
											<div class="infobox infobox-green">
												<div class="infobox-progress">
													<div class="easy-pie-chart percentage" data-percent="100" data-size="55">
														<?php
														// Get count of assignments with statusid = 6 (Done)
														$assign_done_sql = "SELECT COUNT(*) as done_count 
																	 FROM pm_projecttasktb 
																	 WHERE createdbyid = " . intval($user_id) . "
																	 AND statusid = 6
																	 AND istask = 1";
														$assign_done_result = mysqli_query($conn, $assign_done_sql);
														$assign_done_count = 0;
														
														if ($assign_done_result && mysqli_num_rows($assign_done_result) > 0) {
															$assign_done = mysqli_fetch_assoc($assign_done_result);
															$assign_done_count = $assign_done['done_count'];
														}
														?>
														<span class="percent"><?php echo $assign_done_count; ?></span>
													</div>
												</div>
												<div class="infobox-data">
													<span class="infobox-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Done</span>
													<div class="infobox-content">
														<span class="bigger-110">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Assignments</span>
													</div>
												</div>
											</div>
										</a>

										<!-- Rejected -->
										<a href="#" onclick="return handleInfoboxClick('assignments', '2')" class="infobox-link">
											<div class="infobox infobox-orange2">
												<div class="infobox-progress">
													<div class="easy-pie-chart percentage" data-percent="100" data-size="55">
														<?php
														// Get count of assignments with statusid = 2 (Rejected)
														$assign_reject_sql = "SELECT COUNT(*) as reject_count 
																	 FROM pm_projecttasktb 
																	 WHERE createdbyid = " . intval($user_id) . "
																	 AND statusid = 2
																	 AND istask = 1";
														$assign_reject_result = mysqli_query($conn, $assign_reject_sql);
														$assign_reject_count = 0;
														
														if ($assign_reject_result && mysqli_num_rows($assign_reject_result) > 0) {
															$assign_reject = mysqli_fetch_assoc($assign_reject_result);
															$assign_reject_count = $assign_reject['reject_count'];
														}
														?>
														<span class="percent"><?php echo $assign_reject_count; ?></span>
													</div>
												</div>
												<div class="infobox-data">
													<span class="infobox-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reject</span>
													<div class="infobox-content">
														<span class="bigger-110">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Assignments</span>
													</div>
												</div>
											</div>
										</a>

										<!-- Cancelled -->
										<a href="#" onclick="return handleInfoboxClick('assignments', '5')" class="infobox-link">
											<div class="infobox infobox-orange2">
												<div class="infobox-progress">
													<div class="easy-pie-chart percentage" data-percent="100" data-size="55">
														<?php
														// Get count of assignments with statusid = 5 (Cancelled)
														$assign_cancelled_sql = "SELECT COUNT(*) as cancelled_count 
																	 FROM pm_projecttasktb 
																	 WHERE createdbyid = " . intval($user_id) . "
																	 AND statusid = 5
																	 AND istask = 1";
														$assign_cancelled_result = mysqli_query($conn, $assign_cancelled_sql);
														$assign_cancelled_count = 0;
														
														if ($assign_cancelled_result && mysqli_num_rows($assign_cancelled_result) > 0) {
															$assign_cancelled = mysqli_fetch_assoc($assign_cancelled_result);
															$assign_cancelled_count = $assign_cancelled['cancelled_count'];
														}
														?>
														<span class="percent"><?php echo $assign_cancelled_count; ?></span>
													</div>
												</div>
												<div class="infobox-data">
													<span class="infobox-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cancelled</span>
													<div class="infobox-content">
														<span class="bigger-110">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Assignments</span>
													</div>
												</div>
											</div>
										</a>
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


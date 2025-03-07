<?php
include('../check_session.php');
$moduleid=4;
include('proxy.php');
$conn = connectionDB();

$taskId=$_GET['taskId'];

$myquery = "SELECT pm_projecttasktb.subject,pm_projecttasktb.description,
                    pm_projecttasktb.deadline,pm_projecttasktb.startdate,pm_projecttasktb.enddate,
                    sys_priorityleveltb.priorityname,
                    pm_projecttasktb.assignee,pm_projecttasktb.percentdone,
                    sys_taskclassificationtb.classification,sys_taskstatustb.statusname,
                    sys_usertb.user_firstname,sys_usertb.user_lastname,sys_projecttb.projectname,
                    pm_projecttasktb.classificationid,pm_projecttasktb.statusid,
                    pm_projecttasktb.priorityid,pm_projecttasktb.projectid
            FROM pm_projecttasktb
            LEFT JOIN sys_taskclassificationtb ON sys_taskclassificationtb.id=pm_projecttasktb.classificationid
            LEFT JOIN sys_taskstatustb ON sys_taskstatustb.id=pm_projecttasktb.statusid
            LEFT JOIN sys_priorityleveltb ON sys_priorityleveltb.id=pm_projecttasktb.priorityid
            LEFT JOIN sys_usertb ON sys_usertb.id=pm_projecttasktb.createdbyid
            LEFT JOIN sys_projecttb ON sys_projecttb.id=pm_projecttasktb.projectid
            WHERE pm_projecttasktb.id = '$taskId'";
$myresult = mysqli_query($conn, $myquery);
while($row = mysqli_fetch_assoc($myresult)){
    $myTaskProjectId = $row['projectid'];    
    $myTaskPriorityId = $row['priorityid'];    
    $myTaskStatusId = $row['statusid'];    
    $myTaskClassId = $row['classificationid'];    
    $myTaskSubject = $row['subject'];    
    $myTaskDescription = $row['description'];    
    $myTaskDeadline = $row['deadline'];    
    $myTaskStartdate = $row['startdate'];    
    $myTaskEnddate = $row['enddate'];    
    $myTaskPriorityname = $row['priorityname'];    
    $myTaskAssignee = $row['assignee'];    
    $myTaskPercentage = $row['percentdone'];    
    $myTaskClass = $row['classification'];    
    $myTaskStatus = $row['statusname'];    
    $myTaskCreatedfName = $row['user_firstname'];
    $myTaskCreatedlName = $row['user_lastname'];
    $myTaskProjectname = $row['projectname'];    

    if (($myTaskSubject == '')||($myTaskSubject == null)||($myTaskSubject == "NULL")){$myTaskSubject=NULL;}
    if (($myTaskDescription == '')||($myTaskDescription == null)||($myTaskDescription == "NULL")){$myTaskDescription=NULL;}
    if (($myTaskDeadline == '')||($myTaskDeadline == null)||($myTaskDeadline == "NULL")){$myTaskDeadline="1900-01-01";}
    if (($myTaskStartdate == '')||($myTaskStartdate == null)||($myTaskStartdate == "NULL")){$myTaskStartdate="1900-01-01";}
    if (($myTaskEnddate == '')||($myTaskEnddate == null)||($myTaskEnddate == "NULL")){$myTaskEnddate="1900-01-01";}
    if($myTaskEnddate == "1900-01-01"){$myTaskEnddate = null;}
    if($myTaskDeadline == "1900-01-01"){$myTaskDeadline = null;}
    if($myTaskStartdate == "1900-01-01"){$myTaskStartdate = null;}
    if (($myTaskPriorityname == '')||($myTaskPriorityname == null)||($myTaskPriorityname == "NULL")){$myTaskPriorityname=NULL;}
    if (($myTaskAssignee == '')||($myTaskAssignee == null)||($myTaskAssignee == "NULL")){$myTaskAssignee=NULL;}
    if (($myTaskPercentage == '')||($myTaskPercentage == null)||($myTaskPercentage == "NULL")){$myTaskPercentage=NULL;}
    if (($myTaskClass == '')||($myTaskClass == null)||($myTaskClass == "NULL")){$myTaskClass=NULL;}
    if (($myTaskStatus == '')||($myTaskStatus == null)||($myTaskStatus == "NULL")){$myTaskStatus=NULL;}
    if (($myTaskCreatedfName == '')||($myTaskCreatedfName == null)||($myTaskCreatedfName == "NULL")){$myTaskCreatedfName=NULL;}
    if (($myTaskCreatedlName == '')||($myTaskCreatedlName == null)||($myTaskCreatedlName == "NULL")){$myTaskCreatedlName=NULL;}
    if (($myTaskProjectname == '')||($myTaskProjectname == null)||($myTaskProjectname == "NULL")){$myTaskProjectname=NULL;}
    
    $myTaskCreatedbyName = $myTaskCreatedfName." ,".$myTaskCreatedlName;
}
?>

<!-- jQuery Tags Input -->
<link href="../assets/customjs/jquery.tagsinput.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/chosen.min.css" />
<style>
    .modal-body {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .widget-body {
        padding: 15px;
    }
    
    .form-group {
        margin-bottom: 10px;
    }
    
    textarea {
        min-height: 100px;
    }
</style>
<div class="modal-body">
    <div id="signup-box" class="signup-box widget-box no-border">
        <div class="widget-body">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><small>×</small></button>
            <h4 class="header green lighter bigger">
                <i class="ace-icon fa fa-users blue"></i>
                Manage&nbsp;&nbsp;<?php echo $myTaskSubject;?>
            </h4>
            <p> Enter your details to begin: </p>
            <form id="taskInfoUpdate">
                <input type="hidden" class="form-control" id="taskIdUp2" name="taskIdUp2" value="<?php echo $taskId;?>"/>
                <input type="hidden" class="form-control" id="taskUseridUp2" name="taskUseridUp2" value="<?php echo $userid;?>"/>
                <fieldset>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="block clearfix">Project Owner
                                    <span class="block input-icon input-icon-left">
                                        <select class="form-control" id="projectOwnerUp2" name="projectOwnerUp2">
                                            <?php 
                                                $query66 = "SELECT id,projectname FROM sys_projecttb";
                                                $result66 = mysqli_query($conn, $query66);
                                                while($row66 = mysqli_fetch_assoc($result66)){
                                                    $projectname=mb_convert_case($row66['projectname'], MB_CASE_TITLE, "UTF-8");
                                                    $projectid=$row66['id'];
                                            ?>
                                            <option value="<?php echo $projectid;?>"
                                            <?php 
                                                if ($myTaskProjectId == $projectid){
                                                    echo "selected";
                                                }
                                            ?>><?php echo $projectname;?></option>
                                            <?php } ?>
                                        </select>
                                    </span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="block clearfix">Classification
                                    <span class="block input-icon input-icon-left">
                                        <select class="form-control" id="taskClassificationUp2" name="taskClassificationUp2">
                                            <option value="">Select Classification</option>
                                            <?php 
                                                $query66 = "SELECT id,classification FROM sys_taskclassificationtb";
                                                $result66 = mysqli_query($conn, $query66);
                                                while($row66 = mysqli_fetch_assoc($result66)){
                                                    $classification=mb_convert_case($row66['classification'], MB_CASE_TITLE, "UTF-8");
                                                    $classid=$row66['id'];
                                            ?>
                                            <option value="<?php echo $classid;?>"
                                            <?php 
                                                if ($myTaskClassId == $classid){
                                                    echo "selected";
                                                }
                                            ?>><?php echo $classification;?></option>
                                            <?php } ?>
                                        </select>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="block clearfix">Priority Level
                                    <span class="block input-icon input-icon-left">
                                        <select class="form-control" id="taskPriorityUp2" name="taskPriorityUp2">
                                            <option value="">Select Priority</option>
                                            <?php 
                                                $query66 = "SELECT id,priorityname FROM sys_priorityleveltb";
                                                $result66 = mysqli_query($conn, $query66);
                                                while($row66 = mysqli_fetch_assoc($result66)){
                                                    $priorityname=mb_convert_case($row66['priorityname'], MB_CASE_TITLE, "UTF-8");
                                                    $priorityid=$row66['id'];
                                            ?>
                                            <option value="<?php echo $priorityid;?>"
                                            <?php 
                                                if ($myTaskPriorityId == $priorityid){
                                                    echo "selected";
                                                }
                                            ?>><?php echo $priorityname;?></option>
                                            <?php } ?>
                                        </select>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="block clearfix">Subject
                                    <span class="block input-icon input-icon-left">
                                        <i class="ace-icon fa fa-user"></i>
                                        <input class="form-control" placeholder="taskSubject" id="taskSubjectUp2" name="taskSubjectUp2" value="<?php echo $myTaskSubject;?>"/>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="block clearfix">Assignee
                                    <select multiple="" name="taskAssigneeUp2" id="taskAssigneeUp2" class="chosen-select form-control" data-placeholder="Choose a State...">
                                        <?php 
                                            $query66 = "SELECT id,user_firstname,user_lastname FROM sys_usertb WHERE user_statusid = '1'";
                                            $result66 = mysqli_query($conn, $query66);
                                            while($row66 = mysqli_fetch_assoc($result66)){
                                                $user_firstname=mb_convert_case($row66['user_firstname'], MB_CASE_TITLE, "UTF-8");
                                                $user_lastname=mb_convert_case($row66['user_lastname'], MB_CASE_TITLE, "UTF-8");
                                                $engrid=$row66['id'];
                                                $name = $user_firstname." , ".$user_lastname;
                                        ?>
                                        <option value="<?php echo $engrid;?>"
                                        <?php 
                                            $query66x = "SELECT assigneeid FROM pm_taskassigneetb WHERE taskid = '$taskId'";
                                            $result66x = mysqli_query($conn, $query66x);
                                            while($row66x = mysqli_fetch_assoc($result66x)){
                                                $assigneeid=$row66x['assigneeid'];
                                                if ($assigneeid == $engrid){
                                                    echo "selected";
                                                }
                                            }
                                        ?>><?php echo $name;?></option>
                                        <?php } ?>
                                    </select>
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="block clearfix">Target Date
                                    <span class="block input-icon input-icon-left">
                                        <i class="ace-icon fa fa-calendar"></i>
                                        <input class="form-control date-picker" id="taskTargetDateUp2" name="taskTargetDateUp2" type="text" data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd" value="<?php echo $myTaskDeadline;?>"/>
                                    </span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="block clearfix">Start Date
                                    <span class="block input-icon input-icon-left">
                                        <i class="ace-icon fa fa-calendar"></i>
                                        <input class="form-control date-picker" id="taskStartDateUp2" name="taskStartDateUp2" type="text" data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd" value="<?php echo $myTaskStartdate;?>"/>
                                    </span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="block clearfix">Actual End Date
                                    <span class="block input-icon input-icon-left">
                                        <i class="ace-icon fa fa-calendar"></i>
                                        <input class="form-control date-picker" id="taskEndDateUp2" name="taskEndDateUp2" type="text" data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd" value="<?php echo $myTaskEnddate;?>" disabled />
                                    </span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="block clearfix">Task Status
                                    <span class="block input-icon input-icon-left">
                                        <select class="form-control" id="taskStatus2" name="taskStatus2">
                                            <?php 
                                                $query66 = "SELECT * FROM sys_taskstatustb";
                                                $result66 = mysqli_query($conn, $query66);
                                                while($row66 = mysqli_fetch_assoc($result66)){
                                                    $statusname=mb_convert_case($row66['statusname'], MB_CASE_TITLE, "UTF-8");
                                                    $statusid=$row66['id'];
                                            ?>
                                            <option value="<?php echo $statusid;?>"
                                            <?php 
                                                if ($myTaskStatusId == $statusid){
                                                    echo "selected";
                                                }
                                            ?>><?php echo $statusname;?></option>
                                            <?php } ?>
                                        </select>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="block clearfix">Description
                                    <span class="block input-icon input-icon-left">
                                        <textarea class="ckeditor" id="descriptionupdate2" name="descriptionupdate2"><?php echo trim($myTaskDescription); ?></textarea>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <a class="width-55 pull-right btn btn-sm btn-danger" href="threadPage.php?taskId=<?php echo $taskId; ?>" id="openThread">
                                Open Thread<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="clearfix">
                        <div id="flashtaskup"></div>    
                        <div id="insert_taskup"></div>    
                        <button type="button" class="width-25 pull-right btn btn-sm" id="resetTaskUpBtn" name="resetTaskUpBtn" data-dismiss="modal">
                            <i class="ace-icon fa fa-refresh"></i>
                            <span class="bigger-110">Close</span>
                        </button>

                        <button type="button" class="width-25 pull-right btn btn-sm btn-success" id="submitTaskUptBtn" name="submitTaskUptBtn">
                            <span class="bigger-110">Update</span>
                            <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                        </button>    
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../assets/customjs/projectmanagement.js"></script>
<script src="../assets/customjs/jquery.tagsinput.js"></script>
<script src="../assets/js/chosen.jquery.min.js"></script>
<script src="../assets/ckeditor/ckeditor.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#taskAssigneeUp2').tagsInput({
            width: 'auto'
        });

        // Initialize CKEditor with specific configuration
        CKEDITOR.replace('descriptionupdate2', {
            height: '200px',
            removeButtons: 'Source,Save,NewPage,DocProps,Preview,Print,Templates,document,Cut,Copy,Paste,PasteText,PasteFromWord,Undo,Redo,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Bold,Italic,Underline,Strike,Subscript,Superscript,RemoveFormat,NumberedList,BulletedList,Outdent,Indent,Blockquote,CreateDiv,JustifyLeft,JustifyCenter,JustifyRight,JustifyBlock,BidiLtr,BidiRtl,Link,Unlink,Anchor,CreatePlaceholder,Image,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,InsertPre,Styles,Format,Font,FontSize,TextColor,BGColor,UIColor,Maximize,ShowBlocks,About'
        });

        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        });

        if(!ace.vars['touch']) {
            $('.chosen-select').chosen({allow_single_deselect:true}); 
        }

        // Handle file selection display
        $('#attachFile').on('change', function() {
            const fileInput = this;
            const fileNameDisplay = $('#fileNameDisplay');
            
            if (fileInput.files.length > 10) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Too Many Files',
                    text: 'Maximum 10 files can be uploaded at once',
                    confirmButtonColor: '#3085d6'
                });
                fileInput.value = '';
                fileNameDisplay.text('No files selected').css('color', '#666');
                return;
            }
            
            if (fileInput.files.length > 0) {
                const fileNames = fileInput.files.length >= 4 
                    ? `${fileInput.files.length} files selected`
                    : Array.from(fileInput.files).map(file => file.name).join(', ');
                fileNameDisplay.text(fileNames).css('color', '#333');
            } else {
                fileNameDisplay.text('No files selected').css('color', '#666');
            }
        });

        // Handle file upload
        $('#uploadBtn').click(function() {
            const fileInput = $('#attachFile')[0];
            const files = fileInput.files;
            
            if (files.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Files Selected',
                    text: 'Please select files to upload',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            const formData = new FormData();
            
            for (let i = 0; i < files.length; i++) {
                formData.append('attachFile[]', files[i]);
            }
            
            formData.append('taskId', $('#taskIdUp2').val());
            formData.append('taskSubjectUp2', $('#taskSubjectUp2').val());
            
            Swal.fire({
                title: 'Uploading...',
                text: 'Please wait while we upload your files',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $('#uploadBtn').prop('disabled', true);
            
            $.ajax({
                url: 'upload_file.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Files uploaded successfully',
                            confirmButtonColor: '#3085d6'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Upload Failed',
                            text: response.message || 'Unknown error occurred',
                            confirmButtonColor: '#3085d6'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage = 'An error occurred during upload';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                        }
                    } catch (e) {}
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Failed',
                        text: errorMessage,
                        confirmButtonColor: '#3085d6'
                    });
                },
                complete: function() {
                    $('#uploadBtn').prop('disabled', false);
                }
            });
        });
    });
</script>
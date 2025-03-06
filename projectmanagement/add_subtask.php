<?php 
include('../check_session.php');
$moduleid=4;
$conn = connectionDB();
?>
<!-- CKEditor -->
<script src="../assets/ckeditor/ckeditor.js"></script>
<!-- jQuery Tags Input -->
<link href="../assets/customjs/jquery.tagsinput.css" rel="stylesheet">
<!-- page specific plugin styles -->
<link rel="stylesheet" href="../assets/css/chosen.min.css" />

<div class="modal-body">
    <style>
        /* Add this at the top of the modal-body */
        .modal-dialog {
            width: 90%; /* Adjust this percentage as needed */
            max-width: 800px; /* Optional: set a max-width to prevent it from getting too wide */
        }
    </style>
    <div id="signup-box" class="signup-box widget-box no-border">
        <div class="widget-body">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><small>×</small></button>
            <h4 class="header green lighter bigger">
                <i class="ace-icon fa fa-users blue"></i>
                New Subtask Registration
            </h4>
            <p> Enter your details to begin: </p>
            <form id="subtaskInfo">
                <input type="hidden" class="form-control" id="subtaskUserid" name="subtaskUserid" value="<?php echo $userid;?>"/>
                <input type="hidden" class="form-control" id="parentTaskId" name="parentTaskId" value="<?php echo $_GET['taskId'] ?? ''; ?>"/>
                
                <fieldset>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="block clearfix">Subject
                                    <span class="block input-icon input-icon-left">
                                        <i class="ace-icon fa fa-user"></i>
                                        <input class="form-control" placeholder="subtaskSubject" id="subtaskSubject" name="subtaskSubject" />
                                    </span>
                                </label>
                            </div>  
                        </div>
                        
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="block clearfix">Priority Level
                                    <span class="block input-icon input-icon-left">
                                        <select class="form-control" id="subtaskPriority" name="subtaskPriority">
                                            <option value="">Select Priority</option>
                                            <?php 
                                                $query66 = "SELECT id,priorityname FROM sys_priorityleveltb";
                                                $result66 = mysqli_query($conn, $query66);
                                                while($row66 = mysqli_fetch_assoc($result66)){
                                                    $priorityname=mb_convert_case($row66['priorityname'], MB_CASE_TITLE, "UTF-8");
                                                    $prioritynameid=$row66['id'];
                                            ?>
                                            <option value="<?php echo $prioritynameid;?>"><?php echo $priorityname;?></option>
                                            <?php } ?>
                                        </select>
                                    </span>
                                </label>
                            </div>  
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="block clearfix">Assignee
                                    <span class="block input-icon input-icon-left">
                                        <select name="subtaskAssignee" id="subtaskAssignee" class="form-control">
                                            <option value="">Select Assignee</option>
                                            <?php 
                                                $query66 = "SELECT id,user_firstname,user_lastname FROM sys_usertb WHERE user_statusid = '1' ";
                                                $result66 = mysqli_query($conn, $query66);
                                                while($row66 = mysqli_fetch_assoc($result66)){
                                                    $user_firstname=mb_convert_case($row66['user_firstname'], MB_CASE_TITLE, "UTF-8");
                                                    $user_lastname=mb_convert_case($row66['user_lastname'], MB_CASE_TITLE, "UTF-8");
                                                    $engrid=$row66['id'];
                                                    $name = $user_firstname." , ".$user_lastname;
                                            ?>
                                            <option value="<?php echo $engrid;?>"><?php echo $name;?></option>
                                            <?php } ?>
                                        </select>
                                    </span>
                                </label>
                            </div>  

                            <div class="form-group">
                                <label class="block clearfix">Target Date
                                    <span class="block input-icon input-icon-left">
                                        <i class="ace-icon fa fa-calendar"></i>
                                        <input class="form-control date-picker" id="subtaskTargetDate" name="subtaskTargetDate" type="text" data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd"/>
                                    </span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="block clearfix">Start Date
                                    <span class="block input-icon input-icon-left">
                                        <i class="ace-icon fa fa-calendar"></i>
                                        <input class="form-control date-picker" id="subtaskStartDate" name="subtaskStartDate" type="text" data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd"/>
                                        <input class="form-control date-picker" id="subtaskEndDate" name="subtaskEndDate" type="hidden" data-date-format="yyyy-mm-dd" value="1900-01-01"/>
                                    </span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="block clearfix">Description
                                    <textarea id="subtaskDescription" name="subtaskDescription" rows="5" cols="80"></textarea>
                                </label>
                            </div>  
                        </div>
                    </div>  

                    <div class="clearfix">
                        <div id="flash5"></div>  
                        <div id="insert_search5"></div>  
                        <button type="button" class="width-25 pull-right btn btn-sm" id="resetSubtaskBtn" name="resetSubtaskBtn" data-dismiss="modal">
                            <i class="ace-icon fa fa-refresh"></i>
                            <span class="bigger-110">Close</span>
                        </button>

                        <button type="button" class="width-25 pull-right btn btn-sm btn-success" id="submitSubtaskBtn" name="submitSubtaskBtn">
                            <span class="bigger-110">Register</span>
                            <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                        </button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>

<script src="../assets/customjs/projectmanagement.js"></script>
<script src="../assets/customjs/jquery.tagsinput.js"></script>
<script src="../assets/js/jquery-ui.custom.min.js"></script>
<script src="../assets/js/chosen.jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#subtaskAssignee').tagsInput({
            width: 'auto'
        });
    });
    CKEDITOR.replace('subtaskDescription');
    $('.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true
    })
    
    if(!ace.vars['touch']) {
        $('.chosen-select').chosen({allow_single_deselect:true}); 
        
        $('#chosen-multiple-style .btn').on('click', function(e){
            var target = $(this).find('input[type=radio]');
            var which = parseInt(target.val());
            if(which == 2) $('#form-field-select-4').addClass('tag-input-style');
             else $('#form-field-select-4').removeClass('tag-input-style');
        });
    }

    // Handle form submission
    $('#submitSubtaskBtn').click(function() {
        // Get CKEditor content
        var description = CKEDITOR.instances.subtaskDescription.getData();
        
        // Create FormData object
        var formData = new FormData($('#subtaskInfo')[0]);
        
        // Remove any existing subtaskDescription and add the one from CKEditor
        formData.delete('subtaskDescription');
        formData.append('subtaskDescription', description);

        // For debugging - log the form data
        /*
        for (var pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        */

        // Ajax submission
        $.ajax({
            url: 'save_subtask.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(result) {
                if(result.success) {
                    $('#flash5').html('<div class="alert alert-success">' + result.message + '</div>');
                    setTimeout(function() {
                        $('#modal-subtask').modal('hide');
                        if(typeof loadTasks === 'function') {
                            loadTasks();
                        }
                    }, 1500);
                } else {
                    $('#flash5').html('<div class="alert alert-danger">' + (result.message || 'Unknown error occurred') + '</div>');
                }
            },
            error: function(xhr, status, error) {
                console.log('Status:', status);
                console.log('Error:', error);
                console.log('Response:', xhr.responseText);
                $('#flash5').html('<div class="alert alert-danger">Error submitting form. Please check console for details.</div>');
            }
        });
    });
</script>

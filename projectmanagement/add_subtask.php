<?php 
include('../check_session.php');
$moduleid=4;
$conn = connectionDB();
?>
<!-- Remove duplicate jQuery if it's already loaded in the parent page -->
<!-- Remove duplicate Bootstrap if it's already loaded in the parent page -->

<!-- Essential CSS -->
<link rel="stylesheet" href="../assets/css/chosen.min.css" />
<link href="../assets/customjs/jquery.tagsinput.css" rel="stylesheet">

<style>
    .modal-800 {
        max-width: 800px;
        width: 100%;
        margin: 30px auto;
    }
    /* Add any additional modal styles here */
</style>

<div class="modal-dialog modal-800">
    <div class="modal-content">
        <div class="modal-body">
            <!--<div id="signup-box" class="signup-box widget-box no-border">-->
            <div id="signup-box" class="signup-box widget-box no-border">
                <div class="widget-body">
                    <!--<div class="widget-main">-->
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><small>×</small></button>
                    <h4 class="header green lighter bigger">
                        <i class="ace-icon fa fa-users blue"></i>
                        New Subtask Registration
                    </h4>
                    <p> Enter your details to begin: </p>
                    <form id="subtaskInfo">
                        <input type="hidden" class="form-control" id="subtaskUserid" name="subtaskUserid" value="<?php echo $userid;?>"/>
                        <input type="hidden" class="form-control" id="parentTaskId" name="parentTaskId" value=""/>
                        <fieldset>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="block clearfix">Project Name
                                            <span class="block input-icon input-icon-left">
                                                <select class="form-control" id="subtaskProjectOwner" name="subtaskProjectOwner">
                                                    <option value="">Select Owner</option>
                                                    <?php 
                                                        $query66 = "SELECT id,projectname FROM sys_projecttb WHERE statusid <> 6";
                                                        $result66 = mysqli_query($conn, $query66);
                                                        while($row66 = mysqli_fetch_assoc($result66)){
                                                            $projectname=mb_convert_case($row66['projectname'], MB_CASE_TITLE, "UTF-8");
                                                            $projectid=$row66['id'];
                                                    ?>
                                                    <option value="<?php echo $projectid;?>"><?php echo $projectname;?></option>
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
                                                <select class="form-control" id="subtaskClassification" name="subtaskClassification">
                                                    <option value="">Select Classification</option>
                                                    <?php 
                                                        $query66 = "SELECT id,classification FROM sys_taskclassificationtb";
                                                        $result66 = mysqli_query($conn, $query66);
                                                        while($row66 = mysqli_fetch_assoc($result66)){
                                                            $classification=mb_convert_case($row66['classification'], MB_CASE_TITLE, "UTF-8");
                                                            $classificationid=$row66['id'];
                                                    ?>
                                                    <option value="<?php echo $classificationid;?>"><?php echo $classification;?></option>
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
                                        <label class="block clearfix">Assignee
                                            <span class="block input-icon input-icon-left">
                                                <select multiple="" name="subtaskAssignee" id="subtaskAssignee2" class="chosen-select form-control" id="form-field-select-subtaskAssignee" data-placeholder="Choose a State...">
                                                    <!--<option value="">Select Assignee</option>-->
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
                                    <!--<div class="form-group">
                                        <label class="block clearfix">Assignee
                                            <span class="block input-icon input-icon-left">
                                                <input id="subtaskAssignee" name = "subtaskAssignee" type="text" class="form-control tags" value="" />
                                            </span>
                                        </label>
                                    </div>-->
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
                                                <input class="form-control date-picker" id="subtaskEndDate" name="subtaskEndDate" type="hidden" data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd" value="1900-01-01"/>
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
                                <div id="subtaskFlash"></div>  
                                <div id="subtaskInsertSearch"></div>  
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
                    <!--</div>-->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Essential JavaScript - Make sure these are loaded in the correct order -->
<script>
    // Check if libraries are already loaded
    if (typeof jQuery === 'undefined') {
        document.write('<script src="../assets/js/jquery-2.1.4.min.js"><\/script>');
    }
    
    if (typeof CKEDITOR === 'undefined') {
        document.write('<script src="../assets/ckeditor/ckeditor.js"><\/script>');
    }
</script>

<!-- Load additional required scripts -->
<script src="../assets/customjs/projectmanagement.js"></script>
<script src="../assets/customjs/jquery.tagsinput.js"></script>
<script src="../assets/js/jquery-ui.custom.min.js"></script>
<script src="../assets/js/chosen.jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // Wait for CKEDITOR to be available
        var initCKEditor = function() {
            if (typeof CKEDITOR !== 'undefined') {
                // Destroy existing instance if it exists
                if (CKEDITOR.instances.subtaskDescription) {
                    CKEDITOR.instances.subtaskDescription.destroy();
                }
                
                // Initialize new instance
                CKEDITOR.replace('subtaskDescription', {
                    height: '150px',
                    removePlugins: 'elementspath,resize',
                    toolbarGroups: [
                        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align' ] },
                        { name: 'links' },
                        { name: 'insert' }
                    ]
                });
            } else {
                console.warn('CKEditor not loaded');
            }
        };

        // Initialize CKEditor after a short delay to ensure DOM is ready
        setTimeout(initCKEditor, 100);

        // Initialize other plugins only if they exist
        if ($.fn.tagsInput) {
            $('#subtaskAssignee').tagsInput({
                width: 'auto'
            });
        }

        if ($.fn.datepicker) {
            $('.date-picker').datepicker({
                autoclose: true,
                todayHighlight: true
            });
        }

        if ($.fn.chosen && !ace.vars['touch']) {
            $('.chosen-select').chosen({
                allow_single_deselect: true,
                no_results_text: 'No matches found',
                width: '100%'
            });
        }

        // Form validation
        $('#subtaskInfo').on('submit', function(e) {
            e.preventDefault();
            var form = $(this)[0];
            if (!form.checkValidity()) {
                e.stopPropagation();
                $(form).addClass('was-validated');
                return;
            }
            // Add your form submission logic here
        });

        // Add required validation to important fields
        $('#subtaskProjectOwner, #subtaskClassification, #subtaskPriority, #subtaskSubject, #subtaskAssignee2, #subtaskTargetDate, #subtaskStartDate').attr('required', true);
    });

    // Clean up when modal is closed
    $(document).on('hidden.bs.modal', '#modal-form', function() {
        if (CKEDITOR.instances.subtaskDescription) {
            CKEDITOR.instances.subtaskDescription.destroy();
        }
    });
</script>
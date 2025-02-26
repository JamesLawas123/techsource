function showcreatetask(id2) {
	var id2 = id2;
	// alert(id2);	
												
	/*var searchstring =  '';																
	if(id ==''){searchstring=searchstring;}else{searchstring=searchstring+" "+id;}
	*/																																	
	var dataString = 'id2='+ id2;									
				
	// alert(dataString);
				
	$.ajax({
	type: "GET",
	url: "modal_createtask.php",
	data: dataString,
	cache: false,																							
			  success: function(html)
			    {
				   $("#myModal_createTask").show();
				   $('#myModal_createTask').empty();
				   $("#myModal_createTask").append(html);
											   
			   },
			  error: function(html)
			    {
				   $("#myModal_createTask").show();
				   $('#myModal_createTask').empty();
				   $("#myModal_createTask").append(html);	
				   $("#myModal_createTask").hide();											   												   		
			   }											   
	        });
}

/*====processing of data from form to php==============*/
$(document).ready(function() {
	$('#taskInfo')
	.on('init.field.bv', function(e, data) {	
	var $parent    = data.element.parents('.form-group'),
	    $icon      = $parent.find('.form-control-feedback[data-bv-icon-for="' + data.field + '"]'),
	    options    = data.bv.getOptions(),                      // Entire options
	    validators = data.bv.getOptions(data.field).validators; // The field validators
	
	    if (validators.notEmpty && options.feedbackIcons && options.feedbackIcons.required) {
	        $icon.addClass(options.feedbackIcons.required).show();
	    }
	})   
	.bootstrapValidator({
        message: 'This value is not valid',
        live: 'enabled',
		// submitButtons: 'button[type="button"]',			    
		submitButtons: 'button[name="submitTasktBtn"]',			    
        feedbackIcons: {
        required: 'glyphicon glyphicon-asterisk',
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            taskProjectOwner: {
                validators: {
                    notEmpty: {
                        message: 'The owner is required'
                    }                            
                }
            },
			taskClassification: {
                validators: {
                    notEmpty: {
                        message: 'The classification is required'
                    }                            
                }
            },
			taskPriority: {
                validators: {
                    notEmpty: {
                        message: 'The priority level is required'
                    }                            
                }
            },
			taskSubject: {
                validators: {
                    notEmpty: {
                        message: 'The subject is required'
                    }                            
                }
            },
			taskAssignee: {
                validators: {
                    notEmpty: {
                        message: 'Person assigned is required'
                    }                            
                }
            },
			taskTargetDate: {
                validators: {
                    notEmpty: {
                        message: 'Target Date is required'
                    },
					date: {
						format: 'YYYY-MM-DD',
						message: 'The date is not valid. Should be in YYYY-MM-DD'
					}
                }
            },
			taskStartDate: {
                validators: {
                    notEmpty: {
                        message: 'Start Date is required'
                    },
					date: {
						format: 'YYYY-MM-DD',
						message: 'The date is not valid. Should be in YYYY-MM-DD'
					}
                }
            },
			taskEndDate: {
                validators: {
                    notEmpty: {
                        message: 'Expected date to finish is required'
                    },
					date: {
						format: 'YYYY-MM-DD',
						message: 'The date is not valid. Should be in YYYY-MM-DD'
					}
                }
            }
		}
    })
	.on('success.field.bv', function(e, data) {
         console.log(data.field, data.element, '-->success');
    });
	$('#submitTasktBtn').click(function() {
		$('#taskInfo').bootstrapValidator('validate');
		var bootstrapValidator = $('#taskInfo').data('bootstrapValidator');
		var stat1 = bootstrapValidator.isValid();
		if(stat1=='1')
		{
			var taskProjectOwner = $("#taskProjectOwner").val();	
			var taskUserid = $("#taskUserid").val();	
			var taskClassification = $("#taskClassification").val();	
			var taskPriority = $("#taskPriority").val();	
			var taskSubject = encodeURIComponent($("#taskSubject").val());	
			// var taskAssignee = $("#taskAssignee").chosen().val()
			var taskAssignee2 = $("#taskAssignee2").val();	
			var taskTargetDate = $("#taskTargetDate").val();	
			var taskStartDate = $("#taskStartDate").val();	
			var taskEndDate = $("#taskEndDate").val();		
			var description= CKEDITOR.instances.description.getData();
			var description = encodeURIComponent(description);	
			
			var dataString = 'taskUserid='+ taskUserid
							+'&taskProjectOwner='+ taskProjectOwner
							+'&taskClassification='+ taskClassification
							+'&taskPriority='+ taskPriority
							+'&taskSubject='+ taskSubject
							+'&taskAssignee2='+ taskAssignee2
							+'&taskTargetDate='+ taskTargetDate
							+'&taskStartDate='+ taskStartDate
							+'&taskEndDate='+ taskEndDate
							+'&description='+ description;
			
		
			$.ajax({
				type: "GET",
				url: "saveTask.php",
				data: dataString,
				cache: false,									
						beforeSend: function(html) 
							{			   
								$("#flash5").show();
								$("#flash5").html('<img src="ajax-loader.gif" align="absmiddle">&nbsp;Saving...Please Wait.');				
							},															
						success: function(html)
						    {
								$("#insert_search5").show();
								$('#insert_search5').empty();
								$("#insert_search5").append(html);
								$("#flash5").hide();																		   
						   },
						error: function(html)
						    {
								$("#insert_search5").show();
								$('#insert_search5').empty();
								$("#insert_search5").append(html);
								$("#flash5").hide();													   												   		
						   }											   
			});
		}
	});	
});



//project updateCommands
function showUpdateTask(id2) {
	var id2 = id2;
																																	
	var dataString = 'id2='+ id2;									
				
	// alert(dataString);
				
	$.ajax({
	type: "GET",
	url: "modal_updatetask.php",
	data: dataString,
	cache: false,																							
			  success: function(html)
			    {
				   $("#myModal_updateTask").show();
				   $('#myModal_updateTask').empty();
				   $("#myModal_updateTask").append(html);
											   
			   },
			  error: function(html)
			    {
				   $("#myModal_updateTask").show();
				   $('#myModal_updateTask').empty();
				   $("#myModal_updateTask").append(html);	
				   $("#myModal_updateTask").hide();											   												   		
			   }											   
	        });
}


/*====processing of data from form to php==============*/
$(document).ready(function() {
	$('#taskInfoUpdate')
	.on('init.field.bv', function(e, data) {	
	var $parent    = data.element.parents('.form-group'),
	    $icon      = $parent.find('.form-control-feedback[data-bv-icon-for="' + data.field + '"]'),
	    options    = data.bv.getOptions(),                      // Entire options
	    validators = data.bv.getOptions(data.field).validators; // The field validators
	
	    if (validators.notEmpty && options.feedbackIcons && options.feedbackIcons.required) {
	        $icon.addClass(options.feedbackIcons.required).show();
	    }
	})   
	.bootstrapValidator({
        message: 'This value is not valid',
        live: 'enabled',
		// submitButtons: 'button[type="button"]',			    
		submitButtons: 'button[name="submitTaskUptBtn"]',			    
        feedbackIcons: {
        required: 'glyphicon glyphicon-asterisk',
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            projectOwnerUp2: {
                validators: {
                    notEmpty: {
                        message: 'The owner is required'
                    }                            
                }
            },
			taskClassificationUp2: {
                validators: {
                    notEmpty: {
                        message: 'The classification is required'
                    }                            
                }
            },
			taskPriorityUp2: {
                validators: {
                    notEmpty: {
                        message: 'The priority level is required'
                    }                            
                }
            },
			taskSubjectUp2: {
                validators: {
                    notEmpty: {
                        message: 'The subject is required'
                    }                            
                }
            },
			taskAssigneeUp2: {
                validators: {
                    notEmpty: {
                        message: 'Person assigned is required'
                    }                            
                }
            },
			taskStatus2: {
                validators: {
                    notEmpty: {
                        message: 'Status is required'
                    }                            
                }
            },
			taskTargetDateUp2: {
                validators: {
                    notEmpty: {
                        message: 'Target Date is required'
                    },
					date: {
						format: 'YYYY-MM-DD',
						message: 'The date is not valid. Should be in YYYY-MM-DD'
					}
                }
            },
			taskStartDateUp2: {
                validators: {
                    notEmpty: {
                        message: 'Start Date is required'
                    },
					date: {
						format: 'YYYY-MM-DD',
						message: 'The date is not valid. Should be in YYYY-MM-DD'
					}
                }
            },
			taskEndDateUp2: {
                validators: {
                    notEmpty: {
                        message: 'Expected date to finish is required'
                    },
					date: {
						format: 'YYYY-MM-DD',
						message: 'The date is not valid. Should be in YYYY-MM-DD'
					}
                }
            },
			uploadedFiles: {
                validators: {
                    notEmpty: {
                        message: 'Uploaded files here'
                    }
                }
            }
		}
    })
	.on('success.field.bv', function(e, data) {
         console.log(data.field, data.element, '-->success');
    });
	$('#submitTaskUptBtn').click(function() {
		$('#taskInfoUpdate').bootstrapValidator('validate');
		var bootstrapValidator = $('#taskInfoUpdate').data('bootstrapValidator');
		var stat1 = bootstrapValidator.isValid();
		if(stat1=='1')
		{
			var taskIdUp2 = $("#taskIdUp2").val();	
			var taskUseridUp2 = $("#taskUseridUp2").val();	
			var projectOwnerUp2 = $("#projectOwnerUp2").val();	
			var taskClassificationUp2 = $("#taskClassificationUp2").val();	
			var taskPriorityUp2 = $("#taskPriorityUp2").val();	
			var taskSubjectUp2 = encodeURIComponent($("#taskSubjectUp2").val());	
			var taskAssigneeUp2 = $("#taskAssigneeUp2").val();	
			var taskTargetDateUp2 = $("#taskTargetDateUp2").val();	
			var taskStartDateUp2 = $("#taskStartDateUp2").val();	
			var taskEndDateUp2 = $("#taskEndDateUp2").val();		
			var taskStatus2 = $("#taskStatus2").val();		
			var descriptionupdate2= CKEDITOR.instances.descriptionupdate2.getData();
			var descriptionupdate2 = encodeURIComponent(descriptionupdate2);	
			
			var dataString = 'taskIdUp2='+ taskIdUp2
							+'&taskUseridUp2='+ taskUseridUp2
							+'&projectOwnerUp2='+ projectOwnerUp2
							+'&taskClassificationUp2='+ taskClassificationUp2
							+'&taskPriorityUp2='+ taskPriorityUp2
							+'&taskSubjectUp2='+ taskSubjectUp2
							+'&taskAssigneeUp2='+ taskAssigneeUp2
							+'&taskTargetDateUp2='+ taskTargetDateUp2
							+'&taskStartDateUp2='+ taskStartDateUp2
							+'&taskStatus2='+ taskStatus2
							+'&taskEndDateUp2='+ taskEndDateUp2
							+'&descriptionupdate2='+ descriptionupdate2;
			
			// alert(taskAssignee2);
			$.ajax({
				type: "GET",
				url: "updateTask.php",
				data: dataString,
				cache: false,									
						beforeSend: function(html) 
							{			   
								$("#flashtaskup").show();
								$("#flashtaskup").html('<img src="ajax-loader.gif" align="absmiddle">&nbsp;Saving...Please Wait.');				
							},															
						success: function(html)
						    {
								$("#insert_taskup").show();
								$('#insert_taskup').empty();
								$("#insert_taskup").append(html);
								$("#flashtaskup").hide();																		   
						   },
						error: function(html)
						    {
								$("#insert_taskup").show();
								$('#insert_taskup').empty();
								$("#insert_taskup").append(html);
								$("#flashtaskup").hide();													   												   		
						   }											   
			});
		}
	});	
	//revalidate
	$('#taskInfoUpdate').bootstrapValidator('revalidateField', 'taskStartDateUp2');
	$('#taskInfoUpdate').bootstrapValidator('revalidateField', 'taskTargetDateUp2');
	
});

$('#tabUL a[href="#taskfromticket"]').on('click', function(event){				
	showtasksfromticket();
});

function showtasksfromticket() {
	// alert('test');
	var user_id = $("#user_id").val();	
	var mygroup = $("#mygroup").val();	
	var dataString = 'user_id='+ user_id
					+'&mygroup='+ mygroup;
	$.ajax({
	type: "GET",
	url: "taskfromtickets.php",
	data: dataString,
	cache: false,	
			beforeSend: function(html) 
				{			   
					$("#insert_tickets").show();
					$("#insert_tickets").html('<img src="ajax-loader.gif" align="absmiddle">&nbsp;Loading...Please Wait.');				
				},	
			  success: function(html)
				{
				   $("#insertbody_tickets").show();
				   $('#insertbody_tickets').empty();
				   $("#insertbody_tickets").append(html);
				   $("#insert_tickets").hide();				   			   		  
			   },
			  error: function(html)
				{
				   $("#insertbody_tickets").show();
				   $('#insertbody_tickets').empty();
				   $("#insertbody_tickets").append(html);	
				   $("#insertbody_tickets").hide();	
				   $("#insert_tickets").hide();			   					   		
			   }						   
	});
}

$('#tabUL a[href="#taskfrompmdone"]').on('click', function(event){				
	showtasksfrompmdone();
});

function showtasksfrompmdone() {
	// alert('test');
	$.ajax({
	type: "GET",
	url: "assignmentdone.php",
	cache: false,	
			beforeSend: function(html) 
				{			   
					$("#insert_assignmentdone").show();
					$("#insert_assignmentdone").html('<img src="ajax-loader.gif" align="absmiddle">&nbsp;Loading...Please Wait.');				
				},	
			  success: function(html)
				{
				   $("#insertbody_assignmentdone").show();
				   $('#insertbody_assignmentdone').empty();
				   $("#insertbody_assignmentdone").append(html);
				   $("#insert_assignmentdone").hide();				   			   		  
			   },
			  error: function(html)
				{
				   $("#insertbody_assignmentdone").show();
				   $('#insertbody_assignmentdone').empty();
				   $("#insertbody_assignmentdone").append(html);	
				   $("#insertbody_assignmentdone").hide();	
				   $("#insert_assignmentdone").hide();			   					   		
			   }						   
	});
}


$('#tabUL a[href="#taskfromticketdone"]').on('click', function(event){				
	showtasksticketdone();
});

function showtasksticketdone() {
	// alert('test');
	$.ajax({
	type: "GET",
	url: "taskfromticketsdone.php",
	cache: false,	
			beforeSend: function(html) 
				{			   
					$("#insert_taskdone").show();
					$("#insert_taskdone").html('<img src="ajax-loader.gif" align="absmiddle">&nbsp;Loading...Please Wait.');				
				},	
			  success: function(html)
				{
				   $("#insertbody_taskdone").show();
				   $('#insertbody_taskdone').empty();
				   $("#insertbody_taskdone").append(html);
				   $("#insert_taskdone").hide();				   			   		  
			   },
			  error: function(html)
				{
				   $("#insertbody_taskdone").show();
				   $('#insertbody_taskdone').empty();
				   $("#insertbody_taskdone").append(html);	
				   $("#insertbody_taskdone").hide();	
				   $("#insert_taskdone").hide();			   					   		
			   }						   
	});
}

$('#tabUL a[href="#taskfrompmreject"]').on('click', function(event){				
	showtasksfrompmreject();
});

function showtasksfrompmreject() {
	// alert('test');
	$.ajax({
	type: "GET",
	url: "assignmentreject.php",
	cache: false,	
			beforeSend: function(html) 
				{			   
					$("#insert_taskreject").show();
					$("#insert_taskreject").html('<img src="ajax-loader.gif" align="absmiddle">&nbsp;Loading...Please Wait.');				
				},	
			  success: function(html)
				{
				   $("#insertbody_taskreject").show();
				   $('#insertbody_taskreject').empty();
				   $("#insertbody_taskreject").append(html);
				   $("#insert_taskreject").hide();				   			   		  
			   },
			  error: function(html)
				{
				   $("#insertbody_taskreject").show();
				   $('#insertbody_taskreject').empty();
				   $("#insertbody_taskreject").append(html);	
				   $("#insertbody_taskreject").hide();	
				   $("#insert_taskreject").hide();			   					   		
			   }						   
	});
}

$('#tabUL a[href="#taskfromticketreject"]').on('click', function(event){				
	showtasksticketreject();
});

function showtasksticketreject() {
	// alert('test');
	$.ajax({
	type: "GET",
	url: "taskfromticketsreject.php",
	cache: false,	
			beforeSend: function(html) 
				{			   
					$("#insert_ticketreject").show();
					$("#insert_ticketreject").html('<img src="ajax-loader.gif" align="absmiddle">&nbsp;Loading...Please Wait.');				
				},	
			  success: function(html)
				{
				   $("#insertbody_ticketreject").show();
				   $('#insertbody_ticketreject').empty();
				   $("#insertbody_ticketreject").append(html);
				   $("#insert_ticketreject").hide();				   			   		  
			   },
			  error: function(html)
				{
				   $("#insertbody_ticketreject").show();
				   $('#insertbody_ticketreject').empty();
				   $("#insertbody_ticketreject").append(html);	
				   $("#insertbody_ticketreject").hide();	
				   $("#insert_ticketreject").hide();			   					   		
			   }						   
	});
}


/*
#open thread
*/
function showopenThread(id2) {
	var id2 = id2;
																																	
	var dataString = 'id2='+ id2;									
				
	// alert(dataString);
				
	$.ajax({
	type: "GET",
	url: "modal_threads.php",
	data: dataString,
	cache: false,																							
			  success: function(html)
			    {
				   $("#insert_openThread").show();
				   $('#insert_openThread').empty();
				   $("#insert_openThread").append(html);
											   
			   },
			  error: function(html)
			    {
				   $("#insert_openThread").show();
				   $('#insert_openThread').empty();
				   $("#insert_openThread").append(html);	
				   $("#insert_openThread").hide();											   												   		
			   }											   
	        });
}



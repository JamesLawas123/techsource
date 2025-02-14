function showcreateticket(id2) {
	var id2 = id2;
	// alert(id2);	
												
	/*var searchstring =  '';																
	if(id ==''){searchstring=searchstring;}else{searchstring=searchstring+" "+id;}
	*/																																	
	var dataString = 'id2='+ id2;									
				
	// alert(dataString);
				
	$.ajax({
	type: "GET",
	url: "modal_createticket.php",
	data: dataString,
	cache: false,																							
			  success: function(html)
			    {
				   $("#myModal_createTicket").show();
				   $('#myModal_createTicket').empty();
				   $("#myModal_createTicket").append(html);
											   
			   },
			  error: function(html)
			    {
				   $("#myModal_createTicket").show();
				   $('#myModal_createTicket').empty();
				   $("#myModal_createTicket").append(html);	
				   $("#myModal_createTicket").hide();											   												   		
			   }											   
	        });
}

/*====processing of data from form to php==============*/
$(document).ready(function() {
	$('#ticketInfo')
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
		submitButtons: 'button[name="submitTicketBtn"]',			    
        feedbackIcons: {
        required: 'glyphicon glyphicon-asterisk',
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            ticketProjectOwner: {
                validators: {
                    notEmpty: {
                        message: 'The owner is required'
                    }                            
                }
            },
			ticketClassification: {
                validators: {
                    notEmpty: {
                        message: 'The classification is required'
                    }                            
                }
            },
			ticketPriority: {
                validators: {
                    notEmpty: {
                        message: 'The priority level is required'
                    }                            
                }
            },
			ticketSubject: {
                validators: {
                    notEmpty: {
                        message: 'The subject is required'
                    }                            
                }
            },
			ticketTargetDate: {
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
			description: {
                validators: {
                    notEmpty: {
                        message: 'The description is required'
                    }                            
                }
            }
		}
    })
	.on('success.field.bv', function(e, data) {
         console.log(data.field, data.element, '-->success');
    });
	$('#submitTicketBtn').click(function() {
		$('#ticketInfo').bootstrapValidator('validate');
		var bootstrapValidator = $('#ticketInfo').data('bootstrapValidator');
		var stat1 = bootstrapValidator.isValid();
		if(stat1=='1')
		{
			var ticketUserid = $("#ticketUserid").val();	
			var ticketProjectOwner = $("#ticketProjectOwner").val();	
			var ticketClassification = $("#ticketClassification").val();	
			var ticketPriority = $("#ticketPriority").val();	
			var ticketSubject = encodeURIComponent($("#ticketSubject").val());	
			var ticketTargetDate = $("#ticketTargetDate").val();	
			var description= CKEDITOR.instances.description.getData();
			var description = encodeURIComponent(description);	
			
			var dataString = 'ticketUserid='+ ticketUserid
							+'&ticketProjectOwner='+ ticketProjectOwner
							+'&ticketClassification='+ ticketClassification
							+'&ticketPriority='+ ticketPriority
							+'&ticketSubject='+ ticketSubject
							+'&ticketTargetDate='+ ticketTargetDate
							+'&description='+ description;
			
			// alert(taskAssignee2);
			$.ajax({
				type: "GET",
				url: "saveticket.php",
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
function showUpdateTicket(id2) {
	var id2 = id2;
																																	
	var dataString = 'id2='+ id2;									
				
	// alert(dataString);
				
	$.ajax({
	type: "GET",
	url: "modal_updateticket.php",
	data: dataString,
	cache: false,																							
			  success: function(html)
			    {
				   $("#myModal_updateTicket").show();
				   $('#myModal_updateTicket').empty();
				   $("#myModal_updateTicket").append(html);
											   
			   },
			  error: function(html)
			    {
				   $("#myModal_updateTicket").show();
				   $('#myModal_updateTicket').empty();
				   $("#myModal_updateTicket").append(html);	
				   $("#myModal_updateTicket").hide();											   												   		
			   }											   
	        });
}


/*====processing of data from form to php==============*/
$(document).ready(function() {
	$('#ticketInfoUpdate')
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
		submitButtons: 'button[name="submitTicketUptBtn"]',			    
        feedbackIcons: {
        required: 'glyphicon glyphicon-asterisk',
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            ticketStatus2: {
                validators: {
                    notEmpty: {
                        message: 'The status is required'
                    }                            
                }
            },
			descriptionupdate2: {
                validators: {
                    notEmpty: {
                        message: 'The description is required'
                    }                            
                }
            }
		}
    })
	.on('success.field.bv', function(e, data) {
         console.log(data.field, data.element, '-->success');
    });
	$('#submitTicketUptBtn').click(function() {
		$('#ticketInfoUpdate').bootstrapValidator('validate');
		var bootstrapValidator = $('#ticketInfoUpdate').data('bootstrapValidator');
		var stat2 = bootstrapValidator.isValid();
		if(stat2=='1')
		{
			var ticketIdUp2 = $("#ticketIdUp2").val();	
			var ticketUseridUp2 = $("#ticketUseridUp2").val();	
			var ticketSubjectUp2 = encodeURIComponent($("#ticketSubjectUp2").val());	
			var ticketStatus2 = $("#ticketStatus2").val();	
			var descriptionupdate2= CKEDITOR.instances.descriptionupdate2.getData();
			var descriptionupdate2 = encodeURIComponent(descriptionupdate2);	
			
			var dataString = 'ticketIdUp2='+ ticketIdUp2
							+'&ticketUseridUp2='+ ticketUseridUp2
							+'&ticketSubjectUp2='+ ticketSubjectUp2
							+'&ticketStatus2='+ ticketStatus2
							+'&descriptionupdate2='+ descriptionupdate2;
			
			// alert(taskAssignee2);
			$.ajax({
				type: "GET",
				url: "updateticket.php",
				data: dataString,
				cache: false,									
						beforeSend: function(html) 
							{			   
								$("#flashticketup").show();
								$("#flashticketup").html('<img src="ajax-loader.gif" align="absmiddle">&nbsp;Saving...Please Wait.');				
							},															
						success: function(html)
						    {
								$("#insert_ticketup").show();
								$('#insert_ticketup').empty();
								$("#insert_ticketup").append(html);
								$("#flashticketup").hide();																		   
						   },
						error: function(html)
						    {
								$("#insert_ticketup").show();
								$('#insert_ticketup').empty();
								$("#insert_ticketup").append(html);
								$("#flashticketup").hide();													   												   		
						   }											   
			});
		}
	});	
});


$('#tabUL a[href="#ticketprogress"]').on('click', function(event){				
	showticketprogress();
});

function showticketprogress() {
	// alert('test');
	$.ajax({
	type: "GET",
	url: "ticketinprogress.php",
	cache: false,	
			beforeSend: function(html) 
				{			   
					$("#insert_ticketprocess").show();
					$("#insert_ticketprocess").html('<img src="ajax-loader.gif" align="absmiddle">&nbsp;Loading...Please Wait.');				
				},	
			  success: function(html)
				{
				   $("#insertbody_ticketprocess").show();
				   $('#insertbody_ticketprocess').empty();
				   $("#insertbody_ticketprocess").append(html);
				   $("#insert_ticketprocess").hide();				   			   		  
			   },
			  error: function(html)
				{
				   $("#insertbody_ticketprocess").show();
				   $('#insertbody_ticketprocess').empty();
				   $("#insertbody_ticketprocess").append(html);	
				   $("#insertbody_ticketprocess").hide();	
				   $("#insert_ticketprocess").hide();			   					   		
			   }						   
	});
}

$('#tabUL a[href="#ticketpending"]').on('click', function(event){				
	showticketpending();
});

function showticketpending() {
	// alert('test');
	$.ajax({
	type: "GET",
	url: "ticketpending.php",
	cache: false,	
			beforeSend: function(html) 
				{			   
					$("#insert_ticketpending").show();
					$("#insert_ticketpending").html('<img src="ajax-loader.gif" align="absmiddle">&nbsp;Loading...Please Wait.');				
				},	
			  success: function(html)
				{
				   $("#insertbody_ticketpending").show();
				   $('#insertbody_ticketpending').empty();
				   $("#insertbody_ticketpending").append(html);
				   $("#insert_ticketpending").hide();				   			   		  
			   },
			  error: function(html)
				{
				   $("#insertbody_ticketpending").show();
				   $('#insertbody_ticketpending').empty();
				   $("#insertbody_ticketpending").append(html);	
				   $("#insertbody_ticketpending").hide();	
				   $("#insert_ticketpending").hide();			   					   		
			   }						   
	});
}

$('#tabUL a[href="#ticketreject"]').on('click', function(event){				
	showticketrejected();
});

function showticketrejected() {
	// alert('test');
	$.ajax({
	type: "GET",
	url: "ticketrejected.php",
	cache: false,	
			beforeSend: function(html) 
				{			   
					$("#insert_ticketrejected").show();
					$("#insert_ticketrejected").html('<img src="ajax-loader.gif" align="absmiddle">&nbsp;Loading...Please Wait.');				
				},	
			  success: function(html)
				{
				   $("#insertbody_ticketrejected").show();
				   $('#insertbody_ticketrejected').empty();
				   $("#insertbody_ticketrejected").append(html);
				   $("#insert_ticketrejected").hide();				   			   		  
			   },
			  error: function(html)
				{
				   $("#insertbody_ticketrejected").show();
				   $('#insertbody_ticketrejected').empty();
				   $("#insertbody_ticketrejected").append(html);	
				   $("#insertbody_ticketrejected").hide();	
				   $("#insert_ticketrejected").hide();			   					   		
			   }						   
	});
}


$('#tabUL a[href="#ticketcancelled"]').on('click', function(event){				
	showticketcancelled();
});

function showticketcancelled() {
	// alert('test');
	$.ajax({
	type: "GET",
	url: "ticketcancelled.php",
	cache: false,	
			beforeSend: function(html) 
				{			   
					$("#insert_ticketcancelled").show();
					$("#insert_ticketcancelled").html('<img src="ajax-loader.gif" align="absmiddle">&nbsp;Loading...Please Wait.');				
				},	
			  success: function(html)
				{
				   $("#insertbody_ticketcancelled").show();
				   $('#insertbody_ticketcancelled').empty();
				   $("#insertbody_ticketcancelled").append(html);
				   $("#insert_ticketcancelled").hide();				   			   		  
			   },
			  error: function(html)
				{
				   $("#insertbody_ticketcancelled").show();
				   $('#insertbody_ticketcancelled').empty();
				   $("#insertbody_ticketcancelled").append(html);	
				   $("#insertbody_ticketcancelled").hide();	
				   $("#insert_ticketcancelled").hide();			   					   		
			   }						   
	});
}

$('#tabUL a[href="#ticketdone"]').on('click', function(event){				
	showticketdone();
});

function showticketdone() {
	// alert('test');
	$.ajax({
	type: "GET",
	url: "ticketdone.php",
	cache: false,	
			beforeSend: function(html) 
				{			   
					$("#insert_ticketdone").show();
					$("#insert_ticketdone").html('<img src="ajax-loader.gif" align="absmiddle">&nbsp;Loading...Please Wait.');				
				},	
			  success: function(html)
				{
				   $("#insertbody_ticketdone").show();
				   $('#insertbody_ticketdone').empty();
				   $("#insertbody_ticketdone").append(html);
				   $("#insert_ticketdone").hide();				   			   		  
			   },
			  error: function(html)
				{
				   $("#insertbody_ticketdone").show();
				   $('#insertbody_ticketdone').empty();
				   $("#insertbody_ticketdone").append(html);	
				   $("#insertbody_ticketdone").hide();	
				   $("#insert_ticketdone").hide();			   					   		
			   }						   
	});
}


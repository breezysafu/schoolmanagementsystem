	$(document).ready(function(){
		$('#wp-end-time').timepicker({
			 showInputs: false,
			 showMeridian:false,
			 defaultTime: null
		});
		$('#timepicker1').timepicker({
			showInputs: false,
			showMeridian:false,
			defaultTime: null
		});
		 
			
		$("#SettingsSocialForm,#SettingsMgmtForm,#SettingsGradeForm,#paytm_setting_form,#sms_settings_form, #payment_settings_form").submit(function(e) {			
			
			e.preventDefault();
			$( "#overlay" ).addClass( "overlays" );
			$('.pnloader').remove();
			var formval = document.getElementById("displaypicture");
			
			var data 	=	new FormData();
			var fdata	=	$('#SettingsInfoForm').serializeArray();
			var file1	=	$('#displaypicture')[0].files[0];
			
			data.append('action', 'GenSetting');
			data.append('displaypicture',file1);
			$.each(fdata,function(key,input){
				data.append(input.name,input.value);
			});
			
			data.append('data',fdata);
			
			jQuery.ajax({
				type:"POST",
				url:ajax_url,
				data:data,
				cache: false,
				processData: false,
				contentType: false,	
				beforeSend:function() {
					$.fn.notify('loader',{'desc':'Saving settings..'});
					$( "#overlay" ).addClass( "overlays" );	
					$('#setting_submit').attr('disabled',true);	
				
				},
				success:function(ires) {
					$('#setting_submit').attr('disabled',false);	
					if(ires=='success'){
						var pntype='success';
						var pntext="Information Saved Successfully";
						window.location.reload();
					} else {
						var pntype='error';
						var pntext= ires=='' ? "Something went wrong" : ires;
					}
					//$.fn.notify(pntype,{'desc':pntext});
					$.fn.notify(pntype, {'desc': pntext, autoHide: true, clickToHide: true });
					
				},
				complete:function(){
					$('.pnloader').remove();
					$("#overlay").removeClass("overlays");    
				}
			});

		});
		
		$('#AddGradeForm').validate({
			//e.preventDefault();
			rules: {
				grade_name: { required: true },
				grade_point:{ required: true },
				mark_from: { required: true },
				mark_upto: { required: true },
			},
			messages: {
				grade_name: "Please Enter Grade Name",
				grade_point: "Please Enter Grade Point",
				mark_from : "Please Enter Mark From",
				mark_upto: "Please Enter Mark Upto",
			},
			submitHandler: function(form) {				
				var fdata	=	$('#AddGradeForm').serializeArray();				
				fdata.push({name: 'action', value: 'manageGrade'});				
				jQuery.ajax({
					method:"POST",
					url:ajax_url, 
					data:fdata,
					beforeSend:function(){
						$.fn.notify('loader',{'desc':'Saving grade..'});
						$('#grade_save').attr("disabled", 'disabled');	
					},
					success:function(ires) {
						$('#grade_save').removeAttr('disabled');
						$('#AddGradeForm').trigger("reset");
						if(ires=='success'){
							var pntype='success';
							var pntext="Grade Saved Successfully";
						}
						else{
							var pntype='error';
							var pntext="Something went wrong";
						}
						$.fn.notify(pntype, {'desc': pntext, autoHide: true, clickToHide: true });
						//$.fn.notify(pntype,{'desc':pntext});
					},
					complete:function(){
						$('#grade_save').removeAttr('disabled');
						$('.pnloader').remove();
						$('#AddGradeForm').trigger("reset");
					}
				});
			}
		});

		
			

		
        $('#displaypicture').change(function(){
        	
        	var reader = new FileReader();
        	reader.onload = function (e) {
    			// get loaded data and render thumbnail.
        		$("#image").attr({src:e.target.result,width:150,height:150});
        		$('.sch-remove-logo').show();
        		$('.sch-logo-container').show();
    		};
    		reader.readAsDataURL(this.files[0]);  
        
    });
  
		$('.sch-remove-logo').click( function(e){
			$('#sch_logo_control').val('');
			//$("#image").val('');
			$('.sch-logo-container').hide();
			$('.sch-remove-logo').hide();
			$('.logo-label').html( 'Upload Logo');
		});
		$('#wpsp_grade_list, #wpsp_sub_division_table,#wpsp_class_hours').dataTable({
			"order": [],
			"columnDefs": [ {
				  "targets"  : 'nosort',
				  "orderable": false,
			}],
			responsive: true,
		});
	
		$('.DeleteGrade').click(function(){
			var gid=$(this).attr('data-id');
			var gdata=new Array();
			gdata.push({name: 'action', value: 'manageGrade'},{name: 'grade_id', value: gid},{name: 'actype', value: 'delete'});
			jQuery.ajax({
				method:"POST",
				url:ajax_url, 
				data:gdata, 
				success:function(gres) {
					if(gres=='success'){
						$.fn.notify('success', {'desc': 'Grade deleted succesfully!', autoHide: true, clickToHide: true });
						window.location.reload();
					}else
					$.fn.notify('error', {'desc':gres, autoHide: true, clickToHide: true });	
						},
				error:function(){
					$.fn.notify('error', {'desc':'Something went wrong', autoHide: true, clickToHide: true });	
									},
				beforeSend:function(){
					$.fn.notify('loader',{'desc':'Deleting grade..'});
				},
				
				complete:function(){
					$('.pnloader').remove();
				}
			});			
		});
		
		$('#SubFieldsClass').change(function(){
			$('#SubFieldSubject option:gt(0)').remove();
			var sfdata=new Array();
			var cid=$(this).val();
			sfdata.push({name: 'action', value: 'subjectList'},{name: 'ClassID', value: cid});
			jQuery.ajax({
				method:"POST",
				url:ajax_url, 
				data:sfdata, 
				success:function(sfres) {
					var newOptions=$.parseJSON(sfres);
					var $el = $("#SubFieldSubject");					$.each( newOptions.subject,function(field,value) {	
						$el.append($("<option></option>").attr("value", value.id).text(value.sub_name)); 
					});
				},
				error:function(){
					$.fn.notify('error', {'desc':'Something went wrong', autoHide: true, clickToHide: true });	
					},
				beforeSend:function(){
					$.fn.notify('loader',{'desc':'Loading Subjects'});
				},
				complete:function(){
					PNotify.removeAll();
				}
			});
		});
		$('input[type=radio][name=sch_sms_provider]').change(function() {
			var value = this.value;
			$( '.sms_setting_div' ).hide();
			$( '#sms_main_'+value ).show();
		});
		  $("#SubFieldsForm").validate({
        rules: {
            ClassID: {
                required: true,
               
            },
            SubjectID: {
                 required: true,
            },
            FieldName: {
                required: true
               
            }
          },

        messages: {

            ClassID: "Please Select class name",
            SubjectID: "Please Select Subject Name",
            FieldName: "Please enter Field Name"
        },
        /*});*/
		/*$('#SubFieldsForm').submit(function(e){*/
			  submitHandler: function(form) {
			//e.preventDefault();
			 var sfdata = $('#SubFieldsForm').serializeArray();
			//var sfdata=$(this).serializeArray();
			sfdata.push({name: 'action', value: 'addSubField'});
			jQuery.ajax({
				method:"POST",
				url:ajax_url, 
				data:sfdata, 
				success:function(sfres) {
					if(sfres=='success'){
						$('#SubFieldsForm')[0].reset();
						$.fn.notify('success', {'desc': 'Fields added succesfully!', autoHide: true, clickToHide: true });
						
						 $('#AddFieldsModal').html('');
                    $('#AddFieldsModal').modal('hide');
					var delay = 1000;
						 setTimeout(function() {
						location.reload(true);
						}, delay);
						$('#SubFieldsForm .btn-primary').attr('disabled','disabled');
					}else
						$.fn.notify('error', {'desc': sfres, autoHide: true, clickToHide: true });				
						$('#SubFieldsForm .btn-primary').attr('disabled',false);
				},
				error:function(){
					//$.fn.notify('error',{'desc':'Something went wrong'});
					$.fn.notify('error', {'desc': 'Something went wrong', autoHide: true, clickToHide: true });
				},
				beforeSend:function(){
					$.fn.notify('loader',{'desc':'Saving Fields'});
					//$('#SubFieldsForm .btn-primary').attr('disabled','disabled');
				},
				complete:function(){
					$('.pnloader').remove();
				}
			});
		   }
		});
		
		//Subject Fields Update Function
		
		$('.SFUpdate').click(function(){
			var sfid=$(this).attr('data-id');
			var field=$("#"+sfid+"SF").val();
			var sfdata=new Array();
			sfdata.push({name: 'action', value: 'updateSubField'},{name: 'sfid', value: sfid},{name: 'field', value: field});
			jQuery.ajax({
				method:"POST",
				url:ajax_url, 
				data:sfdata, 
				success:function(sfres) {
					if(sfres=='success'){
						$.fn.notify('success', {'desc': 'Field updated succesfully!', autoHide: true, clickToHide: true });
						
						var  wpsp_pageURL= $('#wpsp_locationginal').val();
										console.log(wpsp_pageURL);
										var delay = 1000;
										var url =  wpsp_pageURL+"/admin.php?page=sch-settings&sc=subField";
										var timeoutID = setTimeout(function() {
										window.location.href = url;
										}, delay);
					}else
						$.fn.notify('error', {'desc': sfres, autoHide: true, clickToHide: true });				
				},
				error:function(){
					$.fn.notify('error', {'desc': 'Something went wrong', autoHide: true, clickToHide: true });
					//$.fn.notify('error',{'desc':'Something went wrong'});
				},
				beforeSend:function(){
					$.fn.notify('loader',{'desc':'Saving Fields'});
				},
				complete:function(){
					$('.pnloader').remove();
				}
			});
		});
		//Sub Field delete function
		$('.SFDelete').click(function(){
			var sfid=$(this).attr('data-id');
			$( "#overlay" ).addClass("overlays");
			  new PNotify({
                    title: 'Confirmation Needed',
                    text: 'Are you sure want to delete?',
                    icon: 'glyphicon glyphicon-question-sign',
                    hide: false,
                    confirm: {
                        confirm: true
                    },
                    buttons: {
                        closer: false,
                        sticker: false
                    },
                    history: {
                        history: false
                    },
                }).get().on('pnotify.confirm', function(){ 
					var sfdata=new Array();
					sfdata.push({name: 'action', value: 'deleteSubField'},{name: 'sfid', value: sfid});
					jQuery.ajax({
						method:"POST",
						url:ajax_url, 
						data:sfdata, 
						success:function(sfres) {
							if(sfres=='success'){
								$.fn.notify('success', {'desc': 'Field deleted succesfully!', autoHide: true, clickToHide: true });
								//window.location.reload();
								var  wpsp_pageURL= $('#wpsp_locationginal').val();
										console.log(wpsp_pageURL);
										var delay = 1000;
										var url =  wpsp_pageURL+"/admin.php?page=sch-settings&sc=subField";
										var timeoutID = setTimeout(function() {
										window.location.href = url;
										}, delay);
							}else
								$.fn.notify('error', {'desc': sfres, autoHide: true, clickToHide: true });
														},
						error:function(){
							$.fn.notify('error', {'desc': 'Something went wrong', autoHide: true, clickToHide: true });
						},
						beforeSend:function(){
							$.fn.notify('loader',{'desc':'Deleting Fields'});
						},
						complete:function(){
							$('.pnloader').remove();
						}
					});
                }).on('pnotify.cancel', function() {
                    $("#overlay").removeClass("overlays");    
				});
		});
	});

    //Setting info Fields Validation

    $("#SettingsInfoForm").validate({
       rules: {
            sch_email: "required",
            sch_name: "required",
            sch_addr: "required",
            sch_city: "required",
            sch_state: "required",
            sch_country: "required",
            sch_pno: {
				required: true,
				number: true,
				minlength: 7
               
			}

        },
        messages: {
            sch_email: "Please enter a valid email address",
            sch_name: "Please enter a school name",
            sch_addr: "Please enter a school address",
            sch_city: "Please enter a schoool city",
            sch_state: "Please enter a school state",
            sch_country: "Please enter a school country", 
            sch_pno: {
                required: "Please enter a school phone number",
                minlength: "Password must be at least 7 characters long",
                number: "Please enter a valid school phone number",
            }

        },

        submitHandler: function (form) {
			var formval = document.getElementById("displaypicture");
			var data 	=	new FormData();
			var fdata	=	$('#SettingsInfoForm').serializeArray();
			var file1	=	$('#displaypicture')[0].files[0];
			
			data.append('action', 'GenSetting');
			data.append('displaypicture',file1);
			$.each(fdata,function(key,input){
				data.append(input.name,input.value);
			});
			
			data.append('data',fdata);
				jQuery.ajax({
				type:"POST",
				url:ajax_url,
				data:data,
				cache: false,
				processData: false,
				contentType: false,	
				beforeSend:function() {
					$.fn.notify('loader',{'desc':'Saving settings..'});
					$( "#overlay" ).addClass( "overlays" );	
					$('#setting_submit').attr('disabled',true);	
				
				},
				success:function(ires) {
					$('#setting_submit').attr('disabled',false);	
					if(ires=='success'){
						var pntype='success';
						var pntext="Information Saved Successfully";
						window.location.reload();
					} else {
						var pntype='error';
						var pntext= ires=='' ? "Something went wrong" : ires;
					}
					//$.fn.notify(pntype,{'desc':pntext});
					$.fn.notify(pntype, {'desc': pntext, autoHide: true, clickToHide: true });
					
				},
				complete:function(){
					$('.pnloader').remove();
					$("#overlay").removeClass("overlays");    
				}
			});
		}        
    });





			
			
			
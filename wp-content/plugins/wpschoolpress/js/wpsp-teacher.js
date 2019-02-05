	$(document).ready(function(){
		var table	=	$('#teacher_table').dataTable({
			"order": [],
			"columnDefs": [ {
			  "targets"  : 'nosort',
			  "orderable": false,
			}],
			responsive: true,
		});
		$('.dropdown-menu').click(function(e) {
			e.stopPropagation();
		});
		
		$("#Dob").datepicker({
			autoclose: true,
			dateFormat: date_format,
			todayHighlight: true,
			changeMonth: true,
			changeYear: true,
			maxDate: 0,
			 yearRange: "-50:+0",
		});
		
		
		$( "#Doj" ).datepicker({
			autoclose: true,
			dateFormat: date_format,
			todayHighlight: true,
    		changeMonth: true,
            changeYear: true,
			maxDate: 0,
			beforeShow: function(input, inst) {
				$(document).off('focusin.bs.modal');
			},
			onClose:function(){
				$(document).on('focusin.bs.modal');
			},
			onSelect: function( selectedDate ) {
				$( ".Dol" ).datepicker( "option", "minDate", selectedDate );
			}
  		});
		
		$( "#Dol" ).datepicker({
			autoclose: true,
			dateFormat: date_format, 
			todayHighlight: true,  
			changeMonth: true,
			changeYear: true,
			beforeShow: function(input, inst) {
				$(document).off('focusin.bs.modal');
			},
			onClose:function(){
				$(document).on('focusin.bs.modal');
			},
			onSelect: function( selectedDate ) {
				$( ".Doj" ).datepicker( "option", "maxDate", selectedDate );
			}
  		});
		
		
		
		$('#ClassID').change(function () {
			$('#TeacherClass').submit();
		});
	
		$("#displaypicture").change(function(){	
			$('#test').html('');	
			var fsize = document.getElementById("displaypicture").files[0].size;
			var fileName = $(this).val();
			var maxsize = 3 * 1024 * 1024; //3145728			
			if( fsize > maxsize ) {
				$('#test').html( 'File Size should be less than 3 MB, Please select another file');
				$(this).val('');
			}	
			var fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
			if($.inArray(fileExtension, ['jpg','jpeg']) == -1) { 
				$('#test').html( 'Please select either jpg or jpeg file');
				$(this).val('');
			}
		});

	$("#TeacherEditForm").validate({
		rules: {
				firstname: "required",Address: "required",lastname: "required",
				Username: {
					required: true,
					minlength: 5
				},
				Password: {
					required: true,
					minlength: 4
				},
				ConfirmPassword: {
					required: true,
					minlength: 4,
					equalTo: "#Password"
				},
				Email: {
					required: true,
					email: true
				},
				Phone: {					
					number:true,	
					minlength: 7
													
				},
				zipcode:{number:true},
				
			},
			messages: {
				firstname: "Please Enter Teacher Name",Address: "Please Enter current Address",lastname: "Please Enter Last Name",
				Username: {
					required: "Please enter a username",
					minlength: "Username must consist of at least 5 characters"
				},
				Password: {
					required: "Please provide a password",
					minlength: "Password must be at least 5 characters long"
				},
				Confirm_password: {
					required: "Please provide a password",
					minlength: "Password must be at least 5 characters long",
					equalTo: "Please enter the same password as above"
				},
				Email: "Please enter a valid email address",
			},
        submitHandler: function (form) {
			    var myform = document.getElementById("TeacherEditForm");	
				var data = new FormData();
				var fdata = $('#TeacherEditForm').serializeArray();
				var ufile = $('#displaypicture')[0].files[0];
				data.append('action', 'UpdateTeacher');
				data.append('displaypicture', ufile);
				$.each(fdata, function (key, input) {
					data.append(input.name, input.value);
				});
				data.append('data',fdata);
				
				$.ajax({
						type: "POST",
						url: ajax_url,
						data: data,
						cache: false,
						processData: false, 
						contentType: false,
						beforeSend:function () {
							$('#u_teacher').attr('disabled','disabled');
							$.fn.notify('loader',{'desc':'Saving data..'});		
						},
						success: function(rdata) {
							if(rdata=='success0')
							{	
								$.fn.notify('success', {'desc': 'Teacher Updated successfully !', autoHide: true, clickToHide: true});
								var  wpsp_pageURL= $('#wpsp_locationginal').val();
								var delay = 1000;
								var url =  wpsp_pageURL+"/admin.php?page=sch-teacher";
								var timeoutID = setTimeout(function() {
								window.location.href = url;
								}, delay);	
								$('#TeacherEditForm').trigger("reset");	
								$('#u_teacher').attr('disabled','disabled');	
							}
							else
							{
								$.fn.notify('error', {'desc': rdata, autoHide: true, clickToHide: true });		
							}
						},
						error:function () {
								$.fn.notify('error', {'desc': 'Something went wrong!', autoHide: true, clickToHide: true });		
						},
						complete:function(){
							$('.pnloader').remove();	
						}
				});
				
				
		}        
    });

	      // Working hours validation
	      $('#whours').keypress(function (event) {
	            return isNumber(event, this)
	        });
		      
		  function isNumber(evt, element) {
	        var charCode = (evt.which) ? evt.which : event.keyCode
	        if (
	             (charCode != 58 || $(element).val().indexOf(':') != -1) &&    
	            (charCode < 48 || charCode > 57))
	            return false;
	        return true;
	      }
	     
		   $('#whours').on("cut copy paste",function(e) {
		      e.preventDefault();
		   });

	      // Working hours validation end


		$("#TeacherEntryForm").validate({
			rules: {
				firstname: "required",Address: "required",lastname: "required",
				Username: {
					required: true,
					minlength: 5
				},
				Password: {
					required: true,
					minlength: 4
				},
				ConfirmPassword: {
					required: true,
					minlength: 4,
					equalTo: "#Password"
				},
				displaypicture: {
					required: true,
				}, 
				Email: {
					required: true,
					email: true
				},
				Phone: {					
					number:true,												
				},
				zipcode:{number:true},
				
			},
			messages: {
				firstname: "Please Enter Teacher Name",Address: "Please Enter current Address",lastname: "Please Enter Last Name",
				Username: {
					required: "Please enter a username",
					minlength: "Username must consist of at least 5 characters"
				},
				Password: {
					required: "Please provide a password",
					minlength: "Password must be at least 5 characters long"
				},
				displaypicture: {
					required: "Please Upload Profile Image",
				},
				Confirm_password: {
					required: "Please provide a password",
					minlength: "Password must be at least 5 characters long",
					equalTo: "Please enter the same password as above"
				},
				Email: "Please enter a valid email address",
			},
			submitHandler: function(form){
				var data = new FormData();
				var fdata=$('#TeacherEntryForm').serializeArray();
				var ufile = $('#displaypicture')[0].files[0];
				data.append('displaypicture', ufile);
				data.append('action', 'AddTeacher');
				$.each(fdata,function(key,input){
						data.append(input.name,input.value);
				});
				data.append('data',fdata);
				$.ajax({
						type: "POST",
						url: ajax_url,
						data: data,
						cache: false,
						processData: false, 
						contentType: false,
						beforeSend:function () {
							$.fn.notify('loader',{'desc':'Saving data..'});
							$('#teacherform').attr("disabled", 'disabled');							
						},
						success: function(rdata) {
							$('#teacherform').removeAttr('disabled');
							if(rdata=='success')
							{
								console.log(rdata);
								$.fn.notify('success', {'desc': 'Teacher added successfully !', autoHide: true, clickToHide: true});
								var  wpsp_pageURL= $('#wpsp_locationginal').val();
								var delay = 1000;
								var url =  wpsp_pageURL+"/admin.php?page=sch-teacher";
								var timeoutID = setTimeout(function() {
								window.location.href = url;
								}, delay);	
								$('#TeacherEntryForm').trigger("reset");
								$('#teacherform').attr("disabled", 'disabled');	
							}
							else
							{
								$.fn.notify('error', {'desc': rdata, autoHide: true, clickToHide: true });	
							}
						},
						error:function () {
							$.fn.notify('error', {'desc': 'Something went wrong!', autoHide: true, clickToHide: true });
							$('#teacherform').removeAttr('disabled');
						},
						complete:function(){
							$('.pnloader').remove();
							$('#teacherform').removeAttr('disabled');
						}
				});
			}		
		});
		
		$(document).on('click','.ViewTeacher',function(e) {		
			e.preventDefault();
			var data=[];
			var tid=$(this).data('id');
			data.push({name: 'action', value: 'TeacherPublicProfile'},{name: 'id', value: tid});
			jQuery.post(ajax_url, data, function(pdata) {
				$('#ViewModalContent').html(pdata);
				$('#ViewModal').modal('show');
			});
		});
		
		$('#AddTeacher').on('click',function(e){
			e.preventDefault();
			$('#AddModal').modal('show');
		});
		
		$('#TeacherEntryForm').submit(function(e){
			e.preventDefault();
		});
		
		$('#TeacherImportForm').submit(function(e){
			e.preventDefault();
		});	
		
		
		
		$("#selectall").click(function(){
			if($(this).prop("checked")==true){
				$(".tcrowselect").prop('checked',true);
			}else{
				$(".tcrowselect").prop("checked",false);
			}
		});
		
		$(".tcrowselect").click(function () {
		   if ($(this).prop("checked") != true) {
			   $("#selectall").prop("checked", false);
		   }
		});

		$(document).on('click','.ClassDeleteBt',function(e) { 
        var tid = $(this).data('id');
		var elements = document.querySelectorAll('body > *');
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
							sticker: true,
						},
						
						history: {
							history: false
						},
					}).get().on('pnotify.confirm', function(){
						 var data = [];

						data.push({
							name: 'action',
							value: 'DeleteTeacher'
						}, {
							name: 'tid',
							value: tid
						});

						tid = '0';

						jQuery.post(ajax_url, data, function(cddata) {
							
							 if (cddata == 'success') {

							  $.fn.notify('success', {'desc': 'Deleted successfully!'});
							  location.reload();
							
							} else {
								$.fn.notify('error', {'desc': 'Operation failed.Something went wrong!'});
							}
						});	
				}).on('pnotify.cancel', function() {
					 $("#overlay").removeClass("overlays");	
				});	

	    });
	
		$('#bulkaction').change(function(){
			
			var op=$(this).val();
			if(op=='bulkUsersDelete'){
				var uids = $('input[name^="UID"]').map(function() {
						if($(this).prop('checked')==true)
						return this.value;
				}).get();
				if(uids.length==0){
					$.fn.notify('error',{'desc':'No user selected!'});
					return false;	
				} else {				
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
						var data=new Array();
						data.push({name:'action',value:'bulkDelete'});
						data.push({name:'UID',value:uids});
						data.push({name:'type',value:'teacher'});
						$.ajax({
								type: "POST",
								url: ajax_url,	
								data:data,
								beforeSend: function(){
									$.fn.notify('loader',{'desc':'Deleting Students!'});
								},
								success: function(data) {
									$('.pnloader').remove();
									 if (data == 'success') {
										$.fn.notify('success', {'desc': 'Deleted successfully!'});
									} else {
										$.fn.notify('error', {'desc': 'Operation failed.Something went wrong!'});
									}
									location.reload();
									
								   //$.fn.notify('success',{'desc':'Deleted successfully!'});								   
								},
								complete: function(){
									$('.pnloader').remove();
								}
						});
					});
				}
			}
		});
		
		$("#selectall").click(function(){
			if($(this).prop("checked")==true){
				$(".tcrowselect").prop('checked',true);
			}else{
				$(".tcrowselect").prop("checked",false);
			}
		});


	   $('#displaypicture').change(function(){
	   var fp = $("#displaypicture");
	   var lg = fp[0].files.length; // get length
	   var items = fp[0].files;
	   var fileSize = 0;
   
		   if (lg > 0) {
			   for (var i = 0; i < lg; i++) {
				   fileSize = fileSize+items[i].size; // get file size
			   }
			   if(fileSize > 3000000) {
				   document.getElementById("test").innerHTML = "File size must not be more than 3 MB";
					//alert('File size must not be more than 2 MB');
					$('#displaypicture').val('');
			   }
		   }
		});
	});
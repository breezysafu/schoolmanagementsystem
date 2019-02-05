$(document).ready(function() {
	
	updateDate();
	
	function updateDate() {		
		$('.ExStart').datepicker({
			autoclose: true,
			dateFormat: date_format, 
			todayHighlight: true,  
			changeMonth: true,
			changeYear: true,
			 minDate:'0d',
			beforeShow: function(input, inst) {
				$(document).off('focusin.bs.modal');
			},
			onClose:function(){
				$(document).on('focusin.bs.modal');
			},
			onSelect: function( selectedDate ) {
				$( ".ExEnd" ).datepicker( "option", "minDate", selectedDate );
			}
		});
		
		$('.ExEnd').datepicker({
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
				$( ".ExStart" ).datepicker( "option", "maxDate", selectedDate );
			}
		});
	}
	

		$('.select_date').datepicker({autoclose: true,dateFormat: date_format, todayHighlight: true,  changeMonth: true,
			changeYear: true,
			beforeShow: function(input, inst) {
				$(document).off('focusin.bs.modal');
			},
			onClose:function(){
				$(document).on('focusin.bs.modal');
			},		
		});
		
		$('#exam_class_table').dataTable({
			"order": [],
			"columnDefs": [ {
			  "targets"  : 'nosort',
			  "orderable": false,
			}],
			responsive: true,
		});		
		
		$("#ExamEntryForm").validate({
			onkeyup:false,
			rules: {
				'class_name': {
					required: true,					
				},
				'ExName': {
					required: true,
					minlength: 2
				},
				
				'ExStart': {
					required:true
				},
				'ExEnd': {
					required:true
				},
			},
			messages: {
				ExamName: {
					required: "Please enter Exam Name",
					minlength: "Exam name must consist of at least 2 characters"
				},
				class_name:{
					required: "Please select class name",
				}
			},
			submitHandler: function(form){
				var data=$('#ExamEntryForm').serializeArray();
				data.push({name: 'action', value: 'AddExam'});
				$.ajax({
						type: "POST",
						url: ajax_url,
						data: data,
						
						success: function(rdata) {
									if(rdata=='success')
									{
										$.fn.notify('success', {'desc': 'Exam Created Successfully!', autoHide: true, clickToHide: true});
										var  wpsp_pageURL= $('#wpsp_locationginal').val();
										console.log(wpsp_pageURL);
										var delay = 1000;
										var url =  wpsp_pageURL+"/admin.php?page=sch-exams";
										var timeoutID = setTimeout(function() {
										window.location.href = url;
										}, delay);
										//$('.formresponse').html("<div class='alert alert-success'>Exam Created Successfully!</div>");
										$('#ExamEntryForm').trigger("reset");
										$('#e_submit').attr('disabled','disabled');
									}
									else
									{
										$.fn.notify('error', {'desc': rdata, autoHide: true, clickToHide: true });
										//$('.formresponse').html("<div class='alert alert-danger'>"+rdata+"</div>");
									}
						}
				});

			}
		});
		
		
		/*Edit Save */
		$("#ExamEditForm").validate({
			onkeyup:false,
			rules: {
				'class_name': {
					required: true,					
				},
				'ExamID': {
					required: true,
					number:true
				},
				'ExName': {
					required: true,
					minlength: 2
				},
				
				'ExStart': {
					required:true
				},
				'ExEnd': {
					required:true
				},
			
			},
			messages: {
				SName: {
					required: "Please enter Subject Name",
					minlength: "Subject must consist of at least 2 characters"
				},
				class_name:{
					required: "Please select class name",
				}
			},
			submitHandler: function(form){
				var data=$('#ExamEditForm').serializeArray();
				data.push({name: 'action', value: 'UpdateExam'});
				$.ajax({
						type: "POST",
						url: ajax_url,
						data: data,
						success: function(rdata) {
							if(rdata=='updated')								
							{
								$.fn.notify('success', {'desc': 'Exam information updated Successfully!', autoHide: true, clickToHide: true});
								var  wpsp_pageURL= $('#wpsp_locationginal').val();
								console.log(wpsp_pageURL);
								var delay = 1000;
								var url =  wpsp_pageURL+"/admin.php?page=sch-exams";
								var timeoutID = setTimeout(function() {
								window.location.href = url;
								}, delay);
								//$('.formresponse').html("<div class='alert alert-success'>Exam information updated Successfully!</div>");
								$('#e_submit').attr('disabled','disabled');
							}
							else
							{
								$.fn.notify('error', {'desc': rdata, autoHide: true, clickToHide: true });
								//$('.formresponse').html("<div class='alert alert-danger'>"+rdata+"</div>");
							}
						}
				});

			}
		
		});
		/* Exam Delete */		
		$(document).on('click','.ExamDeleteBt',function(e) {		
			var eid=$(this).attr('eid');	
			$( "#overlay" ).addClass( "overlays" );	
			new PNotify({			
				title: 'Confirmation Needed',
				text: 'Are you sure want to delete?',
				icon: 'glyphicon glyphicon-question-sign',
				hide: false,				
				confirm: { confirm: true },
				buttons: { closer: false, sticker: false },
				history: {	history: false },
			}).get().on('pnotify.confirm', function(){
				var data=[];
				data.push({name: 'action', value: 'DeleteExam'},{name: 'eid', value: eid});				
				jQuery.post(ajax_url, data, function(cddata) {					
					if(cddata=='deleted') {	
					
						$('#InfoModalBody').html("<div class='col-md-8 text-green'>Exam deleted successfully!</div>");					
						location.reload();
												
					} else {
						$('#InfoModalBody').html("<div class='col-md-8 text-red'>"+cddata+"</div>");					
					}
				});
			}).on('pnotify.cancel', function() {
                $("#overlay").removeClass("overlays");    
            });
		});
	
		
		$(document).on('change','#class_name,#edit_class_name',function(e){	
		
			var data = [];
			$('.action-button').attr('disabled','disabled');			
			data.push({name: 'action', value: 'subjectList'});
			data.push({name: 'ClassID', value: $(this).val()});
			jQuery.post(ajax_url, data, function(subject_list) {			
				var subject_json = $.parseJSON(subject_list);
				var html='';
				$.each(subject_json.subject,function(field,value) {
					html += '<input type="checkbox" name="subjectid[]" value="'+value.id+'" class="exam-subjects" id="subject-'+value.id+'"><label for="subject-'+value.id+'">'+value.sub_name+'</label>';
				});
				$('.exam-class-list').html(html);
				$('.action-button').removeAttr('disabled');
				$('.exam-all-subjects').attr('checked', false);
			});
		});		
		
		$(document).on('click','.exam-all-subjects',function(){
			if($(this).prop("checked")==true){
				$(".exam-subjects").prop('checked',true);
			}else{
				$(".exam-subjects").prop("checked",false);
			}
		});
		
		$(document).on('click','.exam-subjects',function() {
			if($(this).prop("checked")==false){
				$(".exam-all-subjects").prop('checked',false);
			}
		});
	});
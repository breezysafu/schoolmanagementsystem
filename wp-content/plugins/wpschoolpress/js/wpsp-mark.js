jQuery(document).ready(function () 
{
	$('#ClassID').change( function()	{				$('#ExamID').attr('disabled','disabled');
		var data = $(this).serializeArray();
		data.push({name: 'action', value: 'subjectList'});
		data.push({name: 'get_exam_list', value: 1 });
		jQuery.post(ajax_url, data, function(subject_list) {
			$('#SubjectID').find('option').remove().end();
			$('#ExamID').find('option').remove().end();
			var subject_json = $.parseJSON(subject_list);
			$("#ExamID").append('<option value="">Select Exam</option>');
			$.each(subject_json.exam,function(field,value) {
				$("#ExamID").append('<option value="'+ value.eid +'">'+ value.e_name +'</option>');
			});			$('#ExamID').removeAttr('disabled');
			/*
			$.each(subject_json.subject,function(field,value) {
				$("#SubjectID").append('<option value="'+ value.id +'">'+ value.sub_name +'</option>');
			});*/
		});
	});	
 
	$('#ExamID').change( function()	{		$('#SubjectID').attr('disabled','disabled');
		var data = $(this).serializeArray();
		data.push({name: 'action', value: 'getMarksubject'});
		jQuery.post(ajax_url, data, function(subject_list) {
			$('#SubjectID').find('option').remove().end();
			var subject_json = $.parseJSON(subject_list);
			$.each(subject_json.subject,function(field,value) {
				$("#SubjectID").append('<option value="'+ value.id +'">'+ value.sub_name +'</option>');
			});			$('#SubjectID').removeAttr('disabled');
		});
	});
	
	$('#wp-student-mark').dataTable({
			"order": [],
			"columnDefs": [ {
			  "targets"  : 'nosort',
			  "orderable": false,
			}],
			responsive: true,
	});
		
	$('.MarkAction').click( function(e) {
		 var mclass=$('#ClassID').val();
		 var msub=$('#SubjectID').val();
		 var mexam=$('#ExamID').val();
		 var err='';
		 if(mclass=='' || mclass==null){
		 	err+='Select class to enter mark . </br>';
		 }if(msub=='' || msub==null){
		 	err+='Select subject to enter mark . </br>';
		 }if( mexam=='' || mexam==null){
		 	err+='Select exam to enter mark .';
		 }
		 if(err!=''){
			 e.preventDefault();
			 	new PNotify({
			        title: 'Error!',
			        type: 'error',
			        text: err,
			        icon: 'glyphicon glyphicon-alert'
			    });	
			    /*new PNotify({
			        text: "Please Wait",
			        type: 'info',
			        icon: 'fa fa-spinner fa-spin',
			        hide: false,
			        buttons: {
			            closer: false,
			            sticker: false
			        },
			        opacity: .75,
			        shadow: false,
			        width: "170px"
	    		});*/
		 }
	});
	
	/***************** Mark Entry **********************/
	$('input.numbers').bind('keypress', function(e) {
		return ( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57)) ? false : true ;
	});
$('input.numbers').bind("cut copy paste",function(e) {
        e.preventDefault();
    });
 
	$('#AddMark_Submit').click(function(e) {
		e.preventDefault();
		var empty=0;
		
		$("#AddMarkForm .markbox").each(function(){
			var value=$(this).val();
			if(value=='')
			{
				empty=1;
			}
		});
		if(empty<1)
		{
			var postData = $('#AddMarkForm').serializeArray();
			postData.push({name: 'action', value: 'addMark'});	
			
			jQuery.ajax({
				method:"POST",
				url:ajax_url, 
				data:postData,
				beforeSend:function(){
					$('#AddMark_Submit').attr('disabled','disabled');
					$.fn.notify('loader',{'desc':'Saving..'});
				},
				success:function(response) {
					if (response == 'success' ) {
						document.getElementById("AddMarkForm").reset(); 
						new PNotify({
							title: 'Success!',
							type: 'success',
							text: 'Marks Saved Successfully !...',
							icon: 'glyphicon glyphicon-check'
						});
						setTimeout(function() {
						$( "#viewmarks" ).click();
						

					}, 2000);
					$('#AddMark_Submit').attr('disabled','disabled');	
					} else if(response == 'false' ) { 
						new PNotify({
							title: 'Error!',
							type: 'error',
							text: 'Marks Entry failed !... ',
							icon: 'glyphicon glyphicon-alert'
						});
					}else if (response == 'update' ) {
						new PNotify({
							title: 'Success!',
							type: 'success',
							text: 'Marks updated Successfully !...',
							icon: 'glyphicon glyphicon-check'
						});
						setTimeout(function() {
						$( "#viewmarks" ).click();
						

					}, 2000);
						//$( "#viewmarks" ).click();
					}else{
						new PNotify({
							title: 'Error!',
							type: 'error',
							text: response,
							icon: 'glyphicon glyphicon-alert'
						});
					}
				},
				complete:function(){
					$('.pnloader').remove();
				}
			});		
		}
		else{
			new PNotify({
			        title: 'Error!',
			        type: 'error',
			        text: 'Mark Box Should not be Empty, Mark Absentees as AB..',
			        icon: 'glyphicon glyphicon-alert'
			    });
		}
	 });
	 
	/***************** Report *****************************/
	$('#rclass_id').change(function(){
		var cid=$(this).val();
		var getStudent= [];
		getStudent.push({name: 'action', value: 'get_students_list'});
		getStudent.push({name: 'cid', value: cid});
		jQuery.post(ajax_url, getStudent, function(students_list) {	
			$('#rstud_id').find('option').remove().end();
			var students_json = $.parseJSON(students_list);
			$.each(students_json,function(field,value)
			{
			$("#rstud_id").append('<option value="'+ value.wp_usr_id +'">'+ value.full_name +'</option>');
			});
		});
	});
	
	$('#rstud_id').change(function(){
		$('#report_sub').submit();	
	});
});
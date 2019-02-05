	$(document).ready(function(){
		$("#checkAll").click(function () {
			$(".mid_checkbox").prop('checked', $(this).prop('checked'));
		});
		
		$('#message-list').dataTable({
			"order": [],
			"columnDefs": [ {
			  "targets"  : 'nosort',
			  "orderable": false,
			}],			
			responsive: true,
			pageLength:25,
		});
		
        $('#r_id').multiselect({
            columns: 1,
            placeholder: 'Select Receiver(s)',
            search: true
        });
		$("#showGroup").click(function(){
			$('#receiverUsers').hide();
			$('#receiverGroups').show();
		});
		$("#showUser").click(function(){
			$('#receiverGroups').hide();
			$('#receiverUsers').show();
		});
		$("#newMessageForm").validate({
			onkeyup:false,
			rules: {
				'r_id': {
					required: true
				},
				'subject':{
					required: true
				},
				'message':{
					required:true,
					minlength:10
				}
			},
			messages: {
				r_id: {
					required: "Please add at least one receiver"
				},
                subject:{
                    required: "Please enter subject"
                },
                message:{
				    required:"Please enter message",
					minlength: "Message should contain of at least 10 characters"
                }
			},
			submitHandler: function(form){
				var data=$('#newMessageForm').serializeArray();
				data.push({name: 'action', value: 'sendMessage'});
				$.ajax({
						type: "POST",
						url: ajax_url,
						data: data,
						beforeSend:function () {
							$.fn.notify('loader',{'desc':'Sending message..'});
							$('#send').attr("disabled", 'disabled');
						},
						success: function(mres) {
							$('#send').removeAttr('disabled');							
							if( mres=='Message sent successfully' ) {
								  $.fn.notify('success',{'desc':'Message sent Successfully!', autoHide: true, clickToHide: true});
								//$('.formresponse').html("<div class='alert alert-success'>Message sent Successfully!</div>");
								  var delay = 1000;
								 setTimeout(function() {
						location.reload(true);
						}, delay);
								$('#newMessageForm').trigger("reset");
							} else {
								$.fn.notify('error', {'desc': mres, autoHide: true, clickToHide: true });
								//$('.formresponse').html("<div class='alert alert-danger'>"+mres+"</div>");
							}
						},
						error:function () {
							$.fn.notify('error', {'desc': 'Something went wrong!', autoHide: true, clickToHide: true });
							//$('#formresponse').html("<div class='alert alert-danger'>Something went wrong!</div>");
							$('#send').removeAttr('disabled');
						},
						complete:function(){
							$('.pnloader').remove();
							$('#send').removeAttr('disabled');
						}
				});
			}
		});
        $(document).on('submit','#replyMessageForm',function() {				
			if( $('#replyMessageForm').find('#message').val() != '' ) {
				var data=$(this).serializeArray();
				$('#sendReply').attr( 'disabled', 'disabled' );
				data.push({name: 'action', value: 'sendMessage'});
				$.ajax({
					type: "POST",
					url: ajax_url,
					data: data,
					success: function(mres) {					
						$('#viewMessageContainer').html(mres);
						$('#sendReply').removeAttr( 'disabled' );
					}
				});
			}
        });
        $(document).on('click','#student_teacher',function(e){
			if( $('#student_teacher').is(':checked') ) {
				$('.wp-subject-list').addClass('none');
				var classid =  $('input[name=childname]:checked').attr('classid');
				$('.wp-subject-name').attr('checked', false); //uncheck checkbox
				$('.class-name-'+classid).removeClass('none');
			} else {
				$('.wp-subject-list').addClass('none');
				$('.wp-subject-name').attr('checked', false); //uncheck checkbox
			}
		});	
		
        $(document).on('click','.msg-child-list',function(e){
			$('#student_classteacher').val( $(this).attr('teacherid') );
			$('.wpsps-message-list').removeClass('none');
			$('.wp-subject-list').addClass('none');
			
			if( $('#student_teacher').is(':checked') ) { 
				$('.wp-subject-list').addClass('none');
				var classid =  $('input[name=childname]:checked').attr('classid');
				$('.wp-subject-name').attr('checked', false); //uncheck checkbox
				$('.class-name-'+classid).removeClass('none');
			} else {
				$('.wp-subject-list').addClass('none');
				$('.wp-subject-name').attr('checked', false); //uncheck checkbox
			}
		});
		
		/*
        $(document).on('click','.viewMess',function(e){
            var mid=$(this).attr('mid');
            var data=[];
            data.push({name: 'action', value: 'viewMessage'},{name: 'mid', value: mid});
            jQuery.post(ajax_url, data, function(mvres) {
                if(mvres){
                    $('#viewMessageContainer').html(mvres);
                    $('#replyMessageModal').modal('show');
                }
                else{
                    $('#viewMessageContainer').html("<div class='col-md-8 alert alert-danger'>Something went wrong!</div>");
                }
            });
        }); */
	$(document).on('click','.ClassDeleteBt',function(e) {	
		 var mid = $(this).data('id');		
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
                 }).get().on('pnotify.confirm', function () {   
				var data=[];
				data.push({
							name: 'action',
							value: 'deleteMessage'
						}, {
							name: 'mid',
							value: mid
						});
				
				jQuery.post(ajax_url, data, function(delete_messages)
				{
					location.reload();
				});
			 });
		});
		
		$('#bulkaction').change(function(){			
			if( $(this).val()=='bulkUsersDelete' ) {
				var uids = $('input[name^="mid"]').map(function () {
                if ($(this).prop('checked') == true)
                     return this.value;
                }).get();
                if (uids.length == 0) {
                    $.fn.notify('error', {'desc': 'No user selected!'});
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
                 }).get().on('pnotify.confirm', function () {   
				//if( result == true ) {
					var postData=[];
					$('.mid_checkbox:checkbox:checked').each(function()
					{
						postData.push({name:$(this).attr('name'),value:$(this).val()});
					});
					postData.push({name: 'action', value: 'deleteMessage'});
					postData.push({name:'multipledelete', value:1});
					jQuery.post(ajax_url, postData, function(delete_messages)
					{
						
						location.reload();
					});
				//}
				 });
             }
			}
		});
	});
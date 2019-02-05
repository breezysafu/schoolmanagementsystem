$(document).ready(function() {	$('#notify_table').dataTable({				"order": [],				"columnDefs": [ {					  "targets"  : 'nosort',					  "orderable": false,				}],				responsive: true,	});
    /*Delete Attendance*/  	
    $(document).on('click','.notify-Delete',function(){
       if(confirm("Are you want to delete this entry?")){
            var aid=$(this).attr('data-id');
            if( aid == '' ) {
                $.fn.notify('error',{'desc':'Notification information Missing!'});
            }else{
                var data=[];
                data.push({name: 'action', value: 'deleteNotify'},{name: 'notifyid', value: aid});
                $.ajax({
                    type: "POST",
                    url: ajax_url,
                    data: data,
                    beforeSend:function () {
                        $.fn.notify('loader',{'desc':'Deleting entry..'});
                    },
                    success: function(res) {
                        $.fn.notify('success',{'desc':'Notification entry deleted successfully..'});
						$( this ).closest( 'tr' ).remove();
                    },
                    error:function(){
                        $.fn.notify('error',{'desc':'Something went wrong. Try after refreshing page..'});
                    },
                    complete:function () {
                        $('.pnloader').remove();
                    }
                });
            }
       }
    });
	/* View Notification */
	$(document).on('click','.notify-view',function() {
		var cid=$(this).attr('data-id');
		if($.isNumeric(cid)){
			var data=[];
			data.push({name: 'action', value: 'getNotify'},{name: 'notifyid', value: cid});
			$.ajax({
				type: "POST",
				url: ajax_url,
				data: data,
				beforeSend:function () {
					$.fn.notify('loader',{'desc':'Loading Notification..'});
				},
				success: function(res) {
					$('#ViewModalContent').html(res);
				},
				error:function(){
					$.fn.notify('error',{'desc':'Something went wrong. Try after refreshing page..'});
				},
				complete:function () {
					$('.pnloader').remove();
				}
			});
			$('#ViewModal').modal('show');
		}else{
			$.fn.notify('error',{'desc':"Notification ID Missing.."});
		}
	});
	
	$(document).on('click','#notifySubmit',function() {		
		$("#NotifyEntryForm").validate({
			rules: {				
				subject: {
					required: true,
					minlength: 10
				},
				receiver: "required",
				type: "required"
			},			
			submitHandler: function(form){
				$('#notifySubmit').submit();
			}
		}); 
	});

});
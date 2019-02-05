$(document).ready(function(){
	$('#import').dataTable({
					"order": [],
					"columnDefs": [ {
					  "targets"  : 'nosort',
					  "orderable": false,
					}],
					responsive: true,
	});
				
	$(document).on('click','.undoimport',function(){			
		var id = $(this).attr('value');	
		var data=new Array();
		data.push({name:'id',value:id},{name:'action',value:'undoImport'});
		$.ajax({
			type: "POST",
			url: ajax_url,	
			data:data,
			beforeSend:function(){
				$.fn.notify('loader',{'desc':'Removing rows!'});
			},
			success: function(res) {
			   $.fn.notify('success',{'desc':'Rows removed successfully'});
			   //location.reload();
			},
			complete:function(){
				$('.pnloader').remove();
			}
		});
	});               
			
});
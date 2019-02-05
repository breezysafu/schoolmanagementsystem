$(document).ready(function() {
	
	$('#transport_table').dataTable({
				"order": [],
				"columnDefs": [ {
					  "targets"  : 'nosort',
					  "orderable": false,
				}],
				responsive: true,
	});
	
    $('#AddNew').click(function(){
        var data=new Array();
        data.push({name: 'action', value: 'addTransport'});
       $.ajax({
           type:"GET",
           url:ajax_url,
           data:data,
           beforeSend:function(){
               $.fn.notify('loader',{'desc':'Loading Form..'});
           },
           success: function (form) {
                $('#TransModalBody').html(form);
           },
           complete:function(){
               $('.pnloader').remove();
               $('#TransModal').modal('show');
           },
           error:function(){
               $.fn.notify('error',{'desc':'Something went wrong..'});
           }
       })
    });
    $('.EditTrans').click(function(){
        var id=$(this).attr('data-id');
        var data=new Array();
        data.push({name: 'action', value: 'updateTransport'},{name: 'id', value: id});
        $.ajax({
            type:"GET",
            url:ajax_url,
            data:data,
            beforeSend:function(){
                $.fn.notify('loader',{'desc':'Loading Form..'});
            },
            success: function (form) {
                $('#TransModalBody').html(form);
            },
            complete:function(){
                $('.pnloader').remove();
                $('#TransModal').modal('show');
            },
            error:function(){
                $.fn.notify('error',{'desc':'Somethng went wrong..'});
            }
        })
    });
    $('.ViewTrans').click(function(){
        var id=$(this).attr('data-id');
        var data=new Array();
        data.push({name: 'action', value: 'viewTransport'},{name: 'id', value: id});
        $.ajax({
            type:"GET",
            url:ajax_url,
            data:data,
            beforeSend:function(){
                $.fn.notify('loader',{'desc':'Loading data..'});
            },
            success: function (form) {
                $('#TransModalTitle').text("Transport Information");
                $('#TransModalBody').html(form);
            },
            complete:function(){
                $('.pnloader').remove();
                $('#TransModal').modal('show');
            },
            error:function(){
                $.fn.notify('error',{'desc':'Somethng went wrong..'});
            }
        })
    });
    $(document).on('click','#TransSubmit', function(e){
        e.preventDefault();
        var data=$('#TransEntryForm').serializeArray();
        data.push({name: 'action', value: 'addTransport'});
        $.ajax({
            type:"POST",
            url:ajax_url,
            data:data,
            beforeSend:function(){
                $.fn.notify('loader',{'desc':'Saving data..'});
            },
            success: function (res) {
                if(res=='success'){
                    $.fn.notify('success',{'desc':'Transport details saved successfully..'});
                    $('#TransModalBody').html('');
                    $('#TransModal').modal('hide');
						var delay = 1000;
						 setTimeout(function() {
						location.reload(true);
						}, delay);
                }else{
                    $.fn.notify('error',{'desc':res});
                }
            },
            complete:function(){
                $('.pnloader').remove();
            },
            error:function(){
                $.fn.notify('error',{'desc':'Somethng went wrong..'});
            }
        })
    })
    $(document).on('click','#TransUpdate', function(e){
        e.preventDefault();
        var data=$('#TransEditForm').serializeArray();
        data.push({name: 'action', value: 'updateTransport'});
        $.ajax({
            type:"POST",
            url:ajax_url,
            data:data,
            beforeSend:function(){
                $.fn.notify('loader',{'desc':'Saving data..'});
            },
            success: function (res) {
                if(res=='success'){
                    $.fn.notify('success',{'desc':'Transport details saved successfully..'});
                    $('#TransModalBody').html('');
                    $('#TransModal').modal('hide');
					var delay = 1000;
						 setTimeout(function() {
						location.reload(true);
						}, delay);
						
                }else{
                    $.fn.notify('error',{'desc':res});
                }
            },
            complete:function(){
                $('.pnloader').remove();
            },
            error:function(){
                $.fn.notify('error',{'desc':'Somethng went wrong..'});
            }
        })
    });
    $('.DeleteTrans').click(function () {
        var transid=$(this).attr('data-id');
        $('#TransModal').modal('show');
        $('#TransModalTitle').text("Confirm your action");
        $('#TransModalBody').html("<h3>Are you want to delete Transport details?</h3><div class='pull-right'><div class='btn btn-default' data-dismiss='modal' id='ConfirmCancel'>Cancel</div>&nbsp; <div class='btn btn-danger' data-id="+transid+" id='DeleteTransConfirm'>Confirm</div></div>");

    })
    $(document).on('click','#DeleteTransConfirm',function(){
        var id=$(this).attr('data-id');
        var data=new Array();
        data.push({name: 'action', value: 'deleteTransport'},{name: 'id', value: id});
        $.ajax({
            type:"POST",
            url:ajax_url,
            data:data,
            beforeSend:function(){
                $.fn.notify('loader',{'desc':'Deleting transport details..'});
            },
            success: function (res) {
                if(res=='success'){
                    $('#TransModalBody').html('');
                    $('#TransModal').modal('hide');
                }else{
                    $.fn.notify('error',{'desc':'Something went wrong..'});
                }
            },
            complete:function(){
                $('.pnloader').remove();

            },
            error:function(){
                $.fn.notify('error',{'desc':'Something went wrong..'});
            }
        })
    });
});

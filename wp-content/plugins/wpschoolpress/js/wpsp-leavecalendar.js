
    $(document).ready(function(){
		var addDate='<div class="row"><div class="col-md-3">from<span class="red">*</span><input type="text" name="spls" class="form-control spls select_date"></div><div class="col-md-3">to<span class="red">*</span><input type="text" name="sple" class="form-control sple select_date"></div><div class="col-md-6">Reason<input type="text" name="splr" class="form-control"></div></div></div>';

		$('#addLeaveDays').click(function(){
			$('#addLeaveDaysBody').toggle();
		});

		$('.leaveAdd').click(function(){
		    var cid=$(this).attr('data-id');
			var form='<form action="" id="addLeaveDateForm" method="post">';
			$('#leaveModalHeader').html("Add Leave Date");
			$('#leaveModalBody').html(form + addDate+'<div class="MTTen"><input type="hidden" name="ClassID" value="'+cid+'"><input type="submit" class="btn btn-primary" id="addLeaveDateSubmit" value="Save"></div></form>');
			$('#leaveModal').modal('show');
		});

		$('#wpsp_leave_days').dataTable({
					"order": [],
					"columnDefs": [ {
					  "targets"  : 'nosort',
					  "orderable": false,
					}],
					responsive: true,
		});
		$('.leaveView').click(function(){
			$('#leaveModalHeader').html("Leave Dates");
            $('#leaveModalBody').html('');
            var cid=$(this).attr('data-id');
            var data=[];
            data.push({name: 'action', value: 'getLeaveDays'},{name: 'cid', value: cid});
            $.ajax({
                type: "POST",
                url: ajax_url,
                data: data,
                beforeSend:function(){
                    $.fn.notify('loader',{'desc':'Loading dates..'});
                },
                success: function (res) {
                    $('#leaveModalBody').html(res);
                },
                complete:function(){
                    $('.pnloader').remove();
                },
                error: function () {
                    $.fn.notify('error',{'desc':'Something went wrong!'});
                }
            });
			$('#leaveModal').modal('show');
		});

		$('.leaveDelete').click(function(){
			var lid = $(this).attr('data-id');
			if($.isNumeric(lid)){
				$('#leaveModalBody').html("<h4>Are you sure want to delete all dates?</h4><div class='pull-right'><div class='btn btn-default' data-dismiss='modal' id='AllDeleteCancel'>Cancel</div>&nbsp; <div class='btn btn-danger' data-id='"+lid+"' id='AllDeleteConfirm'>Confirm</div></div><div style='clear:both'></div>");
			}else{
				$('#leaveModalBody').html("Class id missing can't delete. Please report it to support for deletion");
			}
			$('#leaveModalHeader').html("Delete all date");
			$('#leaveModal').modal('show');
		});
        $(document).on('click','.dateDelete', function(){
            var lid = $(this).attr('data-id');
            if($.isNumeric(lid)){
                $('#leaveModalBody').html("<h4>Are you sure want to delete this dates?</h4><div class='pull-right'><div class='btn btn-default' data-dismiss='modal' id='DateDeleteCancel'>Cancel</div>&nbsp; <div class='btn btn-danger' data-id='"+lid+"' id='DateDeleteConfirm'>Confirm</div></div><div style='clear:both'></div>");
            }else{
                $('#leaveModalBody').html("Class id missing can't delete. Please report it to support for deletion");
            }
            $('#leaveModalHeader').html("Delete all date");
            $('#leaveModal').modal('show');
        });
		$(document).on('click','#AllDeleteConfirm',function(){
			var cid=$(this).attr('data-id');
			var data=[];
			data.push({name: 'action', value: 'deleteAllLeaves'},{name: 'cid', value: cid});
			$.ajax({
				type: "POST",
				url: ajax_url,
				data: data,
                beforeSend:function(){
                    $.fn.notify('loader',{'desc':'Deleting leaves..'});
                },
				success: function (res) {
                    if(res=='success'){
                        var pntype='success';
                        var pntext="Dates deleted Successfully";
                    }
                    else{
                        var pntype='error';
                        var pntext=res;
                    }
                    $('#leaveModal').modal('hide');
                    $.fn.notify(pntype,{'desc':pntext});
                },
                complete:function(){
                    $('.pnloader').remove();
                },
				error: function () {
                    $.fn.notify('error',{'desc':'Something went wrong!'});
				}
			});

		});
        $(document).on('focus',".spls", function(){
            $('.spls').datepicker({
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
			}
			
			});
        });
		 $(document).on('focus',".sple", function(){
            $('.sple').datepicker({
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
			}
			});
        });
		
	
		
	
		

		$(document).on('submit','#addLeaveDateForm',function(e){
		    e.preventDefault();
            var data=$(this).serializeArray();
            data.push({name: 'action', value: 'addLeaveDay'});
            $.ajax({
                type: "POST",
                url: ajax_url,
                data: data,
                beforeSend:function(){
                    $.fn.notify('loader',{'desc':'Adding date..'});
                },
                success: function (res) {
                    if(res=='success'){
                        var pntype='success';
                        var pntext="Dates added Successfully";
                    }
                    else{
                        var pntype='error';
                        var pntext=res;
                    }
                    $('#leaveModal').modal('hide');
                    $.fn.notify(pntype,{'desc':pntext});
                },
                complete:function(){
                    $('.pnloader').remove();
                },
                error: function () {
                    $.fn.notify('error',{'desc':'Something went wrong!'});
                }
            });

        });
        $(document).on('click','#DateDeleteConfirm',function() {
            var lid = $(this).attr('data-id');
            var data = [];
            data.push({name: 'action', value: 'deleteAllLeaves'}, {name: 'lid', value: lid});
            $.ajax({
                type: "POST",
                url: ajax_url,
                data: data,
                beforeSend: function () {
                    $.fn.notify('loader', {'desc': 'Deleting date..'});
                },
                success: function (res) {
                    if (res == 'success') {
                        var pntype = 'success';
                        var pntext = 'Date deleted successfully';
                    }else {
                        var pntype = 'error';
                        var pntext = res;
                    }
                    $('#leaveModal').modal('hide');
                    $.fn.notify(pntype, {'desc': pntext});
                },
                complete: function () {
                    $('.pnloader').remove();
                },
                error: function () {
                    $.fn.notify('error', {'desc': 'Something went wrong!'});
                }
            });
        });
        $('#ClassID').change(function(){
            var cid = $(this).val();
            var data = [];
            data.push({name: 'action', value: 'getClassYear'}, {name: 'cid', value: cid});
            $.ajax({
                type: "POST",
                url: ajax_url,
                data: data,
                beforeSend: function () {
                    $.fn.notify('loader', {'desc': 'Loading Class Year..'});
                },
                success: function (res) {
                    try {
                        var clyear = $.parseJSON(res);
                        $('#CSDate').val(clyear.c_sdate);
                        $('#CEDate').val(clyear.c_edate);
                    } catch (e) {

                    }
                },
                complete: function () {
                    $('.pnloader').remove();
                },
                error: function () {
                    $.fn.notify('error', {'desc': 'Something went wrong!'});
                }
            });
        })
		
	});
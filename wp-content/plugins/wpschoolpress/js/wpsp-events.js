$(document).ready(function() {

	updateDate();
	
	function updateDate() {

		$('.stime, .etime').timepicker({
			showInputs: true,
			showMeridian:false,
			template: false,
		});     
		
		$('.sdate').datepicker({
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
				$( ".edate" ).datepicker( "option", "minDate", selectedDate );
			}
		});
		
		$('.edate').datepicker({
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
				$( ".sdate" ).datepicker( "option", "maxDate", selectedDate );
			}
		});
	}
		
	$('#calendar').fullCalendar({
			header: {
				left: 'title',
				center: 'today,prev,next',
				right: 'month,basicWeek,basicDay'
			},
				firstDay:1,
				editable: true,
				selectable: true,
				selectHelper: true,
				ignoreTimezone:true,
				timeFormat: 'h(:mm)',
				allDaySlot :false,
				select: function(start, end) 
				{
					$('#calevent_entry')[0].reset();
					$('#calevent_save').prop('disabled',false);
					$('#calevent_delete').prop('disabled','disabled');
					var nstart=start.format('MM/DD/YYYY');
					var nend=moment();
					var nend= end.subtract(1, "days").format("MM/DD/YYYY");

					$('#sdate').val(nstart);
					$('#edate').val(nend);
					$('#basicModal').modal('show');

				},
				eventClick:  function(event, jsEvent, view) 
					{	
						$('#viewEventTitle').html(event.title);
						event.start == null ? $('#eventStart').html('N/A') : $('#eventStart').html( event.start.format('MM/DD/YYYY h:mm A') );
						event.end == null ? $('#eventEnd').html('N/A') : $('#eventEnd').html( event.end.format('MM/DD/YYYY h:mm A') );
						$('#eventDesc').html(event.description);
						$("#viewModal").modal('show');
						$('#editEvent').click(function(){
							edit_event(event);
						});
						/* Delete Event */
						$('#deleteEvent').click(function(){
							if(confirm("Are you sure want to delete?")==true)
							{
								var postData=new Array();
								postData.push({name: 'action', value: 'deleteEvent'});
								postData.push({name: 'evid', value:event.id});
								jQuery.post(ajax_url, postData, function(result) {
									if(result=='success')
									{
										$('#response').html("<div class='alert alert-success'>Event deleted successfully..</div>");
										location.reload(true);

									}
									else{
										$('#response').html("<div class='alert alert-danger'>Action failed please refresh and try..</div>");
										location.reload(true);
									}

								});
							}
						});
					},
			eventDrop: function(event)
			{
				event.preventDefault();
			},
			eventLimit: true, // allow "more" link when too many events
			
			events: {
				url: ajax_url,
				type: "POST",
				data:{'action': 'listEvent'},
				dataType: "JSON",
				error: function() {
					alert('There is an error while fetching events!');
				}
			},
			
		});	
	
	/*New Event*/
	$('#calevent_save').click(function() {
		var nstart=$('#sdate').val();
		var nend=$('#edate').val();
		var ntitle= $('#evtitle').val();
		var newEvent={
			title : ntitle,
			start : nstart,
			end : nend
		}
		if(ntitle=='' || nstart=='' || nend=='')
		{
			
			 $.fn.notify('error', {'desc': rdata, autoHide: true, clickToHide: true  });
			$('#response').html("<div class='alert alert-danger'>Title and dates are mandatory..</div>");
		}
		else{
			$('#calevent_save').prop('disabled','disabled');
			var postData=$('#calevent_entry').serializeArray();
			if($.isNumeric($('#evid').val())){
				postData.push({name: 'action', value: 'updateEvent'});
			}else {
				postData.push({name: 'action', value: 'addEvent'});
			}
			jQuery.post(ajax_url, postData, function(result) {
				if(result=='success')
				{
					$('#evid').val('');
					 $.fn.notify('success',{'desc':'Event details saved successfully..'});
					location.reload(true);
                    $('#basicModal').html('');
                    $('#basicModal').modal('hide');
					 setTimeout(function() {
						location.reload(true);
						}, delay);
					
					//$('#response').html("<div class='alert alert-success'>Event Saved Successfully..</div>");
					//location.reload(true);
				}
				else{
					$('#calevent_save').prop('disabled',false);
					$('#response').html("<div class='alert alert-danger'>Please try Again..</div>");
				}
			});
		}
	});
});
	/* Edit event */
	function edit_event(event) {
		//alert( event.start + ':' + event.end );
		event.start == null ? $('#sdate').val('') : $('#sdate').val(event.start.format('MM/DD/YYYY'));
		event.end == null ? $('#edate').val('') : $('#edate').val( event.end.format('MM/DD/YYYY') );
		
		event.start == null ? $('#stime').val('') : $('#stime').val(event.start.format('h:mm A'));
		event.end == null ? $('#etime').val('') : $('#etime').val( event.end.format('h:mm A') );
		
		/*$('#sdate').val(event.start.format('MM/DD/YYYY'));
		$('#edate').val(event.end.format('MM/DD/YYYY'));		
		$('#stime').val( event.start.format('h:mm A') );
		$('#etime').val( event.end.format('h:mm A') ); */
		$('#evtitle').val( event.title );
		$('#evdesc').val( event.description );
		$('#evid').val( event.id );
		$('#evcolor').val( event.color );
		$( '#viewModal' ).modal( 'hide' );
		$('#basicModal').modal( 'show' );
	}


$(document).ready(function() {
		
	$('#multiple-events').fullCalendar({
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
						$('#eventStart').html(event.start.format('MM/DD/YYYY h:mm A'));
						$('#eventEnd').html(event.end.format('MM/DD/YYYY h:mm A'));
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
				data:{'action': 'listdashboardschedule'},
				dataType: "JSON",
				error: function() {
					alert('There is an error while fetching events!');
				}
			},
			
		});
	
});
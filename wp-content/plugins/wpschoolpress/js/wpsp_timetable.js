$(document).ready(function(){		

	$("#sessions_template").change(function() {

		if($(this).val()=="new") {

			$('#enter_sessions').show();

			$('#select_template').hide();

		} else {

			$('#enter_sessions').hide();

			$('#select_template').show();

		}

	});

	

    $('#deleteTimetable').click(function()	{

		var cid=$(this).attr('data-id');

        if(confirm("Are you sure want to delete class Timetable?") == true) {

			var ttDetails= [];

            ttDetails.push({name: 'cid' , value: cid });

            ttDetails.push({name: 'action', value: 'deletTimetable'});

            jQuery.post(ajax_url, ttDetails, function(stat) {

                if(stat=='deleted') {

					$("#TimetableContainer").html("<div class='alert alert-info'>Class Timetable deleted Successfully..</div>");

                }

            });

        }

    });

        $("#timetable_form").validate({

            rules: {

                noh: {
                    required: true,
                },
                sessions_template:{
                    required: true,
                },
                wpsp_class_name:{
                    required: true,
                },               
            },
            messages: {
                noh:{
                    required: "Please enter number of sessions",
                },
                sessions_template:{
                    required: "Please enter number of sessions",
                },
                wpsp_class_name:{
                    required: "Please select class",
                }
            }
        });

    $('.item').draggable({

        revert:true,

        proxy:'clone'

    });

	

    $('.drop').droppable({

		accept: '.item',

        onDragEnter:function(){

            $(this).addClass('over');

        },

        onDragLeave:function(){

            $(this).removeClass('over');

        },

        onDrop:function(e,source) {			

            $(this).removeClass('over');

            if ($(source).hasClass('assigned')){

                $(this).append(source);

            } else {

                var c = $(source).clone().addClass('assigned');

                $(this).empty().append(c);

                c.draggable({

                    revert:true

                });

            }

			

            $('#ajax_response').text('Saving..');

            var cid=$('#class_id').val();

            var tid=$(this).attr('tid');

            var sid=$(source).attr('id');

            var day=$(this).closest('tr').attr('id');

            var ttDetails= [];

            ttDetails.push({name: 'cid' , value: cid });

            ttDetails.push({name: 'tid' , value: tid });

            ttDetails.push({name: 'sid' , value: sid });

            ttDetails.push({name: 'day' , value: day });

            ttDetails.push({name: 'action', value: 'save_timetable'});

            jQuery.post(ajax_url, ttDetails, function(stat1) {

                var arr = stat1.split(',');

                var count = arr.length;

                if(count==2) {

					var classname = arr[0];

					var stat = arr[1];

				} else {

					stat = stat1;

				}



                if(stat=='true' || stat=='updated') {

					if(count==2) {

						$('#ajax_response_exist').text('This Teacher also assigned to class '+classname);

					} else {

                        $('#ajax_response_exist').text('');

                    }

					

                    $('#ajax_response').text('Saved..');

                } else {

                    $('#ajax_response').html("<p class='bg-red'> Not Saved..</p>");

                }

			});

        }

	});

	

    $('.removesubject').droppable({

        accept:'.assigned',

        onDragEnter:function(e,source){

            $(source).addClass('trash');

        },

        onDragLeave:function(e,source){

            $(source).removeClass('trash');

        },

        onDrop:function(e,source){

            $(source).remove();

        }

    });

	

    $('#print_timetable').click(function() {

        var divToPrint=document.getElementById("timetable_table");

        newWin= window.open("","Timetable Print");

        newWin.document.write('<style>table{border-collapse: collapse;}table,th,td{border: 1px solid black;}td.break{border:0;}tr.break{border:1px solid #000;}</style>');

        newWin.document.write(divToPrint.outerHTML);

        newWin.print();

        newWin.close();

	});

	

    $('.daytype').change(function () {

        if( this.value == 0 ) {

            $('.dayval').show();

            $('.daynam').hide();

		} else {

            $('.daynam').show();

            $('.dayval').hide();

        }

    });



	$('#ClassID').change(function(){

       $('#TimetableClass').submit();

    });

		

	$('.wp-delete-timetable').click(function() {

		if( confirm("Are you sure want to delete class Timetable?") == true ) {

			var tid = $(this).data('id');	

			var data=[];

			data.push({name: 'action', value: 'deletTimetable'},{name: 'cid', value: tid});

			$.ajax({

				type: "POST",

				url: ajax_url,

				data: data,

				beforeSend: function () {

					$.fn.notify('loader', {'desc': 'Deleting image..'});

				},

				success: function (pdata) {

					if( pdata=='deleted')

						$.fn.notify('success',{'desc':'Time Table Deleted Successfully'});

					else

						$.fn.notify('error',{'desc':'Try Again Later'});						

				},

				complete: function () {

					$('.pnloader').remove();

				}

			})

		}	

    });

});



function Popup(data) {

	var mywindow = window.open('', 'Timetable Print', 'height=400,width=600');

    mywindow.document.write(data);

    mywindow.document.close(); // necessary for IE >= 10

    mywindow.focus(); // necessary for IE >= 10

    mywindow.print();

    mywindow.close();

    return true;

}

   




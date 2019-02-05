$(document).ready(function() {

    $('#subject_table').dataTable({
        "order": [],
        "columnDefs": [{
            "targets": 'nosort',
            "orderable": false,
        }],
        responsive: true,
    });

    $("#AddSubjectButton").click(function() {
        $("#SClassName").text($("#ClassID option:selected").text());
        $("#SCID").val($("#ClassID").val());
        $("#AddSubjectModal").modal('show');
    });
    $("#ClassID").change(function() {
        $("#SubjectList-Form").submit();
    });
    $("#ShowExtraFields").click(function() {
        $(".SubjectExtraDetails").toggle();
    });


    if($.trim($('#ClassID').val()) == 'all'){

         $('#subdisable').attr('disabled','disabled');
         
    } else {
        
         $('#subdisable').attr('disabled',false);
    }


    $("#SubjectEntryForm").validate({
        onkeyup: false,
        ignore: [],
        rules: {
            'SNames[]': {
                required: true,
                minlength: 2
            },
            'SCID': {
                required: true,
                number: true
            },
            STeacherID: {
                number: true
            }
        },
        messages: {
            SName: {
                required: "Please enter Subject Name",
                minlength: "Subject must consist of at least 2 characters"
            },
            SCID: {
                required: "Class ID missing please refresh"
            }
        },
        submitHandler: function(form) {
            var data = $('#SubjectEntryForm').serializeArray();
            data.push({
                name: 'action',
                value: 'AddSubject'
            });
            $.ajax({
                type: "POST",
                url: ajax_url,
                data: data,

                success: function(rdata) {
                    if (rdata == 'success') {
						$.fn.notify('success', {'desc': 'Subject created successfully!', autoHide: true, clickToHide: true});
                        var  wpsp_pageURL= $('#wpsp_locationginal1').val();
                        console.log(wpsp_pageURL);
                        var delay = 1000;
                        var url =  wpsp_pageURL+"/admin.php?page=sch-subject";
                        var timeoutID = setTimeout(function() {
                        window.location.href = url;
                        }, delay); 
						$('#s_submit').attr('disabled','disabled');
                     //   $('.formresponse').html("<div class='alert alert-success'>Subject Created Successfully!</div>");
                        $('#SubjectEntryForm').trigger("reset");
                    } else {
						$.fn.notify('error', {'desc': rdata, autoHide: true, clickToHide: true });
                       // $('.formresponse').html("<div class='alert alert-danger'>" + rdata + "</div>");
                    }
                }
            });

        }
    });

    /*Edit Subject Modal */
    $(".EditSubjectLink").click(function() {
        var sid = $(this).attr('sid');
        var SDetails = [];
        SDetails.push({
            name: 'action',
            value: 'SubjectInfo'
        });
        SDetails.push({
            name: 'sid',
            value: sid
        });
        $.ajax({
            type: "POST",
            url: ajax_url,
            data: SDetails,
            beforeSend: function() {
                $('#formresponse').html("Saving..");
				$('#u_teacher').attr('disabled','disabled');
            },
            success: function(subject_details) {
                var sdatapar = $.parseJSON(subject_details);
                if (typeof sdatapar == 'object') {
                    $('#SRowID').val(sdatapar.id);
                    $('#ESClassID').val(sdatapar.class_id);
                    $('#EditSCode').val(sdatapar.sub_code);
                    $('#EditSName').val(sdatapar.sub_name);
                    $('#EditBName').val(sdatapar.book_name);
                    try {
                        $("#EditSTeacherID option[value=" + sdatapar.sub_teach_id + "]").attr('selected', 'selected');
                    } catch (e) {
                        //
                    }
                    $('#EditSubjectModal').modal('show');
					$('#u_teacher').attr('disabled','disabled');
                } else {
                    $('#InfoModalTitle').text("Error Information!");
                    $('#InfoModalBody').html("<h3>Sorry! No data retrived!</h3><span class='text-muted'>You can refresh page and try again</span>");
                    $('#InfoModal').modal('show');
                }
            },
            error: function() {
                $('#InfoModalTitle').text("Error Information!");
                $('#InfoModalBody').html("<h3>Sorry! File not reachable!</h3><span class='text-muted'>Check your internet connection!</span>");
                $('#InfoModal').modal('show');
            }
        });

    });
    /*Edit Save */
    $("#SEditForm").validate({
        onkeyup: false,
        rules: {
            EditSName: {
                required: true,
                minlength: 2
            },

            EditSTeacherID: {
                number: true
            }
        },
        messages: {
            SName: {
                required: "Please enter Subject Name",
                minlength: "Subject must consist of at least 2 characters"
            }
        },
        submitHandler: function(form) {
            var data = $('#SEditForm').serializeArray();
			//$( "#overlay" ).addClass( "overlays" );
            data.push({
                name: 'action',
                value: 'UpdateSubject'
            });
            $.ajax({
                type: "POST",
                url: ajax_url,
                data: data,
                success: function(rdata) {
                    if (rdata == 'updated') {
						$.fn.notify('success', {'desc': 'Subject information updated Successfully!', autoHide: true, clickToHide: true});
                        var  wpsp_pageURL= $('#wpsp_locationginal1').val();
                        console.log(wpsp_pageURL);
                        var delay = 1000;
                        var url =  wpsp_pageURL+"/admin.php?page=sch-subject";
                        var timeoutID = setTimeout(function() {
                        window.location.href = url;
                        }, delay); 
						//$('#u_teacher').attr('disabled','disabled');
						$('#SEditSave').attr('disabled','disabled');
                        //$('#editformresponse').html("<div class='alert alert-success'>Subject information updated Successfully!</div>");
                       // window.location.reload();
                    } else {

						
						$.fn.notify('error', {'desc': rdata, autoHide: true, clickToHide: true });
                       // alert('a');
                       $('#SEditSave').attr('disabled',false);
                        //$('#editformresponse').html("<div class='alert alert-danger'>" + rdata + "</div>");
						
						  
                    }
					
                }
            });

        }

    });
    /* Subject Delete */
    $(document).on('click', '.SubjectDeleteBt', function(e) {
		$( "#overlay" ).addClass( "overlays" );
        var sid = $(this).attr('sid');
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
        }).get().on('pnotify.confirm', function() {
            var data = [];
            data.push({
                name: 'action',
                value: 'DeleteSubject'
            }, {
                name: 'sid',
                value: sid
            });
            jQuery.post(ajax_url, data, function(cddata) {
                if (cddata == 'deleted') {
                    $('#InfoModalBody').html("<div class='col-md-8 alert alert-success'>Subject deleted successfully!</div>");
					
                    location.reload();
                } else {
                    $('#InfoModalBody').html("<div class='col-md-8 alert alert-danger'>" + cddata + "</div>");
                }
            });
        }).on('pnotify.cancel', function() {
                $("#overlay").removeClass("overlays");    
        });
    });
    /*
    $(document).on('click','#SubjectDeleteConfirm',function(e){
    	var data=[];
    	data.push({name: 'action', value: 'DeleteSubject'},{name: 'sid', value: sid});
    	sid='0';
    	jQuery.post(ajax_url, data, function(cddata) {
    		if(cddata=='deleted'){
    			$('#InfoModalBody').html("<div class='col-md-8 alert alert-success'>Subject deleted successfully!</div>");
    			location.reload();
    		}
    		else{
    			$('#InfoModalBody').html("<div class='col-md-8 alert alert-danger'>"+cddata+"</div>");
    		}
    	});
    });
    $('.modal').on('hidden.bs.modal', function (e) {
    	//location.reload();
    }); */
});
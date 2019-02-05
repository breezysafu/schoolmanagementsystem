	$(document).ready(function(){
		 $('#child_list').multiselect({
            columns: 1,
            placeholder: 'Select Student',
            search: true,
			searchOptions : {
				'default'    : 'Search Student',             // search input placeholder text
				showOptGroups: true,                // show option group titles if no options remaining				
			},
        });
		var lines = [];
		$('.dropdown-menu').click(function(e) {
			e.stopPropagation();
		});
		
		$( ".select_date" ).datepicker({
			autoclose: true,
			dateFormat: date_format,
			todayHighlight: true,
    		changeMonth: true,
            changeYear: true,
			yearRange: "-60:+0",
			beforeShow: function(input, inst) {
				$(document).off('focusin.bs.modal');
			},
			onClose:function(){
				$(document).on('focusin.bs.modal');
			},
  		});
		$('#parent_table').dataTable({
			"order": [],
			"columnDefs": [ {
			  "targets"  : 'nosort',
			  "orderable": false,
			}],
			responsive: true,
		});
				
		$('#ClassID').change(function(){
			$('#ClassForm').submit();
		});
		$('#AddParent').on('click',function(e){
			e.preventDefault();
			$('#AddModal').modal('show');
		});
		$('#ParentEntryForm').submit(function(e){
			e.preventDefault();
		});
		$('#ParentImportForm').submit(function(e){
			e.preventDefault();
		});		
		$('#importcsv').change(function(e){   			 
			var ext = $("input#importcsv").val().split(".").pop().toLowerCase();
			if($.inArray(ext, ["csv"]) == -1) {
				$.fn.notify('error',{'desc':'File format must be CSV!'});
				return false;
			}
			if (e.target.files != undefined) {
				var reader = new FileReader();
				reader.onload = function(e) {
					var csvval=e.target.result.split("\n");                    
					var csvvalue=csvval[0].split(",");
					for (var i = 1; i < csvval.length; i++) {
						var data = csvval[i].split(',');
						if (data.length == csvvalue.length) {
							var tarr = {};
							for (var j = 0; j < csvvalue.length; j++) {
								tarr[csvvalue[j]] =  data[j];
							}
							lines.push(tarr);
						}
					}
					var columnnames = new Array();
					for(var i=0;i<csvvalue.length;i++)
					{
					var temp=csvvalue[i];                       
					columnnames.push(temp);
					}                    
					$.each(columnnames,function(key,value){
					   $('#user_name').append($('<option>', { value: value,text : value }));
					   $('#user_pass').append($('<option>', { value: value,text : value }));
					   $('#user_email').append($('<option>', { value: value,text : value }));
					   $('#rollno').append($('<option>', { value: value,text : value }));
					   $('#full_name').append($('<option>', { value: value,text : value }));
					   $('#gender').append($('<option>', { value: value,text : value }));
					   $('#address').append($('<option>', { value: value,text : value }));
					   $('#bloodgrp').append($('<option>', { value: value,text : value }));
					   $('#DOB').append($('<option>', { value: value,text : value }));
					   $('#doj').append($('<option>', { value: value,text : value }));
					   $('#phone').append($('<option>', { value: value,text : value }));
					   $('#prof').append($('<option>', { value: value,text : value }));
					   $('#student_id').append($('<option>', { value: value,text : value }));
					   $('#qual').append($('<option>', { value: value,text : value }));
					});
					$('.mapsection').show();
				};
				reader.readAsText(e.target.files.item(0));
			}
			return false;
		});
        $("#selectall").click(function(){
            if($(this).prop("checked")==true){
                $(".ptrowselect").prop('checked',true);
            }else{
                $(".ptrowselect").prop("checked",false);
            }
        });        
		
		$('.ViewParent').click(function(e){
			e.preventDefault();
			var data=[];
			var pid=$(this).data('id');
			data.push({name: 'action', value: 'ParentPublicProfile'},{name: 'id', value: pid}, {name: 'button', value: 1});
			jQuery.post(ajax_url, data, function(pdata) {
				$('#ViewModalContent').html(pdata);
				$('#ViewModal').modal('show');
			});
		});
		
        // $('#displaypicture').change(function () {
        //     if (this.files.length > 0) {
        //         $.each(this.files, function (index, value) {
        //             $('#fp').html($('#fp').html() + '<br />' +
        //                 '<b>' + Math.round((value.size / 1024)) + '</b> KB');
        //         })
        //     }
        // });
        $('#displaypicture').change(function(){
               var fp = $("#displaypicture");
               var lg = fp[0].files.length; // get length
               var items = fp[0].files;
               var fileSize = 0;
           if (lg > 0) {
               for (var i = 0; i < lg; i++) {
                   fileSize = fileSize+items[i].size; // get file size
               }
               if(fileSize > 3000000) {
                   document.getElementById("test").innerHTML = "File size must not be more than 3 MB";
                    //alert('File size must not be more than 2 MB');
                    $('#displaypicture').val('');
               }
           }
        });
	});
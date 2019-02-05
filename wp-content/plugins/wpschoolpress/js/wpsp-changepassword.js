$(document).ready(function() {
	
	$.validator.addMethod("notEqualTo", function(value, element, param) {
	 return this.optional(element) || value != $(param).val();
	 }, "New Password is not same as old password");
	 
	$("#changepassword").validate({		

		onkeyup:false,

		rules: {
			oldpw: {
				required: true,				
			},
			newpw: {
				notEqualTo:"#oldpw",
				required: true,
				minlength: 2,
			},
			newrpw: {
				notEqualTo:"#oldpw",
				required: true,
				equalTo: "#newpw",
			}
		},

		messages: {
			oldpw: {
				required: "Please enter Current Password",				
			},
			newpw: {
				required: "Please enter New Password",
				notEqualTo:"Old Password is not same as New password",
			},
			newrpw: {
				required: "Please enter Confirm New Password",
				equalTo : "Confirm New Password Should be same as New Password",
				notEqualTo:"Old Password is not same as New password",
			}
		},

		submitHandler: function(form) {

			$('#Change').attr("disabled", 'disabled');

			$( '#message_response' ).html('');

			var data=$('#changepassword').serializeArray();

			data.push({name: 'action', value: 'changepassword'});

			$.ajax({

					type: "POST",

					url: ajax_url,

					data: data,
                    beforeSend:function () {
							$.fn.notify('loader',{'desc':'Loading..'});
							$('#Change').attr("disabled",true);
						},
					success: function(response) {

						var response_data = jQuery.parseJSON(response);

						$('#Change').removeAttr('disabled');

						if( response_data.success == 1 ) {

							//$( '#message_response' ).html( "<div class='alert alert-success'>"+response_data.msg+"</div>" );
							$.fn.notify('error', {'desc': response_data.msg, autoHide: true, clickToHide: true});  
							$( '#changepassword' ).find("input[type=password]").val("");
							$('#Change').attr("disabled",true);

						} else	{
								//$.fn.notify('error',{'desc':response_data.msg});
							$.fn.notify('success', {'desc': response_data.msg, autoHide: true, clickToHide: true  });
							//$( '#message_response' ).html( "<div class='alert alert-danger'>"+response_data.msg+"</div>" );

						}

						$('.form-control').val('');	


					},
					error:function () {
						$.fn.notify('error', {'desc': 'Something went wrong!', autoHide: true, clickToHide: true  });
							//$('#formresponse').html("<div class='alert alert-danger'>Something went wrong!</div>");
							$('#Change').removeAttr('disabled');
						},
						complete:function(){
							$('.pnloader').remove();
							$('#Change').removeAttr('disabled');
						}

				});

		}

	});

});
var $=jQuery.noConflict();
$(document).ready(function(){
	$('#wpsp-import-data').click(function(e){	
	
		e.preventDefault();
		$('.response').html('');
		$('.spinner').css( 'visibility', 'visible');	
		$(this).attr( 'disabled', 'disabled' );
		var data = {				
			'action': 'ImportContents',
		};	
		jQuery.post(ajaxurl, data, function(response) {				
			$('#wpsp-import-data').removeAttr( 'disabled' );			
			$('.spinner').css( 'visibility', 'hidden');					
			$('.response').html(response);
		});		
	});
		
		$('#contactForm').submit('click',function(e) {
			e.preventDefault();
			var name = $('#inputName').val();
			var email = $('#inputEmail').val();
			var message = $('#inputMessage').val();
			var error=[];
			function isValidEmailAddress(email){
				var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
				return pattern.test(email);
			}
			if(!isValidEmailAddress(email)){
				error.push("Please enter valid email address!");
			}
			if(name.length<3){
				error.push("Please enter valid name. More than 3 characters!");
			}
			if(message.length<10){
				error.push("Please enter valid message. More than 10 characters!");
			}
			if(error.length===0) {
				var contactData=$(this).serializeArray();
				$.ajax({
					type: "POST",
					url: "http://localhost/wpschoolpress/public/wpadminContact",
					data: contactData,
					cache: false,
					success: function(result){
						$('#contactResponse').removeClass('alert-danger');
						$('#contactResponse').addClass('alert-success');
						$('#contactResponse').text(result);
						$('#contactForm').trigger("reset");
					},
					error: function (result) {
						$('#contactResponse').addClass('alert-danger');
						$('#errorList').empty();
						for(var i in result.responseJSON.message){
							$('#errorList').append("<li>"+result.responseJSON.message[i]+"</li>");
						}
					}
				});
			}else{
				if(error.length>0){
					$('#contactResponse').addClass('alert-danger');
					$('#errorList').empty();
					for(var i in error){
						$('#errorList').append("<li>"+error[i]+"</li>");
					}
				}			}
	});
});
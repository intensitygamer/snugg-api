
// For Add User Account

$(document).ready(function() {
	
	$("#add_account").click(function(event) {

	event.preventDefault();
		
		$.ajax({
		
		type: "POST",
		
		url: base_url + "AdminController/insert_account",
		
		dataType: 'json',
		
		data: $("#adduserform").serialize(),
				
				success: function(res) {
				
				if (res.response=="success"){
				
				// Show Entered Value
				
					$('.validate').css({"color": "green", "font-size": "15px", "text-align": "center"});
					
					$('.validate').html(res.message);
						setTimeout(function(){
					 	 document.location.href = res.redirect;
					}, 3000); 

				}else{

					$('.validate').css({"color": "red", "font-size": "15px", "text-align": "center"});
					$('.validate').html(res.message);
				}
			},

		  resetForm: true

		});

	  return false;

	});
});


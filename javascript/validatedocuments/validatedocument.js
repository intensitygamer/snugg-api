
// For Printing Doc

$(document).ready(function() {
	$("#print_list").click(function(event) {
	event.preventDefault();
		$.ajax({
		type: "POST",
		url: base_url + "AdminController/print_document_list",
		dataType: 'json',
		data: $("#print_doc_form").serialize(),
			success: function(res) {
				if (res.response=="success"){
					$('body').load(res.redirect);
				}
				else{
					alert(res.message);
				}
			},
		  resetForm: true
		});
	  return false;
	});
});

// load notification

$(document).ready(function() {
 var url= base_url + "HeadController/notify_doc_status";
  window.setInterval(function(){
   $.getJSON(url,function(data){
 	 if (data!= 1){
   	  $('.request').html();
  }
  	 else{
   	 $('.request').html(data);
   }
 })
 }, 1000);

});

   $(document).ready(function() {
    $('.get_notify').bind('click', function() {
      var status=$('.request').text();
        $.ajax({
        	  type: "GET",  
              url: base_url + "HeadController/get_status",
              data: "status=" + status,
              success:function(res){
               }
        }); 
    });
 });
$(".view_forward_log").click(function() {
    var doc_id = $(this).closest("tr").children("td:first").text();
    var confirmation_status = $(this).closest("tr").find("td:nth-child(14)").text();
    $('[name="doc_id"]').val(doc_id);
    $('[name="confirmation_status"]').val(confirmation_status);
    if (confirmation_status = 0){
    	$(".update_status").text('Confirm');
    }
    else{
    	$(".update_status").text('Unconfirm');
    }
});
//for add user account
$(document).ready(function() {
	$(".update_status").click(function(event) {
	event.preventDefault();
		$.ajax({
		type: "POST",
		url: base_url + "HeadController/update_confirmation",
		dataType: 'json',
		data: $("#confirmation_form").serialize(),
			success: function(res) {
				if (res.response=="success"){
				  $('.success-confirmation').css({"color": "green", "font-size": "15px", "text-align": "center", "margin": "20px auto" });
					 $('.success-confirmation').html(res.message);
					setTimeout(function(){
					 	 document.location.href = res.redirect;
					}, 3000); 
				}
				else{
					alert(res.message);
				}
			},
		  resetForm: true
		});
	  return false;
	});
});


jQuery(document).ready(function(){

jQuery('#ideapro_contact_submit').on('touchstart click',function(e){
	jQuery('.error-smart').remove();	
	var name=jQuery('#your_name').val();
	var email=jQuery('#your_email').val();
	var phone=jQuery('#phone_number').val();
	var comment=jQuery('#your_comments').val();
	if(name!="")
	{
		jQuery("#your_name").removeClass("error");
		jQuery('.error-smart').remove();	
	}
	else
	{
		e.preventDefault();
		jQuery("#your_name").addClass("error");
		jQuery("#your_name").after('<div class="error-smart">Name field is required</div>');
	}

	if(email!="")
	{
		jQuery("#your_email").removeClass("error");
		jQuery('.error-smart').remove();	
	}
	else
	{
		e.preventDefault();
		jQuery("#your_email").addClass("error");
		jQuery("#your_email").after('<div class="error-smart">Email field is required</div>');
	}
	if(phone!="")
	{
		jQuery("#phone_number").removeClass("error");
		jQuery('.error-smart').remove();	
	}
	else
	{
		e.preventDefault();
		jQuery("#phone_number").addClass("error");
		jQuery("#phone_number").after('<div class="error-smart">Phone field is required</div>');
	}
	if(comment!="")
	{
		jQuery("#your_comments").removeClass("error");
		jQuery('.error-smart').remove();	
	}
	else
	{
		e.preventDefault();
		jQuery("#your_comments").addClass("error");
		jQuery("#your_comments").after('<div class="error-smart">Message field is required</div>');
	}

if(email != '')
{
if (validateEmail(email)) {
jQuery("#your_email").removeClass();
}
else {
	e.preventDefault();
	jQuery(".gmail-error").remove();
	jQuery(".all-class1").remove();

jQuery("#your_email").after("<div class='all-class1 all-class'>Please enter a valid email address.</div>");
jQuery("#your_email").addClass("error");
}
}
	
});	
	function validateEmail(email) {
		var filter = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
		if (filter.test(email)) {
		return true;
		}
		else {
		return false;
		}
		}
	
});

jQuery(document).ready(function(){
function submit_contact_form()
{
	var fd = new FormData();
	fd.append('ideaproContactSubmit','1');
	fd.append('name',jQuery("#your_name").val());
	fd.append('email',jQuery("#your_email").val());
	fd.append('phone',jQuery("#phone_number").val());
	fd.append('comments',jQuery("#your_comments").val());
	js_submit(fd,submit_contact_form_callback);


}

function submit_contact_form_callback(data)
{
	var jdata = JSON.parse(data);

	if(jdata.success == 1)
	{
		var mess = jdata.message;

		jQuery("#response_div").html(mess);
		jQuery("#response_div").css("background-color","green");
		jQuery("#response_div").css("color","#FFFFFF");
		jQuery("#response_div").css("padding","20px");
	}

}

function js_submit(fd,callback)
{
	var submitUrl = 'http://localhost/Narola/Narola-contact/wp-content/plugins/Nisl Contact/process/';

	jQuery.ajax({url: submitUrl,type:'post',data:fd,contentType:false,processData:false,success:function(response){ callback(response); },});

}
});


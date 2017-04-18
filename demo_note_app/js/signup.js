jQuery(document).ready(function() {
	$('.signup_container input[type=Submit]').on('click',function() {
		new_user();
    });
});

function new_user() {
	var user_name = $(".signup_container .user_name .login_text").val();
	var email = $(".signup_container .user_email .login_text").val();
	var pw = $(".signup_container .password .login_text").val();
	
	var json_param = {};
	json_param['task']= "createUser";
	json_param['name']= $(".signup_container .user_name .login_text").val();
	json_param['email'] = $(".signup_container .user_email .login_text").val();
	json_param['pw'] = $(".signup_container .password .login_text").val();
	
	$(".loading").removeClass('hidden'); // show loading gif
	
	$.post("json/note_signup_dbops.php", 
			json_param,
			function(data) {
				$(".loading").addClass('hidden'); //hide loading gif 
				
				if (data['error']) {
					// error
					$err_html = "<div class='err_msg'>" + data['error'] + "</div>";
					$('.msg').html($err_html);
				}
				else
				{
					$('.msg').html('');
					window.location.href = data['home_page'];
				}
	});
}

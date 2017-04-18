jQuery(document).ready(function() {
	$('.login_container input[type=Submit]').on('click',function() {
		login();
    });
});

/*
 * login() : check crendentials, if login good, redirect to home page
 */
function login() {
	var email = $(".login_container .user_email .login_text").val();
	var pw = $(".login_container .password .login_text").val();
	
	//email and password validation done server side
	
	var json_param = {};
	json_param['task']= "loginUser";
	json_param['email'] = email;
	json_param['pw'] = pw
	
	$(".loading").removeClass('hidden'); // show loading gif
	
	$.post("json/note_signup_dbops.php", 
			json_param,
			function(data) {
				$(".loading").addClass('hidden'); //hide loading gif 
				
				if (data['error']) {
					//Login failed, display reason
					$err_html = "<div class='err_msg'>" + data['error'] + "</div>";
					$('.msg').html($err_html);
				}
				else
				{
					//Login good, redirect
					$('.msg').html('');
					window.location.href = data['home_page'];

				}
	});
}
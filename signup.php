<?php
/*
 * Signup (request account) page
 */

$current_page = "login.php";
$ABS_PATH="/var/www/remote/";

$no_auth=true;
require_once('sub/head.php'); //page header
?>
<div class='page_title'>Sign Up</div>
<div class='page_contents'>
	<div class='signup_container section'>
		<div class='loading hidden'><img src='img/ajax-loader.gif'> Loading ...</div>
		<div class='form_element user_name'>
			<span class='login_label'>Name:</span><br>
			<input class='login_text' type='text' placeholder='' >
		</div>
		<div class='form_element user_email'>
			<span class='login_label'>Email:</span><br>
			<input class='login_text' type='text' placeholder='' >
		</div>
		<div class='form_element password'>
			<span class='login_label'>Password</span><br>
			<input class='login_text' type='password' placeholder='' ><br>
			<span class='helpful_info'>At least 8 characters,1 lower, 1 cap, 1 number, 1 special char</span><br>
		</div>
		<div class='form_element'>
			<input  type='submit' value='Create Account' />
		</div>
		<div class='form_element msg'>
		</div>
		<div class='form_element'>
			Existing user? <a href="login.php" class="">Login</a>
		</div>
	</div> <!-- signup container section-->
</div> <!-- page_contents -->
<?php 
require_once('sub/footer.php');


?>

<?php
/*
 * Login Page
 */
$current_page = "login.php";

$no_auth=true;
require_once('sub/head.php'); //page header
?>
<div class='page_title'>Login</div>
<div class='page_contents'>
	<div class='login_container section'>
		<div class='form_element user_email'>
			<span class='login_label'>Email:</span><br>
			<input class='login_text' type='text' placeholder='' >
		</div>
		<div class='form_element password'>
			<span class='login_label'>Password</span><br>
			<input class='login_text' type='password' placeholder='' >
		</div>
		<div class='form_element'>
			<input  type='submit' value='Login' />
		</div>
		<div class='form_element msg'>
		</div>
		<div class='form_element'>
			Not an existing user? <a href="signup.php" class="">New Account</a>
		</div>
	</div> <!-- login container section -->
</div> <!-- page container -->
<?php 
require_once('sub/footer.php');


?>

<?php
/*
* Generally called by javascript/Jquery post to get/check user credentials
* or to create user account
* 
* Queries the database and return JSON format data
*/
/*
 * Login/Signup page database operations, called from js scripts
*
* Gets/updates user credentials from database
*
* Queries the database and return JSON format data
*
*/

$HOME_PAGE = 'note_view.php';

$PHP_SELF = $_SERVER['PHP_SELF'];
$REQUEST_URI = $_SERVER['REQUEST_URI'];
//require_once('sub/load_config.php');
require_once('../sub/database_ops.php');
require_once('../sub/gen_functions.php');

header('Content-type: application/json');
//response array:
$resp = array();

//passed paramters:
$task = getString('task','');
$name = getString('name','');
$email = getString('email','');
$password = getString('pw','');

if ($task == 'createUser') {	
	$resp['user_id'] = create_user($name,$email,$password);	
	$resp['home_page'] = $HOME_PAGE; 
	jsonReturn($resp);
}
else if ($task == 'loginUser') {	
	login_user($email,$password);
	
	//login successful, go to previous page user was on or home page
	$redirect_page = $HOME_PAGE;
	if (isset($_SESSION['sessionPendingRequest'])) {
		$redirect_page = $_SESSION['sessionPendingRequest'];
		unset($_SESSION['sessionPendingRequest']);
	}
	$resp['home_page'] = $redirect_page;
	
	jsonReturn($resp);
}
else if ($task == 'logout') {
	logout_user();
	jsonReturn($resp);
}
else {
	//LogError("unknown task request: $task");
	jsonRetError("invalid request");
}
exit;


/*
 * FUNCTIONS FOR TASKS
 */
/*
 * If credentials are valid, set Session user id variabe, other return error message
 * User is 'logged in' when session variable is set to valid user id
 */
function login_user($email,$password) {
	if (!email_valid($email)) {
		jsonRetError("Invalid email");
	}
	if (!email_exist($email)) {
		jsonRetError("Email is incorrect");
	}
		
	$user_id = check_credentials($email,$password);
	if ($user_id > 0) {
		session_start();
		$_SESSION['sessionUserId'] = $user_id;
		return true;
	}
	else {
		jsonRetError("Password is incorrect");
	}
	return false;
}
/*
 * check_credentials($email,$password)
 * 
 * Check the password and email match a user, return user id
 */ 
function check_credentials($email,$password) {
	$pw_hash = get_hash($password);
	$sql = "SELECT id FROM user WHERE email=:email AND password=:pw_hash";
	$params = array(":email" => $email,":pw_hash"=>$pw_hash);
	$user_id = get_scalar($sql,$params);
	if ($user_id > 0) {
		return $user_id;
	}
	else {
		return 0;
	}
}
/*
 * Check validity of email and password, create user record and log the user in
 * User is 'logged in' when session user id variable is set.
 * Otherwise return error message
*/
function create_user($name,$email,$password) {
	if (!email_valid($email)) {
		jsonRetError("Invalid email");
	}
	if (email_exist($email)) {
		jsonRetError("Email is already in use by another user");
	}
	if (!password_valid($password)) {
		jsonRetError("Invalid password format");
	}
	
	$pw_hash = get_hash($password);

	$sql = "INSERT INTO user (name,email,password,created_at) VALUES (:name,:email,:password,NOW())";
	$params = array(':name' => $name,':email' => $email, ':password'=> $pw_hash);
	$user_id = insert_record($sql,$params);
	
	session_start();
	if ($user_id > 0) {
		$_SESSION['sessionUserId'] = $user_id;
	}
	return $user_id;
}
/*
 * 
 */
function email_valid($email="jdoe@gmail.com") {
	//make sure email format is valid
	$email = filter_var($email, FILTER_SANITIZE_EMAIL);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
	//if (preg_match("/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/",$email)) {
		return true;
	}
	else {
		return false;
	}
}

function password_valid($string) {
	//make sure password format is valid
	if (strlen($string) < 8) {
		jsonRetError("Password must contain at least 8 characters");
	}
	else if (!preg_match("#[0-9]+#",$string)) {
		jsonRetError("Password must contain at least 1 number");
	}
	else if (!preg_match("#[A-Z]+#",$string)) {
		jsonRetError("Password must contain at least 1 capital letter");
	}
	else if (!preg_match("#[a-z]+#",$string)) {
		jsonRetError("Password must contain at least 1 lowercase letter");
	}
	else if (preg_match("#[\s]+#",$string)) {
		jsonRetError("Password cannot contain spaces");
	}
	else if (!preg_match("#[^\da-zA-Z]+#",$string)) {
		jsonRetError("Password must contain at least 1 special character");
	}
	return true;
}

function email_exist($email) {
	//check if already in use
	
	$user_id = get_scalar("SELECT id FROM user WHERE email = :email",array(':email'=> $email));
	if ($user_id > 0) {
		return true;
	}
	return false;
}

function logout_user() {
	session_start();
	session_unset();
	$_SESSION = array();
	session_destroy();
}
?>
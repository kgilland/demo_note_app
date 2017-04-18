<?php 
/*
 * Sets access level for user, forces re-login if user has been idle for more than specified seconds
* Checks if user has access to current page
*
* This is not a standalone page. It is included in head.php for html pages, 
* included directly in php script for JSON pages
*/

session_start();

$user_id = 0 ; //this is used on all pages to get/post user info

if (isset($_SESSION['sessionUserId'])) {
	//current session still good
	$user_id = $_SESSION['sessionUserId'];
	return;
}

//session has expired:
//this next saves the current page, so when the user re-logs in, it goes to the page they were on when
//their session expired
$_SESSION['sessionPendingRequest'] = $_SERVER['REQUEST_URI'];

if ($json_return) {
	//auth was called from json page, return result as json
	header('Content-type: application/json');
	$resp['msg']  = "Session timed out. Please refresh the page to re-login.";
	$resp['no_auth'] = true;
	jsonReturn($resp);
}
else {
	//auth was called from html page, redirect to login page
	//go to login page and make user re-enter credentials
	header("Location: https://${_SERVER['SERVER_NAME']}/login.php");
}

exit;

?>
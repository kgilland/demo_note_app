<?php
/*
 * This is not a stand alone page. It is the header section for most all HTML pages.
 * 
 * Code to fill certainn top-level parameters and write the heading HTML
 */

$PHP_SELF = $_SERVER['PHP_SELF'];
$REQUEST_URI = $_SERVER['REQUEST_URI'];

require_once('sub/load_config.php');
require_once('sub/database_ops.php'); //DB connect and basic functions
require_once('sub/gen_functions.php');

if (!$no_auth) {
	require('sub/auth.php'); //checks authorization 
}
//page-specific javascript file
$self = basename($PHP_SELF, ".php");
$selfjs = "js/$self.js";

header("Content-Type: text/html; charset=utf-8");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?=$title?></title>
	<meta http-equiv='Content-Type' content='text/html;charset=utf-8' />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<script src='https://code.jquery.com/jquery-2.2.0.js'></script>
	<script src='js/general.js'></script>
<?php 	
//page-specific javascript must be added after general.js
if (file_exists($selfjs)) { 
	?><script src='<?=$selfjs?>'></script><?php
}
?>	
	<link rel='icon' type='image/vnd.microsoft.icon' href='img/smile.ico' />
	<link rel='stylesheet' href='css/main.css' type='text/css' />	
</head>
<body>
<div class='main-container'>
	<div class="divBanner">
		<span class='title'><?=$title?></span>
	</div>
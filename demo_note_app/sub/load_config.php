<?php 
/*
 * Read config file parameters
 */
	$config_file = "app.conf";
	if (!is_file($config_file)) {
		if (is_file("../$config_file")) {
			$config_file = "../$config_file";
		}
		else {
			//LogError("config file ($config_file) not found");
			return 0;
		}
	}
	
	$config_settings = parse_ini_file($config_file);
	
	//Read parameters:
	$title = $config_settings['title'];
?>
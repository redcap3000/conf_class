<?php
	// working in same path so just need to provide file name
	$config_file_path = 'sample_config.php';
	
	
	require($config_file_path);
	// make new class
	$config = new conf_class($config_file_path);
	// now display it
	
	echo $config->html;
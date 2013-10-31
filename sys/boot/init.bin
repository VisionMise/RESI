<?php

	/**
	 * Boot
	 */
	setWorkingFolder();



	/**
	 * Global Config
	 * Load resi configuration
	 * then check that it was loaded
	 */
	global $config;
	$config 	= loadConfig();



	/**
	 * Get Libraries
	 */
	loadLibraries();


    /** Rest Data **/
    $restData 		= loadRestData();
    $group			= 'member';
    $class 			= $restData['class'];

    if (isset($config[$group][$class])) {
    	$method 	= strtoupper($restData['method']);
    	$denied 	= ($config[$group][$class][$method] == false);
    } else {
    	$denied 	= false;
    }


	loadEventHandlers($restData);


	if (!$denied) {
		/** Connect to database **/
	    global $sqlConnection;
	    global $sqlConnected;
	    
	    $cfgDatabase    = $config['mysql'];
/*
	    $sqlConnection  = new mysqli(
	        $cfgDatabase['host'],
	        $cfgDatabase['user'],
	        $cfgDatabase['password'],
	        $cfgDatabase['database'],
	        $cfgDatabase['port']
	    );
	    
	    $sqlConnected   = ($sqlConnection->errno == 0);
	   */
	}


	/**
	 * Register API objects
	 */
	registerObjects();


	global $asei;
	$asei->processRequest($denied);


	// ---------- Functional Methods ---------- //



	function registerObjects() {
		global $config;

		$objects = (isset($config['objects'])) ? $config['objects'] : null;
		foreach ($objects as $name => $type) {
			$object 	= new $type();
			if (!$object) continue;

			registerObject($name, $object);
		}
	}


	/**
	 * Load Libraries
	 * Loads libraries based on what type of request
	 * is being made. Library types and locations are
	 * set in the global configuration
	 * @return void
	 */
	function loadLibraries() {
		global $clientMode;
		global $config;		
		global $workingFolder;

		switch ($clientMode) {

			case 'rest_api':
			default:
				$libList	= (isset($config['resi']['library']))
					? $config['resi']['library']
					: array()
				;
			break;
		}

		$ext 	= (isset($libList['ext']))
			? $libList['ext']
			: 'php'
		;

		$folder = (isset($libList['folder']))
			? $workingFolder.'/'.$libList['folder']
			: null
		;

		if (isset($libList['ext'])) 	unset($libList['ext']);
		if (isset($libList['folder']))	unset($libList['folder']);

		ksort($libList);

		foreach ($libList as $lib) {
			$path 			= realpath("$folder/$lib.$ext");
			if (!$path) 	continue;

			$included		= include($path);
			if (!$included)	continue;
		}
	}



	/**
	 * Set Working Folder
	 * Sets absolute root directory for system
	 */
	function setWorkingFolder() {
		global $workingFolder;
		$workingFolder = realpath('../../');
		define("root", $workingFolder);
	}



	/**
	 * Load Configuration
	 * @return array resi configuration array
	 */
	function loadConfig() {
		$configPath			= realpath(root."/cfg/resi.conf");
		if (!$configPath)	return false;

		$config 			= parse_ini_file($configPath, true);

		if ($config === false) {
			throw new Exception('Could not Load Config');
			exit();
		}

		return $config;
	}
	
?>
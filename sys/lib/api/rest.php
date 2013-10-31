<?php

	/** Boot **/
	if (!require('../../boot/init.bin')) {
		throw new Exception('Could not initialize');
		exit();
	}




	// ---------- Functional Methods ---------- //



	/**
	 * Load Event Handlers
	 * @param  array  $restData Array of Rest Data
	 * @return void
	 */
	function loadEventHandlers(array $restData = array()) {
		global $asei;
		global $mlHandler;

		$asei		= new asei($restData);
		$mlHandler 	= new htmlOutputHandler();

		registerObject('mlHandler', $mlHandler);
		registerHandler('renderHtml', 'mlHandler', 'handleHtmlOutput');

		//$asei->processRequest();
	}



	/**
	 * Load Rest Data
	 * Parses URL redirect request in to data
	 * @return array Array of rest data
	 */
	function loadRestData() {
		$rest = array('rest'=>null);

		if (isset($_SERVER['QUERY_STRING'])) {
			parse_str($_SERVER['QUERY_STRING'], $rest);
		}

		$data = array_filter(explode('/', $rest['rest']));
		if (!$data or !count($data)) return false;

		$keys			= array_keys($data);
		$firstKey		= (isset($keys[0])) ? $keys[0] : null;
		$lastKey		= $keys[count($keys)-1];

		//$secKey			= (isset($keys[1])) ? $keys[1] : null;
		$class 			= (isset($data[$firstKey]))	? $data[$firstKey] 	: null;
		$type 			= (isset($data[$lastKey]))	? $data[$lastKey] 	: null;
		//$method			= (isset($data[$secKey]))	? $data[$secKey]	: null;
		//$methodParts	= explode('.', $method, 2);
		//$method 		= $methodParts[0];
		//$type 			= (isset($methodParts[1]))	? $methodParts[1]	: null;
		//$parts 			= explode('.', $type, 2);
		//$type 			= $parts[count($parts)-1];
		//$data[$lastKey]	= $parts[0];

		if ($class) 					unset($data[$firstKey]);
		if (isset($data[$lastKey])) 	unset($data[$lastKey]);
	

		sort($data);
		$param 			= $data;
		$reqMethod 		= strtolower($_SERVER['REQUEST_METHOD']);
		$method 		= $reqMethod;
		$valid			= ($class and $method and $type);

		/** Get POST/PUT/DELETE **/
		if ($reqMethod == 'post') {
			$param 			= array_merge($_POST, $param);
		}

		$struct	= array(
			'class'			=> $class,
			'method'		=> $method,
			'type'			=> $type,
			'data'			=> $param,
			'valid'			=> $valid,
			'request_type'	=> $reqMethod
		);

		//print_r($struct);
		return $struct;
	}



?>
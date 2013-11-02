<?php

	$request	= array_filter(explode('/', $_GET['page']));
	$pageInput	= array();

	foreach ($request as $item) {
		if (item == 'page') continue;
		$pageInput[] = $item;
	}

	chdir("../../../");
	$appStr 	= implode('/', $pageInput);
	$path 		= getcwd()."/ui/$appStr";

	print_r($path);

?>

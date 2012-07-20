<?php

// Format of regex => parseInfo
$regexRoutes = array(
	
	// Map nothing to the home page.
	'#^$#' => array(
		'controller' => 'page',
		'action' => 'view',
		'action_params' => array(
			'page_name' => 'home',
		),
	),
	
	// Allow direct access to all pages via a "/page/page_name" URL.
	'#^page/(.*)$#' => array(
		'controller' => 'page',
		'action' => 'view',
		'action_params' => array(
			'page_name' => 1,
		),
	),
	
	// Map report/controller_name/action/params to controllers/report/controller_name.php containing ReportControllerNameController class
	// Views are expected in views/report/controller_name/view_name.php
	'#^(subpath)/([^/]*)/?([^/]*)/?(.*)$#i' => array(
		'sub_path'=>1,
		'controller' => 2,
		'action' => 3,
		'additional_params' => 4,
	),
	
	// Map controller/action/params
	'#^([^/]+)/([^/]+)/?(.*)$#' => array(
		'controller' => 1,
		'action' => 2,
		'additional_params' => 3,
	),
	
	// Map controllers to a default action (not needed if you use the
	// Lvc_Config static setters for default controller name, action
	// name, and action params.)
	'#^([^/]+)/?$#' => array(
		'controller' => 1,
		'action' => 'index',
	),
	
);

?>
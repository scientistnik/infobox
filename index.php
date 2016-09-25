<?php

ini_set('display_errors', 1);

require_once 'core/model.php';
require_once 'core/view.php';
require_once 'core/controller.php';

$routes = explode('/', $_SERVER['REQUEST_URI']);

$local = 0;

//echo $_SERVER['REQUEST_URI']."<br>";

function without_get($str) {
	$pos = strpos($str,"?");
	if ($pos === false)
		return $str;
	else
		return substr($str,0,$pos);
}

if (empty($routes[1+$local])) $con = "";
else $con = without_get($routes[1+$local]);

if (empty($con) || $con == "index.php")
	$controller_name = 'main';
else	
	$controller_name = $con;

if (empty($routes[2+$local])) 
	$action_name = 'index';
else 
	$action_name = without_get($routes[2+$local]);

//echo "Controller: $controller_name <br>";
//echo "Action: $action_name <br>";

$controller_path = "controllers/$controller_name.php";

if (file_exists($controller_path)) {
	include $controller_path;
}
else {
	//error page
	echo "Error controller";
	return;
}

$controller = new $controller_name;
$action = $action_name;

if (method_exists($controller, $action)) {
	$controller->$action();
}
else {
	//error page
	echo "Error action";
	return;
}

?>

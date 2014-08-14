<?php

include 'model.php';

function request() {
	if ($_SERVER['REQUEST_METHOD'] != 'GET')
		return false;
	if (!isset($_GET['call']))
		return false;

	$call = $_GET['call'];
	$args = $_GET;
	unset($args['call']);
	$data = call_user_func_array($call, $args);
	echo json_encode($data);

	return true;
}

if (!getUser()) {
	header('HTTP/1.0 401 Unauthorized');
	exit;
}

$success = request();

if (!$success) {
	header('HTTP/1.0 400 Bad Request');
	exit;
}

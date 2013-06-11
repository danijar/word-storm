<?php

include 'model.php';

if(!getUser()) die('You are not logged in.');

/*********************
 *  AJAX HANDLER     *
 *********************/

if($_SERVER['REQUEST_METHOD'] == 'GET'){
	$call = $_GET['call']; unset($_GET['call']);
	$data = call_user_func_array($call, $_GET);
	echo json_encode($data);
}
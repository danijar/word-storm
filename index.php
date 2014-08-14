<?php

// Development
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Include
include 'model.php';

// Post requests
include 'action.php';

// Load view
header('Content-Type: text/html; charset=utf-8');
if (getUser())
	include 'view/index.php';
else
	include 'view/public.php';

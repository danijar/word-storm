<?php

// development
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

// include
include 'model.php';

// post requests
include 'action.php';

// load view
header('Content-Type: text/html; charset=utf-8');
if(getUser()) include 'view/index.php';
else include 'view/public.php';

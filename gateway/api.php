<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: *');
header("Content-type: application/json; charset=utf-8");
include_once('../framework/initialise/framework.init.php');
global $request;
require_check_abs('application/astromart_api/astromart_api.init.php');
?>
<?php
/*
 * @company: 	Symbiotic Infotech Pvt. Ltd.
 * @copyright: 	© Symbiotic Infotech Pvt. Ltd. 2011
 *				All rights reserved.Any redistribution or reproduction of part
 * 				or all of the contents in any form is prohibited. You may not,
 * 				except with express written permission, distribute or
 * 				commercially exploit or personally use the content.
 * 				Nor may you transmit it or store it in any other media or
 * 				other form of electronic or physical retrieval system.
 *
 * @filename:	action
 * @filetype:	PHP
 * @filedesc:	This is the primary gateway to all web pages
 * 				from the framework. It includes the default
 * 				framework along with other platforms view classes
 * 				to provide a launch pad for most web page rendering
 *
 */

session_start();
//Initialise the gateway
global $gateway,$request,$tsResponse;

//Set the gateway type
$gateway = 'action';

//Initialise the basic framework

include_once '../framework/initialise/framework.init.php';
include_once '../framework/initialise/helper.php';

if(isset($request['responsetype']))
	$responseType = strtolower(trim($request['responsetype']));
else 
	$responseType = strtolower(trim($request['responseType']));

//Initialise the services data

require_check_abs('application/'.$request['application'].'/'.$request['application'].'.init.php');
switch($responseType){
	case "xml":
		header('content-type: text/xml');
		$xml = $tsResponse->arrToXML();
		$request_end = microtime(true);
		$response_time = round(($request_end - $request_start),4);
		console ( LOG_LEVEL_INFO, "API action : " . $request['action']. " XML Output : " . $xml . " : Response in seconds : $response_time " );
		print $xml;
		break;
	default:
		print '';	
}
?>

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
 * @filename:	ajax.init.php
 * @filetype:	PHP
 * @filedesc:	This file is used for intializing the needed modules for Ajax module.
 *
 */
 
 /* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

global $library;
$fileLoaderObj = new fileLoader("ajax");
$fileLoaderObj->addFile("application/qa/model/qa.class.inc",true);
$fileLoaderObj->addFile("application/member/model/member.class.inc",true);
//$fileLoaderObj->addFile("application/academic_tracker/model/academic_tracker.class.inc",true);
$fileLoaderObj->addFile("application/ajax/controller/ajax.service.class.inc");
$library->addLibrary($fileLoaderObj);

if($library->addLibrary($fileLoaderObj) !== true){
	console(LOG_LEVEL_ERROR,"Unable to create library Service");
	return false;
}
$library->loadLibrary("ajax");
$library->loadLibrary("dbqryconstructor");
$library->loadLibrary("dbvalidator");
new AjaxController();

?>
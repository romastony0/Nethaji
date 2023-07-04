<?php
session_start();
if($_REQUEST){
	foreach($_REQUEST as $key => $val){
		$_SESSION[$key] = $val;
	}
	
}
?>
<?php

/*
 * @product: 	Service Delivery Platform
 * @package: 	session
 * @company: 	Symbiotic Infotech Pvt. Ltd.
 * @copyright: 	2011
 * @disclaimer:	to be filled in........
 * 
 * @filename:	session.class.lib
 * @filetype:	PHP
 * @filedesc:	to be filled in........
 * 
 */
 
class Session {
	
	private $valid = false;
	private $error = false;
	private $lasterror;
	
	public function __construct() {
		session_start ();
		$this->init_session ();
	}
	
	private function init_session() {
		$ip = $_SERVER ['REMOTE_ADDR'];
		$user_agent = ! empty ( $_SERVER ['HTTP_USER_AGENT'] ) ? $_SERVER ['HTTP_USER_AGENT'] : "";
		
		if (! ereg ( "proxy\.aol\.com$", gethostbyaddr ( $ip ) )) { //if surfer's ISP is not AOL
			if ($this->isSetVar ( "config" )) {
				if ($ip == $this->getVar ( "config.ip" ) && $user_agent == $this->getVar ( "config.user_agent" ))
					$this->valid = true;
				else {
					$this->valid = false;
					$this->setError ( 1, "Hacking attempt !!!" );
				}
			} else {
				$this->setVar ( "config.ip", $ip );
				$this->setVar ( "config.user_agent", $user_agent );
				$this->valid = true;
			}
		} else { //if surfer's ISP is AOL, we can't verify IP adress
			

			if (! $this->isSetVar ( "config" )) {
				$this->setVar ( "config.ip", $ip );
				$this->setVar ( "config.user_agent", $user_agent );
			}
			
			$this->valid = true; //So the session validity is always "true"
		}
	
	}
	
	/* 
	 * Set a session variable
	 * $name : variable name. You can set table as session variable by using the dot character.
	 *			For example : setVar("config.ip", "192.168.0.1") <=> $_SESSION['config']['ip'] = "192.168.0.1"
	 * $value : value
	 *
	 */
	public function setVar($name, $value) {
		$expr = $this->getNames ( $name );
		$expr .= " = \$value;";
		eval ( $expr );
	}
	
	/* 
	 * Get a session variable
	 * $name : 		variable name.
	 * Examples : 	getVar("test") <=> $_SESSION['test']
	 *			 	getVar("config.ip") <=> $_SESSION['config']['ip']
	 */
	public function getVar($name) {
		if ($this->isSetVar ( $name )) {
			$result = eval ( "return " . $this->getNames ( $name ) . ";" );
			return $result;
		}
		$this->setError ( 2, "$name doesn't exist" );
		return false;
	}
	
	/* 
	 * Unset a session variable
	 * $name : variable name
	 */
	public function unsetVar($name) {
		if ($this->isSetVar ( $name )) {
			$var = $this->getNames ( $name );
			eval ( "unset($var);" );
			return true;
		}
		$this->setError ( 2, "$name doesn't exist" );
		return false;
	}
	
	/* 
	 * Test if a session variable has been set
	 * $name: variable name
	 */
	public function isSetVar($name) {
		$expr = "return isset(" . $this->getNames ( $name ) . ");";
		return eval ( $expr );
	}
	
	/* 
	 * Test if the session has not been hacked.
	 * Use it at the beginning of your script
	 */
	public function isValid() {
		return $this->valid;
	}
	
	private function getNames($name) {
		if (is_string ( $name )) {
			if (strpos ( $name, "." ))
				$names = explode ( ".", $name );
			else
				$names = array ($name );
			
			$expr = $expr = "\$_SESSION";
			foreach ( $names as $item )
				$expr .= is_numeric ( $item ) ? "[$item]" : "['$item']";
			
			return $expr;
		}
		$this->setError ( 3, "$name is not a string" );
		return false;
	}
	
	private function setError($err_num, $err_msg) {
		if ($this->error === false)
			$this->error = array ();
		$this->error [$err_num] = $err_msg;
		$this->lasterror = $err_num;
	}
	
	public function getError($err_num) {
		if (! is_array ( $this->error ) || ! array_key_exists ( $err_num, $this->error ))
			return false;
		return $this->error [$err_num];
	}
	
	/* 
	 * Get the last error
	 */
	public function getLastError() {
		if ($this->lasterror)
			return $this->getError ( $this->lasterror );
		return false;
	}
	
	public function __destruct() {
		session_unset ();
	}
	
	/* 
	 * Add a session identifier to an url
	 * $url
	 */
	public function appendSID($url) {
		$sid = "";
		if (strlen ( SID )) {
			
			if (strpos ( $url, "?" ) === true)
				$sep = "&";
			else
				$sep = "?";
			
			$sid = $sep . SID;
		
		}
		return $url . $sid;
	}

}


/*
include("class_session.inc.php");$session = new Session();
//test session
if($session->isValid()){//Test if the session has not been stolen
	print_r($session->getVar("test"));unset($session);session_destroy();
}
else 
	echo $session->getLastError();
//init session
$session->setVar("test", array("a"=>1, "b"=>2));$session->setVar("test.c", 3);
echo "<p><a href=\"test_session.php\">Next</a></p>";	
*/

?>
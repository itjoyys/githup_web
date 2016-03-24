<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller {

	public function __construct() {
		parent::__construct();
	}


	public function index() {
		if(!empty($_GET['intr'])){
			$_SESSION['intr'] = $_GET['intr'];
		}
		$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])
 						&& $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://':"http://";
		$this->add('OLD_URL', $http_type . $_SERVER['HTTP_HOST']);
		$this->display('index.html');
	}


}
?>
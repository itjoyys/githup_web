<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lot extends MY_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index(){
		$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])
 						&& $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://':"http://";
		$this->add('OLD_URL', $http_type . $_SERVER['HTTP_HOST']);
		$this->display('lot.html');
	}

}
?>
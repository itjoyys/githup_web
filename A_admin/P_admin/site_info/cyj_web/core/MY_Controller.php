<?php if (!defined('BASEPATH')) {
	exit('No direct access allowed.');
}

class MY_Controller extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->cismarty->assign('site_url',URL);
	}

	public function add($key, $val) {
		$this->cismarty->assign($key, $val);
	}

	public function assign($key, $val) {
		$this->cismarty->assign($key, $val);
	}

	public function display($html) {
		$this->cismarty->display($html);
	}
}
?>
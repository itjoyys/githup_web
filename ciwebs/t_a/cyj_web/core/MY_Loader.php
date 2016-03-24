<?php if (!defined('BASEPATH')) {
	exit('No direct access allowed.');
}

class MY_Loader extends CI_Loader {

	public function __construct() {
		parent::__construct();
		$this->_ci_view_paths = array(CVIEWPATH=> TRUE);
	}
}
?>
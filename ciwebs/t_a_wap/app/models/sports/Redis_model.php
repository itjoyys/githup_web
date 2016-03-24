<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Redis_model extends MY_Model {

	function __construct() {
		$this->redis_db();
	}
	public function save(){
		$this->redis->setex(1,1,1);
	}


}
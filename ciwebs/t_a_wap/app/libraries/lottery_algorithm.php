<?php
if (!defined('BASEPATH')) {
	EXIT('No direct script asscess allowed');
}

require_once APPPATH . 'libraries/Lottery/gdklsf.algorithm.php';
require_once APPPATH . 'libraries/Lottery/cqklsf.algorithm.php';
require_once APPPATH . 'libraries/Lottery/cqssc.algorithm.php';
require_once APPPATH . 'libraries/Lottery/fc3d.algorithm.php';
require_once APPPATH . 'libraries/Lottery/pk10.algorithm.php';
require_once APPPATH . 'libraries/Lottery/pls.algorithm.php';
require_once APPPATH . 'libraries/Lottery/bjkl8.algorithm.php';

/**
 * 彩票算法类
 */
class Lottery_Algorithm {

	private $lottery = null;

	public function __construct() {
	}

	/**
	 * 根据彩票类型设置lottery
	 * @param [type] $params [description]
	 * $params = array(
	 * 		"cate"=>"gdklsf",
	 * 		'data_info'->[object]
	 * )
	 */
	public function set_lottery($params) {
		//大写第一个字母
		$cate = ucfirst(strtolower($params['cate']));
		$_classname = $cate . "_Aalgorithm";
		$this->lottery = new $_classname;
		$this->lottery->set_ball($params['data_info']);
	}

	public function is_ok() {
		return $this->lottery->is_ok;
	}

	public function win_or_lost($bet) {
		return $this->lottery->win_or_lost($bet);
	}
}

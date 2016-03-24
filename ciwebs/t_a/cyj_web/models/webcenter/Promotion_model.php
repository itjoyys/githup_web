<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Promotion_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	/**
	 * 获取优惠分类
	 * @param  [array] $map [查询条件]
	 * @return [array]      [优惠]
	 * PK 黄
	 */
	public function get_promot_cate($map) {
		if($_SESSION['ty'] == 14){
			$data = $this->db->from("info_activity_edit")->where($map)->order_by('sort ASC')->get()->result_array();
		}else{
			$data = $this->db->from("info_activity_use")->where($map)->order_by('sort ASC')->get()->result_array();
		}
		return $data;
	}

	/**
	 * 优惠活动内页
	 * @return [array] [优惠活动分类和内容]
	 * PK 黄
	 */
	public function get_promotions(){
		$info = array();
		$info['index_id'] = INDEX_ID;
		$info['site_id'] = SITEID;
		$info['state'] = 1;
		$info['ctype'] = 2;
		$data = $this->get_promot_cate($info);
		if(!empty($data) && is_array($data)){
			foreach ($data as $key => $value) {
				$data[$key]['status'] = ','.$value['pid'];
			}
			$promotion['data'] = $data;
		}
		$info['ctype'] = 1;
		$cate = $this->get_promot_cate($info);
		if(!empty($cate) && is_array($cate)){
			$promotion['cate'] = $cate;
		}
		return $promotion;
	}


}


?>
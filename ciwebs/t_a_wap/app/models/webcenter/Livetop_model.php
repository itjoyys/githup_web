<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Livetop_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	/*
	 *视讯
	 */
	function get_livetop() {
	    //视讯配置
	    $map = array();
	    $map['site_id'] = SITEID;
	    if (defined('INDEX_ID')) {
	        $map['index_id'] = INDEX_ID;
	    }
	    $video_config = $this->db->from('web_config')->where($map)->select('video_module')->get()->row_array();
	    $video_config = explode(',',$video_config['video_module']);
	    return $video_config;
	}
}

?>
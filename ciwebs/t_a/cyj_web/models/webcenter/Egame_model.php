<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Egame_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	//获取电子配置
	public function get_gameconf($map){
		$video_config = $this->db->from('web_config')->where($map)->select('video_module')->get()->row_array();
	    $video_config = explode(',',$video_config['video_module']);
	    if(in_array('eg', $video_config)){
	    	$game_config[] = 'EG';
	    }
	    if(in_array('mg', $video_config)){
	    	$game_config[] = 'MG';
	    }
	    if(in_array('ag', $video_config)){
	    	$game_config[] = 'AG';
	    }
	    if(in_array('bbin', $video_config)){
	    	$game_config[] = 'BBIN';
	    }
	    if(in_array('pt', $video_config)){
	    	$game_config[] = 'PT';
	    }
	    
	    /*if(in_array('gpi', $video_config)){
	    	$game_config[] = 'GPI';
	    }*/
	    return $game_config;
	}

	//获取游戏
	public function get_game($map,$like,$limit) {
		$start = $limit * 60;
		$this->public_db->select('gameid,name,image,type');
		$this->public_db->where($map);
		if(!empty($like)){
			$this->public_db->like('name',$like);
		}
		$this->public_db->order_by('id ASC');
		$game = $this->public_db->get('mg_game', 60, $start)->result_array();
		return $game;
	}

	function get_egames($per,$offset,$map) {
		if (!empty($map)) {
			$this->public_db->where($map);
		}
		$this->public_db->order_by('id ASC');
		return $this->public_db->get('mg_game',$per,$offset)->result_array();
	}

}
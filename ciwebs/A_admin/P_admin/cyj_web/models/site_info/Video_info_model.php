<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Video_info_model extends MY_Model {

	function __construct() {
		//$this->init_db();
	}
	public function get_views($index_id) {
		$data=$this->db->from('web_config')->where("index_id= '". $index_id."' and site_id = '".$_SESSION['site_id']."'")->order_by('id asc')->get()->result_array();
		return $data[0];
	}

	public function get_edit($index_id,$name){
		$map['video_module'] = $name;
		$row=$this->db->from('web_config')->where("index_id= '". $index_id."' and site_id = '".$_SESSION['site_id']."'")->update('web_config',$map);
		return $row;
	}

		//获取视讯图片
	public function get_imgs($index_id){
        $db_model['tab'] = 'info_video';
        $db_model['type'] = 1;
        return $this->M($db_model)->field("type,img_url")->where(array('site_id'=>$_SESSION['site_id'],'index_id'=>$index_id))->select('type');
	}

	//更新插入视讯自定义图片信息
	public function up_video_img($arr){
        $db_model['tab'] = 'info_video';
        $db_model['type'] = 1;
        return $this->M($db_model)->add_update($arr);
	}

}

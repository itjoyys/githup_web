<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

//域名管理
class Server_domain_model extends MY_Model {

	function __construct() {
		$this->init_db();
	}

	//获取域名
	public function get_site_domain($map,$limit){
		$db_model = array();
		$db_model['tab'] = 'server_domain';
		$db_model['type'] = 4;

		if ($limit) {
			//分页查
		    return $this->M($db_model)->where($map)->order("id desc")->limit($limit)->select();
		}else{
			//查询总数
            return $this->M($db_model)->where($map)->count();
		}
	}

    //获取支付域名
    public function get_pay_domain($map){
        $db_model = array();
		$db_model['tab'] = 'server_pay_domain';
		$db_model['type'] = 4;

		return $this->M($db_model)->where($map)->select();
    }

    //获取总数
	public function get_site_domainnums($index_id = 'a'){
		$db_model = array();
		$db_model['tab'] = 'web_config';
		$db_model['type'] = 1;
		$map = array();
		$map['site_id'] = $_SESSION['site_id'];
		$map['index_id'] = $index_id;
	    //查询总数
        return $this->M($db_model)->where($map)->getField('domain_num');
	}


}
<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Blance_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	//会员启用余额
	public function get_user_blance_on($map){
      $db_model['tab'] = 'k_user';
		  $db_model['type'] = 1;
	    return $this->M($db_model)->field("sum(money) as money,sum(og_money) as og,sum(mg_money) as mg,sum(ag_money) as ag,sum(bbin_money) as bbin,sum(ct_money) as ct,sum(lebo_money) as lebo,sum(pt_money) as pt")->where($map)->find();
	}

  //会员停用金额
  public function get_user_blance($map){
      $db_model['tab'] = 'k_user';
      $db_model['type'] = 1;
      return $this->M($db_model)->field("sum(money) as money,sum(og_money) as og,sum(mg_money) as mg,sum(ag_money) as ag,sum(bbin_money) as bbin,sum(ct_money) as ct,sum(lebo_money) as lebo,sum(pt_money) as pt")->where($map)->find();
  }

      //查询代理
  public function  get_agent($aid,$field){

      $db_model['tab'] = 'k_user';
	    $db_model['type'] = 1;
	    $map[$field] = array('>' , 0);
      $map['shiwan'] = 0;
      $map['site_id'] = $_SESSION['site_id'];
      if (!empty($aid)) {
          $map['agent_id'] = $aid;
      }
	    return $this->M($db_model)->field("agent_id,SUM( CASE WHEN is_delete = 0 THEN ".$field." ELSE 0 END) as yes,SUM( CASE WHEN is_delete = 2 THEN ".$field." ELSE 0 END) as no")->where($map)->group('agent_id')->select('agent_id');
  }

    //查询代理用户
    public function get_agent_name($agent_user){
       $db_model['tab'] = 'k_user_agent';
	     $db_model['type'] = 1;
       $mapa['site_id'] = $_SESSION['site_id'];
       $mapa['agent_type'] = 'a_t';
       if (!empty($agent_user)) {
          $mapa['agent_user'] = $agent_user;
       }
	     return $this->M($db_model)->field('agent_user ,id,agent_name')->where($mapa)->select('id');
    }

    public function get_mem_cash($map,$field,$limit){
        $db_model['tab'] = 'k_user';
	      $db_model['type'] = 1;


	  	  return $this->M($db_model)->field("username,( CASE WHEN is_delete = 0 THEN ".$field." ELSE 0 END) as y,( CASE WHEN is_delete = 2 THEN ".$field." ELSE 0 END) as n")->where($map)->limit($limit)->select();
    }


    //获取视讯配置
   public function get_video_config(){
       $db_model = array();
       $db_model['tab'] = 'web_config';
       $db_model['type'] = 1;

       $data = $this->M($db_model)->where(array('site_id'=>$_SESSION['site_id']))->getField('video_module');

       if ($data) {
           return explode(',',$data);
       }else{
           return 0;
       }
   }


}
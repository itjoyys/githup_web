<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}


class Member_index_model extends MY_Model
{

    function __construct() {
        parent::__construct();
    }
    public function get_sp_games_view($map){

    }
    //获取在线会员
    public  function on_user(){
        $redis_key = 'ulg'.CLUSTER_ID.'_'.$_SESSION['site_id'].'*';
        $redis = new Redis();
        $redis->connect(REDIS_HOST,REDIS_PORT);
        $on_user = $redis->keys($redis_key);
        if (!empty($on_user)) {
            foreach ($on_user as $key => $val) {
                $new_user_on[] = str_replace('ulg'.CLUSTER_ID.'_'.$_SESSION['site_id'],'',$val);
            }
            unset($on_user);
        }else{
            $new_user_on = 0;
        }
        return $new_user_on;
    }

    public function update_user_status($map){
        $this->db->trans_status();
        $return = $this->db->where($map['where'])->update($map['table'],$map['data']);
        if(!empty($map['table2'])&&!empty($map['data2'])&&!empty($map['where2'])){
            $return = $this->db->where($map['where2'])->update($map['table2'],$map['data2']);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

  public function get_pagent_user($pid,$list=''){
      $map['table'] = 'k_user_agent';
      $map['select']='agent_user,agent_type,pid';
      $map['where']['id'] = $pid;
      $rows = $this->get_table_one($map);
      $list[$rows['agent_type']] = $rows['agent_user'];
      if($rows['pid']){
      $list = array_merge($this->get_pagent_user($rows['pid'],$list),$list);
      }

      return $list;
  }
  //获取体育设置列表数据
    public function get_spType($val,$uid,$aid){
        $db_model['tab'] = 'sp_games_view';
        $db_model['type'] = 1;
        $a = array();
        $a[0] = $this->M($db_model)->join("join k_user_agent_sport_set on k_user_agent_sport_set.type_id = sp_games_view.id")->where("sp_games_view.type = '" . $val . "' and k_user_agent_sport_set.aid = '" . $aid . "'")->select("t_type");
        $a[1] = $this->M($db_model)->join("join k_user_sport_set on k_user_sport_set.type_id = sp_games_view.id")->where("sp_games_view.type = '" . $val . "' and k_user_sport_set.uid = '" . $uid . "'")->select("t_type");

        return $a;
    }
    //获取彩票设置列表数据
    public function get_FcType($val,$uid,$aid){
        $db_model['tab'] = 'fc_games_view';
        $db_model['type'] = 1;
        $a = array();
        $a[0] = $this->M($db_model)->join("join k_user_agent_fc_set on k_user_agent_fc_set.type_id = fc_games_view.id")->where("fc_games_view.fc_type = '" . $val . "' and k_user_agent_fc_set.aid = '" . $aid . "'")->select("type");
        $a[1] = $this->M($db_model)->join("join k_user_fc_set on k_user_fc_set.type_id = fc_games_view.id")->where("fc_games_view.fc_type = '" . $val . "' and k_user_fc_set.uid = '" . $uid . "'")->select("type");

        return $a;
    }
    //获取会员代理设定数据$type=1是体育，2是视讯
    public function get_Uagent($mapA,$type){
        if($type==2){
            $db_model['tab'] = 'k_user_agent_fc_set';
        }else{
            $db_model['tab'] = 'k_user_agent_sport_set';
        }
        $db_model['type'] = 1;

        $updata=$this->M($db_model)->where($mapA)->find();
        return $updata;
    }
    //获取会员设定数据$type=1是体育，2是视讯
    public function get_Uaset($mapA,$type){
        if($type==2){
            $db_model['tab'] = 'k_user_fc_set';
        }else{
            $db_model['tab'] = 'k_user_sport_set';
        }
        $db_model['type'] = 1;

        $data=$this->M($db_model)->where($mapA)->find();
        return $data;
    }
    //添加会员设定数据$type=1是体育，2是视讯
    public function Add_Uaset($map,$type){
        if($type==2){
            $db_model['tab'] = 'k_user_fc_set';
        }else{
            $db_model['tab'] = 'k_user_sport_set';
        }
        $db_model['type'] = 1;
        return $this->M($db_model)->add($map);
    }
    //修改会员设定数据$type=1是体育，2是视讯
    public function Update_Uaset($idS,$map,$type){
        if($type==2){
            $db_model['tab'] = 'k_user_fc_set';
        }else{
            $db_model['tab'] = 'k_user_sport_set';
        }
        $db_model['type'] = 1;
        return $this->M($db_model)->where("id = '" . $idS . "'")->update($map);
    }

     //传递子分类ID返回所有的父级分类
        Static Public function getParents($agent,$id) {
            $arr = array();
            foreach ($agent as $v) {
                if ($v['id'] == $id) {
                   $arr[$v['agent_type']] = $v['agent_user'];
                   $arr['intr'] = $v['intr'];
                   $arr = array_merge(self::getParents($agent, $v['pid']), $arr);
                }
            }
            return $arr;
        }
    function get_agents($index_id,$site_id,$agentsstr,$is_quanxian){
             // 查询代理
        $map = array();
         if (!empty($index_id)) {
            $map['index_id'] = $index_id;
        }else{
            $map['index_id'] = $_SESSION['index_id'];
        }
         if ($is_quanxian == 1){
           $map['id'] = array('in','('.$agentsstr.')');
        }elseif($is_quanxian == 0){
           $map['id'] = $_SESSION['agent_id'];
        }
        $map['site_id'] = $site_id;
        $map['is_demo'] = 0;
        $db_model = array();
        $db_model['tab'] = 'k_user_agent';
        $db_model['type'] = 1;
        return $this->M($db_model)->where($map)->select();
    }
}
<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * 报告
 */
class Agent_index_model extends MY_Model
{

    function __construct() {
        //$this->init_db();
    }

   //查询总代旗下所有代理
  public function get_agents($id){
      $db_model['tab'] = 'k_user_agent';
      $db_model['type'] = 1;

      $map = array();
      $map['site_id'] = $_SESSION['site_id'];
      $map['pid'] = $id;

      return $this->M($db_model)->field("id")->where($map)->select('id');
  }

  //更新会员总代股东
  public function update_agents($ua_id,$sh_id,$at_ids){
      $db_model['tab'] = 'k_user';
      $db_model['type'] = 1;

      $map = array();
      $map['site_id'] = $_SESSION['site_id'];
      $map['agent_id'] = array('in','('.$at_ids.')');

      return $this->M($db_model)->where($map)->update(array('ua_id'=>$ua_id,'sh_id'=>$sh_id));
  }

   //获取对应代理数据
  public function get_agents_list($map,$limit,$order = 'id desc'){
      $db_model['tab'] = 'k_user_agent';
      $db_model['type'] = 1;

      return $this->M($db_model)->where($map)->limit($limit)->order($order)->select('id');
  }

  public function get_users_data($strs,$type = 'a_t'){
      $db_model['tab'] = 'k_user';
      $db_model['type'] = 1;

      $map['site_id'] = $_SESSION['site_id'];
      if ($type == 'a_t') {
          $field = 'agent_id';
      }elseif($type == 'u_a'){
          $field = 'ua_id';
      }elseif($type == 's_h'){
          $field = 'sh_id';
      }
      $map[$field] = array('in','('.implode(',',$strs).')');

      return $this->M($db_model)->field("count(1) as num,".$field)->where($map)->group($field)->select($field);
  }

   public function get_pagent_user($pid){
       $map['table'] = 'k_user_agent';
       $map['select']='agent_user,sports_scale,lottery_scale,video_scale,index_id';
       $map['where']['id'] = $pid;
       $rows = $this->get_table_one($map);
       return $rows;
   }

   public function get_sibling_del($agent_id){
       $map['table'] = 'sys_admin';
       $map['select'] = 'is_delete';
       $map['where']['agent_id'] = $agent_id;
       $res = $this->get_table_one($map);
       foreach ($res as $key => $value) {
          return $value;
       }
   }

   public function get_intr(){
       $map['table']='k_user_agent';
       $map['select'] = "intr";
       $map['order'] = "intr desc";
       $intr =  $this->get_table_one($map);
       return $intr['intr'];
   }

   //代理申请处理
   public function agent_apply_do($id,$arr,$brr){
       $this->db->trans_status();
       $this->db->insert('sys_admin',$brr);

       $this->db->where(array('site_id'=>$_SESSION['site_id'],'id'=>$id));
       $this->db->update('k_user_agent',$arr);

       if ($this->db->trans_status() === FALSE) {
           $this->db->trans_rollback();
           return FALSE;
       } else {
           $this->db->trans_commit();
           return TRUE;
       }
   }

     //获取在线总代(*)
  public function on_user(){
      $redis_key = 'aglg'.$_SESSION['site_id'].'*';
      $redis = new Redis();
      $redis->connect(REDIS_HOST,REDIS_PORT);
      $on_user = $redis->keys($redis_key);
      if (!empty($on_user)) {
          foreach ($on_user as $key => $val) {
            $new_user_on[] = str_replace('aglg'.$_SESSION['site_id'],'',$val);
          }
          unset($on_user);
      }else{
          $new_user_on = 0;
      }
      return $new_user_on;
  }

   //添加股东 总代 代理
   public  function set_agent_user($dataA,$dataS){
       $this->db->trans_status();

       $this->db->insert('k_user_agent',$dataA);
       $dataS['agent_id'] = $this->db->insert_id();
       $this->db->insert('sys_admin',$dataS);

       if ($this->db->trans_status() === FALSE) {
           $this->db->trans_rollback();
           return FALSE;
       } else {
           $this->db->trans_commit();
           return $dataS['agent_id'];
       }
   }

   public  function edit_agent_user($map){
       $this->db->trans_status();
       $return = $this->db->where($map['where'])->update($map['table'],$map['data']);
       if(!empty($map['table2'])&&!empty($map['data2'])){
           $return = $this->db->insert($map['table2'],$map['data2']);

       }
       if ($this->db->trans_status() === FALSE) {
           $this->db->trans_rollback();
           return FALSE;
       } else {
           $this->db->trans_commit();
           return $map['data2']['agent_id'];
       }
   }
  //股东代理总代 更新
  public  function update_agent($id,$data){
      $this->db->trans_status();
      $this->db->where(array('id'=>$id,'site_id'=>$_SESSION['site_id']))->update('k_user_agent',$data);

      $data_s['about'] = $data['agent_name'];
      $data_s['login_pwd'] = $data['agent_pwd'];

      $this->db->where(array('agent_id'=>$id,'site_id'=>$_SESSION['site_id']))->update('sys_admin',$data_s);

      if ($this->db->trans_status() === FALSE) {
          $this->db->trans_rollback();
          return FALSE;
      } else {
          $this->db->trans_commit();
          return true;
      }
  }
   public function agent_setAdd($aid,$pid,$site_id){
       $spState = $this->agent_sport_set($aid,$pid,$site_id);
       $fcState = $this->agent_fc_set($aid,$pid,$site_id);
       if ($spState && $fcState) {
           return 1;
       }else{
           return 0;
       }
   }

   //股东添加 代理 aid 当前股东id pid上一级id

   private function agent_sport_set($aid,$pid,$site_id){
       $map['table']='k_user_agent_sport_set';
       $map['where']['site_id'] = $site_id;
       if (empty($pid)) {
           //为空表示股东添加
           $map['where']['is_default'] = 1;
       }else{
           $map['where']['aid'] = $pid;
       }
      $spSetdata = $this->get_table($map);
       $inum = 0;
       $map1['table'] = 'k_user_agent_sport_set';
       foreach ($spSetdata as $key => &$val) {
           $val['aid'] = $aid;
           unset($val['id']);
           unset($val['is_default']);
           $map1['data']=$val;
           $itate = $this->create_table($map1);
           if ($itate) {
               $inum++;
           }
       }
       return $inum;
   }

   private function agent_fc_set($aid,$pid,$site_id){
       $map['table']='k_user_agent_fc_set';
       $map['where']['site_id'] = $site_id;
       if (empty($pid)) {
           //为空表示股东添加
           $map['where']['is_default'] = 1;
       }else{
           $map['where']['aid'] = $pid;
       }
       $spSetdata = $this->get_table($map);
       $inum = 0;
       $map1['table'] = 'k_user_agent_fc_set';
       foreach ($spSetdata as $key => &$val) {
           $val['aid'] = $aid;
           unset($val['id']);
           unset($val['is_default']);
           $map1['data']=$val;
           $itate = $this->create_table($map1);
           if ($itate) {
               $inum++;
           }
       }
       return $inum;
   }
   //彩票设定添加
   //
      //获取所有代理
   public function get_agentsdata(){
       $db_model = array();
       $db_model['tab'] = 'k_user_agent';
       $db_model['type'] = 1;

       $map = array();
       $map['agent_type'] = 'a_t';
       $map['is_delete'] = 0;
       $map['site_id'] = $_SESSION['site_id'];
       return $this->M($db_model)->field("id,agent_user,agent_name")->where($map)->select('id');
   }

   public function get_agent_domain($map = array(),$limit){
       $db_model = array();
       $db_model['tab'] = 'k_user_agent_domain';
       $db_model['type'] = 1;

       if ($limit) {
        //分页查
          return $this->M($db_model)->where($map)->order("ID DESC")->limit($limit)->select();
       }else{
        //查询总数
          return $this->M($db_model)->where($map)->count();
       }
   }

   //代理推广域名添加
   public function agent_domain_add_do($arr = array(),$id =''){
       $db_model = array();
       $db_model['tab'] = 'k_user_agent_domain';
       $db_model['type'] = 1;

       if ($id) {
         //更新
          return $this->M($db_model)->where(array('id'=>$id))->update($arr);
       }else{
         //添加
          $arr['site_id'] = $_SESSION['site_id'];
          $arr['state'] = 1;
          $arr['add_date'] = date('Y-m-d H:i:s');
          return $this->M($db_model)->add($arr);
       }
   }
}
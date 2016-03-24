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

     function get_agents($index_id,$site_id,$agentsstr,$is_quanxian,$mem_order,$mem_sort,$agent_user,$is_delete,$limit){
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
        if (! empty($mem_order) && ! empty($mem_sort)) {
            $ord = $mem_sort . " " . $mem_order;
        } else {
            $ord = "`agent_name` desc";
        }
        if(!empty($agent_user)){
          $map['agent_user'] = array('like','%'.$agent_user.'%');
        }

         if (isset($is_delete) && $is_delete != '') {
            $map['where']['is_delete'] = $is_delete;
        }
        $map['site_id'] = $site_id;
        $map['is_demo'] = 0;
        $db_model = array();
        $db_model['tab'] = 'k_user_agent';
        $db_model['type'] = 1;
        return $this->M($db_model)->where($map)->order($ord)->limit($limit)->select();
    }

    function get_all_agents(){
             // 查询代理

        $map = array();
        $map['index_id'] = $_SESSION['index_id'];
        $agentsstr = $_SESSION['agent_ids'];
        $map['id'] = array('in','('.$agentsstr.')');
        $map['site_id'] = $_SESSION['site_id'];
        $map['is_demo'] = 0;
        $db_model = array();
        $db_model['tab'] = 'k_user_agent';
        $db_model['type'] = 1;
        return $this->M($db_model)->where($map)->select();
    }
}
<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}


class Member_index_model extends MY_Model
{

    function __construct() {
        parent::__construct();
    }

       //获取注册人数
    public function get_user_reg($index_id){
        $db_model['tab'] = 'k_user';
        $db_model['type'] = 1;

        $map = array();
        $map['site_id'] = $_SESSION['site_id'];
        $map['reg_date'] = array('like',date('Y-m-d').'%');
        $map['shiwan'] = 0;
        if (!empty($index_id)) {
            $map['index_id'] = $index_id;
        }
        return $this->M($db_model)->where($map)->count();
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

        //获取注册人数
    public function user_new_reg($index_id){
        $redis = new Redis();
        $redis->connect(REDIS_HOST,REDIS_PORT);
        $on_user = 0;
        if ($_SESSION['index_id']) {
            if (empty($index_id)) {
                $map_r['select'] = 'index_id';
                $map_r['table'] = 'web_config';
                $map_r['where']['site_id'] = $_SESSION['site_id'];
                $sites = $this->rget($map_r);
                foreach ($sites as $key => $val) {
                    $on_user_tmp = 0;
                    $redis_key = $_SESSION['site_id'].'_'.date('Ymd').$val['index_id'];
                    $on_user_tmp = $redis->lsize($redis_key);
                    $on_user += $on_user_tmp;
                }
            }else{
                $redis_key = $_SESSION['site_id'].'_'.date('Ymd').$index_id;
                $on_user = $redis->lsize($redis_key);
            }
        }else{
            $redis_key = $_SESSION['site_id'].'_'.date('Ymd').'a';
            $on_user = $redis->lsize($redis_key);
        }
        return $on_user;
    }

    //查询代理
    public function get_agents($index_id){
        $redis = new Redis();
        $redis->connect(REDIS_HOST,REDIS_PORT);
        $redis_key = $_SESSION['site_id'].'_agents_'.$index_id;
        //$redis->delete($redis_key);
        $vdata = $redis->hmget($redis_key);
        if (empty($vdata)) {
            $db_model['tab'] = 'k_user_agent';
            $db_model['type'] = 1;

            $map = array();
            $map['site_id'] = $_SESSION['site_id'];
            $map['agent_type'] = 'a_t';
            $map['is_demo'] = 0;
            if (!empty($index_id)) {
                $map['index_id'] = $index_id;
            }
            $mdata = $this->M($db_model)->field("id,agent_user,agent_login_user,agent_name")->where($map)->select('id');
            foreach ($mdata as $key => $val) {
                $agent_json = json_encode($val, JSON_UNESCAPED_UNICODE);
                $agent_json = str_replace('"', '-', $agent_json);
                $hset[$key] = $agent_json;
            }
            $redis->hmset($redis_key,$hset);
        }else{
            $vdata = str_replace('-', '"', $vdata);
            foreach ($vdata as $key => $val) {
                $mdate[$key] = json_decode($val,true);//转数组
            }
        }
        return $mdata;
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
        return array_filter($new_user_on);
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
        $db_model['is_port'] = 1;//读取从库
        $a = array();
        $a[0] = $this->M($db_model)->join("join k_user_agent_sport_set on k_user_agent_sport_set.type_id = sp_games_view.id")->where("sp_games_view.type = '" . $val . "' and k_user_agent_sport_set.aid = '" . $aid . "'")->select("t_type");
        $a[1] = $this->M($db_model)->join("join k_user_sport_set on k_user_sport_set.type_id = sp_games_view.id")->where("sp_games_view.type = '" . $val . "' and k_user_sport_set.uid = '" . $uid . "'")->select("t_type");

        return $a;
    }
    //获取彩票设置列表数据
    public function get_FcType($val,$uid,$aid){
        $db_model['tab'] = 'fc_games_view';
        $db_model['type'] = 1;
        $db_model['is_port'] = 1;//读取从库
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
        $db_model['is_port'] = 1;//读取从库
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
        $db_model['is_port'] = 1;//读取从库
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
        //获取会员数
        public function get_menber_sum($map){
            $db_model['tab'] = $map['table'];
            $db_model['type'] = 1;

             //判断是否是获取全站会员数量
            if (count($map) == 2) {
                $redis_key = $_SESSION['site_id'].'_users_num';
                $redis = new Redis();
                $redis->connect(REDIS_HOST,REDIS_PORT);
                $user_num = $redis->get($redis_key);
                if (empty($user_num)) {
                    $user_num = $this->M($db_model)->field("count('k_user.uid') as menber_num")->where($map['where'])->getField('menber_num');
                    $redis->set($redis_key,$user_num);
                }
                return $user_num;
            }else{
                if(!empty($map['where_in'])){
                    $in=  implode($map['where_in']['data'],',');
                    $map['where']['k_user.uid'] = array('in','('.$in.')');
                 }
                 if(!empty($map['like'])){
                    $map['where'][$map['like']['title']] = array('like','%'.$map['like']['match'].'%');

                 }
                 return $this->M($db_model)->field("count('k_user.uid') as menber_num")->where($map['where'])->getField('menber_num');
            }

            // if(!empty($map['where_in'])){
            //     $in=  implode($map['where_in']['data'],',');
            //     $map['where']['k_user.uid'] = array('in','('.$in.')');
            //  }
            //  if(!empty($map['like'])){
            //    $map['where'][$map['like']['title']] = array('like','%'.$map['like']['match'].'%');

            //  }
            //  return $this->M($db_model)->field("count('k_user.id') as menber_num")->where($map['where'])->getField('menber_num');

        }
        //获取会员列表数据
        public function get_menber_list($map){
            $db_model['tab'] = $map['table'];
            $db_model['type'] = 1;
            $limit=$map['offset'].','.$map['pagecount'];
            if(!empty($map['where_in'])){
                $in=  implode($map['where_in']['data'],',');
                $map['where']['k_user.uid'] = array('in','('.$in.')');
            }
            if(!empty($map['like'])){
               $map['where'][$map['like']['title']] = array('like','%'.$map['like']['match'].'%');
             }
            return $this->M($db_model)->field($map['select'])->limit($limit)->where($map['where'])->order($map['order'])->select();

        }
}
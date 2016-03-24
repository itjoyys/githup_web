<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class User_model extends MY_Model {

    function __construct() {
        $this->init_db();
    }
     
//用户信息
    public function get_user($d){
        $field    =$d['field'];
        $uid      =$d['uid'];
        $site_id  =$d['site_id'];
        $index_id =$d['index_id'];
        $sql  ="select $field from k_user where uid=? and site_id=? and index_id=? limit 0,1";
        $query=$this->private_db->query($sql, array($uid,$site_id,$index_id));
        $row  = $query->row_array();
        return $row;
    }
    public function get_user_money($uid){             
        $sql="select money from k_user where uid=? limit 0,1";
        $query=$this->private_db->query($sql, array($uid));
        $row = $query->row_array();
        return $row;
    }
    public function redis_update_token($value){
         
        $this->load->library('sportsbet');
        $redis_token_key=$this->sportsbet->make_token_key($value[0]);
        $value['token']=md5($redis_token_key.rand(1,99999));
        $value['uid']=$value[0];
        $value['username']=$value[1];
        $value['agentid']=$value[2];
        $value['levelid']=$value[3];
        $value['shiwan']=$value[4];
        $value['siteid']=$value[5];
        $value['indexid']=$value[6]; 
        $value['ua_id']=$value[7]; 
        $value['sh_id']=$value[8]; 
        $this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file')); 
        $this->cache->save($redis_token_key,json_encode($value),144000);
        return $value['token'];
       // $redis->setex($redis_token_key,'14400',json_encode($value));
    }
    public function getbet($p,$u){
        $page  =intval($p['p']);
        $status=intval($p['status']);
        $dsorcg=$p['dsorcg'];
        $p=intval($p['betpage']);
        $pnum=10;
        if($p<=0)$p=1;
        $pset=($p-1)*$pnum;
        $uid   =$u['uid'];
        
        if($dsorcg==1){
            $this->private_db->select('count(*) as numrows');
            if($status==-1) $this->private_db->where(['uid'=>$uid,'site_id'=>$u['siteid'],'is_jiesuan'=>1]);
            else $this->private_db->where(['uid'=>$uid,'site_id'=>$u['siteid'],'status'=>$status]);
            $data['nums']= $this->private_db->count_all_results('k_bet'); 
            //echo $this->private_db->last_query();
            $this->private_db->select('number,ball_sort,match_name,master_guest,bet_info,bet_money,bet_time,bet_win,win,status,bet_point');
            if($status==-1) $this->private_db->where(['uid'=>$uid,'site_id'=>$u['siteid'],'is_jiesuan'=>1]);
            else $this->private_db->where(['uid'=>$uid,'site_id'=>$u['siteid'],'status'=>$status]);
            $this->private_db->order_by('bet_time', 'DESC');
            
            $query=$this->private_db->get('k_bet',$pnum,$pset);
            //echo $this->private_db->last_query();
            $d=$query->result_array();
        }
        else{
            if($status==-1) $map=" AND `k_bet_cg_group`.`is_jiesuan` =1";
            else  $map=" AND `k_bet_cg_group`.`status` =$status";
            $field="`k_bet_cg_group`.`number`,`k_bet_cg_group`.`bet_win`,`k_bet_cg_group`.cg_count, `k_bet_cg`.`ball_sort`, `k_bet_cg`.`match_name`, `k_bet_cg`.`master_guest`, `k_bet_cg`.`bet_info`, `k_bet_cg_group`.`bet_money`, `k_bet_cg`.`bet_time`, `k_bet_cg_group`.`win`, `k_bet_cg_group`.`status`, `k_bet_cg`.`status` as `status2`";
            $sql="SELECT $field
                    FROM `k_bet_cg_group`
                    JOIN `k_bet_cg` ON `k_bet_cg_group`.`gid` = `k_bet_cg`.`gid`
                    WHERE `k_bet_cg_group`.`uid` = $uid
                    AND `k_bet_cg_group`.`site_id` = '".$u['siteid']."'
                    $map";
            $sqlnum="SELECT count(*) as numrows
                    FROM `k_bet_cg_group`
                    JOIN `k_bet_cg` ON `k_bet_cg_group`.`gid` = `k_bet_cg`.`gid`
                    WHERE `k_bet_cg_group`.`uid` = $uid
                    AND `k_bet_cg_group`.`site_id` = '".$u['siteid']."'
                    $map";
            $orderlimit=" ORDER BY `k_bet_cg_group`.`bet_time` DESC LIMIT $pset ,$pnum";

            $query = $this->private_db->query($sqlnum);
            $d=$query->row_array();
             
            $data['nums']=$d['numrows'];
            $query = $this->private_db->query($sql.$orderlimit);
            $d=$query->result_array();
           // echo $this->private_db->last_query();
        }
        //$data['nums']=100;
        $data['d']=$d;
        $data['page']=ceil($data['nums']/$pnum);
        if ($data['page']==0) $data['page']=1;
        return $data;
    }
}
<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Bet_model extends MY_Model {

    function __construct() {
        $this->init_db();
    }

    public function get_token($token){
        $d= $this->redis_get_token($token);
        return $d;
    }
    public function get_ft_match($id,$type=''){
        if($type=='bd')$sql="select Match_CoverDate,Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest, Match_Name, Match_IsLose, Match_Bd10, Match_Bd20, Match_Bd21, Match_Bd30, Match_Bd31, Match_Bd32, Match_Bd40, Match_Bd41, Match_Bd42, Match_Bd43, Match_Bd00, Match_Bd11, Match_Bd22, Match_Bd33, Match_Bd44, Match_Bdup5, Match_Bdg10, Match_Bdg20, Match_Bdg21, Match_Bdg30, Match_Bdg31, Match_Bdg32, Match_Bdg40, Match_Bdg41, Match_Bdg42, Match_Bdg43,'1' as lose_ok from bet_match where Match_Id=$id  and  Match_Type!=2 and Match_CoverDate > '".date('Y-m-d H:i:s')."' limit 0,1";
        elseif($type=='zrq')$sql="select Match_CoverDate,Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest, Match_Name, Match_IsLose, Match_Total01Pl,Match_Total23Pl,Match_Total46Pl,Match_Total7upPl,'1' as lose_ok from bet_match where Match_Id=$id  and  Match_Type!=2 and Match_CoverDate > '".date('Y-m-d H:i:s')."' limit 0,1";
        else $sql="select Match_CoverDate,Match_ID, Match_HalfId, Match_Date, Match_Time, Match_Master, Match_Guest,Match_Bmdy,Match_BHo, Match_RGG, Match_Name, Match_IsLose,Match_Bdpl, Match_BzM, Match_BzG, Match_BzH, Match_DxDpl,Match_Bgdy, Match_BAo, Match_Bxpl, Match_Bhdy, Match_BRpk, Match_Hr_ShowType, Match_DxXpl,Match_Bdxpk, Match_DxGG, Match_Ho, Match_Ao, Match_MasterID, Match_GuestID, Match_ShowType, Match_Type, Match_DsDpl, Match_DsSpl,Match_IsShowb,'1' as lose_ok from bet_match where Match_Id=$id and  Match_Type!=2 and Match_CoverDate > '".date('Y-m-d H:i:s')."' limit 0,1";
        $query=$this->public_db->query($sql);
        $d=$query->result_array();
        return $d;
    }
    public function get_ftp_match($id,$type=''){
        $this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
        $d=$this->cache->get('ZQGQ_JSON');
        $d=json_decode($d,true);
        $d=$d[1];
        if($d){
            foreach($d as $k =>$r){
                $dx    =    array();
                if($id ==$r[0] ){
                    $zqgq[0]['Match_ID']=$r[0];
                    $zqgq[0]['Match_Master']=$r[5];
                    $zqgq[0]['Match_Guest']=$r[6];
                    $zqgq[0]['Match_Name']=$r[2];
                    $zqgq[0]['Match_Time']=$r[1];
                    $zqgq[0]['Match_Ho']=$r[9];
                    $zqgq[0]['Match_DxDpl']=$r[14];
                    $zqgq[0]['Match_BHo']=$r[23];
                    $zqgq[0]['Match_Bdpl']=$r[28];
                    $zqgq[0]['Match_Ao']=$r[10];
                    $zqgq[0]['Match_DxXpl']=$r[13];
                    $zqgq[0]['Match_BAo']=$r[24];
                    $zqgq[0]['Match_Bxpl']=$r[27];
                    $zqgq[0]['Match_RGG']=str_replace(' ','',$r[8]);
                    $zqgq[0]['Match_BRpk']=str_replace(' ','',$r[22]);
                    $zqgq[0]['Match_ShowType']=$r[7];
                    $zqgq[0]['Match_Hr_ShowType']=$r[7];
                    $zqgq[0]['Match_DxGG']=str_replace(' ','',str_replace('O','',$r[11]));
                    $zqgq[0]['Match_Bdxpk']=str_replace('O','',str_replace(' ','',$r[25]));
                    $zqgq[0]['Match_HRedCard']=$r[29];
                    $zqgq[0]['Match_GRedCard']=$r[30];
                    $zqgq[0]['Match_NowScore']=$r[18].":".$r[19];
                    $zqgq[0]['Match_BzM']=$r[33];
                    $zqgq[0]['Match_BzG']=$r[34];
                    $zqgq[0]['Match_BzH']=$r[35];
                    $zqgq[0]['Match_Bmdy']=$r[36];
                    $zqgq[0]['Match_Bgdy']=$r[37];
                    $zqgq[0]['Match_Bhdy']=$r[38];
                    $zqgq[0]['Match_CoverDate']=date('Y-m-d H:i:s');
                    $zqgq[0]['Match_Date']=$r[1];
                    $zqgq[0]['Match_Type']=2;
                    $zqgq[0]['lose_ok']=0;
                    $zqgq[0]['Match_IsShowb']=1;
                    break;
                }

            }
        }else $zqgq='';



        $d=$zqgq;

        return $d;
    }
    public function get_bk_match($id){
        $sql="SELECT Match_CoverDate,Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest, Match_RGG, Match_Name, Match_IsLose, Match_DxDpl, Match_DxXpl, Match_DxGG, Match_Ho, Match_Ao, Match_MasterID, Match_GuestID, Match_ShowType, Match_Type, Match_DsDpl, Match_DsSpl,'1' as lose_ok from lq_match where Match_Id=$id AND Match_CoverDate >now()  limit 0,1";
        $query=$this->public_db->query($sql);
        $d=$query->result_array();
        return $d;
    }
    public function get_bkp_match($id){
        $this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
        $d=$this->cache->get('LQGQ_JSON');
        //print_r($d);
        $d=json_decode($d,true);
        $d=$d[1];
        if($d){
            foreach($d as $k =>$r){
            $dx    =    array();
            if($id ==$r[0] ){
                $lqgq[0]['Match_ID']=$r[0];
                $lqgq[0]['Match_Master']=$r[5];
                $lqgq[0]['Match_Guest']=$r[6];
                $lqgq[0]['Match_Name']=$r[2];
                $lqgq[0]['Match_Time']=$r[1];
                $lqgq[0]['Match_Ho']=$r[9];
                $lqgq[0]['Match_DxDpl']=$r[14];
                $lqgq[0]['Match_BHo']=$r[23];
                $lqgq[0]['Match_Bdpl']=$r[28];
                $lqgq[0]['Match_Ao']=$r[10];
                $lqgq[0]['Match_DxXpl']=$r[13];
                $lqgq[0]['Match_BAo']=$r[24];
                $lqgq[0]['Match_Bxpl']=$r[27];
                $lqgq[0]['Match_RGG']=str_replace(' ','',$r[8]);
                $lqgq[0]['Match_BRpk']=str_replace(' ','',$r[22]);
                $lqgq[0]['Match_ShowType']=$r[7];
                $lqgq[0]['Match_Hr_ShowType']=$r[7];
                $lqgq[0]['Match_DxGG']=str_replace(' ','',str_replace('O','',$r[11]));
                $lqgq[0]['Match_Bdxpk']=str_replace('O','',str_replace(' ','',$r[25]));
                $lqgq[0]['Match_HRedCard']=$r[29];
                $lqgq[0]['Match_GRedCard']=$r[30];
                $lqgq[0]['Match_NowScore']=$r[53].":".$r[54];
                $lqgq[0]['Match_BzM']=$r[33];
                $lqgq[0]['Match_BzG']=$r[34];
                $lqgq[0]['Match_BzH']=$r[35];
                $lqgq[0]['Match_Bmdy']=$r[36];
                $lqgq[0]['Match_Bgdy']=$r[37];
                $lqgq[0]['Match_Bhdy']=$r[38];
                $lqgq[0]['Match_CoverDate']=date('Y-m-d H:i:s');
                $lqgq[0]['Match_Date']=$r[1];
                $lqgq[0]['Match_Type']=2;
                $lqgq[0]['lose_ok']=1;
                $lqgq[0]['Match_IsShowb']=1;
                break;
            }
        }


        }else $lqgq='';


        $d=$lqgq;

        return $d;
    }
    /*排球*/
    public function get_vb_match($id){
        $sql="SELECT Match_ID, Match_Master, Match_Guest, Match_Name, Match_Time, Match_Date, Match_IsLose, Match_Ho, Match_DxDpl,  Match_DsDpl, Match_Ao, Match_DxXpl, Match_DsSpl, Match_RGG, Match_DxGG, Match_ShowType, Match_BzM, Match_BzG,'1' as lose_ok from volleyball_match where Match_Id=$id AND Match_CoverDate >now()  limit 0,1";
        $query=$this->public_db->query($sql);
        $d=$query->result_array();
        return $d;
    }
    /*网球*/
    public function get_tn_match($id){
        $sql="SELECT Match_ID, Match_Master, Match_Guest, Match_Name, Match_Time, Match_Date, Match_IsLose, Match_Ho, Match_DxDpl,  Match_DsDpl, Match_Ao, Match_DxXpl, Match_DsSpl, Match_RGG, Match_DxGG, Match_ShowType, Match_BzM, Match_BzG,'1' as lose_ok from tennis_match where Match_Id=$id AND Match_CoverDate >now()  limit 0,1";
        $query=$this->public_db->query($sql);
        $d=$query->result_array();
        return $d;
    }
    /*棒球*/
    public function get_bb_match($id){
        $sql="SELECT Match_ID, Match_Master, Match_Guest, Match_Name, Match_Time, Match_Date, Match_IsLose, Match_Ho, Match_DxDpl,  Match_DsDpl, Match_Ao, Match_DxXpl, Match_DsSpl, Match_RGG, Match_DxGG, Match_ShowType, Match_BzM, Match_BzG,'1' as lose_ok from baseball_match where Match_Id=$id   AND Match_CoverDate >now() limit 0,1";
        $query=$this->public_db->query($sql);
        $d=$query->result_array();
        return $d;
    }

    public function checkxe($uid,$agentid){
        if($uid && $agentid){
        $spArr = array('ft','bk','vb','bs','tn');
        foreach ($spArr as $key => $val) {

                    $tmpA_=$tmpB_=$tmpA = $tmpB = array();
                    $query = $this->private_db->query("select * from sp_games_view join k_user_agent_sport_set on k_user_agent_sport_set.type_id = sp_games_view.id where sp_games_view.type = '$val' and k_user_agent_sport_set.aid = '$agentid'");
                    $tmpA=$query->result_array();
                     foreach ($tmpA as $k => $v) {
                         $tmpA_[$v['t_type']]=$v;
                     }
                    $query = $this->private_db->query(" select * from sp_games_view join k_user_sport_set on k_user_sport_set.type_id = sp_games_view.id where sp_games_view.type = '$val' and k_user_sport_set.uid = '$uid'");
                    $tmpB=$query->result_array();
                     foreach ($tmpB as $k => $v) {
                         $tmpB_[$v['t_type']]=$v;
                     }
                    $set[$val] = @array_merge($tmpA_,$tmpB_);
                }
                return $set;
        }
    }
    public function addbetds($d){

        strpos($_SERVER['HTTP_REFERER'], 'wapsport')>0?$d['ptype']=1:$d['ptype']=0;
        $orderid=date('YmdHis').$d['uid'].rand(10000,99999);
        $this->private_db->trans_begin();
        $this->private_db->where([
            'uid'     =>$d['uid'],
            'site_id' =>$d['site_id'],
            'money >='=>$d['bet_money']
            ]);
        if($d['bet_point']<0) {
            $d['bet_money']=abs($d['bet_money']*$d['bet_point']);

        }
        $d['bet_win']=$d['bet_win']+$d['bet_money'];
        //else                  $d['bet_money']=$d['bet_money'];
        $this->private_db->set('money','`money`-'.$d['bet_money'],FALSE);
        $this->private_db->update('k_user');
        $q1=$this->private_db->affected_rows();

        $this->private_db->set('number', $orderid);
        $this->private_db->set('balance', $d['balance']-$d['bet_money']);
        $d['is_shiwan'] = $_SESSION['shiwan'];//判断是否试玩账号
        $this->private_db->insert('k_bet', $d);
        $q2=$this->private_db->insert_id();

        //写现金记录表
        /*site_id,*/            $record['site_id']       =   $d['site_id'];
        /*uid,*/                $record['uid']           =   $d['uid'];
        /*cash_type,*/          $record['cash_type']     =   2;
        /*cash_do_type,*/       $record['cash_do_type']  =   2;
        /*cash_num,*/           $record['cash_num']      =   $d['bet_money'];
        /*cash_balance,*/       $record['cash_balance']  =   $d['balance']-$d['bet_money'];
        /*cash_date,*/          $record['cash_date']     =   $d['bet_time'];
        /*remark,*/             $record['remark']        =   '体育注单:'.$orderid;
        /*source_id,*/          $record['source_id']     =   $q2;
        /*username,*/           $record['username']      =   $d['username'];
        /*agent_id,*/           $record['agent_id']      =   $d['agent_id'];
        /*source_type*/         $record['source_type']   =   8;//scoure_type 8 单式 9 串关
                                $record['ptype']         =   $d['ptype'];
                                $record['is_shiwan'] = $_SESSION['shiwan'];//判断是否试玩账号
                                $record['index_id'] = $d['index_id'];
        $this->private_db->insert('k_user_cash_record', $record);
        $q3=$this->private_db->insert_id();
        if ($this->private_db->trans_status() === FALSE || $q1!=1 || $q2<=0)
        {
            $this->private_db->trans_rollback();
            return false;
        }
        else
        {
            if($q1==1 && $q2>0 && $q3 >0) {
                $this->private_db->trans_commit();
                return true;
            }
            else {
                $this->private_db->trans_rollback();
                return false;
            }
        }
       //echo $this->private_db->last_query();
    }
    public function addbetcg($d,$cd){
            strpos($_SERVER['HTTP_REFERER'], 'wapsport')>0?$d['ptype']=1:$d['ptype']=0;
            $orderid='C'.date('YmdHis').$d['uid'].rand(10000,99999);
            $this->private_db->trans_begin();
            $this->private_db->where([
            'uid'     =>$d['uid'],
            'site_id' =>$d['site_id'],
            'money >='=>$d['bet_money']
            ]);
            $this->private_db->set('money','`money`-'.$d['bet_money'],FALSE);
            $this->private_db->update('k_user');
            $q=$this->private_db->affected_rows();

            $this->private_db->set('number', $orderid);
            $d['is_shiwan'] = $_SESSION['shiwan'];
            $this->private_db->insert('k_bet_cg_group', $d);
            $q1=$this->private_db->insert_id();
            $i=0;
            foreach($cd as $k=>$v){
                $this->private_db->set('gid', $q1);
                $this->private_db->insert('k_bet_cg', $v);
                if(!($this->private_db->insert_id()) || $q!=1) {
                    $this->private_db->trans_rollback();
                    return false;
                }
                $i++;
            }
            //写现金记录表
            /*site_id,*/            $record['site_id']       =   $d['site_id'];
            /*uid,*/                $record['uid']           =   $d['uid'];
            /*cash_type,*/          $record['cash_type']     =   2;
            /*cash_do_type,*/       $record['cash_do_type']  =   2;
            /*cash_num,*/           $record['cash_num']      =   $d['bet_money'];
            /*cash_balance,*/       $record['cash_balance']  =   $d['balance'];
            /*cash_date,*/          $record['cash_date']     =   $d['bet_time'];
            /*remark,*/             $record['remark']        =   '体育注单:'.$orderid;
            /*source_id,*/          $record['source_id']     =   $q1;
            /*username,*/           $record['username']      =   $d['username'];
            /*agent_id,*/           $record['agent_id']      =   $d['agent_id'];
            /*source_type*/         $record['source_type']   =   9;//scoure_type 8 单式 9 串关
                                    $record['ptype']         =   $d['ptype'];
                                    $record['is_shiwan'] = $_SESSION['shiwan'];//判断是否试玩账号
                                    $record['index_id'] = $d['index_id'];
            $q2=$this->private_db->insert('k_user_cash_record', $record);
            // echo $this->private_db->last_query();
            if($this->private_db->trans_status() === FALSE || !$q2){
                $this->private_db->trans_rollback();
                return false;
            }else{
                    if($q1>0 && $q2>0 && $i==count($cd)) {
                    $this->private_db->trans_commit();
                    return true;
                }
                else {
                    $this->private_db->trans_rollback();
                    return false;
                }

            }
    }

}
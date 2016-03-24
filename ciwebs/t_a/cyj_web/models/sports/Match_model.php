<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Match_model extends MY_Model {

    function __construct() {
        $this->init_db();
    }
    public function matchnum(){
        //足球滚球
        $this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
        $d=$this->cache->get('ZQGQ_JSON');
        $d=json_decode($d,true); 
        $d=$d[1];
        $data['ftpnum']=$data['ftp_num']=count($d);
        //篮球滚球
        $this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
        $d=$this->cache->get('LQGQ_JSON');
        $d=json_decode($d,true);  
        $d=$d[1];
        $data['bkpnum']=$data['bkp_num']=count($d);
        //今日足球
        $where=" where Match_Type=1 and Match_CoverDate between '".date('Y-m-d H:i:s')."' and '".date('Y-m-d 23:59:59')."' $where";
        $sql = "SELECT count(*) as num FROM bet_match $where";
        $query=$this->public_db->query($sql);
        $d=$query->row_array();
        $data['ftnum']=$d['num'];
        //今日篮球
        $sql = "SELECT count(*) as num FROM lq_match where Match_Type=1 and Match_CoverDate between '".date('Y-m-d H:i:s')."' and '".date('Y-m-d 23:59:59')."' ";
        $query=$this->public_db->query($sql);
        $d=$query->row_array();
        $data['bknum']=$d['num'];
        //今日排球
        $sql = "SELECT count(*) as num FROM volleyball_match where Match_Type=1 and Match_CoverDate between '".date('Y-m-d H:i:s')."' and '".date('Y-m-d 23:59:59')."'";
        $query=$this->public_db->query($sql);
        $d=$query->row_array();
        $data['vbnum']=$d['num'];
        //今日网球
        $sql = "SELECT count(*) as num FROM tennis_match where Match_Type=1 and Match_CoverDate between '".date('Y-m-d H:i:s')."' and '".date('Y-m-d 23:59:59')."'";
        $query=$this->public_db->query($sql);
        $d=$query->row_array();
        $data['tnnum']=$d['num'];
        //今日棒球
        $sql = "SELECT count(*) as num FROM baseball_match where Match_Type=1 and Match_CoverDate between '".date('Y-m-d H:i:s')."' and '".date('Y-m-d 23:59:59')."'";
        $query=$this->public_db->query($sql);
        $d=$query->row_array();
        $data['bbnum']=$d['num'];
        //早餐足球 
        $sql = "SELECT count(*) as num FROM bet_match where Match_Type=0 and Match_CoverDate > '".date('Y-m-d 23:59:59')."'";
        $query=$this->public_db->query($sql);
        $d=$query->row_array();
        $data['ftmnum']=$d['num'];
        //早餐篮球
        $sql = "SELECT count(*) as num FROM lq_match where Match_Type=0 and Match_CoverDate > '".date('Y-m-d 23:59:59')."' ";
        $query=$this->public_db->query($sql);
        $d=$query->row_array();
        $data['bkmnum']=$d['num'];
        //早餐排球
        $sql = "SELECT count(*) as num FROM volleyball_match where Match_Type=0 and Match_CoverDate >  '".date('Y-m-d 23:59:59')."'";
        $query=$this->public_db->query($sql);
        $d=$query->row_array();
        $data['vbmnum']=$d['num'];
        //早餐网球
        $sql = "SELECT count(*) as num FROM tennis_match where Match_Type=0 and Match_CoverDate  >  '".date('Y-m-d 23:59:59')."'";
        $query=$this->public_db->query($sql);
        $d=$query->row_array();
        $data['tnmnum']=$d['num'];
        //早餐棒球
        $sql = "SELECT count(*) as num FROM baseball_match where Match_Type=0 and Match_CoverDate >  '".date('Y-m-d 23:59:59')."'";
        $query=$this->public_db->query($sql);
        $d=$query->row_array();
        $data['bbmnum']=$d['num'];
        return $data;

    }
    public function FootballPlaying($r){
        $this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
        $d=$this->cache->get('ZQGQ_JSON');
        $d=json_decode($d,true); 
        $d=$d[1];
        /*$d=file_get_contents('D:\Web\wwwroot_new\ciwebs_\t_a\cyj_web\models\sports\1.txt');
        //echo $d;exit;
        $d=json_decode($d,true); 
        $d=$d['db'];*/
        $legname=[];
        if($d){
            foreach($d as $k =>$r){
            $dx    =    array();
            if(!in_array($r[2], $legname) && strpos($r[2], '测试')===false)$legname[]=$r[2];
            
            $zqgq[$k]['Match_ID']=$r[0];
            $zqgq[$k]['Match_Master']=$r[5];
            $zqgq[$k]['Match_Guest']=$r[6];
            $zqgq[$k]['Match_Name']=$r[2];
            $zqgq[$k]['Match_Time']=$r[1];
            $zqgq[$k]['Match_Ho']=$r[9];
            $zqgq[$k]['Match_DxDpl']=$r[14];
            $zqgq[$k]['Match_BHo']=$r[23];
            $zqgq[$k]['Match_Bdpl']=$r[28];
            $zqgq[$k]['Match_Ao']=$r[10];
            $zqgq[$k]['Match_DxXpl']=$r[13];
            $zqgq[$k]['Match_BAo']=$r[24];
            $zqgq[$k]['Match_Bxpl']=$r[27];
            $zqgq[$k]['Match_RGG']=str_replace(' ','',$r[8]);
            $zqgq[$k]['Match_BRpk']=str_replace(' ','',$r[22]);
            $zqgq[$k]['Match_ShowType']=$r[7];
            $zqgq[$k]['Match_Hr_ShowType']=$r[7];
            $zqgq[$k]['Match_DxGG']=str_replace(' ','',str_replace('O','',$r[11]));
            $zqgq[$k]['Match_Bdxpk']=str_replace('O','',str_replace(' ','',$r[25]));
            $zqgq[$k]['Match_HRedCard']=$r[29];
            $zqgq[$k]['Match_GRedCard']=$r[30];
            $zqgq[$k]['Match_NowScore']=$r[18].":".$r[19];
            $zqgq[$k]['Match_BzM']=$r[33];
            $zqgq[$k]['Match_BzG']=$r[34];
            $zqgq[$k]['Match_BzH']=$r[35];
            $zqgq[$k]['Match_Bmdy']=$r[36];
            $zqgq[$k]['Match_Bgdy']=$r[37];
            $zqgq[$k]['Match_Bhdy']=$r[38];
            $zqgq[$k]['Match_CoverDate']=date('Y-m-d H:i:s');
            $zqgq[$k]['Match_Date']='';
            $zqgq[$k]['Match_Type']=2;
            $zqgq[$k]['Match_IsShowb']=1;
            }
        }else $zqgq='';


         
        $d['d']=$zqgq;
        $d['d']=$zqgq;
        
        //获取总数据 
        $d['nums']=count($d);
        $d['legname']=$legname; 
        $d['page']=1;
        return $d;
    }
    public function BasketballPlaying($r){
        $this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
        $d=$this->cache->get('LQGQ_JSON');
        $d=json_decode($d,true); 
        $Match_Name=array();
        $d=$d[1];
        $legname=[];
        if($d){

        
            foreach($d as $k =>$r){
                $dx    =    array();
                //if($r['legname'])
                if(!in_array($r[2], $legname) && strpos($r[2], '测试')===false)$legname[]=$r[2];
               
                $zqgq[$k]['Match_ID']=$r[0];
                $zqgq[$k]['Match_Master']=$r[5];
                $zqgq[$k]['Match_Guest']=$r[6];
                $zqgq[$k]['Match_Name']=$r[2];
                $zqgq[$k]['Match_Time']=$r[1];
                $zqgq[$k]['Match_Ho']=$r[9];
                $zqgq[$k]['Match_DxDpl']=$r[14];
                $zqgq[$k]['Match_BHo']=$r[23];
                $zqgq[$k]['Match_Bdpl']=$r[28];
                $zqgq[$k]['Match_Ao']=$r[10];
                $zqgq[$k]['Match_DxXpl']=$r[13];
                $zqgq[$k]['Match_BAo']=$r[24];
                $zqgq[$k]['Match_Bxpl']=$r[27];
                $zqgq[$k]['Match_RGG']=str_replace(' ','',$r[8]);
                $zqgq[$k]['Match_BRpk']=str_replace(' ','',$r[22]);
                $zqgq[$k]['Match_ShowType']=$r[7];
                $zqgq[$k]['Match_Hr_ShowType']=$r[7];
                $zqgq[$k]['Match_DxGG']=str_replace(' ','',str_replace('O','',$r[11]));
                $zqgq[$k]['Match_Bdxpk']=str_replace('O','',str_replace(' ','',$r[25]));
                $zqgq[$k]['Match_HRedCard']=$r[29];
                $zqgq[$k]['Match_GRedCard']=$r[30];
                $zqgq[$k]['Match_NowScore']=intval($r[53]).":".intval($r[54]);
                if($zqgq[$k]['Match_NowScore']=='0:0')$zqgq[$k]['Match_NowScore']='';
                $zqgq[$k]['Match_BzM']=$r[33];
                $zqgq[$k]['Match_BzG']=$r[34];
                $zqgq[$k]['Match_BzH']=$r[35];
                $zqgq[$k]['Match_Bmdy']=$r[36];
                $zqgq[$k]['Match_Bgdy']=$r[37];
                $zqgq[$k]['Match_Bhdy']=$r[38];
                $zqgq[$k]['Match_CoverDate']=date('Y-m-d H:i:s',$lasttime);
                $zqgq[$k]['Match_Date']='';
                $zqgq[$k]['Match_Type']=2;
                $zqgq[$k]['Match_IsShowb']=1;
                
         
                if(!in_array($zqgq[$k]['Match_Name'],$Match_Name) && $zqgq[$k]['Match_Name']) $Match_Name[]=$zqgq[$k]['Match_Name'];
         
            }
        }else $zqgq='';
         
        $d['d']=$zqgq;
         
        //获取总数据 
        $d['nums']=count($d);
        $d['legname']=$legname;  
        $d['page']=1;
        return $d;
    }
    public function FootballToday($r){
        $p=intval($r['p']);
        $p<=0?$p='1':$p;
        $pnum=40;
        $limit=($p-1)*$pnum;
        $limit=" limit $limit,$pnum";

        $where='';
        $legar=array();
        if($r['leg']) {
            $leg=explode('|',$r['leg']);
            foreach ($leg as $key => $value) {
                 $legar[] = $this->public_db->escape_like_str($value);
            }
            $leg=implode("','",$legar);
            $where=" and Match_Name in('$leg')";
        }
        $orderby="order by Match_CoverDate ,iPage,match_name,Match_Master,Match_ID,iSn";
        $where=" where Match_Type=1 and Match_CoverDate between '".date('Y-m-d H:i:s')."' and '".date('Y-m-d 23:59:59')."' $where";
        $sql = "SELECT Match_ID, Match_HalfId, Match_Date, Match_Time, Match_Master, Match_Guest,Match_Bmdy,Match_BHo, Match_RGG, Match_Name, Match_IsLose,Match_Bdpl, Match_BzM, Match_BzG, Match_BzH, Match_DxDpl,Match_Bgdy, Match_BAo, Match_Bxpl, Match_Bhdy, Match_BRpk, Match_Hr_ShowType, Match_DxXpl,Match_Bdxpk, Match_DxGG, Match_Ho, Match_Ao, Match_MasterID, Match_GuestID, Match_ShowType, Match_Type, Match_DsDpl, Match_DsSpl,Match_IsShowb 
        FROM bet_match $where $orderby $limit";
        $query=$this->public_db->query($sql);
        $d['d']=$query->result_array();

        
        //获取总联赛名
        $sql = "SELECT Match_Name FROM bet_match $where ";
        $query=$this->public_db->query($sql);
        $dMatch_Name=$query->result_array();
        $Match_Name=array();
        foreach ($dMatch_Name as $key => $value) {
            if(!in_array($value['Match_Name'],$Match_Name) && $value['Match_Name']) $Match_Name[]=$value['Match_Name'];
        } 
        //获取总数据 
        $d['nums']=count($dMatch_Name);
        $d['legname']=$Match_Name; 
        $d['page']=ceil($d['nums']/$pnum);
        return $d;
    }
    public function FootballGG($r){
        $p=intval($r['p']);
        $p<=0?$p='1':$p;
        $pnum=40;
        $limit=($p-1)*$pnum;
        $limit=" limit $limit,$pnum";
        $where='';
        $legar=array();
        if($r['leg']) {
            $leg=explode('|',$r['leg']);
            foreach ($leg as $key => $value) {
                 $legar[] = $this->public_db->escape_like_str($value);
            }
            $leg=implode("','",$legar);
            $where=" and Match_Name in('$leg')";
        }

        $sql = "SELECT Match_ID, Match_HalfId, Match_Date, Match_Time, Match_Master, Match_Guest,Match_Bmdy,Match_BHo, Match_RGG, Match_Name, Match_IsLose,Match_Bdpl, Match_BzM, Match_BzG, Match_BzH, Match_DxDpl,Match_Bgdy, Match_BAo, Match_Bxpl, Match_Bhdy, Match_BRpk, Match_Hr_ShowType, Match_DxXpl,Match_Bdxpk, Match_DxGG, Match_Ho, Match_Ao, Match_MasterID, Match_GuestID, Match_ShowType, Match_Type, Match_DsDpl, Match_DsSpl,Match_IsShowb 
        FROM bet_match Where  Match_BzM>0 and  Match_BzG>0 and  Match_BzH>0 and match_name not like '%特别投注%' and Match_CoverDate between '".date('Y-m-d H:i:s')."' and  '".date('Y-m-d 23:59:59')."'  $where order by Match_CoverDate ,iPage,match_name,Match_Master,Match_ID,iSn  $limit";
        $query=$this->public_db->query($sql);
        $d['d']=$query->result_array();
        //获取总联赛名
        $sql = "SELECT Match_Name FROM bet_match where Match_BzM>0 and  Match_BzG>0 and  Match_BzH>0 and match_name not like '%特别投注%' and Match_CoverDate between '".date('Y-m-d H:i:s')."' and  '".date('Y-m-d 23:59:59')."' $where ";
        $query=$this->public_db->query($sql);
        $dMatch_Name=$query->result_array();
        $Match_Name=array();
        foreach ($dMatch_Name as $key => $value) {
            if(!in_array($value['Match_Name'],$Match_Name) && $value['Match_Name']) $Match_Name[]=$value['Match_Name'];
        } 
        //获取总数据 
        $d['nums']=count($dMatch_Name);
        $d['legname']=$Match_Name; 
        $d['page']=ceil($d['nums']/$pnum);
        return $d;
    }
    public function FootballZRQ($r){
        $p=intval($r['p']);
        $where='';
        $legar=array();
        if($r['leg']) {
            $leg=explode('|',$r['leg']);
            foreach ($leg as $key => $value) {
                 $legar[] = $this->public_db->escape_like_str($value);
            }
            $leg=implode("','",$legar);
            $where=" and Match_Name in('$leg')";
        }
        $p<=0?$p='1':$p;
        $pnum=40;
        $limit=($p-1)*$pnum;
        $limit=" limit $limit,$pnum";
        $sql = "select Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest, Match_Name, Match_IsLose, Match_BzM, Match_Total01Pl, Match_Total23Pl, Match_Total46Pl, Match_Total7upPl, Match_BzG, Match_BzH from bet_match where Match_Type=1 and Match_IsShowt=1 and Match_Total01Pl>0 AND Match_CoverDate>now() $where  order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn ".$limit;
                $query=$this->public_db->query($sql);
        $d['d']=$query->result_array(); 


        //获取总联赛名
        $sql = "SELECT Match_Name FROM bet_match where Match_Type=1  and Match_Total01Pl>0 AND Match_CoverDate>now()  $where ";
        $query=$this->public_db->query($sql);
        $dMatch_Name=$query->result_array();
        $Match_Name=array();
        foreach ($dMatch_Name as $key => $value) {
            if(!in_array($value['Match_Name'],$Match_Name) && $value['Match_Name']) $Match_Name[]=$value['Match_Name'];
        } 
        //获取总数据 
        $d['nums']=count($dMatch_Name);
        $d['legname']=$Match_Name; 
         
        $d['page']=ceil($d['nums']/$pnum);
        return $d;
    }
    public function FootballBD($r){
        $p=intval($r['p']);
        $p<=0?$p='1':$p;
        $pnum=40;
        $limit=($p-1)*$pnum;
        $limit=" limit $limit,$pnum";
        $legar=array();
        $where=''; 
        if($r['leg']) {
            $leg=explode('|',$r['leg']);
            foreach ($leg as $key => $value) {
                 $legar[] = $this->public_db->escape_like_str($value);
            }
            $leg=implode("','",$legar);
            $where=" and Match_Name in('$leg')";
        }
        $sql = "select Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest, Match_Name, Match_IsLose, Match_Bd10, Match_Bd20, Match_Bd21, Match_Bd30, Match_Bd31, Match_Bd32, Match_Bd40, Match_Bd41, Match_Bd42, Match_Bd43, Match_Bd00, Match_Bd11, Match_Bd22, Match_Bd33, Match_Bd44, Match_Bdup5, Match_Bdg10, Match_Bdg20, Match_Bdg21, Match_Bdg30, Match_Bdg31, Match_Bdg32, Match_Bdg40, Match_Bdg41, Match_Bdg42, Match_Bdg43 FROM bet_match where Match_Type=1 and Match_IsShowbd=1 and Match_CoverDate>now() and Match_Bd21>0 $where order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn".$limit;
        $query=$this->public_db->query($sql);
        $d['d']=$query->result_array();
        //获取总联赛名
        $sql = "SELECT Match_Name FROM bet_match where Match_Type=1 and  Match_CoverDate>now() and Match_Bd21>0 $where ";
        $query=$this->public_db->query($sql);
        $dMatch_Name=$query->result_array();
        $Match_Name=array();
        foreach ($dMatch_Name as $key => $value) {
            if(!in_array($value['Match_Name'],$Match_Name) && $value['Match_Name']) $Match_Name[]=$value['Match_Name'];
        } 
        //获取总数据 
        $d['nums']=count($dMatch_Name);
        $d['legname']=$Match_Name; 
        $d['page']=ceil($d['nums']/$pnum);
        return $d;
    }
    public function Fbnews(){
            $sql = "select notice_title,notice_date,notice_content FROM site_notice where (sid = '0' or sid = '".SITEID."') and notice_cate='s_p' order by notice_date desc limit 0,10";
            $query=$this->public_db->query($sql);
            $notice=$query->result_array();
            return $notice;
        }
    public function BasketballToday($r){
        $p=intval($r['p']);
        $p<=0?$p='1':$p;
        $pnum=40;
        $limit=($p-1)*$pnum;
        $limit=" limit $limit,$pnum";
        $legar=array();
        $where=''; 
        if($r['leg']) {
            $leg=explode('|',$r['leg']);
            foreach ($leg as $key => $value) {
                 $legar[] = $this->public_db->escape_like_str($value);
            }
            $leg=implode("','",$legar);
            $where=" and Match_Name in('$leg')";
        }
        $sql = "SELECT Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest, '' as Match_BzM, '' as Match_BzG, '' as Match_BzH ,Match_RGG, Match_Name, Match_IsLose, Match_DxDpl, Match_DxXpl, Match_DxGG, Match_Ho, Match_Ao, Match_MasterID, Match_GuestID, Match_ShowType, Match_Type, Match_DsDpl, Match_DsSpl 
        FROM lq_match where  Match_Type=1 and Match_CoverDate between '".date('Y-m-d H:i:s')."' and '".date('Y-m-d 23:59:59')."' $where  order by Match_CoverDate ,iPage,iSn,Match_ID,match_name,Match_Master $limit";
        $query=$this->public_db->query($sql);
        $d['d']=$query->result_array();
        //获取总联赛名
        $sql = "SELECT Match_Name FROM lq_match where Match_Type=1 and Match_CoverDate between '".date('Y-m-d H:i:s')."' and '".date('Y-m-d 23:59:59')."' $where ";
        $query=$this->public_db->query($sql);
        $dMatch_Name=$query->result_array();
        $Match_Name=array();
        foreach ($dMatch_Name as $key => $value) {
            if(!in_array($value['Match_Name'],$Match_Name) && $value['Match_Name']) $Match_Name[]=$value['Match_Name'];
        } 
        //获取总数据 
        $d['nums']=count($dMatch_Name);
        $d['legname']=$Match_Name; 
        $d['page']=ceil($d['nums']/$pnum);
        return $d;
    }
    public function BasketballGG($r){
        $p=intval($r['p']);
        $p<=0?$p='1':$p;
        $pnum=40;
        $limit=($p-1)*$pnum;
        $limit=" limit $limit,$pnum";
        $sql = "SELECT Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest, '' as Match_BzM, '' as Match_BzG, '' as Match_BzH ,Match_RGG, Match_Name, Match_IsLose, Match_DxDpl, Match_DxXpl, Match_DxGG, Match_Ho, Match_Ao, Match_MasterID, Match_GuestID, Match_ShowType, Match_Type, Match_DsDpl, Match_DsSpl 
        FROM lq_match where  Match_CoverDate > '".date('Y-m-d H:i:s')."'   order by Match_CoverDate ,iPage,iSn,Match_ID,match_name,Match_Master $limit";
        $query=$this->public_db->query($sql);
        $d['d']=$query->result_array();
        $sql = "SELECT count(*) as nums FROM  lq_match   where  Match_CoverDate > '".date('Y-m-d H:i:s')."' ";
        $query=$this->public_db->query($sql);
        $d['nums']=$query->row_array();    
        $d['nums']=$d['nums']['nums'];
        $d['page']=ceil($d['nums']/$pnum);
        return $d;
    }
    public function VolleyballToday($r){
        $p=intval($r['p']);
        $p<=0?$p='1':$p;
        $pnum=40;
        $limit=($p-1)*$pnum;
        $limit=" limit $limit,$pnum";
        $legar=array();
        $where=''; 
        if($r['leg']) {
            $leg=explode('|',$r['leg']);
            foreach ($leg as $key => $value) {
                 $legar[] = $this->public_db->escape_like_str($value);
            }
            $leg=implode("','",$legar);
            $where=" and Match_Name in('$leg')";
        }
        $sql = "SELECT Match_ID, Match_Master, Match_Guest, Match_Name, Match_Time, Match_Date, Match_IsLose, Match_Ho, Match_DxDpl,  Match_DsDpl, Match_Ao, Match_DxXpl, Match_DsSpl, Match_RGG, Match_DxGG, Match_ShowType, Match_BzM, Match_BzG 
        FROM volleyball_match where  Match_CoverDate between '".date('Y-m-d H:i:s')."' and '".date('Y-m-d 23:59:59')."' $where  order by Match_CoverDate ,Match_ID,match_name,Match_Master $limit";
        $query=$this->public_db->query($sql);
        $d['d']=$query->result_array();
        //获取总联赛名
        $sql = "SELECT Match_Name FROM volleyball_match where Match_CoverDate between '".date('Y-m-d H:i:s')."' and '".date('Y-m-d 23:59:59')."' $where ";
        $query=$this->public_db->query($sql);
        $dMatch_Name=$query->result_array();
        $Match_Name=array();
        foreach ($dMatch_Name as $key => $value) {
            if(!in_array($value['Match_Name'],$Match_Name) && $value['Match_Name']) $Match_Name[]=$value['Match_Name'];
        } 
        //获取总数据 
        $d['nums']=count($dMatch_Name);
        $d['legname']=$Match_Name; 
        $d['page']=ceil($d['nums']/$pnum);
        return $d;
    }
    public function TennisToday($r){
        $p=intval($r['p']);
        $p<=0?$p='1':$p;
        $pnum=40;
        $limit=($p-1)*$pnum;
        $limit=" limit $limit,$pnum";
        $legar=array();
        $where=''; 
        if($r['leg']) {
            $leg=explode('|',$r['leg']);
            foreach ($leg as $key => $value) {
                 $legar[] = $this->public_db->escape_like_str($value);
            }
            $leg=implode("','",$legar);
            $where=" and Match_Name in('$leg')";
        }
        $sql = "SELECT Match_ID, Match_Master, Match_Guest, Match_Name, Match_Time, Match_Date, Match_IsLose, Match_Ho, Match_DxDpl,  Match_DsDpl, Match_Ao, Match_DxXpl, Match_DsSpl, Match_RGG, Match_DxGG, Match_ShowType, Match_BzM, Match_BzG 
        FROM tennis_match where  Match_CoverDate between '".date('Y-m-d H:i:s')."' and '".date('Y-m-d 23:59:59')."' $where  order by Match_CoverDate ,Match_ID,match_name,Match_Master $limit";
        $query=$this->public_db->query($sql);
        $d['d']=$query->result_array();
        //获取总联赛名
        $sql = "SELECT Match_Name FROM tennis_match where Match_CoverDate between '".date('Y-m-d H:i:s')."' and '".date('Y-m-d 23:59:59')."' $where ";
        $query=$this->public_db->query($sql);
        $dMatch_Name=$query->result_array();
        $Match_Name=array();
        foreach ($dMatch_Name as $key => $value) {
            if(!in_array($value['Match_Name'],$Match_Name) && $value['Match_Name']) $Match_Name[]=$value['Match_Name'];
        } 
        //获取总数据 
        $d['nums']=count($dMatch_Name);
        $d['legname']=$Match_Name; 
        $d['page']=ceil($d['nums']/$pnum);
        return $d;
    }    
    public function FootballMorning($r){
        $p=intval($r['p']);
        $p<=0?$p='1':$p;
        $pnum=40;
        $limit=($p-1)*$pnum;
        $limit=" limit $limit,$pnum";

        $where='';
        $legar=array();
        if($r['leg']) {
            $leg=explode('|',$r['leg']);
            foreach ($leg as $key => $value) {
                 $legar[] = $this->public_db->escape_like_str($value);
            }
            $leg=implode("','",$legar);
            $where=" and Match_Name in('$leg')";
        }
        $orderby="order by Match_CoverDate ,iPage,match_name,Match_Master,Match_ID,iSn";
        $where=" where Match_Type!=1 and Match_CoverDate > '".date('Y-m-d 23:59:59')."' $where";
        $sql = "SELECT Match_ID, Match_HalfId, Match_Date, Match_Time, Match_Master, Match_Guest,Match_Bmdy,Match_BHo, Match_RGG, Match_Name, Match_IsLose,Match_Bdpl, Match_BzM, Match_BzG, Match_BzH, Match_DxDpl,Match_Bgdy, Match_BAo, Match_Bxpl, Match_Bhdy, Match_BRpk, Match_Hr_ShowType, Match_DxXpl,Match_Bdxpk, Match_DxGG, Match_Ho, Match_Ao, Match_MasterID, Match_GuestID, Match_ShowType, Match_Type, Match_DsDpl, Match_DsSpl,Match_IsShowb 
        FROM bet_match $where $orderby $limit";
        $query=$this->public_db->query($sql);
        $d['d']=$query->result_array();

        
        //获取总联赛名
        $sql = "SELECT Match_Name FROM bet_match $where ";
        $query=$this->public_db->query($sql);
        $dMatch_Name=$query->result_array();
        $Match_Name=array();
        foreach ($dMatch_Name as $key => $value) {
            if(!in_array($value['Match_Name'],$Match_Name) && $value['Match_Name']) $Match_Name[]=$value['Match_Name'];
        } 
        //获取总数据 
        $d['nums']=count($dMatch_Name);
        $d['legname']=$Match_Name; 
        $d['page']=ceil($d['nums']/$pnum);
        return $d;
    }
        
    public function FootballMBD($r){
        $p=intval($r['p']);
        $p<=0?$p='1':$p;
        $pnum=40;
        $limit=($p-1)*$pnum;
        $limit=" limit $limit,$pnum";
        $legar=array();
        $where=''; 
        if($r['leg']) {
            $leg=explode('|',$r['leg']);
            foreach ($leg as $key => $value) {
                 $legar[] = $this->public_db->escape_like_str($value);
            }
            $leg=implode("','",$legar);
            $where=" and Match_Name in('$leg')";
        }
        $sql = "select Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest, Match_Name, Match_IsLose, Match_Bd10, Match_Bd20, Match_Bd21, Match_Bd30, Match_Bd31, Match_Bd32, Match_Bd40, Match_Bd41, Match_Bd42, Match_Bd43, Match_Bd00, Match_Bd11, Match_Bd22, Match_Bd33, Match_Bd44, Match_Bdup5, Match_Bdg10, Match_Bdg20, Match_Bdg21, Match_Bdg30, Match_Bdg31, Match_Bdg32, Match_Bdg40, Match_Bdg41, Match_Bdg42, Match_Bdg43 FROM bet_match where Match_Type=0 and Match_IsShowbd=1 and Match_CoverDate>now() and Match_Bd21>0 $where order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn".$limit;
        $query=$this->public_db->query($sql);
        $d['d']=$query->result_array();
        //获取总联赛名
        $sql = "SELECT Match_Name FROM bet_match where Match_Type=0 and  Match_CoverDate> '".date('Y-m-d 23:59:59')."' and Match_Bd21>0 $where ";
        $query=$this->public_db->query($sql);
        $dMatch_Name=$query->result_array();
        $Match_Name=array();
        foreach ($dMatch_Name as $key => $value) {
            if(!in_array($value['Match_Name'],$Match_Name) && $value['Match_Name']) $Match_Name[]=$value['Match_Name'];
        } 
        //获取总数据 
        $d['nums']=count($dMatch_Name);
        $d['legname']=$Match_Name; 
        $d['page']=ceil($d['nums']/$pnum);
        return $d;
    }
        
        public function FootballMZRQ($r){
        $p=intval($r['p']);
        $where='';
        $legar=array();
        if($r['leg']) {
            $leg=explode('|',$r['leg']);
            foreach ($leg as $key => $value) {
                 $legar[] = $this->public_db->escape_like_str($value);
            }
            $leg=implode("','",$legar);
            $where=" and Match_Name in('$leg')";
        }
        $p<=0?$p='1':$p;
        $pnum=40;
        $limit=($p-1)*$pnum;
        $limit=" limit $limit,$pnum";
        $sql = "select Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest, Match_Name, Match_IsLose, Match_BzM, Match_Total01Pl, Match_Total23Pl, Match_Total46Pl, Match_Total7upPl, Match_BzG, Match_BzH from bet_match where Match_Type=0 and Match_IsShowt=1 and Match_Total01Pl>0 AND Match_CoverDate>now() $where  order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn ".$limit;
                $query=$this->public_db->query($sql);
        $d['d']=$query->result_array(); 


        //获取总联赛名
        $sql = "SELECT Match_Name FROM bet_match where Match_Type=0  and Match_Total01Pl>0 AND Match_CoverDate> '".date('Y-m-d 23:59:59')."'  $where ";
        $query=$this->public_db->query($sql);
        $dMatch_Name=$query->result_array();
        $Match_Name=array();
        foreach ($dMatch_Name as $key => $value) {
            if(!in_array($value['Match_Name'],$Match_Name) && $value['Match_Name']) $Match_Name[]=$value['Match_Name'];
        } 
        //获取总数据 
        $d['nums']=count($dMatch_Name);
        $d['legname']=$Match_Name; 
         
        $d['page']=ceil($d['nums']/$pnum);
        return $d;
    }
        
        public function FootballMGG($r){
        $p=intval($r['p']);
        $p<=0?$p='1':$p;
        $pnum=40;
        $limit=($p-1)*$pnum;
        $limit=" limit $limit,$pnum";
        $where='';
        $legar=array();
        if($r['leg']) {
            $leg=explode('|',$r['leg']);
            foreach ($leg as $key => $value) {
                 $legar[] = $this->public_db->escape_like_str($value);
            }
            $leg=implode("','",$legar);
            $where=" and Match_Name in('$leg')";
        }

        $sql = "SELECT Match_ID, Match_HalfId, Match_Date, Match_Time, Match_Master, Match_Guest,Match_Bmdy,Match_BHo, Match_RGG, Match_Name, Match_IsLose,Match_Bdpl, Match_BzM, Match_BzG, Match_BzH, Match_DxDpl,Match_Bgdy, Match_BAo, Match_Bxpl, Match_Bhdy, Match_BRpk, Match_Hr_ShowType, Match_DxXpl,Match_Bdxpk, Match_DxGG, Match_Ho, Match_Ao, Match_MasterID, Match_GuestID, Match_ShowType, Match_Type, Match_DsDpl, Match_DsSpl,Match_IsShowb 
        FROM bet_match Where   match_name not like '%特别投注%' and Match_BzM>0 and  Match_BzG>0 and  Match_BzH>0 and Match_CoverDate > '".date('Y-m-d 23:59:59')."'  $where order by Match_CoverDate ,iPage,match_name,Match_Master,Match_ID,iSn  $limit";
        $query=$this->public_db->query($sql);
        $d['d']=$query->result_array();
        //获取总联赛名
        $sql = "SELECT Match_Name FROM bet_match where match_name not like '%特别投注%' and Match_BzM>0 and  Match_BzG>0 and  Match_BzH>0 and Match_CoverDate > '".date('Y-m-d 23:59:59')."' $where ";
        $query=$this->public_db->query($sql);
        $dMatch_Name=$query->result_array();
        $Match_Name=array();
        foreach ($dMatch_Name as $key => $value) {
            if(!in_array($value['Match_Name'],$Match_Name) && $value['Match_Name']) $Match_Name[]=$value['Match_Name'];
        } 
        //获取总数据 
        $d['nums']=count($dMatch_Name);
        $d['legname']=$Match_Name; 
        $d['page']=ceil($d['nums']/$pnum);
        return $d;
    }
        
        public function BasketballMorning($r){
        $p=intval($r['p']);
        $p<=0?$p='1':$p;
        $pnum=40;
        $limit=($p-1)*$pnum;
        $limit=" limit $limit,$pnum";
        $legar=array();
        $where=''; 
        if($r['leg']) {
            $leg=explode('|',$r['leg']);
            foreach ($leg as $key => $value) {
                 $legar[] = $this->public_db->escape_like_str($value);
            }
            $leg=implode("','",$legar);
            $where=" and Match_Name in('$leg')";
        }
        $sql = "SELECT Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest, '' as Match_BzM, '' as Match_BzG, '' as Match_BzH ,Match_RGG, Match_Name, Match_IsLose, Match_DxDpl, Match_DxXpl, Match_DxGG, Match_Ho, Match_Ao, Match_MasterID, Match_GuestID, Match_ShowType, Match_Type, Match_DsDpl, Match_DsSpl 
        FROM lq_match where  Match_CoverDate > '".date('Y-m-d 23:59:59')."' $where  order by Match_CoverDate ,iPage,iSn,Match_ID,match_name,Match_Master $limit";
        $query=$this->public_db->query($sql);
        $d['d']=$query->result_array();
        //获取总联赛名
        $sql = "SELECT Match_Name FROM lq_match where Match_CoverDate >  '".date('Y-m-d 23:59:59')."' $where ";
        $query=$this->public_db->query($sql);
        $dMatch_Name=$query->result_array();
        $Match_Name=array();
        foreach ($dMatch_Name as $key => $value) {
            if(!in_array($value['Match_Name'],$Match_Name) && $value['Match_Name']) $Match_Name[]=$value['Match_Name'];
        } 
        //获取总数据 
        $d['nums']=count($dMatch_Name);
        $d['legname']=$Match_Name; 
        $d['page']=ceil($d['nums']/$pnum);
        return $d;
    }
        
        public function BasketballMGG($r){
        $p=intval($r['p']);
        $p<=0?$p='1':$p;
        $pnum=40;
        $limit=($p-1)*$pnum;
        $limit=" limit $limit,$pnum";
                $sql = "SELECT Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest, '' as Match_BzM, '' as Match_BzG, '' as Match_BzH ,Match_RGG, Match_Name, Match_IsLose, Match_DxDpl, Match_DxXpl, Match_DxGG, Match_Ho, Match_Ao, Match_MasterID, Match_GuestID, Match_ShowType, Match_Type, Match_DsDpl, Match_DsSpl 
        FROM lq_match where  Match_Type=0 and  Match_CoverDate > '".date('Y-m-d 23:59:59')."'   order by Match_CoverDate ,iPage,iSn,Match_ID,match_name,Match_Master $limit";
        $query=$this->public_db->query($sql);
        $d['d']=$query->result_array();
        $sql = "SELECT count(*) as nums FROM  lq_match   where  Match_Type=0 and Match_CoverDate > '".date('Y-m-d 23:59:59')."' ";
        $query=$this->public_db->query($sql);
        $d['nums']=$query->row_array();    
        $d['nums']=$d['nums']['nums'];
        $d['page']=ceil($d['nums']/$pnum);
        return $d;
    }
        
        public function VolleyballMorning($r){
        $p=intval($r['p']);
        $p<=0?$p='1':$p;
        $pnum=40;
        $limit=($p-1)*$pnum;
        $limit=" limit $limit,$pnum";
        $legar=array();
        $where=''; 
        if($r['leg']) {
            $leg=explode('|',$r['leg']);
            foreach ($leg as $key => $value) {
                 $legar[] = $this->public_db->escape_like_str($value);
            }
            $leg=implode("','",$legar);
            $where=" and Match_Name in('$leg')";
        }
        $sql = "SELECT Match_ID, Match_Master, Match_Guest, Match_Name, Match_Time, Match_Date, Match_IsLose, Match_Ho, Match_DxDpl,  Match_DsDpl, Match_Ao, Match_DxXpl, Match_DsSpl, Match_RGG, Match_DxGG, Match_ShowType, Match_BzM, Match_BzG 
        FROM volleyball_match where  Match_Type=0 and  Match_CoverDate > '".date('Y-m-d 23:59:59')."' $where  order by Match_CoverDate ,Match_ID,match_name,Match_Master $limit";
        $query=$this->public_db->query($sql);
        $d['d']=$query->result_array();
        //获取总联赛名
        $sql = "SELECT Match_Name FROM volleyball_match where Match_Type=0 and  Match_CoverDate > '".date('Y-m-d 23:59:59')."' $where ";
        $query=$this->public_db->query($sql);
        $dMatch_Name=$query->result_array();
        $Match_Name=array();
        foreach ($dMatch_Name as $key => $value) {
            if(!in_array($value['Match_Name'],$Match_Name) && $value['Match_Name']) $Match_Name[]=$value['Match_Name'];
        } 
        //获取总数据 
        $d['nums']=count($dMatch_Name);
        $d['legname']=$Match_Name; 
        $d['page']=ceil($d['nums']/$pnum);
        return $d;
    }
    
    public function TennisMorning($r){
        $p=intval($r['p']);
        $p<=0?$p='1':$p;
        $pnum=40;
        $limit=($p-1)*$pnum;
        $limit=" limit $limit,$pnum";
        $legar=array();
        $where=''; 
        if($r['leg']) {
            $leg=explode('|',$r['leg']);
            foreach ($leg as $key => $value) {
                 $legar[] = $this->public_db->escape_like_str($value);
            }
            $leg=implode("','",$legar);
            $where=" and Match_Name in('$leg')";
        }
        $sql = "SELECT Match_ID, Match_Master, Match_Guest, Match_Name, Match_Time, Match_Date, Match_IsLose, Match_Ho, Match_DxDpl,  Match_DsDpl, Match_Ao, Match_DxXpl, Match_DsSpl, Match_RGG, Match_DxGG, Match_ShowType, Match_BzM, Match_BzG 
        FROM tennis_match where  Match_CoverDate > '".date('Y-m-d 23:59:59')."' $where  order by Match_CoverDate ,Match_ID,match_name,Match_Master $limit";
        $query=$this->public_db->query($sql);
        $d['d']=$query->result_array();
        //获取总联赛名
        $sql = "SELECT Match_Name FROM tennis_match where Match_CoverDate > '".date('Y-m-d 23:59:59')."' $where ";
        $query=$this->public_db->query($sql);
        $dMatch_Name=$query->result_array();
        $Match_Name=array();
        foreach ($dMatch_Name as $key => $value) {
            if(!in_array($value['Match_Name'],$Match_Name) && $value['Match_Name']) $Match_Name[]=$value['Match_Name'];
        } 
        //获取总数据 
        $d['nums']=count($dMatch_Name);
        $d['legname']=$Match_Name; 
        $d['page']=ceil($d['nums']/$pnum);
        return $d;
    }    
    public function BaseballToday($r){
        $p=intval($r['p']);
        $p<=0?$p='1':$p;
        $pnum=40;
        $limit=($p-1)*$pnum;
        $limit=" limit $limit,$pnum";
        $legar=array();
        $where=''; 
        if($r['leg']) {
            $leg=explode('|',$r['leg']);
            foreach ($leg as $key => $value) {
                 $legar[] = $this->public_db->escape_like_str($value);
            }
            $leg=implode("','",$legar);
            $where=" and Match_Name in('$leg')";
        }
       $sql = "SELECT Match_ID, Match_Master, Match_Guest, Match_Name, Match_Time, Match_Date, Match_IsLose, Match_Ho, Match_DxDpl,  Match_DsDpl, Match_Ao, Match_DxXpl, Match_DsSpl, Match_RGG, Match_DxGG, Match_ShowType, Match_BzM, Match_BzG 
        FROM baseball_match where  Match_CoverDate between '".date('Y-m-d H:i:s')."' and '".date('Y-m-d 23:59:59')."' $where  order by Match_CoverDate ,Match_ID,match_name,Match_Master $limit";
        $query=$this->public_db->query($sql);
        $d['d']=$query->result_array();
        //获取总联赛名
        $sql = "SELECT Match_Name FROM baseball_match where Match_CoverDate between '".date('Y-m-d H:i:s')."' and '".date('Y-m-d 23:59:59')."' $where ";
        $query=$this->public_db->query($sql);
        $dMatch_Name=$query->result_array();
        $Match_Name=array();
        foreach ($dMatch_Name as $key => $value) {
            if(!in_array($value['Match_Name'],$Match_Name) && $value['Match_Name']) $Match_Name[]=$value['Match_Name'];
        } 
        //获取总数据 
        $d['nums']=count($dMatch_Name);
        $d['legname']=$Match_Name; 
        $d['page']=ceil($d['nums']/$pnum);
        return $d;
    }
    public function BaseballMorning($r){
        $p=intval($r['p']);
        $p<=0?$p='1':$p;
        $pnum=40;
        $limit=($p-1)*$pnum;
        $limit=" limit $limit,$pnum";
        $legar=array();
        $where=''; 
        if($r['leg']) {
            $leg=explode('|',$r['leg']);
            foreach ($leg as $key => $value) {
                 $legar[] = $this->public_db->escape_like_str($value);
            }
            $leg=implode("','",$legar);
            $where=" and Match_Name in('$leg')";
        }
       $sql = "SELECT Match_ID, Match_Master, Match_Guest, Match_Name, Match_Time, Match_Date, Match_IsLose, Match_Ho, Match_DxDpl,  Match_DsDpl, Match_Ao, Match_DxXpl, Match_DsSpl, Match_RGG, Match_DxGG, Match_ShowType, Match_BzM, Match_BzG 
        FROM baseball_match where  Match_CoverDate > '".date('Y-m-d 23:59:59')."' $where  order by Match_CoverDate ,Match_ID,match_name,Match_Master $limit";
        $query=$this->public_db->query($sql);
        $d['d']=$query->result_array();
        //获取总联赛名
        $sql = "SELECT Match_Name FROM baseball_match where Match_CoverDate > '".date('Y-m-d 23:59:59')."' $where ";
        $query=$this->public_db->query($sql);
        $dMatch_Name=$query->result_array();
        $Match_Name=array();
        foreach ($dMatch_Name as $key => $value) {
            if(!in_array($value['Match_Name'],$Match_Name) && $value['Match_Name']) $Match_Name[]=$value['Match_Name'];
        } 
        //获取总数据 
        $d['nums']=count($dMatch_Name);
        $d['legname']=$Match_Name; 
        $d['page']=ceil($d['nums']/$pnum);
        return $d;
    }
    
    public function FBRresults($time){
        $date=date('Y-m-d',strtotime("now")-3600*24*$time);
        $sql    = "select Match_MatchTime, Match_Type,Match_Name,Match_Master,Match_Guest,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR,Match_ID from bet_match where Match_Date='".date('m-d',strtotime($date))."' and (MB_Inball is not null or MB_Inball_HR is not NULL) and (Match_JS=1 or Match_SBJS=1) GROUP BY Match_Master,Match_Guest,Match_Name order by Match_CoverDate,iPage,iSn,Match_ID,Match_Name,Match_Master ";
        $query=$this->public_db->query($sql);
        $d=$query->result_array();

        return $d;
    }    
    
     public function BKRresults($time){
        $date=date('Y-m-d',strtotime("now")-3600*24*$time);
        $sql    ="select Match_Date,Match_Time, Match_Name,Match_Master,Match_Guest,MB_Inball_1st,TG_Inball_1st,MB_Inball_2st,TG_Inball_2st,MB_Inball_3st,TG_Inball_3st,MB_Inball_4st,TG_Inball_4st,MB_Inball_HR,TG_Inball_HR,MB_Inball_ER,TG_Inball_ER,MB_Inball,TG_Inball,MB_Inball_Add,TG_Inball_Add from  lq_match where Match_Date='".date('m-d',strtotime($date))."' and Match_JS=1 group by Match_Master order by Match_CoverDate,Match_ID asc";                         
        $query=$this->public_db->query($sql);
        $d=$query->result_array();
        return $d;
    } 
    
    public function TNRresults($time){
        $date=date('Y-m-d',strtotime("now")-3600*24*$time);
        $sql    ="select Match_Date,Match_Time, Match_Name,Match_Master,Match_Guest,MB_Inball,TG_Inball from tennis_match where MB_Inball is not null and  Match_Date='".date('m-d',strtotime($date))."' and Match_JS=1";
        $query=$this->public_db->query($sql);
        $d=$query->result_array();
        return $d;
    } 
    
    public function VBRresults($time){
        $date=date('Y-m-d',strtotime("now")-3600*24*$time);
        $sql    ="select Match_Date,Match_Time, Match_Name,Match_Master,Match_Guest,MB_Inball,TG_Inball from volleyball_match where MB_Inball is not null and  Match_Date='".date('m-d',strtotime($date))."' and Match_JS=1";
        $query=$this->public_db->query($sql);
        $d=$query->result_array();
        return $d;
    } 
    
    public function BBRresults($time){
        $date=date('Y-m-d',strtotime("now")-3600*24*$time);
        $sql    ="select Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from baseball_match where MB_Inball is not null and  Match_Date='".date('m-d',strtotime($date))."' and Match_JS=1";//
        $query=$this->public_db->query($sql);
        $d=$query->result_array();
        return $d;
    } 
    
    public function JsonCopyRight(){
        $site_id=SITEID;
        $index_id=INDEX_ID;
        $sql    ="select copy_right,web_name from  web_config where site_id='$site_id' and index_id='$index_id'";                         
        $query=$this->private_db->query($sql);
        $d=$query->row_array(0);
        return $d;
    }
        
}
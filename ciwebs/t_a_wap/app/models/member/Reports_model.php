<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Reports_model extends MY_Model {

	public function __construct() {
		parent::__construct();
	}

	//获取会员七天所有投注
	public function GetALLBet($uid,$username){
       //获取七天前日期
      for ($i=0; $i <7 ; $i++) {
          $tmp_date = date('Y-m-d', strtotime("-$i days"));
          $weeks[$i]['tmp_date'] = $tmp_date;
          $weeks[$i]['begin_date'] = $tmp_date.' 00:00:00';
          $weeks[$i]['end_date'] = $tmp_date.' 23:59:59';
      }

      $fc_arr = $this->UserFcBet($weeks,$uid);
      $sp_arr = $this->UserSpBet($weeks,$uid);
      //$video_arr = $this->UserVideoBet($weeks,$username);
      $data = array();
      $data['ErrorCode'] = 0;
      foreach ($weeks as $key => $val) {
          $data['Data'][$key]['AccountDate'] = $val['tmp_date'];

          $data['Data'][$key]['AllBet'] = $fc_arr['bet'.$key] + $sp_arr[0]['bet'.$key] + $sp_arr[1]['bet'.$key];
          $data['Data'][$key]['YxBet'] = $fc_arr['yxbet'.$key] + $sp_arr[0]['yxbet'.$key] + $sp_arr[1]['yxbet'.$key];

          $data['Data'][$key]['TotalBetNums'] = $fc_arr['num'.$key] + $sp_arr[0]['num'.$key] + $sp_arr[1]['num'.$key];
          $data['Data'][$key]['WinLoseMoney'] = $fc_arr['win'.$key] - $fc_arr['yxbet'.$key] + $sp_arr[0]['win'.$key] + $sp_arr[1]['win'.$key] - $sp_arr[0]['yxbet'.$key] - $sp_arr[1]['yxbet'.$key];
      }
      return $data;
	}

      //彩票
  public function UserFcBet($arr,$uid){
      $cstr = '';
      foreach ($arr as $key => $val) {
          $cstr .="sum(case when update_time >= '".$val['begin_date']."' and update_time <= '".$val['end_date']."' then win end) as win".$key.",sum(case when update_time >= '".$val['begin_date']."' and update_time <= '".$val['end_date']."' then money end) as bet".$key.",sum(case when update_time >= '".$val['begin_date']."' and update_time <= '".$val['end_date']."' and status in ('1','2') then money end) as yxbet".$key.",count(case when update_time >= '".$val['begin_date']."' and update_time <= '".$val['end_date']."' then id end) as num".$key.',';
      }
      $cstr .= 'uid';

      $this->db->from('c_bet');
      $this->db->where('uid',$uid);
      $this->db->where('site_id',SITEID);
      $this->db->where('js',1);
      $this->db->select($cstr);
      return $this->db->get()->row_array();
  }

  //体育
  public function UserSpBet($arr,$uid){
      $cstr = $cstr_cg = '';
      foreach ($arr as $key => $val) {
          $spcstr .= "sum(case when update_time >= '".$val['begin_date']."' and update_time <= '".$val['end_date']."' then bet_money end) as bet".$key.",sum(case when update_time >= '".$val['begin_date']."' and update_time <= '".$val['end_date']."' then win end) as win".$key.",sum(case when update_time >= '".$val['begin_date']."' and update_time <= '".$val['end_date']."' and status in ('1','2','4','5') then bet_money end) as yxbet".$key.",count(case when update_time >= '".$val['begin_date']."' and update_time <= '".$val['end_date']."' then 1 end) as num".$key.',';
      }
      foreach ($arr as $key => $val) {
          $spcgcstr .= "sum(case when update_time >= '".$val['begin_date']."' and update_time <= '".$val['end_date']."' then bet_money end) as bet".$key.",sum(case when update_time >= '".$val['begin_date']."' and update_time <= '".$val['end_date']."' then win end) as win".$key.",sum(case when update_time >= '".$val['begin_date']."' and update_time <= '".$val['end_date']."' and status in ('1','2') then bet_money end) as yxbet".$key.",count(case when update_time >= '".$val['begin_date']."' and update_time <= '".$val['end_date']."' then 1 end) as num".$key.',';
      }
      $cstr = 'uid';
      $this->db->from('k_bet');
      $this->db->where('uid',$uid);
      $this->db->where('site_id',SITEID);
      $this->db->where('is_jiesuan',1);
      $this->db->select($spcstr.$cstr);
      $sp_arr[0] = $this->db->get()->row_array();

      $this->db->from('k_bet_cg_group');
      $this->db->where('uid',$uid);
      $this->db->where('site_id',SITEID);
      $this->db->where('is_jiesuan',1);
      $this->db->select($spcgcstr.$cstr);
      $sp_arr[1] = $this->db->get()->row_array();
      return $sp_arr;
  }

  //视讯
  public function UserVideoBet($arr,$username){
      //视讯字段匹配
      //注单时间 有效下注 盈利
      $vtype = array('ag'=>array('bet_time','valid_betamount'),
                     'og'=>array('add_time','valid_amount'),
                     'mg'=>array('update_time','income','payout'),
                     'ct'=>array('transaction_date_time','availablebet'),
                     'lebo'=>array('betstart_time','valid_betamount','payout'),
                     'bbin'=>array('wagers_date','commissionable','payoff'));
      //视讯数据读取
      foreach ($vtype as $k => $v) {
          $table = $k.'_bet_record';
          $cstr = '';
          foreach ($arr as $key => $val) {
              //最近一笔结束时间为 取款时间
              //时间转换
              if ($k === 'og') {
                  $val['begin_date'] = date("Y-m-d H:i:s", strtotime("$val[begin_date] +12 hours"));
                  $val['end_date'] = date("Y-m-d H:i:s", strtotime("$val[end_date] +12 hours"));
              }elseif($k === 'ct'){
                  $val['begin_date'] = date("Y-m-d H:i:s", strtotime("$val[begin_date] +11 hours"));
                  $val['end_date'] = date("Y-m-d H:i:s", strtotime("$val[end_date] +11 hours"));
              }

              $cstr .= 'sum(case when '.$v[0]." >= '".$val['begin_date']."' and ".$v[0]." <= '".$val['end_date']."' then ".$v[1]." end) as bet".$key.',sum(case when '.$v[0]." >= '".$val['begin_date']."' and ".$v[0]." <= '".$val['end_date']."' then 1 end) as num".$key.',';
          }
          $cstr .= 'pkusername';

          $this->video_db->from($table);
          $this->video_db->select($cstr);
          $this->video_db->where('pkusername',$username);
          $this->video_db->where('site_id',SITEID);
          //单个视讯条件判断
          switch ($k) {
            case 'bbin':
                $this->video_db->where('result_type <>','-1');
                $this->video_db->where('result <>','D');
                $this->video_db->where('result <>','-1');
              break;
            case 'og':
                $this->video_db->where_in('result_type',array(1,2));
              break;
            case 'mg':
              break;
            case 'ct':
                $this->video_db->where('is_revocation',1);
              break;
            case 'lebo':
              break;
          }
          $video_arr[$k] = $this->video_db->get()->row_array();
      }
      return $video_arr;
  }

  //获取一天各个游戏投注情况
  //fc
  public function GetALLBetByGameClass($uid,$username,$tdate){
    $this->load->model('member/Member_record_model');
      $date_arr = array();
      if($tdate == "yesterday"){
        $date_arr[0]['begin_date'] = date("Y-m-d",strtotime("-1 day"))." 00:00:00";
        $date_arr[0]['end_date']   = date("Y-m-d",strtotime("-1 day"))." 23:59:59";
      }elseif($tdate == "theweek"){
        $date_arr[0]['begin_date'] =date("Y-m-d",strtotime("last Monday"))." 00:00:00";
        $date_arr[0]['end_date']   =date("Y-m-d")." 23:59:59";
      }elseif($tdate == 'lastweek'){
        $date_arr[0]['begin_date'] =date("Y-m-d",strtotime("last Monday")-604800)." 00:00:00";
        $date_arr[0]['end_date'] =date("Y-m-d",strtotime("last Monday")-86400)." 23:59:59";
      }else{
        $date_arr[0]['begin_date'] = date("Y-m-d")." 00:00:00";
        $date_arr[0]['end_date']   = date("Y-m-d")." 23:59:59";
      }
        $data = array();
        $map['table'] = "c_bet";
        $map['where']['site_id'] = SITEID;
        $map['where']['uid'] = $uid;
        $map['where']['js'] = 1;
        $map['where']['update_time >'] = $date_arr[0]['begin_date'];
        $map['where']['update_time <'] = $date_arr[0]['end_date'];
        //彩票数据
        $map['sum'] = array('money');
        $cp = $this->Member_record_model->get_bb_count_sum($map);
        $cpc = $this->Member_record_model->get_bb_count_co($map);
        //有效彩票数据
        $valid_map = $map;
        $valid_map['where_in']['type'] = 'status';
        $valid_map['where_in']['val'] = array(1,2);
        $valid_map['sum'] = array('money','win');
        $valid_cp = $this->Member_record_model->get_bb_count_sum($valid_map);

        //体育数据
        $con['where']['site_id'] = SITEID;
        $con['where']['uid'] = $uid;
        $con['table'] = "k_bet";
        $con['where']['bet_time >'] = $date_arr[0]['begin_date'];
        $con['where']['bet_time <'] = $date_arr[0]['end_date'];
        $con['sum'] = array('bet_money');
        $ty = $this->Member_record_model->get_bb_count_sum($con);
        $tyc = $this->Member_record_model->get_bb_count_co($con);

        //有效体育数据
        $valid_con = $con;
        $valid_con['where']['is_jiesuan'] = 1;
        $valid_con['where_in']['type'] = 'status';
        $valid_con['where_in']['val'] = array(1,2,4,5);
        $valid_con['sum'] = array('bet_money','win');
        $valid_ty = $this->Member_record_model->get_bb_count_sum($valid_con);

        //体育串关
        $con['table'] = 'k_bet_cg_group';
        $cg_ty = $this->Member_record_model->get_bb_count_sum($con);
        $cg_tyc = $this->Member_record_model->get_bb_count_co($con);

        $cg_where = $con;
        $cg_where['where']['is_jiesuan'] = 1;
        $cg_where['where_in']['type'] = 'status';
        $cg_where['where_in']['val'] = array(1,2,4,5);
        $cg_where['sum'] = array('bet_money','win');
        $cg_valid_ty = $this->Member_record_model->get_bb_count_sum($cg_where);
        //体育、串关总和
        $tyc += $cg_tyc;
        $ty['bet_money']+=$cg_ty['bet_money'];
        $valid_ty['bet_money']+=$cg_valid_ty['bet_money'];
        $valid_ty['win']+=$cg_valid_ty['win'];

        $cpdata = array();
        $cpdata['times'] = $cpc;
        $cpdata['count'] = 0+$cp['money'];
        $cpdata['valid_money'] = 0+$valid_cp['money'];
        $cpdata['valid_win'] = $valid_cp['win'] - $valid_cp['money'];

        $tydata = array();
        $tydata['times'] = $tyc;
        $tydata['count'] = $ty['bet_money'];
        $tydata['valid_money'] = $valid_ty['bet_money'];
        $tydata['valid_win'] = $valid_ty['win'];

      $this->load->model('Common_model');
      $this->load->library('Games');
      $copyright = $this->Common_model->get_copyright();
      $video_config = explode(',',$copyright['video_module']);
      $types = array('fc','sp');
      $types = array_merge($types,$video_config);
      $games = new Games();
      foreach ($video_config as $key => $value) {
        if ($value == 'pt' || $value == 'bbin' || $value == 'og' || $value == 'ct' ){
          continue;
        }
        if($value == 'mg'){
          $video_data['mg'] = json_decode($games->GetAvailableAmountByUser('mg', $username, $date_arr[0]['begin_date'], $date_arr[0]['end_date']));
          $video_data['mgdz'] = json_decode($games->GetAvailableAmountByUser('mg', $username, $date_arr[0]['begin_date'], $date_arr[0]['end_date'],1));
        }else{
          $video_data[$value] = json_decode($games->GetAvailableAmountByUser($value, $username, $date_arr[0]['begin_date'], $date_arr[0]['end_date']));
        }
      }


      $data = array();
      $data['ErrorCode'] = 0;
      foreach ($types as $key => $val) {
        if ($val == 'pt' || $val == 'bbin' || $val == 'og' || $val == 'ct' ){
          continue;
        }
           //总笔数
           if ($val == 'sp') {
               $data['Data'][$key]['GameClassName'] = '体育';
               $data['Data'][$key]['AllBet'] = $tydata['count'];
               $data['Data'][$key]['YxBet'] = $tydata['valid_money'];
               $data['Data'][$key]['TotalBetNums'] = $tydata['times'];
               $data['Data'][$key]['WinLoseMoney'] = $tydata['valid_win'];
               $data['Data'][$key]['GameClassID'] = $val;
           }else if($val == 'fc'){
               $data['Data'][$key]['GameClassName'] = '彩票';
               $data['Data'][$key]['AllBet'] = $cpdata['count'];
               $data['Data'][$key]['YxBet'] = $cpdata['valid_money'];
               $data['Data'][$key]['TotalBetNums'] = $cpdata['times'];
               $data['Data'][$key]['WinLoseMoney'] = $cpdata['valid_win'];
               $data['Data'][$key]['GameClassID'] = $val;
           }else if($val == 'mg'){
                $data['Data']['mg']['GameClassName'] = strtoupper($val).'视讯';
                $data['Data']['mg']['TotalBetNums'] = $video_data['mg']->data->BetBS+0;
                $data['Data']['mg']['AllBet'] = $video_data['mg']->data->BetAll+0;
                $data['Data']['mg']['WinLoseMoney'] = $video_data['mg']->data->BetPC+0;
                $data['Data']['mg']['YxBet'] = $video_data['mg']->data->BetYC+0;
                $data['Data']['mg']['GameClassID'] = 'mg';
                $data['Data']['mgdz']['GameClassName'] = strtoupper($val).'电子';
                $data['Data']['mgdz']['TotalBetNums'] = $video_data['mgdz']->data->BetBS+0;
                $data['Data']['mgdz']['AllBet'] = $video_data['mgdz']->data->BetAll+0;
                $data['Data']['mgdz']['WinLoseMoney'] = $video_data['mgdz']->data->BetPC+0;
                $data['Data']['mgdz']['YxBet'] = $video_data['mgdz']->data->BetYC+0;
                $data['Data']['mgdz']['GameClassID'] = 'mgdz';
            }else{
                $data['Data'][$key]['GameClassName'] = strtoupper($val).'视讯';
                $data['Data'][$key]['TotalBetNums'] = $video_data[$val]->data->BetBS+0;
                $data['Data'][$key]['AllBet'] = $video_data[$val]->data->BetAll+0;
                $data['Data'][$key]['WinLoseMoney'] = $video_data[$val]->data->BetPC+0;
                $data['Data'][$key]['YxBet'] = $video_data[$val]->data->BetYC+0;
                $data['Data'][$key]['GameClassID'] = $val;
            }
        }
      return $data;
  }

  //获取当天游戏所有下注类别
  public function GetALLBetByGame($type,$uid,$username,$tdate){
       $date_arr = array();
      if($tdate == "yesterday"){
        $date_arr[0]['begin_date'] = date("Y-m-d",strtotime("-1 day"))." 00:00:00";
        $date_arr[0]['end_date']   = date("Y-m-d",strtotime("-1 day"))." 23:59:59";
      }elseif($tdate == "theweek"){
        $date_arr[0]['begin_date'] =date("Y-m-d",strtotime("last Monday"))." 00:00:00";
        $date_arr[0]['end_date']   =date("Y-m-d")." 23:59:59";
      }elseif($tdate == 'lastweek'){
        $date_arr[0]['begin_date'] =date("Y-m-d",strtotime("last Monday")-604800)." 00:00:00";
        $date_arr[0]['end_date'] =date("Y-m-d",strtotime("last Monday")-86400)." 23:59:59";
      }else{
        $date_arr[0]['begin_date'] = date("Y-m-d")." 00:00:00";
        $date_arr[0]['end_date']   = date("Y-m-d")." 23:59:59";
      }
      $map['where']['uid'] = $uid;
      $map['where']['site_id'] = SITEID;
      $map['where']['index_id'] = INDEX_ID;
      $map['where']['update_time >='] = $date_arr[0]['begin_date'];
      $map['where']['update_time <='] = $date_arr[0]['end_date'];
      //return $this->fcBetList($map);
      if ($type == 'fc') {
          $rdata = $this->fcBetList($map);
      }elseif($type == 'sp'){
          $rdata[0] = $this->spBetList($map);

      }

      $data = array();
      $data['ErrorCode'] = 0;
      $data['Data'][$key] = array();
      foreach ($rdata as $key => $val) {
          $data['Data'][$key]['GameID'] = $val['type'];
          $data['Data'][$key]['GameName'] = $val['type'];
          $data['Data'][$key]['AllBet'] = $val['bet'] + 0;
          $data['Data'][$key]['YxBet'] = $val['yxbet'] + 0;

          $data['Data'][$key]['TotalBetNums'] = $val['num'] + 0;
          $data['Data'][$key]['WinLoseMoney'] = $val['win'] - $val['yxbet'] + 0;
      }

      return $data;

  }

  //彩票类型下注
  public function fcBetList($map){
      $this->db->from('c_bet');
      $this->db->where($map['where']);
      $this->db->where('js','1');
      $this->db->group_by('type');
      $this->db->select("sum(money) as bet,sum(case when status in('1','2') then money end) as yxbet,sum(case when status in('1','2') then win end) as win,count(1) as num,uid,type");
      $res = $this->db->get()->result_array();
      //echo $this->db->last_query();exit();
      return $res;
  }

    //体育类型下注
  public function spBetList($map){
      $this->db->from('k_bet');
      $this->db->where($map['where']);
      $this->db->where('is_jiesuan',1);
      $this->db->select("sum(bet_money) as bet,sum(case when status in('1','2','4','5') then bet_money end) as yxbet,sum(win) as win,count(1) as num,uid");
      $sparr = $this->db->get()->row_array();
      //串关
      $this->db->from('k_bet_cg_group');
      $this->db->where($map['where']);
      $this->db->where('is_jiesuan',1);
      $this->db->select("sum(bet_money) as bet,sum(case when status in('1','2') then bet_money end) as yxbet,count(1) as num,sum(win) as win,uid");
      $spcgarr = $this->db->get()->row_array();
      if ($sparr['num']) {$sparr['type'] = '体育单式';}
      if ($spcgarr['num']) {$spcgarr['type'] = '体育串关';}

      if ($sparr['num'] && $spcgarr['num']) {
          return array_merge($sparr,$spcgarr);
      }elseif($sparr['num']){
          return $sparr;
      }else{
          return $spcgarr;
      }
  }


  //获取额度转换交易记录
  public function get_cash_record($uid){
      $this->db->from('k_user_cash_record');
      $this->db->where('index_id',INDEX_ID);
      $this->db->where('site_id',SITEID);
      $this->db->where('uid',$uid);
      $this->db->where('cash_type',1);
      $this->db->where('cash_do_type',1);
      $this->db->order_by('cash_date desc');
      //$this->db->limit(10);
      $cash_record = $this->db->get()->result_array();
      $json = array();
      $json['ErrorCode'] = 0;
      $result = array();
      $result['SearchTime'] = date('Y-m-d H:i:s');
      $result['List'] = array();
      foreach ($cash_record as $key => $value) {
        $arr = explode(",",$value['remark']);
        $arr1 = explode("转",$arr[0]);
        $arr2 = explode("：",$arr1[1]);
        $arr3 = explode(":",$arr2[0]);
        $arr4 = explode("出",$arr3[0]);
        if($arr4[1]){
          $InWallet = $arr4[1];
        }else{
          $InWallet = $arr4[0];
        }
        $result['List'][$key]['OutWallet'] = $arr1[0];
        $result['List'][$key]['InWallet'] = $InWallet;
        $result['List'][$key]['TransferDateTime'] = $value['cash_date'];
        $result['List'][$key]['Amount'] = '--'.$value['cash_num'].'--';
        $result['List'][$key]['TransferState'] = '--'.$value['cash_do_type'].'--';
        $result['List'][$key]['State'] = "转账成功";
      }
      $json['Data'] = $result;
      return $json;
  }

      //获取彩票当天输赢
  public function GetTodayWinLossWithMember($uid){
      /* $this->db->from('c_bet');
      $this->db->where('uid',$uid);
      $this->db->where('update_time >=',date('Y-m-d').' 00:00:00');
      $this->db->where('update_time <=',date('Y-m-d').' 23:59:59');
      $this->db->where('site_id',SITEID);
      $this->db->where_in('status',array(1,2));
      $this->db->select("uid,sum(win-money) as win");
      return $this->db->get()->row_array(); */
  		//$starttime = "2016-01-23 00:00:00";
  		//$endtime  = "2016-01-23 23:59:59";
	  	$starttime = date('Y-m-d')." 00:00:00";
	  	$endtime  = date('Y-m-d')." 23:59:59";
	  	
	  	$this->private_db->select('sum(win) as win,sum(money) as bjin');
	  	$where = "status = 1 and js =1 and uid =".$uid;
	  	$this->private_db->where($where." and addtime BETWEEN '".$starttime."' and '".$endtime."'");
	  	$query = $this->private_db->get('c_bet');
	  	//echo $this->private_db->last_query();exit;
	  	$rows = $query->result_array();
	  	$win = $rows[0]['win']-$rows[0]['bjin'];
	  	
	  	$this->private_db->select('sum(money) as money');
	  	$where1 = "status = 2 and js =1 and uid =".$uid;
	  	$this->private_db->where($where1." and addtime BETWEEN '".$starttime."' and '".$endtime."'");
	  	$query1 = $this->private_db->get('c_bet');
	  	
	  	$rows1 = $query1->result_array();
	  	$money = $rows1[0]['money'];
	  	$res_j['win'] = intval($win) - intval($money);
	  	//p($res_j);exit;
	  	if($res_j['win'] == 0){
	  		$res_j['win'] = '0.00';
	  	}
	  	
	  	return $res_j;
  }

  //存款
  public function GetDepositReport($uid,$get){
    $pagesize    = $get['PageSize'];
    $CurrentPage = $get['CurrentPage'];
    $offset=($CurrentPage-1)*$pagesize;
    $time = date('Y-m-d',strtotime('-7days'))." 00:00:00";
    $this->db->where('uid',$uid);
    $this->db->where('log_time >=',$time);
    $this->db->order_by('log_time desc');
    $out_data = $this->db->get('k_user_bank_in_record',$pagesize,$offset)->result_array();
    $json = array();
    $json['ErrorCode'] = 0;
    $json['Data']['SearchTime'] = date('Y-m-d H:i:s');
    $info = array();
    foreach ($out_data as $k => $v) {
        $info[$k]['ID']            = $v['order_num'];
        $info[$k]['Amount']        = floatval($v['deposit_money']);
        $info[$k]['State']         = intval($v['make_sure']);
        $info[$k]['ApplyDateTime'] = $v['log_time'];
        $info[$k]['ActionType']    = $v['into_style'];
        $info[$k]['BankName']      = "";
        $info[$k]['BankAccount']   = '';
        $info[$k]['CancelRemark']  = $v['remark'];
        $info[$k]['Remark']        = $v['remark'];
    }
    $json['Data']['List'] = $info;
    return $json;
  }


  //取款记录
  public function GetWithdrawReport($uid,$get){
    $pagesize    = $get['PageSize'];
    $CurrentPage = $get['CurrentPage'];
    $offset=($CurrentPage-1)*$pagesize;
    $time = date('Y-m-d',strtotime('-7days'))." 00:00:00";
    $this->db->where('uid',$uid);
    $this->db->where('out_time >=',$time);
    $this->db->order_by('out_time desc');
    $out_data = $this->db->get('k_user_bank_out_record',$pagesize,$offset)->result_array();
    $json = array();
    $json['ErrorCode'] = 0;
    $json['Data']['SearchTime'] = date('Y-m-d H:i:s');
    $info = array();
    foreach ($out_data as $k => $v) {
        $info[$k]['ID']            = $v['order_num'];
        $info[$k]['Amount']        = floatval($v['outward_num']);
        $info[$k]['State']         = intval($v['out_status']);
        $info[$k]['ApplyDateTime'] = $v['out_time'];
        $info[$k]['ActionType']    = 2;
        $info[$k]['BankName']      = "";
        $info[$k]['BankAccount']   = '';
        $info[$k]['CancelRemark']  = $v['remark'];
        $info[$k]['Remark']        = $v['remark'];
    }
    $json['Data']['List'] = $info;
    return $json;
  }

  //GetTransferLogs
  //某一类型的注单报表
  //{DateTime: "today", GameClassID: "fc", GameID: "北京赛车PK拾", PageSize: 10, CurrentPage: 1}
  function GetAccountByGameDetail($times,$gametype,$pagesize,$currentage){

//lottery_today()
  }

  //会员专区，交易记录，体育
  public function tc_record($start_date,$end_date,$pagesize,$currentage,$type=1){
    $this->load->model('Common_model');
    $copyright = $this->Common_model->get_copyright();
      $video_config = explode(',',$copyright['video_module']);
      foreach ($video_config as $key => $value) {
        $video_config[$key] = strtoupper($value);
      }
    $uid=$_SESSION['uid'];

    if($gtype == 1){
      $sql_union = "(select * from k_bet) as bet";
    }else if($gtype == 2){
      $this->db->from('k_bet_cg');
      $this->db->where('site_id',SITEID);
      $this->db->where('uid',$uid);
      $deposit = $this->db->get()->result_array();
      $this->db->from('k_bet_cg');
      $this->db->where('site_id',SITEID);
      $this->db->where('uid',$uid);
      $this->db->group_by('gid');
      $deposit2 = $this->db->get()->result_array();

      $sql_union=array();
      foreach($deposit2 as $key=>$value){
        $sql_union[] = "(select * from k_bet_cg_group where gid = '".$value['gid']."') as bet";
      }
      //var_dump($sql_union);die;
    }


    $map['where']="uid='".$uid."' and site_id='".SITEID."'";

    //时间判断
    if (!empty($start_date)) {
      $s_date = $start_date;
      $this->add("s_date", $s_date);
    }else{
      $s_date  = date("Y-m-d",time());
      $this->add("s_date", $s_date);
    }

    if (!empty($end_date)) {
      $e_date = $end_date;
      $this->add("e_date", $e_date);
    }else{
      $e_date = date("Y-m-d",time());
      $this->add("e_date", $e_date);
    }
    $map['where'] .= " and bet_time > '".$s_date." 00:00:00' and bet_time < '".$e_date." 23:59:59'";
    $map['order'] =" order by bet_time desc";


    if($gtype == 1){
      $count = $this->Member_record_model->get_record_b_count($sql_union,$map);
    }else if($gtype == 2){
      foreach ($sql_union as $key => $value) {
        $count = $this->Member_record_model->get_record_b_count($value,$map);
      }
    }
    //分页
    $perNumber=isset($pagesize)?$pagesize:10; //每页显示的记录数
    $totalPage=ceil($count/$perNumber); //计算出总页数
    $page=isset($currentage)?$currentage:1;
    if($totalPage<$page){
      $page = 1;
    }
    $startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
    $map['limit']=$startCount;
    $map['limit2'] =$perNumber;

    $data = array();
    if($gtype == 1){
      $data = $this->Member_record_model->get_record_b($sql_union,$map);
    }else if($gtype == 2){
      foreach($sql_union as $key =>$value){
        //var_dump($value);die;
        $data[] = $this->Member_record_model->get_record_b($value,$map);
      }
    }

    $array = array();
    foreach ($data as $k=>$val){
      if($gtype == 2 && !empty($val)){
        foreach ($val as $key => $value) {
          //$gtype == 2 $val['ball_sort'] == '串关'
          $map_c['where'] = "gid in (".$value['gid'].")";
          $map_c['order'] = "bid desc";
          $data_cg = $this->Member_record_model->get_record_cg($map_c);
        }
          $value['chuanlian'] = $data_cg;
          $value['ball_sort'] = '串关';
          $array[] = $value;
      }
      else if(!empty($val)){
        $array[] = $val;
      }else{
        $array = array();
      }
    }

    /*
    $this->add("gtype", $gtype);
    $this->add("totalPage",$totalPage);
    $this->add("data", $array);
    $this->add('video_config',$video_config);
    $this->display('member/tc_record.html');
    */
  }

  //会员专区，交易记录，彩票
  public function lottery_today($gtype,$start_date,$end_date,$pagesize,$currentage){
    $this->load->model('Common_model');
    $copyright = $this->Common_model->get_copyright();
      $video_config = explode(',',$copyright['video_module']);
      foreach ($video_config as $key => $value) {
        $video_config[$key] = strtoupper($value);
      }
    $uid=$_SESSION['uid'];
    $arrry['c_bet.uid'] = $uid;
    /*
    $fctype = array('1'=>'重庆时时彩','2'=>'重庆快乐十分',
    '3'=>'广东快乐十分','4'=>'北京赛车PK拾','5'=>'福彩3D',
    '6'=>'排列三','7'=>'北京快乐8','8'=>'六合彩','9'=>'江苏快3',
    '10'=>'吉林快3','11'=>'新疆时时彩','12'=>'天津时时彩',
    '13'=>'江西时时彩');*/
        //彩票种类判断
    if (!empty($gtype)) {
         $arrry['c_bet.type'] = $gtype;
    }
    if($start_date == "yesterday"){
        $date_arr[0]['begin_date'] = date("Y-m-d",strtotime("-1 day"))." 00:00:00";
        $date_arr[0]['end_date']   = date("Y-m-d",strtotime("-1 day"))." 23:59:59";
    }elseif($start_date == "theweek"){
        $date_arr[0]['begin_date'] =date("Y-m-d",strtotime("last Monday"))." 00:00:00";
        $date_arr[0]['end_date']   =date("Y-m-d")." 23:59:59";
    }elseif($start_date == 'lastweek'){
        $date_arr[0]['begin_date'] =date("Y-m-d",strtotime("last Monday")-604800)." 00:00:00";
        $date_arr[0]['end_date'] =date("Y-m-d",strtotime("last Monday")-86400)." 23:59:59";
    }else{
        $date_arr[0]['begin_date'] = date("Y-m-d")." 00:00:00";
        $date_arr[0]['end_date']   = date("Y-m-d")." 23:59:59";
    }

        //时间
    $arrry['c_bet.update_time >'] = $date_arr[0]['begin_date'];
    $arrry['c_bet.update_time <'] = $date_arr[0]['end_date'];

    $this->load->model('member/Member_record_model');
    $count = $this->Member_record_model->get_record_cp_count($arrry);
    //分页
    $perNumber=$pagesize; //每页显示的记录数
    $totalPage=ceil($count/$perNumber); //计算出总页数
    $page=$currentage;
    if($totalPage<$page){
      $page = 1;
    }
   
    $map['order'] = 'c_bet.update_time desc';
    $startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
    $map['limit']=$startCount;
    $map['limit2'] =$perNumber;


    $data = $this->Member_record_model->get_record_cp($arrry,$map);
    /*
    $this->add("totalPage",$totalPage);
    $this->add("s_date", $s_date);
    $this->add("e_date", $e_date);
    $this->add("data", $data);
    $this->add('video_config',$video_config);
    $this->display('member/lottery_today.html');
    */
    return $data;
  }

  //会员专区、交易记录、视讯
  public function sx_today(){
    $this->load->model('Common_model');
    $copyright = $this->Common_model->get_copyright();
      $video_config = explode(',',$copyright['video_module']);
      foreach ($video_config as $key => $value) {
        $video_config[$key] = strtoupper($value);
      }
    $array['Company'] = $this->input->get('Company');
    $this->add('Company',$array['Company']);
    $this->add('video_config',$video_config);
    $this->display('member/sx_today.html');
  }
  public function sx_today_do(){
    if($_SESSION['shiwan'] == 1){
        $info = array();
        $info['error'] = '请申请正式账号！祝您游戏愉快';
        echo json_encode($info);exit;
      }
    $this->load->library('Games');
    $array['username'] = $_SESSION['username'];
    $array['Company'] = strtolower($this->input->get('g_type'));
    $array['VideoType'] = $this->input->get('VideoType');
    $array['gametype'] = $this->input->get('gametype');
    $array['start_date'] = $this->input->get('S_Time');
    $array['end_date'] = $this->input->get('E_Time');
    $array['OrderId'] = $this->input->get('OrderId');
    $array['page'] = $this->input->get('Page');
    $array['page_num'] = $this->input->get('Page_Num')?$this->input->get('Page_Num'):20;
    $array['agentid'] = $this->input->get('agentid');

    //判断mg电子
    if($array['Company'] == 'mgc'){
      $array['Company'] = 'mg';
    }
    //时间判断
    if (empty($array['start_date'])) {
      $array['start_date']  = date("Y-m-d");
    }
    if (empty($array['end_date'])) {
      $array['end_date'] = date("Y-m-d");
    }
    //订单号查询
    if(!empty($order)){
      if(preg_match("/^[\W]*$/i",$array['OrderId'])){
        echo '<script>alert("您输入的订单号非法")</script>';
      }
    }
    if(empty($array['page'])){
      $array['page'] = 1;
    }

    if(!empty($array['VideoType']))$this->add("ty_name", "VideoType");
    if(!empty($array['gametype']))$this->add("ty_name", "gametype");

    $games = new Games();
    $data = $games->GetBetRecord($array['Company'], $array['username'], $array['OrderId'], $array['VideoType'],$array['gametype'], $array['start_date']." 00:00:00", $array['end_date']." 23:59:59",$array['agentid'], $array['page'], $array['page_num']);
    $data = json_decode($data,true);
      //获取视讯游戏类型的名字
      $video = $this->Member_record_model->get_all_one($array['Company']);
      $result1 = $data['data'];
      $result = $data['data']['data'];
      if(!empty($result) && !empty($video) && $array['Company'] != 'bbin'){
        foreach($result as $key=>$value){
          foreach ($video as $k => $v) {
            if($v['type'] == $value['BetType']){
              $data['data']['data'][$key]['BetType'] = $v['name'];
            }
          }
        }
      }elseif (!empty($result) && !empty($video) && $array['Company'] == 'bbin') {
        foreach($result as $key=>$value){
            if($value['BetType'] == '3'){
              $data['data']['data'][$key]['BetType'] = '视讯';
            }elseif($value['BetType'] == '5'){
              $data['data']['data'][$key]['BetType'] = '电子';
            }
        }
      }
    echo json_encode($data);
  }

}
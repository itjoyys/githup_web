<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Cash_agent_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

		//手续费修改
	function fee_list_edit($map,$data){
		$db_model['tab'] = 'k_fee_set';
		$db_model['type'] = 1;
		return $this->M($db_model)->where($map)->update($data);

	}
		//代理退佣增加
	function endhire_add($data){
		$db_model['tab'] = 'k_hire_config';
		$db_model['type'] = 1;
		return $this->M($db_model)->add($data);

	}

		//代理退佣修改
	function endhire_edit($map,$data){
		$db_model['tab'] = 'k_hire_config';
		$db_model['type'] = 1;
		return $this->M($db_model)->where($map)->update($data);
	}

	//管理期数修改
	function qishu_list_edit($map,$data){
		$db_model['tab'] = 'k_qishu';
		$db_model['type'] = 1;
		return $this->M($db_model)->where($map)->update($data);

	}
		//退佣查询
  	public function agent_serch_list($where){
      $db_model = array();
      $db_model['tab'] = 'k_user_agent_record';
      $db_model['type'] = 1;
      return $this->M($db_model)->where($where)->order('id ASC')->select();
  	}


        /**
        * 获取一定条件下的--代理用户uid数组
        * @param  [int] $atype       [0 全部,1 股东,2 总代理,3  代理]
        * @param  [string] $username [当type 1=股东, 2=总代理, 3=代理的名字]
        * @return [array]            [用户id的数组]
        */
       public function getallagentid($atype, $username) {
               $arr_agentid = array();
               $where = " 1=1 ";
               $model = array();
               $model['tab'] = 'k_user_agent';
               $model['type'] = 1;
               if ($atype == 0) {
                       $where = "agent_type= 'a_t' and site_id='" . SITEID . "' and is_demo='0'";
               } else if ($atype == 1) {
                       //查询总代pid
                       $inwhere = "select id from k_user_agent WHERE agent_type = 's_h'  AND site_id='" . SITEID . "' and is_demo='0' and agent_user ='" . $username . "'";
                       $agent = $this->M($model)->field("id")
                                      ->where("agent_type = 'u_a'  AND site_id='" . SITEID . "' and is_demo='0' AND pid IN($inwhere)")
                                      ->select('u-id');
                       if (empty($agent)) {
                               return $arr_agentid;
                       }
                       $agentids = array();
                       foreach ($agent as $value) {
                               $agentids[] = $value["id"];
                       }
                       $where = "agent_type = 'a_t' AND pid in(" . implode(",", $agentids) . ") AND site_id='" . SITEID . "' and is_demo='0'";
               } else if ($atype == 2) {
                       //查询总代pid
                       $agent = $this->M($model)->field("id")
                                      ->where("agent_type = 'u_a' AND site_id='" . SITEID . "' and is_demo='0' AND agent_user ='" . $username . "'")
                                      ->find('u-id');
                       if (empty($agent)) {
                               return $arr_agentid;
                       }
                       $where = "agent_type = 'a_t' AND pid=" . $agent['id'] . " and site_id='" . SITEID . "' and is_demo='0'";
               } else if ($atype == 3) {
                       //查询代理pid
                       $where = "agent_type = 'a_t' and site_id='" . SITEID . "' and is_demo='0' and agent_user ='" . $username . "'";
               }
               $rows = $this->M($model)->field("id,agent_user,agent_name,bankid,bankno")->where($where)->select('u-id');
               return $rows;
       }

       /**
        * 根据代理商ID 和期数日期条件获取彩票下注情况
        * @param  [string] $str       [代理商ID字符串，用逗号分隔]
        * @param  [array] $date [起始时间]
        * @return [array]            [各个代理商旗下会员下注情况]
        */
       	Public function return_fcbet($str,$date){
            $db_model['tab'] = "c_bet";
            $db_model['type'] = 1;
            $mapFc['site_id'] = $_SESSION['site_id'];
            $mapFc['agent_id'] = array('in','('.$str.')');
            $mapFc['update_time'] = array(
                              array('>=',$date[0]),
                              array('<=',$date[1])
                             );//结算时间
             $fcBet = $this->M($db_model)
            ->field("username as pkusername ,sum(case when status in (1,2) then money end) as valid_betamount,sum(case when js = 1 then win end) as netamount,agent_id")->where($mapFc)->group('pkusername')->select("u-pkusername");
            return $fcBet;
        }

        /**
        * 根据代理商ID 和期数日期条件获取体育下注情况
        * @param  [string] $str       [代理商ID字符串，用逗号分隔]
        * @param  [array] $date [起始时间]
        * @return [array]            [各个代理商旗下会员下注情况]
        */
       	public function return_kbet($str,$date){
            $db_model['tab'] = "k_bet";
            $db_model['type'] = 1;
            $mapSp['site_id'] = $_SESSION['site_id'];
            $mapSp['agent_id'] = array('in','('.$str.')');
            $mapSp['update_time'] = array(
                              array('>=',$date[0]),
                              array('<=',$date[1])
                             );
    	return $this->M($db_model)->field("username as pkusername,sum(case when status in (1,2,4,5) then bet_money end) as valid_betamount,sum(case when is_jiesuan = 1 then win end) as netamount,agent_id")->where($mapSp)->group('pkusername')->select("u-pkusername");
        }

        /**
        * 根据代理商ID 和期数日期条件获取串关下注情况
        * @param  [string] $str       [代理商ID字符串，用逗号分隔]
        * @param  [array] $date [起始时间]
        * @return [array]            [各个代理商旗下会员下注情况]
        */
        public function return_cg($str,$date){
            $db_model['tab'] = "k_bet_cg_group";
            $db_model['type'] = 1;
            $mapSp['site_id'] = $_SESSION['site_id'];
            $mapSp['agent_id'] = array('in','('.$str.')');
            $mapSp['update_time'] = array(
                              array('>=',$date[0]),
                              array('<=',$date[1])
                             );
    	return $this->M($db_model)->field("username as pkusername,sum(case when status in (1,2) then bet_money end) as valid_betamount,sum(case when is_jiesuan = 1 then win end) as netamount,agent_id")->where($mapSp)->group('pkusername')->select("u-pkusername");
        }

        /**
        * 根据代理商ID 和期数日期条件获取视讯下注情况
        * @param  [string] $str       [代理商ID字符串，用逗号分隔]
        * @param  [string] $type       [视讯类型]
        * @param  [array] $date [起始时间]
        * @return [array]            [各个代理商旗下会员下注情况]
        */
        public function get_video_bet($str,$type,$date = array()){
            $field_name=$this->time_type($type);
            $map[$field_name[0]]=array(array('>=',$date[0]),array('<=',$date[1]));
            $map['site_id'] = $_SESSION['site_id'];
            $map['agent_id'] = array('in','('.$str.')');
            if ($type == 'mgdz') {
                $map['module_id'] =  array('<','28');
                $db_model['tab'] = 'mg_bet_record';
            }elseif ($type == "mg") {
                //mg视讯类别
                $db_model['tab'] = 'mg_bet_record';
                $map['module_id'] =  array('in','(28,29,30,32)');
            }elseif ($type == "bbdz") {
                //bbdz
                $db_model['tab'] = 'bbin_bet_record';
                $map['gamekind'] = 5;
            }elseif ($type == "bbin") {
                //bbin视讯类别
                $db_model['tab'] = 'bbin_bet_record';
                $map['gamekind'] = array('in','(1,3,12,15)');
            }else{
                //添加pt电子
                if ($type == 'pt') {
                    $map['Bet + Win'] = array('>',0);
                }
                $db_model['tab'] = $type.'_bet_record';
            }
            $db_model['type'] = 3;
              //判断是否联表
           // $is_union = $this->video_union($type,$date);
            if ($is_union) {
                return $this->M($db_model)->field($field_name[1])->where($map)->group('pkusername')->unselect('u-pkusername',$is_union);
            }else{
                return $this->M($db_model)->field($field_name[1])->where($map)->group('pkusername')->select("u-pkusername");
            }

        }

        //根据不同视讯返回对应数据库字段
    public function time_type($type){
        //注单时间 下注 有效下注 结果 游戏类型 下注详情
        switch ($type)
        {
        case 'lebo':

            $field_name = array('update_time',"pkusername,sum(betamount) as bet_amount,sum(valid_betamount) as valid_betamount,sum(payout) as netamount,agent_id");
            break;
        case 'bbin':

            $field_name = array('wagers_date',"pkusername,sum(betamount) as bet_amount,sum(commissionable) as valid_betamount,sum(payoff) as netamount,agent_id");
            break;

        case 'bbdz':

            $field_name = array('wagers_date',"pkusername,sum(betamount) as bet_amount,sum(commissionable) as valid_betamount,sum(payoff) as netamount,agent_id");
            break;
        case 'mgdz':
            $field_name = array('date',"pkusername,sum(income) as bet_amount,sum(income) as valid_betamount,sum(payout) as netamount,agent_id");
            break;

        case 'mg':

            $field_name = array('date',"pkusername,sum(income) as bet_amount,sum(income) as valid_betamount,sum(payout) as netamount,agent_id");
            break;
        case 'ag':
            $field_name = array('bet_time',"pkusername,sum(bet_amount) as bet_amount,sum(valid_betamount) as valid_betamount,sum(netamount) as netamount,agent_id");

          break;
        case 'ct':

            $field_name = array('transaction_date_time',"pkusername,betpoint,availablebet,win_or_loss-betpoint,sum(betpoint) as bet_amount,sum(availablebet) as valid_betamount,sum(win_or_loss-betpoint) as netamount,agent_id");
            break;
        case 'og':

            $field_name = array('add_time',"pkusername,sum(betting_amount) as bet_amount,sum(valid_amount) as valid_betamount,sum(win_lose_amount) as netamount,agent_id");
          break;
        case 'pt':
            $field_name = array('GameDate',"pkusername,sum(Bet) as bet_amount,sum(Bet) as valid_betamount,sum(Win) as netamount,agent_id");
          break;
        default:

            $field_name = array('betstart_time',"pkusername,sum(betamount) as bet_amount,sum(valid_betamount) as valid_betamount,sum(payout) as netamount,agent_id");
        }
        return $field_name;
    }


    //视讯是否联表判断
    public function video_union($type,$date){
        if (date('Ym',strtotime($date[1])) != date('Ym',strtotime($date[0])) && date('Ym') >= date('Ym',strtotime($date[1]))) {
          if($type == 'mgdz'){
             $tab1 = 'mg_bet_record_'.date('Ym',strtotime($date[0]));
             $tab2 = 'mg_bet_record_'.date('Ym',strtotime($date[1]));
          }else if($type == 'bbdz'){
              $tab1 = 'bbin_bet_record_'.date('Ym',strtotime($date[0]));
              $tab2 = 'bbin_bet_record_'.date('Ym',strtotime($date[1]));
          }else{
            $tab1 = $type.'_bet_record_'.date('Ym',strtotime($date[0]));
            $tab2 = $type.'_bet_record_'.date('Ym',strtotime($date[1]));
          }
            return array('tab1'=>$tab1,'tab2'=>$tab2);
        }
    }

    /*
    * 根据所有下注情况和代理ID返回有效会员数
    * @param  [array] $fcBet       [彩票下注情况]
    * @param  [array] $spBet       [体育下注情况]
    * @param  [array] $spc_bet     [串关下注情况]
    * @param  [array] $userRe     [视讯下注情况]
    * @param  [array] $valid_menber [有效会员门槛]
    * @param  [int] $agen_id       [代理ID]
    * @return [int]            [有效会员数]
    */
    public function get_valid_menber($fcBet,$spBet,$spc_bet,$userRe,$valid_menber,$agent_id){
        $arr = array_merge((array)$fcBet,(array)$spBet,(array)$spc_bet,(array)$userRe['mg'],(array)$userRe['mgdz'],(array)$userRe['ag'],(array)$userRe['og'],(array)$userRe['ct'],(array)$userRe['lebo'],(array)$userRe['bbin'],(array)$userRe['bbdz'],(array)$userRe['pt']);
        foreach($arr as $k => $v){
            if($agent_id != $v['agent_id'] || $v['valid_betamount'] < $valid_menber){
                unset($arr[$k]);
            }
        }
        return count($arr);
    }

     //数组合并
    public function v_array($arr = array(),$bet = array()){
        if (!empty($arr) && !empty($bet)) {

            $keys_uid = array_keys($arr);
            $keys_uid_cg = array_keys($bet);
            $intersect_arr = array_intersect($keys_uid, $keys_uid_cg);
            $arr = array_merge_recursive($arr,$bet);
        }elseif (empty($arr) && !empty($bet)) {
            $arr = $bet;
        }
        return $arr;
    }

    /*
    * 根据所有当前期数ID和代理ID返回有效会员数
    * @param  [string] $agentsstr       [代理ID]
    * @param  [array] $qs_info       [期数]
    * @return [array] $oldbets         [前期累计数据]
    */
    public function get_old_info($agentsstr,$qs_info){
        //前期累计数据
        $mapSp = array();
        $db_model['tab'] = "k_user_agent_record";
        $db_model['type'] = 1;
        $mapSp['site_id'] = $_SESSION['site_id'];
        $mapSp['agent_id'] = array('in','('.$agentsstr.')');
        $mapSp['qishu_starttime'] = array(
                  array('<',$qs_info[0]['start_date']),
                 );
        $result = $this->M($db_model)->field("max(qishu_id) as qishu_id")->where($mapSp)->limit(1)->find();
        if(empty($result)){ return array();};
        $db_model['tab'] = 'k_user_agent_record';
        $db_model['type'] = 1;
        return $this->M($db_model)->field('now_bet as oldbet,now_cbet as oldcbet,now_kbet as oldkbet,now_vbet as oldvbet,now_ebet as oldebet,now_validbet as oldvalidbet ,now_validcbet as oldvalidcbet,now_validkbet as oldvalidkbet,now_validvbet as oldvalidvbet,now_validebet as oldvalidebet,nowcash as oldcash,valid_usernum as old_usernum,agent_id')->where("agent_id in (" . $agentsstr . ") and status in (0,4) and qishu_starttime<'" . $qs_info[0]['start_date'] . "' and site_id='" . $_SESSION['site_id'] . "' and type=0 and qishu_id = '" . $result['qishu_id'] . "'")->select('u-agent_id');

    }

    /*
    * 根据所有当前期数数据和代理ID返回会员注册送优惠
    * @param  [string] $agentsstr       [代理ID]
    * @param  [array] $date       [起始时间]
    * @param  [array] $field       [查询的字段]
    * @param  [array] $field2       [查询的字段]
    * @param  [array] $uid       [会员ID]
    * @return [array] $qt_reg_cash         [会员注册送优惠]
    */
    public function get_qt_zc_cash($agentsstr,$date,$field,$field2,$uid){
        $mapSp = array();
        $db_model['tab'] = "k_user_cash_record";
        $db_model['type'] = 1;
        $mapSp['site_id'] = $_SESSION['site_id'];
        $mapSp['agent_id'] = array('in','('.$agentsstr.')');
        $mapSp['cash_type'] = 6;
        $mapSp['cash_do_type'] = 1;
        if($uid != ''){
            $mapSp['uid'] = $uid;
        }
        $mapSp['cash_date'] = array(
                              array('>=',$date[0]),
                              array('<=',$date[1])
                             );
       if($field2 == ''){
            return $this->M($db_model)->field($field)->where($mapSp)->select();
       }else{
            return $this->M($db_model)->field($field)->where($mapSp)->group($field2)->select('u-'.$field2);
       }

    }

    /*
    * 根据所有当前期数数据和代理ID返回天天反水
    * @param  [string] $agentsstr       [代理ID]
    * @param  [array] $date       [起始时间]
    * @param  [array] $field       [查询的字段]
    * @param  [array] $field2       [查询的字段]
    * @param  [array] $uid       [用户ID]
    * @return [array] $defection         [天天反水]
    */
    public function get_defection($agentsstr,$date,$field,$field2,$uid){
        $mapSp = array();
        $db_model['tab'] = "k_user_discount_count";
        $db_model['type'] = 1;
        $mapSp['site_id'] = $_SESSION['site_id'];
        $mapSp['agent_id'] = array('in','('.$agentsstr.')');
        $mapSp['state'] = 1;
        if($uid != ''){
            $mapSp['uid'] = $uid;
        }
        $mapSp['do_time'] = array(
                              array('>=',$date[0]),
                              array('<=',$date[1])
                             );
       if($field2 == ''){
           return $this->M($db_model)->field($field)->where($mapSp)->select();
        }else{
            return $this->M($db_model)->field($field)->where($mapSp)->group($field2)->select('u-'.$field2);
        }

    }

     /*
    * 根据所有当前期数数据获取手续费设定
    * @param  [array] $date       [起始时间]
    * @return [array] $fee         [手续费设定]
    */
    public function get_fee($date){
        $mapSp = array();
        $db_model['tab'] = "k_fee_set";
        $db_model['type'] = 1;
        $mapSp['site_id'] = $_SESSION['site_id'];
        $mapSp['is_delete'] = 0;
        $mapSp['valid_date'] = array(
                              array('<=',$date[0])
                             );

	return $this->M($db_model)->where($mapSp)->limit(1)->order('valid_date desc')->find();
    }

    /*
    * 根据所有当前期数数据 手续费设定 代理用户名 获取会员前台申请出款费用
    * @param  [string] $agentsstr  [代理用户名]
    * @param  [array] $date       [起始时间]
    * @param [array] $where         [手续条件]
    * @param [string] $field         [查询字段]
    * @param [string] $field2         [查询字段]
    * @param [string] $uid         [用户ID]
    * @return [int] $qt_out_fee   [会员前台申请出款费用]
    */
    public function get_qt_out_fee($agentsuser,$date,$where,$field,$field2,$uid){
        $mapSp = array();
        $db_model['tab'] = "k_user_bank_out_record";
        $db_model['type'] = 1;
        $mapSp['site_id'] = $_SESSION['site_id'];
        $mapSp['agent_user'] = array('in','('.$agentsuser.')');
        if($where != ''){
            $mapSp['outward_money'] = $where;
        }
        if($uid != ''){
            $mapSp['uid'] = $uid;
        }
        $mapSp['out_status'] = 1;
        $mapSp['do_time'] = array(
                              array('>=',$date[0]),
                              array('<=',$date[1])
                             );
        if($field2 == ''){
            return $this->M($db_model)->field($field)->where($mapSp)->select();
        }else{
            return $this->M($db_model)->field($field)->where($mapSp)->group($field2)->select('u-'.$field2);
        }


    }

    /*
    * 根据所有当前期数数据 手续费设定 代理用户名 获取会员前台申请入款费用
    * @param  [string] $agentsstr  [代理用户名]
    * @param  [array] $date       [起始时间]
    * @param [array] $where         [手续条件]
    * @param [int] $type         [入款类型 1为公司入款 2为线上入款]
    * @param [string] $field         [查询字段]
    * @param [string] $field2         [查询字段]
    * @param [string] $uid         [用户ID]
    * @return [int] $qt_in_fee   [会员前台申请入款费用]
    */
    public function get_qt_in_fee($agentsuser,$date,$where,$field,$type,$field2,$uid){
        $mapSp = array();
        $db_model['tab'] = "k_user_bank_in_record";
        $db_model['type'] = 1;
        $mapSp['site_id'] = $_SESSION['site_id'];
        $mapSp['agent_user'] = array('in','('.$agentsuser.')');
        if($where != ''){
            $mapSp['deposit_num'] = $where;
        }
        if($uid != ''){
            $mapSp['uid'] = $uid;
        }
        $mapSp['into_style'] = $type;
        $mapSp['make_sure'] = 1;
        $mapSp['do_time'] = array(
                              array('>=',$date[0]),
                              array('<=',$date[1])
                             );
       if($field2 == ''){
           return $this->M($db_model)->field($field)->where($mapSp)->select();
       }else{
           return $this->M($db_model)->field($field)->where($mapSp)->group($field2)->select('u-'.$field2);
       }


    }

    /*
    * 根据所有当前期数数据 手续费设定 代理ID 获取会员后台申请入款费用
    * @param  [string] $agentsstr  [代理ID]
    * @param  [array] $date       [起始时间]
    * @param [array] $where         [手续条件]
    * @param [string] $field         [查询字段]
    * @param [string] $field2         [查询字段]
    * @param [int] uid         [用户ID]
    * @return [int] $ht_fee   [会员后台出入款]
    */
    public function get_ht_fee($agentsstr,$date,$where,$field,$field2,$uid){
        $mapSp = array();
        $db_model['tab'] = "k_user_catm";
        $db_model['type'] = 1;
        $mapSp['site_id'] = $_SESSION['site_id'];
        $mapSp['agent_id'] = array('in','('.$agentsstr.')');
        if($where != ''){
            $mapSp['catm_money'] = $where;
        }
        if($uid != ''){
            $mapSp['uid'] = $uid;
        }
        $mapSp['catm_type'] = array('in','(1,2,3,4,5,6,7,8)');
        $mapSp['is_rebate'] = 1;
        $mapSp['updatetime'] = array(
                              array('>=',$date[0]),
                              array('<=',$date[1])
                             );
       if($field2 == ''){
           return $this->M($db_model)->field($field)->where($mapSp)->select();
       }else{
           return $this->M($db_model)->field($field)->where($mapSp)->group($field2)->select('u-'.$field2);
       }
    }

    /*
    * 根据当期和前期所有数据计算可获退佣
    * @param  [array] $agents       [起始时间]
    * @return [int] $retucash   [可获退佣]
    */
    public function getagentProfit($agents){
        if($agents['hire_id'] == 0){
            return 0;
        }else{
            //可获退佣计算


            $cbet_you = $agents['nowvalidcbet']+$agents['oldvalidcbet']; //彩票有效投注
            $kbet_pai = $agents['oldkbet']+$agents['nowkbet']+ $agents['oldckbet']+$agents['nowckbet']; //体育总盈利
            $ebet_pai = $agents['oldebet']+$agents['nowebet']; //电子总盈利
            $vbet_pai = $agents['oldvbet']+$agents['nowvbet']; //视讯总盈利
            $sxfcash = $agents['feemoney'] + $agents['oldcash']; //当前手续费
            $shengyuqian = $cbet_you * $agents['lottery_water_rate'] * 0.01 - $sxfcash; //剩余待扣金额


            //视讯
            if ($vbet_pai < 0) {
                    $v_huo = $vbet_pai * $agents['video_slay_rate'] * 0.01;
            } else {
                    $shengyuqian += $vbet_pai;
                    if ($shengyuqian > 0) {
                            $v_huo = $shengyuqian * $agents['video_slay_rate'] * 0.01;
                            $shengyuqian = 0;
                    } else {
                            $v_huo = 0;
                    }
            }

            //电子
            if ($ebet_pai < 0) {
                    $e_huo = $ebet_pai * $agents['evideo_slay_rate'] * 0.01;
            } else {
                    $shengyuqian += $ebet_pai;
                    if ($shengyuqian > 0) {
                            $e_huo = $shengyuqian * $agents['evideo_slay_rate'] * 0.01;
                            $shengyuqian = 0;
                    } else {
                            $e_huo = 0;
                    }
            }

            //体育
            if ($kbet_pai < 0) {
                    $k_huo = $kbet_pai * $agents['sport_slay_rate'] * 0.01;
                    if ($shengyuqian < 0) {
                            $k_huo += $shengyuqian;
                    }
            } else {
                    $shengyuqian += $kbet_pai;
                    if ($shengyuqian > 0) {

                            $k_huo = $shengyuqian * $agents['sport_slay_rate'] * 0.01;

                    } else {
                            $k_huo += $shengyuqian;
                    }
            }

            return $v_huo + $e_huo + $k_huo;
        }
    }

    //银行卡种类
    public function bank_type(){
        return array(
            '1'=>'中國銀行','2'=>'中國工商銀行','3'=>'中國建設銀行',
            '4'=>'中國招商銀行','5'=>'中國民生銀行','7'=>'中國交通銀行',
            '8'=>'中國郵政銀行','9'=>'中國农业銀行','10'=>'華夏銀行',
            '11'=>'浦發銀行','12'=>'廣州銀行','13'=>'北京銀行',
            '14'=>'平安銀行','15'=>'杭州銀行','16'=>'溫州銀行',
            '17'=>'中國光大銀行','18'=>'中信銀行','19'=>'浙商銀行',
            '20'=>'漢口銀行','21'=>'上海銀行','22'=>'廣發銀行',
            '23'=>'农村信用社','24'=>'深圳发展银行','25'=>'渤海银行',
            '26'=>'东莞银行','27'=>'宁波银行','28'=>'东亚银行',
            '29'=>'晋商银行','30'=>'南京银行','31'=>'广州农商银行',
            '32'=>'上海农商银行','33'=>'珠海农村信用合作联社',
            '34'=>'顺德农商银行','35'=>'尧都区农村信用联社',
            '36'=>'浙江稠州商业银行','37'=>'北京农商银行',
            '38'=>'重庆银行','39'=>'广西农村信用社','40'=>'江苏银行',
            '41'=>'吉林银行','42'=>'成都银行','50'=>'兴业银行',
            '100'=>'支付宝','101'=>'微信支付','102'=>'财付通'
        );
    }


}
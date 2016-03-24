<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class User_bet_model extends MY_Model {
    //单个会员的 有效打码
	function __construct() {
		parent::__construct();
	}

	//会员所有打码
   	public  function user_bet_all($uid,$username,$date=array(),$type){
   		$bet = array();
   	    $bet['fc'] = $this->user_fc_bet($uid,$date);//彩票
   	 	$bet['sp'] = $this->user_sp_bet($uid,$date);//体育
   	  	$bet['video'] = $this->user_video_bet($username,$date,'ag,og,mg,ct.lebo,bbin');//视讯

   	  	return $bet;
    }

        //会员彩票所有打码
   	public  function user_fc_bet($uid,$date=array()){
    	$mapFc = array();
    	$mapFc['uid'] = $uid;
        $mapFc['status'] = array('in','(1,2)');
        if (!empty($date)) {
	        $mapFc['addtime'] = array(
	                        array('>=',$date[0].' 00:00:00'),
	                        array('<=',$date[1].' 23:59:59')
	                        );
        }

        $db_model = array();
        $db_model['tab'] = 'c_bet';
		$db_model['type'] = 1;
        return $this->M($db_model)->field("sum(money) as bet,count(id) as num,count(distinct uid) as userNum")->where($mapFc)->find();
    }



	 //会员体育下注
   	public  function user_sp_bet($uid,$date=array()){
        $spBet = array();
    	$mapSp = array();
    	$mapSp['uid'] = $uid;
        $mapSp['status'] = array('in','(1,2,4,5)');
        if (!empty($date)) {
	        $mapSp['bet_time'] = array(
	                        array('>=',$date[0].' 00:00:00'),
	                        array('<=',$date[1].' 23:59:59')
	                        );
        }
        $db_model = array();
        $db_model['tab'] = 'k_bet';
		$db_model['type'] = 1;
        $spBet = $this->M($db_model)->field("sum(bet_money) as bet,count(bid) as num,count(distinct uid) as userNum")->where($mapSp)->find();
        //体育串关
        unset($mapSp['status']);
        $mapSp['status'] = array('in','(1,2)');
        $db_model['tab'] = 'k_bet_cg_group';
        $spc_bet = $this->M($db_model)->field("sum(bet_money) as bet,count(gid) as num,count(distinct uid) as userNum")->where($mapSp)->find();
        $spBet['bet'] += $spc_bet['bet'];
        $spBet['num'] += $spc_bet['num'];
        $spBet['userNum'] += $spc_bet['userNum'];
    	return $spBet;
    }

    //视讯打码 注意时间转换 OG中国时间 CT柬埔寨时间
   	public  function user_video_bet($username,$date=array(),$type){
        $map = array();
        $map['pkusername'] = $username;
        $map['site_id'] = $_SESSION['site_id'];
	    $ARR = array();
	    $db_model = array();
	    $db_model['type'] = 3;
	       //表后缀
       // $tab_data = '_'.date('Ym',strtotime($date[0]));
        $tab_data = '';
        //AG视讯
	    if (strstr($type, 'ag')) {
	    	$map['bet_time'] = array(
	                            array('>=',$date[0]),
	                            array('<=',$date[1])
	                          );
		    $db_model['tab'] = 'ag_bet_record'.$tab_data;
		    $ARR = $this->M($db_model)->field("sum(valid_betamount) as ag_bet")->where($map)->find();
            unset($map['bet_time']);
	    }

	    //BBIN视讯
	    if (strstr($type, 'bbin')) {
	    	$map['wagers_date'] =  array(
	                            array('>=',$date[0]),
	                            array('<=',$date[1])
	                          );
		    $map['payoff'] = array('<>','0.0000');
		    $db_model['tab'] = 'bbin_bet_record'.$tab_data;
		    $bbin_arr= $this->M($db_model)->field("sum(betamount) as bbin_bet")->where($map)->find();

		    $ARR = $this->video_array($ARR,$bbin_arr);
		    unset($map['payoff']);
		    unset($bbin_arr);
		    unset($map['wagers_date']);
	    }

	    //CT视讯 使用柬埔寨时间 +11小时
	    if (strstr($type, 'ct')) {
	    	$ct_s_date = date("Y-m-d H:i:s", strtotime("$date[0] +11 hours"));
	    	$ct_e_date = date("Y-m-d H:i:s", strtotime("$date[1] +11 hours"));
	    	$map['transaction_date_time'] = array(
	                            array('>=',$ct_s_date),
	                            array('<=',$ct_e_date)
	                          );
		    $db_model['tab'] = 'ct_bet_record'.$tab_data;

		    $ct_arr = $this->M($db_model)->field("sum(availablebet) as ct_bet")->where($map)->find();

		    $ARR = $this->video_array($ARR,$ct_arr);
		    unset($ct_arr);
		    unset($map['transaction_date_time']);
	    }

	    //LEBO视讯
	    if (strstr($type, 'lebo')) {
	    	$map['betstart_time'] = array(
	                            array('>=',$date[0]),
	                            array('<=',$date[1])
	                          );
		    $db_model['tab'] = 'lebo_bet_record'.$tab_data;
		    $lebo_arr = $this->M($db_model)->field("sum(valid_betamount) as lebo_bet")->where($map)->find();
		    $ARR = $this->video_array($ARR,$lebo_arr);
		    unset($lebo_arr);
		    unset($map['betstart_time']);
	    }

	    //MG
	    if (strstr($type, 'mg')) {
	    	$map['date'] = array(
	                            array('>=',$date[0]),
	                            array('<=',$date[1])
	                          );
		    $db_model['tab'] = 'mg_bet_record'.$tab_data;

		    $mg_arr = $this->M($db_model)->field("sum(income) as mg_bet")->where($map)->find();
		    $ARR = $this->video_array($ARR,$mg_arr);
		    unset($mg_arr);
		    unset($map['date']);
	    }

	    //OG视讯
	    if (strstr($type, 'og')) {
	    	$og_s_date = date("Y-m-d H:i:s", strtotime("$date[0] +12 hours"));
	    	$og_e_date = date("Y-m-d H:i:s", strtotime("$date[1] +12 hours"));
	    	$map['add_time'] = array(
	                            array('>=',$og_s_date),
	                            array('<=',$og_e_date)
	                          );
		    $db_model['tab'] = 'og_bet_record'.$tab_data;
		    $og_arr =$this->M($db_model)->field("sum(valid_amount) as og_bet")->where($map)->find();

		    $ARR = $this->video_array($ARR,$og_arr);
		    unset($og_arr);
		    unset($map['add_time']);
	    }
	    return $ARR;
   	}

   	//数组
   	public function video_array($arr = array(),$bet = array()){
   	    if (!empty($arr) && !empty($bet)) {
	        $arr = array_merge_recursive($arr,$bet);
	    }elseif (empty($arr) && !empty($bet)) {
	        $arr = $bet;
	    }
	    return $arr;
   	}

}
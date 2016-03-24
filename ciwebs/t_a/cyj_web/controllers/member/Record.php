<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Record extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->model('member/Member_record_model');
		$this->Member_record_model->login_check($_SESSION['uid']);

	}

	//会员专区，交易记录，体育
	public function tc_record(){
		$this->load->model('Common_model');
		$copyright = $this->Common_model->get_copyright();
  		$video_config = explode(',',$copyright['video_module']);
  		foreach ($video_config as $key => $value) {
  			$video_config[$key] = strtoupper($value);
  		}
		$uid=$_SESSION['uid'];
		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');
		$order = $this->input->get('order');
		$gtype = $this->input->get('gtype');
		$gtype = empty($gtype)?1:$gtype;

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
		//订单号查询
		if(empty($order)){
			$map['where'] .= " and bet_time > '".$s_date." 00:00:00' and bet_time < '".$e_date." 23:59:59'";

			$map['order'] =" order by bet_time desc";
		}else{
			if(preg_match("/^[\W]*$/i",$order)){
				echo '<script>alert("您输入的订单号非法")</script>';
			}else{
				$map['where'] .= " and number = '".$order."'";
				$this->add("order", $order);
			}
		}

		if($gtype == 1){
			$count = $this->Member_record_model->get_record_b_count($sql_union,$map);
		}else if($gtype == 2){
			foreach ($sql_union as $key => $value) {
				$count = $this->Member_record_model->get_record_b_count($value,$map);
			}
		}
		//分页
		$perNumber=isset($_GET['page_num'])?$_GET['page_num']:10; //每页显示的记录数
		$totalPage=ceil($count/$perNumber); //计算出总页数
		$page=isset($_GET['page'])?$_GET['page']:1;
		if($totalPage<$page){
			$page = 1;
		}
		$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
		$map['limit']=$startCount;
		$map['limit2'] =$perNumber;

		$getpage = $this->input->get('page');
		if($getpage){
			$map['limit']=($getpage-1)*10;
			$this->add("dqpage", $getpage);
		}else{
			$this->add("dqpage", 1);
		}

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


		$this->add("gtype", $gtype);
		$this->add("totalPage",$totalPage);
		$this->add("data", $array);
		$this->add('video_config',$video_config);
		$this->display('member/tc_record.html');
	}

	//会员专区，交易记录，彩票
	public function lottery_today(){
		$this->load->model('Common_model');
		$copyright = $this->Common_model->get_copyright();
  		$video_config = explode(',',$copyright['video_module']);
  		foreach ($video_config as $key => $value) {
  			$video_config[$key] = strtoupper($value);
  		}
		$uid=$_SESSION['uid'];
		$arrry['c_bet.uid'] = $uid;

		$start_date = $this->input->get('start_date');
		$s_date = empty($start_date)?date('Y-m-d'):$start_date;
		$end_date = $this->input->get('end_date');
		$e_date = empty($end_date)?date('Y-m-d'):$end_date;

		$order = $this->input->get('order');
		$gtype = $this->input->get('gtype');

		$fctype = array('1'=>'重庆时时彩','2'=>'重庆快乐十分',
		'3'=>'广东快乐十分','4'=>'北京赛车PK拾','5'=>'福彩3D',
		'6'=>'排列三','7'=>'北京快乐8','8'=>'六合彩','9'=>'江苏快3',
		'10'=>'吉林快3','11'=>'新疆时时彩','12'=>'天津时时彩',
		'13'=>'江西时时彩');
        //彩票种类判断
		if (!empty($gtype)) {
		     $arrry['c_bet.type'] = $fctype[$gtype];
		}

        //时间
		$arrry['c_bet.addtime >'] = $s_date." 00:00:00";
		$arrry['c_bet.addtime <'] = $e_date." 23:59:59";

		//订单号查询
		if(!empty($order)){
		    if(preg_match("/^[\W]*$/i",$_GET['order'])){
			    echo '<script>alert("您输入的订单号非法")</script>';
			    exit();
			}else{
				$arrry['c_bet.did'] = $order;
			}
		}

		$count = $this->Member_record_model->get_record_cp_count($arrry);
		//分页
		$perNumber=isset($_GET['page_num'])?$_GET['page_num']:10; //每页显示的记录数
		$totalPage=ceil($count/$perNumber); //计算出总页数
		$page=isset($_GET['page'])?$_GET['page']:1;
		if($totalPage<$page){
			$page = 1;
		}
		$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
		$map['limit']=$startCount;
		$map['limit2'] =$perNumber;

		$getpage = $this->input->get('page');
		if($getpage){
			$map['limit']=($getpage-1)*10;
		}else{
		}
		$map['order'] = 'c_bet.addtime desc';

		$data = $this->Member_record_model->get_record_cp($arrry,$map);

		$this->add("totalPage",$totalPage);
		$this->add("s_date", $s_date);
		$this->add("e_date", $e_date);
		$this->add("data", $data);
		$this->add('video_config',$video_config);
		$this->display('member/lottery_today.html');
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
		$array['VideoType'] = $this->input->get('VideoType');
		$array['gametype'] = $this->input->get('gametype');
		$this->add('Company',$array['Company']);
		$this->add('VideoType',$array['VideoType']);
		$this->add('gametype',$array['gametype']);
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




	//会员专区、交易记录、往来记录
	public function correspondence(){
		$uid=$_SESSION['uid'];
		//接受get数据
		$s_date = $this->input->get('start_date');
		$e_date = $this->input->get('end_date');
		$username = $this->input->get('username');
		$deptype = $this->input->get('deptype');
		$page = $this->input->get('page');

		//时间判断
		if (!empty($s_date) && !empty($e_date)) {
		   $map['where'] = "k_user_cash_record.cash_date > '".$s_date." 00:00:00' and k_user_cash_record.cash_date < '".$e_date." 23:59:59' ";
		   $con['where'] = "cash_date > '".$s_date." 00:00:00' and cash_date < '".$e_date." 23:59:59' ";
		   $this->add('s_date',$s_date);
		   $this->add('e_date',$e_date);
		}elseif (!empty($s_date)) {
		   $map['where'] = "k_user_cash_record.cash_date > '".$s_date." 00:00:00' ";
		   $con['where'] = "cash_date > '".$s_date." 00:00:00' ";
		   $this->add('s_date',date('Y-m-d'));
		}elseif (!empty($e_date)) {
		   $map['where'] = "k_user_cash_record.cash_date < '".$e_date." 23:59:59' ";
		   $con['where'] = "cash_date < '".$e_date." 23:59:59' ";
		   $this->add('e_date',date('Y-m-d'));
		}else{
		   $map['where'] = "k_user_cash_record.cash_date like '".date('Y-m-d')."%' ";
		   $con['where'] = "cash_date like '".date('Y-m-d')."%' ";
		   $s_date = $e_date = date('Y-m-d');
		   $this->add('s_date',date('Y-m-d'));
		   $this->add('e_date',date('Y-m-d'));
		}
		$map['where'] .= "and k_user.site_id = '".SITEID."' ";
		//账户查询
		if(!empty($username)) $map['where'] .= "and k_user.username = '".$username."'";
		//方式
		if (!empty($deptype)) {
		  $type;
		  $type = $deptype;
		  $arrType = explode('-', $deptype);
		  if (count($arrType) > 1) {
		     //表示检索参数cash_do_type
		     $map['where'] .= " and ((k_user_cash_record.cash_do_type = '".$arrType[0]."' and k_user_cash_record.cash_type = '".$arrType[1]."' ) or k_user_cash_record.cash_do_type = '".$arrType[2]."') ";
		     $con['where'] .= " and ((cash_do_type = '".$arrType[0]."' and cash_type = '".$arrType[1]."' ) or cash_do_type = '".$arrType[2]."') ";
		  }else{
		     if($type == 1 || $type == 2 || $type == 4 || $type == 3 || $type == 14 || $type == 15 || $type==19 || $type==7 ||$type==23){
		      $map['where'] .= " and k_user_cash_record.cash_type = '".$type."'";
		      $con['where'] .= " and cash_type = '".$type."'";
		    }elseif($type == 'in'){
		      //入款明细
		      $map['where'] .= " and (k_user_cash_record.cash_do_type = '3' or k_user_cash_record.cash_type in (10,11)) ";
		      $con['where'] .= " and (cash_do_type = '3' or cash_type in (10,11)) ";
		    }elseif($type == 'out'){
		      //出款明细
		      $map['where'] .= " and ((k_user_cash_record.cash_do_type = '2' and k_user_cash_record.cash_type = '12') or k_user_cash_record.cash_type in (7,8,19)) ";
		      $con['where'] .= " and ((cash_do_type = '2' and cash_type = '12') or cash_type in (7,8,19)) ";
		    }else{
		      $map['where'] .= " and k_user_cash_record.cash_type = '".$type."' ";
		      $con['where'] .= " and cash_type = '".$type."' ";
		    }

		  }
		}
		//账户
		$map['where'] .= "and k_user.uid = '".$_SESSION['uid']."'";
		$con['where'] .= "and uid = '".$_SESSION['uid']."' ";
		//获得记录总数
		$count = $this->Member_record_model->get_correspondence_count($map);
		//分页
		$perNumber=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
		$totalPage=ceil($count/$perNumber); //计算出总页数
		$page=isset($page)?$page:1;
		$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
		$map['limit'] = $startCount;
		$map['limit2'] = $perNumber;
		//print_r($map);exit;
		$data = $this->Member_record_model->get_correspondence_record($map);
		//小计
		foreach ($data as $k=>$val){
			$counts += $val['cash_num']+$val['discount_num'];
		}

		//总计
		$totl = $this->Member_record_model->get_correspondence_totl($con);
		if(!empty($totl)) $all_count=number_format($totl['0']['cash_num']+$totl['0']['discount_num'],2);
		//print_r($totl);exit;
		$this->add('deptype',$deptype);
		$this->add('page',$page);
		$this->add("all_count", $all_count);
		$this->add("data", $data);
		$this->add('counts', $counts? $counts: 0);
		$this->add('count', $count);
		$this->add('num', count($data)?count($data):0);
		$this->add("totalPage", $totalPage);
		$this->display('member/correspondence.html');
	}

	//会员专区、报表统计
	public function bb_count(){
		$this->load->model('Common_model');
		$copyright = $this->Common_model->get_copyright();
  		$video_config = explode(',',$copyright['video_module']);
  		foreach ($video_config as $key => $value) {
  			$video_config[$value]['name'] = strtoupper($value).'视讯';
  			unset($video_config[$key]);
  			if(strtoupper($value) == 'MG'){
  				$video_config['mgdz']['name'] = strtoupper($value).'电子';
  			}
  		}
  		asort($video_config);//数组排序
  		if($_SESSION['shiwan'] == 1){
  			$video_config = array();
  		}
  		$this->add('video_config',$video_config);
		$this->display('member/bb_count.html');
	}

	public function bb_count_do(){
		$this->load->library('Games');
		$loginname = $_SESSION['username'];
		$uid = $_SESSION['uid'];
		$action = $this->input->post('action');

		if($action == "yesterday"){
			$starttime = date("Y-m-d",strtotime("-1 day"))." 00:00:00";
			$endtime   = date("Y-m-d",strtotime("-1 day"))." 23:59:59";
		}elseif($action == "theweek"){
			$starttime=date("Y-m-d",strtotime("last Monday"))." 00:00:00";
			$endtime=date("Y-m-d")." 23:59:59";
		}elseif($action == 'lastweek'){
			$starttime=date("Y-m-d",strtotime("last Monday")-604800)." 00:00:00";
			$endtime=date("Y-m-d",strtotime("last Monday")-86400)." 23:59:59";
		}else{
			$starttime = date("Y-m-d")." 00:00:00";
			$endtime   = date("Y-m-d")." 23:59:59";
		}

		$data = array();

		$map['table'] = "c_bet";
		$map['where']['site_id'] = SITEID;
		$map['where']['uid'] = $uid;
		$map['where']['addtime >'] = $starttime;
		$map['where']['addtime <'] = $endtime;
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
		$con['where']['bet_time >'] = $starttime;
		$con['where']['bet_time <'] = $endtime;
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
		$cpdata['name'] = '彩票';
		$cpdata['times'] = $cpc;
		$cpdata['count'] = 0+$cp['money'];
		$cpdata['valid_money'] = 0+$valid_cp['money'];
		$cpdata['valid_win'] = $valid_cp['win'] - $valid_cp['money'];

		$tydata = array();
		$tydata['name'] = '体育';
		$tydata['times'] = $tyc;
		$tydata['count'] = $ty['bet_money'];
		$tydata['valid_money'] = $valid_ty['bet_money'];
		$tydata['valid_win'] = $valid_ty['win'];


		$this->load->model('Common_model');
		$copyright = $this->Common_model->get_copyright();
  		$video_config = explode(',',$copyright['video_module']);
  		$data[] = $cpdata;
  		$data[] = $tydata;
  		if($_SESSION['shiwan'] == 0){
	  		$games = new Games();
	  		foreach ($video_config as $key => $value) {
	  			if($value == 'mg'){
	  				$video_data['mg'] = json_decode($games->GetAvailableAmountByUser('mg', $loginname, $starttime, $endtime));
	  				$video_data['mgdz'] = json_decode($games->GetAvailableAmountByUser('mg', $loginname, $starttime, $endtime,1));
	  			}else{
	  				$video_data[$value] = json_decode($games->GetAvailableAmountByUser($value, $loginname, $starttime, $endtime));
	  			}
	  		}

	  		foreach ($video_data as $k => $v) {
	  			if($k == 'mgdz'){
	  				$info[$k]['name'] = 'MG电子';
	  			}elseif($k == 'pt'){
	  				$info[$k]['name'] = 'PT电子';
	  			}elseif($k == 'eg'){
	  				$info[$k]['name'] = 'EG电子';
	  			}else{
	  				$info[$k]['name'] = strtoupper($k).'视讯';
	  			}

	  			$info[$k]['times'] = !empty($v->data->BetBS) ? $v->data->BetBS : 0;
	  			$info[$k]['count'] = !empty($v->data->BetAll) ? $v->data->BetAll : 0;
	  			$info[$k]['valid_money'] = !empty($v->data->BetYC) ? $v->data->BetYC : 0;
	  			$info[$k]['valid_win'] = !empty($v->data->BetPC) ? $v->data->BetPC : 0;
	  			$data[] = $info[$k];
	  		}
  		}

		echo json_encode($data);exit;
	}
}
?>
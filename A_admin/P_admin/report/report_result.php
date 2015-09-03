<?php
set_time_limit(0);
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once("../../class/Level.class.php");
include_once("../../lib/video/Games.class.php");
$is_res = $_GET['is_res'];//是否有结果
$date_start = $_GET["date_start"]." 00:00:00";
$date_end = $_GET["date_end"]." 23:59:59";

//层级数组
$level_a = array(
    '0'=>array('name'=> '會員'),
    '1'=>array('name'=> '代理商'),
    '2'=>array('name'=> '代理商所得'),
);

if ($_GET['atype'] == 's_h') {
   //股东报表
   $atype = 'u_a';
   $table_title = '股東';
   $u_table_title = '公司';
   $level_a[3]['name'] = '總代理';
   $level_a[4]['name'] = '股東';
   $level_a[5]['name'] = '公司';
}elseif ($_GET['atype'] == 'u_a') {
   //总代报表
   $a_map = " and agent_type <> 's_h'";
   $atype = 'a_t';
   $table_title = '總代理';
   $u_table_title = '股東';
   $level_a[3]['name'] = '總代理';
   $level_a[4]['name'] = '股東';
}elseif ($_GET['atype'] == 'a_t') {
	//echo 2;exit;
   //代理商报表
   $atype = 'user';

   $a_map = " and agent_type <> 'u_a'";
   $table_title = '代理';
   $u_table_title = '總代理';
   $level_a[3]['name'] = '總代理';
}elseif($_GET['atype'] == 'user'){
	//echo 1;exit;
	$table_title = '会员';
	$u_table_title = '代理';
}


if(!empty($_GET['uid'])){
	$uname = M('k_user_agent',$db_config)
			->where("id='".$_GET['uid']."' and site_id='".SITEID."'")
			->find();
	
}
if ($_GET['atype'] == 's_h') {
	$r_title = COMPANY_NAME.'公司';
}elseif ($_GET['atype'] == 'u_a') {
	$r_title =$uname['agent_user'].'股東';
}elseif ($_GET['atype'] == 'a_t') {
	$r_title =$uname['agent_user'].'總代';
}elseif ($_GET['atype'] == 'user'){
	$r_title =$uname['agent_user'].'代理';
}

$u = M('k_user',$db_config);
//获取每个股东旗下所有会员

if($_GET['atype']!='user'){
	$agent = M('k_user_agent',$db_config)
		->field('id,agent_user,agent_name,video_scale,sports_scale,lottery_scale,agent_type,pid')
		->where("is_demo = '0' and site_id = '".SITEID."'".$a_map)
		->select();

	if (!empty($agent)) {
		foreach ($agent as $key => $val) {
			//旗下股东 总代 代理读取
			if ($val['agent_type'] == $_GET['atype']) {
				if(!empty($_GET['uid'])){
					if($val['pid']==$_GET['uid']){
						$agent_sh[$val['id']] = $val;
					}
				}else{
					$agent_sh[$val['id']] = $val;
				}
			}
		}
	}
}else{
	$agent_sh=$u
			->field("uid,username,agent_id")
			->where("shiwan = '0'  and site_id = '".SITEID."' and agent_id='".$_GET['uid']."'")
			->select();
}
//股东层
if($_GET['atype'] == 's_h'){
	foreach ($agent_sh as $k => $v) {
		foreach ($agent as $key => $value) {
			if($k == $value['pid']){
				$agent_sh[$value['pid']]['zdv'][$value['id']]=$value;
			}
		}
	}
	foreach ($agent_sh as $k => $v) {
		foreach ($v['zdv'] as $key => $value) {
			foreach ($agent as $ke => $va) {
				if($value['id'] == $va['pid']){
					$agent_sh[$v['id']]['dlv'][$va['id']]=$va;
				}
			}
		}
	}
//总代层
}else if($_GET['atype'] == 'u_a'){
	foreach ($agent_sh as $k => $v) {
		$s_h = M('k_user_agent',$db_config)
			->field('id,agent_user,agent_name,video_scale,sports_scale,lottery_scale,agent_type,pid')
			->where("id=$v[pid]")
			->find();
		$agent_sh[$k]['gdv']=$s_h;
		foreach ($agent as $ke => $va) {
			if($v['id'] == $va['pid']){
				$agent_sh[$v['id']]['dlv'][$va['id']]=$va;
			}
		}
		
	}
//代理层
}elseif ($_GET['atype'] == 'a_t') {
	foreach ($agent_sh as $k => $v) {
		$u_a = M('k_user_agent',$db_config)
			->field('id,agent_user,agent_name,video_scale,sports_scale,lottery_scale,agent_type,pid')
			->where("id=$v[pid]")
			->find();
		$agent_sh[$k]['zdv']=$u_a;
		$s_h = M('k_user_agent',$db_config)
			->field('id,agent_user,agent_name,video_scale,sports_scale,lottery_scale,agent_type')
			->where("id=$u_a[pid]")
			->find();
		$agent_sh[$k]['gdv']=$s_h;
	}
}elseif ($_GET['atype'] == 'user') {
	
	foreach ($agent_sh as $k => $v) {
		$a_t = M('k_user_agent',$db_config)
			->field('id,agent_user,agent_name,video_scale,sports_scale,lottery_scale,agent_type,pid')
			->where("id=$v[agent_id]")
			->find();
			$agent_sh[$k]['dlv']=$a_t;
	}
}

if($_GET['atype']!='user'){
//报表统计
	foreach ($agent_sh as $k_a => $v_a) 
	{
		//彩票
		if ($_GET['cp'] == '2') 
		{	
			//股东层
			if($_GET['atype'] == 's_h')
			{
				foreach ($v_a['dlv'] as $key => $value) 
				{	
					//条件初始化
					$data_a = $data_b = array();
					//彩票总笔数和总投注
					$data_a['agent_id'] = $key;
					$data_a['addtime']  = array(array('>=',$date_start),array('<=',$date_end));
					if($_GET['is_res'] == 1){
						$data_a['js'] = 1;
						$data_a['status']   = array('!=',"0");
					}
					$cp_bett = M('c_bet',$db_config)
							->field("count(id) as cp_count,sum(money) as c_bet_money")
							->where($data_a)
							->find();
					
					$agent_sh[$k_a]['fc']['cp_count']     += $cp_bett['cp_count'];
					$agent_sh[$k_a]['fc']['c_bet_money']  += $cp_bett['c_bet_money'];
					
					
					
					//彩票总有效金额
					$data_b['agent_id'] = $key;
					$data_b['status']   = array('!=',"0");
					$data_b['addtime']  = array(array('>=',$date_start),array('<=',$date_end));
				 	$cp_bet = M('c_bet',$db_config)
						 	->field("sum(money) as c_bet_money_YX,sum(win) as payout_fc")
						 	->where($data_b)
						 	->find();

				 	$agent_sh[$k_a]['fc']['c_bet_money_YX'] += $cp_bet['c_bet_money_YX'];
					$agent_sh[$k_a]['fc']['payout_fc']      += $cp_bet['payout_fc'];

					//代理交收
					$agent_sh[$k_a]['fc']['s_hdljs'] += (1-$value['lottery_scale']) * ($cp_bet['c_bet_money_YX'] - $cp_bet['payout_fc']);
					//代理所得
					$agent_sh[$k_a]['fc']['s_hdljg'] += $value['lottery_scale'] * ($cp_bet['c_bet_money_YX'] - $cp_bet['payout_fc']);
					
					//总代交收
				 	$agent_sh[$k_a]['fc']['s_hzdjs'] += (1 - $v_a['zdv'][$value['pid']]['lottery_scale']) * ($cp_bet['c_bet_money_YX'] - $cp_bet['payout_fc']);
					
				 	//股东交收
				 	$agent_sh[$k_a]['fc']['s_hgdjs'] += (1-$v_a['lottery_scale']) * ($cp_bet['c_bet_money_YX'] - $cp_bet['payout_fc']);

				 	//公司交收
					$agent_sh[$k_a]['fc']['s_hgsjs'] += $v_a['lottery_scale'] * ($cp_bet['c_bet_money_YX'] - $cp_bet['payout_fc']);
				}
			//总代层
			}
			else if($_GET['atype'] == 'u_a')
			{
				foreach ($v_a['dlv'] as $key => $value) 
				{
					//条件初始化
					$data_a = $data_b = array();
					//彩票总笔数和总投注
					$data_a['agent_id'] = $key;
					$data_a['addtime']  = array(array('>=',$date_start),array('<=',$date_end));
					if($_GET['is_res'] == 1){
						$data_a['js'] = 1;
						$data_a['status']   = array('!=',"0");
					}
					$cp_bett = M('c_bet',$db_config)
							->field("count(id) as cp_count,sum(money) as c_bet_money")
							->where($data_a)
							->find();
					
					$agent_sh[$k_a]['fc']['cp_count']     += $cp_bett['cp_count'];
					$agent_sh[$k_a]['fc']['c_bet_money']  += $cp_bett['c_bet_money'];
					
					//彩票总有效金额
					$data_b['agent_id'] = $key;
					$data_b['status']   = array('!=',"0");
					$data_b['addtime']  = array(array('>=',$date_start),array('<=',$date_end));
				 	$cp_bet = M('c_bet',$db_config)
						 	->field("sum(money) as c_bet_money_YX,sum(win) as payout_fc")
						 	->where($data_b)
						 	->find();

				 	$agent_sh[$k_a]['fc']['c_bet_money_YX'] += $cp_bet['c_bet_money_YX'];
					$agent_sh[$k_a]['fc']['payout_fc']      += $cp_bet['payout_fc'];

					//代理交收
					$agent_sh[$k_a]['fc']['u_adljs'] += (1-$value['lottery_scale']) * ($cp_bet['c_bet_money_YX'] - $cp_bet['payout_fc']);
					//代理所得
					$agent_sh[$k_a]['fc']['u_adljg'] += $value['lottery_scale'] * ($cp_bet['c_bet_money_YX'] - $cp_bet['payout_fc']);
					
					//总代交收
				 	$agent_sh[$k_a]['fc']['u_azdjs'] += (1 - $v_a['lottery_scale']) * ($cp_bet['c_bet_money_YX'] - $cp_bet['payout_fc']);
					
				 	//股东交收
				 	$agent_sh[$k_a]['fc']['u_agdjs'] += (1-$v_a['gdv']['lottery_scale']) * ($cp_bet['c_bet_money_YX'] - $cp_bet['payout_fc']);

				}
			}
			//代理层
			else if($_GET['atype'] == 'a_t')
			{
				//条件初始化
					$data_a = $data_b = array();
					//彩票总笔数和总投注
					$data_a['agent_id'] = $k_a;
					$data_a['addtime']  = array(array('>=',$date_start),array('<=',$date_end));
					if($_GET['is_res'] == 1){
						$data_a['js'] = 1;
						$data_a['status']   = array('!=',"0");
					}
					$cp_bett = M('c_bet',$db_config)
							->field("count(id) as cp_count,sum(money) as c_bet_money")
							->where($data_a)
							->find();
					
					$agent_sh[$k_a]['fc']['cp_count']     += $cp_bett['cp_count'];
					$agent_sh[$k_a]['fc']['c_bet_money']  += $cp_bett['c_bet_money'];
					
					//彩票总有效金额
					$data_b['agent_id'] = $k_a;
					$data_b['status']   = array('!=',"0");
					$data_b['addtime']  = array(array('>=',$date_start),array('<=',$date_end));
				 	$cp_bet = M('c_bet',$db_config)
						 	->field("sum(money) as c_bet_money_YX,sum(win) as payout_fc")
						 	->where($data_b)
						 	->find();

				 	$agent_sh[$k_a]['fc']['c_bet_money_YX'] += $cp_bet['c_bet_money_YX'];
					$agent_sh[$k_a]['fc']['payout_fc']      += $cp_bet['payout_fc'];

					//代理交收
					$agent_sh[$k_a]['fc']['a_tdljs'] += (1-$v_a['lottery_scale']) * ($cp_bet['c_bet_money_YX'] - $cp_bet['payout_fc']);
					//代理所得
					$agent_sh[$k_a]['fc']['a_tdljg'] += $v_a['lottery_scale'] * ($cp_bet['c_bet_money_YX'] - $cp_bet['payout_fc']);
					
					//总代交收
				 	$agent_sh[$k_a]['fc']['a_tzdjs'] += (1 - $v_a['zdv']['lottery_scale']) * ($cp_bet['c_bet_money_YX'] - $cp_bet['payout_fc']);
					
				 	//股东交收
				 	$agent_sh[$k_a]['fc']['a_tgdjs'] += (1-$v_a['gdv']['lottery_scale']) * ($cp_bet['c_bet_money_YX'] - $cp_bet['payout_fc']);
					
			}
		}

		//体育
		if ($_GET['sp'] == '1') 
		{
			//股东层
			if($_GET['atype'] == 's_h')
			{
				foreach ($v_a['dlv'] as $key => $value) 
				{	
					//条件初始化
					$data_a = $data_b = $data_c = $data_d = array();
					//体育总笔数和总投注
					$data_a['agent_id']  = $key;
					$data_a['bet_time']  = array(array('>=',$date_start),array('<=',$date_end));
					if($_GET['is_res'] == 1){
						$data_a['is_jiesuan'] = 1;
						$data_a['status']    = array('in',"(1,2,4,5)");
					}
					$sp_bett = M('k_bet',$db_config)
							->field("count(bid) as sp_count,sum(bet_money) as sp_bet_money")
							->where($data_a)
							->find();

					$agent_sh[$k_a]['sp']['sp_count']     += $sp_bett['sp_count'];
					$agent_sh[$k_a]['sp']['sp_bet_money'] += $sp_bett['sp_bet_money'];

					//体育总有效金额和总派彩
					$data_b['agent_id']  = $key;
					$data_b['bet_time']  = array(array('>=',$date_start),array('<=',$date_end));
					$data_b['status']    = array('in',"(1,2,4,5)");
					$sp_bet = M('k_bet',$db_config)
							->field("sum(bet_money) as sp_bet_money_YX,sum(win) as payout_sp")
							->where($data_b)
							->find();

					$agent_sh[$k_a]['sp']['sp_bet_money_YX'] += $sp_bet['sp_bet_money_YX'];
					$agent_sh[$k_a]['sp']['payout_sp']       += $sp_bet['payout_sp'];

					//体育串关总笔数和总投注
					$data_c['agent_id'] = $key;
					$data_c['bet_time'] = array(array('>=',$date_start),array('<=',$date_end));
					if($_GET['is_res'] == 1){
						$data_c['is_jiesuan'] = 1;
						$data_c['status']    = array('in',"(1,2)");
					}
			 	  	$spc_bett = M('k_bet_cg_group',$db_config)
					 	  	->field("count(gid) as spc_count,sum(bet_money) as spc_bet_money")
					 	  	->where($data_c)
					 	  	->find();

					$agent_sh[$k_a]['sp']['sp_count']     += $spc_bett['spc_count'];
					$agent_sh[$k_a]['sp']['sp_bet_money'] += $spc_bett['spc_bet_money'];

					//体育串关总有效金额和总派彩
					$data_d['agent_id'] = $key;
					$data_d['bet_time'] = array(array('>=',$date_start),array('<=',$date_end));
					$data_d['status']    = array('in',"(1,2)");
			 	  	$spc_bet = M('k_bet_cg_group',$db_config)
					 	  	->field("sum(bet_money) as spc_bet_money_YX,sum(win) as payout_spc")
					 	  	->where($data_d)
					 	  	->find();

					$agent_sh[$k_a]['sp']['sp_bet_money_YX'] += $spc_bet['spc_bet_money_YX'];
					$agent_sh[$k_a]['sp']['payout_sp']       += $spc_bet['payout_spc'];
					//代理交收
					$agent_sh[$k_a]['sp']['s_hdljs'] += (1-$value['sports_scale']) * (($sp_bet['sp_bet_money_YX'] + $spc_bet['spc_bet_money_YX']) - ($sp_bet['payout_sp'] + $spc_bet['payout_spc']));
					//代理所得
					$agent_sh[$k_a]['sp']['s_hdljg'] += $value['sports_scale'] * (($sp_bet['sp_bet_money_YX'] + $spc_bet['spc_bet_money_YX']) - ($sp_bet['payout_sp'] + $spc_bet['payout_spc']));
					
					//总代交收
				 	$agent_sh[$k_a]['sp']['s_hzdjs'] += (1 - $v_a['zdv'][$value['pid']]['sports_scale']) * (($sp_bet['sp_bet_money_YX'] + $spc_bet['spc_bet_money_YX']) - ($sp_bet['payout_sp'] + $spc_bet['payout_spc']));
					
				 	//股东交收
				 	$agent_sh[$k_a]['sp']['s_hgdjs'] += (1-$v_a['sports_scale']) * (($sp_bet['sp_bet_money_YX'] + $spc_bet['spc_bet_money_YX']) - ($sp_bet['payout_sp'] + $spc_bet['payout_spc']));
				}
			}
			//总代层
			else if($_GET['atype'] == 'u_a')
			{
				foreach ($v_a['dlv'] as $key => $value) 
				{	
					//条件初始化
					$data_a = $data_b = $data_c = $data_d = array();
					//体育总笔数和总投注
					$data_a['agent_id']  = $key;
					$data_a['bet_time']  = array(array('>=',$date_start),array('<=',$date_end));
					if($_GET['is_res'] == 1){
						$data_a['is_jiesuan'] = 1;
						$data_a['status']    = array('in',"(1,2,4,5)");
					}
					$sp_bett = M('k_bet',$db_config)
							->field("count(bid) as sp_count,sum(bet_money) as sp_bet_money")
							->where($data_a)
							->find();

					$agent_sh[$k_a]['sp']['sp_count']     += $sp_bett['sp_count'];
					$agent_sh[$k_a]['sp']['sp_bet_money'] += $sp_bett['sp_bet_money'];

					//体育总有效金额和总派彩
					$data_b['agent_id']  = $key;
					$data_b['bet_time']  = array(array('>=',$date_start),array('<=',$date_end));
					$data_b['status']    = array('in',"(1,2,4,5)");
					$sp_bet = M('k_bet',$db_config)
							->field("sum(bet_money) as sp_bet_money_YX,sum(win) as payout_sp")
							->where($data_b)
							->find();

					$agent_sh[$k_a]['sp']['sp_bet_money_YX'] += $sp_bet['sp_bet_money_YX'];
					$agent_sh[$k_a]['sp']['payout_sp']       += $sp_bet['payout_sp'];

					//体育串关总笔数和总投注
					$data_c['agent_id'] = $key;
					$data_c['bet_time'] = array(array('>=',$date_start),array('<=',$date_end));
					if($_GET['is_res'] == 1){
						$data_c['is_jiesuan'] = 1;
						$data_c['status']    = array('in',"(1,2)");
					}
			 	  	$spc_bett = M('k_bet_cg_group',$db_config)
					 	  	->field("count(gid) as spc_count,sum(bet_money) as spc_bet_money")
					 	  	->where($data_c)
					 	  	->find();

					$agent_sh[$k_a]['sp']['sp_count']     += $spc_bett['spc_count'];
					$agent_sh[$k_a]['sp']['sp_bet_money'] += $spc_bett['spc_bet_money'];

					//体育串关总有效金额和总派彩
					$data_d['agent_id'] = $key;
					$data_d['bet_time'] = array(array('>=',$date_start),array('<=',$date_end));
					$data_d['status']    = array('in',"(1,2)");
			 	  	$spc_bet = M('k_bet_cg_group',$db_config)
					 	  	->field("sum(bet_money) as spc_bet_money_YX,sum(win) as payout_spc")
					 	  	->where($data_d)
					 	  	->find();

					$agent_sh[$k_a]['sp']['sp_bet_money_YX'] += $spc_bet['spc_bet_money_YX'];
					$agent_sh[$k_a]['sp']['payout_sp']       += $spc_bet['payout_spc'];



					//代理交收
					$agent_sh[$k_a]['sp']['u_adljs'] += (1-$value['sports_scale']) * (($sp_bet['sp_bet_money_YX'] + $spc_bet['spc_bet_money_YX']) - ($sp_bet['payout_sp'] + $spc_bet['payout_spc']));
					//代理所得
					$agent_sh[$k_a]['sp']['u_adljg'] += $value['sports_scale'] * (($sp_bet['sp_bet_money_YX'] + $spc_bet['spc_bet_money_YX']) - ($sp_bet['payout_sp'] + $spc_bet['payout_spc']));
					
					//总代交收
				 	$agent_sh[$k_a]['sp']['u_azdjs'] += (1 - $v_a['sports_scale']) * (($sp_bet['sp_bet_money_YX'] + $spc_bet['spc_bet_money_YX']) - ($sp_bet['payout_sp'] + $spc_bet['payout_spc']));
					
				 	//股东交收
				 	$agent_sh[$k_a]['sp']['u_agdjs'] += (1-$v_a['gdv']['sports_scale']) * (($sp_bet['sp_bet_money_YX'] + $spc_bet['spc_bet_money_YX']) - ($sp_bet['payout_sp'] + $spc_bet['payout_spc']));
				}
			}
			//代理层
			else if($_GET['atype'] == 'a_t')
			{
				//条件初始化
					$data_a = $data_b = $data_c = $data_d = array();
					//体育总笔数和总投注
					$data_a['agent_id']  = $k_a;
					$data_a['bet_time']  = array(array('>=',$date_start),array('<=',$date_end));
					if($_GET['is_res'] == 1){
						$data_a['is_jiesuan'] = 1;
						$data_a['status']    = array('in',"(1,2,4,5)");
					}
					$sp_bett = M('k_bet',$db_config)
							->field("count(bid) as sp_count,sum(bet_money) as sp_bet_money")
							->where($data_a)
							->find();

					$agent_sh[$k_a]['sp']['sp_count']     += $sp_bett['sp_count'];
					$agent_sh[$k_a]['sp']['sp_bet_money'] += $sp_bett['sp_bet_money'];

					//体育总有效金额和总派彩
					$data_b['agent_id']  = $k_a;
					$data_b['bet_time']  = array(array('>=',$date_start),array('<=',$date_end));
					$data_b['status']    = array('in',"(1,2,4,5)");
					$sp_bet = M('k_bet',$db_config)
							->field("sum(bet_money) as sp_bet_money_YX,sum(win) as payout_sp")
							->where($data_b)
							->find();

					$agent_sh[$k_a]['sp']['sp_bet_money_YX'] += $sp_bet['sp_bet_money_YX'];
					$agent_sh[$k_a]['sp']['payout_sp']       += $sp_bet['payout_sp'];

					//体育串关总笔数和总投注
					$data_c['agent_id'] = $k_a;
					$data_c['bet_time'] = array(array('>=',$date_start),array('<=',$date_end));
					if($_GET['is_res'] == 1){
						$data_c['is_jiesuan'] = 1;
						$data_c['status']    = array('in',"(1,2)");
					}
			 	  	$spc_bett = M('k_bet_cg_group',$db_config)
					 	  	->field("count(gid) as spc_count,sum(bet_money) as spc_bet_money")
					 	  	->where($data_c)
					 	  	->find();

					$agent_sh[$k_a]['sp']['sp_count']     += $spc_bett['spc_count'];
					$agent_sh[$k_a]['sp']['sp_bet_money'] += $spc_bett['spc_bet_money'];

					//体育串关总有效金额和总派彩
					$data_d['agent_id'] = $k_a;
					$data_d['bet_time'] = array(array('>=',$date_start),array('<=',$date_end));
					$data_d['status']    = array('in',"(1,2)");
			 	  	$spc_bet = M('k_bet_cg_group',$db_config)
					 	  	->field("sum(bet_money) as spc_bet_money_YX,sum(win) as payout_spc")
					 	  	->where($data_d)
					 	  	->find();

					$agent_sh[$k_a]['sp']['sp_bet_money_YX'] += $spc_bet['spc_bet_money_YX'];
					$agent_sh[$k_a]['sp']['payout_sp']       += $spc_bet['payout_spc'];



					//代理交收
					$agent_sh[$k_a]['sp']['a_tdljs'] += (1-$k_a['sports_scale']) * (($sp_bet['sp_bet_money_YX'] + $spc_bet['spc_bet_money_YX']) - ($sp_bet['payout_sp'] + $spc_bet['payout_spc']));
					//代理所得
					$agent_sh[$k_a]['sp']['a_tdljg'] += $k_a['sports_scale'] * (($sp_bet['sp_bet_money_YX'] + $spc_bet['spc_bet_money_YX']) - ($sp_bet['payout_sp'] + $spc_bet['payout_spc']));
					
					//总代交收
				 	$agent_sh[$k_a]['sp']['a_tzdjs'] += (1 - $v_a['zdv']['sports_scale']) * (($sp_bet['sp_bet_money_YX'] + $spc_bet['spc_bet_money_YX']) - ($sp_bet['payout_sp'] + $spc_bet['payout_spc']));
					
				 	//股东交收
				 	$agent_sh[$k_a]['sp']['a_tgdjs'] += (1-$v_a['gdv']['sports_scale']) * (($sp_bet['sp_bet_money_YX'] + $spc_bet['spc_bet_money_YX']) - ($sp_bet['payout_sp'] + $spc_bet['payout_spc']));
			}
			
		}

		//bbin视讯
		if ($_GET['bbsx'] == '9') 
		{
			//股东层
			if($_GET['atype'] == 's_h')
			{	
				$info = '';
				foreach ($v_a['dlv'] as $key => $value) 
				{
					$info .= $key.'|';
				}
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$bbBet=$games->GetAgentAvailableAmountByAgentid('bbin',$info , $date_start , $date_end,'3');
					if($bbBet){
						$bbBet = json_decode($bbBet);
						if($bbBet->data->Code==10023){
							if(!empty($bbBet->data)){
								foreach ($bbBet->data->data as $Bet_k => $Bet_v) 
								{
									foreach ($v_a['dlv'] as $key => $value) 
									{
										if($key == $Bet_v->agentid){
											$agent_sh[$k_a]['bbsx']['num']   += $Bet_v->BetBS;
											$agent_sh[$k_a]['bbsx']['BetYC'] += $Bet_v->BetYC;
											$agent_sh[$k_a]['bbsx']['BetPC'] += $Bet_v->BetPC;
											$agent_sh[$k_a]['bbsx']['BetAll'] += $Bet_v->BetAll;
											//代理交收
											$agent_sh[$k_a]['bbsx']['s_hdljs'] += (1-$value['video_scale']) * (0-$Bet_v->BetPC);
											//代理所得
											$agent_sh[$k_a]['bbsx']['s_hdljg'] += $value['video_scale'] * (0-$Bet_v->BetPC);
												
											//总代交收
											 $agent_sh[$k_a]['bbsx']['s_hzdjs'] += (1 - $v_a['zdv'][$value['pid']]['video_scale']) * (0-$Bet_v->BetPC);
												
											 //股东交收
											 $agent_sh[$k_a]['bbsx']['s_hgdjs'] += (1-$v_a['video_scale']) * (0-$Bet_v->BetPC);
										}
									}
								}
							}
						}
					}
				}
			}
			//总代层
			else if($_GET['atype'] == 'u_a')
			{
				$info = '';
				foreach ($v_a['dlv'] as $key => $value) 
				{
					$info .= $key.'|';
				}
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$bbBet=$games->GetAgentAvailableAmountByAgentid('bbin',$info , $date_start , $date_end,'3');
						
					if($bbBet){
						$bbBet = json_decode($bbBet);
						if($bbBet->data->Code==10023){
							if(!empty($bbBet->data)){
								foreach ($bbBet->data->data as $Bet_k => $Bet_v) 
								{
									foreach ($v_a['dlv'] as $key => $value) 
									{
										if($key == $Bet_v->agentid){
											$agent_sh[$k_a]['bbsx']['num']   += $Bet_v->BetBS;
											$agent_sh[$k_a]['bbsx']['BetYC'] += $Bet_v->BetYC;
											$agent_sh[$k_a]['bbsx']['BetPC'] += $Bet_v->BetPC;
											$agent_sh[$k_a]['bbsx']['BetAll'] += $Bet_v->BetAll;
											//代理交收
											$agent_sh[$k_a]['bbsx']['u_adljs'] += (1-$value['video_scale']) * (0-$Bet_v->BetPC);
											//代理所得
											$agent_sh[$k_a]['bbsx']['u_adljg'] += $value['video_scale'] * (0-$Bet_v->BetPC);
												
											//总代交收
											 $agent_sh[$k_a]['bbsx']['u_azdjs'] += (1 - $v_a['video_scale']) * (0-$Bet_v->BetPC);
												
											 //股东交收
											 $agent_sh[$k_a]['bbsx']['u_agdjs'] += (1-$v_a['gdv']['video_scale']) * (0-$Bet_v->BetPC);
										}
									}
								}
							}
						}
					}
				}
			}
			//代理层
			else if($_GET['atype'] == 'a_t')
			{
				$info = '';
				$info .= $k_a.'|';
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$bbBet=$games->GetAgentAvailableAmountByAgentid('bbin',$info , $date_start , $date_end,'3');
						
					if($bbBet){
						$bbBet = json_decode($bbBet);
						if($bbBet->data->Code==10023){
							if(!empty($bbBet->data)){
								foreach ($bbBet->data->data as $Bet_k => $Bet_v) 
								{									
									if($k_a == $Bet_v->agentid){
										$agent_sh[$k_a]['bbsx']['num']   += $Bet_v->BetBS;
										$agent_sh[$k_a]['bbsx']['BetYC'] += $Bet_v->BetYC;
										$agent_sh[$k_a]['bbsx']['BetPC'] += $Bet_v->BetPC;
										$agent_sh[$k_a]['bbsx']['BetAll'] += $Bet_v->BetAll;
										//代理交收
										$agent_sh[$k_a]['bbsx']['a_tdljs'] += (1-$v_a['video_scale']) * (0-$Bet_v->BetPC);
										//代理所得
										$agent_sh[$k_a]['bbsx']['a_tdljg'] += $v_a['video_scale'] * (0-$Bet_v->BetPC);
											
										//总代交收
										 $agent_sh[$k_a]['bbsx']['a_tzdjs'] += (1 - $v_a['zdv']['video_scale']) * (0-$Bet_v->BetPC);
											
										 //股东交收
										 $agent_sh[$k_a]['bbsx']['a_tgdjs'] += (1-$v_a['gdv']['video_scale']) * (0-$Bet_v->BetPC);
									}
								}
							}
						}
					}
				}
			}
		}

		
		//bbin机率
		if ($_GET['bbdz'] == '12') 
		{
			//股东层
			if($_GET['atype'] == 's_h')
			{	
				$info = '';
				foreach ($v_a['dlv'] as $key => $value) 
				{
					$info .= $key.'|';
				}
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$bbBet=$games->GetAgentAvailableAmountByAgentid('bbin',$info , $date_start , $date_end,'5');
					if($bbBet){
						$bbBet = json_decode($bbBet);
						if($bbBet->data->Code==10023){
							if(!empty($bbBet->data)){
								foreach ($bbBet->data->data as $Bet_k => $Bet_v) 
								{
									foreach ($v_a['dlv'] as $key => $value) 
									{
										if($key == $Bet_v->agentid){
											$agent_sh[$k_a]['bbdz']['num']   += $Bet_v->BetBS;
											$agent_sh[$k_a]['bbdz']['BetYC'] += $Bet_v->BetYC;
											$agent_sh[$k_a]['bbdz']['BetPC'] += $Bet_v->BetPC;
											$agent_sh[$k_a]['bbdz']['BetAll'] += $Bet_v->BetAll;
											//代理交收
											$agent_sh[$k_a]['bbdz']['s_hdljs'] += (1-$value['video_scale']) * (0-$Bet_v->BetPC);
											//代理所得
											$agent_sh[$k_a]['bbdz']['s_hdljg'] += $value['video_scale'] * (0-$Bet_v->BetPC);
												
											//总代交收
											 $agent_sh[$k_a]['bbdz']['s_hzdjs'] += (1 - $v_a['zdv'][$value['pid']]['video_scale']) * (0-$Bet_v->BetPC);
												
											 //股东交收
											 $agent_sh[$k_a]['bbdz']['s_hgdjs'] += (1-$v_a['video_scale']) * (0-$Bet_v->BetPC);
										}
									}
								}
							}
						}
					}
				}
			}
			//总代层
			else if($_GET['atype'] == 'u_a')
			{
				$info = '';
				foreach ($v_a['dlv'] as $key => $value) 
				{
					$info .= $key.'|';
				}
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$bbBet=$games->GetAgentAvailableAmountByAgentid('bbin',$info , $date_start , $date_end,'5');
						
					if($bbBet){
						$bbBet = json_decode($bbBet);
						if($bbBet->data->Code==10023){
							if(!empty($bbBet->data)){
								foreach ($bbBet->data->data as $Bet_k => $Bet_v) 
								{
									foreach ($v_a['dlv'] as $key => $value) 
									{
										if($key == $Bet_v->agentid){
											$agent_sh[$k_a]['bbdz']['num']   += $Bet_v->BetBS;
											$agent_sh[$k_a]['bbdz']['BetYC'] += $Bet_v->BetYC;
											$agent_sh[$k_a]['bbdz']['BetPC'] += $Bet_v->BetPC;
											$agent_sh[$k_a]['bbdz']['BetAll'] += $Bet_v->BetAll;
											//代理交收
											$agent_sh[$k_a]['bbdz']['u_adljs'] += (1-$value['video_scale']) * (0-$Bet_v->BetPC);
											//代理所得
											$agent_sh[$k_a]['bbdz']['u_adljg'] += $value['video_scale'] * (0-$Bet_v->BetPC);
												
											//总代交收
											 $agent_sh[$k_a]['bbdz']['u_azdjs'] += (1 - $v_a['video_scale']) * (0-$Bet_v->BetPC);
												
											 //股东交收
											 $agent_sh[$k_a]['bbdz']['u_agdjs'] += (1-$v_a['gdv']['video_scale']) * (0-$Bet_v->BetPC);
										}
									}
								}
							}
						}
					}
				}
			}
			//代理层
			else if($_GET['atype'] == 'a_t')
			{
				$info = '';
				$info .= $k_a.'|';
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$bbBet=$games->GetAgentAvailableAmountByAgentid('bbin',$info , $date_start , $date_end,'5');
						
					if($bbBet){
						$bbBet = json_decode($bbBet);
						if($bbBet->data->Code==10023){
							if(!empty($bbBet->data)){
								foreach ($bbBet->data->data as $Bet_k => $Bet_v) 
								{				
									if($k_a == $Bet_v->agentid){
										$agent_sh[$k_a]['bbdz']['num']   += $Bet_v->BetBS;
										$agent_sh[$k_a]['bbdz']['BetYC'] += $Bet_v->BetYC;
										$agent_sh[$k_a]['bbdz']['BetPC'] += $Bet_v->BetPC;
										$agent_sh[$k_a]['bbdz']['BetAll'] += $Bet_v->BetAll;
										//代理交收
										$agent_sh[$k_a]['bbdz']['a_tdljs'] += (1-$v_a['video_scale']) * (0-$Bet_v->BetPC);
										//代理所得
										$agent_sh[$k_a]['bbdz']['a_tdljg'] += $v_a['video_scale'] * (0-$Bet_v->BetPC);
											
										//总代交收
										 $agent_sh[$k_a]['bbdz']['a_tzdjs'] += (1 - $v_a['zdv']['video_scale']) * (0-$Bet_v->BetPC);
											
										 //股东交收
										 $agent_sh[$k_a]['bbdz']['a_tgdjs'] += (1-$v_a['gdv']['video_scale']) * (0-$Bet_v->BetPC);
									}
								}
							}
						}
					}
				}
			}
		}


		//BBIN球类
		if ($_GET['bbty'] == '13') 
		{
			//股东层
			if($_GET['atype'] == 's_h')
			{	
				$info = '';
				foreach ($v_a['dlv'] as $key => $value) 
				{
					$info .= $key.'|';
				}
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$bbBet=$games->GetAgentAvailableAmountByAgentid('bbin',$info , $date_start , $date_end,'1');
					if($bbBet){
						$bbBet = json_decode($bbBet);
						if($bbBet->data->Code==10023){
							if(!empty($bbBet->data)){
								foreach ($bbBet->data->data as $Bet_k => $Bet_v) 
								{
									foreach ($v_a['dlv'] as $key => $value) 
									{
										if($key == $Bet_v->agentid){
											$agent_sh[$k_a]['bbty']['num']   += $Bet_v->BetBS;
											$agent_sh[$k_a]['bbty']['BetYC'] += $Bet_v->BetYC;
											$agent_sh[$k_a]['bbty']['BetPC'] += $Bet_v->BetPC;
											$agent_sh[$k_a]['bbty']['BetAll'] += $Bet_v->BetAll;
											//代理交收
											$agent_sh[$k_a]['bbty']['s_hdljs'] += (1-$value['video_scale']) * (0-$Bet_v->BetPC);
											//代理所得
											$agent_sh[$k_a]['bbty']['s_hdljg'] += $value['video_scale'] * (0-$Bet_v->BetPC);
												
											//总代交收
											 $agent_sh[$k_a]['bbty']['s_hzdjs'] += (1 - $v_a['zdv'][$value['pid']]['video_scale']) * (0-$Bet_v->BetPC);
												
											 //股东交收
											 $agent_sh[$k_a]['bbty']['s_hgdjs'] += (1-$v_a['video_scale']) * (0-$Bet_v->BetPC);
										}
									}
								}
							}
						}
					}
				}
			}
			//总代层
			else if($_GET['atype'] == 'u_a')
			{
				$info = '';
				foreach ($v_a['dlv'] as $key => $value) 
				{
					$info .= $key.'|';
				}
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$bbBet=$games->GetAgentAvailableAmountByAgentid('bbin',$info , $date_start , $date_end,'1');
						
					if($bbBet){
						$bbBet = json_decode($bbBet);
						if($bbBet->data->Code==10023){
							if(!empty($bbBet->data)){
								foreach ($bbBet->data->data as $Bet_k => $Bet_v) 
								{
									foreach ($v_a['dlv'] as $key => $value) 
									{
										if($key == $Bet_v->agentid){
											$agent_sh[$k_a]['bbty']['num']   += $Bet_v->BetBS;
											$agent_sh[$k_a]['bbty']['BetYC'] += $Bet_v->BetYC;
											$agent_sh[$k_a]['bbty']['BetPC'] += $Bet_v->BetPC;
											$agent_sh[$k_a]['bbty']['BetAll'] += $Bet_v->BetAll;
											//代理交收
											$agent_sh[$k_a]['bbty']['u_adljs'] += (1-$value['video_scale']) * (0-$Bet_v->BetPC);
											//代理所得
											$agent_sh[$k_a]['bbty']['u_adljg'] += $value['video_scale'] * (0-$Bet_v->BetPC);
												
											//总代交收
											 $agent_sh[$k_a]['bbty']['u_azdjs'] += (1 - $v_a['video_scale']) * (0-$Bet_v->BetPC);
												
											 //股东交收
											 $agent_sh[$k_a]['bbty']['u_agdjs'] += (1-$v_a['gdv']['video_scale']) * (0-$Bet_v->BetPC);
										}
									}
								}
							}
						}
					}
				}
			}
			//代理层
			else if($_GET['atype'] == 'a_t')
			{
				$info = '';
				$info .= $k_a.'|';
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$bbBet=$games->GetAgentAvailableAmountByAgentid('bbin',$info , $date_start , $date_end,'1');
						
					if($bbBet){
						$bbBet = json_decode($bbBet);
						if($bbBet->data->Code==10023){
							if(!empty($bbBet->data)){
								foreach ($bbBet->data->data as $Bet_k => $Bet_v) 
								{
										
									if($k_a == $Bet_v->agentid){
										$agent_sh[$k_a]['bbty']['num']   += $Bet_v->BetBS;
										$agent_sh[$k_a]['bbty']['BetYC'] += $Bet_v->BetYC;
										$agent_sh[$k_a]['bbty']['BetPC'] += $Bet_v->BetPC;
										$agent_sh[$k_a]['bbty']['BetAll'] += $Bet_v->BetAll;
										//代理交收
										$agent_sh[$k_a]['bbty']['a_tdljs'] += (1-$v_a['video_scale']) * (0-$Bet_v->BetPC);
										//代理所得
										$agent_sh[$k_a]['bbty']['a_tdljg'] += $v_a['video_scale'] * (0-$Bet_v->BetPC);
											
										//总代交收
										 $agent_sh[$k_a]['bbty']['a_tzdjs'] += (1 - $v_a['zdv']['video_scale']) * (0-$Bet_v->BetPC);
											
										 //股东交收
										 $agent_sh[$k_a]['bbty']['a_tgdjs'] += (1-$v_a['gdv']['video_scale']) * (0-$Bet_v->BetPC);
									}
								}
							}
						}
					}
				}
			}
		}

		
		//BBIN彩票
		if ($_GET['bbcp'] == '14') 
		{
			//股东层
			if($_GET['atype'] == 's_h')
			{	
				$info = '';
				foreach ($v_a['dlv'] as $key => $value) 
				{
					$info .= $key.'|';
				}
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$bbBet=$games->GetAgentAvailableAmountByAgentid('bbin',$info , $date_start , $date_end,'12');
					if($bbBet){
						$bbBet = json_decode($bbBet);
						if($bbBet->data->Code==10023){
							if(!empty($bbBet->data)){
								foreach ($bbBet->data->data as $Bet_k => $Bet_v) 
								{
									foreach ($v_a['dlv'] as $key => $value) 
									{
										if($key == $Bet_v->agentid){
											$agent_sh[$k_a]['bbcp']['num']   += $Bet_v->BetBS;
											$agent_sh[$k_a]['bbcp']['BetYC'] += $Bet_v->BetYC;
											$agent_sh[$k_a]['bbcp']['BetPC'] += $Bet_v->BetPC;
											$agent_sh[$k_a]['bbcp']['BetAll'] += $Bet_v->BetAll;
											//代理交收
											$agent_sh[$k_a]['bbcp']['s_hdljs'] += (1-$value['video_scale']) * (0-$Bet_v->BetPC);
											//代理所得
											$agent_sh[$k_a]['bbcp']['s_hdljg'] += $value['video_scale'] * (0-$Bet_v->BetPC);
												
											//总代交收
											 $agent_sh[$k_a]['bbcp']['s_hzdjs'] += (1 - $v_a['zdv'][$value['pid']]['video_scale']) * (0-$Bet_v->BetPC);
												
											 //股东交收
											 $agent_sh[$k_a]['bbcp']['s_hgdjs'] += (1-$v_a['video_scale']) * (0-$Bet_v->BetPC);
										}
									}
								}
							}
						}
					}
				}
			}
			//总代层
			else if($_GET['atype'] == 'u_a')
			{
				$info = '';
				foreach ($v_a['dlv'] as $key => $value) 
				{
					$info .= $key.'|';
				}
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$bbBet=$games->GetAgentAvailableAmountByAgentid('bbin',$info , $date_start , $date_end,'12');
						
					if($bbBet){
						$bbBet = json_decode($bbBet);
						if($bbBet->data->Code==10023){
							if(!empty($bbBet->data)){
								foreach ($bbBet->data->data as $Bet_k => $Bet_v) 
								{
									foreach ($v_a['dlv'] as $key => $value) 
									{
										if($key == $Bet_v->agentid){
											$agent_sh[$k_a]['bbcp']['num']   += $Bet_v->BetBS;
											$agent_sh[$k_a]['bbcp']['BetYC'] += $Bet_v->BetYC;
											$agent_sh[$k_a]['bbcp']['BetPC'] += $Bet_v->BetPC;
											$agent_sh[$k_a]['bbcp']['BetAll'] += $Bet_v->BetAll;
											//代理交收
											$agent_sh[$k_a]['bbcp']['u_adljs'] += (1-$value['video_scale']) * (0-$Bet_v->BetPC);
											//代理所得
											$agent_sh[$k_a]['bbcp']['u_adljg'] += $value['video_scale'] * (0-$Bet_v->BetPC);
												
											//总代交收
											 $agent_sh[$k_a]['bbcp']['u_azdjs'] += (1 - $v_a['video_scale']) * (0-$Bet_v->BetPC);
												
											 //股东交收
											 $agent_sh[$k_a]['bbcp']['u_agdjs'] += (1-$v_a['gdv']['video_scale']) * (0-$Bet_v->BetPC);
										}
									}
								}
							}
						}
					}
				}
			}
			//代理层
			else if($_GET['atype'] == 'a_t')
			{
				$info = '';
				$info .= $k_a.'|';
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$bbBet=$games->GetAgentAvailableAmountByAgentid('bbin',$info , $date_start , $date_end,'12');
						
					if($bbBet){
						$bbBet = json_decode($bbBet);
						if($bbBet->data->Code==10023){
							if(!empty($bbBet->data)){
								foreach ($bbBet->data->data as $Bet_k => $Bet_v) 
								{		
									if($k_a == $Bet_v->agentid){
										$agent_sh[$k_a]['bbcp']['num']   += $Bet_v->BetBS;
										$agent_sh[$k_a]['bbcp']['BetYC'] += $Bet_v->BetYC;
										$agent_sh[$k_a]['bbcp']['BetPC'] += $Bet_v->BetPC;
										$agent_sh[$k_a]['bbcp']['BetAll'] += $Bet_v->BetAll;
										//代理交收
										$agent_sh[$k_a]['bbcp']['a_tdljs'] += (1-$v_a['video_scale']) * (0-$Bet_v->BetPC);
										//代理所得
										$agent_sh[$k_a]['bbcp']['a_tdljg'] += $v_a['video_scale'] * (0-$Bet_v->BetPC);
											
										//总代交收
										 $agent_sh[$k_a]['bbcp']['a_tzdjs'] += (1 - $v_a['zdv']['video_scale']) * (0-$Bet_v->BetPC);
											
										 //股东交收
										 $agent_sh[$k_a]['bbcp']['a_tgdjs'] += (1-$v_a['gdv']['video_scale']) * (0-$Bet_v->BetPC);
									}
								}
							}
						}
					}
				}
			}
		}



		
		//mg视讯
		if ($_GET['mg'] == '7') 
		{
			//股东层
			if($_GET['atype'] == 's_h')
			{	
				$info = '';
				foreach ($v_a['dlv'] as $key => $value) 
				{
					$info .= $key.'|';
				}
				$info = trim($info,'|');
				if($info){
					$games = new Games();
					$mgBet=$games->GetAgentAvailableAmountByAgentid('mg',$info , $date_start , $date_end);
					if($mgBet){
                        $mgBet = json_decode($mgBet);
						
						if($mgBet->data->Code==10023){
							if(!empty($mgBet->data)){
								foreach ($mgBet->data->data as $Bet_k => $Bet_v) 
								{
									foreach ($v_a['dlv'] as $key => $value) 
									{
										if($key == $Bet_v->agentid){
											$agent_sh[$k_a]['mg']['num']   += $Bet_v->BetBS;
											$agent_sh[$k_a]['mg']['BetYC'] += $Bet_v->BetYC;
											$agent_sh[$k_a]['mg']['BetPC'] += $Bet_v->BetPC;
											$agent_sh[$k_a]['mg']['BetAll'] += $Bet_v->BetAll;
											//代理交收
											$agent_sh[$k_a]['mg']['s_hdljs'] += (1-$value['video_scale']) * (0-$Bet_v->BetPC);
											//代理所得
											$agent_sh[$k_a]['mg']['s_hdljg'] += $value['video_scale'] * (0-$Bet_v->BetPC);
												
											//总代交收
											 $agent_sh[$k_a]['mg']['s_hzdjs'] += (1 - $v_a['zdv'][$value['pid']]['video_scale']) * (0-$Bet_v->BetPC);
												
											 //股东交收
											 $agent_sh[$k_a]['mg']['s_hgdjs'] += (1-$v_a['video_scale']) * (0-$Bet_v->BetPC);
										}
									}
								}
							}
						}
					}
				}
				
			}
			//总代层
			else if($_GET['atype'] == 'u_a')
			{
				$info = '';
				foreach ($v_a['dlv'] as $key => $value) 
				{
					$info .= $key.'|';
				}
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$mgBet=$games->GetAgentAvailableAmountByAgentid('mg',$info , $date_start , $date_end);
						
					if($mgBet){
						$mgBet = json_decode($mgBet);
						if($mgBet->data->Code==10023){
							if(!empty($mgBet->data)){
								foreach ($mgBet->data->data as $Bet_k => $Bet_v) 
								{
									foreach ($v_a['dlv'] as $key => $value) 
									{
										if($key == $Bet_v->agentid){
											$agent_sh[$k_a]['mg']['num']   += $Bet_v->BetBS;
											$agent_sh[$k_a]['mg']['BetYC'] += $Bet_v->BetYC;
											$agent_sh[$k_a]['mg']['BetPC'] += $Bet_v->BetPC;
											$agent_sh[$k_a]['mg']['BetAll'] += $Bet_v->BetAll;
											//代理交收
											$agent_sh[$k_a]['mg']['u_adljs'] += (1-$value['video_scale']) * (0-$Bet_v->BetPC);
											//代理所得
											$agent_sh[$k_a]['mg']['u_adljg'] += $value['video_scale'] * (0-$Bet_v->BetPC);
												
											//总代交收
											 $agent_sh[$k_a]['mg']['u_azdjs'] += (1 - $v_a['video_scale']) * (0-$Bet_v->BetPC);
												
											 //股东交收
											 $agent_sh[$k_a]['mg']['u_agdjs'] += (1-$v_a['gdv']['video_scale']) * (0-$Bet_v->BetPC);
										}
									}
								}
							}
						}
					}
				}
			}
			//代理层
			else if($_GET['atype'] == 'a_t')
			{
				$info = '';
				$info .= $k_a.'|';
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$mgBet=$games->GetAgentAvailableAmountByAgentid('mg',$info , $date_start , $date_end);
						
					if($mgBet){
						$mgBet = json_decode($mgBet);
						if($mgBet->data->Code==10023){
							if(!empty($mgBet->data)){
								foreach ($mgBet->data->data as $Bet_k => $Bet_v) 
								{										
									if($k_a == $Bet_v->agentid){
										$agent_sh[$k_a]['mg']['num']   += $Bet_v->BetBS;
										$agent_sh[$k_a]['mg']['BetYC'] += $Bet_v->BetYC;
										$agent_sh[$k_a]['mg']['BetPC'] += $Bet_v->BetPC;
										$agent_sh[$k_a]['mg']['BetAll'] += $Bet_v->BetAll;
										//代理交收
										$agent_sh[$k_a]['mg']['a_tdljs'] += (1-$v_a['video_scale']) * (0-$Bet_v->BetPC);
										//代理所得
										$agent_sh[$k_a]['mg']['a_tdljg'] += $v_a['video_scale'] * (0-$Bet_v->BetPC);
											
										//总代交收
										 $agent_sh[$k_a]['mg']['a_tzdjs'] += (1 - $v_a['zdv']['video_scale']) * (0-$Bet_v->BetPC);
											
										 //股东交收
										 $agent_sh[$k_a]['mg']['a_tgdjs'] += (1-$v_a['gdv']['video_scale']) * (0-$Bet_v->BetPC);
									}
								}
							}
						}
					}
				}
			}
		}

		//mg电子
		if ($_GET['mgdz'] == '8') 
		{
			//股东层
			if($_GET['atype'] == 's_h')
			{	
				$info = '';
				foreach ($v_a['dlv'] as $key => $value) 
				{
					$info .= $key.'|';
				}
				$info = trim($info,'|');
				if($info){
					$games = new Games();
					$mgdzBet=$games->GetAgentAvailableAmountByAgentid('mg',$info , $date_start , $date_end,'1');
					if($mgdzBet){
						$mgdzBet = json_decode($mgdzBet);
						if($mgdzBet->data->Code==10023){
							if(!empty($mgdzBet->data)){
								foreach ($mgdzBet->data->data as $Bet_k => $Bet_v) 
								{
									foreach ($v_a['dlv'] as $key => $value) 
									{
										if($key == $Bet_v->agentid){
											
											$agent_sh[$k_a]['mgdz']['num']   += $Bet_v->BetBS;
											$agent_sh[$k_a]['mgdz']['BetYC'] += $Bet_v->BetYC;
											$agent_sh[$k_a]['mgdz']['BetPC'] += $Bet_v->BetPC;
											$agent_sh[$k_a]['mgdz']['BetAll'] += $Bet_v->BetAll;
											//代理交收
											$agent_sh[$k_a]['mgdz']['s_hdljs'] += (1-$value['video_scale']) * (0-$Bet_v->BetPC);
											//代理所得
											$agent_sh[$k_a]['mgdz']['s_hdljg'] += $value['video_scale'] * (0-$Bet_v->BetPC);
												
											//总代交收
											 $agent_sh[$k_a]['mgdz']['s_hzdjs'] += (1 - $v_a['zdv'][$value['pid']]['video_scale']) * (0-$Bet_v->BetPC);
												
											 //股东交收
											 $agent_sh[$k_a]['mgdz']['s_hgdjs'] += (1-$v_a['video_scale']) * (0-$Bet_v->BetPC);
										}
									}
								}
							}
						}
					}
				}
				
			}
			//总代层
			else if($_GET['atype'] == 'u_a')
			{
				$info = '';
				foreach ($v_a['dlv'] as $key => $value) 
				{
					$info .= $key.'|';
				}
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$mgdzBet=$games->GetAgentAvailableAmountByAgentid('mg',$info , $date_start , $date_end,'1');
						
					if($mgdzBet){
						$mgdzBet = json_decode($mgdzBet);
						if($mgdzBet->data->Code==10023){
							if(!empty($mgdzBet->data)){
								foreach ($mgdzBet->data->data as $Bet_k => $Bet_v) 
								{
									foreach ($v_a['dlv'] as $key => $value) 
									{
										if($key == $Bet_v->agentid){
											$agent_sh[$k_a]['mgdz']['num']   += $Bet_v->BetBS;
											$agent_sh[$k_a]['mgdz']['BetYC'] += $Bet_v->BetYC;
											$agent_sh[$k_a]['mgdz']['BetPC'] += $Bet_v->BetPC;
											$agent_sh[$k_a]['mgdz']['BetAll'] += $Bet_v->BetAll;
											//代理交收
											$agent_sh[$k_a]['mgdz']['u_adljs'] += (1-$value['video_scale']) * (0-$Bet_v->BetPC);
											//代理所得
											$agent_sh[$k_a]['mgdz']['u_adljg'] += $value['video_scale'] * (0-$Bet_v->BetPC);
												
											//总代交收
											 $agent_sh[$k_a]['mgdz']['u_azdjs'] += (1 - $v_a['video_scale']) * (0-$Bet_v->BetPC);
												
											 //股东交收
											 $agent_sh[$k_a]['mgdz']['u_agdjs'] += (1-$v_a['gdv']['video_scale']) * (0-$Bet_v->BetPC);
										}
									}
								}
							}
						}
					}
				}
			}
			//代理层
			else if($_GET['atype'] == 'a_t')
			{
				$info = '';
				$info .= $k_a.'|';
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$mgdzBet=$games->GetAgentAvailableAmountByAgentid('mg',$info , $date_start , $date_end,'1');
						
					if($mgdzBet){
						$mgdzBet = json_decode($mgdzBet);
						if($mgdzBet->data->Code==10023){
							if(!empty($mgdzBet->data)){
								foreach ($mgdzBet->data->data as $Bet_k => $Bet_v) 
								{
										
									if($k_a == $Bet_v->agentid){
										$agent_sh[$k_a]['mgdz']['num']   += $Bet_v->BetBS;
										$agent_sh[$k_a]['mgdz']['BetYC'] += $Bet_v->BetYC;
										$agent_sh[$k_a]['mgdz']['BetPC'] += $Bet_v->BetPC;
										$agent_sh[$k_a]['mgdz']['BetAll'] += $Bet_v->BetAll;
										//代理交收
										$agent_sh[$k_a]['mgdz']['a_tdljs'] += (1-$v_a['video_scale']) * (0-$Bet_v->BetPC);
										//代理所得
										$agent_sh[$k_a]['mgdz']['a_tdljg'] += $v_a['video_scale'] * (0-$Bet_v->BetPC);
											
										//总代交收
										 $agent_sh[$k_a]['mgdz']['a_tzdjs'] += (1 - $v_a['zdv']['video_scale']) * (0-$Bet_v->BetPC);
											
										 //股东交收
										 $agent_sh[$k_a]['mgdz']['a_tgdjs'] += (1-$v_a['gdv']['video_scale']) * (0-$Bet_v->BetPC);
									}
								}
							}
						}
					}
				}
			}
		}

		//ag视讯
		if ($_GET['ag'] == '10') 
		{
			//股东层
			if($_GET['atype'] == 's_h')
			{
				$info = '';
				foreach ($v_a['dlv'] as $key => $value) 
				{
					$info .= $key.'|';
				}
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$agBet=$games->GetAgentAvailableAmountByAgentid('ag',$info , $date_start , $date_end);
						
					if($agBet){
						$agBet = json_decode($agBet);
						if($agBet->data->Code==10023){
							if(!empty($agBet->data)){
								foreach ($agBet->data->data as $Bet_k => $Bet_v) 
								{
									foreach ($v_a['dlv'] as $key => $value) 
									{
										if($key == $Bet_v->agentid){
											$agent_sh[$k_a]['ag']['num']   += $Bet_v->BetBS;
											$agent_sh[$k_a]['ag']['BetYC'] += $Bet_v->BetYC;
											$agent_sh[$k_a]['ag']['BetPC'] += $Bet_v->BetPC;
											$agent_sh[$k_a]['ag']['BetAll'] += $Bet_v->BetAll;
											//代理交收
											$agent_sh[$k_a]['ag']['s_hdljs'] += (1-$value['video_scale']) * (0-$Bet_v->BetPC);
											//代理所得
											$agent_sh[$k_a]['ag']['s_hdljg'] += $value['video_scale'] * (0-$Bet_v->BetPC);
												
											//总代交收
											 $agent_sh[$k_a]['ag']['s_hzdjs'] += (1 - $v_a['zdv'][$value['pid']]['video_scale']) * (0-$Bet_v->BetPC);
												
											 //股东交收
											 $agent_sh[$k_a]['ag']['s_hgdjs'] += (1-$v_a['video_scale']) * (0-$Bet_v->BetPC);
										}
									}
								}
							}
						}
					}
				}
			}
			//总代层
			else if($_GET['atype'] == 'u_a')
			{
				$info = '';
				foreach ($v_a['dlv'] as $key => $value) 
				{
					$info .= $key.'|';
				}
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$agBet=$games->GetAgentAvailableAmountByAgentid('ag',$info , $date_start , $date_end);
						
					if($agBet){
						$agBet = json_decode($agBet);
						if($agBet->data->Code==10023){
							if(!empty($agBet->data)){
								foreach ($agBet->data->data as $Bet_k => $Bet_v) 
								{
									foreach ($v_a['dlv'] as $key => $value) 
									{
										if($key == $Bet_v->agentid){
											$agent_sh[$k_a]['ag']['num']   += $Bet_v->BetBS;
											$agent_sh[$k_a]['ag']['BetYC'] += $Bet_v->BetYC;
											$agent_sh[$k_a]['ag']['BetPC'] += $Bet_v->BetPC;
											$agent_sh[$k_a]['ag']['BetAll'] += $Bet_v->BetAll;
											//代理交收
											$agent_sh[$k_a]['ag']['u_adljs'] += (1-$value['video_scale']) * (0-$Bet_v->BetPC);
											//代理所得
											$agent_sh[$k_a]['ag']['u_adljg'] += $value['video_scale'] * (0-$Bet_v->BetPC);
												
											//总代交收
											 $agent_sh[$k_a]['ag']['u_azdjs'] += (1 - $v_a['video_scale']) * (0-$Bet_v->BetPC);
												
											 //股东交收
											 $agent_sh[$k_a]['ag']['u_agdjs'] += (1-$v_a['gdv']['video_scale']) * (0-$Bet_v->BetPC);
										}
									}
								}
							}
						}
					}
				}
			}
			//代理层
			else if($_GET['atype'] == 'a_t')
			{
				$info = '';
				$info .= $k_a.'|';
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$agBet=$games->GetAgentAvailableAmountByAgentid('ag',$info , $date_start , $date_end);
						
					if($agBet){
						$agBet = json_decode($agBet);
						if($agBet->data->Code==10023){
							if(!empty($agBet->data)){
								foreach ($agBet->data->data as $Bet_k => $Bet_v) 
								{		
									if($k_a == $Bet_v->agentid){
										$agent_sh[$k_a]['ag']['num']   += $Bet_v->BetBS;
										$agent_sh[$k_a]['ag']['BetYC'] += $Bet_v->BetYC;
										$agent_sh[$k_a]['ag']['BetPC'] += $Bet_v->BetPC;
										$agent_sh[$k_a]['ag']['BetAll'] += $Bet_v->BetAll;
										//代理交收
										$agent_sh[$k_a]['ag']['a_tdljs'] += (1-$v_a['video_scale']) * (0-$Bet_v->BetPC);
										//代理所得
										$agent_sh[$k_a]['ag']['a_tdljg'] += $v_a['video_scale'] * (0-$Bet_v->BetPC);
											
										//总代交收
										 $agent_sh[$k_a]['ag']['a_tzdjs'] += (1 - $v_a['zdv']['video_scale']) * (0-$Bet_v->BetPC);
											
										 //股东交收
										 $agent_sh[$k_a]['ag']['a_tgdjs'] += (1-$v_a['gdv']['video_scale']) * (0-$Bet_v->BetPC);
									}
								}
							}
						}
					}
				}
			}
		}

		//og视讯
		if ($_GET['og'] == '11') 
		{
			//股东层
			if($_GET['atype'] == 's_h')
			{	
				$info = '';
				foreach ($v_a['dlv'] as $key => $value) 
				{
					$info .= $key.'|';
				}
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$ogBet=$games->GetAgentAvailableAmountByAgentid('og',$info , $date_start , $date_end);
					if($ogBet){
						$ogBet = json_decode($ogBet);
						if($ogBet->data->Code==10023){
							if(!empty($ogBet->data)){
								foreach ($ogBet->data->data as $Bet_k => $Bet_v) 
								{
									foreach ($v_a['dlv'] as $key => $value) 
									{
										if($key == $Bet_v->agentid){
											$agent_sh[$k_a]['og']['num']   += $Bet_v->BetBS;
											$agent_sh[$k_a]['og']['BetYC'] += $Bet_v->BetYC;
											$agent_sh[$k_a]['og']['BetPC'] += $Bet_v->BetPC;
											$agent_sh[$k_a]['og']['BetAll'] += $Bet_v->BetAll;
											//代理交收
											$agent_sh[$k_a]['og']['s_hdljs'] += (1-$value['video_scale']) * (0-$Bet_v->BetPC);
											//代理所得
											$agent_sh[$k_a]['og']['s_hdljg'] += $value['video_scale'] * (0-$Bet_v->BetPC);
												
											//总代交收
											 $agent_sh[$k_a]['og']['s_hzdjs'] += (1 - $v_a['zdv'][$value['pid']]['video_scale']) * (0-$Bet_v->BetPC);
												
											 //股东交收
											 $agent_sh[$k_a]['og']['s_hgdjs'] += (1-$v_a['video_scale']) * (0-$Bet_v->BetPC);
										}
									}
								}
							}
						}
					}
				}
			}
			//总代层
			else if($_GET['atype'] == 'u_a')
			{
				$info = '';
				foreach ($v_a['dlv'] as $key => $value) 
				{
					$info .= $key.'|';
				}
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$ogBet=$games->GetAgentAvailableAmountByAgentid('og',$info , $date_start , $date_end);
						
					if($ogBet){
						$ogBet = json_decode($ogBet);
						if($ogBet->data->Code==10023){
							if(!empty($ogBet->data)){
								foreach ($ogBet->data->data as $Bet_k => $Bet_v) 
								{
									foreach ($v_a['dlv'] as $key => $value) 
									{
										if($key == $Bet_v->agentid){
											$agent_sh[$k_a]['og']['num']   += $Bet_v->BetBS;
											$agent_sh[$k_a]['og']['BetYC'] += $Bet_v->BetYC;
											$agent_sh[$k_a]['og']['BetPC'] += $Bet_v->BetPC;
											$agent_sh[$k_a]['og']['BetAll'] += $Bet_v->BetAll;
											//代理交收
											$agent_sh[$k_a]['og']['u_adljs'] += (1-$value['video_scale']) * (0-$Bet_v->BetPC);
											//代理所得
											$agent_sh[$k_a]['og']['u_adljg'] += $value['video_scale'] * (0-$Bet_v->BetPC);
												
											//总代交收
											 $agent_sh[$k_a]['og']['u_azdjs'] += (1 - $v_a['video_scale']) * (0-$Bet_v->BetPC);
												
											 //股东交收
											 $agent_sh[$k_a]['og']['u_agdjs'] += (1-$v_a['gdv']['video_scale']) * (0-$Bet_v->BetPC);
										}
									}
								}
							}
						}
					}
				}
			}
			//代理层
			else if($_GET['atype'] == 'a_t')
			{
				$info = '';
				$info .= $k_a.'|';
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$ogBet=$games->GetAgentAvailableAmountByAgentid('og',$info , $date_start , $date_end);
						
					if($ogBet){
						$ogBet = json_decode($ogBet);
						if($ogBet->data->Code==10023){
							if(!empty($ogBet->data)){
								foreach ($ogBet->data->data as $Bet_k => $Bet_v) 
								{
										
									if($k_a == $Bet_v->agentid){
										$agent_sh[$k_a]['og']['num']   += $Bet_v->BetBS;
										$agent_sh[$k_a]['og']['BetYC'] += $Bet_v->BetYC;
										$agent_sh[$k_a]['og']['BetPC'] += $Bet_v->BetPC;
										$agent_sh[$k_a]['og']['BetAll'] += $Bet_v->BetAll;
										//代理交收
										$agent_sh[$k_a]['og']['a_tdljs'] += (1-$v_a['video_scale']) * (0-$Bet_v->BetPC);
										//代理所得
										$agent_sh[$k_a]['og']['a_tdljg'] += $v_a['video_scale'] * (0-$Bet_v->BetPC);
											
										//总代交收
										 $agent_sh[$k_a]['og']['a_tzdjs'] += (1 - $v_a['zdv']['video_scale']) * (0-$Bet_v->BetPC);
											
										 //股东交收
										 $agent_sh[$k_a]['og']['a_tgdjs'] += (1-$v_a['gdv']['video_scale']) * (0-$Bet_v->BetPC);
									}
								}
							}
						}
					}
				}
				
			}
		}

		//ct视讯
		if ($_GET['ct'] == '4') 
		{
			//股东层
			if($_GET['atype'] == 's_h')
			{	
				$info = '';
				foreach ($v_a['dlv'] as $key => $value) 
				{
					$info .= $key.'|';
				}
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$ctBet=$games->GetAgentAvailableAmountByAgentid('ct',$info , $date_start , $date_end);
					if($ctBet){
						$ctBet = json_decode($ctBet);
						if($ctBet->data->Code==10023){
							if(!empty($ctBet->data)){
								foreach ($ctBet->data->data as $Bet_k => $Bet_v) 
								{
									foreach ($v_a['dlv'] as $key => $value) 
									{
										if($key == $Bet_v->agentid){
											$agent_sh[$k_a]['ct']['num']   += $Bet_v->BetBS;
											$agent_sh[$k_a]['ct']['BetYC'] += $Bet_v->BetYC;
											$agent_sh[$k_a]['ct']['BetPC'] += $Bet_v->BetPC;
											$agent_sh[$k_a]['ct']['BetAll'] += $Bet_v->BetAll;
											//代理交收
											$agent_sh[$k_a]['ct']['s_hdljs'] += (1-$value['video_scale']) * (0-$Bet_v->BetPC);
											//代理所得
											$agent_sh[$k_a]['ct']['s_hdljg'] += $value['video_scale'] * (0-$Bet_v->BetPC);
												
											//总代交收
											 $agent_sh[$k_a]['ct']['s_hzdjs'] += (1 - $v_a['zdv'][$value['pid']]['video_scale']) * (0-$Bet_v->BetPC);
												
											 //股东交收
											 $agent_sh[$k_a]['ct']['s_hgdjs'] += (1-$v_a['video_scale']) * (0-$Bet_v->BetPC);
										}
									}
								}
							}
						}
					}
				}
			}
			//总代层
			else if($_GET['atype'] == 'u_a')
			{
				$info = '';
				foreach ($v_a['dlv'] as $key => $value) 
				{
					$info .= $key.'|';
				}
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$ctBet=$games->GetAgentAvailableAmountByAgentid('ct',$info , $date_start , $date_end);
						
					if($ctBet){
						$ctBet = json_decode($ctBet);
						if($ctBet->data->Code==10023){
							if(!empty($ctBet->data)){
								foreach ($ctBet->data->data as $Bet_k => $Bet_v) 
								{
									foreach ($v_a['dlv'] as $key => $value) 
									{
										if($key == $Bet_v->agentid){
											$agent_sh[$k_a]['ct']['num']   += $Bet_v->BetBS;
											$agent_sh[$k_a]['ct']['BetYC'] += $Bet_v->BetYC;
											$agent_sh[$k_a]['ct']['BetPC'] += $Bet_v->BetPC;
											$agent_sh[$k_a]['ct']['BetAll'] += $Bet_v->BetAll;
											//代理交收
											$agent_sh[$k_a]['ct']['u_adljs'] += (1-$value['video_scale']) * (0-$Bet_v->BetPC);
											//代理所得
											$agent_sh[$k_a]['ct']['u_adljg'] += $value['video_scale'] * (0-$Bet_v->BetPC);
												
											//总代交收
											 $agent_sh[$k_a]['ct']['u_azdjs'] += (1 - $v_a['video_scale']) * (0-$Bet_v->BetPC);
												
											 //股东交收
											 $agent_sh[$k_a]['ct']['u_agdjs'] += (1-$v_a['gdv']['video_scale']) * (0-$Bet_v->BetPC);
										}
									}
								}
							}
						}
					}
				}
			}
			//代理层
			else if($_GET['atype'] == 'a_t')
			{
				$info = '';
				$info .= $k_a.'|';
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$ctBet=$games->GetAgentAvailableAmountByAgentid('ct',$info , $date_start , $date_end);
						
					if($ctBet){
						$ctBet = json_decode($ctBet);
						if($ctBet->data->Code==10023){
							if(!empty($ctBet->data)){
								foreach ($ctBet->data->data as $Bet_k => $Bet_v) 
								{
									if($k_a == $aid['agent_id']){
										$agent_sh[$k_a]['ct']['num']   += $Bet_v->BetBS;
										$agent_sh[$k_a]['ct']['BetYC'] += $Bet_v->BetYC;
										$agent_sh[$k_a]['ct']['BetPC'] += $Bet_v->BetPC;
										$agent_sh[$k_a]['ct']['BetAll'] += $Bet_v->BetAll;
										//代理交收
										$agent_sh[$k_a]['ct']['a_tdljs'] += (1-$v_a['video_scale']) * (0-$Bet_v->BetPC);
										//代理所得
										$agent_sh[$k_a]['ct']['a_tdljg'] += $v_a['video_scale'] * (0-$Bet_v->BetPC);
											
										//总代交收
										 $agent_sh[$k_a]['ct']['a_tzdjs'] += (1 - $v_a['zdv']['video_scale']) * (0-$Bet_v->BetPC);
											
										 //股东交收
										 $agent_sh[$k_a]['ct']['a_tgdjs'] += (1-$v_a['gdv']['video_scale']) * (0-$Bet_v->BetPC);
									}
								}
							}
						}
					}
				}
			}
		}

		//lebo视讯
		if ($_GET['lebo'] == '3') 
		{
			//股东层
			if($_GET['atype'] == 's_h')
			{	
				$info = '';
				foreach ($v_a['dlv'] as $key => $value) 
				{
					$info .= $key.'|';
				}
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$leboBet=$games->GetAgentAvailableAmountByAgentid('lebo',$info , $date_start , $date_end);
						
					if($leboBet){
						$leboBet = json_decode($leboBet);
						if($leboBet->data->Code==10023){
							if(!empty($leboBet->data)){
								foreach ($leboBet->data->data as $Bet_k => $Bet_v) 
								{
									foreach ($v_a['dlv'] as $key => $value) 
									{
										if($key == $Bet_v->agentid){
											$agent_sh[$k_a]['lebo']['num']   += $Bet_v->BetBS;
											$agent_sh[$k_a]['lebo']['BetYC'] += $Bet_v->BetYC;
											$agent_sh[$k_a]['lebo']['BetPC'] += $Bet_v->BetPC;
											$agent_sh[$k_a]['lebo']['BetAll'] += $Bet_v->BetAll;
											//代理交收
											$agent_sh[$k_a]['lebo']['s_hdljs'] += (1-$value['video_scale']) * (0-$Bet_v->BetPC);
											//代理所得
											$agent_sh[$k_a]['lebo']['s_hdljg'] += $value['video_scale'] * (0-$Bet_v->BetPC);
												
											//总代交收
											 $agent_sh[$k_a]['lebo']['s_hzdjs'] += (1 - $v_a['zdv'][$value['pid']]['video_scale']) * (0-$Bet_v->BetPC);
												
											 //股东交收
											 $agent_sh[$k_a]['lebo']['s_hgdjs'] += (1-$v_a['video_scale']) * (0-$Bet_v->BetPC);
										}
									}
								}
							}
						}
					}
				}
			}
			//总代层
			else if($_GET['atype'] == 'u_a')
			{
				$info = '';
				foreach ($v_a['dlv'] as $key => $value) 
				{
					$info .= $key.'|';
				}
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$leboBet=$games->GetAgentAvailableAmountByAgentid('lebo',$info , $date_start , $date_end);
						
					if($leboBet){
						$leboBet = json_decode($leboBet);
						if($leboBet->data->Code==10023){
							if(!empty($leboBet->data)){
								foreach ($leboBet->data->data as $Bet_k => $Bet_v) 
								{
									foreach ($v_a['dlv'] as $key => $value) 
									{
										if($key == $Bet_v->agentid){
											$agent_sh[$k_a]['lebo']['num']   += $Bet_v->BetBS;
											$agent_sh[$k_a]['lebo']['BetYC'] += $Bet_v->BetYC;
											$agent_sh[$k_a]['lebo']['BetPC'] += $Bet_v->BetPC;
											$agent_sh[$k_a]['lebo']['BetAll'] += $Bet_v->BetAll;
											//代理交收
											$agent_sh[$k_a]['lebo']['u_adljs'] += (1-$value['video_scale']) * (0-$Bet_v->BetPC);
											//代理所得
											$agent_sh[$k_a]['lebo']['u_adljg'] += $value['video_scale'] * (0-$Bet_v->BetPC);
												
											//总代交收
											 $agent_sh[$k_a]['lebo']['u_azdjs'] += (1 - $v_a['video_scale']) * (0-$Bet_v->BetPC);
												
											 //股东交收
											 $agent_sh[$k_a]['lebo']['u_agdjs'] += (1-$v_a['gdv']['video_scale']) * (0-$Bet_v->BetPC);
										}
									}
								}
							}
						}
					}
				}
			}
			//代理层
			else if($_GET['atype'] == 'a_t')
			{
				$info = '';
				$info .= $k_a.'|';
				$info = trim($info,'|');
				if($info)
				{
					$games = new Games();
					$leboBet=$games->GetAgentAvailableAmountByAgentid('lebo',$info , $date_start , $date_end);
						
					if($leboBet){
						$leboBet = json_decode($leboBet);
						if($leboBet->data->Code==10023){
							if(!empty($leboBet->data)){
								foreach ($leboBet->data->data as $Bet_k => $Bet_v) 
								{

									if($k_a == $Bet_v->agentid){
										$agent_sh[$k_a]['lebo']['num']   += $Bet_v->BetBS;
										$agent_sh[$k_a]['lebo']['BetYC'] += $Bet_v->BetYC;
										$agent_sh[$k_a]['lebo']['BetPC'] += $Bet_v->BetPC;
										$agent_sh[$k_a]['lebo']['BetAll'] += $Bet_v->BetAll;
										//代理交收
										$agent_sh[$k_a]['lebo']['a_tdljs'] += (1-$v_a['video_scale']) * (0-$Bet_v->BetPC);
										//代理所得
										$agent_sh[$k_a]['lebo']['a_tdljg'] += $v_a['video_scale'] * (0-$Bet_v->BetPC);
											
										//总代交收
										 $agent_sh[$k_a]['lebo']['a_tzdjs'] += (1 - $v_a['zdv']['video_scale']) * (0-$Bet_v->BetPC);
											
										 //股东交收
										 $agent_sh[$k_a]['lebo']['a_tgdjs'] += (1-$v_a['gdv']['video_scale']) * (0-$Bet_v->BetPC);
									}
								}
							}
						}
					}
				}
			}
		}
	}
}
//会员层
else
{
	
	//彩票
	if ($_GET['cp'] == '2') 
	{	
		foreach($agent_sh as $k_a => $v_a)
		{
			//条件初始化
			$data_a = $data_b = array();
			//彩票总笔数和总投注
			$data_a['uid'] = $v_a['uid'];
			$data_a['addtime']  = array(array('>=',$date_start),array('<=',$date_end));
			if($_GET['is_res'] == 1){
				$data_a['js'] = 1;
				$data_a['status']   = array('in',"(1,2)");
			}
			$cp_bett = M('c_bet',$db_config)
					->field("count(id) as cp_count,sum(money) as c_bet_money")
					->where($data_a)
					->find();
			
			$agent_sh[$k_a]['fc']['cp_count']     += $cp_bett['cp_count'];
			$agent_sh[$k_a]['fc']['c_bet_money']  += $cp_bett['c_bet_money'];
		
			//彩票总有效金额
			$data_b['uid'] = $v_a['uid'];
			$data_b['status']   = array('in',"(1,2)");
			$data_b['addtime']  = array(array('>=',$date_start),array('<=',$date_end));
			$cp_bet = M('c_bet',$db_config)
					->field("sum(money) as c_bet_money_YX,sum(win) as payout_fc")
					->where($data_b)
					->find();

			$agent_sh[$k_a]['fc']['c_bet_money_YX'] += $cp_bet['c_bet_money_YX'];
			$agent_sh[$k_a]['fc']['payout_fc']      += $cp_bet['payout_fc'];

			//代理交收
			$agent_sh[$k_a]['fc']['userdljs'] += (1-$v_a['dlv']['lottery_scale']) * ($cp_bet['c_bet_money_YX'] - $cp_bet['payout_fc']);
			//代理所得
			$agent_sh[$k_a]['fc']['userdljg'] += $v_a['dlv']['lottery_scale'] * ($cp_bet['c_bet_money_YX'] - $cp_bet['payout_fc']);
			
			//总代交收
			//$agent_sh[$k_a]['fc']['userzdjs'] += (1 - $v_a['zdv'][$value['pid']]['lottery_scale']) * ($cp_bet['c_bet_money_YX'] - $cp_bet['payout_fc']);
			
			//股东交收
			//$agent_sh[$k_a]['fc']['usergdjs'] += (1-$v_a['lottery_scale']) * ($cp_bet['c_bet_money_YX'] - $cp_bet['payout_fc']);
			
		}
	}
	
	//体育
	if ($_GET['sp'] == '1') 
	{
		foreach($agent_sh as $k_a => $v_a)
		{
			//条件初始化
			$data_a = $data_b = $data_c = $data_d = array();
			//体育总笔数和总投注
			$data_a['uid']  = $v_a['uid'];
			$data_a['bet_time']  = array(array('>=',$date_start),array('<=',$date_end));
			if($_GET['is_res'] == 1){
				$data_a['is_jiesuan'] = 1;
				$data_a['status']    = array('in',"(1,2,4,5)");
			}
			$sp_bett = M('k_bet',$db_config)
					->field("count(bid) as sp_count,sum(bet_money) as sp_bet_money")
					->where($data_a)
					->find();

			$agent_sh[$k_a]['sp']['sp_count']     += $sp_bett['sp_count'];
			$agent_sh[$k_a]['sp']['sp_bet_money'] += $sp_bett['sp_bet_money'];

			//体育总有效金额和总派彩
			$data_b['uid']  = $v_a['uid'];
			$data_b['bet_time']  = array(array('>=',$date_start),array('<=',$date_end));
			$data_b['status']    = array('in',"(1,2,4,5)");
			$sp_bet = M('k_bet',$db_config)
					->field("sum(bet_money) as sp_bet_money_YX,sum(win) as payout_sp")
					->where($data_b)
					->find();

			$agent_sh[$k_a]['sp']['sp_bet_money_YX'] += $sp_bet['sp_bet_money_YX'];
			$agent_sh[$k_a]['sp']['payout_sp']       += $sp_bet['payout_sp'];

			//体育串关总笔数和总投注
			$data_c['uid'] = $v_a['uid'];
			$data_c['bet_time'] = array(array('>=',$date_start),array('<=',$date_end));
			if($_GET['is_res'] == 1){
				$data_c['is_jiesuan'] = 1;
				$data_c['status']    = array('in',"(1,2,4,5)");
			}
			$spc_bett = M('k_bet_cg_group',$db_config)
					->field("count(gid) as spc_count,sum(bet_money) as spc_bet_money")
					->where($data_c)
					->find();

			$agent_sh[$k_a]['sp']['sp_count']     += $spc_bett['spc_count'];
			$agent_sh[$k_a]['sp']['sp_bet_money'] += $spc_bett['spc_bet_money'];

			//体育串关总有效金额和总派彩
			$data_d['uid'] = $v_a['uid'];
			$data_d['bet_time'] = array(array('>=',$date_start),array('<=',$date_end));
			$data_d['status']    = array('in',"(1,2,4,5)");
			$spc_bet = M('k_bet_cg_group',$db_config)
					->field("sum(bet_money) as spc_bet_money_YX,sum(win) as payout_spc")
					->where($data_d)
					->find();

			$agent_sh[$k_a]['sp']['sp_bet_money_YX'] += $spc_bet['spc_bet_money_YX'];
			$agent_sh[$k_a]['sp']['payout_sp']       += $spc_bet['payout_spc'];

			//代理交收
			$agent_sh[$k_a]['sp']['userdljs'] += (1-$v_a['dlv']['sports_scale']) * (($sp_bet['sp_bet_money_YX'] + $spc_bet['spc_bet_money_YX']) - ($sp_bet['payout_sp'] + $spc_bet['payout_spc']));
			//代理所得
			$agent_sh[$k_a]['sp']['userdljg'] += $v_a['dlv']['sports_scale'] * (($sp_bet['sp_bet_money_YX'] + $spc_bet['spc_bet_money_YX']) - ($sp_bet['payout_sp'] + $spc_bet['payout_spc']));
			
			//总代交收
			//$agent_sh[$k_a]['sp']['u_azdjs'] += (1 - $v_a['zdv'][$value['pid']]['sports_scale']) * (($sp_bet['sp_bet_money_YX'] + $spc_bet['spc_bet_money_YX']) - ($sp_bet['payout_sp'] + $spc_bet['payout_spc']));
			
			//股东交收
			//$agent_sh[$k_a]['sp']['u_agdjs'] += (1-$v_a['sports_scale']) * (($sp_bet['sp_bet_money_YX'] + $spc_bet['spc_bet_money_YX']) - ($sp_bet['payout_sp'] + $spc_bet['payout_spc']));
		}
	}
		
		
	//bbin视讯
	if ($_GET['bbsx'] == '9') 
	{	
		$info = '';
		$games = new Games();
		$bbBet=$games->GetUserAvailableAmountByAgentid('bbin',$_GET['uid'], $date_start , $date_end,'3');
		if($bbBet)
		{
			$bbBet = json_decode($bbBet);
			if($bbBet->data->Code==10023)
			{
				foreach($agent_sh as $k_a => $v_a)
				{
					if(!empty($bbBet->data))
					{
						foreach ($bbBet->data->data as $Bet_k => $Bet_v) 
						{
							if($v_a['username'] == $Bet_v->username){
								$aid = M('k_user',$db_config)
								->where("username = '$Bet_v->username' and site_id = '".SITEID."'")
								->field('agent_id')
								->find();
								
								$agent_sh[$k_a]['bbsx']['num']   += $Bet_v->BetBS;
								$agent_sh[$k_a]['bbsx']['BetYC'] += $Bet_v->BetYC;
								$agent_sh[$k_a]['bbsx']['BetPC'] += $Bet_v->BetPC;
								$agent_sh[$k_a]['bbsx']['BetAll'] += $Bet_v->BetAll;
								//代理交收
								$agent_sh[$k_a]['bbsx']['userdljs'] += (1-$v_a['dlv']['video_scale']) * (0-$Bet_v->BetPC);
								//代理所得
								$agent_sh[$k_a]['bbsx']['userdljg'] += $v_a['dlv']['video_scale'] * (0-$Bet_v->BetPC);
								 
							}
						}
					}
				}
			}
		}
	}

	//bbin机率
	if ($_GET['bbdz'] == '12') 
	{	
		$info = '';
		$games = new Games();
		$bbBet=$games->GetUserAvailableAmountByAgentid('bbin',$_GET['uid'], $date_start , $date_end,'5');
		if($bbBet)
		{
			$bbBet = json_decode($bbBet);
			if($bbBet->data->Code==10023)
			{
				foreach($agent_sh as $k_a => $v_a)
				{
					if(!empty($bbBet->data))
					{
						foreach ($bbBet->data->data as $Bet_k => $Bet_v) 
						{
							if($v_a['username'] == $Bet_v->username){
								$aid = M('k_user',$db_config)
								->where("username = '$Bet_v->username' and site_id = '".SITEID."'")
								->field('agent_id')
								->find();
								
								$agent_sh[$k_a]['bbdz']['num']   += $Bet_v->BetBS;
								$agent_sh[$k_a]['bbdz']['BetYC'] += $Bet_v->BetYC;
								$agent_sh[$k_a]['bbdz']['BetPC'] += $Bet_v->BetPC;
								$agent_sh[$k_a]['bbdz']['BetAll'] += $Bet_v->BetAll;
								//代理交收
								$agent_sh[$k_a]['bbdz']['userdljs'] += (1-$v_a['dlv']['video_scale']) * (0-$Bet_v->BetPC);
								//代理所得
								$agent_sh[$k_a]['bbdz']['userdljg'] += $v_a['dlv']['video_scale'] * (0-$Bet_v->BetPC);
								 
							}
						}
					}
				}
			}
		}
	}

		
	//bbin球类
	if ($_GET['bbty'] == '13') 
	{	
		$info = '';
		$games = new Games();
		$bbBet=$games->GetUserAvailableAmountByAgentid('bbin',$_GET['uid'], $date_start , $date_end,'1');
		if($bbBet)
		{
			$bbBet = json_decode($bbBet);
			if($bbBet->data->Code==10023)
			{
				foreach($agent_sh as $k_a => $v_a)
				{
					if(!empty($bbBet->data))
					{
						foreach ($bbBet->data->data as $Bet_k => $Bet_v) 
						{
							if($v_a['username'] == $Bet_v->username){
								$aid = M('k_user',$db_config)
								->where("username = '$Bet_v->username' and site_id = '".SITEID."'")
								->field('agent_id')
								->find();
								
								$agent_sh[$k_a]['bbty']['num']   += $Bet_v->BetBS;
								$agent_sh[$k_a]['bbty']['BetYC'] += $Bet_v->BetYC;
								$agent_sh[$k_a]['bbty']['BetPC'] += $Bet_v->BetPC;
								$agent_sh[$k_a]['bbty']['BetAll'] += $Bet_v->BetAll;
								//代理交收
								$agent_sh[$k_a]['bbty']['userdljs'] += (1-$v_a['dlv']['video_scale']) * (0-$Bet_v->BetPC);
								//代理所得
								$agent_sh[$k_a]['bbty']['userdljg'] += $v_a['dlv']['video_scale'] * (0-$Bet_v->BetPC);
								 
							}
						}
					}
				}
			}
		}
	}

	//bbin彩票
	if ($_GET['bbcp'] == '14') 
	{	
		$info = '';
		$games = new Games();
		$bbBet=$games->GetUserAvailableAmountByAgentid('bbin',$_GET['uid'], $date_start , $date_end,'12');
		if($bbBet)
		{
			$bbBet = json_decode($bbBet);
			if($bbBet->data->Code==10023)
			{
				foreach($agent_sh as $k_a => $v_a)
				{
					if(!empty($bbBet->data))
					{
						foreach ($bbBet->data->data as $Bet_k => $Bet_v) 
						{
							if($v_a['username'] == $Bet_v->username){
								$aid = M('k_user',$db_config)
								->where("username = '$Bet_v->username' and site_id = '".SITEID."'")
								->field('agent_id')
								->find();
								
								$agent_sh[$k_a]['bbcp']['num']   += $Bet_v->BetBS;
								$agent_sh[$k_a]['bbcp']['BetYC'] += $Bet_v->BetYC;
								$agent_sh[$k_a]['bbcp']['BetPC'] += $Bet_v->BetPC;
								$agent_sh[$k_a]['bbcp']['BetAll'] += $Bet_v->BetAll;
								//代理交收
								$agent_sh[$k_a]['bbcp']['userdljs'] += (1-$v_a['dlv']['video_scale']) * (0-$Bet_v->BetPC);
								//代理所得
								$agent_sh[$k_a]['bbcp']['userdljg'] += $v_a['dlv']['video_scale'] * (0-$Bet_v->BetPC);
								 
							}
						}
					}
				}
			}
		}
	}



	
	//ag视讯
	if ($_GET['ag'] == '10') 
	{	
		$info = '';
		$games = new Games();
		$agBet=$games->GetUserAvailableAmountByAgentid('ag',$_GET['uid'], $date_start , $date_end);
		if($agBet)
		{
			$agBet = json_decode($agBet);
			if($agBet->data->Code==10023)
			{
				foreach($agent_sh as $k_a => $v_a)
				{
					if(!empty($agBet->data))
					{
						foreach ($agBet->data->data as $Bet_k => $Bet_v) 
						{
							if($v_a['username'] == $Bet_v->username){
								$aid = M('k_user',$db_config)
								->where("username = '$Bet_v->username' and site_id = '".SITEID."'")
								->field('agent_id')
								->find();
								
								$agent_sh[$k_a]['ag']['num']   += $Bet_v->BetBS;
								$agent_sh[$k_a]['ag']['BetYC'] += $Bet_v->BetYC;
								$agent_sh[$k_a]['ag']['BetPC'] += $Bet_v->BetPC;
								$agent_sh[$k_a]['ag']['BetAll'] += $Bet_v->BetAll;
								//代理交收
								$agent_sh[$k_a]['ag']['userdljs'] += (1-$v_a['dlv']['video_scale']) * (0-$Bet_v->BetPC);
								//代理所得
								$agent_sh[$k_a]['ag']['userdljg'] += $v_a['dlv']['video_scale'] * (0-$Bet_v->BetPC);
								 
							}
						}
					}
				}
			}
		}
	}
	
	//og视讯
	if ($_GET['og'] == '11') 
	{	
		$info = '';
		$games = new Games();
		$ogBet=$games->GetUserAvailableAmountByAgentid('og',$_GET['uid'], $date_start , $date_end);
		if($ogBet)
		{
			$ogBet = json_decode($ogBet);
			if($ogBet->data->Code==10023)
			{
				foreach($agent_sh as $k_a => $v_a)
				{
					if(!empty($ogBet->data))
					{
						foreach ($ogBet->data->data as $Bet_k => $Bet_v) 
						{
							if($v_a['username'] == $Bet_v->username){
								$aid = M('k_user',$db_config)
								->where("username = '$Bet_v->username' and site_id = '".SITEID."'")
								->field('agent_id')
								->find();
								
								$agent_sh[$k_a]['og']['num']   += $Bet_v->BetBS;
								$agent_sh[$k_a]['og']['BetYC'] += $Bet_v->BetYC;
								$agent_sh[$k_a]['og']['BetPC'] += $Bet_v->BetPC;
								$agent_sh[$k_a]['og']['BetAll'] += $Bet_v->BetAll;
								//代理交收
								$agent_sh[$k_a]['og']['userdljs'] += (1-$v_a['dlv']['video_scale']) * (0-$Bet_v->BetPC);
								//代理所得
								$agent_sh[$k_a]['og']['userdljg'] += $v_a['dlv']['video_scale'] * (0-$Bet_v->BetPC);
								 
							}
						}
					}
				}
			}
		}
	}
	
	//mg视讯
	if ($_GET['mg'] == '7') 
	{	
		$info = '';
		$games = new Games();
		$mgBet=$games->GetUserAvailableAmountByAgentid('mg',$_GET['uid'], $date_start , $date_end);
		if($mgBet)
		{
			$mgBet = json_decode($mgBet);
			if($mgBet->data->Code==10023)
			{
				foreach($agent_sh as $k_a => $v_a)
				{
					if(!empty($mgBet->data))
					{
						foreach ($mgBet->data->data as $Bet_k => $Bet_v) 
						{
							if($v_a['username'] == $Bet_v->username){
								$aid = M('k_user',$db_config)
								->where("username = '$Bet_v->username' and site_id = '".SITEID."'")
								->field('agent_id')
								->find();
								
								$agent_sh[$k_a]['mg']['num']   += $Bet_v->BetBS;
								$agent_sh[$k_a]['mg']['BetYC'] += $Bet_v->BetYC;
								$agent_sh[$k_a]['mg']['BetPC'] += $Bet_v->BetPC;
								$agent_sh[$k_a]['mg']['BetAll'] += $Bet_v->BetAll;
								//代理交收
								$agent_sh[$k_a]['mg']['userdljs'] += (1-$v_a['dlv']['video_scale']) * (0-$Bet_v->BetPC);
								//代理所得
								$agent_sh[$k_a]['mg']['userdljg'] += $v_a['dlv']['video_scale'] * (0-$Bet_v->BetPC);
								 
							}
						}
					}
				}
			}
		}
	}

	//mg电子
	if ($_GET['mgdz'] == '8') 
	{	
		$info = '';
		$games = new Games();
		$mgdzBet=$games->GetUserAvailableAmountByAgentid('mg',$_GET['uid'], $date_start , $date_end,'1');
		if($mgdzBet)
		{
			$mgdzBet = json_decode($mgdzBet);
			if($mgdzBet->data->Code==10023)
			{
				foreach($agent_sh as $k_a => $v_a)
				{
					if(!empty($mgdzBet->data))
					{
						foreach ($mgdzBet->data->data as $Bet_k => $Bet_v) 
						{
							if($v_a['username'] == $Bet_v->username){
								$aid = M('k_user',$db_config)
								->where("username = '$Bet_v->username' and site_id = '".SITEID."'")
								->field('agent_id')
								->find();
								
								$agent_sh[$k_a]['mgdz']['num']   += $Bet_v->BetBS;
								$agent_sh[$k_a]['mgdz']['BetYC'] += $Bet_v->BetYC;
								$agent_sh[$k_a]['mgdz']['BetPC'] += $Bet_v->BetPC;
								$agent_sh[$k_a]['mgdz']['BetAll'] += $Bet_v->BetAll;
								//代理交收
								$agent_sh[$k_a]['mgdz']['userdljs'] += (1-$v_a['dlv']['video_scale']) * (0-$Bet_v->BetPC);
								//代理所得
								$agent_sh[$k_a]['mgdz']['userdljg'] += $v_a['dlv']['video_scale'] * (0-$Bet_v->BetPC);
								 
							}
						}
					}
				}
			}
		}
	}
	
	//lebo视讯
	if ($_GET['lebo'] == '3') 
	{	
		$info = '';
		$games = new Games();
		$leboBet=$games->GetUserAvailableAmountByAgentid('lebo',$_GET['uid'], $date_start , $date_end);
		if($leboBet)
		{
			$leboBet = json_decode($leboBet);
			if($leboBet->data->Code==10023)
			{
				foreach($agent_sh as $k_a => $v_a)
				{
					if(!empty($leboBet->data))
					{
						foreach ($leboBet->data->data as $Bet_k => $Bet_v) 
						{
							if($v_a['username'] == $Bet_v->username){
								$aid = M('k_user',$db_config)
								->where("username = '$Bet_v->username' and site_id = '".SITEID."'")
								->field('agent_id')
								->find();
								
								$agent_sh[$k_a]['lebo']['num']   += $Bet_v->BetBS;
								$agent_sh[$k_a]['lebo']['BetYC'] += $Bet_v->BetYC;
								$agent_sh[$k_a]['lebo']['BetPC'] += $Bet_v->BetPC;
								$agent_sh[$k_a]['lebo']['BetAll'] += $Bet_v->BetAll;
								//代理交收
								$agent_sh[$k_a]['lebo']['userdljs'] += (1-$v_a['dlv']['video_scale']) * (0-$Bet_v->BetPC);
								//代理所得
								$agent_sh[$k_a]['lebo']['userdljg'] += $v_a['dlv']['video_scale'] * (0-$Bet_v->BetPC);
								 
							}
						}
					}
				}
			}
		}
	}
	
	//ct视讯
	if ($_GET['ct'] == '3') 
	{	
		$info = '';
		$games = new Games();
		$ctBet=$games->GetUserAvailableAmountByAgentid('ct',$_GET['uid'], $date_start , $date_end);
		if($ctBet)
		{
			$ctBet = json_decode($ctBet);
			if($ctBet->data->Code==10023)
			{
				foreach($agent_sh as $k_a => $v_a)
				{
					if(!empty($ctBet->data))
					{
						foreach ($ctBet->data->data as $Bet_k => $Bet_v) 
						{
							if($v_a['username'] == $Bet_v->username){
								$aid = M('k_user',$db_config)
								->where("username = '$Bet_v->username' and site_id = '".SITEID."'")
								->field('agent_id')
								->find();
								
								$agent_sh[$k_a]['ct']['num']   += $Bet_v->BetBS;
								$agent_sh[$k_a]['ct']['BetYC'] += $Bet_v->BetYC;
								$agent_sh[$k_a]['ct']['BetPC'] += $Bet_v->BetPC;
								$agent_sh[$k_a]['ct']['BetAll'] += $Bet_v->BetAll;
								//代理交收
								$agent_sh[$k_a]['ct']['userdljs'] += (1-$v_a['dlv']['video_scale']) * (0-$Bet_v->BetPC);
								//代理所得
								$agent_sh[$k_a]['ct']['userdljg'] += $v_a['dlv']['video_scale'] * (0-$Bet_v->BetPC);
								 
							}
						}
					}
				}
			}
		}
	}
	
	
	
	foreach($agent_sh as $k_a => $v_a)
	{
		$agent_sh[$k_a]['id']           = $v_a['uid'];
		$agent_sh[$k_a]['agent_user']   = $v_a['username'];
		$agent_sh[$k_a]['agent_name']   = '线上注册会员';

	}
}


?>

<?php $title="报表明细"; require("../common_html/header.php");?>
<body> 
<style type="text/css">
table.m_tab th span{
	margin-right:8px;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
	  $("td").each(function () {
          if (parseFloat($(this).text())<0) {
        	  $(this).css("color","red");
          }
      })
	});
	  
$(document).ready(function(){  
	$("#m_tab1").tablesorter({
		sortList:false,
		headers:{	//有特别指定按digit的列是可以保证正负数值正确排序，改动时请注意列的对应
			4:{
				sorter:'digit'
			},
			5:{
				sorter:'digit'
			},
			6:{
				sorter:'digit'
			},	
			7:{
				sorter:'digit'
			},
			8:{
				sorter:'digit'
			},
			9:{
				sorter:'digit'
			},	
			10:{
				sorter:'digit'
			},
			11:{
				sorter:'digit'
			},
			12:{
				sorter:'digit'
			},	
			13:{
				sorter:'digit'
			}			
		}
	});
	$("#m_tab2").tablesorter({
		sortList:false,
		headers:{	//有特别指定按digit的列数是可以保证正负数值正确排序，links是针对正则的排序，改动时请注意列的对应
			2:{
				sorter:'links'
			},
			4:{
				sorter:'digit'
			},
			5:{
				sorter:'digit'
			},
			6:{
				sorter:'digit'
			},	
			7:{
				sorter:'digit'
			},
			8:{
				sorter:'digit'
			},
			9:{
				sorter:'digit'
			},	
			10:{
				sorter:'digit'
			},
			11:{
				sorter:'digit'
			},
			12:{
				sorter:'digit'
			},	
			13:{
				sorter:'digit'
			}			
		}
	});	
	$("#m_tab3").tablesorter({
		sortList:false,
		headers:{	//有特别指定按digit的列数是可以保证正负数值正确排序，links是针对正则的排序，改动时请注意列的对应
			2:{
				sorter:'links'
			},
			4:{
				sorter:'digit'
			},
			5:{
				sorter:'digit'
			},
			6:{
				sorter:'digit'
			},	
			7:{
				sorter:'digit'
			},
			8:{
				sorter:'digit'
			},
			9:{
				sorter:'digit'
			},	
			10:{
				sorter:'digit'
			},
			11:{
				sorter:'digit'
			},
			12:{
				sorter:'digit'
			},	
			13:{
				sorter:'digit'
			}				
		}
	});	
	$("#m_tab4").tablesorter({
		sortList:false,
		headers:{
			2:{
				sorter:'links'
			},
			4:{
				sorter:'links'
			},
			5:{
				sorter:'digit'
			},
			6:{
				sorter:'digit'
			},	
			7:{
				sorter:'digit'
			},
			8:{
				sorter:'digit'
			},
			9:{
				sorter:'digit'
			},	
			10:{
				sorter:'digit'
			},
			11:{
				sorter:'digit'
			},
			12:{
				sorter:'digit'
			},	
			13:{
				sorter:'digit'
			}			
		}
	});	
	//$("th[id^=target]").css("background-color","#527A98");
} );
$.tablesorter.addParser({
    // set a unique id 
    id: 'links',
    is: function(s)
    {
        // return false so this parser is not auto detected 
        return false;
    },
    format: function(s)
    {
        // format your data for normalization 
        return s.replace(new RegExp(/<.*?>/),"");
    },
    // set type, either numeric or text
    type: 'numeric'
}); 
</script>
<div id="con_wrap">
  <div class="input_002"><?=$r_title?> - 報表查詢</div>
  <div class="con_menu">
  	<a href="javascript:history.go(-1);">返回上一頁</a>
  </div>
</div>
<div class="content" id="content_report">

<table border="0" cellpadding="0" cellspacing="0" id="m_tab1" class="m_tab tablesorter">
	<thead>
	<tr class="m_title">
		<td colspan="14" style="text-align:left;background:#FFFFFF;color:#333131">
        <div style="float:left">總報表 日期：<?=$date_start?>~ <?=$date_end?> 投注類型：全部 投注方式：全部</div>
        <div style="float:right">"<font color="#ff0000">紅</font>"色代表业主亏损，"<font color="#000">黑</font>"色代表业主盈利</div></td>
	</tr>
	<tr class="m_title">
	  <th width="150" rowspan="2" nowrap="nowrap" style="font-size:14px;" class="header"><span><?=$table_title?>名稱</span></th>
	  <th width="80" rowspan="2" class="header" style="font-size:14px;"><span>總筆數</span></th>
	  <th id="target1" width="80" rowspan="2" style="font-size:12px;" class="header"><span>總下注金額</span></th>
	  <th width="80" rowspan="2" style="font-size:12px;" class="header"><span>總有效金額</span></th>
	   <th width="80" rowspan="2" style="font-size:12px;" class="header"><span>總派彩</span></th>
      <th width="80" rowspan="2" style="font-size:12px;" class="header"><span><?=$u_table_title?>占成</span></th>
      <th width="80" rowspan="2" style="font-size:12px;" class="header"><span><?=$u_table_title?>退水</span></th>
      <th width="80" rowspan="2" style="font-size:12px;" class="header"><span><?=$u_table_title?>損益</span></th>
	  <td colspan="<?=count($level_a)?>" style="text-align:center;font-size:12px;background-color: #fecee4;color:#122858"><span>總各層交收</span></td>
    </tr>
	<tr class="m_title">
	    <?php foreach ($level_a as $key => $val) {
        ?>
          <th class="header"><span><?=$val['name']?></span></th>
        <?php }?>
        <th style="display:none" class="header"><span>後台</span></th>
	</tr>
	</thead>
	<tbody>

    <?php foreach ($agent_sh as $key => $val) {

		$total_count += $val['sp']['sp_count']+$val['fc']['cp_count']+$val['mg']['num']+$val['ag']['num']+$val['og']['num']+$val['bbsx']['num']+$val['bbdz']['num']+$val['bbty']['num']+$val['bbcp']['num']+$val['ct']['num']+$val['lebo']['num']+$val['mgdz']['num'];
		$total_bet += $val['sp']['sp_bet_money']+$val['fc']['c_bet_money']+$val['mg']['BetAll']+$val['ag']['BetAll']+$val['og']['BetAll']+$val['bbsx']['BetAll']+$val['bbdz']['BetAll']+$val['bbty']['BetAll']+$val['bbcp']['BetAll']+$val['ct']['BetAll']+$val['lebo']['BetAll']+$val['mgdz']['BetAll'];
		$total_bet_YX_o = $val['sp']['sp_bet_money_YX']+$val['fc']['c_bet_money_YX']+$val['mg']['BetYC']+$val['ag']['BetYC']+$val['og']['BetYC']+$val['bbsx']['BetYC']+$val['bbdz']['BetYC']+$val['bbty']['BetYC']+$val['bbcp']['BetYC']+$val['ct']['BetYC']+$val['lebo']['BetYC']+$val['mgdz']['BetYC'];
		$total_bet_YX += $total_bet_YX_o;
		$total_payout_o = $val['sp']['payout_sp']+$val['fc']['payout_fc']+$val['mg']['BetPC']+$val['ag']['BetPC']+$val['og']['BetPC']+$val['bbsx']['BetPC']+$val['bbdz']['BetPC']+$val['bbty']['BetPC']+$val['bbcp']['BetPC']+$val['ct']['BetPC']+$val['lebo']['BetPC']+$val['mgdz']['BetPC']+$val['mg']['BetYC']+$val['ag']['BetYC']+$val['og']['BetYC']+$val['bbsx']['BetYC']+$val['bbdz']['BetYC']+$val['bbty']['BetYC']+$val['bbcp']['BetYC']+$val['ct']['BetYC']+$val['lebo']['BetYC']+$val['mgdz']['BetYC'];
		$total_payout+=$total_payout_o;
		if($atype == 'u_a'){
			$total_dljs+=$val['fc']['s_hdljs']+$val['sp']['s_hdljs']+$val['mg']['s_hdljs']+$val['ag']['s_hdljs']+$val['og']['s_hdljs']+$val['ct']['s_hdljs']+$val['bbsx']['s_hdljs']+$val['bbdz']['s_hdljs']+$val['bbty']['s_hdljs']+$val['bbcp']['s_hdljs']+$val['lebo']['s_hdljs']+$val['mgdz']['s_hdljs'];
			$total_zdjs+=$val['fc']['s_hzdjs']+$val['sp']['s_hzdjs']+$val['mg']['s_hzdjs']+$val['ag']['s_hzdjs']+$val['og']['s_hzdjs']+$val['ct']['s_hzdjs']+$val['bbsx']['s_hzdjs']+$val['bbdz']['s_hzdjs']+$val['bbty']['s_hzdjs']+$val['bbcp']['s_hzdjs']+$val['lebo']['s_hzdjs']+$val['mgdz']['s_hzdjs'];
			$total_gdjs+=$val['fc']['s_hgdjs']+$val['sp']['s_hgdjs']+$val['mg']['s_hgdjs']+$val['ag']['s_hgdjs']+$val['og']['s_hgdjs']+$val['ct']['s_hgdjs']+$val['bbsx']['s_hgdjs']+$val['bbdz']['s_hgdjs']+$val['bbty']['s_hgdjs']+$val['bbcp']['s_hgdjs']+$val['lebo']['s_hgdjs']+$val['mgdz']['s_hgdjs'];
			$total_dljg+=$val['fc']['s_hdljg']+$val['sp']['s_hdljg']+$val['mg']['s_hdljg']+$val['ag']['s_hdljg']+$val['og']['s_hdljg']+$val['ct']['s_hdljg']+$val['bbsx']['s_hdljg']+$val['bbdz']['s_hdljg']+$val['bbty']['s_hdljg']+$val['bbcp']['s_hdljg']+$val['lebo']['s_hdljg']+$val['mgdz']['s_hdljg'];//总代理商结果
			$total_ddljs=$val['fc']['s_hdljs']+$val['sp']['s_hdljs']+$val['mg']['s_hdljs']+$val['ag']['s_hdljs']+$val['og']['s_hdljs']+$val['ct']['s_hdljs']+$val['bbsx']['s_hdljs']+$val['bbdz']['s_hdljs']+$val['bbty']['s_hdljs']+$val['bbcp']['s_hdljs']+$val['lebo']['s_hdljs']+$val['mgdz']['s_hdljs'];
			$total_dzdjs=$val['fc']['s_hzdjs']+$val['sp']['s_hzdjs']+$val['mg']['s_hzdjs']+$val['ag']['s_hzdjs']+$val['og']['s_hzdjs']+$val['ct']['s_hzdjs']+$val['bbsx']['s_hzdjs']+$val['bbdz']['s_hzdjs']+$val['bbty']['s_hzdjs']+$val['bbcp']['s_hzdjs']+$val['lebo']['s_hzdjs']+$val['mgdz']['s_hzdjs'];
			$total_dgdjs=$val['fc']['s_hgdjs']+$val['sp']['s_hgdjs']+$val['mg']['s_hgdjs']+$val['ag']['s_hgdjs']+$val['og']['s_hgdjs']+$val['ct']['s_hgdjs']+$val['bbsx']['s_hgdjs']+$val['bbdz']['s_hgdjs']+$val['bbty']['s_hgdjs']+$val['bbcp']['s_hgdjs']+$val['lebo']['s_hgdjs']+$val['mgdz']['s_hgdjs'];
			$total_ddljg=$val['fc']['s_hdljg']+$val['sp']['s_hdljg']+$val['mg']['s_hdljg']+$val['ag']['s_hdljg']+$val['og']['s_hdljg']+$val['ct']['s_hdljg']+$val['bbsx']['s_hdljg']+$val['bbdz']['s_hdljg']+$val['bbty']['s_hdljg']+$val['bbcp']['s_hdljg']+$val['lebo']['s_hdljg']+$val['mgdz']['s_hdljg'];//单个股东下代理商结果
			$total_dacczc=$total_dgdjs;
			$total_acczc+=$total_dacczc;
		}elseif($atype == 'a_t'){
			$total_dljs+=$val['fc']['u_adljs']+$val['sp']['u_adljs']+$val['mg']['u_adljs']+$val['ag']['u_adljs']+$val['og']['u_adljs']+$val['ct']['u_adljs']+$val['bbsx']['u_adljs']+$val['bbdz']['u_adljs']+$val['bbty']['u_adljs']+$val['bbcp']['u_adljs']+$val['lebo']['u_adljs']+$val['mgdz']['u_adljs'];
			$total_zdjs+=$val['fc']['u_azdjs']+$val['sp']['u_azdjs']+$val['mg']['u_azdjs']+$val['ag']['u_azdjs']+$val['og']['u_azdjs']+$val['ct']['u_azdjs']+$val['bbsx']['u_azdjs']+$val['bbdz']['u_azdjs']+$val['bbty']['u_azdjs']+$val['bbcp']['u_azdjs']+$val['lebo']['u_azdjs']+$val['mgdz']['u_azdjs'];
			$total_gdjs+=$val['fc']['u_agdjs']+$val['sp']['u_agdjs']+$val['mg']['u_agdjs']+$val['ag']['u_agdjs']+$val['og']['u_agdjs']+$val['ct']['u_agdjs']+$val['bbsx']['u_agdjs']+$val['bbdz']['u_agdjs']+$val['bbty']['u_agdjs']+$val['bbcp']['u_agdjs']+$val['lebo']['u_agdjs']+$val['mgdz']['u_agdjs'];
			$total_dljg+=$val['fc']['u_adljg']+$val['sp']['u_adljg']+$val['mg']['u_adljg']+$val['ag']['u_adljg']+$val['og']['u_adljg']+$val['ct']['u_adljg']+$val['bbsx']['u_adljg']+$val['bbdz']['u_adljg']+$val['bbty']['u_adljg']+$val['bbcp']['u_adljg']+$val['lebo']['u_adljg']+$val['mgdz']['u_adljg'];//总代理商结果
			$total_ddljs=$val['fc']['u_adljs']+$val['sp']['u_adljs']+$val['mg']['u_adljs']+$val['ag']['u_adljs']+$val['og']['u_adljs']+$val['ct']['u_adljs']+$val['bbsx']['u_adljs']+$val['bbdz']['u_adljs']+$val['bbty']['u_adljs']+$val['bbcp']['u_adljs']+$val['lebo']['u_adljs']+$val['mgdz']['u_adljs'];
			$total_dzdjs=$val['fc']['u_azdjs']+$val['sp']['u_azdjs']+$val['mg']['u_azdjs']+$val['ag']['u_azdjs']+$val['og']['u_azdjs']+$val['ct']['u_azdjs']+$val['bbsx']['u_azdjs']+$val['bbdz']['u_azdjs']+$val['bbty']['u_azdjs']+$val['bbcp']['u_azdjs']+$val['lebo']['u_azdjs']+$val['mgdz']['u_azdjs'];
			$total_dgdjs=$val['fc']['u_agdjs']+$val['sp']['u_agdjs']+$val['mg']['u_agdjs']+$val['ag']['u_agdjs']+$val['og']['u_agdjs']+$val['ct']['u_agdjs']+$val['bbsx']['u_agdjs']+$val['bbdz']['u_agdjs']+$val['bbty']['u_agdjs']+$val['bbcp']['u_agdjs']+$val['lebo']['u_agdjs']+$val['mgdz']['u_agdjs'];
			$total_ddljg=$val['fc']['u_adljg']+$val['sp']['u_adljg']+$val['mg']['u_adljg']+$val['ag']['u_adljg']+$val['og']['u_adljg']+$val['ct']['u_adljg']+$val['bbsx']['u_adljg']+$val['bbdz']['u_adljg']+$val['bbty']['u_adljg']+$val['bbcp']['u_adljg']+$val['lebo']['u_adljg']+$val['mgdz']['u_adljg'];//单个股东下代理商结果
			$total_dacczc=$total_dzdjs-$total_dgdjs;
			$total_acczc+=$total_dacczc;
		}elseif($_GET['atype']=='a_t'){
			$total_dljs+=$val['fc']['a_tdljs']+$val['sp']['a_tdljs']+$val['mg']['a_tdljs']+$val['ag']['a_tdljs']+$val['og']['a_tdljs']+$val['ct']['a_tdljs']+$val['bbsx']['a_tdljs']+$val['bbdz']['a_tdljs']+$val['bbty']['a_tdljs']+$val['bbcp']['a_tdljs']+$val['lebo']['a_tdljs']+$val['mgdz']['a_tdljs'];
			$total_zdjs+=$val['fc']['a_tzdjs']+$val['sp']['a_tzdjs']+$val['mg']['a_tzdjs']+$val['ag']['a_tzdjs']+$val['og']['a_tzdjs']+$val['ct']['a_tzdjs']+$val['bbsx']['a_tzdjs']+$val['bbdz']['a_tzdjs']+$val['bbty']['a_tzdjs']+$val['bbcp']['a_tzdjs']+$val['lebo']['a_tzdjs']+$val['mgdz']['a_tzdjs'];
			$total_gdjs+=$val['fc']['a_tgdjs']+$val['sp']['a_tgdjs']+$val['mg']['a_tgdjs']+$val['ag']['a_tgdjs']+$val['og']['a_tgdjs']+$val['ct']['a_tgdjs']+$val['bbsx']['a_tgdjs']+$val['bbdz']['a_tgdjs']+$val['bbty']['a_tgdjs']+$val['bbcp']['a_tgdjs']+$val['lebo']['a_tgdjs']+$val['mgdz']['a_tgdjs'];
			$total_dljg+=$val['fc']['a_tdljg']+$val['sp']['a_tdljg']+$val['mg']['a_tdljg']+$val['ag']['a_tdljg']+$val['og']['a_tdljg']+$val['ct']['a_tdljg']+$val['bbsx']['a_tdljg']+$val['bbdz']['a_tdljg']+$val['bbty']['a_tdljg']+$val['bbcp']['a_tdljg']+$val['lebo']['a_tdljg']+$val['mgdz']['a_tdljg'];//总代理商结果
			$total_ddljs=$val['fc']['a_tdljs']+$val['sp']['a_tdljs']+$val['mg']['a_tdljs']+$val['ag']['a_tdljs']+$val['og']['a_tdljs']+$val['ct']['a_tdljs']+$val['bbsx']['a_tdljs']+$val['bbdz']['a_tdljs']+$val['bbty']['a_tdljs']+$val['bbcp']['a_tdljs']+$val['lebo']['a_tdljs']+$val['mgdz']['a_tdljs'];
			$total_dzdjs=$val['fc']['a_tzdjs']+$val['sp']['a_tzdjs']+$val['mg']['a_tzdjs']+$val['ag']['a_tzdjs']+$val['og']['a_tzdjs']+$val['ct']['a_tzdjs']+$val['bbsx']['a_tzdjs']+$val['bbdz']['a_tzdjs']+$val['bbty']['a_tzdjs']+$val['bbcp']['a_tzdjs']+$val['lebo']['a_tzdjs']+$val['mgdz']['a_tzdjs'];
			$total_dgdjs=$val['fc']['a_tgdjs']+$val['sp']['a_tgdjs']+$val['mg']['a_tgdjs']+$val['ag']['a_tgdjs']+$val['og']['a_tgdjs']+$val['ct']['a_tgdjs']+$val['bbsx']['a_tgdjs']+$val['bbdz']['a_tgdjs']+$val['bbty']['a_tgdjs']+$val['bbcp']['a_tgdjs']+$val['lebo']['a_tgdjs']+$val['mgdz']['a_tgdjs'];
			$total_ddljg=$val['fc']['a_tdljg']+$val['sp']['a_tdljg']+$val['mg']['a_tdljg']+$val['ag']['a_tdljg']+$val['og']['a_tdljg']+$val['ct']['a_tdljg']+$val['bbsx']['a_tdljg']+$val['bbdz']['a_tdljg']+$val['bbty']['a_tdljg']+$val['bbcp']['a_tdljg']+$val['lebo']['a_tdljg']+$val['mgdz']['a_tdljg'];//单个股东下代理商结果
			$total_dacczc=$total_ddljs-$total_dzdjs;
			$total_acczc+=$total_dacczc;
		}elseif($_GET['atype']=='user'){
			$total_dljs+=$val['fc']['userdljs']+$val['sp']['userdljs']+$val['mg']['userdljs']+$val['ag']['userdljs']+$val['og']['userdljs']+$val['ct']['userdljs']+$val['bbsx']['userdljs']+$val['bbdz']['userdljs']+$val['bbty']['userdljs']+$val['bbcp']['userdljs']+$val['lebo']['userdljs']+$val['mgdz']['userdljs'];
			$total_zdjs+=$val['fc']['userzdjs']+$val['sp']['userzdjs']+$val['mg']['userzdjs']+$val['ag']['userzdjs']+$val['og']['userzdjs']+$val['ct']['userzdjs']+$val['bbsx']['userzdjs']+$val['bbdz']['userzdjs']+$val['bbty']['userzdjs']+$val['bbcp']['userzdjs']+$val['lebo']['userzdjs']+$val['mgdz']['userzdjs'];
			$total_gdjs+=$val['fc']['usergdjs']+$val['sp']['usergdjs']+$val['mg']['usergdjs']+$val['ag']['usergdjs']+$val['og']['usergdjs']+$val['ct']['usergdjs']+$val['bbsx']['usergdjs']+$val['bbdz']['usergdjs']+$val['bbty']['usergdjs']+$val['bbcp']['usergdjs']+$val['lebo']['usergdjs']+$val['mgdz']['usergdjs'];
			$total_dljg+=$val['fc']['userdljg']+$val['sp']['userdljg']+$val['mg']['userdljg']+$val['ag']['userdljg']+$val['og']['userdljg']+$val['ct']['userdljg']+$val['bbsx']['userdljg']+$val['bbdz']['userdljg']+$val['bbty']['userdljg']+$val['bbcp']['userdljg']+$val['lebo']['userdljg']+$val['mgdz']['userdljg'];//总代理商结果
			$total_ddljs=$val['fc']['userdljs']+$val['sp']['userdljs']+$val['mg']['userdljs']+$val['ag']['userdljs']+$val['og']['userdljs']+$val['ct']['userdljs']+$val['bbsx']['userdljs']+$val['bbdz']['userdljs']+$val['bbty']['userdljs']+$val['bbcp']['userdljs']+$val['lebo']['userdljs']+$val['mgdz']['userdljs'];
			$total_dzdjs=$val['fc']['userzdjs']+$val['sp']['userzdjs']+$val['mg']['userzdjs']+$val['ag']['userzdjs']+$val['og']['userzdjs']+$val['ct']['userzdjs']+$val['bbsx']['userzdjs']+$val['bbdz']['userzdjs']+$val['bbty']['userzdjs']+$val['bbcp']['userzdjs']+$val['lebo']['userzdjs']+$val['mgdz']['userzdjs'];
			$total_dgdjs=$val['fc']['usergdjs']+$val['sp']['usergdjs']+$val['mg']['usergdjs']+$val['ag']['usergdjs']+$val['og']['usergdjs']+$val['ct']['usergdjs']+$val['bbsx']['usergdjs']+$val['bbdz']['usergdjs']+$val['bbty']['usergdjs']+$val['bbcp']['usergdjs']+$val['lebo']['usergdjs']+$val['mgdz']['usergdjs'];
			$total_ddljg=$val['fc']['userdljg']+$val['sp']['userdljg']+$val['mg']['userdljg']+$val['ag']['userdljg']+$val['og']['userdljg']+$val['ct']['userdljg']+$val['bbsx']['userdljg']+$val['bbdz']['userdljg']+$val['bbty']['userdljg']+$val['bbcp']['userdljg']+$val['lebo']['userdljg']+$val['mgdz']['userdljg'];//单个股东下代理商结果
			$total_dacczc=$total_ddljg;
			$total_acczc+=$total_dacczc;
		}
		
   ?>
   <?php if($val['sp']['sp_count']+$val['fc']['cp_count']+$val['mg']['num']+$val['ag']['num']+$val['og']['num']+$val['bbsx']['num']+$val['bbdz']['num']+$val['bbty']['num']+$val['bbcp']['num']+$val['ct']['num']+$val['lebo']['num']+$val['mgdz']['num']>0){?>
	<tr class="m_rig" align="left">
			<td align="center"><?=$val['agent_user']?>(<?=$val['agent_name']?>)</td>
			<td><?=$val['sp']['sp_count']+$val['fc']['cp_count']+$val['mg']['num']+$val['ag']['num']+$val['og']['num']+$val['bbsx']['num']+$val['bbdz']['num']+$val['bbty']['num']+$val['bbcp']['num']+$val['ct']['num']+$val['lebo']['num']+$val['mgdz']['num']?></td>
			<td><?=number_format($val['sp']['sp_bet_money']+$val['fc']['c_bet_money']+$val['mg']['BetAll']+$val['ag']['BetAll']+$val['og']['BetAll']+$val['bbsx']['BetAll']+$val['bbdz']['BetAll']+$val['bbty']['BetAll']+$val['bbcp']['BetAll']+$val['ct']['BetAll']+$val['lebo']['BetAll']+$val['mgdz']['BetAll'],4)?></td>
			<td><?=number_format($total_bet_YX_o+0,4)?></td>
			<td><?=number_format($total_payout_o+0,4)?></td>

			<td><?=number_format($total_bet_YX_o-$total_payout_o,4)?></td>
			<td>0.00</td>
			<td><?=number_format($total_bet_YX_o-$total_payout_o,4)?></td>

	        <td><?=number_format($total_bet_YX_o-$total_payout_o,4)?></td>
	        <td><?=number_format($total_ddljs,4)?></td>
	        <td><?=number_format($total_ddljg,4)?></td>
	        <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($total_dzdjs,4)?></td>
	        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($total_dgdjs,4)?></td>
	        <td <?php if($_GET['atype']=='u_a'||$_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
			<td style="display:none">0.00</td>
	</tr>
   <?php }?>
  <?php }?>
	</tbody>
	<tfoot>
	<?php if($total_count > 0){?>
	<tr class="m_rig">
		<td align="center">總計</td>
		<td><?=$total_count?></td>
		<td><?=number_format($total_bet,4)?></td>
		<td><?=number_format($total_bet_YX,4)?></td>
		<td><?=number_format($total_payout,4)?></td>
		<td><?=number_format($total_bet_YX-$total_payout,4)?></td>
			<td>0.00</td>
		<td><?=number_format($total_bet_YX-$total_payout,4)?></td>
        <td><?=number_format($total_bet_YX-$total_payout,4)?></td>
        <td><?=number_format($total_dljs,4)?></td>
        <td><?=number_format($total_dljg,4)?></td>
        <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($total_zdjs,4)?></td>
        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($total_gdjs,4)?></td>
        <td <?php if($_GET['atype']=='u_a'||$_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
		<td style="display:none">0.00</td>
	</tr>
	<?php }else{?>
	 <tr class="m_rig">
	 <td colspan="14" align="center">暂无数据</td>
	 </tr>
	 <?php }?>
	</tfoot>
</table>
<br>
<!-- 体育 -->
<?php  if ($_GET['sp'] == '1') {
?>
<div>
<table border="0" cellpadding="0" cellspacing="0" id="m_tab2" class="m_tab tablesorter">
	<thead>
	<tr class="m_title">
		<td colspan="14" style="text-align:left;background:#FFFFFF;color:#333131">
        <div style="float:left">體育 日期：<?=$date_start?>~ <?=$date_end?> 投注類型：全部 投注方式：全部</div>
        <div style="float:right">"<font color="#ff0000">紅</font>"色代表业主亏损，"<font color="#000">黑</font>"色代表业主盈利</div></td>
	</tr>
	<tr class="m_title">
	  <th width="100" rowspan="2" nowrap="nowrap" style="font-size:12px;" class="header"><span><?=$table_title?>名稱</span></th>
	  <th width="80" rowspan="2" class="header"><span>筆數</span></th>
	  <th id="target2" width="80" rowspan="2" class="header"><span>下注金額</span></th>
	  <th width="80" rowspan="2" class="header"><span>有效金額</span></th>
	   <th width="80" rowspan="2" class="header"><span>總派彩</span></th>
      <th width="80" rowspan="2" class="header"><span><?=$u_table_title?>占成</span></th>
      <th width="80" rowspan="2" class="header"><span><?=$u_table_title?>退水</span></th>
      <th width="80" rowspan="2" class="header"><span><?=$u_table_title?>損益</span></th>
	  <td colspan="<?=count($level_a)?>" style="text-align:center;background-color: #fecee4;color:#122858"><span>各層交收</span></td>
    </tr>
	<tr class="m_title">
		  <?php foreach ($level_a as $key => $val) {
        ?>
          <th class="header"><span><?=$val['name']?></span></th>
        <?php }?>
        <th style="display:none" class="header"><span>後台</span></th>
	</tr>
	</thead>
	<tbody>
   <?php foreach ($agent_sh as $key => $val) {
		$sp_total_count += $val['sp']['sp_count'];
		$sp_total_bet += $val['sp']['sp_bet_money'];
		$sp_total_payout += $val['sp']['payout_sp'];
		$sp_total_bet_YX += $val['sp']['sp_bet_money_YX'];
		if($atype == 'u_a'){
			$sp_total_dljs+=$val['sp']['s_hdljs'];
			$sp_total_zdjs+=$val['sp']['s_hzdjs'];
			$sp_total_gdjs+=$val['sp']['s_hgdjs'];
			$sp_total_dljg+=$val['sp']['s_hdljg'];//体育代理商总结果
			$sp_total_ddljs=$val['sp']['s_hdljs']+0;
			$sp_total_dzdjs=$val['sp']['s_hzdjs']+0;
			$sp_total_dgdjs=$val['sp']['s_hgdjs']+0;
			$sp_total_ddljg=$val['sp']['s_hdljg']+0;//体育代理商结果
			$sp_total_dacczc=$sp_total_dgdjs;
			$sp_total_acczc+=$sp_total_dacczc;
		}elseif($atype == 'a_t'){
			$sp_total_dljs+=$val['sp']['u_adljs'];
			$sp_total_zdjs+=$val['sp']['u_azdjs'];
			$sp_total_gdjs+=$val['sp']['u_agdjs'];
			$sp_total_dljg+=$val['sp']['u_adljg'];//体育代理商总结果
			$sp_total_ddljs=$val['sp']['u_adljs']+0;
			$sp_total_dzdjs=$val['sp']['u_azdjs']+0;
			$sp_total_dgdjs=$val['sp']['u_agdjs']+0;
			$sp_total_ddljg=$val['sp']['u_adljg']+0;//体育代理商结果
			$sp_total_dacczc=$sp_total_dzdjs-$sp_total_dgdjs;
			$sp_total_acczc+=$sp_total_dacczc;
		}elseif($_GET['atype'] == 'a_t'){
			$sp_total_dljs+=$val['sp']['a_tdljs'];
			$sp_total_zdjs+=$val['sp']['a_tzdjs'];
			$sp_total_gdjs+=$val['sp']['a_tgdjs'];
			$sp_total_dljg+=$val['sp']['a_tdljg'];//体育代理商总结果
			$sp_total_ddljs=$val['sp']['a_tdljs']+0;
			$sp_total_dzdjs=$val['sp']['a_tzdjs']+0;
			$sp_total_dgdjs=$val['sp']['a_tgdjs']+0;
			$sp_total_ddljg=$val['sp']['a_tdljg']+0;//体育代理商结果
			$sp_total_dacczc=$sp_total_ddljs-$sp_total_dzdjs;
			$sp_total_acczc+=$sp_total_dacczc;
		}elseif($_GET['atype'] == 'user'){
			$sp_total_dljs+=$val['sp']['userdljs'];
			$sp_total_zdjs+=$val['sp']['userzdjs'];
			$sp_total_gdjs+=$val['sp']['usergdjs'];
			$sp_total_dljg+=$val['sp']['userdljg'];//体育代理商总结果
			$sp_total_ddljs=$val['sp']['userdljs']+0;
			$sp_total_dzdjs=$val['sp']['userzdjs']+0;
			$sp_total_dgdjs=$val['sp']['usergdjs']+0;
			$sp_total_ddljg=$val['sp']['userdljg']+0;//体育代理商结果
			$sp_total_dacczc=$sp_total_dljg;
			$sp_total_acczc+=$sp_total_dacczc;
		}

		if ($_GET['atype'] == 'user') {
        $report_url = "../note/list.php?username=".$val['agent_user']."&date_start=".substr($date_start,0,10)."&date_end=".substr($date_end,0,10);
    }else{
         $report_url = './report_result.php?uid='.$val['id'].'&is_res='.$is_res.'&date_start='.substr($date_start,0,10).'&date_end='.substr($date_end,0,10).'&sp='.$_GET['sp'].'&cp='.$_GET['cp'].'&mg='.$_GET['mg'].'&ag='.$_GET['ag'].'&bbsx='.$_GET['bbsx'].'&bbdz='.$_GET['bbdz'].'&bbty='.$_GET['bbty'].'&bbcp='.$_GET['bbcp'].'&ct='.$_GET['ct'].'&lebo='.$_GET['lebo'].'&mgdz='.$_GET['mgdz'].'&og='.$_GET['og'].'&atype='.$atype;
    }
   ?>
   <?php if($val['sp']['sp_count'] > 0){?>
    <tr class="m_rig" align="left">
			<td align="center" nowrap="nowrap"><?=$val['agent_user']?>(<?=$val['agent_name']?>)</td>
			<td><?=$val['sp']['sp_count']?></td>
			<td align="right"><a href="<?=$report_url?>" class="a_001"><?=number_format($val['sp']['sp_bet_money']+0,4)?></a></td>
			<td><?=number_format($val['sp']['sp_bet_money_YX']+0,4)?></td>
			<td><?=number_format($val['sp']['payout_sp']+0,4)?></td>			
			<td><?=number_format($val['sp']['sp_bet_money_YX']-$val['sp']['payout_sp'],4)?></td>
			<td>0.00</td>
			<td><?=number_format($val['sp']['sp_bet_money_YX']-$val['sp']['payout_sp'],4)?></td>
			
            <td><?=number_format($val['sp']['sp_bet_money_YX']-$val['sp']['payout_sp'],4)?></td>
        	<td><?=number_format($sp_total_ddljs,4)?></td>
        	<td><?=number_format($sp_total_ddljg,4)?></td>
        	<td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($sp_total_dzdjs,4)?></td>
        	<td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($sp_total_dgdjs,4)?></td>
        	<td <?php if($_GET['atype']=='u_a'||$_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
			<td style="display:none">0.00</td>
		</tr>
   <?php }?>
    <?php }?>
	</tbody>
	<tfoot>
	<?php if($sp_total_count > 0){?>
	<tr class="m_rig">
		<td align="center">總計</td>
		<td><?=$sp_total_count?></td>
		<td><?=number_format($sp_total_bet,4)?></td>
		<td><?=number_format($sp_total_bet_YX+0,4)?></td>	
		<td><?=number_format($sp_total_payout,4)?></td>	
        <td><?=number_format($sp_total_bet_YX-$sp_total_payout,4)?></td>
		<td>0.00</td>
		<td><?=number_format($sp_total_bet_YX-$sp_total_payout,4)?></td>
		
		<td><?=number_format($sp_total_bet_YX-$sp_total_payout,4)?></td>
		<td><?=number_format($sp_total_dljs,4)?></td>
		<td><?=number_format($sp_total_dljg,4)?></td>
		<td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($sp_total_zdjs,4)?></td>
		<td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($sp_total_gdjs,4)?></td>
		<td <?php if($_GET['atype']=='u_a'||$_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
			<td style="display:none">0.00</td>
	</tr>
	<?php }else{?>
	 <tr class="m_rig">
	 <td colspan="14" align="center">暂无数据</td>
	 </tr>
	 <?php }?>
	</tfoot>
</table>
<br>
</div>
<?php }?>
<!-- 彩票 -->
<?php  if ($_GET['cp'] == '2') {
?>
<div>
<table border="0" cellpadding="0" cellspacing="0" id="m_tab3" class="m_tab tablesorter">
	<thead>
	<tr class="m_title">
		<td colspan="14" id="cp_panel" style="text-align:left;background:#FFFFFF;color:#333131">
        <div style="float:left">彩票 日期：<?=$date_start?>~ <?=$date_end?> 投注類型：全部 投注方式：全部</div>
         <div style="float:right">"<font color="#ff0000">紅</font>"色代表业主亏损，"<font color="#000">黑</font>"色代表业主盈利</div>
        </td>
    </tr>
	<tr class="m_title">
	  <th width="100" nowrap="nowrap" rowspan="2" style="font-size:12px;" class="header"><span><?=$table_title?>名稱</span></th>
	  <th width="80" rowspan="2" style="font-size:12px;" class="header"><span>筆數</span></th>
	  <th id="target3" width="80" rowspan="2" style="font-size:12px;" class="header"><span>下注金額</span></th>
	  <th width="80" rowspan="2" style="font-size:12px;" class="header"><span>有效金額</span></th>
	   <th width="80" rowspan="2" style="font-size:12px;" class="header"><span>總派彩</span></th>
      <th width="80" rowspan="2" style="font-size:12px;" class="header"><span><?=$u_table_title?>占成</span></th>
      <th width="80" rowspan="2" style="font-size:12px;" class="header"><span><?=$u_table_title?>退水</span></th>
      <th width="80" rowspan="2" style="font-size:12px;" class="header"><span><?=$u_table_title?>損益</span></th>
	  <td colspan="<?=count($level_a)?>" style="text-align:center;font-size:12px;background-color: #fecee4;color:#122858"><span>各層交收</span></td>
    </tr>
	<tr class="m_title">
		  <?php foreach ($level_a as $key => $val) {
        ?>
          <th class="header"><span><?=$val['name']?></span></th>
        <?php }?>
        <th style="display:none" class="header">後台</th>
	</tr>
	</thead>
	<tbody>
		<?php foreach ($agent_sh as $key => $val) {
		$fc_total_count += $val['fc']['cp_count'];
		$fc_total_bet += $val['fc']['c_bet_money'];
		$fc_total_bet_YX += $val['fc']['c_bet_money_YX'];
		$fc_total_payout += $val['fc']['payout_fc'];
		if($atype == 'u_a'){
			$fc_total_dljs+=$val['fc']['s_hdljs'];
			$fc_total_zdjs+=$val['fc']['s_hzdjs'];
			$fc_total_gdjs+=$val['fc']['s_hgdjs'];
			$fc_total_dljg+=$val['fc']['s_hdljg']+0;//总代理商结果
			$fc_total_ddljs=$val['fc']['s_hdljs']+0;
			$fc_total_dzdjs=$val['fc']['s_hzdjs']+0;
			$fc_total_dgdjs=$val['fc']['s_hgdjs']+0;
			$fc_total_ddljg=$val['fc']['s_hdljg']+0;//总代理商结果
			$fc_total_dacczc=$fc_total_dgdjs;
			$fc_total_acczc+=$fc_total_dacczc;
		}elseif($atype == 'a_t'){
			$fc_total_dljs+=$val['fc']['u_adljs'];
			$fc_total_zdjs+=$val['fc']['u_azdjs'];
			$fc_total_gdjs+=$val['fc']['u_agdjs'];
			$fc_total_dljg+=$val['fc']['u_adljg']+0;//总代理商结果
			$fc_total_ddljs=$val['fc']['u_adljs']+0;
			$fc_total_dzdjs=$val['fc']['u_azdjs']+0;
			$fc_total_dgdjs=$val['fc']['u_agdjs']+0;
			$fc_total_ddljg=$val['fc']['u_adljg']+0;//总代理商结果
			$fc_total_dacczc=$fc_total_dzdjs-$fc_total_dgdjs;
			$fc_total_acczc+=$fc_total_dacczc;
		}elseif($_GET['atype'] == 'a_t'){
			$fc_total_dljs+=$val['fc']['a_tdljs'];
			$fc_total_zdjs+=$val['fc']['a_tzdjs'];
			$fc_total_gdjs+=$val['fc']['a_tgdjs'];
			$fc_total_dljg+=$val['fc']['a_tdljg']+0;//总代理商结果
			$fc_total_ddljs=$val['fc']['a_tdljs']+0;
			$fc_total_dzdjs=$val['fc']['a_tzdjs']+0;
			$fc_total_dgdjs=$val['fc']['a_tgdjs']+0;
			$fc_total_ddljg=$val['fc']['a_tdljg']+0;//总代理商结果
			$fc_total_dacczc=$fc_total_ddljs-$fc_total_dzdjs;
			$fc_total_acczc+=$fc_total_dacczc;
		}elseif($_GET['atype'] == 'user'){
			$fc_total_dljs+=$val['fc']['userdljs'];
			$fc_total_zdjs+=$val['fc']['userzdjs'];
			$fc_total_gdjs+=$val['fc']['usergdjs'];
			$fc_total_dljg+=$val['fc']['userdljg']+0;//总代理商结果
			$fc_total_ddljs=$val['fc']['userdljs']+0;
			$fc_total_dzdjs=$val['fc']['userzdjs']+0;
			$fc_total_dgdjs=$val['fc']['usergdjs']+0;
			$fc_total_ddljg=$val['fc']['userdljg']+0;//总代理商结果
			$fc_total_dacczc=$fc_total_ddljg;
			$fc_total_acczc+=$fc_total_dacczc;
		}
		if ($_GET['atype'] == 'user') {
         $report_url = "../note/fc.php?username=".$val['agent_user']."&date_start=".substr($date_start,0,10)."&date_end=".substr($date_end,0,10);
    }else{
         $report_url = './report_result.php?uid='.$val['id'].'&is_res='.$is_res.'&date_start='.substr($date_start,0,10).'&date_end='.substr($date_end,0,10).'&sp='.$_GET['sp'].'&cp='.$_GET['cp'].'&mg='.$_GET['mg'].'&ag='.$_GET['ag'].'&bbsx='.$_GET['bbsx'].'&bbdz='.$_GET['bbdz'].'&bbty='.$_GET['bbty'].'&bbcp='.$_GET['bbcp'].'&ct='.$_GET['ct'].'&lebo='.$_GET['lebo'].'&mgdz='.$_GET['mgdz'].'&og='.$_GET['og'].'&atype='.$atype;
    }
   ?>
    <?php if($val['fc']['cp_count'] > 0){?>
    <tr class="m_rig" align="left">
			<td align="center" nowrap="nowrap"><?=$val['agent_user']?>(<?=$val['agent_name']?>)</td>
			<td><?=$val['fc']['cp_count']+0?></td>
			<td align="right"><a href="<?=$report_url?>" class="a_001"><?=number_format($val['fc']['c_bet_money']+0,4)?></a></td>
			<td><?=number_format($val['fc']['c_bet_money_YX']+0,4)?></td>
			<td><?=number_format($val['fc']['payout_fc']+0,4)?></td>
			<td><?=number_format($val['fc']['c_bet_money_YX']-$val['fc']['payout_fc']+0,4)?></td>
			<td>0.00</td>
			<td><?=number_format($val['fc']['c_bet_money_YX']-$val['fc']['payout_fc']+0,4)?></td>
			
           <td><?=number_format($val['fc']['c_bet_money_YX']-$val['fc']['payout_fc']+0,4)?></td>
           <td><?=number_format($fc_total_ddljs,4)?></td>
           <td><?=number_format($fc_total_ddljg,4)?></td>
           <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($fc_total_dzdjs,4)?></td>
           <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($fc_total_dgdjs,4)?></td>
           <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='u_a'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
        
			<td style="display:none">0.00</td>
		</tr>
	<?php }?>
    <?php }?>
	</tbody>
	<tfoot>
	<?php if($fc_total_count > 0){?>
	<tr class="m_rig">
		<td align="center">總計</td>
		<td><?=$fc_total_count+0?></td>
		<td><?=number_format($fc_total_bet+0,4)?></td>
		<td><?=number_format($fc_total_bet_YX+0,4)?></td>
		 <td><?=number_format($fc_total_payout+0,4)?></td>
        <td><?=number_format($fc_total_bet_YX-$fc_total_payout,4)?></td>
			<td>0.00</td>
		<td><?=number_format($fc_total_bet_YX-$fc_total_payout,4)?></td>
		
        <td><?=number_format($fc_total_bet_YX-$fc_total_payout,4)?></td>
        <td><?=number_format($fc_total_dljs,4)?></td>
        <td><?=number_format($fc_total_dljg,4)?></td>
        <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($fc_total_zdjs,4)?></td>
        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($fc_total_gdjs,4)?></td>
        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='u_a'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>

			<td style="display:none">0.00</td>
	</tr>
	<?php }else{?>
	 <tr class="m_rig">
	 <td colspan="14" align="center">暂无数据</td>
	 </tr>
	 <?php }?>
	</tfoot>
</table>
<br>
</div>
<?php }?>
<!-- mg视讯 -->
<?php  if ($_GET['mg'] == '7') {
?>
<div>
<table border="0" cellpadding="0" cellspacing="0" id="m_tab4" class="m_tab tablesorter">
	<thead>
	<tr class="m_title">
		<td colspan="15" id="zr_panel" style="text-align:left;background:#FFFFFF;color:#333131">
        <div style="float:left">MG视讯 日期：<?=$date_start?>~ <?=$date_end?> 投注類型：全部 投注方式：全部</div>
         <div style="float:right">"<font color="#ff0000">紅</font>"色代表业主亏损，"<font color="#000">黑</font>"色代表业主盈利</div></td>
	</tr>
	<tr class="m_title">
	  <th width="100" nowrap="nowrap" rowspan="2" style="font-size:12px;"><span><?=$table_title?>名稱</span></th>
	  <th width="50" rowspan="2" style="font-size:12px;"><span>筆數</span></th>
	  <th id="target4" width="80" rowspan="2" style="font-size:12px;"><span>下注金額</span></th>
	  <th width="80" rowspan="2" style="font-size:12px;"><span>有效金額</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span>總派彩</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>占成</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>退水</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>損益</span></th>
	  <td colspan="7" style="font-size:12px;background-color: #fecee4;color:#122858"><span>各層交收</span></td>
    </tr>
	<tr class="m_title">
		  <?php foreach ($level_a as $key => $val) {
        ?>
          <th class="header"><span><?=$val['name']?></span></th>
        <?php }?>
        <th style="display:none"><span>後台</span></th>
	</tr>
	</thead>
	<tbody>
	
	<?php foreach ($agent_sh as $key => $val) {
		$mg_total_count += $val['mg']['num'];
		$mg_total_bet += $val['mg']['BetYC'];
		$mg_total_pcbet += $val['mg']['BetPC'] + $val['mg']['BetYC'];
		$mg_total_betall += $val['mg']['BetAll'];
		if($atype == 'u_a'){
			$mg_total_dljs+=$val['mg']['s_hdljs'];
			$mg_total_zdjs+=$val['mg']['s_hzdjs'];
			$mg_total_gdjs+=$val['mg']['s_hgdjs'];
			$mg_total_dljg+=$val['mg']['s_hdljg']+0;//总代理商结果
			$mg_total_ddljs=$val['mg']['s_hdljs']+0;
			$mg_total_dzdjs=$val['mg']['s_hzdjs']+0;
			$mg_total_dgdjs=$val['mg']['s_hgdjs']+0;
			$mg_total_ddljg=$val['mg']['s_hdljg']+0;//总代理商结果
			$mg_total_dacczc=$mg_total_dgdjs;
			$mg_total_acczc+=$mg_total_dacczc;
		}elseif($atype == 'a_t'){
			$mg_total_dljs+=$val['mg']['u_adljs'];
			$mg_total_zdjs+=$val['mg']['u_azdjs'];
			$mg_total_gdjs+=$val['mg']['u_agdjs'];
			$mg_total_dljg+=$val['mg']['u_adljg']+0;//总代理商结果
			$mg_total_ddljs=$val['mg']['u_adljs']+0;
			$mg_total_dzdjs=$val['mg']['u_azdjs']+0;
			$mg_total_dgdjs=$val['mg']['u_agdjs']+0;
			$mg_total_ddljg=$val['mg']['u_adljg']+0;//总代理商结果
			$mg_total_dacczc=$mg_total_dzdjs-$mg_total_dgdjs;
			$mg_total_acczc+=$mg_total_dacczc;
		}elseif($_GET['atype'] == 'a_t'){
			$mg_total_dljs+=$val['mg']['a_tdljs'];
			$mg_total_zdjs+=$val['mg']['a_tzdjs'];
			$mg_total_gdjs+=$val['mg']['a_tgdjs'];
			$mg_total_dljg+=$val['mg']['a_tdljg']+0;//总代理商结果
			$mg_total_ddljs=$val['mg']['a_tdljs']+0;
			$mg_total_dzdjs=$val['mg']['a_tzdjs']+0;
			$mg_total_dgdjs=$val['mg']['a_tgdjs']+0;
			$mg_total_ddljg=$val['mg']['a_tdljg']+0;//总代理商结果
			$mg_total_dacczc=$mg_total_ddljs-$mg_total_dzdjs;
			$mg_total_acczc+=$mg_total_dacczc;
		}elseif($_GET['atype'] == 'user'){
			$mg_total_dljs+=$val['mg']['userdljs'];
			$mg_total_zdjs+=$val['mg']['userzdjs'];
			$mg_total_gdjs+=$val['mg']['usergdjs'];
			$mg_total_dljg+=$val['mg']['userdljg']+0;//总代理商结果
			$mg_total_ddljs=$val['mg']['userdljs']+0;
			$mg_total_dzdjs=$val['mg']['userzdjs']+0;
			$mg_total_dgdjs=$val['mg']['usergdjs']+0;
			$mg_total_ddljg=$val['mg']['userdljg']+0;//总代理商结果
			$mg_total_dacczc=$mg_total_ddljg;
			$mg_total_acczc+=$mg_total_dacczc;
		}
	if ($_GET['atype'] == 'user') {
         $report_url = "../note/video.php?username=".$val['agent_user']."&date_start=".substr($date_start,0,10)."&date_end=".substr($date_end,0,10)."&gtype=mg";
    }else{
         $report_url = './report_result.php?uid='.$val['id'].'&is_res='.$is_res.'&date_start='.substr($date_start,0,10).'&date_end='.substr($date_end,0,10).'&sp='.$_GET['sp'].'&cp='.$_GET['cp'].'&mg='.$_GET['mg'].'&ag='.$_GET['ag'].'&bbsx='.$_GET['bbsx'].'&bbdz='.$_GET['bbdz'].'&bbty='.$_GET['bbty'].'&bbcp='.$_GET['bbcp'].'&ct='.$_GET['ct'].'&lebo='.$_GET['lebo'].'&mgdz='.$_GET['mgdz'].'&og='.$_GET['og'].'&atype='.$atype;
    }
   ?>
   <?php if($val['mg']['num'] > 0){?>
	<tr class="m_rig" align="left">
			<td align="center" nowrap="nowrap"><?=$val['agent_user']?>(<?=$val['agent_name']?>)</td>
			<td><?=$val['mg']['num']+0?></td>
			<td align="right"><a href="<?=$report_url?>" class="a_001"><?=number_format($val['mg']['BetAll'],4)?></a></td>
			<td><?=number_format($val['mg']['BetYC'],4)?></td>
			<td><?=number_format($val['mg']['BetPC'] + $val['mg']['BetYC'],4)?></td>
			<td><?=number_format(0-$val['mg']['BetPC'],4)?></td>
			<td>0.00</td>
			<td><?=number_format(0-$val['mg']['BetPC'],4)?></td>
			<td><?=number_format(0-$val['mg']['BetPC'],4)?></td>
			<td><?=number_format($mg_total_ddljs,4)?></td>
	        <td><?=number_format($mg_total_ddljg,4)?></td>
	        <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($mg_total_dzdjs,4)?></td>
	        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($mg_total_dgdjs,4)?></td>
	        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='u_a'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
			<td style="display:none">0.00</td>
	</tr>
<?php }?>
 <?php }?>
	</tbody>
	<tfoot>
	<?php if($mg_total_count > 0){?>
	<tr class="m_rig">
		<td align="center">總計</td>
		<td><?=$mg_total_count?></td>
		<td><?=number_format($mg_total_betall,4)?></td>
		<td><?=number_format($mg_total_bet,4)?></td>
		<td><?=number_format($mg_total_pcbet,4)?></td>
        <td><?=number_format($mg_total_bet-$mg_total_pcbet,4)?></td>
		<td>0.00</td>
		<td><?=number_format($mg_total_bet-$mg_total_pcbet,4)?></td>
		<td><?=number_format($mg_total_bet-$mg_total_pcbet,4)?></td>
		<td><?=number_format($mg_total_dljs,4)?></td>
        <td><?=number_format($mg_total_dljg,4)?></td>
        <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($mg_total_zdjs,4)?></td>
        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($mg_total_gdjs,4)?></td>
        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='u_a'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
		<td style="display:none">0.00</td>
	</tr>
	<?php }else{?>
	 <tr class="m_rig">
	 <td colspan="14" align="center">暂无数据</td>
	 </tr>
	 <?php }?>
	</tfoot>
</table>
</div>
<?php }?>

<!-- mg电子 -->
<?php  if ($_GET['mgdz'] == '8') {
?>
<div>
<table border="0" cellpadding="0" cellspacing="0" id="m_tab4" class="m_tab tablesorter">
	<thead>
	<tr class="m_title">
		<td colspan="15" id="zr_panel" style="text-align:left;background:#FFFFFF;color:#333131">
        <div style="float:left">MG电子 日期：<?=$date_start?>~ <?=$date_end?> 投注類型：全部 投注方式：全部</div>
         <div style="float:right">"<font color="#ff0000">紅</font>"色代表业主亏损，"<font color="#000">黑</font>"色代表业主盈利</div></td>
	</tr>
	<tr class="m_title">
	  <th width="100" nowrap="nowrap" rowspan="2" style="font-size:12px;"><span><?=$table_title?>名稱</span></th>
	  <th width="50" rowspan="2" style="font-size:12px;"><span>筆數</span></th>
	  <th id="target4" width="80" rowspan="2" style="font-size:12px;"><span>下注金額</span></th>
	  <th width="80" rowspan="2" style="font-size:12px;"><span>有效金額</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span>總派彩</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>占成</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>退水</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>損益</span></th>
	  <td colspan="7" style="font-size:12px;background-color: #fecee4;color:#122858"><span>各層交收</span></td>
    </tr>
	<tr class="m_title">
		  <?php foreach ($level_a as $key => $val) {
        ?>
          <th class="header"><span><?=$val['name']?></span></th>
        <?php }?>
        <th style="display:none"><span>後台</span></th>
	</tr>
	</thead>
	<tbody>
	
	<?php foreach ($agent_sh as $key => $val) {
		$mgdz_total_count += $val['mgdz']['num'];
		$mgdz_total_bet += $val['mgdz']['BetYC'];
		$mgdz_total_pcbet += $val['mgdz']['BetPC'] + $val['mgdz']['BetYC'];
		$mgdz_total_betall += $val['mgdz']['BetAll'];
		if($atype == 'u_a'){
			$mgdz_total_dljs+=$val['mgdz']['s_hdljs'];
			$mgdz_total_zdjs+=$val['mgdz']['s_hzdjs'];
			$mgdz_total_gdjs+=$val['mgdz']['s_hgdjs'];
			$mgdz_total_dljg+=$val['mgdz']['s_hdljg']+0;//总代理商结果
			$mgdz_total_ddljs=$val['mgdz']['s_hdljs']+0;
			$mgdz_total_dzdjs=$val['mgdz']['s_hzdjs']+0;
			$mgdz_total_dgdjs=$val['mgdz']['s_hgdjs']+0;
			$mgdz_total_ddljg=$val['mgdz']['s_hdljg']+0;//总代理商结果
			$mgdz_total_dacczc=$mgdz_total_dgdjs;
			$mgdz_total_acczc+=$mgdz_total_dacczc;
		}elseif($atype == 'a_t'){
			$mgdz_total_dljs+=$val['mgdz']['u_adljs'];
			$mgdz_total_zdjs+=$val['mgdz']['u_azdjs'];
			$mgdz_total_gdjs+=$val['mgdz']['u_agdjs'];
			$mgdz_total_dljg+=$val['mgdz']['u_adljg']+0;//总代理商结果
			$mgdz_total_ddljs=$val['mgdz']['u_adljs']+0;
			$mgdz_total_dzdjs=$val['mgdz']['u_azdjs']+0;
			$mgdz_total_dgdjs=$val['mgdz']['u_agdjs']+0;
			$mgdz_total_ddljg=$val['mgdz']['u_adljg']+0;//总代理商结果
			$mgdz_total_dacczc=$mgdz_total_dzdjs-$mgdz_total_dgdjs;
			$mgdz_total_acczc+=$mgdz_total_dacczc;
		}elseif($_GET['atype'] == 'a_t'){
			$mgdz_total_dljs+=$val['mgdz']['a_tdljs'];
			$mgdz_total_zdjs+=$val['mgdz']['a_tzdjs'];
			$mgdz_total_gdjs+=$val['mgdz']['a_tgdjs'];
			$mgdz_total_dljg+=$val['mgdz']['a_tdljg']+0;//总代理商结果
			$mgdz_total_ddljs=$val['mgdz']['a_tdljs']+0;
			$mgdz_total_dzdjs=$val['mgdz']['a_tzdjs']+0;
			$mgdz_total_dgdjs=$val['mgdz']['a_tgdjs']+0;
			$mgdz_total_ddljg=$val['mgdz']['a_tdljg']+0;//总代理商结果
			$mgdz_total_dacczc=$mgdz_total_ddljs-$mgdz_total_dzdjs;
			$mgdz_total_acczc+=$mgdz_total_dacczc;
		}elseif($_GET['atype'] == 'user'){
			$mgdz_total_dljs+=$val['mgdz']['userdljs'];
			$mgdz_total_zdjs+=$val['mgdz']['userzdjs'];
			$mgdz_total_gdjs+=$val['mgdz']['usergdjs'];
			$mgdz_total_dljg+=$val['mgdz']['userdljg']+0;//总代理商结果
			$mgdz_total_ddljs=$val['mgdz']['userdljs']+0;
			$mgdz_total_dzdjs=$val['mgdz']['userzdjs']+0;
			$mgdz_total_dgdjs=$val['mgdz']['usergdjs']+0;
			$mgdz_total_ddljg=$val['mgdz']['userdljg']+0;//总代理商结果
			$mgdz_total_dacczc=$mgdz_total_ddljg;
			$mgdz_total_acczc+=$mgdz_total_dacczc;
		}
	if ($_GET['atype'] == 'user') {
         $report_url = "../note/video.php?username=".$val['agent_user']."&date_start=".substr($date_start,0,10)."&date_end=".substr($date_end,0,10)."&gtype=mg";
    }else{
         $report_url = './report_result.php?uid='.$val['id'].'&is_res='.$is_res.'&date_start='.substr($date_start,0,10).'&date_end='.substr($date_end,0,10).'&sp='.$_GET['sp'].'&cp='.$_GET['cp'].'&mg='.$_GET['mg'].'&ag='.$_GET['ag'].'&bbsx='.$_GET['bbsx'].'&bbdz='.$_GET['bbdz'].'&bbty='.$_GET['bbty'].'&bbcp='.$_GET['bbcp'].'&ct='.$_GET['ct'].'&lebo='.$_GET['lebo'].'&mgdz='.$_GET['mgdz'].'&og='.$_GET['og'].'&atype='.$atype;
    }
   ?>
   <?php if($val['mgdz']['num'] > 0){?>
	<tr class="m_rig" align="left">
			<td align="center" nowrap="nowrap"><?=$val['agent_user']?>(<?=$val['agent_name']?>)</td>
			<td><?=$val['mgdz']['num']+0?></td>
			<td align="right"><a href="<?=$report_url?>" class="a_001"><?=number_format($val['mgdz']['BetAll'],4)?></a></td>
			<td><?=number_format($val['mgdz']['BetYC'],4)?></td>
			<td><?=number_format($val['mgdz']['BetPC']+$val['mgdz']['BetYC'],4)?></td>
			<td><?=number_format(0-$val['mgdz']['BetPC'],4)?></td>
			<td>0.00</td>
			<td><?=number_format(0-$val['mgdz']['BetPC'],4)?></td>
			<td><?=number_format(0-$val['mgdz']['BetPC'],4)?></td>
			<td><?=number_format($mgdz_total_ddljs,4)?></td>
	        <td><?=number_format($mgdz_total_ddljg,4)?></td>
	        <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($mgdz_total_dzdjs,4)?></td>
	        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($mgdz_total_dgdjs,4)?></td>
	        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='u_a'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
			<td style="display:none">0.00</td>
	</tr>
<?php }?>
 <?php }?>
	</tbody>
	<tfoot>
	<?php if($mgdz_total_count > 0){?>
	<tr class="m_rig">
		<td align="center">總計</td>
		<td><?=$mgdz_total_count?></td>
		<td><?=number_format($mgdz_total_betall,4)?></td>
		<td><?=number_format($mgdz_total_bet,4)?></td>
		<td><?=number_format($mgdz_total_pcbet,4)?></td>
        <td><?=number_format($mgdz_total_bet-$mgdz_total_pcbet,4)?></td>
		<td>0.00</td>
		<td><?=number_format($mgdz_total_bet-$mgdz_total_pcbet,4)?></td>
		<td><?=number_format($mgdz_total_bet-$mgdz_total_pcbet,4)?></td>
		<td><?=number_format($mgdz_total_dljs,4)?></td>
        <td><?=number_format($mgdz_total_dljg,4)?></td>
        <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($mgdz_total_zdjs,4)?></td>
        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($mgdz_total_gdjs,4)?></td>
        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='u_a'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
		<td style="display:none">0.00</td>
	</tr>
	<?php }else{?>
	 <tr class="m_rig">
	 <td colspan="14" align="center">暂无数据</td>
	 </tr>
	 <?php }?>
	</tfoot>
</table>
</div>
<?php }?>


<!-- bbin视讯 -->
<?php  if ($_GET['bbsx'] == '9') {
?>
<div>
<table border="0" cellpadding="0" cellspacing="0" id="m_tab4" class="m_tab tablesorter">
	<thead>
	<tr class="m_title">
		<td colspan="15" id="zr_panel" style="text-align:left;background:#FFFFFF;color:#333131">
        <div style="float:left">BBIN视讯 日期：<?=$date_start?>~ <?=$date_end?> 投注類型：全部 投注方式：全部</div>
         <div style="float:right">"<font color="#ff0000">紅</font>"色代表业主亏损，"<font color="#000">黑</font>"色代表业主盈利</div></td>
	</tr>
	<tr class="m_title">
	  <th width="100" nowrap="nowrap" rowspan="2" style="font-size:12px;"><span><?=$table_title?>名稱</span></th>
	  <th width="50" rowspan="2" style="font-size:12px;"><span>筆數</span></th>
	  <th id="target4" width="80" rowspan="2" style="font-size:12px;"><span>下注金額</span></th>
	  <th width="80" rowspan="2" style="font-size:12px;"><span>有效金額</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span>總派彩</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>占成</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>退水</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>損益</span></th>
	  <td colspan="7" style="font-size:12px;background-color: #fecee4;color:#122858"><span>各層交收</span></td>
    </tr>
	<tr class="m_title">
		  <?php foreach ($level_a as $key => $val) {
        ?>
          <th class="header"><span><?=$val['name']?></span></th>
        <?php }?>
        <th style="display:none"><span>後台</span></th>
	</tr>
	</thead>
	<tbody>
	
	<?php foreach ($agent_sh as $key => $val) {
		$bbsx_total_count += $val['bbsx']['num'];
		$bbsx_total_bet += $val['bbsx']['BetYC'];
		$bbsx_total_pcbet += $val['bbsx']['BetPC'] + $val['bbsx']['BetYC'];
		$bbsx_total_betall += $val['bbsx']['BetAll'];
		if($atype == 'u_a'){
			$bbsx_total_dljs+=$val['bbsx']['s_hdljs'];
			$bbsx_total_zdjs+=$val['bbsx']['s_hzdjs'];
			$bbsx_total_gdjs+=$val['bbsx']['s_hgdjs'];
			$bbsx_total_dljg+=$val['bbsx']['s_hdljg']+0;//总代理商结果
			$bbsx_total_ddljs=$val['bbsx']['s_hdljs']+0;
			$bbsx_total_dzdjs=$val['bbsx']['s_hzdjs']+0;
			$bbsx_total_dgdjs=$val['bbsx']['s_hgdjs']+0;
			$bbsx_total_ddljg=$val['bbsx']['s_hdljg']+0;//总代理商结果
			$bbsx_total_dacczc=$bbsx_total_dgdjs;
			$bbsx_total_acczc+=$bbsx_total_dacczc;
		}elseif($atype == 'a_t'){
			$bbsx_total_dljs+=$val['bbsx']['u_adljs'];
			$bbsx_total_zdjs+=$val['bbsx']['u_azdjs'];
			$bbsx_total_gdjs+=$val['bbsx']['u_agdjs'];
			$bbsx_total_dljg+=$val['bbsx']['u_adljg']+0;//总代理商结果
			$bbsx_total_ddljs=$val['bbsx']['u_adljs']+0;
			$bbsx_total_dzdjs=$val['bbsx']['u_azdjs']+0;
			$bbsx_total_dgdjs=$val['bbsx']['u_agdjs']+0;
			$bbsx_total_ddljg=$val['bbsx']['u_adljg']+0;//总代理商结果
			$bbsx_total_dacczc=$bbsx_total_dzdjs-$bbsx_total_dgdjs;
			$bbsx_total_acczc+=$bbsx_total_dacczc;
		}elseif($_GET['atype'] == 'a_t'){
			$bbsx_total_dljs+=$val['bbsx']['a_tdljs'];
			$bbsx_total_zdjs+=$val['bbsx']['a_tzdjs'];
			$bbsx_total_gdjs+=$val['bbsx']['a_tgdjs'];
			$bbsx_total_dljg+=$val['bbsx']['a_tdljg']+0;//总代理商结果
			$bbsx_total_ddljs=$val['bbsx']['a_tdljs']+0;
			$bbsx_total_dzdjs=$val['bbsx']['a_tzdjs']+0;
			$bbsx_total_dgdjs=$val['bbsx']['a_tgdjs']+0;
			$bbsx_total_ddljg=$val['bbsx']['a_tdljg']+0;//总代理商结果
			$bbsx_total_dacczc=$bbsx_total_ddljs-$bbsx_total_dzdjs;
			$bbsx_total_acczc+=$bbsx_total_dacczc;
		}elseif($_GET['atype'] == 'user'){
			$bbsx_total_dljs+=$val['bbsx']['userdljs'];
			$bbsx_total_zdjs+=$val['bbsx']['userzdjs'];
			$bbsx_total_gdjs+=$val['bbsx']['usergdjs'];
			$bbsx_total_dljg+=$val['bbsx']['userdljg']+0;//总代理商结果
			$bbsx_total_ddljs=$val['bbsx']['userdljs']+0;
			$bbsx_total_dzdjs=$val['bbsx']['userzdjs']+0;
			$bbsx_total_dgdjs=$val['bbsx']['usergdjs']+0;
			$bbsx_total_ddljg=$val['bbsx']['userdljg']+0;//总代理商结果
			$bbsx_total_dacczc=$bbsx_total_ddljg;
			$bbsx_total_acczc+=$bbsx_total_dacczc;
		}
	if ($_GET['atype'] == 'user') {
         $report_url = "../note/video.php?username=".$val['agent_user']."&date_start=".substr($date_start,0,10)."&date_end=".substr($date_end,0,10)."&gtype=bbin";
    }else{
         $report_url = './report_result.php?uid='.$val['id'].'&is_res='.$is_res.'&date_start='.substr($date_start,0,10).'&date_end='.substr($date_end,0,10).'&sp='.$_GET['sp'].'&cp='.$_GET['cp'].'&mg='.$_GET['mg'].'&ag='.$_GET['ag'].'&bbsx='.$_GET['bbsx'].'&bbdz='.$_GET['bbdz'].'&bbty='.$_GET['bbty'].'&bbcp='.$_GET['bbcp'].'&ct='.$_GET['ct'].'&lebo='.$_GET['lebo'].'&mgdz='.$_GET['mgdz'].'&og='.$_GET['og'].'&atype='.$atype;
    }
   ?>
   <?php if($val['bbsx']['num'] > 0){?>
	<tr class="m_rig" align="left">
			<td align="center" nowrap="nowrap"><?=$val['agent_user']?>(<?=$val['agent_name']?>)</td>
			<td><?=$val['bbsx']['num']+0?></td>
			<td align="right"><a href="<?=$report_url?>" class="a_001"><?=number_format($val['bbsx']['BetAll'],4)?></a></td>
			<td><?=number_format($val['bbsx']['BetYC'],4)?></td>
			<td><?=number_format($val['bbsx']['BetPC'] + $val['bbsx']['BetYC'],4)?></td>
			<td><?=number_format(0-$val['bbsx']['BetPC'],4)?></td>
			<td>0.00</td>
			<td><?=number_format(0-$val['bbsx']['BetPC'],4)?></td>
			<td><?=number_format(0-$val['bbsx']['BetPC'],4)?></td>
			<td><?=number_format($bbsx_total_ddljs,4)?></td>
	        <td><?=number_format($bbsx_total_ddljg,4)?></td>
	        <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($bbsx_total_dzdjs,4)?></td>
	        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($bbsx_total_dgdjs,4)?></td>
	        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='u_a'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
			<td style="display:none">0.00</td>
	</tr>
 <?php }?>
 <?php }?>
	</tbody>
	<tfoot>
	<?php if($bbsx_total_count > 0){?>
	<tr class="m_rig">
		<td align="center">總計</td>
		<td><?=$bbsx_total_count?></td>
		<td><?=number_format($bbsx_total_betall,4)?></td>
		<td><?=number_format($bbsx_total_bet,4)?></td>
		<td><?=number_format($bbsx_total_pcbet,4)?></td>
        <td><?=number_format($bbsx_total_bet-$bbsx_total_pcbet,4)?></td>
		<td>0.00</td>
		<td><?=number_format($bbsx_total_bet-$bbsx_total_pcbet,4)?></td>
		<td><?=number_format($bbsx_total_bet-$bbsx_total_pcbet,4)?></td>
		<td><?=number_format($bbsx_total_dljs,4)?></td>
        <td><?=number_format($bbsx_total_dljg,4)?></td>
        <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($bbsx_total_zdjs,4)?></td>
        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($bbsx_total_gdjs,4)?></td>
        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='u_a'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
		<td style="display:none">0.00</td>
	</tr>
	<?php }else{?>
	 <tr class="m_rig">
	 <td colspan="14" align="center">暂无数据</td>
	 </tr>
	 <?php }?>
	</tfoot>
</table>
</div>
<?php }?>


<!-- bbin机率 -->
<?php  if ($_GET['bbdz'] == '12') {
?>
<div>
<table border="0" cellpadding="0" cellspacing="0" id="m_tab4" class="m_tab tablesorter">
	<thead>
	<tr class="m_title">
		<td colspan="15" id="zr_panel" style="text-align:left;background:#FFFFFF;color:#333131">
        <div style="float:left">BBIN机率 日期：<?=$date_start?>~ <?=$date_end?> 投注類型：全部 投注方式：全部</div>
         <div style="float:right">"<font color="#ff0000">紅</font>"色代表业主亏损，"<font color="#000">黑</font>"色代表业主盈利</div></td>
	</tr>
	<tr class="m_title">
	  <th width="100" nowrap="nowrap" rowspan="2" style="font-size:12px;"><span><?=$table_title?>名稱</span></th>
	  <th width="50" rowspan="2" style="font-size:12px;"><span>筆數</span></th>
	  <th id="target4" width="80" rowspan="2" style="font-size:12px;"><span>下注金額</span></th>
	  <th width="80" rowspan="2" style="font-size:12px;"><span>有效金額</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span>總派彩</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>占成</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>退水</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>損益</span></th>
	  <td colspan="7" style="font-size:12px;background-color: #fecee4;color:#122858"><span>各層交收</span></td>
    </tr>
	<tr class="m_title">
		  <?php foreach ($level_a as $key => $val) {
        ?>
          <th class="header"><span><?=$val['name']?></span></th>
        <?php }?>
        <th style="display:none"><span>後台</span></th>
	</tr>
	</thead>
	<tbody>
	
	<?php foreach ($agent_sh as $key => $val) {
		$bbdz_total_count += $val['bbdz']['num'];
		$bbdz_total_bet += $val['bbdz']['BetYC'];
		$bbdz_total_pcbet += $val['bbdz']['BetPC'] + $val['bbdz']['BetYC'];
		$bbdz_total_betall += $val['bbdz']['BetAll'];
		if($atype == 'u_a'){
			$bbdz_total_dljs+=$val['bbdz']['s_hdljs'];
			$bbdz_total_zdjs+=$val['bbdz']['s_hzdjs'];
			$bbdz_total_gdjs+=$val['bbdz']['s_hgdjs'];
			$bbdz_total_dljg+=$val['bbdz']['s_hdljg']+0;//总代理商结果
			$bbdz_total_ddljs=$val['bbdz']['s_hdljs']+0;
			$bbdz_total_dzdjs=$val['bbdz']['s_hzdjs']+0;
			$bbdz_total_dgdjs=$val['bbdz']['s_hgdjs']+0;
			$bbdz_total_ddljg=$val['bbdz']['s_hdljg']+0;//总代理商结果
			$bbdz_total_dacczc=$bbdz_total_dgdjs;
			$bbdz_total_acczc+=$bbdz_total_dacczc;
		}elseif($atype == 'a_t'){
			$bbdz_total_dljs+=$val['bbdz']['u_adljs'];
			$bbdz_total_zdjs+=$val['bbdz']['u_azdjs'];
			$bbdz_total_gdjs+=$val['bbdz']['u_agdjs'];
			$bbdz_total_dljg+=$val['bbdz']['u_adljg']+0;//总代理商结果
			$bbdz_total_ddljs=$val['bbdz']['u_adljs']+0;
			$bbdz_total_dzdjs=$val['bbdz']['u_azdjs']+0;
			$bbdz_total_dgdjs=$val['bbdz']['u_agdjs']+0;
			$bbdz_total_ddljg=$val['bbdz']['u_adljg']+0;//总代理商结果
			$bbdz_total_dacczc=$bbdz_total_dzdjs-$bbdz_total_dgdjs;
			$bbdz_total_acczc+=$bbdz_total_dacczc;
		}elseif($_GET['atype'] == 'a_t'){
			$bbdz_total_dljs+=$val['bbdz']['a_tdljs'];
			$bbdz_total_zdjs+=$val['bbdz']['a_tzdjs'];
			$bbdz_total_gdjs+=$val['bbdz']['a_tgdjs'];
			$bbdz_total_dljg+=$val['bbdz']['a_tdljg']+0;//总代理商结果
			$bbdz_total_ddljs=$val['bbdz']['a_tdljs']+0;
			$bbdz_total_dzdjs=$val['bbdz']['a_tzdjs']+0;
			$bbdz_total_dgdjs=$val['bbdz']['a_tgdjs']+0;
			$bbdz_total_ddljg=$val['bbdz']['a_tdljg']+0;//总代理商结果
			$bbdz_total_dacczc=$bbdz_total_ddljs-$bbdz_total_dzdjs;
			$bbdz_total_acczc+=$bbdz_total_dacczc;
		}elseif($_GET['atype'] == 'user'){
			$bbdz_total_dljs+=$val['bbdz']['userdljs'];
			$bbdz_total_zdjs+=$val['bbdz']['userzdjs'];
			$bbdz_total_gdjs+=$val['bbdz']['usergdjs'];
			$bbdz_total_dljg+=$val['bbdz']['userdljg']+0;//总代理商结果
			$bbdz_total_ddljs=$val['bbdz']['userdljs']+0;
			$bbdz_total_dzdjs=$val['bbdz']['userzdjs']+0;
			$bbdz_total_dgdjs=$val['bbdz']['usergdjs']+0;
			$bbdz_total_ddljg=$val['bbdz']['userdljg']+0;//总代理商结果
			$bbdz_total_dacczc=$bbdz_total_ddljg;
			$bbdz_total_acczc+=$bbdz_total_dacczc;
		}
	if ($_GET['atype'] == 'user') {
         $report_url = "../note/video.php?username=".$val['agent_user']."&date_start=".substr($date_start,0,10)."&date_end=".substr($date_end,0,10)."&gtype=bbin";
    }else{
         $report_url = './report_result.php?uid='.$val['id'].'&is_res='.$is_res.'&date_start='.substr($date_start,0,10).'&date_end='.substr($date_end,0,10).'&sp='.$_GET['sp'].'&cp='.$_GET['cp'].'&mg='.$_GET['mg'].'&ag='.$_GET['ag'].'&bbsx='.$_GET['bbsx'].'&bbdz='.$_GET['bbdz'].'&bbty='.$_GET['bbty'].'&bbcp='.$_GET['bbcp'].'&ct='.$_GET['ct'].'&lebo='.$_GET['lebo'].'&mgdz='.$_GET['mgdz'].'&og='.$_GET['og'].'&atype='.$atype;
    }
   ?>
   <?php if($val['bbdz']['num'] > 0){?>
	<tr class="m_rig" align="left">
			<td align="center" nowrap="nowrap"><?=$val['agent_user']?>(<?=$val['agent_name']?>)</td>
			<td><?=$val['bbdz']['num']+0?></td>
			<td align="right"><a href="<?=$report_url?>" class="a_001"><?=number_format($val['bbdz']['BetAll'],4)?></a></td>
			<td><?=number_format($val['bbdz']['BetYC'],4)?></td>
			<td><?=number_format($val['bbdz']['BetPC'] + $val['bbdz']['BetYC'],4)?></td>
			<td><?=number_format(0-$val['bbdz']['BetPC'],4)?></td>
			<td>0.00</td>
			<td><?=number_format(0-$val['bbdz']['BetPC'],4)?></td>
			<td><?=number_format(0-$val['bbdz']['BetPC'],4)?></td>
			<td><?=number_format($bbdz_total_ddljs,4)?></td>
	        <td><?=number_format($bbdz_total_ddljg,4)?></td>
	        <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($bbdz_total_dzdjs,4)?></td>
	        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($bbdz_total_dgdjs,4)?></td>
	        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='u_a'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
			<td style="display:none">0.00</td>
	</tr>
 <?php }?>
 <?php }?>
	</tbody>
	<tfoot>
	<?php if($bbdz_total_count > 0){?>
	<tr class="m_rig">
		<td align="center">總計</td>
		<td><?=$bbdz_total_count?></td>
		<td><?=number_format($bbdz_total_betall,4)?></td>
		<td><?=number_format($bbdz_total_bet,4)?></td>
		<td><?=number_format($bbdz_total_pcbet,4)?></td>
        <td><?=number_format($bbdz_total_bet-$bbdz_total_pcbet,4)?></td>
		<td>0.00</td>
		<td><?=number_format($bbdz_total_bet-$bbdz_total_pcbet,4)?></td>
		<td><?=number_format($bbdz_total_bet-$bbdz_total_pcbet,4)?></td>
		<td><?=number_format($bbdz_total_dljs,4)?></td>
        <td><?=number_format($bbdz_total_dljg,4)?></td>
        <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($bbdz_total_zdjs,4)?></td>
        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($bbdz_total_gdjs,4)?></td>
        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='u_a'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
		<td style="display:none">0.00</td>
	</tr>
	<?php }else{?>
	 <tr class="m_rig">
	 <td colspan="14" align="center">暂无数据</td>
	 </tr>
	 <?php }?>
	</tfoot>
</table>
</div>
<?php }?>



<!-- bbin球类 -->
<?php  if ($_GET['bbty'] == '13') {
?>
<div>
<table border="0" cellpadding="0" cellspacing="0" id="m_tab4" class="m_tab tablesorter">
	<thead>
	<tr class="m_title">
		<td colspan="15" id="zr_panel" style="text-align:left;background:#FFFFFF;color:#333131">
        <div style="float:left">BBIN球类 日期：<?=$date_start?>~ <?=$date_end?> 投注類型：全部 投注方式：全部</div>
         <div style="float:right">"<font color="#ff0000">紅</font>"色代表业主亏损，"<font color="#000">黑</font>"色代表业主盈利</div></td>
	</tr>
	<tr class="m_title">
	  <th width="100" nowrap="nowrap" rowspan="2" style="font-size:12px;"><span><?=$table_title?>名稱</span></th>
	  <th width="50" rowspan="2" style="font-size:12px;"><span>筆數</span></th>
	  <th id="target4" width="80" rowspan="2" style="font-size:12px;"><span>下注金額</span></th>
	  <th width="80" rowspan="2" style="font-size:12px;"><span>有效金額</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span>總派彩</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>占成</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>退水</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>損益</span></th>
	  <td colspan="7" style="font-size:12px;background-color: #fecee4;color:#122858"><span>各層交收</span></td>
    </tr>
	<tr class="m_title">
		  <?php foreach ($level_a as $key => $val) {
        ?>
          <th class="header"><span><?=$val['name']?></span></th>
        <?php }?>
        <th style="display:none"><span>後台</span></th>
	</tr>
	</thead>
	<tbody>
	
	<?php foreach ($agent_sh as $key => $val) {
		$bbty_total_count += $val['bbty']['num'];
		$bbty_total_bet += $val['bbty']['BetYC'];
		$bbty_total_pcbet += $val['bbty']['BetPC'] + $val['bbty']['BetYC'];
		$bbty_total_betall += $val['bbty']['BetAll'];
		if($atype == 'u_a'){
			$bbty_total_dljs+=$val['bbty']['s_hdljs'];
			$bbty_total_zdjs+=$val['bbty']['s_hzdjs'];
			$bbty_total_gdjs+=$val['bbty']['s_hgdjs'];
			$bbty_total_dljg+=$val['bbty']['s_hdljg']+0;//总代理商结果
			$bbty_total_ddljs=$val['bbty']['s_hdljs']+0;
			$bbty_total_dzdjs=$val['bbty']['s_hzdjs']+0;
			$bbty_total_dgdjs=$val['bbty']['s_hgdjs']+0;
			$bbty_total_ddljg=$val['bbty']['s_hdljg']+0;//总代理商结果
			$bbty_total_dacczc=$bbty_total_dgdjs;
			$bbty_total_acczc+=$bbty_total_dacczc;
		}elseif($atype == 'a_t'){
			$bbty_total_dljs+=$val['bbty']['u_adljs'];
			$bbty_total_zdjs+=$val['bbty']['u_azdjs'];
			$bbty_total_gdjs+=$val['bbty']['u_agdjs'];
			$bbty_total_dljg+=$val['bbty']['u_adljg']+0;//总代理商结果
			$bbty_total_ddljs=$val['bbty']['u_adljs']+0;
			$bbty_total_dzdjs=$val['bbty']['u_azdjs']+0;
			$bbty_total_dgdjs=$val['bbty']['u_agdjs']+0;
			$bbty_total_ddljg=$val['bbty']['u_adljg']+0;//总代理商结果
			$bbty_total_dacczc=$bbty_total_dzdjs-$bbty_total_dgdjs;
			$bbty_total_acczc+=$bbty_total_dacczc;
		}elseif($_GET['atype'] == 'a_t'){
			$bbty_total_dljs+=$val['bbty']['a_tdljs'];
			$bbty_total_zdjs+=$val['bbty']['a_tzdjs'];
			$bbty_total_gdjs+=$val['bbty']['a_tgdjs'];
			$bbty_total_dljg+=$val['bbty']['a_tdljg']+0;//总代理商结果
			$bbty_total_ddljs=$val['bbty']['a_tdljs']+0;
			$bbty_total_dzdjs=$val['bbty']['a_tzdjs']+0;
			$bbty_total_dgdjs=$val['bbty']['a_tgdjs']+0;
			$bbty_total_ddljg=$val['bbty']['a_tdljg']+0;//总代理商结果
			$bbty_total_dacczc=$bbty_total_ddljs-$bbty_total_dzdjs;
			$bbty_total_acczc+=$bbty_total_dacczc;
		}elseif($_GET['atype'] == 'user'){
			$bbty_total_dljs+=$val['bbty']['userdljs'];
			$bbty_total_zdjs+=$val['bbty']['userzdjs'];
			$bbty_total_gdjs+=$val['bbty']['usergdjs'];
			$bbty_total_dljg+=$val['bbty']['userdljg']+0;//总代理商结果
			$bbty_total_ddljs=$val['bbty']['userdljs']+0;
			$bbty_total_dzdjs=$val['bbty']['userzdjs']+0;
			$bbty_total_dgdjs=$val['bbty']['usergdjs']+0;
			$bbty_total_ddljg=$val['bbty']['userdljg']+0;//总代理商结果
			$bbty_total_dacczc=$bbty_total_ddljg;
			$bbty_total_acczc+=$bbty_total_dacczc;
		}
	if ($_GET['atype'] == 'user') {
         $report_url = "../note/video.php?username=".$val['agent_user']."&date_start=".substr($date_start,0,10)."&date_end=".substr($date_end,0,10)."&gtype=bbin";
    }else{
         $report_url = './report_result.php?uid='.$val['id'].'&is_res='.$is_res.'&date_start='.substr($date_start,0,10).'&date_end='.substr($date_end,0,10).'&sp='.$_GET['sp'].'&cp='.$_GET['cp'].'&mg='.$_GET['mg'].'&ag='.$_GET['ag'].'&bbsx='.$_GET['bbsx'].'&bbdz='.$_GET['bbdz'].'&bbty='.$_GET['bbty'].'&bbcp='.$_GET['bbcp'].'&ct='.$_GET['ct'].'&lebo='.$_GET['lebo'].'&mgdz='.$_GET['mgdz'].'&og='.$_GET['og'].'&atype='.$atype;
    }
   ?>
   <?php if($val['bbty']['num'] > 0){?>
	<tr class="m_rig" align="left">
			<td align="center" nowrap="nowrap"><?=$val['agent_user']?>(<?=$val['agent_name']?>)</td>
			<td><?=$val['bbty']['num']+0?></td>
			<td align="right"><a href="<?=$report_url?>" class="a_001"><?=number_format($val['bbty']['BetAll'],4)?></a></td>
			<td><?=number_format($val['bbty']['BetYC'],4)?></td>
			<td><?=number_format($val['bbty']['BetPC'] + $val['bbty']['BetYC'],4)?></td>
			<td><?=number_format(0-$val['bbty']['BetPC'],4)?></td>
			<td>0.00</td>
			<td><?=number_format(0-$val['bbty']['BetPC'],4)?></td>
			<td><?=number_format(0-$val['bbty']['BetPC'],4)?></td>
			<td><?=number_format($bbty_total_ddljs,4)?></td>
	        <td><?=number_format($bbty_total_ddljg,4)?></td>
	        <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($bbty_total_dzdjs,4)?></td>
	        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($bbty_total_dgdjs,4)?></td>
	        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='u_a'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
			<td style="display:none">0.00</td>
	</tr>
 <?php }?>
 <?php }?>
	</tbody>
	<tfoot>
	<?php if($bbty_total_count > 0){?>
	<tr class="m_rig">
		<td align="center">總計</td>
		<td><?=$bbty_total_count?></td>
		<td><?=number_format($bbty_total_betall,4)?></td>
		<td><?=number_format($bbty_total_bet,4)?></td>
		<td><?=number_format($bbty_total_pcbet,4)?></td>
        <td><?=number_format($bbty_total_bet-$bbty_total_pcbet,4)?></td>
		<td>0.00</td>
		<td><?=number_format($bbty_total_bet-$bbty_total_pcbet,4)?></td>
		<td><?=number_format($bbty_total_bet-$bbty_total_pcbet,4)?></td>
		<td><?=number_format($bbty_total_dljs,4)?></td>
        <td><?=number_format($bbty_total_dljg,4)?></td>
        <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($bbty_total_zdjs,4)?></td>
        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($bbty_total_gdjs,4)?></td>
        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='u_a'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
		<td style="display:none">0.00</td>
	</tr>
	<?php }else{?>
	 <tr class="m_rig">
	 <td colspan="14" align="center">暂无数据</td>
	 </tr>
	 <?php }?>
	</tfoot>
</table>
</div>
<?php }?>


<!-- bbin彩票 -->
<?php  if ($_GET['bbcp'] == '14') {
?>
<div>
<table border="0" cellpadding="0" cellspacing="0" id="m_tab4" class="m_tab tablesorter">
	<thead>
	<tr class="m_title">
		<td colspan="15" id="zr_panel" style="text-align:left;background:#FFFFFF;color:#333131">
        <div style="float:left">BBIN彩票 日期：<?=$date_start?>~ <?=$date_end?> 投注類型：全部 投注方式：全部</div>
         <div style="float:right">"<font color="#ff0000">紅</font>"色代表业主亏损，"<font color="#000">黑</font>"色代表业主盈利</div></td>
	</tr>
	<tr class="m_title">
	  <th width="100" nowrap="nowrap" rowspan="2" style="font-size:12px;"><span><?=$table_title?>名稱</span></th>
	  <th width="50" rowspan="2" style="font-size:12px;"><span>筆數</span></th>
	  <th id="target4" width="80" rowspan="2" style="font-size:12px;"><span>下注金額</span></th>
	  <th width="80" rowspan="2" style="font-size:12px;"><span>有效金額</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span>總派彩</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>占成</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>退水</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>損益</span></th>
	  <td colspan="7" style="font-size:12px;background-color: #fecee4;color:#122858"><span>各層交收</span></td>
    </tr>
	<tr class="m_title">
		  <?php foreach ($level_a as $key => $val) {
        ?>
          <th class="header"><span><?=$val['name']?></span></th>
        <?php }?>
        <th style="display:none"><span>後台</span></th>
	</tr>
	</thead>
	<tbody>
	
	<?php foreach ($agent_sh as $key => $val) {
		$bbcp_total_count += $val['bbcp']['num'];
		$bbcp_total_bet += $val['bbcp']['BetYC'];
		$bbcp_total_pcbet += $val['bbcp']['BetPC'] + $val['bbcp']['BetYC'];
		$bbcp_total_betall += $val['bbcp']['BetAll'];
		if($atype == 'u_a'){
			$bbcp_total_dljs+=$val['bbcp']['s_hdljs'];
			$bbcp_total_zdjs+=$val['bbcp']['s_hzdjs'];
			$bbcp_total_gdjs+=$val['bbcp']['s_hgdjs'];
			$bbcp_total_dljg+=$val['bbcp']['s_hdljg']+0;//总代理商结果
			$bbcp_total_ddljs=$val['bbcp']['s_hdljs']+0;
			$bbcp_total_dzdjs=$val['bbcp']['s_hzdjs']+0;
			$bbcp_total_dgdjs=$val['bbcp']['s_hgdjs']+0;
			$bbcp_total_ddljg=$val['bbcp']['s_hdljg']+0;//总代理商结果
			$bbcp_total_dacczc=$bbcp_total_dgdjs;
			$bbcp_total_acczc+=$bbcp_total_dacczc;
		}elseif($atype == 'a_t'){
			$bbcp_total_dljs+=$val['bbcp']['u_adljs'];
			$bbcp_total_zdjs+=$val['bbcp']['u_azdjs'];
			$bbcp_total_gdjs+=$val['bbcp']['u_agdjs'];
			$bbcp_total_dljg+=$val['bbcp']['u_adljg']+0;//总代理商结果
			$bbcp_total_ddljs=$val['bbcp']['u_adljs']+0;
			$bbcp_total_dzdjs=$val['bbcp']['u_azdjs']+0;
			$bbcp_total_dgdjs=$val['bbcp']['u_agdjs']+0;
			$bbcp_total_ddljg=$val['bbcp']['u_adljg']+0;//总代理商结果
			$bbcp_total_dacczc=$bbcp_total_dzdjs-$bbcp_total_dgdjs;
			$bbcp_total_acczc+=$bbcp_total_dacczc;
		}elseif($_GET['atype'] == 'a_t'){
			$bbcp_total_dljs+=$val['bbcp']['a_tdljs'];
			$bbcp_total_zdjs+=$val['bbcp']['a_tzdjs'];
			$bbcp_total_gdjs+=$val['bbcp']['a_tgdjs'];
			$bbcp_total_dljg+=$val['bbcp']['a_tdljg']+0;//总代理商结果
			$bbcp_total_ddljs=$val['bbcp']['a_tdljs']+0;
			$bbcp_total_dzdjs=$val['bbcp']['a_tzdjs']+0;
			$bbcp_total_dgdjs=$val['bbcp']['a_tgdjs']+0;
			$bbcp_total_ddljg=$val['bbcp']['a_tdljg']+0;//总代理商结果
			$bbcp_total_dacczc=$bbcp_total_ddljs-$bbcp_total_dzdjs;
			$bbcp_total_acczc+=$bbcp_total_dacczc;
		}elseif($_GET['atype'] == 'user'){
			$bbcp_total_dljs+=$val['bbcp']['userdljs'];
			$bbcp_total_zdjs+=$val['bbcp']['userzdjs'];
			$bbcp_total_gdjs+=$val['bbcp']['usergdjs'];
			$bbcp_total_dljg+=$val['bbcp']['userdljg']+0;//总代理商结果
			$bbcp_total_ddljs=$val['bbcp']['userdljs']+0;
			$bbcp_total_dzdjs=$val['bbcp']['userzdjs']+0;
			$bbcp_total_dgdjs=$val['bbcp']['usergdjs']+0;
			$bbcp_total_ddljg=$val['bbcp']['userdljg']+0;//总代理商结果
			$bbcp_total_dacczc=$bbcp_total_ddljg;
			$bbcp_total_acczc+=$bbcp_total_dacczc;
		}
	if ($_GET['atype'] == 'user') {
         $report_url = "../note/video.php?username=".$val['agent_user']."&date_start=".substr($date_start,0,10)."&date_end=".substr($date_end,0,10)."&gtype=bbin";
    }else{
         $report_url = './report_result.php?uid='.$val['id'].'&is_res='.$is_res.'&date_start='.substr($date_start,0,10).'&date_end='.substr($date_end,0,10).'&sp='.$_GET['sp'].'&cp='.$_GET['cp'].'&mg='.$_GET['mg'].'&ag='.$_GET['ag'].'&bbsx='.$_GET['bbsx'].'&bbdz='.$_GET['bbdz'].'&bbty='.$_GET['bbty'].'&bbcp='.$_GET['bbcp'].'&ct='.$_GET['ct'].'&lebo='.$_GET['lebo'].'&mgdz='.$_GET['mgdz'].'&og='.$_GET['og'].'&atype='.$atype;
    }
   ?>
   <?php if($val['bbcp']['num'] > 0){?>
	<tr class="m_rig" align="left">
			<td align="center" nowrap="nowrap"><?=$val['agent_user']?>(<?=$val['agent_name']?>)</td>
			<td><?=$val['bbcp']['num']+0?></td>
			<td align="right"><a href="<?=$report_url?>" class="a_001"><?=number_format($val['bbcp']['BetAll'],4)?></a></td>
			<td><?=number_format($val['bbcp']['BetYC'],4)?></td>
			<td><?=number_format($val['bbcp']['BetPC'] + $val['bbcp']['BetYC'],4)?></td>
			<td><?=number_format(0-$val['bbcp']['BetPC'],4)?></td>
			<td>0.00</td>
			<td><?=number_format(0-$val['bbcp']['BetPC'],4)?></td>
			<td><?=number_format(0-$val['bbcp']['BetPC'],4)?></td>
			<td><?=number_format($bbcp_total_ddljs,4)?></td>
	        <td><?=number_format($bbcp_total_ddljg,4)?></td>
	        <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($bbcp_total_dzdjs,4)?></td>
	        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($bbcp_total_dgdjs,4)?></td>
	        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='u_a'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
			<td style="display:none">0.00</td>
	</tr>
 <?php }?>
 <?php }?>
	</tbody>
	<tfoot>
	<?php if($bbcp_total_count > 0){?>
	<tr class="m_rig">
		<td align="center">總計</td>
		<td><?=$bbcp_total_count?></td>
		<td><?=number_format($bbcp_total_betall,4)?></td>
		<td><?=number_format($bbcp_total_bet,4)?></td>
		<td><?=number_format($bbcp_total_pcbet,4)?></td>
        <td><?=number_format($bbcp_total_bet-$bbcp_total_pcbet,4)?></td>
		<td>0.00</td>
		<td><?=number_format($bbcp_total_bet-$bbcp_total_pcbet,4)?></td>
		<td><?=number_format($bbcp_total_bet-$bbcp_total_pcbet,4)?></td>
		<td><?=number_format($bbcp_total_dljs,4)?></td>
        <td><?=number_format($bbcp_total_dljg,4)?></td>
        <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($bbcp_total_zdjs,4)?></td>
        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($bbcp_total_gdjs,4)?></td>
        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='u_a'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
		<td style="display:none">0.00</td>
	</tr>
	<?php }else{?>
	 <tr class="m_rig">
	 <td colspan="14" align="center">暂无数据</td>
	 </tr>
	 <?php }?>
	</tfoot>
</table>
</div>
<?php }?>



<!-- ag视讯 -->
<?php  if ($_GET['ag'] == '10') {
?>
<div>
<table border="0" cellpadding="0" cellspacing="0" id="m_tab4" class="m_tab tablesorter">
	<thead>
	<tr class="m_title">
		<td colspan="15" id="zr_panel" style="text-align:left;background:#FFFFFF;color:#333131">
        <div style="float:left">AG视讯 日期：<?=$date_start?>~ <?=$date_end?> 投注類型：全部 投注方式：全部</div>
         <div style="float:right">"<font color="#ff0000">紅</font>"色代表业主亏损，"<font color="#000">黑</font>"色代表业主盈利</div></td>
	</tr>
	<tr class="m_title">
	  <th width="100" nowrap="nowrap" rowspan="2" style="font-size:12px;"><span><?=$table_title?>名稱</span></th>
	  <th width="50" rowspan="2" style="font-size:12px;"><span>筆數</span></th>
	  <th id="target4" width="80" rowspan="2" style="font-size:12px;"><span>下注金額</span></th>
	  <th width="80" rowspan="2" style="font-size:12px;"><span>有效金額</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span>總派彩</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>占成</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>退水</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>損益</span></th>
	  <td colspan="7" style="font-size:12px;background-color: #fecee4;color:#122858"><span>各層交收</span></td>
    </tr>
	<tr class="m_title">
		  <?php foreach ($level_a as $key => $val) {
        ?>
          <th class="header"><span><?=$val['name']?></span></th>
        <?php }?>
        <th style="display:none"><span>後台</span></th>
	</tr>
	</thead>
	<tbody>
	
	<?php foreach ($agent_sh as $key => $val) {
		$ag_total_count += $val['ag']['num'];
		$ag_total_bet += $val['ag']['BetYC'];
		$ag_total_pcbet += $val['ag']['BetPC'] + $val['ag']['BetYC'];
		$ag_total_betall += $val['ag']['BetAll'];
		if($atype == 'u_a'){
			$ag_total_dljs+=$val['ag']['s_hdljs'];
			$ag_total_zdjs+=$val['ag']['s_hzdjs'];
			$ag_total_gdjs+=$val['ag']['s_hgdjs'];
			$ag_total_dljg+=$val['ag']['s_hdljg']+0;//总代理商结果
			$ag_total_ddljs=$val['ag']['s_hdljs']+0;
			$ag_total_dzdjs=$val['ag']['s_hzdjs']+0;
			$ag_total_dgdjs=$val['ag']['s_hgdjs']+0;
			$ag_total_ddljg=$val['ag']['s_hdljg']+0;//总代理商结果
			$ag_total_dacczc=$ag_total_dgdjs;
			$ag_total_acczc+=$ag_total_dacczc;
		}elseif($atype == 'a_t'){
			$ag_total_dljs+=$val['ag']['u_adljs'];
			$ag_total_zdjs+=$val['ag']['u_azdjs'];
			$ag_total_gdjs+=$val['ag']['u_agdjs'];
			$ag_total_dljg+=$val['ag']['u_adljg']+0;//总代理商结果
			$ag_total_ddljs=$val['ag']['u_adljs']+0;
			$ag_total_dzdjs=$val['ag']['u_azdjs']+0;
			$ag_total_dgdjs=$val['ag']['u_agdjs']+0;
			$ag_total_ddljg=$val['ag']['u_adljg']+0;//总代理商结果
			$ag_total_dacczc=$ag_total_dzdjs-$ag_total_dgdjs;
			$ag_total_acczc+=$ag_total_dacczc;
		}elseif($_GET['atype'] == 'a_t'){
			$ag_total_dljs+=$val['ag']['a_tdljs'];
			$ag_total_zdjs+=$val['ag']['a_tzdjs'];
			$ag_total_gdjs+=$val['ag']['a_tgdjs'];
			$ag_total_dljg+=$val['ag']['a_tdljg']+0;//总代理商结果
			$ag_total_ddljs=$val['ag']['a_tdljs']+0;
			$ag_total_dzdjs=$val['ag']['a_tzdjs']+0;
			$ag_total_dgdjs=$val['ag']['a_tgdjs']+0;
			$ag_total_ddljg=$val['ag']['a_tdljg']+0;//总代理商结果
			$ag_total_dacczc=$ag_total_ddljs-$ag_total_dzdjs;
			$ag_total_acczc+=$ag_total_dacczc;
		}elseif($_GET['atype'] == 'user'){
			$ag_total_dljs+=$val['ag']['userdljs'];
			$ag_total_zdjs+=$val['ag']['userzdjs'];
			$ag_total_gdjs+=$val['ag']['usergdjs'];
			$ag_total_dljg+=$val['ag']['userdljg']+0;//总代理商结果
			$ag_total_ddljs=$val['ag']['userdljs']+0;
			$ag_total_dzdjs=$val['ag']['userzdjs']+0;
			$ag_total_dgdjs=$val['ag']['usergdjs']+0;
			$ag_total_ddljg=$val['ag']['userdljg']+0;//总代理商结果
			$ag_total_dacczc=$ag_total_ddljg;
			$ag_total_acczc+=$ag_total_dacczc;
		}
	if ($_GET['atype'] == 'user') {
         $report_url = "../note/video.php?username=".$val['agent_user']."&date_start=".substr($date_start,0,10)."&date_end=".substr($date_end,0,10)."&gtype=ag";
    }else{
         $report_url = './report_result.php?uid='.$val['id'].'&is_res='.$is_res.'&date_start='.substr($date_start,0,10).'&date_end='.substr($date_end,0,10).'&sp='.$_GET['sp'].'&cp='.$_GET['cp'].'&mg='.$_GET['mg'].'&ag='.$_GET['ag'].'&bbsx='.$_GET['bbsx'].'&bbdz='.$_GET['bbdz'].'&bbty='.$_GET['bbty'].'&bbcp='.$_GET['bbcp'].'&ct='.$_GET['ct'].'&lebo='.$_GET['lebo'].'&mgdz='.$_GET['mgdz'].'&og='.$_GET['og'].'&atype='.$atype;
    }
   ?>
   <?php if($val['ag']['num'] > 0){?>
	<tr class="m_rig" align="left">
			<td align="center" nowrap="nowrap"><?=$val['agent_user']?>(<?=$val['agent_name']?>)</td>
			<td><?=$val['ag']['num']+0?></td>
			<td align="right"><a href="<?=$report_url?>" class="a_001"><?=number_format($val['ag']['BetAll'],4)?></a></td>
			<td><?=number_format($val['ag']['BetYC'],4)?></td>
			<td><?=number_format($val['ag']['BetPC'] + $val['ag']['BetYC'],4)?></td>
			<td><?=number_format(0-$val['ag']['BetPC'],4)?></td>
			<td>0.00</td>
			<td><?=number_format(0-$val['ag']['BetPC'],4)?></td>
			<td><?=number_format(0-$val['ag']['BetPC'],4)?></td>
			<td><?=number_format($ag_total_ddljs,4)?></td>
	        <td><?=number_format($ag_total_ddljg,4)?></td>
	        <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($ag_total_dzdjs,4)?></td>
	        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($ag_total_dgdjs,4)?></td>
	        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='u_a'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
			<td style="display:none">0.00</td>
	</tr>
 <?php }?>
 <?php }?>
	</tbody>
	<tfoot>
	<?php if($ag_total_count > 0){?>
	<tr class="m_rig">
		<td align="center">總計</td>
		<td><?=$ag_total_count?></td>
		<td><?=number_format($ag_total_betall,4)?></td>
		<td><?=number_format($ag_total_bet,4)?></td>
        <td><?=number_format($ag_total_pcbet,4)?></td>
		<td><?=number_format($ag_total_bet-$ag_total_pcbet,4)?></td>
		<td>0.00</td>
		<td><?=number_format($ag_total_bet-$ag_total_pcbet,4)?></td>
		<td><?=number_format($ag_total_bet-$ag_total_pcbet,4)?></td>
		<td><?=number_format($ag_total_dljs,4)?></td>
        <td><?=number_format($ag_total_dljg,4)?></td>
        <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($ag_total_zdjs,4)?></td>
        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($ag_total_gdjs,4)?></td>
        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='u_a'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
		<td style="display:none">0.00</td>
	</tr>
	<?php }else{?>
	 <tr class="m_rig">
	 <td colspan="14" align="center">暂无数据</td>
	 </tr>
	 <?php }?>
	</tfoot>
</table>
</div>
<?php }?>

<!-- og视讯 -->
<?php  if ($_GET['og'] == '11') {
?>
<div>
<table border="0" cellpadding="0" cellspacing="0" id="m_tab4" class="m_tab tablesorter">
	<thead>
	<tr class="m_title">
		<td colspan="15" id="zr_panel" style="text-align:left;background:#FFFFFF;color:#333131">
        <div style="float:left">OG视讯 日期：<?=$date_start?>~ <?=$date_end?> 投注類型：全部 投注方式：全部</div>
         <div style="float:right">"<font color="#ff0000">紅</font>"色代表业主亏损，"<font color="#000">黑</font>"色代表业主盈利</div></td>
	</tr>
	<tr class="m_title">
	  <th width="100" nowrap="nowrap" rowspan="2" style="font-size:12px;"><span><?=$table_title?>名稱</span></th>
	  <th width="50" rowspan="2" style="font-size:12px;"><span>筆數</span></th>
	  <th id="target4" width="80" rowspan="2" style="font-size:12px;"><span>下注金額</span></th>
	  <th width="80" rowspan="2" style="font-size:12px;"><span>有效金額</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span>總派彩</span></th> 
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>占成</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>退水</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>損益</span></th>
	  <td colspan="7" style="font-size:12px;background-color: #fecee4;color:#122858"><span>各層交收</span></td>
    </tr>
	<tr class="m_title">
		  <?php foreach ($level_a as $key => $val) {
        ?>
          <th class="header"><span><?=$val['name']?></span></th>
        <?php }?>
        <th style="display:none"><span>後台</span></th>
	</tr>
	</thead>
	<tbody>
	
	<?php foreach ($agent_sh as $key => $val) {
		$og_total_count += $val['og']['num'];
		$og_total_bet += $val['og']['BetYC'];
		$og_total_pcbet += $val['og']['BetPC'] + $val['og']['BetYC'];
		$og_total_betall += $val['og']['BetAll'];
		if($atype == 'u_a'){
			$og_total_dljs+=$val['og']['s_hdljs'];
			$og_total_zdjs+=$val['og']['s_hzdjs'];
			$og_total_gdjs+=$val['og']['s_hgdjs'];
			$og_total_dljg+=$val['og']['s_hdljg']+0;//总代理商结果
			$og_total_ddljs=$val['og']['s_hdljs']+0;
			$og_total_dzdjs=$val['og']['s_hzdjs']+0;
			$og_total_dgdjs=$val['og']['s_hgdjs']+0;
			$og_total_ddljg=$val['og']['s_hdljg']+0;//总代理商结果
			$og_total_dacczc=$og_total_dgdjs;
			$og_total_acczc+=$og_total_dacczc;
		}elseif($atype == 'a_t'){
			$og_total_dljs+=$val['og']['u_adljs'];
			$og_total_zdjs+=$val['og']['u_azdjs'];
			$og_total_gdjs+=$val['og']['u_agdjs'];
			$og_total_dljg+=$val['og']['u_adljg']+0;//总代理商结果
			$og_total_ddljs=$val['og']['u_adljs']+0;
			$og_total_dzdjs=$val['og']['u_azdjs']+0;
			$og_total_dgdjs=$val['og']['u_agdjs']+0;
			$og_total_ddljg=$val['og']['u_adljg']+0;//总代理商结果
			$og_total_dacczc=$og_total_dzdjs-$og_total_dgdjs;
			$og_total_acczc+=$og_total_dacczc;
		}elseif($_GET['atype'] == 'a_t'){
			$og_total_dljs+=$val['og']['a_tdljs'];
			$og_total_zdjs+=$val['og']['a_tzdjs'];
			$og_total_gdjs+=$val['og']['a_tgdjs'];
			$og_total_dljg+=$val['og']['a_tdljg']+0;//总代理商结果
			$og_total_ddljs=$val['og']['a_tdljs']+0;
			$og_total_dzdjs=$val['og']['a_tzdjs']+0;
			$og_total_dgdjs=$val['og']['a_tgdjs']+0;
			$og_total_ddljg=$val['og']['a_tdljg']+0;//总代理商结果
			$og_total_dacczc=$og_total_ddljs-$og_total_dzdjs;
			$og_total_acczc+=$og_total_dacczc;
		}elseif($_GET['atype'] == 'user'){
			$og_total_dljs+=$val['og']['userdljs'];
			$og_total_zdjs+=$val['og']['userzdjs'];
			$og_total_gdjs+=$val['og']['usergdjs'];
			$og_total_dljg+=$val['og']['userdljg']+0;//总代理商结果
			$og_total_ddljs=$val['og']['userdljs']+0;
			$og_total_dzdjs=$val['og']['userzdjs']+0;
			$og_total_dgdjs=$val['og']['usergdjs']+0;
			$og_total_ddljg=$val['og']['userdljg']+0;//总代理商结果
			$og_total_dacczc=$og_total_ddljg;
			$og_total_acczc+=$og_total_dacczc;
		}
	if ($_GET['atype'] == 'user') {
         $report_url = "../note/video.php?username=".$val['agent_user']."&date_start=".substr($date_start,0,10)."&date_end=".substr($date_end,0,10)."&gtype=og";
    }else{
         $report_url = './report_result.php?uid='.$val['id'].'&is_res='.$is_res.'&date_start='.substr($date_start,0,10).'&date_end='.substr($date_end,0,10).'&sp='.$_GET['sp'].'&cp='.$_GET['cp'].'&mg='.$_GET['mg'].'&ag='.$_GET['ag'].'&bbsx='.$_GET['bbsx'].'&bbdz='.$_GET['bbdz'].'&bbty='.$_GET['bbty'].'&bbcp='.$_GET['bbcp'].'&ct='.$_GET['ct'].'&lebo='.$_GET['lebo'].'&mgdz='.$_GET['mgdz'].'&og='.$_GET['og'].'&atype='.$atype;
    }
   ?>
   <?php if($val['og']['num'] > 0){?>
	<tr class="m_rig" align="left">
			<td align="center" nowrap="nowrap"><?=$val['agent_user']?>(<?=$val['agent_name']?>)</td>
			<td><?=$val['og']['num']+0?></td>
			<td align="right"><a href="<?=$report_url?>" class="a_001"><?=number_format($val['og']['BetAll'],4)?></a></td>
			<td><?=number_format($val['og']['BetYC'],4)?></td>
			<td><?=number_format($val['og']['BetPC'] + $val['og']['BetYC'],4)?></td>
			<td><?=number_format(0-$val['og']['BetPC'],4)?></td>
			<td>0.00</td>
			<td><?=number_format(0-$val['og']['BetPC'],4)?></td>
			<td><?=number_format(0-$val['og']['BetPC'],4)?></td>
			<td><?=number_format($og_total_ddljs,4)?></td>
	        <td><?=number_format($og_total_ddljg,4)?></td>
	        <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($og_total_dzdjs,4)?></td>
	        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($og_total_dgdjs,4)?></td>
	        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='u_a'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
			<td style="display:none">0.00</td>
	</tr>
 <?php }?>
 <?php }?>
	</tbody>
	<tfoot>
	<?php if($og_total_count > 0){?>
	<tr class="m_rig">
		<td align="center">總計</td>
		<td><?=$og_total_count?></td>
		<td><?=number_format($og_total_betall,4)?></td>
		<td><?=number_format($og_total_bet,4)?></td>
		<td><?=number_format($og_total_pcbet,4)?></td>
        <td><?=number_format($og_total_bet-$og_total_pcbet,4)?></td>
		<td>0.00</td>
		<td><?=number_format($og_total_bet-$og_total_pcbet,4)?></td>
		<td><?=number_format($og_total_bet-$og_total_pcbet,4)?></td>
		<td><?=number_format($og_total_dljs,4)?></td>
        <td><?=number_format($og_total_dljg,4)?></td>
        <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($og_total_zdjs,4)?></td>
        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($og_total_gdjs,4)?></td>
        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='u_a'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
		<td style="display:none">0.00</td>
	</tr>
	<?php }else{?>
	 <tr class="m_rig">
	 <td colspan="14" align="center">暂无数据</td>
	 </tr>
	 <?php }?>
	</tfoot>
</table>
</div>
<?php }?>




<!-- ct视讯 -->
<?php  if ($_GET['ct'] == '4') {
?>
<div>
<table border="0" cellpadding="0" cellspacing="0" id="m_tab4" class="m_tab tablesorter">
	<thead>
	<tr class="m_title">
		<td colspan="15" id="zr_panel" style="text-align:left;background:#FFFFFF;color:#333131">
        <div style="float:left">CT视讯 日期：<?=$date_start?>~ <?=$date_end?> 投注類型：全部 投注方式：全部</div>
         <div style="float:right">"<font color="#ff0000">紅</font>"色代表业主亏损，"<font color="#000">黑</font>"色代表业主盈利</div></td>
	</tr>
	<tr class="m_title">
	  <th width="100" nowrap="nowrap" rowspan="2" style="font-size:12px;"><span><?=$table_title?>名稱</span></th>
	  <th width="50" rowspan="2" style="font-size:12px;"><span>筆數</span></th>
	  <th id="target4" width="80" rowspan="2" style="font-size:12px;"><span>下注金額</span></th>
	  <th width="80" rowspan="2" style="font-size:12px;"><span>有效金額</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span>總派彩</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>占成</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>退水</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>損益</span></th>
	  <td colspan="7" style="font-size:12px;background-color: #fecee4;color:#122858"><span>各層交收</span></td>
    </tr>
	<tr class="m_title">
		  <?php foreach ($level_a as $key => $val) {
        ?>
          <th class="header"><span><?=$val['name']?></span></th>
        <?php }?>
        <th style="display:none"><span>後台</span></th>
	</tr>
	</thead>
	<tbody>
	
	<?php foreach ($agent_sh as $key => $val) {
		$ct_total_count += $val['ct']['num'];
		$ct_total_bet += $val['ct']['BetYC'];
		$ct_total_pcbet += $val['ct']['BetPC'] + $val['ct']['BetYC'];
		$ct_total_betall += $val['ct']['BetAll'];
		if($atype == 'u_a'){
			$ct_total_dljs+=$val['ct']['s_hdljs'];
			$ct_total_zdjs+=$val['ct']['s_hzdjs'];
			$ct_total_gdjs+=$val['ct']['s_hgdjs'];
			$ct_total_dljg+=$val['ct']['s_hdljg']+0;//总代理商结果
			$ct_total_ddljs=$val['ct']['s_hdljs']+0;
			$ct_total_dzdjs=$val['ct']['s_hzdjs']+0;
			$ct_total_dgdjs=$val['ct']['s_hgdjs']+0;
			$ct_total_ddljg=$val['ct']['s_hdljg']+0;//总代理商结果
			$ct_total_dacczc=$ct_total_dgdjs;
			$ct_total_acczc+=$ct_total_dacczc;
		}elseif($atype == 'a_t'){
			$ct_total_dljs+=$val['ct']['u_adljs'];
			$ct_total_zdjs+=$val['ct']['u_azdjs'];
			$ct_total_gdjs+=$val['ct']['u_agdjs'];
			$ct_total_dljg+=$val['ct']['u_adljg']+0;//总代理商结果
			$ct_total_ddljs=$val['ct']['u_adljs']+0;
			$ct_total_dzdjs=$val['ct']['u_azdjs']+0;
			$ct_total_dgdjs=$val['ct']['u_agdjs']+0;
			$ct_total_ddljg=$val['ct']['u_adljg']+0;//总代理商结果
			$ct_total_dacczc=$ct_total_dzdjs-$ct_total_dgdjs;
			$ct_total_acczc+=$ct_total_dacczc;
		}elseif($_GET['atype'] == 'a_t'){
			$ct_total_dljs+=$val['ct']['a_tdljs'];
			$ct_total_zdjs+=$val['ct']['a_tzdjs'];
			$ct_total_gdjs+=$val['ct']['a_tgdjs'];
			$ct_total_dljg+=$val['ct']['a_tdljg']+0;//总代理商结果
			$ct_total_ddljs=$val['ct']['a_tdljs']+0;
			$ct_total_dzdjs=$val['ct']['a_tzdjs']+0;
			$ct_total_dgdjs=$val['ct']['a_tgdjs']+0;
			$ct_total_ddljg=$val['ct']['a_tdljg']+0;//总代理商结果
			$ct_total_dacczc=$ct_total_ddljs-$ct_total_dzdjs;
			$ct_total_acczc+=$ct_total_dacczc;
		}elseif($_GET['atype'] == 'user'){
			$ct_total_dljs+=$val['ct']['userdljs'];
			$ct_total_zdjs+=$val['ct']['userzdjs'];
			$ct_total_gdjs+=$val['ct']['usergdjs'];
			$ct_total_dljg+=$val['ct']['userdljg']+0;//总代理商结果
			$ct_total_ddljs=$val['ct']['userdljs']+0;
			$ct_total_dzdjs=$val['ct']['userzdjs']+0;
			$ct_total_dgdjs=$val['ct']['usergdjs']+0;
			$ct_total_ddljg=$val['ct']['userdljg']+0;//总代理商结果
			$ct_total_dacczc=$ct_total_ddljg;
			$ct_total_acczc+=$ct_total_dacczc;
		}
	if ($_GET['atype'] == 'user') {
         $report_url = "../note/video.php?username=".$val['agent_user']."&date_start=".substr($date_start,0,10)."&date_end=".substr($date_end,0,10)."&gtype=ct";
    }else{
        $report_url = './report_result.php?uid='.$val['id'].'&is_res='.$is_res.'&date_start='.substr($date_start,0,10).'&date_end='.substr($date_end,0,10).'&sp='.$_GET['sp'].'&cp='.$_GET['cp'].'&mg='.$_GET['mg'].'&ag='.$_GET['ag'].'&bbsx='.$_GET['bbsx'].'&bbdz='.$_GET['bbdz'].'&bbty='.$_GET['bbty'].'&bbcp='.$_GET['bbcp'].'&ct='.$_GET['ct'].'&lebo='.$_GET['lebo'].'&mgdz='.$_GET['mgdz'].'&og='.$_GET['og'].'&atype='.$atype;
    }
   ?>
   <?php if($val['ct']['num'] > 0){?>
	<tr class="m_rig" align="left">
			<td align="center" nowrap="nowrap"><?=$val['agent_user']?>(<?=$val['agent_name']?>)</td>
			<td><?=$val['ct']['num']+0?></td>
			<td align="right"><a href="<?=$report_url?>" class="a_001"><?=number_format($val['ct']['BetAll'],4)?></a></td>
			<td><?=number_format($val['ct']['BetYC'],4)?></td>
			<td><?=number_format($val['ct']['BetPC'] +$val['ct']['BetYC'],4)?></td>
			<td><?=number_format(0-$val['ct']['BetPC'],4)?></td>
			<td>0.00</td>
			<td><?=number_format(0-$val['ct']['BetPC'],4)?></td>
			<td><?=number_format(0-$val['ct']['BetPC'],4)?></td>
			<td><?=number_format($ct_total_ddljs,4)?></td>
	        <td><?=number_format($ct_total_ddljg,4)?></td>
	        <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($ct_total_dzdjs,4)?></td>
	        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($ct_total_dgdjs,4)?></td>
	        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='u_a'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
			<td style="display:none">0.00</td>
	</tr>
 <?php }?>
 <?php }?>
	</tbody>
	<tfoot>
	<?php if($ct_total_count > 0){?>
	<tr class="m_rig">
		<td align="center">總計</td>
		<td><?=$ct_total_count?></td>
		<td><?=number_format($ct_total_betall,4)?></td>
		<td><?=number_format($ct_total_bet,4)?></td>
		<td><?=number_format($ct_total_pcbet,4)?></td>
        <td><?=number_format($ct_total_bet-$ct_total_pcbet,4)?></td>
		<td>0.00</td>
		<td><?=number_format($ct_total_bet-$ct_total_pcbet,4)?></td>
		<td><?=number_format($ct_total_bet-$ct_total_pcbet,4)?></td>
		<td><?=number_format($ct_total_dljs,4)?></td>
        <td><?=number_format($ct_total_dljg,4)?></td>
        <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($ct_total_zdjs,4)?></td>
        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($ct_total_gdjs,4)?></td>
        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='u_a'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
		<td style="display:none">0.00</td>
	</tr>
	 <?php }else{?>
	 <tr class="m_rig">
	 <td colspan="14" align="center">暂无数据</td>
	 </tr>
	 <?php }?>
	</tfoot>
</table>
</div>
<?php }?>







<!-- lebo视讯 -->
<?php  if ($_GET['lebo'] == '3') {
?>
<div>
<table border="0" cellpadding="0" cellspacing="0" id="m_tab4" class="m_tab tablesorter">
	<thead>
	<tr class="m_title">
		<td colspan="15" id="zr_panel" style="text-align:left;background:#FFFFFF;color:#333131">
        <div style="float:left">LEBO视讯 日期：<?=$date_start?>~ <?=$date_end?> 投注類型：全部 投注方式：全部</div>
         <div style="float:right">"<font color="#ff0000">紅</font>"色代表业主亏损，"<font color="#000">黑</font>"色代表业主盈利</div></td>
	</tr>
	<tr class="m_title">
	  <th width="100" nowrap="nowrap" rowspan="2" style="font-size:12px;"><span><?=$table_title?>名稱</span></th>
	  <th width="50" rowspan="2" style="font-size:12px;"><span>筆數</span></th>
	  <th id="target4" width="80" rowspan="2" style="font-size:12px;"><span>下注金額</span></th>
	  <th width="80" rowspan="2" style="font-size:12px;"><span>有效金額</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span>總派彩</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>占成</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>退水</span></th>
      <th width="80" rowspan="2" style="font-size:12px;"><span><?=$u_table_title?>損益</span></th>
	  <td colspan="7" style="font-size:12px;background-color: #fecee4;color:#122858"><span>各層交收</span></td>
    </tr>
	<tr class="m_title">
		  <?php foreach ($level_a as $key => $val) {
        ?>
          <th class="header"><span><?=$val['name']?></span></th>
        <?php }?>
        <th style="display:none"><span>後台</span></th>
	</tr>
	</thead>
	<tbody>
	
	<?php foreach ($agent_sh as $key => $val) {
		$lebo_total_count += $val['lebo']['num'];
		$lebo_total_bet += $val['lebo']['BetYC'];
		$lebo_total_pcbet += $val['lebo']['BetPC'] + $val['lebo']['BetYC'];
		$lebo_total_betall += $val['lebo']['BetAll'];
		if($atype == 'u_a'){
			$lebo_total_dljs+=$val['lebo']['s_hdljs'];
			$lebo_total_zdjs+=$val['lebo']['s_hzdjs'];
			$lebo_total_gdjs+=$val['lebo']['s_hgdjs'];
			$lebo_total_dljg+=$val['lebo']['s_hdljg']+0;//总代理商结果
			$lebo_total_ddljs=$val['lebo']['s_hdljs']+0;
			$lebo_total_dzdjs=$val['lebo']['s_hzdjs']+0;
			$lebo_total_dgdjs=$val['lebo']['s_hgdjs']+0;
			$lebo_total_ddljg=$val['lebo']['s_hdljg']+0;//总代理商结果
			$lebo_total_dacczc=$lebo_total_dgdjs;
			$lebo_total_acczc+=$lebo_total_dacczc;
		}elseif($atype == 'a_t'){
			$lebo_total_dljs+=$val['lebo']['u_adljs'];
			$lebo_total_zdjs+=$val['lebo']['u_azdjs'];
			$lebo_total_gdjs+=$val['lebo']['u_agdjs'];
			$lebo_total_dljg+=$val['lebo']['u_adljg']+0;//总代理商结果
			$lebo_total_ddljs=$val['lebo']['u_adljs']+0;
			$lebo_total_dzdjs=$val['lebo']['u_azdjs']+0;
			$lebo_total_dgdjs=$val['lebo']['u_agdjs']+0;
			$lebo_total_ddljg=$val['lebo']['u_adljg']+0;//总代理商结果
			$lebo_total_dacczc=$lebo_total_dzdjs-$lebo_total_dgdjs;
			$lebo_total_acczc+=$lebo_total_dacczc;
		}elseif($_GET['atype'] == 'a_t'){
			$lebo_total_dljs+=$val['lebo']['a_tdljs'];
			$lebo_total_zdjs+=$val['lebo']['a_tzdjs'];
			$lebo_total_gdjs+=$val['lebo']['a_tgdjs'];
			$lebo_total_dljg+=$val['lebo']['a_tdljg']+0;//总代理商结果
			$lebo_total_ddljs=$val['lebo']['a_tdljs']+0;
			$lebo_total_dzdjs=$val['lebo']['a_tzdjs']+0;
			$lebo_total_dgdjs=$val['lebo']['a_tgdjs']+0;
			$lebo_total_ddljg=$val['lebo']['a_tdljg']+0;//总代理商结果
			$lebo_total_dacczc=$lebo_total_ddljs-$lebo_total_dzdjs;
			$lebo_total_acczc+=$lebo_total_dacczc;
		}elseif($_GET['atype'] == 'user'){
			$lebo_total_dljs+=$val['lebo']['userdljs'];
			$lebo_total_zdjs+=$val['lebo']['userzdjs'];
			$lebo_total_gdjs+=$val['lebo']['usergdjs'];
			$lebo_total_dljg+=$val['lebo']['userdljg']+0;//总代理商结果
			$lebo_total_ddljs=$val['lebo']['userdljs']+0;
			$lebo_total_dzdjs=$val['lebo']['userzdjs']+0;
			$lebo_total_dgdjs=$val['lebo']['usergdjs']+0;
			$lebo_total_ddljg=$val['lebo']['userdljg']+0;//总代理商结果
			$lebo_total_dacczc=$lebo_total_ddljg;
			$lebo_total_acczc+=$lebo_total_dacczc;
		}
	if ($_GET['atype'] == 'user') {
         $report_url = "../note/video.php?username=".$val['agent_user']."&date_start=".substr($date_start,0,10)."&date_end=".substr($date_end,0,10)."&gtype=lebo";
    }else{
        $report_url = './report_result.php?uid='.$val['id'].'&is_res='.$is_res.'&date_start='.substr($date_start,0,10).'&date_end='.substr($date_end,0,10).'&sp='.$_GET['sp'].'&cp='.$_GET['cp'].'&mg='.$_GET['mg'].'&ag='.$_GET['ag'].'&bbsx='.$_GET['bbsx'].'&bbdz='.$_GET['bbdz'].'&bbty='.$_GET['bbty'].'&bbcp='.$_GET['bbcp'].'&ct='.$_GET['ct'].'&lebo='.$_GET['lebo'].'&mgdz='.$_GET['mgdz'].'&og='.$_GET['og'].'&atype='.$atype;
    }
   ?>
   <?php if($val['lebo']['num'] > 0){?>
	<tr class="m_rig" align="left">
			<td align="center" nowrap="nowrap"><?=$val['agent_user']?>(<?=$val['agent_name']?>)</td>
			<td><?=$val['lebo']['num']+0?></td>
			<td align="right"><a href="<?=$report_url?>" class="a_001"><?=number_format($val['lebo']['BetAll'],4)?></a></td>
			<td><?=number_format($val['lebo']['BetYC'],4)?></td>
			<td><?=number_format($val['lebo']['BetPC'] + $val['lebo']['BetYC'],4)?></td>
			<td><?=number_format(0-$val['lebo']['BetPC'],4)?></td>
			<td>0.00</td>
			<td><?=number_format(0-$val['lebo']['BetPC'],4)?></td>
			<td><?=number_format(0-$val['lebo']['BetPC'],4)?></td>
			<td><?=number_format($lebo_total_ddljs,4)?></td>
	        <td><?=number_format($lebo_total_ddljg,4)?></td>
	        <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($lebo_total_dzdjs,4)?></td>
	        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($lebo_total_dgdjs,4)?></td>
	        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='u_a'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
			<td style="display:none">0.00</td>
	</tr>
<?php }?>
 <?php }?>
	</tbody>
	<tfoot>
	<?php if($lebo_total_count > 0){?>
	<tr class="m_rig">
		<td align="center">總計</td>
		<td><?=$lebo_total_count?></td>
		<td><?=number_format($lebo_total_betall,4)?></td>
		<td><?=number_format($lebo_total_bet,4)?></td>
		<td><?=number_format($lebo_total_pcbet,4)?></td>
        <td><?=number_format($lebo_total_bet-$lebo_total_pcbet,4)?></td>
		<td>0.00</td>
		<td><?=number_format($lebo_total_bet-$lebo_total_pcbet,4)?></td>
		<td><?=number_format($lebo_total_bet-$lebo_total_pcbet,4)?></td>
		<td><?=number_format($lebo_total_dljs,4)?></td>
        <td><?=number_format($lebo_total_dljg,4)?></td>
        <td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($lebo_total_zdjs,4)?></td>
        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($lebo_total_gdjs,4)?></td>
        <td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='u_a'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
		<td style="display:none">0.00</td>
	</tr>
	 <?php }else{?>
	 <tr class="m_rig">
	 <td colspan="14" align="center">暂无数据</td>
	 </tr>
	 <?php }?>
	</tfoot>
</table>
</div>
<?php }?>
<?php require("../common_html/footer.php"); ?>
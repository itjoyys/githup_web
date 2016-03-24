<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cash_agent extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('cash/Cash_agent_model');
	}

	public function index(){
        //读取期数
    	$map = array();
    	$map['table'] = 'k_qishu';
    	$map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['is_delete'] = 0;
        $map['order']="id DESC";
        $qishu=$this->Cash_agent_model->rget($map);
          //表单数据处理
            $qs = intval($_GET['qs']); //期数
            $atype = intval($_GET['atype']); //0 全部,1 股东,2 总代理,3  代理
            $username = trim($_GET['username']); //代理的名字
            $kf_ty = intval($_GET['kf_ty']); // -1 全部,0 未达门坎,1 达到门槛
            if ($qs > 0) {
                $maps = array();
                $maps['table'] = 'k_qishu';
                $maps['where']['site_id'] = $_SESSION['site_id'];
                $maps['where']['is_delete'] = 0;
                $maps['where']['id'] = $qs;
                $qs_info=$this->Cash_agent_model->rget($maps);
            }
            if ($atype >= 1 && empty($username)) {
                $url= URL."/cash/cash_agent/index?qs=$qs";
                showmessage('请输入股东,总代理,或者代理，的用户名！',$url,0);
            }
            if(!empty($qs_info)){
                $date[0] = $qs_info[0]['start_date'] . ' 00:00:00';
                $date[1] = $qs_info[0]['end_date'] . ' 23:59:59';
                $agents = $this->Cash_agent_model->getallagentid($atype,$username);//根据用户类型 和用户名获取代理ID号
                //获取有效会员判定门槛
                $map = array();
                $map['table'] = 'k_hire_config';
                $map['where']['site_id'] = $_SESSION['site_id'];
                $map['where']['is_delete'] = 0;
                $valid_menber=$this->Cash_agent_model->rget($map);
                
                
                if($agents){
                    $g_type = array('mgdz','mg','ag','og','ct','lebo','bbin','bbdz','pt');
                    foreach ($agents as $k => $v){
                        $agentsstr .= $v['id'].',';
                        $agentsuser .= '"'.$v['agent_user'].'",';
                    }
                     $agentsstr = rtrim($agentsstr, ",");
                     $agentsuser = rtrim($agentsuser, ",");
                     
                    //获取退佣手续费设定
                    $fee = $this->Cash_agent_model->get_fee($date);
                     
                    /***********************************费用计算华丽分割线******************************************/
                    //前台注册赠送的优惠费用
                    $field = 'sum(discount_num) as money,agent_id';
                    $field2 = 'agent_id';
                    $qt_reg_cash = $this->Cash_agent_model->get_qt_zc_cash($agentsstr,$date,$field,$field2);
                    
                    //天天反水费用
                    $field = 'sum(total_e_fd) as money,agent_id';
                    $field2 = 'agent_id';
                    $defection = $this->Cash_agent_model->get_defection($agentsstr,$date,$field,$field2);
                    
                    //会员前台申请出款费用
                    $outmaxcash1 = array('<',$fee['out_max_fee'] / ($fee['out_fee'] * 0.01));
                    $outmaxcash2 = array('>=',$fee['out_max_fee'] / ($fee['out_fee'] * 0.01));
                    $field1 = 'sum(outward_money) as money,agent_user';
                    $field2 = 'count(*) as times,agent_user';
                    $field3 = 'agent_user';
                    $qt_out_fee1 = $this->Cash_agent_model->get_qt_out_fee($agentsuser,$date,$outmaxcash1,$field1,$field3);//小于手续费上限的数据
                    $qt_out_fee2 = $this->Cash_agent_model->get_qt_out_fee($agentsuser,$date,$outmaxcash2,$field2,$field3);//大于手续费上限的数据
                    foreach($qt_out_fee1 as $k => $v){//小于手续费上限的数据
                        $qt_out_fee[$v['agent_user']] += $v['money'] * $fee['out_fee'] * 0.01;
                    }
                    
                    foreach($qt_out_fee2 as $k => $v){//大于手续费上限的数据
                        $qt_out_fee[$v['agent_user']] += $v['times'] * $fee['out_max_fee'];
                    }
                    
                    
                    //会员前台申请公司入款费用
                    $inmaxcash1 = array('<',$fee['in_max_fee'] / ($fee['in_fee'] * 0.01));
                    $inmaxcash2 = array('>=',$fee['in_max_fee'] / ($fee['in_fee'] * 0.01));
                    $field3 = 'sum(deposit_num) as money,sum(favourable_num) as favourable_num,sum(other_num) as other_num,agent_user';
                    $field4 = 'count(*) as times,sum(favourable_num) as favourable_num,sum(other_num) as other_num,agent_user';
                    $field5 = 'agent_user';
                    $qt_in_fee1 = $this->Cash_agent_model->get_qt_in_fee($agentsuser,$date,$inmaxcash1,$field3,1,$field5);//小于手续费上限的数据
                    $qt_in_fee2 = $this->Cash_agent_model->get_qt_in_fee($agentsuser,$date,$inmaxcash2,$field4,1,$field5);//大于手续费上限的数据
                    foreach($qt_in_fee1 as $k => $v){//小于手续费上限的数据
                        $qt_in_fee[$v['agent_user']] += $v['money'] * $fee['in_fee'] * 0.01;
                        $qt_in_fee[$v['agent_user']] += $v['favourable_num']+$v['other_num'];
                    }
                     
                    foreach($qt_in_fee2 as $k => $v){//大于手续费上限的数据
                        $qt_in_fee[$v['agent_user']] += $v['times'] * $fee['in_max_fee'];
                        $qt_in_fee[$v['agent_user']] += $v['favourable_num']+$v['other_num'];
                    }
                   
                   
                    //会员前台申请线上入款费用
                    $qt_in_fee3 = $this->Cash_agent_model->get_qt_in_fee($agentsuser,$date,$inmaxcash1,$field3,2,$field5);//小于手续费上限的数据
                    $qt_in_fee4 = $this->Cash_agent_model->get_qt_in_fee($agentsuser,$date,$inmaxcash2,$field4,2,$field5);//大于手续费上限的数据
                    foreach($qt_in_fee3 as $k => $v){//小于手续费上限的数据
                        $qt_in_fee[$v['agent_user']] += $v['money'] * $fee['in_fee'] * 0.01;
                        $qt_in_fee[$v['agent_user']] += $v['favourable_num']+$v['other_num'];
                    }
                     
                    foreach($qt_in_fee4 as $k => $v){//大于手续费上限的数据
                        $qt_in_fee[$v['agent_user']] += $v['times'] * $fee['in_max_fee'];
                        $qt_in_fee[$v['agent_user']] += $v['favourable_num']+$v['other_num'];
                    }
                    
                    
                    //后台手动入款费用
                    $field5 = 'sum(catm_money) as money,sum(catm_give) as catm_give,sum(atm_give) as atm_give,agent_id';
                    $field6 = 'count(*) as times,sum(catm_give) as catm_give,sum(atm_give) as atm_give,agent_id';
                    $field7 = 'agent_id';
                    $qt_in_fee5 = $this->Cash_agent_model->get_ht_fee($agentsstr,$date,$inmaxcash1,$field5,$field7);//小于手续费上限的数据
                    $qt_in_fee6 = $this->Cash_agent_model->get_ht_fee($agentsstr,$date,$inmaxcash2,$field6,$field7);//大于手续费上限的数据
                                        

                    foreach($qt_in_fee5 as $k => $v){//小于手续费上限的数据
                        $ht_in_fee[$v['agent_id']] += $v['money'] * $fee['in_fee'] * 0.01;
                        $ht_in_fee[$v['agent_id']] += $v['catm_give'] + $v['atm_give'];
                    }
                   
                     
                    foreach($qt_in_fee6 as $k => $v){//大于手续费上限的数据
                        $ht_in_fee[$v['agent_id']] += $v['times'] * $fee['in_max_fee'];
                        $ht_in_fee[$v['agent_id']] += $v['catm_give']+$v['atm_give'];
                    }
                    //总费用统计
                    foreach($agents as $k => $v){
                        foreach($qt_reg_cash as $kk => $vv){//前台注册送优惠
                            if($v['id'] == $vv['agent_id']){
                                $agents[$k]['feemoney'] += $vv['money'];
                            }
                        }
                        foreach($defection as $kk => $vv){//天天反水
                            if($v['id'] == $vv['agent_id']){
                                $agents[$k]['feemoney'] += $vv['money'];
                            }
                        }
                        
                        $agents[$k]['feemoney'] += $qt_out_fee[$v['agent_user']];//前台申请出款费用
                        $agents[$k]['feemoney'] += $qt_in_fee[$v['agent_user']];//前台申请入款费用
                        $agents[$k]['feemoney'] += $ht_in_fee[$v['id']];//后台手动入款费用
                    }
                    
                    
                    /***********************************费用计算华丽分割线******************************************/
                    
                    
                     //彩票数据
                     $fcBet = $this->Cash_agent_model->return_fcbet($agentsstr,$date);
                     
                     //体育数据
                     $spBet = $this->Cash_agent_model->return_kbet($agentsstr,$date);
                    
                    //串关
                    $spc_bet = $this->Cash_agent_model->return_cg($agentsstr,$date);
                    
                     //视讯数据
                    foreach ($g_type as $key => $val) {
                        if($val == "mg"||$val == "mgdz" || $val == "pt"){
                            $userRe[$val] = $this->Cash_agent_model->get_video_bet($agentsstr,$val,$date);
                        }else{
                            $userRe[$val] = $this->Cash_agent_model->get_video_bet($agentsstr,$val,$date);
                             foreach ($userRe[$val] as $k => $v){
                                $userRe[$val][$k]["netamount"] = $userRe[$val][$k]["netamount"]+$userRe[$val][$k]["valid_betamount"];
                             }
                        }
                    }
                    

                    //当期有效会员
                    foreach($agents as $k => $v){
                        $agents[$k]['num'] = $this->Cash_agent_model->get_valid_menber($fcBet,$spBet,$spc_bet,$userRe,$valid_menber[0]['valid_money'],$v['id']);
                    }
                    //p($agents);
                    $agents = $this->Cash_agent_model->v_array($agents,$this->Cash_agent_model->get_old_info($agentsstr,$qs_info));
                    
                    $video = array('mg','ag','og','bbin','ct','lebo','pt');
                    foreach ($agents as $k => $v) {
                        //彩票当期数据
                        foreach ($fcBet as $kk => $vv){
                            if($k == 'u-'.$vv['agent_id']){
                                $agents[$k]['nowvalidcbet'] += $vv['valid_betamount'];
                                $agents[$k]['nowcbet'] += -($vv['netamount']-$vv['valid_betamount']);
                            }
                        }
                        
                        //体育当期数据
                        foreach ($spBet as $kk => $vv){
                            if($k == 'u-'.$vv['agent_id']){
                                $agents[$k]['nowvalidkbet'] += $vv['valid_betamount'];
                                $agents[$k]['nowkbet'] += -($vv['netamount']-$vv['valid_betamount']);
                                
                            }
                        }
                        //串关当期数据
                        foreach ($spc_bet as $kk => $vv){
                            if($k == 'u-'.$vv['agent_id']){
                                $agents[$k]['nowvalidckbet'] += $vv['valid_betamount'];
                                $agents[$k]['nowckbet'] += -($vv['netamount']-$vv['valid_betamount']);
                            }
                        }
                        //视讯当期数据
                        foreach ($userRe as $kk => $vv){
                            foreach($vv as $key => $value){
                                if($k == 'u-'.$value['agent_id']){
                                    if(in_array($kk,$video)){
                                        $agents[$k]['nowvalidvbet'] += $value['valid_betamount'];
                                        $agents[$k]['nowvbet'] += -($value['netamount']-$value['valid_betamount']);
                                    }else{
                                        $agents[$k]['nowvalidebet'] += $value['valid_betamount'];
                                        $agents[$k]['nowebet'] += -($value['netamount']-$value['valid_betamount']);
                                    }
                                }
                            }
                        }
                        
                         $agents[$k]['qishu_id'] = $qs;
                    }
                    foreach($agents as $k => $v){
                        if(empty($v['num'])){
                            $agents[$k]['num'] = 0;
                        }
                        if(empty($v['old_usernum'])){
                            $agents[$k]['old_usernum'] = 0;
                        }
                        if(empty($v['nowcbet'])){
                            $agents[$k]['nowcbet'] = 0;
                        }
                        if(empty($v['nowkbet'])){
                            $agents[$k]['nowkbet'] = 0;
                        }
                        if(empty($v['nowckbet'])){
                            $agents[$k]['nowckbet'] = 0;
                        }
                        if(empty($v['nowvbet'])){
                            $agents[$k]['nowvbet'] = 0;
                        }
                        if(empty($v['nowebet'])){
                            $agents[$k]['nowebet'] = 0;
                        }
                        if(empty($v['oldbet'])){
                            $agents[$k]['oldbet'] = 0;
                        }
                        if(empty($v['oldcbet'])){
                            $agents[$k]['oldcbet'] = 0;
                        }
                        if(empty($v['oldkbet'])){
                            $agents[$k]['oldkbet'] = 0;
                        }
                        if(empty($v['oldvbet'])){
                            $agents[$k]['oldvbet'] = 0;
                        }
                        if(empty($v['oldebet'])){
                            $agents[$k]['oldebet'] = 0;
                        }
                        if(empty($v['oldvalidbet'])){
                            $agents[$k]['oldvalidbet'] = 0;
                        }
                        if(empty($v['oldvalidcbet'])){
                            $agents[$k]['oldvalidcbet'] = 0;
                        }
                        if(empty($v['oldvalidkbet'])){
                            $agents[$k]['oldvalidkbet'] = 0;
                        }
                        if(empty($v['oldvalidvbet'])){
                            $agents[$k]['oldvalidvbet'] = 0;
                        }
                        if(empty($v['oldvalidebet'])){
                            $agents[$k]['oldvalidebet'] = 0;
                        }
                        if(empty($v['nowvalidcbet'])){
                            $agents[$k]['nowvalidcbet'] = 0;
                        }
                        if(empty($v['nowvalidkbet'])){
                            $agents[$k]['nowvalidkbet'] = 0;
                        }
                        if(empty($v['nowvalidckbet'])){
                            $agents[$k]['nowvalidckbet'] = 0;
                        }
                        if(empty($v['nowvalidebet'])){
                            $agents[$k]['nowvalidebet'] = 0;
                        }
                        if(empty($v['nowvalidvbet'])){
                            $agents[$k]['nowvalidvbet'] = 0;
                        }
                    }
                    foreach($agents as $k => $v){
                        //门槛判断
                        foreach($valid_menber as $kk => $vv){
                            if((($v['num']+$v['old_usernum']))>=$vv['effective_user']){
                                if(((floatval($v['nowcbet'])+floatval($v['nowkbet'])+floatval($v['nowckbet'])+floatval($v['nowvbet'])+floatval($v['nowebet'])+floatval($v['oldcbet'])+floatval($v['oldkbet'])+floatval($v['oldvbet'])+floatval($v['oldebet']))) >= floatval($vv['self_profit'])){
                                if(((floatval($v['oldvalidcbet'])+floatval($v['oldvalidkbet'])+floatval($v['oldvalidvbet'])+floatval($v['oldvalidebet'])+floatval($v['nowvalidcbet'])+floatval($v['nowvalidkbet'])+floatval($v['nowvalidckbet'])+floatval($v['nowvalidebet'])+floatval($v['nowvalidvbet']))) >= floatval($vv['self_effective_bet'])){
                                        $agents[$k]['sport_slay_rate'] = $vv['sport_slay_rate'];
                                        $agents[$k]['lottery_slay_rate'] = $vv['lottery_slay_rate'];
                                        $agents[$k]['video_slay_rate'] = $vv['video_slay_rate'];
                                        $agents[$k]['evideo_slay_rate'] = $vv['evideo_slay_rate'];
                                        $agents[$k]['sport_water_rate'] = $vv['sport_water_rate'];
                                        $agents[$k]['lottery_water_rate'] = $vv['lottery_water_rate'];
                                        $agents[$k]['video_water_rate'] = $vv['video_water_rate'];
                                        $agents[$k]['evideo_water_rate'] = $vv['evideo_water_rate'];
                                        $agents[$k]['self_effective_bet'] = $vv['self_effective_bet'];
                                        $agents[$k]['hire_id'] = $vv['id'];
                                    }else{
                                        $agents[$k]['sport_slay_rate'] = 0;
                                        $agents[$k]['lottery_slay_rate'] = 0;
                                        $agents[$k]['video_slay_rate'] = 0;
                                        $agents[$k]['evideo_slay_rate'] = 0;
                                        $agents[$k]['sport_water_rate'] = 0;
                                        $agents[$k]['lottery_water_rate'] = 0;
                                        $agents[$k]['video_water_rate'] = 0;
                                        $agents[$k]['evideo_water_rate'] = 0;
                                        $agents[$k]['self_effective_bet'] = 0;
                                        $agents[$k]['hire_id'] = 0;
                                    }
                                }
                            }
                        }
                            
                            
                        
                        
                    }
                    //当期可获退佣计算
                    foreach($agents as $k => $v){
                        $agents[$k]['retucash'] = $this->Cash_agent_model->getagentProfit($v);
                        if($kf_ty == 1 && $v['hire_id'] == 0){
                            unset($agents[$k]);
                        }else if($kf_ty == 0 && $v['hire_id'] > 0){
                            unset($agents[$k]);
                        }
                    }
                    //p($agents);exit;
                    
                    $bank = $this->Cash_agent_model->bank_type();
                    if (isset($_GET['savebtn'])) {
                        foreach ($agents as $aid => $agent) {
                                $map = array();
                                $model['tab'] = 'k_user_agent_record';
                                $model['type'] = 1;
                                $map['qishu_id'] = $qs;
                                $map['agent_id'] = $agent['id'];
                                $oldrecord = $this->Cash_agent_model->M($model)->where($map)->find();
                                $data_m = array();
                                $data_m['agent_id'] = $agent['id'];
                                $data_m['agent_user'] = $agent['agent_user'];
                                $data_m['agent_name'] = $agent['agent_name'];

                                $data_m['old_bet'] = floatval($agent['oldbet']);
                                $data_m['old_cbet'] = floatval($agent['oldcbet']);
                                $data_m['old_kbet'] = floatval($agent['oldkbet']);
                                $data_m['old_vbet'] = floatval($agent['oldvbet']);
                                $data_m['old_ebet'] = floatval($agent['oldebet']);
                                $data_m['now_bet'] = floatval($agent['nowcbet'])+floatval($agent['nowkbet'])+floatval($agent['nowckbet'])+floatval($agent['nowebet'])+floatval($agent['nowvbet']); //总盈利
                                $data_m['now_cbet'] = floatval($agent['nowcbet']);
                                $data_m['now_kbet'] = floatval($agent['nowkbet'])+floatval($agent['nowckbet']);
                                $data_m['now_vbet'] = floatval($agent['nowvbet']);
                                $data_m['now_ebet'] = floatval($agent['nowebet']);

                                $data_m['valid_usernum'] = floatval($agent['num']+$agent['old_usernum']);
                                $data_m['sport_slay_rate'] = floatval($agent['sport_slay_rate']);

                                $data_m['lottery_slay_rate'] = floatval($agent['lottery_slay_rate']);
                                $data_m['video_slay_rate'] = floatval($agent['video_slay_rate']);

                                $data_m['evideo_slay_rate'] = floatval($agent['evideo_slay_rate']);
                                $data_m['old_validbet'] = floatval($agent['oldvalidbet']);
                                $data_m['old_validcbet'] = floatval($agent['oldvalidcbet']);
                                $data_m['old_validkbet'] = floatval($agent['oldvalidkbet']);
                                $data_m['old_validvbet'] = floatval($agent['oldvalidvbet']);
                                $data_m['old_validebet'] = floatval($agent['oldvalidebet']);

                                $data_m['now_validbet'] = floatval($agent['nowvalidcbet']+$agent['nowvalidkbet']+$agent['nowvalidckbet']+$agent['nowvalidebet']+$agent['nowvalidvbet']);
                                $data_m['now_validcbet'] = floatval($agent['nowvalidcbet']);
                                $data_m['now_validkbet'] = floatval($agent['nowvalidkbet']+$agent['nowvalidckbet']);
                                $data_m['now_validvbet'] = floatval($agent['nowvalidvbet']);
                                $data_m['now_validebet'] = floatval($agent['nowvalidebet']);
                                $data_m['sport_water_rate'] = floatval($agent['sport_water_rate']);

                                $data_m['lottery_water_rate'] = floatval($agent['lottery_water_rate']);
                                $data_m['video_water_rate'] = floatval($agent['video_water_rate']);

                                $data_m['evideo_water_rate'] = floatval($agent['evideo_water_rate']);
                                $data_m['qishu_id'] = $qs;
                                $data_m['qishu_starttime'] = $date[0];
                                $data_m['qishu_endtime'] = $date[1];

                                $data_m['oldcash'] = floatval($agent['oldcash']); //手续费
                                $data_m['nowcash'] = floatval($agent['feemoney']);

                                $data_m['retuCash'] = floatval($agent['retucash']);
                                $data_m['site_id'] = $_SESSION['site_id'];
                                $data_m['bank'] = $bank[$agent['bankid']] . '<br>' . $agent['bankno'];
                                $data_m['info'] = "";
                                $data_m['hascash'] = 0;
                                
                                $num = $agent['hire_id'];
                                if ($num > 0) {
                                        $data_m['status'] = 0;
                                } else {
                                        $data_m['status'] = 4;
                                }
                                $map = array();
                                $model['tab'] = 'k_qishu';
                                $model['type'] = 1;
                                $map['id'] = $qs;
                                $record = $this->Cash_agent_model->M($model)->where($map)->find();
                                
                                if ($record['state'] == 1) {
                                        showmessage('该期退佣已停止！', 'back',0);
                                }

                                if (empty($oldrecord)) {
                                        $model['tab'] = 'k_user_agent_record';
                                        $model['type'] = 1;
                                        $this->Cash_agent_model->M($model)->add($data_m);
                                       
                                } else {
                                    $model['tab'] = 'k_user_agent_record';
                                    $model['type'] = 1;
                                    $map = array();
                                    $map['qishu_id'] = $qs;
                                    $map['agent_id'] = $agent['id'];
                                    $this->Cash_agent_model->M($model)->where($map)->update($data_m);
                                }
                        }
                        $do_log['log_info'] = $_SESSION['login_name'] . '存档了退佣统计,期数ID:'.$qs;
                        $this->Cash_agent_model->Syslog($do_log);
                }
                }
            }
            
        $this->add("agents",$agents);
        $this->add("qishu",$qishu);
        $this->display('cash/cash_agent/index.html');

	}
        
        //退佣统计中点击当期费用按钮第一层
        public function charge_total(){
            $qs = intval($_GET['qs_id']); //期数id
            $agentid = intval($_GET['agentid']); //代理ID
            $agentname = '"'.$_GET['agent_name'].'"'; //代理名字
            $model['tab'] = 'k_qishu';
            $model['type'] = 1;
            $map['site_id'] = $_SESSION['site_id'];
            $map['id'] = $qs;
            $map['is_delete'] = 0;
            $qishu = $this->Cash_agent_model->M($model)->where($map)->limit(1)->find();
            if (empty($qishu) && $agentid == 0) {
                showmessage('期数和代理不能问为空!','back',0);
            }
            $date[0] = $qishu['start_date'] . ' 00:00:00';
            $date[1] = $qishu['end_date'] . ' 23:59:59';
            /*****************************费用开始****************************/
            //行政費用	退傭優惠	退水優惠	存款優惠	其他費用	小計金額
            /*****************************费用开始****************************/
            //读取手续费设定
            
            $uids = array();
            $alluserfee = array();
            $map = array();
            $model['tab'] = 'k_fee_set';
            $model['type'] = 1;
            $map['site_id'] = $_SESSION['site_id'];
            $map['valid_date'] = array('<=',$date[0]);
            $map['is_delete'] = 0;
            $fee = $this->Cash_agent_model->M($model)->where($map)->order('valid_date desc')->find();
            
            
            //肯需要大内存
            @ini_set('memory_limit', '128M');
            //后台入款费用计算
            
            $inmaxcash = $fee['in_max_fee'] / ($fee['in_fee'] * 0.01);
            $outmaxcash = $fee['out_max_fee'] / ($fee['out_fee'] * 0.01);
            $field1 = 'catm_money,catm_give,atm_give,agent_id,uid,username,catm_type';
            $ht_cash = $this->Cash_agent_model->get_ht_fee($agentid,$date,'',$field1,'');
            
            foreach ($ht_cash as $cash) {
            //行政费用计算
                    ////在线存款、公司入款；人工存入项目：人工存入、负数额度归0、取消出款、其它
                    if (in_array($cash['catm_type'], array(1, 3, 4, 5, 6))) {
                            if ($cash['catm_money'] >= $inmaxcash) {
                                    $alluserfee[$cash['uid']]['xzfee'] += $inmaxcash * $fee['in_fee'] * 0.01;
                            } else {
                                    $alluserfee[$cash['uid']]['xzfee'] += $cash['catm_money'] * $fee['in_fee'] * 0.01;
                            }
                            $uids[] = $cash['uid'];
                    }
//p($alluserfee);
            //存款優惠
                    if (in_array($cash['catm_type'], array(1, 3, 4, 5, 6))) {
                            $alluserfee[$cash['uid']]['ckfee'] += $cash['catm_give'] + $cash['atm_give'];
                            $uids[] = $cash['uid'];
                    }

            //退水优惠
                    if ($cash['catm_type'] == 7) {
                            //TODO 游戏退水
                            $alluserfee[$cash['uid']]['tsfee'] += $cash['catm_money'] + $cash['catm_give'] + $cash['atm_give'];
                            $uids[] = $cash['uid'];
                    }
            //其它优惠
                    if ($cash['catm_type'] == 2) {
                            $alluserfee[$cash['uid']]['otherfee'] += $cash['catm_give'] + $cash['atm_give'];
                            $uids[] = $cash['uid'];
                    }
                    if ($cash['catm_type'] == 8) {
                            $alluserfee[$cash['uid']]['otherfee'] += $cash['catm_money'] + $cash['catm_give'] + $cash['atm_give'];
                            $uids[] = $cash['uid'];
                    }
                    
            }
            
            //前台会员公司入款计算
            $field = 'deposit_num,favourable_num,other_num,agent_user,uid';
            $qt_in_cash = $this->Cash_agent_model->get_qt_in_fee($agentname,$date,'',$field,1,'');
           

            foreach ($qt_in_cash as $in_cash) {
            //行政费用计算
                    if ($in_cash['deposit_num'] >= $inmaxcash) {
                            $alluserfee[$in_cash['uid']]['xzfee'] += $inmaxcash * $fee['in_fee'] * 0.01;
                    } else {
                            $alluserfee[$in_cash['uid']]['xzfee'] += $in_cash['deposit_num'] * $fee['in_fee'] * 0.01;
                    }
            //存款優惠
                    $alluserfee[$in_cash['uid']]['ckfee'] += $in_cash['favourable_num']+$in_cash['other_num'];
                    $uids[] = $in_cash['uid'];
            }
            
            //前台会员线上入款计算
            $field = 'deposit_num,favourable_num,other_num,agent_user,uid';
            $xs_in_cash = $this->Cash_agent_model->get_qt_in_fee($agentname,$date,'',$field,2,'');
            
            foreach ($xs_in_cash as $in_cash) {
            //行政费用计算
                    if ($in_cash['deposit_num'] >= $inmaxcash) {
                            $alluserfee[$in_cash['uid']]['xzfee'] += $inmaxcash * $fee['in_fee'] * 0.01;
                    } else {
                            $alluserfee[$in_cash['uid']]['xzfee'] += $in_cash['deposit_num'] * $fee['in_fee'] * 0.01;
                    }
            //存款優惠
                    $alluserfee[$in_cash['uid']]['ckfee'] += $in_cash['favourable_num']+$in_cash['other_num'];
                    $uids[] = $in_cash['uid'];
            }
            
            //会员申请出款费用计算
            $field1 = 'outward_money,agent_user,uid';
            $qt_out_cash = $this->Cash_agent_model->get_qt_out_fee($agentname,$date,'',$field1,'');
            foreach ($qt_out_cash as $out_cash) {
            //出款行政费用计算
                    if ($out_cash['outward_money'] >= $outmaxcash) {
                            $alluserfee[$out_cash['uid']]['xzfee'] += $outmaxcash * $fee['out_fee'] * 0.01;
                    } else {
                            $alluserfee[$out_cash['uid']]['xzfee'] += $out_cash['outward_money'] * $fee['out_fee'] * 0.01;
                    }
                    $uids[] = $out_cash['uid'];
            }
            
            //前台注册赠送的优惠
            $field1 = 'discount_num,uid';
            $qt_zc_cash = $this->Cash_agent_model->get_qt_zc_cash($agentid,$date,$field1,'');
            
            foreach ($qt_zc_cash as $v) {
                $alluserfee[$v['uid']]['otherfee'] += $v['discount_num'];
                $uids[] = $v['uid'];
            }
            //p($alluserfee);
            //天天反水
            $field1 = 'total_e_fd,uid';
            $qt_defection_cash = $this->Cash_agent_model->get_defection($agentid,$date,$field1,'');
            foreach ($qt_defection_cash as $value) {
                $alluserfee[$value['uid']]['tsfee'] += $value['total_e_fd'];
                $uids[] = $value['uid'];
            }
            
            //获取所有用户名
            $map = array();
            $model['tab'] = 'k_user';
            $model['type'] = 1;
            $map['uid'] = array('in',"(" . implode(",", $uids) . ")");
            $user = $this->Cash_agent_model->M($model)->field("uid,username")->where("uid in (" . implode(",", $uids) . ")")->select();
            foreach ($user as $value) {
                    $userinfo[$value['uid']] = $value['username'];
            }
            
            $xzfee = $tyfee = $tsfee = $ckfee = $otherfee = $allfee = 0;
            foreach ($alluserfee as $key => $value) {
                $alluserfee[$key]['userallfee'] = floatval($value['xzfee']) + floatval($value['tsfee']) + floatval($value['ckfee']) + floatval($value['otherfee']);
                $xzfee += floatval($value['xzfee']);
                $tyfee += floatval($value['tyfee']);
                $tsfee += floatval($value['tsfee']);
                $ckfee += floatval($value['ckfee']);
                $otherfee += floatval($value['otherfee']);
                $allfee += $alluserfee[$key]['userallfee'];
            
            }
             
            $this->add('qishu',$qishu);
            $this->add('agent_name',str_replace('"', '', $agentname));
            $this->add('agentid',$agentid);
            $this->add('alluserfee',$alluserfee);
            $this->add('userinfo',$userinfo);
            $this->add('xzfee',$xzfee);
            $this->add('tyfee',$tyfee);
            $this->add('tsfee',$tsfee);
            $this->add('ckfee',$ckfee);
            $this->add('otherfee',$otherfee);
            $this->add('allfee',$allfee);
            $this->display('cash/cash_agent/charge_total.html');
        }
        
        //点击行政费用
        public function charge_xz(){
            $qs = intval($_GET['qs_id']); //期数id
            $agentid = intval($_GET['agentid']); //代理id
            $uid = intval($_GET['user_id']); //用户id
            $agentname = '"'.$_GET['agent_name'].'"'; //代理名字
            
            $map = array();
            $model['tab'] = 'k_qishu';
            $model['type'] = 1;
            $map['site_id'] = $_SESSION['site_id'];
            $map['id'] = $qs;
            $map['is_delete'] = 0;
            $qishu = $this->Cash_agent_model->M($model)->where($map)->limit(1)->find();

            if (empty($qishu) && $agentid == 0) {
                showmessage('期数和代理不能问为空!','back',0);
            }
            $date[0] = $qishu['start_date'] . ' 00:00:00';
            $date[1] = $qishu['end_date'] . ' 23:59:59';
            
            $map = array();
            $model['tab'] = 'k_fee_set';
            $model['type'] = 1;
            $map['site_id'] = $_SESSION['site_id'];
            $map['valid_date'] = array('<=',$date[0]);
            $map['is_delete'] = 0;
            $fee = $this->Cash_agent_model->M($model)->where($map)->order('valid_date desc')->find();
            
            //后台入款
            $inmaxcash = $fee['in_max_fee'] / ($fee['in_fee'] * 0.01);
            $outmaxcash = $fee['out_max_fee'] / ($fee['out_fee'] * 0.01);
            $field1 = 'catm_money as money,catm_give,atm_give,agent_id,uid,username,catm_type,updatetime as cash_date';
            $ht_cash = $this->Cash_agent_model->get_ht_fee($agentid,$date,'',$field1,'',$uid);
            
            foreach ($ht_cash as $cash) {
                $xzfee[] = $cash;
                $username = $cash['username'];
            }
            //前台会员公司入款计算
            $field = 'deposit_num as money,favourable_num,other_num,agent_user,uid,do_time as cash_date';
            $qt_in_cash = $this->Cash_agent_model->get_qt_in_fee($agentname,$date,'',$field,1,'',$uid);
            foreach ($qt_in_cash as $in_cash) {
                $in_cash['catm_type'] = 11;
                $xzfee[] = $in_cash;
            }
            
            //前台会员线上入款计算
            $field = 'deposit_num as money,favourable_num,other_num,agent_user,uid,do_time as cash_date';
            $xs_in_cash = $this->Cash_agent_model->get_qt_in_fee($agentname,$date,'',$field,2,'',$uid);
            
            foreach ($xs_in_cash as $in_cash) {
                $in_cash['catm_type'] = 11;
                $xzfee[] = $in_cash;
            }
            
            //会员申请出款费用计算
            $field1 = 'outward_money as money,agent_user,uid,do_time as cash_date';
            $qt_out_cash = $this->Cash_agent_model->get_qt_out_fee($agentname,$date,'',$field1,'',$uid);
            foreach ($qt_out_cash as $out_cash) {
                    $out_cash['catm_type'] = 12;
                    $xzfee[] = $out_cash;
            }
            
            foreach ($xzfee as $k=>$value) {
                if ($value['catm_type'] == 12) {
                        $xzfee[$k]['fee'] = $fee['out_fee'];
                        if ($value['money'] >= $outmaxcash) {
                                $xzfee[$k]['fee_a'] = $outmaxcash * $fee['out_fee'] * 0.01;
                        } else {
                                $xzfee[$k]['fee_a'] = $value['money'] * $fee['out_fee'] * 0.01;
                        }
                        $xzfee[$k]['inc'] = 0.00;
                        $xzfee[$k]['outc'] = $value['money'];
                        $outcash += $xzfee[$k]['outc'];
                } else if (in_array($value['catm_type'], array(1,3, 4, 5,6,11))) {
                        $xzfee[$k]['fee'] = $fee['in_fee'];
                        if ($value['money'] >= $inmaxcash) {
                                $xzfee[$k]['fee_a'] = $inmaxcash * $fee['in_fee'] * 0.01;
                        } else {
                                $xzfee[$k]['fee_a'] = $value['money'] * $fee['in_fee'] * 0.01;
                        }
                        $xzfee[$k]['inc'] = $value['money'];
                        $xzfee[$k]['outc'] = 0.00;
                        $incash += $xzfee[$k]['inc'];
                } 
                $allcash += $xzfee[$k]['fee_a'];
            }
            $this->add('xzfee',$xzfee);
            $this->add('qishu',$qishu);
            $this->add('outcash',$outcash);
            $this->add('incash',$incash);
            $this->add('allcash',$allcash);
            $this->add('username',$username);
            $this->display('cash/cash_agent/charge_xz.html');
        }
        
        //点击退水优惠
        public function charge_ts(){
            
            $qs = intval($_GET['qs_id']); //期数id
            $agentid = intval($_GET['agentid']); //代理id
            $uid = intval($_GET['user_id']); //用户id
            $agentname = '"'.$_GET['agent_name'].'"'; //代理名字
            
            $map = array();
            $model['tab'] = 'k_qishu';
            $model['type'] = 1;
            $map['site_id'] = $_SESSION['site_id'];
            $map['id'] = $qs;
            $map['is_delete'] = 0;
            $qishu = $this->Cash_agent_model->M($model)->where($map)->limit(1)->find();

            if (empty($qishu) && $agentid == 0) {
                showmessage('期数和代理不能问为空!','back',0);
            }
            $date[0] = $qishu['start_date'] . ' 00:00:00';
            $date[1] = $qishu['end_date'] . ' 23:59:59';
            
            //后台存入退水优惠
            $field1 = 'catm_money as money,catm_give,atm_give,agent_id,uid,username,catm_type,updatetime as do_time';
            $ht_cash = $this->Cash_agent_model->get_ht_fee($agentid,$date,'',$field1,'',$uid);
            foreach ($ht_cash as $cash) {
            //退水优惠
                if($cash['catm_type'] == 7){
                    $cash['total_e_fd'] = $cash['catm_money'] + $cash['catm_give'] + $cash['atm_give'];
                    $cash['rgcrts'] = $cash['total_e_fd'];
                    $tsfee[] = $cash;
                    $username = $cash['username'];
                }
            }
            
            //天天反水
            $field1 = '*';
            $qt_defection_cash = $this->Cash_agent_model->get_defection($agentid,$date,$field1,'',$uid);
            foreach ($qt_defection_cash as $value) {
                    $tsfee[] = $value;
                    $username = $value['username'];
                    $betall += $value['betall'];
                    $sp_bet += $value['sp_bet'];
                    $fc_bet += $value['fc_bet'];
                    $lebo_bet += $value['lebo_bet'];
                    $mg_bet += $value['mg_bet'];
                    $mgdz_bet += $value['mgdz_bet'];
                    $bbin_bet += $value['bbin_bet'];
                    $bbdz_bet += $value['bbdz_bet'];
                    $ag_bet += $value['ag_bet'];
                    $ct_bet += $value['ct_bet'];
                    $og_bet += $value['og_bet'];
                    $sp_fd += $value['sp_fd'];
                    $fc_fd += $value['fc_fd'];
                    $lebo_fd += $value['lebo_fd'];
                    $mg_fd += $value['mg_fd'];
                    $mgdz_fd += $value['mgdz_fd'];
                    $bbin_fd += $value['bbin_fd'];
                    $bbdz_fd += $value['bbdz_fd'];
                    $ag_fd += $value['ag_fd'];
                    $ct_fd += $value['ct_fd'];
                    $og_fd += $value['og_fd'];
                    $rgcrts += $value['rgcrts'];
                    $total_e_fd += $value['total_e_fd'];
            }
            
            $this->add('betall',$betall);
            $this->add('sp_bet',$sp_bet);
            $this->add('fc_bet',$fc_bet);
            $this->add('lebo_bet',$lebo_bet);
            $this->add('mg_bet',$mg_bet);
            $this->add('mgdz_bet',$mgdz_bet);
            $this->add('bbin_bet',$bbin_bet);
            $this->add('bbdz_bet',$bbin_bet);
            $this->add('ag_bet',$ag_bet);
            $this->add('ct_bet',$ct_bet);
            $this->add('og_bet',$og_bet);
            $this->add('sp_fd',$sp_fd);
            $this->add('fc_fd',$fc_fd);
            $this->add('lebo_fd',$lebo_fd);
            $this->add('mg_fd',$mg_fd);
            $this->add('mgdz_fd',$mgdz_fd);
            $this->add('bbin_fd',$bbin_fd);
            $this->add('bbdz_fd',$bbdz_fd);
            $this->add('ag_fd',$ag_fd);
            $this->add('ct_fd',$ct_fd);
            $this->add('og_fd',$og_fd);
            $this->add('rgcrts',$rgcrts);
            $this->add('total_e_fd',$total_e_fd);
            $this->add('tsfee',$tsfee);
            $this->add('qishu',$qishu);
            $this->add('username',$username);
            $this->display('cash/cash_agent/charge_ts.html');
        }
        
        //点击存款优惠
        public function charge_ck(){
            $qs = intval($_GET['qs_id']); //期数id
            $agentid = intval($_GET['agentid']); //代理id
            $uid = intval($_GET['user_id']); //用户id
            $agentname = '"'.$_GET['agent_name'].'"'; //代理名字
            
            $map = array();
            $model['tab'] = 'k_qishu';
            $model['type'] = 1;
            $map['site_id'] = $_SESSION['site_id'];
            $map['id'] = $qs;
            $map['is_delete'] = 0;
            $qishu = $this->Cash_agent_model->M($model)->where($map)->limit(1)->find();

            if (empty($qishu) && $agentid == 0) {
                showmessage('期数和代理不能问为空!','back',0);
            }
            $date[0] = $qishu['start_date'] . ' 00:00:00';
            $date[1] = $qishu['end_date'] . ' 23:59:59';
            
            //后台存入退水优惠
            $field1 = 'catm_money as money,catm_give,atm_give,agent_id,uid,username,catm_type,updatetime as cash_date';
            $ht_cash = $this->Cash_agent_model->get_ht_fee($agentid,$date,'',$field1,'',$uid);
            foreach ($ht_cash as $cash) {
            //退水优惠
                if(in_array($cash['catm_type'],array(1,3,4,5,6))){
                    $ckfee[] = $cash;
                    $username = $cash['username'];
                }
            }
            
            //前台会员公司入款计算
            $field = 'deposit_num as money,favourable_num as catm_give,other_num as atm_give,agent_user,uid,do_time as cash_date,username';
            $qt_in_cash = $this->Cash_agent_model->get_qt_in_fee($agentname,$date,'',$field,1,'',$uid);
            foreach ($qt_in_cash as $in_cash) {
                    $ckfee[] = $in_cash;
                    $username = $in_cash['username'];
            }
            
            //前台会员线上入款计算
            $field = 'deposit_num as money,favourable_num as catm_give,other_num as atm_give,agent_user,uid,do_time as cash_date,username';
            $xs_in_cash = $this->Cash_agent_model->get_qt_in_fee($agentname,$date,'',$field,2,'',$uid);
            
            foreach ($xs_in_cash as $in_cash) {
                    $ckfee[] = $in_cash;
                    $username = $in_cash['username'];
            }
           
            foreach ($ckfee as $k => $value) {
                $ckfee[$k]['discount'] = floatval($value['catm_give']) + floatval($value['atm_give']);
                $alld += $ckfee[$k]['discount'];
                if($value['catm_give'] == '0' && $value['atm_give'] == '0'){
                    unset($ckfee[$k]);
                }
            }
            
            $this->add('ckfee',$ckfee);
            $this->add('qishu',$qishu);
            $this->add('username',$username);
            $this->add('alld',$alld);
            $this->display('cash/cash_agent/charge_ck.html');
        }
        
        //点击其他费用
        public function charge_qt(){
            $qs = intval($_GET['qs_id']); //期数id
            $agentid = intval($_GET['agentid']); //代理id
            $uid = intval($_GET['user_id']); //用户id
            $agentname = '"'.$_GET['agent_name'].'"'; //代理名字
            
            $map = array();
            $model['tab'] = 'k_qishu';
            $model['type'] = 1;
            $map['site_id'] = $_SESSION['site_id'];
            $map['id'] = $qs;
            $map['is_delete'] = 0;
            $qishu = $this->Cash_agent_model->M($model)->where($map)->limit(1)->find();

            if (empty($qishu) && $agentid == 0) {
                showmessage('期数和代理不能问为空!','back',0);
            }
            $date[0] = $qishu['start_date'] . ' 00:00:00';
            $date[1] = $qishu['end_date'] . ' 23:59:59';
            
            //后台存入退水优惠
            $field1 = 'catm_money as money,catm_give,atm_give,agent_id,uid,username,catm_type,updatetime as cash_date';
            $ht_cash = $this->Cash_agent_model->get_ht_fee($agentid,$date,'',$field1,'',$uid);
            foreach ($ht_cash as $cash) {
            //退水优惠
                if(in_array($cash['catm_type'],array(2,8))){
                    $otherfee[] = $cash;
                    $username = $cash['username'];
                }
            }
            
            //前台注册赠送的优惠
            $field1 = 'discount_num,uid,username,cash_date';
            $qt_zc_cash = $this->Cash_agent_model->get_qt_zc_cash($agentid,$date,$field1,'',$uid);
            
            foreach ($qt_zc_cash as $v) {
                $v['catm_type'] = 20;
                $otherfee[] = $v;
                $username = $v['username'];
            }
            
            foreach($otherfee as $k => $value){
                if($value['catm_type'] == 20){
                    $otherfee[$k]['money'] = $value['discount_num'];
                }elseif($value['catm_type'] == 2 || $value['catm_type'] == 8){
                    $otherfee[$k]['money'] = $value['catm_give'];
                }
                $allfee += $otherfee[$k]['money'];
            }
            
            $this->add('otherfee',$otherfee);
            $this->add('allfee',$allfee);
            $this->add('qishu',$qishu);
            $this->add('username',$username);
            $this->add('alld',$alld);
            $this->display('cash/cash_agent/charge_qt.html');
        }
   

	//代理退佣设定列表读取
	public function endhire_list(){
		$map = array();
                $map['table'] = 'k_hire_config';
                $map['where']['site_id'] = $_SESSION['site_id'];
		$map['where']['is_delete'] = 0;
		$hire=$this->Cash_agent_model->rget($map);
		$this->add('hire',$hire);
		$this->display('cash/cash_agent/endhire_list.html');
	}
	//代理退佣有效投注金额修改
	public function endhire_edit(){
		$validCust=$this->input->post('validCust');
		if(!empty($validCust)){
			$map=array();
			$map['site_id'] = $_SESSION['site_id'];
			$data['valid_money'] = $validCust;
			$result=$this->Cash_agent_model->endhire_edit($map,$data);
			if($result){
				$log['log_info'] = '修改了退佣统计，代理退佣设定';
                $this->Cash_agent_model->Syslog($log);
                showmessage('修改成功','back');
            }else{
                showmessage('修改失败','back',0);
            }
		}
	}

	//代理退佣删除设定
	public function endhire_delete(){
		$id=$this->input->get('id');
		if(!empty($id)){
			$result=$this->Cash_agent_model->rdel('k_hire_config',array('id'=>$id));
			if($result){
				$log['log_info'] = '删除了退佣统计，代理退佣设定:'.$id;
                $this->Cash_agent_model->Syslog($log);
                showmessage('删除成功','back');
            }else{
                showmessage('删除失败','back',0);
            }
		}
	}

	//代理退佣设定
	public function show_endhire_config(){
		$id = $this->input->get('id');
		if(!empty($id)){
			$map = array();
	    	$map['table'] = 'k_hire_config';
	    	$map['where']['site_id'] = $_SESSION['site_id'];
	    	$map['where']['id'] = $id;
			$map['where']['is_delete'] = 0;
			$hire=$this->Cash_agent_model->rfind($map);
			$this->add('hire',$hire);
		}
		$this->display('cash/cash_agent/add_endhire_config.html');
	}

	//代理退佣设定修改
	public function edit_endhire_config(){
		$id = $this->input->post('id');
		$data['self_profit']=$this->input->post('self_profit');//自身盈利金额
		$data['effective_user']=$this->input->post('effective_user');//有效会员
		//体育退佣比例
		$data['sport_slay_rate'] =$this->input->post('sport_slay_rate');
		//彩票退佣比例
		$data['lottery_slay_rate']=$this->input->post('lottery_slay_rate');
		//视讯退佣比例
		$data['video_slay_rate']=$this->input->post('video_slay_rate');
		//电子退佣比例
		$data['evideo_slay_rate']=$this->input->post('evideo_slay_rate');
		//自身有效投注
		$data['self_effective_bet']=$this->input->post('self_effective_bet');
		//体育退水比例
		$data['sport_water_rate']=$this->input->post('sport_water_rate');
		//彩票退水比例
		$data['lottery_water_rate']=$this->input->post('lottery_water_rate');
		//视讯退水比例
		$data['video_water_rate']=$this->input->post('video_water_rate');
		//电子退水比例
		$data['evideo_water_rate']=$this->input->post('evideo_water_rate');
		if(empty($id)){
			//增加
			$data['site_id'] = $_SESSION['site_id'];
			$log_id=$this->Cash_agent_model->endhire_add($data);
			if($log_id){
				$log['log_info'] = '添加了退佣统计，代理退佣设定:'.$log_id;
                $this->Cash_agent_model->Syslog($log);
                $url= URL."/cash/cash_agent/endhire_list";
                showmessage('添加成功',$url);
            }else{
                showmessage('添加失败','back',0);
            }
		}else{
			//修改
			$map['id'] = $id;
			$log_id = $this->Cash_agent_model->endhire_edit($map,$data);
			if($log_id){
				$log['log_info'] = '修改了退佣统计，代理退佣设定'.$log_id;
                $this->Cash_agent_model->Syslog($log);
                $url= URL."/cash/cash_agent/endhire_list";
                showmessage('修改成功',$url);
            }else{
                showmessage('修改失败','back',0);
            }
		}
	}

	//手续费列表
	public function fee_list(){
		$map = array();
                $map['table'] = 'k_fee_set';
                $map['where']['site_id'] = $_SESSION['site_id'];
		$map['where']['is_delete'] = 0;
		$map['order'] = 'id DESC';
		$fee=$this->Cash_agent_model->rget($map);

		$this->add('fee',$fee);
		$this->display('cash/cash_agent/fee_list.html');
	}

	//手续费设定
	public function show_fee(){
		$id=$this->input->get('id');
		if(!empty($id)){
			$map = array();
	    	$map['table'] = 'k_fee_set';
	    	$map['where']['site_id'] = $_SESSION['site_id'];
	    	$map['where']['id'] = $id;
			$fee=$this->Cash_agent_model->rfind($map);
			$this->add('fee',$fee);

		}
		$this->display('cash/cash_agent/fee_add.html');
	}

	//手续费设定增加修改
	public function add_fee_list(){
		$id=$this->input->post('id');
		//入款手续费
		$data['in_fee']=$this->input->post('infeeper');
		//入款手续费上限
		$data['in_max_fee']=$this->input->post('maxinfee');
		//出款手续费
		$data['out_fee']=$this->input->post('outfeeper');
		//出款手续费上限
		$data['out_max_fee']=$this->input->post('maxoutfee');
		//生效日期
		$data['valid_date']=$this->input->post('validdate');
		$data['valid_date']=!empty($data['valid_date'])?$data['valid_date']:date('Y-m-d');
		if(empty($id)){
			//增加
			$data['site_id']=$_SESSION['site_id'];
			$result=$this->Cash_agent_model->radd("k_fee_set",$data);
			if($result){
				$log['log_info'] = '添加了退佣统计，代理退佣设定'.$index_id;
                $this->Cash_agent_model->Syslog($log);
                $url= URL."/cash/cash_agent/fee_list";
                showmessage('添加成功',$url);
            }else{
                showmessage('添加失败','back',0);
            }
		}else{
			//修改
			$map['id']=$id;
			$result=$this->Cash_agent_model->fee_list_edit($map,$data);
			if($result){
				$log['log_info'] = '修改了退佣统计，代理退佣设定'.$index_id;
                $this->Cash_agent_model->Syslog($log);
                $url= URL."/cash/cash_agent/fee_list";
                showmessage('修改成功',$url);
            }else{
                showmessage('修改失败','back',0);
            }
		}
	}
	//手续费设定删除
	public function del_fee_list(){
		$id=$this->input->get('id');
		if(!empty($id)){
			$map['id']=$id;
			$result=$this->Cash_agent_model->rdel('k_fee_set',$map);
			if($result){
				$log['log_info'] = '删除了退佣统计，代理退佣设定'.$index_id;
                $this->Cash_agent_model->Syslog($log);
                showmessage('删除成功','back');
            }else{
                showmessage('删除失败','back',0);
            }
		}
	}

    //期数管理列表
    public function end_hire_list(){
    	//读取期数
    	$map = array();
    	$map['table'] = 'k_qishu';
    	$map['where']['site_id'] = $_SESSION['site_id'];
		$map['where']['is_delete'] = 0;
		$map['order']="id DESC";
		$qishu=$this->Cash_agent_model->rget($map);
		//var_dump($qishu);die;
		$this->add("qishu",$qishu);
    	$this->display('cash/cash_agent/end_hire_list.html');
    }

     //期数设定
    public function show_qishu_list(){
    	$id=$this->input->get('id');
		if(!empty($id)){
                    $map = array();
                    $map['table'] = 'k_qishu';
                    $map['where']['id'] = $id;
                    $qishu=$this->Cash_agent_model->rfind($map);
                    $this->add('qishu',$qishu);
		}
		$this->display('cash/cash_agent/add_qishu.html');
    }

      //期数设定增加修改
    public function add_qishu_list(){
    	$id=$this->input->post('id');
    	$data['qsname']=$this->input->post('qsname');   //期数名
		$data['start_date']=$this->input->post('start_date'); //开始日期
		$data['end_date']=$this->input->post('end_date');  //结束日期
		//是否显示
		$data['is_xianshi']=intval($this->input->post('is_xianshi'));
		//是否正常
		$data['state']= intval($this->input->post('state'));
		$url= URL."/cash/cash_agent/end_hire_list";
		//判断期数是否存在
		$map=array();
		$map['table'] = 'k_qishu';
		$map["where"]['site_id']=$_SESSION['site_id'];
		$map["where"]['qsname']=$data['qsname'];
		$row=$this->Cash_agent_model->rfind($map);
                
		if(empty($id)){
			//增加
			$data['site_id'] = $_SESSION['site_id'];
			if(!empty($row)){
				showmessage('期数重复',$url);
			}
			$result=$this->Cash_agent_model->radd("k_qishu",$data);
			if($result){
				$log['log_info'] = '添加了退佣统计，期数，期数为'.$data['qsname'];
                $this->Cash_agent_model->Syslog($log);
                showmessage('添加成功',$url);
            }else{
                showmessage('添加失败',$url,0);
            }
		}else{
			//修改
			$map['table']="k_qishu";
			$map['where']='id='.$id;
			$result=$this->Cash_agent_model->rupdate($map,$data);
			if(!empty($row) && $row['id'] != $id){
				showmessage('期数重复',$url);
			}
			if($result){
				$log['log_info'] = '修改了退佣统计期数，期数为'.$data['qsname'];
                $this->Cash_agent_model->Syslog($log);
                showmessage('修改成功',$url);
            }else{
                showmessage('修改失败',$url,0);
            }
		}
	}

	//期数删除
	public function del_qishu_list(){
		$id=$this->input->get('id');
		if(!empty($id)){
			$result=$this->Cash_agent_model->rdel('k_qishu',array('id'=>$id));
			if($result){
				$log['log_info'] = '删除了退佣统计期数,期数ID为'.$id;
                $this->Cash_agent_model->Syslog($log);
                showmessage('删除成功','back');
            }else{
                showmessage('删除失败','back',0);
            }
		}
	}

	//退佣查询列表页
	public function agent_serch_list(){
		//读取全部期数
		$map = array();
    	$map['table'] = 'k_qishu';
    	$map['where']['site_id'] = $_SESSION['site_id'];
		$map['where']['is_delete'] = 0;
		$map['order'] = "id DESC";
		$qishu = $this->Cash_agent_model->rget($map);
		$this->add("qishu",$qishu);
		$this->display('cash/cash_agent/agent_serch_list.html');
	}

	//退佣查询
	public function show_agent_serch(){
		//查询条件
		$qs = $this->input->get('qs');//期数id选择
		$username = $this->input->get('username');//用户名选择
		$kf = $this->input->get('kf');//可获退佣
		$yf = $this->input->get('yf');//已获退佣
		$startnum = $this->input->get('startnum');//有效会员下限
		$endnum = $this->input->get('endnum');//有效会员上限
		$where = "1=1 ";
		$where .= " and qishu_id =".$qs;
		if ($qs){
			if ($username){
				$where.= " and agent_user like '%".$username."%' ";
			}
			if ($kf==1){
				$where.=" and retuCash > 0 ";
			}else if($kf==2){
				$where .=" and retuCash = 0 ";
			}
			if ($yf==1){
		   		$where .=" and hascash >0 ";
		   	}else if($yf==2){
				$where .=" and hascash=0 ";
		   	}
		   	if (!empty($startnum)){
   				$where .=" and valid_usernum>='".$startnum."'";
   			}
   			if (!empty($endnum)){
   				$where .=" and valid_usernum<='".$endnum."'";
   			}
   			$agentRecord = $this->Cash_agent_model->agent_serch_list($where);

   			//p($agentRecord);die;
   			foreach($agentRecord as $key=>$val){
   					$agentRecord[$key]['status']=$this->status_type($val['status']);
   			}
		}
		$map = array();
    	$map['table'] = 'k_qishu';
    	$map['where']['site_id'] = $_SESSION['site_id'];
		$map['where']['is_delete'] = 0;
		$map['order'] = "id DESC";
		$qishu=$this->Cash_agent_model->rget($map);
		$this->add("qishu",$qishu);
		$this->add("qs",$qs);
		$this->add("username",$username);
		$this->add("kf",$kf);
		$this->add("yf",$yf);
		$this->add("startnum",$startnum);
		$this->add("endnum",$endnum);
		$this->add("agentRecord",$agentRecord);
		$this->display('cash/cash_agent/agent_serch_list.html');
	}

	//代理状态
	function status_type($type){
		switch ($type) {
			case 0:
				return '未處理';
				break;
			case 1:
				return '取消';
				break;
			case 2:
				return '退傭';
				break;
			case 3:
				return '掛賬';
				break;
			case 4:
				return '未達門檻';
				break;
			case 5:
				return '未達佣金資格';
				break;
			case 6:
				return '已達門檻';
				break;
		}
	}

	//代理动作发送
	public function agent_status(){
		$ac = $this ->input ->post("ac");
		$ids = $this ->input ->post("ids");
		$data['status'] = $this ->input ->post("dz");
		$url =URL."/cash/cash_agent/agent_serch_list";
		if ($ac){
			if (!empty($ids)){
				foreach($ids as $key=>$val){
					$map=array();
					$map['table']='k_user_agent_record';
					$map['where']['id']=$val;
					$info=$this->Cash_agent_model->rfind($map);
					//p($info);die;
					unset($map);
					$map['table']='k_qishu';
					$map['where']['id']=$info['qishu_id'];
					$qs_status=$this->Cash_agent_model->rfind($map);
					if ($qs_status['state']==1){
    					showmessage('该期已被锁定',$url);
    				}
    				if ($info['type']>0){
    					showmessage('不能对同一代理进行重复操作',$url);
    				}
    				if ($data['status'] !=0&&$data['status']!=4){
    					//操作后新的已获退佣= 现有的可获退佣+已获退佣
	    				$data['hascash'] =$info['retuCash']+$info['hascash'];
	    				//操作后新的可获退佣
	    				$data['retuCash'] =0;
	    				$data['type'] =1;//已操作
	    				$befor_status =$this->status_type($info['status']);
	    				$after_status =$this->status_type($data['status']);
	    				$data['info'] ='操作者:'.$_SESSION['login_name'].'将'.$befor_status.'改为'.$after_status;
	    				$map1 =array();
						$map1['table'] ="k_user_agent_record";
						$map1['where']['id'] =$val;
						$result[] =$this->Cash_agent_model->rupdate($map1,$data);
					}
				}
				if (!empty($result)){
					$log['log_info'] = $_SESSION['login_name']."进行了退佣操作";
                	$this->Cash_agent_model->Syslog($log);
                	showmessage('操作成功',$url);
    			}
			}
		}
	}

}

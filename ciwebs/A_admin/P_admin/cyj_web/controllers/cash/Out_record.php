<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Out_record extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('cash/Out_record_model');
	}

	public function index()
	{
		//时间条件
		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');
		$timearea = $this->input->get('timearea');//时区
		$timearea = empty($timearea)?0:$timearea;
		$index_id = $this->input->get('index_id');//站点切换
		$level_id = $this->input->get('level_id');
		$small = $this->input->get('small');
		$big = $this->input->get('big');
		$out_status = $this->input->get('out_status');
		$account = $this->input->get('account');
		$page = $this->input->get('page');
		$reload = $this->input->get('reload');
		$page_num = $this->input->get('page_num');
		$reload = empty($reload)?'30':$reload;
		$jk = $this->input->get('jk');//判断是否监控页面

		$ptype = $this->input->get('ptype');   //出款请求来源

		if (!empty($start_date)) {
			$s_date = $start_date;
		} else {
			$s_date = $start_date = date("Y-m-d");
		}
		if (!empty($end_date)) {
			$e_date = $end_date;
		} else {
			$e_date = $end_date = date("Y-m-d");
		}

		 //查询时间判断
        about_limit($end_date,$start_date);

		$start_date = strtotime($start_date . ' 00:00:00') - $timearea * 3600;
		$end_date = strtotime($end_date . ' 23:59:59') - $timearea * 3600;

		$start_date = date('Y-m-d H:i:s', $start_date);
        $end_date = date('Y-m-d H:i:s', $end_date);

        $map_out .= "k_user_bank_out_record.site_id = '".$_SESSION['site_id']."' and k_user_bank_out_record.out_time >= '".$start_date."' and k_user_bank_out_record.out_time <= '".$end_date."' ";

        $map_new = array();
        if (!empty($index_id)) {
        	$map_out .= " and k_user_bank_out_record.index_id = '".$index_id."' ";
        	$map_new['index_id'] = $index_id;
            $this->add('index_id',$index_id);
        }

        if ($level_id) {
            $map_out .= " and k_user_bank_out_record.level_id = '".$level_id."' ";
            $this->add('level_id',$level_id);
        }

		if(isset($ptype) && $ptype != -1){
			$map_out .= " and k_user_bank_out_record.ptype = '".$ptype."'";
		}

		if(!empty($small)){
			$map_out .=" and k_user_bank_out_record.outward_money > '".$small."'";
		}
		if(!empty($big)){
			$map_out .=" and k_user_bank_out_record.outward_money < '".$big."'";
		}
		if (!empty($out_status)) {
			$map_out .= " and k_user_bank_out_record.out_status = '".$out_status."'";
		}
		if (!empty($account)) {
		   $map_out .= " and k_user_bank_out_record.username = '".$account."'";
		}

		    //总计
	    $all_out = $this->Out_record_model->all_report($map_out);

		$db_model = array();
		$db_model['tab'] = 'k_user_bank_out_record';
        $db_model['base_type'] = 1;
        $count = $this->Out_record_model->mcount($map_out,$db_model);
	    $perNumber = empty($page_num)?100:$page_num;
	    $page = isset($page)?$page:1;
	    $totalPage = ceil($count/$perNumber);
	    $startCount = ($page-1)*$perNumber;
	    $limit = $startCount.",".$perNumber;
	    $data = array();
		$data = $this->Out_record_model->get_out($map_out,$limit);
		$charge = $favourable_num = $expenese_num = $outward_money = $num = 0;
		foreach ($data as $key => $val) {
			$num = $num + 1;
		    $charge = $charge + $val['charge'];
		    $favourable_num = $favourable_num + $val['favourable_num'];
		    $expenese_num = $expenese_num + $val['expenese_num'];
		    $outward_money = $outward_money + $val['outward_money'];

		    $excData = json_encode($val, JSON_UNESCAPED_UNICODE);
		    $excData = str_replace('"', '--', $excData);
		    $hset[$key] = $excData;
		}
		$this->Out_record_model->in_data_redis($hset);
        //判断是否有出款
        $map_new['table'] = 'k_user_bank_out_record';
        $map_new['where']['out_status'] = 0;
        $map_new['where']['site_id'] = $_SESSION['site_id'];
	    $new_out_state = $this->Out_record_model->rfind($map_new);
	    $new_out_state = empty($new_out_state)?0:1;

        //多站点判断
	    if (!empty($_SESSION['index_id'])) {
	    	$this->add('sites_str','站点：'.$this->Out_record_model->select_sites());
	    }

	      //获取层级
	    $leveldata = $this->Out_record_model->get_level_data();
	    $this->add('leveldata',$leveldata);

        $this->add('new_url',URL.'/cash/monitor/index?gstype=1&xstype=1&outtype=1');
        $listen = $this->input->get('listen');
        $listen = empty($listen)?'1':$listen;
        $this->add('listen',$listen);
        $this->add('site_id',$_SESSION['site_id']);
	    $this->add('data',$data);
	    $this->add('all_out',$all_out);
	    $this->add('count',$count);
	    $this->add('level_id',$level_id);
	    $this->add('num',$num);
	    $this->add('charge',$charge);
	    $this->add('favourable_num',$favourable_num);
	    $this->add('expenese_num',$expenese_num);
	    $this->add('outward_money',$outward_money);
	    $this->add('reload',$reload);
	    $this->add('out_status',$out_status);
	    $this->add('timearea',$timearea);
	    $this->add('jk',$jk);
	    $this->add('s_date',$s_date);
	    $this->add('e_date',$e_date);
	    $this->add('new_out_state',$new_out_state);
	    $this->add('page',$this->Out_record_model->get_page('k_user_bank_out_record',$totalPage,$page));

		$this->add('ptype',$ptype);
		$this->display('cash/out_record.html');
	}

	//预备出款
	public function out_ready(){
		$id = $this->input->get('id');
	    $reload = $this->input->get('reload');
	    $reload = empty($reload)?'30':$reload;
		$jk = $this->input->get('jk');//判断是否来自监控
		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');
		$index_id = $this->input->get('index_id');//站点切换
		$out_status = $this->input->get('out_status');
                $listen = $this->input->get('listen');
                $listen = empty($listen)?'1':$listen;

        if ($jk == 1) {
             //监控控制参数
      	    $gstype = $this->input->get('gstype');
      	    $xstype = $this->input->get('xstype');
      	    $outtype = $this->input->get('outtype');
            $url_s = URL.'/cash/monitor/index?gstype='.$gstype.'&xstype='.$xstype.'&outtype='.$outtype.'&listen='.$listen;
        }else{
            $url_s = URL.'/cash/Out_record/index?start_date='.$start_date.'&end_date='.$end_date.'&reload='.$reload.'&index_id='.$index_id.'&out_status='.$out_status.'&listen='.$listen;
        }

		if (empty($id)) {
		    showmessage('参数错误',$url_s,0);
		}
		$out_data = $this->get_order_state($id);
		if ($out_data['out_status'] != 0) {
		   showmessage('订单状态已被更改',$url_s,0);
		}
		$dataY = $map = array();
		$map['table'] = 'k_user_bank_out_record';
        $map['where']['id'] = $id;
        $map['where']['site_id'] = $_SESSION['site_id'];

	    $dataY['out_status'] = 4;
	    $dataY['do_time'] = date('Y-m-d H:i:s');
	    $dataY['admin_user'] = $_SESSION["login_name"];

	    if ($this->Out_record_model->rupdate($map,$dataY)) {
	    	//获取会员账号
		    $log['log_info'] = '预备出款,'.$log['order_num'];
		    $log['uname'] = $out_data['username'];
		    $log['type'] = 1;
	        $this->Out_record_model->Syslog($log);
			showmessage('预备出款成功！',$url_s);

	    }else{
	        showmessage('预备出款失败！',$url_s,0);
	    }
	}

	//确定出款
	public function out_do(){
		$id = $this->input->get('id');
	    $reload = $this->input->get('reload');
	    $reload = empty($reload)?'30':$reload;
		$jk = $this->input->get('jk');//判断是否来自监控
		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');
		$am = $this->input->get('am');
		$index_id = $this->input->get('index_id');//站点切换
		$out_status = $this->input->get('out_status');
		$type = $this->input->get('type');//确定 拒绝类型
                $listen = $this->input->get('listen');
                $listen = empty($listen)?'1':$listen;
		if ($jk == 1) {
               //监控控制参数
      	    $gstype = $this->input->get('gstype');
      	    $xstype = $this->input->get('xstype');
      	    $outtype = $this->input->get('outtype');
            $url_s = URL.'/cash/monitor/index?gstype='.$gstype.'&xstype='.$xstype.'&outtype='.$outtype.'&listen='.$listen;
        }else{
            $url_s = URL.'/cash/Out_record/index?start_date='.$start_date.'&end_date='.$end_date.'&reload='.$reload.'&index_id='.$index_id.'&out_status='.$out_status.'&listen='.$listen;
        }
		if (empty($id)) {
		    showmessage('参数错误',$url_s,0);
		}

		//权限金额判断
      if ($_SESSION['quanxian'] != 'sadmin' && $am > $_SESSION['online_max']) {
	      showmessage('金额超出你的权限范围，金额要小于'.$_SESSION['online_max'],$url_s,0);
	  }

		$out_data = $this->get_order_state($id);
		if ($out_data['out_status'] == 3 || $out_data['out_status'] == 1 || $out_data['out_status'] == 2) {
		    showmessage('订单状态已被更改',$url_s,0);
		}

		if (!empty($out_data['admin_user']) && $out_data['admin_user'] != $_SESSION['login_name']) {
		    showmessage('请不要点击别人的预备出款',$url_s,0);
		}
	    $out_data['id'] = $id;
	    //获取稽核信息
	    $audit = $this->Out_record_model->get_audit($out_data['uid'],$out_data['out_time']);
	    if (!empty($audit)) {
	        $audit = array_keys($audit);
	    }

	    if ($this->Out_record_model->bank_out_do($out_data,$type,$audit)) {
            $log['log_info'] = '线上出款成功,订单号：'.$out_data['order_num'];
            if ($type == 2) {$log['log_info'] .= ',拒绝出款';}

	        $log['uname'] = $out_data['username'];
	        $log['type'] = 1;
	        $this->Out_record_model->Syslog($log);
		    usleep(10000);//延迟0.01秒
		    showmessage('线上出款成功！',$url_s);

		}else{
			$log['log_info'] = '线上出款失败,订单号：'.$out_data['order_num'];
			if ($type == 2) {$log['log_info'] .= ',拒绝出款';}

	        $log['uname'] = $out_data['username'];
	        $log['type'] = 1;
	        $this->Out_record_model->Syslog($log);
		    usleep(10000);//延迟0.01秒
		    howmessage('线上出款失败',$url_s,0);
		}
	}

    //取消出款
    public function out_cancel(){
        $remarks = $this->input->post('remarks');//取消备注
    	$id = $this->input->get('id');
	    $reload = $this->input->get('reload');
	    $reload = empty($reload)?'30':$reload;
		$jk = $this->input->get('jk');//判断是否来自监控
		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');
		$index_id = $this->input->get('index_id');//站点切换
		$out_status = $this->input->get('out_status');
                $listen = $this->input->get('listen');
                $listen = empty($listen)?'1':$listen;

		if ($jk == 1) {
               //监控控制参数
      	    $gstype = $this->input->get('gstype');
      	    $xstype = $this->input->get('xstype');
      	    $outtype = $this->input->get('outtype');
            $url_s = URL.'/cash/monitor/index?gstype='.$gstype.'&xstype='.$xstype.'&outtype='.$outtype.'&listen='.$listen;
        }else{
            $url_s = URL.'/cash/Out_record/index?start_date='.$start_date.'&end_date='.$end_date.'&reload='.$reload.'&index_id='.$index_id.'&out_status='.$out_status.'&listen='.$listen;
        }

		if (empty($id)) {
		    showmessage('参数错误',$url_s,0);
		}
		$out_data = $this->get_order_state($id);
		if ($out_data['out_status'] == 3 || $out_data['out_status'] == 1 || $out_data['out_status'] == 2) {
		    showmessage('订单状态已被更改',$url_s,0);
		}

		if (!empty($out_data['admin_user']) && $out_data['admin_user'] != $_SESSION['login_name']) {
		    showmessage('请不要点击别人的预备出款',$url_s,0);
		}

		$out_data['id'] = $id;
                $out_data['remarks'] = $remarks;//取消备注
		if ($this->Out_record_model->bank_out_cancel($out_data)) {
            $log['log_info'] = '取消出款,订单号：'.$out_data['order_num'];
	        $log['uname'] = $out_data['username'];
	        $log['type'] = 1;
	        $this->Out_record_model->Syslog($log);
	    	usleep(10000);//延迟0.01秒
	        showmessage('取消出款成功！',$url_s);
		}else{
			$log['log_info'] = '取消出款失败,订单号：'.$out_data['order_num'];
			$log['uname'] = $out_data['username'];
	        $log['type'] = 1;
	        $this->Out_record_model->Syslog($log);
	    	usleep(10000);//延迟0.01秒
	        showmessage('取消出款失败！',$url_s,0);
		}
    }

    //获取订单状态
    function get_order_state($id){
        $map = array();
        $map['table'] = 'k_user_bank_out_record';
        $map['select'] = 'out_status,uid,order_num,charge,favourable_num,expenese_num,outward_money,balance,out_balance,outward_num,audit_id,username,admin_user,out_time';
        $map['where']['id'] = $id;
        return $this->Out_record_model->rfind($map);
    }


	//修改手续费
	public function charge(){
		$id = $this->input->post('id');
		$charge = $this->input->post('charge');
		$jk = $this->input->get('jk');

		if ($jk == 1) {
			   //监控控制参数
      	    $gstype = $this->input->get('gstype');
      	    $xstype = $this->input->get('xstype');
      	    $outtype = $this->input->get('outtype');
            $url_s = URL.'/cash/monitor/index?gstype='.$gstype.'&xstype='.$xstype.'&outtype='.$outtype;
        }else{
            $url_s = URL.'/cash/out_record/index';
        }

        if(empty($id)){
			showmessage('参数错误',$url_s,0);
		}
		$map = array();
		$map['table'] = 'k_user_bank_out_record';
		$map['select'] = 'charge,outward_money,username,order_num';
		$map['where']['id'] = $id;
		$map['where']['site_id'] = $_SESSION['site_id'];
		$Money = $this->Out_record_model->rfind($map);

		if($charge < 0){
			showmessage('手续费不能小于0！',$url_s,0);
		}
		if($charge > ($Money['charge']+$Money['outward_money'])){
			showmessage('手续费不能大于真正出款额度',$url_s,0);
		}
		$data_cha = array();
		$data_cha['outward_money'] = $Money['outward_money']-$charge+$Money['charge'];
		$data_cha['charge'] = $charge;

		$map_u = array();
		$map_u['table'] = 'k_user_bank_out_record';
		$map_u['where']['id'] = $id;
		$map_u['where']['site_id'] = $_SESSION['site_id'];

		if($this->Out_record_model->rupdate($map_u,$data_cha)){
			$log['log_info'] = '修改出款手续费,'.$Money['order_num'];
			$log['uname'] = $Money['username'];
	        $log['type'] = 1;
	        $this->Out_record_model->Syslog($log);
			showmessage('手续费修改成功！',$url_s);
		}else{
			$log['log_info'] = '修改出款手续费失败,ID：'.$id;
	        $this->Out_record_model->Syslog($log);
			showmessage('手续费修改失败！',$url_s,0);
		}
	}

	//行政费
	public function expenese_num(){
        $id = $this->input->post('id');
        $expenese_num = $this->input->post('expenese_num');
        $is_fav = $this->input->post('is_fav');
        $jk = $this->input->get('jk');

		if ($jk == 1) {
               //监控控制参数
      	    $gstype = $this->input->get('gstype');
      	    $xstype = $this->input->get('xstype');
      	    $outtype = $this->input->get('outtype');
            $url_s = URL.'/cash/monitor/index?gstype='.$gstype.'&xstype='.$xstype.'&outtype='.$outtype;
        }else{
            $url_s = URL.'/cash/out_record/index';
        }

        if(empty($id)){
			showmessage('参数错误',$url_s,0);
		}
		$map = array();
		$map['table'] = 'k_user_bank_out_record';
		$map['select'] = 'outward_num,outward_money,favourable_out,favourable_num,expenese_num,username,order_num';
		$map['where']['id'] = $id;
		$map['where']['site_id'] = $_SESSION['site_id'];
		$Money = $this->Out_record_model->rfind($map);
		if($expenese_num<0){
			showmessage('行政费不能小于0！',$url_s,0);
		}

		if($expenese_num > ($Money['expenese_num']+$Money['outward_money'])){
			showmessage('行政费不能大于真正出款额度',$url_s,0);
		}

		//判断是否取消优惠
		$data_e = array();
		if ($Money['favourable_out'] == '1' && $is_fav == 0) {
			$data_e['outward_money'] = $Money['outward_money'] + $Money['favourable_num'];
			$data_e['favourable_out'] = 0;
			$data_e['favourable_num'] = 0;
		}else{
			$data_e['outward_money'] = $Money['outward_money'];
		}

		$data_e['outward_money'] = $data_e['outward_money']-$expenese_num+$Money['expenese_num'];
		$data_e['expenese_num'] = $expenese_num;
        $map_u = array();
        $map_u['table'] = 'k_user_bank_out_record';
        $map_u['where']['id'] = $id;
		$map_u['where']['site_id'] = $_SESSION['site_id'];

		if($this->Out_record_model->rupdate($map_u,$data_e)){
			$log['log_info'] = '修改行政费成功,'.$Money['order_num'];
			$log['uname'] = $Money['username'];
	        $log['type'] = 1;
	        $this->Out_record_model->Syslog($log);
			showmessage('行政费修改成功！',$url_s);

		}else{
			$log['log_info'] = '修改行政费失败,ID：'.$id;
	        $this->Out_record_model->Syslog($log);
			showmessage('行政费修改失败！',$url_s,0);
		}
	}

    //会员出入款信息
    public function user_cash_ajax(){
        $uid = $this->input->get('uid');
        if ($this->input->is_ajax_request() && !empty($uid)) {
	        $this->load->model('cash/User_cash_model');
		    //会员入款信息
			$userBankin = $this->User_cash_model->user_cash_All($uid);
		    //会员出款
		    $userBankout = $this->User_cash_model->user_out_All($uid);

			//最近3笔入款
			$data2 = $this->Out_record_model->get_user_3($uid);
			foreach ($data2 as $key => $val) {
			    $data2[$key]['cash_type'] = $this->cash_type($val['cash_type']);
			}

			$value = array();
			$value['in_all_money'] = $userBankin['money'];//入款总额
			$value['count_in'] = $userBankin['num']+ '笔';
			$value['out_all_money'] = $userBankout['money'];
			$value['count_out'] = $userBankout['num']+ '笔';
			$value['owen_money'] = $userBankin['money'] - $userBankout['money'];
			$value['data1'] = $data2[0];
			$value['data2'] = $data2[1];
			$value['data3'] = $data2[2];
			echo json_encode($value);
			exit();
	    }
	}

	//获取会员账号信息
	public function user_data_ajax(){
        $uid = $this->input->get('uid');
        if ($this->input->is_ajax_request() && !empty($uid)) {
        	$map = array();
        	$map['table'] = 'k_user';
        	$map['where']['uid'] = $uid;
        	$map['where']['site_id'] = $_SESSION['site_id'];
            $data = $this->Out_record_model->rfind($map);

	        $data['city']=explode("-",$data['pay_address']);
	        $data['pay_card']= $this->bank_type($data['pay_card']);
	        exit(json_encode($data));
        }
	}
    //银行类型
	function bank_type($type) {
		switch ($type) {
			case '1':
				return '中國銀行';
				break;
			case '2':
				return '中國工商銀行';
				break;
			case '3':
				return '中國建設銀行';
				break;
			case '4':
				return '中國招商銀行';
				break;
			case '5':
				return '中國民生銀行';
				break;
			case '7':
				return '中國交通銀行';
				break;
			case '8':
				return '中國邮政銀行';
				break;
			case '9':
				return '中國农业銀行';
				break;
			case '10':
				return '華夏銀行';
				break;
			case '11':
				return '浦發銀行';
				break;
			case '12':
				return '廣州銀行';
				break;
			case '13':
				return '北京銀行';
				break;
			case '14':
				return '平安銀行';
				break;
			case '15':
				return '杭州銀行';
				break;
			case '16':
				return '溫州銀行';
				break;
			case '17':
				return '中國光大銀行';
				break;
			case '18':
				return '中信銀行';
				break;
			case '19':
				return '浙商銀行';
				break;
			case '20':
				return '漢口銀行';
				break;
			case '21':
				return '上海銀行';
				break;
			case '22':
				return '廣發銀行';
				break;
			case '23':
				return '农村信用社';
				break;
			case '24':
				return '深圳发展银行';
				break;
			case '25':
				return '渤海银行';
				break;
			case '26':
				return '东莞银行';
				break;
			case '27':
				return '宁波银行';
				break;
			case '28':
				return '东亚银行';
				break;
			case '29':
				return '晋商银行';
				break;
			case '30':
				return '南京银行';
				break;
			case '31':
				return '广州农商银行';
				break;
			case '32':
				return '上海农商银行';
				break;
			case '33':
				return '珠海农村信用合作联社';
				break;
			case '34':
				return '顺德农商银行';
				break;
			case '35':
				return '尧都区农村信用联社';
				break;
			case '36':
				return '浙江稠州商业银行';
				break;
			case '37':
				return '北京农商银行';
				break;
			case '38':
				return '重庆银行';
				break;
			case '39':
				return '广西农村信用社';
				break;
			case '40':
				return '江苏银行';
				break;
			case '41':
				return '吉林银行';
				break;
			case '42':
				return '成都银行';
				break;
			case '50':
				return '兴业银行';
				break;
			case '100':
				return '支付宝';
				break;
			case '101':
				return '微信支付';
				break;
			case '102':
				return '财付通';
				break;
		}
	}

    //类型
	function cash_type($type){
	    switch($type)
		{
			case 1:
			  return "額度轉換";
			  break;
			case 5:
			   return "入款";
			  break;
			case 6:
			   return "入款";
			  break;
			case 7:
			   return "入款";
			  break;
			case 9:
			   return "优惠退水";
			  break;
			case 10:
			   return "在线存款";
			  break;
			case 11:
			   return "公司入款";
			  break;
			case 12:
			   return "存入取出";
			  break;
			case 13:
			   return "优惠冲销";
			  break;
		}
	}

	//导出Excel表格
	/*public function out_record_download() {
		//$this->load->library('PHPExcel');
		$data = $this->Out_record_model->out_data_redis();

		//$objPHPExcel = new PHPExcel();

		$name = '出款列表-' . date("Y-m-d H:i:s",time());    //生成的Excel文件文件名

		//以下是一些设置 ，作者  标题等
		$objPHPExcel->getProperties()->setCreator("user")
									 ->setTitle("outRecord")
									 ->setSubject("outRecord")
									 ->setDescription("outRecord")
									 ->setKeywords("excel")
		                             ->setCategory("result file");

		$objActSheet = $objPHPExcel->setActiveSheetIndex(0);
        //设置列宽度
        $objActSheet->getColumnDimension('A')->setWidth(20);
        $objActSheet->getColumnDimension('B')->setWidth(10);
        $objActSheet->getColumnDimension('C')->setWidth(12);
        $objActSheet->getColumnDimension('D')->setWidth(15);
        $objActSheet->getColumnDimension('E')->setWidth(8);
        $objActSheet->getColumnDimension('F')->setWidth(12);
        $objActSheet->getColumnDimension('G')->setWidth(10);
        $objActSheet->getColumnDimension('H')->setWidth(12);
        $objActSheet->getColumnDimension('I')->setWidth(10);
        $objActSheet->getColumnDimension('J')->setWidth(12);
        $objActSheet->getColumnDimension('K')->setWidth(12);
        $objActSheet->getColumnDimension('L')->setWidth(12);
        $objActSheet->getColumnDimension('M')->setWidth(18);
        $objActSheet->getColumnDimension('N')->setWidth(8);
        $objActSheet->getColumnDimension('O')->setWidth(10);
        $objActSheet->getColumnDimension('P')->setWidth(15);
        //设置行高
		$objActSheet->getRowDimension(1)->setRowHeight(30);
		$objActSheet->getRowDimension(2)->setRowHeight(20);

		//设置字体大小
		$objActSheet->getStyle('A1:P1')->getFont()->setSize(12);
		//设置粗体
		$objActSheet->getStyle('A1:P2')->getFont()->setBold(true);

        //以下就是对处理Excel里的数据， 横着取数据，其他基本都不要改

        $objActSheet->setCellValue('A1', $name);
        $objActSheet->mergeCells("A1:P1");
		$num = 2;
        $objActSheet->setCellValue('A' . $num, '站别')
					->setCellValue('B' . $num, '層級')
					->setCellValue('C' . $num, '代理商')
					->setCellValue('D' . $num, '會員帳號')
					->setCellValue('E' . $num, '首次')
					->setCellValue('F' . $num, '提出額度')
					->setCellValue('G' . $num, '手續費')
					->setCellValue('H' . $num, '優惠金額')
					->setCellValue('I' . $num, '行政費')
					->setCellValue('J' . $num, '出款金額')
					->setCellValue('K' . $num, '账户余额')
					->setCellValue('L' . $num, '優惠扣除')
					->setCellValue('M' . $num, '出款日期')
					->setCellValue('N' . $num, '稽核')
					->setCellValue('O' . $num, '状态')
					->setCellValue('P' . $num, '操作者');
        foreach($data as $k => $v){
         	$num += 1;
         	$allnum = $allnum + 1;
         	$charge = $charge + $v['charge'];
		    $favourable_num = $favourable_num + $v['favourable_num'];
		    $expenese_num = $expenese_num + $v['expenese_num'];
		    $outward_money = $outward_money + $v['outward_money'];

         	$v['outward_style'] = $v['outward_style'] == 1 ? '是' : '否';
         	$v['favourable_out'] = $v['favourable_out'] == 1 ? '是' : '否';
         	$v['out_jihe'] = $v['out_status'] == 0 ? '稽核' : '--';
         	switch ($v['out_status']) {
         		case 1:
         			$v['out_status'] = '已出款';
         			break;
         		case 2:
         			$v['out_status'] = '已拒绝';
         			break;
         		case 3:
         			$v['out_status'] = '取消';
         			break;
         		case 4:
         			$v['out_status'] = '正在出款';
         			break;
         		default:
         			$v['out_status'] = '未处理';
         			break;
         	}

         	//Excel的第A列，do_url是你查出数组的键值，下面以此类推
         	$objActSheet->setCellValue('A' . $num, $v['do_url'])
						->setCellValue('B' . $num, $v['level_des'])
						->setCellValue('C' . $num, $v['agent_user'])
						->setCellValue('D' . $num, $v['username'])
						->setCellValue('E' . $num, $v['outward_style'])
						->setCellValue('F' . $num, $v['outward_num'])
						->setCellValue('G' . $num, $v['charge'])
						->setCellValue('H' . $num, $v['favourable_num'])
						->setCellValue('I' . $num, $v['expenese_num'])
						->setCellValue('J' . $num, $v['outward_money'])
						->setCellValue('K' . $num, $v['balance'])
						->setCellValue('L' . $num, $v['favourable_out'])
						->setCellValue('M' . $num, $v['out_time'])
						->setCellValue('N' . $num, $v['out_jihe'])
						->setCellValue('O' . $num, $v['out_status'])
						->setCellValue('p' . $num, $v['admin_user']);
			if ($num % 2 == 0) {
				$objActSheet->getStyle("A$num:P$num")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        		$objActSheet->getStyle("A$num:P$num")->getFill()->getStartColor()->setARGB('00E8ECF0');
			}else{
				$objActSheet->getStyle("A$num:P$num")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        		$objActSheet->getStyle("A$num:P$num")->getFill()->getStartColor()->setARGB('00FFFFFF');
			}

        }
        //合并单元格
        $next = $num + 1;
        $allstr = "总计:笔数：$allnum 手续费：$charge 優惠金額：$favourable_num 行政费：$expenese_num 总出款金額：$outward_money";
        $objActSheet->setCellValue('A'.$next, $allstr);
        $objActSheet->mergeCells("A$next:P$next");

        //设置水平对齐方式
        $objActSheet->getStyle("A1:P$next")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //设置垂直对齐方式
        $objActSheet->getStyle("A1:P$next")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        //设置表格边框样式
        $objActSheet->getStyle("A1:P$next")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objActSheet->getStyle("A1:P$next")->getBorders()->getAllBorders()->getColor()->setARGB('00FADCDC');
        //背景色
        $objActSheet->getStyle('A1:P2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objActSheet->getStyle('A1:P2')->getFill()->getStartColor()->setARGB('00FADCDC');
        $objActSheet->getStyle("F2:F$num")->getFill()->getStartColor()->setARGB('00127507');

        //设置字体颜色
		$objActSheet->getStyle("F1:F$num")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

        $objPHPExcel->getActiveSheet()->setTitle('outRecord');
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$name.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
	}*/

	public function out_record_download() {
		$data = $this->Out_record_model->out_data_redis();
		if (empty($data)) {
		    showmessage('导出数据为空！','back',0);
		}

		$name = '出款列表';    //生成的Excel文件文件名
		$title = array(
			'站别-do_url-20',   //标题-键名-单元格宽度
			'層級-level_des-10',
			'代理商-agent_user-12',
			'會員帳號-username-15',
			'首次-outward_style-8',
			'提出額度-outward_num-12',
			'手續費-charge-10',
			'優惠金額-favourable_num-12',
			'行政費-expenese_num-10',
			'出款金額-outward_money-12',
			'账户余额-balance-12',
			'優惠扣除-favourable_out-12',
			'出款日期-out_time-18',
			'稽核-out_jihe-8',
			'状态-out_status-10',
			'操作者-admin_user-15'
			);
		foreach($data as $k => &$v){
         	$allnum = $allnum + 1;
         	$charge = $charge + $v['charge'];
		    $favourable_num = $favourable_num + $v['favourable_num'];
		    $expenese_num = $expenese_num + $v['expenese_num'];
		    $outward_money = $outward_money + $v['outward_money'];

		    $v['outward_style'] = $v['outward_style'] == 1 ? '是' : '否';
         	$v['favourable_out'] = $v['favourable_out'] == 1 ? '是' : '否';
         	$v['out_jihe'] = $v['out_status'] == 0 ? '稽核' : '--';
         	switch ($v['out_status']) {
         		case 1:
         			$v['out_status'] = '已出款';
         			break;
         		case 2:
         			$v['out_status'] = '已拒绝';
         			break;
         		case 3:
         			$v['out_status'] = '取消';
         			break;
         		case 4:
         			$v['out_status'] = '正在出款';
         			break;
         		default:
         			$v['out_status'] = '未处理';
         			break;
         	}
		}
		//$color = array('F'=>'00127507');
		$allstr = "总计:笔数：$allnum 手续费：$charge 優惠金額：$favourable_num 行政费：$expenese_num 总出款金額：$outward_money";
		$this->out_Excel_download($data,$title,$name,$allstr,20,$color);

	}

	/**
	 * [out_Excel_download 导出EXCEL表格]
	 * @param  [array]  $data   [导出数据]
	 * @param  [array]  $title  [列标题-键名-列宽]
	 * @param  string  $name    [表格名称]
	 * @param  string  $allstr  [最下方总计]
	 * @param  integer $height  [内容行高]
	 * @param  string  $color   [单独列背景色]
	 */
	public function out_Excel_download($data,$title,$name='',$allstr='',$height=15,$color='') {
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();
		$name = $name . '_' . date("YmdHis");    //生成的Excel文件文件名

		$arr = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		foreach ($title as $key => $value) {
			$kv = $arr[$key];
			$title[$kv] = $value;
			unset($title[$key]);
		}

		$lastk = count($title) - 1;

		 /*以下是一些设置 ，作者  标题等*/
		$objPHPExcel->getProperties()->setCreator("user")
									 ->setTitle("outRecord")
									 ->setSubject("outRecord")
									 ->setDescription("outRecord")
									 ->setKeywords("excel")
		                             ->setCategory("result file");

		$objActSheet = $objPHPExcel->setActiveSheetIndex(0);

		/*以下就是对处理Excel里的数据， 横着取数据，其他基本都不要改*/

        $objActSheet->setCellValue('A1', $name);
        $objActSheet->mergeCells("A1:".$arr[$lastk]."1");
		$num = 2;

		foreach ($title as $k => $v) {
			$k_v = explode('-', $v);
			if(!empty($k_v[2])){
				$objActSheet->getColumnDimension($k)->setWidth($k_v[2]);
			}else{
			 	//设置列自适应宽度   内容为中文时此设置无效
	        	$objActSheet->getColumnDimension($k)->setAutoSize(true);
			}
			$objActSheet->setCellValue($k . $num, "$k_v[0]");
		}


        foreach($data as $k => $v){
         	$num += 1;
         	//Excel的第A列，do_url是你查出数组的键值，下面以此类推
         	foreach ($title as $ke => $va) {
         		$k_v = explode('-', $va);
         		$objActSheet->setCellValue($ke . $num, $v[$k_v[1]]);
         		//自动换行
				$objActSheet->getStyle($ke . $num)->getAlignment()->setWrapText(true);
         	}

         	//内容行高
			$objActSheet->getRowDimension($num)->setRowHeight($height);

			if ($num % 2 == 0) {
				$objActSheet->getStyle("A$num:".$arr[$lastk].$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        		$objActSheet->getStyle("A$num:".$arr[$lastk].$num)->getFill()->getStartColor()->setARGB('00E8ECF0');
			}else{
				$objActSheet->getStyle("A$num:".$arr[$lastk].$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        		$objActSheet->getStyle("A$num:".$arr[$lastk].$num)->getFill()->getStartColor()->setARGB('00FFFFFF');
			}

        }
        $next = $num + 1;
        $objActSheet->setCellValue('A'.$next, $allstr);
        $objActSheet->mergeCells("A$next:".$arr[$lastk].$next);
        //设置行高
		$objActSheet->getRowDimension(1)->setRowHeight(30);
		$objActSheet->getRowDimension(2)->setRowHeight(20);
		//设置字体大小
		$objActSheet->getStyle('A1:'.$arr[$lastk].'1')->getFont()->setSize(12);
		//设置粗体
		$objActSheet->getStyle('A1:'.$arr[$lastk].'2')->getFont()->setBold(true);

        //设置水平对齐方式
        $objActSheet->getStyle("A1:".$arr[$lastk].$next)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //设置垂直对齐方式
        $objActSheet->getStyle("A1:".$arr[$lastk].$next)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        //设置表格边框样式
        $objActSheet->getStyle("A1:".$arr[$lastk].$next)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objActSheet->getStyle("A1:".$arr[$lastk].$next)->getBorders()->getAllBorders()->getColor()->setARGB('00FADCDC');
        //背景色
        $objActSheet->getStyle('A1:'.$arr[$lastk].'2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objActSheet->getStyle('A1:'.$arr[$lastk].'2')->getFill()->getStartColor()->setARGB('00FADCDC');
        if (!empty($color)) {
        	foreach ($color as $key => $value) {
	        	$objActSheet->getStyle($key."2:$key".$num)->getFill()->getStartColor()->setARGB($value);
	        	//设置字体颜色
				$objActSheet->getStyle($key."2:$key".$num)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
	        }
        }

        $objPHPExcel->getActiveSheet()->setTitle('outRecord');
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$name.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
	}

}

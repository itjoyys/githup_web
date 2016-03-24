<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_record extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('cash/Bank_record_model');
	}
	public function ring(){
		$d=$this->input->get(['in','rktype','out','cktype']);
		$this->display('cash/ring.html');
	}
    //公司入款 线上入款
	public function index()
	{
		$into_style = $this->input->get('into_style');//公司入款线上入款类型
		//时间条件
		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');
		$timearea = $this->input->get('timearea');//时区
		$timearea = empty($timearea)?0:$timearea;

        $listen = $this->input->get('listen');
		$listen = empty($listen)?'1':$listen;

		$index_id = $this->input->get('index_id');//站点切换
		$small = $this->input->get('small');
		$big = $this->input->get('big');
		$account = $this->input->get('account');
		$reload = $this->input->get('reload');
		$reload = empty($reload)?'30':$reload;
		$status = $this->input->get('status');
		$page = $this->input->get('page');
		$order = $this->input->get('order');
        $jk = $this->input->get('jk');
        $page_num = $this->input->get('page_num');//每页数量
        $map['site_id'] = $_SESSION['site_id'];
        $map['into_style'] = $into_style;
        $where ="k_user_bank_in_record.into_style ='".$into_style."' and k_user_bank_in_record.site_id = '".$_SESSION['site_id']."'";
		
		$ptype = $this->input->get('ptype');   //公司入款来源

		$pay_id = $this->input->get('pay_id');   //入款商户

		$bid = $this->input->get('bid');   //收款方式

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

        //时区转换
		$start_date = strtotime($start_date . ' 00:00:00') - $timearea * 3600;
		$end_date = strtotime($end_date . ' 23:59:59') - $timearea * 3600;
		$start_date = date('Y-m-d H:i:s', $start_date);
        $end_date = date('Y-m-d H:i:s', $end_date);

        switch ($status) {
        	case '3':
        	    //无优惠
        		$map['favourable_num'] = 0;
		        $map['other_num'] = 0;
		        $where .=" and (k_user_bank_in_record.favourable_num = '0' and k_user_bank_in_record.other_num = '0')";
        		break;
        	case '4':
        	    //有优惠
        		$map['favourable_num+other_num'] = array(">",0);
        		$where .=" and (k_user_bank_in_record.favourable_num + k_user_bank_in_record.other_num) > '0' ";

        		break;
        	case '-1':
        		break;
        	case '0':
        	    //未处理
		        $where .=" and k_user_bank_in_record.make_sure = '".$status."' ";
	            $map['make_sure'] = $status;
        		break;
        	case  '1' || '2':
        	    //确定 取消 未处理
		        $where .=" and k_user_bank_in_record.make_sure = '".$status."'";
	            $map['make_sure'] = $status;
        		break;
        }
        //线上入款2 公司入款1
        if ($into_style == 2) {
            $map['in_date'] = array(array(">=",$start_date),array("<=",$end_date));
		    $where .=" and k_user_bank_in_record.in_date >= '".$start_date."' and k_user_bank_in_record.in_date <= '".$end_date."'";

			if(!empty($pay_id)){    //商户ID查询条件
				$map['pay_id'] = $pay_id;
				$where .=" and k_user_bank_in_record.pay_id = '".$pay_id."'";
			}
        }elseif($into_style == 1){
            if($status == 1){
			    $map['log_time'] = array(
					              array(">=",$start_date),
					              array("<=",$end_date));
			    $where .=" and k_user_bank_in_record.log_time >= '".$start_date."' and k_user_bank_in_record.log_time <= '".$end_date."'";
			}else{
				$map['log_time'] = array(
					              array(">=",$start_date),
					              array("<=",$end_date));
			    $where .=" and k_user_bank_in_record.log_time >= '".$start_date."' and k_user_bank_in_record.log_time <= '".$end_date."'";
			}
			
			if(isset($bid) && $bid != -1){
				$map['bid'] = $bid;
				$where .=" and k_user_bank_in_record.bid = '".$bid."'";
			}
        }

		//订单检索
		if(!empty($order)){
			$map['order_num'] = $order;
			$where .=" and k_user_bank_in_record.order_num = '".$order."'";
		}
		if(!empty($small)){
			$map['deposit_money'] = array(">",$small);
			$where .=" and k_user_bank_in_record.deposit_money > '".$small."'";
		}
		if(!empty($big)){
			$map['deposit_money'] = array("<",$big);
			$where .=" and k_user_bank_in_record.deposit_money < '".$big."'";
		}
		if(!empty($account)){
			$map['username'] = $account;
			$where .=" and k_user_bank_in_record.username = '".$account."'";
		}
		if (!empty($index_id)) {
			$map['index_id'] = $index_id;
			$where .=" and k_user_bank_in_record.index_id = '".$index_id."'";
		}
		if(isset($ptype) && $ptype != -1){    //入款查询条件
			$map['ptype'] = $ptype;
			$where .=" and k_user_bank_in_record.ptype = '".$ptype."'";
		}
        $db_model['tab'] = 'k_user_bank_in_record';
        $db_model['base_type'] = 1;

        $count = $this->Bank_record_model->mcount($map,$db_model);

        //分页
		$perNumber = empty($page_num)?100:$page_num;
		$totalPage=ceil($count/$perNumber); //计算出总页数
		$page=isset($page)?$page:1;
		if($totalPage<$page){
			$page=1;
		}
		$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
		$limit=$startCount.",".$perNumber;
		if ($into_style == 2) {
            $bdata = $this->Bank_record_model->get_bankin_online($where,$limit);
            $online_type = $this->Bank_record_model->get_online_type();
		}else {
		    $bdata = $this->Bank_record_model->get_bankin_line($where,$limit);
            $bank_type = $this->Bank_record_model->get_bank_type();

            $bank_arr = $this->Bank_record_model->M(array('tab'=>'k_bank','type'=>1))->where("site_id = '".$_SESSION['site_id']."'")->select("id");
		}

        $deposit_money = $deposit_num = $other_num = 0 ;
        foreach ($bdata as $key => $val) {
            $bdata[$key]['is_first_zh'] = $this->is_first($val['is_firsttime']);
            if ($into_style == 2) {
                //$bdata[$key]['pay_type'] = $this->payment_type($val['paytype']).'(ID：'.$val['pay_id'].')';
                //$bdata[$key]['pay_type'] = $this->Bank_record_model->get_online_type($val['paytype']).'(ID：'.$val['pay_id'].')';
                $bdata[$key]['pay_type'] = $online_type[$val['paytype']]['online_bank_name'].'(ID：'.$val['pay_id'].')';
            }else{
            	$bdata[$key]['in_type_zh'] = $this->in_type($val['in_type']);
                //$bdata[$key]['bank_style_zh'] = $this->Bank_record_model->get_bank_type($val['bank_style']);
                //$bdata[$key]['bank_type_zh'] = $this->Bank_record_model->get_bank_type($val['bank_type']);
                $tmp_bank_type = $bank_arr[$val['bid']]['bank_type'];
                $bdata[$key]['bank_style_zh'] = $bank_type[$val['bank_style']]['bank_name'];
                $bdata[$key]['bank_type_zh'] = $bank_type[$tmp_bank_type]['bank_name'];
	            if ($val['in_type'] ==2 || $val['in_type'] == 3 || $val['in_type'] == 4) {
					$bdata[$key]['in_type_zh'] = $bdata[$key]['in_type_zh']."<br>".'網點：'.$val['in_atm_address'];
				}
				//判断是取消操作并获取操作时间
				if($val['make_sure'] == 2 && !empty($val['do_time'])){
					 //操作时间与系统时间的差与两小时的差距
					 $bdata[$key]['date_type'] = time() - strtotime($val['do_time']) - 7200;
				}
            }

            //总计
		    $deposit_num =$deposit_num + $val['deposit_num'];
		    $favourable_num = $favourable_num + $val['favourable_num'];
		    $other_num = $other_num + $val['other_num'];
		    $deposit_money = $deposit_money + $val['deposit_money'];
        }

        $this->Bank_record_model->in_data_redis($bdata);

        $this->add('new_add_state',$this->is_new_in($into_style));
        $this->add('listen',$listen);
        $this->add('bdata',$bdata);
        $this->add('reload',$reload);
        $this->add('timearea',$timearea);
        $this->add('new_url',URL.'/cash/monitor/index?gstype=1&xstype=1&outtype=1');

		$this->add('site_id',$_SESSION['site_id']);
        $this->add('num',$count);
        $this->add('status',$status);
        $this->add('deposit_num',$deposit_num);
        $this->add('favourable_num',$favourable_num);
        $this->add('other_num',$other_num);
        $this->add('deposit_money',$deposit_money);
        $this->add('page', $this->Bank_record_model->get_page('k_user_bank_in_record',$totalPage,$page));
        $this->add('index_id',$index_id);
	    $this->add('s_date',$s_date);
	    $this->add('e_date',$e_date);
	    $this->add('account',$account);

		$this->add('ptype',$ptype);   //入款来源
		
        //多站点判断
	    if (!empty($_SESSION['index_id'])) {
	    	$this->add('sites_str','站点：'.$this->Bank_record_model->select_sites());
	    }

	    if ($into_style == 1) {
			$company_bank = array();      //收款方式和卡号
	        foreach ($bank_arr as $key => $value) {
				if($value['is_delete'] == 0){
					$company_bank[$value['id']]['bid'] = $value['id'];
					$company_bank[$value['id']]['card_ID'] = $value['card_ID'];
					$company_bank[$value['id']]['bank_name'] = $bank_type[$value['bank_type']]['bank_name'];
				}
	        }
			$this->add('bid',$bid);
			$this->add('company_bank',$company_bank);
	        $this->display('cash/bank_record.html');
	    }elseif($into_style == 2){
	    	$this->cash_online();
	    	$paydata = $this->Bank_record_model->get_pay_id($index_id);
	    	foreach ($paydata as $key => $val) {
	    		$payment_type[$key]['online_bank_name'] = $online_type[$val['pay_type']]['online_bank_name'].'(ID：'.$val['id'].')';
	    		$payment_type[$key]['pay_id'] = $val['id'];
	    	}
	    	$this->add('pay_id',$pay_id);
	    	$this->add('payment_type',$payment_type);
	    	$this->display('cash/bank_record_online.html');
	    }

	}

	//线上入款取消状态更新
	function cash_online(){
        $db_model['tab'] = 'k_user_bank_in_record';
		$db_model['type'] = 1;

		$tmp_time = date('Y-m-d H:i:s',(time()-1800));
	    $map_no['site_id'] = $_SESSION['site_id'];
	    $map_no['into_style'] = 2;
	    $map_no['make_sure'] = '0';
	    $map_no['in_date'] = array('<=',$tmp_time);

	    $result_xs = $this->Bank_record_model->M($db_model)->where($map_no)->find();

		if(!empty($result_xs)){
		    $this->Bank_record_model->M($db_model)->where($map_no)->update(array('make_sure'=>2,'do_time'=>date('Y-m-d H:i:s')));
		}
	}

	//公司入款
	public function bank_record_do(){
	  //更改状态为取消
      $id = $this->input->get('id');
      $index_id = $this->input->get('index_id');
      $uid = $this->input->get('uid');
      $am = $this->input->get('am');
      $type = $this->input->get('type');//取消 确定
      $order_num = $this->input->get('order_num');
      $jk = $this->input->get('jk');//监控参数
      $reload = $this->input->get('reload');
      $reload = empty($reload)?'30':$reload;
      $listen = $this->input->get('listen');
      $listen = empty($listen)?'1':$listen;

      if ($jk == 1) {
      	  //监控控制参数
      	  $gstype = $this->input->get('gstype');
      	  $xstype = $this->input->get('xstype');
      	  $outtype = $this->input->get('outtype');
          $url_str = URL.'/cash/monitor/index?gstype='.$gstype.'&xstype='.$xstype.'&outtype='.$outtype.'&listen='.$listen;
      }else{
          $url_str = URL.'/cash/bank_record/index?into_style=1&reload='.$reload.'&index_id='.$index_id.'&listen='.$listen;
      }


      if (empty($id) || empty($uid) || empty($order_num) || empty($type)) {
          showmessage('参数错误',$url_str,0);
      }
      //权限金额判断
      if ($_SESSION['quanxian'] != 'sadmin' && $am > $_SESSION['online_max']) {
	      showmessage('金额超出你的权限范围，金额要小于'.$_SESSION['online_max'],$url_str,0);
	  }
      //获取会员信息
	  $uinfo = $this->get_username($id);

      switch ($type) {
	      	case 's0':
	      	   //判断状态是否更改
		      if (!$this->Bank_record_model->is_state($id,0)) {
		          showmessage('该订单状态已被更改',$url_str,0);
		      }
	  		  $map = array();
	      	  $map['id'] = $id;
	      	  $map['uid'] = $uid;
      	      //更新视讯余额
              $this->update_video_money($uinfo['username'],$uid);

	      	  $data_q = array();
			  $data_q['make_sure'] = 2;
			  $data_q['do_time'] = date('Y-m-d H:i:s');
			  $data_q['admin_user'] = $_SESSION['login_name'];
	          if ($this->Bank_record_model->bank_cancel($map,$data_q,$index_id,$order_num)) {
	              $log['log_info'] = '取消公司入款,单号：'.$order_num;
	              $log['type'] = 1;
	              $log['uname'] = $uinfo['username'];
	              $this->Bank_record_model->Syslog($log);
				  sleep(1);
				  showmessage('公司入款取消成功',$url_str);
	          }else{
	              showmessage('公司入款取消失败',$url_str,0);
	          }
	  		break;
	  	    case 's1':
               if ($this->Bank_record_model->is_state($id,1)) {
		          showmessage('该订单状态已被更改',$url_str);
		       }
		       if ($this->Bank_record_model->bank_in_do($id,$uid,$index_id,$order_num)) {
		       	   $log['log_info'] = '确定公司入款,单号：'.$order_num;
		       	   $log['type'] = 1;
	               $log['uname'] = $uinfo['username'];
	               $this->Bank_record_model->Syslog($log);

				   sleep(1);//延迟
				   showmessage('确定公司入款成功',$url_str);
		       }else{
				   sleep(1);//延迟
				   showmessage('确定公司入款失败',$url_str,0);
		       }

	  		break;
      }

	}
    //取消声音
	public function online_sound_no(){
		$id = $this->input->get('id');
		$jk = $this->input->get('jk');//监控参数
		$reload = $this->input->get('reload');
	    $reload = empty($reload)?'30':$reload;
            $listen = $this->input->get('listen');
	    $listen = empty($listen)?'1':$listen;

	    if ($jk == 1) {
	    	 //监控控制参数
      	    $gstype = $this->input->get('gstype');
      	    $xstype = $this->input->get('xstype');
      	    $outtype = $this->input->get('outtype');
            $url_str = URL.'/cash/monitor/index?gstype='.$gstype.'&xstype='.$xstype.'&outtype='.$outtype.'&listen='.$listen;
	    }else{
		    $url_str = URL.'/cash/bank_record/index?into_style=2&reload='.$reload.'&listen='.$listen;
		}
		if(empty($id)){
            showmessage('参数错误',$url_str,0);
		}else{
			$map['table'] = 'k_user_bank_in_record';
			$map['where']['id'] = $id;
			$map['where']['site_id'] = $_SESSION['site_id'];
            if($this->Bank_record_model->rupdate($map,array('status'=>1))){
                showmessage('操作成功',$url_str);
            }else{
            	showmessage('操作失败',$url_str,0);
            }
		}

	}

    //是否有新入款
	function is_new_in($type){
	    //判断是否有新入款信息
		$strIn = date('Y-m-d').'%';
		$map = array();
		$map['table'] = 'k_user_bank_in_record';
		$map['where']['site_id'] = $_SESSION['site_id'];
		$map['where']['make_sure'] = 0;
		if ($type == 2) {
		    $map['where']['status'] = 0;//线上入款 不再提醒状态为0
		}
		$map['where']['into_style'] = $type;
		$new_state = $this->Bank_record_model->rfind($map);
		$new_state = empty($new_state)?0:1;
		return $new_state;
	}

	//首存
	function is_first($type){
        if ($type) {
            return '是';
        }else{
        	return '否';
        }
	}

	//线下入款方式
	function in_type($type) {
		switch ($type) {
			case '1':
				return '网银转帐';
				break;
			case '2':
				return 'ATM自动柜员机';
				break;
			case '3':
				return 'ATM现金入款';
				break;
			case '4':
				return '银行柜台';
				break;
			case '5':
				return '手机转帐';
				break;
			case '6':
				return '支付宝转账';
				break;
			case '7':
				return '财付通';
				break;
			case '8':
				return '微信支付';
				break;
		}
	}

	//更新会员视讯余额
	function update_video_money($username,$uid){
		$this->load->library('video/Games');
		$games = new Games();
	    $data = $games->GetAllBalance($username);
	    $list = array('ag','og','mg','ct','bbin','lebo');
		$result = json_decode($data);

		if ($result->data->Code == 10017) {
		    $data_u = array();
			if (!empty($result->data->ogstatus)) {
				$data_u['og_money'] = floatval($result->data->ogbalance);
			}
			if (!empty($result->data->agstatus)) {
				$data_u['ag_money'] = floatval($result->data->agbalance);
			}
			if (!empty($result->data->mgstatus)) {
				$data_u['mg_money'] = floatval($result->data->mgbalance);
			}
			if (!empty($result->data->ctstatus)) {
				$data_u['ct_money'] = floatval($result->data->ctbalance);
			}
			if (!empty($result->data->bbinstatus)) {
				$data_u['bbin_money'] = floatval($result->data->bbinbalance);
			}
			if (!empty($result->data->lebostatus)) {
				$data_u['lebo_money'] = floatval($result->data->lebobalance);
			}
            if (!empty($data_u)) {
                $map_u = array();
	            $map_u['table'] = 'k_user';
	            $map_u['where']['uid'] = $uid;
	            $map_u['where']['site_id'] = $_SESSION['site_id'];
				$this->Bank_record_model->rupdate($map_u,$data_u);
            }
		}
	}

	//获取会员账号
	function get_username($id){
        $map = array();
        $map['table'] = 'k_user_bank_in_record';
        $map['where']['id'] = $id;
        $map['where']['site_id'] = $_SESSION['site_id'];
        return $this->Bank_record_model->rfind($map);
	}

	//Excel下载
	public function analysis_note_download(){
		$type = $this->input->get('type');
		$data = $this->Bank_record_model->out_data_redis();
		if (empty($data)) {
		    showmessage('导出数据为空！','back',0);
		}
		//p($data);
		if($type == 1){
			$name = '公司入款列表';    //生成的Excel文件文件名
			foreach ($data as $key => &$val) {

				//总计
				$allnum = $allnum + 1;
			    $deposit_num =$deposit_num + $val['deposit_num'];
			    $favourable_num = $favourable_num + $val['favourable_num'];
			    $other_num = $other_num + $val['other_num'];
			    $deposit_money = $deposit_money + $val['deposit_money'];

				$val['order_num'] = (string)$val['order_num'];

				$val['level_des'] = $val['level_des'].'/'.$val['agent_user'];

				$val['in_name'] = $val['in_name'].'/'.$val['in_date'];

				$val['bank_style_zh'] = $val['bank_style_zh'].'/'.$val['in_type_zh'];

				$val['deposit_num'] = $val['deposit_num'].'/'.$val['favourable_num'];

				$val['deposit_money'] = $val['deposit_money'].'/'.$val['remark'];

				$val['log_time'] = '(美东)系统时间'.$val['log_time'].'/操作时间'.$val['do_time'];

				$val['card_userName'] = '卡主:'.$val['card_userName'].'/ 卡号:'.$val['card_ID'].'/ 银行:'.$val['bank_type_zh'];

				$val['make_sure'] = $val['make_sure'] == 1 ? '已确认' : '已取消';

			}
			//p($data);die;
			$title = array(
				'订单号-order_num-20',   //标题-键名-单元格宽度
				'层级/代理商-level_des-30',
				'會員帳號-username-12',
				'存款人与时间-in_name-30',
				'存款银行与方式-bank_style_zh-30',
				'存入金額与优惠-deposit_num-17',
				'存入总额与备注-deposit_money-40',
				'存入銀行帳戶-card_userName-32',
				'狀態-make_sure-10',
				'首存-is_first_zh-12',
				'操縱者-admin_user-12',
				'時間-log_time-60'
				);

			//$color = array('D'=>'00127507');
			$allstr = "总计:笔数：$allnum 存入金額：$deposit_num 存款優惠：$favourable_num 其他優惠：$other_num 存入總金額：$deposit_money";
		}else{
			$name = '线上入款列表';    //生成的Excel文件文件名
			foreach ($data as $key => &$val) {

				//总计
				$allnum = $allnum + 1;
			    $deposit_num =$deposit_num + $val['deposit_num'];
			    $favourable_num = $favourable_num + $val['favourable_num'];
			    $other_num = $other_num + $val['other_num'];
			    $deposit_money = $deposit_money + $val['deposit_money'];

				$val['order_num'] = (string)$val['order_num'];

				$val['deposit_num'] = '存入金额:'.$val['deposit_num'].'/ 存款/其他优惠: '.$val['favourable_num'].'/'.$val['other_num'];

				$val['log_time'] = '(美东)系统时间'.$val['log_time'].'/操作时间'.$val['do_time'];

				if($val['make_sure'] == 0){
					$val['make_sure'] = "未支付";
				}else{
					$val['make_sure'] = $val['make_sure'] == 1 ? '已支付' : '已取消';
				}


			}
			$title = array(  //标题-键名-单元格宽度
				'層級-level_des-10',
				'订单号-order_num-20',
				'代理商-agent_user-15',
				'會員帳號-username-12',
				'存入金額-deposit_num-30',
				'存入总額-deposit_money-10',
				'状态-make_sure-12',
				'支付方式-pay_type-15',
				'首存-is_first_zh-12',
				'操縱者-admin_user-12',
				'時間-log_time-60'
				);

			//$color = array('F'=>'00127507');
			$allstr = "总计:笔数：$allnum 存入金額：$deposit_num 存款優惠：$favourable_num 其他優惠：$other_num 存入總金額：$deposit_money";
		}
		$this->out_Excel_download($data,$title,$name,$allstr,23,$color);
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

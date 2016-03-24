<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('cash/Payment_model');
	}
    //支付参数设定
	public function index()
	{

		//初始化
		$cash_f = $this->Payment_model->rget(array('table'=>'k_cash_config','order'=>'id ASC','where'=>array('type'=>1,'site_id'=>$_SESSION['site_id'])));

		foreach ($cash_f as $key => $val) {
		    $cash_f[$key]['type_name_zh'] = $this->rmb_type($val['type_name']);
		}

		//公司自定义设置
		$cash_z = $this->Payment_model->rget(array('table'=>'k_cash_config','where'=>array('is_delete'=>0,'site_id'=>$_SESSION['site_id'],'type'=>0)));
        $cash_all = $cash_z;
		//本站点 全部种类
		$count = array_unshift($cash_all,$cash_f[0]);

        $this->add('cash_f',$cash_f);
        $this->add('cash_z',$cash_z);
        $this->add('cash_all',$cash_all);
	    $this->display('cash/payment/payment_index.html');
	}
	//支付参数设置详情
	public function payment_detail(){
		$id = $this->input->get('id');
		if (empty($id)) {
             showmessage('参数错误!',URL.'/cash/payment/index',0);
	    }
	    $map = array();
	    $map['table'] = 'k_cash_config_view';
	    $map['where']['site_id'] = $_SESSION['site_id'];
	    $map['where']['id'] = $id;
	    $cash_config  = $this->Payment_model->rfind($map);
        $this->add('cash_config',$cash_config);
        $this->display('cash/payment/payment_detail.html');
	}

	//删除
	public function payment_detail_del(){
	    $id = $this->input->get('id');
	    if (empty($id)) {
             showmessage('参数错误!',URL.'/cash/payment/index',0);
	    }
	    if ($this->Payment_model->payment_del($id)) {
	        $log['log_info'] = '删除支付参数设定';
	        $this->Payment_model->Syslog($log);
            showmessage('删除成功!',URL.'/cash/payment/index');
	    }else{
	    	$log['log_info'] = '删除支付参数失败';
	        $this->Payment_model->Syslog($log);
            showmessage('删除失败!',URL.'/cash/payment/index',0);
	    }
	}
    //支付参数添加
	public function payment_add(){
		$name = $this->input->post('name');
		$cp_id = $this->input->post('cp_id');//源数据
		if (empty($name) || empty($cp_id)) {
		    showmessage('参数错误!',URL.'/cash/payment/index');
		}

        $cid = $this->Payment_model->payment_add($cp_id,$name);
		if ($cid) {
			$log['log_info'] = '添加支付参数成功,ID：'.$cid;
	        $this->Payment_model->Syslog($log);
            showmessage('添加成功!',URL.'/cash/payment/index');
		}else{
			$log['log_info'] = '添加支付参数失败';
	        $this->Payment_model->Syslog($log);
            showmessage('添加失败!',URL.'/cash/payment/index',0);
		}
	}
	//添加
	public function payment_add_do(){
        $id = $this->input->post('id');
        if (empty($id)) {
             showmessage('参数错误!',URL.'/cash/payment/index',0);
	    }

        $data_c['is_fee_free'] = $this->input->post('is_fee_free');//是否免手续费
        $data_c['fee_free_num'] = $this->input->post('fee_free_num');//免费次数
        $data_c['out_fee'] = $this->input->post('out_fee');//手续费

        //线上存款优惠
        $data_ol['ol_deposit'] = $this->input->post('ol_deposit');
        //优惠标准
        $data_ol['ol_discount_num'] = $this->input->post('ol_discount_num');
        //优惠比例
        $data_ol['ol_discount_per'] = $this->input->post('ol_discount_per');
        //线上单笔最大存款
        $data_ol['ol_catm_max'] = $this->input->post('ol_catm_max');
        //线上单笔最少存款
        $data_ol['ol_catm_min'] = $this->input->post('ol_catm_min');
        //优惠上限
        $data_ol['ol_discount_max'] = $this->input->post('ol_discount_max');
        //单笔出款上限
        $data_ol['ol_atm_max'] = $this->input->post('ol_atm_max');
        //单笔出款下限
        $data_ol['ol_atm_min'] = $this->input->post('ol_atm_min');
        //其它优惠配置
		$data_ol['ol_other_discount_num'] = $this->input->post('ol_other_discount_num');
		$data_ol['ol_other_discount_per'] = $this->input->post('ol_other_discount_per');
		$data_ol['ol_other_discount_max'] = $this->input->post('ol_other_discount_max');
		$data_ol['ol_o_discount_max_24'] = $this->input->post('ol_o_discount_max_24');
		$data_ol['ol_is_game_audit'] = $this->input->post('ol_is_game_audit');
        $data_ol['ol_game_audit'] = $this->input->post('ol_game_audit');//游戏額度稽核
        $data_ol['ol_is_sport_audit'] = $this->input->post('ol_is_sport_audit');
        $data_ol['ol_sport_audit'] = $this->input->post('ol_sport_audit');//体育額度稽核
        $data_ol['ol_is_fc_audit'] = $this->input->post('ol_is_fc_audit');
        $data_ol['ol_fc_audit'] = $this->input->post('ol_fc_audit');//福彩額度稽核
        $data_ol['ol_is_zh_audit'] = $this->input->post('ol_is_zh_audit');
        $data_ol['ol_zh_audit'] = $this->input->post('ol_zh_audit');//综合額度稽核
        $data_ol['ol_is_ct_audit'] = $this->input->post('ol_is_ct_audit');
        $data_ol['ol_ct_audit'] = $this->input->post('ol_ct_audit');//常態性稽核
        $data_ol['ol_discount_audit'] = $this->input->post('ol_discount_audit');//優惠餘額稽核
        $data_ol['ol_ct_fk_audit'] = $this->input->post('ol_ct_fk_audit');//常態性稽核放寬額度
        $data_ol['ol_ct_xz_audit'] = $this->input->post('ol_ct_xz_audit');//常態性稽核行政費率
        $data_ol['ol_is_give_up'] = $this->input->post('ol_is_give_up');//是否可放弃优惠

        //线下设置
        $data_l['line_deposit'] = $this->input->post('line_deposit');//存款优惠
		$data_l['line_discount_num'] = $this->input->post('line_discount_num');
		$data_l['line_discount_per'] = $this->input->post('line_discount_per');
		$data_l['line_catm_max'] = $this->input->post('line_catm_max');
		$data_l['line_catm_min'] = $this->input->post('line_catm_min');
		$data_l['line_discount_max'] = $this->input->post('line_discount_max');
		$data_l['line_other_discount_num'] = $this->input->post('line_other_discount_num');
		$data_l['line_other_discount_per'] = $this->input->post('line_other_discount_per');
		$data_l['line_other_discount_max'] = $this->input->post('line_other_discount_max');
		$data_l['line_o_discount_max_24'] = $this->input->post('line_o_discount_max_24');//其他優惠24小時內最高上限
        $data_l['line_is_game_audit'] = $this->input->post('line_is_game_audit');
        $data_l['line_game_audit'] = $this->input->post('line_game_audit');//游戏額度稽核
        $data_l['line_is_sport_audit'] = $this->input->post('line_is_sport_audit');
        $data_l['line_sport_audit'] = $this->input->post('line_sport_audit');//体育額度稽核
        $data_l['line_is_fc_audit'] = $this->input->post('line_is_fc_audit');
        $data_l['line_fc_audit'] = $this->input->post('line_fc_audit');//福彩額度稽核
        $data_l['line_is_zh_audit'] = $this->input->post('line_is_zh_audit');
        $data_l['line_zh_audit'] = $this->input->post('line_zh_audit');//综合額度稽核
        $data_l['line_is_ct_audit'] = $this->input->post('line_is_ct_audit');
        $data_l['line_ct_audit'] = $this->input->post('line_ct_audit');//常態性稽核
        $data_l['line_discount_audit'] = $this->input->post('line_discount_audit');//優惠餘額稽核
        $data_l['line_ct_fk_audit'] = $this->input->post('line_ct_fk_audit');//常態性稽核放寬額度
        $data_l['line_ct_xz_audit'] = $this->input->post('line_ct_xz_audit');//常態性稽核行政費率
        $data_l['line_is_give_up'] = $this->input->post('line_is_give_up');//是否可放弃优惠

        if ($this->Payment_model->payment_update($id,$data_c,$data_ol,$data_l)) {
        	$log['log_info'] = '编辑支付参数成功,ID：'.$id;
	        $this->Payment_model->Syslog($log);
            showmessage('更新成功!',URL.'/cash/payment/index');
        }else{
        	$log['log_info'] = '编辑支付参数失败,ID：'.$id;
	        $this->Payment_model->Syslog($log);
        	showmessage('更新失败!',URL.'/cash/payment/index',0);
        }
	}

	//币别
	function rmb_type($type) {
		switch ($type) {
			case 'RMB':
				return '人民幣';
				break;
			case 'HKD':
				return '港幣';
				break;
			case 'USD':
				return '美金';
				break;
			case 'MYR':
				return '馬幣';
				break;
			case 'SGD':
				return '新幣';
				break;
			case 'THB':
				return '泰銖';
				break;
			case 'GBP':
				return '英磅';
				break;
			case 'JPY':
				return '日幣';
				break;
			case 'EUR':
				return '歐元';
				break;
			case 'IDR':
				return '印尼盾';
				break;

		}
	}

	//银行卡管理
	public function payment_bank_list(){
        $card_num = $this->input->get('card_num');//卡号
		$type = $this->input->get('type');
		$index_id = $this->input->get('index_id');
		$index_id = empty($index_id)?'a':$index_id;
		$is_delete = $this->input->get('is_delete');
		$is_delete = empty($is_delete)?0:$is_delete;
		$order_num = $this->input->get('order_num');

		//多站点判断
	    if (!empty($_SESSION['index_id'])) {
	    	$this->add('sites_str','站点：'.str_replace('全部', '请选择站点',$this->Payment_model->select_sites()));
	    }

        $level = $this->Payment_model->get_levels($index_id);

        $map = array();
        $map['table'] = 'k_bank';
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['index_id'] = $index_id;
        $map['where']['is_delete'] = $is_delete;
        if (!empty($type)) {
            $map['where']['bank_type'] = $type;
        }
        if (!empty($card_num)) {
            $map['like']['title'] = 'card_ID';
            $map['like']['match'] = $card_num;
            $map['like']['after'] = 'both';
        }
        $banks = $this->Payment_model->rget($map);

        $bank_type = $this->Payment_model->get_banks();
        $i = 1;
        foreach ($banks as $key => $val) {
            $banks[$key]['bank_type_zh'] = $bank_type[$val['bank_type']]['bank_name'];
            $level_s = '';
            foreach (explode(',', $val['level_id']) as $j=>$s) {
		        if ($i%2 == 0) {
		            $level_s .= $level[$s]['level_des']."<br>";
		        }else{
		            $level_s .= $level[$s]['level_des'].'，';
		        }
		        $i++;
             }
            $banks[$key]['level_des'] = $level_s;
        }

        $this->add('banks',$banks);
        $this->add('level',$level);
        $this->add('is_delete',$is_delete);
        $this->add('index_id',$index_id);
		$this->add('bank_type',$bank_type);
		$this->add('order_num',$order_num);
		$this->add('type',$type);
		$this->add('card_num',$card_num);
        $this->display('cash/payment/payment_bank_list.html');
	}

	//银行卡记录
	public function payment_bank_log(){
	    $order_num = $this->input->get('order_num');
	    $start_date = $this->input->get('start_date');
	    $end_date = $this->input->get('end_date');
	    $bank_id = $this->input->get('id');//银行卡ID
	    $page = $this->input->get('page');

	    $map = array();
	    $map['site_id'] = $_SESSION['site_id'];
	    $map['make_sure'] = 1;
	    $map['into_style'] = 1;
	    if (!empty($bank_id)) {
	    	$map['bid'] = $bank_id;
	    }
	    if (!empty($order_num)) {
	        $map['order_num'] = array('like','%'.$order_num.'%');
                 $this->add('order_num',$order_num);
	    }
            if (empty($start_date) && empty($end_date)) {
				$start_date=$end_date=date('Y-m-d');
                $map['log_time'] = array(array('>=',$start_date.' 00:00:00'),array('<=',$end_date.' 23:59:59'));
                $this->add('start_date',$start_date);
                $this->add('end_date',$end_date);
            }elseif(!empty($start_date) && empty($end_date)){
                $map['log_time'] = array(array('>=',$start_date.' 00:00:00'));
                $this->add('start_date',$start_date);
            }elseif(empty($start_date) && !empty($end_date)){
                $map['log_time'] = array(array('<=',$end_date.' 23:59:59'));
                $this->add('end_date',$end_date);
            }else{
                $map['log_time'] = array(array('>=',$start_date.' 00:00:00'),array('<=',$end_date.' 23:59:59'));
                $this->add('start_date',$start_date);
                $this->add('end_date',$end_date);
            }
            $countN = $this->Payment_model->mcount($map,array('tab'=>'k_user_bank_in_record','type'=>1));
            if($countN==0){
                $allNum[0]=0;
            }else{
            $sum='deposit_num';
            $allNum = $this->Payment_model->sum($sum,$map,array('tab'=>'k_user_bank_in_record','type'=>1));
            }
            $CurrentPage=isset($page)?$page:1;
            $pagenum = 50;
            $start  = ($CurrentPage-1)*$pagenum;

            $totalPage=ceil($countN/$pagenum);
            if($totalPage<$CurrentPage){
              $CurrentPage = 1;
            }
            $limit=$start.",".$pagenum;
            $dataRe = $this->Payment_model->get_bank_log($map,$limit);
            $pagecount=0;
            foreach ($dataRe as $key => $value) {
                $pagecount+=$value['deposit_num'];
            }
            $pagecount = sprintf("%.2f",$pagecount);

            $this->add('page',$this->Payment_model->get_page('k_user_bank_in_record',$totalPage,$page));
            $this->add('bank_id',$bank_id);
            $this->add('dataRe',$dataRe);
            $this->add('pagecount',$pagecount);
            $this->add('allNum',$allNum[0]);

            $this->display('cash/payment/payment_bank_log.html');

	}
        //线上支付详情
    public function payment_pay_log(){
	    $order_num = $this->input->get('order_num');
	    $start_date = $this->input->get('start_date');
	    $end_date = $this->input->get('end_date');
	    $pay_id = $this->input->get('id');//支付平台id
	    $page = $this->input->get('page');

	    $map = array();
	    $map['site_id'] = $_SESSION['site_id'];
	    $map['make_sure'] = 1;
	    $map['into_style'] = 2;
	    if (!empty($pay_id)) {
	    	$map['pay_id'] = $pay_id;
	    }
	    if (!empty($order_num)) {
	        $map['order_num'] = array('like','%'.$order_num.'%');
                 $this->add('order_num',$order_num);
	    }
            if (empty($start_date) && empty($end_date)) {
                $start_date=$end_date=date('Y-m-d');
                $map['log_time'] = array(array('>=',$start_date.' 00:00:00'),array('<=',$end_date.' 23:59:59'));
                 $this->add('start_date',$start_date);
                 $this->add('end_date',$end_date);
            }elseif(!empty($start_date) && empty($end_date)){
                $map['log_time'] = array(array('>=',$start_date.' 00:00:00'));
                $this->add('start_date',$start_date);
            }elseif(empty($start_date) && !empty($end_date)){
                $map['log_time'] = array(array('<=',$end_date.' 23:59:59'));
                $this->add('end_date',$end_date);
            }else{
                $map['log_time'] = array(array('>=',$start_date.' 00:00:00'),array('<=',$end_date.' 23:59:59'));
                $this->add('start_date',$start_date);
                $this->add('end_date',$end_date);
            }

		$countN = $this->Payment_model->mcount($map,array('tab'=>'k_user_bank_in_record','type'=>1));
                if($countN==0){
                $allNum[0]=0.00;
                }else{
                $sum='deposit_num';
                $allNum = $this->Payment_model->sum($sum,$map,array('tab'=>'k_user_bank_in_record','type'=>1));
                }
                //var_dump($allNum[0]);die;
		$CurrentPage=isset($page)?$page:1;
		$pagenum = 50;
		$start  = ($CurrentPage-1)*$pagenum;

		$totalPage=ceil($countN/$pagenum);
		if($totalPage<$CurrentPage){
		  $CurrentPage = 1;
		}
		$limit=$start.",".$pagenum;
		$dataRe = $this->Payment_model->get_bank_log($map,$limit);
                //$pagecount = $this->Payment_model->page_count($sum,$map,array('tab'=>'k_user_bank_in_record','type'=>1),$limit,$join);

        $pagecount=0;
        foreach ($dataRe as $key => $value) {
            $pagecount+=$value['deposit_num'];
        }
        $pagecount = sprintf("%.2f",$pagecount);
                //var_dump($pagecount);
        $this->add('page',$this->Payment_model->get_page('k_user_bank_in_record',$totalPage,$page));
        $this->add('pay_id',$pay_id);
        $this->add('dataRe',$dataRe);
        $this->add('pagecount',$pagecount);
        $this->add('allNum',$allNum[0]);
        //$this->add('index_id',$index_id);

	    $this->display('cash/payment/payment_pay_log.html');

	}
	//添加银行卡
	public function payment_bank_add(){
		$id = $this->input->get('id');
		$index_id = $this->input->get('index_id');
		$index_id = empty($index_id)?'a':$index_id;
		$map = array();
        $map['table'] = 'k_user_level';
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['index_id'] = $index_id;
        $map['where']['is_delete'] = 0;

        $level = $this->Payment_model->rget($map);

        if (!empty($id)) {
            //编辑
            $map_b = array();
            $map_b['table'] = 'k_bank';
            $map_b['where']['id'] = $id;
            $map_b['where']['site_id'] = $_SESSION['site_id'];
            $bank = $this->Payment_model->rfind($map_b);
            $this->add('bank',$bank);
            $level = $this->is_in_arr($level,$bank['level_id']);
        }else{
        	//多站点判断
		    if (!empty($_SESSION['index_id'])) {
		    	$this->add('sites_str',str_replace('全部', '请选择站点',$this->Payment_model->select_sites()));
		    }
        }
        $level = array_chunk($level,4);//分隔成多个数组
        $this->add('level',$level);
        $this->add('index_id',$index_id);
		$this->add('bank_type',$this->Payment_model->get_banks());
	    $this->display('cash/payment/payment_bank_add.html');
	}

	//添加银行卡处理
	public function payment_bank_add_do(){

		$id = $this->input->get('id');
        $level = $this->input->get('level');
        $index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?'a':$index_id;

		$data['bank_type'] = $this->input->get('bank_type');
	    $data['card_ID'] = $this->input->get('card_ID');
	    $data['card_address'] = $this->input->get('card_address');
	    $data['card_userName'] = $this->input->get('card_userName');
	    $data['stop_amount'] = $this->input->get('stop_amount');
	    $data['remark'] = $this->input->get('remark');
	    if (empty($data['card_ID']) || empty($data['card_address']) || empty($data['card_userName'])) {
	        showmessage('请完善表单!',URL.'/cash/payment/payment_bank_list',0);
	    }

	    $map = array();
        $map['table'] = 'k_user_level';
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['index_id'] = $index_id;
        $map['where']['is_delete'] = 0;

        $user_level = $this->Payment_model->rget($map);
        foreach ($user_level as $v){
		    $level_all[] = $v['id'];
		}
	    $data['level_id'] = empty($level)?implode(',', $level_all):implode(',', $level);
	    if (empty($id)) {
	        //添加
	        $data['index_id'] = $this->input->get('index_id')?$this->input->get('index_id'):'a';
	        $data['site_id'] = $_SESSION['site_id'];
	        $data['is_delete'] = 0;
	        if ($this->Payment_model->radd('k_bank',$data)) {
	            $log['log_info'] = '添加银行卡成功';
		        $this->Payment_model->Syslog($log);
	            showmessage('加银行卡成功!',URL.'/cash/payment/payment_bank_list');
	        }else{
	        	showmessage('加银行卡失败!',URL.'/cash/payment/payment_bank_list',0);
	        }
	    }else{
	    	//编辑
	    	$mapr = array();
	    	$mapr['table'] = 'k_bank';
	    	$mapr['where']['id'] = $id;
	    	$mapr['where']['site_id'] = $_SESSION['site_id'];
            if ($this->Payment_model->rupdate($mapr,$data)) {
	            $log['log_info'] = '添加银行卡成功';
		        $this->Payment_model->Syslog($log);
	            showmessage('更新银行卡成功!',URL.'/cash/payment/payment_bank_list');
	        }else{
	        	showmessage('更新银行卡失败!',URL.'/cash/payment/payment_bank_list',0);
	        }
	    }

	}

	//银行卡删除
	public function payment_bank_do(){
	    $id = $this->input->get('id');
	    $type = $this->input->get('type');//操作类型
	    if (empty($id)) {
            showmessage('参数错误!',URL.'/cash/payment/payment_bank_list',0);
	    }
        $map = array();
        $map['table'] = 'k_bank';
        $map['where']['id'] = $id;
        $map['where']['site_id'] = $_SESSION['site_id'];

	    if ($this->Payment_model->rupdate($map,array('is_delete'=>$type))) {
	    	$log['log_info'] = '操作银行卡,ID：'.$id;
	        $this->Payment_model->Syslog($log);
	        showmessage('操作成功!',URL.'/cash/payment/payment_bank_list');
	    }else{
	    	showmessage('操作失败!',URL.'/cash/payment/payment_bank_list',0);
	    }
	}

	//第三方种类
	function payment_type(){
	    return array('1'=>'新生','2'=>'易宝','3'=>'环迅',
	    	         '4'=>'币付宝','5'=>'通汇卡','6'=>'宝付',
	    	         '7'=>'智付','8'=>'汇潮','9'=>'国付宝',
	    	         '10'=>'融宝','11'=>'快捷通','12'=>'新环迅',
                     '13'=>'易宝点卡');
	}

	//线上支付
	public function payment_online_list(){
		$index_id = $this->input->get('index_id');
		$index_id = empty($index_id)?'a':$index_id;
		$is_delete = $this->input->get('is_delete');
		$is_delete = empty($is_delete)?0:$is_delete;
		$pay_type = $this->input->get('pay_type');

			//多站点判断
	    if (!empty($_SESSION['index_id'])) {
	    	$this->add('sites_str','站点：'.str_replace('全部', '请选择站点',$this->Payment_model->select_sites()));
	    }

	    //第三方种类
	    $payment_type = $this->Payment_model->get_pays();

		$map = array();
		$map['table'] = 'pay_set';
		$map['where']['site_id'] = $_SESSION['site_id'];
		$map['where']['index_id'] = $index_id;
		$map['where']['is_delete'] = $is_delete;
		if (!empty($pay_type)) {
		    $map['where']['pay_type'] = $pay_type;
		}

		$level = $this->Payment_model->get_levels($index_id);
        //包括全部站点条件
        unset($map['where']['index_id']);
        $map['where_in']['item'] = 'index_id';
        $map['where_in']['data'] =  array('0',$index_id);
		$data = $this->Payment_model->rget($map);
		$i = 1;
		foreach ($data as $key => $val) {
            $data[$key]['pay_type_zh'] = $payment_type[$val['pay_type']]['online_bank_name'];
            $level_s = '';
            foreach (explode(',', $val['level_id']) as $j=>$s) {
                if($level[$s]['level_des']){
    		        if ($i%2 == 0) {
    		            $level_s .= $level[$s]['level_des']."<br>";
    		        }else{
    		            $level_s .= $level[$s]['level_des'].'，';
    		        }
                }
		        $i++;
             }
            $data[$key]['level_des'] = $level_s;
		}
        $this->add('data',$data);
        $this->add('pay_type',$pay_type);
        $this->add('is_delete',$is_delete);
        $this->add('payment_type',$payment_type);
        $this->add('index_id',$index_id);
        $this->display('cash/payment/payment_online_list.html');
	}

    //线上支付操作
	public function payment_pay_do(){
	    $id = $this->input->get('id');
	    $type = $this->input->get('type');//操作类型
	    $type = empty($type)?0:$type;
	    if (empty($id)) {
            showmessage('参数错误!',URL.'/cash/payment/payment_online_list',0);
	    }
        $map = array();
        $map['table'] = 'pay_set';
        $map['where']['id'] = $id;
        $map['where']['site_id'] = $_SESSION['site_id'];

	    if ($this->Payment_model->rupdate($map,array('is_delete'=>$type))) {
	    	$log['log_info'] = '操作支付平台,ID：'.$id;
	        $this->Payment_model->Syslog($log);
	        showmessage('操作成功!',URL.'/cash/payment/payment_online_list');
	    }else{
	    	showmessage('操作失败!',URL.'/cash/payment/payment_online_list',0);
	    }
	}

	//添加第三方
	public function payment_pay_add(){
		$id = $this->input->get('id');
		$index_id = $this->input->get('index_id');
		$index_id = isset($index_id)?$index_id:'a';

		$map = array();
        $map['table'] = 'k_user_level';
        $map['where']['site_id'] = $_SESSION['site_id'];
        if (!empty($index_id)) {
            $map['where']['index_id'] = $index_id;
        }
        $map['where']['is_delete'] = 0;

        $level = $this->Payment_model->rget($map);

		if (!empty($id)) {
            //编辑
            $map_b = array();
            $map_b['table'] = 'pay_set';
            $map_b['where']['id'] = $id;
            $map_b['where']['site_id'] = $_SESSION['site_id'];
            $data = $this->Payment_model->rfind($map_b);
            //层级数据整理
            $level = $this->is_in_arr($level,$data['level_id']);
            $this->add('data',$data);
        }else{
        	//多站点判断
		    if (!empty($_SESSION['index_id'])) {
		    	$this->add('sites_str',$this->Payment_model->select_sites());
		    }
			$this->add('cansee',1);
        }

        $level = array_chunk($level,4);//分隔成多个数组

        $this->add('level',$level);
        $this->add('index_id',$index_id);

		$this->add('payment_type',$this->Payment_model->get_pays());
	    $this->display('cash/payment/payment_pay_add.html');
	}
    //层级数据勾选判断
	function is_in_arr($arr,$str){
        $tmp_arr = explode(',',$str);
        foreach ($arr as $k => $v) {
            if (in_array($v['id'],$tmp_arr)) {
                $arr[$k]['is_check'] = 1;
            }
        }
        return $arr;
	}

    //添加第三方处理
	public function payment_pay_add_do(){
		$id = $this->input->get('id');
        $level = $this->input->get('level');
        $index_id = $this->input->get('index_id');
        $index_id = isset($index_id)?$index_id:'a';
        //国付宝专用
        $data['vircarddoin'] = $this->input->get('vircarddoin');//账号

		$data['pay_id'] = $this->input->get('pay_id');//商户ID
	    $data['pay_domain'] = $this->input->get('pay_domain');//支付域名
	    $data['f_url'] = $this->input->get('f_url');//返回地址
	    $data['terminalid'] = $this->input->get('terminalid');//终端ID

	    $data['money_limits'] = $this->input->get('money_limits');//限额
	    $data['pay_type'] = $this->input->get('pay_type');//支付平台
        $data['is_card'] = $this->input->get('is_card');//是否为点卡
	    if (empty($data['pay_domain']) || empty($data['pay_type'])) {
	        showmessage('请完善表单!',URL.'/cash/payment/payment_online_list',0);
	    }

	    $map = array();
        $map['table'] = 'k_user_level';
        $map['where']['site_id'] = $_SESSION['site_id'];
        if (!empty($index_id)) {
            $map['where']['index_id'] = $index_id;
        }
        $map['where']['is_delete'] = 0;

        $user_level = $this->Payment_model->rget($map);
        foreach ($user_level as $v){
		    $level_all[] = $v['id'];
		}
	    $data['level_id'] = empty($level)?implode(',', $level_all):implode(',', $level);
	    if (empty($id)) {
	        //添加
	        $data['index_id'] = $index_id;
	        $data['site_id'] = $_SESSION['site_id'];
	        $data['is_delete'] = 0;
			$data['pay_key'] = $this->input->get('pay_key');//秘钥
            if ( empty($data['pay_key'])) {
                showmessage('请完善表单!',URL.'/cash/payment/payment_online_list',0);
            }
	        if ($this->Payment_model->radd('pay_set',$data)) {
	            $log['log_info'] = '添加第三方成功';
		        $this->Payment_model->Syslog($log);
	            showmessage('添加第三方成功!',URL.'/cash/payment/payment_online_list');
	        }else{
	        	showmessage('添加第三方失败!',URL.'/cash/payment/payment_online_list',0);
	        }
	    }else{
	    	//编辑
	    	$mapr = array();
	    	$mapr['table'] = 'pay_set';
	    	$mapr['where']['id'] = $id;
	    	$mapr['where']['site_id'] = $_SESSION['site_id'];
            if ($this->Payment_model->rupdate($mapr,$data)) {
	            $log['log_info'] = '更新第三方成功';
		        $this->Payment_model->Syslog($log);
	            showmessage('更新第三方成功!',URL.'/cash/payment/payment_online_list');
	        }else{
	        	showmessage('更新第三方失败!',URL.'/cash/payment/payment_online_list',0);
	        }
	    }

	}

}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitor extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('cash/Bank_record_model');
	}

	public function index()
	{
		$gstype = $this->input->get("gstype");
		$xstype = $this->input->get("xstype");
		$outtype = $this->input->get("outtype");
                $listen = $this->input->get('listen');
		$listen = empty($listen)?'1':$listen;

		if ($gstype==1){
			$gssound =1;
		}
		if ($outtype==1){
			$cksound =1;
		}
		if ($xstype==1){
			$xssound =1;
		}
		//公司入款
		$map = array();
		$map['k_user_bank_in_record.site_id'] = $_SESSION['site_id'];
		$map['k_user_bank_in_record.into_style'] = 1;
		$map['k_user_bank_in_record.make_sure'] = 0;
		$data_gs = $this->Bank_record_model->get_bankin_line($map,'50');

		//线上入款
	    $map['k_user_bank_in_record.into_style'] = 2;
		$data_xs = $this->Bank_record_model->get_bankin_online($map,'50');

		$map['k_user_bank_in_record.status'] = 0;
		$data_xs_s = $this->Bank_record_model->get_bankin_online($map,'1');

		//出款
		$map_o = array();
		$map_o['k_user_bank_out_record.site_id'] = $_SESSION['site_id'];
		$map_o['k_user_bank_out_record.out_status'] = array('in','(0,4)');
		$this->load->model('cash/Out_record_model');
		$data_ck = $this->Out_record_model->get_out($map_o,'50');

		if ($data_gs) {
			$bank_type = $this->Bank_record_model->get_bank_type();
			$bank_arr = $this->Bank_record_model->M(array('tab'=>'k_bank','type'=>1))->where("site_id = '".$_SESSION['site_id']."'")->select("id");
			foreach ($data_gs as $key => $val) {
	            $data_gs[$key]['is_first_zh'] = $this->is_first($val['is_firsttime']);
	            $data_gs[$key]['in_type_zh'] = $this->in_type($val['in_type']);
	            // $data_gs[$key]['bank_style_zh'] = $this->Bank_record_model->get_bank_type($val['bank_style']);
	            // $data_gs[$key]['bank_type_zh'] = $this->Bank_record_model->get_bank_type($val['bank_type']);
                $tmp_bank_type = $bank_arr[$val['bid']]['bank_type'];
	            $data_gs[$key]['bank_style_zh'] = $bank_type[$val['bank_style']]['bank_name'];
                //$data_gs[$key]['bank_type_zh'] = $bank_type[$val['bank_style']]['bank_name'];
                $data_gs[$key]['bank_type_zh'] = $bank_type[$tmp_bank_type]['bank_name'];
	            if ($val['in_type'] ==2 || $val['in_type'] == 3 || $val['in_type'] == 4) {
					$data_gs[$key]['in_type_zh'] = $data_gs[$key]['in_type_zh']."<br>".'網點：'.$val['in_atm_address'];
				}
	        }
			$this->add('data_gs',$data_gs);
			$this->add('gs_state',1);
		}

		if ($data_xs) {
			$online_type = $this->Bank_record_model->get_online_type();
			foreach ($data_xs as $key => $val) {
	            $data_xs[$key]['is_first_zh'] = $this->is_first($val['is_firsttime']);
	            $data_xs[$key]['pay_type'] = $online_type[$val['paytype']]['online_bank_name'].'(ID：'.$val['pay_id'].')';
	            //$data_xs[$key]['pay_type'] = $this->Bank_record_model->get_online_type($val['paytype']).'(ID：'.$val['pay_id'].')';
	        }
			$this->add('data_xs',$data_xs);
			if ($data_xs_s) {
			    $this->add('xs_state',1);
			}
		}

		if ($data_ck) {
			$this->add('data_ck',$data_ck);
			$this->add('ck_state',1);
		}
		$this->add("gstype",$gstype);
		$this->add("xstype",$xstype);
		$this->add("outtype",$outtype);
		$this->add("gssound",$gssound);
		$this->add("cksound",$cksound);
                $this->add('listen',$listen);
		// $this->add("xs_state",$xs_state);

        $this->cash_online();
		$this->display('cash/monitor.html');
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

}

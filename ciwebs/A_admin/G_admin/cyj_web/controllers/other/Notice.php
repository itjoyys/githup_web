<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Notice extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('other/Notice_model');
	}

	public function index(){
		$map = array();
		$map['table'] = 'site_notice';
		$map['where']['sid'] = 0;
		$map['where']['notice_state'] = 1;
		$map['order'] = 'notice_date DESC';

		$notice = $this->Notice_model->rget($map);
		//数据拼接
		foreach ($notice as $key => $val) {
		   switch ($val['notice_cate']) {
		   	case '3'://彩票
		   		$fc[] = $val;
		   		break;
		   	case '4'://体育
		   		$sp[] = $val;
		   		break;
		   	case '5'://视讯
		   		$vd[] = $val;
		   		break;
		   	case '2'://视讯
		   		$wh[] = $val;
		   		break;
		   }
		}
		$this->add('fc',$fc);
		$this->add('sp',$sp);
		$this->add('vd',$vd);
		$this->add('wh',$wh);
        $this->display('other/notice.html');
	}

}
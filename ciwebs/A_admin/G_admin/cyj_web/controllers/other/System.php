<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class System extends MY_Controller {

	public function __construct() {
		parent::__construct();
        $this->login_check();
		$this->load->model('other/System_model');
	}

	public function limit_index(){
        $type = $this->input->get('type');
        if (empty($type)) {
           exit('system error 0000');
        }
        if ($type == 'sp') {
            $spTitle = array('ft'=>'足球','bk'=>'篮球','vb'=>'排球','bs'=>'棒球','tn'=>'网球');
            $data = $this->System_model->get_sp_limit($_SESSION["agent_id"]);

            $this->add('data',$data);
            $this->add('spTitle',$spTitle);
            $this->display('other/sp_list.html');
        }elseif($type == 'fc'){
            $Title = array('fc_3d'=>'福彩3D','pl_3'=>'排列三',
             'cq_ssc'=>'重庆时时彩','cq_ten'=>'重庆快乐十分',
             'gd_ten'=>'广东快乐十分','bj_8'=>'北京快乐8',
             'bj_10'=>'北京PK拾','tj_ssc'=>'天津时时彩',
             'xj_ssc'=>'新疆时时彩','jx_ssc'=>'江西时时彩','jl_k3'=>'吉林快三',
             'js_k3'=>'江苏快三','liuhecai'=>'六合彩'
            );
            $data = $this->System_model->get_fc_limit($_SESSION["agent_id"]);
            $this->add('data',$data);
            $this->add('Title',$Title);
            $this->display('other/fc_list.html');
        }
	}

}
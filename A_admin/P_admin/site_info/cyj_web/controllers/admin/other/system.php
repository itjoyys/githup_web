<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class System extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('admin/other/system_model');
	}

	public function limit_index(){
        $type = $this->input->get('type');
        if (empty($type)) {
           exit('system error 0000');
        }
        if ($type == 'sp') {
            $spTitle = array('ft'=>'足球','bk'=>'篮球','vb'=>'排球','bs'=>'棒球','tn'=>'网球');
            $spArr = array('ft','bk','vb','bs','tn');
            foreach ($spArr as $key => $val) {
                $data[] = $this->system_model->get_sp_limit($val);
            }  
        }elseif($type == 'fc'){
            $Title = array('fc_3d'=>'福彩3D','pl_3'=>'排列三',
             'cq_ssc'=>'重庆时时彩','cq_ten'=>'重庆快乐十分',
             'gd_ten'=>'广东快乐十分','bj_8'=>'北京快乐8',
             'bj_10'=>'北京PK拾','tj_ssc'=>'天津时时彩',
             'xj_ssc'=>'新疆时时彩','jx_ssc'=>'江西时时彩','jl_k3'=>'吉林快三',
             'js_k3'=>'江苏快三','liuhecai'=>'六合彩'
            );
            $fcArr = array('fc_3d','pl_3','cq_ssc','cq_ten','gd_ten','bj_8','bj_10','tj_ssc','xj_ssc','jx_ssc','jl_k3','js_k3','liuhecai');
            foreach ($fcArr as $key => $val) {
                $data[] = $this->system_model->get_fc_limit($val);
            }  
        }
	    	
        foreach ($case as $k => $v) {
           $case[$k]['type_z'] = case_type($v['type']);
           $case[$k]['url'] = self::get_view($v['id']);
        }
        $this->add("case",$case);
        switch ($cstate) {
            case '1':
                $this->display('site_info/case_yes_index.html');
                break;
            case '3':
                $this->display('site_info/case_no_index.html');
                break;
            case '2':
                $this->display('site_info/case_examine.html');
                break;
            default:
                $this->display('site_info/case_index.html');
                break;
        }
	}

    public function case_send(){
        $id = intval($this->input->get("id"));
        if (empty($id)) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        $arr['cstate'] = 2;//审核中状态
        if ($this->system_model->update_case($arr,$id)) {
            show_error('发送案件审核成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
            show_error('发送案件失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        } 
    }

    public function case_del(){
        $id = intval($this->input->get("id"));
        $eid = intval($this->input->get("eid"));
        $type = intval($this->input->get("type"));
        if (empty($id) || empty($type)) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        if ($type == 13) {
           $eid = 13;
        }
        if ($this->system_model->case_del($eid,$id,self::get_tab($type))) {
            show_error('删除案件成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
            show_error('删除案件失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        } 
    }

    //获取预览网址
   function get_view($cid){
       $site_id = 't';
       $www = $this->system_model->rfind('web_config','site_id',$site_id,'conf_www');
       if (!empty($www) && !empty($www['conf_www'])) {
          return 'http://'.$www['conf_www'].'/?eid='.$cid.'&vt=sview';
       }else{
           show_error('站点主域名未设置!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
       } 
    }
   //判断案件的类别返回对应的表
   function get_tab($type){
       if ($type == 1 || $type == 2) {
          return 'info_deposit_edit';
       }elseif($type == 9 || $type == 10){
          return 'info_reg_edit';
       }elseif($type >=3 && $type <= 8){
          return 'info_iword_edit';
       }elseif($type == 11 || $type == 12){
          return 'info_logo_edit';
       }elseif ($type == 13) {
          return 'info_flash_edit';
       }elseif ($type == 14) {
          return 'info_activity_edit';
       }elseif ($type == 15) {
          return 'info_cateimg_edit';
       }elseif ($type == 16 || $type ==17) {
          return 'info_float_c_edit';
       }
   }
}
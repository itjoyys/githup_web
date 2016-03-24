<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Info_case extends MY_Controller {

	public function __construct() {
		parent::__construct();
    $this->login_check();
		$this->load->model('site_info/Info_case_model');
	}

	public function case_index(){
        $cstate = $this->input->get('cs');
        if (empty($cstate)) {
           $map['cstate'] = 0;
        }else{
           $map['cstate'] = $cstate;
        }
        $map['site_id'] = $_SESSION['site_id'];
        $count = $this->Info_case_model->get_case_count($map);

        if ($count > 0) {
            $per_page =30;
            $totalPage = ceil($count / $per_page);
            $page =  $this->input->get("per_page");
            $page = ($page==0)?1:$page;
            $offset = ($page - 1) * $per_page;
        }
        $this->load->library('pagination');
        $config['base_url'] = URL.'/site_info/info_case/case_index?cs='.$map['cstate'];
        $config['total_rows'] = $count;
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $this->pagination->use_page_numbers = true;
        $this->pagination->page_query_string = true;
        $this->add("page_html", $this->pagination->create_links());
        $this->add("page", $page);
        $case = $this->Info_case_model->get_case($per_page,$offset,$map);

        foreach ($case as $k => $v) {
           $case[$k]['type_z'] = case_type($v['type']);
           $case[$k]['url'] = self::get_view($v['id'],$v['type'],$v['index_id']);
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
        if ($this->Info_case_model->update_case($arr,$id)) {
            $drr['log_info'] = '发送案件审核成功,ID:'.$id;
            $this->Info_case_model->Syslog($drr);
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
        if ($this->Info_case_model->case_del($eid,$id,self::get_tab($type))) {
            $drr['log_info'] = '删除案件成功,ID:'.$id;
            $this->Info_case_model->Syslog($drr);
            show_error('删除案件成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
            $drr['log_info'] = '删除案件失败,ID:'.$id;
            $this->Info_case_model->Syslog($drr);
            show_error('删除案件失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
    }

    //获取预览网址
   function get_view($cid,$type,$index_id){
       $www = $this->Info_case_model->rfind(array('table'=>'web_config','where'=>array('site_id'=>$_SESSION['site_id'],'index_id'=>$index_id)));

       if (!empty($www) && !empty($www['conf_www'])) {
          return 'http://'.$www['conf_www'].'/?eid='.$cid.'&vt=sview&ty='.$type;
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
       }elseif($type == 11 || $type == 12 || $type == 30){
          return 'info_logo_edit';
       }elseif ($type == 13 ||$type == 31) {
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
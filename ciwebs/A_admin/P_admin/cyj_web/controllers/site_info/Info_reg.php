<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Info_reg extends MY_Controller {

	public function __construct() {
		parent::__construct();
        $this->login_check();
		$this->load->model('site_info/Info_reg_model');
	}

	public function reg_index(){
        $index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?'a':$index_id;
        $map = array();
        $map['table'] = 'info_reg_edit_view';
        $map['select'] = 'title,state,case_state,id,color,code,name';
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['index_id'] = $index_id;
		$reg = $this->Info_reg_model->rget($map);
		foreach ($reg as $k => $val) {
            if ($val['state'] == 2) {
                $reg[$k]['state_z'] = '<font style="color:#CB102C;;">停用</font>';
            }else{
                $reg[$k]['state_z'] = '<font style="color:#07B817;">启用</font>';
            }
        }

             //多站点判断
        if (!empty($_SESSION['index_id'])) {
            $this->add('sites_str','站点：'.str_replace('全部', '请选择站点',$this->Info_reg_model->select_sites()));
            $this->add('index_id',$index_id);
        }

        $this->add("reg",$reg);
        $this->display('site_info/info_reg.html');
	}

   public function reg_edit()
    {
        $id = intval($this->input->get("id"));
        if (empty($id)) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        $map = array();
        $map['table'] = 'info_reg_edit_view';
        $map['select'] = 'id,content,content_f,title';
        $map['where']['id'] = $id;
        $map['where']['site_id'] = $_SESSION['site_id'];
        $reg = $this->Info_reg_model->rfind($map);
        $this->add("reg",$reg);
        $this->display('site_info/info_reg_e.html');
    }

   public function reg_title_do()
    {
        $id = intval($this->input->post("id"));
        $arr['title'] = $this->input->post('title');
        $arr['color'] = $this->input->post('color');
        $arr['state'] = $this->input->post('state');
        if (empty($id) || empty($arr['title'])) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        $map = array();
        $map['table'] = 'info_reg_edit';
        $map['where']['id'] = $id;
        $map['where']['site_id'] = $_SESSION['site_id'];

        if ($this->Info_reg_model->rupdate($map,$arr)) {
            $drr['log_info'] = '修改注册文案标题信息成功,ID:'.$id;
            $this->Info_reg_model->Syslog($drr);
            show_error('修改成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
            $drr['log_info'] = '修改注册文案标题信息失败,ID:'.$id;
            $this->Info_reg_model->Syslog($drr);
            show_error('修改失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
    }

    public function reg_content_do()
    {
        $id = intval($this->input->get("id"));
        $arr['content'] = $this->input->post('content');
        $arr['content_f'] = $this->input->post('content_f');
        if (empty($id)) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        $map = array();
        $map['table'] = 'info_reg_edit';
        $map['where']['id'] = $id;
        $map['where']['site_id'] = $_SESSION['site_id'];
        if ($this->Info_reg_model->rupdate($map,$arr)) {
            $drr['log_info'] = '修改注册文案内容信息成功,ID:'.$id;
            $this->Info_reg_model->Syslog($drr);
            show_error('修改成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
            $drr['log_info'] = '修改注册文案内容信息失败,ID:'.$id;
            $this->Info_reg_model->Syslog($drr);
            show_error('修改失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
    }

   public function reg_case()
    {
        $id = intval($this->input->get("id"));
        $index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?'a':$index_id;
        if (empty($id)) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        $map = array();
        $map['table'] = 'info_reg_edit_view';
        $map['select'] = 'title,type,site_id';
        $map['where']['id'] = $id;
        $map['where']['site_id'] = $_SESSION['site_id'];
        $arr = $this->Info_reg_model->rfind($map);
        $arr['eid'] = $id;//文案对应详情id
        $arr['index_id'] = $index_id;
        $arr['add_date'] = date('Y-m-d H:i:s');//提交时间
        if ($this->Info_reg_model->reg_case($arr,$id)) {
            $drr['log_info'] = '存储注册文案成功,ID:'.$id;
            $this->Info_reg_model->Syslog($drr);
            show_error('存储案件成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
            $drr['log_info'] = '存储注册文案失败,ID:'.$id;
            $this->Info_reg_model->Syslog($drr);
            show_error('存储案件失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
    }





}
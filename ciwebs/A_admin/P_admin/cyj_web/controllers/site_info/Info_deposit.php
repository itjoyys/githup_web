<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Info_deposit extends MY_Controller {

	public function __construct() {
		parent::__construct();
        $this->login_check();
		$this->load->model('site_info/Info_deposit_model');
	}

	public function deposit_index(){
        $index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?'a':$index_id;
        $map = array();
        $map['table'] = 'info_deposit_edit_view';
        $map['select'] = 'title,state,case_state,id,color,code,name';
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['index_id'] = $index_id;
		$deposit = $this->Info_deposit_model->rget($map);
		foreach ($deposit as $k => $val) {
            if ($val['state'] == 2) {
                $deposit[$k]['state_z'] = '<font style="color:#CB102C;;">停用</font>';
            }else{
                $deposit[$k]['state_z'] = '<font style="color:#07B817;">启用</font>';
            }
        }
           //多站点判断
        if (!empty($_SESSION['index_id'])) {
            $this->add('sites_str','站点：'.str_replace('全部', '请选择站点',$this->Info_deposit_model->select_sites()));
            $this->add('index_id',$index_id);
        }
        $this->add("deposit",$deposit);
        $this->display('site_info/info_deposit.html');
	}

   public function deposit_edit()
    {
        $id = intval($this->input->get("id"));
        if (empty($id)) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        $map = array();
        $map['table'] = 'info_deposit_edit_view';
        $map['select'] = 'id,content,title';
        $map['where']['id'] = $id;
        $map['where']['site_id'] = $_SESSION['site_id'];
        $deposit = $this->Info_deposit_model->rfind($map);
        $this->add("deposit",$deposit);
        $this->display('site_info/info_deposit_e.html');
    }

   public function deposit_title_do()
    {
        $id = intval($this->input->post("id"));
        $arr['title'] = $this->input->post('title');
        $arr['color'] = $this->input->post('color');
        $arr['state'] = $this->input->post('state');
        if (empty($id) || empty($arr['title'])) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        $map = array();
        $map['table'] = 'info_deposit_edit';
        $map['where']['id'] = $id;
        $map['where']['site_id'] = $_SESSION['site_id'];
        if ($this->Info_deposit_model->rupdate($map,$arr)) {
            $drr['log_info'] = '修改存款文案标题成功,ID:'.$id;
            $this->Info_deposit_model->Syslog($drr);
            show_error('修改成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
            $drr['log_info'] = '修改存款文案标题失败,ID:'.$id;
            $this->Info_deposit_model->Syslog($drr);
            show_error('修改失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
    }

    public function deposit_content_do()
    {
        $id = intval($this->input->get("id"));
        $arr['content'] = $this->input->post('editorValue');
        if (empty($id) || empty($arr['content'])) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        $map = array();
        $map['table'] = 'info_deposit_edit';
        $map['where']['id'] = $id;
        $map['where']['site_id'] = $_SESSION['site_id'];
        if ($this->Info_deposit_model->rupdate($map,$arr)) {
            $drr['log_info'] = '修改存款文案内容成功,ID:'.$id;
            $this->Info_deposit_model->Syslog($drr);
            show_error('修改成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
            $drr['log_info'] = '修改存款文案内容失败,ID:'.$id;
            $this->Info_deposit_model->Syslog($drr);
            show_error('修改失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
    }

   public function deposit_case()
    {   $index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?'a':$index_id;
        $id = intval($this->input->get("id"));
        if (empty($id)) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        $map = array();
        $map['table'] = 'info_deposit_edit';
        $map['select'] = 'title,type,site_id,index_id';
        $map['where']['id'] = $id;
        $map['where']['site_id'] = $_SESSION['site_id'];
        $arr = $this->Info_deposit_model->rfind($map);
        $arr['eid'] = $id;//文案对应详情id
        $arr['add_date'] = date('Y-m-d H:i:s');//提交时间
        $arr['index_id'] = $index_id;
        if ($this->Info_deposit_model->deposit_case($arr,$id)) {
            $drr['log_info'] = '存储存款文案成功,ID:'.$id;
            $this->Info_deposit_model->Syslog($drr);
            show_error('存储案件成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
            $drr['log_info'] = '存储存款文案失败,ID:'.$id;
            $this->Info_deposit_model->Syslog($drr);
            show_error('存储案件失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
    }





}
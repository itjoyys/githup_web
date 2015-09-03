<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Info_deposit extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('site_info/Info_deposit_model');
	}

	public function deposit_index(){
		$deposit = $this->Info_deposit_model->rget('info_deposit_edit_view','site_id',$_SESSION['site_id'],'title,state,case_state,id,color,code,name');
		foreach ($deposit as $k => $val) {
            if ($val['state'] == 2) {
                $deposit[$k]['state_z'] = '<font style="color:#CB102C;;">停用</font>';
            }else{
                $deposit[$k]['state_z'] = '<font style="color:#07B817;">启用</font>';
            }
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
        $deposit = $this->Info_deposit_model->rfind('info_deposit_edit_view','id',$id,'id,content,title');
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
        if ($this->Info_deposit_model->rupdate('info_deposit_edit','id',$id,$arr)) {
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
        if ($this->Info_deposit_model->rupdate('info_deposit_edit','id',$id,$arr)) {
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
    {
        $id = intval($this->input->get("id"));
        if (empty($id)) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        $arr = $this->Info_deposit_model->rfind('info_deposit_edit','id',$id,'title,type,site_id');
        $arr['eid'] = $id;//文案对应详情id
        $arr['add_date'] = date('Y-m-d H:i:s');//提交时间
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
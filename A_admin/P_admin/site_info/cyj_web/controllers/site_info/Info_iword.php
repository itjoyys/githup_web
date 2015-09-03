<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Info_iword extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('site_info/Info_iword_model');
    }

    public function iword_index()
    {
        $iword = $this->Info_iword_model->rget('info_iword_edit_view','site_id',$_SESSION['site_id'],'id,name,title,state,type,code,is_form,is_agent,case_state,sort');
        foreach ($iword as $k => $val) {
            if ($val['state'] == 2) {
                $iword[$k]['state_z'] = '<font style="color:#CB102C;;">停用</font>';
            }else{
                $iword[$k]['state_z'] = '<font style="color:#07B817;">启用</font>';
            }
        }
        $this->add("iword",$iword);
        $this->display('site_info/info_iword.html');
    }

    public function iword_edit()
    {
        $id = intval($this->input->get("id"));
        $type = intval($this->input->get("type"));
        if (empty($id)) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        $iword = $this->Info_iword_model->rfind('info_iword_edit_view','id',$id);
        $this->add("iword",$iword);
        if ($type == '5') {
            $this->display('site_info/info_iword2_e.html');
        }else{
            $this->display('site_info/info_iword_e.html');
        }
        
    }

    public function iword_title_do(){
        $id = intval($this->input->post("id"));
        $arr['title'] = $this->input->post('title');
        $arr['sort'] = $this->input->post('sort');
        if ($arr['sort'] =='') {
            $arr['sort'] = 0;
        }
        $arr['state'] = $this->input->post('state');
        if (empty($id) || empty($arr['title'])) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        if ($this->Info_iword_model->rupdate('info_iword_edit','id',$id,$arr)) {
            $drr['log_info'] = '修改首页文案标题信息成功,ID:'.$id;
            $this->Info_iword_model->Syslog($drr);
            show_error('修改成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
            $drr['log_info'] = '修改首页文案标题信息失败,ID:'.$id;
            $this->Info_iword_model->Syslog($drr);
            show_error('修改失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        } 
    }

    public function is_do_iword(){
        $id = intval($this->input->get('id'));
        $type = $this->input->get('type');
        if (empty($id) || empty($type)) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        switch ($type) {
            case 'f':
                $arr['is_form'] = 1-intval($this->input->get('state'));
                break;
            case 'a':
                $arr['is_agent'] = 1-intval($this->input->get('state'));
                break;
        }
        if ($this->Info_iword_model->rupdate('info_iword_edit','id',$id,$arr)) {
            $drr['log_info'] = '修改首页文案成功,ID:'.$id;
            $this->Info_iword_model->Syslog($drr);
            show_error('修改成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
            $drr['log_info'] = '修改首页文案失败,ID:'.$id;
            $this->Info_iword_model->Syslog($drr);
            show_error('修改失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        } 

    }

    public function iword_content_do(){
        $id = intval($this->input->get("id"));
        $arr['content'] = $this->input->post('content');
        $arr_con = $this->input->post('content_1');
        if (empty($id) || empty($arr['content'])) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        if ($arr_con) {
            $arr['content_1'] = $arr_con;
        }
        if ($this->Info_iword_model->rupdate('info_iword_edit','id',$id,$arr)) {
            $drr['log_info'] = '修改首页文案内容信息成功,ID:'.$id;
            $this->Info_iword_model->Syslog($drr);
            show_error('修改成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
            $drr['log_info'] = '修改首页文案失败,ID:'.$id;
            $this->Info_iword_model->Syslog($drr);
            show_error('修改失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        } 
    }

    public function iword_case(){
        $id = intval($this->input->get("id"));
        if (empty($id)) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        $arr = $this->Info_iword_model->rfind('info_iword_edit_view','id',$id,'title,type,site_id');
        $arr['eid'] = $id;//文案对应详情id
        $arr['add_date'] = date('Y-m-d H:i:s');//提交时间
        if ($this->Info_iword_model->iword_case($arr,$id)) {
            $drr['log_info'] = '存储首页文案成功,ID:'.$id;
            $this->Info_iword_model->Syslog($drr);
            show_error('存储案件成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
            $drr['log_info'] = '存储首页文案失败,ID:'.$id;
            $this->Info_iword_model->Syslog($drr);
            show_error('存储案件失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        } 
    }

}
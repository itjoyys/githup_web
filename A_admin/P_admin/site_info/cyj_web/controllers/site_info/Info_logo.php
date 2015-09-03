<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Info_logo extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('site_info/Info_logo_model');
	}

	public function logo_index() {
		$logo = $this->Info_logo_model->rget('info_logo_edit_view','site_id',$_SESSION['site_id']);
		$img_url = $this->Info_logo_model->rfind('web_config','site_id',$_SESSION['site_id']);
		foreach ($logo as $k => $val) {
			if ($val['state'] == 2) {
				$logo[$k]['state_z'] = '<font style="color:#CB102C;;">停用</font>';
			}else{
         $logo[$k]['state_z'] = '<font style="color:#07B817;">启用</font>';
			}
			if ($val['type'] == 11) {
				$logo[$k]['width'] = $val['site_logo_w'];
				$logo[$k]['height'] = $val['site_logo_h'];
			}else{
                $logo[$k]['width'] = $val['mem_logo_w'];
				$logo[$k]['height'] = $val['mem_logo_h'];
			}
			if (strstr($val['logo_url'],'swf')) {
			   $logo[$k]['itype'] = 2;
			}else{
				$logo[$k]['itype'] = 1;
			}
			$logo[$k]['logo_url'] = 'http://'.$img_url['conf_www'].ltrim($val['logo_url'],'.');

		}
		$this->add("logo",$logo);
		$this->add("img_url",$img_url['conf_www']);
		$this->display('site_info/logo_index.html');
	}

	public function logo_title_do(){
		$id = intval($this->input->post("id"));
        $arr['title'] = $this->input->post('title');
        // $arr['start_date'] = $this->input->post('start_date');
        // $arr['end_date'] = $this->input->post('end_date');
        $arr['state'] = $this->input->post('state');
        if (empty($id) || empty($arr['title'])) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        $lid = $this->Info_logo_model->rupdate('info_logo_edit','id',$id,$arr);
        if ($lid) {
        	$drr['log_info'] = '成功修改站内LOGO,ID:'.$lid;
            $this->Info_logo_model->Syslog($drr);
            show_error('修改成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
        	$drr['log_info'] = '修改站内LOGO失败,ID:'.$lid;
            $this->Info_logo_model->Syslog($drr);
            show_error('修改成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        } 
	}

	//图片上传
	public function up_logo_do(){
		$id = intval($this->input->post('id'));
		$arr['type'] = intval($this->input->post('type'));
		if (empty($id) || empty($arr['type'])) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
		$config['upload_path'] = UPIMG_URL;
		$config['allowed_types'] = 'gif|jpeg|jpg|png|swf';//文件类型
		$config['max_size'] = '1000000';
		$this->load->library('upload',$config);
        if($this->upload->do_upload('logo')){
           $upload_data = $this->upload->data();
           $arr['img_url'] = './site_info/img/'.$upload_data['file_name'];
           $arr['site_id'] = $_SESSION['site_id'];
           $arr['add_date'] = date('Y-m-d H:i:s');
           if ($this->Info_logo_model->uplogo($arr,$id)) {
           	  $drr['log_info'] = '上传LOGO成功,ID:'.$id;
              $this->Info_logo_model->Syslog($drr);
	          show_error('上传成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	       }else{
              //删除上传的文件
              $drr['log_info'] = '上传LOGO失败,ID:'.$id;
              $this->Info_logo_model->Syslog($drr);
			  //$result = @unlink($arr['img_url']);
	          show_error('上传失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	       } 
         }else{
         	$drr['log_info'] = '上传LOGO失败,ID:'.$id;
            $this->Info_logo_model->Syslog($drr);
            $error = array("error" => $this->upload->display_errors());
            show_error('上传错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
         }
	}

	public function logo_case(){
		$id = intval($this->input->get("id"));
        if (empty($id)) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        $arr = $this->Info_logo_model->rfind('info_logo_edit_view','id',$id,'title,type,site_id');
        $arr['eid'] = $id;//文案对应详情id
        $arr['add_date'] = date('Y-m-d H:i:s');//提交时间
        if ($this->Info_logo_model->logo_case($arr,$id)) {
        	$drr['log_info'] = '存储LOGO案件成功,ID:'.$id;
              $this->Info_logo_model->Syslog($drr);
            show_error('存储案件成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
        	$drr['log_info'] = '存储LOGO案件失败,ID:'.$id;
            $this->Info_logo_model->Syslog($drr);
            show_error('存储案件失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        } 
	}
}
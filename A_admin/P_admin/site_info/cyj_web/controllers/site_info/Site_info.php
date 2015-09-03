<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Site_info extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('site_info/Site_info_model');
	}

	public function website() {
		$data = $this->Site_info_model->rfind('web_config','site_id',$_SESSION['site_id']);
		$this->add("data",$data);
		$this->display('site_info/website.html');
	}

	public function website_do(){
		$arr['web_name'] = $this->input->post("web_name");
        $arr['copy_right'] = $this->input->post('copyright');
        $arr['qq'] = intval($this->input->post('qq'));
        $arr['keywords'] = $this->input->post('keywords');
        $arr['description'] = $this->input->post('description');
        $arr['email'] = $this->input->post('email');
        $arr['remember'] = $this->input->post('remember');
        $arr['tel'] = $this->input->post('tel');
        $arr['online_service'] = $this->input->post('online_service');
        if (empty($arr['web_name']) || empty($arr['copy_right'])) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        if ($this->Site_info_model->rupdate('web_config','site_id',$_SESSION['site_id'],$arr)) {
        	$drr['log_info'] = '成功修改网站基本信息';
        	$this->Site_info_model->Syslog($drr);
            show_error('修改成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
        	$drr['log_info'] = '修改网站基本信息失败';
        	$this->Site_info_model->Syslog($drr);
            show_error('修改失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
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
           if ($this->Site_info_model->uplogo($arr,$id)) {
	          show_error('上传成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	       }else{
              //删除上传的文件
			  $result = @unlink($arr['img_url']);
	          show_error('上传失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	       } 
         }else{
            $error = array("error" => $this->upload->display_errors());
            show_error('上传错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
         }
	}
}
<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Info_flash extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('site_info/Info_flash_model');
	}

	public function flash_index() {
		$flash = $this->Info_flash_model->rget('info_flash_edit_view','site_id',$_SESSION['site_id']);
		$arr = array('A','B','C','D','E');
		$img_url = $this->Info_flash_model->rfind('web_config','site_id',$_SESSION['site_id']);
		foreach ($arr as $k => $v) {
			 $img_field = 'img_'.$v;
			 $s_field = 'state_'.$v;
			 $sz_field = 'state_z'.$v;
			 if (!empty($flash[0][$img_field])) {
	        	//非空表示启用
	        	$flash[0][$sz_field] = '<font class="font_up open_s" data-id="'.$flash[0]['id'].'" data-type= "'.$v.'" data-state=1 style="color:#07B817;">【启用】</font>';
	        	$flash[0][$s_field] = 1;
	        }else{
	        	$flash[0][$sz_field] = '<font class="font_up open_s" data-id="'.$flash[0]['id'].'" data-type= "'.$v.'" data-state=0 style="color:#CB102C;;">【停用】</font>';
	        	$flash[0][$s_field] = 0;
	        }
	        $flash[0][$img_field] = 'http://'.$img_url['conf_www'].ltrim($flash[0][$img_field],'.');
		}
		$this->add('cstate',$flash[0]['case_state']);
		$this->add("flash",$flash);
		$this->display('site_info/info_flash.html');
	}

	public function flash_title_do(){
		$id = intval($this->input->get("id"));
        $type = $this->input->get('type');
        $state = intval($this->input->get('state'));
        $img_field = 'img_'.$type;
        $title_field = 'title_'.$type;
        $url_field = 'url_'.$type;
        $arr[$title_field] = $this->input->get('title');
        $arr[$url_field] = $this->input->get('url');


        if (empty($state)) {
        	$arr[$img_field] = 0;
        }else{
        	$arr[$img_field] = 1;
        }
	
        if (empty($id) || empty($type)) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        if ($this->Info_flash_model->rupdate('info_flash_edit','id',$id,$arr)) {
        	$drr['log_info'] = '修改首页幻灯片标题成功,ID:'.$id;
	        $this->Info_flash_model->Syslog($drr);
            show_error('修改成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
        	$drr['log_info'] = '修改首页幻灯片标题失败,ID:'.$id;
	        $this->Info_flash_model->Syslog($drr);
            show_error('修改失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        } 
	}


	 //图片上传
	public function up_flash_do(){
		$id = intval($this->input->post('id'));
		$img_type = $this->input->post('type');//图片字段拼接专用
		$title_field = 'title_'.$img_type;
        $url_field = 'url_'.$img_type;
		if (empty($id) || empty($img_type)) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        $img_field = 'img_'.$img_type;
		$config['upload_path'] = UPIMG_URL;
		$config['allowed_types'] = 'gif|jpeg|jpg|png|swf';//文件类型
		$config['max_size'] = '1000000';
		$this->load->library('upload',$config);
        if($this->upload->do_upload('flash') || empty($_POST['filename'])){
           if (!empty($_POST['filename'])) {
                $upload_data = $this->upload->data();
                $arr[$img_field] = './site_info/img/'.$upload_data['file_name'];
           }
           $arr[$title_field] = $this->input->post('title');
           $arr[$url_field] = $this->input->post('url');
           if ($this->Info_flash_model->upflash($arr,$id)) {
           	  $drr['log_info'] = '上传首页幻灯片成功,ID:'.$id;
	          $this->Info_flash_model->Syslog($drr);
	          show_error('上传成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	       }else{
              //删除上传的文件
			  //$result = @unlink($arr['img_url']);
			  $drr['log_info'] = '上传首页幻灯片失败,ID:'.$id;
	          $this->Info_flash_model->Syslog($drr);
	          show_error('上传失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	       } 
         }else{
            // $error = array("error" => $this->upload->display_errors());
            $drr['log_info'] = '上传首页幻灯片失败,ID:'.$id;
	        $this->Info_flash_model->Syslog($drr);
            show_error('上传错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
         }
	}

	public function flash_case(){
		$eid = intval($this->input->get('id'));
		$type = $this->input->get('type');
		if (empty($eid) && empty($type)) {
	        show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	  
		}
   
		$arr['eid'] = $eid;
		$arr['type'] = $type;
		$arr['title'] = '首页轮播图';
		$arr['site_id'] = $_SESSION['site_id'];
        $arr['add_date'] = date('Y-m-d H:i:s');//提交时间
        if ($this->Info_flash_model->flash_case($arr,'id',$eid)) {
        	$drr['log_info'] = '存储首页幻灯片案件成功,ID:'.$id;
	        $this->Info_flash_model->Syslog($drr);
            show_error('存储案件成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
        	$drr['log_info'] = '存储首页幻灯片案件失败,ID:'.$id;
	        $this->Info_flash_model->Syslog($drr);
            show_error('存储案件失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        } 
	}
	
}


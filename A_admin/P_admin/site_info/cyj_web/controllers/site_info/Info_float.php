<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Info_float extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('site_info/Info_float_model');
	}

	public function float_index() {
		$float = $this->Info_float_model->rget('info_float_c_edit','site_id',$_SESSION['site_id']);
		foreach ($float as $k => $val) {
			if ($val['state'] == 2) {
				$float[$k]['state_z'] = '<font style="color:#CB102C;;">停用</font>';
			}else{
                $float[$k]['state_z'] = '<font style="color:#07B817;">启用</font>';
			}
			if ($val['case_state'] == 1) {
		   	   $cstate = 1;
		   	}else{
		   	   $cstate = 0;
		   	}
		   	$float[$k]['type_z'] = case_type($val['type']);
		}
		$this->add("float",$float);
		$this->add("cstate",$cstate);
		$this->display('site_info/info_float.html');
	}

	public function float_title_do(){
		$id = intval($this->input->post("id"));
        $arr['title'] = $this->input->post('title');
        $arr['state'] = $this->input->post('state');
        if (empty($id) || empty($arr['title'])) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        if ($this->Info_float_model->rupdate('info_float_c_edit','id',$id,$arr)) {
        	$drr['log_info'] = '修改首页幻灯片成功,ID:'.$id;
            $this->Info_float_model->Syslog($drr);
            show_error('修改成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
        	$drr['log_info'] = '修改首页幻灯片失败,ID:'.$id;
            $this->Info_float_model->Syslog($drr);
            show_error('修改失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        } 
	}

	//删除左右浮动图片
	public function float_list_del(){
       $id = intval($this->input->get('id'));
       if (empty($id)) {
       	  show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
       }
       if ($this->Info_float_model->rdel('info_float_list_edit','id',$id)) {
       	   $drr['log_info'] = '删除首页幻灯片成功,ID:'.$id;
           $this->Info_float_model->Syslog($drr);
           show_error('删除成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
       }else{
       	   $drr['log_info'] = '删除首页幻灯片失败,ID:'.$id;
           $this->Info_float_model->Syslog($drr);
           show_error('删除失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
       } 
	}

	public function info_float_e(){
		$id = intval($this->input->get("id"));
		if (empty($id)) {
			show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
		}
		$float_list = $this->Info_float_model->rget('info_float_list_view','fid',$id);
		foreach ($float_list as $k => $val) {
			$float_list[$k]['width'] = $val['float_w'];
			$float_list[$k]['height'] = $val['float_h'];
			if ($val['eff'] == '1') {
				$float_list[$k]['eff_z'] = '开启';
			}else{
				$float_list[$k]['eff_z'] = '关闭';
			}
		}
		$this->add('float_list',$float_list);
		$cstate = 1;
		$this->add("cstate",$cstate);
		$this->add("fid",$id);
		$this->display('site_info/info_float_list.html');
	}
	public function float_list_title_do(){
		$id = intval($this->input->post('id'));
		$arr['url'] = $this->input->post('url');
		$arr['sort'] = $this->input->post('sort');
		$arr['eff'] = $this->input->post('eff');
        if (empty($arr['url'])) {
        	$arr['url'] = '#';
        }
        if (empty($id)) {
        	//为空表示新增
        	$arr['fid'] = intval($this->input->post('fid'));//源ID
        	$arr['site_id'] = 't';//站点ID
        	$log_1 = $this->Info_float_model->radd('info_float_list_edit',$arr);
        	if ($log_1) {
	            show_error('新增成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	        }else{
	            show_error('新增失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	        } 
        }else{
        	//编辑
        	if ($this->Info_float_model->rupdate('info_float_list_edit','id',$id,$arr)){
	            show_error('修改成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	        }else{
	            show_error('修改失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	        } 
        }
	}

	 //图片上传
	public function up_float_do(){
		$id = intval($this->input->post('id'));
		$arr['type'] = intval($this->input->post('type'));
		if (empty($id) || empty($arr['type'])) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
		$config['upload_path'] = UPIMG_URL;
		$config['allowed_types'] = 'gif|jpeg|jpg|png|swf';//文件类型
		$config['max_size'] = '1000000';
		$this->load->library('upload',$config);
        if($this->upload->do_upload('float')){
           $upload_data = $this->upload->data();
           $arr['img_url'] = './site_info/img/'.$upload_data['file_name'];
           $arr['site_id'] = $_SESSION['site_id'];
           $arr['add_date'] = date('Y-m-d H:i:s');
           if ($this->Info_float_model->upfloat($arr,$id)) {
           	  $drr['log_info'] = '上传首页幻灯片图片成功,ID:'.$id;
              $this->Info_float_model->Syslog($drr);
	          show_error('上传成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	       }else{
              //删除上传的文件
			  //$result = @unlink($arr['img_url']);
			  $drr['log_info'] = '上传首页幻灯片图片失败,ID:'.$id;
              $this->Info_float_model->Syslog($drr);
	          show_error('上传失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	       } 
         }else{
            // $error = array("error" => $this->upload->display_errors());
            $drr['log_info'] = '上传首页幻灯片图片失败,ID:'.$id;
            $this->Info_float_model->Syslog($drr);
            show_error('上传错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
         }
	}

	public function float_case(){
		$fid = intval($this->input->get('fid'));
		if (empty($fid)) {
			show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
		}
		$arr_f = $this->Info_float_model->rget('info_float_list_view','fid',$fid,'type,title,id');
		$arr['flash_id'] = '';
		foreach ($arr_f as $k => $v) {
		   $arr['flash_id'] .= $v['id'].',';
		}
		$arr['flash_id'] = rtrim($arr['flash_id'],',');
		$arr['type'] = $arr_f[0]['type'];
		$arr['title'] = $arr_f[0]['title'];
		$arr['site_id'] = $_SESSION['site_id'];
		$arr['eid'] = $fid;
        if (empty($arr['type'])) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        $arr['add_date'] = date('Y-m-d H:i:s');//提交时间
        if ($this->Info_float_model->float_case($arr,$fid)) {
        	$drr['log_info'] = '存储首页幻灯片案件成功,ID:'.$id;
            $this->Info_float_model->Syslog($drr);
            show_error('存储案件成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
        	$drr['log_info'] = '存储首页幻灯片案件失败,ID:'.$id;
            $this->Info_float_model->Syslog($drr);
            show_error('存储案件失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        } 
	}

	
}
<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Info_flash extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('site_info/Info_flash_model');
	}

	public function flash_index() {
		$index_id = $this->input->get('index_id');
                $index_id = empty($index_id)?'a':$index_id;
                $map = array();
                $map['table'] = 'info_flash_edit';
                $map['where']['site_id'] = $_SESSION['site_id'];
                $map['where']['index_id'] = $index_id;
		        $flash = $this->Info_flash_model->rget($map);

                //初始化数据
                $map_a = array();
                $map_a['table'] = 'info_flash_edit';
                $map_a['where']['site_id'] = $_SESSION['site_id'];
                $map_a['where']['index_id'] = $index_id;
                $map_a['where']['type'] = 13;
                $flash_a = $this->Info_flash_model->rget($map_a);
                if(empty($flash_a)){
                    $add_flasha = array();
                    $add_flasha['site_id'] = $_SESSION['site_id'];
                    $add_flasha['index_id'] = $index_id;
                    $add_flasha['type'] = 13;
                    if($this->Info_flash_model->create_wap_date($add_flasha)){
                        $this->flash_index();
                        die;
                    }else{
                        show_error('初始化数据失败，请重新操作!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
                    }
                }

                //初始化数据
                $map_b = array();
                $map_b['table'] = 'info_flash_edit';
                $map_b['where']['site_id'] = $_SESSION['site_id'];
                $map_b['where']['index_id'] = $index_id;
                $map_b['where']['type'] = 31;
                $flash_a = $this->Info_flash_model->rget($map_b);
                if(empty($flash_a)){
                    $add_flashb = array();
                    $add_flashb['site_id'] = $_SESSION['site_id'];
                    $add_flashb['index_id'] = $index_id;
                    $add_flashb['type'] = 31;
                    if($this->Info_flash_model->create_wap_date($add_flashb)){
                        $this->flash_index();
                        die;
                    }else{
                        show_error('初始化数据失败，请重新操作!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
                    }
                }

		if (!empty($flash)) {
		    $this->add('flash_state',1);
		}
		$arr = array('A','B','C','D','E');
		$img_url = $this->Info_flash_model->rfind(array('table'=>'web_config','where'=>array('site_id'=>$_SESSION['site_id'],'index_id'=>$index_id)));
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
                    if(count($flash)>1){
                        if (!empty($flash[1][$img_field])) {
                            //非空表示启用
                            $flash[1][$sz_field] = '<font class="font_up open_s" data-id="'.$flash[1]['id'].'" data-type= "'.$v.'" data-state=1 style="color:#07B817;">【启用】</font>';
                            $flash[1][$s_field] = 1;
                       }else{
                            $flash[1][$sz_field] = '<font class="font_up open_s" data-id="'.$flash[1]['id'].'" data-type= "'.$v.'" data-state=0 style="color:#CB102C;;">【停用】</font>';
                            $flash[1][$s_field] = 0;
                       }
                            $flash[1][$img_field] = 'http://'.$img_url['wap_url'].ltrim($flash[1][$img_field],'.');
                    }
                }
	        
		       //多站点判断
            if (!empty($_SESSION['index_id'])) {
                $this->add('sites_str','站点：'.str_replace('全部', '请选择站点',$this->Info_flash_model->select_sites()));
                $this->add('index_id',$index_id);
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
        $map = array();
        $map['table'] = 'info_flash_edit';
        $map['where']['id'] = $id;
        $map['where']['site_id'] = $_SESSION['site_id'];
        if ($this->Info_flash_model->rupdate($map,$arr)) {
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
                $wap_pc_type = $this->input->post('wap_pc_type');//区分是pc端还是wap端轮播
		$index_id = $this->input->get('index_id');
		$index_id = empty($index_id)?'a':$index_id;
		$title_field = 'title_'.$img_type;
        $url_field = 'url_'.$img_type;
		if (empty($id) || empty($img_type)) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        $img_field = 'img_'.$img_type;
        if($wap_pc_type==31){
            $config['upload_path'] = UPIMG_URL.$index_id.'_wap/public/cdn/';
        }else{
            $config['upload_path'] = UPIMG_URL.$index_id.'/site_info/img/';
        }
		//$config['upload_path'] = UPIMG_URL.$index_id.'/site_info/img/';
		$config['allowed_types'] = 'gif|jpeg|jpg|png|swf';//文件类型
		$config['max_size'] = '1000000';
		$config['file_name'] = 'flsh'.date('YmdHis');
                
               if($_FILES['flash']['size']>=$config['max_size']){
                   $msg = '您上传的文件有'.$_FILES['flash']['size']/1024 .'KB，不要大于'.$config['max_size']/1024 . 'KB!'; 
                   show_error('上传失败!'.$msg.'<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
               }
		$this->load->library('upload',$config);
        if($this->upload->do_upload('flash') || empty($_POST['filename'])){
           if (!empty($_POST['filename'])) {
                $upload_data = $this->upload->data();
                if($wap_pc_type==31){
                   $arr[$img_field] = '/public/cdn/'.$upload_data['file_name'];
                }else{
                    $arr[$img_field] = '/site_info/img/'.$upload_data['file_name'];
                }
                
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
                $index_id = $this->input->get('index_id');
		$index_id = empty($index_id)?'a':$index_id;
		$eid = intval($this->input->get('id'));
		$type = $this->input->get('type');
		if (empty($eid) && empty($type)) {
	        show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');

		}

		$arr['eid'] = $eid;
		$arr['type'] = $type;
		$arr['title'] = '首页轮播图';
		$arr['site_id'] = $_SESSION['site_id'];
                $arr['index_id'] = $index_id;
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


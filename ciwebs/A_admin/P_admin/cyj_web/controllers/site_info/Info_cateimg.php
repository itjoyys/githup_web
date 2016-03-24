<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Info_cateimg extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('site_info/Info_cateimg_model');
	}

	public function cateimg_index() {
		$index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?'a':$index_id;
        $map = array();
        $map['table'] = 'info_cateimg_edit_view';
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['index_id'] = $index_id;
        $map['order'] = 'sort DESC';

		$cateimg = $this->Info_cateimg_model->rget($map);

		if (!empty($cateimg)) {
		    $this->add('cateimg_state',1);

		}

		$arr = array('a','b','c','d','e','f');

		$img_url = $this->Info_cateimg_model->rfind(array('table'=>'web_config','where'=>array('site_id'=>$_SESSION['site_id'],'index_id'=>$index_id)));
		foreach ($arr as $k => $v) {
			 $img_field = 'img_'.$v;
			 $s_field = 'state_'.$v;
			 $sz_field = 'state_z'.$v;
			 if (!empty($cateimg[0][$img_field])) {
	        	//非空表示启用
$cateimg[0][$sz_field] = '<font class="font_up open_s" data-id="'.$cateimg[0]['id'].'" data-type= "'.$v.'" data-state=1 style="color:#07B817;">【启用】</font>';
	        	$cateimg[0][$s_field] = 1;
	        }else{
	        	$cateimg[0][$sz_field] = '<font class="font_up open_s" data-id="'.$cateimg[0]['id'].'" data-type= "'.$v.'" data-state=0 style="color:#CB102C;;">【停用】</font>';
	        	$cateimg[0][$s_field] = 0;
	        }
	        $cateimg[0][$img_field] = 'http://'.$img_url['conf_www'].ltrim($cateimg[0][$img_field],'.');
		}

		       //多站点判断
        if (!empty($_SESSION['index_id'])) {
            $this->add('sites_str','站点：'.str_replace('全部', '请选择站点',$this->Info_cateimg_model->select_sites()));
            $this->add('index_id',$index_id);
        }

		$this->add('cstate',$cateimg[0]['case_state']);
		$this->add("cateimg",$cateimg);
		$this->display('site_info/info_cateimg.html');
	}

	 //图片上传
	public function up_cateimg_do(){
        $filename = $this->input->post('filename');
		$id = intval($this->input->post('id'));
		$type= $this->input->post('type');
		$url= $this->input->post('url');
		$index_id = $this->input->get('index_id');
		$index_id = empty($index_id)?'a':$index_id;
		if (empty($id) || empty($type)) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        $img_field = 'img_'.$type;
        $url_field = 'url_'.$type;
		$config['upload_path'] = UPIMG_URL.$index_id.'/site_info/img/';
		$config['allowed_types'] = 'gif|jpeg|jpg|png|swf';//文件类型
		$config['max_size'] = '1000000';
                
                if($_FILES['flash']['size']>=$config['max_size']){
                   $msg = '您上传的文件有'.$_FILES['flash']['size']/1024 .'KB，不要大于'.$config['max_size']/1024 . 'KB!'; 
                   show_error('上传失败!'.$msg.'<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
               }
		$this->load->library('upload',$config);
        if($this->upload->do_upload('cateimg') || empty($filename)){
           if (!empty($filename)) {
               $upload_data = $this->upload->data();
               $arr[$img_field] = '/site_info/img/'.$upload_data['file_name'];
           }
           $arr[$url_field] = $url;
           $map = array();
           $map['table'] = 'info_cateimg_edit';
           $map['where']['id'] = $id;
           $map['where']['site_id'] = $_SESSION['site_id'];
           if ($this->Info_cateimg_model->rupdate($map,$arr)) {
           	  $drr['log_info'] = '上传游戏分类图片成功,ID:'.$id;
              $this->Info_cateimg_model->Syslog($drr);
	          show_error('上传成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	       }else{
              //删除上传的文件
              $drr['log_info'] = '上传游戏分类图片失败,ID:'.$id;
              $this->Info_cateimg_model->Syslog($drr);
			 // $result = @unlink($arr[$img_field]);
	          show_error('上传失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	       }
         }else{
         	$drr['log_info'] = '上传游戏分类图片失败,ID:'.$id;
            $this->Info_cateimg_model->Syslog($drr);
            $error = array("error" => $this->upload->display_errors());
            show_error('上传错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
         }
	}

	public function cateimg_case(){
                $index_id = $this->input->get('index_id');
                $index_id = empty($index_id)?'a':$index_id;
		$eid = intval($this->input->get('id'));
		$type = $this->input->get('type');
		$arr['eid'] = $eid;
		$arr['type'] = $type;
		$arr['title'] = '首页游戏图';
		$arr['site_id'] = $_SESSION['site_id'];
                $arr['index_id'] = $index_id;
        if (empty($eid) || empty($type)) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        $arr['add_date'] = date('Y-m-d H:i:s');//提交时间
        if ($this->Info_cateimg_model->cateimg_case($arr,$eid)) {
        	$drr['log_info'] = '存储游戏分类图片案件成功,ID:'.$id;
            $this->Info_cateimg_model->Syslog($drr);
            show_error('存储案件成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
        	$drr['log_info'] = '存储游戏分类图片案件失败,ID:'.$id;
            $this->Info_cateimg_model->Syslog($drr);
            show_error('存储案件失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
	}


}
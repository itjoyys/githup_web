<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Video_info extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('site_info/Video_info_model');
	}

	public function video_index() {
		$index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?'a':$index_id;
		$data = $this->Video_info_model->get_views($index_id);
		$video_module = $data['video_module'];
		$array_video = explode(',',$video_module);
		$array_video2 = array_flip($array_video);
		// foreach ($array_video2 as $key => $value) {
		// 	$array_video2[$key] = $value*2+2;
		// }

			//获取视讯自定义图片
		$video_imgs = $this->Video_info_model->get_imgs($index_id);

		foreach ($array_video2 as $key => $value) {
			$array_video2[$key] = $value*2+2;
			if (empty($video_imgs[$key])) {
			    $video_imgs[$key]['img_url'] = '/public/images/img_0'.$key.'.png';
			}
            //去掉pt pk电子
			if ($key == 'pt' || $key == 'pk') {
			    unset($array_video2[$key]);
			    unset($video_imgs[$key]);
			}
		}


        //获取网址
        $map = array();
        $map['table'] = 'web_config';
        $map['select'] = "conf_www";
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['index_id'] = $index_id;
		$conf_www = $this->Video_info_model->rfind($map);

			//多站点判断
	    if (!empty($_SESSION['index_id'])) {
            $this->add('sites_str','站点：'.str_replace('全部', '请选择站点',$this->Video_info_model->select_sites()));
            $this->add('index_id',$index_id);
	    }
        $_SESSION['video'] = $array_video2;
        $this->add("array_video",$array_video2);
        $this->add('conf_www',$conf_www['conf_www']);
        $this->add('video_imgs',$video_imgs);
        $this->add('site_id',$_SESSION['site_id']);
		$this->display('site_info/video_info.html');
	}

	//修改序号
	public function video_edit(){
		$index_id = $this->input->post('index_id');
		$index_id = empty($index_id)?'a':$index_id;
		$name = $this->input->post('title');
		$order = $this->input->post('order');
		$array_video = $_SESSION['video'];
		$array_video[$name] = $order;
		$array_video = array_flip($array_video);
		ksort($array_video);
		$new = implode(",",$array_video);
		$result = $this->Video_info_model->get_edit($index_id,$new);
		if($result){
			show_error('修改成功!<a href="video_index">返回</a>', 200, '提示');
		}else{
			show_error('修改失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
		}
	}


		//图片上传
	public function up_videoimg_do(){
		$vtype = $this->input->post('vtype');//视讯类别
        $index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?'a':$index_id;
		if (empty($vtype)) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
		$config['upload_path'] = '../../'.$_SESSION['site_id'].'_'.$index_id.'/site_info/img/';
		$config['allowed_types'] = 'gif|jpeg|jpg|png|swf';//文件类型
		$config['max_size'] = '1000000';
		$config['file_name'] = 'video'.date('YmdHis');
                
                if($_FILES['flash']['size']>=$config['max_size']){
                   $msg = '您上传的文件有'.$_FILES['flash']['size']/1024 .'KB，不要大于'.$config['max_size']/1024 . 'KB!'; 
                   show_error('上传失败!'.$msg.'<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
               }
		$this->load->library('upload',$config);
        if($this->upload->do_upload('videoimg')){
           $upload_data = $this->upload->data();
           $arr['img_url'] = '/site_info/img/'.$upload_data['file_name'];
           $arr['site_id'] = $_SESSION['site_id'];
           $arr['index_id'] = $index_id;
           $arr['type'] = $vtype;
           $arr['add_date'] = date('Y-m-d H:i:s');
           if ($this->Video_info_model->up_video_img($arr)) {
           	  $drr['log_info'] = '上传视讯图片:'.$vtype;
              //$this->Video_info_model->Syslog($drr);
	          show_error('上传成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	       }else{
              //删除上传的文件
              $drr['log_info'] = '上传视讯图片失败:'.$vtype;
             // $this->Video_info_model->Syslog($drr);
			  //$result = @unlink($arr['img_url']);
	          show_error('上传失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	       }
         }else{
         	$drr['log_info'] = '上传LOGO失败,ID:'.$id;
           // $this->Info_logo_model->Syslog($drr);
            $error = array("error" => $this->upload->display_errors());
            show_error('上传错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
         }
	}

}
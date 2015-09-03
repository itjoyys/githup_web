<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Info_activity extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('site_info/Info_activity_model');
	}

	public function activity_index() {
		$pid = intval($this->input->get('pid'));
		if (empty($pid)) {
		    $pid = 0;
		}
		$activity = $this->Info_activity_model->get_activity($pid);
		foreach ($activity as $k => $val) {
			if ($val['state'] == 2) {
				$activity[$k]['state_z'] = '<font style="color:#CB102C;;">停用</font>';
			}else{
                $activity[$k]['state_z'] = '<font style="color:#07B817;">启用</font>';
			}
			if ($val['case_state'] == 1) {
		   	   $cstate = 1;
		   	}else{
		   	   $cstate = 0;
		   	}
			$activity[$k]['width'] = $val['activity_w'];
			$activity[$k]['height'] = $val['activity_h'];
		}
		$this->add("activity",$activity);
		$this->add("cstate",$cstate);
		$this->add("pid",$pid);
		$this->display('site_info/info_activity.html');
	}

	public function activity_edit()
    {
        $id = intval($this->input->get("id"));
        if (empty($id)) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }

        $activity = $this->Info_activity_model->rfind('info_activity_edit','id',$id);
        $this->add("activity",$activity);
        $this->display('site_info/info_activity_e.html');
    }

    public function activity_del(){
    	$id = intval($this->input->get('id'));
    	if (empty($id)) {
    	    show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
    	}
    	$arr['state'] = 0;
    	if ($this->Info_activity_model->rupdate('info_activity_edit','id',$id,$arr)) {
    		$drr['log_info'] = '成功删除优惠活动信息,ID:'.$id;
        	$this->Info_activity_model->Syslog($drr);
            show_error('删除成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
        	$drr['log_info'] = '删除优惠活动信息失败,ID:'.$id;
        	$this->Info_activity_model->Syslog($drr);
            show_error('删除失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        } 
    }

	public function activity_title_do(){
		$id = intval($this->input->post("id"));
        $arr['title'] = $this->input->post('title');
        $arr['state'] = $this->input->post('state');
        $arr['sort'] = intval($this->input->post('sort'));
        $arr['ctype'] = intval($this->input->post('ctype'));
        $arr['pid'] = intval($this->input->post('pid'));
        if (empty($arr['pid'])) {
            $arr['pid'] = 0;
        }

        if (empty($arr['title']) || empty($arr['ctype'])) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }

        if (!empty($id)) {
	        if ($this->Info_activity_model->rupdate('info_activity_edit','id',$id,$arr)) {
	        	$drr['log_info'] = '成功修改优惠活动标题信息,ID:'.$id;
	        	$this->Info_activity_model->Syslog($drr);
	            show_error('修改成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	        }else{
	        	$drr['log_info'] = '修改优惠活动标题信息失败,ID:'.$id;
	        	$this->Info_activity_model->Syslog($drr);
	            show_error('修改失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	        } 
        }else{
        	$arr['site_id'] = $_SESSION['site_id'];
        	$arr['type'] = 14;
        	if ($this->Info_activity_model->radd('info_activity_edit',$arr)) {
	        	$drr['log_info'] = '成功添加优惠活动标题信息,ID:'.$id;
	        	$this->Info_activity_model->Syslog($drr);
	            show_error('添加成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	        }else{
	        	$drr['log_info'] = '添加优惠活动标题信息失败,ID:'.$id;
	        	$this->Info_activity_model->Syslog($drr);
	            show_error('添加失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	        } 
        }

	}

	 //图片上传
	public function up_activity_do(){
		$id = intval($this->input->post('id'));
		$arr['type'] = intval($this->input->post('type'));
		if (empty($id) || empty($arr['type'])) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
		$config['upload_path'] = UPIMG_URL;
		$config['allowed_types'] = 'gif|jpeg|jpg|png|swf';//文件类型
		$config['max_size'] = '1000000';
		$this->load->library('upload',$config);
        if($this->upload->do_upload('img')){
           $upload_data = $this->upload->data();
           $arr['img_url'] = './site_info/img/'.$upload_data['file_name'];
           if ($this->Info_activity_model->upactivity($arr,$id)) {
	          show_error('上传成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	       }else{
              //删除上传的文件
			 //$result = @unlink($arr['img_url']);
	          show_error('上传失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	       } 
         }else{
            // $error = array("error" => $this->upload->display_errors());
            show_error('上传错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
         }
	}

	public function activity_content_do(){
		$id = intval($this->input->get("id"));
		$arr = array();
		$arr['content'] = $this->input->post('content');
		if (empty($id) || empty($arr['content'])) {
		    show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
		}
		if ($this->Info_activity_model->rupdate('info_activity_edit','id',$id,$arr)) {
			  $drr['log_info'] = '成功更新优惠活动内容信息,ID:'.$id;
	          $this->Info_activity_model->Syslog($drr);
	          show_error('更新成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	    }else{
	    	  $drr['log_info'] = '更新优惠活动内容信息失败,ID:'.$id;
	          $this->Info_activity_model->Syslog($drr);
	          show_error('更新失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	    } 
	}

	public function activity_case(){
		$arr_f = $this->Info_activity_model->get_state_activity('1','type,id');
		$arr['flash_id'] = '';
		foreach ($arr_f as $k => $v) {
		   $arr['flash_id'] .= $v['id'].',';
		}
		$arr['flash_id'] = rtrim($arr['flash_id'],',');
		$arr['type'] = $arr_f[0]['type'];
		$arr['title'] = '优惠活动';
		$arr['site_id'] = $_SESSION['site_id'];
        if (empty($arr['type'])) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        $arr['add_date'] = date('Y-m-d H:i:s');//提交时间
        if ($this->Info_activity_model->activity_case($arr)) {
        	$drr['log_info'] = '存储优惠活动案件成功,ID:'.$id;
	        $this->Info_activity_model->Syslog($drr);
            show_error('存储案件成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
        	$drr['log_info'] = '存储优惠活动案件失败,ID:'.$id;
	        $this->Info_activity_model->Syslog($drr);
            show_error('存储案件失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        } 
	}
    //编辑器中图片上传
	public function activity_c_img_up(){
		header("Content-Type: text/html; charset=utf-8");
		$config_json = file_get_contents(dirname(__file__)."/ueditor/config.json");
        $new_url = '../../'.$_SESSION['site_id'].'/site_info/img';
        $config_json = str_replace('../../t/site_info/img',$new_url,$config_json);
        $CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "",$config_json), true);
		$img_www = '';
        $img_www = $this->Info_activity_model->rfind('web_config','site_id',$_SESSION['site_id']);
		$action = $_GET['action'];
		switch ($action) {
		    case 'config':
		        $result =  json_encode($CONFIG);
		        break;
		    /* 上传图片 */
		    case 'uploadimage':
		    /* 上传涂鸦 */
		    case 'uploadscrawl':
		    /* 上传视频 */
		    case 'uploadvideo':
		    /* 上传文件 */
		    case 'uploadfile':
		        $result = include(dirname(__file__)."/ueditor/action_upload.php");
		        break;
		    /* 列出图片 */
		    case 'listimage':
		        $result = include(dirname(__file__)."/ueditor/action_list.php");
		        break;
		    /* 列出文件 */
		    case 'listfile':
		        $result = include(dirname(__file__)."/ueditor/action_list.php");
		        break;

		    /* 抓取远程文件 */
		    case 'catchimage':
		        $result = include(dirname(__file__)."/ueditor/action_crawler.php");
		        break;
		    default:
		        $result = json_encode(array(
		            'state'=> '请求地址出错'
		        ));
		        break;
		}
		/* 输出结果 */
		if (isset($_GET["callback"])) {
		    if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
		        echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
		    } else {
		        echo json_encode(array(
		            'state'=> 'callback参数不合法'
		        ));
		    }
		} else {
		    echo $result;
		}
	}

	
}
<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Site_pop extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('site_info/Site_pop_model');
	}

	public function pop() {
        $count = $this->Site_pop_model->get_pop_count();
        if ($count > 0) {
            $per_page = 20;
            $totalPage = ceil($count / $per_page);
            $page =  $this->input->get("per_page");
            $page = ($page==0)?1:$page;
            $offset = ($page - 1) * $per_page;
        }
        $this->load->library('pagination');
        $config['base_url'] = '/site_info/index.php/site_info/site_pop/pop';
        $config['total_rows'] = $count;
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $this->pagination->use_page_numbers = true;
        $this->pagination->page_query_string = true;
        $this->add("page_html", $this->pagination->create_links());
        $this->add("page", $page);
        $data = $this->Site_pop_model->get_pop($per_page,$offset);
        foreach ($data as $key => $val) {
            if ($val['is_delete'] == '1') {
                $data[$key]['state_z'] = '<font style="color:#07B817;">启用</font>';
            }else{
                $data[$key]['state_z'] = '<font style="color:#CB102C;;">停用</font>';
            }

            if ($val['ad_type'] == '1') {
                $data[$key]['ad_type_z'] = '中间弹窗';
            }
        }
        $pop_config = $this->Site_pop_model->rfind('site_pop_config','site_id',$_SESSION['site_id']);
        if (!empty($pop_config)) {
            $this->add("title_bcolor",$pop_config['title_bcolor']);
            $this->add("pop_bcolor",$pop_config['pop_bcolor']);
            $this->add("title_color",$pop_config['title_color']);
            $this->add("id",$pop_config['id']);
        }
		$this->add("data",$data);
		$this->display('site_info/pop.html');
	}

    public function pop_config_do(){
        $id = intval($this->input->post('pop_id'));
        $arr['title_bcolor'] = $this->input->post('title_bcolor');
        $arr['pop_bcolor'] = $this->input->post('pop_bcolor');
        $arr['title_color'] = $this->input->post('title_color');
        if (empty($arr['title_bcolor']) && empty($arr['pop_bcolor'])) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示'); 
        }
        if (!empty($id)) {
            if ($this->Site_pop_model->rupdate('site_pop_config','id',$id,$arr)) {
                $drr['log_info'] = '成功修改站内弹窗广告样式,ID:'.$id;
                $this->Site_pop_model->Syslog($drr);
                show_error('修改成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
            }else{
                $drr['log_info'] = '修改站内弹窗广告样式失败,ID:'.$id;
                $this->Site_pop_model->Syslog($drr);
                show_error('修改失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
            }
        }else{
            $arr['site_id'] = $_SESSION['site_id'];
            $id = $this->Site_pop_model->radd('site_pop_config',$arr);
            if ($id) {
                $drr['log_info'] = '成功修改站内弹窗广告样式,ID:'.$id;
                $this->Site_pop_model->Syslog($drr);
                show_error('修改成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
            }else{
                $drr['log_info'] = '修改站内弹窗广告样式失败,ID:'.$id;
                $this->Site_pop_model->Syslog($drr);
                show_error('修改失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
            }
        }

    }

    Public function pop_edit(){
        $id = intval($this->input->get('id'));
        if (!empty($id)) {
            $data = $this->Site_pop_model->rfind('site_ad','id',$id);
        }
        $this->add('data',$data);
        $this->display('site_info/pop_add.html');
    }

    Public function add_pop_do(){
        $id = intval($this->input->post('id'));
        $arr['title'] = $this->input->post('title');
        $arr['ad_type'] = $this->input->post('ad_type');
        $arr['content'] = $this->input->post('content');
        if (!empty($id)) {
            //不为空表示更新
            if ($this->Site_pop_model->rupdate('site_ad','id',$id,$arr)) {
                $drr['log_info'] = '成功修改站内弹窗广告,ID:'.$id;
                $this->Site_pop_model->Syslog($drr);
                show_error('修改成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
            }else{
                $drr['log_info'] = '修改站内弹窗广告失败,ID:'.$id;
                $this->Site_pop_model->Syslog($drr);
                show_error('修改失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
            }
        }else{
            //表示添加
            $arr['is_delete'] = 1;
            $arr['site_id'] = $_SESSION['site_id'];
            $arr['add_date']  = date('Y-m-d H:i:s');
            $aid = $this->Site_pop_model->radd('site_ad',$arr);
            if ($aid) {
                $drr['log_info'] = '成功添加站内弹窗广告,ID:'.$aid;
                $this->Site_pop_model->Syslog($drr);
                show_error('添加成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
            }else{
                $drr['log_info'] = '添加站内弹窗广告失败,ID:'.$aid;
                $this->Site_pop_model->Syslog($drr);
                show_error('添加失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
            }
        }
    }

    Public function pop_del(){
        $id = intval($this->input->get('id'));
        if (empty($id)) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示'); 
        }
        $arr['is_delete'] = 0;
        if ($this->Site_pop_model->rupdate('site_ad','id',$id,$arr)) {
            $drr['log_info'] = '成功删除站内弹窗广告,ID:'.$id;
            $this->Site_pop_model->Syslog($drr);
            show_error('删除成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
            $drr['log_info'] = '删除站内弹窗广告失败,ID:'.$id;
            $this->Site_pop_model->Syslog($drr);
            show_error('删除失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        } 
    }

	public function pop_title_do(){
        $id = intval($this->input->post('id'));
		$arr['title'] = $this->input->post("title");
        $arr['ad_type'] = $this->input->post('ad_type');
        $arr['is_delete'] = intval($this->input->post('is_delete'));
        if (empty($arr['title']) || empty($id)) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        if ($this->Site_pop_model->rupdate('site_ad','id',$id,$arr)) {
        	$drr['log_info'] = '成功修改站内弹窗广告标题信息,ID:'.$id;
        	$this->Site_pop_model->Syslog($drr);
            show_error('修改成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
        	$drr['log_info'] = '修改站内弹窗广告标题信息失败,ID:'.$id;
        	$this->Site_pop_model->Syslog($drr);
            show_error('修改失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        } 
	}

    //编辑器中图片上传
    public function pop_c_img_up(){
        header("Content-Type: text/html; charset=utf-8");
        $config_json = file_get_contents(dirname(__file__)."/ueditor/config.json");
        $new_url = '../../'.$_SESSION['site_id'].'/site_info/img';
        $config_json = str_replace('../../t/site_info/img',$new_url,$config_json);
        $CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "",$config_json), true);
        $img_www = '';
        $img_www = $this->Site_pop_model->rfind('web_config','site_id',$_SESSION['site_id']);
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
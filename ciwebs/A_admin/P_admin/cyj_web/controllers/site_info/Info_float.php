<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Info_float extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('site_info/Info_float_model');
	}

	public function float_index() {
        $index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?'a':$index_id;
		    $this->db->from('info_float_c_edit');
        $this->db->where("index_id",$index_id);
		    $this->db->where("site_id",$_SESSION['site_id']);
		    $float=$this->db->get()->result_array();
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
                //多站点判断
                if (!empty($_SESSION['index_id'])) {
                    $this->add('sites_str','站点：'.str_replace('全部', '请选择站点',$this->Info_float_model->select_sites()));
                    $this->add('index_id',$index_id);
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
            $map = array();
            $map['table'] = 'info_float_c_edit';
            $map['where']['id'] = $id;
            $map['where']['site_id'] = $_SESSION['site_id'];
            if ($this->Info_float_model->rupdate($map,$arr)) {
                    $drr['log_info'] = '修改左右浮动图成功,ID:'.$id;
                $this->Info_float_model->Syslog($drr);
                show_error('修改成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
            }else{
                    $drr['log_info'] = '修改左右浮动图失败,ID:'.$id;
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
        $map = array();
        $map['table'] = 'info_float_list_edit';
        $map['where']['id'] = $id;
        $arr['state'] = 3;
       if ($this->Info_float_model->rupdate($map,$arr)) {
       	   $drr['log_info'] = '删除左右浮动图成功,ID:'.$id;
           $this->Info_float_model->Syslog($drr);
           show_error('删除成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
       }else{
       	   $drr['log_info'] = '删除左右浮动图失败,ID:'.$id;
           $this->Info_float_model->Syslog($drr);
           show_error('删除失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
       }
	}

	public function info_float_e(){
		$id = intval($this->input->get("id"));
		if (empty($id)) {
			show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
		}
    $map = array();
    $this->db->from('info_float_c_edit');
    $this->db->where("id",$id);
    $listtype=$this->db->get()->row_array();
    if($listtype){
      $this->add('listtype',case_type($listtype['type']));
    }
    $index_id = $this->input->get('index_id');
    $index_id = empty($index_id)?'a':$index_id;
		$this->db->from('info_float_list_edit');
    $this->db->where("index_id",$index_id);
		$this->db->where("site_id",$_SESSION['site_id']);
    $this->db->where("fid",$id);
    $this->db->where("state",1);
    $this->db->order_by('sort','ASC');
		$float_list=$this->db->get()->result_array();
		foreach ($float_list as $k => $val) {
			if ($val['eff'] == '1') {
				$float_list[$k]['eff_z'] = '开启';
			}else{
				$float_list[$k]['eff_z'] = '关闭';
			}
      $float_list[$k]['urltypezh'] = $this->urltype($val['urltype']);
		}
		$this->add('float_list',$float_list);
    $this->add('index_id',$index_id);
		$cstate = 1;
		$this->add("cstate",$cstate);
		$this->add("fid",$id);
		$this->display('site_info/info_float_list.html');
	}

  public function urltype($urltype){
    switch ($urltype) {
      case '1':
        return '体育';
        break;
      case '2':
        return '视讯';
        break;
      case '3':
        return '电子';
        break;
      case '4':
        return '彩票';
        break;
      case '5':
        return '优惠活动';
        break;
      case '6':
        return '代理联盟';
        break;
      case '7':
        return '免费试玩';
        break;
      case '8':
        return '常见问题';
        break;
      case '9':
        return '关于我们';
        break;
      case '10':
        return '联系我们';
        break;
      case '11':
        return '会员注册';
        break;
      case '12':
        return '存款帮助';
        break;
      case '13':
        return '取款帮助';
        break;
    }
  }
	public function float_list_title_do(){
            $id = intval($this->input->post('id'));

            $arr['sort'] = $this->input->post('sort');
            $arr['eff'] = $this->input->post('eff');
            $arr['is_blank'] = $this->input->post('is_blank');
            $arr['is_inter'] = $this->input->post('is_inter');
            if($arr['is_inter'] == 0){
              $arr['urltype'] = $this->input->post('urltype');
              $arr['url'] = '';
            }else if($arr['is_inter'] == 1){
              $arr['url'] = $this->input->post('url');
              $arr['urltype'] = '';
            }
            $index_id = $this->input->post('index_id');
            $arr['index_id'] = empty($index_id)?'a':$index_id;
            if (empty($arr['url'])) {
                    $arr['url'] = '###';
            }
            if (empty($id)) {
                    //为空表示新增
                    $arr['fid'] = intval($this->input->post('fid'));//源ID
                    $arr['site_id'] = $_SESSION['site_id'];//站点ID
                    $arr['state'] =1;
                    $log_1 = $this->Info_float_model->radd('info_float_list_edit',$arr);
                    if ($log_1) {
                        show_error('新增成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
                    }else{
                        show_error('新增失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
                    }
            }else{
                    //编辑
                    $map = array();
                    $map['table'] = 'info_float_list_edit';
                    $map['where']['id'] = $id;
                    if ($this->Info_float_model->rupdate($map,$arr)){
                        show_error('修改成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
                    }else{
                        show_error('修改失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
                    }
            }
	}

	 //图片上传
	public function up_float_do(){
		$id = intval($this->input->post('id'));
		$type = intval($this->input->post('type'));
                $index_id = $this->input->post('index_id');
                $arr['index_id'] = empty($index_id)?'a':$index_id;
		if (empty($id) || empty($type)) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
		$config['upload_path'] = UPIMG_URL.$arr['index_id'].'/site_info/img/';
		$config['allowed_types'] = 'gif|jpeg|jpg|png|swf';//文件类型
		$config['max_size'] = '1000000';
		$config['file_name'] = 'flat'.date('YmdHis');
     //判断是否存在目录
     if(!is_dir($config['upload_path'])) {
          mkdir($config['upload_path'],0777,true);
      }

                if($_FILES['flash']['size']>=$config['max_size']){
                   $msg = '您上传的文件有'.$_FILES['flash']['size']/1024 .'KB，不要大于'.$config['max_size']/1024 . 'KB!';
                   show_error('上传失败!'.$msg.'<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
               }
		$this->load->library('upload',$config);
        if($this->upload->do_upload('flash')){
           $upload_data = $this->upload->data();
           $arr['img_url'] = '/site_info/img/'.$upload_data['file_name'];
           $arr['site_id'] = $_SESSION['site_id'];
           $arr['add_date'] = date('Y-m-d H:i:s');
           if ($this->Info_float_model->upfloat($arr,$id,$type)) {
           	  $drr['log_info'] = '上传左右浮动图成功,ID:'.$id;
              $this->Info_float_model->Syslog($drr);
	          show_error('上传成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	       }else{
              //删除上传的文件
			  //$result = @unlink($arr['img_url']);
			  $drr['log_info'] = '上传左右浮动图失败,ID:'.$id;
              $this->Info_float_model->Syslog($drr);
	          show_error('上传失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
	       }
         }else{
             //$error = array("error" => $this->upload->display_errors());
            $drr['log_info'] = '上传左右浮动图失败,ID:'.$id;
            $this->Info_float_model->Syslog($drr);
            show_error('上传错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
         }
	}

	public function float_case(){
            $index_id = $this->input->get('index_id');
                $index_id = empty($index_id)?'a':$index_id;
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
                $arr['index_id'] = $index_id;
        if (empty($arr['type'])) {
            show_error('参数错误!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
        $arr['add_date'] = date('Y-m-d H:i:s');//提交时间
        if ($this->Info_float_model->float_case($arr,$fid)) {
        	$drr['log_info'] = '存储左右浮动图案件成功,ID:'.$id;
            $this->Info_float_model->Syslog($drr);
            show_error('存储案件成功!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }else{
        	$drr['log_info'] = '存储左右浮动图案件失败,ID:'.$id;
            $this->Info_float_model->Syslog($drr);
            show_error('存储案件失败!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
        }
	}


}
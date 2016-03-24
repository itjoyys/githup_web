<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->model('member/News_model');
		$this->News_model->login_check($_SESSION['uid']);
	}

	//信息公告，最新消息
	public function latest_news(){
		$time=time()-60*60*24*3;
        $time=date('Y-m-d H:i:s',$time);
        $map['where'] = "is_delete ='0' and site_id='".SITEID."' and index_id = '".INDEX_ID."' and show_type='2' and add_time >'".$time."'";
        $map['order'] = "add_time desc";
        $news = $this->News_model->get_latest_news($map);

        $this->add('news', $news);
        $this->add('new_type', 'now');
        $this->display('member/latest_news.html');
	}

	//信息公告，最新消息——>历史消息
	public function histiry_news(){
		$map['where'] = "is_delete='0' and site_id='".SITEID."' and show_type='2' and index_id = '".INDEX_ID."'";
		$map['order'] = "add_time desc";
		$count = $this->News_model->get_histiry_news_count($map);
		if ($count > 0) {
			//分页
			$per_page =10;
			$perNumber = ($perNumber == 0) ? $per_page : $perNumber; //每页显示的记录数

			$totalPage = ceil($count / $perNumber); //计算出总页数
			$page =  $this->input->get("per_page");
			$page = ($page == 0) ? 1 :$page;
			$offset = ($page - 1) * $perNumber; //分页开始,根据此方法计算出开始的记录
			$hnews = $this->News_model->get_histiry_news($perNumber,$offset ,$map);
		}

		$this->load->library('pagination');
		$config['base_url'] = '/index.php/member/news/histiry_news';	//分页路径
		$config['total_rows'] = $count;
		$config['per_page'] = $per_page;
		$this->pagination->initialize($config);
		$this->pagination->use_page_numbers = true;
		$this->pagination->page_query_string = true;

		$this->add("page_html", $this->pagination->create_links());
		$this->add("page", $page);
		$this->add('new_type', 'his');
		$this->add("news", $hnews);
		$this->display('member/latest_news.html');

	}

	//信息公告，个人信息
	public function sms(){
		$row = $this->News_model->get_user_level_id($_SESSION['uid']);
		if(empty($row[0]['level_id'])){
			$data = array();
			$this->add("dqpage", 1);
			$this->add("totalPage",1);
			$this->add("data", $data);
			$this->display('member/sms.html');
			exit;
		}
		$map['where']="(uid='".$_SESSION["uid"]."' or (uid = '' and (level_id = '".$row[0]['level_id']."' or level_id = '-1')))   and is_delete = '0' and site_id = '".SITEID."' and index_id = '".INDEX_ID."'";
		$map['order']='islook asc,msg_id desc';
		$id = $this->News_model->get_sms_id($map);

		if($id){
			foreach ($id as $v) {
				$bid .=	$v['msg_id'].',';
			}
			$id		=	rtrim($bid,',');
			$count = $this->News_model->get_sms_count($id);
		

			//分页
			$perNumber=isset($_GET['page_num'])?$_GET['page_num']:10; //每页显示的记录数
			$totalPage=ceil($count/$perNumber); //计算出总页数
			$page=isset($_GET['page'])?$_GET['page']:1;
			if($totalPage<$page){
				$page = 1;
			}
			$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
			$map['limit']=$startCount;
			$map['limit2'] =$perNumber;

			$getpage = $this->input->get('page');
			if($getpage){
				$map['limit']=($getpage-1)*10;
				$this->add("dqpage", $getpage);
			}else{
				$this->add("dqpage", 1);
			}

			$data = $this->News_model->get_sms($id,$map);
			$this->add("totalPage",$totalPage);
			$this->add("data", $data);
		}
		$this->display('member/sms.html');
	}

	//信息公告，个人信息，信息状态改变
	public function sms_change(){
		$type = $this->input->post('type');
		$uid = $this->input->post('uid');
		if($type =='look'){
			$map['msg_id']=$uid;
			$query = $this->News_model->up_sms_change($map);
			if($query){
				echo  1;exit;
			}else{
				echo  2;exit;
			}
		}
	}

	//信息公告，游戏公告
	public function games_news(){
		$curr = $this->input->get('curr');
		$curr = !empty($curr) ? $curr : 'sp';
		$data = $this->News_model->get_sports_news($curr);
		$ntitle = array('sp'=>'体育公告','tv'=>'视讯公告','fc'=>'彩票公告');
		$this->add("curr", $curr);
		$this->add("news_title", $ntitle[$curr]);
		$this->add("data", $data);
		$this->display('member/member_news.html');
	}

}

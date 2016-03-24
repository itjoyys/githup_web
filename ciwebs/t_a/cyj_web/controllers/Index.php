<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller {
    public function __construct() {
		parent::__construct();

		$this->load->model('Index_model');
		$this->load->model('Common_model');
		$this->float_left();
		$this->notice_count();
		$this->site_pop(1);   // 弹窗
		$this->get_member();//会员信息
		$this->add("flash",$this->Index_model->get_flash());//获取首页轮播图

        //时间输出
		$time_md = date('Y') . '/' . date('m') . '/' . date('d') . ' ' . date('H') . ':' . date('i') . ':' . date('s');
		$this->add("timemd", $time_md);

        $web_data = $this->Common_model->get_copyright();
		$this->add('web_data',$web_data);

		$this->add('con_data',$web_data);
                $xl = $this->Index_model->get_xl_top();
                $this->add('xl',$xl[0]);
                $this->add('xl2',$xl[1]);
		$this->add('title',$web_data['web_name']);
        $this->add('copy_right',$web_data['copy_right']);
		$this->add("logo", $this->Index_model->get_logo());//获取logo
		$this->add('meau_foot',$this->Index_model->get_meau_footer());
		$this->add("left_ad",$this->Index_model->get_site_pop(3));//左下角广告

		$this->parse('bottom','web/bottom.html');

	}

	public function index() {
		$ty = $this->input->get('ty');     //获取type值，判断是否是预览
		$_SESSION['ty'] = $ty;
		//判断intr是否正确
		// $_GET = array_change_key_case($_GET, CASE_LOWER);
  //       $intr = $this->input->get('intr');
		// $url = URL.'/index.php/index/N_index';

		$urldata = $_SERVER["QUERY_STRING"];
		$urldata = explode('=',strtolower($urldata));
		$urldata = array_change_key_case($urldata, CASE_LOWER);

        switch ($urldata[0]) {
        	case 'uuno'://会员推广
                $url = URL.'/index.php/index/zhuce';
        		break;
        	case 'intr'://注册页面
                $url = URL.'/index.php/index/zhuce';
        		break;
        	case 'aff'://首页
                $url = URL.'/index.php/index/N_index';
        		break;
        	case 'live'://视讯页面
                $url = URL.'/index.php/index/livetop';
        		break;
        	case 'spor'://体育页面
                $url = URL.'/index.php/index/sports';
        		break;
        	case 'egam'://电子页面
                $url = URL.'/index.php/index/egame';
        		break;
        	case 'iyou'://优惠页面
                $url = URL.'/index.php/index/youhui';
        		break;
        	case 'lott'://彩票页面
                $url = URL.'/index.php/index/lottery';
        		break;
        	default:
        		$url = URL.'/index.php/index/N_index';
        		break;
        }
        //$intr = $urldata[1];

        if ($urldata[0] != 'uuno') {
            $intr = $urldata[1];
        }else{
        	$this->Index_model->is_uuno_true($urldata[1]);
        }


        if(!empty($intr)){
            $Astate = $this->Index_model->is_intr($intr);
            if ($Astate) {
				$_SESSION['intr'] = $Astate['intr'];
				//$url = URL.'/index.php/index/zhuce';
			}else{
				//message('您输入的介绍人不存在！');
			}
            $this->add('intr',$intr);
        }
		$this->add('url',$url);
		$this->display('web/index.html');
	}

	public function N_index(){


		$this->notice(1);
		$this->add('csstype',1);
		$this->parse('header','web/header.html');
		$this->display('web/N_index.html');
	}

	//手机介绍页
    public function wapview(){

        $web_data = $this->Common_model->get_copyright();

        $this->add('sitename',$web_data['web_name']);
        $this->add('wapurl',$web_data['wap_url']);
        $this->display('wapview/wapview.html');
    }
    //体育
    public function sports(){

      $token=$_SESSION["token"];
      $uid=$_SESSION["uid"];
      $this->add('token',$token);
      $this->add('uid',$uid);
      $this->notice(8);
      $this->add('csstype',8);
      $this->parse('header','web/header.html');
      $this->display('web/sports.html');
    }
    //彩票
	public function lottery(){
	    $this->notice(2);
		$this->add('csstype',2);
		$this->parse('header','web/header.html');

	    $this->display('web/lottery.html');
	}
	//视讯
	public function livetop(){

		$this->notice(3);
		$video_config = $this->Index_model->get_livetop();

		$video_imgs = $this->Index_model->get_video_imgs();

		 //初始图片路径替换
        foreach ($video_config as $key => $val) {

        	if (empty($video_imgs[$val])) {
        	    $video_imgs[$val]['img_url'] = '/public/images/img_0'.$val.'.png';
        	}
        	if ($val == 'pt' || $val == 'eg') {
        	    unset($video_config[$key]);
        	}
        }

        $this->add('csstype',3);
		$this->parse('header','web/header.html');
		$this->add('video_config',$video_config);
		$this->add('video_imgs',$video_imgs);

	    $this->display('web/livetop.html');
	}
	/**
	 * 电子游戏
	 */
	public function egame() {
		$type = $this->input->get('type');
		$topid = $this->input->get('top');
		$limit = $this->input->get('lim');
		$gname = $this->input->get('search');

		$jgame = $this->input->get('metype');//电子下拉类型

		$gname = $gname !=='undefined' ? $gname : 0;
		$this->load->model('webcenter/Egame_model');
		$conf['site_id'] = SITEID;
		$conf['index_id'] = INDEX_ID;
		$game_module = $this->Egame_model->get_gameconf($conf);

		$map['type'] = !empty($type) ? strtolower($type) : strtolower($game_module[0]);
		$map['status'] = 1;
		if($topid){
			$map['topid'] = $topid;
		}
		if(empty($limit)){
			$limit = 0;
		}

		$data = $this->Egame_model->get_game($map,$gname,$limit);

		$count = $this->Index_model->rcount('mg_game',$map,3);
		$allpage = ceil($count/60);
		$data[0]['allpage'] = $allpage;
		$data[0]['page'] = $limit;
		$data[0]['top_id'] = $topid;
		$data[0]['typeOf'] = $type;

		if($this->input->is_ajax_request()){
			if(!empty($data)){
				echo $this->Common_model->JSON($data);
				exit;
			}else{
				echo 0;
				exit;
			}
		}
		$this->notice(7);
		$this->add('csstype',7);
		$this->parse('header','web/header.html');
		$this->add('allpage',$allpage);
		$this->add('page',$limit);
		$this->add('gametype',$game_module[0]);
		$this->add('game_module', $game_module);
		if (!$jgame || $jgame == 'm') {
		    $jgame = $game_module[0];
		}

		$this->add("jgame",$jgame);
		$dz_i = array_search($jgame,$game_module);
		$this->add("dz_i",$dz_i);

		$this->parse('egame_html','web/egame_data.html');
		$this->display('web/egame.html');
	}

	//文案信息
	public function iword(){
	    if(empty($_SESSION['ty']) || $_SESSION['ty'] > 8 || $_SESSION['ty'] < 3){
	    	$type = $this->input->get('metype');
	    	$type = empty($type)?1:$type;
	    	$map['table'] = 'info_iword_use';
	  	}elseif($_SESSION['ty'] >= 3 && $_SESSION['ty'] <= 8){      //预览文案
	  		$map['table'] = 'info_iword_edit';
	  		$map['where']['case_state'] = 1;
	  		$type = $_SESSION['ty'];
	  	}
	    $map['where']['type'] = $type;
	    $map['where']['index_id'] = INDEX_ID;
	    $map['where']['site_id'] = SITEID;

	    $this->notice(10);
	    $this->add('csstype',10);
		$this->parse('header','web/header.html');
	    $agent_url = $this->Common_model->get_copyright();

	    $this->add("agent_url",$agent_url['agent_url']);
	    $this->add('iword',$this->Index_model->rfind($map));
		$this->display('web/iword.html');
	}

	//优惠活动
	public function youhui(){
		$this->get_member();
    	$data = $this->Index_model->get_promotions();
    	$this->notice(4);
    	$this->add('csstype',4);
		$this->parse('header','web/header.html');
		foreach($data['data'] as $key=>$value){
			$point = strpos($value['img'],'.');
			$point2 = strpos($value['content'],'.');
			if($point === 0){
			   $img = substr($value['img'],1);
			   $data['data'][$key]['img'] = $img;
			}
			if($point2 === 13){
			   $content = substr_replace($value['content'],"",$point2,1);
			   $data['data'][$key]['content'] = $content;
			}
		}
		$this->add('promotion',$data);
		$this->parse('promotion_html','web/promotions.html');
		$this->add('old_url',OLD_URL);
	    $this->display('web/youhui.html');
	}
	//试玩注册
	public function shiwan(){
		$this->add('old_url',OLD_URL);
		$this->get_member();
		$this->notice();
		$this->parse('header','web/header.html');
	    $this->display('web/zhuce_shiwan.html');
	}
	//会员注册
	public function zhuce(){
		//获取后台用户注册设定
		$map = array();
		$map['table'] = 'k_user_reg_config';
		$map['where']['site_id'] = SITEID;
		$map['where']['index_id'] = INDEX_ID;
		$result = array();
		$result = $this->Index_model->rfind($map);
		if ($result['is_work'] == 0 || empty($result)) {
			echo "<script>alert('系统禁用了用户注册功能,请联系管理员!');window.location.href='/';</script>";exit;
		}
		$this->notice();
		$this->parse('header','web/header.html');
	    $this->display('web/zhuce.html');
	}

	//注册试玩
	public function shiwan_reg(){
		$this->notice();
		$this->parse('header','web/header.html');
	    $this->display('web/zhuce_shiwan.html');
	}


	//代理注册
	public function daili_shenqing(){
		$map = array();
		$map['table'] = 'k_user_agent_config';
		$map['select'] = 'is_daili';
		$map['where']['index_id'] = INDEX_ID;
		$map['where']['site_id'] = SITEID;
		$config = $this->Index_model->rfind($map);
		if (empty($config) || $config['is_daili'] == 0) {
			message('系统关闭了代理注册功能', '/');
		}
		$this->get_member();
		$this->notice();
		$this->parse('header','web/header.html');
	    $this->display('web/zhuce_daili.html');
	}

	//用户信息
	public function get_member(){
        if (!empty($_SESSION['uid'])) {
        	$this->Index_model->login_check($_SESSION['uid']);
        	$map = array();
	        $map['table'] = 'k_user';
	        $map['select'] = 'username,money,ag_money,og_money,mg_money,ct_money,lebo_money,bbin_money';
            $map['where']['uid'] = $_SESSION['uid'];
            $map['where']['site_id'] = SITEID;
            $map['where']['index_id'] = INDEX_ID;

            $data = $this->Index_model->rfind($map);
		    $this->add('uid',$_SESSION['uid']);
		    $this->add('money',$data['money']);
		    $this->add('ogmoney',$data['og_money']);
		    $this->add('ctmoney',$data['ct_money']);
		    $this->add('mgmoney',$data['mg_money']);
		    $this->add('agmoney',$data['ag_money']);
		    $this->add('lebomoney',$data['lebo_money']);
		    $this->add('bbinmoney',$data['bbin_money']);
		    $this->add('ptmoney',$data['pt_money']);
		    $this->add('username',$_SESSION['username']);
		    $this->add('user',$data);
        }
	}

	//左右浮动
	public function float_left(){
		$this->load->model('webcenter/Float_model');
        $data = $this->Float_model->get_allfloat();
        $floatl = $data['floatl'];  //左浮动数据
        $floatr = $data['floatr'];  //右浮动数据

        $fleft = !empty($floatl) ? 1 : 0;
        $fright = !empty($floatr) ? 1 : 0;

		if($fleft == 1){
			foreach($floatl as $key=>$value){
				$point = strpos($value['img_A'],'.');
				$point2 = strpos($value['img_B'],'.');
				if($point === 0){
				   $img_A = substr($value['img_A'],1);
				   $floatl[$key]['img_A'] = $img_A;
				}
				if($point2 === 0){
				   $img_B = substr($value['img_B'],1);
				   $floatl[$key]['img_B'] = $img_B;
				}
			}
		}
		if($fright == 1){
			foreach($floatr as $k=>$v){
				$rpoint = strpos($v['img_A'],'.');
				$rpoint2 = strpos($v['img_B'],'.');
				if($rpoint === 0){
				   $img_A2 = substr($v['img_A'],1);
				   $floatr[$k]['img_A'] = $img_A2;
				}
				if($rpoint2 === 0){
				   $img_B2 = substr($v['img_B'],1);
				   $floatr[$k]['img_B'] = $img_B2;
				}
			}
		}

        $this->add('fleft',$fleft);
		$this->add('fright',$fright);
        $this->add('floatl',$floatl);
		$this->add('floatr',$floatr);
    }

    //跑马灯公告
    public function notice($type=0) {
    	$this->load->model('webcenter/Notice_model');
    	$notice = $this->Notice_model->getNotice($type,1);
    	$notice2 = $this->Notice_model->getNotice($type,2);

    	$this->add('notice',$notice);
    	$this->add('notice2',$notice2);
		$this->parse('notice_html','web/notice.html');
    }

    //点击弹出历史消息
    public function notice_data() {
    	$this->load->model('webcenter/Notice_model');
    	$list = $this->Notice_model->get_notice_data();
    	$this->add('list',$list);
    	$this->display('web/notice_data.html');
    }

    //获取会员未读消息数
    public function notice_count(){
    	$this->get_member();
	 	$this->load->model('webcenter/Notice_model');
    	//$count = $this->Notice_model->get_notice_count();
    	$count = $this->Notice_model->new_notice_count();   //redis获取
    	$this->add('count',$count);

 	}


	 //弹窗
    public function site_pop($type) {
    	$pop = $this->Index_model->get_site_pop($type);
    	$this->add('pop',$pop);
		$this->parse('site_pop','web/site_pop.html');
    }
    //备用网址
    public function detect(){
    	$this->notice();
		$this->parse('header','web/header.html');
    	$this->display('web/detect.html');
    }

    //备用网址内页
    public function about(){
    	$this->display('web/about.html');
    }
}

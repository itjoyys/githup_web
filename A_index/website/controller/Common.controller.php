<?php

class Common extends Controller {
    /*
	 *首页模板
	 */
    function index() {

        global $db_config;
        $View = $this->_header();
        $_GET = array_change_key_case($_GET, CASE_LOWER);
        $intr= @$_GET['intr'];
        if (is_numeric($intr))$View->add("intr",$intr);
        //前台文案预览控制
	    if (!empty($_GET['vt']) && $_GET['vt'] == 'sview' && !empty($_GET['ty']) && !empty($_GET['eid'])) {
	    	$dataS = M('info_case',$db_config)->where("id = '".$_GET['eid']."'")->find();
			if (!empty($dataS) && $dataS['type'] == $_GET['ty']) {
				$sview['ty'] = $_GET['ty'];
			    $sview['id'] = $dataS['eid'];
			    $_SESSION['sview'] = $sview;
			}else{
				unset($_SESSION['sview']);
			}
	    }else{
	    	unset($_SESSION['sview']);
	    }
        $View->show("index");
    }

    function rsa(){
        global $db_config;
        $games=new Games();
        $params=$_POST['param'];
        $key = $_POST['key'];
        $siteid = $_POST['siteid'];
        $username = $_POST['username'];
        $params= preg_replace('/[\s　]/', '+', $params);
        $rkey = $games->getKey($params);
        if($key!=$rkey){
            echo "no";
            exit();
        }
        $params =$games->decrypt($params);
        $result = json_decode(trim($params,"data="));
        $data_u = array();
        if (!empty($result->ogstatus)) {
            $data_u['og_money'] = floatval($result->ogbalance);
        }
        if (!empty($result->agstatus)) {
            $data_u['ag_money'] = floatval($result->agbalance);
        }
        if (!empty($result->mgstatus)) {
            $data_u['mg_money'] = floatval($result->mgbalance);
        }
        if (!empty($result->ctstatus)) {
            $data_u['ct_money'] = floatval($result->ctbalance);
        }
        if (!empty($result->bbinstatus)) {
            $data_u['bbin_money'] = floatval($result->bbinbalance);
        }
        if (!empty($result->lebostatus)) {
            $data_u['lebo_money'] = floatval($result->lebobalance);
        }
        if(!empty($siteid)&&!empty($username)){
       		$ok= M("k_user", $db_config)->where("site_id = '" . $siteid . "' and username = '" . $username . "'")->update($data_u);
        }
        echo "yes";
    }

    function setvideomoney($username){
        $games = new Games();
        $data = $games->SetKUserMoney($username);
    }
	/*
	 *首页
	 */
	function N_index() {
		global $db_config;
		$View = $this->_header(1);
		if (!empty($_SESSION['uid'])) {
			include_once "./include/login_check.php";
            $uid = $_SESSION['uid'];
			$reg = M("k_user", $db_config);
			$data = $reg->field("money")->where("site_id = '" . SITEID . "' and uid = '" . $uid . "'")->find();
			$View->add("money", $data['money']);
		}
		//首页幻灯片
		$mapf['site_id'] = SITEID;
		$mapf['type'] = 13;
		$flash = M('info_flash_use',$db_config)->where($mapf)->find();
		$View->add("flash",$flash);
		$View->show("N_index");
	}

		//获取消息类别
	public function getNotice($type){
			global $db_config, $mysqlt, $_DBC;

		$Mess = M("k_message", $db_config);

		$map = '';
		$map = "site_id = '".SITEID."' and is_delete = 0 and show_type = 2";

		if ($type == '8') {
			$map .= " and (game_type like '%1%' or state = 1)";
		}elseif($type == '3' || $type == '7'){
			$map .= " and (game_type like '%3%' or state = 1)";
		}elseif($type == '2'){

			$map .= " and (game_type like '%2%' or state = 1)";
		}else{
			$map .= " and state = 1";
		}

		$u = M('site_notice',$db_config);
		$map1 = "sid = '0' and notice_state = '1'";

		$notice1 = $u->field('notice_cate,notice_date as add_time,notice_content as chn_simplified')->where($map1)->order('notice_date DESC')->select();
		//数据拼接
		foreach ($notice1 as $key => $val) {
		   switch ($val['notice_cate']) {
		   	case '3'://彩票
		   		$fc[] = $val;
		   		break;
		   	case '4'://体育
		   		$sp[] = $val;
		   		break;
		   	case '5'://视讯
		   		$vd[] = $val;
		   		break;
	   		case '2'://维护公告，全站显示
		   		$wh[] = $val;
		   		break;
		   }
		}

        $notice = '';
        $table = M("k_message", $db_config);
		$notice = $table->where($map)->order("add_time DESC")->limit(8)->select();
		if ($type == '8') {
			if(!empty($sp)){
				$notice = array_merge($sp, $notice);
			}
			if(!empty($wh)){
				$notice = array_merge($wh, $notice);
			}
			$db_config['host'] = $_DBC['public']['host'];
			$db_config['user'] = $_DBC['public']['user'];
			$db_config['pass'] = $_DBC['public']['pwd'];
			$db_config['dbname'] = $_DBC['public']['dbname'];

			$notice3 = M('site_notice',$db_config)->where("(sid = '0' or sid = '".SITEID."') and notice_cate='s_p'")->order("notice_date DESC")->limit("0,30")->select();
			$notice = array_merge($notice3, $notice);

		}elseif($type == '3' || $type == '7'){
			if(!empty($vd)){
				$notice = array_merge($vd, $notice);
			}
			if(!empty($wh)){
				$notice = array_merge($wh, $notice);
			}
		}elseif($type == '2'){
			if(!empty($fc)){
				$notice = array_merge($fc, $notice);
			}
			if(!empty($wh)){
				$notice = array_merge($wh, $notice);
			}
		}else{
			if(!empty($wh)){
				$notice = array_merge($wh, $notice);
			}
		}
		foreach ($notice as $key => $row)
		{
		    $add_time[$key]  = $row['add_time'];
		}
		if (is_array($add_time)) {
			array_multisort($add_time,SORT_DESC,$notice);
		}

		if(!empty($notice)){
			$info = '';
			foreach ($notice as $key => $value) {
				if(isset($value['chn_simplified'])){
					$info .= $value['chn_simplified']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				}elseif (isset($value['notice_content'])) {
					$info .= $value['notice_content']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				}

			}
			return $info;
		}
	}

	function upupFlash() {
		$View = $this->_header();
		$View->show("upupFlash");
	}

	function refresh() {
		$View = $this->_header();
		$View->show("refresh");
	}

    //前台基本文案控制
	function iword(){
		global $db_config;
		$View = $this->_header(10);
		if (empty($_GET['itype'])) {
			exit('system error 0000');
		}
		$iword = self::indexBase('1',$_GET['itype'],$_SESSION['sview']);
		$agent_url = M('web_config',$db_config)->where("site_id = '".SITEID."'")->getField('agent_url');
		$View->add("agent_url",$agent_url);
		$View->add("iword",$iword);
		$View->show("iword");
	}

	//开户协议
	function get_agreement(){
       global $db_config;
       $map['site_id'] = SITEID;
       $map['type'] = 18;
       $data = M('info_reg_use',$db_config)->where($map)->find();
       exit($data['content']);
	}

	  //基本数据加载
	 public function indexBase($id,$type,$sview){
	  	 global $db_config;
	  	 $iword = array();
	  	 $tab = self::get_tab($type);
	  	 $tab_use = str_replace('edit','use',$tab);
		 if (!empty($_SESSION['sview'])) {
			 $iword = M($tab,$db_config)->where("id = '".$_SESSION['sview']['id']."'")->find();
		  }else{
		  	$map_u['site_id'] = SITEID;
		  	$map_u['type'] = $type;
	        $iword = M($tab_use,$db_config)->where($map_u)->find();
		  }
		  return $iword;
	  }

    function get_tab($type){
       if ($type == 1 || $type == 2) {
          return 'info_deposit_edit';
       }elseif($type == 9 || $type == 10){
          return 'info_reg_edit';
       }elseif($type >=3 && $type <= 8){
          return 'info_iword_edit';
       }elseif($type == 11 || $type == 12){
          return 'info_logo_edit';
       }elseif ($type == 13) {
          return 'info_flash_edit';
       }elseif ($type == 14) {
          return 'info_activity_edit';
       }elseif ($type == 15) {
          return 'info_cateimg_edit';
       }elseif ($type == 16 || $type ==17) {
          return 'info_float_c_edit';
       }
   }

	/*
	 *关于我们
	 */
	function about() {
		$View = $this->_header(10);
		$View->show("about");
	}

	/*
	 *线路检测
	 */
	function detect() {
		$View = $this->_header(11);
		$View->show("detect");
	}

	/*
	 *联系我们
	 */
	function contactus() {
		$View = $this->_header(9);
		$View->show("contactus");
	}

	/*
	 *常见问题
	 */
	function changjian() {
		$View = $this->_header();
		$View->show("changjian");
	}
	/*
	 *合作伙伴
	 */
	function cooperation() {
		global $db_config;
		$View = $this->_header();
		//判断前台是否开启代理注册申请
		$aState = M('k_user_agent_config', $db_config)->where("site_id = '" . SITEID . "'")->getField("is_daili");
		$View->add('astate', $aState);
		$View->show("cooperation");
	}
	/*
	 *存款帮助
	 */
	function cunkuan() {
		$View = $this->_header(6);
		$View->show("cunkuan");
	}
	/*
	 *取款帮助
	 */
	function qukuan() {
		$View = $this->_header();
		$View->show("qukuan");
	}
	/*
	 *优惠活动
	 */
	function youhui() {
		$View = $this->_header(4);
		$View->add("cssState", '4');
		$View->show("youhui");
	}
	/*
	 *彩票
	 */
	function lottery() {
		$View = $this->_header(2);
		$View->show("lottery");

	}
    /*
     *视讯
     */
    function livetop() {
        $View = $this->_header(3);
        $View->show("livetop");
    }
    /*
 *SPORTS
 */
    function sports() {
        $View = $this->_header(8);
        $View->show("sports");
    }

	/*
	 *电子
	 */
	function egame() {
		global $db_config, $mysqlt, $_DBC;
		$View = $this->_header(7);

		$db_config['host'] = $_DBC['public']['host'];
		$db_config['user'] = $_DBC['public']['user'];
		$db_config['pass'] = $_DBC['public']['pwd'];
		$db_config['dbname'] = $_DBC['public']['dbname'];
		$category = '';
		$item = "all";
		$langx = "zh";
		$search = "";
		$page = 0;
		$category_arr = array(
			'SLOTS' => '1',
			'TABLE GAMES' => '2',
			'VIDEO POKER' => '3',
			'Others' => '4',
			"all" => '0',
		);
		$item_arr = array(
			'3 Reel Slots' => '11',
			'5 Reel Slots' => '12',
			'Bonus Screen' => '13',
			'Others' => '14',
			'BlackJack' => '21',
			'OtherCasinoGames' => '22',
			'OtherTableGames' => '23',
			'Others' => '24',
			'Poker' => '25',
			'Roulette' => '26',
			'VIDEO POKER' => '31',
			"all" => '0',
		);
		$where = "";
		if (!empty($category)) {
			$topid = $category_arr[$category];
			$where .= "topid ='" . $topid . "'";
		} else {
			$where = " 1=1 ";
		}
		if (!empty($item) && $item != 'all') {
			$itemid = $item_arr[$item];
			$where .= " AND item = '" . $itemid . "'";
		}
		if (!empty($search)) {
			$where .= " AND (gameid like '%" . $search . "%' OR name like '%" . $search . "%')";
		}
		$model = M("mg_game", $db_config);
		$count = $model->where($where)->order("id ASC")->count();
		$pagecount = ceil($count / 15);
		if ($pagecount <= ($page + 1)) {
			$nextpage = $pagecount - 1;
			$page = $pagecount - 1;
		} else {
			$nextpage = $page + 1;
		}
		$games = array();
		if ($count > 0) {
			$startid = $page * 15;
			$games = $model->where($where)->order("id ASC")->limit($startid . ",15")->select();
		}
		//获取维护中的游戏列表
		$hiddengame = M("mg_game", $db_config)->where("status = 2")->select("gameid");
		$hideGameList = "";
		foreach ($hiddengame as $value) {
			$hideGameList .= '"' . $value["gameid"] . '",';
		}

		$View->add("hideGameList", $hideGameList);
		$View->add("category", $category);
		$View->add("item", $item);
		$View->add("langx", $Langx);
		$View->add("search", $search);
		$View->add("page", $page);
		$View->add("nextpage", $nextpage);
		$View->add("pagecount", $pagecount);
		$View->add("games", $games);
		$View->show("egame");
	}

	function egame_item() {
		global $_DBC;
		//ini_set("display_errors", "On");
		//error_reporting(E_ALL);
		$View = $this->_header(7);
		$db_config['host'] = $_DBC['public']['host'];
		$db_config['user'] = $_DBC['public']['user'];
		$db_config['pass'] = $_DBC['public']['pwd'];
		$db_config['dbname'] = $_DBC['public']['dbname'];
		//[Category] => SLOTS [Game_category] => all [Langx] => zh
		//[Category] => SLOTS [Game_category] => all [Langx] => zh [page] => 1
		//父类型id 1 SLOTS，2 TABLE GAMES，3 VIDEO POKER，4 Others
		//子类型id 11 3 Reel Slots，12 5 Reel Slots，13 Bonus Screen ，14 Others
		//21 BlackJack 22 OtherCasinoGames 23 OtherTableGames 24 Others 25 Poker 26 Roulette 31 VIDEO POKER
		$category = trim($_POST['Category']);
		$item = trim($_POST['Game_category']);
		$langx = trim($_POST['Langx']);
		$search = trim($_POST['search']);
		$page = intval($_POST['page']);
		$category_arr = array(
			'SLOTS' => '1',
			'TABLE GAMES' => '2',
			'VIDEO POKER' => '3',
			'Others' => '4',
			"all" => '0',
		);
		$item_arr = array(
			'3 Reel Slots' => '11',
			'5 Reel Slots' => '12',
			'Bonus Screen' => '13',
			'Others' => '14',
			'BlackJack' => '21',
			'OtherCasinoGames' => '22',
			'OtherTableGames' => '23',
			'Others' => '24',
			'Poker' => '25',
			'Roulette' => '26',
			'VIDEO POKER' => '31',
			"all" => '0',
		);
		$where = "";
		if (!empty($category)) {
			$topid = $category_arr[$category];
			$where .= "topid ='" . $topid . "'";
		} else {
			$where = " 1=1 ";
		}
		if (!empty($item) && $item != 'all') {
			$itemid = $item_arr[$item];
			$where .= " AND itemid = '" . $itemid . "'";
		}
		if (!empty($search)) {
			$where .= " AND (gameid like '%" . $search . "%' OR name like '%" . $search . "%')";
		}
		$model = M("mg_game", $db_config);
		$count = $model->where($where)->order("id ASC")->count();
		$pagecount = ceil($count / 15);
		if ($pagecount <= ($page + 1)) {
			$nextpage = $pagecount - 1;
			$page = $pagecount - 1;
		} else {
			$nextpage = $page + 1;
		}
		$games = array();
		if ($count > 0) {
			$startid = $page * 15;
			$games = $model->where($where)->order("id ASC")->limit($startid . ",15")->select();
		}
		//获取维护中的游戏列表
		$hiddengame = M("mg_game", $db_config)->where("status = 2")->select("gameid");
		$hideGameList = "";
		foreach ($hiddengame as $value) {
			$hideGameList .= '"' . $value["gameid"] . '",';
		}

		$View->add("hideGameList", $hideGameList);
		$View->add("category", $category);
		$View->add("item", $item);
		$View->add("langx", $Langx);
		$View->add("search", $search);
		$View->add("page", $page);
		$View->add("nextpage", $nextpage);
		$View->add("pagecount", $pagecount);
		$View->add("games", $games);
		$View->show("egame_ajax");

		/*
	[id] => 4
	[topid] => 2
	[itemid] => 24
	[gameid] => AtlanticCity
	[name] => 亚特兰提斯21点
	[image] => images/BTN_AtlanticCityBlackjack1.png
	[status] => 1
	 */
	}
    //注册内页--固定
	function join_member(){
		global $db_config;
		//pktoken防跨站
		$pk_token = getPKtoken();
	    $_SESSION['PKtoken']= $pk_token;
	    $_SESSION['PKtokenState']= 1;
        //获取后台用户注册设定
		$data = array();
		$reg = M("k_user_reg_config", $db_config);
		$data = $reg->field("*")->where("site_id = '" . SITEID . "' and is_work = '1'")->find();
		if (empty($data)) {
			message('系统禁用了用户注册功能,请联系管理员!');
		}

		//对于后台设置 是否必填等选项的设定
		$list = array();
		if (!empty($data)) {
			foreach ($data as $k => $v) {
				$class = explode('-', $v);
				if (isset($class) && $class[1] != '') {
					if ($class[0] == 0) {
						$list[$k]['name'] = $k; //email
						if ($k == 'email') {
							$list[$k]['name_zh'] = '邮箱';
							$list[$k]['info'] = '请填写您的真实邮箱';
						} elseif ($k == 'qq') {
							$list[$k]['name_zh'] = 'QQ';
							$list[$k]['info'] = '请填写您的真实QQ';
						} elseif ($k == 'passport') {
							$list[$k]['name_zh'] = '身份证';
							$list[$k]['info'] = '健康博彩，未成年人请勿参与';
						} elseif ($k == 'mobile') {
							$list[$k]['name_zh'] = '手机号码';
							$list[$k]['info'] = '此为您取回登入密码的唯一途径，请注意安全，务必真实！';
						}
						if ($class[1] == 1) {
							$list[$k]['status'] = '*'; //必填
						}
					}
				}
			}
		}
		$View = $this->_header();
		//文案信息
		$map_d['site_id'] = SITEID;
		$map_d['type'] = 9;
		$data = M('info_reg_use',$db_config)->where($map_d)->find();
        $View->add("data", $data);
		$View->add("pk_token",$pk_token);
		$View->add("list", $list);
		$View->show("join_member");
	}
    //试玩内页--固定
	function join_shiwan(){
		global $db_config;
		$pk_token = getPKtoken();
	    $_SESSION['PKtoken'] = $pk_token;
	    $_SESSION['PKtokenState']= 1;
		$View = $this->_header();
		$user = M('k_user', $db_config);
		$where = "username like 'TEST%' and site_id='" . SITEID . "'";
		$rs = $user->field("username")->where($where)->order("uid desc")->limit(1)->find();
		$username = 'TEST' . rand(10, 99) . (substr($rs['username'], 6) + 1);
		//文案信息
		$map_d['site_id'] = SITEID;
		$map_d['type'] = 9;
		$data = M('info_reg_use',$db_config)->where($map_d)->find();
        $View->add("data", $data);
		$View->add("username",$username);
		$View->add("pk_token",$pk_token);
		$View->show("join_shiwan");
	}
	//代理注册内页--固定
	function join_agent(){
		global $db_config;
		//获取代理申请配置
		$config = M("k_user_agent_config", $db_config)->field("is_email,is_qq,is_zh_name,is_en_name,is_card,is_phone,is_payname,from_url_form,other_method_form,is_daili")->where("site_id = '".SITEID."'")->find();
		if (empty($config) || $config['is_daili'] == 0) {
			message('系统关闭了代理注册功能', './index.php');
		}
        unset($config['is_daili']);
		//对于后台设置 是否必填等选项的设定
		$clist = array();
		foreach ($config as $k => $v) {
	        $is_on = explode('-',$v);
	        if ($is_on[0] == '0') {
	            $clist[$k]['name'] = $k;
	            switch ($k) {
	            	case 'is_email':
	            		$clist[$k]['name_zh'] = '邮箱';
						$clist[$k]['cue'] = '请输入邮箱!';
	            		break;
	            	case 'is_qq':
	            		$clist[$k]['name_zh'] = 'qq';
						$clist[$k]['cue'] = '请输入qq!';
	            		break;
	            	case 'is_zh_name':
	            		$clist[$k]['name_zh'] = '中文昵稱';
						$clist[$k]['cue'] = '请输入中文昵稱!';
	            		break;
	            	case 'is_en_name':
	            		$clist[$k]['name_zh'] = '英文昵稱';
						$clist[$k]['cue'] = '请输入英文昵稱!';
	            		break;
	            	case 'is_card':
	            		$clist[$k]['name_zh'] = '身份證';
						$clist[$k]['cue'] = '请输入身份證號!';
	            		break;
	            	case 'is_phone':
	            		$clist[$k]['name_zh'] = '手机号码';
						$clist[$k]['cue'] = '此为您取回登入密码的唯一途径,请注意安全,务必真实!';
	            		break;
	            	case 'is_payname':
	            		$clist[$k]['name_zh'] = '真實姓名';
						$clist[$k]['cue'] = '必须与您的银行帐户名称相同，否则不能出款!!';
	            		break;
	            	case 'from_url_form':
	            		$clist[$k]['name_zh'] = '推广网址';
						$clist[$k]['read'] = '输入您的网址全地址不包含http://，仅填写您的网址即可。';
	            		break;
	            	case 'other_method_form':
	            		$clist[$k]['name_zh'] = '其他方式';
						$clist[$k]['read'] = '* 若您有其他的推广平台，可以在此输入可文字描述！';
	            		break;

	            }
	            if ($is_on[1] == 1) {
					$clist[$k]['status'] = '*'; //必填
				} else {
					$clist[$k]['status'] = ''; //必填
				}
	        }

		}
        //文案信息
		$map_d['site_id'] = SITEID;
		$map_d['type'] = 10;
		$data = M('info_reg_use',$db_config)->where($map_d)->find();

		$View = $this->_header(5);
		$View->add("data", $data);
		$View->add("config", $clist);
		$View->show("join_agent");

	}

	 //代理注册ajax检测
	function agent_ajax_check(){
		global $db_config;
		if ($_GET['ajax'] == 'Check_agent_user') {
			$log_1 = M('k_user_agent', $db_config)->where('agent_user = "' . $_GET['user'] . '" or agent_login_user = "' . $_GET['user'] . '"')->find();
			$log_2 = M('sys_admin', $db_config)->where('login_name_1 = "' . $_GET['user'] . '"')->find();
			if ($log_1 || $log_2) {
				exit(json_encode(0));
			}else{
				exit(json_encode(1));
			}
	     }
	}

	/*
	 *注册
	 */
	function zhuce() {
		global $db_config;
		//获取默认代理商
		$deAgent = $this->getAgent();
		$_SESSION['intr'] = $deAgent['intr'];
		//获取后台用户注册设定
		$data = array();
		$reg = M("k_user_reg_config", $db_config);
		$data = $reg->field("*")->where("site_id = '" . SITEID . "' and is_work = '1'")->find();
		if (empty($data)) {
			message('系统禁用了用户注册功能,请联系管理员!');
		}

		//pktoken防跨站
		$pk_token = getPKtoken();
	    $_SESSION['PKtoken']= $pk_token;
	    $_SESSION['PKtokenState']= 1;

		//对于后台设置 是否必填等选项的设定
		$list = array();
		if (!empty($data)) {
			foreach ($data as $k => $v) {
				$class = explode('-', $v);
				if (isset($class) && $class[1] != '') {
					if ($class[0] == 0) {
						$list[$k]['name'] = $k; //email
						if ($k == 'email') {
							$list[$k]['name_zh'] = '邮箱';
							$list[$k]['info'] = '请填写您的真实邮箱';
						} elseif ($k == 'qq') {
							$list[$k]['name_zh'] = 'QQ';
							$list[$k]['info'] = '请填写您的真实QQ';
						} elseif ($k == 'passport') {
							$list[$k]['name_zh'] = '身份证';
							$list[$k]['info'] = '健康博彩，未成年人请勿参与';
						} elseif ($k == 'mobile') {
							$list[$k]['name_zh'] = '手机号码';
							$list[$k]['info'] = '此为您取回登入密码的唯一途径，请注意安全，务必真实！';
						}
						if ($class[1] == 1) {
							$list[$k]['status'] = '*'; //必填
						}
					}
				}
			}
		}
		$View = $this->_header();
		$View->add("html", $html);
		$View->add("pk_token",$pk_token);
		$View->add("list", $list);
		$View->add("copy_right", $this->copyright());
		$hastype = empty($_GET['type']) ? false : true;
		$View->show("zhuce");
	}

	//会员注册处理

	function user_reg_do() {
		global $db_config;
		if ($_POST) {
			include_once "./include/filter.php";
			include_once(dirname(__FILE__)."/../../ip.php");
			$logintime = date("Y-m-d H:i:s");
			$zcname = $_POST["zcname"];
			$password = md5(md5($_POST["zcpwd1"]));
			$zcturename = $_POST["zcturename"];
			$qkpwd = $_POST["address1"] . $_POST["address2"] . $_POST["address3"] . $_POST["address4"];
			$birthday = $_POST['birthday1'] . '-' . $_POST['birthday2'] . '-' . $_POST['birthday3'];
			if (!$zcname || !$password || !$zcturename || !$qkpwd) {
				message('请完善表单!');
			}
			if (empty($_POST['zcyzm']) || $_POST["zcyzm"] != $_SESSION["randcode"]) {
				message('您输入的验证码有误!');
			}

			$user = M('k_user', $db_config);
			$is_have = $user->field('username')->where("username='" . $zcname . "' and site_id ='" . SITEID . "'")->find();
			if(!empty($is_have['username'])){
				message('您输入的账号已被他人使用！');
			}
			if (!empty($_POST['email'])) {
				if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $_POST['email'])) {
					message('您输入邮箱不正确，请重新输入邮箱!');
				} else {
					$email = $_POST['email'];
				}
			}

			$passport = $_POST['passport'];

			if (!empty($_POST['qq'])) {
				if (!preg_match("/^[1-9][0-9]{4,9}$/", $_POST['qq'])) {
					message('您输入QQ号不正确，请重新输入QQ号!');
				} else {
					$qq = $_POST['qq'];
				}
			}
			$mobile = $_POST['mobile'];
			$_SESSION["randcode"] = rand(1000, 9999); //更换一下验证码

			//会员所属代理商
			$deAgent = $this->getAgent();
			$agent_id = $deAgent['id'];
            $userIP = $this->get_client_ip();
			$address = ipsetarea($userIP); //获取客户端IP所在的国
			//判断IP是否重复注册
			$map_ip = array();
			$map_ip['site_id'] = SITEID;
			$map_ip['reg_ip'] = $userIP;
			$isn_ip = M('k_user',$db_config)->where($map_ip)->find();
			//注册优惠
			$reg_state = $this->reg_dis();  
			
			//注册默认层级
			$level_id = $this->getUserlevel();
			$Buser = M('k_user',$db_config);
			$Buser->begin();

			$dataa = array();
			$dataa['username']    = $zcname;
			$dataa['password']    = $password;
			if (!$isn_ip) {
			    $dataa['money'] = $reg_state['money'];   
			}else{
				$dataa['money'] = 0;
			}
			
			$dataa['qk_pwd']      = $qkpwd;
			$dataa['mobile']      = $mobile;
			$dataa['email']       = $email;
            //判断是否多站点
            if (defined('INDEX_ID')) {
			   $dataa['index_id'] = INDEX_ID;
			}
			$dataa['reg_ip']      = $userIP;
			$dataa['login_ip']    = $userIP;
			$dataa['login_time']  = $logintime;
			$dataa['pay_name']    = $zcturename;
			$dataa['lognum']      = 1;
			$dataa['reg_address'] = $address;
			$dataa['qq']          = $qq;
			$dataa['site_id']     = SITEID;
			$dataa['agent_id']    = $agent_id;
			$dataa['level_id']    = $level_id;
			$dataa['passport']    = $passport;
			$dataa['birthday']    = $birthday;

			try {
				$log_1 = $Buser ->add($dataa);

				$id = $log_1;

				if ($reg_state['money'] > 0 && !$isn_ip) {
					//发送会员消息
					$dataM              = array();
					$dataM['type']      = 2;
					$dataM['site_id']   = SITEID;
					$dataM['uid']       = $id;
					$dataM['level']     = 2;
					$dataM['msg_title'] = $zcname . ',' . "會員注册贈送優惠";
					$dataM['msg_info']  = $zcname . ',' . "會員注册贈送優惠" . $reg_state['money'] . "元 祝您游戏愉快！";
					$log_2 = $Buser -> setTable('k_user_msg')
					         -> add($dataM);

					//写入流水记录
					$dataR                 = array();
					$dataR['uid']          = $id;
					$dataR['username']     = $zcname;
					$dataR['agent_id']     = $agent_id;
					$dataR['site_id']      = SITEID;
					$dataR['cash_balance'] = $reg_state['money']; // 用户当前余额;
					$dataR['cash_date']    = date('Y-m-d H:i:s');
					$dataR['cash_type']    = 6;
					$dataR['cash_only']    = 1;
					$dataR['cash_do_type'] = 1;
					$dataR['discount_num'] = $reg_state['money']; // 金额
					$dataR['remark'] = $zcname . ',' . "會員注册贈送優惠" . $reg_state['money'] . "元";
					$log_3 = $Buser -> setTable('k_user_cash_record')
							 ->add($dataR);

					//写入稽核记录
					if ($reg_state['d_bet'] > 0) {
						$datae                  = array();
						$datae['username']      = $zcname;
						$datae['site_id']       = SITEID;
						$datae['uid']           = $id;
						$datae['source_type']   = 5; //注册优惠
						$datae['begin_date']    = date('Y-m-d H:i:s');
						$datae['type']          = 1;
						$datae['is_zh']         = 1; //有综合稽核
						$datae['is_ct']         = 0;//无常态
						$datae['catm_give']     = $reg_state['money']; //存款优惠
						$datae['type_code_all'] = $reg_state['d_bet'] * $reg_state['money']; //综合稽核打码
						$log_4 = $Buser -> setTable('k_user_audit')
							 ->add($datae);
					}
				}

				$mtime = time();
				$dataf               = array();
				$dataf['ssid']   = session_id();
				$dataf['uid']        = $id;
				$dataf['login_time'] = date("Y-m-d H:i:s");
				$dataf['is_login']   = 1;
				$dataf['www']        = $_SERVER['HTTP_HOST'];
				$dataf['site_id']    = SITEID;
				$dataf['ip']  = $address . $userIP;
				$log_5 = $Buser -> setTable('k_user_login')
						->add($dataf);

				$datag                = array();
				$datag['uid']         = $id;
				$datag['username']    = $zcname;
				$datag['ip']          = $userIP;
				$datag['level_id'] = $level_id;
				$datag['state']     = 1;
				$datag['ip_address']  = $address;
				$datag['login_time']  = date("Y-m-d H:i:s");
				$datag['www']         = $_SERVER['HTTP_HOST'];
				$datag['site_id']     = SITEID;
				$log_6 = $Buser -> setTable('history_login')
						->add($datag);

				if ($log_1 && $log_5 && $log_6) {
					$Buser->commit(); //事务提交
					$_SESSION["uid"] = $id;
					$_SESSION["username"] = $zcname;
					$_SESSION['agent_id'] = $agent_id;
					$_SESSION['level_id'] = $level_id;
					$_SESSION['ssid'] = session_id();
					$this->redis_update_user();
					message("欢迎您的加入!", "./index.php?a=login_info&username=".$zcname."&password=".$_POST["zcpwd1"]);
				} else {
					$Buser->rollback(); //数据回滚
					message("由于网络堵塞，本次注册失败。\\n请您稍候再试，或联系在线客服。");
				}
			} catch (Exception $e) {
				$Buser->rollback(); //数据回滚
				message("由于网络堵塞，本次注册失败。\\n请您稍候再试，或联系在线客服。");
			}

		}
	}

	//会员注册ajax检测
	function checkUserajax() {
		global $db_config;
		if ($_GET['ajax'] == 'CheckDBuser') {
			$user = M('k_user', $db_config);
			$is_have = $user->where("username='" . $_GET['user'] . "' and site_id ='".SITEID."'")->find();
			if ($is_have) {
				echo false;
				exit();
			} else {
				echo true;
				exit();
			}
		}elseif ($_GET['ajax'] == 'CheckCode') {
			//验证码检测
			if ($_SESSION['randcode'] != $_GET['a_idcode']) {
				echo false;
				exit();
			} else {
				echo true;
				exit();
			}
		}
	}

	//会员注册ajax检测验证码
	function checkCodeajax() {
		global $db_config;
		if ($_GET['ajax'] == 'checkCode') {
			if ($_GET["code"] != $_SESSION["randcode"]) {
				echo false;
				exit();
			}else{
				echo true;
				exit();
			}
		}
	}

	//登录验证
	function logincheck() {
		global $db_config;
		if ($_POST["action"] == "login") {
			if ($_SESSION['randcode'] . "" != $_POST["vlcodes"] . "") //判断验证码是否正确
			{
				exit(json_encode(array('error' => '1')));
			}
			//返回值 1 验证码2账号3密码
			$_SESSION["randcode"] = rand(1000, 9999);
			$map['username'] = $_POST['username'];
			$map['site_id'] = SITEID;
			  //判断是否多站点
            if (defined('INDEX_ID')) {
			   $map['index_id'] = INDEX_ID;
			}
			$map['is_delete'] = array('in','(0,2)');
			$password = md5(md5($_POST['password']));
			$uid = M('k_user', $db_config)->field("is_delete,password")
			     ->where($map)->find();
			if (empty($uid)) {
				//账号不存在
			    exit(json_encode(array('error' => '2')));
			} else {
				//账号存在
				if ($uid['password'] !== $password) {
					//密码错误
					exit(json_encode(array('error'=>'3')));
				}else{
					if ($uid['is_delete'] == 2) {
					    exit(json_encode(array('error' => '5'))); //停用
					} else {
						exit(json_encode(array('error' => '4'))); //返回值 4 表示成
					}
				}

			}
		}
	}

	function logout() {
		global $db_config;
		$this->setvideomoney($_SESSION["username"]);
        //更新会员登录
		$dataUL = array();
		$dataUL['is_login'] = 0;
		$log_ul = M('k_user_login',$db_config)
		        ->where("uid = '".$_SESSION['uid']."'")
		        ->update($dataUL);
		$this->redis_del_user();
		session_destroy();
		echo "<script>window.open('/','_top')</script>";

	}

	function login_info() {
		global $db_config;
		header("Content-type: text/html; charset=utf-8");
		//查询公告配置，匹配用户
		$data_notice = M("k_message", $db_config)->where("  site_id ='".SITEID."' and is_delete = '0' and show_type = '1' ")->order("add_time DESC")->select();

		$uid = M('k_user', $db_config)->field("level_id")->where("username = '".$_GET['username']."'")->find();


		//获取版权信息
		$copy_right = $this->copyright();
		$View = new View();
		$data_mes = '';
		//判断用户是否符合要求弹出广告
		if (!empty($data_notice)) {
			foreach ($data_notice as $k => $v) {
				if(!empty($v) && $v != 'undefined'){

					$v['chn_simplified'] = htmlspecialchars_decode($v['chn_simplified']);
			    $v['chn_simplified'] = preg_replace("/<br \s*\/?\/>/i", "\n", $v['chn_simplified']);
			    $v['chn_simplified'] = str_replace("&lt;br /&gt;", "", $v['chn_simplified']);
			    $v['chn_simplified'] = str_replace("&amp;lt;br /&amp;gt;", "", $v['chn_simplified']);
			    $level = explode(",", $v['level_power']);	//层级要求
					$zduser = explode(",", $v['zduser']);	//指定用户名

					if(in_array("-1", $level) && in_array($_GET['username'], $zduser)){ //当用户符合 指定用户时弹窗
						$data_mes =	$data_mes.$v['chn_simplified'].'|';
					}elseif ( in_array($uid['level_id'], $level)){		//当用户 层级 符合时弹窗
						$data_mes =	$data_mes.$v['chn_simplified'].'|';
					}elseif (in_array("-2", $level)){	//全部用户弹窗
						$data_mes =	$data_mes.$v['chn_simplified'].'|';
					}
				}
			}
			$View->add("data_notice", $data_mes);
    }

		$View->add('copy_right', $copy_right);
		$View->add('username', $_GET['username']);
		$View->add('password', $_GET['password']);
		$View->show("login_info");
	}

	function login_info_do(){
		global $db_config;
		include_once "./include/filter.php";
		include_once(dirname(__FILE__)."/../../class/user.php");
		include_once(dirname(__FILE__)."/../../ip.php");
		$userIP = $this->get_client_ip();
		$address = ipsetarea($userIP); //获取客户端IP所在的国
		$address = $address . '(' . $userIP . ')';
		if ($_POST['loginTO'] == 1) {
			//我同意
			$passwd = md5(md5($_POST["password"]));
			$uid = user::login($_POST["username"],$passwd, $_SERVER['HTTP_HOST'], $userIP,$address);

			if ($uid > 0) {
			    $this->setvideomoney($_POST["username"]);
                $this->redis_update_user();
				echo "<script>window.location.href='./'</script>";
			}else{
				// print_r($uid);
				die();
			}
		} elseif ($_POST['loginTO'] == 2) {
			//我不同意
			echo "<script>window.location.href='./'</script>";
		}
	}

	//试玩注册
	function shiwan_reg() {
		global $db_config, $mysqlt;
        if (!empty($_SESSION['uid'])) {
        	message('登陆用户不能注册试玩账号!');
        	exit();
        }
		if ($_POST && !empty($_POST['zcpwd1'])) {
			include_once "./include/filter.php";
			$username = $_POST["zcname"];
			$password = md5(md5($_POST["zcpwd1"]));
			$password2 = md5(md5($_POST["zcpwd2"]));

			$logintime = date("Y-m-d H:i:s");
			if ($password != $password2) {
				message('两次输入密码不一致!');exit;
			}
			if (!$password) {
				message('请填写完整表单!');exit;
			}

			//获取测试代理商id
			$agent_demo = M('k_user_agent', $db_config)->where("agent_type = 'a_t' and is_demo = 1 and site_id = '".SITEID."'")->getField('id');

			$data_s['username'] = $username;
			$data_s['password'] = $password;
			$data_s['money'] = 1000; //试玩赠送金额
			$data_s['site_id'] = SITEID;
			$data_s['agent_id'] = $agent_demo;
			$data_s['shiwan'] = 1;

			$shi_S = M('k_user', $db_config)->add($data_s);
			$web_site = M('web_config', $db_config)->where("site_id = '" . SITEID . "'")->find();
			if ($shi_S) {
				$_SESSION["shiwan"] = 1;
				$_SESSION["uid"] = $shi_S;
				$_SESSION["username"] = $username;
                $_SESSION['agent_id'] = $agent_demo;
				// user::msg_add($_SESSION["uid"],$web_site['reg_msg_from'],$web_site['reg_msg_title'],$web_site['reg_msg_msg']);
				header("Content-type: text/html; charset=utf-8");
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
				echo "<script>alert(\"恭喜您，注册成功。\");top.location.href='./index.php';</script>";
				exit();
			} else {

				message("由于网络堵塞，本次注册失败。\\n请您稍候再试，或联系在线客服。");
			}
		} else {
			//pktoken防跨站
			$pk_token = getPKtoken();
		    $_SESSION['PKtoken']= $pk_token;
		    $_SESSION['PKtokenState']= 1;
			$View = $this->_header();
			$user = M('k_user', $db_config);
			$where = "username like 'TEST%' and site_id='" . SITEID . "'";
			$rs = $user->field("username")->where($where)->order("uid desc")->limit(1)->find();
			$username = 'TEST' . rand(10, 99) . (substr($rs['username'], 6) + 1);

			$View->add("copy_right", $this->copyright());
			$View->add('username', $username);
			$View->add("pk_token",$pk_token);
			$View->show("zhuce_shiwan");
		}
	}

	/*
	 *代理注册
	 */
	function daili_shenqing() {

		global $db_config, $mysqlt;
		include_once(dirname(__FILE__)."/../../class/user.php");

		//获取代理申请配置
		$config = M("k_user_agent_config", $db_config)->where("site_id = '".SITEID."'")->find();
		if ($config['is_daili'] == 0 && is_array($config)) {
			message('系统关闭了代理注册功能', '/index.php');
		}

		//对于后台设置 是否必填等选项的设定
		$list = array();
		foreach ($config as $k => $v) {
			$class = explode('-', $v);
			if ($class[1] != '') {
				if ($class[0] == 0) {
					$list[$k]['name'] = $k; //email
					if ($k == 'is_email') {
						$list[$k]['name_zh'] = '邮箱';
						$list[$k]['cue'] = '请输入邮箱!';
					} elseif ($k == 'is_qq') {
						$list[$k]['name_zh'] = 'qq';
						$list[$k]['cue'] = '请输入qq!';
					} elseif ($k == 'is_zh_name') {
						$list[$k]['name_zh'] = '中文昵稱';
						$list[$k]['cue'] = '请输入中文昵稱!';
					} elseif ($k == 'is_en_name') {
						$list[$k]['name_zh'] = '英文昵稱';
						$list[$k]['cue'] = '请输入英文昵稱!';
					} elseif ($k == 'is_card') {
						$list[$k]['name_zh'] = '身份證';
						$list[$k]['cue'] = '请输入身份證號!';
					} elseif ($k == 'is_phone') {
						$list[$k]['name_zh'] = '手机号码';
						$list[$k]['cue'] = '此为您取回登入密码的唯一途径，请注意安全，务必真实!';
					} elseif ($k == 'is_payname') {
						$list[$k]['name_zh'] = '真實姓名';
						$list[$k]['cue'] = '必须与您的银行帐户名称相同，否则不能出款!!';
					}
					if ($class[1] == 1) {
						$list[$k]['status'] = '*'; //必填
					} else {
						$list[$k]['status'] = ''; //必填
					}
				}
			}
		}

		//检验登陆用户是否已有代理
		if ($_SESSION["uid"]) {
			if (user::is_daili($_SESSION["uid"])) {
				echo "<script>alert('你已经是" . $web_site['web_name'] . "的代理\\n不需重复代理');location.href='index.php';</script>";
				exit();
			}
		}

		//检验用户是否重复提交代理申请
		// $query = M("k_user_agent_apply", $db_config)->where("ip='" . $_SERVER["REMOTE_ADDR"] . "' and add_time>='" . date("Y-m-d") . " 00:00:00' and add_time<='" . date("Y-m-d") . " 23:59:59'")->select();
		// if ($query) {
		// 	message('代理每天只能申请一次，您今天已经提交申请了，请等待客服人员联系和确认。', '/index.php');
		// }


		$View = $this->_header(5);
		$View->add("config", $list);
		$View->add("AGENT_PRE", AGENT_PRE);
		$View->add("copy_right", $this->copyright());
		$View->show("zhuce_daili");
	}

	function daili_shenqing_check(){
		global $db_config, $mysqlt;
		/*ajax验证用户名是否存在*/
		if ($_GET['ajax'] == 'Check_agent_user') {
			$is_have2 = M('k_user_agent', $db_config)->where('agent_user = "' . $_GET['user'] . '" or agent_login_user = "' . $_GET['user'] . '"')->find();
			$is_have3 = M('sys_admin', $db_config)->where('login_name_1 = "' . $_GET['user'] . '"')->find();
			if ($is_have2 || $is_have3) {
				return ture;

			}else{
				echo "false";
			}
			exit;
		}

		/*ajax验证真实姓名是否重复*/
		if ($_GET['ajax'] == 'check_r_name') {
			$r_mame = M('k_user_agent', $db_config)->where('realname = "' . $_GET['r_name'] . '"')->find();
			if ($r_mame) {
				return ture;

			}else{
				echo "false";
			}
			exit;
		}

		/*ajax验证银行卡是否重复*/
		if ($_GET['ajax'] == 'checkcard') {
			$result = M('k_user_agent',$db_config)->field('id')->where(" bankno= '$_GET[bank_account_form]' and site_id = '".SITEID."'")->find();
			    if($result['id']){
			    	return ture;
					}else{
						echo "false";
					}
					exit;
		}
		//检验是否提交表单
		if ($_POST) {
			//检验验证码是否正确
			if ($_POST['yzm_form'] == $_SESSION["randcode"]) {
				$_SESSION["randcode"] = rand(10000, 99999); //更换一下验证码

				//完善表单  注册帐号 - 新增代理部分
				if ($_POST['r_user_form'] == '' || $_POST['password_form'] == '' || $_POST['passwd_form'] == '' || $_POST['yzm_form'] == '') {
					message("请完善注册帐号 - 新增代理部分");
				}

				$result = M('k_user_agent',$db_config)->field('id')->where(" bankno= '$_POST[bank_account_form]' and site_id = '".SITEID."'")->find();
			    if($result['id']){
			        message('该银行卡已经绑定到代理账号！');
			    }
			  //获取代理申请配置
				$config = M("k_user_agent_config", $db_config)->where("site_id = '".SITEID."'")->find();
				//代理基本数据部分
				if ($config['is_payname'] == '1-1') {
				//后台设置是否需要验证
					if ($_POST['is_payname'] == '') {
						message("请填写真实姓名");
					}
				}

				if ($config['is_phone'] == '1-1') {
					if ($_POST['is_phone'] == '') {
						message("请填写手机号码");
					}
				}
				if ($config['is_qq'] == '1-1') {
					if ($_POST['is_qq'] == '') {
						message("请填写QQ");
					}
				}
				if ($config['is_email'] == '1-1') {
					if ($_POST['is_email'] == '') {
						message("请填写邮箱");
					}
				}
				//完善表单   代理银行资料部分
				if ($_POST['safe_pass1'] == '' || $_POST['safe_pass2'] == '' || $_POST['safe_pass3'] == '' || $_POST['safe_pass4'] == '') {
					message("请完善注册帐号 - 新增代理部分");
				}
				$map = array();
				$map['personalid'] = strtolower($_POST['is_card']);
				$map['mobile'] = $_POST['is_phone'];
				//$map['zh_name'] = $_POST['is_zh_name'];
				$map['en_name'] = $_POST['is_en_name'];
				$map['realname'] = $_POST['is_payname'];
				$map['grounds'] = $_POST['grounds']; //申请理由
				$map['uid'] = !empty($_SESSION["uid"]) ? $_SESSION["uid"] : '0';
				$map['agent_login_user'] = addslashes($_POST['r_user_form']); //用户名
				$map['agent_pwd'] = md5(md5($_POST['password_form'])); //密码
				$map['is_apply'] = 1;
				$map['agent_name'] = addslashes($_POST['is_zh_name']); //用户昵称
				$map['from_url'] = $_POST['from_url_form']; //推广网址
				$map['do_url'] = $_SERVER["HTTP_HOST"]; //提交网址
				$map['site_id'] = SITEID;
				$map['agent_company'] = COMPANY_NAME;
				$map['other_method'] = addslashes($_POST['other_method_form']); //其他方式
				$map['bankid'] = $_POST['bank_type_form']; //银行
				$map['bankno'] = addslashes($_POST['bank_account_form']); //银行账号
				$map['qq'] = $_POST['is_qq'];
				$map['email'] = $_POST['is_email'];
				$map['province'] = $_POST['bank_province_form'];
				$map['city'] = $_POST['bank_county_form'];
				$map['safe_pass'] = $_POST['safe_pass1'] . $_POST['safe_pass2'] . $_POST['safe_pass3'] . $_POST['safe_pass4']; //安全密码


				$map['add_date'] = date('Y-m-d H:i:s', time());
				$map['is_delete'] = 5;
				$map['ip'] = $this->get_client_ip();

				//检验帐号是否已存在
				$apply = M('k_user_agent', $db_config);
				$is_have = $apply->where('agent_login_user = "' . $map['agent_login_user'] . '"')->find();
				if ($is_have) {
					message("帐号已存在！");
				}
				//var_dump($map);exit;
				//添加代理数据
				$rows = $apply->add($map);
				if ($rows) {

					echo "<script>alert(\"您的申请已经提交，请等待客服人员联系和确认。\");top.location.href='./';</script>";
					/* message('您的申请已经提交，请等待客服人员联系和确认。', "top.location.href='./'"); */
				} else {
					message('保存失败');
				}

			} else {
				message('验证码错误');
			}
		}

	}

	private function _header($v = 0) {
		global $db_config;
        if (!empty($_SESSION['uid'])) {
        	$uid = $_SESSION['uid'];
        	$login1 = M("k_user", $db_config)->field("is_delete")->where("uid = '" . $_SESSION['uid'] . "'")->find(); //查是否暂停
			if ($login1['is_delete'] == 2) {
				$_SESSION['is_delete'] = 2;
			} elseif ($login1['is_delete'] == 0) {
				$_SESSION['is_delete'] = 0;
			}

			$login = M("k_user_login", $db_config)->field("is_login")->where("uid = '" . $_SESSION['uid'] . "'")->find(); //查是否删除

			if ($login['is_login'] == 0 && !empty($_SESSION['is_delete'])) {
				echo "<script>alert(\"对不起，您的账号异常已被停止，请与在线客服联系！\");</script>";
				$this->redis_del_user();
				session_destroy();
			}else{
				// $this->redis_update_user();

			}

        }

		//尾部版权
		$copy_right = M('web_config',$db_config)->where("site_id = '".SITEID."'")->getField('copy_right');
		//logo
		$map_lo = array();
		$map_lo['site_id'] = SITEID;
		$map_lo['type'] = 11;
		$map_lo['state'] = 1;
		$logo = M('info_logo_use',$db_config)->where($map_lo)->find();
		$View = new View();
        if (!empty($logo)) {
            $View->add("logo", $logo);
        }
		if (!empty($uid)) {
			$reg = M("k_user", $db_config);
			$data = $reg->field("money,ag_money,og_money,mg_money,ct_money,bbin_money,lebo_money")->where("site_id = '" . SITEID . "' and uid = '" . $uid . "'")->find();
			$View->add("money", $data['money']);
			$View->add("agmoney", $data['ag_money']);
			$View->add("mgmoney", $data['mg_money']);
			$View->add("ogmoney", $data['og_money']);
			$View->add("ctmoney", $data['ct_money']);
			$View->add("lebomoney", $data['lebo_money']);
			$View->add("bbinmoney", $data['bbin_money']);
			$View->add("username", $_SESSION['username']);
			$View->add("uid", $uid);
		}


		$View->add("title", $this->getTitle());
		$time_md = date('Y') . '/' . date('m') . '/' . date('d') . ' ' . date('H') . ':' . date('i') . ':' . date('s');

		if ($v > 0) {
			$View->add("csstype", $v);
		}
		$View->add("meau_foot", self::meau_foot());//尾部导航
		$View->add("con_data",self::contact());//尾部联系资料
		$View->add("pop",self::site_pop());//弹窗控制
		$View->parse("site_pop","site_pop.html");
		$View->add("timemd", $time_md);
		$View->add("copy_right", $copy_right);
		$View->add("count", $this->notice_count());
		$View->add("notice", $this->getNotice($v));
		$View->parse("header", "header.html");
		$View->parse("notice_html", "notice.html");
		$View->parse("bottom", "bottom.html");
		return $View;
	}

		//网页底部导航
	public function meau_foot(){
		global $db_config;
		$meau_foot = array();
		$map_mf = array();
		$map_mf['site_id'] = SITEID;
		$map_mf['state'] = 1;
		$meau_foot = M('info_iword_use',$db_config)
		           ->where($map_mf)->order("sort desc")->select();
		if ($meau_foot) {
		    $i = count($meau_foot);
			foreach ($meau_foot as $k => $v) {
				if ($i > ($k+1)) {
					$meau_foot[$k]['str_m'] = '|';
				}
			}
		}
		return $meau_foot;
	}

    //网页底部联系文案
	public function contact(){
       global $db_config;
       $con_data = array();
       $con_data = M('web_config',$db_config)->where("site_id = '".SITEID."'")->find();
       if (!empty($con_data)) {
       	   if ($con_data['tel']) {
       	       $tel = explode(',',$con_data['tel']);
       	   }
       	   if ($con_data['qq']) {
       	       $qq = explode(',',$con_data['qq']);
       	   }
       	   if ($con_data['email']) {
       	       $email = explode(',',$con_data['email']);
       	   }
       }

       $con_data['tel'] = $tel;
       $con_data['qq'] = $qq;
       $con_data['email'] = $email;
       return $con_data;
	}

		//弹窗广告
	public function site_pop(){
		global $db_config;
		$pop_config = array();
	    $pop = M('site_ad',$db_config)->where("site_id = '".SITEID."' and is_delete = '1'")->find();
        if (!empty($pop)) {
        	$pop_config = M('site_pop_config',$db_config)->where("site_id = '".SITEID."'")->find();
		    $pop['pop_config'] = $pop_config;
            $pop['pop_state'] = 1;
        }else{
        	$pop['pop_state'] = 0;
        }
        return $pop;
	}

	//注册赠送优惠
	public function reg_dis() {
		global $db_config;
		$map['site_id'] = SITEID;
		$reg = M('k_user_apply', $db_config)->where($map)
		                                    ->find();
		if ($reg['discount_money'] > 0) {
			$reg_d['money'] = $reg['discount_money']; //注册赠送金额
			$reg_d['d_bet'] = $reg['discount_bet']; //打码量
		} else {
			$reg_d['money'] = $reg_d['d_bet'] = 0;
		}
		return $reg_d;
	}

	//获取网站标题
	public function getTitle(){
		global $db_config;
		$map['site_id'] = SITEID;
		$web_name = M('web_config', $db_config)->where($map)->getField("web_name");
		return $web_name;
	}

	//前台版权
	public function copyright() {
		global $db_config;
		$map['site_id'] = SITEID;
		$copy_right = M('web_config', $db_config)->where($map)->getField("copy_right");
		return $copy_right;
	}

	//获取本站点默认代理
	//商
	public function getAgent() {
		global $db_config;
		if (defined('INDEX_ID')) {
		    //是否开启多前台
		   $map['index_id'] = INDEX_ID;
		}
		$map['site_id'] = SITEID;
		$map['is_default'] = 1; //表示站点默认代理商
		$map['agent_type'] = 'a_t';
		$Uagent = M('k_user_agent', $db_config);
		$deAgent = $Uagent->field("id,intr")->where($map)->find();

		//判断传入是否有效
		if (!empty($_SESSION['intr'])) {
			$mapS = array();
			if (defined('INDEX_ID')) {
			    //是否开启多前台
			   $mapS['index_id'] = INDEX_ID;
			}
			$mapS['intr'] = $_SESSION['intr'];
			$mapS['site_id'] = SITEID;
			$Astate = $Uagent->where($mapS)->find();
			if ($Astate) {
				$deAgent['intr'] = $Astate['intr'];
				$deAgent['id'] = $Astate['id'];
			}
		}
		return $deAgent;
	}

	//获取站点默认层级
	public function getUserlevel() {
		global $db_config;
		if (defined('INDEX_ID')) {
		    //是否开启多前台
		   $map['index_id'] = INDEX_ID;
		}
		$map['site_id'] = SITEID;
		$map['is_default'] = 1;
		$deLevel = M('k_user_level', $db_config)->where($map)->getField("id");
		return $deLevel;
	}

   public function get_client_ip(){
    $realip = '';
    $unknown = 'unknown';
    if (isset($_SERVER)){
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)){
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach($arr as $ip){
                $ip = trim($ip);
                if ($ip != 'unknown'){
                    $realip = $ip;
                    break;
                }
            }
        }else if(isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], $unknown)){
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        }else if(isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)){
            $realip = $_SERVER['REMOTE_ADDR'];
        }else{
            $realip = $unknown;
        }
    }else{
        if(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), $unknown)){
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        }else if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), $unknown)){
            $realip = getenv("HTTP_CLIENT_IP");
        }else if(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), $unknown)){
            $realip = getenv("REMOTE_ADDR");
        }else{
            $realip = $unknown;
        }
    }
    $realip = preg_match("/[\d\.]{7,15}/", $realip, $matches) ? $matches[0] : $unknown;
    return $realip;
}


 public function notice_data(){
 	global $db_config;
 	$View = new View();
 	$list = '';
 	$list = M('k_message',$db_config)->where("site_id = '".SITEID."' and is_delete = 0 and show_type = 2")->order('add_time DESC')->limit(10)->select();
 	$u = M('site_notice',$db_config);
	$map1 = "sid = '0' and notice_state = '1'";

	$notice1 = $u->field('notice_cate,notice_date as add_time,notice_content as chn_simplified')->where($map1)->order('notice_date DESC')->select();
	if(!empty($list) && !empty($notice1)){
		$list = array_merge($list, $notice1);
	}
	if(empty($list)){
		$list = $notice1;
	}
	foreach ($list as $key => $row)
		{
		    $add_time[$key]  = $row['add_time'];
		}
	array_multisort($add_time,SORT_DESC,$list);
 	$View->add("list",$list);
 	$View->show("notice_data");
 }

 public function notice_count(){
 	global $db_config;
 	$View = new View();
 	$list = '';
 	$list = M('k_user_msg',$db_config)->field('count(msg_id) as count')->where("(uid='" . $_SESSION["uid"] . "' or (uid = '' and (level_id like '%" . $_SESSION['level_id'] . "%' or level_id = '-1')))   and is_delete = '0' and islook='0'")->find();
 	$count = !empty($list['count'])?$list['count']:0;
 	return $count;

 }

 //更新会员在线
 public function redis_update_user(){
 	include_once(dirname(__FILE__)."/../../include/redis_config.php");
	$redis_key = 'ulg'.CLUSTER_ID.'_'.SITEID.$_SESSION['uid'];
	$redis->setex($redis_key,'1200','1');
 }

 //会员离线清除
 public function redis_del_user(){
    include_once(dirname(__FILE__)."/../../include/redis_config.php");
	$redis_key = 'ulg'.CLUSTER_ID.'_'.SITEID.$_SESSION['uid'];
	$redis->del($redis_key);
 }
}
?>
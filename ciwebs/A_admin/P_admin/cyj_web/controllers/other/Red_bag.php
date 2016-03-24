<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Red_bag extends MY_Controller {

	public function __construct() {
		parent::__construct();
        $this->login_check();
		$this->load->model('account/Member_reg_model');
		$this->load->model('cash/Payment_model');
		$this->load->model('other/Red_bag_model');
		$this->gxc = 0;
	}


	public function index(){
		$index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?"a":$index_id;
		//多站点判断
        if (!empty($_SESSION['index_id'])) {
            $this->add('sites_str','站点：'.str_replace('全部', '请选择站点',$this->Member_reg_model->select_sites()));
        }
        $arr = array();
        $arr['table'] = "redenvelopes";
        $arr['where']['site_id'] = $_SESSION['site_id'];
        $arr['where']['index_id'] = $index_id;
        //$arr['where']['status <>'] = "3";//剔除已删除的红包
        $arr['order'] = "id desc";//剔除已删除的红包
        $bag_info = $this->Payment_model->rget($arr);
        if(!empty($bag_info)){
        	foreach ($bag_info as $key => $value) {
        		$bag_info[$key]['status'] = $this->Red_bag_model->get_bag_status($value['status']);
        		$bag_info[$key]['is_ing'] = $value['status'];
          		$bag_info[$key]['groupid'] = $this->Red_bag_model->get_level_info($value['groupid'],$_SESSION['site_id'],$index_id);
        	}
        	//var_dump($bag_info);die;
        	 $this->add('bag_info',$bag_info);
        }
        $this->add('index_id',$index_id);
		$this->add('level',$level);
        $this->add('siteid',$_SESSION['site_id']);

        $this->display('other/red_bag_show.html');
	}



	    //到红包设定页面
	public function bag_set_show(){
        $index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?"a":$index_id;
        $pic = $this->input->get('pic');
        $pic = empty($pic)?"1":$pic;
        $id = $this->input->get('id');
        //多站点判断
        if (!empty($_SESSION['index_id'])) {
            $this->add('sites_str','站点：'.str_replace('全部', '请选择站点',$this->Member_reg_model->select_sites()));
        }
        $map = array();
        $map['table'] = 'k_user_level';
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['index_id'] = $index_id;
        $map['where']['is_delete'] = 0;
		$map['select'] = "id,level_des";
		$level = $this->Payment_model->rget($map);
        if($id>0){
	        $arr = array();
	        $arr['table'] = "redenvelopes";
	        $arr['where']['site_id'] = $_SESSION['site_id'];
	        $arr['where']['index_id'] = $index_id;
	        $arr['where']['id'] = $id;
	        $bag_info = $this->Payment_model->rfind($arr);
	        //层级数据整理
       		$level = $this->is_in_arr($level,$bag_info['groupid']);
        }

        if($bag_info['starttime'] == '' || $bag_info['endtime'] == ''){
        	$bag_info['starttime'] = date('Y-m-d H:i:s');
        	$bag_info['endtime'] = date('Y-m-d H:i:s',strtotime('+1 day'));
        }
        $level = array_chunk($level,4);//分隔成多个数组
        $this->add('index_id',$index_id);
		$this->add('level',$level);
		$this->add('id',$id);
        $this->add('siteid',$_SESSION['site_id']);
        $this->add('bag_info',$bag_info);
        $this->add('pic',$pic);
        $this->display('other/red_bag.html');
	}

	public function creat_red_bag(){
		$id= $this->input->get("id");
		$index_id= $this->input->get("index_id");

		$this->add("id",$id);
		$this->add("index_id",$index_id);
		$map = array();
		$map['table'] ="redenvelopes";
		$map['where']['id'] = $id;
		$map['where']['index_id'] = $index_id;
		$result =  $this->Payment_model->rfind($map);
		$result['groupid'] = $this->Red_bag_model->get_level_info($result['groupid'],$_SESSION['site_id'],$index_id);
		$num = $result['red_num'];//红包个数
		$total = $result['totle_money'];//红包总额
		$min = $result['min_inpoint'];//红包最小额度
		$amount_inpoint = $result['amount_inpoint'];//打码限制
		$groupid = $result['groupid'];//分层
		$starttime = $result['starttime'];
		$endtime = $result['endtime'];
		$title = $result['title'];
		$is_ip = $result['is_ip'];
		$pj_money = $num*$min;
		$safe_money = $total - $pj_money;//随机安全上限
		$pjz = sprintf('%.2f',$total/$num);//平均金额
		$pjz2 = $pjz;
		for ($i=1;$i<$num;$i++){
		  //$safe_total=($total-($num-$i)*$min)/($num-$i);//随机安全上限
		  /*$money=mt_rand($min*100,$safe_total*100)/100;
		  $total=$total-$money;*/
		  $money = mt_rand($min*100,$pjz*100)/100;
			$this ->gxc += ($pjz2 - $money);
			$pjz = $this->gxc + $pjz2;
		  $total=$total-$money;
			$temp = array(
			 'i' => $i,
			 'money' => $money,    //当前红包额度
			 'total' => $total,    //红包剩余额度
			 'min_inpoint' => $min,    //红包剩余额度
			 'amount_inpoint' =>$amount_inpoint,//打码限制
			 'groupid' =>$groupid,//分层
			 'starttime' =>$starttime,//活动起始时间
			  'title' =>$title,//红包名字
			 'endtime' =>$endtime,//活动结束时间
			 'is_ip' =>$is_ip//是否限制ip
			 );
			 $back_msg['res'][] = $temp;
		}
		$temp = array(
			 'i' => $num,
			 'money' => sprintf('%.2f',$total),    //当前红包额度
			 'total' => 0,    //红包剩余额度
			 'min_inpoint' => $min,    //红包剩余额度
			 'amount_inpoint' =>$amount_inpoint,//打码限制
			 'groupid' =>$groupid,//分层
			 'starttime' =>$starttime,//活动起始时间
			  'title' =>$title,//红包名字
			 'endtime' =>$endtime,//活动结束时间
			 'is_ip' =>$is_ip
			 );
		$back_msg['res'][] = $temp;
		$this->add("back_msg",$back_msg);
		$this->add("total",$result['totle_money']);
	    $this->add("num",$result['red_num']);
		$this->display('other/see_look.html');
	}
	//设定红包
	public function set_bag_do(){
		$index_id = $this->input->post('index_id');
        $index_id = empty($index_id)?'a':$index_id;
		$total = $this->input->post("total");//红包总额
		$num = $this->input->post("num");//红包个数
		$min = $this->input->post("min");//红包最少金额
		$level = $this->input->post("level");//可参加会员层级
		$bet_set = $this->input->post("bet_set");//领取红包打码量限制
		$bet_set = empty($bet_set)?0:$bet_set;//领取红包打码量限制
		$award_times = $this->input->post("award_times");//可领取红包次数
		$title = $this->input->post("title");//活动标题
		$description = $this->input->post("description");//活动简介
		$end_theme = $this->input->post("end_theme");//结束标题
		$end_instruction = $this->input->post("end_instruction");//活动结束简介
		$starttime = $this->input->post("starttime");//活动开始时间
		$endtime = $this->input->post("endtime");//活动结束时间
		$is_ip = $this->input->post("is_ip");//是否限制ip
		$pic = $this->input->post("pic");
		$id = $this->input->post("id");
		//友好提示！
		if($num*$min>$total){
			showmessage('红包总额超过设定总额!',URL.'/other/Red_bag/index',0);
		}
		//限制红包时间为48小时以内
		if((strtotime($endtime)-strtotime($starttime))>60*60*48){
			showmessage('红包活动时间必须设置在48小时以内!',URL.'/other/Red_bag/index',0);
		}
		if(empty($is_ip)){
			showmessage('请设定是否限制IP!',URL.'/other/Red_bag/index',0);
		}
		if(empty($id)){
			//过滤活动时间，不能重复
			$sql = "SELECT * FROM `redenvelopes` WHERE `index_id` = '".$index_id."' AND `site_id` = '".$_SESSION['site_id']."' AND `status` in ('0','1') AND (`endtime` > '".$starttime."' OR `starttime` > '".$starttime."' OR `endtime` > '".$endtime."' OR `starttime` > '".$endtime."')";
			$query = $this->db->query($sql);
			$msg = $query->result_array();
			if($msg){
				showmessage('该时间段已有红包!',URL.'/other/Red_bag/index',0);
			}
		}
		if(empty($total)){
			 showmessage('请设置红包总额!',URL.'/other/Red_bag/index',0);
		}
		if(empty($pic)){
			 showmessage('请选择红包效果图!',URL.'/other/Red_bag/index',0);
		}
		if(empty($num)){
			 showmessage('请设置红包分配个数!',URL.'/other/Red_bag/index',0);
		}
		if(empty($min)){
			 showmessage('请设置红包最少金额!',URL.'/other/Red_bag/index',0);
		}
		if($min < "0.1"){
			 showmessage('红包金额最少为0.1元!',URL.'/other/Red_bag/index',0);
		}
		$bet = is_numeric ($bet_set)?true : false;
		if($bet==false){
			 showmessage('设置的打码倍数必须为数字!',URL.'/other/Red_bag/index',0);
		}
		if($num >5000){
			 showmessage('建议您将红包数量设置在5000个以内!',URL.'/other/Red_bag/index',0);
		}
		if(empty($title)){
			 showmessage('请设置活动标题!',URL.'/other/Red_bag/index',0);
		}
		if(empty($starttime)){
			 showmessage('请设置可领取红包开始时间!',URL.'/other/Red_bag/index',0);
		}
		if(empty($endtime)){
			 showmessage('请设置可领取红包结束时间!',URL.'/other/Red_bag/index',0);
		}
		if(!empty($level)){
			$level = implode(',',$level);
		}else{
			$map = array();
	        $map['table'] = 'k_user_level';
	        $map['where']['site_id'] = $_SESSION['site_id'];
	        $map['where']['index_id'] = $index_id;
	        $map['where']['is_delete'] = 0;
			$map['select'] = "id";
			$level = $this->Payment_model->rget($map);
			$level = array_column($level, 'id');
			$level = implode(',',$level);
		}
		if (empty($bet_set)){
			$bet_set = 0 ;
		}
		if (empty($award_times)){
			$award_times = 1 ;
		}

		$data = array();
		$data['site_id'] = $_SESSION['site_id'];
		$data['index_id'] = $index_id;
		$data['totle_money'] = $total;
		$data['red_num'] = $num;
		$data['min_inpoint'] = $min;
		$data['groupid'] = $level;
		$data['amount_inpoint'] = $bet_set;
		$data['award_times'] = $award_times;
		$data['title'] = $title;
		$data['description'] = $description;
		$data['end_theme'] = $end_theme;
		$data['end_instruction'] = $end_instruction;
		$data['starttime'] = $starttime;
		$data['endtime'] = $endtime;
		$data['create_uid'] = $_SESSION['adminid'];
		$data['create_time'] = date('y-m-d h:i:s',time());
		$data['is_ip'] = $is_ip;
		$data['create_ip'] = $this->Payment_model->get_ip();
		$data['pic'] = $pic;
		//var_dump($data);die;
		if($id>0){//修改
			$map1 = array();
			$map1['table'] = "redenvelopes";
			$map1['where']['id'] = $id;
			$result = $this->Payment_model->rupdate($map1,$data);
			if($result){
				$log['log_info'] = '修改了红包配置:'.$_SESSION['login_name_1'];
             	$this->Payment_model->Syslog($log);
				 showmessage('修改成功！',URL.'/other/Red_bag/index',1);
			}else{
				showmessage('修改失败！',URL.'/other/Red_bag/index',0);
			}
		}else{//添加
			$result = $this->Payment_model->radd("redenvelopes",$data);
			if($result){
				$log['log_info'] = '设定了红包配置:'.$_SESSION['login_name_1'];
             	$this->Payment_model->Syslog($log);
				 showmessage('设置成功！',URL.'/other/Red_bag/index',1);
			}else{
				showmessage('设置失败！',URL.'/other/Red_bag/index',0);
			}
		}
	}
	 //层级数据勾选判断
	function is_in_arr($arr,$str){
        $tmp_arr = explode(',',$str);
        foreach ($arr as $k => $v) {
            if (in_array($v['id'],$tmp_arr)) {
                $arr[$k]['is_check'] = 1;
            }
        }
        return $arr;
	}


	function del_red_bag(){
		$id = $this->input->get('id');
		$index_id = $this->input->get('index_id');
		$map1 = array();
		$map1['table'] = "redenvelopes";
		$map1['where']['id'] = $id;
		$data['status'] = "3";
		$redis = new Redis();
		$redis->connect(REDIS_HOST,REDIS_PORT);
		$key = "red_bag".$_SESSION['site_id'].$index_id.$id;
		$redis->delete($key);
		$keyss = "red_bag".$_SESSION['site_id']."_".$index_id."_".date("Y-m-d",strtotime("Y-m-d"));
		$redis->delete($keyss);
		$keysss = "red_bag".$_SESSION['site_id']."_".$index_id."_littlered_".$id;
		$redis->delete($keysss);
		$result = $this->Payment_model->rupdate($map1,$data);
		if($result){
			$log['log_info'] = '删除了红包:'.$_SESSION['login_name_1'];
            $this->Payment_model->Syslog($log);
			 showmessage('已终止红包活动！',URL.'/other/Red_bag/index',1);
		}else{
			showmessage('终止失败！',URL.'/other/Red_bag/index',0);
		}
	}
	function make_red_bag(){
		$id = $this->input->get('id');
		$index_id = $this->input->get('index_id');
		$make_sure = $this->input->get('make_sure');
		//从redis上获取所有小红包信息
		$redis = new Redis();
		$redis->connect(REDIS_HOST,REDIS_PORT);
		$data = $redis->lrange("red_bag".$_SESSION['site_id'].$index_id.$id,0,-1);
		if (!empty($data)) {
		$result =array();
		$data = str_replace('--', '"', $data);
		$kk=count($data);
			foreach($data as $key=>$value){
				$temp= json_decode($value,true);//转数组
				$result[] = array(
					'uuid' =>$temp['i'],
					'rid' =>$id,
					'index_id' =>$index_id,
					'site_id' =>$_SESSION['site_id'],
					'money' => $temp['money'],//红包总额
					'min_inpoint' => $temp['min_inpoint'],//红包最小额度
					'amount_inpoint' => $temp['amount_inpoint'],//打码限制
					'groupid' => $temp['groupid'],
					'username' =>"",
					'uid' =>"",
					'starttime' => $temp['starttime'],
					'title' => $temp['title'],
					'make_sure' => "1",
					'createip' => $temp['createip'],
					'endtime' => $temp['endtime']
					);
			}
		}
		$msg = $this->Red_bag_model->update_add_info($result,$id,$index_id);
		if($msg){
			$map = array();
			$map['table'] = "redenvelopes";
			$map['where']['id'] = $id;
			$map['where']['index_id'] = $index_id;
			$map['where']['site_id'] = $_SESSION['site_id'];
			$set['make_sure'] = "1";
			$this->Red_bag_model->rupdate($map,$set);
			$log['log_info'] = '生成了红包:'.$_SESSION['login_name_1'];
             $this->Payment_model->Syslog($log);
			 showmessage('红包生成成功！',URL.'/other/Red_bag/index',1);
		}else{
			showmessage('红包生成失败！',URL.'/other/Red_bag/index',0);
		}
	}

	function search_bag_index(){
		$index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?"a":$index_id;
        $page = $this->input->get('page');
        $title = $this->input->get('title');
        $ptype = $this->input->get('ptype');
        $starttime = $this->input->get('starttime');
        $endtime = $this->input->get('endtime');
        $money = $this->input->get('money');
        $username = $this->input->get('username');
        $page_num = $this->input->get('page_num');
        $pagenum = isset($page_num)?$page_num:100; // 每页显示的记录数
        $page = isset($page) ? $page : 1;
		//多站点判断
        if (!empty($_SESSION['index_id'])) {
            $this->add('sites_str','站点：'.str_replace('全部', '请选择站点',$this->Member_reg_model->select_sites()));
        }
        $titles = $this->Red_bag_model->get_bag_group();//获取红包分组信息
        $map = array();
        $map['table'] = 'redenvelopes_log';
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['index_id'] = $index_id;
        $map['where']['uid >'] = 0;
        if($title){
        	 $map['like']['title'] = "title";
        	 $map['like']['match'] = $title;
        	 $map['like']['after'] = "both";
        }
        if($money){
        	 $map['where']['min_inpoint>='] = $money;
        }
        if($username){
        	 $map['like']['title'] = "username";
        	 $map['like']['match'] = $username;
        	 $map['like']['after'] = "both";
        }
        if($ptype==1){
        	 $map['where']['ptype'] = 1;
        }
        if($ptype==0){
        	 $map['where']['ptype'] = 0;
        }
        if($starttime && $endtime){
        	 $map['where']['starttime >='] = $starttime;
        	 $map['where']['endtime <='] = $endtime;
        }
        $sum = $this->Red_bag_model->rcount($map);//计算出总数
        $totalPage = ceil($sum / $pagenum); // 计算出总页数
        if ($totalPage < $page) {
            $page = 1;
        }
        $map['pagecount'] = $pagenum;
        $map['offset'] = $page > 1 ? ($page - 1) * $pagenum : 0;
        $list = $this->Red_bag_model->get_table($map);
        $this->add('index_id',$index_id);
		$this->add('level',$level);
        $this->add('siteid',$_SESSION['site_id']);
        $this->add('title',$title);
		$this->add('starttime',$starttime);
        $this->add('endtime',$endtime);
        $this->add('username',$username);
        $this->add('money',$money);
        $this->add('ptype',$ptype);
        $this->add('list',$list);
        $this->add('totalPage', $totalPage);
        $this->add("rid",$rid);
        $this->add("titles",$titles);
		$this->display('other/search_bag_index.html');
	}

	public function edit_red_bag(){
		//@ini_set('memory_limit', '128M');
		$id = $this->input->get('id');
		$index_id = $this->input->get('index_id');
		$money = $this->input->post('money');
		$map = array();
		$map['table'] ="redenvelopes";
		$map['where']['id'] = $id;
		$map['where']['index_id'] =  $index_id;
		$map['where']['site_id'] =  $_SESSION['site_id'];
		$data =  $this->Payment_model->rfind($map);
		$key = "red_bag".$_SESSION['site_id'].$index_id.$id;
		$redis = new Redis();
		$result = array();
		$total_money = 0;
		$redis->connect(REDIS_HOST,REDIS_PORT);
		foreach ($money as $k => $v) {
			$temp['money']= $v;//红包额
			$temp['rid'] = $id;
			$temp['uuid'] = $k;
			$temp['index_id'] = $index_id;
			$temp['min_inpoint'] = $data['min_inpoint'];//红包最小额度
			$temp['amount_inpoint'] = $data['amount_inpoint'];//打码限制
			$temp['groupid'] = $data['groupid'];
			$temp['username'] = "";
			$temp['uid'] = "";
			$temp['starttime'] = $data['starttime'];
			$temp['title'] = $data['title'];
			$temp['make_sure'] = "1";
			$temp['endtime'] = $data['endtime'];
			$temp['createip'] =$data['createip'];
			$temp['is_ip'] =$data['is_ip'];
			$temp['site_id'] =$_SESSION['site_id'];
			$result[] = $temp;
			$total_money+=$v;
		}
		$keyss = "red_bag".$_SESSION['site_id']."_".$index_id."_".date("Y-m-d",strtotime($data['starttime']));
		$datas = $redis->lrange($keyss,0,-1);
		if(empty($datas)){
			 $redis->rpush($keyss,$data);
		}else{
	    	$datas = str_replace('--', '"', $datas);
	    	$message = false;
			foreach($datas as $k=>$v){
				$temp= json_decode($v,true);//转数组
				if($temp['id'] == $data['id']){
					$message = true;
					break;
				}
			}
			if($message===flase){
				 $redis->rpush($keyss,$data);
			}
		}
		$big_jsons = json_encode($data, JSON_UNESCAPED_UNICODE);
		$big_jsons = str_replace('"', '--', $big_jsons);
		$redis->lpush($keyss,$big_jsons);

		$msg = $this->Red_bag_model->add_bag_info($result,$id,$index_id);
		if($msg){
			//更新总额
			$map1 = array();
			$map1['table'] = "redenvelopes";
			$map1['where']['id'] = $id;
			$map1['where']['index_id'] =$index_id;
			$map1['where']['site_id'] = $_SESSION['site_id'];
			$set1['totle_money'] = $total_money;
			 $this->Red_bag_model->rupdate($map1,$set1);

			$map = array();
			$map['table'] = "redenvelopes";
			$map['where']['id'] = $id;
			$map['where']['index_id'] =$index_id;
			$map['where']['site_id'] = $_SESSION['site_id'];
			$set['make_sure'] = "1";
			 $this->Red_bag_model->rupdate($map,$set);
			$log['log_info'] = '生成了红包:'.$_SESSION['login_name_1'];
             $this->Payment_model->Syslog($log);
              showmessage('红包已生成成功！',URL.'/other/Red_bag/index',1);
		}else{
			showmessage('红包生成失败！',URL.'/other/Red_bag/index',0);
		}

	}

	function search_look(){
		$id = $this->input->get('id');
		$index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?"a":$index_id;
		$copy = $this->Red_bag_model->get_web_config($index_id);
		//多站点判断
        if (!empty($_SESSION['index_id'])) {
            $this->add('sites_str','站点：'.str_replace('全部', '请选择站点',$this->Member_reg_model->select_sites()));
        }
        $map = array();
        $map['table'] = 'redenvelopes_log';
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['index_id'] = $index_id;
        $map['where']['rid'] = $id;
        $map['where']['make_sure'] = 1;
        $result = $this->Red_bag_model->rget($map);
        $num = count($result);
        $total_money = 0;
        $take = 0;
        $take_num = 0;
         $data =$result;
        foreach ($result as $key => $value) {
        	if($value['uid']){
        		$take += $value['money'];
        		$take_num = $take_num+1;
        		$data[$key]['groupid'] = $this->Red_bag_model->get_level_info($value['groupid'],$_SESSION['site_id'],$index_id);
        		$user = $this->get_user_info($value['uid'],$index_id);
        		$data[$key]['allmoney'] = $user[0]['money'];
        	}
        	 $total_money+= $value['money'];
        	}
        $no_take = $total_money-$take;
        $no_num = $num - $take_num;
        $this->add('total_money',$total_money);
        $this->add('take',$take);
        $this->add('no_take',$no_take );
        $this->add('num',$num);
        $this->add('take_num',$take_num);
        $this->add('no_num',$no_num);
        $this->add('result',$data);
		$this->add("copy_right",$copy['copy_right']);
		$this->display('other/see_look_too.html');
	}
	function get_redis_info($index_id,$id){
		$redis = new Redis();
		$redis->connect(REDIS_HOST,REDIS_PORT);
		$key="red_bag".$_SESSION['site_id'].$index_id.$id;
	    $data = $redis->lrange($key,0,-1);
	    $data = str_replace('--', '"', $data);
			foreach($data as $key=>$value){
				$temp= json_decode($value,true);//转数组
				$result[] = array(
					'uuid' =>$temp['i'],
					'rid' =>$id,
					'index_id' =>$index_id,
					'site_id' =>$_SESSION['site_id'],
					'money' => $temp['money'],//红包总额
					'min_inpoint' => $temp['min_inpoint'],//红包最小额度
					'amount_inpoint' => $temp['amount_inpoint'],//打码限制
					'groupid' => $temp['groupid'],
					'username' =>"",
					'uid' =>"",
					'starttime' => $temp['starttime'],
					'title' => $temp['title'],
					'make_sure' => "1",
					'createip' => $temp['createip'],
					'endtime' => $temp['endtime']
					);
			}
			return $result;
	}

	function get_user_info($uid,$index_id){
		$map = array();
		$map["table"] = "k_user";
		$map["where"]['uid'] = $uid;
		$map["where"]['index_id'] = $index_id;
		$map["where"]['site_id'] = $_SESSION['site_id'];
		return $this->Red_bag_model->rget($map);
	}

		//层级信息
	function get_level_info($level,$site_id,$index_id){
		$map = array();
		$map['table'] = "k_user_level";
		$map['where']['site_id'] = $site_id;
		$map['where']['index_id'] = $index_id;
		$map['where_in']['id'] = $level;
		$map['select']= "level_des,id";
		$result = $this->rget($map);
		$str = "";
		$groupid = explode(",",$level);
		foreach ($result as $key => $value) {
			if(in_array($value['id'],$groupid)){
				if($key!=count($groupid)-1){
					$str.=$value['level_des'].",";
				}else{
					$str.=$value['level_des'];
				}
			}
		}
		return $str;
	}

}
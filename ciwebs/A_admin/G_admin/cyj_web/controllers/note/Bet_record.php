<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bet_record extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('note/Note_model');

	      //会员下注时启用
	    $itype = $this->input->get('itype');
	    $uname = $this->input->get('username');
	    if ($itype == 1) {
	    	$this->add('itype',$itype);
	        $this->add('note_types',$this->note_types($uname));
	    }
	}

	  //体育注单
    public function sp_bet_record(){
        $agent_id = $_SESSION['agent_id'];
	    $username = $this->input->get('username');
        $number = $this->input->get('number');//订单号
	    $s_date = $this->input->get('start_date');
	    $s_date = empty($s_date)?date('Y-m-d'):$s_date;
	    $e_date = $this->input->get('end_date');
	    $e_date = empty($e_date)?date('Y-m-d'):$e_date;
	    $page = $this->input->get('page');
	    $ltype = $this->input->get('ltype');//状态判断
        $sp_lx = $this->input->get('sp_lx');//单式串关
       // p($_SESSION);die;
        $map['site_id'] = $_SESSION['site_id'];
        if ($_SESSION['guanliyuan'] == 1){
            $agentsstr = $_SESSION['agent_ids'];
            $map['agent_id'] = array('in','('.$agentsstr.')');
        }else{
           $map['agent_id'] = $agent_id;
        }

	    if (!empty($username)) {
	        $map['username'] = array('like',$username);
	    }
	    if (!empty($number)) {
	        $map['number'] = array('like',$number.'%');
	    }
        //状态判断
	    if(isset($ltype)){
            if ($ltype != -1) {
                $map['is_jiesuan'] = $ltype;
            }
	    }
        //下注时间
	    $map['bet_time'] = array(array('>=',$s_date.' 00:00:00'),
	    	                     array('<=',$e_date.' 23:59:59')
	    	                     );
	    //p($map);
        if ($sp_lx == 'cg') {
            $count = $this->Note_model->mcount($map,array('tab'=>'k_bet_cg_group','base_type'=>1));
        }else{
        	$count = $this->Note_model->mcount($map,array('tab'=>'k_bet','base_type'=>1));
        }

		$perNumber=50;
		$totalPage=ceil($count/$perNumber);
		$page=isset($page)?$page:1;
		if($totalPage<$page){
		  $page = 1;
		}
		$startCount=($page-1)*$perNumber;
		$limit=$startCount.",".$perNumber;
	    $data = $this->Note_model->get_sp_bet($sp_lx,$map,$limit);
        $count_x = $money_x= 0;
	    foreach ($data as $k=>$v){
	        $money_x += $v['bet_money'];
	        $count_x ++;
	    }
	    //总计
	    $count_all = $this->Note_model->get_sp_count($map,$sp_lx);
	    $this->add('data', $data);
        $this->add('page', $this->Note_model->get_page('k_bet',$totalPage,$page));
	    $this->add('start_date',$s_date);
	    $this->add('money_x',$money_x);
	    $this->add('count_x',$count_x);
        $this->add('count_all',$count_all);
	    $this->add('end_date',$e_date);
	    if ($sp_lx == 'cg') {
	    	$this->display('note/sp_cg_bet_record.html');
	    }else{
	    	$this->display('note/sp_bet_record.html');
	    }
    }

    //彩票注单
	public function fc_bet_record(){
	    $site_id = $_SESSION['site_id'];
	    $index_id = $this->input->get('index_id');
	    $uid = $this->input->get('uid');
	    $agent_id = $_SESSION['agent_id'];
	    $page = $this->input->get('page');
	    $start_date = $this->input->get('start_date');
	    $end_date = $this->input->get('end_date');
	    $cp_type = $this->input->get('cp_type');
	    $status = $this->input->get('status');
	    $order = $this->input->get('order');
	    $username = $this->input->get('username');
	    $statustype = $this->input->get('statustype');
	    $pagenum = 50;
	    $CurrentPage = isset($page) ? $page : 1;
	    $start_date = $start_date?$start_date:date('Y-m-d');
	    $end_date = $end_date?$end_date:date('Y-m-d');
	    $this->add('start_date', $start_date);
	    $this->add('end_date', $end_date);
	    $map['table'] = 'c_bet';
	    $map['where']['site_id'] = $site_id;
	    if (! empty($start_date) && ! empty($end_date)) {
	        $map['where']['addtime >='] = $start_date.' 00:00:00';
	        $map['where']['addtime <='] = $end_date.' 23:59:59';
	    }
	    if (! empty($cp_type)) {
	        $map['where']['type'] = $cp_type;
	    }
	    if (!empty($index_id)) {
	        $map['where']['index_id'] = $index_id;
	    }
	    if ($_SESSION['guanliyuan'] == 1){
            $agentsstr = $_SESSION['agent_ids'];
            $map['in']['agent_id'] = $agentsstr;
        }else{
           $map['where']['agent_id'] = $_SESSION['agent_id'];
        }
	    //$map['where']['agent_id'] = $agent_id;
	    //结算
        if($status == 5){
	        $map['where']['js'] = 1;
	    }elseif(isset($status) && $status != -1){
	    	$map['where']['status'] = $status;
	    }

	    if (! empty($order)) {
	        $map['where']['did'] = $order;
	    }
	    if (! empty($username)) {
	        $map['where']['username'] = $username;
	    }
	    $map['order']='id desc';
	    $sum = $this->Note_model->get_table_count($map);
	    $totalPage = ceil($sum / $pagenum); // 计算出总页数
	    if ($totalPage < $CurrentPage) {
	        $CurrentPage = 1;
	    }
	    $map['pagecount'] = $pagenum;
	    $map['offset'] = $page > 1 ? ($page - 1) * $pagenum : 0;
	    $list = $this->Note_model->get_table($map);
	    foreach ($list as $k=>$v){
	        $x['money']+=$v['money'];
	        $x['result']+=$v['win'];
	    }
	    $x['count']=count($list);
	    $mapsum['select'] = 'count(id) as count,sum(money) as money,sum(win) as result';
	    $mapsum['table'] = $map['table'];
	    $mapsum['where'] = $map['where'];
	    $all = $this->Note_model->get_table_one($mapsum);
	    $cpmap['table'] = 'fc_games';
	    $cplist = $this->Note_model->get_table($cpmap);
	    $this->add('totalPage', $totalPage);
	    $this->add('list', $list);
	    $this->add('cplist', $cplist);
	    $this->add('x', $x);
	    $this->add('all', $all);
	    $this->display('note/fc_bet_record.html');
	}

    //视讯注单
	public function video_bet_record(){
	    $site_id = $_SESSION['site_id'];
	    $agent_id = $_SESSION['agent_id'];
	    $uid = $this->input->get('uid');
        $username = $this->input->get('username');
	    $page = $this->input->get('page');
	    $type = $this->input->get('type');
	    $videotype =$this->input->get('VideoType');
	    $gametype = $this->input->get('GameType');
	    $start_date =$this->input->get('start_date');
	    $end_date = $this->input->get('end_date');
	    $start_date = $start_date?$start_date:date('Y-m-d');
	    $end_date = $end_date?$end_date:date('Y-m-d');
	    $this->add('start_date', $start_date);
	    $this->add('end_date', $end_date);
	    //p($_SESSION);
        //判断查询区间
	    $is_sdate = explode('-',$start_date);
	    $is_edate = explode('-',$end_date);
	    if ($is_sdate[0] != $is_edate[0] || ($is_edate[1] - $is_sdate[1])>1) {
	         showmessage('查询区间只能在两个月之间','back',0);
	    }
	    $g_type_arr = array("og", "ag", "mg", "ct", "bbin","lebo");
	    $Company = $type?$type:'lebo';
	    if (!in_array($Company, $g_type_arr)) {
	        $data['Error']='未知的游戏';
	        exit(json_encode($data));
	    }
        $date = array($start_date.' 00:00:00',$end_date.' 23:59:59');
        $count = $this->Note_model->vcount($Company,$username,$date);
		$perNumber = 50;
		$totalPage=ceil($count/$perNumber);
		$page=isset($page)?$page:1;
		if($totalPage<$page){
		  $page = 1;
		}
		$startCount=($page-1)*$perNumber;
		$limit=$startCount.",".$perNumber;
	    $data = $this->Note_model->get_video_bet($Company,$username,$date,$limit);
        $field_name=$this->Note_model->time_type($Company);
        //总计
	    $sum = $this->Note_model->video_num($Company,$date,$username);
	    $sum_list = array(0,0,0,$sum['sum1'],$sum['sum2'],$sum['sum3']);
        $video_game_arr = $this->video_type();
	    foreach ($data as $key => $value) {
	        $sum_list[0] += $value[$field_name[1]];
	        $sum_list[1] += $value[$field_name[2]];
	        if($field_name[3] == "win_or_loss-betpoint"){
	            $sum_list[2] += $value['win_or_loss']-$value['betpoint'];
	        }else{
	            $sum_list[2] += $value[$field_name[3]];
	        }
            //游戏类型字段
            $tmp_game = $value[$field_name[4]];
            if ($Company == 'ag' && $value['data_type'] == 'EBR') {
                $data[$key]['game_zh'] = '电子游戏';
            }elseif($Company == 'bbin' && $value['gamekind'] != 3){
                switch ($value['gamekind']) {
                	case '12':
                		$data[$key]['game_zh'] = 'BB彩票';
                		break;
                	case '1':
                		$data[$key]['game_zh'] = 'BB体育';
                		break;
                	case '5':
                		$data[$key]['game_zh'] = '电子游戏';
                		break;
                }

            }else{
            	if ($Company == 'og' && $value['table_id'] == 10 && $tmp_game == 12) {
            	    $data[$key]['game_zh'] = '新式龙虎';
            	}else{
            		$data[$key]['game_zh'] = $video_game_arr[$Company][$tmp_game];
            	}
            	//$data[$key]['game_zh'] = $video_game_arr[$Company][$tmp_game];
            }

            //下注中文翻译
            $data[$key]['bet_detail'] = $this->video_bet_detail_ch($Company,$value[$field_name[5]],$value[$field_name[4]]);
	    }
	    $sum_list[0] = sprintf("%.4f",$sum_list[0]);
	    $sum_list[1] = sprintf("%.4f",$sum_list[1]);
	    $sum_list[2] = sprintf("%.4f",$sum_list[2]);
	    $sum_list[3] = sprintf("%.4f",$sum_list[3]);
	    $sum_list[4] = sprintf("%.4f",$sum_list[4]);
	    $sum_list[5] = sprintf("%.4f",$sum_list[5]);

       //这里只是为了new一个对象，随便传了一个存在的表ag_user
        $this->add('page',$this->Note_model->get_page('ag_user',$totalPage,$page));
	    $this->add('data', $data);
        $this->add('type', $Company);
        $this->add('countN', $count);
	    $this->add('sum_list', $sum_list);
	    $this->add('Company', $Company);
	    $this->display("note/video/".$Company."_bet_record.html");
	}

	//视讯类型
	public function video_type(){
	    return array(
	    	  'ct'=>array('1'=>'百家乐','2'=>'轮盘','3'=>'骰宝',
	    	              '4'=>'龙虎','5'=>'番摊','7'=>'保险百家乐',
	    	              '8'=>'波比轮盘','9'=>'骰宝番摊','10'=>'波比百家乐',
	    	              '13'=>'色碟'),
	    	  'og'=>array('11'=>'标准百家乐','12'=>'经典龙虎','13'=>'轮盘',
	    	              '14'=>'骰宝','16'=>'番摊'),
	    	  'ag'=>array('BAC'=>'百家乐','DT'=>'龙虎','SHB'=>'骰宝',
	    	  	          'ROU'=>'轮盘','CBAC'=>'包桌百家乐','LINK'=>'连环百家乐'
	    	              ),
	    	  'lebo'=>array('4'=>'龙虎','3'=>'骰宝','1'=>'百家乐',
	    	  	          '2'=>'轮盘'),
	    	  'bbin'=>array('3015'=>'番摊','3003'=>'龙虎斗','3001'=>'百家乐',
	    	  	          '3002'=>'二八杠','3005'=>'三公','3006'=>'温州牌九',
	    	  	          '3007'=>'轮盘','3008'=>'骰宝','3010'=>'德州扑克',
	    	  	          '3011'=>'色碟','3011'=>'色碟','3011'=>'色碟',
	    	  	          '3011'=>'色碟','3012'=>'牛牛','3014'=>'无限21点'
	    	  	          ),


	    );
	}

	//视讯中文翻译
	public function video_bet_detail_ch($v_type,$detail,$g_type){
        if ($v_type == 'lebo') {
        	$tmp_bet_arr = explode('#',$detail);
    		$bet_detail = '';
    		$tmp_bet_arr = array_filter($tmp_bet_arr);//去掉空元素

        	if ($g_type == 4) {
        		$long_hu = array('1'=>'龙','2'=>'和','3'=>'虎');
        	}elseif($g_type == 1){
        		$long_hu = array('1'=>'闲','2'=>'庄','3'=>'闲对子','4'=>'和',
            	'5'=>'庄对子','6'=>'小','8'=>'大','16'=>'任意一对','17'=>'完美对子');
	        }
	        foreach ($tmp_bet_arr as $k => $v) {
                $tmp_bet_detail = explode(',',$v);
    	        $bet_detail .= $long_hu[$tmp_bet_detail[0]].$tmp_bet_detail[1];
    		}
    		if (empty($bet_detail)) {
                $bet_detail = $detail;
            }
        }elseif($v_type == 'og'){
            $long_hu = array('101'=>'闲','102'=>'庄','103'=>'和','104'=>'闲对','105'=>'庄对','201'=>'龙','202'=>'虎','203'=>'和',
            	'401'=>'点数4','402'=>'点数5','403'=>'点数6','404'=>'点数7',
            	'405'=>'点数8','406'=>'点数9','407'=>'点数10','408'=>'点数11',
            	'409'=>'点数12','410'=>'点数13','411'=>'点数14',
            	'412'=>'点数15','413'=>'点数16','414'=>'点数17','415'=>'小',
            	'416'=>'大','417'=>'三军1','418'=>'三军2','419'=>'三军3',
            	'420'=>'三军4','421'=>'三军5','422'=>'三军6',
            	'423'=>'短牌1','424'=>'短牌2','425'=>'短牌3','426'=>'短牌4',
            	'427'=>'短牌5','428'=>'短牌6','429'=>'围骰1','430'=>'围骰2',
            	'431'=>'围骰3','432'=>'短牌4','433'=>'短牌5','434'=>'围骰6',
            	'435'=>'全骰','436'=>'长牌1~2','437'=>'长牌1~3',
            	'438'=>'长牌1~4','439'=>'长牌1~5','440'=>'长牌1~6',
            	'441'=>'长牌2~3','442'=>'长牌2~4','443'=>'长牌2~5',
                '444'=>'长牌2~6','445'=>'长牌3~4','446'=>'长牌3~5',
                '447'=>'长牌3~6','448'=>'长牌4~5','449'=>'长牌4~6',
                '450'=>'长牌5~6','451'=>'单','452'=>'双'
            	);
            $tmp_bet_arr = explode(',',$detail);
    		$bet_detail = '';
    		$tmp_bet_arr = array_filter($tmp_bet_arr);//去掉空元素
    		foreach ($tmp_bet_arr as $k => $v) {
                $tmp_bet_detail = explode('^',$v);
    	        $bet_detail .= $long_hu[$tmp_bet_detail[0]].'('.$tmp_bet_detail[1].')';
    		}
            if (empty($bet_detail)) {
                $bet_detail = $detail;
            }
        }
        return $bet_detail;
	}

	//获取体育串关详情
	public function getinformation(){
        $gid = $this->input->post('gid');

        $info=$this->Note_model->get_information($gid);
        exit(json_encode($info));
    }

       //注单跳转
    public function note_types($uname){
        return '注单类型：<select name="note_type" id="note_type"><option value="sp_bet_record?io=0&itype=1&username='.$uname.'">體育</option><option value="fc_bet_record?io=1&itype=1&username='.$uname.'">彩票</option><option value="video_bet_record?io=2&itype=1&username='.$uname.'">视讯</option></select>&nbsp;';
    }
}
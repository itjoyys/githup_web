<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Discount extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('cash/Discount_model');
	}

	public function index()
	{
		//时间条件
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		$index_id = $this->input->post('index_id');
	    $index_id = empty($index_id)?'a':$index_id;
		if (empty($start_date) || empty($end_date)) {
		    $start_date = $end_date = date('Y-m-d');
		}

        //查询时间判断
        about_limit($end_date,$start_date);

		//多站点判断
	    if (!empty($_SESSION['index_id'])) {
	    	$this->add('sites_str',str_replace('全部', '请选择站点',$this->Discount_model->select_sites()));
	    }

        //获取股东
        $map = array();
        $map['table'] = 'k_user_agent';
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['index_id'] = $index_id;
        $map['where']['agent_type'] = 's_h';
        $map['where']['is_demo'] = 0;
        $this->add('agent_sh',$this->Discount_model->rget($map));
        //获取层级
        $map['table'] = 'k_user_level';
        $map['select'] = 'level_des,id';
        $map['where']['is_delete'] = 0;
        unset($map['where']['agent_type']);
        unset($map['where']['is_demo']);

        $level = $this->Discount_model->rget($map);
        $level = array_chunk($level,4);//分隔成多个数组
        $this->add('level',$level);

        $n_week = date('Y-m-d',(time()-((date('w')==0?7:date('w'))-1)*24*3600));
        $this->add('n_week',$n_week);
        $this->add('l_week',date('Y-m-d', strtotime($n_week.'-7 day')));
        $this->add('now_date',date("Y-m-d"));
        $this->add('l_date',date("Y-m-d", strtotime("-1 day")));
        $this->add('l_week_2',date("Y-m-d", strtotime("last Sunday")));

        $this->add('start_date',$start_date);
        $this->add('site_id', $_SESSION['site_id']);
        $this->add('index_id',$index_id);
        $this->add('end_date',$end_date);
        $this->add('now_time',date('Y-m-d H:i:s'));
		$this->display('cash/discount_index.html');
	}

	//优惠统计
	public function dis_count(){
		$date_start = $this->input->post('date_start');
		$date_end = $this->input->post('date_end');
		$sh_id = $this->input->post('sh_name');//股东ID
        $level = $this->input->post('level');
        $index_id = $this->input->post('index_id');
        $index_id = empty($index_id)?'a':$index_id;
		$wtype = $this->input->post('wtype');

        if (empty($sh_id)) {
            showmessage('请选择股东！',URL.'/cash/discount/index',0);
        }

         //判断是否已经返水
        $map_f = array();
        $map_f['table'] = 'k_user_discount_search';
        $map_f['where']['agent_id'] = $sh_id;
        $map_f['where']['site_id'] = $_SESSION['site_id'];
        $map_f['where']['back_time_start'] = $date_start;
        $map_f['where']['back_time_end'] = $date_end;
        $map_f['where']['people_num >'] = 0;
        $is_dis_true = $this->Discount_model->rfind($map_f);
        $is_dis_true = empty($is_dis_true)?0:1;

        //层级
	    if (!empty($level)) {
	        $level_str = implode(',',$level);
	        $level_title = '(层级类型)';
	        $wtype = 2;//层级类型
	        $this->add('level_str',$level_str);
	    }else{
	        $level_str = 0;
	    }

          //读取视讯配置
        $videoc = $this->Discount_model->M(array('tab'=>'web_config','type'=>1))->where("site_id = '".$_SESSION['site_id']."'")->getField('video_module');
        $video_c = explode(',',$videoc);
        foreach ($video_c as $k => $v) {
            $video_types[] = $v;
            if ($v == 'mg') {
                $video_types[] = 'mgdz';
            }elseif($v == 'bbin'){
                $video_types[] = 'bbdz';
                $video_types[] = 'bbsp';
                $video_types[] = 'bbfc';
            }
        }

        //$type = 'ag,og,mg,mgdz,ct,lebo,bbin,bbdz';
        $date = array($date_start,$date_end);
	    $userIds = $this->Discount_model->Dis_agentsh($sh_id,$date,$level,$index_id,$video_c);

        $title_type = array('sp'=>'体育','fc'=>'彩票','mg'=>'MG视讯','mgdz'=>'MG电子','ag'=>'AG视讯','og'=>'OG视讯','ct'=>'CT视讯','lebo'=>'LEBO视讯','bbin'=>'BB視訊','bbdz'=>'BB电子','bbsp'=>'BB体育','bbfc'=>'BB彩票','pt'=>'PT电子','eg'=>'EG电子');

        //type
        $vtype = array_merge(array('sp','fc'), $video_types);
        //css合并数量
        $this->add('inum',count($vtype));

        $bettypes = $fdtypes = array();
	    //type
	    //$vtype = array('sp','fc','mg','mgdz','ag','og','ct','lebo','bbin','bbdz');
	    //数据整合
	    foreach ($userIds as $key => $val) {
	    	foreach ($vtype as $k => $v) {
	    		$k_bet = $v.'_bet';
	    		$k_fd = $v.'_fd';
                $total[$k_bet] += $val[$k_bet];
                $total[$k_fd] += $val[$k_fd];

                $totalb[$k_bet] += $val[$k_bet];
                $totalf[$k_fd] += $val[$k_fd];

	    	}
            $totalBet += $val['betall'];
            $total_fd += $val['total_e_fd'];
	    }

        foreach ($vtype as $k => $v) {
            $bettypes[] = $v.'_bet';
            $fdtypes[] = $v.'_fd';
        }

	    $this->add('userIds',$userIds);
        $this->add('is_dis_true',$is_dis_true);
        $this->add('totalb',$totalb);
        $this->add('totalf',$totalf);

	    $this->add('site_id',$_SESSION['site_id']);
	    $this->add('totalBet',$totalBet);
	    $this->add('total_fd',$total_fd);
        $this->add('title_type',$title_type);

	    $this->add('iNum',count($userIds));
	    $this->add('agent_id',$sh_id);
        $this->add("vtype",$vtype);
        $this->add('bettypes',$bettypes);
        $this->add('fdtypes',$fdtypes);
	    $this->add('dis_type',$wtype);
	    $this->add('index_id',$index_id);
	    $this->add('date_start',$date_start);
	    $this->add('date_end',$date_end);
	    $this->add('dis_name',$date_start.' ~ '.$date_end.$level_title);
	    $this->display('cash/discount_count.html');
	}

	//写入返水
	public function dis_save(){
		$index_id = $this->input->post('index_id');
        $index_id = empty($index_id)?'a':$index_id;
		$uid = $this->input->post('uid');
		$dis_type = $this->input->post('dis_type');//返水类型
		$agent_id = $this->input->post('agent_id');//股东ID
		$level_str = $this->input->post('level_str');//层级ID
		$back_time_start = $this->input->post('date_start');
		$back_time_end = $this->input->post('date_end');
		$zhbet = $this->input->post('zhbet');//综合打码
		$remark = $this->input->post('remark');//事件名称

		if (empty($uid) || empty($agent_id) || empty($dis_type)) {
            showmessage('数据不能为空',URL.'/cash/discount/index',0);
        }

	    $data = array();
	    $data['site_id'] = $_SESSION['site_id'];
	    $data['index_id'] = $index_id;
	     //是否勾选层级
	    if (!empty($level_str)) {
	        $data['level_str'] = $level_str;
	    }
	    $data['agent_id'] = $agent_id;
	    $data['admin_user'] = $_SESSION['login_name']; // 操作者
	    $data['back_time_start'] = $back_time_start;
	    $data['back_time_end'] = $back_time_end;
	    $data['type'] = $dis_type;
	    $data['bet'] = intval($zhbet);
	    $data['event'] = $remark; // 事件名称
        $data['people_num'] = count($uid);//选择的退水人数
        $data['dis_date'] = md5($back_time_start.$back_time_end);//返水时间

        // $dddd = $this->Discount_model->dis_save($uid,$data);

        // p($dddd);die();

	    if ($this->Discount_model->dis_save($uid,$data)) {
            $log['log_info'] = $back_time_start.'-'.$back_time_end."(美东)優惠退水完成";
	        $this->Discount_model->Syslog($log);
            showmessage('退水成功!',URL.'/cash/discount/index');
	    }else{
	    	showmessage('退水失败!',URL.'/cash/discount/index',0);
	    }

    }

    //返水记录
    public function discount_log(){
        $month = $this->input->get('month');
        $year = $this->input->get('year');
        $year = empty($year)?date('Y'):$year;

        $index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?'a':$index_id;

        if (!empty($month)) {
            if ($month < 10) {
                $month_g = $year.'-0'.$month;
            }else{
                $month_g = $year.'-'.$month;
            }
        }else{
            $month = date('n');
            $month_g = date('Y-m');
        }
        // p($month_g);die();
        //月份匹配
        $in = ($year == date('Y'))?date('m'):12;
        //月份
        for ($i = $in; $i > 0; $i--) {
            $month_arr[] = $i;
        }

			//多站点判断
	    if (!empty($_SESSION['index_id'])) {
	    	$this->add('sites_str','站点：'.str_replace('全部', '请选择站点',$this->Discount_model->select_sites()));
	    }

		$map = array();
		$map['table'] = 'k_user_discount_search';
		$map['like']['title'] = 'back_time_end';
        $map['like']['match'] = $month_g;
        $map['like']['after'] = 'both';
		$map['where']['site_id'] = $_SESSION['site_id'];
		if (!empty($index_id)) {
		    $map['where']['index_id'] = $index_id;
		}
		$map['order'] = 'id DESC';

         // p($map);die();

		$this->add('data',$this->Discount_model->rget($map));
		$this->add('site_id', $_SESSION['site_id']);
		$this->add('index_id',$index_id);
		$this->add('month',$month);
        $this->add('year',$year);
		$this->add('month_arr',$month_arr);
        $this->display('cash/discount_log.html');
    }

    //优惠返点列表
    public function discount_fd(){
    	$index_id = $this->input->get('index_id');
    	$index_id = empty($index_id)?'a':$index_id;
        $level_id = $this->input->get('level_id');
        $level_id = empty($level_id)?0:$level_id;

    		//多站点判断
	    if (!empty($_SESSION['index_id'])) {
	    	$this->add('sites_str','站点：'.str_replace('全部', '请选择站点',$this->Discount_model->select_sites()));
	    }

         //获取层级
        $map = array();
        $map['table'] = 'k_user_level';
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['index_id'] = $index_id;
        $map['where']['is_delete'] = 0;
        $map['order'] = 'id DESC';
        $level_data = $this->Discount_model->rget($map);
        //获取返点数据
    	$map = array();
    	$map['table'] = 'k_user_discount_set';
    	$map['where']['site_id'] = $_SESSION['site_id'];
    	$map['where']['index_id'] = $index_id;
        $map['where']['level_id'] = $level_id;
		$map['where']['is_delete'] = 0;
		$map['order'] = 'count_bet DESC';

        $data = $this->Discount_model->rget($map);
           //获取视讯配置
        $viedo_config = array();
        $vdata = $this->Discount_model->get_video_config();
        if ($vdata) {
            foreach ($vdata as $key => $val) {
                if($val == 'mg'){           //电子视讯都有
                    $video_config[$key]['vtype'] = $val.'_discount';
                    $video_config[$key]['vtitle'] = strtoupper($val).'視訊';
                    $video_config[$key]['dtype'] = $val.'dz_discount';
                    $video_config[$key]['dtitle'] = strtoupper($val).'電子';
                }elseif ($val == 'bbin') {                //电子与视讯拼接字段不一致
                    $video_config[$key]['vtype'] = 'bbin_discount';
                    $video_config[$key]['vtitle'] = 'BB視訊';
                    $video_config[$key]['dtype'] = 'bbdz_discount';
                    $video_config[$key]['dtitle'] = 'BB電子';
                    $video_config[$key]['stype'] = 'bbsp_discount';
                    $video_config[$key]['stitle'] = 'BB体育';
                    $video_config[$key]['ftype'] = 'bbfc_discount';
                    $video_config[$key]['ftitle'] = 'BB彩票';
                }elseif ($val == 'pt') {                  //只有电子
                    $video_config[$key]['dtype'] = 'pt_discount';
                    $video_config[$key]['dtitle'] = strtoupper($val).'電子';
                }elseif ($val == 'eg') {                  //只有电子
                    $video_config[$key]['dtype'] = 'eg_discount';
                    $video_config[$key]['dtitle'] = strtoupper($val).'電子';
                }else{                                   //只有视讯
                    $video_config[$key]['vtype'] = $val.'_discount';
                    $video_config[$key]['vtitle'] = strtoupper($val).'視訊';
                }
            }
        }

        $this->add('video_config',$video_config);
		$this->add('data',$data);
    	$this->add('index_id',$index_id);
        $this->add('level_id',$level_id);
        $this->add('site_id',$_SESSION['site_id']);
        $this->add('level_data',$level_data);
        $this->display('cash/discount_fd.html');
    }

    //返点优惠设定
    public function discount_fd_add(){
    	$index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?'a':$index_id;
    	$id = $this->input->get('id');

              //获取层级
        $map = array();
        $map['table'] = 'k_user_level';
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['index_id'] = $index_id;
        $map['where']['is_delete'] = 0;
        $map['order'] = 'id DESC';
        $level_data = $this->Discount_model->rget($map);
        $this->add('level_data',$level_data);

    	if (!empty($id)) {
    	    //编辑
    	    $edit = $this->Discount_model->rfind(array('table'=>'k_user_discount_set','where'=>array('id'=>$id,'site_id'=>$_SESSION['site_id'])));
    	    $this->add('edit',$edit);
    	}else{
    		//多站点判断
		    if (!empty($_SESSION['index_id'])) {
		    	$this->add('sites_str',str_replace('全部', '请选择站点',$this->Discount_model->select_sites()));

	    	}
	    }

              //获取视讯配置
        $viedo_config = array();
        $vdata = $this->Discount_model->get_video_config();
        if ($vdata) {
            foreach ($vdata as $key => $val) {
                if($val == 'mg'){         //电子视讯都有
                    $video_config[$key]['vtype'] = $val.'_discount';
                    $video_config[$key]['vtitle'] = strtoupper($val).'視訊優惠';
                    $video_config[$key]['dtype'] = $val.'dz_discount';
                    $video_config[$key]['dtitle'] = strtoupper($val).'電子優惠';
                }elseif ($val == 'bbin') {              //电子与视讯拼接字段不一致
                    $video_config[$key]['vtype'] = 'bbin_discount';
                    $video_config[$key]['vtitle'] = 'BBIN視訊優惠';
                    $video_config[$key]['dtype'] = 'bbdz_discount';
                    $video_config[$key]['dtitle'] = 'BB電子優惠';
                    $video_config[$key]['stype'] = 'bbsp_discount';
                    $video_config[$key]['stitle'] = 'BB体育優惠';
                    $video_config[$key]['ftype'] = 'bbfc_discount';
                    $video_config[$key]['ftitle'] = 'BB彩票優惠';
                }elseif ($val == 'pt') {              //只有电子
                    $video_config[$key]['dtype'] = $val.'_discount';
                    $video_config[$key]['dtitle'] = strtoupper($val).'電子優惠';
                }elseif ($val == 'eg') {              //只有电子
                    $video_config[$key]['dtype'] = $val.'_discount';
                    $video_config[$key]['dtitle'] = strtoupper($val).'電子優惠';
                }else{                                //只有视讯
                    $video_config[$key]['vtype'] = $val.'_discount';
                    $video_config[$key]['vtitle'] = strtoupper($val).'視訊優惠';
                }
            }
        }

        $this->add('video_config',$video_config);
        $this->add('index_id',$index_id);
        $this->add('site_id',$_SESSION['site_id']);
        $this->display('cash/discount_fd_add.html');
    }

    //返点优惠处理
    public function discount_fd_add_do(){
    	$id = $this->input->post('id');
        $index_id = $this->input->post('index_id');
        $index_id = empty($index_id)?'a':$index_id;
        $level_id = $this->input->post('level_id');
        $level_id = empty($level_id)?0:$level_id;

        $data['level_id'] = $level_id;
    	$data['count_bet'] = $this->input->post('count_bet');//有效投注
    	$data['fc_discount'] = $this->input->post('fc_discount');
    	$data['sp_discount'] = $this->input->post('sp_discount');
    	$data['mg_discount'] = $this->input->post('mg_discount');
        $data['mgdz_discount'] = $this->input->post('mgdz_discount');
    	$data['ag_discount'] = $this->input->post('ag_discount');
    	$data['bbin_discount'] = $this->input->post('bbin_discount');
        $data['bbdz_discount'] = $this->input->post('bbdz_discount');
        $data['bbsp_discount'] = $this->input->post('bbsp_discount');
        $data['bbfc_discount'] = $this->input->post('bbfc_discount');
    	$data['lebo_discount'] = $this->input->post('lebo_discount');
    	$data['og_discount'] = $this->input->post('og_discount');
    	$data['ct_discount'] = $this->input->post('ct_discount');
        $data['pt_discount'] = $this->input->post('pt_discount');//pt电子
        $data['eg_discount'] = $this->input->post('eg_discount');//pt电子
        $data['liuhecai_discount'] = $this->input->post('liuhecai_discount');//六合彩
    	$data['max_discount'] = $this->input->post('max_discount');

    	if (empty($data['max_discount']) || empty($data['count_bet'])) {
    	    showmessage('数据不能为空!',URL.'/cash/discount/discount_fd',0);
    	}

    	if (empty($id)) {
    	    //添加
    	    $data['index_id'] = $index_id;
    	    $data['site_id'] = $_SESSION['site_id'];
    	    if ($this->Discount_model->radd('k_user_discount_set',$data)) {
    		    $log['log_info'] = '添加优惠返水比例';
	            $this->Discount_model->Syslog($log);
                showmessage('添加成功!',URL.'/cash/discount/discount_fd');
    		}else{
    			showmessage('添加失败!',URL.'/cash/discount/discount_fd',0);
    		}
    	}else{
    		$map = array();
    		$map['table'] = 'k_user_discount_set';
    		$map['where']['id'] = $id;
    		if ($this->Discount_model->rupdate($map,$data)) {
    		    $log['log_info'] = '更新优惠返水比例,ID:'.$id;
	            $this->Discount_model->Syslog($log);
                showmessage('编辑成功!',URL.'/cash/discount/discount_fd');
    		}else{
    			showmessage('编辑失败!',URL.'/cash/discount/discount_fd',0);
    		}
    	}
    }

    //删除优惠设定
    public function discount_fd_del(){
        $id = $this->input->get('id');
        if (empty($id)) {
            showmessage('参数错误!',URL.'/cash/discount/discount_fd',0);
        }

        if ($this->Discount_model->rdel('k_user_discount_set',array('id'=>$id))) {
		    $log['log_info'] = '删除返水比例';
            $this->Discount_model->Syslog($log);
            showmessage('删除成功!',URL.'/cash/discount/discount_fd');
    	}else{
    		showmessage('删除失败!',URL.'/cash/discount/discount_fd',0);
    	}
    }

    //会员返水明细
    public function discount_user_list(){
    	$id = $this->input->get('id');
    	if (empty($id)) {
    	    showmessage('参数错误!',URL.'/cash/discount/index',0);
    	}

    	$map = array();
    	$map['site_id'] = $_SESSION['site_id'];
    	$map['kds_id'] = $id;

    	$datas = $this->Discount_model->get_discount_user_list($map);
    	// foreach ($datas as $key => $val) {
    	//     if ($val['state'] == 1) {
    	//         $datas[$key]['state_zh'] = '返水完成';
    	//     }else{
    	//     	$datas[$key]['state_zh'] = '优惠冲销';
    	//     	$datas[$key]['cx_zh'] = '<font class="color_cx">冲销</font>';
    	//     }
    	//     $uJson = json_encode($val, JSON_UNESCAPED_UNICODE);
     //        $datas[$key]['uJson'] = str_replace('"', '-', $uJson);
    	// }

                       //读取视讯配置
        $videoc = $this->Discount_model->M(array('tab'=>'web_config','type'=>1))->where("site_id = '".$_SESSION['site_id']."'")->getField('video_module');
        $video_c = explode(',',$videoc);
        foreach ($video_c as $k => $v) {
            $video_types[] = $v;
            if ($v == 'mg') {
                $video_types[] = 'mgdz';
            }elseif($v == 'bbin'){
                $video_types[] = 'bbdz';
                $video_types[] = 'bbsp';
                $video_types[] = 'bbfc';
            }
        }

        $title_type = array('sp'=>'体育','fc'=>'彩票','mg'=>'MG视讯','mgdz'=>'MG电子','ag'=>'AG视讯','og'=>'OG视讯','ct'=>'CT视讯','lebo'=>'LEBO视讯','bbin'=>'BB視訊','bbdz'=>'BB电子','bbsp'=>'BB体育','bbfc'=>'BB彩票','pt'=>'PT电子','eg'=>'EG电子');

        //type
        $vtype = array_merge(array('sp','fc'), $video_types);
        //css合并数量
        $this->add('inum',count($vtype));

        $bettypes = $fdtypes = array();
        foreach ($datas as $key => $val) {
            if ($val['state'] == 1) {
                $datas[$key]['state_zh'] = '返水完成';
            }else{
                $datas[$key]['state_zh'] = '优惠冲销';
                $datas[$key]['cx_zh'] = '<font class="color_cx">冲销</font>';
            }
            $uJson = json_encode($val, JSON_UNESCAPED_UNICODE);
            $datas[$key]['uJson'] = str_replace('"', '-', $uJson);

            foreach ($vtype as $k => $v) {
                $k_bet = $v.'_bet';
                $k_fd = $v.'_fd';

                $totalb[$k_bet] += $val[$k_bet];
                $totalf[$k_fd] += $val[$k_fd];
            }
        }

        foreach ($vtype as $k => $v) {
            $bettypes[] = $v.'_bet';
            $fdtypes[] = $v.'_fd';
        }

        $this->add('title_type',$title_type);
        $this->add('iNum',count($userIds));
        $this->add("vtype",$vtype);
        $this->add('bettypes',$bettypes);
        $this->add('fdtypes',$fdtypes);
        $this->add('totalb',$totalb);
        $this->add('totalf',$totalf);



        $map_t = array();
        $map_t['table'] = 'k_user_discount_search';
        $map_t['where']['id'] = $id;
        $map_t['where']['site_id'] = $_SESSION['site_id'];
        $this->add('title',$this->Discount_model->rfind($map_t));
        $this->add('datas',$datas);
        $this->add('id',$id);
        $this->add('site_id',$_SESSION['site_id']);
        $this->display('cash/discount_user_list.html');
    }

    //优惠冲销
    public function discount_cx(){
        $id = $this->input->post('id');
        $sid = $this->input->post('sid');
        $title = $this->input->post('title');
        $zh = $this->input->post('zh');//是否有综合打码
        if (empty($id) || empty($sid)) {
    	    showmessage('参数错误!',URL.'/cash/discount/discount_log',0);
    	}

    	if ($this->Discount_model->discount_cx($id,$sid,$title,$zh)) {
		    $log['log_info'] = '优惠冲销';
            $this->Discount_model->Syslog($log);
            showmessage('优惠冲销成功!',URL.'/cash/discount/discount_log');
    	}else{
    		showmessage('优惠冲销失败!',URL.'/cash/discount/discount_log',0);
    	}
    }

    //会员自助返水查询
    public function rakeback_log(){
        $date['start_date'] = $this->input->get('date_start');
		$date['end_date'] = $this->input->get('date_end');
		if (empty($date['start_date']) || empty($date['end_date'])) {
		    $date['start_date'] = $date['end_date'] = date('Y-m-d');
		}
        $site_id = $_SESSION['site_id'];
        $index_id = $this->input->get('index_id');
        $index_id = empty($index_id) ? 'a' : $index_id;
        $username = $this->input->get('username');
        $order = $this->input->get('order');

        //多站点判断
        if (!empty($_SESSION['index_id'])) {
            $this->add('sites_str',str_replace('全部', '请选择站点',$this->Discount_model->select_sites()));
        }

        $data = $this->Discount_model->get_self_fd($site_id,$date,$index_id,$username,$order);

        $this->add('start_date',$date['start_date']);
        $this->add('end_date',$date['end_date']);
        $this->add('site_id', $_SESSION['site_id']);
        $this->add('index_id',$index_id);
        $this->add('data', $data);
        $this->display('cash/rakeback_log.html');
    }

    public function user_self_fd_list(){
        $uid = $this->input->get('id');
        if (empty($uid)) {
            showmessage('参数错误!',URL.'/cash/discount/index',0);
        }
        $title['back_time_start'] = $this->input->get('date_start');
        $title['back_time_end'] = $this->input->get('date_end');
        $data = $this->Discount_model->get_user_fd_list($uid,$title);
        $title['fd_num'] = count($data);
        //读取视讯配置
        $videoc = $this->Discount_model->M(array('tab'=>'web_config','type'=>1))->where("site_id = '".$_SESSION['site_id']."'")->getField('video_module');
        $video_c = explode(',',$videoc);
        foreach ($video_c as $k => $v) {
            $video_types[] = $v;
            if ($v == 'mg') {
                $video_types[] = 'mgdz';
            }elseif($v == 'bbin'){
                $video_types[] = 'bbdz';
                $video_types[] = 'bbsp';
                $video_types[] = 'bbfc';
            }
        }

        $title_type = array('sp'=>'体育','fc'=>'彩票','mg'=>'MG视讯','mgdz'=>'MG电子','ag'=>'AG视讯','og'=>'OG视讯','ct'=>'CT视讯','lebo'=>'LEBO视讯','bbin'=>'BB視訊','bbdz'=>'BB电子','bbsp'=>'BB体育','bbfc'=>'BB彩票','pt'=>'PT电子','eg'=>'EG电子');

        //type
        $vtype = array_merge(array('sp','fc'), $video_types);
        //css合并数量
        $this->add('inum',count($vtype));

        foreach ($data as $key => $val) {
            foreach ($vtype as $k => $v) {
                $k_bet = $v.'_bet';
                $k_fd = $v.'_fd';

                $totalb[$k_bet] += $val[$k_bet];
                $totalf[$k_fd] += $val[$k_fd];
            }
            $title['totalbet'] += $val['all_bet'];
            $title['totalfd'] += $val['total_e_fd'];
        }

        foreach ($vtype as $k => $v) {
            $bettypes[] = $v.'_bet';
            $fdtypes[] = $v.'_fd';
        }

        $this->add('site_id', $_SESSION['site_id']);
        $this->add('title',$title);
        $this->add('title_type',$title_type);
        $this->add("vtype",$vtype);
        $this->add('bettypes',$bettypes);
        $this->add('fdtypes',$fdtypes);
        $this->add('totalb',$totalb);
        $this->add('totalf',$totalf);
        $this->add('data', $data);
        $this->display('cash/rakeback_userself_list.html');
    }

}

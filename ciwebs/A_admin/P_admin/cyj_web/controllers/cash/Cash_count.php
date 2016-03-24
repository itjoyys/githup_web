<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cash_count extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('cash/Cash_count_model');
                $this->load->model('note/Note_model');
	}
        
    public function index() {
        $this->add('now_week', date("Y-m-d", strtotime("last  Monday")));
        $this->add('now_date', date('Y-m-d'));
        $this->add('now_date_12', date("Y-m-d", strtotime("-12 hour")));
        $this->add('now_week_l', date("Y-m-d", strtotime("last Monday -1 week")));
        $this->add('timearea', $timearea);
        $this->add('now_week_n', date("Y-m-d", strtotime("last Sunday")));
        $s_date = $e_date = date("Y-m-d");
        $this->add('s_date', $s_date);
        $this->add('e_date', $e_date);
        //多站点判断
        if (!empty($_SESSION['index_id'])) {
            $this->add('sites_str', '站点：' . $this->Cash_count_model->select_sites());
        }
        $this->display('cash/cash_count.html');
    }

    public function ajax_index() {
        //时间条件
        $start_date = $this->input->get('date_start');
        $end_date = $this->input->get('date_end');
        $timearea = $this->input->get('timearea'); //时区
        $index_id = $this->input->get('index_id'); //站点切换
        $username = $this->input->get('username'); //会员账号

        if (empty($timearea)) {
            $timearea = 0;
        }
        if (!empty($start_date)) {
            $s_date = $start_date;
        } else {
            $s_date = $start_date = date("Y-m-d");
        }
        if (!empty($end_date)) {
            $e_date = $end_date;
        } else {
            $e_date = $end_date = date("Y-m-d");
        }

        //查询时间判断
        about_limit($end_date, $start_date);

        $start_date = strtotime($start_date . ' 00:00:00') - $timearea * 3600;
        $end_date = strtotime($end_date . ' 23:59:59') - $timearea * 3600;

        $start_date = date('Y-m-d H:i:s', $start_date);
        $end_date = date('Y-m-d H:i:s', $end_date);

        //公司入款 线上入款
        $map_1['do_time'] = array(array('<=', $end_date), array('>=', $start_date));
        $map_1['site_id'] = $_SESSION['site_id'];
        $map_1['into_style'] = 1;
        $map_1['make_sure'] = 1;



        //人工存入 人工提出
        $map_catm['updatetime'] = array(array('<=', $end_date), array('>=', $start_date));
        $map_catm['site_id'] = $_SESSION['site_id'];
        $map_catm['type'] = 1;
        $map_catm['catm_type'] = array('in', '(1,4,6)');

        //会员出款被扣
        $map_out = "site_id = '" . $_SESSION['site_id'] . "' and out_status = '1' and (charge+favourable_num+expenese_num) > '0' and out_time >= '" . $start_date . "' and out_time <= '" . $end_date . "'";

        //会员出款
        $map_oua = "site_id = '" . $_SESSION['site_id'] . "' and out_status = '1' and out_time >= '" . $start_date . "' and out_time <= '" . $end_date . "'";


        //给予返水
        $map_f = array();
        $map_f['site_id'] = $_SESSION['site_id'];
        $map_f['state'] = 1;
        $map_f['do_time'] = array(array('<=', $end_date), array('>=', $start_date));

        //给予优惠
        $map_fav = "site_id = '" . $_SESSION['site_id'] . "' and discount_num > 0 and cash_date >= '" . $start_date . "' and cash_date <= '" . $end_date . "' and (cash_only = 1 or cash_type in (10,11,12))";

        if (!empty($index_id)) {
            $map_1['index_id'] = $index_id;
            $map_catm['index_id'] = $index_id;

            $map_f['index_id'] = $index_id;

            $map_fav .= ' and index_id = "' . $index_id . '"';
            $map_oua .= ' and index_id = "' . $index_id . '"';
            $map_out .= ' and index_id = "' . $index_id . '"';
            $this->add('index_id', $index_id);
        }

        //添加会员账号
        if (!empty($username)) {
            $map_1['username'] = $username;
            $map_catm['username'] = $username;
            $map_f['username'] = $username;

            $map_fav .= " and username = '" . $username . "' ";
            $map_oua .= " and username = '" . $username . "' ";
            $map_out .= " and username = '" . $username . "' ";
        }


        $income_line_data = $this->income_line($map_1);
        unset($map_1['do_time']);
        $map_1['in_date'] = array(array('<=', $end_date), array('>=', $start_date));
        $income_ol_data = $this->income_ol($map_1);
        $income_catm_data = $this->income_catm($map_catm);
        $income_catm_odata = $this->income_catm_o($map_catm);
        $take_off_data = $this->take_off($map_out);
        $user_out_money = $this->user_out_money($map_oua);
        //$dis_count_data = $this->discount_count($map_f);
        $dis_count_data = $this->Cash_count_model->discount_count_user($map_f);
        $discount_fav = $this->discount_fav($map_fav);
        //账目统计
        $all_own_money = $income_line_data['deposit_num']//公司入款
            + $income_ol_data['deposit_num']//线上支付
            + $income_catm_data['catm_money']///人工存入
            + $take_off_data['charge'] + $take_off_data['expenese_num'] + $take_off_data['favourable_num']//会员出款被扣
            - $user_out_money['outward_money'] //会员出款
            - $dis_count_data['money']//返水
            - $income_catm_odata['catm_money']//人工提出
            - $discount_fav['fav_money']; //给予优惠
        //实际盈亏
        $yinkui_money = $income_line_data['deposit_num'] + $income_ol_data['deposit_num'] + $income_catm_data['catm_money'] - $user_out_money['outward_money']  //会员出款
            - $income_catm_odata['catm_money']; //人工出款
        //各项链接
        $bank_line_inurl = URL . "/cash/bank_record/index?status=1&start_date=$s_date&end_date=$e_date&timearea=$timearea&index_id=$index_id&into_style=1&account=$username";
        $bank_ol_inurl = URL . "/cash/bank_record/index?status=1&start_date=$s_date&end_date=$e_date&timearea=$timearea&index_id=$index_id&into_style=2&account=$username";
        $out_url = URL . "/cash/out_record/index?out_status=1&start_date=$s_date&end_date=$e_date&timearea=$timearea&index_id=$index_id&account=$username";
        $catm_url = URL . "/cash/catm/catm_record?otype=1-1-4-6&start_date=$s_date&end_date=$e_date&timearea=$timearea&index_id=$index_id&username=$username";
        $catmM_url = URL . "/cash/catm/catm_record?otype=2&start_date=$s_date&end_date=$e_date&timearea=$timearea&index_id=$index_id&username=$username";
        $fd_url = URL . "/cash/Cash_count/fd_user_list?sdate=$s_date&edate=$e_date&timearea=$timearea&index_id=$index_id&username=$username";
        $promotion_o_url = URL . "/cash/Cash_count/promotion_list?sdate=$s_date&edate=$e_date&timearea=$timearea&index_id=$index_id&username=$username";

        $str = "合计:" . $take_off_data['outNum'] . "笔/" . $take_off_data['userNum'] . "人_" . strval($take_off_data['charge']) . "_" . strval($take_off_data['favourable_num']) . "_" . strval($take_off_data['expenese_num']) . "_" . ($take_off_data['charge'] + $take_off_data['expenese_num'] + $take_off_data['favourable_num']);
        $map_out_url = URL . "/cash/cash_count/map_out_list?sdate=$s_date&edate=$e_date&timearea=$timearea&index_id=$index_id&str=$str&username=$username";


        $tds = array();
        //公司入款
        $tds[0] = "<a href='" . $bank_line_inurl . "'><font class='in_font'>" . ($income_line_data['deposit_num']+0) ."</font></a>(" . ($income_line_data['countNum']+0) . "笔)(". ($income_line_data['userNum']+0) ."人)";
        //$tds[0] = "<a href='www.baidu.com'><font class='in_font'>1</font></a>(1笔)(1人)";
        //会员出款
        $tds[1] = "<a href=$out_url>" . ($user_out_money['outward_money']+0) ."</a>(" . ($user_out_money['outNum']+0) ."笔)(" .($user_out_money['userNum']+0) ."人)";
        //线上支付
        $tds[2] = "<a href=$bank_ol_inurl><font class='in_font'>" . ($income_ol_data['deposit_num']+0) . "</font></a>(" . ($income_ol_data['countNum']+0) ."笔)(" . ($income_ol_data['userNum']+0) . "人)";
        //给予优惠
        $tds[3] = "<a href=$promotion_o_url><font class='in_font'>" . ($discount_fav['fav_money']+0) . "</font></a>(" . ($discount_fav['fav_num']+0) ."笔)(" . ($discount_fav['userNum']+0) . "人)";
        //人工存入
        $tds[4] = "<a href=$catm_url><font class='in_font'>" . ($income_catm_data['catm_money']+0) . "</font></a>(" . ($income_catm_data['catmNum']+0) . "笔)(" . ($income_catm_data['userNum']+0) . "人)";
        //人工提出
        $tds[5] = "<a href=$catmM_url>" . ($income_catm_odata['catm_money']+0) . "</a>(" . ($income_catm_odata['catmNum']+0) . "笔)(" . ($income_catm_odata['userNum']+0) . "人)";
        //会员出款被扣金额
        $tds[6] = "<font class='in_font'><a href='$map_out_url'>" . ($take_off_data['charge'] + $take_off_data['expenese_num'] + $take_off_data['favourable_num']+0) . "</a></font>(" . ($take_off_data['outNum']+0) . "笔) (" . ($take_off_data['userNum']+0) . "人)";
        //给予反水
        $tds[7] = "<a href=$fd_url>" . ($dis_count_data['money']+0). "</a>(" . ($dis_count_data['people_num']+0) . "笔)(" . ($dis_count_data['userNum']+0) . "人)";
        //
        $tds[8] = "实际盈亏：$yinkui_money&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;账目统计：$all_own_money";
        
        echo json_encode($tds);
    }
    
	//公司入款
	function income_line($map){
       return  $this->Cash_count_model->income_line($map);
	}

	//线上入款
	function income_ol($map){
	   $map['into_style'] = 2;
	   return  $this->Cash_count_model->income_line($map);
	}

	//人工存入
	function income_catm($map){
        return  $this->Cash_count_model->income_catm($map);
	}
    //人工提出
	function income_catm_o($map){
		$map['type'] = 2;
        unset($map['catm_type']); //去掉对应条件
        return  $this->Cash_count_model->income_catm($map);
	}
    //会员出款
	function user_out_money($map){
        return  $this->Cash_count_model->user_out_money($map);
	}

	 //会员出款被扣
	function take_off($map){
        return  $this->Cash_count_model->take_off($map);
	}

	//给予返水
	function discount_count($map){
		$data = array();
		$data[] = $this->Cash_count_model->discount_count($map);
		//给予返水的人数
		$discIds =  $this->Cash_count_model->discount_user($map);
		if (!empty($discIds)) {
			$discIds = '(' . implode(',', i_array_column($discIds, 'id')) . ')';
			$map_dp = array();
			$map_dp['kds_id'] = array('in', $discIds);
	        $map_dp['state'] = 1;
			$data[] =  $this->Cash_count_model->discount_count_user($map_dp);
		}
		return $data;
	}

	function discount_fav($map){
         return  $this->Cash_count_model->discount_fav($map);
	}

		//给予返水 详情
	public function fd_user_list(){
		$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		$timearea = $this->input->get('timearea');//时区
		$timearea = empty($timearea)?0:$timearea;
		$username = $this->input->get('username');

		$sdate = strtotime($sdate . ' 00:00:00') - $timearea * 3600;
		$edate = strtotime($edate . ' 23:59:59') - $timearea * 3600;
		$sdate = date('Y-m-d H:i:s', $sdate);
        $edate = date('Y-m-d H:i:s', $edate);
		$index_id = $this->input->get('index_id');//多站点

		$db_model = array();
	    $db_model['tab'] = 'k_user_discount_count';
	    $db_model['type'] = 1;

	    $map = array();
	    $map['site_id'] = $_SESSION['site_id'];
	    $map['state'] = 1;
	    $map['do_time'] = array(array('>=',$sdate),array('<=',$edate));
	    if (!empty($index_id)) {
	        $map['index_id'] = $index_id;
	    }
	    if (!empty($username)) {
	        $map['username'] = $username;
	    }
	    $list = $this->Cash_count_model->M($db_model)->field("id,uid,username,site_id,level_des,agent_user,agent_id,username,sum(sp_fd) as sp_fd,sum(fc_fd) as fc_fd,sum(ag_fd) as ag_fd,sum(og_fd) as og_fd,sum(mg_fd) as mg_fd,sum(mgdz_fd) as mgdz_fd,sum(ct_fd) as ct_fd,sum(bbin_fd) as bbin_fd,sum(bbdz_fd) as bbdz_fd,sum(lebo_fd) as lebo_fd,sum(total_e_fd) as total_e_fd,state,do_time")->where($map)->group("uid")->select();
        $tot_sp_fd = $tot_fc_fd = $tot_ag_fd = $tot_og_fd = $tot_mg_fd = 0;
        $tot_ct_fd = $tot_lebo_fd = $tot_bbin_fd = $tot_all_fd = 0;
        $tot_mgdz_fd = $tot_bbdz_fd = 0;
        $videos = $this->Note_model->get_videos();
        $this->add('videos',$videos);
        $video_list_arr = $video_sum_arr = $video_title_arr = array();
        foreach ($list as $key => $val) {
            $tot_sp_fd += $val['sp_fd'];
            $tot_fc_fd += $val['fc_fd'];
            $tot_ag_fd += $val['ag_fd'];
            $tot_og_fd += $val['og_fd'];
            $tot_mg_fd += $val['mg_fd'];
            $tot_mgdz_fd += $val['mgdz_fd'];
            $tot_ct_fd += $val['ct_fd'];
            $tot_lebo_fd += $val['lebo_fd'];
            $tot_bbin_fd += $val['bbin_fd'];
            $tot_bbdz_fd += $val['bbdz_fd'];
            $tot_all_fd += $val['total_e_fd'];
        }
        if(in_array('ag',$videos)){
            $video_list_arr[$k][0] = $v['ag_fd'];
            $video_title_arr[0] = 'AG視訊';
            $video_sum_arr[0] =  $tot_ag_fd;
        }
        if(in_array('og',$videos)){
            $video_title_arr[3] = 'OG視訊';
            $video_sum_arr[3] = $tot_og_fd;
        }
        if(in_array('mg',$videos)){
            $video_title_arr[1] = 'MG視訊';
            $video_title_arr[2] = 'MG电子';
            $video_sum_arr[1] = $tot_mg_fd;
            $video_sum_arr[2] = $tot_mgdz_fd;
        }
        if(in_array('ct',$videos)){
            $video_title_arr[4] = 'CT視訊';
            $video_sum_arr[4] = $tot_ct_fd;
        }
        if(in_array('lebo',$videos)){
            $video_title_arr[5] = 'LEBO視訊';
            $video_sum_arr[5] = $tot_lebo_fd;
        }
        if(in_array('bbin',$videos)){
            $video_title_arr[6] = 'BBIN視訊';
            $video_title_arr[7] = 'BBIN电子';
            $video_sum_arr[6] = $tot_bbin_fd;
            $video_sum_arr[7] = $tot_bbdz_fd;
        }
        foreach ($list as $k => $v) {
            if(in_array('ag',$videos)){
                $video_list_arr[$k][0] = $v['ag_fd'];
            }
            if(in_array('og',$videos)){
                $video_list_arr[$k][3] = $v['og_fd'];
            }
            if(in_array('mg',$videos)){
                $video_list_arr[$k][1] = $v['mg_fd'];
                $video_list_arr[$k][2] = $v['mgdz_fd'];
            }
            if(in_array('ct',$videos)){
                $video_list_arr[$k][4] = $v['ct_fd'];
            }
            if(in_array('lebo',$videos)){
                $video_list_arr[$k][5] = $v['lebo_fd'];
            }
            if(in_array('bbin',$videos)){
                $video_list_arr[$k][6] = $v['bbin_fd'];
                $video_list_arr[$k][7] = $v['bbdz_fd'];
            }
        }
        $this->add('video_list_arr',$video_list_arr);
        $this->add('video_title_arr',$video_title_arr);
        $this->add('video_sum_arr',$video_sum_arr);
        $this->add('list',$list);
        $this->add('tot_sp_fd',$tot_sp_fd);
        $this->add('tot_fc_fd',$tot_fc_fd);
        $this->add('tot_ag_fd',$tot_ag_fd);
        $this->add('tot_og_fd',$tot_og_fd);
        $this->add('tot_mg_fd',$tot_mg_fd);
        $this->add('tot_mgdz_fd',$tot_mgdz_fd);
        $this->add('tot_ct_fd',$tot_ct_fd);
        $this->add('tot_lebo_fd',$tot_lebo_fd);
        $this->add('tot_bbin_fd',$tot_bbin_fd);
        $this->add('tot_bbdz_fd',$tot_bbdz_fd);
        $this->add('tot_all_fd',$tot_all_fd);
        $this->display('cash/fd_user_list.html');
	}
        //会员出款扣钱 详情
	public function map_out_list(){
        $str = $this->input->get('str');
        //$str = explode('_', $str);
        $sdate = $this->input->get('sdate');
        $edate = $this->input->get('edate');
        $timearea = $this->input->get('timearea');//时区
        $timearea = empty($timearea)?0:$timearea;
        $username = $this->input->get('username');

        $sdate = strtotime($sdate . ' 00:00:00') - $timearea * 3600;
        $edate = strtotime($edate . ' 23:59:59') - $timearea * 3600;
        $sdate = date('Y-m-d H:i:s', $sdate);
        $edate = date('Y-m-d H:i:s', $edate);
        $index_id = $this->input->get('index_id');//多站点
        //var_dump($sdate,$edate,$timearea,$index_id);die;
        $db_model = array();
	    $db_model['tab'] = 'k_user_bank_out_record';
	    $db_model['type'] = 1;

	    $map = array();
	    $map['site_id'] = $_SESSION['site_id'];
	    $map['out_status'] = 1;
            $map['(charge+favourable_num+expenese_num)']=array(array('>',0));
	    $map['out_time'] = array(array('>=',$sdate),array('<=',$edate));
	    if (!empty($index_id)) {
	        $map['index_id'] = $index_id;
	    }
	    if (!empty($username)) {
	        $map['username'] = $username;
	    }
	    $list = $this->Cash_count_model->M($db_model)->field("id,username,agent_user,sum(charge) charge,sum(favourable_num) favourable_num,sum(expenese_num) expenese_num,sum(charge+favourable_num+expenese_num) as money4")->where($map)->group('uid')->select();
            //p($str);die;
            $str=  "<td colspan='2'>".str_replace("_", "</td><td>", $str)."</td>";
            $this->add('list',$list);
            $this->add('str',$str);
            $this->display('cash/map_out_list.html');
	}

	/**
	 * 给予优惠--详情
	 */
	public function promotion_list(){
		$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		$timearea = $this->input->get('timearea');//时区
		$timearea = empty($timearea)?0:$timearea;
		$username = $this->input->get('username');

		$sdate = strtotime($sdate . ' 00:00:00') - $timearea * 3600;
		$edate = strtotime($edate . ' 23:59:59') - $timearea * 3600;
		$sdate = date('Y-m-d H:i:s', $sdate);
        $edate = date('Y-m-d H:i:s', $edate);
		$index_id = $this->input->get('index_id');//多站点

        //获取代理商账号
		$db_model['tab'] = 'k_user_agent';
		$db_model['type'] = 1;
		$agent = $this->Cash_count_model->M($db_model)->field("agent_user,id")->where(array('site_id'=>$_SESSION['site_id'],'is_demo'=>0,'agent_type'=>'a_t'))->select('id');

		$map = '';
		$map = "site_id = '" . $_SESSION['site_id'] . "' and discount_num > 0 and cash_date >= '" . $sdate . "' and cash_date <= '" . $edate . "' and (cash_only = 1 or cash_type in (10,11,12))";
		if (!empty($index_id)) {
	        $map .= " and index_id = '" . $index_id . "' ";
	    }
	    if (!empty($username)) {
	        $map .= " and username = '".$username."' ";
	    }
		$data = $this->Cash_count_model->discount_list($map);
        $pm_count = 0;
		foreach ($data as $k => $val) {
			$pm_count += $val['pm_money'];
            $data[$k]['agent_user'] = $agent[$val['agent_id']]['agent_user'];
		}
		$this->add('pm_count',$pm_count);
		$this->add('listData',$data);
		$this->display('cash/promotion_user_list.html');
	}

}

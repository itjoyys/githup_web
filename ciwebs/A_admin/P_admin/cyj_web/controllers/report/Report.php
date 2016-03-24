<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->login_check();
        $this->load->model('report/Report_model');
    }
    //报表
    public function index(){

        //彩票结果
        $map_fc = array();
        $fc_data=array();
        $map_fc['site_id'] = $_SESSION['site_id'];
        $map_fc['js'] = 1; //有结果
        $fc_data["today_yes"] = $this->Report_model->report_fc($map_fc,0);//今天
        $fc_data["yestoday_yes"] = $this->Report_model->report_fc($map_fc,1);//昨天
        $map_fc['js'] = 0; //无结果
        $fc_data["today_no"] = $this->Report_model->report_fc($map_fc,0);//今天
        $fc_data["yestoday_no"] = $this->Report_model->report_fc($map_fc,1);//昨天

        //体育结果
        $map_fc = array();
        $sport_data=array();
        $map_fc['site_id'] = $_SESSION['site_id'];
        $map_fc['is_jiesuan'] = 1; //有结果
        $sport_data["today_yes"] = $this->Report_model->report_fc($map_fc,0,"bet_time","k_bet","bid");//今天
        $sport_data["yestoday_yes"] = $this->Report_model->report_fc($map_fc,1,"bet_time","k_bet","bid");//昨天
        $map_fc['is_jiesuan'] = 0; //无结果
        $sport_data["today_no"] = $this->Report_model->report_fc($map_fc,0,"bet_time","k_bet","bid");//今天
        $sport_data["yestoday_no"] = $this->Report_model->report_fc($map_fc,1,"bet_time","k_bet","bid");//昨天

        //体育串关结果
        $map_fc = array();
        $spcinfoy_data=array();
        $map_fc['site_id'] = $_SESSION['site_id'];
        $map_fc['is_jiesuan'] = 1; //有结果
        $spcinfoy_data["today_yes"] = $this->Report_model->report_fc($map_fc,0,"bet_time","k_bet_cg_group","gid");//今天
        $spcinfoy_data["old_yes"] = $this->Report_model->report_fc($map_fc,1,"bet_time","k_bet_cg_group","gid");//昨天
        $map_fc['is_jiesuan'] = 0; //无结果
        $spcinfoy_data["today_no"] = $this->Report_model->report_fc($map_fc,0,"bet_time","k_bet_cg_group","gid");//今天
        $spcinfoy_data["old_no"] = $this->Report_model->report_fc($map_fc,1,"bet_time","k_bet_cg_group","gid");//昨天

           //期数缓存redis

        //读取视讯配置
        $videoc = $this->Report_model->M(array('tab'=>'web_config','type'=>1))->where("site_id = '".$_SESSION['site_id']."'")->getField('video_module');
        $video_c = explode(',',strtoupper($videoc));
        $this->add('video_c',$video_c);

        $periods_old = $this->periods_old();
        $periods = $this->periods();
        $now_periods = $periods[date('m')-1];
          //本期
        if (date('m') == 1) {
            if ($now_periods['sdate'] > date('Y-m-d')) {
                $now_periods = $periods_old[11];
                $old_periods = $periods_old[10];
            }else{
                $old_periods = $periods_old[11];
            }
        }else{
            if ($now_periods['sdate'] > date('Y-m-d')) {
                $now_periods = $periods[date('m')-2];
                $old_periods = $periods[date('m')-3];
            }else{
                $old_periods = $periods[date('m')-2];
            }
        }

        $this->add('now_periods',$now_periods);
        $this->add('old_periods',$old_periods);
        $this->add('periods_old',$periods_old);
        $this->add('periods',$periods);

        $this->add('now_date',date('Y-m-d'));
        $this->add('yestoday_date',date('Y-m-d',strtotime("-1 day")));
        $this->add('today_yes',$fc_data["today_yes"]);
        $this->add('today_no',$fc_data["today_no"]);
        $this->add('yestoday_yes',$fc_data["yestoday_yes"]);
        $this->add('yestoday_no',$fc_data["yestoday_no"]);

        $this->add('sp_today_yes',$sport_data["today_yes"]);
        $this->add('sp_today_no',$sport_data["today_no"]);
        $this->add('sp_old_yes',$sport_data["yestoday_yes"]);
        $this->add('sp_old_no',$sport_data["yestoday_no"]);

        $this->add('spcinfoy_today_yes',$spcinfoy_data["today_yes"]);
        $this->add('spcinfoy_today_no',$spcinfoy_data["today_no"]);
        $this->add('spcinfoy_old_yes',$spcinfoy_data["old_yes"]);
        $this->add('spcinfoy_old_no',$spcinfoy_data["old_no"]);
        $this->display('report/index.html');
    }

    //获取股东总代代理数据
    public function get_agents(){
        $atype = $this->input->get('atype');
        $map = array();
        $map['table'] = 'k_user_agent';
        $map['select'] = 'id,agent_login_user,agent_name,agent_user';
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['agent_type'] = $atype;
        $map['where']['is_demo'] = 0;
        $agents = $this->Report_model->rget($map);
        $agent_html = '';
        if (!empty($agents)) {
            foreach ($agents as $key => $val) {
                $agent_html .= '<option  value="'.$val['id'].'" >'.$val['agent_user'].'【'.$val['agent_name'].'】'.'</option>';
            }
        }
        exit($agent_html);
    }

    //报表明细 单项
    public function report(){
        $date_start = $this->input->get('date_start');
        $date_end = $this->input->get('date_end');
        $aid = $this->input->get('aid');
        $rtype = $this->input->get('rtype');
        $type = $this->input->get('game');//报表种类
        $date = array($date_start.' 00:00:00',$date_end.' 23:59:59');

        //查询时间判断
        about_limit($date_end,$date_start);

        //报表项目标题
        $re_title = $this->return_retitle();
        $uname = '';
        if ($rtype != '0') {
            $map_ag = array();
            $map_ag['table'] = 'k_user_agent';
            $map_ag['select'] = 'agent_name,agent_user,agent_login_user';
            $map_ag['where']['agent_type'] = $rtype;
            $map_ag['where']['site_id'] = $_SESSION['site_id'];
            $map_ag['where']['id'] = $aid;
            $adata = $this->Report_model->rfind($map_ag);

            $uname = $adata['agent_name'].'【'.$adata['agent_user'].'】';
            if ($rtype == 'a_t') {
                $aids = $aid;
            }else{
                $aids = $this->Report_model->get_agentsid($aid,$rtype);
            }

        }else{
            $aids = 0;
            $uname = '公司【'.$_SESSION['site_id'].'】';
            $aids = $this->Report_model->get_all_agents();
            $aids = implode(',',array_keys($aids));
        }
        if ($rtype == 's_h') {
            $rt = 'u_a';
            $re_url = 'report_list';
        }elseif ($rtype == 'u_a') {
            $rt = 'a_t';
            $re_url = 'report_list_u';
        }elseif ($rtype == 'a_t') {
            $rt = 'u';
            $re_url = 'report_list_u';
        }else{
            $rt = 's_h';
            $re_url = 'report_list';
        }

        $rurl = URL.'/report/report/'.$re_url.'?aid='.$aid.'&rtype='.$rt.'&sdate='.$date_start.'&edate='.$date_end.'&game='.implode('-',$type);

        //总报表
        $data = $this->Report_model->get_report_result($date,$aids,$type,'0');

        //最前面插入元素
        array_unshift($data,array('num'=>0,'win_num'=>0,'lose_num'=>0,'all_bet'=>0,'bet'=>0,'win'=>0));

        foreach ($data as $key => $val) {
            $data[0]['num'] += $val['num'];
            $data[0]['all_bet'] += $val['all_bet'];//总投注
            $data[0]['bet'] += $val['bet'];//有效投注
            if ($key != '0' && empty($val['num'])) {
                unset($data[$key]);
                continue;
            }
            //盈利特殊处理
            $data[$key]['income'] = $val['win'] - $val['bet'];//总收入
            //负数跳过
            $data[0]['win'] += $data[$key]['win'];//总派彩
            $data[0]['income'] += $data[$key]['income'];//总盈利
            $data[$key]['uname'] = $uname;
            $data[$key]['re_title'] = $re_title[$key];
            //代理类型中文替换
            $data[$key]['type_zh'] = $this->return_agent_type($key);
        }


        // P($data);die();
        $this->add('date_start',$date_start);
        $this->add('date_end',$date_end);
        $this->add('data',$data);
        $this->add('rurl',$rurl);
        $this->display('report/report.html');

    }
    //报表列表
    public function report_list(){
        $sdate = $this->input->get('sdate');
        $edate = $this->input->get('edate');
        $aid = $this->input->get('aid');
        $rtype = $this->input->get('rtype');
        $type = $this->input->get('game');
        $vd_arr = explode('-',$type);
        if (in_array('ag',$vd_arr)) {$vd_arr[] = 'agter';}
        if (in_array('mg',$vd_arr)) {$vd_arr[] = 'mgdz';}
        if (in_array('bbin',$vd_arr)) {$vd_arr[] = 'bbdz';}

        $date = array($sdate.' 00:00:00',$edate.' 23:59:59');

        //查询时间判断
        about_limit($edate,$sdate);

        //报表项目标题
        $re_title = $this->return_retitle();
         //获取股东
        $agent_sh = $this->Report_model->get_agent_sh($rtype,$aid);
        $gid = 0;
        $re_url = 'report_list';
        if ($rtype == 's_h') {
            $rt = 'u_a';
        }elseif ($rtype == 'u_a') {
            $rt = 'a_t';
            $re_url = 'report_list_u';
        }elseif ($rtype == 'a_t') {
            $rt = 'u';
            $re_url = 'report_list_u';
        }
        //下级链接
        $rurl = URL.'/report/report/'.$re_url.'?rtype='.$rt.'&sdate='.$sdate.'&edate='.$edate.'&game='.$type;

        $redata = array('0'=>array());
        foreach ($vd_arr as $k => $v) {
            $tmp_arr = array('num'=>0,'bet'=>0,'all_bet'=>0,'win'=>0,'income'=>0);
            foreach ($agent_sh as $key => $val) {
                $aids = array();
                //获取代理id
                $aids = $this->Report_model->get_agentsid($key,$rtype);

                if (!empty($aids)) {
                    if ($v == 'fc') {
                        $redata[$v]['u-'.$key] = $this->Report_model->get_report_fc($date,$aids,$v,'');
                    }elseif ($v == 'sp') {
                        $redata[$v]['u-'.$key] = $this->Report_model->get_report_sp($date,$aids,$v,'');
                    }else{
                        if ($v == 'mgdz') {
                            $redata['mgdz']['u-'.$key] = $this->Report_model->get_report_video($date,$aids,'mg','mgdz',0);
                        }elseif($v == 'bbdz'){
                            $redata['bbdz']['u-'.$key] = $this->Report_model->get_report_video($date,$aids,'bbin','bbdz',0);
                        }elseif($v == 'agter'){//捕鱼
                            $redata['agter']['u-'.$key] = $this->Report_model->get_report_video($date,$aids,'ag','agter',0);
                        }else{
                            $redata[$v]['u-'.$key] = $this->Report_model->get_report_video($date,$aids,$v,'',0);
                        }
                    }

                    unset($redata[$v]['u-'.$key]['uid']);
                    unset($redata[$v]['u-'.$key]['username']);

                    if (empty($redata[$v]['u-'.$key]['num'])) {
                        unset($redata[$v]['u-'.$key]);
                        continue;
                    }
                    $redata[$v]['u-'.$key]['agent_id'] = $key;
                    $redata[$v]['u-'.$key]['uname'] = $val['agent_name'].'【'.$val['agent_user'].'】';
                    $redata[$v]['u-'.$key]['rurl'] = $rurl.'&aid='.$key;
                    $redata[$v]['u-'.$key]['re_title'] = $re_title[$v];

                        //盈利特殊处理
                    $redata[$v]['u-'.$key]['income'] = $redata[$v]['u-'.$key]['win'] - $redata[$v]['u-'.$key]['bet'];//总收入

                    //单项总计
                    $tmp_arr['num'] += $redata[$v]['u-'.$key]['num'];
                    $tmp_arr['all_bet'] += $redata[$v]['u-'.$key]['all_bet'];
                    $tmp_arr['bet'] += $redata[$v]['u-'.$key]['bet'];
                    $tmp_arr['win'] += $redata[$v]['u-'.$key]['win'];
                    $tmp_arr['income'] += $redata[$v]['u-'.$key]['income'];

                }

            }

            $tmp_rearr = $redata[$v];
            unset($tmp_rearr[0]);
            $redata[0] = array_merge_recursive($redata[0],$tmp_rearr);

            $redata[$v][0]['num'] = $tmp_arr['num'];
            $redata[$v][0]['all_bet'] = $tmp_arr['all_bet'];
            $redata[$v][0]['bet'] = $tmp_arr['bet'];
            $redata[$v][0]['win'] = $tmp_arr['win'];
            $redata[$v][0]['income'] = $tmp_arr['income'];
            $redata[$v][0]['uname'] = '总计';
        }
        //总报表单独处理
        foreach ($redata[0] as $ki => $vi) {
            if (is_array($vi['num'])) {
                $redata[0][$ki]['num'] = array_sum($vi['num']);
                $redata[0][$ki]['all_bet'] = array_sum($vi['all_bet']);
                $redata[0][$ki]['bet'] = array_sum($vi['bet']);
                $redata[0][$ki]['win'] = array_sum($vi['win']);
                $redata[0][$ki]['income'] = array_sum($vi['income']);
                $redata[0][$ki]['agent_id'] = $vi['agent_id'][0];
                $redata[0][$ki]['rurl'] = $vi['rurl'][0];
                $redata[0][$ki]['re_title'] = '总报表';
                $redata[0][$ki]['uname'] = $vi['uname'][0];
            }

            $redata[0][0]['num'] += $redata[0][$ki]['num'];
            $redata[0][0]['all_bet'] += $redata[0][$ki]['all_bet'];
            $redata[0][0]['bet'] += $redata[0][$ki]['bet'];
            $redata[0][0]['win'] += $redata[0][$ki]['win'];
            $redata[0][0]['income'] += $redata[0][$ki]['income'];
            $redata[0][0]['uname'] = '总计';
        }
        $this->add('is_ud',1);
        $this->add('re_title',$re_title);
        $this->add('date_start',$sdate);
        $this->add('date_end',$edate);
        $this->add('agent_sh',$redata);
        $this->display('report/report_list.html');

    }

     //会员 代理 报表
    public function report_list_u(){
        $sdate = $this->input->get('sdate');
        $edate = $this->input->get('edate');
        $aid = $this->input->get('aid');
        $rtype = $this->input->get('rtype');
        $type = $this->input->get('game');
        $vd_arr = explode('-',$type);
        $vnum = count($vd_arr);

        $date = array($sdate.' 00:00:00',$edate.' 23:59:59');

         //查询时间判断
        about_limit($edate,$sdate);

        //报表项目标题
        $re_title = $this->return_retitle();

        if ($rtype == 'a_t') {
            $gid = 1;
            $agids = $this->Report_model->get_agent_idu($aid);
            //代理数据
            $agentname = $this->Report_model->get_agent_sh('a_t',$aid);
            $agids = array_keys($agids);
            $aid = '';
            $this->add('is_ud',1);
            $rurl = URL.'/report/report/report_list_u?rtype='.$rt.'&sdate='.$sdate.'&edate='.$edate.'&game='.$type;
        }else{
            $gid = 2;
        }

        $redata = $this->Report_model->get_report_result($date,$aid,$vd_arr,$gid);

        foreach ($redata as $key => $val) {
            $tmp_arr = array('num'=>0,'all_bet'=>0,'bet'=>0,'win'=>0,'income'=>0);
            foreach ($val as $k => $v) {
                if ($gid == '1') {
                   //显示代理商
                   if (!in_array($k,$agids)) {
                        //去掉其它的代理数据
                        unset($redata[$key][$k]);
                        continue;
                    }
                    unset($redata[$key][$k]['username']);
                    unset($redata[$key][$k]['uid']);
                }else{
                    //显示会员报表
                    unset($redata[$key][$k]['agent_id']);
                    unset($redata[$key][$k]['uid']);
                }

                if (is_array($v['num'])) {
                    if ($gid == '1') {
                        $redata[$key][$k]['agent_id'] = $v['agent_id'][0];
                    }else{
                        $redata[$key][$k]['username'] = $v['username'][0];
                    }
                    $redata[$key][$k]['num'] = array_sum($v['num']);
                    $redata[$key][$k]['all_bet'] = array_sum($v['all_bet']);
                    $redata[$key][$k]['bet'] = array_sum($v['bet']);
                    $redata[$key][$k]['win'] = array_sum($v['win']);
                }
                if ($gid == '1') {
                    $redata[$key][$k]['uname'] = $agentname[$redata[$key][$k]['agent_id']]['agent_name'].'【'.$agentname[$redata[$key][$k]['agent_id']]['agent_user'].'】';
                    $redata[$key][$k]['rurl'] = $rurl.'&aid='.$redata[$key][$k]['agent_id'];
                }else{
                    $redata[$key][$k]['uname'] = $redata[$key][$k]['username'].'【线上会员】';
                    unset($redata[$key][$k]['username']);
                }
                   //盈利特殊处理
                $redata[$key][$k]['income'] = $redata[$key][$k]['win'] - $redata[$key][$k]['bet'];//总收入

                //单个项总计
                $tmp_arr['num'] += $redata[$key][$k]['num'];
                $tmp_arr['all_bet'] += $redata[$key][$k]['all_bet'];
                $tmp_arr['bet'] += $redata[$key][$k]['bet'];
                $tmp_arr['win'] += $redata[$key][$k]['win'];
                $tmp_arr['income'] += $redata[$key][$k]['income'];
            }

            $redata[$key][0]['num'] = $tmp_arr['num'];
            $redata[$key][0]['all_bet'] = $tmp_arr['all_bet'];
            $redata[$key][0]['bet'] = $tmp_arr['bet'];
            $redata[$key][0]['win'] = $tmp_arr['win'];
            $redata[$key][0]['income'] = $tmp_arr['income'];

            $redata[$key][0]['uname'] = '总计';
        }

        // p($redata);die();

        $this->add('re_title',$re_title);
        $this->add('date_start',$sdate);
        $this->add('date_end',$edate);
        $this->add('agent_sh',$redata);
        $this->display('report/report_list.html');
    }

    //类型返回
    function return_agent_type($type){
        switch ($type) {
            case '0':
                return '公司';
                break;
            case 's_h':
                return '股东';
                break;
            case 'u_a':
                return '总代';
                break;
            case 'a_t':
                return '代理';
                break;

            default:
                return '公司';
                break;
        }
    }
    //报表项目标题
    function return_retitle(){
        return array('0'=>'总报表','fc'=>'彩票','sp'=>'体育','ag'=>'AG视讯','agter'=>'AG捕鱼','og'=>'OG视讯','mg'=>'MG视讯','mgdz'=>'MG电子','ct'=>'CT视讯','lebo'=>'LEBO视讯','bbin'=>'BB视讯','bbdz'=>'BB电子','pt'=>'PT电子','eg'=>'EG电子');
    }

        //上期
    function periods_old(){
        return array(
            '0'=>array('id'=>1,'sdate'=>'2015-01-05','edate'=>'2015-02-01'),
            '1'=>array('id'=>2,'sdate'=>'2015-02-02','edate'=>'2015-03-01'),
            '2'=>array('id'=>3,'sdate'=>'2015-03-02','edate'=>'2015-04-05'),
            '3'=>array('id'=>4,'sdate'=>'2015-04-06','edate'=>'2015-05-03'),
            '4'=>array('id'=>5,'sdate'=>'2015-05-04','edate'=>'2015-05-31'),
            '5'=>array('id'=>6,'sdate'=>'2015-06-01','edate'=>'2015-07-05'),
            '6'=>array('id'=>7,'sdate'=>'2015-07-06','edate'=>'2015-08-02'),
            '7'=>array('id'=>8,'sdate'=>'2015-08-03','edate'=>'2015-09-06'),
            '8'=>array('id'=>9,'sdate'=>'2015-09-07','edate'=>'2015-10-04'),
            '9'=>array('id'=>10,'sdate'=>'2015-10-05','edate'=>'2015-11-01'),
            '10'=>array('id'=>11,'sdate'=>'2015-11-02','edate'=>'2015-12-06'),
            '11'=>array('id'=>12,'sdate'=>'2015-12-07','edate'=>'2016-01-03'));
    }

    //本期
    function periods(){
        return array(
            '0'=>array('id'=>1,'sdate'=>'2016-01-04','edate'=>'2016-01-31'),
            '1'=>array('id'=>2,'sdate'=>'2016-02-01','edate'=>'2016-03-06'),
            '2'=>array('id'=>3,'sdate'=>'2016-03-07','edate'=>'2016-04-03'),
            '3'=>array('id'=>4,'sdate'=>'2016-04-04','edate'=>'2016-05-01'),
            '4'=>array('id'=>5,'sdate'=>'2016-05-02','edate'=>'2016-06-05'),
            '5'=>array('id'=>6,'sdate'=>'2016-06-06','edate'=>'2016-07-03'),
            '6'=>array('id'=>7,'sdate'=>'2016-07-04','edate'=>'2016-07-31'),
            '7'=>array('id'=>8,'sdate'=>'2016-08-01','edate'=>'2016-09-04'),
            '8'=>array('id'=>9,'sdate'=>'2016-09-05','edate'=>'2016-10-02'),
            '9'=>array('id'=>10,'sdate'=>'2016-10-03','edate'=>'2016-11-06'),
            '10'=>array('id'=>11,'sdate'=>'2016-11-07','edate'=>'2016-12-04'),
            '11'=>array('id'=>12,'sdate'=>'2016-12-05','edate'=>'2017-01-01'));
    }




}
?>
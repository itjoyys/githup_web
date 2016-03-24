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
        if ($_SESSION['guanliyuan']){
            $this->add('guanliyuan',$_SESSION['guanliyuan']);
            $this->add('agent_iid',$_SESSION['agent_id']);
        }

        //读取视讯配置
        $videoc = $this->Report_model->M(array('tab'=>'web_config','type'=>1))->where("site_id = '".$_SESSION['site_id']."'")->getField('video_module');
        $video_c = explode(',',strtoupper($videoc));
        $this->add('video_c',$video_c);

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
        $agent_html = '';
        $agent_html .='<option  value="'.$_SESSION['agent_id'].'" >'.全部.'</option>';
        $map = array();
        $map['table'] = 'k_user_agent';
        $map['select'] = 'id,agent_login_user,agent_name';
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['is_demo'] = 0;
        if ($atype=="u_a"){
            $map['where']['pid'] = $_SESSION["agent_id"];
            $map['where']['agent_type'] = "a_t";
            $agents = $this->Report_model->rget($map);
            if (!empty($agents)) {
                foreach ($agents as $key => $val) {
                    $agent_html .= '<option  value="'.$val['id'].'" >'.$val['agent_login_user'].'【'.$val['agent_name'].'】'.'</option>';
                }
            }
            exit($agent_html);
        }else{
            $map['where']['id'] = $_SESSION["agent_id"];
            $agents = $this->Report_model->rfind($map);
            exit($agent_html);
        }


    }

    //报表明细 单项
    public function report(){
        $date_start = $this->input->get('date_start');
        $date_end = $this->input->get('date_end');
        $aid = $this->input->get('aid');
        $rtype = $this->input->get('rtype');
        $type = $this->input->get('game');//报表种类
        $date = array($date_start.' 00:00:00',$date_end.' 23:59:59');

        //报表项目标题
        $re_title = $this->return_retitle();
        $uname = '';
        if ($rtype != '0') {
            $map_ag = array();
            $map_ag['table'] = 'k_user_agent';
            $map_ag['select'] = 'agent_name,agent_login_user';
            $map_ag['where']['agent_type'] = $rtype;
            $map_ag['where']['site_id'] = $_SESSION['site_id'];
            $map_ag['where']['id'] = $aid;
            $adata = $this->Report_model->rfind($map_ag);

            $uname = $adata['agent_user'].'('.$adata['agent_name'].')';
            if ($rtype == 'a_t') {
                $aids = $aid;
            }else{
                $aids = $this->Report_model->get_agentsid($aid,$rtype);
            }

        }else{
            $aids = 0;
            $uname = $_SESSION['site_id'].'公司';
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

        $rurl = URL.'/report/report/'.$re_url.'?aid='.$aid.'&rtype='.$rt.'&date_start='.$date_start.'&date_end='.$date_end.'&game='.implode('-',$type);

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

     //会员 代理 报表
    public function report_list_u(){
        $type = $this->input->get('game');
        if ($_SESSION['guanliyuan']){
            $sdate = $this->input->get('date_start');
            $edate = $this->input->get('date_end');
            $aid = $this->input->get('aid');
            $rtype = $this->input->get('rtype');
            if(is_array($type)){
                $vd_arr = $type;
            }else{
                $vd_arr = explode('-',$type);
                $vnum = count($vd_arr);
            }
            if (!empty($aid)&&$aid!=$_SESSION['agent_id']){
                $rtype = "u";
                $sdate = $this->input->get('date_start');
                $edate = $this->input->get('date_end');
            }
        }else{
            $sdate = $this->input->get('date_start');
            $edate = $this->input->get('date_end');
            $aid = $this->input->get('aid');
            $aid = $_SESSION['agent_id'];
            $rtype = "u";
            $vd_arr = $type;
        }
        $date = array($sdate.' 00:00:00',$edate.' 23:59:59');
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
            $rurl = URL.'/report/report/report_list_u?rtype='.$rt.'&date_start='.$sdate.'&date_end='.$edate.'&game='.$type;
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
                    $redata[$key][$k]['uname'] = $agentname[$redata[$key][$k]['agent_id']]['agent_name'];
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
        return array('0'=>'总报表','fc'=>'彩票','sp'=>'体育','ag'=>'AG视讯','og'=>'OG视讯','mg'=>'MG视讯','mgdz'=>'MG电子','ct'=>'CT视讯','lebo'=>'LEBO视讯','bbin'=>'BB视讯','bbdz'=>'BB电子','pt'=>'PT电子','eg'=>'EG电子');
    }




}
?>
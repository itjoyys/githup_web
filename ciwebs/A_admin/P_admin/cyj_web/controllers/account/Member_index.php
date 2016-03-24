<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Member_index extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->login_check();
        $this->load->model('account/Member_index_model');
    }

    public function index()
    {
        $index_id = $this->input->get('index_id');

        // 查询用户
        $site_id = $_SESSION['site_id'];
        $todayReg_count = $this->Member_index_model->user_new_reg($index_id);
        //$todayReg_count = $this->Member_index_model->get_user_reg($index_id);

        // 查询代理
        $agent_up = $this->Member_index_model->get_agents($index_id);

        if (!empty($_SESSION['index_id'])) {
            $this->add('sites_str','站点：'.$this->Member_index_model->select_sites());
        }

        $this->get_user_all();

        //获取站点视讯配置
        $vdata = array();
        $vdata = $this->Member_index_model->get_video_config();
        if ($vdata) {
            foreach ($vdata as $key => $val) {
                if ($val != 'eg') {//bj直接走余额
                    $video_config[$key]['vtype'] = $val.'_money';
                    $video_config[$key]['title'] = strtoupper($val).'余额';
                }
            }
        }

        //现金系统权限
        if (strpos($_SESSION['quanxian'], 'e12') !== false || $_SESSION['quanxian'] == 'sadmin') {
            $this->add('is_e12',1);
        }
        //注单权限
        if (strpos($_SESSION['quanxian'], 'b2') !== false || strpos($_SESSION['quanxian'], 'b4') !== false || strpos($_SESSION['quanxian'], 'b5') !== false || $_SESSION['quanxian'] == 'sadmin') {
            $this->add('is_b',1);
        }

        $this->add('video_config',$video_config);

        $this->add('agent_up', $agent_up);
        $this->add('todayReg_count', $todayReg_count);
        $this->add('siteid',$_SESSION['site_id']);
        $this->display('account/member_index/index.html');
    }

    private function get_user_all()
    {
        $site_id = $_SESSION['site_id'];
        $page = $this->input->get('page');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $mem_sort = $this->input->get('mem_sort');
        $mem_order = $this->input->get('mem_order');
        $agent_id = $this->input->get('agent_id');
        $mem_enable = $this->input->get('mem_enable');
        $agent_user = $this->input->get('agent_user');
        $search_type = $this->input->get('search_type');
        $search_name = $this->input->get('search_name');
        $mem_status = $this->input->get('mem_status');
        $index_id = $this->input->get('index_id');
        $page_num = $this->input->get('page_num');
        $pagenum = isset($page_num)?$page_num:100; // 每页显示的记录数
        $page = isset($page) ? $page : 1;

        //来源
        $ptype = $this->input->get('ptype');

        //$map['select'] = "k_user.*,k_user_agent.agent_user,k_user_agent.agent_name";
        $map['where']['site_id'] = $site_id;
        $map['where']['shiwan'] = 0;
        //获取在线会员id
        $res_user_list = $this->Member_index_model->on_user();
        // p($res_user_list);die();

        if (! empty($mem_status)) {
            $map['where_in']['item'] = 'k_user.uid';
            $map['where_in']['data'] = $res_user_list ? $res_user_list : array(
                '0'
            );
        }
        if (! empty($mem_order) && ! empty($mem_sort)) {
            $map['order'] = $mem_sort . " " . $mem_order;
        } else {
            $map['order'] = "`reg_date` desc";
        }
        // $map['join']['table'] = 'k_user_agent';
        // $map['join']['action'] = 'k_user_agent.id = k_user.agent_id';
        $map['table'] = 'k_user';
        if (!empty($index_id)) {
            $map['where']['k_user.index_id'] = $index_id;
        }
        //来源条件
        if (isset($ptype) && $ptype != -1) {
            $map['where']['k_user.ptype'] = $ptype;
        }

        if (! empty($mem_enable)) {
            $mem_enable = $mem_enable == 'stat' ? '0' : $mem_enable;
            $map['where']['k_user.is_delete'] = $mem_enable;
        }

        if (!empty($index_id)) {
            $map['where']['k_user.index_id'] = $index_id;
        }
        if (! empty($start_date) && ! empty($end_date)) {
             //查询时间判断
            about_limit($end_date,$start_date);

            $map['where']['k_user.reg_date >='] = $start_date.' 00:00:00';
            $map['where']['k_user.reg_date <='] = $end_date.' 23:59:59';
        }
        if (! empty($agent_id)) {
            $map['where']['k_user.agent_id'] = $agent_id;
        }
        if (! empty($mem_enable)) {
            $map['where']['k_user.is_delete'] = $mem_enable;
        }
        if (! empty($agent_user)) {
            $map['where']['k_user_agent.agent_user'] = $agent_user;
        }
        if (! empty($search_name)) {
            switch ($search_type) {
                case '0':
                    $map['like']['title'] = 'k_user.username';
                    break;
                case '1':
                    $map['like']['title'] = 'k_user.pay_name';
                    break;
                case '2':
                    $map['like']['title'] = 'k_user.mobile';
                    break;
                case '3':
                    $map['like']['title'] = 'k_user.pay_num';
                    break;
                case '4':
                    $map['like']['title'] = 'k_user.reg_ip';
                    break;
                case '5':
                    $map['like']['title'] = 'k_user.login_ip';
                    break;
            }
            $map['like']['match'] = $search_name;
            $map['like']['after'] = 'both';
        }
        // p($map);die();
        //$sum = $this->Member_index_model->get_table_count($map);
         $sum = $this->Member_index_model->get_menber_sum($map);
        if (empty($start_date) && empty($end_date)) {
            $sum_title = '总会员数:' . $sum;
        } else {
            $sum_title = '此日期内会员数:' . $sum;
        }
        $totalPage = ceil($sum / $pagenum); // 计算出总页数
        if ($totalPage < $page) {
            $page = 1;
        }

        $map['pagecount'] = $pagenum;
        $map['offset'] = $page > 1 ? ($page - 1) * $pagenum : 0;

		if (!empty($mem_status) && !empty($res_user_list) || empty($mem_status)) {
            //$list = $this->Member_index_model->get_table($map);
             $list = $this->Member_index_model->get_menber_list($map);
             foreach ($list as $k => $v) {
				if ($res_user_list) {
					if (in_array($v['uid'],$res_user_list)) {
						$list[$k]['Online_state'] = "<span style=\"color:#FF00FF;\">在線</span>";
					} else {
						$list[$k]['Online_state'] = "<span style=\"color:#999999;\">離線</span>";
					}

				} else {
					$list[$k]['Online_state'] = "<span style=\"color:#999999;\">離線</span>";
				}
			}
		}
        $this->add('totalPage', $totalPage);
        $this->add('sum_title', $sum_title);
        $this->add('site_id',$_SESSION['site_id']);
        $this->add('list', $list);
    }

    public function member_edit()
    {
        $uid = $this->input->get('uid');
        if (empty($uid)) {
            showmessage('非法操作', 'back', '0');
        }
        $map['table'] = 'k_user';
        $map['where']['uid'] = $uid;
        $map['where']['site_id'] = $_SESSION['site_id'];
        $rows = $this->Member_index_model->get_table_one($map);
        $this->add('rows', $rows);
        $this->display('account/member_index/member_edit.html');
    }

    public function member_edit_do()
    {
        $where['uid'] = $this->input->get('uid');
        $where['site_id'] = $_SESSION['site_id'];
        if (empty($where['uid'])) {
            showmessage('非法操作', 'back', '0');
        }
        $data['change_pwd'] = $this->input->post('change_pwd');
        $passwd = $this->input->post('passwd');
        $repasswd = $this->input->post('repasswd');

        $this->load->library('form_validation');
        $this->form_validation->set_rules('change_pwd', '不能为空', 'trim|required');
        $this->form_validation->set_rules('passwd', '密码', 'callback_check_pass');
        $this->form_validation->set_rules('repasswd', '确认密码', 'callback_check_pass');

        $this->form_validation->set_rules('repasswd', '2次密碼不相同', 'matches[passwd]');
        if ($this->form_validation->run() == FALSE) {
            $this->add("validation_errors", validation_errors());
            showmessage(validation_errors(), 'back', '0');
        } else {
            if ($passwd != '******' || $repasswd != '******') {
                $data['password'] = md5(md5($passwd));
            }
            $map['table'] = 'k_user';
            $map['where'] = $where;
            //获取会员账号
            $uname = $this->get_uname($where['uid']);
            if ($this->Member_index_model->rupdate($map,$data)) {
                $do_log['log_info'] = '修改用户成功:'.$where['uid'];
                $do_log['type'] = 1;
                $do_log['uname'] = $uname['username'];
                $this->Member_index_model->Syslog($do_log);
                showmessage('修改用户成功', 'back', '1');
            } else {
                $do_log['log_info'] = '修改用户失败:'.$where['uid'];
                $do_log['type'] = 1;
                $do_log['uname'] = $uname['username'];
                $this->Member_index_model->Syslog($do_log);
                showmessage('修改用户失败', 'back', '0');
            }
        }
    }

    public function check_pass($str)
    {
        if ($str == '******') {
            return true;
        }
        if (empty($str)) {
            $this->form_validation->set_message('check_pass', '{field}不能为空');
            return false;
        }
        if (! preg_match("/^[A-Za-z0-9]+$/u", $str)) {
            $this->form_validation->set_message('check_pass', '{field}输出格式错误');
            return false;
        } else {
            return true;
        }
    }

    public function edituser()
    {
        // 会员相关处理
        $uid = $this->input->get('uid');
        $action = $this->input->get('act');
        if (! empty($uid) && ! empty($action)) {
            switch ($action) {
                case 'stop':
                    // 停用会员
                    $data['is_delete'] = 2;
                    $data2['is_login'] = 0;
                    break;
                case 'using':
                    // 启用会员
                    $data['is_delete'] = 0;
                    break;
                case 'renew':
                    $this->load->library('video/Games');
                    $username = $this->input->get('username');

                    if (!empty($username)) {
                        $games = new Games();
                        $data = $games->GetAllBalance($username);
                        $list = array('ag','og','mg','ct','bbin','lebo');
                        $result = json_decode($data);

                        if ($result->data->Code == 10017) {
                            $data_u = array();
                            if (!empty($result->data->ogstatus)) {
                                $data_u['og_money'] = floatval($result->data->ogbalance);
                            }
                            if (!empty($result->data->agstatus)) {
                                $data_u['ag_money'] = floatval($result->data->agbalance);
                            }
                            if (!empty($result->data->mgstatus)) {
                                $data_u['mg_money'] = floatval($result->data->mgbalance);
                            }
                            if (!empty($result->data->ctstatus)) {
                                $data_u['ct_money'] = floatval($result->data->ctbalance);
                            }
                            if (!empty($result->data->bbinstatus)) {
                                $data_u['bbin_money'] = floatval($result->data->bbinbalance);
                            }
                            if (!empty($result->data->lebostatus)) {
                                $data_u['lebo_money'] = floatval($result->data->lebobalance);
                            }

                            $db_model['tab'] = 'k_user';
                            $db_model['type'] = 1;
                            $map_u = array();
                            $map_u['uid'] = $uid;
                            $map_u['site_id'] = $_SESSION['site_id'];
                            $this->Member_index_model->M($db_model)->where($map_u)->update($data_u);
                            showmessage('更新余额成功', 'back', '1');
                            exit();
                        }else{
                            showmessage('API请求失败', 'back', '0');
                        }
                    }
                    break;
            }
            $map['table'] = 'k_user';
            $map['where']['uid'] = $uid;
            $map['data'] = $data;
            $map['table2'] = 'k_user_login';
            $map['where2']['uid'] = $uid;
            $map['data2'] = $data2;
            //获取会员账号
            $uname = $this->get_uname($uid);
            if ($this->Member_index_model->update_user_status($map)) {
                $do_log['log_info'] = '修改用户状态成功:'.$uid;
                $do_log['type'] = 1;
                $do_log['uname'] = $uname['username'];
                $this->Member_index_model->Syslog($do_log);
                showmessage('修改用户状态成功', 'back', '1');
            } else {
                $do_log['log_info'] = '修改用户状态成功:'.$uid;
                $do_log['type'] = 1;
                $do_log['uname'] = $uname['username'];
                $this->Member_index_model->Syslog($do_log);
                showmessage('修改用户状态失败', 'back', '0');
            }
        } else {
            showmessage('非法操作', 'back', '0');
        }
    }

    //会员资料
    public function member_data(){
        $uid = $this->input->get('uid');
        if (empty($uid)) {
            showmessage('参数非法', 'back', '0');
        }
        $map = array();
        $map['table'] = 'k_user';
        $map['where']['uid'] = $uid;
        $map['where']['site_id'] = $_SESSION['site_id'];

        $user_data = $this->Member_index_model->rfind($map);

        $memauth = trim($_SESSION["mem_auth"]);

        if (!strstr($memauth,'a1') && $_SESSION["quanxian"]!='sadmin') {
            $user_data['pay_name'] = '无权查看';
        }
        if (!strstr($memauth,'b1') && $_SESSION["quanxian"]!='sadmin') {
            $user_data['pay_num'] = '无权查看';
        }
        if (!strstr($memauth,'c1') && $_SESSION["quanxian"]!='sadmin') {
            $user_data['qk_pwd'] = '无权查看';
        }
        if (!strstr($memauth,'d1') && $_SESSION["quanxian"]!='sadmin') {
            $user_data['mobile'] = '无权查看';
        }
        if (!strstr($memauth,'e1') && $_SESSION["quanxian"]!='sadmin') {
            $user_data['email'] = '无权查看';
        }
        if (!strstr($memauth,'f1') && $_SESSION["quanxian"]!='sadmin') {
            $user_data['qq'] = '无权查看';
        }
        if (!strstr($memauth,'g1') && $_SESSION["quanxian"]!='sadmin') {
            $user_data['passport'] = '无权查看';
        }

        $assoc=explode("-",$user_data['pay_address']);
        $this->add('assoc',$assoc);
        $this->add('user_data',$user_data);
        $this->display('account/member_index/member_data.html');
    }

    //会员资料更新
    public function member_data_do(){
        $uid = $this->input->post('uid');
        $user_data = $this->input->post();

        if (empty($uid)) {
            showmessage('参数非法', 'back', '0');
        }
        $memauth = trim($_SESSION["mem_auth"]);
        if (!strstr($memauth,'a2') && $_SESSION["quanxian"] != 'sadmin') {
            unset($user_data['pay_name']);
        }
        if (!strstr($memauth,'b2') && $_SESSION["quanxian"] != 'sadmin') {
            unset($user_data['pay_num']);
        }elseif(!empty($user_data['pay_num'])){
            if(preg_match('/^[\d]{16,19}$/',$user_data["pay_num"])==0){
                showmessage('银行卡号是16-19位纯数字', 'back', '0');
            }
           //  $is_state = $this->Member_index_model->M(array('tab'=>'k_user','type'=>1))->where("uid != '".$uid."' and site_id = '".$_SESSION['site_id']."' and pay_num ='".$user_data['pay_num']."' ")->find();
           // if(!empty($is_state)){showmessage('该卡号已被绑定', 'back', '0');}
        }
        if (!strstr($memauth,'c2') && $_SESSION["quanxian"] != 'sadmin') {
            unset($user_data['qk_pwd']);
        }
        if (!strstr($memauth,'d2') && $_SESSION["quanxian"] != 'sadmin') {
            unset($user_data['mobile']);
        }
        if (!strstr($memauth,'e2') && $_SESSION["quanxian"] != 'sadmin') {
            unset($user_data['email']);
        }
        if (!strstr($memauth,'f2') && $_SESSION["quanxian"] != 'sadmin') {
            unset($user_data['qq']);
        }
        if (!strstr($memauth,'g2') && $_SESSION["quanxian"] != 'sadmin') {
            unset($user_data['passport']);
        }

        $user_data['pay_address'] = $user_data['province'].'-'.$user_data['city'];
        unset($user_data['uid']);
        unset($user_data['province']);
        unset($user_data['city']);
        unset($user_data['submitbtn']);

        $map = array();
        $map['table'] = 'k_user';
        $map['where']['uid'] = $uid;
        $map['where']['site_id'] = $_SESSION['site_id'];

        //获取会员账号
        $uname = $this->get_uname($uid);

        if($this->Member_index_model->rupdate($map,$user_data)){
            $do_log['log_info'] = '修改会员资料:'.$uid;
            $do_log['type'] = 1;
            $do_log['uname'] = $uname['username'];
            $this->Member_index_model->Syslog($do_log);
            showmessage('修改成功', URL.'/account/member_index');
        }else{
            showmessage('修改会员资料失败', 'back', '0');
        }
    }

     //用户体育下注设定
    public function user_set() {
        $uid = $this->input->get('uid');
        $aid = $this->input->get('aid');
        $spTitle = array('ft' => '足球', 'bk' => '篮球', 'vb' => '排球',
            'bs' => '棒球', 'tn' => '网球'
        );

        //站点体育退水限额
        $spArr = array('ft', 'bk', 'vb', 'bs', 'tn');
        //单项设置处理
        if ($_POST['type'] == 'setOne' && !empty($_POST['id'])) {
            $idS = $_POST['id'];
            $aid = $_POST['aid'];
            $uid = $_POST['uid'];
            unset($_POST['aid']);
            unset($_POST['id']); //去掉不需要的数据
            unset($_POST['type']);
            //上一级数据上限
            $updata = array();
            $mapA = array();
            $site_id = $_SESSION['site_id'];
            $mapA['site_id'] = $site_id;
            $mapA['aid'] = $_POST['agent_id'];
            $mapA['type_id'] = $_POST['type_id']; //玩法类型id
            $updata = $this->Member_index_model->get_Uagent($mapA, 1);

            //var_dump($updata);die;
            //限制重复提交
            unset($mapA['aid']);
            $mapA['uid'] = $_POST['uid'];
            $uSPstate = $this->Member_index_model->get_Uaset($mapA, 1);

            if ($_POST['min'] < $updata['min'] || $_POST['water_break'] > $updata['water_break'] || $_POST['single_field_max'] > $updata['single_field_max'] || $_POST['single_note_max'] > $updata['single_note_max']) {
                //message("您设定的数据超出上级范围,设置上级数据!");
                showmessage('您设定的数据超出上级范围,设置上级数据!', 'back', '0');
                exit();
            }

            unset($_POST['agent_id']);
            if (empty($aid) && empty($uSPstate)) {
                //为空表示添加
                $_POST['site_id'] = $site_id;
                $spSta = $this->Member_index_model->Add_Uaset($_POST, 1);
            } else {
                //更新
                unset($_POST['uid']);
                $spSta = $this->Member_index_model->Update_Uaset($idS, $_POST, 1);
            }
            //获取会员账号
            $uname = $this->get_uname($uid);
            // var_dump($spSta);die;
            if ($spSta>0) {
                $do_log['log_info'] = '成功编辑会员体育设定:' . $_POST['type_id'];
                $do_log['uname'] = $uname['username'];
                $do_log['type'] = 1;
                $this->Member_index_model->Syslog($do_log);
                showmessage('修改成功!', 'back', '1');
            } else {
                $do_log['log_info'] = '失败编辑会员体育设定:' . $_POST['type_id'];
                $do_log['uname'] = $uname['username'];
                $do_log['type'] = 1;
                $this->Member_index_model->Syslog($do_log);
                showmessage('修改失败!', 'back', '0');
            }
        } else {
            foreach ($spArr as $key => $val) {
                $tmp = $this->Member_index_model->get_spType($val, $uid, $aid);
                $spType[$val] = array_merge($tmp[0], $tmp[1]);
            }
        }
        //var_dump($spType);die;
        $typeC = 'sp';
        $this->add('uid', $uid);
        $this->add('aid', $aid);
        $this->add('typeC', $typeC);
        $this->add('spType', $spType);
        $this->add('spTitle', $spTitle);
        $this->display('account/member_index/user_set.html');
    }

    //用户彩票下注设定
    public function user_fc_set() {
        $uid = $this->input->get('uid');
        $aid = $this->input->get('aid');
        $site_id = $_SESSION['site_id'];
        //标题
        $fcTitle = array(
            'fc_3d' => '福彩3D', 'pl_3' => '排列三',
            'cq_ssc' => '重庆时时彩', 'cq_ten' => '重庆快乐十分',
            'gd_ten' => '广东快乐十分', 'bj_8' => '北京快乐8',
            'bj_10' => '北京PK拾', 'tj_ssc' => '天津时时彩',
            'xj_ssc' => '新疆时时彩', 'jx_ssc' => '江西时时彩',
            'jl_k3' => '吉林快三', 'js_k3' => '江苏快三',
            'liuhecai' => '六合彩'
        );

        //站点体育退水限额
        $fcArr = array('fc_3d', 'pl_3', 'cq_ssc', 'cq_ten', 'gd_ten', 'bj_8',
            'bj_10', 'tj_ssc', 'xj_ssc', 'jx_ssc', 'jl_k3', 'js_k3',
            'liuhecai'
        );
        //$Fcgame = M('fc_games_view', $db_config);
        //$Uagent = M('k_user_agent_fc_set', $db_config);
        //$Uaset = M('k_user_fc_set', $db_config);

        //单项设置处理
        if ($_POST['type'] == 'setOne' && !empty($_POST['id'])) {

            $idS = $_POST['id'];
            $aid = $_POST['aid'];
            $uid = $_POST['uid'];
            unset($_POST['aid']);
            unset($_POST['id']); //去掉不需要的数据
            unset($_POST['type']);
            //上一级数据上限
            $updata = array();
            $mapA = array();

            $mapA['site_id'] = $site_id;
            $mapA['aid'] = $_POST['agent_id'];
            $mapA['type_id'] = $_POST['type_id']; //玩法类型id
            //$updata = $Uagent->where($mapA)->find();
            $updata = $this->Member_index_model->get_Uagent($mapA, 2);
            //限制重复提交
            unset($mapA['aid']);
            $mapA['uid'] = $_POST['uid'];
            // var_dump($mapA);die;
            $uFCstate = $this->Member_index_model->get_Uaset($mapA, 2);
            //var_dump($uFCstate);die;
            if ($_POST['min'] < $updata['min'] || $_POST['charges_a'] > $updata['charges_a'] || $_POST['single_field_max'] > $updata['single_field_max'] || $_POST['single_note_max'] > $updata['single_note_max']) {
                showmessage('您设定的数据超出上级范围,设置上级数据!', 'back', '0');
                exit();
            }
            unset($_POST['agent_id']);
            //var_dump($_POST);die;
            if (empty($aid) && empty($uFCstate)) {
                //为空表示添加
                $_POST['site_id'] = $site_id;
                $spSta = $this->Member_index_model->Add_Uaset($_POST, 2);
            } else {
                //更新
                unset($_POST['uid']);
                $spSta = $this->Member_index_model->Update_Uaset($idS, $_POST, 2);
            }

            //获取会员账号
            $uname = $this->get_uname($uid);

            if ($spSta>0) {
                $do_log['log_info'] = '成功编辑会员彩票设定:' . $_POST['type_id'];
                $do_log['type'] = 1;
                $do_log['uname'] = $uname['username'];
                $this->Member_index_model->Syslog($do_log);

                showmessage('修改成功!', 'back', '1');
            } else {
                $do_log['log_info'] = '失败编辑会员彩票设定:' . $_POST['type_id'];
                $do_log['type'] = 1;
                $do_log['uname'] = $uname['username'];
                $this->Member_index_model->Syslog($do_log);
                showmessage('修改失败!', 'back', '0');
            }
        } else {
            foreach ($fcArr as $key => $val) {
                $tmp = $this->Member_index_model->get_FcType($val, $uid, $aid);
                $fcType[$val] = array_merge($tmp[0], $tmp[1]);
            }
        }

        $typeC = 'fc';
        $this->add('uid', $uid);
        $this->add('aid', $aid);
        $this->add('fcType', $fcType);
        $this->add('fcTitle', $fcTitle);
        $this->add('typeC', $typeC);
        $this->display('account/member_index/user_fc_set.html');
    }

     //ajax请求用户下注设定数据
    public function video_set_ajax() {
        $bet_arr = $this->input->post('bet_arr');
        $id = $this->input->post('id');
        //添加股东获取上级占成
        if (!empty($id)) {
            //查询分成信息
            $limit = $this->input->post('limit');
            if ($bet_arr == 'AG限彩') {
                $limit = "'" . $limit . "'";
            }
            $agent = $this->Member_index_model->M(array('tab' => 'k_user_video_set', 'type' => 1));
            $data = $agent->field("*")
                            ->where("bet_arr = '" . $bet_arr . "' and video_id not in(" . $limit . ")")->select();
            if (!empty($data)) {
                foreach ($data as $key => $val) {
                    if ($val['id'] == $id) {
                        $selected = "selected=selected";
                    } else {
                        $selected = "";
                    }
                    $vhtml .= '<option   value="' . $val['video_id'] . '"  ' . $selected . '  >' . $val['min'] . "-" . $val['max'] . '</option>';
                }
            }
            echo json_encode($vhtml);
        }
    }

    //用户视讯下注设定展示
    public function user_video_set() {
        $table = $this->Member_index_model->M(array('tab' => 'k_user_video_set', 'type' => 1));
        $uid = $this->input->get('uid');
        $aid = $this->input->get('aid');
        $data = array(
            'og' => 'OG',
            'ag' => 'AG',
            '0' => 'CT',
            '1' => 'CT',
            '2' => 'CT',
            '3' => 'CT',
            '4' => 'CT',
            '5' => 'CT',
            '6' => 'CT'
        );
        // 数组变形
        if (empty($uid)) {
            showmessage('非法操作', 'back', '0');
        }
        $user_table = $this->Member_index_model->M(array('tab' => 'k_user', 'type' => 1));
        $agent_table = $this->Member_index_model->M(array('tab' => 'k_user_agent', 'type' => 1));
        $user = $user_table->field("agent_id,og_limit,ag_limit,ct_limit")
                ->where('uid=' . $uid)
                ->find();

        $agent = $agent_table->field("pid,og_limit,ag_limit,ct_limit,agent_type")
                ->where('id=' . $user['agent_id'])
                ->find();
        $upagent = $agent_table->field("pid,og_limit,ag_limit,ct_limit,agent_type")
                ->where('id=' . $agent['pid'])
                ->find();
        $sh = $agent_table->field("pid,og_limit,ag_limit,ct_limit,agent_type")
                ->where('id=' . $upagent['pid'])
                ->find();
        $sh['og_limit'] = $sh['og_limit'] ? $sh['og_limit'] : '1,1';
        $sh['ag_limit'] = $sh['ag_limit'] ? $sh['ag_limit'] : "A";
        $sh['ct_limit'] = $sh['ct_limit'] ? $sh['ct_limit'] : "1702,1703,1700,3596`1706,36,37,10`11,35,9,3616`1707,1708,300,3604`693,3488,3612,691`1704,1705,3600,3156`1972,1971,3608,1973";

        $upagent['og_limit'] = $upagent['og_limit'] ? $upagent['og_limit'] : $sh['og_limit'];
        $upagent['ag_limit'] = $upagent['ag_limit'] ? $upagent['ag_limit'] : $sh['ag_limit'];
        $upagent['ct_limit'] = $upagent['ct_limit'] ? $upagent['ct_limit'] : $sh['ct_limit'];
        $agent['og_limit'] = $agent['og_limit'] ? $agent['og_limit'] : $upagent['og_limit'];
        $agent['ag_limit'] = $agent['ag_limit'] ? $agent['ag_limit'] : $upagent['ag_limit'];
        $agent['ct_limit'] = $agent['ct_limit'] ? $agent['ct_limit'] : $upagent['ct_limit'];
        $user['og_limit'] = $user['og_limit'] ? $user['og_limit'] : $agent['og_limit'];
        $user['ag_limit'] = $user['ag_limit'] ? $user['ag_limit'] : $agent['ag_limit'];
        $user['ct_limit'] = $user['ct_limit'] ? $user['ct_limit'] : $agent['ct_limit'];

        foreach ($data as $key => $val) {
            if ($val == 'AG') {
                $where = "video_id in('" . $user['ag_limit'] . "') and type='" . $val . "'";
                $video[$key]['video_name'] = $val;
                $video[$key]['limit'] = $user['ag_limit'];
                $video[$key]['data'] = $table->where("$where")
                        ->order('limitype asc,min asc')
                        ->select();
            } elseif ($val == 'OG') {
                $limitall = explode(',', $user['og_limit']);
                foreach ($limitall as $k => $v) {
                    $limitype = $k + 1;
                    $where = "video_id in(" . $v . ") and limitype='" . $limitype . "'";
                    $video[$key]['video_name'] = $val;
                    $video[$key]['limit'] = $v;
                    $video[$key]['data'][$k] = $table->where("$where")
                            ->order('limitype asc,min asc')
                            ->find();
                }
            } elseif ($val == 'CT') {
                $limit = explode("`", $user['ct_limit']);
                $where = "video_id in(" . $limit[$key] . ") and type='" . $val . "'";
                $video[$key]['video_name'] = $val;
                $video[$key]['limit'] = $limit[$key];
                $video[$key]['data'] = $table->where("$where")
                        ->order('limitype asc,min asc')
                        ->select();
            }
        }
        $this->add('uid', $uid);
        $this->add('aid', $aid);
        $this->add('video', $video);
        $this->display('account/member_index/video_user_set.html');
    }

    //用户视讯下注设定操作
    private function video_user_set_do() {
        $uid = $this->input->post('uid');
        $video_id = $this->input->post('video_id');
        $single_note = $this->input->post('single_note');
        $type = $this->input->post('type');
        $bet_arr = $this->input->post('bet_arr');
        $user_table = $this->Member_index_model->M(array('tab' => 'k_user', 'type' => 1));
        $agent_table = $this->Member_index_model->M(array('tab' => 'k_user_agent', 'type' => 1));
        $user = $user_table->field("agent_id,og_limit,ag_limit,ct_limit")
                ->where('uid=' . $uid)
                ->find();
        $agent = $agent_table->field("pid,og_limit,ag_limit,ct_limit,agent_type")
                ->where('id=' . $user['agent_id'])
                ->find();
        $upagent = $agent_table->field("pid,og_limit,ag_limit,ct_limit,agent_type")
                ->where('id=' . $agent['pid'])
                ->find();
        $sh = $agent_table->field("pid,og_limit,ag_limit,ct_limit,agent_type")
                ->where('id=' . $upagent['pid'])
                ->find();
        $sh['og_limit'] = $sh['og_limit'] ? $sh['og_limit'] : '1,1,1';
        $sh['ag_limit'] = $sh['ag_limit'] ? $sh['ag_limit'] : "A";
        $sh['ct_limit'] = $sh['ct_limit'] ? $sh['ct_limit'] : "1702,1703,1700,3596`1706,36,37,10`11,35,9,3616`1707,1708,300,3604`693,3488,3612,691`1704,1705,3600,3156`1972,1971,3608,1973";

        $upagent['og_limit'] = $upagent['og_limit'] ? $upagent['og_limit'] : $sh['og_limit'];
        $upagent['ag_limit'] = $upagent['ag_limit'] ? $upagent['ag_limit'] : $sh['ag_limit'];
        $upagent['ct_limit'] = $upagent['ct_limit'] ? $upagent['ct_limit'] : $sh['ct_limit'];
        $agent['og_limit'] = $agent['og_limit'] ? $agent['og_limit'] : $upagent['og_limit'];
        $agent['ag_limit'] = $agent['ag_limit'] ? $agent['ag_limit'] : $upagent['ag_limit'];
        $agent['ct_limit'] = $agent['ct_limit'] ? $agent['ct_limit'] : $upagent['ct_limit'];
        $user['og_limit'] = $user['og_limit'] ? $user['og_limit'] : $agent['og_limit'];
        $user['ag_limit'] = $user['ag_limit'] ? $user['ag_limit'] : $agent['ag_limit'];
        $user['ct_limit'] = $user['ct_limit'] ? $user['ct_limit'] : $agent['ct_limit'];
        if (is_numeric($type)) {
            $ct_limit = explode('`', $user['ct_limit']);
            $limit2 = explode(',', $ct_limit[$type]);
            $keys = array_keys($limit2, $video_id);
            $keys = $keys[0];
            $limit2[$keys] = $single_note;
            $ct_limit[$type] = implode(',', $limit2);
            $single_note = implode('`', $ct_limit);
        }
        if ($type == 'og') {
            $og_limit = explode(',', $user['og_limit']);
            switch ($bet_arr) {
                case 'OG视讯':
                    $keys = 0;
                    break;
                case 'OG轮盘':
                    $keys = 1;
                    break;
                default:
                    ;
                    break;
            }
            $og_limit[$keys] = $single_note;
            $single_note = implode(',', $og_limit);
        }

        if (!empty($uid)) {
            $type = is_numeric($type) ? 'ct' : $type;
            $return = $user_table->where('uid=' . $uid)->update(array(
                $type . '_limit' => $single_note
            ));
                   //获取会员账号
            $uname = $this->get_uname($uid);
            if ($return) {
                $do_log['log_info'] = '编辑用户视讯设置:' . $uid;
                $do_log['type'] = 1;
                $do_log['uname'] = $uname['username'];
                $this->Member_index_model->Syslog($do_log);
                showmessage('编辑成功', 'back', '1');
            } else {
                $do_log['log_info'] = '编辑用户视讯设置:' . $uid;
                $do_log['type'] = 1;
                $do_log['uname'] = $uname['username'];
                $this->Member_index_model->Syslog($do_log);
                showmessage('编辑失败', 'back', '0');
            }
        }
    }
    //设定操作选择，其实这里只能选择视讯，以前大神都做好了
    //不过被我改成这样了
    public function user_set_do()
    {
        $stype = $this->input->post('stype');
        switch ($stype) {
            case 'sp':
                $this->sp_user_set_do();
                break;
            case 'fc':
                $this->fc_user_set_do();
                break;
            case 'video':
                $this->video_user_set_do();
                break;
            default:
                $this->sp_user_set_do();
                break;
        }

    }

    //获取会员账号
   public function get_uname($uid){
        $map = array();
        $map['tab'] = 'k_user';
        $map['type'] = 1;
        $map['is_port'] = 1;
        $map['where']['uid'] = $uid;
        $map['where']['site_id'] = $_SESSION['site_id'];
        return $this->Member_index_model->mfind($map);
    }

	//强制会员下线
    public function login_out(){
        $uid = $this->input->get('uid');
        //$username = $this->input->get('username');
        $db_model['tab'] = 'k_user_login';
        $db_model['type'] = 1;
        $map_u = array();
        $map_u['uid'] = $uid;
        $map_u['site_id'] = $_SESSION['site_id'];
        $data_u = array();
        $data_u['ssid'] = 1;
        $result = $this->Member_index_model->M($db_model)->where($map_u)->update($data_u);
        if($result){
            $this->redis_del_user($uid);
            showmessage('客户已被成功踢下线！', 'back', '1');
        }else{
            showmessage('客户被迫下线失败！', 'back', '0');
        }
    }


    //会员离线清除
    public function redis_del_user($uid){
        $redis = new Redis();
        $redis->connect(REDIS_HOST,REDIS_PORT);
        $redis_key = 'ulg'.CLUSTER_ID.'_'.SITEID.$uid;
        $redis->del($redis_key);
    }


}
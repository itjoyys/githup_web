<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Agent_index extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->login_check();
        $this->load->model('account/Agent_index_model');
    }

    public function index()
    {
        $agent_type = $this->input->get('agent_type');
        // 查询股东
        $this->get_agent_all();
        if (!empty($_SESSION['index_id'])) {
            $this->add('sites_str','站点：'.str_replace('全部', '请选择站点',$this->Agent_index_model->select_sites()));
        }
        $this->add('siteid',$_SESSION['site_id']);
        if ($agent_type != 'a_t') {
            //股东总代视图
            $this->display('account/agent_index/index.html');
        } else {
            //代理视图
            $this->display('account/agent_index/agent_index.html');
        }
    }

    private function get_agent_all() //查询股东总代理 代理··用agent_type 判断
    {
        $site_id = $_SESSION['site_id'];
        $index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?'a':$index_id;
        $agent_type = $this->input->get('agent_type');
        $aid = $this->input->get('aid');
        $pid = $this->input->get('pid');
        $page = $this->input->get('page');
        $mem_sort = $this->input->get('mem_sort');
        $mem_order = $this->input->get('mem_order');
        $agent_user = $this->input->get('agent_user');
        $is_delete = $this->input->get('is_delete');
        $agent_status = $this->input->get('agent_status');//是否在线
        $pagenum = 50; // 每页显示的记录数
        $CurrentPage = isset($page) ? $page : 1;

        $map_up = array();
        $map_up['table'] = 'k_user_agent';
        $map_up['where']['site_id'] = $_SESSION['site_id'];
        if ($agent_type == 'a_t') {
            $map_up['where']['agent_type'] = 'u_a';
        }elseif ($agent_type == 'u_a') {
            $map_up['where']['agent_type'] = 's_h';
        }
        $map_up['where']['is_delete'] = 0;
        $map_up['where']['is_demo'] = 0;
        $map_up['where']['index_id'] = $index_id;

        $this->add('datas',$this->Agent_index_model->rget($map_up));

          if (! empty($mem_order) && ! empty($mem_sort)) {
            $order = $mem_sort . " " . $mem_order;
        } else {
            $order = "`id` desc";
        }

        if (isset($is_delete) && $is_delete != '') {
            $map['is_delete'] = $is_delete;
        }
        if (!empty($pid)) {$map['pid'] = $pid;}
        $map['index_id'] = $index_id;
        $map['site_id'] = $site_id;
        $map['is_demo'] = 0;

        if (! empty($aid)) {$map['pid'] = $aid;
        } else {
            if (! empty($agent_type)) {
                $map['agent_type'] = $agent_type;
            }
        }
        if (! empty($agent_user)) {
            $map['agent_user'] = array('like','%'.$agent_user.'%');
        }
        //获取在线代理 总代
        $res_user_list = $this->Agent_index_model->on_user();
        if (! empty($agent_status)) {
             $map['agent_user'] = array('in','('.implode(',',$res_user_list).')');
        }
        $count = $this->Agent_index_model->mcount($map,array('tab'=>'k_user_agent','type'=>1));

        $totalPage = ceil($count / $pagenum);
        $page = isset($page) ? $page : 1;
        if ($totalPage < $page) {
            $page = 1;
        }
        $startCount = ($page - 1) * $pagenum;
        $limit = $startCount . "," . $pagenum;

        $list = $this->Agent_index_model->get_agents_list($map,$limit,$order);

        //获取旗下会员总数
        $users_data = $this->Agent_index_model->get_users_data(array_keys($list),$agent_type);
        $list = array_values($list);

        foreach ($list as $key => $value) {
            $list[$key]['pagent_user'] = $this->Agent_index_model->get_pagent_user($value['pid']);

            if ($users_data[$value['id']]) {
                $list[$key]['user_num'] = $users_data[$value['id']]['num'] + 0;
            }else{
                $list[$key]['user_num'] = 0;
            }

             //对在线离线进行判断（*）
            if ($res_user_list) {
                if (in_array($value['id'],$res_user_list)) {
                    $list[$key]['Online_state'] = "<span style=\"color:#FF00FF;\">在線</span>";
                } else {
                    $list[$key]['Online_state'] = "<span style=\"color:#999999;\">離線</span>";
                }
            } else {
                $list[$key]['Online_state'] = "<span style=\"color:#999999;\">離線</span>";
            }
            if ($agent_type == 's_h') {
                // p($agent);//股东
                $agentmap['table'] = 'k_user_agent';
                $agentmap['where']['pid'] = $value['id'];
                $agent_data1 = $this->Agent_index_model->get_table($agentmap); // zongdai
                $list[$key]['agents_num'] = count($agent_data1);
                if ($agent_data1) {
                    foreach ($agent_data1 as $k1 => $v1) {
                        $agentmap2['table'] = 'k_user_agent';
                        $agentmap2['where']['pid'] = $v1['id'];
                        $agent_data2 = $this->Agent_index_model->get_table($agentmap2); // daili
                        $list[$key]['agent_num'] += count($agent_data2);
                    }
                } else {
                    $list[$key]['agent_num'] = 0;
                }
            } elseif ($agent_type == 'u_a') {
                // $agentmap2['table'] = 'k_user_agent';
                // $agentmap2['where']['pid'] = $value['id'];

                $agent_data2 = $this->Agent_index_model->get_agents($value['id']);
                $at_ids = implode(',',array_keys($agent_data2));

                //$agent_data2 = $this->Agent_index_model->get_table($agentmap2); // daili
                $list[$key]['agent_num'] = 0;
                $list[$key]['agent_num'] += count($agent_data2);
            }
        }
        $this->add('agent_type',$agent_type);
        $this->add('index_id',$index_id);
        $this->add('totalPage', $totalPage);
        $this->add('list', $list);
    }

    public function agent_edit() //股东总代代理修改页面
    {
        $aid = $this->input->get('aid');
        if (empty($aid)) {
            showmessage('非法操作', 'back', '0');
        }
        $map['table'] = 'k_user_agent';
        $map['where']['id'] = $aid;

        $rows = $this->Agent_index_model->get_table_one($map);
        $rows['ps'] = $this->Agent_index_model->get_pagent_user($rows['pid']);
        $this->add('agent', $rows);
        if ($rows['agent_type'] != 's_h') {
            $rows['sports_scale'] = $rows['sports_scale'] ? $rows['sports_scale'] : $rows['ps']['sports_scale'];
            $rows['lottery_scale'] = $rows['lottery_scale'] ? $rows['lottery_scale'] : $rows['ps']['lottery_scale'];
            $rows['video_scale'] = $rows['video_scale'] ? $rows['video_scale'] : $rows['ps']['video_scale'];
            $this->display('account/agent_index/agent_a_edit.html'); //由于代理的页面跟股东总代有所不同
        } else {
            $this->display('account/agent_index/agent_edit.html');
        }
    }
    //股东代理修改
    public function agent_edit_do()  //股东总代代理修改 提交表单
    {
        $id = $this->input->post('id');
        $agent_type = $this->input->post('agent_type');
        if (empty($id)) {
            showmessage('非法操作', 'back', '0');
        }
        $passwd = $this->input->post('agent_pwd');
        $repasswd = $this->input->post('agent_pwd2');
        $data['agent_name'] = $this->input->post('agent_name');
        //股东页面独用
        if ($agent_type == 's_h') {
            $data['sports_scale'] = $this->input->post('sports_scale');
            $data['lottery_scale'] = $this->input->post('lottery_scale');
            $data['video_scale'] = $this->input->post('video_scale');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('agent_name', '代理名称不能为空', 'trim|required');
        $this->form_validation->set_rules('agent_pwd', '密码', 'callback_check_pass');
        $this->form_validation->set_rules('agent_pwd2', '确认密码', 'callback_check_pass');

        $this->form_validation->set_rules('agent_pwd2', '2次密碼不相同', 'matches[agent_pwd]');
        if ($this->form_validation->run() == FALSE) {
            $this->add("validation_errors", validation_errors());
            showmessage('表单数据不合法', 'back', '0');
        } else {
            if ($passwd != '******' || $repasswd != '******') {
                $data['agent_pwd'] = md5(md5($passwd));
            }
               //获取代理账号信息
            $alog = $this->get_aname($id);
            if ($this->Agent_index_model->update_agent($id,$data)) {
                $do_log['log_info'] = '修改代理成功:' . $id;
                $do_log['uname'] = $alog['agent_user'];
                $do_log['type'] = 2;
                $this->Agent_index_model->Syslog($do_log);
                showmessage('修改成功', URL.'/account/agent_index/index?agent_type='.$agent_type, '1');
            } else {
                showmessage('修改失败', 'back', '0');
            }
        }
    }

    public function agent_scale_edit_do() //修改代理占成
    {

        $id = $this->input->post('id');
        if (empty($id)) {
            showmessage('非法操作', 'back', '0');
        }
        $data['sports_scale'] = $this->input->post('sports_scale');
        $data['lottery_scale'] = $this->input->post('lottery_scale');
        $data['video_scale'] = $this->input->post('video_scale');

        $map = array();
        $map['table'] = 'k_user_agent';
        $map['where']['id'] = $id;
        $map['where']['site_id'] = $_SESSION['site_id'];
        //获取代理账号信息
        $alog = $this->get_aname($id);
        if ($this->Agent_index_model->rupdate($map,$data)) {
            $do_log['log_info'] = '修改占成成功:' . $id;
            $do_log['uname'] = $alog['agent_user'];
            $do_log['type'] = 2;
            $this->Agent_index_model->Syslog($do_log);
            showmessage('修改占成成功', 'back', '1');
        } else {
            showmessage('修改占成失败', 'back', '0');
        }
    }

    public function agent_add()    //添加代理
    {
        $agent_type = $this->input->get('agent_type');
        if (! empty($_SESSION['index_id'])) {
            $this->add('sites_str', str_replace('全部', '请选择站点',$this->Agent_index_model->select_sites()));
        }else{
            $map = array();
            $map['table'] = 'k_user_agent';
            $map['where']['site_id'] = $_SESSION['site_id'];
            if ($agent_type == 'a_t') {
                $map['where']['agent_type'] = 'u_a';
            }elseif ($agent_type == 'u_a') {
                $map['where']['agent_type'] = 's_h';
            }
            $map['where']['is_delete'] = 0;
            $map['where']['is_demo'] = 0;
            $datas = $this->Agent_index_model->rget($map);
            $this->add('datas',$datas);
        }
        $this->add('agent_type',$agent_type);
        $this->display('account/agent_index/agent_add.html');
    }

    public function agent_add_do()  //添加代理提交表单
    {

        $apply_type = $this->input->post('apply_type');
        //代理申请处理专用
        if ($apply_type == 5) {
            $this->agent_apply_do();
            exit();
        }

        $agent_type = $this->input->get('agent_type');
        $index_id = $this->input->post('index_id');
        $index_id = empty($index_id)?'a':$index_id;
        $site_id = $_SESSION['site_id'];
        $data = $this->input->post();
        if (empty($agent_type)) {
            showmessage('非法操作', 'back', '0');
        }
        $this->load->library('form_validation');
        if ($agent_type != 's_h') {
             $this->form_validation->set_rules('shareholder', '不能为空', 'trim|required');
        }

        $this->form_validation->set_rules('agent_user', '不能为空', 'trim|required');
        $this->form_validation->set_rules('agent_name', '不能为空', 'trim|required');
        if (empty($data['id'])) {
            $this->form_validation->set_rules('agent_pwd', '密码', 'callback_check_pass');
            $this->form_validation->set_rules('agent_pwd2', '确认密码', 'callback_check_pass');
            $this->form_validation->set_rules('agent_pwd2', '2次密碼不相同', 'matches[agent_pwd]');
        }
        if ($this->form_validation->run() == FALSE) {
            $this->add("validation_errors", validation_errors());
            showmessage('表单数据不合法', 'back', '0');
        } else {
            if (empty($data['id'])) {
                $dataA['agent_pwd'] = md5(md5($data['agent_pwd']));
                $dataS['login_pwd'] = md5(md5($data['agent_pwd']));
            } else {
                $dataA['agent_pwd'] = $data['app_pwd'];
                $dataS['login_pwd'] = $data['app_pwd'];
            }
            $dataS['login_name'] = $site_id . $data['agent_user'];
            $dataS['login_name_1'] = $data['agent_user'];
            $dataS['quanxian'] = 'agadmin'; // 添加代理时 默认给权限

            $config = $this->Agent_index_model->rfind(array('table'=>'web_config','where'=>array('site_id'=>$_SESSION['site_id'],'index_id'=>$index_id)));
            $dataS['admin_url'] = $config['agent_url'];
            $dataS['type'] = 1;
            $dataS['index_id'] = $index_id;
            $dataS['login_pwd'] = $dataA['agent_pwd'];
            $dataS['about'] = $data['agent_name'];
            $dataS['site_id'] = $site_id;
            $dataS['add_date'] = date('Y-m-d H:i:s'); // 添加时间

            $dataA['agent_name'] = $data['agent_name']; // 名字
            $dataA['video_scale'] = $data['video_scale']; // 视讯占成
            $dataA['sports_scale'] = $data['sports_scale']; // 体育占成
            $dataA['lottery_scale'] = $data['lottery_scale']; // 彩票占成
            $intr = $this->Agent_index_model->get_intr();

            $dataA['intr'] = $intr + 1; // 代理商推广id
            $dataA['site_id'] = $site_id;
            $dataA['index_id'] = $index_id;
            $dataA['pid'] = $data['shareholder'];
            $dataA['agent_user'] = $site_id . $data['agent_user'];
            $dataA['agent_login_user'] = $data['agent_user'];
            $dataA['add_date'] = date('Y-m-d H:i:s'); // 添加时间
            $dataA['agent_type'] = $agent_type; // 表示代理

            if (empty($data['id'])) {
                //添加账号
                $map['table'] = 'k_user_agent';
                $map['where']['agent_login_user'] = $data['agent_user'];
                $map['where']['site_id'] = $_SESSION['site_id'];
                $info_1 = $this->Agent_index_model->rfind($map);
                $map1['table'] = 'sys_admin';
                $map1['where']['login_name_1'] = $data['agent_user'];
                $info_2 = $this->Agent_index_model->rfind($map1);

                if (!empty($info_1) || !empty($info_2)) {
                    showmessage("该账号已被使用", 'back', '0');
                }
                $retrun = $this->Agent_index_model->set_agent_user($dataA,$dataS);
            } else {
                $map['where']['id'] = $data['id'];
                $map['data']['is_delete'] = 0;
                $map['data2']['agent_id'] = $data['id'];
                $retrun = $this->Agent_index_model->edit_agent_user($map);
            }
            if ($retrun > 0) {
                $state = $this->Agent_index_model->agent_setAdd($retrun, $dataA['pid'], $site_id);
                if (empty($state)) {
                    $do_log['log_info'] = '设定参数错误:' . $dataA['agent_user'];
                    $this->Agent_index_model->Syslog($do_log);
                    showmessage("添加失败,设定参数错误请联系管理员", 'back', '0');
                }
            }
            if ($retrun > 0) {
                $do_log['log_info'] = '添加代理成功:' . $dataA['agent_user'];
                $do_log['type'] = 2;
                $do_log['uname'] = $data['agent_user'];
                $this->Agent_index_model->Syslog($do_log);

                 //删除代理缓存
                $redis = new Redis();
                $redis->connect(REDIS_HOST,REDIS_PORT);
                $redis_key = $_SESSION['site_id'].'_agents_'.$index_id;
                $redis->delete($redis_key);

                $redis_key = $_SESSION['site_id'].'_agents_';
                $redis->delete($redis_key);

                showmessage('添加代理成功',URL.'/account/agent_index?agent_type=a_t', '1');
            } else {
                $do_log['log_info'] = '添加代理失败:' . $dataA['agent_user'];
                $do_log['type'] = 2;
                $do_log['uname'] = $data['agent_user'];
                $this->Agent_index_model->Syslog($do_log);
                showmessage('添加代理失败', 'back', '0');
            }
        }
    }

    //代理申请
    public function agent_apply_do($id){
        $id = $this->input->post('id');//代理ID
        $agent_name = $this->input->post('agent_name');
        $adata['pid'] = $this->input->post('shareholder');
        $agent_user = $this->input->post('agent_user');
        $adata['agent_user'] = $_SESSION['site_id'].$agent_user;

        $adata['sports_scale'] = $this->input->post('sports_scale');
        $adata['lottery_scale'] = $this->input->post('lottery_scale');
        $adata['video_scale'] = $this->input->post('video_scale');
        $adata['agent_type'] = 'a_t'; // 表示代理
        $adata['is_delete'] = 0;

        $intr = $this->Agent_index_model->get_intr();
        $adata['intr'] = $intr + 1; // 代理商推广id

        $apply_data = $this->Agent_index_model->rfind(array('table'=>'k_user_agent','where'=>array('id'=>$id,'site_id'=>$_SESSION['site_id'])));

        $sdata['login_pwd'] = $apply_data['agent_pwd'];
        $sdata['about'] = $agent_name;
        $sdata['login_name'] = $_SESSION['site_id'] . $agent_user;
        $sdata['login_name_1'] = $agent_user;
        $sdata['quanxian'] = 'agadmin'; // 添加代理时 默认给权限

        $config = $this->Agent_index_model->rfind(array('table'=>'web_config','where'=>array('site_id'=>$_SESSION['site_id'],'index_id'=>$apply_data['index_id'])));

        //添加设定参数
        $state = $this->Agent_index_model->agent_setAdd($id, $dataA['pid'], $_SESSION['site_id']);

        $sdata['admin_url'] = $config['agent_url'];
        $sdata['type'] = 1;
        $sdata['agent_id'] = $id;
        $sdata['index_id'] = $apply_data['index_id'];
        $sdata['login_pwd'] = $apply_data['agent_pwd'];
        $sdata['site_id'] = $_SESSION['site_id'];
        $sdata['add_date'] = date('Y-m-d H:i:s'); // 添加时间

        if ($this->Agent_index_model->agent_apply_do($id,$adata,$sdata)) {
            $do_log['log_info'] = '添加代理成功:' . $id;
            $do_log['type'] = 2;
            $do_log['uname'] = $agent_user;
            $this->Agent_index_model->Syslog($do_log);
            showmessage('添加代理成功', URL.'/account/agent_index?agent_type=a_t', '1');
         } else {
            showmessage('添加代理失败', 'back', '0');
         }
    }

    public function agent_add_ajax()
    {
        $scatype = array(
            'sports_scale',
            'video_scale',
            'lottery_scale'
        );
        $id = $this->input->post('id');
        $atype = $this->input->post('atype');
        // 添加股东获取上级占成
        if (! empty($id) && $atype == 'ua') {
            // 查询分成信息
            $vhtml = array(
                'sports_scale' => '',
                'video_scale' => '',
                'lottery_scale' => ''
            );
            $data = $this->Agent_index_model->get_pagent_user($id);
            if (! empty($data)) {
                foreach ($scatype as $key => $val) {
                    for ($i = 0; $i <= $data[$val]; $i += 0.05) {
                        $vhtml[$val] .= '<option  value="' . $i . '" >' . $i . '成</option>';
                    }
                }
                $vhtml['index_id'] = $data['index_id'];
            } else {
                $vhtml['video_scale'] = '<option  value="0" >0成</option>';
                $vhtml['sports_scale'] = '<option  value="0" >0成</option>';
                $vhtml['lottery_scale'] = '<option  value="0" >0成</option>';
            }
            echo json_encode($vhtml);
        }
    }
    //检测代理股东账户是否重复ajaxj
    public function agent_user_check($username)
    {
        $name = $this->input->post('name');
        $param = $this->input->post('param');
        if ($name == 'agent_user') {
            $map['table'] = 'k_user_agent';
            $map['where']['agent_login_user'] = $param;
            $map['site_id'] = $_SESSION['site_id'];
            $info = $this->Agent_index_model->rfind($map);
            $map1['table'] = 'sys_admin';
            $map1['where']['login_name_1'] = $param;
            $info1 = $this->Agent_index_model->rfind($map1);
            if (! empty($info) || ! empty($info1)) {
                exit('此帳號已有人使用');
            } else {
                exit('y');
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

    public function editagent()
    {
        // 代理相关处理
        $id = $this->input->get('id');
        $agent_type = $this->input->get('agent_type');
        $action = $this->input->get('action');
        if (! empty($id) && ! empty($action)) {
            switch ($action) {
                case 'stop':
                    // 停用代理
                    $data['is_delete'] = 2;
                    break;
                case 'using':
                    // 启用代理
                    $data['is_delete'] = 0;
                    break;
                case 'recovery':
                    // 恢复
                    $data['is_delete'] = 0;
                    break;
                case 'renew':
                    break;
            }
            $map['table'] = 'k_user_agent';
            $map['where']['id'] = $id;
            $map['data'] = $data;

            $map1['table'] = 'sys_admin';
            $map1['where']['agent_id'] = $id;
            $map1['data'] = $data;
            $agent_type_name = get_agent_type($agent_type);
            //获取代理账号
            $uname = $this->get_aname($id);
            if ($this->Agent_index_model->update_table($map) && $this->Agent_index_model->update_table($map1)) {
                $do_log['log_info'] = '修改状态成功:' . $id;
                $do_log['type'] = 2;
                $do_log['uname'] = $uname['agent_user'];
                $this->Agent_index_model->Syslog($do_log);
                showmessage('修改状态成功', 'back', '1');
            } else {
                $do_log['log_info'] = '修改状态失败:' . $id;
                $do_log['type'] = 2;
                $do_log['uname'] = $uname['agent_user'];
                $this->Agent_index_model->Syslog($do_log);
                showmessage('修改状态失败', 'back', '0');
            }
        } else {
            showmessage('非法操作', 'back', '0');
        }
    }

    //获取每个站点对应的总代 股东
    public function get_agents_i(){
        $index_id = $this->input->get('index_id');
        $agent_type = $this->input->get('type');
        $map = array();
        $map['table'] = 'k_user_agent';
        $map['where']['site_id'] = $_SESSION['site_id'];
        if ($agent_type == 'a_t') {
            $map['where']['agent_type'] = 'u_a';
        }elseif ($agent_type == 'u_a') {
            $map['where']['agent_type'] = 's_h';
        }
        $map['where']['is_delete'] = 0;
        $map['where']['is_demo'] = 0;
        $map['where']['index_id'] = $index_id;

        $datas = $this->Agent_index_model->rget($map);
        if (!empty($datas)) {
            $str_html = '<option>请选择所屬上级</option>';
            foreach ($datas as $key => $v) {
                $str_html .='<option value="'.$v['id'].'" >'.$v['agent_user'].'</option>';
            }
            echo $str_html;
        }
        exit();
    }
    //代理设置页面   stype 来判断调用那个私有方法分别为体育彩票和视讯
    public function agent_set(){
        $stype = $this->input->get('stype');
        switch ($stype) {
            case 'sp':
                $this->sp_agent_set();
                break;
            case 'fc':
                $this->fc_agent_set();
                break;
            case 'video':
                $this->video_agent_set();
                break;
            default:
                $this->sp_agent_set();
                break;
        }
    }
    //体育设定
    private  function sp_agent_set(){
        $aid = $this->input->get('aid');
        $spTitle = array('ft' => '足球','bk' => '篮球','vb' => '排球',
            'bs' => '棒球','tn' => '网球');
        // 站点体育退水限额
        $spArr = array('ft','bk','vb','bs','tn');
        if (empty($aid)) {
            showmessage('非法操作', 'back', '0');
        }
           //查询是否存在设定数据没有就添加
        $map = array();
        $map['table'] = 'k_user_agent_sport_set';
        $map['select'] = 'aid,site_id,type_id';
        $map['where']['aid'] = 0;
        $map['where']['site_id'] = $_SESSION['site_id'];
        $set_arr = $this->Agent_index_model->rget($map);
        foreach ($set_arr as $key => $val) {
            $set_arr[$key]['aid'] = $aid;
        }
        $map['where']['aid'] = $aid;
        $map['where']['site_id'] = $_SESSION['site_id'];
        $tmpA = $this->Agent_index_model->rget($map);
        if (empty($tmpA)) {
            $this->Agent_index_model->M(array('tab'=>'k_user_agent_sport_set','type'=>1))->add($set_arr,1);
        }

          //写入redis缓存
        $this->load->model('other/System_model');
        $spType = $this->System_model->get_sp_limit($aid);

        // foreach ($spArr as $key => $val) {
        //      $spType[$val] = $this->Agent_index_model->M(array('tab'=>'sp_games_view','type'=>1))->join("inner join k_user_agent_sport_set on sp_games_view.id = k_user_agent_sport_set.type_id")->where("k_user_agent_sport_set.aid = '".$aid."' and sp_games_view.type = '".$val."'")->select();
        // }
        $this->add('aid', $aid);
        $this->add('title', '体育');
        $this->add('spType', $spType);
        $this->display('account/agent_index/sp_agent_set.html');
    }
        //福彩设定
    private function fc_agent_set(){
        $aid = $this->input->get('aid');
        $fcTitle = array(
            'fc_3d' => '福彩3D','pl_3' => '排列三','cq_ssc' => '重庆时时彩',
            'cq_ten' => '重庆快乐十分','gd_ten' => '广东快乐十分',
            'bj_8' => '北京快乐8','bj_10' => '北京PK拾',
            'tj_ssc' => '天津时时彩','xj_ssc' => '新疆时时彩',
            'jx_ssc' => '江西时时彩','jl_k3' => '吉林快三',
            'js_k3' => '江苏快三','liuhecai' => '六合彩');
        // 站点体育退水限额
        $fcArr = array('fc_3d','pl_3','cq_ssc','cq_ten',
            'gd_ten','bj_8','bj_10','tj_ssc','xj_ssc','jx_ssc',
            'jl_k3','js_k3','liuhecai');
        if (empty($aid)) {
            showmessage('非法操作', 'back', '0');
        }
        //判断是否存在 不存在添加
        $map = array();
        $map['table'] = 'k_user_agent_fc_set';
        $map['where']['aid'] = $aid;
        $map['where']['site_id'] = $_SESSION['site_id'];
        $tmpA = $this->Agent_index_model->rget($map);
        if (empty($tmpA)) {
            $map['select'] = 'aid,site_id,type_id';
            $map['where']['aid'] = 0;
            $map['where']['site_id'] = $_SESSION['site_id'];
            $set_arr = $this->Agent_index_model->rget($map);
            foreach ($set_arr as $key => $val) {
                $set_arr[$key]['aid'] = $aid;
            }
            $this->Agent_index_model->M(array('tab'=>'k_user_agent_fc_set','type'=>1))->add($set_arr,1);
        }

               //写入redis缓存
        $this->load->model('other/System_model');
        $fcType = $this->System_model->get_fc_limit($aid);
        // foreach ($fcArr as $key => $val) {
        //     $fcType[$val] = $this->Agent_index_model->M(array('tab'=>'fc_games_view','type'=>1))->join("inner join k_user_agent_fc_set on fc_games_view.id = k_user_agent_fc_set.type_id")->where("k_user_agent_fc_set.aid = '".$aid."' and fc_games_view.fc_type = '".$val."'")->select();
        // }

        $this->add('aid', $aid);
        $this->add('title', '彩票');
        $this->add('fcType', $fcType);
        $this->add('fcTitle', $fcTitle);
        $this->display('account/agent_index/fc_agent_set.html');
    }

    //代理设置提交表单   stype 来判断调用那个私有方法分别为体育彩票和视讯
    public function agent_set_do(){
        $stype = $this->input->post('stype');
        switch ($stype) {
            case 'sp':
                $this->sp_agent_set_do();
                break;
            case 'fc':
                $this->fc_agent_set_do();
                break;
            case 'video':
                $this->video_agent_set_do();
                break;
            default:
                $this->sp_agent_set_do();
                break;
        }
    }
    //体育设定修改
    private function sp_agent_set_do(){
        $site_id = $_SESSION['site_id'];
        $type = $this->input->post('type');
        $id = $this->input->post('id');
        $uid = $this->input->post('uid');

        if ($type != 'setOne' && empty($id)) {}
        $idS = $this->input->post('id');
        $aid = $this->input->get('aid');
        // 上一级数据上限
        $type_id = $this->input->post('type_id');
        $agent_id = $this->input->post('agent_id');
        $map['table'] = 'k_user_agent';
        $map['select'] = 'pid';
        $map['where']['site_id'] = $site_id;
        $map['where']['id'] = $agent_id;
        $pid = $this->Agent_index_model->get_table_one($map);
        $map1['table'] = 'k_user_agent_sport_set';
        $map1['where']['site_id'] = $site_id;
        $map1['where']['type_id'] = $type_id; // 玩法类型id
        if (empty($pid['pid'])) {
            $map1['where']['is_default'] = 1;
        } else {
            $map1['where']['aid'] = $pid['pid'];
        }
        $updata = $this->Agent_index_model->get_table_one($map1);

        $data = $this->input->post();

        if ($data['min'] < $updata['min'] || $data['water_break'] > $updata['water_break'] || $data['single_field_max'] > $updata['single_field_max'] || $data['single_note_max'] > $updata['single_note_max']) {
            showmessage('您设定的数据超出上级范围,设置上级数据!', 'back', '0');
            exit();
        }

        $addmap['table'] = 'k_user_agent_sport_set';
        $addmap['data']['min'] = $data['min'];
        $addmap['data']['type_id'] = $type_id; // 玩法类型id
        $addmap['data']['water_break'] = $data['water_break'];
        $addmap['data']['single_field_max'] = $data['single_field_max'];
        $addmap['data']['single_note_max'] = $data['single_note_max'];
        // 更新
        $addmap['where']['id'] = $idS;
        $addmap['where']['site_id'] = $site_id;
        $addmap['where']['aid'] = $agent_id;
        $spSta = $spSta = $this->Agent_index_model->update_table($addmap);
        //获取代理账号信息
        $alog = $this->get_aname($agent_id);
        if ($spSta) {
            $do_log['log_info'] = '修改体育设定成功:' . $type_id;
            $do_log['uname'] = $alog['agent_user'];
            $do_log['type'] = 2;
            $this->Agent_index_model->Syslog($do_log);

            $redis_key = $_SESSION['site_id'].'_'.$agent_id.'_sp_limit';
            $this->delete_redis_key($redis_key);

            showmessage('修改成功!', 'back', '1');
        } else {
            $do_log['log_info'] = '修改体育设定失败:' . $type_id;
            $do_log['uname'] = $alog['agent_user'];
            $do_log['type'] = 2;
            $this->Agent_index_model->Syslog($do_log);
            showmessage('修改失败!', 'back', '0');
        }
    }

      //删除redis里面对应的key
    public function delete_redis_key($redis_key){
        $redis = new Redis();
        $redis->connect(REDIS_HOST,REDIS_PORT);
        $redis->delete($redis_key);
    }

    //福彩设定修改
    private function fc_agent_set_do(){
        $site_id = $_SESSION['site_id'];
        $type = $this->input->post('type');
        $id = $this->input->post('id');
        if ($type != 'setOne' && empty($id)) {}
        $idS = $this->input->post('id');
        // 上一级数据上限
        $type_id = $this->input->post('type_id');
        $agent_id = $this->input->post('agent_id');
        $map['table'] = 'k_user_agent';
        $map['select'] = 'pid';
        $map['where']['site_id'] = $site_id;
        $map['where']['id'] = $agent_id;
        $pid = $this->Agent_index_model->get_table_one($map);
        $map1['table'] = 'k_user_agent_fc_set';
        $map1['where']['site_id'] = $site_id;
        $map1['where']['type_id'] = $type_id; // 玩法类型id
        if (empty($pid['pid'])) {
            $map1['where']['is_default'] = 1;
        } else {
            $map1['where']['aid'] = $pid['pid'];
        }
        $updata = $this->Agent_index_model->get_table_one($map1);
        $data = $this->input->post();
        if ($data['min'] < $updata['min'] || $data['charges_a'] > $updata['charges_a'] || $data['single_field_max'] > $updata['single_field_max'] || $data['single_note_max'] > $updata['single_note_max']) {
            showmessage('您设定的数据超出上级范围,设置上级数据!', 'back', '0');
            exit();
        }

        $addmap['table'] = 'k_user_agent_fc_set';
        $addmap['data']['min'] = $data['min'];
        $addmap['data']['type_id'] = $type_id; // 玩法类型id
        $addmap['data']['charges_a'] = $data['charges_a'];
        $addmap['data']['single_field_max'] = $data['single_field_max'];
        $addmap['data']['single_note_max'] = $data['single_note_max'];
        // 更新
        $addmap['where']['id'] = $idS;
        $addmap['where']['site_id'] = $site_id;
        $addmap['where']['aid'] = $agent_id;
        $spSta = $spSta = $this->Agent_index_model->update_table($addmap);

        //获取代理账号信息
        $alog = $this->get_aname($agent_id);
        if ($spSta) {
            $do_log['log_info'] = '修改彩票设定成功:' . $type_id;
            $do_log['uname'] = $alog['agent_user'];
            $do_log['type'] = 2;
            $this->Agent_index_model->Syslog($do_log);

            $redis_key = $_SESSION['site_id'].'_'.$agent_id.'_fc_limit';
            $this->delete_redis_key($redis_key);

            showmessage('修改成功!', 'back', '1');
        } else {
            $do_log['log_info'] = '修改彩票设定失败:' . $type_id;
            $do_log['uname'] = $alog['agent_user'];
            $do_log['type'] = 2;
            $this->Agent_index_model->Syslog($do_log);
            showmessage('修改失败!', 'back', '0');
        }
    }
    //视讯设定
    function video_agent_set(){
        $table =$this->Agent_index_model->M(array('tab' => 'k_user_video_set','type' => 1));
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
        $aid = $this->input->get('aid');
        if (empty($aid)) {showmessage('非法操作', 'back', '0');}
        $agent_table =$this->Agent_index_model->M(array('tab' => 'k_user_agent','type' => 1));
        $agent = $agent_table->field("pid,og_limit,ag_limit,ct_limit,agent_type")
            ->where('id=' . $aid)
            ->find();
        switch ($agent['agent_type']) {
            case 's_h':
                $agent['og_limit'] = $agent['og_limit'] ? $agent['og_limit'] : '1,1';
                $agent['ag_limit'] = $agent['ag_limit'] ? $agent['ag_limit'] : "A";
                $agent['ct_limit'] = $agent['ct_limit'] ? $agent['ct_limit'] : "1702,1703,1700,3596`1706,36,37,10`11,35,9,3616`1707,1708,300,3604`693,3488,3612,691`1704,1705,3600,3156`1972,1971,3608,1973";
                break;
            case 'u_a':
                $sh = $agent_table->field("pid,og_limit,ag_limit,ct_limit,agent_type")
                    ->where('id=' . $agent['pid'])
                    ->find();
                $sh['og_limit'] = $sh['og_limit'] ? $sh['og_limit'] : '1,1';
                $sh['ag_limit'] = $sh['ag_limit'] ? $sh['ag_limit'] : "A";
                $sh['ct_limit'] = $sh['ct_limit'] ? $sh['ct_limit'] : "1702,1703,1700,3596`1706,36,37,10`11,35,9,3616`1707,1708,300,3604`693,3488,3612,691`1704,1705,3600,3156`1972,1971,3608,1973";
                $agent['og_limit'] = $agent['og_limit'] ? $agent['og_limit'] : $sh['og_limit'];
                $agent['ag_limit'] = $agent['ag_limit'] ? $agent['ag_limit'] : $sh['ag_limit'];
                $agent['ct_limit'] = $agent['ct_limit'] ? $agent['ct_limit'] : $sh['ct_limit'];
                break;
            case 'a_t':
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
                break;
        }

            foreach ($data as $key => $val) {
                if ($val == 'AG') {
                    $where = "video_id in('" . $agent['ag_limit'] . "') and type='" . $val . "'";
                    $video[$key]['video_name'] = $val;
                    $video[$key]['limit'] = $agent['ag_limit'];
                    $video[$key]['data'] = $table->where("$where")->order('min asc')
                    ->select();
                } elseif ($val == 'OG') {
                    $limitall = explode(',', $agent['og_limit']);
                    foreach ($limitall as $k=>$v){
                        $limitype =$k+1;
                        $where = "video_id in(" . $v . ") and limitype='" . $limitype . "'";
                        $video[$key]['video_name'] = $val;
                        $video[$key]['limit'] = $v;
                        $video[$key]['data'][$k] = $table->where("$where")
                        ->order('limitype asc,min asc')
                        ->find();
                    }
                } elseif ($val == 'CT') {
                    $limit = explode("`", $agent['ct_limit']);
                    $where = "video_id in(" . $limit[$key] . ") and type='" . $val . "'";
                    $video[$key]['video_name'] = $val;
                    $video[$key]['limit'] = $limit[$key];
                    $video[$key]['data'] = $table->where("$where")->order('min asc')
                    ->select();
                }

            }
            $this->add('aid', $aid);
            $this->add('video', $video);
            $this->display('account/agent_index/video_agent_set.html');
    }
        //视讯设定修改处理
    private function video_agent_set_do(){
        $aid = $this->input->post('aid');
        $video_id = $this->input->post('video_id');
        $single_note = $this->input->post('single_note');
        $type = $this->input->post('type');
        $bet_arr = $this->input->post('bet_arr');
        if (empty($aid)) {showmessage('非法操作', 'back', '0');}
        $agent_table =$this->Agent_index_model->M(array('tab' => 'k_user_agent','type' => 1));
        $agent = $agent_table->field("pid,og_limit,ag_limit,ct_limit,agent_type")
        ->where('id=' . $aid)
        ->find();
        switch ($agent['agent_type']) {
            case 's_h':
                $agent['og_limit'] = $agent['og_limit'] ? $agent['og_limit'] : '1,1';
                $agent['ag_limit'] = $agent['ag_limit'] ? $agent['ag_limit'] : "A";
                $agent['ct_limit'] = $agent['ct_limit'] ? $agent['ct_limit'] : "1702,1703,1700,3596`1706,36,37,10`11,35,9,3616`1707,1708,300,3604`693,3488,3612,691`1704,1705,3600,3156`1972,1971,3608,1973";
                break;
            case 'u_a':
                $sh = $agent_table->field("pid,og_limit,ag_limit,ct_limit,agent_type")
                ->where('id=' . $agent['pid'])
                ->find();
                $sh['og_limit'] = $sh['og_limit'] ? $sh['og_limit'] : '1,1';
                $sh['ag_limit'] = $sh['ag_limit'] ? $sh['ag_limit'] : "A";
                $sh['ct_limit'] = $sh['ct_limit'] ? $sh['ct_limit'] : "1702,1703,1700,3596`1706,36,37,10`11,35,9,3616`1707,1708,300,3604`693,3488,3612,691`1704,1705,3600,3156`1972,1971,3608,1973";
                $agent['og_limit'] = $agent['og_limit'] ? $agent['og_limit'] : $sh['og_limit'];
                $agent['ag_limit'] = $agent['ag_limit'] ? $agent['ag_limit'] : $sh['ag_limit'];
                $agent['ct_limit'] = $agent['ct_limit'] ? $agent['ct_limit'] : $sh['ct_limit'];
                break;
            case 'a_t':
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
                break;
        }
        if (is_numeric($type)) {
            $ct_limit = explode('`', $agent['ct_limit']);
            $limit2 = explode(',', $ct_limit[$type]);
            $keys = array_keys($limit2, $video_id);
            $keys = $keys[0];
            $limit2[$keys] = $single_note;
            $ct_limit[$type] = implode(',', $limit2);
            $single_note = implode('`', $ct_limit);
        }
        if ($type=='og') {
            $og_limit = explode(',', $agent['og_limit']);
            switch ($bet_arr) {
                case 'OG视讯':
                    $keys=0 ;
                    break;
                case 'OG轮盘':
                    $keys=1 ;
                    break;
                default:
                    ;
                    break;
            }
            $og_limit[$keys] = $single_note;
            $single_note = implode(',', $og_limit);
        }
        if(!empty($aid)){
            $type = is_numeric($type) ? 'ct' : $type;
            $return =$agent_table->where('id='.$aid)->update(array($type . '_limit'=>$single_note));
            //获取代理账号信息
            $alog = $this->get_aname($aid);

            if ($return) {
                $do_log['log_info'] = '编辑代理视讯设置:' . $aid;
                $do_log['uname'] = $alog['agent_login_user'];
                $do_log['type'] = 2;
                $this->Agent_index_model->Syslog($do_log);
                showmessage('编辑成功', 'back', '1');
            }else{
                $do_log['log_info'] = '编辑代理视讯设置:' . $aid;
                $do_log['uname'] = $alog['agent_login_user'];
                $do_log['type'] = 2;
                $this->Agent_index_model->Syslog($do_log);
                showmessage('编辑失败', 'back', '0');
            }
        }


    }

    public function agent_fc_detailed_set(){
        $site_id = $_SESSION['site_id'];
        $db_model['tab'] = 'web_config';
        $db_model['type'] = 1;
        $db_model['is_port'] = 1;//读取从库
        $web['where']['site_id'] = $site_id;
        $web['field'] = 'is_update_odd,is_open_pankou,is_close_caizhong,is_close_wanfa,lottery_pan';
        $webconfig = $this->Agent_index_model->M($db_model)->field($web['field'])->where($web['where'])->find();

        $agent_id = $this->input->get('agent_id');
        $agent_type = $this->input->get('agent_type');
        $type = $this->input->get('type');//彩票类型
        $type = empty($type)?'fc_3d':$type;
        $db_model['tab'] = 'fc_games';
        $db_model['type'] = 1;
        $db_model['is_port'] = 1;//读取从库
        $fc_games = $this->Agent_index_model->M($db_model)->where()->select();

        $close_lottery = $this->Agent_index_model->M(array('tab' => 'close_lottery','type' => 1));
        $agent_table =$this->Agent_index_model->M(array('tab' => 'k_user_agent','type' => 1));
        switch ($agent_type) {
            case 's_h':
                $where = array('agent_id'=>$agent_id,'caizhong'=>$type,'type'=>1);
                $agent_close['caizhong'] = $close_lottery->where($where)->find();
                $where = array('agent_id'=>$agent_id,'caizhong'=>$type,'type'=>3);
                $agent_close['pankou'] = $close_lottery->where($where)->find();
                $db_model['tab'] = 'fc_games_type';
                $db_model['type'] = 1;
                $db_model['is_port'] = 1;//读取从库
                $mapfc['fc_type']=$type;
                $mapfc['close_lottery.agent_id']=$agent_id;
                $join = 'left join close_lottery on fc_games_type.id = close_lottery.wanfa';
                $fc_games_type = $this->Agent_index_model->M($db_model)->join($join)->where($mapfc)->select();
                break;
            case 'u_a':
                $sh = $agent_table->field("pid,og_limit,ag_limit,ct_limit,agent_type")
                ->where('id=' . $agent['pid'])
                ->find();
                $sh['og_limit'] = $sh['og_limit'] ? $sh['og_limit'] : '1,1';
                $sh['ag_limit'] = $sh['ag_limit'] ? $sh['ag_limit'] : "A";
                $sh['ct_limit'] = $sh['ct_limit'] ? $sh['ct_limit'] : "1702,1703,1700,3596`1706,36,37,10`11,35,9,3616`1707,1708,300,3604`693,3488,3612,691`1704,1705,3600,3156`1972,1971,3608,1973";
                $agent['og_limit'] = $agent['og_limit'] ? $agent['og_limit'] : $sh['og_limit'];
                $agent['ag_limit'] = $agent['ag_limit'] ? $agent['ag_limit'] : $sh['ag_limit'];
                $agent['ct_limit'] = $agent['ct_limit'] ? $agent['ct_limit'] : $sh['ct_limit'];
                break;
            case 'a_t':
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
                break;
        }

        if(empty($fc_games_type)){
            $db_model['tab'] = 'fc_games_type';
            $db_model['type'] = 1;
            $db_model['is_port'] = 1;//读取从库
            $mapfc2['fc_type']=$type;
            $fc_games_type = $this->Agent_index_model->M($db_model)->where($mapfc2)->select();
        }
        $this->add('fc_games',$fc_games);
        $this->add('agent_close',$agent_close);
        $this->add('type',$type);
        $this->add('webconfig',$webconfig);
        $this->add('fc_games_type',$fc_games_type);
        $this->display('account/agent_index/agent_fc_detailed_set.html');
    }

    public function agent_fc_detailed_set_do(){
        $site_id = $_SESSION['site_id'];
        $db_model['tab'] = 'web_config';
        $db_model['type'] = 1;
        $db_model['is_port'] = 1;//读取从库
        $web['where']['site_id'] = $site_id;
        $web['field'] = 'is_update_odd,is_open_pankou,is_close_caizhong,is_close_wanfa,lottery_pan';
         $webconfig = $this->Agent_index_model->M($db_model)->field($web['field'])->where($web['where'])->find();
//         $agent_id = $this->input->post('agent_id');
//         $agent_type = $this->input->post('agent_type');
//         $is_close_caizhong = $this->input->post('is_close_caizhong');
//         $is_open_pankou = $this->input->post('is_open_pankou');
        $post = $this->input->post();

        $db_model['tab'] = 'fc_games_type';
        $db_model['type'] = 1;
        $db_model['is_port'] = 1;//读取从库
        $mapfc['where']['fc_type']=$post['type'];
        $mapfc['field'] = 'id';
        $fc_games_type = $this->Agent_index_model->M($db_model)->field($mapfc['field'])->where($mapfc['where'])->select();
        $db_model['tab'] = 'close_lottery';
        $db_model['type'] = 1;
        $db_model['is_port'] = 1;//读取从库
        $close_lottery = $this->Agent_index_model->M($db_model);
        p($post);
        $close_lottery->begin();
        try {


        if(!empty($post['is_close_caizhong'])){
            $array['site_id'] = $site_id;
            $array['agent_id'] = $post['agent_id'];
            $array['caizhong'] = $post['type'];
            $array['type'] =1;
            $list = $close_lottery->where($array)->select();
            if(!empty($list)){
                $data['value'] = $post['is_close_caizhong'];
                $return1 = $close_lottery->where($array)->update($data);
            }else{
                $data = $array;
                $data['value'] = $post['is_close_caizhong'];
                $return1 = $close_lottery->add($data);
            }
        }

        foreach ($fc_games_type as $key=>$val){

            $array2['site_id'] = $site_id;
            $array2['agent_id'] = $post['agent_id'];
            $array2['caizhong'] = $post['type'];
            $array2['wanfa'] = $val['id'];
            $array2['type'] =2;
            $list = $close_lottery->where($array2)->select();
            if(!empty($list)){
                $data2['value'] = $post[$list[0]['id']."_is_close_wanfa"];
                p($post[$list[0]['id']."_is_close_wanfa"]);
                $return2[] = $close_lottery->where($array2)->update($data2);
            }else{
            $data2 = $array2;
            $data['value'] = $post[$val['id']."_is_close_wanfa"];
            $return2[] = $close_lottery->add($data2);
            }
        }

        if(!empty($post['is_open_pankou'])){
            $array3['site_id'] = $site_id;
            $array3['agent_id'] = $post['agent_id'];
            $array3['caizhong'] = $post['type'];
            $array3['type'] =3;
            $list = $close_lottery->where($array3)->select();
            if(!empty($list)){
                $data3['value'] = $post['is_open_pankou'];
                $return3 = $close_lottery->where($array3)->update($data3);
            }else{
                $data3 = $array3;
                $data['value'] = $post['is_close_caizhong'];
                $return3 = $close_lottery->add($data3);
            }
        }
        $close_lottery->commit();
        $true = true;

        } catch (Exception $e) {
            $close_lottery->rollback();
            $true = false;
        }

        if ($true) {
            $do_log['log_info'] = '编辑代理彩票详细设置:' . $post['agent_id'];
            //$do_log['uname'] = $post['agent_id'];
            $do_log['type'] = 2;
            $this->Agent_index_model->Syslog($do_log);
            showmessage('编辑成功', 'back', '1');
        }else{
            $do_log['log_info'] = '编辑代理彩票详细设置:' . $post['agent_id'];
           // $do_log['uname'] = $post['agent_id'];
            $do_log['type'] = 2;
            $this->Agent_index_model->Syslog($do_log);
            showmessage('编辑失败', 'back', '0');
        }

    }



    //获取代理账号
    function get_aname($id){
        $map = array();
        $map['tab'] = 'k_user_agent';
        $map['type'] = 1;
        $map['is_port'] = 1;
        $map['field'] = 'agent_login_user,agent_user';
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['id'] = $id;
        return $this->Agent_index_model->mfind($map);
    }

     //代理推广域名
    public function agent_domain(){
        $id = intval($this->input->get('id'));
        $domain = $this->input->get('domain');
        $intr = $this->input->get('intr');
        $username = $this->input->get('username');
        $index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?'a':$index_id;
        $page = intval($this->input->get('page'));

        if (!empty($_SESSION['index_id'])) {
            $this->add('sites_str','站点：'.str_replace('全部', '请选择站点',$this->Agent_index_model->select_sites()));
        }

        $page = empty($page)?1:$page;

        $map = array();
        if ($id) {$map['aid'] = $id;}
        $map['site_id'] = $_SESSION['site_id'];
        $map['index_id'] = $index_id;
        $map['state'] = 1;
        if ($domain) {
            $map['domain'] = array('like','%'.$domain.'%');
        }

        //获取总数
        $count = $this->Agent_index_model->get_agent_domain($map,'');

        $perNumber = 50;
        $totalPage=ceil($count/$perNumber);
        $page=isset($page)?$page:1;
        if($totalPage<$page){
          $page = 1;
        }
        $startCount=($page-1)*$perNumber;
        $limit=$startCount.",".$perNumber;
        $data = $this->Agent_index_model->get_agent_domain($map,$limit);

        $agents = $this->Agent_index_model->get_agentsdata();
        $this->add('agents',$agents);
        $this->add('data',$data);
        $this->add('index_id',$index_id);
        $this->add('agent_id',$id);
        $this->add('intr',$intr);
        $this->add('page', $this->Agent_index_model->get_page('history_login',$totalPage,$page));
        $this->display('account/agent_index/agent_domain_index.html');
    }
    //代理推广域名添加编辑
    public function agent_domain_add(){
        $id = intval($this->input->get('id'));
        $index_id = $this->input->get('index_id');
        $intr = $this->input->get('intr');
        $agent_id = intval($this->input->get('agent_id'));

        if ($id) {
            $map = array();
            $map['table'] = 'k_user_agent_domain';
            $map['where']['id'] = $id;
            $data = $this->Agent_index_model->rfind($map);
            exit(json_encode($data));
        }
        $this->add('index_id',$index_id);
        $this->add('agent_id',$agent_id);
        $this->add('id',$id);
        $this->add('intr',$intr);
        $this->display('account/agent_index/agent_domain_add.html');
    }
    //删除代理推广域名
    public function agent_domain_state(){
        $state = intval($this->input->get('state'));
        $id = intval($this->input->get('id'));
        $aid = intval($this->input->get('aid'));
        if (empty($id)) {
            showmessage('参数错误', URL.'/account/agent_index/agent_domain?id='.$aid, '0');
        }
        if ($state) {
            $arr['state'] = 0;
        }else{
            $arr['state'] = 1;
        }
        $log = $this->Agent_index_model->agent_domain_add_do($arr,$id);
        if ($log) {
            showmessage('操作成功', 'back', '1');
        }else{
            showmessage('操作失败', 'back', '0');
        }
    }
    //代理推广域名添加处理
    public function agent_domain_add_do(){
        $id = intval($this->input->post('id'));
        $arr['index_id'] = $this->input->post('index_id');
        $arr['aid'] = intval($this->input->post('agent_id'));
        $arr['domain'] = $this->input->post('domain');
        $arr['intr'] = $this->input->post('intr');

        if (empty($arr['domain']) || empty($arr['aid']) || empty($arr['index_id'])) {
            showmessage('完善表单', URL.'/account/agent_index/agent_domain?id='.$arr['aid'], '0');
        }
        $log = $this->Agent_index_model->agent_domain_add_do($arr,$id);

        if ($log) {
            showmessage('操作成功', URL.'/account/agent_index/agent_domain?id='.$arr['aid'].'&intr='.$arr['intr'].'&index_id='.$arr['index_id'], '1');
        }else{
            showmessage('操作失败', URL.'/account/agent_index/agent_domain?id='.$arr['aid'].'&intr='.$arr['intr'].'&index_id='.$arr['index_id'], '0');
        }
    }


}
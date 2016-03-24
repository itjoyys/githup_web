<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Sub_account extends MY_Controller {

	public function __construct() {
		parent::__construct();
        $this->login_check();
		$this->load->model('account/Sub_account_model');
	}

	public function index(){
        $search_name = $this->input->get('search_name');
        $search_type = $this->input->get('search_type');
        $sub_status = $this->input->get('sub_status');
        $page = $this->input->get('page');
        $map = array();
        $map['site_id'] = $_SESSION['site_id'];
        $map['is_delete'] = array('in','(0,2)');
        $map['type'] = 0;
        //账号名称检索
        if(!empty($search_name)){
            if($search_type == 0){
                $str = '%'.$search_name.'%';
                $map['login_name'] = array('like',$str);
            }elseif($search_type == 1){
                $str = '%'.$search_name.'%';
                $map['about'] = array('like',$str);
            }
        }

        $db_model['tab'] = 'sys_admin';
        $db_model['base_type'] = 1;

        $sum = $this->Sub_account_model->mcount($map,$db_model);
        //获取在线状态
        $new_user_on = $this->is_online_account();

        $perNumber=25; //每页显示的记录数
        $totalPage=ceil($sum/$perNumber); //计算出总页数
        $page=isset($page)?$page:1;
        $page=($totalPage<$page)?1:$page;
        $startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
        $limit=$startCount.",".$perNumber;

        //p($new_user_on);die;
        if (! empty($sub_status)) {
            $map['table'] = 'sys_admin';
            $map['where_in']['item'] = 'sys_admin.uid';
            $map['where_in']['data'] = $new_user_on ? $new_user_on : array(
                '0'
            );
            $data = $this->Sub_account_model->get_table($map);
        }else{
            $data = $this->Sub_account_model->get_sub_count($map,$limit);
        }
        foreach ($data as $key => $value) {
            if ($data["$key"]['quanxian']=='sadmin') {
                unset($data["$key"]);
            }
        }
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                if(!empty($new_user_on)){
                     if (in_array($val['uid'],$new_user_on)) {
                         $data[$key]['is_state'] = "<span style=\"color:#FF00FF;\">在線</span>";
                      }else{
                         $data[$key]['is_state'] = "<span style=\"color:#999999;\">離線</span>";
                      }
                  }else{
                     $data[$key]['is_state'] = "<span style=\"color:#999999;\">離線</span>";
                  }

                 $data[$key]['updatetime'] = date('Y-m-d H:i:s',$data[$key]['updatetime']);
            }
        }

        $this->add('data',$data);
        $this->add('search_name',$search_name);
        $this->add('search_type',$search_type);
        $this->add('npage',$page);
        $this->add('page',$this->Sub_account_model->get_page('sys_admin',$totalPage,$page));
        $this->display('account/sub_account.html');
    }

    //在线状态
    public function is_online_account($arr){
        $on_user = $this->Sub_account_model->redis_online_account();
        if (!empty($on_user)) {
            foreach ($on_user as $key => $val) {
                $new_user_on[] = str_replace('alg'.$_SESSION['site_id'],'',$val);
            }
            unset($on_user);
        }else{
          $new_user_on = 0;
        }
        return $new_user_on;
    }

    //子账号编辑信息
    public function edit_account(){
        $uid = $this->input->get('uid');
        $username = $this->input->get('uname');
        $zh_name = $this->input->get('zh');//中文昵称
        if (empty($uid) || empty($username)) {
            showmessage('参数错误','back');
        }
        $this->add('uid',$uid);
        $this->add('username',$username);
        $this->add('zh_name',$zh_name);
        $this->display('account/account_add.html');
    }

    public function account_add(){
        $this->display('account/account_add.html');
    }

    //添加处理
    public function account_add_do(){
        $uid = $this->input->post('uid');
        $arr['about'] = $this->input->post('about');
        $arr['login_name_1'] = $this->input->post('login_name_1');//登陆账号
        $arr['login_pwd'] = $this->input->post('login_pwd');
        $repasswd = $this->input->post('repasswd');

        if (empty($arr['about']) || empty($arr['login_pwd']) || empty($repasswd)) {
            showmessage('请完善表单','back');
        }

        if ($arr['login_pwd'] != '******') {
            ///留空表示不更新
            if ($repasswd != $arr['login_pwd']) {
                showmessage('两次密码输入不正确','back',0);
            }
            $arr['login_pwd'] = md5(md5(trim($arr['login_pwd'])));
        }

        if (empty($uid)) {
            //为空添加
            $admin_url = $this->Sub_account_model->rfind(array('table'=>'web_config','where'=>array('site_id'=>$_SESSION['site_id'])));

            if ($this->is_sub_account($arr['login_name_1'])) {
                showmessage('账号已经存在','back');
            }

            $arr['site_id'] = $_SESSION['site_id'];
            $arr['admin_url'] = $admin_url['admin_url'];
            $arr['login_name'] = $_SESSION['site_id'].$arr['login_name_1'];
            $arr['agent_id'] = 0;
            $arr['type'] = 0;
            $arr['add_date'] = date("Y-m-d H:i:s");
            if ($this->Sub_account_model->radd('sys_admin',$arr)) {
                $log['log_info'] = '新增子账号：'.$arr['login_name_1'];
                $this->Sub_account_model->Syslog($log);
                showmessage('添加成功',URL.'/account/sub_account');
            }else{
                showmessage('添加失败','back',0);
            }
        }else{
            //编辑
            unset($arr['login_name_1']);
            if ($this->Sub_account_model->rupdate(array('table'=>'sys_admin','where'=>array('site_id'=>$_SESSION['site_id'],'uid'=>$uid)),$arr)) {
                $log['log_info'] = '更新子账号ID：'.$uid;
                $this->Sub_account_model->Syslog($log);
                showmessage('更新成功',URL.'/account/sub_account');
            }else{
                showmessage('更新失败','back',0);
            }
        }
    }

    //账号重复检查
    public function is_sub_account($username){
        $map = array();
        $map['table'] = 'sys_admin';
        $map['where']['login_name_1'] = $username;
        //$map['where']['site_id'] = $_SESSION['site_id'];
        $is_exist = $this->Sub_account_model->rfind($map);
        if (empty($is_exist)) {
            //不存在
            return 0;
        }else{
            return 1;
        }
    }

    //账号基本状态控制
    public function account_state_do(){
        //子账号暂停 删除 启用操作
        $type = $this->input->get('type');
        $map = array();
        $map['table'] = 'sys_admin';
        $map['where']['uid'] = $this->input->get('uid');
        $map['where']['site_id'] = $_SESSION['site_id'];
        $uname = $this->input->get('uname');
        if (empty($map['where']['uid']) || empty($type)) {
            showmessage('参数错误',URL.'/account/sub_account/index',0);
        }
        $dataP = array();
        switch ($type) {
          case '1':
            //暂停
              $dataP['is_delete'] = 2;
              $this->Sub_account_model->getaccountinfo($this->input->get('uid'));
              if ($this->Sub_account_model->rupdate($map,$dataP)) {
                  $log['log_info'] = '暂停子账号：'.$uname;
                  $this->Sub_account_model->Syslog($log);
                  showmessage('操作成功',URL.'/account/sub_account');

              }else{
                  showmessage('操作失败',URL.'/account/sub_account/index',0);
              }
            break;
          case '2':
            //启用
             $dataP['is_delete'] = 0;
             $dataP['error_num'] = 0;
             if ($this->Sub_account_model->rupdate($map,$dataP)) {
                  $log['log_info'] = '启用子账号：'.$uname;
                  $this->Sub_account_model->Syslog($log);
                  showmessage('操作成功',URL.'/account/sub_account');
             }else{
                  showmessage('操作失败',URL.'/account/sub_account/index',0);
             }
            break;
          case '3':
            //删除
             $dataP['is_delete'] = 1;
             $this->Sub_account_model->getaccountinfo($this->input->get('uid'));
             if ($this->Sub_account_model->rupdate($map,$dataP)) {
                  $log['log_info'] = '删除子账号：'.$uname;
                  $this->Sub_account_model->Syslog($log);
                  showmessage('操作成功',URL.'/account/sub_account');
              }else{
                  showmessage('操作失败',URL.'/account/sub_account/index',0);
              }
            break;
        }
    }

    //子账号权限
    public function authorization(){
        $uid = $this->input->get('uid');
        $username = $this->input->get('uname');
        $catm_max = $this->input->get('catm_max');
        $online_max = $this->input->get('online_max');
        if (empty($uid)) {
            showmessage('参数错误',URL.'/account/sub_account/index',0);
        }

        //全部导航
        $meau_arr = return_meau();

              //获取当前会员权限
        $map = array();
        $map['table'] = 'sys_admin';
        $map['select'] = 'quanxian,mem_auth';
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['uid'] = $uid;
        $user_quanxian = $this->Sub_account_model->rfind($map);
        //权限数组处理
        $typeArr = array('a','b','c','d','e','f');
        foreach ($typeArr as $k => $v) {
            foreach ($meau_arr[$v]['val'] as $key => $val) {
                if (strpos($user_quanxian['quanxian'],$v.$key) !== false) {
                    $meau_arr[$v]['val'][$key] = array('k1'=>$val,'k2'=>1);
                }else{
                    $meau_arr[$v]['val'][$key] = array('k1'=>$val);
                }
            }
            $type_tmp = $v.'_arr';
            $$type_tmp = $this->arr_three($meau_arr[$v]['val']);
            $this->add($v.'_arr',$$type_tmp);
        }

        $mem_meau = $this->mem_meau_check($this->mem_meau(),$user_quanxian['mem_auth']);

        $this->add('online_max',$online_max);
        $this->add('catm_max',$catm_max);
        $this->add('username',$username);
        $this->add('uid',$uid);
        $this->add('mem_meau',$mem_meau);
        $this->display('account/authorization.html');
    }

    //子账号权限处理
    public function authorization_do(){
        $uid = $this->input->post('uid');
        $online_max = $this->input->post('online_max');
        $catm_max = $this->input->post('catm_max');

        $quanxian = $this->input->post('quanxian');
        $mem_auth = $this->input->post('mem_auth');

        $online_max = empty($online_max)?0:$online_max;
        $catm_max = empty($catm_max)?0:$catm_max;
        if (empty($uid)) {
            showmessage('参数错误',URL.'/account/sub_account/index',0);
        }
        $arr = array();
        $arr['quanxian'] = implode(',', $quanxian);
        $arr['mem_auth'] = implode(',', $mem_auth);
        $arr['online_max'] = $online_max;
        $arr['catm_max'] = $catm_max;

        $map = array();
        $map['table'] = 'sys_admin';
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['uid'] = $uid;
        if ($this->Sub_account_model->rupdate($map,$arr)) {
            showmessage('操作成功',URL.'/account/sub_account/index');
        }else{
            showmessage('操作失败',URL.'/account/sub_account/index',0);
        }

    }

    //数组处理 每组四个
    function arr_three($arr = array()){
        $i = $j = 0;
        $return_arr = array();
        foreach ($arr as $key => $val) {
            if ($j%4 == 0) {
                $i++;
            }
            $j++;
            $return_arr[$i][$key] = $val;
        }
        return $return_arr;
    }

    //会员详细控制
    function mem_meau(){
        return array(
            'a' => array(
                'name' => '真实姓名',
                'val' => array('1' => '查看','2' => '修改'),
            ),
            'b' => array(
                'name' => '银行账号',
                'val' => array('1' => '查看','2' => '修改'),
            ),
            'c' => array(
                'name' => '取款密码',
                'val' => array('1' => '查看','2' => '修改'),
            ),
            'd' => array(
                'name' => '手 机 号',
                'val' => array('1'  => '查看','2' => '修改'),
            ),

            'e' => array(
                'name' => '会员邮箱',
                'val' => array('1' => '查看','2' => '修改'),
            ),

            'f' => array(
                'name' => '会员Q Q',
                'val' => array('1' => '查看','2' => '修改'),
            ),
            'g' => array(
                'name' => '身份证号',
                'val' => array('1' => '查看','2' => '修改'),
             ),
        );
    }

    //会员详细权限配置
    function mem_meau_check($arr,$auth_str){
        foreach ($arr as $k => $v) {
          foreach ($v['val'] as $key => $val) {
                if (strpos($auth_str,$k.$key) !== false) {
                    $arr[$k]['val'][$key] = array('k1'=>$val,'k2'=>1);
                }else{
                    $arr[$k]['val'][$key] = array('k1'=>$val);
                }
            }
        }
      return $this->arr_three($arr);
    }

    //ajax检测子账号是否重复
    function is_ajax_check(){
        $login_name_1 = $this->input->get('uname');
        $map = array();
        $map['where']['login_name_1'] = $login_name_1;
        if ($this->Sub_account_model->rfind('sys_admin',$map)) {
            $data = 1;
        }else{
            $data = 2;
        }
        exit(json_encode($data));
    }

}
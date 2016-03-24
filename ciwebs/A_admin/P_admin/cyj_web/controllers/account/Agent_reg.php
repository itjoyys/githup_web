<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Agent_reg extends MY_Controller {

	public function __construct() {
		parent::__construct();
        $this->login_check();
		$this->load->model('account/Agent_reg_model');
	}
    //代理申请列表
	public function index(){
        $index_id = $this->input->get('index_id');
        $uname = $this->input->get('uname');
        $is_delete = $this->input->get('is_delete');
        $page = $this->input->get('page');
        $type = $this->input->get('type');//查询类别
        $index_id = empty($index_id)?'a':$index_id;

        $map = array();
        $map['site_id'] = $_SESSION['site_id'];
        $map['index_id'] = $index_id;
        $map['is_delete'] = array('<>',1);
        $map['is_apply'] = 1;//表示申请状态
        //检索条件
        if(!empty($uname) && !empty($type)){
            $map[$type]=array('like','%'.$uname.'%');
            $this->add('uname',$uname);
            $this->add('type',$type);
        }
        if($is_delete == '0' || $is_delete == '5'){
            $map['is_delete'] = $is_delete;
            $this->add('is_delete',$is_delete);
        }

        $count = $this->Agent_reg_model->get_count($map);

        //分页
        $perNumber = 50;
        $totalPage = ceil($count/$perNumber);
        $page = isset($page)?$page:1;
        $startCount = ($page-1)*$perNumber;
        $limit = $startCount.",".$perNumber;

        $agent = $this->Agent_reg_model->get_agent($map,$limit);
        $pages = $this->Agent_reg_model->get_page($totalPage,$page);

        if (!empty($_SESSION['index_id'])) {
             $this->add('sites_str','站点：'.str_replace('全部', '请选择站点',$this->Agent_reg_model->select_sites()));
        }
        foreach ($agent as $key => $val) {
            switch ($val['is_delete']) {
                case '0':
                    $agent[$key]['state_a'] = '<font>已添加</font>';
                    break;
                case '1':
                    $agent[$key]['state_a'] = '<font style="color:#f00;">停用</font>';
                    break;
                case '5':
                    $agent[$key]['state_a'] = '<font style="color:#f00;">未处理</font>';
                    break;
            }
        }
        $this->add('index_id',$index_id);
        $this->add('agent',$agent);
        $this->add('page',$pages);
        $this->display('account/agent_ex.html');
	}

    //删除申请代理
    public function agent_del(){
        $id = $this->input->get('id');
        $type = $this->input->get('type');
        if (empty($id) || $type != 'del') {
             showmessage('参数错误','back',0);
        }
        $map = $arr = array();
        $map['table'] = 'k_user_agent';
        $map['where']['id'] = $id;
        $arr['is_delete'] = 1;
        if ($this->Agent_reg_model->rupdate($map,$arr)) {
            $log['log_info'] = '删除代理申请,ID：'.$id;
            $this->Agent_reg_model->Syslog($log);
            showmessage('删除成功','index');
        }else{
            showmessage('删除失败','index',0);
        }

    }
    //代理设置
    public function agent_set(){
        $index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?'a':$index_id;
        $map = array();
        $map['table'] = 'k_user_agent_config';
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['index_id'] = $index_id;

        $data = $this->Agent_reg_model->rfind($map);
        //多站点
        if (!empty($_SESSION['index_id'])) {
            $this->add('sites_str','站点：'.str_replace('全部', '请选择站点',$this->Agent_reg_model->select_sites()));
        }
        $this->add('index_id',$index_id);
        $this->add('data',$data);
        $this->display('account/agent_set.html');
    }

    //代理申请配置
    public function agent_set_do(){
        $id = $this->input->post('id');
        $index_id = $this->input->post('index_id');
        $from_url_form = $this->input->post('from_url_form');
        $other_method_form = $this->input->post('other_method_form');
        $is_zh_name = $this->input->post('is_zh_name');
        $is_email = $this->input->post('is_email');
        $is_en_name = $this->input->post('is_en_name');
        $is_card = $this->input->post('is_card');
        $is_qq = $this->input->post('is_qq');

        $is_phone = $this->input->post('is_phone');
        $is_qq = $this->input->post('is_qq');

        $is_zh_name_true = $this->input->post('is_zh_name_true');
        $is_zh_name_true = empty($is_zh_name_true) ? '0' : $is_zh_name_true;

        $is_email_true = $this->input->post('is_email_true');
        $is_email_true = empty($is_email_true) ? '0' : $is_email_true;

        $is_en_name_true = $this->input->post('is_en_name_true');
        $is_en_name_true = empty($is_en_name_true) ? '0' : $is_en_name_true;

        $is_card_true = $this->input->post('is_card_true');
        $is_card_true = empty($is_card_true) ? '0' : $is_card_true;

        $is_qq_true = $this->input->post('is_qq_true');
        $is_qq_true = empty($is_qq_true) ? '0' : $is_qq_true;

        $is_phone_true = $this->input->post('is_phone_true');
        $is_phone_true = empty($is_phone_true) ? '0' : $is_phone_true;

        $from_url_form_true = $this->input->post('from_url_form_true');
        $from_url_form_true = empty($from_url_form_true)?'0':$from_url_form_true;

        $other_method_form_true = $this->input->post('other_method_form_true');
        $other_method_form_true = empty($other_method_form_true)?'0':$other_method_form_true;

        $arr['is_daili'] = $this->input->post('is_daili');
        $arr['from_url_form'] = $from_url_form.'-'.$from_url_form_true;
        $arr['other_method_form'] = $other_method_form.'-'.$other_method_form_true;
        $arr['is_zh_name'] = $is_zh_name.'-'.$is_zh_name_true;
        $arr['is_email'] = $is_email.'-'.$is_email_true;
        $arr['is_en_name'] = $is_en_name.'-'.$is_en_name_true;
        $arr['is_card'] = $is_card.'-'.$is_card_true;
        $arr['is_qq'] = $is_qq.'-'.$is_qq_true;
        $arr['is_payname'] = '0-1';
        $arr['is_phone'] = $is_phone.'-'.$is_phone_true;

        if (!empty($id)) {
            //编辑
            $map['table'] = 'k_user_agent_config';
            $map['where']['site_id'] = $_SESSION['site_id'];
            $map['where']['id'] = $id;
            //编辑
            if($this->Agent_reg_model->rupdate($map,$arr)){
                $log['log_info'] = '设置代理注册设定,ID：'.$id;
                $this->Agent_reg_model->Syslog($log);
                showmessage('更新成功','back');
            }else{
                showmessage('更新失败','back',0);
            }
        }else{
            $arr['site_id'] = $_SESSION['site_id'];
            $arr['index_id'] = $index_id;
            if($this->Agent_reg_model->radd('k_user_agent_config',$arr)){
                $log['log_info'] = '设置会员注册设定,ID：'.$index_id;
                $this->Agent_reg_model->Syslog($log);
                showmessage('设置成功','back');
            }else{
                showmessage('设置失败','back',0);
            }

        }
    }

    //申请资料详情
    public function agent_data(){
        $id = $this->input->get('id');
        $wtype = $this->input->get('wtype');
        if (empty($id)) {
            showmessage('参数错误','back',0);
        }
        $map = array();
        $map['table'] = 'k_user_agent';
        $map['where']['id'] = $id;
        $map['where']['site_id'] = $_SESSION['site_id'];
        $this->add('data',$this->Agent_reg_model->rfind($map));
        $this->add('wtype',$wtype);
        $this->display('account/agent_data.html');
    }

    //代理资料编辑处理
    public function agent_data_do(){
        $id = $this->input->post('id');
        $aname = $this->input->post('agent_login_user');
        if (empty($id)) {
            showmessage('参数错误','back',0);
        }else{
            $map['table'] = 'k_user_agent';
            $map['where']['site_id'] = $_SESSION['site_id'];
            $map['where']['id'] = $id;
            $zh = "/[\u4e00-\u9fa5]+/";
            $num = "/\d+/";
            $email = "/^\w+@\w+(\.\w+)+(\,\w+@\w+(\.\w+)+)*$/";

            $data['realname']     = $this->input->post('realname');
            $data['realname']     = empty($data['realname']) ? '0' : $data['realname'];

            $data['en_name']      = $this->input->post('en_name');
            $data['en_name']      = empty($data['en_name']) ? '0' : $data['en_name'];

            $data['personalid']   = $this->input->post('card');
            $data['personalid']   = empty($data['personalid']) ? '0' : $data['personalid'];

            $data['mobile']       = $this->input->post('mobile');
            $data['mobile']       = empty($data['mobile']) ? '0' : $data['mobile'];

            $data['qq']           = $this->input->post('qq');
            $data['qq']           = empty($data['qq']) ? '0' : $data['qq'];

            $data['email']        = $this->input->post('email');
            $data['email']        = empty($data['email']) ? '0' : $data['email'];

            $data['bankid']       = $this->input->post('bankid');
            $data['bankid']       = empty($data['bankid']) ? '0' : $data['bankid'];

            $data['bankno']       = $this->input->post('bankno');
            $data['bankno']       = empty($data['bankno']) ? '0' : $data['bankno'];

            $data['province']     = $this->input->post('province');
            $data['province']     = empty($data['province']) ? '0' : $data['province'];

            $data['city']         = $this->input->post('city');
            $data['city']         = empty($data['city']) ? '0' : $data['city'];

            $data['safe_pass']    = $this->input->post('safe_pass');
            $data['safe_pass']    = empty($data['safe_pass']) ? '0' : $data['safe_pass'];

            $data['remark']       = $this->input->post('remark');
            $data['remark']       = empty($data['remark']) ? '0' : $data['remark'];

            $data['from_url']     = $this->input->post('website');
            $data['from_url']     = empty($data['from_url']) ? '0' : $data['from_url'];

            $data['other_method'] = $this->input->post('other_website');
            $data['other_method'] = empty($data['other_method']) ? '0' : $data['other_method'];

            if (!empty($data['personalid'])) {
                if (preg_match($zh,$data['personalid'])) {
                    showmessage('请输入正确的身份证号','back',0);
                    exit();
                }
            }
            if (!empty($data['qq'])) {
                if (!preg_match($num,$data['qq'])) {
                    showmessage('请输入正确的QQ号','back',0);
                    exit();
                }
            }
            if (!empty($data['mobile'])) {
                if (!preg_match($num,$data['mobile'])) {
                    showmessage('请输入正确的手机号','back',0);
                    exit();
                }
            }
            if (!empty($data['email'])) {
                if (!preg_match($email,$data['email'])) {
                    showmessage('请输入正确的邮箱','back',0);
                    exit();
                }
            }
            if (!empty($data['bankno'])) {
                if (!preg_match($num,$data['bankno'])) {
                    showmessage('请输入正确的银行帐号','back',0);
                    exit();
                }
            }
            if($this->Agent_reg_model->rupdate($map,$data)){
                $log['log_info'] = '修改代理基本信息,'.$aname;
                $log['uname'] = $aname;
                $log['type'] = 2;
                $this->Agent_reg_model->Syslog($log);
                showmessage('修改成功',URL.'/account/agent_index/index?agent_type=a_t', '1');
            }else{
                showmessage('修改失败','back',0);
            }
        }

    }

    //添加代理处理
    public function agent_apply_do(){
        $index_id = $this->input->get('iid');
        $index_id = empty($index_id)?'a':$index_id;
        $id = $this->input->get('id');

        $map = array();
        $map['table'] = 'k_user_agent';
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['index_id'] = $index_id;
        $map['where']['agent_type'] = 'u_a';
        $map['where']['is_demo'] = 0;
        $map['where']['is_delete'] = 0;
        $this->add('datas',$this->Agent_reg_model->rget($map));
        $this->add('agent_type','a_t');
        $this->add('atype',5);
        $this->display('account/agent_index/agent_add.html');
    }

	 //优惠设定
    public function agent_discount_set(){
        $is_del = $this->input->post('is_del');
        $index_id = $this->input->post('index_id');
        $index_id = empty($index_id)?'a':$index_id;
        $result_id = $this->input->post('result_id');
        $agent_type = $this->input->post('agent_type');
        $set = array();
        $set['is_ip'] = $this->input->post('is_ip');
        $set['discount_money']=$this->input->post('discount_money');
        $set['discount_bet']=$this->input->post('discount_bet');
        $set['agent_type']=$agent_type;
        $set['pid']=$this->input->post('pid');
        $set['agent_id']=$this->input->post('agent_id');
        $uname = $this->input->post('uname');//代理账号
         //是否删除下级设定
        if ($is_del){
            if ($agent_type != 'a_t'){
                $mapb = array();
                $mapb['pid'] =  $set['agent_id'];
                $mapb['site_id'] = $_SESSION['site_id'];
                $mapb['index_id'] = $index_id;
                $data['discount_money'] = 0;
                $data['discount_bet'] = 0;
                $data['is_ip'] = 0;
                $this->Agent_reg_model->M(array('tab' => 'k_user_agent_dis','type' => 1)) ->where($mapb)->update($data);
            }
        }
         $url = URL."/account/agent_index?agent_type=".$agent_type;
        if(empty($result_id)){
            //添加
             $set['site_id'] = $_SESSION['site_id'];
             $set['index_id'] = $index_id;
             if($this->Agent_reg_model->radd("k_user_agent_dis",$set)){
                $log['log_info'] = '设置代理旗下会员注册优惠设定';
                $log['type'] = 2;
                $log['uname'] = $uname;
                $this->Agent_reg_model->Syslog($log);
                showmessage('设定成功',$url);
             }else{
                showmessage('设定失败',$url,0);
             }
        }else{
            //编辑
            $edit['table'] = 'k_user_agent_dis';
            $edit['where']['site_id'] = $_SESSION['site_id'];
            $edit['where']['id'] = $result_id;
            if($this->Agent_reg_model->rupdate($edit,$set)){
                $log['log_info'] = '设置代理旗下会员注册优惠设定';
                $log['type'] = 2;
                $log['uname'] = $uname;
                $this->Agent_reg_model->Syslog($log);
                showmessage('设定成功',$url);
            }else{
                showmessage('设定失败',$url,0);
            }
        }
    }

      //添加代理优惠设定
    public function agent_discount(){
        $index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?'a':$index_id;
        $agent_id = $this->input->get('id');
        $agent_type = $this->input->get('agent_type');
        if (empty($agent_id)) {
            showmessage('参数错误','back',0);
        }
        $maps = array();
        $maps['table'] = 'k_user_agent';
        $maps['where']['site_id'] = $_SESSION['site_id'];
        $maps['where']['id'] = $agent_id;
        $data = $this->Agent_reg_model->rfind($maps);

        $map = array();
        $map['table'] = 'k_user_agent_dis';
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['agent_id'] = $agent_id;
        $map['where']['agent_type'] = $agent_type;
        if ($agent_type == "s_h"){
            $map['where']['pid'] = 0;
        }
        $this->add('result',$this->Agent_reg_model->rfind($map));
        $this->add('agent_type',$agent_type);
        $this->add('agent_id',$agent_id);
        $this->add('data',$data);
        $this->display('account/agent_index/agent_discount.html');
    }

}
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Agent_examine extends MY_Controller
{
    // 代理申请管理
    public function __construct()
    {
        parent::__construct();
        $this->login_check();
        $this->load->model('account/Agent_examine_model');
    }

    public function Index()
    {
        // 查询多站点
        if (! empty($_SESSION['index_id'])) {
            $this->add('sites_str', $this->Agent_examine_model->select_sites());
        }
        $this->get_user_all(); //调用列表方法
        $this->display('account/agent_examine/index.html');
    }
    //列表方法
    private function get_user_all()
    {
        $site_id = $_SESSION['site_id'];
        $page_num = $this->input->get('page_num');
        $page = $this->input->get('page');
        $enable = $this->input->get('enable');
        $search_type = $this->input->get('search_type');
        $search_name = $this->input->get('search_name');
        $mem_status = $this->input->get('mem_status');
        $index_id = $this->input->get('index_id');
        $pagenum = isset($page_num) ? $page_num : 20; // 每页显示的记录数
        $CurrentPage = isset($page) ? $page : 1;
        $map['order'] = 'is_delete desc,id desc';
        $map['where']['is_apply'] = 1;
        $map['table'] = 'k_user_agent';
        if (! empty($index_id)) {
            $map['where']['index_id'] = $index_id;
        }

        if (! empty($enable)) {
            $map['where']['is_delete'] = $enable;
        }
        $map['where']['site_id'] = $site_id;
        if (! empty($search_name)) {
            $map['like']['title'] = $search_type;
            $map['like']['match'] = $search_name;
            $map['like']['after'] = 'both';
        }

        $sum = $this->Agent_examine_model->get_table_count($map);
        $totalPage = ceil($sum / $pagenum); // 计算出总页数
        if ($totalPage < $CurrentPage) {
            $CurrentPage = 1;
        }

        $map['pagecount'] = $pagenum;
        $map['offset'] = $page > 1 ? ($page - 1) * $pagenum : 0;
        $list = $this->Agent_examine_model->get_table($map);
        $this->add('totalPage', $totalPage);
        $this->add('list', $list);
    }
    //打开修改信息页面
  public function agent_data(){

      $uid  =$this->input->get('uid');
      $site_id = $_SESSION['site_id'];
      if (empty($uid)) {showmessage('非法操作', 'back', '0');}
          $data=$this->Agent_examine_model->M(array('tab' => 'k_user_agent','type' => 1 ))
          ->where('id='.$uid)->find();
          $data['agent_user'] = $data['agent_user']?$data['agent_user']:$data['agent_login_user'];
          $addre = explode(",",$data['bank_account']);
          $this->add('data', $data);
          $this->display('account/agent_examine/agent_data.html');
  }
  //修改信息提交
  public function agent_data_do(){
      //修改数据
      $id = $this->input->post('id');
      if (empty($id)) {showmessage('非法操作', 'back', '0');}
      $map['data']['realname']=$this->input->post('realname');
      $map['data']['en_name']=$this->input->post('en_name');
      $map['data']['personalid']=$this->input->post('card');
      $map['data']['mobile']=$this->input->post('mobile');
      $map['data']['qq']=$this->input->post('qq');
      $map['data']['email']=$this->input->post('email');

      $map['data']['bankid']=$this->input->post('bankid');
      $map['data']['bankno']=$this->input->post('bankno');
      $map['data']['province']=$this->input->post('province');

      $map['data']['city']=$this->input->post('city');
      $map['data']['safe_pass']=$this->input->post('safe_pass');
      $map['data']['remark']=$this->input->post('remark');
      $map['data']['other_method']=$this->input->post('other_website');
      $map['data']['from_url']=$this->input->post('website');
      $map['data']['zh_name']=$this->input->post('nickname');
      $map['data']['grounds']=$this->input->post('grounds');
      $map['where']['id'] = $id;
      $map['table'] = 'k_user_agent';
      $result=$this->Agent_examine_model->update_table($map);
      if($result){
          //获取代理账号
          $alog = $this->get_aname($id);
          $do_log['log_info'] = '修改代理详细信息成功:' . $id;
          $do_log['uname'] = $alog['agent_login_user'];
          $do_log['type'] = 2;
          $this->Agent_examine_model->Syslog($do_log);
          showmessage('修改代理详细信息成功', 'back', '0');
      }else{
          $do_log['log_info'] = '修改代理详细信息失败:' . $id;
          $this->Agent_examine_model->Syslog($do_log);
          showmessage('修改代理详细信息失败', 'back', '0');
      }
  }

  //获取代理账号
  function get_aname($id){
      $map = array();
      $map['table'] = 'k_user_agent';
      $map['select'] = 'agent_login_user';
      $map['where']['site_id'] = $_SESSION['site_id'];
      $map['where']['id'] = $id;
      return $this->Agent_examine_model->rfind($map);
  }
}
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
        $this->get_agent_all();
        $this->display('account/agent_index/agent_index.html');

    }

    private function get_agent_all()
    {
        $data = $this->Agent_index_model->get_all_agents();
        $this->add('datas',$data);
        $sum = count($data);
        $page = $this->input->get('page');
        $mem_sort = $this->input->get('mem_sort');
        $mem_order = $this->input->get('mem_order');
        $agent_user = $this->input->get('agent_user');
        $is_delete = $this->input->get('is_delete');
        $perNumber = 50; // 每页显示的记录数
        $page = isset($page) ? $page : 1;
        $totalPage = ceil($sum / $perNumber); // 计算出总页数
        $page = isset($page)?$page:1;
        if($totalPage<$page){
          $page = 1;
        }
        $startCount =($page-1)*$perNumber;
        $limit = $startCount.",".$perNumber;
        $index_id = $_SESSION['index_id'];
        $site_id = $_SESSION['site_id'];
        $is_quanxian = $_SESSION['guanliyuan'];
        $agentsstr = $_SESSION['agent_ids'];
        $list = $this->Agent_index_model->get_agents($index_id,$site_id,$agentsstr,$is_quanxian,$mem_order,$mem_sort,$agent_user,$is_delete,$limit);
        foreach ($list as $key => $value) {
            $list[$key]['is_delete'] = $this->Agent_index_model->get_sibling_del($value['id']);
                $usermap2['table'] = 'k_user';
                $usermap2['select'] = 'uid';
                $usermap2['where']['agent_id'] = $value['id'];
                $usermap2['where']['shiwan'] = 0;
                $user_data_3 = $this->Agent_index_model->get_table_count($usermap2); // huiyuan
                $list[$key]['user_num'] += $user_data_3;
                if (! $list[$key]['user_num']) {
                    $list[$key]['user_num'] = '无';
                }
        }
        $this->add('page', $this->Agent_index_model->get_page('k_user_agent',$totalPage,$page));
        $this->add('agent_type',$agent_type);
        $this->add('index_id',$index_id);
        $this->add('totalPage', $totalPage);
        $this->add('list', $list);
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
        $this->add('data',$this->Agent_index_model->rfind($map));
        $this->add('wtype',$wtype);
        $this->display('account/agent_data.html');
    }


}
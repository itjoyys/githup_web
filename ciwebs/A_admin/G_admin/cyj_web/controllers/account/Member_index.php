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
        // 查询用户
        $index_id = $this->input->get('index_id');
        $site_id = $_SESSION['site_id'];
        $is_quanxian = $_SESSION['guanliyuan'];
        $agentsstr = $_SESSION['agent_ids'];
        $map = array();
        $map['table'] = 'k_user';
        $map['where']['site_id'] = $site_id;
        $map['where']['shiwan'] = 0;
        if ($is_quanxian == 1){
            $map['in']['agent_id'] = $agentsstr;
        }else{
            $map['where']['agent_id'] = $_SESSION['agent_id'];
        }
        $map['like']['title'] = 'reg_date';
        $map['like']['match'] = date('Y-m-d');
        $map['like']['after'] = 'after';
        $todayReg_count = $this->Member_index_model->get_table_count($map);
          // 查询代理
        $agent_up = $this->Member_index_model->get_agents($index_id,$site_id,$agentsstr,$is_quanxian);
        $this->get_user_all();
        $this->add('is_guanliyuan', $is_quanxian);
        $this->add('todayReg_count', $todayReg_count);
        $this->add('agent_up', $agent_up);
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
        $agentsstr = $_SESSION['agent_ids'];
        $mem_enable = $this->input->get('mem_enable');
        $agent_user = $this->input->get('agent_user');
        $search_type = $this->input->get('search_type');
        $search_name = $this->input->get('search_name');
        $mem_status = $this->input->get('mem_status');
        $pagenum = 50; // 每页显示的记录数
        $CurrentPage = isset($page) ? $page : 1;
        $map['select'] = "k_user.*,k_user_agent.agent_user,k_user_agent.agent_name";
        $map['where']['shiwan'] = 0;
        $res_user_list = $this->Member_index_model->on_user();
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
        $map['join']['table'] = 'k_user_agent';
        $map['join']['action'] = 'k_user_agent.id = k_user.agent_id';
        $map['table'] = 'k_user';

        if (! empty($mem_enable)) {
            $mem_enable = $mem_enable == 'stat' ? '0' : $mem_enable;
            $map['where']['k_user.is_delete'] = $mem_enable;
        }
        $map['where']['k_user.site_id'] = $site_id;
        if (!empty($index_id)) {
                $map['where']['k_user.index_id'] = $index_id;
        }
        if (! empty($start_date) && ! empty($end_date)) {
            $map['where']['k_user.reg_date >='] = $start_date;
            $map['where']['k_user.reg_date <='] = $end_date;
        }
		 if (! empty($agent_id)) {
            $map['where']['k_user.agent_id'] = $agent_id;
        }
        if ($_SESSION['guanliyuan'] == 0){
                $map['where']['k_user.agent_id'] = $_SESSION['agent_id'];
        }else{
			$s=str_replace('"', '', $_SESSION['agent_ids']);
			$arr = explode(",",$s);
                 $map['where_in']['item'] =  "agent_id";
				 $map['where_in']['data'] =  $arr;
				 
        }
        if (! empty($mem_enable)) {
            $map['where']['k_user.is_delete'] = $mem_enable;
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
        $sum = $this->Member_index_model->get_table_count($map);
        if (empty($start_date) && empty($end_date)) {
            $sum_title = '总会员数:' . $sum;
        } else {
            $sum_title = '此日期内会员数:' . $sum;
        }
        $totalPage = ceil($sum / $pagenum); // 计算出总页数
        if ($totalPage < $CurrentPage) {
            $CurrentPage = 1;
        }
        $map['pagecount'] = $pagenum;
        $map['offset'] = $page > 1 ? ($page - 1) * $pagenum : 0;
		if (!empty($mem_status) && !empty($res_user_list) || empty($mem_status)) {
            $list = $this->Member_index_model->get_table($map);
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
        $this->add('list', $list);
    }

}
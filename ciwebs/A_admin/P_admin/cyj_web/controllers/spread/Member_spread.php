<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//会员推广系统
class Member_spread extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('spread/Member_spread_model');
	}
    //会员推广系统   统计推广了会员的人
	public function index()
	{
	    $index_id = $this->input->get('index_id');
        // 查询用户
        $site_id = $_SESSION['site_id'];
        // 查询代理
        $agent_up = $this->Member_spread_model->get_agents($index_id);

        if (!empty($_SESSION['index_id'])) {
            $this->add('sites_str','站点：'.$this->Member_spread_model->select_sites());
        }
        //获取推广过会员的会员信息
        $this->get_user_all();
        $this->add('agent_up', $agent_up);
        $this->add('siteid',$_SESSION['site_id']);
				$this->display('spread/member_spread_index.html');
	}


	private function get_user_all()
    {
        $site_id = $_SESSION['site_id'];
        $page = $this->input->get('page');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $search_type = $this->input->get('search_type');
        $search_name = $this->input->get('search_name');
        $index_id = $this->input->get('index_id');
        $page_num = $this->input->get('page_num');
        $top_uid = $this->input->get('top_uid');

        if(!empty($top_uid)){
        	$map['where']['k_user.top_uid'] = $top_uid;
		}else{
			$map['where']['k_user.spread_num'] = array('>',0);
		}

		$this->add('top_uid',$top_uid);
        $pagenum = isset($page_num)?$page_num:100; // 每页显示的记录数
        $page = isset($page) ? $page : 1;

        $map['where']['site_id'] = $site_id;
        $map['where']['shiwan'] = 0;

        //获取在线会员id
        $res_user_list = $this->Member_spread_model->on_user();
        if (! empty($mem_status)) {
            $map['where_in']['item'] = 'k_user.uid';
            $map['where_in']['data'] = $res_user_list ? $res_user_list : array(
                '0'
            );
        }

        $map['order'] = "`reg_date` desc";    //注册时间倒序排列
        $map['table'] = 'k_user';
        if (!empty($index_id)) {
            $map['where']['k_user.index_id'] = $index_id;
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

        $sum = $this->Member_spread_model->get_menber_sum($map);

        $totalPage = ceil($sum / $pagenum); // 计算出总页数
        if ($totalPage < $page) {
            $page = 1;
        }

        $map['pagecount'] = $pagenum;
        $map['offset'] = $page > 1 ? ($page - 1) * $pagenum : 0;
		    //p($map);die;
		$list = $this->Member_spread_model->get_menber_list($map);

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
        $this->add('totalPage', $totalPage);
        $this->add('site_id',$_SESSION['site_id']);
        $this->add('list', $list);
    }

	//返点优惠统计
	public function member_spread_dis_count(){
	    $this->display('spread/member_spread_count.html');
	}


}

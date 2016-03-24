<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Account_search extends MY_Controller
{
    // protected $site_id = $_SESSION['site_id'];
    public function __construct()
    {
        parent::__construct();
        $this->login_check();
        $this->load->model('account/Account_search_model');
    }

    public function Index()
    {
        $search_type = $this->input->get('search_type');
        $account = $this->input->get('account');
        // 查询用户
        switch ($search_type) {
            case 1:
                $this->get_shareholder();
                break;
            case 2:
                $this->get_up_agent();
                break;
            case 3:
                $this->get_agent();
                break;
            case 4:
                $this->get_user();
                break;
            case 5:
                $this->get_intr();
                break;
            default:
                ;
                break;
        }

        $this->display('account/account_search/index.html');
    }

    private function get_intr()
    {
        $account = $this->input->get('account');
        $allagent = $this->Account_search_model->M(array(
            'tab' => 'k_user_agent',
            'type' => 1
        ))
            ->where("is_delete=0 and site_id='" . $_SESSION['site_id'] . "' and is_demo = '0'")
            ->select();
        $sql = "k_user_agent.site_id='" . $_SESSION['site_id'] . "' and k_user_agent.intr like '%" . $account . "%' and k_user_agent.agent_type = 'a_t' and k_user_agent.is_demo = '0'";
        $user = $this->Account_search_model->M(array(
            'tab' => 'k_user_agent',
            'type' => 1
        ))
            ->join("left join k_user on k_user.agent_id = k_user_agent.id")
            ->field("count(k_user.uid) as username,k_user_agent.id as agent_id")
            ->where($sql)
            ->group("agent_id")
            ->select();

        if (! empty($user)) {
            foreach ($user as $k => $v) {
                $arr = $this->Account_search_model->getParents($allagent, $v['agent_id']);
                $user[$k] = array_merge($arr, $v);
                $user[$k]['a_t']=$user[$k]['a_t'] . "(代理推广id:" . $user[$k]['intr'] . ")";
            }
            $this->add('user', $user);
        }
    }

    private function get_user()
    {
        $account = $this->input->get('account');
        $like = $this->input->get('like');
        $allagent = $this->Account_search_model->M(array(
            'tab' => 'k_user_agent',
            'type' => 1
        ))
            ->where("is_delete=0 and site_id='" . $_SESSION['site_id'] . "' and is_demo = '0'")
            ->select();
        if ($like){
             $sql = "site_id = '" . $_SESSION['site_id'] . "' and username like '" ."%". $account."%" . "' and shiwan <> 1 ";
         }else{
            $sql = "site_id = '" . $_SESSION['site_id'] . "' and username = '" .$account. "' and shiwan <> 1 ";
         }

        $user = $this->Account_search_model->M(array(
            'tab' => 'k_user',
            'type' => 1
        ))
            ->where($sql)
            ->field('uid,username,agent_id')
            ->select();
        if (! empty($user)) {
            foreach ($user as $k => $v) {
                $arr = $this->Account_search_model->getParents($allagent, $v['agent_id']);
                $user[$k] = array_merge($arr, $v);
            }
            $this->add('like', $like);
            $this->add('user', $user);
        }
    }

    private function get_agent()
    {
        $account = $this->input->get('account');
        $like = $this->input->get('like');
        $allagent = $this->Account_search_model->M(array(
            'tab' => 'k_user_agent',
            'type' => 1
        ))
            ->where("is_delete=0 and site_id='" . $_SESSION['site_id'] . "' and is_demo = '0'")
            ->select();
        if ($like){
           $sql = "k_user_agent.site_id='" . $_SESSION['site_id'] . "' and k_user_agent.agent_user like '%" . $account . "%' and k_user_agent.agent_type = 'a_t' and k_user_agent.is_demo = '0'";
       }else{
            $sql = "k_user_agent.site_id='" . $_SESSION['site_id'] . "' and k_user_agent.agent_user ='" . $account . "' and k_user_agent.agent_type = 'a_t' and k_user_agent.is_demo = '0'";
       }
        $user = $this->Account_search_model->M(array(
            'tab' => 'k_user_agent',
            'type' => 1
        ))
            ->join("left join k_user on k_user.agent_id = k_user_agent.id")
            ->field("count(k_user.uid) as username,k_user_agent.id as agent_id")
            ->where($sql)
            ->group("agent_id")
            ->select();

        if (! empty($user)) {
            foreach ($user as $k => $v) {
                $arr = $this->Account_search_model->getParents($allagent, $v['agent_id']);
                $user[$k] = array_merge($arr, $v);
                $user[$k]['a_t']=$user[$k]['a_t'] . "(代理推广id:" . $user[$k]['intr'] . ")";
            }
            $this->add('like', $like);
            $this->add('user', $user);
        }
    }

    private function get_up_agent()
    {
        $account = $this->input->get('account');
        $like = $this->input->get('like');
       $map['table'] = 'k_user_agent';
        $map['select'] = 'id,pid,agent_user';
        $map['where']['is_demo'] = 0;
        $map['where']['agent_type'] = 'u_a';
        $map['where']['site_id'] = $_SESSION['site_id'];
        if ($like){
           $map['like']['title'] = 'agent_user';
           $map['like']['match'] = $account;
           $map['like']['after'] = 'both';
        }else{
           $map['where']['agent_user'] = $account;
        }
        $user = $this->Account_search_model->get_table($map);
        if (! empty($user)) {
            foreach ($user as $k => $v) {
                $agent_c = 0;
                $agent_1 = $this->Account_search_model->M(array(
                    'tab' => 'k_user_agent',
                    'type' => 1
                ))
                    ->where("pid = '" . $v['id'] . "'")
                    ->select();
                if (! empty($agent_1)) {
                    foreach ($agent_1 as $key => $val) {
                        $agent_count = $this->Account_search_model->M(array(
                            'tab' => 'k_user',
                            'type' => 1
                        ))
                            ->field("count(uid) as count")
                            ->where("agent_id = '" . $val['id'] . "'")
                            ->find();
                        $agent_c += $agent_count['count'];
                    }
                }

                $s_h = $this->Account_search_model->M(array(
                    'tab' => 'k_user_agent',
                    'type' => 1
                ))
                    ->where("id = '" . $v['pid'] . "'")
                    ->field('agent_user')
                    ->find();
                $user[$k]['s_h'] = $s_h['agent_user'];
                $user[$k]['u_a'] = $v['agent_user'];
                $user[$k]['a_t'] = count($agent_1);
                $user[$k]['username'] = $agent_c;
            }
            $this->add('like', $like);
            $this->add('user', $user);
        }
    }

    private function get_shareholder()
    {
        $account = $this->input->get('account');
        $like = $this->input->get('like');
        $map['table'] = 'k_user_agent';
        $map['select'] = 'id,pid,agent_user';
        $map['where']['is_demo'] = 0;
        $map['where']['agent_type'] = 's_h';
        $map['where']['site_id'] = $_SESSION['site_id'];
        if ($like){
          $map['like']['title'] = 'agent_user';
          $map['like']['match'] = $account;
          $map['like']['after'] = 'both';
        }else{
          $map['where']['agent_user'] = $account;
        }

        $user = $this->Account_search_model->get_table($map);

        $upagentmap['table'] = 'k_user_agent';
        $upagentmap['select'] = 'id,pid,agent_user,agent_type';
        $upagentmap['where']['is_demo'] = 0;
        $upagentmap['where']['agent_type <>'] = 's_h';
        $upagentmap['where']['site_id'] = $_SESSION['site_id'];
        $upagent = $this->Account_search_model->get_table($upagentmap);
        if (! empty($user)) {
            foreach ($user as $key => $val) {
                $c_data = $this->Account_search_model->getChildsCount($upagent, $val['id'], 'u_a', 'a_t');
                $user[$key]['s_h'] = $val['agent_user'];
                $user[$key]['u_a'] = $c_data['u_a'] + 0;
                $user[$key]['a_t'] = $c_data['a_t'] + 0;

                $agents_id = $this->Account_search_model->getChildsId($upagent, $val['id'], 'a_t'); // 股东旗下所有会员id
                if (! empty($agents_id)) {
                    $usermap['table'] = 'k_user';
                    $usermap['where']['site_id'] = $_SESSION['site_id'];
                    $usermap['where']['shiwan <>'] = 2;
                    $usermap['where_in']['item'] = 'agent_id';
                    $usermap['where_in']['data'] = $agents_id;
                    $user[$key]['username'] = $this->Account_search_model->get_table_count($usermap);
                } else {
                    $user[$key]['username'] = 0;
                }
            }
            $this->add('user', $user);
        }
    }
}
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cash_system extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->login_check();
        $this->load->model('cash/Cash_system_model');
    }

    public function index() {
        //时间条件
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $timearea = $this->input->get('timearea'); //时区
        $timearea = empty($timearea) ? 0 : $timearea;
        $index_id = $this->input->get('index_id'); //站点切换
        $deptype = $this->input->get('deptype'); //类型
        $uid = $this->input->get('uid'); //其它链接过来的uid

        $page = $this->input->get('page');
        $username = $this->input->get('username');
        $order_num = $this->input->get('number');

        if (!empty($start_date)) {
            $s_date = $start_date;
        } else {
            $s_date = $start_date = date("Y-m-d");
        }
        if (!empty($end_date)) {
            $e_date = $end_date;
        } else {
            $e_date = $end_date = date("Y-m-d");
        }

        //查询时间判断
        about_limit($end_date,$start_date);

        $start_date = strtotime($start_date . ' 00:00:00') - $timearea * 3600;
        $end_date = strtotime($end_date . ' 23:59:59') - $timearea * 3600;

        $start_date = date('Y-m-d H:i:s', $start_date);
        $end_date = date('Y-m-d H:i:s', $end_date);

        //$map = "k_user_cash_record.site_id = '".$_SESSION['site_id']."' and k_user_cash_record.cash_date >= '".$start_date."' and k_user_cash_record.cash_date <= '".$end_date."' and k_user.shiwan = '0' ";
        $db_model2 = array();
        $db_model2['tab'] = 'k_user_agent';
        $db_model2['type'] = 1;
        $map2 = "site_id = '" . $_SESSION['site_id'] . "' and is_demo=1 and agent_type='a_t'";
        $agent_id = $this->Cash_system_model->M($db_model2)->field('id')->where($map2)->getField('id');
        $map = "k_user_cash_record.site_id = '" . $_SESSION['site_id'] . "' and k_user_cash_record.cash_date >= '" . $start_date . "' and k_user_cash_record.cash_date <= '" . $end_date . "' and k_user_cash_record.agent_id <>$agent_id ";

        if (!empty($index_id)) {
            $map .= " and k_user_cash_record.index_id = '" . $index_id . "' ";
            $this->add('index_id', $index_id);
        }

        //方式
        if (!empty($deptype)) {
            $map .= $this->deptype($deptype);
        }

        //账户查询
        if (!empty($username)) {
            $map .= " and k_user_cash_record.username = '" . $username . "' ";
            $this->add('username', $username);
        }

        //其它链接过来的uid
        if (!empty($uid)) {
            $map .= "and k_user_cash_record.uid = '" . $uid . "'";
        }

        // 订单号查询
        if (!empty($order_num)) {
            $map .= "and k_user_cash_record.remark like '%" . $order_num . "%'";
        }

        //获得记录总数
        $db_model = array();
        $db_model['tab'] = 'k_user_cash_record';
        $db_model['base_type'] = 1;
        //$join = 'left join k_user on k_user.uid = k_user_cash_record.uid';

        $redis = new Redis();
        $redis->connect(REDIS_HOST, REDIS_PORT);
        $rediskey = $db_model['tab'] . $db_model['base_type'] . 'left join k_user on k_user.uid = k_user_cash_record.uid' . $map;
        $rediskey = 'k_user_cash_record_count' . md5($rediskey);
        $count = json_decode($redis->get($rediskey), true);
        if (!$count) {
            $count = $this->Cash_system_model->mcount($map, $db_model, $join);
            $redis->setex($rediskey, '20', json_encode($count));
        }

        //分页
        $page_num = 50;
        $totalPage = ceil($count / $page_num);
        $page = isset($page) ? $page : 1;
        if ($totalPage < $page) {
            $page = 1;
        }
        $startCount = ($page - 1) * $page_num;
        $limit = $startCount . "," . $page_num;
        $record = array();
        $record = $this->Cash_system_model->get_all_system($map, $limit);
        $count_c = 0;
        foreach ($record as $key => $val) {
            if ($deptype == 'gs_11' || $deptype == 'xs_10') {
                //公司入款不含优惠
                $val['discount_num'] = 0;
            }

            $record[$key]['cash_type_zh'] = $this->cash_type_r($val['cash_type']);
            $record[$key]['cash_do_type_zh'] = $this->cash_do_type_r($val['cash_do_type']);
            $count_c +=$val['cash_num'] + $val['discount_num'];
        }

        //多站点判断
        if (!empty($_SESSION['index_id'])) {
            $this->add('sites_str', '站点：' . $this->Cash_system_model->select_sites());
        }
        $this->add('record', $record);

        $all_count = $this->Cash_system_model->get_all_count($map);
        if ($deptype == 'gs_11' || $deptype == 'xs_10') {
            //公司入款不含优惠
            $all_count['Dnum'] = 0;
        }

        //总计
        $this->add('all_count', $all_count);
        //分页
        $this->add('page', $this->Cash_system_model->get_page('k_user_cash_record', $totalPage, $page));
        $this->add('s_date', $s_date);
        $this->add('page_num', $page_num);
        $this->add('count_c', $count_c);
        $this->add('deptype', $deptype);
        $this->add('timearea', $timearea);
        $this->add('site_id',$_SESSION['site_id']);
        $this->add('e_date', $e_date);
        $this->display('cash/cash_system.html');
    }

    //条件类型处理
    function deptype($type) {
        if (empty($type)) {
            return '';
        }
        $arrType = explode('-', $type);
        if (count($arrType) > 1) {
            //表示检索参数cash_do_type
            return " and ((k_user_cash_record.cash_do_type = '" . $arrType[0] . "' and k_user_cash_record.cash_type = '" . $arrType[1] . "' ) or k_user_cash_record.cash_do_type = '" . $arrType[2] . "') ";
        }
        switch ($type) {
            case 'xsqk'://线上取款 包括拒绝取消
                return " and k_user_cash_record.cash_type in (7,23,19) ";
                break;
            case 'in'://入款明细 公司入款 线上入款 人工存入 优惠退水 优惠活动
                return " and (k_user_cash_record.cash_do_type = '3' or k_user_cash_record.cash_type in (6,9,10,11)) ";
                break;
            case 'out'://出款明细
                return " and ((k_user_cash_record.cash_do_type in (2,4) and k_user_cash_record.cash_type = '12') or k_user_cash_record.cash_type in (7,19,23)) ";
                break;
            case 'ot'://全网活动优惠
                return " and k_user_cash_record.cash_only = '1' ";
                break;
            case 'wx'://无效注单
                return " and  k_user_cash_record.cash_type in (22,25,26) ";
                break;
            case 'cel'://取消注单
                return " and  k_user_cash_record.cash_type in (27,28) ";
                break;
            case 'gx'://公司入款和线上入款
                return " and k_user_cash_record.cash_type in (10,11) ";
                break;
            case 'gs_11'://公司入款不含优惠
                return " and k_user_cash_record.cash_type = 11 ";
                break;
            case 'xs_10'://线上入款不含优惠
                return " and k_user_cash_record.cash_type = 10 ";
                break;
            default:
                return " and k_user_cash_record.cash_type = '" . $type . "' ";
                break;
        }
    }

    //类型
    function cash_type_r($type) {
        switch ($type) {
            case '1':
                return '額度轉換';
                break;
            case '2':
                return '体育下注';
                break;
            case '3':
                return '彩票下注';
                break;
            case '4':
                return '视讯下注';
                break;
            case '5':
                return '彩票派彩';
                break;
            case '6':
                return '活动优惠';
                break;
            case '7':
                return '系统拒绝出款';
                break;
            case '8':
                return '系统取消出款';
                break;
            case '9':
                return '优惠退水';
                break;
            case '10':
                return '在线存款';
                break;
            case '11':
                return '公司入款';
                break;
            case '12':
                return '存入取出';
                break;
            case '13':
                return '优惠冲销';
                break;
            case '14':
                return '彩票派彩';
                break;
            case '15':
                return '体育派彩';
                break;
            case '19':
                return '线上取款';
                break;
            case '20':
                return '和局返本金';
            case '22':
                return '体育无效注单';
            case '23':
                return '系统取消出款';
            case '24':
                return '系统拒绝出款';
            case '25':
                return '彩票无效注单';
            case '26':
                return '彩票无效注单(扣本金)';
            case '27':
                return '注单取消(彩票)';
            case '28':
                return '注单取消(体育)';
            case '33':
                return '自助返水';
            case '34':
                return 'EG电子下注';
            case '35':
                return 'EG电子派彩';
            case '36':
                return '注单取消(EG电子)';
        }
    }

    //返回交易类别
    function cash_do_type_r($do_type) {
        switch ($do_type) {
            case '1':
                return '存入';
                break;
            case '2':
                return '取出';
                break;
            case '3':
                return '人工存入';
                break;
            case '4':
                return '人工取出';
                break;
            case '5':
                return '扣除派彩';
                break;
            case '6':
                return '返回本金';
                break;
        }
    }

}

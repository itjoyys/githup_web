<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cash_system_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    //查询现金记录
    public function get_all_system($map, $limit) {

        $db_model['tab'] = 'k_user_cash_record';
        $db_model['type'] = 1;
        $db_model['is_port'] = 1; //读取从库

        $redis = new Redis();
        $redis->connect(REDIS_HOST, REDIS_PORT);
        $rediskey = $db_model['tab'] . $db_model['type'] . $db_model['is_port'] . 'k_user_cash_record.*left join k_user on k_user.uid = k_user_cash_record.uid' . $map . 'id desc';
        $rediskey.= $limit;
        $rediskey = 'k_user_cash_record' . md5($rediskey);
        $d = json_decode($redis->get($rediskey), true);

        //echo $d;exit;
        if (!$d) {
            $d = $this->M($db_model)->
                    field('/* parallel */ k_user_cash_record.*')->
                    //join("left join k_user on k_user.uid = k_user_cash_record.uid")->
                    where($map)->
                    order('id desc')->
                    limit($limit)->
                    select();
            $redis->setex($rediskey, '20', json_encode($d));
        }


        return $d;
    }

    //总计
    public function get_all_count($map) {
        $db_model['tab'] = 'k_user_cash_record';
        $db_model['type'] = 1;
        $db_model['is_port'] = 1; //读取从库

        $redis = new Redis();
        $redis->connect(REDIS_HOST, REDIS_PORT);
        $rediskey = $db_model['tab'] . $db_model['type'] . $db_model['is_port'] . 'count(k_user_cash_record.id) as allnum,sum(k_user_cash_record.cash_num) as Cnum,sum(k_user_cash_record.discount_num) as Dnumleft join k_user on k_user.uid = k_user_cash_record.uid' . $map;
        $rediskey.=$limit;
        $rediskey = 'k_user_cash_record_get_all_count' . md5($rediskey);
        $d = json_decode($redis->get($rediskey), true);
        if (!$d) {
            //$d =  $this->M($db_model)->join("left join k_user on k_user.uid = k_user_cash_record.uid")->field("count(k_user_cash_record.id) as allnum,sum(k_user_cash_record.cash_num) as Cnum,sum(k_user_cash_record.discount_num) as Dnum")->where($map)->find();
            $d = $this->M($db_model)->field("/* parallel */ count(k_user_cash_record.id) as allnum,sum(k_user_cash_record.cash_num) as Cnum,sum(k_user_cash_record.discount_num) as Dnum")->where($map)->find();

            $redis->setex($rediskey, '20', json_encode($d));
        }
        return $d;
    }

}

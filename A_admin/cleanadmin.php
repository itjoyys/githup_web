<?php
/*
 * @mebar 2015-03-19
 * 定时清理离线用户
 */
include_once(dirname(__FILE__)."/include/config.php");
include(dirname(__FILE__)"/include/private_config.php");
include_once(dirname(__FILE__)."/lib/class/model.class.php");
//组合配置
$db_config = array();
$db_config['host'] = $_DBC['private']['host'];
$db_config['user'] = $_DBC['private']['user'];
$db_config['pass'] = $_DBC['private']['pwd'];
$db_config['dbname'] = $_DBC['private']['dbname'];

//读取超时用户
$objSys =  M('sys_admin', $db_config);
$time = time() - 24 * 60;
$online = $objSys ->where("updatetime<='$time' and is_login >0")
        ->field("uid,ssid")
        ->order('updatetime ASC')
        ->select();

if (count($online) > 0) {
    //更新状态
    $objSys->where("updatetime<='$time' and is_login >0")
           ->update(array("is_login" => '0','ssid'=>''));
    //删除session
    foreach ($online as $user) {
        session_id($user['ssid']);
        session_write_close();
        session_destroy();
    }
}
unset($online);

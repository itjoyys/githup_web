<?php
include_once dirname(__FILE__) . '/database.php';
unset($mysqlt);
$mysqlt = new MySQLi($_DBC['private']['host'], $_DBC['private']['user'], $_DBC['private']['pwd'], $_DBC['private']['dbname']);
$mysqlt->query("set names " . $_DBC['private']['char_set']);
define("SITEID", 'zun');
define('CLUSTER_ID', '2');//集群编号
define("AGENT_PRE", "pk"); //代理账号前缀
define("COMPANY_NAME", "ampjylc"); //公司名字
$db_config['host'] = $_DBC['private']['host'];
$db_config['user'] = $_DBC['private']['user'];
$db_config['pass'] = $_DBC['private']['pwd'];
$db_config['dbname'] = $_DBC['private']['dbname'];

?>

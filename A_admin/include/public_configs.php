<?php
date_default_timezone_set('PRC');
unset($mysqlis);
$mysqlis	=	new MySQLi("localhost","root","zqchyj2014","sdb_4");
$mysqlis->query("set names utf8");
define("SITEID",'t');
define('OG_PRE','pkt');//og平台用户前缀
define('API_PRE','pkt');
define("AGENT_PRE","pk");//代理账号前缀
define("COMPANY_NAME", "pkt3737");//公司名字
$db_config['host'] = 'localhost';
$db_config['user'] = 'root';
$db_config['pass'] = 'zqchyj2014';
$db_config['dbname'] = 'sdb_4';

?>
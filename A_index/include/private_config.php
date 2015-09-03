<?php
include_once dirname(__FILE__) . '/database.php';
include_once dirname(__FILE__) . '/site_config.php';
unset($mysqlt);
$mysqlt = new MySQLi($_DBC['private']['host'], $_DBC['private']['user'], $_DBC['private']['pwd'], $_DBC['private']['dbname']);
$mysqlt->query("set names " . $_DBC['private']['char_set']);

$db_config['host'] = $_DBC['private']['host'];
$db_config['user'] = $_DBC['private']['user'];
$db_config['pass'] = $_DBC['private']['pwd'];
$db_config['dbname'] = $_DBC['private']['dbname'];



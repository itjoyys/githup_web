<?php
unset($mysqli);
include_once dirname(__FILE__) . '/database.php';
$mysqli = new MySQLi($_DBC['public']['host'], $_DBC['public']['user'], $_DBC['public']['pwd'], $_DBC['public']['dbname']);
$mysqli->query("set names " . $_DBC['public']['char_set']);

$db_config['host'] = $_DBC['public']['host'];
$db_config['user'] = $_DBC['public']['user'];
$db_config['pass'] = $_DBC['public']['pwd'];
$db_config['dbname'] = $_DBC['public']['dbname'];

?>

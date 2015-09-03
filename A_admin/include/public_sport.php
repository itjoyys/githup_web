<?php
include_once dirname(__FILE__) . '/database.php';
$mysqli = new MySQLi($_DBC['public']['host'], $_DBC['public']['user'], $_DBC['public']['pwd'], $_DBC['public']['dbname']);
$mysqli->query("set names " . $_DBC['public']['char_set']);
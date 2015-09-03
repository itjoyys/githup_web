<?php
include_once dirname(__FILE__) . '/database.php';
$mysqlit = new MySQLi($_DBC['private']['host'], $_DBC['private']['user'], $_DBC['private']['pwd'], $_DBC['private']['dbname']);
$mysqlit->query("set names " . $_DBC['private']['char_set']);
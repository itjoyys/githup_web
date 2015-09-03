<?php
include(dirname(__FILE__) .'/database.php');

$redis = new Redis();
$redis->connect($RedisConefi['Host'],$RedisConefi['Port']);

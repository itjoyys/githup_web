<?php
header('Content-Type:text/html; charset=utf-8');
include_once("../../include/config.php");
include ("../../include/mysqli.php");
$type 		= $_REQUEST['type'];
//开始读取赔率
$sql		= "select * from c_odds_2 where type='ball_".$type."' order by id asc";
$query		= $mysqli->query($sql);
$list 		= array();
while ($odds = $query->fetch_array()) {
	for($i = 1; $i<16; $i++){
			$list[$i] = $odds['h'.$i];
		}
}
$arr = array(   
	'oddslist' => $list,    
);  
$json_string = json_encode($arr);   
echo $json_string; 
?> 
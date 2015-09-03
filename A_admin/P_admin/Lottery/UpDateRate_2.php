<?php
header('Content-Type:text/html; charset=utf-8');
include_once("../../include/config.php");
include ("../../include/mysqli.php");
$type 		= $_REQUEST['type'];
$num 		= $_REQUEST['num'];
$i 			= $_REQUEST['i'];
if($type<6 && $num<10){
	$xodds = 0.1;
}else{
	$xodds = 0.01;
}
if($i==0){$xodds = -$xodds;}
$num = 'h'.$num;
//开始修改赔率
$up_odds	=	"update c_odds_2 set ".$num."=".$num."+".$xodds." where type='ball_".$type."'";
$mysqli->query($up_odds);
//开始读取赔率
$sql		= "select * from c_odds_2 where type='ball_".$type."' order by id asc";
$query		= $mysqli->query($sql);
$list 		= array();
while ($odds = $query->fetch_array()) {
	for($i = 1; $i<15; $i++){
			$list[$i] = $odds['h'.$i];
		}
}
$arr = array(   
	'oddslist' => $list,    
);  
$json_string = json_encode($arr);   
echo $json_string; 
?> 
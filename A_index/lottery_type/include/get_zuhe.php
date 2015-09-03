<?php

function get_zuhe($arr,$m){
$result = array();
	if ($m ==1){
	return $arr;
	}

if ($m == count($arr)){
$result[] = implode(',' , $arr);
return $result;
}

$aaa = $arr[0]; //a

unset($arr[0]);

$arr = array_values($arr);

$temp_list1 = get_zuhe($arr, ($m-1));

foreach ($temp_list1 as $s){
	$s = $aaa.','.$s;
	$result[] = $s;
}
unset($temp_list1);

$temp_list2 = get_zuhe($arr, $m);

foreach ($temp_list2 as $s){
	$result[] = $s;
}

unset($temp_list2);
return $result;
}
?>
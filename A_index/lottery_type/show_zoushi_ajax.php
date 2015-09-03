<?php
include_once("../include/config.php");
include ("../include/public_config.php");

include ("./include/show_zoushi_func.php");
if($_POST['type_lei'] == 5 || $_POST['type_lei'] == 6){

	if($_POST['type_lei'] == 5){
		$result = M('c_auto_5',$db_config)->field("ball_1,ball_2,ball_3")->order("qishu desc")->limit("0,60")->select();
	}elseif ($_POST['type_lei'] == 6) {
		$result = M('c_auto_6',$db_config)->field("ball_1,ball_2,ball_3")->order("qishu desc")->limit("0,60")->select();
	}


	$data=array();
	$big_arr_1 = zoushi($result,'ball_1','大小');
	$big_arr_2 = zoushi($result,'ball_2','大小');
	$big_arr_3 = zoushi($result,'ball_3','大小');
	$dan_arr_1 = zoushi($result,'ball_1','单双');
	$dan_arr_2 = zoushi($result,'ball_2','单双');
	$dan_arr_3 = zoushi($result,'ball_3','单双');
	$he_arr_1 = zoushi($result,'ball_1','总和单双');
	$he_arr_2 = zoushi($result,'ball_2','总和大小');
	$dragon_tiger = zoushi($result,'ball_4','龙虎合');

	$data[1]['big_small']=$big_arr_1;
	$data[1]['odd_even']=$dan_arr_1;
	$data[2]['big_small']=$big_arr_2;
	$data[2]['odd_even']=$dan_arr_2;
	$data[3]['big_small']=$big_arr_3;
	$data[3]['odd_even']=$dan_arr_3;
	$data[7]['big_small']=$he_arr_2;
	$data[7]['odd_even']=$he_arr_1;
	$data[7]['dragon_tiger']=$dragon_tiger;


	   echo json_encode($data);

}elseif($_POST['type_lei'] == 2||$_POST['type_lei'] == 10||$_POST['type_lei'] == 11||$_POST['type_lei'] == 12){

	$result = M('c_auto_'.$_POST['type_lei'],$db_config)->field("ball_1,ball_2,ball_3,ball_4,ball_5")->order("qishu desc")->limit("0,60")->select();



	$data=array();
	$big_arr_1 = zoushi($result,'ball_1','大小');
	$big_arr_2 = zoushi($result,'ball_2','大小');
	$big_arr_3 = zoushi($result,'ball_3','大小');
	$big_arr_4 = zoushi($result,'ball_4','大小');
	$big_arr_5 = zoushi($result,'ball_5','大小');
	$dan_arr_1 = zoushi($result,'ball_1','单双');
	$dan_arr_2 = zoushi($result,'ball_2','单双');
	$dan_arr_3 = zoushi($result,'ball_3','单双');
	$dan_arr_4 = zoushi($result,'ball_4','单双');
	$dan_arr_5 = zoushi($result,'ball_5','单双');
	$he_arr_1 = zoushi($result,'ball_1','总和单双');
	$he_arr_2 = zoushi($result,'ball_2','总和大小');
	$dragon_tiger = zoushi($result,'ball_6','龙虎合');


	$data[1]['big_small']=$big_arr_1;
	$data[1]['odd_even']=$dan_arr_1;


	$data[2]['big_small']=$big_arr_2;
	$data[2]['odd_even']=$dan_arr_2;

	$data[3]['big_small']=$big_arr_3;
	$data[3]['odd_even']=$dan_arr_3;

	$data[4]['big_small']=$big_arr_4;
	$data[4]['odd_even']=$dan_arr_4;

	$data[5]['big_small']=$big_arr_5;
	$data[5]['odd_even']=$dan_arr_5;


	$data[7]['big_small']=$he_arr_2;
	$data[7]['odd_even']=$he_arr_1;
	$data[7]['dragon_tiger']=$dragon_tiger;



	   echo json_encode($data);
}

?>
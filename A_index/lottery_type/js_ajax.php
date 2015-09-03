<?php
include_once("../include/config.php");


if(!empty($_POST['action']) && $_POST['action'] == 'ask'){
	$data1 = M("ka_tan_set_money",$db_config)->where("uid = '".$_SESSION['uid']."' and site_id = '".SITEID."'")->find();

	if(!empty($data1)){
		if($data1['is_work']==0){

			$json = json_encode("0");
			echo $json;
		}else{
			$json['is_work']=1;
			$json[0]=$data1['val1'];
			$json[1]=$data1['val2'];
			$json[2]=$data1['val3'];
			$json[3]=$data1['val4'];
			$json[4]=$data1['val5'];
			echo json_encode($json);
		}
	}else{
			$json['is_work']=2;
			$json[0]=100;
			$json[1]=300;
			$json[2]=500;
			$json[3]=1000;
			$json[4]=5000;
			echo json_encode($json);
	}
}







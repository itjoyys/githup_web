<?php 

//特码单双
function danshuang($str){
	
	if($str%2 == 0){
		echo "<font color='blue';>双</font>";
	}else{
		echo "<font color='#f00;';>单</font>";
	}
}

//特码大小
function tm_daxiao($str){
	
	if($str >= 25){
		echo "<font color='blue';>大</font>";
	}else{
		echo "<font color='#f00;';>小</font>";
	}
}
//特码色波
function tm_sebo($str){

$arr1=array(01,02,07,8,12,13,18,19,23,24,29,30,34,35,40,45,46);
$arr2=array(03,04,9,10,14,15,20,25,26,31,36,37,41,42,47,48);
$arr3=array(05,06,11,16,17,21,22,27,28,32,33,38,39,43,44,49);
	if($str != 0){

		if(in_array($str,$arr1)){
			echo "<font color='#f00;';>红波</font>";
		}else if(in_array($str,$arr2)){
			echo "<font color='blue';>蓝波</font>";
		}else if(in_array($str,$arr3)){
			echo "<font color='green';>绿波</font>";
		}
	}

}


//总分大小
function zongfen_daxiao($str){
	$max = 49 * 6 / 2;
	if($str >= $max){
		echo "<font color='blue';>大</font>";
	}else{
		echo "<font color='#f00;';>小</font>";
	}
}

//合数大小
function heshu_daxiao($str){	
	if(strlen($str) == 1){
		$str = "0".$str;		
	}
	$num = substr($str,0,1);
	$num1 = substr($str,1,1);
	$data = intval($num) + intval($num1);
	if($data >= 7){
		echo "<font color='blue';>大</font>";
	}else{
		echo "<font color='#f00;';>小</font>";
		}
	
}
//合数单双
function heshu_danshuang($str){
	if(strlen($str) == 1){
		$str = "0".$str;		
	}
	$num = substr($str,0,1);
	$num1 = substr($str,1,1);
	$data = intval($num) + intval($num1);
	if($data%2 == 0){
		echo "<font color='blue';>双</font>";
	}else{
		echo "<font color='#f00;';>单</font>";
	}
}
//生肖
function shenxiao($nianfen,$num){
	//2014	
	$shu = array(7,19,31,43);
	$niu = array(6,18,30,42);
	$hu = array(5,17,29,41);
	$tu = array(4,16,28,40);
	$long = array(3,15,27,39);
	$she = array(2,14,26,38);
	$ma = array(1,13,25,37,49);
	$yang = array(12,24,36,48);
	$hou = array(11,23,35,47);
	$ji = array(10,22,34,46);
	$gou = array(9,21,33,45);
	$zhu = array(8,20,32,44);
	$arr=array($shu,$niu,$hu,$tu,$long,$she,$ma,$yang,$hou,$ji,$gou,$zhu);

	$shuxiang=array("鼠","牛","虎","兔","龙","蛇","马","羊","猴","鸡","狗","猪");
	$count=count($shuxiang);
	$xu = intval($nianfen)-2014;//变化参数

	for($i=0;$i<$count;$i++){
		$xuhao =$i+$xu;

		if($xuhao  >= 12){
			$xuhao = $xuhao% 12 ;
		}
		
		if(in_array($num,$arr[$i])){
				
		echo $shuxiang[$xuhao];

		}
		
	}	

}




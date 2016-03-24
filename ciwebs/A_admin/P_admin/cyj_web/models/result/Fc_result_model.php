<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Fc_result_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	//获取彩票结果
	public function get_fc_result($type,$map,$limit){
		$db_model['tab'] = $type.'_auto';
		$db_model['type'] = 2;
		$db_model['is_port'] = 1;//读取从库
    return $this->M($db_model)->where($map)->order("id desc")->limit($limit)->select();
	}

	//六合彩
	function result_7($arr = array()){
        $num['tmis_even'] = $this->single_double($arr['ball_7']);//特码单双
        $num['tmis_big'] = $this->big_small($arr['ball_7'],25); //特码大小
        $num['tmis_color'] = $this->tm_sebo($arr['ball_7']);//特码色波
        $num['sum'] = $this->lhc_sum($arr);//总和
        $num['sumis_big']=$this ->big_small($num['sum'],49*6/2); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        $num['sum_hsdx']=$this ->liuhecai_hsdx($num['sum']); //合数大小
        $num['sum_hsds'] = $this->liuhecai_hsds($num['sum']);//合数单双
        $num['sum_shengxiao'] = $this->shengxiao($arr);//生肖
        return $num;
	}

	//福彩3D
	function result_5($arr = array()){
		$num['sum']=$this ->ball_sum($arr);  //总和
		$num['sumis_big']=$this ->big_small($num['sum'],14); //大小
    $num['sumis_even'] = $this->single_double($num['sum']);//单双
    $num['lhh'] = $this->is_tiger($arr,5);//龙虎和
    $num['sanlian'] = $this->sanlian($arr,5);//三连
    return $num;
	}
	//排列3
	function result_6($arr = array()){
		$num['sum']=$this ->ball_sum($arr);  //总和
		$num['sumis_big']=$this ->big_small($num['sum'],14); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        $num['lhh'] = $this->is_tiger($arr,6);//龙虎和
        $num['sanlian'] = $this->sanlian($arr,6);//三连
        return $num;
	}

	//重庆时时彩
	function result_2($arr = array()){
		$num['sum']=$this ->ball_sum($arr);  //总和
		$num['sumis_big']=$this ->big_small($num['sum'],23); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        $num['lhh'] = $this->is_tiger($arr,2);//龙虎和
        $num['qian3'] = $this->Ssc_sanlian($arr,"qian3");//前3
        $num['zhong3'] = $this->Ssc_sanlian($arr,"zhong3");//前3
        $num['hou3'] = $this->Ssc_sanlian($arr,"hou3");//前3
        return $num;
	}
	//天津时时彩
	function result_10($arr = array()){
		$num['sum']=$this ->ball_sum($arr);  //总和
		$num['sumis_big']=$this ->big_small($num['sum'],23); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        $num['lhh'] = $this->is_tiger($arr,10);//龙虎和
        $num['qian3'] = $this->Ssc_sanlian($arr,"qian3");//前3
        $num['zhong3'] = $this->Ssc_sanlian($arr,"zhong3");//前3
        $num['hou3'] = $this->Ssc_sanlian($arr,"hou3");//前3
        return $num;
	}
	//江西时时彩
	function result_11($arr = array()){
		$num['sum']=$this ->ball_sum($arr);  //总和
		$num['sumis_big']=$this ->big_small($num['sum'],23); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        $num['lhh'] = $this->is_tiger($arr,11);//龙虎和
        $num['qian3'] = $this->Ssc_sanlian($arr,"qian3");//前3
        $num['zhong3'] = $this->Ssc_sanlian($arr,"zhong3");//前3
        $num['hou3'] = $this->Ssc_sanlian($arr,"hou3");//前3
        return $num;
	}
	//新疆时时彩
	function result_12($arr = array()){
		$num['sum']=$this ->ball_sum($arr);  //总和
		$num['sumis_big']=$this ->big_small($num['sum'],23); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        $num['lhh'] = $this->is_tiger($arr,12);//龙虎和
        $num['qian3'] = $this->Ssc_sanlian($arr,"qian3");//前3
        $num['zhong3'] = $this->Ssc_sanlian($arr,"zhong3");//前3
        $num['hou3'] = $this->Ssc_sanlian($arr,"hou3");//前3
        return $num;
	}

	//北京赛车PK
	function result_3($arr = array()){
		$num['sum']=$this ->ball_sum($arr);  //总和
		$num['sumis_big']=$this ->big_small($num['sum'],11); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        return $num;
	}

	//北京快乐8
	function result_8($arr = array()){
		$num['sum']=$this ->ball_sum($arr);  //总和
		$num['sumis_big']=$this ->big_small($num['sum'],11); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        return $num;
	}

	//广东快乐10分
	function result_1($arr = array()){
		$num['sum']=$this ->ball_sum($arr);  //总和
		$num['sumis_big']=$this ->gd_dx($num['sum']); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        $num['subws'] = $this->gd_wdx($num['sum']);//尾数大小
         $num['sumis_tiger'] = $this->gd_tiger($arr);//龙虎
        return $num;
	}

	//重庆快乐10分
	function result_4($arr = array()){
		$num['sum']=$this ->ball_sum($arr);  //总和
		$num['sumis_big']=$this ->gd_dx($num['sum']); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        $num['subws'] = $this->gd_wdx($num['sum']);//尾数大小
        $num['sumis_tiger'] = $this->gd_tiger($arr);//龙虎
        return $num;
	}

	//江苏快3
	function result_13($arr = array()){
		$num['sum']=$this ->ball_sum($arr);  //总和
		$num['sumis_big']=$this ->big_small($num['sum'],14); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        $num['lhh'] = $this->is_tiger($arr,13);//龙虎和
        $num['sanlian'] = $this->sanlian($arr,13);//三连
        return $num;
	}

	//吉林快3
	function result_14($arr = array()){
		$num['sum']=$this ->ball_sum($arr);  //总和
		$num['sumis_big']=$this ->big_small($num['sum'],14); //大小
        $num['sumis_even'] = $this->single_double($num['sum']);//单双
        $num['lhh'] = $this->is_tiger($arr,14);//龙虎和
        $num['sanlian'] = $this->sanlian($arr,14);//三连
        return $num;
	}

	//单双公共
	function single_double($num){
	    if($num%2 == 0){
			return "<font color='blue';>双</font>";
		}else{
			return "<font color='#f00';>单</font>";
		}
	}

	//大小
	function big_small($num,$num1){
	    if($num >= $num1){
			return "<font color='blue';>大</font>";
		}else{
			return "<font color='#f00';>小</font>";
		}
	}

	//求和
	function ball_sum($rows){
		$sum=0;
		foreach ($rows as $key=>$value) {
			if(substr($key,0,4)=="ball"){
				$sum+=$value;
			}
		}
		return $sum;
	}

	  //龙虎和
	function is_tiger($array,$type){
		//福彩3D 排列3 江苏快3 吉林快3的龙虎和
	  	if($type==5 || $type==6 || $type==13 || $type==14){
			if($array['ball_1'] > $array['ball_3']){
		    	return "龙";
		   	}
		   	if($array['ball_1'] < $array['ball_3']){
		   	    return "虎";
		   }
		   	if($array['ball_1'] == $array['ball_3']){
		    	return "和";
		   }
		}
		//重庆,江西,新疆,江苏时时彩的龙虎和
		if($type==2 || $type==10 || $type==11 || $type==12){
		   if($array['ball_1'] > $array['ball_5']){
		    return "龙";
		   }
		   if($array['ball_1'] < $array['ball_5']){
		    return "虎";
		   }
		   if($array['ball_1'] == $array['ball_5']){
		    return "和";
		   }
	  	}
	 }

	 //福彩3D 排列3的3连
 	function sanlian($array,$type){
  		if($type==5 || $type==6 || $type==13 || $type==14){
		   $n1=$array['ball_1'];
		   $n2=$array['ball_2'];
		   $n3=$array['ball_3'];
		   if(($n1==0 || $n2==0 || $n3==0) && ($n1==9 || $n2==9 || $n3==9)){
			    if($n1==0){
			     $n1=10;
			    }
			    if($n2==0){
			     $n2=10;
			    }
			    if($n3==0){
			     $n3=10;
			    }
	    	}
		   if($n1==$n2 && $n2==$n3){
		    return "豹子";
		   }elseif(($n1==$n2) || ($n1==$n3) || ($n2==$n3)){
		    return "对子";
		   }elseif(($n1==10 || $n2==10 || $n3==10) && ($n1==9 || $n2==9 || $n3==9) && ($n1==1 || $n2==1 || $n3==1)){
		    return "顺子";
		   }elseif( ( (abs($n1-$n2)==1) && (abs($n2-$n3)==1) ) || ((abs($n1-$n2)==2) && (abs($n1-$n3)==1) && (abs($n2-$n3)==1)) ||((abs($n1-$n2)==1) && (abs($n1-$n3)==1)) ){
		    return "顺子";
		   }elseif((abs($n1-$n2)==1) || (abs($n1-$n3)==1) || (abs($n2-$n3)==1)){
		    return "半顺";
		   }else{
		    return "杂六";
		   }
		}
	}

	function Ssc_sanlian($array,$type){
		$n1=$array['ball_1'];
		$n2=$array['ball_2'];
		$n3=$array['ball_3'];
		$n4=$array['ball_4'];
		$n5=$array['ball_5'];
		if($type=="qian3"){
			if(($n1==0 || $n2==0 || $n3==0) && ($n1==9 || $n2==9 || $n3==9)){
				if($n1==0){
					$n1=10;
				}
				if($n2==0){
					$n2=10;
				}
				if($n3==0){
					$n3=10;
				}
			}
			if($n1==$n2 && $n2==$n3){
				return "豹子";
			}elseif(($n1==$n2) || ($n1==$n3) || ($n2==$n3)){
				return "对子";
			}elseif(($n1==10 || $n2==10 || $n3==10) && ($n1==9 || $n2==9 || $n3==9) && ($n1==1 || $n2==1 || $n3==1)){
				return "顺子";
			}elseif( ( (abs($n1-$n2)==1) && (abs($n2-$n3)==1) ) || ((abs($n1-$n2)==2) && (abs($n1-$n3)==1) && (abs($n2-$n3)==1)) ||((abs($n1-$n2)==1) && (abs($n1-$n3)==1)) ){
				return "顺子";
			}elseif((abs($n1-$n2)==1) || (abs($n1-$n3)==1) || (abs($n2-$n3)==1)){
				return "半顺";
			}else{
				return "杂六";
			}
		}
		if($type=="zhong3"){
			if(($n2==0 || $n3==0 || $n4==0) && ($n2==9 || $n3==9 || $n4==9)){
				if($n2==0){
					$n2=10;
				}
				if($n3==0){
					$n3=10;
				}
				if($n4==0){
					$n4=10;
				}
			}
			if($n2==$n3 && $n3==$n4){
				return "豹子";
			}elseif(($n2==$n3) || ($n2==$n4) || ($n3==$n4)){
				return "对子";
			}elseif(($n2==10 || $n3==10 || $n4==10) && ($n2==9 || $n3==9 || $n4==9) && ($n2==1 || $n3==1 || $n4==1)){
				return "顺子";
			}elseif( ( (abs($n2-$n3)==1) && (abs($n3-$n4)==1) ) || ((abs($n2-$n3)==2) && (abs($n2-$n4)==1) && (abs($n3-$n4)==1)) ||((abs($n2-$n3)==1) && (abs($n2-$n4)==1)) ){
				return "顺子";
			}elseif((abs($n2-$n3)==1) || (abs($n2-$n4)==1) || (abs($n3-$n4)==1)){
				return "半顺";
			}else{
				return "杂六";
			}
		}
		if($type=="hou3"){
			if(($n3==0 || $n4==0 || $n5==0) && ($n3==9 || $n4==9 || $n5==9)){
				if($n3==0){
					$n3=10;
				}
				if($n4==0){
					$n4=10;
				}
				if($n5==0){
					$n5=10;
				}
			}
			if($n3==$n4 && $n4==$n5){
				return "豹子";
			}elseif(($n3==$n4) || ($n3==$n5) || ($n4==$n5)){
				return "对子";
			}elseif(($n3==10 || $n4==10 || $n5==10) && ($n3==9 || $n4==9 || $n5==9) && ($n3==1 || $n4==1 || $n5==1)){
				return "顺子";
			}elseif( ( (abs($n3-$n4)==1) && (abs($n4-$n5)==1) ) || ((abs($n3-$n4)==2) && (abs($n3-$n5)==1) && (abs($n4-$n5)==1)) ||((abs($n3-$n4)==1) && (abs($n3-$n5)==1)) ){
				return "顺子";
			}elseif((abs($n3-$n4)==1) || (abs($n3-$n5)==1) || (abs($n4-$n5)==1)){
				return "半顺";
			}else{
				return "杂六";
			}
		}
	}

	//北京PK虎
	function bj_longhu($array,$type){
		$n1=$array['ball_1'];
		$n2=$array['ball_2'];
		$n3=$array['ball_3'];
		$n4=$array['ball_4'];
		$n5=$array['ball_5'];
		$n6=$array['ball_6'];
		$n7=$array['ball_7'];
		$n8=$array['ball_8'];
		$n9=$array['ball_9'];
		$n10=$array['ball_10'];
		if($type==4){
			if($n1>$n10){
				return '<font color="#FF0000">龙</font>';
			}else{
				return '虎';
			}
		}
		if($type==5){
			if($n2>$n9){
				return '<font color="#FF0000">龙</font>';
			}else{
				return '虎';
			}
		}
		if($type==6){
			if($n3>$n8){
				return '<font color="#FF0000">龙</font>';
			}else{
				return '虎';
			}
		}
		if($type==7){
			if($n4>$n7){
				return '<font color="#FF0000">龙</font>';
			}else{
				return '虎';
			}
		}
		if($type==8){
			if($n5>$n6){
				return '<font color="#FF0000">龙</font>';
			}else{
				return '虎';
			}
		}
	}

	//北京快乐8上中下盘 ,奇偶盘
	function bj_kp($arr,$type){
		if($type==1){//上中下盘
		$compare =($arr["ball_1"]>=40?1:-1)+($arr["ball_2"]>=40?1:-1)+($arr["ball_3"]>=40?1:-1)+($arr["ball_4"]>=40?1:-1)+($arr["ball_5"]>=40?1:-1)+($arr["ball_6"]>=40?1:-1)+($arr["ball_7"]>=40?1:-1)+($arr["ball_8"]>=40?1:-1)+($arr["ball_9"]>=40?1:-1)+($arr["ball_10"]>=40?1:-1)+($arr["ball_11"]>=40?1:-1)+($arr["ball_12"]>=40?1:-1)+($arr["ball_13"]>=40?1:-1)+($arr["ball_14"]>=40?1:-1)+($arr["ball_15"]>=40?1:-1)+($arr["ball_16"]>=40?1:-1)+($arr["ball_17"]>=40?1:-1)+($arr["ball_18"]>=40?1:-1)+($arr["ball_19"]>=40?1:-1)+($arr["ball_20"]>=40?1:-1);

			if($compare<0){
				return '上盘';
			}elseif($compare>0){
				return '下盘';
			}elseif($compare==0){
				return '中盘';
			}
		}

		if($type==2){//奇偶和盘
		$compare =($arr["ball_1"]%2==0?1:-1)+($arr["ball_2"]%2==0?1:-1)+($arr["ball_3"]%2==0?1:-1)+($arr["ball_4"]%2==0?1:-1)+($arr["ball_5"]%2==0?1:-1)+($arr["ball_6"]%2==0?1:-1)+($arr["ball_7"]%2==0?1:-1)+($arr["ball_8"]%2==0?1:-1)+($arr["ball_9"]%2==0?1:-1)+($arr["ball_10"]%2==0?1:-1)+($arr["ball_11"]%2==0?1:-1)+($arr["ball_12"]%2==0?1:-1)+($arr["ball_13"]%2==0?1:-1)+($arr["ball_14"]%2==0?1:-1)+($arr["ball_15"]%2==0?1:-1)+($arr["ball_16"]%2==0?1:-1)+($arr["ball_17"]%2==0?1:-1)+($arr["ball_18"]%2==0?1:-1)+($arr["ball_19"]%2==0?1:-1)+($arr["ball_20"]%2==0?1:-1);

			if($compare>0){
				return '偶盘';
			}elseif($compare<0){
				return '奇盘';
			}elseif($compare==0){
				return '和盘';
			}
		}
	}
	//广东10分,重庆10分大小
	function gd_dx($sum){
		if($sum>=85 && $sum<=132){
			return "<font color='blue';>大</font>";
		}
		if($sum>=36 && $sum<=83){
			return "<font color='#f00';>小</font>";
		}
		if($sum==84){
			return "<font color='#yellow';>和</font>";
		}
	}
	//广东10分,重庆10分尾大小
	function gd_wdx($sum){
		$sum = substr($sum,strlen($sum)-1);
		if($sum>=5){
			return '尾大';
		}else{
			return '尾小';
		}
	}
	//广东龙虎
	function gd_tiger($array){
		if($array['ball_1']>$array['ball_8']){
			return '龙';
		}else{
			return '虎';
		}
	}

	//六合彩特码色波
	function tm_sebo($num){
		$arr1=array(01,02,07,8,12,13,18,19,23,24,29,30,34,35,40,45,46);
		$arr2=array(03,04,9,10,14,15,20,25,26,31,36,37,41,42,47,48);
		$arr3=array(05,06,11,16,17,21,22,27,28,32,33,38,39,43,44,49);
		if($num != 0){
			if(in_array($num,$arr1)){
				return "<font color='#f00';>红波</font>";
			}else if(in_array($num,$arr2)){
				return "<font color='blue';>蓝波</font>";
			}else if(in_array($num,$arr3)){
				return "<font color='green';>绿波</font>";
			}
		}
	}

	//六合彩总分
	function lhc_sum($array){
		return $array['ball_1']+$array['ball_2']+$array['ball_3']+$array['ball_4']+$array['ball_5']+$array['ball_6'];
	}

	//六合彩合数大小
	function liuhecai_hsdx($str){
		if(strlen($str) == 1){
			$str = "0".$str;
		}
		$num = substr($str,0,1);
		$num1 = substr($str,1,1);
		$data = intval($num) + intval($num1);
		if($data >= 7){
			return "<font color='blue';>大</font>";
		}else{
			return "<font color='#f00;';>小</font>";
		}
	}

	//六合彩合数单双
	function liuhecai_hsds($str){
		if(strlen($str) == 1){
			$str = "0".$str;
		}
		$num = substr($str,0,1);
		$num1 = substr($str,1,1);
		$data = intval($num) + intval($num1);
		if($data%2 == 0){
			return "<font color='blue';>双</font>";
		}else{
			return "<font color='#f00;';>单</font>";
		}
	}

	//六合彩生肖
	function shengxiao($array){
		//2014
		$nianfen=substr($array['qishu'],0,4);
		$num=$array['ball_7'];
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
			return $shuxiang[$xuhao];
			}
		}

	}

}
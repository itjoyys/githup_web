<?php

//广东快乐十分开奖函数
function G10_Auto($num , $type){
	$zh = $num[0]+$num[1]+$num[2]+$num[3]+$num[4]+$num[5]+$num[6]+$num[7];
	if($type==1){
		return $zh;
	}
	if($type==2){
		if($zh>=85 && $zh<=132){
			return '大';
		}
		if($zh>=36 && $zh<=83){
			return '小';
		}
		if($zh==84){
			return '和';
		}
	}
	if($type==3){
		if($zh%2==0){
			return '双';
		}else{
			return '单';
		}
	}
	if($type==4){
		$zhws = substr($zh,strlen($zh)-1);
		if($zhws>=5){
			return '尾大';
		}else{
			return '尾小';
		}
	}
	if($type==5){
		if($num[0]>$num[7]){
			return '龙';
		}else{
			return '虎';
		}
	}
}
//重庆时时彩开奖函数
function Ssc_Auto($num , $type){
	$zh = $num[0]+$num[1]+$num[2]+$num[3]+$num[4];
	if($type==1){
		return $zh;
	}
	if($type==2){
		if($zh>=23){
			return '大';
		}
		if($zh<=23){
			return '小';
		}
	}
	if($type==3){
		if($zh%2==0){
			return '双';
		}else{
			return '单';
		}
	}
	if($type==4){
		if($num[0]>$num[4]){
			return '龙';
		}
		if($num[0]<$num[4]){
			return '虎';
		}
		if($num[0]==$num[4]){
			return '和';
		}
	}
	if($type==5){

		$n1=$num[0];
		$n2=$num[1];
		$n3=$num[2];
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
	if($type==6){
		$n1=$num[1];
		$n2=$num[2];
		$n3=$num[3];
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
	if($type==7){
		$n1=$num[2];
		$n2=$num[3];
		$n3=$num[4];
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


	if($type==8){//斗牛
		$n1=$num[0];
		$n2=$num[1];
		$n3=$num[2];
		$n4=$num[3];
		$n5=$num[4];
		$array=$num;
		$zh=0;
		//$zh1=$n1+$n2+$n3+$n4+$n5;
		$zh2=0;
		for($i=0;$i<5;$i++){

			$zh=-1;
			$j=$i+1;
			for($ii=$j;$ii<5;$ii++){

				$jj=$ii+1;
				$zh=-1;
				for($iii=$jj;$iii<5;$iii++){
					$zh=$array[$i]+$array[$ii]+$array[$iii];


					if($zh==0 || $zh%10==0) {

						foreach ($array as $key => $value) {
							if($key==$i)unset($array[$key]);
							if($key==$ii)unset($array[$key]);
							if($key==$iii)unset($array[$key]);
						}

						foreach ($array as $key => $value) {
							$zh2+=$value;
						}

						//echo $zh."|".$zh2."<br>";
						break;
					}
				}
				if($zh==0 || $zh%10==0) break;
			}
			if($zh==0 || $zh%10==0) break;
		}//echo "--".$zh."|".$zh2."<br>";
		if($zh==0 || $zh%10==0){
			if($zh2>10){
				return "牛".($zh2-10);
			}elseif(($zh+$zh2)==0 || $zh2==10){
				return "牛牛";
			}
			else{
				return "牛".$zh2;
			}
		}else
		{
			return "没牛";
		}

	}

	if($type==9){//梭哈
		$n1=$num[0];
		$n2=$num[1];
		$n3=$num[2];
		$n4=$num[3];
		$n5=$num[4];
		if($n1==$n2 && $n2==$n3 && $n3==$n4 && $n4==$n5){
			return "五条";
		}

		$array=$num;
		for($i=0;$i<5;$i++){
			$j=$i+1;
			for($ii=$j;$ii<5;$ii++){
				$jj=$ii+1;
				for($iii=$jj;$iii<5;$iii++){
					$jjj=$iii+1;
					for($iiii=$jjj;$iiii<5;$iiii++){
						if($array[$i]==$array[$ii] && $array[$ii]==$array[$iii] && $array[$iii]==$array[$iiii]) return "四条";
					}
				}
			}
		}

		for($i=0;$i<5;$i++){
			$j=$i+1;
			for($ii=$j;$ii<5;$ii++){
				$jj=$ii+1;
				for($iii=$jj;$iii<5;$iii++){
					if($array[$i]==$array[$ii] && $array[$ii]==$array[$iii] )
					{
						foreach ($array as $key => $value) {
							if($key==$i)unset($array[$key]);
							if($key==$ii)unset($array[$key]);
							if($key==$iii)unset($array[$key]);
						}
						$arr;
						foreach ($array as $key => $value) {
							$arr[]=$value;
						}
						if($arr[0]==$arr[1]) return "葫芦";
					}
				}
			}
		}

		$n1=$num[0];$n1==10?0:$n1;
		$n2=$n1+1;$n2==10?0:$n2;
		$n3=$n2+1;$n3==10?0:$n3;
		$n4=$n3+1;$n4==10?0:$n4;
		$n5=$n4+1;$n5==10?0:$n5;
		if($n1==$num[0] && $n2==$num[1] && $n3==$num[2] && $n4==$num[3] && $n5==$num[4] )
		{
			return "顺子";
		}


		for($i=0;$i<5;$i++){
			$j=$i+1;
			for($ii=$j;$ii<5;$ii++){
				$jj=$ii+1;
				for($iii=$jj;$iii<5;$iii++){
					if($array[$i]==$array[$ii] && $array[$ii]==$array[$iii] ) return "三条";
				}
			}
		}

		$array=$num;
		for($i=0;$i<5;$i++){
			$j=$i+1;
			for($ii=$j;$ii<5;$ii++){
				if($array[$i]==$array[$ii]){
					foreach ($array as $key => $value) {
							if($key==$i)unset($array[$key]);
							if($key==$ii)unset($array[$key]);
					}
					$arr;
					foreach ($array as $key => $value) {
						$arr[]=$value;
					}
					//print_r($arr);break;
					for($vi=0;$vi<3;$vi++){
						$vj=$vi+1;
						for($vii=$vj;$vii<3;$vii++){
							if($arr[$vi]==$arr[$vii]) return "两对";
						}
					}
				}
			}
		}

		$array=$num;
		for($i=0;$i<5;$i++){
			$j=$i+1;
			for($ii=$j;$ii<5;$ii++){
				if($array[$i]==$array[$ii]){
					return "一对";
				}
			}
		}

		return "散号";

	}//梭哈

}

//北京赛车PK拾开奖函数
function Pk10_Auto($num , $type){
	$zh = $num[0]+$num[1];
	if($type==1){
		return $zh;
	}
	if($type==2){
		if($zh>11){
			return '大';
		}else{
			return '小';
		}
	}
	if($type==3){
		if($zh%2==0){
			return '双';
		}else{
			return '单';
		}
	}
	if($type==4){
		if($num[0]>$num[9]){
			return '龙';
		}else{
			return '虎';
		}
	}
	if($type==5){
		if($num[1]>$num[8]){
			return '龙';
		}else{
			return '虎';
		}
	}
	if($type==6){
		if($num[2]>$num[7]){
			return '龙';
		}else{
			return '虎';
		}
	}
	if($type==7){
		if($num[3]>$num[6]){
			return '龙';
		}else{
			return '虎';
		}
	}
	if($type==8){
		if($num[4]>$num[5]){
			return '龙';
		}else{
			return '虎';
		}
	}
}

//重庆快乐十分开奖函数
function C10_Auto($num , $type){
	$zh = $num[0]+$num[1]+$num[2]+$num[3]+$num[4]+$num[5]+$num[6]+$num[7];
	if($type==1){
		return $zh;
	}
	if($type==2){
		if($zh>=85 && $zh<=132){
			return '大';
		}
		if($zh>=36 && $zh<=83){
			return '小';
		}
		if($zh==84){
			return '和';
		}
	}
	if($type==3){
		if($zh%2==0){
			return '双';
		}else{
			return '单';
		}
	}
	if($type==4){
		$zhws = substr($zh,strlen($zh)-1);
		if($zhws>=5){
			return '尾大';
		}else{
			return '尾小';
		}
	}
	if($type==5){
		if($num[0]>$num[7]){
			return '龙';
		}else{
			return '虎';
		}
	}
}

function FC3D_Auto($num , $type){
	$zh = $num[0]+$num[1]+$num[2];
	if($type==0){
		return $zh;
	}

	if($type==1 || $type==2 || $type==3){//第一~三球大小

		if($type==1)$qnum = $num[0];
		if($type==2)$qnum = $num[1];
		if($type==3)$qnum = $num[2];

		if($qnum>=5){
			return '大';
		}else{
			return '小';
		}
	}

	if($type==4 || $type==5 || $type==6){
		if($type==4)$qnum = $num[0];
		if($type==5)$qnum = $num[1];
		if($type==6)$qnum = $num[2];

		if($qnum%2==0){
			return '双';
		}else{
			return '单';
		}
	}

	if($type==7){//总和大小
		if($zh>=14){
			return '总和大';
		}else{
			return '总和小';
		}
	}

	if($type==8){//总和双单
		if($zh%2==0){
			return '总和双';
		}else{
			return '总和单';
		}
	}

	if($type==9){
		if($num[0]>$num[2]){
			return '龙';
		}
		if($num[0]<$num[2]){
			return '虎';
		}
		if($num[0]==$num[2]){
			return '和';
		}
	}

	if($type==10){
		$n1=$num[0];
		$n2=$num[1];
		$n3=$num[2];
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

	if($type==11){
		return max(abs($num[0]-$num[1]),abs($num[0]-$num[2]),abs($num[1]-$num[2]));
	}
}

function k3_Auto($num , $type){
	$zh = $num[0]+$num[1]+$num[2];
	if($type==0){
		return $zh;
	}

	if($type==7){//总和大小
		if($zh>=11){
			return '总和大';
		}else{
			return '总和小';
		}
	}

	if($type==8){//总和双单
		if($zh%2==0){
			return '总和双';
		}else{
			return '总和单';
		}
	}

}


function PL3_Auto($num , $type){
	$zh = $num[0]+$num[1]+$num[2];
	if($type==0){
		return $zh;
	}

	if($type==1 || $type==2 || $type==3){//第一~三球大小

		if($type==1)$qnum = $num[0];
		if($type==2)$qnum = $num[1];
		if($type==3)$qnum = $num[2];

		if($qnum>=5){
			return '大';
		}else{
			return '小';
		}
	}

	if($type==4 || $type==5 || $type==6){
		if($type==4)$qnum = $num[0];
		if($type==5)$qnum = $num[1];
		if($type==6)$qnum = $num[2];

		if($qnum%2==0){
			return '双';
		}else{
			return '单';
		}
	}

	if($type==7){//总和大小
		if($zh>=14){
			return '总和大';
		}else{
			return '总和小';
		}
	}

	if($type==8){//总和双单
		if($zh%2==0){
			return '总和双';
		}else{
			return '总和单';
		}
	}

	if($type==9){
		if($num[0]>$num[2]){
			return '龙';
		}
		if($num[0]<$num[2]){
			return '虎';
		}
		if($num[0]==$num[2]){
			return '和';
		}
	}

	if($type==10){
		$n1=$num[0];
		$n2=$num[1];
		$n3=$num[2];
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

	if($type==11){
		return max(abs($num[0]-$num[1]),abs($num[0]-$num[2]),abs($num[1]-$num[2]));
	}


}
//北京快乐8
function Kl8_Auto($num , $type){
	$zh = $num[0]+$num[1]+$num[2]+$num[3]+$num[4]+$num[5]+$num[6]+$num[7]+$num[8]+$num[9]+$num[10]+$num[11]+$num[12]+$num[13]+$num[14]+$num[15]+$num[16]+$num[17]+$num[18]+$num[19];
	if($type==0){
		return $zh;
	}

	if($type==1){//总和大小
		if($zh>810){
			return '总和大';
		}elseif($zh<810){
			return '总和小';
		}elseif($zh==810){
			return '总和810';
		}
	}

	if($type==2){//总和双单
		if($zh%2==0){
			return '总和双';
		}else{
			return '总和单';
		}
	}

	if($type==3){//上中下盘
		$compare =($num[0]>40?1:-1)+($num[1]>40?1:-1)+($num[2]>40?1:-1)+($num[3]>40?1:-1)+($num[4]>40?1:-1)+($num[5]>40?1:-1)+($num[6]>40?1:-1)+($num[7]>40?1:-1)+($num[8]>40?1:-1)+($num[9]>40?1:-1)+($num[10]>40?1:-1)+($num[11]>40?1:-1)+($num[12]>40?1:-1)+($num[13]>40?1:-1)+($num[14]>40?1:-1)+($num[15]>40?1:-1)+($num[16]>40?1:-1)+($num[17]>40?1:-1)+($num[18]>40?1:-1)+($num[19]>40?1:-1);

		if($compare>0){
			return '下盘';
		}elseif($compare<0){
			return '上盘';
		}elseif($compare==0){
			return '中盘';
		}
	}

	if($type==4){//奇偶和盘
		$compare =($num[0]%2==0?1:-1)+($num[1]%2==0?1:-1)+($num[2]%2==0?1:-1)+($num[3]%2==0?1:-1)+($num[4]%2==0?1:-1)+($num[5]%2==0?1:-1)+($num[6]%2==0?1:-1)+($num[7]%2==0?1:-1)+($num[8]%2==0?1:-1)+($num[9]%2==0?1:-1)+($num[10]%2==0?1:-1)+($num[11]%2==0?1:-1)+($num[12]%2==0?1:-1)+($num[13]%2==0?1:-1)+($num[14]%2==0?1:-1)+($num[15]%2==0?1:-1)+($num[16]%2==0?1:-1)+($num[17]%2==0?1:-1)+($num[18]%2==0?1:-1)+($num[19]%2==0?1:-1);

		if($compare>0){
			return '偶盘';
		}elseif($compare<0){
			return '奇盘';
		}elseif($compare==0){
			return '和盘';
		}
	}


}
?>
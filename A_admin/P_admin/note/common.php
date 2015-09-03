<?php
function getmatch_name($table,$sqlwhere,$type=0){

	global $mysqli;
	if($type == 0){
  		$sql	=	"select match_name from $table ".$sqlwhere." group by match_name";
		$query	=	$mysqli->query($sql);
		while($rs = $query->fetch_array()){
			$arr[]	=	$rs['match_name'];
		}
		return $arr;
	}else{
		$sql	=	"select x_title as match_name from $table ".$sqlwhere." group by x_title";
		$query	=	$mysqli->query($sql);
		while($rs = $query->fetch_array()){
			$arr[]	=	$rs['match_name'];
		}
		return $arr;
	}
}
	
function getString($s){
	return $s ? $s : '0';
}

function getColor($money){
	if($money>=100000){
		return '#FE98A7';
	}elseif($money>=10000){
		return '#FFCAD2';
	}elseif($money>=1000){
		return '#FFDFE7';
	}elseif($money>=10){
		return '#FFF0F2';
	}else{
		return '#FFFFFF';
	}
}

function getAC($num){
	return $num>0 ? 'style="color:#FF0000;"' : '';
}
?>
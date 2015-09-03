<?php
class volleyball_match{
	static function getmatch_name($sqlwhere){
	
		global $mysqli;
		$arr	=	array();	
		$sql	=	"select match_name from volleyball_match ".$sqlwhere." group by match_name";
		$query	=	$mysqli->query($sql);
		while($rs = $query->fetch_array()){
			$arr[]	=	$rs['match_name'];
		}
		return $arr;
	}
	
	static function getmatch_info($match_id,$point_column="match_name"){
		
		global $mysqli;
		$sql	=	"select match_name,match_master,match_time,match_date,match_showtype,Match_CoverDate,match_guest,match_type,match_rgg,match_dxgg,$point_column from volleyball_match where match_id=$match_id limit 1";
		$query	=	$mysqli->query($sql);  		
		$rs		=	$query->fetch_array();
		return $rs;
	}
}
?>
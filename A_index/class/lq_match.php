<?php
ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(1);                    //打印出所有的 错误信息


class lq_match{

	static function getmatch_name($sqlwhere){
	
		global $mysqli;
		$arr	=	array();	
  		$sql	=	"select match_name from lq_match ".$sqlwhere." group by match_name";
		$query	=	$mysqli->query($sql);
		while($rs = $query->fetch_array()){
			$arr[]	=	$rs['match_name'];
		}
		return $arr;
  	}
	
  	static function getmatch_info($match_id,$point_column="Match_Name",$ball_sort=""){
		
		global $mysqli;
        global $redis;
		if($ball_sort != "篮球滚球"){
			$sql	=	"select match_name,match_master,match_time,match_date,Match_CoverDate,match_guest,Match_NowScore,match_showtype,match_type,match_rgg,match_dxgg,$point_column from lq_match where match_id=$match_id limit 1";
			$query	=	$mysqli->query($sql);  		
			$rs		=	$query->fetch_array();
			
			return $rs;
        }else{
            $d=RedisLQGQ();
            $lqgq=$d['lqgq'];
            ////////////////////////////
			//include_once("../../cache/lqgq.php");
			for($i=0;$i<count($lqgq);$i++){
				if($match_id == $lqgq[$i]['Match_ID']) break;
			}
			$rs						=	array();
			$rs['match_name']		=	$lqgq[$i]['Match_Name'];
			$rs['match_master']		=	$lqgq[$i]['Match_Master'];
			$rs['match_time']		=	$lqgq[$i]['Match_Time'];
			$rs['match_guest']		=	$lqgq[$i]['Match_Guest'];
			$rs['Match_NowScore']	=	$lqgq[$i]['Match_NowScore'];
			$rs['match_showtype']	=	$lqgq[$i]['Match_ShowType'];
			$rs['match_type']		=	$lqgq[$i]['Match_Type'];
			$rs['match_rgg']		=	$lqgq[$i]['Match_RGG'];
			$rs['match_dxgg']		=	$lqgq[$i]['Match_DxGG'];
			$rs[$point_column]		=	round($lqgq[$i][$point_column],2);
			
			$sql	=	"select Match_CoverDate from lq_match where match_id=$match_id limit 1"; //取开赛时间
			$query	=	$mysqli->query($sql);
			if($row =	$query->fetch_array()){
				$rs['Match_CoverDate']	=	$row['Match_CoverDate'];
			}
			
			return $rs;
		}
  	}	
}
?>
<?php
class bet_ds{
  	static function dx_add($uid,$ball_sort,$point_column,$match_name,$master_guest,$match_id,$bet_info,$bet_money,$bet_point,$ben_add,$bet_win,$match_time,$match_endtime,$lose_ok,$match_showtype,$match_rgg,$match_dxgg,$match_nowscore,$match_type,$balance,$assets,$Match_HRedCard,$Match_GRedCard,$ksTime,$InsertTime){
	
		global $mysqlt;
  		$mysqlt->autocommit(FALSE);
		$mysqlt->query("BEGIN"); //事务开始
		try{
			//include("../../cache/conf.php");
			$sql	=	"insert into k_bet(uid,ball_sort,point_column,match_name,master_guest,match_id,bet_info,bet_money,bet_point,ben_add,bet_win,match_time,bet_time,match_endtime,lose_ok,match_showtype,match_rgg,match_dxgg,match_nowscore,match_type,balance,assets,Match_HRedCard,Match_GRedCard,www,match_coverdate,site_id,agent_id,username) values ('$uid','$ball_sort','$point_column','$match_name','$master_guest','$match_id','$bet_info','$bet_money','$bet_point','$ben_add','$bet_win','$match_time','$InsertTime','$match_endtime','$lose_ok','$match_showtype','$match_rgg','$match_dxgg','$match_nowscore','$match_type',$balance,$assets,$Match_HRedCard,$Match_GRedCard,'','$ksTime','".SITEID."','".$_SESSION["agent_id"]."','".$_SESSION['username']."')"; //新增一个投注项
			$mysqlt->query($sql);
			$q1		=	$mysqlt->affected_rows;
			$id 	=	$mysqlt->insert_id;
			$datereg=	date("YmdHis",strtotime($InsertTime)).$id;
			$sql	=	"update `k_bet` set `number`='$datereg' where bid=$id"; //更新信息
			$mysqlt->query($sql);
			$q2		=	$mysqlt->affected_rows;
			$sql	=	"update k_user set money=money-$bet_money where money>=$bet_money and uid=$uid and $balance>=0"; //扣钱
			$mysqlt->query($sql);
			$q3		=	$mysqlt->affected_rows;
            $sql_cash	=	"insert into k_user_cash_record(
            site_id,uid,cash_type,cash_do_type,cash_num,cash_balance,cash_date,remark,source_id,username,agent_id,source_type) values
            ('".SITEID."','".$uid."','2','2','".$bet_money."','".$balance."','$InsertTime','体育注单：".$datereg."','$id','".$_SESSION['username']."','".$_SESSION['agent_id']."',8)";//scoure_type 8 单式 9 串关
            $mysqlt->query($sql_cash);
            $q4		=	$mysqlt->affected_rows;
			if($q1==1 && $q2==1 && $q3==1 && $q4==1){
				$mysqlt->commit(); //事务提交
				return array($id,$datereg);
			}else{
				$mysqlt->rollback(); //数据回滚
				return  false;
			}
		}catch(Exception $e){
			$mysqlt->rollback(); //数据回滚
			return  false;
		}
  	}
}
?>
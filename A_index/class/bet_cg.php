<?php
set_include_path(get_include_path()
    . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'].'\class' 
    . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'].'\cache'
	 . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'].'\include'
	 . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'].'\\'
	 );
class bet_cg{
	static function js($gid){
		
		global $mysqlt;
		$sql		=	"select count(*) as nums from k_bet_cg where gid=$gid";
		$query		=	$mysqlt->query($sql);
		$rows 		=	$query->fetch_array();
		$cg_count	=	$rows["nums"];
		$sql		=	"select g.gid from k_bet_cg_group g where $cg_count=(select count(b.gid) from k_bet_cg b where `status` in(1,2,3,4,5,8) and b.gid=g.gid) and $cg_count>(select count(b.gid) from k_bet_cg b where `status` in(3,8) and b.gid=g.gid) and g.gid=$gid";
		$query		=	$mysqlt->query($sql);
		
		if($query->num_rows > 0){    ///判断串关中的子项是否都已结算,且所有的子项不全是平手或无效
			$sql	=	"update k_user,k_bet_cg_group set k_user.money=k_user.money+k_bet_cg_group.win,k_bet_cg_group.status=1,k_bet_cg_group.update_time=now(),k_bet_cg_group.fs=0 where k_user.uid=k_bet_cg_group.uid and k_bet_cg_group.gid=$gid and k_bet_cg_group.status!=1";
		}else{
			$sql	=	"select g.bet_money from k_bet_cg_group g where $cg_count=(select count(b.gid) from k_bet_cg b where b.status in(3,8) and b.gid=g.gid) and g.gid=$gid";
			$query	=	$mysqlt->query($sql);
			
			if($query->num_rows > 0){    /////如果所有子项都是平手或无效,则把串关状态设为3,并把串关的已赢金额设为下注金额.
				$sql=	"update k_user,k_bet_cg_group set k_user.money=k_user.money+k_bet_cg_group.bet_money,k_bet_cg_group.status=3,k_bet_cg_group.win=k_bet_cg_group.bet_money,k_bet_cg_group.update_time=now() where k_user.uid=k_bet_cg_group.uid and k_bet_cg_group.gid=$gid and k_bet_cg_group.status!=3";
			}
		}
		
		$mysqlt->autocommit(FALSE);
		$mysqlt->query("BEGIN"); //事务开始
		try{
			$mysqlt->query($sql);
			$q1	=	$mysqlt->affected_rows;
			if($q1 == 2 || $q1 == 1){
				$mysqlt->commit(); //事务提交
				
				return true;
			}else{
				$mysqlt->rollback(); //数据回滚
				
				return false;
			}
		}catch(Exception $e){
			$mysqlt->rollback(); //数据回滚
			
			return false;
		}
	}
}
?>
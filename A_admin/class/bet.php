<?php
class bet{
    static function set($bid,$status,$mb_inball=NULL,$tg_inball=NULL){ //审核当前投注项1，2， 4，5 赢，输，赢一半，输一半
	
		$sql	=	"";
		$msg	=	"";
		$bid=intval($bid);

		global $mysqlt;
		$sql_f	=	"select g.zqfsbl, g.id,u.gid,b.uid from k_user u,k_group g,k_bet b where b.bid=".$bid." and u.uid=b.uid and g.id=u.gid limit 1";
		//$sql_f	=	"select zqfsbl from k_group where id='".$_SESSION["gid"]."' limit 1";
		$query_f	=	$mysqlt->query($sql_f);
		$rows_f	=	$query_f->fetch_array();
		$fsbl=$rows_f["zqfsbl"];//反水比例
		if(!is_numeric($fsbl))$fsbl=0;
		

    	switch ($status){
    		case "1": //赢
    			$sql	=	"update k_user,k_bet set k_user.money=k_user.money+k_bet.bet_win+(k_bet.bet_money*$fsbl),k_bet.win=k_bet.bet_win,k_bet.status=1 ,k_bet.update_time=now(),k_bet.MB_Inball='$mb_inball',k_bet.TG_Inball='$tg_inball',fs=k_bet.bet_money*$fsbl where k_user.uid=k_bet.uid and k_bet.bid='$bid' and k_bet.status=0";
                $msg	=	"审核了编号为".$bid."的注单,设为赢";
				break; 
   			case "2": //输
   				$sql	=	"update k_user,k_bet set k_user.money=k_user.money+(k_bet.bet_money*$fsbl),status=2,update_time=now(),k_bet.MB_Inball='$mb_inball',k_bet.TG_Inball='$tg_inball',fs=k_bet.bet_money*$fsbl where k_user.uid=k_bet.uid and k_bet.bid='$bid' and k_bet.status=0";
   				$msg	=	"审核了编号为".$bid."的注单,设为输";
   				break;
   		    case "3": //无效或取消
   				$sql	=	"update k_user,k_bet set k_user.money=k_user.money+k_bet.bet_money,k_bet.win=k_bet.bet_money,k_bet.status=3,k_bet.update_time=now(),k_bet.sys_about='批量无效',k_bet.MB_Inball='$mb_inball',k_bet.TG_Inball='$tg_inball' where k_user.uid=k_bet.uid and k_bet.bid='$bid' and k_bet.status=0";
   				$msg	=	"审核了编号为".$bid."的注单,设为取消";
   				break;
		    case "4": //赢一半
   				$sql	=	"update k_user,k_bet set k_user.money=k_user.money+k_bet.bet_money+((k_bet.bet_money/2)*k_bet.bet_point)+(k_bet.bet_money*$fsbl/2),k_bet.win=k_bet.bet_money+((k_bet.bet_money/2)*k_bet.bet_point),k_bet.status=4 ,k_bet.update_time=now(),k_bet.MB_Inball='$mb_inball',k_bet.TG_Inball='$tg_inball',fs=k_bet.bet_money*$fsbl/2 where k_user.uid=k_bet.uid and k_bet.bid='$bid' and k_bet.status=0";
   				$msg	=	"审核了编号为".$bid."的注单,设为赢一半";
   				break;
			case "5": //输一半
   				$sql	=	"update k_user,k_bet set k_user.money=k_user.money+(k_bet.bet_money/2)+(k_bet.bet_money*$fsbl/2),k_bet.win=k_bet.bet_money/2,k_bet.status=5,k_bet.update_time=now(),k_bet.MB_Inball='$mb_inball',k_bet.TG_Inball='$tg_inball',fs=k_bet.bet_money*$fsbl/2 where k_user.uid=k_bet.uid and k_bet.bid='$bid' and k_bet.status=0";
				$msg	=	"审核了编号为".$bid."的注单,设为输一半";
   				break;
   		    case "8": //和局
   				$sql	=	"update k_user,k_bet set k_user.money=k_user.money+k_bet.bet_money,k_bet.win=k_bet.bet_money,k_bet.status=8,k_bet.update_time=now(),k_bet.MB_Inball='$mb_inball',k_bet.TG_Inball='$tg_inball' where k_user.uid=k_bet.uid and k_bet.bid='$bid' and k_bet.status=0";
   				$msg	=	"审核了编号为".$bid."的注单,设为和";
   				break;
   			
			default:break;
    	}
		
		
		global $mysqlto;
		$mysqlt->autocommit(FALSE);
		$mysqlt->query("BEGIN"); //事务开始
		try{
			$mysqlt->query($sql);
			$q1	=	$mysqlt->affected_rows;
			if($q1 == 2 || $q1 == 1){
				$mysqlt->commit(); //事务提交

                $mysqlt->query("insert into sys_log(uid,log_info,log_ip) values('".$_SESSION["adminid"]."','$msg','".$_SERVER['REMOTE_ADDR']."')");
				
				return  true;
			}else{
				$mysqlt->rollback(); //数据回滚
				return  false;
			}
		}catch(Exception $e){
			$mysqlt->rollback(); //数据回滚
			return  false;
		}
    }
	
	static function set_cg($bid,$status,$mb_inball=NULL,$tg_inball=NULL){ //设置串关
    	
		$bid=intval($bid);
		global $mysqlt;
		global $mysqlto;
		$sql		=	"select gid,ben_add,bet_point from k_bet_cg where bid=".$bid;
		$query		=	$mysqlt->query($sql);
		$rows 		=	$query->fetch_array();
		$ben_add	=	$rows["ben_add"];
		$bet_point	=	$rows["bet_point"]; //获得赔率计算
		$gid		=	$rows["gid"];
		$q1			=	$q2	=	true;

		
		$mysqlt->autocommit(FALSE);
		$mysqlt->query("BEGIN"); //事务开始
		try{
			switch($status){
			
				case 1: //注单设为赢
					$sql			=	"update k_bet_cg set status=1,mb_inball=".$mb_inball.",tg_inball=".$tg_inball." where bid=$bid and status=0";
					$mysqlt->query($sql);
					$q1				=	$mysqlt->affected_rows;
					if($q1 == 1){
						$log_msg	=	"把串关单式注单编号为".$bid."设为赢";
						$show_msg	=	"单式编号".$bid."审核完成";
					}else {
						$show_msg	=	"串关单式编号".$bid."审核出错";
						break;
					}
					
					$sql			=	"select win,bet_money from k_bet_cg_group where gid=$gid and `status`=0";
					$query			=	$mysqlt->query($sql);
					$rows 			=	$query->fetch_array();
					$win			=	$rows["win"];
					$bet_money		=	$rows["bet_money"];
					$point			=	$ben_add+$bet_point;
					if($win			==	0){ //如果该组第一次结算
						$win		=	$bet_money*$point;	
					}else{ //第二次结算
						$win		=	$win*$point;
					}
			  
					$sql			=	"update k_bet_cg_group set win=$win where gid=$gid"; //金额设置
					$mysqlt->query($sql);
					$q2				=	$mysqlt->affected_rows;
					if($q2 != 1){
						$show_msg  .=	"串关组中加钱出现错误";
					}
					break;
					
				case 2://输
			  
					$sql			=	"update k_bet_cg set status=2,mb_inball=".$mb_inball.",tg_inball=".$tg_inball." where bid=$bid and status=0";
					$mysqlt->query($sql);
					$q1				=	$mysqlt->affected_rows;
					if($q1 == 1){
						$log_msg	=	"把串关单式注单编号为".$bid."设为输";
						$show_msg	=	"单式编号".$bid."审核完成";
					}else {
						$msg		=	"串关单式编号".$bid."审核出错";
						break;
					}
			  
					$sql			=	"update k_bet_cg_group set win=0,status=2 where gid=$gid";
					$mysqlt->query($sql);
					$q2				=	$mysqlt->affected_rows;
					break;
					
				case 3: //无效
			  
					$sql			=	"update k_bet_cg set status=3,mb_inball=".$mb_inball.",tg_inball=".$tg_inball." where bid=$bid and status=0";
					$mysqlt->query($sql);
					$q1				=	$mysqlt->affected_rows;
					if($q1 == 1){
						$log_msg	=	"把注单编号为".$bid."的串关单式设为无效";
						$show_msg	=	"设为无效操作完成";
					}else{
						$show_msg	=	"串关单式编号".$bid."审核出错";
						break;
					}
					
					$sql			=	"update k_bet_cg_group set cg_count=cg_count-1 where gid=$gid";
					$mysqlt->query($sql);
					$q2				=	$mysqlt->affected_rows;
					if($q2 != 1){
						$show_msg  .=	"减1过程中出现错误";
					}
					break;
			  
				case 4://赢一半
			  
					$sql			=	"update k_bet_cg set status=4,mb_inball=".$mb_inball.",tg_inball=".$tg_inball." where bid=$bid and status=0";
					$mysqlt->query($sql);
					$q1				=	$mysqlt->affected_rows;
					if($q1 == 1){
						$log_msg	=	"把注单编号为".$bid."的串关单式设为赢一半";
						$show_msg	=	"设为赢一半操作完成";
					}else{
						$show_msg	=	"串关单式编号".$bid."审核出错";
						break;
					}
					$sql			=	"select win,bet_money from k_bet_cg_group where gid=$gid and `status`=0";
					$query			=	$mysqlt->query($sql);
					$rows 			=	$query->fetch_array();
					$win			=	$rows["win"];
					$bet_money		=	$rows["bet_money"];
					$point			=	1+$bet_point/2;
					if($win			==	0){ //如果该组第一次结算
						$win		=	$bet_money*$point;	
					}else{ //第二次结算
						$win		=	$win*$point;
					}
			  
					$sql			=	"update k_bet_cg_group set win=$win where gid=$gid and `status`=0";
					$mysqlt->query($sql);
					$q2				=	$mysqlt->affected_rows;
					if($q2 != 1){
						$show_msg  .=	"加钱过程中出现错误，该组串关已结算";
					}
					break;
			  
				case 5://输一半
			  
					$sql			=	"update k_bet_cg set status=5,mb_inball=".$mb_inball.",tg_inball=".$tg_inball." where bid=$bid and status=0";
					$mysqlt->query($sql);
					$q1				=	$mysqlt->affected_rows;
					if($q1 == 1){
						$log_msg	=	"把注单编号为".$bid."的串关单式设为输一半";
						$show_msg	=	"设为输一半操作完成";
					}else{
						$msg		=	"串关单式编号".$bid."审核出错";
						break;
					}
					$sql			=	"select win,bet_money from k_bet_cg_group where gid=$gid and `status`=0";
					$query			=	$mysqlt->query($sql);
					$rows 			=	$query->fetch_array();
					$win			=	$rows["win"];
					$bet_money		=	$rows["bet_money"];
					$point			=	0.5;
					if($win			==	0){ //如果该组第一次结算
						$win		=	$bet_money*$point;	
					}else{ //第二次结算
						$win		=	$win*$point;
					}
			  
					$sql			=	"update k_bet_cg_group set win=$win where gid=$gid and `status`=0";
					$mysqlt->query($sql);
					$q2				=	$mysqlt->affected_rows;
					if($q2 != 1){
						$show_msg  .=	"加钱过程中出现错误，该组串关已结算";
					}
					break; 
			  
				case 8: //平手
			  
					$sql			=	"update k_bet_cg set status=8,mb_inball=".$mb_inball.",tg_inball=".$tg_inball." where bid=$bid and status=0";
					$mysqlt->query($sql);
					$q1				=	$mysqlt->affected_rows;
					if($q1 == 1){
						$log_msg	=	"把注单编号为".$bid."的串关单式设为平手";
						$show_msg	=	"设为平手操作完成";
					}else{
						$show_msg	=	"串关单式编号".$bid."审核出错";
						break;
					}
					break; 
				
					
				default:break;
			}
		 	
			if($q1 == 1){
				$mysqlt->commit(); //事务提交
				if(isset($log_msg)){
					$mysqlto->query("insert into sys_log(uid,log_info,log_ip) values ('".$_SESSION["adminid"]."','".$log_msg."','".$_SERVER['REMOTE_ADDR']."')");
				}
				return  true;
			}else{
				$mysqlt->rollback(); //数据回滚
				return  false;
			}
		}catch(Exception $e){
			$mysqlt->rollback(); //数据回滚
			return  false;
		}
	}
	
	static function qx_bet($bid,$status){ //单式重新结算
    	
		global $mysqlt;
		$money		=	0;
		if($status==1 || $status==2 || $status==4 || $status==5){ //有退水
			$sql	=	"select bet_money from k_bet where bid=$bid";
			$query	=	$mysqlt->query($sql);
			$row 	=	$query->fetch_array();
			if($status	==1 || $status	==2){ //输赢
				$money	=	$row['bet_money']*$fsbl;
			}
			if($status	==4 || $status	==5){
				$money	=	$row['bet_money']*$fsbl/2;
			}
		}
		
		$mysqlt->autocommit(FALSE);
		$mysqlt->query("BEGIN"); //事务开始
		try{
			$sql		=	"update k_bet,k_user set k_user.money=k_user.money-k_bet.win-$money where k_user.uid=k_bet.uid and k_bet.bid=$bid and k_bet.status>0";
			$mysqlt->query($sql);
			$q1			=	$mysqlt->affected_rows;
			$sql		=	"update k_bet set status=0,win=0,update_time=null,fs=0 where k_bet.bid=$bid and k_bet.status>0";
			$mysqlt->query($sql);
			$q2			=	$mysqlt->affected_rows;
			//if($q1==1 && $q2==1){
				$mysqlt->commit(); //事务提交
				return true;
			/*}else{
				$mysqlt->rollback(); //数据回滚
				return false;
			}*/
		}catch(Exception $e){
			$mysqlt->rollback(); //数据回滚
			return false;
		}
	}
	
	static function qx_cgbet($bid){ //串关重新结算
		
		global $mysqlt;
		$sql		=	"select cg.status,cgg.gid,cgg.status as status_cgg,cg.bet_point,cg.match_id,cg.ben_add,cgg.win,cgg.uid,cgg.bet_money,cgg.fs from k_bet_cg cg,k_bet_cg_group cgg where cg.status>0 and cg.gid=cgg.gid and cg.bid=$bid";
		$query		=	$mysqlt->query($sql);
		$t 			=	$query->fetch_array();
		
		$status_cgg	=	$t["status_cgg"];
		$status		=	$t["status"];
		$gid		=	$t["gid"];
		$win		=	$t["win"];
		$uid		=	$t["uid"];
		$ben_add	=	$t["ben_add"];
		$bet_point	=	$t["bet_point"];
		$ts_money	=	$t["fs"];
		$mid		=	$t["match_id"];
		
		$b1	=	$b3	=	$b4	=	$b6	=	false;
			
		$mysqlt->autocommit(FALSE);
		$mysqlt->query("BEGIN"); //事务开始
		try{
			if($status_cgg == 1){ //已结算，扣相应的钱，并设为未结算
				$b1		=	true;
				$sql	=	"select count(gid) as s from k_bet_cg where gid=$gid and status=2 and bid!=$bid";
				$query	=	$mysqlt->query($sql);
				$t 		=	$query->fetch_array();
				
				if($t["s"] > 0){  ///判断子项中是否有输的
					$sql=	"update k_user,k_bet_cg_group set k_user.money=k_user.money-k_bet_cg_group.win-$ts_money,k_bet_cg_group.status=2 where k_user.uid=k_bet_cg_group.uid and k_bet_cg_group.gid=$gid";
				}else{
					$sql=	"update k_user,k_bet_cg_group set k_user.money=k_user.money-k_bet_cg_group.win-$ts_money,k_bet_cg_group.status=0 where k_user.uid=k_bet_cg_group.uid and k_bet_cg_group.gid=$gid";
				}
				$mysqlt->query($sql);
				$q1		=	$mysqlt->affected_rows;
				
			}elseif($status_cgg	== 3){     ////如果状态等于3,说明该串关全是平手或无效,则把状态设为0,且扣去相应的钱..
				$b1		=	true;
				$sql	=	"update k_user,k_bet_cg_group set k_user.money=k_user.money-k_bet_cg_group.win,k_bet_cg_group.status=0,k_bet_cg_group.win=0 where k_user.uid=k_bet_cg_group.uid and k_bet_cg_group.gid=$gid";
				$win	=	0;
				$mysqlt->query($sql);
				$q1		=	$mysqlt->affected_rows;
			}
			
			$sql		=	"update k_bet_cg set status=0 where bid=$bid";
			$mysqlt->query($sql);
			$q3			=	$mysqlt->affected_rows;
			
			$sql		=	"update k_bet_cg_group set update_time=null,fs=0 where gid=$gid"; //更新时间设为空
			$mysqlt->query($sql);
			
			if($status == 2){
				$sql	=	"update k_bet_cg_group g set g.status=0 where g.cg_count=(select count(b.gid) from k_bet_cg b where b.gid=g.gid and b.status!=2) and g.gid=$gid";
				$mysqlt->query($sql);
				$sql 	=	"select status,gid,ben_add,bet_point from k_bet_cg where gid=$gid and status not in(0,3,6,7,8) and  bid!=$bid";
				$query	=	$mysqlt->query($sql);
				
				while($infos	=	$query->fetch_array()){
					$benadd		=	$infos["ben_add"];
					$betpoint	=	$infos["bet_point"]; //获得赔率计算
					$gid		=	$infos["gid"];
					
					$sql		=	"select win,bet_money from k_bet_cg_group where gid=$gid and status=0";
					$query1		=	$mysqlt->query($sql);
					$tx			=	$query1->fetch_array();
					
					$txwin		=	$tx["win"];
					$betmoney	=	$tx["bet_money"];
					$points		=	$benadd+$betpoint;
					
					if($infos["status"] == 1){
						if($txwin==0){ 				//如果该组第一次结算
							$txwin=$betmoney*$points;
						}else{						//第二次结算
							$txwin=$txwin*$points;
						}
						
						$sql="update k_bet_cg_group set win=$txwin where gid=$gid and status=0"; //金额设置
						$mysqlt->query($sql);
					}		
						
					if($infos["status"] == 2){
						$sql="update k_bet_cg_group set win=0,status=2 where gid=$gid";
						$mysqlt->query($sql);
					}			
						
					if($infos["status"] == 4){
						$points=1+$betpoint/2;
						
						if($txwin==0){ 				//如果该组第一次结算
							$txwin=$betmoney*$points;
						}else{						//第二次结算
							$txwin=$txwin*$points;
						}
						
						$sql="update k_bet_cg_group set win=$txwin where gid=$gid and status=0";
						$mysqlt->query($sql);
					}			
						
					if($infos["status"] == 5){
						$points=0.5;
						
						if($txwin==0){ 				//如果该组第一次结算
							$txwin=$betmoney*$points;
						}else{						//第二次结算
							$txwin=$txwin*$points;
						}
						
						$sql="update k_bet_cg_group set win=$txwin where gid=$gid and status=0";
						$mysqlt->query($sql);
					}
				}
			}else{
				if($status==1){  //赢
					$win	=	$win/($ben_add+$bet_point);
				}
				if($status==3){
					$b4		=	true;
					$win	=	$win;
					$sql	=	"update k_bet_cg_group set cg_count=cg_count+1 where gid=$gid";
					$mysqlt->query($sql);
					$q4		=	$mysqlt->affected_rows;
				}
				if($status==4){ //赢一半
					$win	=	$win*2/(1+$ben_add+$bet_point);
				}
				if($status==5){ //输一半
					$win	=	2*$win;
				}
				if($status==6){
					$b6		=	true;
					$sql	=	"update k_bet_cg set status=0 where bid=$bid";
					$mysqlt->query($sql);
					$q6_1	=	$mysqlt->affected_rows;
					$sql	=	"update k_bet_cg_group set status=0 where gid=$gid";
					$mysqlt->query($sql);
					$q6_2	=	$mysqlt->affected_rows;
					$win	=	0;
				}
				$sql		=	"update k_bet_cg_group set win=$win where gid=$gid";
				$mysqlt->query($sql);
				$sql		=	"update k_bet_cg_group set win=0 where gid=$gid and (select count(bid) from k_bet_cg where gid=$gid and status>0)=0";
				$mysqlt->query($sql);
				$sql		=	"update k_bet_cg_group set win=0 where gid=$gid and (select count(bid) from k_bet_cg where gid=$gid and bid!=$bid and status in(0,3,8))=(k_bet_cg_group.cg_count-1)";
				$mysqlt->query($sql);
			}
			
			if($q3>0){
				while(1){
					if($b1){
						if($q1>0) $b3 = true;
						else{
							$b3		= false;
							break;
						}
					}
					if($b4){
						if($q4>0) $b3 = true;
						else{
							$b3		= false;
							break;
						}
					}
					if($b6){
						if($q6_1>0 && $q6_2>0) $b3 = true;
						else{
							$b3		= false;
							break;
						}
					}
					$b3 = true;
					break;
				}
			}
			
			if($b3){
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
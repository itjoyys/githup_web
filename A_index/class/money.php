<?php
class money{
	static function chongzhi($uid,$order,$money,$assets,$status=2,$about=''){
	
		$uid=intval($uid);
		global $mysqlt;
    	$sql_money	=	"insert into k_money(uid,m_value,status,m_order,about,assets,balance,type) values ($uid,$money,$status,'$order','$about',$assets,$assets+$money,101)";
		$sql_user	=	"update k_user set money=money+$money where uid='$uid'";
		
    	$mysqlt->autocommit(FALSE);
		$mysqlt->query("BEGIN"); //事务开始
		try{
			$mysqlt->query($sql_money);
			$q1		=	$mysqlt->affected_rows;
			$mysqlt->query($sql_user);
			$q2		=	$mysqlt->affected_rows;
			if($q1==1 && $q2==1){
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
		  
	static function tixian($uid,$money,$assets,$pay_card,$pay_num,$pay_address,$pay_name,$order=0,$status=2,$about=''){
		$uid=intval($uid);
		global $mysqlt;
    	$sql_user	=	"update k_user set money=money-$money where uid='$uid'";
		
    	$money		=	0-$money; //把金额置成带符号数字
    	if($order	==	'0'){
			$order	=	date("YmdHis")."_".$_SESSION['username'];
		}
		$sql_money	=	"insert into k_money(uid,m_value,status,m_order,pay_card,pay_num,pay_address,pay_name,about,assets,balance,type) values($uid,$money,$status,'$order','$pay_card','$pay_num','$pay_address','$pay_name','$about',$assets,$assets+$money,102)";
    	
		$mysqlt->autocommit(FALSE);
		$mysqlt->query("BEGIN"); //事务开始
		try{
			$mysqlt->query($sql_user);
			$q1		=	$mysqlt->affected_rows;
			$mysqlt->query($sql_money);
			$q2		=	$mysqlt->affected_rows;
			if($q1==1 && $q2==1){
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
<?php
/**
* 取出代理额度
* uid 代理编号
* xjuid 该代理的下级会员编号
* s 指定开始时间
* e 指定结束时间
**/
function getDLED($uid,$s,$e){
	$uid=intval($uid);
	global $mysqlt;
	$yk				=	0; //默认代理额度为 0
	$xjuid			=	'';
	$sql			=	"select uid,username from k_user where top_uid='$uid' and reg_date<='$e' order by uid desc"; //取出该代理所有下级会员
	$query			=	$mysqlt->query($sql);
	while($row		=	$query->fetch_array()) {
		$xjusername	.=	'"'.$row['username'].'",';
		$xjuid	.=	$row['uid'].',';
	}

	if($xjuid){
		$xjuid		=	rtrim($xjuid,',');
		$xjusername		=	rtrim($xjusername,',');
		$sql		=	"select m_value,about,sxf from k_money where `status`=1 and `type`=1 and m_make_time>='$s' and m_make_time<='$e' and uid in(".$xjuid.")"; //本月会员存款取款总额
		$query		=	$mysqlt->query($sql);
		while($row	=	$query->fetch_array()){
			if($row['m_value'] > 0){ //存款
				if(!strstr($row['about'],"后台加款")){

				if(strstr($row['about'],"管理员结算") || $row['about'] == '' || $row['about'] == 'The order system is successful' || $row['about'] == '该订单手工操作成功' || $row['about'] == '该订单在线冲值操作成功'){ //不是系统赠送金额
					$ck[$row['uid']]	+=	$row['m_value'];
					$yk[$row['uid']]	-=	$row['sxf'];
				}else{
					$yk[$row['uid']]	-=	$row['m_value'];
				}
			}
			}else{ //取款
				$yk	-=	$row['sxf'];
			}
		}
		//echo $yk."<br><br>";
		$sql		=	"select zsjr from huikuan where `status`=1 and `adddate`>='$s' and `adddate`<='$e' and uid in(".$xjuid.")"; //本月会员汇款总额
		$query		=	$mysqlt->query($sql);
		while($row	=	$query->fetch_array()){
			$yk		-=	$row['zsjr'];
		}
		$sql		=	"select bet_money,win,fs from k_bet where `status` in (1,2,4,5) and uid in(".$xjuid.") and match_coverdate>='$s' and  match_coverdate<='$e'"; //单式盈亏,未结算，无效不算
		$query		=	$mysqlt->query($sql);
		while($row	=	$query->fetch_array()){
			$yk		+=	$row['bet_money']; //先扣交易金额
			$yk		-=	$row['win']; //再加已赢金额
			$yk		-=	$row['fs']; //再加返水金额
		}
		$sql		=	"select bet_money,win,fs from k_bet_cg_group where `status`=1 and uid in(".$xjuid.") and match_coverdate>='$s' and  match_coverdate<='$e'"; //串关盈亏,已结算才计算
		$query		=	$mysqlt->query($sql);
		while($row	=	$query->fetch_array()){
			$yk		+=	$row['bet_money']; //先扣交易金额
			$yk		-=	$row['win']; //再加已赢金额
			$yk		-=	$row['fs']; //再加返水金额
		}

		$sql		=	"select money,win from c_bet where `js`=1 and uid in(".$xjuid.") and addtime>='$s' and  addtime<='$e'"; //时时彩
		$query		=	$mysqlt->query($sql);
		while($row	=	$query->fetch_array()){
			$yk		+=	$row['money']; //先扣交易金额
			$yk		-=	$row['win']; //再加已赢金额
		}


		//表bm=1就是赢
		$sql		=	"select sum_m,user_ds,bm,rate from ka_tan where checked=1 and username in(".$xjusername.") and adddate>='$s' and  adddate<='$e'"; //六合彩
		$query		=	$mysqlt->query($sql);
		while($row	=	$query->fetch_array()){
			$yk		+=	$row['sum_m']; //先扣交易金额

			$yk		-=  $row['sum_m']*abs($row['user_ds'])/100;
			if($row['bm']==1)
			{
				$yk		-=	$row['sum_m']*$row['rate']; //再加已赢金额
			}

		}

		//echo $e."-".$xjuid."-".$yk."<br>";
	}
	return round($yk,2);
}

/**
* 根据输赢情况获取提成比例值
* shuying 具体输赢金额
**/
function get_point($tysy,$cpsy,$sixsy,$hyfs,$sxf,$qtfy,$type){ //根据输赢获取提成比例
	$shuying=$tysy;
	$bl=0;
	$bcp=1;//如果为0，则时时彩不参与提成   ,需要参与提成请改为1
	$bsix=1;//如果为0，则六合彩不参数提成,需要参与提成请改为1
	if($bcp==1){
		$shuying=$shuying+$cpsy;
	}

	if($bsix==1){
		$shuying=$shuying+$sixsy;
	}

	if($shuying <= 0){
		$bl= 0;
    }elseif($shuying <= 10000){//输赢小于10000的提成为10%
		$bl= 0.1;
    }elseif($shuying <= 50000){
    	$bl= 0.2;
    }elseif($shuying <= 200000){
    	$bl= 0.3;
    }elseif($shuying <= 1000000){
    	$bl= 0.4;
    }else{
    	$bl= 0.5;
    }

	$yongjin=$shuying*$bl;
	$feiyong = $hyfs+$sxf+$qtfy;
	$yongjin = round($yongjin-$feiyong,0);

	if($type==1){
		return $yongjin;
	}else{
		return $bl;
	}
}
?>
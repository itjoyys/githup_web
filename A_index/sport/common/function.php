<?php
function double_format($double_num){
	return $double_num>0 ? sprintf("%.2f",$double_num) : $double_num<0 ? sprintf("%.2f",$double_num) : 0;
}

function cutString($title,$length=38,$bool=0){ //截取字符串
	$tmpstr = '';
    if($length >= strlen($title)) return $title;
	else{
	    for ($i= 0; $i < $length; $i++){
            if (ord(substr($title, $i, 1)) > 0xa0){
                $tmpstr .= substr($title, $i, 2);
                $i++;
            } else {
                $tmpstr .= substr($title, $i, 1);
            }
        }
	    if($bool) return $tmpstr.'..';
	    else return $tmpstr;
	}
}

/**
* 过滤html代码
**/
function htmlEncode($string) { 
	$string=trim($string); 
	$string=str_replace("\'","'",$string); 
	$string=str_replace("&amp;","&",$string); 
	$string=str_replace("&quot;","\"",$string); 
	$string=str_replace("&lt;","<",$string); 
	$string=str_replace("&gt;",">",$string); 
	$string=str_replace("&nbsp;"," ",$string); 
	$string=nl2br($string); 
	//$string=mysql_real_escape_string($string);
	return $string;
}
		
/**
* js 页面提示信息，后重定向页面
*/
function message($value,$url=""){ //默认返回上一页
	header("Content-type: text/html; charset=utf-8");
	$js  ='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>message</title>
</head>

<body>';
	$js  .= "<script type=\"text/javascript\" language=\"javascript\">\r\n";
	$js .= "alert(\"".$value."\");\r\n";
	if($url) $js .= "window.location.href=\"$url\";\r\n";
	else $js .= "window.history.go(-1);\r\n";
	$js .= "</script>\r\n";
$js.="</body></html>";
	echo $js;
	exit;
}

function write_bet_info($ball_sort,$column,$master_guest,$bet_point,$match_showtype,$match_rgg,$match_dxgg,$match_nowscore,$tid=0){
	$dm			=	explode("VS.",$master_guest); //队名
	$qcrq		=	array("Match_Ho","Match_Ao"); //全场让球盘口
	$qcdx		=	array("Match_DxDpl","Match_DxXpl"); //全场大小盘口
	$ds			=	array("Match_DsDpl","Match_DsSpl"); //单双
	$info		=	"";
	if(strrpos($ball_sort,"足球") === 0){
		$bcrq	=	array("Match_BHo","Match_BAo"); //半场让球盘口
		$bcdx	=	array("Match_Bdpl","Match_Bxpl"); //半场大小盘口
		$qcdy	=	array("Match_BzM","Match_BzG","Match_BzH"); //全场独赢
		$bcdy	=	array("Match_Bmdy","Match_Bgdy","Match_Bhdy"); //半场独赢
		$sbbdz	=	array("Match_Hr_Bd10","Match_Hr_Bd20","Match_Hr_Bd21","Match_Hr_Bd30","Match_Hr_Bd31","Match_Hr_Bd32","Match_Hr_Bd40","Match_Hr_Bd41","Match_Hr_Bd42","Match_Hr_Bd43"); //上半波胆主
		$sbbdk	=	array("Match_Hr_Bdg10","Match_Hr_Bdg20","Match_Hr_Bdg21","Match_Hr_Bdg30","Match_Hr_Bdg31","Match_Hr_Bdg32","Match_Hr_Bdg40","Match_Hr_Bdg41","Match_Hr_Bdg42","Match_Hr_Bdg43"); //上半波胆客
		$sbbdp	=	array("Match_Hr_Bd00","Match_Hr_Bd11","Match_Hr_Bd22","Match_Hr_Bd33","Match_Hr_Bd44","Match_Hr_Bdup5"); //上半波胆平
		$bdz	=	array("Match_Bd10","Match_Bd20","Match_Bd21","Match_Bd30","Match_Bd31","Match_Bd32","Match_Bd40","Match_Bd41","Match_Bd42","Match_Bd43"); //波胆主
		$bdk	=	array("Match_Bdg10","Match_Bdg20","Match_Bdg21","Match_Bdg30","Match_Bdg31","Match_Bdg32","Match_Bdg40","Match_Bdg41","Match_Bdg42","Match_Bdg43"); //波胆客
		$bdp	=	array("Match_Bd00","Match_Bd11","Match_Bd22","Match_Bd33","Match_Bd44","Match_Bdup5"); //波胆平
		$rqs	=	array("Match_Total01Pl","Match_Total23Pl","Match_Total46Pl","Match_Total7upPl"); //入球数
		$bqc	=	array("Match_BqMM","Match_BqMH","Match_BqMG","Match_BqHM","Match_BqHH","Match_BqHG","Match_BqGM","Match_BqGH","Match_BqGG"); //半全场
		
		if(in_array($column,$qcrq) || in_array($column,$bcrq)){ //让球
			if(in_array($column,$qcrq))		$info	.=	"让球-";
			else	$info	.=	"上半场让球-";
			
			if($match_showtype ==	"H")	$info	.=	"主让$match_rgg-";
			else	$info	.=	"客让$match_rgg-";
			
			if($column == "Match_Ho" || $column == "Match_BHo") $info .= $dm[0];
			else	$info	.=	$dm[1];
			
		}elseif(in_array($column,$qcdx) || in_array($column,$bcdx)){ //大小
			if(in_array($column,$qcdx)){
				$info		.=	"大小-";
				if($column	==	"Match_DxDpl")	$info	.=	"O";
				else $info	.=	"U";
			}else{
				$info		.=	"上半场大小-";
				if($column	==	"Match_Bdpl")	$info	.=	"O";
				else $info	.=	"U";
			}
			$info			.=	$match_dxgg;
		}elseif(in_array($column,$qcdy) || in_array($column,$bcdy)){ //独赢
			if(in_array($column,$qcdy))			$info	.=	"标准盘-";
			else	$info	.=	"上半场标准盘-";
			
			if(		$column == "Match_BzM" || $column == "Match_Bmdy") $info	.=	$dm[0]."-独赢";
			elseif(	$column == "Match_BzG" || $column == "Match_Bgdy") $info	.=	$dm[1]."-独赢";
			else	$info	.=	"和局";
		}elseif(in_array($column,$ds)){ //单双
			$info			.=	"单双-";
			if($column 		== "Match_DsDpl")	$info .= "单";
			else	$info	.=	"双";
		}elseif(in_array($column,$sbbdz) || in_array($column,$sbbdk) || in_array($column,$sbbdp) || in_array($column,$bdz) || in_array($column,$bdk) || in_array($column,$bdp)){ //波胆
			if(in_array($column,$sbbdz) || in_array($column,$sbbdk) || in_array($column,$sbbdp)) $info	.=	"上半波胆-";
			else	$info	.=	"波胆-";
			
			if(strrpos($column,"up5")){
				$info		.=	"UP5";
			}else{
				$z			 =	substr($column,-2,1);
				$k			 =	substr($column,-1,1);
				if(in_array($column,$sbbdz) || in_array($column,$bdz))	$info	.=	$z.":".$k;
				else $info	.=	$k.":".$z;
			}
		}elseif(in_array($column,$rqs)){ //入球数
			$info			.=	"入球数-";
			if(strrpos($column,"7up")){
				$info		.=	"7UP";
			}else{
				$info		.=	substr($column,-4,1)."~".substr($column,-3,1);
			}
		}elseif(in_array($column,$bqc)){ //半全场
			$info			.=	"半全场-";
			$n1				 = "".substr($column,-2,1);
			$n2				 = "".substr($column,-1,1);
			$n1				 = ($n1 === "H" ? "和" : ($n1 === "M" ? "主" : "客"));
			$n2				 = ($n2 === "H" ? "和" : ($n2 === "M" ? "主" : "客"));
			$info			.=	$n1."/".$n2;
		}
		if($ball_sort		==	"足球滚球"){
			$info		  	.=	"(".$match_nowscore.")";
		}
		$info				.=	"@".$bet_point;
	
	}elseif(strrpos($ball_sort,"篮球") === 0){
		if(in_array($column,$qcrq)){
			$info			.=	"让分-";
			if($match_showtype ==	"H") $info	.=	"主让$match_rgg-";
			else	$info	.=	"客让$match_rgg-";
			
			if($column 		== "Match_Ho")$info .= $dm[0];
			else	$info	.=	$dm[1];
			
		}elseif(in_array($column,$qcdx)){
			$info			.=	"大小-";
			if($column		==	"Match_DxDpl")$info	.=	"O$match_dxgg";
			else $info		.=	"U$match_dxgg";
			
		}elseif(in_array($column,$ds)){ //单双
			$info			.=	"单双-";
			if($column 		== "Match_DsDpl")	$info .= "单";
			else	$info	.=	"双";
		}
		$info			  	.=	"@".$bet_point;
	}elseif(strrpos($ball_sort,"棒球") === 0 || strrpos($ball_sort,"网球") === 0 || strrpos($ball_sort,"排球") === 0){
		$qcdy	=	array("Match_BzM","Match_BzG","Match_BzH"); //全场独赢
		if(in_array($column,$qcrq)){
			$info			.=	"让球-";
			if($match_showtype ==	"H") $info	.=	"主让$match_rgg-";
			else	$info	.=	"客让$match_rgg-";
			
			if($column 		== "Match_Ho")$info .= $dm[0];
			else	$info	.=	$dm[1];
			
		}elseif(in_array($column,$qcdx)){
			$info			.=	"大小-";
			if($column		==	"Match_DxDpl")$info	.=	"O$match_dxgg";
			else $info		.=	"U$match_dxgg";
			
		}elseif(in_array($column,$ds)){ //单双
			$info			.=	"单双-";
			if($column 		== "Match_DsDpl")	$info .= "单";
			else	$info	.=	"双";
		}elseif(in_array($column,$qcdy)){ //独赢
			$info			.=	"标准盘-";
			
			if(		$column == "Match_BzM") $info	.=	$dm[0]."-独赢";
			elseif(	$column == "Match_BzG") $info	.=	$dm[1]."-独赢";
		}
		$info			  	.=	"@".$bet_point;
	}elseif(strrpos($ball_sort,"金融") === 0 || strrpos($ball_sort,"冠军") === 0){
		global $mysqli;
		$query	=	$mysqli->query("SELECT team_name FROM t_guanjun_team where tid=$tid limit 1");
		
		$row	=	$query->fetch_array();//print_r($row);exit;
		if(strrpos($ball_sort,"金融") === 0) $row['team_name']=strtolower(str_replace(" ",'',$row['team_name']));
		$info	=	$row['team_name'].'@'.$bet_point;
	}
	
	return $info;
}

//特码单双
function danshuang($str){

    if($str%2 == 0){
        echo "<font color='blue';>双</font>";
    }else{
        echo "<font color='#f00;';>单</font>";
    }
}

//特码大小
function tm_daxiao($str){

    if($str >= 25){
        echo "<font color='blue';>大</font>";
    }else{
        echo "<font color='#f00;';>小</font>";
    }
}
//特码色波
function tm_sebo($str){

    $arr1=array(01,02,07,8,12,13,18,19,23,24,29,30,34,35,40,45,46);
    $arr2=array(03,04,9,10,14,15,20,25,26,31,36,37,41,42,47,48);
    $arr3=array(05,06,11,16,17,21,22,27,28,32,33,38,39,43,44,49);
    if($str != 0){

        if(in_array($str,$arr1)){
            echo "<font color='#f00;';>红波</font>";
        }else if(in_array($str,$arr2)){
            echo "<font color='blue';>蓝波</font>";
        }else if(in_array($str,$arr3)){
            echo "<font color='green';>绿波</font>";
        }
    }

}

//颜色
function set_style($str){

    $arr1=array(01,02,07,8,12,13,18,19,23,24,29,30,34,35,40,45,46);
    $arr2=array(03,04,9,10,14,15,20,25,26,31,36,37,41,42,47,48);
    $arr3=array(05,06,11,16,17,21,22,27,28,32,33,38,39,43,44,49);
    if($str != 0){

        if(in_array($str,$arr1)){
            echo "ball_r";
        }else if(in_array($str,$arr2)){
            echo "ball_b";
        }else if(in_array($str,$arr3)){
            echo "ball_g";
        }
    }

}


//总分大小
function zongfen_daxiao($str){
    $max = 49 * 6 / 2;
    if($str >= $max){
        echo "<font color='blue';>大</font>";
    }else{
        echo "<font color='#f00;';>小</font>";
    }
}

//合数大小
function heshu_daxiao($str){
    if(strlen($str) == 1){
        $str = "0".$str;
    }
    $num = substr($str,0,1);
    $num1 = substr($str,1,1);
    $data = intval($num) + intval($num1);
    if($data >= 7){
        echo "<font color='blue';>大</font>";
    }else{
        echo "<font color='#f00;';>小</font>";
    }

}
//合数单双
function heshu_danshuang($str){
    if(strlen($str) == 1){
        $str = "0".$str;
    }
    $num = substr($str,0,1);
    $num1 = substr($str,1,1);
    $data = intval($num) + intval($num1);
    if($data%2 == 0){
        echo "<font color='blue';>双</font>";
    }else{
        echo "<font color='#f00;';>单</font>";
    }
}
//生肖
function shenxiao($nianfen,$num){
    //2014
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

            echo $shuxiang[$xuhao];

        }

    }

}

function BallResult($status){
    if($status==0)  $status='未结算';
    else if($status==1)  $status='<span style="color:#FF0000;">赢</span>';
    else if($status==2)  $status='<span style="color:#00CC00;">输</span>';
    else if($status==8)  $status='和局';
    else if($status==3)  $status='注单无效';
    else if($status==4)  $status='<span style="color:#FF0000;">赢一半</span>';
    else if($status==5)  $status='<span style="color:#00CC00;">输一半</span>';
    else if($status==6)  $status='进球无效';
    else if($status==7)  $status='红卡取消';
    return $status;
}

function GetSportList($s=array()){
    global $mysqlt;
    $where='';
    $data_win=0;//总派彩金额
    $p=intval($s['p']);
    $page_nums=intval($s['page_nums']);
    if($p<=0)$p=1;
    if($page_nums<=0)$page_nums=10;
    $limit=($p-1)*$page_nums;
    $limit=" limit $limit,$page_nums";
    if($s['uid']) $where.=" and uid = '".$s['uid']."'";
    if(isset($s['is_jiesuan'])) $where.=" and is_jiesuan = '".$s['is_jiesuan']."'";
    if(isset($s['status'])) $where.=" and status in (".$s['status'].")";
    if($s['bet_money']) $where.=" and bet_money >= '".$s['bet_money']."'";
    if($s['bet_time_s'] && $s['bet_time_e']) $where.=" and bet_time between '".$s['bet_time_s']."' and  '".$s['bet_time_e']."' ";
    if($s['count_win']) $ss='';
    $sql_union="(select * from k_bet union
SELECT `gid`,uid,'串关','','','','','','','','','','',bet_money,'','',bet_win,win,bet_time,'','',`status`,'1',update_time,'','','',`number`,is_jiesuan,balance,'','',assets,www,match_coverdate,fs,'',site_id,username, agent_id from k_bet_cg_group
) as bet";
//echo $where;
    ////统计总派彩金额
    if($s['is_jiesuan']==1){
        $sql_win="select sum(`win`) from $sql_union where site_id='".SITEID."' $where";
        $query_win=$mysqlt->query($sql_win);
        $data_win=$query_win->fetch_assoc();
    }

    //print_r($data_win);
    $data['win']=$data_win;
    ////总条数
    $sql_nums="select count(*) as nums from $sql_union where site_id='".SITEID."' $where";
    $query_nums=$mysqlt->query($sql_nums);
    $data_nums=$query_nums->fetch_assoc();

    $data['nums']=$data_nums['nums'];
    ///

    $data['page']=ceil($data['nums']/$page_nums);
    $sql="select * from $sql_union where site_id='".SITEID."' $where order by bet_time desc $limit";
    $query=$mysqlt->query($sql);
    while($row	=	$query->fetch_array()){
        if($row['ball_sort']=='串关') {
            $row['win']=0;
            $row['bet_info']=GetSportGroup($row['bid']);
        }
        $d[]=$row;
    }
    $data['d']=$d;
    return $data;
}
function GetSportGroup($gid){
    global $mysqlt;
    $sql_cg	=	"select gid,bid,bet_info,match_name,master_guest,bet_time,MB_Inball,TG_Inball,status from k_bet_cg where gid = $gid order by bid desc";
    $query_cg	=	$mysqlt->query($sql_cg);
    $html='';
    while($rows_cg	    =	$query_cg->fetch_array()){
        $html.= '<font color="#CC0000">'.$rows_cg['match_name'].'</font><br>';
        $html.= $rows_cg['master_guest'].'<br>';
        $html.='<font color="#FF0033">'. $rows_cg['bet_info'].'</font><br>';
        if($rows_cg['MB_Inball'] !=null && $rows_cg['TG_Inball'] !=null) $html.= '['.$rows_cg['MB_Inball'].':'.$rows_cg['TG_Inball'].']';
        $html.=' ['.BallResult($rows_cg['status']).']';
        $html.='<div style="height:1px; width:99%; background:#ccc; overflow:hidden;"></div>';
    }
    return $html;
}

?>
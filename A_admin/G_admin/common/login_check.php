<?php
if(empty($_SESSION["adminid"])){
	echo "<script>alert('没有登陆!!');</script>";
	echo "<script>window.parent.location.href='../index.html'</script>";
	exit();
}else{
    include_once(dirname(__FILE__).'/../../include/'.$_SESSION['site_id'].'_private_config.php');
    include_once(dirname(__FILE__).'/../../lib/class/model.class.php');
	$sql	=	"select login_ip,login_address,ssid,onlylogin,uid from sys_admin where is_login=1 and uid=".$_SESSION["adminid"]." limit 1";
	$query	=	$mysqlt->query($sql);
	$rs		=	$query->fetch_array();

	 $sql2	=	"select id from k_user_agent where  id=".$_SESSION["adminid"]." limit 1";
	$query2	=	$mysqlt->query($sql2);
	$agent  =	$query2->fetch_array();
	if($rs['uid'] > 0 || $agent['id'] > 0){
		$onlylogin	=	$rs['onlylogin'];
		$ssid	=	$rs['ssid'];
	
		if($onlylogin==1 && $ssid!=$_SESSION["ssid"])
		{
			echo "<script  charset=\"utf-8\" language=\"javascript\" type=\"text/javascript\">alert(\"帐号在别处登陆\");</script>";
			echo "登陆IP:".$rs['login_ip']."<br>登陆地址:".$rs['login_address'];
			exit();
		}
		//会员在线
		//引入系统函数
		include_once(dirname(__FILE__)."/../../class/admin.php");
	}else{
		unset($_SESSION["adminid"]);
		unset($_SESSION["quanxian"]);
		echo "<script>alert('login!!');</script>";
		exit();
	}
}

function message($value,$url=""){ //默认返回上一页
	header("Content-type: text/html; charset=utf-8");
	
	$js  = "<script type=\"text/javascript\" language=\"javascript\">\r\n";
	$js .= "alert(\"".$value."\");\r\n";
	if($url) $js .= "window.location.href=\"$url\";\r\n";
	else $js .= "window.history.go(-1);\r\n";
	$js .= "</script>\r\n";

	echo $js;
	exit;
}

function check_quanxian($qx){
	$c_quanxian=$_SESSION["quanxian"];
	if(!strpos($c_quanxian,$qx)){
		unset($_SESSION["adminid"]);
		unset($_SESSION["quanxian"]);
		echo "<script>window.parent.location.href='/'</script>";
		exit();
	}
}

function wtype($tp){
	switch($tp){
		case "R":      ////讓球
		$arr = array("match_ho","match_ao");
		break;
				
		case "OU":      ////大小  滾球大小
		$arr = array('match_dxdpl','match_dxxpl');
		break;
		
		case "ROU":      ////滾球大小
		$arr = array('match_dxdpl','match_dxxpl');
		break;
		
		case "PD":      ////波膽
		$arr = array("match_bd10","match_bd20","match_bd21","match_bd30","match_bd31","match_bd32","match_bd40","match_bd41","match_bd42","match_bd43","match_bdg10","match_bdg20","match_bdg21","match_bdg30","match_bdg31","match_bdg32","match_bdg40","match_bdg41","match_bdg42","match_bdg43","match_bd00","match_bd11","match_bd22","match_bd33","match_bd44","match_bdup5");
		break;
		
		case "T":      ////入球
		$arr = array("match_total01pl","match_total23pl","match_total46pl","match_total7uppl");
		break;
		
		case "M":      ////獨贏
		$arr = array("match_bzm","match_bzg","match_bzh");
		break;
		
		case "F":      ////半全場
		$arr = array("match_bqmm","match_bqmh","match_bqmg","match_bqhm","match_bqhh","match_bqhg","match_bqgm","match_bqgh","match_bqgg");
		break;
		
		case "HR":      ////上半場讓球(分) 上半滾球讓球(分)
		$arr = array("match_bho","match_bao");
		break;
		
		case "HRE":      ////上半場讓球(分) 上半滾球讓球(分)
		$arr = array("match_bho","match_bao");
		break;
		
		case "HOU":      ////上半場大小   上半滾球大小
		$arr = array("match_bdpl","match_bxpl");
		break;
		
		case "HROU":      ////上半場大小   上半滾球大小
		$arr = array("match_bdpl","match_bxpl");
		break;
		
		case "HM":      ////上半場獨贏
		$arr = array("match_bmdy","match_bgdy","match_bhdy");
		break;
		
		case "HPD":      ////上半波膽
		$arr = array("match_hr_bd10","match_hr_bd20","match_hr_bd21","match_hr_bd30","match_hr_bd31","match_hr_bd32","match_hr_bd40","match_hr_bd41","match_hr_bd42","match_hr_bd43","match_hr_bdg10","match_hr_bdg20","match_hr_bdg21","match_hr_bdg30","match_hr_bdg31","match_hr_bdg32","match_hr_bdg40","match_hr_bdg41","match_hr_bdg42","match_hr_bdg43","match_hr_bd00","match_hr_bd11","match_hr_bd22","match_hr_bd33","match_hr_bd44","match_hr_bdup5");
		break;
		
		default:
		$arr = array("");
		
	}
	return $arr;
}



/**
*写文件函数
**/
function write_file($filename,$data,$method="rb+",$iflock=1){
	@touch($filename);
	$handle=@fopen($filename,$method);
	if($iflock){
		@flock($handle,LOCK_EX);
	}
	@fputs($handle,$data);
	if($method=="rb+") @ftruncate($handle,strlen($data));
	@fclose($handle);
	@chmod($filename,0777);	
	if( is_writable($filename) ){
		return true;
	}else{
		return false;
	}
}

function get_bj_time($time){  //取中国时间
	//return date("Y-m-d H:i:s",strtotime($time)+43200);
	return date("Y-m-d H:i:s",strtotime($time));
}

function double_format($num){
	return $num>0 ? sprintf("%.2f",$num) : $num<0 ? sprintf("%.2f",$num) : 0;
}

function get_DSFS($piont,$bet_money){ //判断是否返水
	/*/全场让球，大小，单双；上半让球，大小
	$arr	=	array('match_ho','match_ao','match_dxdpl','match_dxxpl','match_bho','match_bao','match_bdpl','match_bxpl','Match_dsdpl','Match_dsspl');
	if(in_array($piont,$arr)){
		return $bet_money;
	}else{
		return 0;
	}*/

	return $bet_money;
}

function get_CGFS($bet_money){ //判断是否返水
	return $bet_money;
	//return 0;
}

function getColor_u($num){
	if($num == 0) return '#009900';
	else if($num == 1) return '#FF9900';
	else if($num == 2) return '#FF99FF';
	else if($num == 3) return '#0099FF';
}

//获取当前代理商旗下的会员uid
        function get_uid($adminid){
        	global $mysqlt;
        	$i=0;
        	$sql="select uid from k_user where agent_id='$adminid'";
        	$query    = $mysqlt->query($sql);
        	while($row = $query->fetch_array()){
        		$uid .= $row['uid'].',';
        	}
        	$uid= rtrim($uid,',');
        	return $uid;
        }


 //获取当前登陆账号旗下的总代理或者代理
       function getcategory($cat_arr=array(),$pid=0,$agent){
		    $date=$agent->where('pid='.$pid)->select();
		    foreach($date as $key=>$value){
		      $pid=$value['id'];
		      $value['agent_user']=$value['agent_user'];
		      $cat_arr[]=$value;
		      $cat_arr=getcategory($cat_arr,$pid,$agent);
		    }
		    return $cat_arr;
		}



//代理商数组组合
    function agent_level($agent,$agent_type){

include_once("../../include/config.php");

 $u = M('k_user_agent',$db_config);
$allagent = $u->field("*")->select(); 
          $arr=array();
          $i =0;
          if (!empty($agent)) {
          	       foreach ($agent as $key => $val) {
		             if ($val['agent_type'] == $agent_type) {
		                $arr[] = $val;
		             }

		          
		           foreach ($allagent as $k => $v) {
		                if ($arr[$i]['pid'] == $v['id']) {
		                  $arr[$i]['up_agent'] = $v['agent_user'];
		                  $arr[$i]['up_agent_id'] = $v['id'];
		                  $arr[$i]['up_sports_scale'] = $v['sports_scale'];
		                  $arr[$i]['up_video_scale'] = $v['video_scale'];
		                  $arr[$i]['up_lottery_scale'] = $v['lottery_scale'];
		                  $i++;
		                }
		             }
		          }
          }

          return $arr;
        }
?>
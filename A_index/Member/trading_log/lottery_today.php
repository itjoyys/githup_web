<?php //ini_set("display_errors","on");
include_once("../../include/config.php");
include_once("../../common/login_check.php");
include_once("../../lib/class/model.class.php");
include("../../class/Level.class.php");
$sql = 1;
$type=$_GET['type'];
//彩票类型选择

switch ($_GET['gtype']){
	case '1':
		$sql .= " and c_bet.type='重庆时时彩'"; break;
	case '2':
		$sql .= " and c_bet.type='重庆快乐十分'"; break;
	case '3':
		$sql .= " and c_bet.type='广东快乐十分'"; break;
	case '4':
		$sql .= " and c_bet.type='北京赛车PK拾'"; break;	
	case '5':
		$sql .= " and c_bet.type='福彩3D'"; break;
	case '6':
		$sql .= " and c_bet.type='排列三'"; break;	
	case '7':
		$sql .= " and c_bet.type='北京快乐8'"; break;		
	case '8':
		$sql .= " and c_bet.type='六合彩'"; break;		
	case '9':
		$sql .= " and c_bet.type='江苏快3'"; break;
	case '10':
		$sql .= " and c_bet.type='吉林快3'"; break;
	case '11':
		$sql .= " and c_bet.type='新疆时时彩'"; break;
	case '12':
		$sql .= " and c_bet.type='天津时时彩'"; break;
	case '13':
		$sql .= " and c_bet.type='江西时时彩'"; break;
	default: 
		break;	
}

//时间判断
	if (!empty($_GET['start_date'])) {
	  $s_date = $_GET['start_date'];
	}else{
	  $s_date  = date("Y-m-d");   
	}

	if (!empty($_GET['end_date'])) {
	  $e_date = $_GET['end_date'];   
	}else{
	  $e_date = date("Y-m-d");   
	}
	//订单号查询
	if(empty($_GET['order'])){
		$sql .= " and c_bet.addtime > '".$s_date." 00:00:00' and c_bet.addtime < '".$e_date." 23:59:59'";
	
	}else{
		if(preg_match("/^[\W]*$/i",$_GET['order'])){
		    echo '<script>alert("您输入的订单号非法")</script>';
		}else{
			$sql .= " and c_bet.did = '".$_GET['order']."'";
		}
	}


$uid=$_SESSION['uid'];
$sql.=" and c_bet.uid='".$uid."'";
$join='left join k_user on c_bet.uid = k_user.uid ';

$count=M('c_bet',$db_config)->where($sql)->join($join)->count();
//分页
$perNumber=isset($_GET['page_num'])?$_GET['page_num']:10; //每页显示的记录数
$totalPage=ceil($count/$perNumber); //计算出总页数
$page=isset($_GET['page'])?$_GET['page']:1;
if($totalPage<$page){
  $page = 1;
}

$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
$limit=$startCount.",".$perNumber;
$data = array();

$data = M('c_bet',$db_config)->where($sql)->field("c_bet.*,k_user.agent_id")->join($join)->order('c_bet.addtime desc')->limit($limit)->select();

foreach($data as $k=>$v){
	if($v['js'] == 0){
		if($v['status'] == 0){
			$data[$k]['result']='未结算'; //没有结算的·结果为0
		}elseif($v['status'] == 4){
			$data[$k]['result']='注单取消';
		}
		
	}else if($v['js'] == 1){
		if($v['win']==0){
			$data[$k]['result']='未中奖';
		}else{
			$data[$k]['result']+=$v['money']*$v['odds'];
		}
	}else if($v['js'] == 3){
		$data[$k]['result']='和局';
	}
}
?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
<script>
	window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myFORM').submit()
    }
  }
</script>
<style>
a{
text-decoration:none;
}
</style>
<link rel="stylesheet" href="../public/css/index_main.css" />
	<link rel="stylesheet" href="../public/css/standard.css" />
</head>
<body  style="BACKGROUND: url(../public/images/content_bg.jpg) repeat-y left top;" >
    <div id="MAMain" style="width:767px">
        <div id="MACenterContent">
            <?php include("common.php") ?>
		    <div id="MMainData" style="overflow-y:scroll; height:330px">
		        <div class="MControlNav">
					<form name="myFORM" id="myFORM" action="<?=$_SERVER["REQUEST_URI"]?>" method="get">
						 注单号：<input class="za_text" style="width:100px" name="order" value="<?=$_GET['order']?>" onKeyUp="value=value.replace(/[^\w]/ig,'')">
			              投注时间：從 <input class="za_text Wdate" name="start_date" value="<?=$s_date?>" readonly="readonly" onclick="WdatePicker()"> 至 <input class="za_text Wdate" name="end_date" value="<?=$e_date?>" readonly="readonly" onclick="WdatePicker()">
			              <input type="submit" value=" 查 询 "/>
			    		<select name="gtype" id="gtype" onchange="document.getElementById('myFORM').submit()" class="MFormStyle" >
							<!--<option value="0" <?if($_GET['gtype']==0){echo 'selected="selected"';}?>>六合彩</option>-->
							<option value="0" >所有彩种</option>
							<option value="8" <?if($_GET['gtype']==8){echo 'selected="selected"';}?>>六合彩</option>
							<option value="5" <?if($_GET['gtype']==5){echo 'selected="selected"';}?>>福彩3D</option>
							<option value="6" <?if($_GET['gtype']==6){echo 'selected="selected"';}?>>排列三</option>
							<option value="1" <?if($_GET['gtype']==1){echo 'selected="selected"';}?>>重慶時時彩</option>
							<option value="12" <?if($_GET['gtype']==12){echo 'selected="selected"';}?>>天津时时彩</option>
							<option value="13" <?if($_GET['gtype']==13){echo 'selected="selected"';}?>>江西时时彩</option>
							<option value="11" <?if($_GET['gtype']==11){echo 'selected="selected"';}?>>新疆时时彩</option>
							<option value="7" <?if($_GET['gtype']==7){echo 'selected="selected"';}?>>北京快乐8</option>
							<option value="4" <?if($_GET['gtype']==4){echo 'selected="selected"';}?>>北京赛车PK拾</option>
							<option value="3" <?if($_GET['gtype']==3){echo 'selected="selected"';}?>>广东快乐十分</option>
							<option value="2" <?if($_GET['gtype']==2){echo 'selected="selected"';}?>>重庆快乐十分</option>
							<option value="9" <?if($_GET['gtype']==9){echo 'selected="selected"';}?>>江苏快3</option>
							<option value="10" <?if($_GET['gtype']==10){echo 'selected="selected"';}?>>吉林快3</option>
						</select>
						<select id="page" name="page" class="za_select">
							<?php  
							for($i=1;$i<=$totalPage;$i++){
								if($i==$page){
									echo  '<option value="'.$i.'" selected>'.$i.'</option>';
								}else{
									echo  '<option value="'.$i.'">'.$i.'</option>';
								}  
							} 
							?>
						</select> <?=$totalPage?> 頁&nbsp;
					</form>
		    	</div>
				<div class="MPanel" style="display: block">
					<table class="MMain" border="1">
						<tbody>
							<tr>
								<th>注单号</th>
								<th>投注日期</th>
								<th>投注类型</th>
								<th>内容</th>
								<th>投注额</th>
								<th>可赢金额</th>
								<th>派彩</th>
							</tr>
						<?if(!empty($data)){
							$money=$fs=$win=0;
							foreach($data as $k=>$v){?>
							<tr align="center">
								<td><?=$v["did"]?></td>
								<td><?php echo $v['addtime'];?> </td>
								<td><a href="javascript:voild(0);" title="<?php echo '期号：'.$v['qishu'];?>"><?php echo $v['type'];?><br/><?php echo '期号：'.$v['qishu'];?></a></td>
								<td><?=$v['mingxi_1']?>:<?=$v['mingxi_2']?><?php if($v['type']=='六合彩' || $v['type']=='北京赛车PK拾'){echo ':'.$v['mingxi_3'];}?></td>
								<td><?php echo $v['money'];?></td>
								<td><?php echo ($v['odds']-1)*$v['money'];?></td>
								<td><?php echo $v['result'];?></td>
							</tr>
							<?}?>
						<?}else{?>
							<tr align="center">
								<td colspan=7>暂时没有投注记录 </td>
							</tr>
						<?}?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
    </div>
</body>
</html>
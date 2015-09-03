<?php //ini_set("display_errors","on");
include_once("../../include/config.php");
include_once("../common/login_check.php"); 
include_once("../../include/private_config.php");
include("../../class/Level.class.php");
$sql = 1;
if($_GET['gtype']!='8'){
	header("location:fc.php?status=".$_GET['status']."&gtype=".$_GET['gtype']."&date_start=".$_GET['date_start']."&date_end=".$_GET['date_end']."&username=".$_GET['username']."&money=".$_GET['money']."&reload=".$_GET['reload']);
}else{
/*	//彩票类型选择
	switch ($_GET['gtype']){
		case '1':
			$sql .= " and c_bet.type='重庆时时彩'"; break;
		case '2':
			$sql .= " and c_bet.type='重庆快乐十分'"; break;
		case '3':
			$sql .= " and c_bet.type='广东快乐十分'"; break;
		case '4':
			$sql .= " and c_bet.type='北京pk拾'"; break;	
		case '5':
			$sql .= " and c_bet.type='福彩3D'"; break;
		case '6':
			$sql .= " and c_bet.type='排列三'"; break;	
		case '7':
			$sql .= " and c_bet.type='北京快乐8'"; break;			
		default: 
			break;	
	}
	//状态选择
	if(isset($_GET['status'])){
		switch ($_GET['status']){
			case '0':
				$sql .= ' and c_bet.js=0'; break;
			case '1':
				$sql .= ' and c_bet.js=1'; break;
			case '2':
				$sql .= ' and c_bet.js=2'; break;	
			default: 
				break;			
		}
	}*/
	//时间区间选择
	if(!empty($_GET['date_start'])){
		$sql .=" and ka_tan.adddate>='".$_GET['date_start']."'";
	}
	if(!empty($_GET['date_end'])){
		$sql .=" and ka_tan.adddate<='".$_GET['date_end']."'";
	}

	//会员账号模糊查询
	if(!empty($_GET['username'])){
		$sql .=" and ka_tan.username like '%".$_GET['username']."%'";
	}
	
	if(!empty($_GET['money'])){
		$sql .=" and ka_tan.sum_m >=".$_GET['money'];
	}

	$join='left join k_user on ka_tan.username = k_user.username ';
	$cp=M('ka_tan',$db_config)->where($sql)->field("ka_tan.*,k_user.agent_id")->join($join)->order('ka_tan.id desc')->select();

	
	//totalFs  可赢金额
	
	
	//查询总和
	$total=M('ka_tan',$db_config)->where($sql)->field("sum(ka_tan.sum_m) as totalMoney")->find();



	//查询所有代理，总代，股东信息并整合为一个数组
	$allagent=M('k_user_agent',$db_config)->where("is_delete=0 and site_id='".SITEID."'")->select();
	if(is_array($cp)){
		foreach($cp as $k=>$v){
					$arr=Level::getParents($allagent,$v['agent_id']);
					$data[$k]=array_merge($v,$arr);
		}
	}
	
	$total_fs=0;
	$total_win=0;
	if(is_array($data)){
		foreach($data as $v){
			$total_fs+=($v['rate']-1)*$v['sum_m'];
			if($v['js']!=0){
				if($v['win']==0){
					$total_win-=$v['sum_m'];
				}else{
					$total_win+=($v['rate']-1)*$v['sum_m'];
				}
			}
		}
	}
	//分页
	$sum=count($data);
	$pagenum=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
	$CurrentPage=isset($_GET['page'])?$_GET['page']:1;

	 $totalPage=ceil($sum/$pagenum); //计算出总页数
	$startCount=($CurrentPage-1)*$pagenum;
	if(is_array($cp)){
		$data=array_slice($data,$startCount,$pagenum);
	}
}

?>
<?php require("../common_html/header.php");?>
<body>

<script language="JavaScript" type="text/JavaScript">
$(document).ready(function(){
	$('#gtype').val('<?=$_GET['gtype']?>');
	$('#status').val('<?=$_GET['status']?>');
	if('<?=$_GET['shijian']?>'>0){
		$('#reload').val('<?=$_GET['shijian']?>');
		timeout('<?=$_GET['shijian']?>');
	}
})
</script>
<script>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myFORM').submit()
    }
  }
</script>
<div id="con_wrap">

<form name="myFORM" id="myFORM" action="<?=$_SERVER["REQUEST_URI"]?>" method="get">
<div class="input_002">彩票詳細注單</div>
<div class="con_menu">
	類型：
	<select id="gtype" name="gtype" onchange="document.getElementById('myFORM').submit()" class="za_select">
 	<option value="1">重慶時時彩</option>
	<option value="2">重庆快乐十分</option>
	<option value="3">广东快乐十分</option>
	<option value="4">北京pk拾</option>
	<option value="5">福彩3D</option>
	<option value="6">排列三</option>
	<option value="7">北京快乐8</option>
	<option value="8">六合彩</option>
	</select>
	狀態：
	<select id="status" name="status" onchange="document.getElementById('myFORM').submit()" class="za_select">
		<option value="-1">全部</option>
		<option value="0">未結算</option>
		<option value="1">已結算</option>
		<option value="2">已取消</option>
	</select>
    &nbsp;&nbsp;日期：
	<input type="text" name="date_start" id="date_start" value="<?if($_GET['date_start']){echo $_GET['date_start'];}?>"  size="10" maxlength="11" class="za_text Wdate" onClick="WdatePicker()">
	--
	<input type="text" name="date_end" id="date_end" value="<?if($_GET['date_end']){echo $_GET['date_end'];}?>"  size="10" maxlength="10" class="za_text Wdate" onClick="WdatePicker()">
    会员帐号：
    <input name="username" type="text" id="username" class="za_text" style="width:80px;min-width:80px" size="15" value="<?if($_GET['username']){echo $_GET['username'];}?>">
	大於此金額：<input type="TEXT" name="money" id="money" value="<?if($_GET['money']){echo $_GET['money'];}?>" size="10" maxlength="10" class="za_text" style="width:50px;min-width:50px">
     <input type="submit"  name="subbtn" value="查詢" class="button_d">
    重新整理：
	<select name="reload" id="reload" onchange="timeout(this.value);">
		<option value="">不自動更新</option>
		<option value="5" <?if($_GET['reload']==5){ echo 'selected="select"';}?>>5秒</option>
		<option value="10" <?if($_GET['reload']==10){ echo 'selected="select"';}?>>10秒</option>
		<option value="15" <?if($_GET['reload']==15){ echo 'selected="select"';}?>>15秒</option>
		<option value="30" <?if($_GET['reload']==30){ echo 'selected="select"';}?>>30秒</option>
		<option value="60" <?if($_GET['reload']==60){ echo 'selected="select"';}?>>60秒</option>
		<option value="120" <?if($_GET['reload']==120){ echo 'selected="select"';}?>>120秒</option>
	</select>
	  每页记录数：
  <select name="page_num" id="page_num" onchange="document.getElementById('myFORM').submit()" class="za_select">
    <option value="20" <?=select_check(20,$pagenum)?>>20条</option>
    <option value="30" <?=select_check(30,$pagenum)?>>30条</option>
    <option value="50" <?=select_check(50,$pagenum)?>>50条</option>
    <option value="100" <?=select_check(100,$pagenum)?>>100条</option>
  </select>
  &nbsp;頁數：
 <select id="page" name="page" class="za_select"> 
  <?php  

    for($i=1;$i<=$totalPage;$i++){
      if($i==$CurrentPage){
        echo  '<option value="'.$i.'" selected>'.$i.'</option>';
      }else{
        echo  '<option value="'.$i.'">'.$i.'</option>';
      }  
    } 
   ?>
  </select> <?php echo  $totalPage ;?> 頁&nbsp;<br>
	<span id="lblTime" style="color:red"></span>  
</div>
</form></div>
<div class="content" id="show">	<table class="m_tab" cellspacing="0" cellpadding="0" border="0">
		<tbody><tr class="m_title_over_co">
			<td align="center">共有 <?php echo $sum;?> 條記錄&nbsp;&nbsp;</td>
		</tr>
	</tbody></table>
	<table width="1090" border="0" cellspacing="0" cellpadding="0" class="m_tab" bgcolor="#000000">
		<tbody><tr class="m_title_over_co">
			<td width="30">序號</td>
			<td width="70">下單時間</td>
			<td width="120">所屬上線</td>
            <td width="70">期数</td>
            <td width="70">下注帐号</td>
			<td width="130">類型</td>
			<td width="330">內容</td>
			<td width="90">下注金額</td>
            <td width="60">可赢金额</td>
            <td width="90">結果</td>
		</tr>
	<?php 
	if(is_array($data)){
		$money=$fs=$win=0;
		foreach($data as $k=>$v){?>
			<tr class="m_cen">
				<td width="30"><?php echo $v['id'];?></td>
				<td width="70"><?php echo $v['adddate'];?></td>
				<td width="120"><span style="float:left;text-align:right;width:50%">公司：</span><span style="float:left;text-align:left;width:50%"><?php echo COMPANY_NAME.'<br/>';?></span>
				<span style="float:left;text-align:right;width:50%">股东：</span><span style="float:left;text-align:left;width:50%"><?php echo $v['s_h'].'<br/>';?></span>
				<span style="float:left;text-align:right;width:50%">总代理：</span><span style="float:left;text-align:left;width:50%"><?php echo $v['u_a'].'<br/>';?></span>
				<span style="float:left;text-align:right;width:50%">代理：</span><span style="float:left;text-align:left;width:50%"><?php echo $v['a_t'];?></span>
				</td>
				<td width="70"><?php echo $v['kithe'];?></td>
				<td width="70"><?php echo $v['username'];?></td>
				<td width="130"><?=$v['class1']?><br></td>
				<td width="330"><?=$v['class2']?>：<a title="<?=$v['class3']?>" >
        <b><font color="#ff0000"><?=$v['class3']?></font></b>@<b><font color="#ff0000"><?=$v['rate']?></font></b></a> <a title="<?=$v['mingxi_1']?>">开奖结果</a></td>
				<td width="90"><?php echo $v['sum_m'];?></td>				
				<td width="60"><?php echo ($v['rate']-1)*$v['sum_m'];?></td>
				<td width="90"><?php if($v['js']==0){?>0<?php }else{ if($v['win']==0){ echo '-'.$v['money']; }else{ echo ($v['rate']-1)*$v['sum_m'];} }?></td>			
			</tr>
		<?php $money +=$v['sum_m'];
			$fs +=($v['rate']-1)*$v['sum_m'];
			if($v['js']!=0){
				if($v['win']==0){
					$win-=$v['sum_m'];
				}else{
					$win+=($v['rate']-1)*$v['sum_m'];
				}
			}
		}
	}?>
			<tr class="m_cen" style="background-Color:#EBF0F1">
				<td colspan="6" style="text-align:right">&nbsp;小計：</td>
				<td align="center"><?php if($data){echo $pagenum;}else{echo 0;}?>笔</td>
				<td align="center"><?php if($data){echo $money;}?></td>
				<td align="center"><?php if($data){echo $fs;}?></td>
				<td align="center"><?php if($data){echo $win;}?></td>
			</tr>
			<tr class="m_cen" style="background-Color:#EBF0F1">
				<td colspan="6" style="text-align:right">&nbsp;總計：</td>
				<td align="center"><?php if($data){echo $sum;}else{echo 0;}?>笔</td>
				<td align="center"><?php if($data){echo $total['totalMoney'];}?></td>
				<td align="center"><?php if($data){echo $total_fs;}?></td>
				<td align="center">0</td>
			</tr>
			<tr  class="m_cen">
				<td  colspan="16"  height="30"><?php echo $pageStr;?></td>
			</tr>
	</tbody></table>
</div>



</body></html>

<script>
var i='<?=$_GET["reload"]?>';
if(i==''){
	var i=0;
}
$(document).ready(function(){

	
	if(i!=0){
		setInterval("timeout(i)",1000);
	}
	
});
function timeout(time){
	i = time;
	var reload=i;
	setInterval("refresh()",1000);
}

	function refresh(){
		if(i <=0){
			var reload=$("#reload").val();
			var gtype=$("#gtype").val();
			var status=$("#status").val();
			var date_start=$("#date_start").val();
			var date_end=$("#date_end").val();
			var username=$("#username").val();
			var money=$("#money").val();
			window.location.href='fc.php?reload='+reload+'&gtype='+gtype+'&username='+username+'&money='+money+'&status='+status+'&date_start='+date_start+'&date_end='+date_end;//调转
		}else{
			$('#lblTime').html('还有'+i+'秒更新');
			i--;
		}
	}

</script>
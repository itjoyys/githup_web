<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once("../../include/private_config.php");
include_once("../../lib/class/model.class.php");

$type=$_GET['type'];

$type=='' ? $se1 = '#FF0' : $se1 = '#FFF';
$type=='六合彩' ? $se2 = '#FF0' : $se2 = '#FFF';
$type=='广东快乐十分' ? $se3 = '#FF0' : $se3 = '#FFF';
$type=='重庆时时彩' ? $se4 = '#FF0' : $se4 = '#FFF';
$type=='北京PK10' ? $se5 = '#FF0' : $se5 = '#FFF';
$type=='重庆幸运农场' ? $se6 = '#FF0' : $se6 = '#FFF';
$type=='北京快乐8' ? $se7 = '#FF0' : $se7 = '#FFF';



$zf=$_GET['zf'];
if($zf==1)
{
	if($_GET['id'] > 0){
	$id	=	$_GET['id'];

	$sql		=	"update k_user,c_bet set k_user.money=k_user.money+c_bet.money,c_bet.js=3,c_bet.win=0,c_bet.fs=0 where k_user.uid=c_bet.uid and c_bet.id='".$id."'";
	$mysqlt->query($sql);
	include_once("../../class/admin.php");
	admin::insert_log($_SESSION["adminid"]," 作废了彩票id=$id");
	}
	
}


//期数
switch ($_GET['type']) {
	case '福彩3D':
		$c = M('c_auto_5',$db_config);
		break;
	case '排列三':
		$c = M('c_auto_6',$db_config);
		break;
	case '重庆时时彩':
		$c = M('c_auto_2',$db_config);
		break;
	case '重庆快乐十分':
		$c = M('c_auto_4',$db_config);
		break;
	case '广东快乐十分':
		$c = M('c_auto_1',$db_config);
		break;
	case '北京PK10':
		$c = M('c_auto_3',$db_config);
		break;
	case '北京快乐8':
		$c = M('c_auto_8',$db_config);
		break;
}
$qishu = $c->field('qishu')->limit(7)->order('qishu DESC')->select();

?>
<?php require("../common_html/header.php");?>
<script type="text/javascript" charset="utf-8" src="/js/jquery.js" ></script>
<script language="javascript">
function go(value){
	if(value != "") location.href=value;
	else return false;
}

function check(){
	if($("#tf_id").val().length > 5){
		$("#status").val("0,1");
	}
	return true;
}

</script>
<script language="JavaScript" src="/js/calendar.js"></script>
<body>
<div id="con_wrap">
<div class="input_002">彩票即时注单</div>
<div class="con_menu">
<select class="za_select" name="temppid" id="temppid" onchange="var jmpURL=this.options[this.selectedIndex].value;if(jmpURL!=''){window.location=jmpURL;}else{this.selectedIndex=0;}">
		<option <?php if($_GET['type'] == '福彩3D'){echo "selected";}?> value="<?=$_SERVER["REQUEST_URI"]?>&type=福彩3D">福彩3D</option>
		}
		<option <?php if($_GET['type'] == '排列三'){echo "selected";}?> value="<?=$_SERVER["REQUEST_URI"]?>&type=排列三">排列三</option>
        <option <?php if($_GET['type'] == '重庆时时彩'){echo "selected";}?> value="<?=$_SERVER["REQUEST_URI"]?>&type=重庆时时彩">重庆时时彩</option>
        <option <?php if($_GET['type'] == '重庆快乐十分'){echo "selected";}?> value="<?=$_SERVER["REQUEST_URI"]?>&type=重庆快乐十分">重庆快乐十分</option>
        <option <?php if($_GET['type'] == '广东快乐十分'){echo "selected";}?> value="<?=$_SERVER["REQUEST_URI"]?>&type=广东快乐十分">广东快乐十分</option>
        <option <?php if($_GET['type'] == '北京PK10'){echo "selected";}?> value="">北京PK10</option>
        <option <?php if($_GET['type'] == '北京快乐8'){echo "selected";}?> value="<?=$_SERVER["REQUEST_URI"]?>&type=北京快乐8">北京快乐8</option>
        <option <?php if($_GET['type'] == '重庆幸运农场'){echo "selected";}?> value="<?=$_SERVER["REQUEST_URI"]?>&type=重庆幸运农场">重庆幸运农场</option>
    </select>
    	<select class="za_select" name="qs" id="qs" onchange="GetData()">
    	 <?php foreach ($qishu as $key => $val): ?>
    	 	<option value="<?=$val['qishu']?>"><?=$val['qishu']?>期</option>
    	 <?php endforeach ?>
    		
    	</select>
    	  会员：<input name="username" type="text" id="username" value="<?=$_GET['username']?>" size="15">
            &nbsp;&nbsp;日期：
            <input name="s_time" type="text" id="s_time" value="<?=$_GET['s_time']?>" onClick="new Calendar(2008,2020).show(this);" size="10" maxlength="10" readonly="readonly" />
            ~
            <input name="e_time" type="text" id="e_time" value="<?=$_GET['e_time']?>" onClick="new Calendar(2008,2020).show(this);" size="10" maxlength="10" readonly="readonly" />&nbsp;&nbsp;注单号：
            <input name="tf_id" type="text" id="tf_id" value="<?=@$_GET['tf_id']?>" size="22">
            &nbsp;&nbsp;
            <input type="submit" name="Submit" value="搜索">

</div>
<!--
<div class="con_menu">
</div>
-->
</div>
 <div class="content">

       
 </div>
<div id="pageMain">
  <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td valign="top">
        <table width="100%" border="0" cellpadding="5" cellspacing="1" class="m_tab" style="margin-top:5px;" bgcolor="#798EB9">   
            <tr class="m_title">
              <td align="center"><strong>订单号</strong></td>
              <td align="center"><strong>彩票类别</strong></td>
              <td align="center"><strong>彩票期号</strong></td>
              <td align="center"><strong>投注玩法</strong></td>
              <td align="center"><strong>投注内容</strong></td>
              <td align="center"><strong>投注金额</strong></td>
			  <td align="center"><strong>反水</strong></td>
              <td align="center"><strong>赔率</strong></td>
        <td height="25" align="center"><strong>输赢结果</strong></td>
        <td align="center"><strong>投注时间</strong></td>
        <td align="center"><strong>投注账号</strong></td>
        <td height="25" align="center"><strong>状态</strong></td>
        </tr>
<?php

      include("../../include/pager.class.php");
	  
	  $t_allmoney=0;
	  $t_sy=0;
	  $uid	=	'';
	  if($_GET['username']){
	      $sql		=	"select uid from k_user where username='".$_GET['username']."' limit 1";
		  $query	=	$mysqlt->query($sql);
		  if($rows	=	$query->fetch_array()){
		  		$uid=	$rows['uid'];
		  }
	  }
 
      $sql	=	"select id from c_bet where money>0 ";
	  if($type!="") $sql.=" and type='".$type."'";
	  if($_GET["uid"]) $uid = $_GET["uid"];
	  if($uid != '') $sql.=" and uid=".$uid;
	  if($_GET["s_time"]) $sql.=" and addtime>='".$_GET["s_time"]." 00:00:00'";
	  if($_GET["e_time"]) $sql.=" and addtime<='".$_GET["e_time"]." 23:59:59'";
	  if(isset($_GET["js"]))  $sql.=" and `js` in (".$_GET["js"].")";
	  if($_GET['tf_id']) $sql.=" and id=".$_GET['tf_id']."";
	  $order = 'id';
	  if($_GET['order']) $order = $_GET['order'];
	  $sql.=" order by $order desc ";
	  
	  $query	=	$mysqlt->query($sql);
	  $sum		=	$mysqlt->affected_rows; //总页数
	  $thisPage	=	1;
	  $pagenum	=	50;
	  if($_GET['page']){
	  	  $thisPage	=	$_GET['page'];
	  }
      $CurrentPage=isset($_GET['page'])?$_GET['page']:1;
	  $myPage=new pager($sum,intval($CurrentPage),$pagenum);
	  $pageStr= $myPage->GetPagerContent();
	  
	  $bid		=	'';
	  $i		=	1; //记录 bid 数
	  $start	=	($thisPage-1)*$pagenum+1;
	  $end		=	$thisPage*$pagenum;
	  while($row = $query->fetch_array()){
	  	  if($i >= $start && $i <= $end){
	  	  	$bid .=	$row['id'].',';
		  }
		  if($i > $end) break;
		  $i++;
	  }
	  if($bid){
	  	$bid	=	rtrim($bid,',');
	  	$sql	=	"select * from c_bet where id in($bid) order by $order desc";
	  	$query	=	$mysqlt->query($sql);
      	while ($rows = $query->fetch_array()) {	  
		  $color = "#FFFFFF";
		  $over	 = "#EBEBEB";
		  $out	 = "#ffffff";
		  $t_allmoney+=$rows['money'];
		  if($rows['js']==1)
		{
				$t_sy+=$rows['win'];
		  }

		  if($rows['win']>0 && $rows['js']==1){
			$color = "#FFE1E1";
			$out   = "#FFE1E1";
		  }
      	?>
      <tr align="center" onMouseOver="this.style.backgroundColor='<?=$over?>'" onMouseOut="this.style.backgroundColor='<?=$out?>'" style="background-color:<?=$color?>; line-height:20px;">
        <td height="25" align="center" valign="middle"><?=$rows['id']?></td>
        <td align="center" valign="middle"><?=$rows['type']?></td>
        <td align="center" valign="middle"><?=$rows['qishu']?></td>
        <td align="center" valign="middle"><?=$rows['mingxi_1']?></td>
        <td align="center" valign="middle"><?=$rows['mingxi_2']?></td>
        <td align="center" valign="middle"><?=$rows['money']?></td>
		<td align="center" valign="middle"><?=$rows['fs']?></td>
        <td align="center" valign="middle"><?=$rows['odds']?></td>
        <td align="center" valign="middle"><?php if($rows['js']==0){?>0<?php }else{?><?=$rows['win']?><?php }?></td>
        <td><?=$rows['addtime']?></td>
        <td><span style="color:#999999;"><?=$rows["assets"]?></span><br /><a href="Order.php?username=<?=$rows["username"]?>"><?=$rows["username"]?></a><br /><span style="color:#999999;"><?=$rows["balance"]?></span></td>
        <td><?php if($rows['js']==0){?><font color="#0000FF">未结算</font>--<a href="?js=0&zf=1&id=<?=$rows['id']?>" title="作废该单"><font color="#ffcccc">作废</font></a><?php }?>
        <?php if($rows['js']==1){?><font color="#FF0000">已结算</font><?php }?>
		<?php if($rows['js']==3){?><font color="#FFcccc">作废</font><?php }?></td>
        </tr>
		<tr>
		</tr>
<?php
	}
}
?>
	<tr style="background-color:#FFFFFF;">
        <td colspan="12" align="center" valign="middle">当前页总投注金额:<?=$t_allmoney?>元   输赢:<?=$t_sy?></td>
        </tr>
	<tr style="background-color:#FFFFFF;">
        <td colspan="12" align="center" valign="middle"><?php echo $pageStr;?></td>
        </tr>

    </table></td>
    </tr>
  </table>
</div>
</body>
</html>
<?
include_once("../../include/config.php");
include_once("../common/login_check.php"); 
include_once("../../class/user.php");
include("../../class/Level.class.php");

// var_dump($_GET);

$user;

$allagent=M('k_user_agent',$db_config)->where("is_delete=0 and site_id='".SITEID."'")->select();
$ids;//所有代理ID
$user_id;//最终总ID
if(!empty($_GET['gid'])){//总代股东入口
	$ids=implode(",",Level::getChildsId($allagent,$_GET['gid'],'a_t'));
}else if(!empty($_GET['did'])){//代理入口
	$ids = $_GET['did'];
}

$user_id;
$map;
if(!empty($_GET['uid'])){	
	
	$user = M('k_user',$db_config)->field("username")->where("uid in (".$_GET['uid'].")")->find();
	$user_id=$_GET['uid'];
	$map = " k_bet.uid in (".$user_id.") and k_bet.site_id='".SITEID."'";
}else if(!empty($_GET['gid']) or !empty($_GET['did'])){
	
	if(!empty($_GET['gid'])){
		$user = M('k_user_agent',$db_config)->field("k_user_agent.agent_user")->where("k_user_agent.id ='".$_GET['gid']."'")->find();
	}else{
		$user = M('k_user_agent',$db_config)->field("k_user_agent.agent_user")->where("k_user_agent.id ='".$_GET['did']."'")->find();
	}
	if (!empty($ids)) {
	   $id_arr=M('k_user',$db_config)->field("uid")->where("agent_id in ($ids)")->select();
	   	if(!empty($id_arr)){
				foreach ($id_arr as $k => $v) {
					$user_id.=",".$v['uid'];
				}
			}
	}
	$user_id  = trim($user_id,',');
	if(!empty($user_id)){
		$map = "k_bet.uid in (".$user_id.") and k_bet.site_id='".SITEID."'";
	}else{
		$map = "k_bet.uid = '".$user_id."' and k_bet.site_id='".SITEID."'";
	}
	
}


$sql_union="(select * from k_bet union
SELECT `gid`,uid,'串关',      '','','','','','','','','','',bet_money,'','',bet_win,win,bet_time,'','',`status`,'1',update_time,'','','',`number`,is_jiesuan,balance,'','',assets,www,match_coverdate,fs,'',site_id,username,agent_id from k_bet_cg_group
) as k_bet";

$c = M($sql_union,$db_config);

if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
   $map = $map." and k_bet.bet_time >='".$_GET['start_date']." 00:00:00' and k_bet.bet_time <= '".$_GET['end_date']." 23:59:59'";
}elseif (!empty($_GET['start_date'])) {
   $map = $map." and k_bet.bet_time >='".$_GET['start_date']."' 00:00:00";
}elseif (!empty($_GET['end_date'])) {
   $map = $map." and k_bet.bet_time <='".$_GET['end_date']."' 23:59:59";
}else{
   $map = $map." and k_bet.bet_time >='".date('Y-m-d')." 00:00:00' and k_bet.bet_time <= '".date('Y-m-d')." 23:59:59'";
}

$where = "";
$where = $map." and status != 0";

$count=$c->field("sum(bet_money) as money,sum(fs) as fs")->where($map)->select();
$count1=$c->field("sum(win) as win")->where($where)->select();

$money_all=!empty($count[0]['money'])?$count[0]['money']:0;
$water_all=!empty($count[0]['fs'])?$count[0]['fs']:0;
$result_all=!empty($count1[0]['win'])?$count1[0]['win']:0;

$sum    = $c->where($map)->count();


//分页
$CurrentPagenum=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
$totalPage=ceil($sum/$CurrentPagenum); //计算出总页数
if(!empty($_GET['page'])){
	$CurrentPage=$_GET['page'];
}else{
	$CurrentPage=1;
}
$startCount=($CurrentPage-1)*$CurrentPagenum; //分页开始,根据此方法计算出开始的记录


$bid    = '';
$start  = ($CurrentPage-1)*$CurrentPagenum+1;
$end  = $CurrentPage*$CurrentPagenum;

$row    = $c->field('bid')->where($map)->select();
// p($row );
if(is_array($row)){
	foreach($row as $i=>$v){
	if($i >= ($start-1) && $i <= ($end-1)){
	  $bid .= $v['bid'].',';
	}
	if($i > $end) break;
	  $i++;
	}
}

if($bid!=''){

$bid  = rtrim($bid,',');
$map.=" and k_bet.bid in($bid)";

$where = "";
$where = $map." and status != 0";

$map.=" order by k_bet.bet_time desc";

$record = $c->where($map)->select();



$count=$c->field("sum(bet_money) as money,sum(fs) as fs")->where($map)->select();
$count2=$c->field("sum(win) as win")->where($where)->select();

}
$money_x=!empty($count[0]['money'])?$count[0]['money']:0;
$water_x=!empty($count[0]['fs'])?$count[0]['fs']:0;
$result_x=!empty($count2[0]['win'])?$count2[0]['win']:0;

?>
<?php require("../common_html/header.php");?>

<style>
	.m_rig td{text-align: center;}
</style>
<script>
	window.onload=function(){
		document.getElementById("ttype").onchange=function(){
		
			window.location.href=this.value;
		}
		document.getElementById("page").onchange=function(){
   
      	document.getElementById('myFORM').submit();
      }
	}
</script>
<body>

<div id="con_wrap">
<div class="input_002"><?=$user['username']?><?=$user['agent_user']?> - 体育下注記錄</div>
<div class="con_menu">
<form id="myFORM" action="bet_record.php?uid=<?=$_GET['uid'] ?>&gid=<?=$_GET['gid'] ?>&did=<?=$_GET['did'] ?>" method="get" name="FrmData">
	<input type="hidden" name="uid" value="<?=$_GET['uid'] ?>">
	&nbsp;&nbsp;下注類型：
	<?php include("./common_record.php") ?>
	
	&nbsp;&nbsp;日期：
	<input type="text" name="start_date" value="<?if($_GET['start_date']){echo $_GET['start_date'];}else
	{echo date("Y-m-d",time());}?>" id="start_date"  size="10" maxlength="11" class="za_text Wdate" onClick="WdatePicker()">
	--
	<input type="text" name="end_date" value="<?if($_GET['end']){echo $_GET['end_date'];}else
	{echo date("Y-m-d",time());}?>" id="end_date" size="10" maxlength="10" class="za_text Wdate" onClick="WdatePicker()">
	    每页记录数：
  <select name="page_num" id="page_num" onchange="document.getElementById('myFORM').submit()" class="za_select">
    <option value="20" <?=select_check(20,$CurrentPagenum)?>>20条</option>
    <option value="30" <?=select_check(30,$CurrentPagenum)?>>30条</option>
    <option value="50" <?=select_check(50,$CurrentPagenum)?>>50条</option>
    <option value="100" <?=select_check(100,$CurrentPagenum)?>>100条</option>
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
  </select> <?php echo  $totalPage ;?> 頁&nbsp;
	<input type="SUBMIT" value="確定" class="za_button">
	重新整理：
		<select name="reload" id="reload" onchange="SetTimeCashList(this.value);">
			<option value="-1">不自動更新</option>
			<option value="5" <?if($_GET['reload']=="5"){ echo 'selected="selected"';}?>>5秒</option>
			<option value="10" <?if($_GET['reload']=="10"){ echo 'selected="selected"';}?>>10秒</option>
			<option value="15" <?if($_GET['reload']=="15"){ echo 'selected="selected"';}?>>15秒</option>
			<option value="30" <?if($_GET['reload']=="30"){ echo 'selected="selected"';}?>>30秒</option>
			<option value="60" <?if($_GET['reload']=="60"){ echo 'selected="selected"';}?>>60秒</option>
			<option value="120" <?if($_GET['reload']=="120"){ echo 'selected="selected"';}?>>120秒</option>
		</select>
		<span id="lblTime" style="color:red"></span>
		<input type="hidden" name="gid" value="<?=$_GET['gid']  ?>">
		<input type="hidden" name="did" value="<?=$_GET['did']  ?>">
</form></div>
</div>
<div class="content">

	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="m_tab" bgcolor="#3B2D1B">
			<tbody>

			<tr class="m_title">
				<td width="5%">序号</td>
				<td width="10%">用户名</td>
				<td width="20%">時間</td>
				<td width="10%">球賽種類</td>
				<td width="30%">內容</td>
				<td width="10%">金額</td>
				<td width="10%">退水</td>
				<td width="10%">結果</td>
			</tr>
			<?php
			  if(!is_array($record)) {  
			?>
			<tr class="m_rig" style="display:;">
				<td height="70" align="center" colspan="8"><font color="#3B2D1B">暫無數據。</font></td>
			</tr>
			<?}else{?>
			<?php
			   foreach ($record as $key => $val) {  


			   	$status='';

	            if($val["status"]==0)  $status='未结算';
	            else if($val["status"]==1)  {
	                $status='<span style="color:#FF0000;">赢</span>';
	            }
	            else if($val["status"]==2)  $status='<span style="color:#00CC00;">输</span>';
	            else if($val["status"]==8)  $status='和局';
	            else if($val["status"]==3)  $status='注单无效';
	            else if($val["status"]==4)  $status='<span style="color:#FF0000;">赢一半</span>';
	            else if($val["status"]==5)  $status='<span style="color:#00CC00;">输一半</span>';
	            else if($val["status"]==6)  $status='进球无效';
	            else if($val["status"]==7)  $status='红卡取消';
			   	 //p($val);
			?>
				<tr class="m_rig" align="left">
					<td align="center"><?=$val['bid']?></td>
					<td align="center"><?=$val['username']?></td>
					<td align="center"><?=$val['bet_time']?></td>
					<td align="center"><font color="<? if ($val["ball_sort"]=="足球滚球"){echo "#0066FF";}else{echo "#336600";}?>">
                                    <b><?=$val["ball_sort"]?></b>
                                </font><br/>
                                <font color="#0000cc"><?=$val["match_id"]?></font><br>
                                <?=$val["number"]?><Br>
                                <?=$val['match_coverdate']?>
                    </td>
					<td align="center">

					 <? if($val['ball_sort']=='串关'){
	                    $gid	=	$val['bid'];
	                    echo $gid;
	                    $sql_cg	=	"select gid,bid,bet_info,match_name,master_guest,bet_time,MB_Inball,TG_Inball,status from k_bet_cg where gid in ($gid) order by bid desc";
	                    $query_cg	=	$mysqlt->query($sql_cg);
	                $html='';
	                while($rows_cg	    =	$query_cg->fetch_array()){
	                    $html.= '<font color="#CC0000">'.$rows_cg['match_name'].'</font><br>';
	                    $html.= $rows_cg['master_guest'].'<br>';
	                    $html.='<font color="#FF0033">'. $rows_cg['bet_info'].'</font><br>';
	                    if($rows_cg['MB_Inball'] !=null && $rows_cg['TG_Inball'] !=null) $html.= '['.$rows_cg['MB_Inball'].':'.$rows_cg['TG_Inball'].']<br>';
	                    $html.='<div style="height:1px; width:99%; background:#ccc; overflow:hidden;"></div>';
	                }
	                echo $html;
	            }else{
	            ?>

	            <font color="#CC0000"><?=$val["match_name"]?></font><br><?=$val['master_guest']?>
                                <font style="color:#FF0033">
                                   <?
                                     if($val["point_column"]==="match_jr" || $val["point_column"]==="match_gj") echo $rows["bet_info"];
                                    else echo str_replace("-","<br>",$val["bet_info"]);
                                  // if($rows['ball_sort']=='足球滚球') echo '['.$rows['match_nowscore'].']';
                                    ?>
                                </font>
                                <? if($val["status"]!=0 && $val["status"]!=3 &&  $val["status"]!=6 && $val["status"]!=7)
                                    if($val["MB_Inball"]!=''){?>
                                        [<?=$val["MB_Inball"]?>:<?=$val["TG_Inball"]?>]
                                    <? } ?>	<br/><?=$val["login_ip"]?>
                             <? } ?>
	            </td>
					<td><?=$val['bet_money']?></td>
					<td><?=$val['fs']?></td>
					<td><?=$status?><br><?=$val['status']==0?"0.00":$val['win']?></td>
				</tr>
				<?}?>	
			<?}?>
			
			<tr class="m_rig" style="background-Color:#EBF0F1;">
				<td colspan="5" align="right">&nbsp;小計：</td>
				<td><?=$money_x?></td>
				<td><?=$water_x?></td>
				<td><?=$result_x?></td>
			</tr>
			<tr class="m_rig" style="background-Color:#EBF0F1;">
				<td colspan="5" align="right">&nbsp;总計：</td>
				<td><?=$money_all?></td>
				<td><?=$water_all?></td>
				<td><?=$result_all?></td>
			</tr>
		
	</tbody></table>
</div>

<script language="javascript">
var vtimeCashList = 0;
var timeGoCashList = null;
function SetTimeCashList(otime)
{
    vtimeCashList=otime;
    if(vtimeCashList>0)
    {
        window.clearTimeout(timeGoCashList);
        document.getElementById("lblTime").innerHTML='還有'+vtimeCashList+'秒更新';  
        if(otime!=0)
        {
            timeGoCashList=setInterval("timeCashList("+otime+")",1000);
        }
    }
    else 
    {
        document.getElementById("lblTime").innerHTML="";
        window.clearTimeout(timeGoCashList);
    }
}
function timeCashList(otime)
{
    if(vtimeCashList<=0)
    {
        document.getElementById("lblTime").innerHTML=""; 
        window.clearTimeout(timeGoCashList); 
    }
    else 
    {
        vtimeCashList=vtimeCashList-1;
        if(vtimeCashList<=0)
        {        	   	
        	getdata();            
            vtimeCashList=otime;
        }
        document.getElementById("lblTime").innerHTML='還有'+vtimeCashList+'秒更新';
        
    }
}
function getdata(page){
	form_obj = document.getElementById("myFORM");
	// form_obj.action = "bet_record.php";
	form_obj.submit();
}
var reload = $("#reload").val();
$(document).ready(function(){
	if(reload>0){
		SetTimeCashList(reload);
	}
	$("#reload").val(reload);
	// $("#page_num").val('20');
	// $("#page").val('0');	
});
</script>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>
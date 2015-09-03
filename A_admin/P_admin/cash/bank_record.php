<?php 
include_once("../../include/config.php");
include_once("../common/login_check.php");
/**
*
*    into_style 1表示公司入款 
*
**/

$b = M('k_user_bank_in_record',$db_config);
$lev=M('k_user_level',$db_config);

$map['site_id'] = SITEID;
$l=$lev->field("level_des,id")->where($map)->select();

	$map['into_style'] = 1;
	$intoStyle=1;
	$inTitle = '公司入款';
	$intoType = 11;
	$gUrl = 'bank_record.php';


$where ="k_user_bank_in_record.into_style ='1' and k_user_bank_in_record.site_id = '".SITEID."'";
//拼接搜索条件
if (isset($_GET['status'])) {
	if ($_GET['status'] == 'dis') {
		$map['favourable_num'] = 0;
		$map['other_num'] = 0;
		$where .=" and (k_user_bank_in_record.favourable_num > '0' or k_user_bank_in_record.other_num > '0')";
	}elseif ($_GET['status'] == 'ndis') {
		$map['favourable_num'] = array(">",0);
		$map['other_num'] = array(">",0);
		$map['_logic'] = 'or';
		$where .=" and (k_user_bank_in_record.favourable_num = '0' and k_user_bank_in_record.other_num = '0')";
	}elseif ($_GET['status'] >= 0) {
	    $where .=" and k_user_bank_in_record.make_sure = '".$_GET['status']."'";
	    $map['make_sure'] = $_GET['status'];
	}
}else{
	$_GET['status'] = -1;
}

if(!empty($_GET['level_id'])){
	$map['level_id'] = $_GET['level_id'];
	$where .=" and k_user_bank_in_record.level_id = '".$_GET['level_id']."'";
}
if(!empty($_GET['small'])){
	$map['deposit_money'] = array(">",$_GET['small']);
	$where .=" and k_user_bank_in_record.deposit_money > '".$_GET['small']."'";
}
if(!empty($_GET['big'])){
	$map['deposit_money'] = array("<",$_GET['big']);
	$where .=" and k_user_bank_in_record.deposit_money < '".$_GET['big']."'";
}
if(!empty($_GET['account'])){
	$map['username'] = $_GET['account'];
	$where .=" and k_user_bank_in_record.username = '".$_GET['account']."'";
}

if (empty($_GET['reload'])) {
	$_GET['reload'] = 120;
}

//时间判断
if (!empty($_GET['start_date'])) {
	$s_date = $start_date = $_GET['start_date'];   
}else{
	$s_date = $start_date = date("Y-m-d");   
}

if (!empty($_GET['end_date'])) {
	$e_date = $end_date = $_GET['end_date'];   
}else{
	$e_date = $end_date = date("Y-m-d");   
}
$start_date = strtotime($start_date.' 00:00:00')+$_GET['timearea']*3600;
$end_date = strtotime($end_date.' 23:59:59')+$_GET['timearea']*3600;
$start_date = date('Y-m-d H:i:s',$start_date);
$end_date = date('Y-m-d H:i:s',$end_date);

if($_GET['status'] == 1){
    $map['log_time'][] = array(">=",$start_date);
    $map['log_time'][] = array("<=",$end_date);
    $where .=" and k_user_bank_in_record.log_time >= '".$start_date."' and k_user_bank_in_record.log_time <= '".$end_date."'";
}else{
	$map['log_time'][] = array(">=",$start_date);
    $map['log_time'][] = array("<=",$end_date);
    $where .=" and k_user_bank_in_record.log_time >= '".$start_date."' and k_user_bank_in_record.log_time <= '".$end_date."'"; 
}



//订单检索
if(!empty($_GET['order'])){
	$map['order_num'] = $_GET['order'];
	$where .=" and k_user_bank_in_record.order_num = '".$_GET['order']."'";
}

// var_dump($map);
$count=$b->where($map)->count();//获得记录总数

//分页
$perNumber=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
$totalPage=ceil($count/$perNumber); //计算出总页数
$page=isset($_GET['page'])?$_GET['page']:1;
if($totalPage<$page){
	$page=1;
}
$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
$limit=$startCount.",".$perNumber;
//查询表信息
 

$data=$b->field("k_bank.card_ID,k_user_bank_in_record.*,k_bank.bank_type,k_bank.card_userName,k_bank.remark")->join("join k_bank on k_bank.id = k_user_bank_in_record.bid")->where($where)->limit($limit)->order('k_user_bank_in_record.id DESC,k_user_bank_in_record.make_sure ASC')->select();
$page = $b->showPage($totalPage,$page);

//判断是否有新入款信息
$strIn = date('Y-m-d').'%';
$new_add_state = $b->field("id")
                   ->where("make_sure = '0' and site_id = '".SITEID."' and into_style = '1'")
                   ->find();
//p($new_add_state);
if (!empty($new_add_state)) {
	$new_add_state = 1;
}else{
	$new_add_state = 0;
}

 
?>
<?php $title="公司入款"; require("../common_html/header.php");?>
<body>
<script src="../public/js/swfobject.js" type="text/javascript"></script>
<script language="JavaScript" src="../public/js/easydialog.min.js"></script>
<link rel="stylesheet" href="../public/css/easydialog.css" type="text/css">
<script type="text/javascript">
 function delevel(id){
  $.get("account_level_get.php?ltype=lvset&id="+id, function(json){
    $('#context').html(json);
      easyDialog.open({
          container : 'delevel'
        });
    });
}
</script>
<script>
//分页跳转
	window.onload=function(){
		document.getElementById("page").onchange=function(){
			document.getElementById('queryform').submit()
		}
		document.getElementById("level_id").onchange=function(){
			document.getElementById('queryform').submit()
		}
		document.getElementById("status").onchange=function(){
			document.getElementById('queryform').submit()
		}
		
		
	}
	function getthis(){
	  
	  var d_now = Date(today);	  
	  var year = today.split('-')[0];
	  
	  for(i=1;i<=13;i++){
		  target_str = $("#"+year+'_'+i).html();		  
		  var data_arr = target_str.split('~');
		  var start = $.trim(data_arr[0].toString());
		  var end = $.trim(data_arr[1].toString());
		  
		  var d1 = new Date(start);
		  var d2 = new Date(end);
		  if((Date.parse(d_now) - Date.parse(d1))>=0 && (Date.parse(d_now) -86400000 - Date.parse(d2))<=0){
			  return(year+'_'+i);
		  }
	  }
  }
 /**
   * 此方法计算本期或者上个期的第一天和最后一天并返回相应的日期格式
 */
function changMonth(timea)
{	
	var   month = getthis();
	
	var target = '';
	var target_str = '';
	if(timea == 'last'){
		var month_arr = month.split('_');
		if(parseInt(month_arr[1])=='1'){
			var y = parseInt(month_arr[0])-1;
			target = y +'_13';
		}else{
			var m = parseInt(month_arr[1])-1;
			target = month_arr[0]+'_'+ m;
		}
	}else{
		target = month;		 
	}
	target_str = $("#"+target).html();
	var data_arr = target_str.split('~');
	var start = $.trim(data_arr[0].replace(/\//g,'-'));
	var end = $.trim(data_arr[1].replace(/\//g,'-'));
	document.getElementById('startdate').value = start;
	document.getElementById('enddate').value = end;
}
</script>
<script type="text/javascript">
var issound = <?=$new_add_state?>;
$(document).ready(function(){
	
	if(issound==1){
     	$('body').append('<embed src="../public/sound/gsrk.swf" autostart="true" width=0 height=0 loop="false">');	
		
	}
});
function setRefresh()
{   
	$('#queryform').submit();
}
function sub(btn,aid){
	if(btn && aid>0){
		$('#btn').val(btn);
		$('#aid').val(aid);
		if(confirm('確認要修改嗎?')){
			$('#queryform').submit();
		}else{
				$('#btn').val('');
				$('#aid').val('0');
		}
	}
	else
		$('#queryform').submit();
	$('#setsub').val(1);
	
}

</script>

<div id="con_wrap">
  <div class="input_002"><?=$inTitle?></div>
  <div class="con_menu" style="height:70px;">
  
  	<form name="queryform" id="queryform" action="<?=$gUrl?>" method="get">

	&nbsp;
	<a href="./bank_record.php" style="color:#f00;"  >公司入款</a>
  	<a href="./bank_record_online.php" >线上入款</a>
  	状态：
	<select name="status" id="status">
	<option value="-1" <?=select_check(-1,$_GET['status'])?>>全部</option>
  	<option value="0" <?=select_check(0,$_GET['status'])?>>未處理</option>
  	<option value="1" <?=select_check(1,$_GET['status'])?>>已確認</option>
  	<option value="2" <?=select_check(2,$_GET['status'])?>>已取消</option>
    <option value="dis" <?=select_check('dis',$_GET['status'])?>>有優惠</option>
    <option value="ndis" <?=select_check('ndis',$_GET['status'])?>>無優惠</option>
  	</select>
  	层级：
  	<select name="level_id" id="level_id">	
    	<option value="">全部</option>    
  		<?php 
  			foreach ($l as $key => $value) {
  				if($l[$key]['id'] == $_GET['level_id']){ ?>
  					<option value="<?=$l[$key]['id'] ?>" selected ><?=$l[$key]['level_des'] ?></option>
				<?php }else{ ?>
  					<option value="<?=$l[$key]['id'] ?>"><?=$l[$key]['level_des'] ?></option>
				<?php } ?>
	       <?php } ?>
						
	</select>
	时区:
	<select name="timearea" id="area">
  	<option value="0" <?=select_check(0,$_GET['timearea'])?>>美东</option>
  	<option value="12" <?=select_check(12,$_GET['timearea'])?>>北京</option>
  	</select>
	日期：
	 <input class="za_text Wdate" onClick="WdatePicker()" value="<?=$s_date?>" name="start_date">
      ~
	  <input class="za_text Wdate" onClick="WdatePicker()" value="<?=$e_date?>" name="end_date">  


	金額：
	<input type="text" name="small" class="za_text" style="min-width:50px;width:50px" value="<?=$_GET['small'] ?>" size="5">~
	<input type="text" name="big" class="za_text" style="min-width:50px;width:50px" value="<?=$_GET['big'] ?>" size="5">&nbsp;&nbsp;	
	
	<br>
	帳號：
	<input type="text" name="account" class="za_text" style="min-width:80px;width:80px" value="<?=$_GET['account'] ?>" size="10">&nbsp;&nbsp;
	
	订单號：
	<input type="text" name="order" class="za_text" style="min-width:80px;width:150px" value="<?=$_GET['order'] ?>">&nbsp;&nbsp;
	<input type="button" onclick="sub(&#39;&#39;,0)" value="查詢" class="button_d">
	刷新：
	<select name="reload" id="retime" class="za_select" onchange="setTimeout(setRefresh(), this.value*1000)">
		<option value="-1">不更新</option>
		
        <option value="30" <?=select_check(30,$_GET['reload'])?>>30秒</option>
		<option value="60" <?=select_check(60,$_GET['reload'])?>>60秒</option>
		<option value="120" <?=select_check(120,$_GET['reload'])?>>120秒</option>
        <option value="180" <?=select_check(180,$_GET['reload'])?>>180秒</option>
	</select>&nbsp;
	每页记录数：
	<select name="page_num" id="page_num" onchange="document.getElementById('queryform').submit()" class="za_select">
		<option value="20" <?=select_check(20,$perNumber)?>>20条</option>
		<option value="30" <?=select_check(30,$perNumber)?>>30条</option>
		<option value="50" <?=select_check(50,$perNumber)?>>50条</option>
		<option value="100" <?=select_check(100,$perNumber)?>>100条</option>
	</select>
	&nbsp;<?=$page?>&nbsp;
     <input type="button" name="btn1" onclick="window.open('cash.php?type=down')" style="color:red" id="btn1" value="監控" class="button_d"> 
</form>
	</div>
</div>
<div class="content">
 <form method="post" name="myFORM" action="rk_excel.php">
	<table width="1024" border="0" cellspacing="0" cellpadding="0" bgcolor="#E3D46E" class="m_tab">
		<tbody><tr class="m_title_over_co">
			<td>层级</td>
			<td>订单号</td>
			<td>代理商</td>
			<td>會員帳號</td>
		    <td>會員銀行帳戶</td>
			<td>存入金額</td>
	        <td>存入銀行帳戶</td>
			<td>狀態</td>
			<td>首存</td>
			<td>操縱者</td>
			<td>時間</td>
		</tr>
	<?php 
	    if (!empty($data)) {
		$num;$deposit_num;$favourable_num;$other_num;$deposit_money;
		$num=0;
		foreach ($data as $k => $v) {
		  $num+=1;
		  $deposit_num += $data[$k]['deposit_num'];
		  $favourable_num+=$data[$k]['favourable_num'];
		  $other_num+=$data[$k]['other_num'];
		  $deposit_money+=$data[$k]['deposit_money'];
		  $atm_address = '';
		 ?>		

			<tr class="m_cen">
			<td><?=$data[$k]['level_des'] ?></td>
			<td><?=$data[$k]['order_num'] ?></td>
			<td><?=$data[$k]['agent_user'] ?></td>
			<td><?=$data[$k]['username'] ?></td>
		   
		<?php if ($intoStyle ==1) { 
			if ($v['in_type'] ==2 || $v['in_type'] == 3 || $v['in_type'] == 4) {
				$atm_address = '網點：'.$v['in_atm_address'];
			}
			echo "<td style=\"text-align:left;\">銀行：".bank_type($v['bank_style'])."<br>存款人：".$v['in_name']."<br>存款時間：".$v['in_date']."<br>方式：".in_type($v['in_type'])."<br>".$atm_address."</td>";
		}
	    ?>
			<td style="text-align:left;">
			存入金額：<?=$data[$k]['deposit_num'] ?>
            <br>存款優惠：<?=$data[$k]['favourable_num'] ?>
			<br>其他優惠：<?=$data[$k]['other_num'] ?>
			<br>存入總金額：<?=$data[$k]['deposit_money'] ?>
			</td>

		  <?php if ($intoStyle ==1) { 
			echo "<td style=\"text-align:left;\">銀行帳號：".$v['card_ID']."<br>銀行：".bank_type($v['bank_type']).$v['card_address']."<br>卡主姓名：".$v['card_userName']."<br>备注：".$v['remark']."</td>";}
	     ?>
			<td>
			<?php 
			if($v['make_sure']==0){			
			 ?>			
			
			 <a class="button_d" href="javascript:void(0);" onclick="if(confirm('要將狀態改為已取消的狀態嗎?')){document.location='./bank_record_do.php?btn=s0&id=<?=$data[$k]['id']?>&uid=<?=$v['uid']?>&order_num=<?=$data[$k]['order_num']?>';}">取消</a>
			&nbsp;<a class="button_d" href="javascript:void(0);" onclick="if(confirm('要將狀態改為已確定的狀態嗎?')){document.location='./bank_record_do.php?btn=s1&id=<?=$data[$k]['id']?>&uid=<?=$v['uid']?>&intoType=<?=$intoType?>';}">確認存款</a>
			<?php }elseif($v['make_sure']==1){
				echo "已确认";
			}elseif ($v['make_sure'] == 2) {
		    ?>
			 已取消
			&nbsp;<a class="button_d" href="javascript:void(0);" onclick="if(confirm('要將狀態改為已確定的狀態嗎?')){document.location='./bank_record_do.php?btn=s1&id=<?=$data[$k]['id']?>&uid=<?=$v['uid']?>&intoType=<?=$intoType?>';}">確認存款</a>
		    <?php

			} ?>
			</td>
			<td><?=is_not($v['is_firsttime'])?></td>
			<td><?=$v['admin_user']?></td>
			<td>
			 系統時間：<?=$v['log_time'] ?>(美东)<br>
             操作時間：<?=$v['do_time'] ?>(美东)	
		   </td>					
	
		</tr>

			<!-- 隐藏传值，用于导出excel，stare -->
			<input name="intoStyle[]" type="hidden" value="<?=$intoStyle?>">
			<input name="in_type[]" type="hidden" value="<?=$v['in_type']?>">
			<input name="atm_address[]" type="hidden" value="網點：<?=$v['in_atm_address']?>">
			<input name="bank_style[]" type="hidden" value="<?=bank_type($v['bank_style'])?>">
			<input name="in_name[]" type="hidden" value="<?=$v['in_name']?>">
			<input name="in_date[]" type="hidden" value="<?=$v['in_date']?>">
			<input name="ck_type[]" type="hidden" value="<?=in_type($v['in_type'])?>">
			
			<input name="level_des[]" type="hidden" value="<?=$v['level_des']?>">
			<input name="order_num[]" type="hidden" value="<?=$data[$k]['order_num'] ?>">
			<input name="agent_user[]" type="hidden" value="<?=$data[$k]['agent_user'] ?>">
			<input name="username[]" type="hidden" value="<?=$data[$k]['username'] ?>">			
			
			<input name="crje[]" type="hidden" value="<?=$data[$k]['deposit_num'] ?>">
			<input name="ckyh[]" type="hidden" value="<?=$data[$k]['favourable_num'] ?>">
			<input name="qtyh[]" type="hidden" value="<?=$data[$k]['other_num'] ?>">
			<input name="crzje[]" type="hidden" value="<?=$data[$k]['deposit_money'] ?>">
			
			<input name="make_sure[]" type="hidden" value="<?=$v['make_sure']?>">
			<input name="admin_user[]" type="hidden" value="<?=$v['admin_user']?>">
			<input name="log_time[]" type="hidden" value="<?=$v['log_time']?>">
			<input name="do_time[]" type="hidden" value="<?=$v['do_time']?>">
			
			<input name="card_ID[]" type="hidden" value="<?=$v['card_ID']?>">
			<input name="bank_type[]" type="hidden" value="<?=bank_type($v['bank_type']).$v['card_address']?>">
			<input name="card_userName[]" type="hidden" value="<?=$v['card_userName']?>">
			<!-- 隐藏传值，用于导出excel,END -->
		
			<?php }} ?>
<!-- 			<tr align="center">
			<td colspan="15">小计:
			存入金額：<font class="fontsty1"><?=$deposit_num ?></font>
			存款優惠：<font class="fontsty2"><?=$favourable_num ?></font>
			其他優惠：<font class="fontsty3"><?=$other_num ?></font>
			存入總金額：<font class="fontsty4"><?=$deposit_money ?></font>
			</td>
		</tr> -->
		<tr align="center">
			<td colspan="15">总计:
			笔数：<font class="fontsty1"><?=$num ?></font>
			存入金額：<font class="fontsty1"><?=$deposit_num ?></font>
			存款優惠：<font class="fontsty2"><?=$favourable_num ?></font>
			其他優惠：<font class="fontsty3"><?=$other_num ?></font>
			存入總金額：<font class="fontsty4"><?=$deposit_money ?></font>
			</td>
		</tr>
	<!--  <tr align="center">
			<td colspan="17">
			<input name="tableTitel" type="hidden" value="<?=$start_date?>---<?=$end_date?>">
			<input type="submit" name="submit"  value="导出表格" class="button_d"></td>
		</tr> -->
	</tbody></table>
</div>
<div id="sound"></div>
<script type="text/javascript">
//var retime = "" + -1;
var retime = $('#retime').val();
$(document).ready(function()
{
	var time = (retime == 0 || retime == -1) ? -1 : "" + retime;
	if(time != -1)
	{
		setTimeout("setRefresh()", time * 1000);		
	}
})
</script>
<!-- 公共尾部 -->
<?php
require("../common_html/footer.php"); 
?>

<?php 
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once("../common/user_set.php");

/**
*
*    into_style  2表示线上入款
*
**/
// var_dump($_GET);
$b = M('k_user_bank_in_record',$db_config);
$lev=M('k_user_level',$db_config);

$map['site_id'] = SITEID;
$l=$lev->field("level_des,id")->where($map)->select();

	$map['into_style'] = 2;
	$intoStyle = 2;	
	$inTitle = '线上入款';
	$intoType = 10;
	$gUrl = 'bank_record_online.php';
	
	

$where ="k_user_bank_in_record.into_style ='".$intoStyle."' and k_user_bank_in_record.site_id = '".SITEID."'";
//拼接搜索条件
if($_GET['status'] != ''){
	if($_GET['status']==0 || $_GET['status']==1 ||$_GET['status']==2 ){
		$map['make_sure'] = $_GET['status'];
		$where .=" and k_user_bank_in_record.make_sure = '".$_GET['status']."'";
	}elseif($_GET['status']==3){//有优惠
		$map1['favourable_num'] = array(">",0);
		$map1['other_num'] = array(">",0);
		$map1['_logic'] = 'or';
		$map['_complex'] = $map1;
		$where .=" and (k_user_bank_in_record.favourable_num > '0' or k_user_bank_in_record.other_num > '0')";
	}elseif($_GET['status']==4){
		$map['favourable_num'] = array("=",0);
		$map['other_num'] = array("=",0);
		$where .=" and k_user_bank_in_record.favourable_num = '0' and k_user_bank_in_record.other_num = '0'";
	}
	
}else{
	$_GET['status'] = '-1';
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
	$_GET['reload'] == 180;
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
$map['in_date'][] = array(">=",$start_date);
$map['in_date'][] = array("<=",$end_date);
$where .=" and k_user_bank_in_record.in_date >= '".$start_date."' and k_user_bank_in_record.in_date <= '".$end_date."'"; 

//订单检索
if(!empty($_GET['order'])){
	$map['order_num'] = $_GET['order'];
	$where ="k_user_bank_in_record.order_num = '".$_GET['order']."'";
}

$count=$b->field("id")->where($map)->count();//获得记录总数
$data1['update_id']=$_SESSION["login_name"];//操作人ID

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
 

//$data=$b->field("k_user_bank_in_record.*,k_user_level.level_des,k_bank.card_ID,k_bank.card_userName,k_bank.card_address,k_bank.account_type,k_bank.remark")->join("left join k_user_level on k_user_bank_in_record.level_id = k_user_level.id left join k_bank on k_bank.id = k_user_bank_in_record.bid")->where($where)->limit($limit)->order('k_user_bank_in_record.in_date DESC')->select();
$data=$b->field("k_user_bank_in_record.*,k_user_level.level_des")->join("left join k_user_level on k_user_bank_in_record.level_id = k_user_level.id")->where($where)->limit($limit)->order('k_user_bank_in_record.in_date DESC')->select();

//判断是否有新入款 
$new_in_state = $b->field("id")
				   ->where("make_sure = '0' and site_id = '".SITEID."' and into_style = '2'")
				   ->find();
if (!empty($new_in_state)) {
	$new_in_state = 1;
}else{
	$new_in_state = 0;
}
$page = $b->showPage($totalPage,$page);

?>
<?php $title="线上入款"; require("../common_html/header.php");?>
<body>
<script src="../public/js/swfobject.js" type="text/javascript"></script>
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
var issound = <?=$new_in_state?>;

$(document).ready(function(){
	if(issound==1){
     	$('body').append('<embed src="../public/sound/xsrk.swf" autostart="true" width=0 height=0 loop="false">');
     	$.ajax({  
		type: "get",  
		url: "cash_online.php",  
		data: {act:"cash_delete"},  
		success: function(r){  
			}
		});	
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
  	<input type="hidden" name="into_style" value="2">
  	<input type="hidden" name="setsub" id="setsub" value="0">
	<input type="hidden" name="btn" id="btn" value="">
	<input type="hidden" name="aid" id="aid" value="0">
	&nbsp;
	<a href="bank_record.php">公司入款</a>
  	<a href="bank_record_online.php"  style="color:#f00;" >线上入款</a>
  	状态：
	<select name="status" id="status">
      	<option value="-1" <?=select_check(-1,$_GET['status'])?>>全部</option>
  	<option value="0" <?=select_check(0,$_GET['status'])?>>未支付</option>
  	<option value="1" <?=select_check(1,$_GET['status'])?>>已支付</option>
  	<option value="2" <?=select_check(2,$_GET['status'])?>>已取消</option>
    <option value="3" <?=select_check(3,$_GET['status'])?>>有優惠</option>
    <option value="4" <?=select_check(4,$_GET['status'])?>>無優惠</option>
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
        <option value="30" <?=select_check(30,$_GET['reload'])?>>30秒</option>
        <option value="-1" <?=select_check(-1,$_GET['reload'])?>>不更新</option>
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
     <input type="button" name="btn1" onclick="window.open('cash.php?type=on')" style="color:red" id="btn1" value="監控" class="button_d"> 
</form>
	</div>
</div>
<div class="content">
	<table width="1024" border="0" cellspacing="0" cellpadding="0" bgcolor="#E3D46E" class="m_tab">
		<tbody><tr class="m_title_over_co">
			<td>层级</td>
			<td>订单号</td>
			<td>代理商</td>
			<td>會員帳號</td>
		<?php if ($intoStyle ==1) { echo "<td>會員銀行帳戶</td>";}?>
			<td>存入金額</td>
	    <?php if ($intoStyle ==1) { echo "<td>存入銀行帳戶</td>";}?>
			<td>狀態</td>
			<td>支付方式</td>
			<td>是否首存</td>
			<td>操縱者</td>
			<td>時間</td>
		</tr>
				<?php 
	    if (!empty($data)) {
		$num;$deposit_num;$favourable_num;$other_num;$deposit_money;
		$num=0;
		foreach ($data as $k => $v) {
			$num+=1;
		$deposit_num+=$data[$k]['deposit_num'];
		$favourable_num+=$data[$k]['favourable_num'];
		$other_num+=$data[$k]['other_num'];
		$deposit_money+=$data[$k]['deposit_money'];
		 ?>		

			<tr class="m_cen">
			<td><?=$v['level_des'] ?></td>
			<td><?=$data[$k]['order_num'] ?></td>
			<td><?=$data[$k]['agent_user'] ?></td>
			<td><?=$data[$k]['username'] ?></td>
		   
		<?php if ($intoStyle ==1) { 
			if ($v['in_type'] ==2 || $v['in_type'] == 3) {
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
			echo "<td style=\"text-align:left;\">銀行帳號：".$v['card_ID']."<br>銀行：".bank_type($v['bank_type']).$v['card_address']."<br>卡主姓名：".$v['card_userName']."</td>";}
	     ?>
			<td>
			<?php 
			if($v['make_sure']==0){			
			 ?>	
			&nbsp;
			<!--<input type="button" value="確認" onclick="if(confirm('要將狀態改為已確定的狀態嗎?')){document.location='cash_op.php?act=online&id=<?=$data[$k]['id']?>&status=1&op=1';}" class="button_d"/>&nbsp;
			<input type="button" value="取消" onclick="if(confirm('要將狀態改為已取消的狀態嗎?')){document.location='cash_op.php?act=online&id=<?=$data[$k]['id']?>&status=2&op=1';}" class="button_d"/>-->
			<?php echo "未支付";?>
			<?php }elseif($v['make_sure']==1){
				echo "已支付";
				}elseif($v['make_sure']==2){
					echo "已取消";
				} ?>
							</td>
							<td><?=payment_type($v['paytype'])."(ID：".$v['pay_id'].")"?></td>
			<td><?php if($v['is_firsttime']==0){echo '否';}else{echo '是';}?></td>
			<td><?=$v['admin_user']?></td>
			
			<td>
					系統時間：<?=$v['log_time'] ?><?php if($intoStyle ==1){echo "(美东)";}else{echo "(北京)";} ?> <br>
                    操作時間：<?=$v['do_time'] ?><?php if($intoStyle ==1){echo "(美东)";}else{echo "(北京)";} ?>	
		</td>					
	
		</tr>
		
			<?php }} ?>
		<tr align="center">
			<td colspan="15">总计:
			笔数：<font class="fontsty1"><?=$num ?></font>
			存入金額：<font class="fontsty1"><?=$deposit_num ?></font>
			存款優惠：<font class="fontsty2"><?=$favourable_num ?></font>
			其他優惠：<font class="fontsty3"><?=$other_num ?></font>
			存入總金額：<font class="fontsty4"><?=$deposit_money ?></font>
			</td>
		</tr>
	 <tr align="center"><td class="table_bg1" colspan="13">&nbsp;</td></tr>
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
<?php require("../common_html/footer.php"); ?>
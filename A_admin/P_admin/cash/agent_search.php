<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");


function status_type($type){
	switch ($type) {
		case 0:
			return '未處理';
			break;
		case 1:
			return '取消';
			break;	
		case 2:
			return '退傭';
			break;
		case 3:
			return '掛賬';
			break;
		case 4:
			return '未達門檻';
			break;
		case 5:
			return '未達佣金資格';
			break;
		case 6:
			return '已達門檻';
			break;
	}
}
//读取全部期数读取
$qishu = M('k_qishu',$db_config)->where("site_id = '".SITEID."' and is_delete = '0'")->order('id desc')->select();

   $qs=$_GET['qs'];

   if($qs){
   	if(!empty($_GET['username'])){
   		$where.=" and agent_user like '%".$_GET['username']."%'";
   	}
   	
   	if($_GET['kf']==1){
   		$where.=" and retuCash>0";
   	}elseif($_GET['kf']==2){
		$where.=" and retuCash=0";
   	}

   	if($_GET['yf']==1){
   		$where.=" and hascash>0";
   	}elseif($_GET['yf']==2){
		$where.=" and hascash=0";
   	}
   	if(!empty($_GET['startnum'])){
   		$where.=" and valid_usernum>='".$_GET['startnum']."'";
   	}
   	if(!empty($_GET['endnum'])){
   		$where.=" and valid_usernum<='".$_GET['endnum']."'";
   	}

   	$table=M('k_user_agent_record',$db_config);
    $agentRecord = $table->where(" qishu_id= ".$qs.$where)->order('id desc')->select();
    //var_dump($agentRecord);
    }

    if(!empty($_POST['ac'])){
    	if(!empty($_POST['ids'])){
    		$ids=$_POST['ids'];
    		foreach($ids as $v){
    			$table=M('k_user_agent_record',$db_config);
    			$info=$table->where("id=$v")->find();
    			$qs_status=M('k_qishu',$db_config)->where("id=$info[qishu_id]")->find();
    			if($qs_status['state']==1){
    				message('该期已被锁定');
    			}
    			if($info['type']>0){
    				message('不能对同一代理进行重复操作！','agent_search.php?search='.$_GET['search'].'&qs='.$_GET['qs'].'&username='.$_GET['username'].'&kf='.$_GET['kf'].'&yf='.$_GET['yf'].'&startnum='.$_GET['startnum'].'&endnum='.$_GET['endnum']);
    			}
    			$data['status']=$_POST['dz'];
    			if($_POST['dz']!=0&&$_POST['dz']!=4){
    				$data['hascash']=$info['retuCash']+$info['hascash'];
    				$data['retuCash']=0;
    			}
    			
    			$data['type']=1;//已操作
    			$data['info']='操作者:'.$_SESSION['login_name'].'将'.status_type($info['status']).'改为'.status_type($_POST['dz']);
    			$result[]=$table->where("id=$v")->update($data);
    		}
    		if(!empty($result)){
    			$do_log = $_SESSION['login_name'].'进行了退佣操作';
				admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
    			message('操作成功');
    		}
    	}
    }
?>

<?php $title="退佣查询"; require("../common_html/header.php");?>
<body>

<script type="text/javascript">

function show_div(event,ty,cp,yx,dz)
{
	var event = event ? event : window.event; 
	var top = left = 0;
	//var div = $("#detail_info");
	$("#detail_ty").text(ty);
	$("#detail_cp").text(cp);
	$("#detail_yx").text(yx);
	$("#detail_dz").text(dz);
	var div = document.getElementById('detail_info');	
	top = event.pageY;
	left =event.pageX;
	div.style.top = top + "px";
	div.style.left = left + "px";
	div.style.display = "block";
}
function hide_div(){
	var div = document.getElementById('detail_info');
	div.style.display = "none";
}
function selectall(){
	//alert($("#allbox").val());
	if($("#allbox").val()==1){
		$("#allbox").val(2);
		$(".checkbox").attr("checked",true);
	}else{
		$("#allbox").val(1);
		$(".checkbox").attr("checked",false);
	}
}

$(function(){
	$("div.con_menu a:nth-child(2)").attr("style","color:red");	
});
$(document).ready(function() {	 
	$("#savebtn").bind("click",function(e){
		var op =  $('#dz').find('option:selected').text();
		if($('.checkbox').is(':checked')) {			
			return confirm('是否要对所选代理商进行'+op+'操作\r\n\r\n操作后将无法恢复！');
		}else{
			alert('请选择需要操作的代理商!');
			return false;
		}
			
	});
});

$(function(){
	$('#kf').val(<?=$_GET['kf']?>);
	$('#yf').val(<?=$_GET['yf']?>);
	$('#qs').val(<?=$_GET['qs']?>);
	
	
})
</script>
<div id="con_wrap">
<div class="input_002">退傭查詢</div>
<div class="con_menu">
	<a href="agent_count.php">退佣统计</a>
    <a href="agent_search.php" style="color:red">退佣查询</a>
    <a href="end_hire.php">期數管理</a>
	<a href="fee_list.php">手續費設定</a>
	<a href="endhire_list.php">代理退傭設定</a>&nbsp;&nbsp;</div>
</div>
<div class="content">

<table border="0">
	<tbody><tr>
    	<td>
    <form name="search_form" id="search_form" method="get">
    <input type="hidden" name="search" value="search"> 
    期數選擇：<select name="qs" id="qs" class="za_select">
        <?php if (!empty($qishu)) {
            foreach ($qishu as $key => $val) {
        ?>
        <option value="<?=$val['id']?>"><?=$val['qsname']?></option>
        <?php }}?>
        </select>
    帳號：<input type="text" name="username" class="za_text" value="<?=$_GET['username']?>" style="min-width:80px;width:80px">
    可獲退傭：<select name="kf" id="kf" class="za_select">
    	<option value="">全部</option>
        <option value="1">大於0</option>
        <option value="2">等於0</option>
    </select>
    已獲退傭：<select name="yf" id="yf" class="za_select">
    	<option value="">全部</option>
        <option value="1">大於0</option>
        <option value="2">等於0</option>
    </select>
    有效會員：<input type="text" name="startnum" value="<?=$_GET['startnum']?>" style="min-width:50px;width:50px" class="za_text"> ~ <input type="text" name="endnum" value="<?=$_GET['endnum']?>" style="min-width:50px;width:50px" class="za_text">
	&nbsp;&nbsp;<input type="submit" value="查詢" class="button_d">
	&nbsp;
    </form>
</td>
    </tr>
</tbody></table>
<form method="post">
<input type="hidden" name="ac" value="ac">
<table width="1024" border="0" cellspacing="0" cellpadding="0" bgcolor="#E3D46E" class="m_tab">
		<tbody><tr class="m_title_over_co">
		  <td rowspan="2">代理帳號</td>
		  <td rowspan="2">名稱</td>
		  <td rowspan="2">有效會員</td>
		  <td colspan="2">派彩</td>
		  <td colspan="4">退傭比例(%)</td>
		  <td colspan="2">有效投注</td>
		  <td colspan="4">退水比例(%)</td>
		  <td colspan="2">費用</td>
		  <td rowspan="2">可獲退傭</td>
		  <td rowspan="2">已獲退傭</td>
		  <td rowspan="2">狀態</td>
		  <td rowspan="2">動作</td>
		  <td rowspan="2">出款銀行資料</td>
		  <td rowspan="2">備注</td>
    </tr>
		<tr class="m_title_over_co">
			<td>累積</td>
			<td>當期</td>
			<td>視訊</td>
            <td>電子</td>
			<td>體育</td>
            <td>彩票</td>
            <td>累積</td>
            <td>當期</td>
            <td>視訊</td>
            <td>電子</td>
            <td>體育</td>
            <td>彩票</td>
            <td>累積</td>
            <td>當期</td>
        </tr>
        <?php if (!empty($agentRecord)) {
        	//foreach ($agentRecord as $key => $value) {
        	//	if($agentRecord[$key]['retuCash'] == 0 && $agentRecord[$key]['hascash'] == 0){
        	//		unset($agentRecord[$key]);
        	//	}
        	//}
        foreach ($agentRecord as $key => $agent) {
        ?>  
		<tr class="m_cen">
        	<td><?=$agent['agent_user']?></td>
			<td><?=$agent['agent_name']?></td>
			<td><?=$agent['valid_usernum']?></td>
			<td onmouseover="show_div(event,&#39;<?=$agent['old_kbet']?>&#39;,&#39;<?=$agent['old_cbet']?>&#39;,&#39;<?=$agent['old_vbet']?>&#39;,&#39;<?=$agent['old_ebet']?>&#39;)" onmouseout="hide_div()"><?=$agent['old_bet']?></td>
			<td onmouseover="show_div(event,&#39;<?=$agent['now_kbet']?>&#39;,&#39;<?=$agent['now_cbet']?>&#39;,&#39;<?=$agent['now_vbet']?>&#39;,&#39;<?=$agent['now_ebet']+0?>&#39;)" onmouseout="hide_div()"><?=$agent['now_bet']?></td>
			<td ><?=$agent['video_slay_rate']?>%</td>
	        <td ><?=$agent['evideo_slay_rate']?>%</td>
	        <td ><?=$agent['sport_slay_rate']?>%</td>
	        <td ><?=$agent['lottery_slay_rate']?>%</td>
            <td onmouseover="show_div(event,&#39;<?=$agent['old_validkbet']?>&#39;,&#39;<?=$agent['old_validcbet']?>&#39;,&#39;<?=$agent['old_validvbet']?>&#39;,&#39;<?=$agent['old_validebet']?>&#39;)" onmouseout="hide_div()"><?=$agent['old_validbet']?></td>
            <td onmouseover="show_div(event,&#39;<?=$agent['now_validkbet']?>&#39;,&#39;<?=$agent['now_validcbet']?>&#39;,&#39;<?=$agent['now_validvbet']?>&#39;,&#39;<?=$agent['now_validebet']?>&#39;)" onmouseout="hide_div()"><?=$agent['now_validbet']?></td>
            <td ><?=$agent['video_water_rate']?>%</td>
            <td ><?=$agent['evideo_water_rate']?>%</td>
            <td ><?=$agent['sport_water_rate']?>%</td>
            <td ><?=$agent['lottery_water_rate']?>%</td>
            <td ><?=$agent['oldcash']?></td>
            <td ><?=$agent['nowcash']?></td>
		    <td ><?=$agent['retuCash']?></td>	
            <td><?=$agent['hascash']?></td>
            <td><?=status_type($agent['status'])?></td>
            <td><input type="checkbox" class="checkbox" name="ids[]" value="<?=$agent['id']?>"></td>
            <td><?=$agent['bank']?></td>
            <td><?=$agent['info']?></td>
		</tr>
        <?php }}?>	  
	 
 
 
				<tr align="center">
			<td class="table_bg1" colspan="18"></td>
			<td class="table_bg1" colspan="1"><input type="checkbox" onclick="selectall();" id="allbox" value="1"></td>
			<td class="table_bg1" colspan="1" align="left">
			
			<select name="dz" id="dz" class="za_select">
	        <option value="1">取消</option>
	        <option value="2">退傭</option>
	        <option value="3">掛賬</option>
	        <option value="0">未處理</option>
	        <option value="4">未达门槛</option>
	        <option value="5">未達佣金資格</option>
	    	</select>
			<input type="submit" value="送出" class="button_d" id="savebtn"></td>
			<td class="table_bg1" colspan="1"></td>	
			<td></td>
            <td></td>	
		</tr>
		</tbody></table>
	</form>
</div>
<div style="position: absolute; width: 300px; display: none; top: 227px; left: 594px;" id="detail_info">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#E3D46E" class="m_tab">
		<tbody><tr class="m_title_over_co">
			<td>視訊</td>
            <td>電子</td>
			<td>體育</td>
			<td>彩票</td>
		</tr>
		<tr>
			<td id="detail_yx"></td>
            <td id="detail_dz"></td>
			<td id="detail_ty"></td>
			<td id="detail_cp"></td>
			
		</tr>					
	</tbody></table>
</div>
</body></html>
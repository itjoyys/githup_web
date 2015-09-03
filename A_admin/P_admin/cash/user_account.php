<?php
include_once("../../include/config.php");
include_once("../common/login_check.php"); 
//print_r($_REQUEST);
//echo "<br />";

$where = " site_id = '".SITEID."'";

//时间判断
if (!empty($_GET['start_date'])) {
	$start_date = $_GET['start_date'];
}else{
	$start_date = date("Y-m-d");
}
if (!empty($_GET['end_date'])) {
	$end_date = $_GET['end_date'];
}else{
	$end_date = date("Y-m-d");
}



//排序
if($_GET['order_assort'] == "count"){
    $order = "count ".$_GET['order'];
}else{
    $order = "money ".$_GET['order'];
}
//echo $order;

//时间
if($_GET['out'] == "in"){
    $where .= " and in_date >= '" . $start_date . "' and in_date <= '" . $end_date . "'";
}elseif($_GET['out'] == "out"){
    $where .= " and out_time >= '" . $start_date . "' and out_time <= '" . $end_date . "'";
}


//判断查询条件
if($_GET['level'] == 2){
    if($_GET['name']){
        $where .= " and agent_user = '".$_GET['name']."'";
        $group = "username";
        $model = true;
    }else{
        $group = "agent_user";
    }
    //金额判断
    if(is_numeric($_GET['money'])){
        $group .= " HAVING money " . ($_GET['money_opt'] == 1 ? '< ' : '> ') . $_GET['money'];
    }
    //次数判断
    if(is_numeric($_GET['amount'])){
        if($_GET['money']){
            $group .= " and count " . ($_GET['amount_opt'] == 1 ? '< ' : '> ') . $_GET['amount'];
        }else{
            $group .= " HAVING count " . ($_GET['amount_opt'] == 1 ? '< ' : '> ') . $_GET['amount'];
        }
    }

    if ($_GET['out'] == "in"){
        $agent = M('k_user_bank_in_record',$db_config);
        $agent_arr = $agent->field("agent_user , SUM(deposit_num) as money, COUNT(id) as count, username")
        ->where($where)
        ->group($group)
        ->order($order)
        ->select();
    }elseif($_GET['out'] == "out"){
        $agent = M('k_user_bank_out_record',$db_config);
        $agent_arr = $agent->field("agent_user , SUM(outward_money)as money, COUNT(id) as count, username")
        ->where($where)
        ->group($group)
        ->order($order)
        ->select();
    }
    //echo $agent->getLastSql();
}elseif($_GET['level'] == 1){
    if($_GET['name']){
        $where .= " and username = '".$_GET['name']."'";
    }
    $group = "username";
    //金额判断
    if(is_numeric($_GET['money'])){
        $group .= " HAVING money " . ($_GET['money_opt'] == 1 ? '< ' : '> ') . $_GET['money'];
    }
    //次数判断
    if(is_numeric($_GET['amount'])){
        if($_GET['money']){
            $group .= " and count " . ($_GET['amount_opt'] == 1 ? '< ' : '> ') . $_GET['amount'];
        }else{
            $group .= " HAVING count " . ($_GET['amount_opt'] == 1 ? '< ' : '> ') . $_GET['amount'];
        }
    }
    if ($_GET['out'] == "in"){
        $agent = M('k_user_bank_in_record',$db_config);
        $agent_arr = $agent->field("agent_user , SUM(deposit_num) as money, COUNT(id) as count, username")
        ->where($where)
        ->group($group)
        ->order($order)
        ->select();
    }elseif($_GET['out'] == "out"){
        $agent = M('k_user_bank_out_record',$db_config);
        $agent_arr = $agent->field("agent_user , SUM(outward_money)as money, COUNT(id) as count, username")
        ->where($where)
        ->group($group)
        ->order($order)
        ->select();
    }
    //echo $agent->getLastSql();
}

//echo $where;
function getUserNum($agent_user){
    global $db_config;
    $user = M('k_user',$db_config);
    $user_num = $user->field("count('id') as num")
    ->where("agent_id = (select id from k_user_agent WHERE agent_user = '".$agent_user."')")
    ->find();
    return $user_num['num'];
}

?>

<?php

if($_GET['excel'] == "true"){
include '../common/PHPExcel.php';
//创建对象
$excel = new PHPExcel();
//Excel表格式,这里简略写了8列
$letter = array('A','B','C','D','E','F','F','G');
//表头数组
if($_GET['out'] == 'in'){
    $tableheader = array('代理商','会员帐号(总数)','入款金额','入款次数');
    $ename = "indata.xls";
}elseif ($_GET['out'] == 'out'){
    $tableheader = array('代理商','会员帐号(总数)','出款金额','出款次数');
    $ename = "outdata.xls";
}

//填充表头信息
for($i = 0;$i < count($tableheader);$i++) {
    $excel->getActiveSheet()->setCellValue("$letter[$i]1","$tableheader[$i]");
}
//填充数组
if($_GET['level'] == 2 && empty($_GET['name'])){
    foreach ($agent_arr as $k=>$arr){
        $agent_arr[$k]['username'] = getUserNum($agent_arr[$k]['agent_user']);
    }
}
$data = $agent_arr;
//填充表格信息
for ($i = 0; $i < count($data) - 1; $i++) {
    $excel->getActiveSheet(0)->setCellValue('A' . ($i + 2), $data[$i]['agent_user']);
    $excel->getActiveSheet(0)->setCellValue('B' . ($i + 2), $data[$i]['username']);
    $excel->getActiveSheet(0)->setCellValue('C' . ($i + 2), $data[$i]['money']);
    $excel->getActiveSheet(0)->setCellValue('D' . ($i + 2), $data[$i]['count']);
}
//清缓存
ob_end_clean();
//创建Excel输入对象
$write = new PHPExcel_Writer_Excel5($excel);
header("Pragma: public");
header("Expires: 0");
header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
header("Content-Type:application/force-download");
header("Content-type:application/vnd.ms-excel");
header("Content-Type:application/octet-stream");
header("Content-Type:application/download");;
header("Content-Disposition:attachment;filename=\"".$ename."\"");
header("Content-Transfer-Encoding:binary");
$write->save('php://output');
}
?>

<?php $title="会员分析系统"; require("../common_html/header.php");?>
<body>
<script>
function getExcel(){
	document.getElementById("excel").value = true;
	document.getElementById("addForm").submit();
}
function sel(){
	document.getElementById("excel").value = false;
	document.getElementById("addForm").submit();
}
</script>
<div  id="con_wrap">
<div  class="input_002">会员分析系统</div>
<div  class="con_menu">
<div>
<a  href="user_account.php"  style="color:red">出入款统计</a>
<!-- <a  href="agent_ad_statistics.php">优惠</a> -->
<a  href="bet_count.php">下注分析</a>
<a  href="bet_num.php">投注人数</a>
</div><br />
<form name="addForm" id="addForm"  action=""  method="get">
日期：
<input class="za_text Wdate" id="start_date" onClick="WdatePicker()" value="<?=$start_date?>" name="start_date"> &nbsp;
<input class="za_text Wdate" id="end_date" onClick="WdatePicker()" value="<?=$end_date?>" name="end_date">
&nbsp;方式：
<select name="out" id="out"  class="za_select">
    <option value="in" <?php if ($_GET['out'] == "in") echo "selected" ?>>入款</option>
	<option value="out" <?php if ($_GET['out'] == "out") echo "selected" ?>>出款</option>
</select>
<br />
帐号查询：
<select name="level" id="level"  class="za_select">
	<option value="1" <?php if ($_GET['level'] == 1) echo "selected" ?>>会员</option>
	<option value="2" <?php if ($_GET['level'] == 2) echo "selected" ?>>代理</option>
    <!--
	<option value="3">总代理</option>    
    <option value="4">股东</option>
    -->
</select>
<input type="text" name="name" id="name" value="<?=$_GET['name'];?>"  class="za_text"  style="min-width:80px;width:100px;">
&nbsp;金额：
<input type="text" name="money" id="money"  value="<?=$_GET['money'];?>"  class="za_text"  style="min-width:80px;width:100px;">
<select name="money_opt" id="money_opt"  class="za_select">
	<option value="0">以上</option>
	<option value="1" <?php if ($_GET['money_opt'] == 1) echo "selected" ?>>以下</option>    
</select>
&nbsp;次数：
<input type="text" name="amount" id="amount"  value="<?=$_GET['amount'];?>"  class="za_text"  style="min-width:80px;width:100px;">
<select name="amount_opt" id="amount_opt"  class="za_select">
	<option value="0">以上</option>
	<option value="1" <?php if ($_GET['amount_opt'] == 1) echo "selected" ?>>以下</option>    
</select>
&nbsp;排序：
<select name="order_assort" id="order_assort"  class="za_select">
    <option value="count" <?php if ($_GET['order_assort'] == '"count"') echo "selected" ?>>按次数排序</option>
    <option value="money" <?php if ($_GET['order_assort'] == 'money') echo "selected" ?>>按金额排序</option>
</select>
<select name="order" id="order"  class="za_select">
	<option value="DESC" <?php if ($_GET['order'] == 'DESC') echo "selected" ?>>递减</option>
	<option value="ASC" <?php if ($_GET['order'] == 'ASC') echo "selected" ?>>递增</option>
</select>
<input type="hidden" id="excel" name="excel" value="false">
<input  type="button" value="搜索" class="za_button" onClick="sel();"> 
<input  type="button" value="导出Excel" class="za_button" onClick="getExcel();">
</form>
</div>
</div>
<div  class="content">
	<table  width="1024"  border="0"  cellspacing="0"  cellpadding="0"  bgcolor="#E3D46E"  class="m_tab">
		<tbody><tr  class="m_title_over_co">
			<td>NO</td>
			<td>代理商</td>
			<td><?php if($_GET['level'] == 1 || $model){ echo "会员账号"; }else{ echo "会员总数"; } ?></td>
			<?php if($_GET['out'] == "in"){ ?>
			<td>入款金额</td>
			<td>入款次数</td>
			<?php }else{ ?>
			<td>出款金额</td>
			<td>出款次数</td>
			<?php } ?>
		</tr>
		<?
		if($_GET['level'] == 2 && !$model){
    		foreach($agent_arr as $k=>$v){?>
    		<tr  class="m_cen">
    			<td><?=$k+1?></td>
    			<td><a href="?name=<?=$v['agent_user']?>&level=2&out=<?=$_GET['out']?>&start_date=<?=$_GET['start_date']?>"><?=$v['agent_user']?></a></td>
    			<td><?php if($_GET['level'] == 2 && $_GET['name']){ echo $v['username'];}else{echo getUserNum($v['agent_user']);} ?></td>
    			<td><?=$v['money']?></td>
    			<td><?=$v['count']?></td>
    		</tr>
    		<?}
		  }elseif($_GET['level'] == 1 || $model){
		    foreach($agent_arr as $k=>$v){?>
		    		<tr  class="m_cen">
		    			<td><?=$k+1?></td>
		    			<td><a href="?name=<?=$v['agent_user']?>&level=2&out=<?=$_GET['out']?>&start_date=<?=$_GET['start_date']?>"><?=$v['agent_user']?></a></td>
		    			<td><a href="?name=<?=$v['username']?>&level=1&out=<?=$_GET['out']?>&start_date=<?=$_GET['start_date']?>"><?=$v['username']?></a></td>
		    			<td><?=$v['money']?></td>
                        <td><?=$v['count']?></td>
		    		</tr>
		<?php }
		  }else{?>
           <tr align="center">
			<td  class="table_bg1" height="27" colspan="15">暂无数据</td>
		   </tr>
		<?php }?>				
		
	</tbody></table>
</div>
<?php require("../common_html/footer.php");?>

<?php 
include_once("../../include/config.php");
include_once("../common/login_check.php");

//银行卡存款状况
//into_style 1表示公司入款 2表示线上入款

$bank_record=M('k_user_bank_in_record',$db_config);

//停用金额
$stop_amount=$_GET['stop_amount'];

$map = "site_id = '".SITEID."' and pay_id='".$_GET['id']."' and into_style = '2' and make_sure=1";




//订单号查询
if(!empty($_GET['order'])){
	$map .=" and order_num like '%".$_GET['order']."%'";
}
//日期查询（默认当天）
if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
   $start_date = $_GET['start_date'];
   $end_date = $_GET['end_date'];
   $map .= " and log_time > '".$_GET['start_date']." 00:00:00' and log_time < '".$_GET['end_date']." 23:59:59' ";
}elseif (!empty($_GET['start_date'])) {
   $start_date = $_GET['start_date'];
   $map .= " and log_time > '".$_GET['start_date']." 00:00:00' ";

}elseif (!empty($_GET['end_date'])) {
  $end_date = $_GET['end_date'];
   $map .= " and log_time < '".$_GET['end_date']." 23:59:59' ";
}else{
   $start_date = $end_date = date('Y-m-d');
   $map .= " and log_time like '".date('Y-m-d')."%' ";
}
//获得记录总数
$sum=$bank_record->where($map)->count();
$sum1=$bank_record->where("site_id = '".SITEID."' and pay_id=".$_GET['id']." and into_style = '2' and make_sure=1")->count();
$sum2=$bank_record->field("sum(deposit_num)")->where("site_id = '".SITEID."' and pay_id=".$_GET['id']." and into_style = '2' and make_sure=1")->select();

//分页
$CurrentPage=isset($_GET['page'])?$_GET['page']:1;
$pagenum=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
$start  = ($CurrentPage-1)*$pagenum;

$totalPage=ceil($sum/$pagenum); //计算出总页数
if($totalPage<$CurrentPage){
  $CurrentPage = 1;
}
$page = $bank_record->showPage($totalPage,$CurrentPage);
// echo $map;
$data=$bank_record->where($map)->limit("$start,$pagenum")->order('log_time DESC')->select();

//当天存款笔数
$count=count($data);
//当天存款总金额
$total=$bank_record->where($map)->field('sum(deposit_num) as total_deposit_num')->limit("$start,$pagenum")->find(); 

//最后存款日
$date=$bank_record->where($map)->order('log_time DESC')->find();
?>
<?php require("../common_html/header.php");?>
<body>
<script>
	window.onload=function(){
		document.getElementById("page").onchange=function(){
			document.getElementById('myFORM').submit()
		}
	}
</script>
<div id="con_wrap">
  <div class="input_002">存款狀況</div>
  <div class="con_menu">
    <form name="myFORM" id="myFORM" action="" method="GET">
    	<input type="button" value="返回上一頁" onclick="window.location.href='pay_set.php'" class="button_e">
    	<input type="hidden" name="id" value="<?=$_GET['id']?>"  class="button_d">
	  	<input type="hidden" name="stop_amount" value="<?=$stop_amount?>">
      訂單號：
      <input class="za_text" size="10" name="order" value="<?=$_GET['order'] ?>">
      &nbsp;
      日期：
      <input class="za_text Wdate" onClick="WdatePicker()" value="<?=$start_date?>" style="min-width:80px;width:80px;" name="start_date" >
      ~
      <input class="za_text  Wdate" onClick="WdatePicker()" value="<?=$end_date?>" style="min-width:80px;width:80px;" name="end_date">
      
      <input class="button_d" value="查詢" type="submit">
      每页记录数：
  <select name="page_num" id="page_num" onchange="document.getElementById('queryform').submit()" class="za_select">
    <option value="20" <?=select_check(20,$pagenum)?>>20条</option>
    <option value="30" <?=select_check(30,$pagenum)?>>30条</option>
    <option value="50" <?=select_check(50,$pagenum)?>>50条</option>
    <option value="100" <?=select_check(100,$pagenum)?>>100条</option>
  </select>
&nbsp;<?=$page?> &nbsp;
    </form>
  </div>
</div>
<table width="99%" class="m_tab">
  <tbody><tr class="m_title">
    <td class="table_bg" colspan="8">存款統計</td>   
  </tr>
  <tr align="center">
  	<td class="table_bg1">存款筆數</td><td style="text-align:left;width:15%"><?=$count?> 筆 &nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;  <?=$sum1+0 ?>筆</td>
  	<td class="table_bg1">累計存款金</td><td style="text-align:left;width:15%">
	<?=$total['total_deposit_num']+0?>元 &nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp; <?=$sum2[0]["sum(deposit_num)"]+0 ?>元
	</td>
    <td class="table_bg1">最後存款日</td><td style="text-align:left;width:15%"><?=$date['log_time']?></td>
    <td class="table_bg1">当日支付限额</td><td style="text-align:left;width:15%">	
	<?=$stop_amount+0?>元</td>	
</tr>
</tbody></table><br>
<table width="99%" class="m_tab">
  <tbody>
  <tr class="m_title">
    <td class="table_bg">序號</td>
    <td class="table_bg">訂單號</td>
    <td class="table_bg">存款金額/元</td>
    <td class="table_bg">存款日期</td>
    <td class="table_bg">會員帳號</td>
    <td class="table_bg">備註</td> 
  </tr>
  <? 
  if (!empty($data)) {
  foreach($data as $k=>$v){
  ?>
   <tr class="m_cen">
    <td class="table_bg"><?=$v['id']?></td>
	<td class="table_bg"><?=$v['order_num']?></td>
	<td class="table_bg"><?=$v['deposit_num']?></td>
	<td class="table_bg"><?=$v['log_time']?></td>
	<td class="table_bg"><?=$v['username']?></td>
	<td class="table_bg"><?=$v['in_info']?></td> 
   </tr>
   <?} }?>
   <tr class="m_cen">
    <td class="table_bg">合計</td>
	<td class="table_bg"></td>
	<td class="table_bg"><?=$total['total_deposit_num']?></td>
	<td class="table_bg"></td>
	<td class="table_bg"></td>
	<td class="table_bg"></td> 
  </tr>
  
</tbody></table>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>
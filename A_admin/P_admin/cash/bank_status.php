<?php 
include_once("../../include/config.php");
include_once("../common/login_check.php");
//银行卡存款状况
//into_style 1表示公司入款 2表示线上入款
//
$Brecord=M('k_user_bank_in_record',$db_config);
$mapB = array();
$mapB['site_id'] = SITEID;
$mapB['into_style'] = 1;
$mapB['make_sure'] = 1;

if ($_GET['id'] == 'all') {
   //所有银行卡订单
}elseif (!empty($_GET['id'])) {
   $mapB['bid'] = $_GET['id'];
   $stop_amount=$_GET['stop_amount'];
}

//订单号查询
if(!empty($_GET['order'])){
  $strO = '%'.$_GET['order'].'%';
  $mapB['order_num'] = array('like',$strO);
}
//日期查询（默认当天）
if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
   $start_date = $_GET['start_date'];
   $end_date = $_GET['end_date'];
   $tmpSdate = $_GET['start_date'].' 00:00:00';
   $tmpEdate = $_GET['end_date'].' 23:59:59';
}else{
   $start_date = $end_date = date('Y-m-d');
}

$tmpSdate = $start_date.' 00:00:00';
$tmpEdate = $end_date.' 23:59:59';
$mapB['log_time'] = array(
                      array('>=',$tmpSdate),
                      array('<=',$tmpEdate)
                    );
//获得记录总数
$countN = $Brecord ->where($mapB)->count();
$sumN = $Brecord->field("sum(deposit_num) as sumN")
        ->where($mapB)->getField('sumN');
//分页
$CurrentPage=isset($_GET['page'])?$_GET['page']:1;
$pagenum=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
$start  = ($CurrentPage-1)*$pagenum;

$totalPage=ceil($countN/$pagenum); //计算出总页数
if($totalPage<$CurrentPage){
  $CurrentPage = 1;
}

$limit=$start.",".$pagenum;
$dataRe = $Brecord->where($mapB)->limit($limit)
        ->order('log_time DESC')->select();
$page = $Brecord->showPage($totalPage,$CurrentPage);



//总笔数
unset($mapB['log_time']);
$countA =$Brecord->where($mapB)->count();
$sumA = $Brecord->field("sum(deposit_num) as sumA")
        ->where($mapB)->getField('sumA');

//最后存款日
$dateL=$Brecord->where($mapB)->field('log_time')->order('log_time DESC')
      ->find();

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
    	<input type="button" value="返回上一頁" onclick="javascript:history.go(-1)" class="button_e">
    	<input type="hidden" name="id" value="<?=$_GET['id']?>"  class="button_d">
		<input type="hidden" name="stop_amount" value="<?=$_GET['stop_amount']?>">
      訂單號：
      <input class="za_text" name="order" value="<?=$_GET['order'] ?>">
      &nbsp;
      日期：
      <input class="za_text Wdate" onclick="WdatePicker();" value="<?=$start_date?>" style="min-width:80px;width:80px;" name="start_date" >
      ~
      <input class="za_text  Wdate" onclick="WdatePicker();" value="<?=$end_date?>" style="min-width:80px;width:80px;" name="end_date">
      
      <input class="button_d" value="查詢" type="submit">
      每页记录数：
  <select name="page_num" id="page_num" onchange="document.getElementById('myFORM').submit()" class="za_select">
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
  	<td class="table_bg1">筆數&nbsp;/&nbsp;总筆數</td><td style="text-align:left;width:15%"><?=$countN+0?> 筆 &nbsp;&nbsp;/&nbsp;&nbsp; <?=$countA+0 ?>筆</td>
  	<td class="table_bg1">存款&nbsp;/&nbsp;总存款</td><td style="text-align:left;width:15%">
	<?=$sumN+0?>元 &nbsp;&nbsp;/&nbsp;&nbsp; <?=$sumA+0 ?>元
	</td>
    <td class="table_bg1">最後存款日</td><td style="text-align:left;width:15%"><?=$dateL['log_time']?></td>
    <td class="table_bg1">停用金額</td><td style="text-align:left;width:15%">	
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
  if (!empty($dataRe)) {
  foreach($dataRe as $k=>$v){
     $allNum += $v['deposit_num'];
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
    <td class="table_bg" colspan="2">合計</td>

	<td class="table_bg"><?=$allNum?></td>
	<td class="table_bg"></td>
	<td class="table_bg"></td>
	<td class="table_bg"></td> 
  </tr>
  
</tbody></table>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>
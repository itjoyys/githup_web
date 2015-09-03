<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");

$map_date['site_id'] = SITEID;
if (!empty($_GET['month'])) {
	$month_s = $_GET['month'];
	$month_g = str_pad('0',2,$_GET['month'],STR_PAD_RIGHT);
    $map_date['event'] = array('like',date('Y').'-'.$month_g.'%');
}else{
	$month_s = date('n');
	$map_date['event'] = array('like',date('Y-m').'%');
}

$data = M('k_user_discount_search',$db_config)
      ->where($map_date)->order("id desc")->select();
?>


<?php $title="优惠查询"; require("../common_html/header.php");?>

<body>
<script>
    //分页跳转
  window.onload=function(){
    document.getElementById("month").onchange=function(){
      document.getElementById('myFORM').submit();
    }
  }
</script>
<div id="con_wrap">
  <div class="input_002">優惠查詢</div>
  <div class="con_menu">
   <a  href="discount_index.php">優惠統計</a>
  <a  href="discount_search.php" style="color:red;">優惠查詢</a>
  <a  href="discount_set.php">返點優惠設定</a>
  <a  href="reg_discount_set.php">申請會員優惠設定</a>
  </div>
</div>
<div class="content">
<form name="myFORM" id="myFORM" method="get">
<table border="0" style="margin-left: 95px;">
	<tbody><tr>
    	<td>
    月份選擇：
        <select name="month" id="month" class="za_select">
            <?php for ($i= date('n'); $i >= 1 ; $i--) {  ?>
            <option value="<?=$i?>" <?=select_check($i,$month_s)?>><?=$i.' 月'?></option>
            <?php }?>
        </select>
</td>
    </tr>
</tbody></table>
</form>
<form method="post" name="myFORM">
<input type="hidden" id="userid" name="userid" value=""> 
<input type="hidden" name="username" value="">
<table width="99%" class="m_tab">        
	<tbody><tr class="m_title">
		<td colspan="11" height="25" align="center">優惠查詢</td>
	</tr>
	
	<tr class="m_title">
		<td>編號</td>
		<td>事件</td>
		<td>返水區間(起)</td>
		<td>返水區間(迄)</td>
		<td>人數/金額</td>
		<td>創建日期</td>
		<td>創建者</td>
		<td>综合打码倍数</td>
		<td>查詢明細</td>
	</tr>
<?php foreach($data as $k=>$v){?>
	<tr class="m_cen">
		<td align="center"><?=$v['id']?></td>
		<td align="center"><?=$v['event']?></td>
		<td align="center"><?=$v['back_time_start']?></td>
		<td align="center"><?=$v['back_time_end']?></td>
		<td align="center"><?=($v['people_num']-$v['no_people_num'])?> / <?=$v['money']?></td>
		<td align="center"><?=$v['addtime']?></td>
		<td align="center"><?=$v['admin_user']?></td>
		<td align="center"><?=$v['bet']?></td>
		<td align="center"><a href="discount_count_detail.php?id=<?=$v['id']?>">明細</a></td>
	</tr>
<?php }?>
	

	</tbody></table> 
</form>
</div>
<?php require("../common_html/footer.php");?>
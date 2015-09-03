<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");

$bank_m = M('k_bank',$db_config);
$level_u = M('k_user_level',$db_config);
$map = array();
$map['site_id'] = SITEID;

//类别选择
if (!empty($_GET['cate'])) {
	if($_GET['cate']!='all'){
		$map['cate'] = $_GET['cate'];
	}		 
}

//删除 操作
switch ($_GET['act']) {
  case 'de':
    $dataB['is_delete'] = 1;
    $state = M('k_bank',$db_config)->where("id = '".$_GET['id']."'")
           ->update($dataB);
    $log = "删除银行卡:".$_GET['cid'];
    admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$log);   
    message("删除银行卡成功");
    break;
}

//状态条件
if($_GET['status'] == 'off'){
   $map['is_delete'] = '2';
}else{
   $map['is_delete'] = '0';
}

//银行
if(!empty($_GET['bank_type'])){
  $map['bank_type'] = $_GET['bank_type'];
}

//卡号
if (!empty($_GET['card_id'])) {
   $card_str = '%'.$_GET['card_id'].'%';
   $map['card_ID'] = array('like',$card_str);
}

$count = $bank_m->where($map)->count();

//分页
$pageN = 20;
$CurrentPage=isset($_GET['page'])?$_GET['page']:1;
$start  = ($CurrentPage-1)*$pageN;

$limit=$start.",".$pageN;
$totalPage=ceil($count/$pageN); //计算出总页数

$bank = $bank_m->where($map)->limit($limit)->select();
$page = $bank_m->showPage($totalPage,$page);
//银行卡层级信息

foreach ($bank as $k =>$v){
    $level = '';

    foreach (explode(',', $v['level_id']) as $j=>$s) {
      
        $level_bank=$level_u->field('level_des')->where('id='.$s.' and site_id=\''.SITEID.'\'')->find();
        //echo $level_u->getLastSql();
        if ($j%2!= 0) {
            $level .=$level_bank['level_des']."<br>";
        }else{
            $level .=$level_bank['level_des'].', ';
        }
        
    }
    $bank[$k]['level_des'] = $level;
}


?>
<?php $title="銀行設定"; require("../common_html/header.php");?>

<body>
<div  id="con_wrap">
  <div  class="input_002">銀行設定</div>
  <div  class="con_menu">
    <form name="myFORM" id="myFORM" action="" method="GET">
       <input onclick="document.location='./bank_status.php?id=all'" type="button" value="訂單号查询" class="button_b">
     类别选择：
     <select  name="cate" id="cate" onchange="document.getElementById('myFORM').submit()" class="za_select">
          <option value="all" <?=$_GET["cate"]==all ? 'selected' : ''?>>全部</option>
          <option value="A" <?=$_GET["cate"]==A ? 'selected' : ''?>>A类</option>
          <option value="B" <?=$_GET["cate"]==B ? 'selected' : ''?>>B类</option>
          <option value="C" <?=$_GET["cate"]==C ? 'selected' : ''?>>C类</option>
          <option value="D" <?=$_GET["cate"]==D ? 'selected' : ''?>>D类</option>
          <option value="E" <?=$_GET["cate"]==E ? 'selected' : ''?>>E类</option>
     </select>

      状态：
       <select  name="status" onchange="document.getElementById('myFORM').submit()" id="status" class="za_select">
           <option value="on" <?=$_GET["status"]=='on' ? 'selected' : ''?>>启用</option>
           <option value="off" <?=$_GET["status"]=='off' ? 'selected' : ''?>>停用</option>
        
      </select>
      <select name="bank_type" onchange="document.getElementById('myFORM').submit()"  id="bank_type" class="za_select"> 
            <option value="0" >全部</option>
            <option value="1" <?php select_check($_GET['bank_type'],'1');?>>中國銀行</option>
            <option value="2" <?php select_check($_GET['bank_type'],'2');?>>中國工商銀行</option>
            <option value="3" <?php select_check($_GET['bank_type'],'3');?>>中國建設銀行</option>
            <option value="4" <?php select_check($_GET['bank_type'],'4');?>>中國招商銀行</option>
            <option value="5" <?php select_check($_GET['bank_type'],'5');?>>中國民生銀行</option>
            <option value="7" <?php select_check($_GET['bank_type'],'7');?>>中國交通銀行</option>
            <option value="8" <?php select_check($_GET['bank_type'],'8');?>>中國郵政銀行</option>
            <option value="9" <?php select_check($_GET['bank_type'],'9');?>>中國农业銀行</option>
            <option value="10" <?php select_check($_GET['bank_type'],'10');?>>華夏銀行</option>
            <option value="11" <?php select_check($_GET['bank_type'],'11');?>>浦發銀行</option>
            <option value="12" <?php select_check($_GET['bank_type'],'12');?>>廣州銀行</option>
            <option value="13" <?php select_check($_GET['bank_type'],'13');?>>北京銀行</option>
            <option value="14" <?php select_check($_GET['bank_type'],'14');?>>平安銀行</option>
            <option value="15" <?php select_check($_GET['bank_type'],'15');?>>杭州銀行</option>
            <option value="16" <?php select_check($_GET['bank_type'],'16');?>>溫州銀行</option>
            <option value="17" <?php select_check($_GET['bank_type'],'17');?>>中國光大銀行</option>
            <option value="18" <?php select_check($_GET['bank_type'],'18');?>>中信銀行</option>
            <option value="19" <?php select_check($_GET['bank_type'],'19');?>>浙商銀行</option>
            <option value="20" <?php select_check($_GET['bank_type'],'20');?>>漢口銀行</option>
            <option value="21" <?php select_check($_GET['bank_type'],'21');?>>上海銀行</option>
            <option value="22" <?php select_check($_GET['bank_type'],'22');?>>廣發銀行</option>
            <option value="23" <?php select_check($_GET['bank_type'],'23');?>>农村信用社</option>
            <option value="24" <?php select_check($_GET['bank_type'],'24');?>>深圳发展银行</option>
            <option value="25" <?php select_check($_GET['bank_type'],'25');?>>渤海银行</option>
            <option value="26" <?php select_check($_GET['bank_type'],'26');?>>东莞银行</option>
            <option value="27" <?php select_check($_GET['bank_type'],'27');?>>宁波银行</option>
            <option value="28" <?php select_check($_GET['bank_type'],'28');?>>东亚银行</option>
            <option value="29" <?php select_check($_GET['bank_type'],'29');?>>晋商银行</option>
            <option value="30" <?php select_check($_GET['bank_type'],'30');?>>南京银行</option>
            <option value="31" <?php select_check($_GET['bank_type'],'31');?>>广州农商银行</option>
            <option value="32" <?php select_check($_GET['bank_type'],'32');?>>上海农商银行</option>
            <option value="33" <?php select_check($_GET['bank_type'],'33');?>>珠海农村信用合作联社</option>
            <option value="34" <?php select_check($_GET['bank_type'],'34');?>>顺德农商银行</option>
            <option value="35" <?php select_check($_GET['bank_type'],'35');?>>尧都区农村信用联社</option>
            <option value="36" <?php select_check($_GET['bank_type'],'36');?>>浙江稠州商业银行</option>
            <option value="37" <?php select_check($_GET['bank_type'],'37');?>>北京农商银行</option>
            <option value="38" <?php select_check($_GET['bank_type'],'38');?>>重庆银行</option>
            <option value="39" <?php select_check($_GET['bank_type'],'39');?>>广西农村信用社</option>
            <option value="40" <?php select_check($_GET['bank_type'],'40');?>>江苏银行</option>
            <option value="41" <?php select_check($_GET['bank_type'],'41');?>>吉林银行</option>
            <option value="42" <?php select_check($_GET['bank_type'],'42');?>>成都银行</option>
            <option value="50" <?php select_check($_GET['bank_type'],'50');?>>兴业银行</option>
            <option value="100" <?php select_check($_GET['bank_type'],'100');?>>支付宝</option>
			<option value="101" <?php select_check($_GET['bank_type'],'101');?>>微信支付</option>
			<option value="102" <?php select_check($_GET['bank_type'],'102');?>>财付通</option>
          </select>&nbsp;<?=$page?> &nbsp;帳號：
          <input name="card_id" class="za_text" type="text" id="card_id" value="<?=$_GET['card_id']?>" size="20" maxlength="20"/> 
      <input type="submit" name="buname" value="查询" class="za_button">
      <a name="add" style="padding: 0.45em .8em .5em;" onclick="document.location='./pay_bank_set_add.php?type=add'" class="button_c">新增银行卡</a>
      </form>
	  
    
  </div>
</div>

<table width="100%" class="m_tab" border="1" cellspacing="0" cellpadding="0">
	<tr class="m_title_over_co"   align="center">
	        <td width="30" height="20">ID</td>
       <td width="120" height="20">銀行類型</td>
        <td width="20%" height="20">開戶行</td>
        <td width="20%" height="20">帳號信息</td>
        <td width="150" height="20">层级信息</td>
        <td width="40" height="20">狀態</td>
        <td height="20">備注</td>
        <td width="250" height="20">操作</td>
    </tr>
	<? if (!empty($bank)) {
  foreach($bank as $k=> $v){?>
      <tr style="background-color:#FFFFFF;color:#000;">
      <td align="center"><?=$v['id']?></td>
      <td align="center"><?=bank_type($v['bank_type'])?></td>
      <td width="10%" align="center"><?=$v['card_address']?></td>
      <td width="10%" align="center">
            帳號：<?=$v['card_ID']?><br>
      收款人：<?=$v['card_userName']?><br>
      停用金額：<?=$v['stop_amount']?>元
     </td>
     <td width="10%" align="center">
		   <?=$v['level_des']?>
    </td>
		<td align="center"><?php if ($v['is_delete'] == '2') {
           echo "<span style=\"color:#FF00FF;\">停用</span>";
         }else{
           echo "<span style=\"color:##1E20CA;\">正常</span>";
         } ?></td>
    
    <td align="center"><?=$v['remark']?></td>
		<td align="center">
        <input onclick="document.location='pay_bank_set_add.php?type=edit&id=<?=$v['id']?>'" type="button" value="修改" class="button_b">
        <input onclick="document.location='bank_status.php?id=<?=$v['id']?>&stop_amount=<?=$v['stop_amount']?>'" type="button" value="存款狀況" class="button_b">
        <input onclick="if(confirm('是否要刪除？'))document.location='pay_bank_set.php?act=de&id=<?=$v['id']?>&cid=<?=$v['card_ID']?>';" type="button" value="刪除" class="button_d">
  
</td>
		</tr>
	<?}}else{
    ?>
    <tr><td colspan="10" align="center" height="35">暂无绑定银行卡</td></tr>
  <?php }?>

</table>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>

<?
function getLevel($arr,$id){
	$str="";
	foreach ($arr as $key => $val) {
	$result=strpos($val['bank_set'],$id);
		if ($result!==false) {
        if ($key%2 == 1) {
           $str .=$val['level_des'].'<br>'; 
        }else{
           $str .=$val['level_des'].'  ';
        }		
      }		
	}
	//$str=rtrim($str,',');
	return $str;
}
?>


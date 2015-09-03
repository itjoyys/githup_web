<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");

//删除操作
if($_GET['action']=='d'){	
	$condition['is_delete']=1;
	$delId = M('k_user_discount_set',$db_config)->where("id=".$_GET['id'])->update($condition);
    if ($delId) {
    	$do_log = $_SESSION['login_name'].'删除了返點優惠設定'.$delId;
        admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
    }    
}

$map['site_id']=SITEID;
$map['is_delete']=0;
$data=M('k_user_discount_set',$db_config)->where($map)->order('id desc')->select();
?>


<?php require("../common_html/header.php");?>
<body>
<div id="con_wrap">
  <div class="input_002">返點優惠設定</div>
  <div class="con_menu">
    <a  href="discount_index.php">優惠統計</a>
  <a  href="discount_search.php">優惠查詢</a>
  <a href="discount_set.php" style="color:red;">返點優惠設定</a>
  <a  href="reg_discount_set.php" >申請會員優惠設定</a>
  <a href="discount_edit.php">新增</a>
  </div>
</div>
<div class="content">
<form method="post" name="myFORM">
<input type="hidden" id="userid" name="userid" value=""> 
<input type="hidden" name="username" value="">
<table width="99%" class="m_tab">        
	<tbody><tr class="m_title">
		<td>ID</td>
		<td>有效總投注</td>
		<td>彩票優惠</td>
		<td>體育優惠</td>
        <td>MG視訊優惠</td>
        <td>MG电子優惠</td>
        <td>BBIN視訊優惠</td>
        <td>BBIN电子優惠</td>
        <td>LEBO視訊優惠</td>
        <td>AG視訊優惠</td>
        <td>OG視訊優惠</td>
        <td>CT視訊優惠</td>
		<td>優惠上限</td>
		<td>功能</td>
	</tr>
	<?php foreach ($data as $k=>$v){?>
	<tr class="m_cen">
		<td align="center"><?=$v['id']?></td>
		<td align="center"><?=$v['count_bet']?></td>
		<td align="center"><?=$v['fc_discount']?>%</td>		
		<td align="center"><?=$v['sp_discount']?> %</td>
        <td align="center"><?=$v['mg_discount']?> %</td>
        <td align="center"><?=$v['mgdz_discount']?> %</td>
        <td align="center"><?=$v['bbin_discount']?> %</td>
        <td align="center"><?=$v['bbdz_discount']?> %</td>
        <td align="center"><?=$v['lebo_discount']?> %</td>
        <td align="center"><?=$v['ag_discount']?> %</td>
        <td align="center"><?=$v['og_discount']?> %</td>
        <td align="center"><?=$v['ct_discount']?> %</td>
		<td align="center"><?=$v['max_discount']?></td>
		<td align="center">
		    <input type="button" name="append" value="修改" onclick="document.location='discount_edit.php?id=<?=$v['id']?>'" class="za_button">
		    <input type="button" onclick="if(confirm('是否要刪除此優惠設定？')) document.location='./discount_set.php?id=<?=$v['id']?>&action=d'" name="append" value="刪除" class="za_button">
		</td>
	</tr>
	<?php }?>
	</tbody></table> 
</form>
</div>
<?php require("../common_html/footer.php");?>
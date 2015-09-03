<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");	
include_once("../common/user_set.php");
/**
* 过滤html代码
**/
function htmlEncode($string) { 
	$string=trim($string); 
	$string=str_replace("\'","'",$string); 
	$string=str_replace("&amp;","&",$string); 
	$string=str_replace("&quot;","\"",$string); 
	$string=str_replace("&lt;","<",$string); 
	$string=str_replace("&gt;",">",$string); 
	$string=str_replace("&nbsp;"," ",$string); 
	$string=nl2br($string); 
	//$string=mysql_real_escape_string($string);
	return $string;
}
//添加支付方式
if(empty($_POST['id']) && !empty($_POST['pay_key'])){
	$sql    =   "insert into pay_set(pay_domain,pay_id,pay_key,pay_type,f_url,money_limits,money_Already,order_id,b_start,money_Lowest) values('".htmlEncode($_POST['pay_domain'])."','".htmlEncode($_POST['pay_id'])."','".htmlEncode($_POST['pay_key'])."','".$_POST['pay_type']."','".htmlEncode($_POST['f_url'])."','".$_POST['money_limits']."','".$_POST['money_Already']."','".$_POST['order_id']."','".$_POST['b_start']."','".$_POST['money_Lowest']."')";
	$mysqlt->autocommit(FALSE);
	$mysqlt->query("BEGIN"); //事务开始
	try{
		$mysqlt->query($sql);
		$q1		=	$mysqlt->affected_rows;
		if($q1 == 1){
			//include_once("../../class/admin.php");
		//	admin::insert_log($_SESSION["adminid"],$_SESSION['login_name'],"添加了支付参数配置");
			$mysqlt->commit(); //事务提交
			message('添加支付方式成功','pay_set.php');
		}else{
			$mysqlt->rollback(); //数据回滚
			message('添加支付方式失败','pay_set.php');
		}
	}catch(Exception $e){
		$mysqlt->rollback(); //数据回滚
		message('添加支付方式失败','pay_set.php');
	}
			
}

//更新支付方式
if(!empty($_POST['id'])){
	$sql    =   "update pay_set set pay_domain='".htmlEncode($_POST['pay_domain'])."',pay_id='".htmlEncode($_POST['pay_id'])."',pay_key='".htmlEncode($_POST['pay_key'])."',pay_type='".htmlEncode($_POST['pay_type'])."',f_url='".htmlEncode($_POST['f_url'])."',money_limits='".htmlEncode($_POST['money_limits'])."',money_Already='".htmlEncode($_POST['money_Already'])."',order_id='".htmlEncode($_POST['order_id'])."',money_Lowest='".htmlEncode($_POST['money_Lowest'])."' where id='".intval($_POST['id'])."'";
	$mysqlt->autocommit(FALSE);
	$mysqlt->query("BEGIN"); //事务开始
	try{
		$mysqlt->query($sql);
		$q1		=	$mysqlt->affected_rows;
		if($q1 == 1){
			include_once("../../class/admin.php");
			admin::insert_log($_SESSION["adminid"],"修改了支付参数配置");
			$mysqlt->commit(); //事务提交
			message('修改支付方式成功','pay_set.php');
		}else{
			$mysqlt->rollback(); //数据回滚
			message('修改支付方式失败','pay_set.php');
		}
	}catch(Exception $e){
		$mysqlt->rollback(); //数据回滚
		message('修改支付方式失败','pay_set.php');
	}
			
}

//启用
if(@$_GET["action"]=="open"){
	$sql="update pay_set set is_delete='0' where id='".intval($_GET['id'])."'";
	$mysqlt->query($sql);
}
//停用
if(@$_GET["action"]=="close"){
	$sql="update pay_set set is_delete='1' where id='".intval($_GET['id'])."'";
	$mysqlt->query($sql);
	message('删除支付方式成功','pay_set.php');
}

//删除
if(@$_GET["action"]=="del"){
    $sql="update pay_set set is_delete='9' where id='".intval($_GET['id'])."'";
    $mysqlt->query($sql);
    message('删除支付方式成功','pay_set.php');
}

if(@$_GET["action"]=="clear"){
	$sql="update pay_set set money_Already='0' where id='".intval($_GET['id'])."'";
	$mysqlt->query($sql);
}



$sql	=	"select * from pay_set where site_id = '".SITEID."' and is_delete!=9 order by order_id asc";
$query	=	$mysqlt->query($sql);
?>
<?php $title="支付平臺設定"; require("../common_html/header.php");?>
<style type="text/css"> 
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	font-size: 12px;
}
</style> 
</HEAD> 
<body>
<div  id="con_wrap">
  <div  class="input_002">支付平臺設定</div>
  <div  class="con_menu"> 
      <a href="./pay_bank_set.php">銀行設定</a>
      <a href="./pay_set.php" style="color:red;">支付平臺設定</a>
    
      <input type="button" value="新增" onclick="window.location.href='./pay_set_add.php'" class="button_e">
  </div>
</div>

<table width="100%" class="m_tab" border="1" bordercolor="#96B697" cellspacing="0" cellpadding="0">
	<tr class="m_title_over_co"   align="center">
        <td width="3%" height="20">id</td>
        <td width="3%" height="20">平台</td>	
        <td width="10%" height="20">商户id</td>
		<td width="5%" height="20">当日支付限额</td>
		<td width="5%" height="20">层级</td>
		<td width="5%" height="20">当日已支付</td> 
		<!-- <td width="5%" height="20">最低充值</td> -->
		<td width="5%" height="20">状态</td>
		<td width="10%" height="20">操作</td>
    </tr>
    
    
    <?
	while($row = $query->fetch_array()){
	?>
		<?php 
			$table = M('k_user_bank_in_record',$db_config);
            $resulet = $table
             ->field('sum(deposit_num) as money')->where("site_id = '".SITEID."' and pay_id = '".$row['id']."' and into_style = 2 and make_sure = 1 and do_time like '%".date('Y-m-d')."%'")->find();
            $money = !empty($resulet['money'])?$resulet['money']:0;

		?>
    	<tr>
        <td width="3%" align="center"><?=$row['id']?></td>
        <td width="3%" align="center"><?=payment_type($row['pay_type']) ?></td>
		<td width="10%" align="center"><?=$row['pay_id']?></td>
		<td width="5%" align="center"><?=$row['money_limits']?></td>
	   <td width="10%" align="center"><?php 
	   $level_u = M('k_user_level',$db_config);
	       $level = '';
	       foreach (explode(',', $row['level_id']) as $j=>$s) {
	          if($s){
	           $level_bank[]=$level_u->field('level_des')->where('id='.$s)->find();

	             if (($j+1)%3 == 0) {
			            $level .=$level_bank[$j]['level_des']."<br>";
			        }else{
			            $level .=$level_bank[$j]['level_des'].', ';
			        }

	          }
	       }
	       echo $level;
	       $level_bank='';
        ?></td>
		<!-- <td width="5%" align="center"><?=$row['money_Already']?></td> -->
		<td width="5%" align="center"><?=$money?></td>
	    <td width="5%" align="center"><?php if ($row['is_delete'] == 1) {
           echo "<span style=\"color:#FF00FF;\">停用</span>";
         }else{
           echo "<span style=\"color:##1E20CA;\">正常</span>";
         } ?></td>

		<td width="15%" align="center">
           <input onclick="document.location='pay_set_add.php?action=mod&id=<?=$row['id']?>'" type="button" value="修改" class="button_b">
        <input onclick="document.location='pay_status.php?id=<?=$row['id']?>&stop_amount=<?=$row['money_limits']?>'" type="button" value="存款狀況" class="button_b">
		<input onclick="if(confirm('是否要删除？'))document.location='pay_set.php?action=del&id=<?=$row['id']?>';" type="button" value="删除" class="button_d">

		</td>
		
		</tr>
     <?
	
	}
	 ?>
</table>

<br/>

<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>
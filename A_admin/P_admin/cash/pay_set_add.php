<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once("../common/user_set.php");

// var_dump($_GET);

//读取信息
if(!empty($_GET['id'])){
	$data = M("pay_set",$db_config)->field("*")->where("id = '".$_GET['id']."'")->find();
	// p($data);
}

//层级读取
$user_level = M('k_user_level',$db_config)->field('id,level_des')->where("is_delete = 0 and site_id = '".SITEID."'")->select();
foreach ($user_level as $v){
    $level_all[] = $v['id'];
}

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
//获取层级
if (!empty($_POST['level'])) {
    $level_con = implode(',', $_POST['level']);
}else{
    $level_con = implode(',', $level_all);
}
//添加支付方式
if(empty($_POST['id']) && !empty($_POST['pay_key'])){
	$sql    =   "insert into pay_set(pay_domain,pay_id,pay_key,pay_type,f_url,money_limits,money_Already,order_id,b_start,money_Lowest,money_max,level_id,site_id,terminalid,vircarddoin) values('".htmlEncode($_POST['pay_domain'])."','".htmlEncode($_POST['pay_id'])."','".htmlEncode($_POST['pay_key'])."','".$_POST['pay_type']."','".htmlEncode($_POST['f_url'])."','".$_POST['money_limits']."','".$_POST['money_Already']."','".$_POST['order_id']."','".$_POST['b_start']."','".$_POST['money_Lowest']."','".$_POST['money_max']."','".$level_con."','".SITEID."','".htmlEncode($_POST['terminalid'])."','".htmlEncode($_POST['vircarddoin'])."')";
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
	$sql    =   "update pay_set set pay_domain='".htmlEncode($_POST['pay_domain'])."',level_id='".$level_con."',pay_id='".htmlEncode($_POST['pay_id'])."',pay_key='".htmlEncode($_POST['pay_key'])."',pay_type='".htmlEncode($_POST['pay_type'])."',f_url='".htmlEncode($_POST['f_url'])."',money_limits='".htmlEncode($_POST['money_limits'])."',money_Already='".htmlEncode($_POST['money_Already'])."',order_id='".htmlEncode($_POST['order_id'])."',money_max='".htmlEncode($_POST['money_max'])."',money_Lowest='".htmlEncode($_POST['money_Lowest'])."',is_delete = '".$_POST['sele']."',vircarddoin = '".$_POST['vircarddoin']."',terminalid='".htmlEncode($_POST['terminalid'])."' where id='".intval($_POST['id'])."'";
	//echo $sql;exit;
	$mysqlt->autocommit(FALSE);
	$mysqlt->query("BEGIN"); //事务开始
	try{
		$mysqlt->query($sql);
		$q1		=	$mysqlt->affected_rows;
		if($q1 == 1){
			include_once("../../class/admin.php");
			admin::insert_log($_SESSION["adminid"],$_SESSION['login_name'],"修改了支付参数配置");
			$mysqlt->commit(); //事务提交
			message('修改支付方式成功','pay_set.php');
		}else{
			$mysqlt->rollback(); //数据回滚
			message('未修改或修改支付方式失败','pay_set.php');
		}
	}catch(Exception $e){
		$mysqlt->rollback(); //数据回滚
		message('未修改或修改支付方式失败','pay_set.php');
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
}

if(@$_GET["action"]=="clear"){
	$sql="update pay_set set money_Already='0' where id='".intval($_GET['id'])."'";
	$mysqlt->query($sql);
}

$sql	=	"select * from pay_set where site_id = '".SITEID."' order by order_id asc";
$query	=	$mysqlt->query($sql);
?>
<?php require("../common_html/header.php");?>
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
 <!--  <div  class="con_menu"> 
      <a href="./pay_bank_set.php">銀行設定</a>
      <a href="./pay_set.php" style="color:red;">支付平臺設定</a>
  </div> --><input type="button" value="返回上一頁" onclick="window.location.href='pay_set.php'" class="button_e">
</div>


    	
  


<table class="m_tab" style="width:50%;">
<tr  class="m_title">
        <td  colspan="3">修改支付信息</td>
 </tr>
<form id="form1" name="form1" method="post" action="pay_set_add.php">
<input type="hidden" name="id" id="id" value="<?=@$_GET["id"] ?>" />
  <tr>
    <td height="25" width="80" align="center"  class="table_bg1" >排序：</td>
    <td height="25"  class="table_bg1" >
    <input name="order_id" type="text" id="order_id" value="<?=$data['order_id']?>" size="10" />数字越小越靠前</td>
  </tr>
  		 <tr>
		<td  height="25"  align="center"  class="table_bg1">層級</td>
          <td>
          	<table  border="0"  cellpadding="1">
          	<tbody>
          	<?php
          	  for ($i=0; $i < (count($user_level)/4); $i++) { 
          	  $j=0;	 
          	?>
          	<tr>
          	   <?php 
          	   foreach ($user_level as $key => $val) {
          	   	  $j++;	
          	   	  $k = $key+$i*4;
          	   	  if (empty($user_level[$k]['level_des'])) {
			          break 2;//如果没有数据就跳出第二层循环
			       }
          	   ?>
			        <td>
			            <input  type="checkbox" <?php check_box2($data['level_id'],$user_level[$k]['id']);?>  name="level[]"  value="<?=$user_level[$k]['id']?>">&nbsp;<?=$user_level[$k]['level_des']?>&nbsp;
			         </td>
           <?php 
           
             if ($j == 4) { 
             	break 1;//跳出第一层循环
             }
             }
             ?>
           </tr>
           <?php }?>
            </tbody></table>
            &nbsp;&nbsp;<font  color="red">不選默認全部層級</font>
          </td>
	</tr>


 <?php if($_GET['action']=='mod'){?>
<tr>
    <td  height="25"  align="center"  class="table_bg1" >支付域名：</td>
    <td  height="25" class="table_bg1" >
        <?=$data['pay_domain']?>
        <input name="pay_domain" class="za_text" type="hidden" style="width:300px"  id="pay_domain" value="<?=$data['pay_domain']?>" size="80" />
    </td>
  </tr>
  <tr>
    <td  height="25"  align="center"  class="table_bg1" >返回地址：</td>
        
    <td  height="25"  class="table_bg1">
        <?=$data['f_url']?>
        <input name="f_url" type="hidden" style="width:300px" id="f_url" value="<?=$data['f_url']?>" size="80" /></td>
  </tr>
  <tr>
    <td  height="25"  align="center"  class="table_bg1" >商户id：</td>
    <td  height="25" class="table_bg1" >
        <?=$data['pay_id']?>
    <input name="pay_id" type="hidden" id="pay_id" value="<?=$data['pay_id']?>" style="width:300px;" /></td>
  </tr>  
  <tr>
    <td  height="25"  align="center"  class="table_bg1" >终端id：</td>
    <td  height="25" class="table_bg1" >
        <?=$data['terminalid']?>
    <input name="terminalid" type="hidden" id="terminalid" value="<?=$data['terminalid']?>" style="width:300px;" /></td>
  </tr>   
  <tr>
    <td  height="25"  align="center"  class="table_bg1" >密匙：</td>
    <td  height="25"  class="table_bg1" style="word-break:break-all" >
        <?=$data['pay_key']?>
    <input name="pay_key" style="width:300px" class="za_text" type="hidden" id="pay_key" value="<?=$data['pay_key']?>" size="80" /></td>
  </tr>
  <tr>
    <td  height="25"  align="center"  class="table_bg1" >账号：</td>
    <td  height="25"  class="table_bg1" style="word-break:break-all" >
        <?=$data['vircarddoin']?>
    <input name="vircarddoin" style="width:300px" class="za_text" type="hidden" id="vircarddoin" value="<?=$data['vircarddoin']?>" size="80" /></td>
  </tr>

    <?php }else{?>
  <tr>
    <td  height="25"  align="center"  class="table_bg1" >支付域名：</td>
    <td  height="25" class="table_bg1" >
        <input name="pay_domain" class="za_text" type="text" style="width:300px"  id="pay_domain" value="<?=$data['pay_domain']?>" size="80" />支付域名，只要写域名，不要加http://
    </td>
  </tr>
  <tr>
    <td  height="25"  align="center"  class="table_bg1" >返回地址：</td>
        
    <td  height="25"  class="table_bg1">
        <input name="f_url" type="text" style="width:300px" id="f_url" value="<?=$data['f_url']?>" size="80" />充值成功返回的地址，只要写域名，不要加http://</td>
  </tr>
  <tr>
    <td  height="25"  align="center"  class="table_bg1" >商户id：</td>
    <td  height="25" class="table_bg1" >
    <input name="pay_id" type="text" id="pay_id" value="<?=$data['pay_id']?>" style="width:300px;" /></td>
  </tr>  
  <tr>
    <td  height="25"  align="center"  class="table_bg1" >终端id：</td>
    <td  height="25" class="table_bg1" >
    <input name="terminalid" type="text" id="terminalid" value="<?=$data['terminalid']?>" style="width:300px;" /></td>
  </tr>   
  <tr>
    <td  height="25"  align="center"  class="table_bg1" >密匙：</td>
    <td  height="25"  class="table_bg1" >
    <input name="pay_key" style="width:300px" class="za_text" type="text" id="pay_key" value="<?=$data['pay_key']?>" size="80" /></td>
  </tr>
  <tr>
    <td height="25"  align="center"  class="table_bg1" >账号：</td>
    <td height="25"  class="table_bg1" ><input name="vircarddoin" type="text" id="vircarddoin" value="<?=$data['vircarddoin']?>" size="20" />暂时只支持国付宝，其他支付方式请勿填写</td>
  </tr>
  <?php }?>


 
  <tr>
    <td height="25"  align="center"  class="table_bg1" >支付限额：</td>
    <td height="25"  class="table_bg1" ><input name="money_limits" type="text" id="money_limits" value="<?=$data['money_limits']?>" size="20" />当此帐户充值达到限额时，自动切换到其他帐户(按照排序，由小到大)</td>
  </tr>
  
   <!--
  <tr>
    <td height="25"  align="center"  class="table_bg1" >已支付：</td>
    <td height="25"  class="table_bg1" ><input name="money_Already" type="text" id="money_Already" value="<?=$data['money_Already']?>" size="20" />当前帐户已支付的金额</td>
  </tr>
  
   <tr>
    <td height="25"  align="center"  class="table_bg1" >最低充值：</td>
    <td height="25"  class="table_bg1"><input name="money_Lowest" type="text" id="money_Lowest" value="<?=$data['money_Lowest']?>" size="20" />允许用户最低充值限额</td>
  </tr>
  <tr>
    <td height="25"  align="center"  class="table_bg1" >最高充值：</td>
    <td height="25"  class="table_bg1"><input name="money_max" type="text" id="money_max" value="<?=$data['money_max']?>" size="20" />允许用户最高充值限额</td>
  </tr>-->
   <?php if($_GET['action']=='mod'){?>

<tr>
    <td height="25"  align="center"  class="table_bg1" >支付平台：</td>
    <td height="25"  class="table_bg1" ><?=payment_type($data['pay_type'])?>
      <input type="hidden" name="pay_type" value="<?=$data['pay_type']?>" />
    </td>
  </tr>
<?php }else{?>
  <tr>
    <td height="25"  align="center"  class="table_bg1" >支付平台：</td>
    <td height="25"  class="table_bg1" ><select id="pay_type" name="pay_type">    
		<option value="1" <?if($data['pay_type']==1) echo "selected=\"selected\"";?>>新生</option>
        <option value="2" <?if($data['pay_type']==2) echo "selected=\"selected\"";?>>易宝</option>
		<option value="3" <?if($data['pay_type']==3) echo "selected=\"selected\"";?>>环迅</option>
<!-- 		<option value="4" <?if($data['pay_type']==4) echo "selected=\"selected\"";?>>聚付通</option>
		<option value="5" <?if($data['pay_type']==5) echo "selected=\"selected\"";?>>v付通</option> -->
		<option value="6" <?if($data['pay_type']==6) echo "selected=\"selected\"";?>>宝付</option>
    <option value="7" <?if($data['pay_type']==7) echo "selected=\"selected\"";?>>智付</option>
    <option value="8" <?if($data['pay_type']==8) echo "selected=\"selected\"";?>>汇潮</option>
    <option value="9" <?if($data['pay_type']==9) echo "selected=\"selected\"";?>>国付宝</option>
      </select></td>
  </tr>
<?php }?>
  <tr>
    <td height="25"  align="center"  class="table_bg1" >状态：</td>
    <td height="25"  class="table_bg1" ><select id="sele" name="sele">    
		<option value="0" <?if($data['is_delete']==0) echo "selected=\"selected\"";?>>启用</option>
        <option value="1" <?if($data['is_delete']==1) echo "selected=\"selected\"";?>>停用</option>
      </select></td>
  </tr>

  <tr align="center">
		<td colspan="2" class="table_bg1">
			<input value="確定" type="submit" class="button_d">&nbsp;
		</td>
	</tr>
</form>
</table>

</body>
</html>
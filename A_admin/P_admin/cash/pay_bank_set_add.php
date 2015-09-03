<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");

//表单处理
//层级读取
$user_level = M('k_user_level',$db_config)->field('id,level_des')->where("is_delete = 0 and site_id = '".SITEID."'")->select();
foreach ($user_level as $v){
    $level_all[] = $v['id'];
}
if (!empty($_POST['level'])) {
    $level_con = implode(',', $_POST['level']);
}else{
    $level_con = implode(',', $level_all);
}
if ($_POST['action'] == 'p_a') {
	$data['bank_type'] = $_POST['bank_type'];
    $data['card_ID'] = $_POST['card_ID'];
    $data['card_address'] = $_POST['card_address'];
    $data['card_userName'] = $_POST['card_userName'];
    $data['stop_amount'] = $_POST['stop_amount'];
    $data['remark'] = $_POST['remark'];
    $data['cate'] = $_POST['cate'];//银行卡类别
    $data['level_id'] = $level_con;
   
	if(empty($_POST['id'])){
      //添加
        	$data['site_id'] = SITEID;
			if (M('k_bank',$db_config)->add($data)) {
				message('添加成功');
			}
	}else {
		//更新
			$data['is_delete'] = $_POST['sele'];
			if (M('k_bank',$db_config)->where("id = '".$_POST['id']."'")->update($data)) {
				message('更新成功','pay_bank_set.php');
			}else{
			    message('没有更新','pay_bank_set.php');
			}
		
	}

}


//查询读取数据
if($_GET['id']){
	$map['id']=$_GET['id'];
	$bank=M('k_bank',$db_config)->where($map)->find();
}
?>
<?php require("../common_html/header.php");?>
<div  id="con_wrap">
<div  class="input_002">新增银行卡</div>
<div  class="con_menu">
    <a href="javascript:history.go(-1);">返回上一頁</a>
</div>
</div>
<div  class="content"  style="width:500px;">
	<form  name="addForm"  action="pay_bank_set_add.php"  method="post"  id="myform"  class="vform">
	<input type="hidden" name="action" value="p_a">
	<input type="hidden" name="id" value="<?php if($bank['id']){ echo $bank['id']; } ?>">
	<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0"  bgcolor="#E3D46E"  class="m_tab"  style="border:0px;">
		<tbody><tr  class="m_title_over_co">
			<td  colspan="2">新增银行卡</td>
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
			            <input  type="checkbox" <?php check_box2($bank['level_id'],$user_level[$k]['id']);?>  name="level[]"  value="<?=$user_level[$k]['id']?>">&nbsp;<?=$user_level[$k]['level_des']?>&nbsp;
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
            &nbsp;&nbsp;<font color="red">不選默認全部層級</font>
          </td>
	</tr>
		<tr>
			<td  class="table_bg1"  align="right">银行名稱：</td>
			<td>
				<div  style="float:left">
				<select name="bank_type"> 
						<option value="1" <?php select_check($bank['bank_type'],'1');?>>中國銀行</option>
						<option value="2" <?php select_check($bank['bank_type'],'2');?>>中國工商銀行</option>
						<option value="3" <?php select_check($bank['bank_type'],'3');?>>中國建設銀行</option>
						<option value="4" <?php select_check($bank['bank_type'],'4');?>>中國招商銀行</option>
						<option value="5" <?php select_check($bank['bank_type'],'5');?>>中國民生銀行</option>
						<option value="7" <?php select_check($bank['bank_type'],'7');?>>中國交通銀行</option>
						<option value="8" <?php select_check($bank['bank_type'],'8');?>>中國郵政銀行</option>
						<option value="9" <?php select_check($bank['bank_type'],'9');?>>中國农业銀行</option>
						<option value="10" <?php select_check($bank['bank_type'],'10');?>>華夏銀行</option>
						<option value="11" <?php select_check($bank['bank_type'],'11');?>>浦發銀行</option>
						<option value="12" <?php select_check($bank['bank_type'],'12');?>>廣州銀行</option>
						<option value="13" <?php select_check($bank['bank_type'],'13');?>>北京銀行</option>
						<option value="14" <?php select_check($bank['bank_type'],'14');?>>平安銀行</option>
						<option value="15" <?php select_check($bank['bank_type'],'15');?>>杭州銀行</option>
						<option value="16" <?php select_check($bank['bank_type'],'16');?>>溫州銀行</option>
						<option value="17" <?php select_check($bank['bank_type'],'17');?>>中國光大銀行</option>
						<option value="18" <?php select_check($bank['bank_type'],'18');?>>中信銀行</option>
						<option value="19" <?php select_check($bank['bank_type'],'19');?>>浙商銀行</option>
						<option value="20" <?php select_check($bank['bank_type'],'20');?>>漢口銀行</option>
						<option value="21" <?php select_check($bank['bank_type'],'21');?>>上海銀行</option>
						<option value="22" <?php select_check($bank['bank_type'],'22');?>>廣發銀行</option>
						<option value="23" <?php select_check($bank['bank_type'],'23');?>>农村信用社</option>
						<option value="24" <?php select_check($bank['bank_type'],'24');?>>深圳发展银行</option>
						<option value="25" <?php select_check($bank['bank_type'],'25');?>>渤海银行</option>
						<option value="26" <?php select_check($bank['bank_type'],'26');?>>东莞银行</option>
						<option value="27" <?php select_check($bank['bank_type'],'27');?>>宁波银行</option>
						<option value="28" <?php select_check($bank['bank_type'],'28');?>>东亚银行</option>
						<option value="29" <?php select_check($bank['bank_type'],'29');?>>晋商银行</option>
						<option value="30" <?php select_check($bank['bank_type'],'30');?>>南京银行</option>
						<option value="31" <?php select_check($bank['bank_type'],'31');?>>广州农商银行</option>
						<option value="32" <?php select_check($bank['bank_type'],'32');?>>上海农商银行</option>
						<option value="33" <?php select_check($bank['bank_type'],'33');?>>珠海农村信用合作联社</option>
						<option value="34" <?php select_check($bank['bank_type'],'34');?>>顺德农商银行</option>
						<option value="35" <?php select_check($bank['bank_type'],'35');?>>尧都区农村信用联社</option>
						<option value="36" <?php select_check($bank['bank_type'],'36');?>>浙江稠州商业银行</option>
						<option value="37" <?php select_check($bank['bank_type'],'37');?>>北京农商银行</option>
						<option value="38" <?php select_check($bank['bank_type'],'38');?>>重庆银行</option>
						<option value="39" <?php select_check($bank['bank_type'],'39');?>>广西农村信用社</option>
						<option value="40" <?php select_check($bank['bank_type'],'40');?>>江苏银行</option>
						<option value="41" <?php select_check($bank['bank_type'],'41');?>>吉林银行</option>
						<option value="42" <?php select_check($bank['bank_type'],'42');?>>成都银行</option>
						<option value="50" <?php select_check($bank['bank_type'],'50');?>>兴业银行</option>
						<option value="100" <?php select_check($bank['bank_type'],'100');?>>支付宝</option>
						<option value="101" <?php select_check($bank['bank_type'],'101');?>>微信支付</option>
						<option value="102" <?php select_check($bank['bank_type'],'102');?>>财付通</option>
					</select>
				</div>
				<div  class="Validform_checktip" id="ad_urls"  style="float:left"></div>
			</td>
		</tr>
		<tr>
		<td height="25" align="center" class="table_bg1">选择类别</td>
          <td><select name="cate"> 
			  <option value="A" <?=$_GET["cate"]==A ? 'selected' : ''?>>A类</option>
          <option value="B" <?=$_GET["cate"]==B? 'selected' : ''?>>B类</option>
          <option value="C" <?=$_GET["cate"]==C ? 'selected' : ''?>>C类</option>
          <option value="D" <?=$_GET["cate"]==D ? 'selected' : ''?>>D类</option>
          <option value="E" <?=$_GET["cate"]==E ? 'selected' : ''?>>E类</option>
			</select></td>
			
	</tr>
		<tr>
			<td  class="table_bg1"  align="right">收款帳號：</td>
			<td>
				<div  style="float:left"><input  type="text"  class="za_text" name="card_ID" style="width:250px;"  value="<?if($bank['card_ID']!=''){echo $bank['card_ID'];}?>"></div>
				<div  class="Validform_checktip" id="card_IDs"  style="float:left"></div>
			</td>
		</tr>
		
		<tr>
			<td  class="table_bg1"  align="right">開戶行：</td>
			<td>
				<div  style="float:left"><input  type="text"  class="za_text"  name="card_address" id="connection_url" style="width:250px;"  value="<?if($bank['card_address']!=''){echo $bank['card_address'];}?>"></div>
				<div  class="Validform_checktip" id="connection_urls"  style="float:left"></div>
			</td>
		</tr>
			<tr>
			<td  class="table_bg1"  align="right">收款人：</td>
			<td>
				<div  style="float:left"><input  type="text"  class="za_text"  name="card_userName" id="connection_url" style="width:250px;"  value="<?if($bank['card_userName']!=''){echo$bank['card_userName'];}?>"></div>
				<div  class="Validform_checktip" id="connection_urls"  style="float:left"></div>
			</td>
		</tr>
		<tr>
		<td height="25" align="center" class="table_bg1">停用金額</td>		
		<td><input class="za_text Validform_error" datatype="n1-9" nullmsg="請輸入停用金額！" errormsg="停用金額必須至少1位數字，最多9個字數字" name="stop_amount" id="stop_amount" value="<?=$bank['stop_amount']?>" onkeydown="return Yh_Text.CheckNumber2();"></td>
		
	</tr>
	<tr>
	    <td height="25"  align="center"  class="table_bg1" >状态：</td>
	    <td height="25"  class="table_bg1" ><select id="sele" name="sele">    
			<option value="0" <?if($bank['is_delete']==0) echo "selected=\"selected\"";?>>启用</option>
	        <option value="2" <?if($bank['is_delete']==2) echo "selected=\"selected\"";?>>停用</option>
	      </select></td>
  	</tr>
		<tr>
		<td height="25" align="center" class="table_bg1">備註</td>
		<td><textarea class="za_text Validform_error" datatype="*2-2000" nullmsg="請輸入備註！" errormsg="備註必須至少2個字元長，最多2000個字元長" name="remark" id="memo" style="width:300px;height:80px;"><?=$bank['remark']?></textarea></td>
		
	</tr>
		<tr>
			<td  colspan="2"  align="center">
				<input  type="button"   name="subbtn" onclick="check()"  class="button_a"  value=" 確 定 ">&nbsp;&nbsp;&nbsp;&nbsp;
				<input  type="reset"  name="cls"  value="重 置 "  class="button_a">
			</td>
		</tr>			
	</tbody></table>
	</form>
</div>
	</tbody></table>
<script>

function check(){

	$("#myform").submit();
}
</script>

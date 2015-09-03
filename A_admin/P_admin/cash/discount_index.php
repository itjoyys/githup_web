<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");

//层级读取
$user_level = M('k_user_level',$db_config)->field('id,level_des')->where("is_delete = 0 and site_id = '".SITEID."'")->select();

//股东读取
$agent_sh = M('k_user_agent',$db_config)->where("is_delete = 0 and site_id = '".SITEID."' and agent_type = 's_h' and is_demo = '0'")->select();
?>
<?php $title="優惠統計"; require("../common_html/header.php");?>
<body>
<div  id="con_wrap">
  <div  class="input_002">優惠統計</div>
  <div  class="con_menu">
  <a  href="discount_index.php" style="color:red;">優惠統計</a>
  <a  href="discount_search.php">優惠查詢</a>
  <a  href="discount_set.php">返點優惠設定</a>
  <a  href="reg_discount_set.php">申請會員優惠設定</a>
  </div>
</div>
<div  class="content">
<form  method="post" name="myFORM" id="myFORM" action="./discount_count.php">
<input  type="hidden"  id="userid"  name="userid"  value=""> 
<input  type="hidden"  name="username"  value="">
<table  style="width:500px"  class="m_tab">        
	<tbody><tr  class="m_title">
		<td  colspan="2"  height="25"  align="center">退水優惠</td>
	</tr>
	<tr>
		<td  height="25"  align="center"  class="table_bg1">日期查詢</td>
		<td  style="text-align:left">
		     <INPUT name="date_start" class="za_text Wdate" style="width: 100px; min-width: 100px;" onclick="WdatePicker()" type="text" size="10" maxlength="11" value="<?=date("Y-m-d")?>">
      			--
      		 <INPUT name="date_end" class="za_text Wdate" style="width: 100px; min-width: 100px;" onclick="WdatePicker()" type="text" size="10" maxlength="10" value="<?=date("Y-m-d")?>">
      		 <INPUT class="za_button" style="margin-top: 5px;" onclick="chg_date('w',-2,6-2)" type="Button" value="本周"> 
                <INPUT class="za_button" style="margin-top: 5px;" onclick="chg_date('lw',-2-7,6-2-7)" type="Button" value="上周"> 
                <INPUT class="za_button" style="margin-top: 5px;" onclick="chg_date('lw',-1,-1)" type="Button" value="昨天">
		</td>		
	</tr>
	<tr>
		<td  height="25"  align="center"  class="table_bg1">體系選擇</td>
          <td>
          	<select  id="wtype"  name="wtype"  class="za_select">
				<option  value="1">股東</option>
				<option  value="2">会员</option>
			</select>
			&nbsp;&nbsp;
			<select  id="wtype_name"  name="sh_name"  class="za_select">
			    <?php if (!empty($agent_sh)) {
			    	foreach ($agent_sh as $key => $val) {
                 ?>
				<option  value="<?=$val['id']?>-<?=$val['index_id']?>"><?=$val['agent_user']?></option>
				<?php
				   }
				}?>
			</select>
			
          </td>
	</tr>
	<tr  id="members_tr"  style="display:none">
		<td  height="25"  align="center"  class="table_bg1">体系选择</td>
          <td>
			<textarea  name="members"  id="members"  class="za_text"  style="width:350px;height:100px;"></textarea><br>
			多个会员帐号之间用引文逗号(,)隔开
          </td>
	</tr>
	<tr>
		<td  height="25"  align="center"  class="table_bg1">顯示條件</td>
          <td>
			<select  name="condition"  class="za_select">
				<option  value="Y">有優惠</option>
			</select>
          </td>
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
			            <input  type="checkbox"  name="level[]"  value="<?=$user_level[$k]['id']?>">&nbsp;<?=$user_level[$k]['level_des']?>&nbsp;
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
	<tr  align="center">
		<td  colspan="2"  class="table_bg1">
			<input  value="確定"  id="savebtn1"  name="savebtn1"  type="submit"  class="button_a">       
        </td>
	</tr>
</tbody></table> 
</form>
</div>
<script language="JavaScript" type="text/JavaScript">
var spLimit = '';
var spRate = '';
var spMax = '';
var Audit_COMPLEX = '';
var today = '<?=date('Y-m-d H:i:s')?>';

function chg_date(range,num1,num2){
	var myFORM=document.forms["myFORM"];
	if(range=='t' || range=='w' || range=='lw' || range=='r'){
		myFORM.date_start.value = today;
		myFORM.date_end.value =myFORM.date_start.value;
	}

	if(range!='t'){
		if(myFORM.date_start.value!=myFORM.date_end.value){
			myFORM.date_start.value =today;
			myFORM.date_end.value =myFORM.date_start.value;
		}
		var aStartDate = myFORM.date_start.value.split('-');
		var newStartDate = new Date(parseInt(aStartDate[0], 10),parseInt(aStartDate[1], 10) - 1,parseInt(aStartDate[2], 10) + num1);
		myFORM.date_start.value = newStartDate.getFullYear()+ '-' + padZero(newStartDate.getMonth() + 1)+ '-' + padZero(newStartDate.getDate());
		var aEndDate = myFORM.date_end.value.split('-');
		var newEndDate = new Date(parseInt(aEndDate[0], 10),parseInt(aEndDate[1], 10) - 1,parseInt(aEndDate[2], 10) + num2);
		myFORM.date_end.value = newEndDate.getFullYear()+ '-' + padZero(newEndDate.getMonth() + 1)+ '-' + padZero(newEndDate.getDate());
	}
}
function padZero(num) {
	return ((num <= 9) ? ("0" + num) : num);
}
//
$(document).ready(function(e) {
    $("#wtype").bind("change",function(b){
			if($(this).val()=="1")
			{
				$("#members_tr").hide();	
				$("#wtype_name").show();
			}
			else
			{
				$("#members_tr").show();
				$("#wtype_name").hide();	
			}
		});
});


</script>

<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>
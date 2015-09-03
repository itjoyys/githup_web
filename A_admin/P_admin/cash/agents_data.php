<?
include_once("../../include/config.php");
include_once("../common/login_check.php");

//读数据
$data=M('k_user_agent_apply',$db_config)->where('id='.$_GET['usernid'])->find();
$addre = explode(",",$data['bank_address']);
//p($data);

if($_POST){
  //p($_POST);
	//修改数据
  $result = M('k_user_agent',$db_config)->field('id')->where(" bankno= '$_POST[bankno]' and site_id = '".SITEID."'")->find();
  if($result){
    if($result['id']!=$_GET['usernid']){
      message('该银行卡已经绑定到代理账号！','agents_data.php?usernid='.$_GET['usernid']);
    }
  }
  $map['r_name']=$_POST['realname'];
  $map['en_name']=$_POST['en_name'];
  $map['zh_name']=$_POST['zh_name'];
  $map['card']=$_POST['card'];
  $map['mobile']=$_POST['mobile'];

	$map['msn_qq']=$_POST['qq'];
  $map['email']=$_POST['email'];
  $map['bankid']=$_POST['bankid'];
  $map['bank_account']=$_POST['bankno'];
	$map['bank_address']=$_POST['province'].','.$_POST['city'];

  $map['safe_pass']=$_POST['passmd'];
  $map['about']=$_POST['remark'];
  
	$map['from_url']=$_POST['website'];
	$map['other_method']=$_POST['other_website'];
  $map['grounds']=$_POST['grounds'];
	
	$result=M('k_user_agent_apply',$db_config)->where('id='.$_POST['id'])->update($map);
  
 
	if($result){
		message('修改成功','agent_examine.php');
	}
}
//p($data);
?>

<?php require("../common_html/header.php");?>
<body> 
<style type="text/css">
  .za_text{
    float: left;
  }
</style>
<script>
$(function(){
  $('#bankid').val(<?=$data['bankid']?>);
})

</script>
<div id="con_wrap">
  <div class="input_002">修改會員資料</div>
  <div class="con_menu"> <a href="javascript:history.go(-1)">返回上一頁</a> </div>
</div>
<div class="content">
	<div id="show_div" style="position: absolute;z-index:99;"></div>
  <div id="Layer1" style="position:absolute; width:780px; height:26px; z-index:1; left: 0px; top: 406px; visibility: hidden; background-color: #FFFFFF; layer-background-color: #FFFFFF; border: 1px none #000000"></div>
  <form name="myFORM" id="myFORM" action="" method="POST">
  <input type="hidden" name="id" value="<?=$_GET['usernid']?>" />
    <table width="650" border="0" cellspacing="0" cellpadding="0" class="m_tab_ed">
      <tbody><tr class="m_title_edit">
        <td colspan="3">基本資料編輯</td>
      </tr>
      <tr class="m_bc_ed">
        <td class="m_mem_ed" width="70">帳號：</td>
        <!-- <td><input type="text" name="username" value="e8imessage" size="12" maxlength="12" class="za_text" datatype="s6-12" nullmsg="請輸入會員的帳號" errormsg="帳戶要求在4-12字元這間，英文和數字組合"/></td> -->
        <td><span style="float:left;height:20px; line-height:20px;margin-top:4px;"></span><input type="text" name="username" readonly="readonly" value="<?=$data['r_user']?>" size="12" maxlength="12" class="za_text"></td>
        <td><div class="Validform_checktip"></div></td>
      </tr>
      <tr class="m_bc_ed">
        <td class="m_mem_ed">真實姓名：</td>
        <td><input type="TEXT" datatype="*" nullmsg="請輸入真實姓名" name="realname" size="10" class="za_text" value="<?=$data['realname']?>"></td>
        <td><div class="Validform_checktip"></div></td>
      </tr>
      <tr class="m_bc_ed">
        <td class="m_mem_ed">英文名稱：</td>
        <td><input type="TEXT" datatype="*" name="en_name" size="10" class="za_text" value="<?=$data['en_name']?>"></td>
        <td><div class="Validform_checktip"></div></td>
      </tr>
      <tr class="m_bc_ed">
        <td class="m_mem_ed">中文名稱：</td>
        <td><input type="TEXT" datatype="*" name="zh_name" size="10" class="za_text" value="<?=$data['agent_name']?>"></td>
        <td><div class="Validform_checktip"></div></td>
      </tr>      
      <!--<tr class="m_bc_ed">
        <td class="m_mem_ed">出生日期：</td>
        <td><input type="TEXT" datatype="*" name="birthday" size="10" class="za_text" value=""></td>
        <td><div class="Validform_checktip"></div></td>
      </tr>
      <tr class="m_bc_ed">
        <td class="m_mem_ed">國家：</td>
        <td><input type="TEXT" datatype="*" name="national" size="10" class="za_text" value=""></td>
        <td><div class="Validform_checktip"></div></td>
      </tr>  -->
      <tr class="m_bc_ed">
        <td class="m_mem_ed">身份證號：</td>
        <td><input type="TEXT" datatype="*" nullmsg="請輸入身份證號" name="card" size="10" class="za_text" value="<?=$data['card']?>"></td>
        <td><div class="Validform_checktip"></div></td>
      </tr>  
      <tr class="m_bc_ed">
        <td class="m_mem_ed">手機：</td>
        <td><input type="TEXT" datatype="*" nullmsg="請輸入手機號碼" name="mobile" size="10" class="za_text" value="<?=$data['mobile']?>"></td>
        <td><div class="Validform_checktip"></div></td>
      </tr>                  
      <tr class="m_bc_ed">
        <td class="m_mem_ed">QQ：</td>
        <td><input type="TEXT" datatype="*" name="qq" size="10" class="za_text" value="<?=$data['qq']?>"></td>
        <td><div class="Validform_checktip"></div></td>
      </tr> 
      <tr class="m_bc_ed">
        <td class="m_mem_ed">E-mail：</td>
        <td><input type="TEXT" datatype="*" name="email" size="10" class="za_text" value="<?=$data['email']?>"></td>
        <td><div class="Validform_checktip"></div></td>
      </tr>
      <tr class="m_bc_ed">
        <td class="m_mem_ed">取款銀行：</td>
        <td>
        	<select name="bankid" id="bankid" class="za_text">
        	  <option value="">請選擇取款銀行</option>
            <option value="1">中國銀行</option>
            <option value="2">中國工商銀行</option>
            <option value="3">中國建設銀行</option>
            <option value="4">中國招商銀行</option>
            <option value="5">中國民生銀行</option>
            <option value="7">中國交通銀行</option>
            <option value="8">中國郵政銀行</option>
            <option value="9">中國农业銀行</option>
            <option value="10">華夏銀行</option>
            <option value="11">浦發銀行</option>
            <option value="12">廣州銀行</option>
            <option value="13">北京銀行</option>
            <option value="14">平安銀行</option>
            <option value="15">杭州銀行</option>
            <option value="16">溫州銀行</option>
            <option value="17">中國光大銀行</option>
            <option value="18">中信銀行</option>
            <option value="19">浙商銀行</option>
            <option value="20">漢口銀行</option>
            <option value="21">上海銀行</option>
            <option value="22">廣發銀行</option>
            <option value="23">农村信用社</option>
            <option value="24">深圳发展银行</option>
            <option value="25">渤海银行</option>
            <option value="26">东莞银行</option>
            <option value="27">宁波银行</option>
            <option value="28">东亚银行</option>
            <option value="29">晋商银行</option>
            <option value="30">南京银行</option>
            <option value="31">广州农商银行</option>
            <option value="32">上海农商银行</option>
            <option value="33">珠海农村信用合作联社</option>
            <option value="34">顺德农商银行</option>
            <option value="35">尧都区农村信用联社</option>
            <option value="36">浙江稠州商业银行</option>
            <option value="37">北京农商银行</option>
            <option value="38">重庆银行</option>
            <option value="39">广西农村信用社</option>
            <option value="40">江苏银行</option>
            <option value="41">吉林银行</option>
            <option value="42">成都银行</option>
            <option value="50">兴业银行</option>
            <option value="100">支付宝</option>
        	</select>
        </td>
        <td><div class="Validform_checktip"></div></td>
      </tr>
      <tr class="m_bc_ed">
        <td class="m_mem_ed">銀行帳號：</td>
        <td><input type="TEXT" datatype="*" name="bankno" size="10" class="za_text" value="<?=$data['bankno']?>"></td>
        <td><div class="Validform_checktip"></div></td>
      </tr> 
      <tr class="m_bc_ed">
        <td class="m_mem_ed">省份：</td>
        <td><input type="TEXT" datatype="*" name="province" size="10" class="za_text" value="<?=$addre[0]?>"></td>
        <td><div class="Validform_checktip"></div></td>
      </tr> 
      <tr class="m_bc_ed">
        <td class="m_mem_ed">縣市：</td>
        <td><input type="TEXT" datatype="*" name="city" size="10" class="za_text" value="<?=$addre[1]?>"></td>
        <td><div class="Validform_checktip"></div></td>
      </tr>  
      <tr class="m_bc_ed">
        <td class="m_mem_ed">取款密碼：</td>
        <td><input type="text" datatype="*" name="passmd" size="10" class="za_text" value="<?=$data['safe_pass']?>"></td>
        <td><div class="Validform_checktip"></div></td>
      </tr> 
      <tr class="m_bc_ed">
        <td class="m_mem_ed">備註：</td>
        <td><textarea style="height: 56px;width208px;" name="remark" class="za_text"><?=$data['about']?></textarea></td>
        <td><div class="Validform_checktip"></div></td>
      </tr> 
      <tr class="m_bc_ed">
        <td class="m_mem_ed">申请理由：</td>
        <td><textarea style="height: 56px;width208px;" name="grounds" class="za_text"><?=$data['grounds']?></textarea></td>
        <td><div class="Validform_checktip"></div></td>
      </tr>
      <tr class="m_bc_ed">
        <td class="m_mem_ed">推广网址：</td>
        <td><input type="TEXT" datatype="*" name="website" id="website" size="20" class="za_text" value="<?=$data['from_url']?>"></td>
        <td><div class="Validform_checktip"></div></td>
      </tr> 
      <tr class="m_bc_ed">
        <td class="m_mem_ed">其他推广方式：</td>
        <td><input type="TEXT" datatype="*" name="other_website" id="other_website" size="20" class="za_text" value="<?=$data['other_method']?>"></td>
        <td><div class="Validform_checktip"></div></td>
      </tr> 
    <tr align="center" class="m_bc_ed">
        <td class="m_bc_td" colspan="3">
			<input type="SUBMIT" value="確定" name="submitbtn" class="za_button">
			&nbsp;&nbsp;&nbsp;
			<input type="button" name="cancelbtn" value="取消" id="FormsButton2" onclick="javascript:history.go(-1)" class="za_button">
		</td>
	</tr>        
    </tbody></table> 
   </form> 
</div>

</body></html>
<?php
include_once("../../include/config.php");
include_once("../common/login_check.php"); 

/**
   *
   * msgtype  1表示普通通知 2优惠通知 3出入款通知
   *
**/
//var_dump($_POST);exit;

if(!empty($_POST['notice_title'])||!empty($_POST['notice_content'])){
	$data=array();
		$data['sid']=SITEID;
		$data['notice_title']=$_POST['notice_title'];
		$data['notice_content']=$_POST['notice_content'];
		$data['notice_cate']=$_POST['notice_cate'];
		$data['notice_date']=date('Y-m-d H:i:s');
		$data['notice_state']='1';
	
	if(!empty($data)){
		if(M('site_notice',$db_config)->add($data)){
			message('发布成功！','notice.php');
		}
	}
}
?>
<?php require("../common_html/header.php");?>
<body>
<div id="con_wrap">
  <div class="input_002">發佈公告</div>
  <div class="con_menu"><input type="button" value="返回上一頁" onclick="javascript:history.go(-1);" class="button_d"></div>
</div>


<form method="post" name="withdrawal_form" action="notice_add.php" id="vform">
<table class="m_tab" style="width:100%;">
	<tbody><tr class="m_title">
		<td height="25" align="center" colspan="2">發佈公告</td>
	</tr>
	<tr>
		<td height="25" align="center" class="table_bg1">类型選擇</td>
          <td>
          	<select id="notice_cate" name="notice_cate" class="za_select">
				<option value="s_p">體育公告</option>
				<option value="f_c">彩票公告</option>
				<option value="v_d">視訊公告</option>
			</select>
			
          </td>
	</tr>
	<tr>
		<td height="25" align="center" class="table_bg1" width="30%">标题</td>
          <td>
          	<div style="float:left"><input class="za_text" datatype="*2-200" nullmsg="請輸入标题！" errormsg="标题必須至少2個字元長，最多200個字元長" name="notice_title" id="title" value=""></div>
          	<div class="Validform_checktip" style="float:left"></div>
          </td>	
	</tr>
	<tr>
		<td height="25" align="center" class="table_bg1">内容</td>
		<td>
			<div style="float:left"><textarea datatype="*2-20000" nullmsg="請輸入備註！" errormsg="備註必須至少2個字元長，最多20000個字元長" class="za_text" name="notice_content" id="content" style="width:500px;height:150px;"></textarea></div>
			<div class="Validform_checktip" style="float:left"></div>
		</td>
	</tr>
	
	<tr align="center">
		<td colspan="2" class="table_bg1">
			<input value="確定" type="submit" class="button_d">&nbsp;&nbsp;&nbsp;
			<input type="reset" value="重置" class="button_d"> &nbsp;&nbsp;&nbsp;	
		</td>
	</tr>
</tbody></table> 
</form>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="clear:both">
		<tbody><tr>
			<td align="center" height="50"></td>
		</tr>
	</tbody></table>


<script src="./member_msg_add_files/yhinput.js" type="text/javascript"></script></body></html>
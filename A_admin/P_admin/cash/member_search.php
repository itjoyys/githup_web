<?php 
include_once("../../include/config.php");
include_once("../common/login_check.php");
 ?>
<?php $title="會員查詢"; require("../common_html/header.php");?>
<body>
<div id="con_wrap">
  <div class="input_002">會員查詢</div>
  <div class="con_menu">
  <input type="button" value="返回上一頁" onclick="document.location='level_member.php';" class="button_e">
  </div>
</div>

<form method="get" name="withdrawal_form" action="level_member.php">
<input type="hidden" name="findUser" value="1">
<table width="99%" class="m_tab" align="center">        
	<tbody><tr class="m_title">
		<td height="25">查詢會員帳號，多個會員用英文逗號隔開,例如：a1,a2,a3</td>
	</tr>
	<tr>
		<td align="center"><textarea name="username" class="za_text" style="width:500px;height:80px;"></textarea></td>
	</tr>
	<tr align="center">
		<td colspan="2" class="table_bg1">
		<input value="查詢" id="savebtn" name="chaxun_btn" type="submit" class="button_a">
        &nbsp;&nbsp;
        <input type="reset" value="重置" class="button_a"> 
        </td>
	</tr>
</tbody></table> 
</form>
<?php require("../common_html/footer.php");?>
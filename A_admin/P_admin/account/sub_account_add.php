<?php
include_once "../../include/config.php";
include_once "../common/login_check.php";
include_once "../comm_menu.php";

$Sys = M('sys_admin',$db_config);
//读取对应账号信息
if (!empty($_GET['uid'])) {
	$rows = $Sys->where("uid = '" . $_GET['uid'] . "'")
	      ->find();
}


//账号权限判断
if (isset($_SESSION["adminid"])) {
    //去掉账号管理
	unset($menu['a05']);
	//取第一个字母判断大类是否显示
	foreach ($menu as $key => $val) {
		$menu_w = substr($key, 0, 1);
		if (!empty($_GET['uid'])) {
			$quanxianD = $Sys->field('quanxian')->where("uid = '" . $_GET['uid'] . "'")->find();
			if (strpos(trim($quanxianD['quanxian']), $key) === false) {
				$aState = false;
			} else {
				$aState = true;
			}
		}

		if (empty($_GET['uid']) || $aState) {
			$val['state'] = 1;
		}

		switch ($menu_w) {
			case 'a':
				$account[$key] = $val;
				break;
			case 'b':
				$note[$key] = $val;
				break;
			case 'c':
				$report[$key] = $val;
				break;
			case 'd':
				$result[$key] = $val;
				break;
			case 'e':
				$cash[$key] = $val;
				break;
			case 'f':
				$other[$key] = $val;
				break;
			case 'g':
			    $members[$key] = $val;
			    break;
		}
	}
}



?>
<?php require "../common_html/header.php";?>
<body>
<style type="text/css">
  .Validform_checktip {color:#FA0B42;}
</style>
<div  id="con_wrap">
  <div  class="input_002">管理子帳號</div>
  <div  class="con_menu">
  <a  href="javascript:history.go(-1);">返回上一頁</a> </div>
</div>
<div  class="content">
  <form  id="myFORM"  name="form1"  action="./sub_account_do.php"  method="POST">
    <table  width="650"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab_ed">
      <tbody><tr  class="m_title_edit">
        <td  colspan="3">基本資料設定</td>
      </tr>

      <tr  class="m_bc_ed">
        <td  class="m_co_ed"  width="120">所屬公司：</td>
        <td  width="110"><?=COMPANY_NAME?></td>
        <td></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">帳號：</td>
        <td>
       <input  name="login_name" id="login_name" style="width:110px;" type="text" value="<?=$rows["login_name"]?>"
        maxlength="12" <?php if (!empty($_GET['uid'])) {echo "readonly";}?>  class="za_text"></td>
        <td><div  class="Validform_checktip">*账号长度最少5个字符最多12个字符,只能数字和字母组合</div></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">密碼：</td>
        <td><input  type="PASSWORD"  datatype="s6-12"  id="passwd"  name="login_pwd"  nullmsg="請輸入密碼"  errormsg="密碼必須至少6個字元長，最多12個字元長，并只能有數字(0-9)，及英文大小寫字母"  value="******"  size="12"  maxlength="12"  class="za_text"></td>
        <td><div  class="Validform_checktip">*密码长度最少6个字符最多12个字符,只能数字和字母组合</div></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">確認密碼：</td>
        <td><input  type="PASSWORD"  name="repasswd"  datatype="*"  recheck="passwd"  nullmsg="請輸入密碼"  errormsg="您两次输入的账号密码不一致！"  value="******"  size="12"  maxlength="12"  class="za_text"></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">名稱：</td>
        <td><input  type="text" value="<?=$rows["about"]?>" name="about" id="about" datatype="*"style="width:110px;" class="za_text"></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>

    </tbody></table>
    <table  width="650"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab_ed">
      <tbody><tr  class="m_title_edit">
        <td  colspan="3">權限設定</td>
      </tr>
     <tr  class="m_bc_ed">
    <td  class="m_co_ed"  width="120">帳號管理</td>
        <td>
         <?php foreach ($account as $key => $val) {
	?>
            <div  class="menu_item">
              <input  type="checkbox"  name="quanxian[]"  value="<?=$key?>" <?php if ($val['state'] == '1') {echo 'checked=""';}?>><?=$val['name']?></div>
         <?php }?>
        </td>

    </tr>
    
         <tr  class="m_bc_ed">
    <td  class="m_co_ed"  width="120">会员资料管理</td>
        <td>
         <?php foreach ($members as $key => $val) {
	?>
            <div  class="menu_item">
              <input  type="checkbox"  name="quanxian[]"  value="<?=$key?>" <?php if ($val['state'] == '1') {echo 'checked=""';}?>><?=$val['name']?></div>
         <?php }?>
        </td>

    </tr>

     <tr  class="m_bc_ed">
    <td  class="m_co_ed"  width="120">即時注單</td>
        <td>
             <?php foreach ($note as $key => $val) {
	?>
            <div  class="menu_item">
              <input  type="checkbox"  name="quanxian[]"  value="<?=$key?>" <?php if ($val['state'] == '1') {echo 'checked=""';}?>><?=$val['name']?></div>
         <?php }?>
        </td>

    </tr>

     <tr  class="m_bc_ed">
    <td  class="m_co_ed"  width="120">報表查詢</td>
        <td>
            <?php foreach ($report as $key => $val) {
	?>
            <div  class="menu_item">
              <input  type="checkbox"  name="quanxian[]"  value="<?=$key?>" <?php if ($val['state'] == '1') {echo 'checked=""';}?>><?=$val['name']?></div>
         <?php }?>

        </td>
    </tr>

     <tr  class="m_bc_ed">
    <td  class="m_co_ed"  width="120">賽果/規則</td>
        <td>
             <?php foreach ($result as $key => $val) {
	?>
            <div  class="menu_item">
              <input  type="checkbox"  name="quanxian[]"  value="<?=$key?>" <?php if ($val['state'] == '1') {echo 'checked=""';}?>><?=$val['name']?></div>
         <?php }?>
        </td>
    </tr>

     <tr  class="m_bc_ed">
    <td  class="m_co_ed"  width="120">現金系統</td>
        <td>
            <?php foreach ($cash as $key => $val) {
	?>
            <div  class="menu_item">
              <input  type="checkbox"  name="quanxian[]"  value="<?=$key?>" <?php if ($val['state'] == '1') {echo 'checked=""';}?>><?=$val['name']?></div>
         <?php }?>

        </td>
    </tr>

     <tr  class="m_bc_ed">
    <td  class="m_co_ed"  width="120">其他<span  id="msg"></span></td>
        <td>
               <?php foreach ($other as $key => $val) {
	?>
            <div  class="menu_item">
              <input  type="checkbox"  name="quanxian[]"  value="<?=$key?>" <?php if ($val['state'] == '1') {echo 'checked=""';}?>><?=$val['name']?></div>
         <?php }?>

        </td>
    </tr>
<!--        <tr  class="m_bc_ed">
       <td  class="m_co_ed"  width="120">详细操作</td>
        <td>
        <input  type="checkbox"  name="me[]"  value="5">編輯會員(
        <input  type="checkbox"  name="me[]"  value="1">姓名
        <input  type="checkbox"  name="me[]"  value="2">出生日期
        <input  type="checkbox"  name="me[]"  value="3">银行账号
        <input  type="checkbox"  name="me[]"  value="4">取款密码)

        </td>
      </tr> -->
      <tr  class="m_bc_ed"  align="center">
        <td  colspan="3"  class="m_bc_td">
        <input name="id" value="<?=$_GET['uid']?>"  type="hidden">
        <input  type="submit"  name="saveBtn"  value="確定"  class="za_button">
       <!--    &nbsp;&nbsp;&nbsp;
          <input  type="button"  id="FormsButton2"  name="FormsButton2"  value="取消"   class="za_button"> --></td>
      </tr>
    </tbody></table>
  </form>
</div>


<!-- 公共尾部 -->
<?php require "../common_html/footer.php";?>
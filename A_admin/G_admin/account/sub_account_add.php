<?php
include_once "../../include/config.php";
include_once "../common/login_check.php";
include_once "../comm_menu.php";

$sys = M('sys_admin',$db_config);

//读取对应账号信息
if (!empty($_GET['uid'])) {
  $rows = M('sys_admin', $db_config)
        ->where("uid = '" . $_GET['uid'] . "'")
        ->find();
}


//账号权限判断
if (isset($_SESSION["adminid"])) {
    //去掉账号管理
  unset($menu['a03']);
  //取第一个字母判断大类是否显示
  foreach ($menu as $key => $val) {
    $menu_w = substr($key, 0, 1);
    if (!empty($_GET['uid'])) {
      $quanxianD = M('sys_admin', $db_config)->field('quanxian')->where("uid = '" . $_GET['uid'] . "'")->find();
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
    }
  }
}

//账号 处理
switch ($_GET['action']) {
  case 'save':
    $username = SITEID.$_POST['login_name'];
    $is_name = $sys->where("login_name='".$username."'")->find();
    if($is_name){
       message("账号已存在!");
    }
    if ($_POST['repasswd'] != $_POST['login_pwd']) {
        message("两次密码不对");
    }
    $dataS = array();
    $dataS['about'] = $_POST['about'];
    $dataS['quanxian'] = implode(',', $_POST["quanxian"]);
    if (!empty($_POST['admin_id'])) {
      //不为空表示更新
      if ($_POST['login_pwd'] != '******') {
        ///留空表示不更新
        $dataS['login_pwd'] = md5(md5(trim($_POST['login_pwd'])));
      }

      $stateS = $sys->where("uid = '".$_POST['admin_id']."'")
              ->update($dataS);
     if ($stateS) {
        admin::insert_log($_SESSION["adminid"], $_SESSION['login_name'], "成功修改了代理后台管理员 " . $_POST['login_name']);
        //需要重新登录，直接踢下线
        admin::make_offline($uid);
        message('修改成功', 'sub_account.php');
     }else{
        admin::insert_log($_SESSION["adminid"], $_SESSION['login_name'], "修改了后台管理员失败 " . $_POST['login_name'])."失败";
        message('修改失败', 'sub_account.php');
      }

    } else {
      //新增加
      $dataS['admin_url'] = $_SERVER['SERVER_NAME'];
      $dataS['site_id'] = SITEID;
      $dataS['agent_id'] = $_SESSION['agent_id'];
      $dataS['type'] = 0;
      $dataS['login_pwd'] = md5(md5($_POST['login_pwd']));
      $dataS['login_name'] = SITEID.$_POST['login_name'];
      $dataS['login_name_1'] = $_POST['login_name'];//登陆账号
      $dataS['add_date'] = date("Y-m-d H:i:s");  //新增时间
      
      $state = $sys->add($dataS);
      if ($state) {
          admin::insert_log($_SESSION["adminid"], $_SESSION['login_name'], "添加了代理后台管理员 " . $_POST['login_name']);
          message('添加成功', 'sub_account.php');
      }else{
          admin::insert_log($_SESSION["adminid"], $_SESSION['login_name'], "添加了代理后台管理员失败 " . $_POST['login_name']);
          message('添加失败', 'sub_account.php');
      }

     
    }
    break;
}
?>
<?php require "../common_html/header.php";?>
<body>

<div  id="con_wrap">
  <div  class="input_002">管理子帳號</div>
  <div  class="con_menu">
  <a  href="javascript:history.go(-1);">返回上一頁</a> </div>
</div>
<div  class="content">
  <form  id="myFORM"  name="form1"  action="<?=$_SERVER['PHP_SELF']?>?action=save"  method="POST">
    <table  width="780"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab_ed">
      <tbody><tr  class="m_title_edit">
        <td  colspan="3">基本資料設定</td>
      </tr>

      <tr  class="m_bc_ed">
        <td  class="m_co_ed"  width="120">所屬公司：</td>
        <td  width="200"><?=COMPANY_NAME?></td>
        <td></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">帳號：</td>
        <td>
       <input  name="login_name" id="login_name"  type="text" value="<?=$rows["login_name"]?>"
        maxlength="12" <?php if (!empty($_GET['uid'])) {echo "readonly";}?>  class="za_text"></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">密碼：</td>
        <td><input  type="PASSWORD"  datatype="s6-12"  id="passwd"  name="login_pwd"  nullmsg="請輸入密碼"  errormsg="密碼必須至少6個字元長，最多12個字元長，并只能有數字(0-9)，及英文大小寫字母"  value="******"  size="12"  maxlength="12"  class="za_text"></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">確認密碼：</td>
        <td><input  type="PASSWORD"  name="repasswd"  datatype="*"  recheck="passwd"  nullmsg="請輸入密碼"  errormsg="您两次输入的账号密码不一致！"  value="******"  size="12"  maxlength="12"  class="za_text"></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>
      <tr  class="m_bc_ed">
        <td  class="m_co_ed">名稱：</td>
        <td><input  type="text"  <?php if (!empty($_GET['action']) && $_GET['action'] == 'update') {echo "readonly";}?>  value="<?=$rows["about"]?>" name="about" id="about" datatype="*" class="za_text"></td>
        <td><div  class="Validform_checktip"></div></td>
      </tr>

    </tbody></table>
    <table  width="780"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab_ed">
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
      <tr  class="m_bc_ed"  align="center">
        <td  colspan="3"  class="m_bc_td">
        <input name="admin_id" value="<?=$_GET['uid']?>"  type="hidden">
        <input  type="submit"  name="saveBtn"  value="確定"  class="za_button">
       <!--    &nbsp;&nbsp;&nbsp;
          <input  type="button"  id="FormsButton2"  name="FormsButton2"  value="取消"   class="za_button"> --></td>
      </tr>
    </tbody></table>
  </form>
</div>


<!-- 公共尾部 -->
<?php require "../common_html/footer.php";?>

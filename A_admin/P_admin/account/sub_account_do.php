<?php
include_once "../../include/config.php";
include_once "../common/login_check.php";

$Sys = M('sys_admin',$db_config);

//账号 处理
$dataS = array();
if ($_POST['login_pwd'] != '******') {
    ///留空表示不更新
    if ($_POST['repasswd'] != $_POST['login_pwd']) {
        message("两次密码不对");
    }
    $dataS['login_pwd'] = md5(md5(trim($_POST['login_pwd'])));
}

//获取后台域名
$admin_url = M('web_config',$db_config)->where("site_id = '".SITEID."'")->getField('admin_url');

if (empty($_POST['id'])) {
    //为空表示添加
    $is_name = $Sys->where("login_name_1 ='".$_POST['login_name']."'")->find();
    if($is_name){
       message("账号已存在!");
    }
    $dataS['about'] = $_POST['about'];
    $dataS['quanxian'] = implode(',', $_POST["quanxian"]);
    $dataS['login_name'] = SITEID.$_POST['login_name'];
    $dataS['admin_url'] = $admin_url;
    $dataS['site_id'] = SITEID;
    $dataS['agent_id'] = 0;
    $dataS['type'] = 0;
    $dataS['login_pwd'] = md5(md5($_POST['login_pwd']));
    $dataS['login_name_1'] = $_POST['login_name'];
    $dataS['add_date'] = date("Y-m-d H:i:s");  

    $state = $Sys->add($dataS);
    if ($state) {
        admin::insert_log($_SESSION["adminid"], $_SESSION['login_name'], "添加了后台管理员 " . $_POST['login_name']);
        message('添加成功', 'sub_account.php');
    }else{
        admin::insert_log($_SESSION["adminid"], $_SESSION['login_name'], "添加了后台管理员失败 " . $_POST['login_name']);
        message('添加失败', 'sub_account.php');
    }
}else{
    $oldData = $Sys->where("uid = '".$_POST['id']."'")->find();
    if ($oldData['about'] != $_POST['about']) {
        $dataS['about'] = $_POST['about'];
    }
    $dataS['quanxian'] = implode(',', $_POST["quanxian"]);
    $dataS['updatetime'] = time();

    
    //更新
    $stateS = $Sys
          ->where("uid = '".$_POST['id']."'")
          ->update($dataS);
    if ($stateS) {
        admin::insert_log($_SESSION["adminid"], $_SESSION['login_name'], "成功修改了后台管理员 " . $_POST['login_name']);
        //需要重新登录，直接踢下线
        admin::make_offline($_POST['id']);
        message('修改成功', 'sub_account.php');
     }else{
        admin::insert_log($_SESSION["adminid"], $_SESSION['login_name'], "修改了后台管理员 " . $_POST['login_name'])."失败";
        message('修改失败', 'sub_account.php');
      }
}

?>
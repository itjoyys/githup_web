<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-11-10 13:46:43
         compiled from "D:\phpStudy\WWW\A_admin_ci\G_admin\cyj_web\views\set_pwd.html" */ ?>
<?php /*%%SmartyHeaderCode:30880564184c344bfe1-67861895%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f7cf2b5f259a98655c737017c13a28e94d552e72' => 
    array (
      0 => 'D:\\phpStudy\\WWW\\A_admin_ci\\G_admin\\cyj_web\\views\\set_pwd.html',
      1 => 1446096096,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '30880564184c344bfe1-67861895',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'site_url' => 0,
    'login_name' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_564184c34bd487_07058368',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_564184c34bd487_07058368')) {function content_564184c34bd487_07058368($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("web_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>
<body>
<?php echo '<script'; ?>
 language="javascript">
function check_sub(){
  var p1 = document.getElementById("password").value;
  var p2 = document.getElementById("password2").value;
  if(p1.length < 1){
    alert('请输入密码');
    document.getElementById("password").focus();
    return false;
  }

  if(p1 != p2){
    alert("两次密码输入不一致");
    document.getElementById("password2").select();
    return false;
  }

  return true;
}
 <?php echo '</script'; ?>
>
<div  id="con_wrap">
  <div  class="input_002">管理員管理--修改</div>
</div>
<div  class="content">
  <form onSubmit="return check_sub();" action="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/index/set_pwd_do" method="post" name="form1">
  <table  width="780"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab_ed">
    <tbody>
    <tr  class="m_title_over_co">
      <td  colspan="2">基本資料設定</td>
    </tr>
    <tr  class="even">
      <td style="float: right;height: 30px;line-height: 30px;"> 帳號：</td>
      <td><?php echo $_smarty_tpl->tpl_vars['login_name']->value;?>
</td>
    </tr>
    <tr >
      <td style="float: right;height: 30px;line-height: 30px;">密碼：</td>
      <td>
        <input  type="password" id="password" type="password" name="password"  value="" maxlength="12" minlength="6" class="za_text" >
        <font color="red">【数字或英文字母,长度6到12位】</font>
      </td>
    </tr>
    <tr  class="even">
      <td style="float: right;height: 30px;line-height: 30px;">確認密碼：</td>
      <td>
        <input  id="password2" type="password"  name="password2"  size="12"  value="" maxlength="12" minlength="6"  class="za_text" >
      </td>
    </tr>
    <tr class="m_bc_ed"  align="center">
      <td  colspan="2"  class="m_bc_td">
        <input  name="s" type="submit" id="s" value="確定"  class="za_button">
      </td>
    </tr>
  </tbody>
  </table>
  </form>

</div>
<!-- 公共尾部 -->
<?php echo '<?php'; ?>
 require("./common_html/footer.php"); <?php echo '?>'; ?>
<?php }} ?>

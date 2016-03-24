<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-11-10 13:20:23
         compiled from "D:\phpStudy\WWW\A_admin_ci\G_admin\cyj_web\views\other\notice.html" */ ?>
<?php /*%%SmartyHeaderCode:894856417e9740c0a7-03451587%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '430f3871658ddbeeebec29bf326d37c3743a21e7' => 
    array (
      0 => 'D:\\phpStudy\\WWW\\A_admin_ci\\G_admin\\cyj_web\\views\\other\\notice.html',
      1 => 1444559221,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '894856417e9740c0a7-03451587',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sp' => 0,
    'val' => 0,
    'fc' => 0,
    'vd' => 0,
    'wh' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_56417e974a07d3_94912981',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56417e974a07d3_94912981')) {function content_56417e974a07d3_94912981($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("web_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>
<body>
<style type="text/css">
.ntext{
    text-align: left;
    padding: 2px;
   background:#fff;
}
.ntext td{
    border-right: 1px solid rgb(250, 216, 217);
    border-bottom: 1px solid rgb(250, 216, 217);
}
</style>
<div id="con_wrap">
<div class="input_002">公告信息</div>
</div>
<div class="content">
<table border="0" cellspacing="0" cellpadding="0" width="25%" style="float:left;clear:none;padding:2px;">
  <tbody>
  <tr class="m_title_over_co">
    <td colspan="2" >體育公告</td>
  </tr>
<?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['sp']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
?>
      <tr class="ntext">
    <td align="center" width="110px;">
      <?php echo $_smarty_tpl->tpl_vars['val']->value['notice_date'];?>

    </td>
    <td class="ntext">
      <?php echo $_smarty_tpl->tpl_vars['val']->value['notice_content'];?>

    </td>
  </tr>
  <?php } ?>

</tbody></table>
<table border="0" cellspacing="0" cellpadding="0" width="25%" style="float:left;clear:none;padding:2px;">

  <tbody><tr class="m_title_over_co">
    <td colspan="2">彩票公告</td>

  </tr>
   <?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['fc']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
?>
      <tr class="ntext">
    <td align="center" width="110px;">
      <?php echo $_smarty_tpl->tpl_vars['val']->value['notice_date'];?>

    </td>
    <td>
      <?php echo $_smarty_tpl->tpl_vars['val']->value['notice_content'];?>

    </td>

  </tr>
  <?php } ?>

</tbody></table>
<table border="0" cellspacing="0" cellpadding="0" width="25%" style="float:left;clear:none;padding:2px;">

  <tbody><tr class="m_title_over_co">
    <td colspan="2">視訊公告</td>

  </tr>
  <?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['vd']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
?>
      <tr class="ntext">
    <td align="center" width="110px;">
      <?php echo $_smarty_tpl->tpl_vars['val']->value['notice_date'];?>

    </td>
    <td>
      <?php echo $_smarty_tpl->tpl_vars['val']->value['notice_content'];?>

    </td>

  </tr>
 <?php } ?>


</tbody></table>
<table border="0" cellspacing="0" cellpadding="0" width="25%" style="float:left;clear:none;padding:2px;">

  <tbody><tr class="m_title_over_co">
    <td colspan="2">维护公告</td>

  </tr>
   <?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['wh']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
?>
      <tr class="ntext">
    <td align="center" width="110px;">
      <?php echo $_smarty_tpl->tpl_vars['val']->value['notice_date'];?>

    </td>
    <td>
      <?php echo $_smarty_tpl->tpl_vars['val']->value['notice_content'];?>

    </td>
  </tr>
  <?php } ?>
</tbody></table>

</div>

<!-- 公共尾部 -->
<?php echo $_smarty_tpl->getSubTemplate ("web_footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php }} ?>

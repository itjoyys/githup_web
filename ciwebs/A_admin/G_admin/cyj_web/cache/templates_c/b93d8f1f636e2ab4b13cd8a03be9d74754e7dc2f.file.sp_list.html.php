<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-11-10 13:17:59
         compiled from "D:\phpStudy\WWW\A_admin_ci\G_admin\cyj_web\views\other\sp_list.html" */ ?>
<?php /*%%SmartyHeaderCode:1895356417e07962624-44111947%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b93d8f1f636e2ab4b13cd8a03be9d74754e7dc2f' => 
    array (
      0 => 'D:\\phpStudy\\WWW\\A_admin_ci\\G_admin\\cyj_web\\views\\other\\sp_list.html',
      1 => 1444640113,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1895356417e07962624-44111947',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'site_url' => 0,
    'data' => 0,
    'i' => 0,
    'spTitle' => 0,
    'spval' => 0,
    'v' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_56417e079e7342_82721105',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56417e079e7342_82721105')) {function content_56417e079e7342_82721105($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("common_html/header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<body>
<div id="con_wrap">
<div class="input_002">限額/退水</div>
<div class="con_menu">
<form method="get" action="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/other/system/limit_index" name="action_form" id="queryform">
類型：
<select onchange="document.getElementById('queryform').submit()" name="type">
  <option value="sp" <?php echo select_check('sp',$_GET['type']);?>
>體育</option>
  <option value="fc" <?php echo select_check('fc',$_GET['type']);?>
>彩票</option>
</select>
</form>
</div>
<div class="content">
<?php  $_smarty_tpl->tpl_vars['spval'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['spval']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['spval']->key => $_smarty_tpl->tpl_vars['spval']->value) {
$_smarty_tpl->tpl_vars['spval']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['spval']->key;
?>
<table width="880" border="0" cellspacing="0" cellpadding="0" class="m_tab">
  <tbody>
  <tr class="m_title_over_co">
    <td height="25"><?php echo $_smarty_tpl->tpl_vars['spTitle']->value[$_smarty_tpl->tpl_vars['i']->value];?>
</td>
      <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['spval']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
      <td><?php echo $_smarty_tpl->tpl_vars['v']->value['t_name'];?>
</td>
      <?php } ?>
  </tr>
  <tr>
   <td nowrap="" align="center">退水設定</td>
        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['spval']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
      <td><?php echo $_smarty_tpl->tpl_vars['v']->value['water_break'];?>
</td>
    <?php } ?>
  <tr>
    <tr>
   <td nowrap="" align="center">單場限額:</td>
        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['spval']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
      <td><?php echo $_smarty_tpl->tpl_vars['v']->value['single_field_max'];?>
</td>
    <?php } ?>
  <tr>
    <tr>
   <td nowrap="" align="center">單注限額::</td>
        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['spval']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
      <td><?php echo $_smarty_tpl->tpl_vars['v']->value['single_note_max'];?>
</td>
    <?php } ?>
  <tr>
       <tr>
   <td nowrap="" align="center">最低限额::</td>
        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['spval']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
      <td><?php echo $_smarty_tpl->tpl_vars['v']->value['min'];?>
</td>
    <?php } ?>
  <tr>
</tbody></table>
<?php } ?>
<br>

<!-- 公共尾部 -->
<?php echo '<?php'; ?>
 require("../common_html/footer.php"); }>
<?php }} ?>

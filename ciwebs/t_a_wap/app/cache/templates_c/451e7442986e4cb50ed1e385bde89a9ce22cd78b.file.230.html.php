<?php /* Smarty version Smarty-3.1.21-dev, created on 2016-01-17 01:50:35
         compiled from "/home/wwwuser/public_html/index_ci/t_wap/views//lottery/liuhecai/230.html" */ ?>
<?php /*%%SmartyHeaderCode:1928800052569b2bab25eaf1-93399296%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '451e7442986e4cb50ed1e385bde89a9ce22cd78b' => 
    array (
      0 => '/home/wwwuser/public_html/index_ci/t_wap/views//lottery/liuhecai/230.html',
      1 => 1452763689,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1928800052569b2bab25eaf1-93399296',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'gamename' => 0,
    'list' => 0,
    'v' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_569b2bab2d2c20_02571120',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_569b2bab2d2c20_02571120')) {function content_569b2bab2d2c20_02571120($_smarty_tpl) {?><ion-content  class="tp40">
<div data-title="<?php echo $_smarty_tpl->tpl_vars['gamename']->value;?>
" data-title2="<?php echo $_GET['gamename2'];?>
"
	class="text-left">
	<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
	<div class="row">
		<div class="col bet ban-bo" data-id="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
">
			<div class="bet-content"><?php echo $_smarty_tpl->tpl_vars['v']->value['input_name'];?>
</div>
			<div class="bet-item"></div>
			<div class="bet-qiu">
				<div ng-bind-html="'<?php echo func_get_shenxiao($_smarty_tpl->tpl_vars['v']->value['type_id'],2015,$_smarty_tpl->tpl_vars['v']->value['input_name']);?>
'|yd:'3'"></div>
			</div>
		</div>
	</div>

	<?php } ?>
</div>
</ion-content><?php }} ?>

<?php /* Smarty version Smarty-3.1.21-dev, created on 2016-01-17 01:50:24
         compiled from "/home/wwwuser/public_html/index_ci/t_wap/views//lottery/liuhecai/232.html" */ ?>
<?php /*%%SmartyHeaderCode:145923985569b2ba07d25a8-24420770%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bafecff300df02e3bdc4803777c1212cbb71aa62' => 
    array (
      0 => '/home/wwwuser/public_html/index_ci/t_wap/views//lottery/liuhecai/232.html',
      1 => 1452763689,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '145923985569b2ba07d25a8-24420770',
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
  'unifunc' => 'content_569b2ba0848785_50811330',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_569b2ba0848785_50811330')) {function content_569b2ba0848785_50811330($_smarty_tpl) {?><ion-content  class="tp40">
<div data-title="<?php echo $_smarty_tpl->tpl_vars['gamename']->value;?>
"
  data-title2="<?php echo $_GET['gamename2'];?>
"	class="text-left">
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

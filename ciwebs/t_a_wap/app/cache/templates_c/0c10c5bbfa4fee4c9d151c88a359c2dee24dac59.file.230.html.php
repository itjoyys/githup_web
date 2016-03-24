<?php /* Smarty version Smarty-3.1.21-dev, created on 2016-01-12 03:22:20
         compiled from "D:\php\web_20156\index_ci\t_wap\views\\lottery\liuhecai\230.html" */ ?>
<?php /*%%SmartyHeaderCode:9285694b71a5d02b9-21141404%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0c10c5bbfa4fee4c9d151c88a359c2dee24dac59' => 
    array (
      0 => 'D:\\php\\web_20156\\index_ci\\t_wap\\views\\\\lottery\\liuhecai\\230.html',
      1 => 1452586932,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9285694b71a5d02b9-21141404',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5694b71a6ddb79_36400002',
  'variables' => 
  array (
    'list' => 0,
    'v' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5694b71a6ddb79_36400002')) {function content_5694b71a6ddb79_36400002($_smarty_tpl) {?><ion-content  class="tp40">
<div data-title="<?php echo $_GET['gamename2'];?>
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

<?php /* Smarty version Smarty-3.1.21-dev, created on 2016-01-14 03:43:54
         compiled from "D:\php\web_20156\index_ci\t_wap\views\\lottery\liuhecai\225.html" */ ?>
<?php /*%%SmartyHeaderCode:473756949db45c4de5-55810429%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '46e2de0bf9912972f28a0d4cf66f43e79561a2f7' => 
    array (
      0 => 'D:\\php\\web_20156\\index_ci\\t_wap\\views\\\\lottery\\liuhecai\\225.html',
      1 => 1452757113,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '473756949db45c4de5-55810429',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_56949db4651804_63553753',
  'variables' => 
  array (
    'config' => 0,
    'v1' => 0,
    'gamename' => 0,
    'v' => 0,
    'k' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56949db4651804_63553753')) {function content_56949db4651804_63553753($_smarty_tpl) {?><ion-content class="bet-view">
<?php  $_smarty_tpl->tpl_vars['v1'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v1']->_loop = false;
 $_smarty_tpl->tpl_vars['k1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['config']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v1']->key => $_smarty_tpl->tpl_vars['v1']->value) {
$_smarty_tpl->tpl_vars['v1']->_loop = true;
 $_smarty_tpl->tpl_vars['k1']->value = $_smarty_tpl->tpl_vars['v1']->key;
?>
<div class="row text-center">
	<div class="col title-no"><?php echo $_smarty_tpl->tpl_vars['v1']->value['name'];?>
</div>
</div>

<div data-title="<?php echo $_smarty_tpl->tpl_vars['gamename']->value;?>
" data-title2="<?php echo $_smarty_tpl->tpl_vars['v1']->value['name'];?>
">
<div class="row">
<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['v1']->value['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
	<?php if (is_numeric($_smarty_tpl->tpl_vars['v']->value['input_name'])) {?>
			<div class="col col-33 bet" data-id="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
">
			<div class="bet-content" ng-bind-html='"<?php echo $_smarty_tpl->tpl_vars['v']->value['input_name'];?>
"|yd:"3"'></div>
			<div class="bet-item"></div>
		</div>
		<?php } else { ?>
			<div class="col col-33 bet bet-bottom special" data-id="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
">
				<div class="bet-content"><?php echo $_smarty_tpl->tpl_vars['v']->value['input_name'];?>
</div>
				<div class="bet-item"></div>
			</div>
			<?php }?>
		<?php if (($_smarty_tpl->tpl_vars['k']->value+1)%3==0) {?>  
	</div>
	<div class="row">
	<?php }?>
	<?php } ?>
	</div>
</div>
<?php } ?>
</ion-content><?php }} ?>

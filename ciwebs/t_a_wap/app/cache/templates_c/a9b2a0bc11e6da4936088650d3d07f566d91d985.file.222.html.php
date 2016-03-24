<?php /* Smarty version Smarty-3.1.21-dev, created on 2016-01-14 23:38:19
         compiled from "/home/wwwuser/public_html/index_ci/t_wap/views//lottery/liuhecai/222.html" */ ?>
<?php /*%%SmartyHeaderCode:675288404569869ab65db94-99700383%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a9b2a0bc11e6da4936088650d3d07f566d91d985' => 
    array (
      0 => '/home/wwwuser/public_html/index_ci/t_wap/views//lottery/liuhecai/222.html',
      1 => 1452763689,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '675288404569869ab65db94-99700383',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'gamename' => 0,
    'list' => 0,
    'v' => 0,
    'k' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_569869ab6e7923_67549295',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_569869ab6e7923_67549295')) {function content_569869ab6e7923_67549295($_smarty_tpl) {?><ion-content class="tp40">
	<div class="row text-center">
	<div class="col title-no"><?php echo $_smarty_tpl->tpl_vars['gamename']->value;?>
</div>
</div>
<div data-title="<?php echo $_smarty_tpl->tpl_vars['gamename']->value;?>
" data-title2="<?php echo $_GET['gamename2'];?>
"
	class="text-left">
	<div class="row">
	<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
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
</ion-content><?php }} ?>

<?php /* Smarty version Smarty-3.1.21-dev, created on 2016-01-12 00:53:35
         compiled from "D:\php\web_20156\index_ci\t_wap\views\\lottery\liuhecai\odds.html" */ ?>
<?php /*%%SmartyHeaderCode:2600568f7eb8867dc7-57432092%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c86d13dcc168bd01e9092b52e1428337166ecd04' => 
    array (
      0 => 'D:\\php\\web_20156\\index_ci\\t_wap\\views\\\\lottery\\liuhecai\\odds.html',
      1 => 1452578012,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2600568f7eb8867dc7-57432092',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_568f7eb8952408_86436479',
  'variables' => 
  array (
    'list' => 0,
    'v' => 0,
    'k' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_568f7eb8952408_86436479')) {function content_568f7eb8952408_86436479($_smarty_tpl) {?><ion-content class="tp40">
	<div class="row text-center">
	<div class="col title-no"><?php echo $_GET['gamename2'];?>
</div>
</div>
<div data-title="{{'HongKongCai.LabelTeMa_A'|translate}}"
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

<?php /* Smarty version Smarty-3.1.21-dev, created on 2016-01-15 00:36:15
         compiled from "/home/wwwuser/public_html/index_ci/t_wap/views//lottery/bj_10/odds.html" */ ?>
<?php /*%%SmartyHeaderCode:14916722995698773f3d6ce4-13018838%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dd74ea29e9a70043d112fb0d01ad0831427d876f' => 
    array (
      0 => '/home/wwwuser/public_html/index_ci/t_wap/views//lottery/bj_10/odds.html',
      1 => 1452754869,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14916722995698773f3d6ce4-13018838',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'list' => 0,
    'v' => 0,
    'k' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5698773f465f15_53955792',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5698773f465f15_53955792')) {function content_5698773f465f15_53955792($_smarty_tpl) {?><ion-content class="bet-view">
<div data-title="<?php echo $_GET['gamename'];?>
">
	<div class="row text-center">
		<div class="col title-no"><?php echo $_GET['gamename'];?>
</div>
	</div>
	<div class="row">
	<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
		<div class="col col-50 bet" data-id="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
">
			<span <?php if (is_numeric($_smarty_tpl->tpl_vars['v']->value['input_name'])) {?> class="bet-content round-7 data-<?php echo $_smarty_tpl->tpl_vars['v']->value['input_name'];?>
" <?php } else { ?> class="bet-content"<?php }?> ><?php echo $_smarty_tpl->tpl_vars['v']->value['input_name'];?>
</span> <span class="bet-item"></span>
		</div>
		<?php if (($_smarty_tpl->tpl_vars['k']->value+1)%2==0) {?>  
	</div>
	<div class="row">
	<?php }?>
	<?php } ?>
	</div>
</div>
</ion-content><?php }} ?>

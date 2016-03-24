<?php /* Smarty version Smarty-3.1.21-dev, created on 2016-01-08 03:12:17
         compiled from "D:\php\web_20156\index_ci\t_wap\views\\lottery\cq_ssc\odds.html" */ ?>
<?php /*%%SmartyHeaderCode:11078568f655813a722-15474478%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ee028f81dd954b63955f2a41180668e496e61b08' => 
    array (
      0 => 'D:\\php\\web_20156\\index_ci\\t_wap\\views\\\\lottery\\cq_ssc\\odds.html',
      1 => 1452240734,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11078568f655813a722-15474478',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_568f6558263564_91859223',
  'variables' => 
  array (
    'list' => 0,
    'v' => 0,
    'k' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_568f6558263564_91859223')) {function content_568f6558263564_91859223($_smarty_tpl) {?><ion-content class="bet-view">
<div data-title="{{'Common.DiYiQiu'|translate}}">
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
			<span <?php if (is_numeric($_smarty_tpl->tpl_vars['v']->value['input_name'])) {?> class="bet-content round-1" <?php } else { ?> class="bet-content"<?php }?> ><?php echo $_smarty_tpl->tpl_vars['v']->value['input_name'];?>
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

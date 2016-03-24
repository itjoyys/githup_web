<?php /* Smarty version Smarty-3.1.21-dev, created on 2016-01-21 18:37:15
         compiled from "D:\WWW\web_20156\index_ci\t_wap\views\\lottery\liuhecai\index.html" */ ?>
<?php /*%%SmartyHeaderCode:439156a0b4db726de0-93722389%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f1778441c4ce73433ad70678ba5b91cd3b45c280' => 
    array (
      0 => 'D:\\WWW\\web_20156\\index_ci\\t_wap\\views\\\\lottery\\liuhecai\\index.html',
      1 => 1452602061,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '439156a0b4db726de0-93722389',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'list' => 0,
    'v' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_56a0b4db8366d9_74723529',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56a0b4db8366d9_74723529')) {function content_56a0b4db8366d9_74723529($_smarty_tpl) {?><div class="cur-lottery">
	<div class="perio">
		<div class="pre-perio">
			<div class="col item">
				<span class="span-fl">{{baseInfo.PrePeriodNumber}}{{'LotteryReports.LabelPeriod'|translate}}</span>
				<div ng-click="loop(baseInfo.ShowResult)"
					ng-if="baseInfo.ShowResult!=null" class="div-fr div-mt">
					<span ng-bind-html='baseInfo.ShowResult|yd:"4":"6"'></span> <span
						class="transparent ion-arrow-swap"></span>
				</div>
			</div>
		</div>
		<div class="cur-perio">
			<div class="col item">
				<span>{{baseInfo.CurrentPeriod}}{{'LotteryReports.LabelPeriod'|translate}}
					{{'Common.LabelClosePan'|translate}}:</span> <span class="time">{{baseInfo.CloseTime}}</span>
			</div>
		</div>
	</div>
    <div class="lottery-bet">
        <cur-menus width="74" top="64">
             <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>     
            <cur-menu title="<?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
"
                      url="/lottery/fc_games_type?gameid=<?php echo $_smarty_tpl->tpl_vars['v']->value['gameid'];?>
&LotteryId=<?php echo $_smarty_tpl->tpl_vars['v']->value['fc_type'];?>
&gamename=<?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
"></cur-menu>
            <?php } ?>

        </cur-menus>
    </div>
</div><?php }} ?>

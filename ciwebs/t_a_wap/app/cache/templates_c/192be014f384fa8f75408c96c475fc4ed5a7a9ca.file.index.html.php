<?php /* Smarty version Smarty-3.1.21-dev, created on 2016-01-14 23:38:19
         compiled from "/home/wwwuser/public_html/index_ci/t_wap/views//lottery/liuhecai/index.html" */ ?>
<?php /*%%SmartyHeaderCode:2021691295569869ab044e42-64158170%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '192be014f384fa8f75408c96c475fc4ed5a7a9ca' => 
    array (
      0 => '/home/wwwuser/public_html/index_ci/t_wap/views//lottery/liuhecai/index.html',
      1 => 1452588901,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2021691295569869ab044e42-64158170',
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
  'unifunc' => 'content_569869ab0c3dc9_80352516',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_569869ab0c3dc9_80352516')) {function content_569869ab0c3dc9_80352516($_smarty_tpl) {?><div class="cur-lottery">
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

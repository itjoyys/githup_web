<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-11-10 13:31:19
         compiled from "D:\phpStudy\WWW\A_admin_ci\G_admin\cyj_web\views\cash\blance.html" */ ?>
<?php /*%%SmartyHeaderCode:2705256417e01565742-27948219%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e20428846452ef5cb5641264c8629059f5424c49' => 
    array (
      0 => 'D:\\phpStudy\\WWW\\A_admin_ci\\G_admin\\cyj_web\\views\\cash\\blance.html',
      1 => 1447133476,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2705256417e01565742-27948219',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_56417e015e2768_21064293',
  'variables' => 
  array (
    'blance_on' => 0,
    'blance_off' => 0,
    'u_date' => 0,
    'sum' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56417e015e2768_21064293')) {function content_56417e015e2768_21064293($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("web_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>

<body>
<div id="con_wrap">
  	<div class="input_002">会员餘額統計</div>
  	<div class="con_menu">
	</div>
</div>
<div style="width:900px;">
<form action="" method="POST" name="form">
<table width="100%" border="0" cellpadding="4" cellspacing="1" class="m_tab">
	<tbody>
	<tr class="m_title_over_co">
		<td width="200"><div align="center">游戏额度</div></td>
		<td width="100"><div align="center">启用</div></td>
		<td width="100"><div align="center">停用</div></td>
		<td width="250"><div align="center">更新时间</div></td>
		<td width="100"><div align="center"></div></td>
	</tr>
	<tr class="m_cen even">
		<td><div align="center">系统额度</div></td>
		<td><div align="right" class="EnableBalance"><?php echo $_smarty_tpl->tpl_vars['blance_on']->value['money']+0;?>
</div></td>
		<td><div align="right" class="DisableBalance"><?php echo $_smarty_tpl->tpl_vars['blance_off']->value['money']+0;?>
</div></td>
		<td><div align="center" class="UpdateTime"><?php echo $_smarty_tpl->tpl_vars['u_date']->value;?>
</div></td>
		<td>
		<div align="center">
		<input type="hidden" class="way" value="cash">
		<input type="hidden" class="HallID" value="3380448">
		<input type="submit" class="form_button updateBtn button_d" value="立即更新">
		</div>
		</td>
	</tr>
	<tr class="m_cen">
		<td><div align="center">MG额度</div></td>
		<td><div align="right" class="EnableBalance"><?php echo $_smarty_tpl->tpl_vars['blance_on']->value['mg']+0;?>
</div></td>
		<td><div align="right" class="DisableBalance"><?php echo $_smarty_tpl->tpl_vars['blance_off']->value['mg']+0;?>
</div></td>
		<td><div align="center" class="UpdateTime"><?php echo $_smarty_tpl->tpl_vars['u_date']->value;?>
</div></td>
		<td>
		<div align="center">
		<input type="hidden" class="way" value="cash">
		<input type="hidden" class="HallID" value="3380448">
		<input type="submit" class="form_button updateBtn button_d" value="立即更新">
		</div>
		</td>
	</tr>

	<tr class="m_cen even">
		<td><div align="center">BBIN额度</div></td>
		<td><div align="right" class="EnableBalance"><?php echo $_smarty_tpl->tpl_vars['blance_on']->value['bbin']+0;?>
</div></td>
		<td><div align="right" class="DisableBalance"><?php echo $_smarty_tpl->tpl_vars['blance_off']->value['bbin']+0;?>
</div></td>
		<td><div align="center" class="UpdateTime"><?php echo $_smarty_tpl->tpl_vars['u_date']->value;?>
</div></td>
		<td>
		<div align="center">
		<input type="hidden" class="way" value="cash">
		<input type="hidden" class="HallID" value="3380448">
		<input type="submit" class="form_button updateBtn button_d" value="立即更新">
		</div>
		</td>
	</tr>
	<tr class="m_cen">
		<td><div align="center">AG额度</div></td>
		<td><div align="right" class="EnableBalance"><?php echo $_smarty_tpl->tpl_vars['blance_on']->value['ag']+0;?>
</div></td>
		<td><div align="right" class="DisableBalance"><?php echo $_smarty_tpl->tpl_vars['blance_off']->value['ag']+0;?>
</div></td>
		<td><div align="center" class="UpdateTime"><?php echo $_smarty_tpl->tpl_vars['u_date']->value;?>
</div></td>
		<td>
		<div align="center">
		<input type="hidden" class="way" value="cash">
		<input type="hidden" class="HallID" value="3380448">
		<input type="submit" class="form_button updateBtn button_d" value="立即更新">
		</div>
		</td>
	</tr>
	<tr class="m_cen even">
		<td><div align="center">OG额度</div></td>
		<td><div align="right" class="EnableBalance"><?php echo $_smarty_tpl->tpl_vars['blance_on']->value['og']+0;?>
</div></td>
		<td><div align="right" class="DisableBalance"><?php echo $_smarty_tpl->tpl_vars['blance_off']->value['og']+0;?>
</div></td>
		<td><div align="center" class="UpdateTime"><?php echo $_smarty_tpl->tpl_vars['u_date']->value;?>
</div></td>
		<td>
		<div align="center">
		<input type="hidden" class="way" value="cash">
		<input type="hidden" class="HallID" value="3380448">
		<input type="submit" class="form_button updateBtn button_d" value="立即更新">
		</div>
		</td>
	</tr>
	<tr class="m_cen">
		<td><div align="center">CT额度</div></td>
		<td><div align="right" class="EnableBalance"><?php echo $_smarty_tpl->tpl_vars['blance_on']->value['ct']+0;?>
</div></td>
		<td><div align="right" class="DisableBalance"><?php echo $_smarty_tpl->tpl_vars['blance_off']->value['ct']+0;?>
</div></td>
		<td><div align="center" class="UpdateTime"><?php echo $_smarty_tpl->tpl_vars['u_date']->value;?>
</div></td>
		<td>
		<div align="center">
		<input type="hidden" class="way" value="cash">
		<input type="hidden" class="HallID" value="3380448">
		<input type="submit" class="form_button updateBtn button_d" value="立即更新">
		</div>
		</td>
	</tr>
	<tr class="m_cen even">
		<td><div align="center">LEBO额度</div></td>
		<td><div align="right" class="EnableBalance"><?php echo $_smarty_tpl->tpl_vars['blance_on']->value['lebo']+0;?>
</div></td>
		<td><div align="right" class="DisableBalance"><?php echo $_smarty_tpl->tpl_vars['blance_off']->value['lebo']+0;?>
</div></td>
		<td><div align="center" class="UpdateTime"><?php echo $_smarty_tpl->tpl_vars['u_date']->value;?>
</div></td>
		<td>
		<div align="center">
		<input type="hidden" class="way" value="cash">
		<input type="hidden" class="HallID" value="3380448">
		<input type="submit" class="form_button updateBtn button_d" value="立即更新">
		</div>
		</td>
	</tr>
    <tr>
		<td class="table_bg1" align="right"><div align="center">總計：</div></td>
		<td class="table_bg1"><div align="right" class="EnableBalance">><?php echo $_smarty_tpl->tpl_vars['sum']->value;?>
</div></td>
		<td class="table_bg1"><div align="right" class="DisableBalance"><?php echo $_smarty_tpl->tpl_vars['sum']->value;?>
</div></td>
		<td class="table_bg1"></td>
		<td class="table_bg1">
		</td>
	</tr>

</tbody></table>
 </form></div>


<?php echo $_smarty_tpl->getSubTemplate ("web_footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>

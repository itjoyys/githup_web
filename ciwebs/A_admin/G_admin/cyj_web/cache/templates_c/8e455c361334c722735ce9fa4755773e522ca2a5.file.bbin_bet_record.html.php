<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-11-10 13:29:48
         compiled from "D:\phpStudy\WWW\A_admin_ci\G_admin\cyj_web\views\note\video\bbin_bet_record.html" */ ?>
<?php /*%%SmartyHeaderCode:21823564180cc4f7aa0-04588384%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8e455c361334c722735ce9fa4755773e522ca2a5' => 
    array (
      0 => 'D:\\phpStudy\\WWW\\A_admin_ci\\G_admin\\cyj_web\\views\\note\\video\\bbin_bet_record.html',
      1 => 1446195665,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21823564180cc4f7aa0-04588384',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'site_url' => 0,
    'Company' => 0,
    'start_date' => 0,
    'end_date' => 0,
    'type' => 0,
    'page' => 0,
    'data' => 0,
    'key' => 0,
    'val' => 0,
    'sum_list' => 0,
    'countN' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_564180cc5e5f52_09571739',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_564180cc5e5f52_09571739')) {function content_564180cc5e5f52_09571739($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("web_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<style>
.m_rig td {
	text-align: center;
}

.Company {
	cursor: pointer;
}

.Company, .CompanyOn {
	padding: 3px 5px;
	margin: 0px 5px 0px 0px;
}

.CompanyOn {
	background: #bc5a83;
	color: #ffffff
}
</style>
<?php echo '<script'; ?>
>
	window.onload = function() {
		document.getElementById("page").onchange = function() {

			document.getElementById('myFORM').submit();
		}
	}
	var indexid = "<?php echo $_GET['index_id'];?>
";
	$(document).ready(function(){
		$("#index_id").val(indexid);
	})

	$(function(){
	    $("#index_id").change(function(event) {
	      $("#myFORM").submit();
	    });
	  })
<?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myFORM').submit()
    }
  }
<?php echo '</script'; ?>
>
<body>

	<div id="con_wrap">
		<div class="input_002">
			下注記錄
		</div>
		<div class="con_menu">
			<form id="myFORM"
				action="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/note/Bet_record/video_bet_record"
				method="get" name="FrmData">
 				<a class="Company" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/note/Bet_record/video_bet_record?type=lebo" >LEBO</a>
 				<a class="Company CompanyOn" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/note/Bet_record/video_bet_record?type=bbin" style="color:#fff;">BBIN</a>
 				<a class="Company" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/note/Bet_record/video_bet_record?type=mg">MG</a>
 				<a class="Company" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/note/Bet_record/video_bet_record?type=ct">CT</a>
 				<a class="Company" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/note/Bet_record/video_bet_record?type=ag">AG</a>
 				<a class="Company" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/note/Bet_record/video_bet_record?type=og">OG</a>
				<input type="hidden" name="Company" id="Company"
					value="<?php echo $_smarty_tpl->tpl_vars['Company']->value;?>
"> &nbsp;&nbsp;

                                        <br /> 账號：<input name="username" type="text" value="<?php echo $_GET['username'];?>
"> 日期：<input
					type="text" name="start_date"
					value="<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
" id="start_date" class="za_text Wdate"
					onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})"> --
				<input type="text" name="end_date"
					value="<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
" id="end_date"
					class="za_text Wdate"
					onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})"> &nbsp; <input type="SUBMIT" value="確定"
					class="za_button">
					 <span id="lblTime" style="color: red"></span>
                                         <input type="hidden" name="gid" value="<?php echo $_GET['gid'];?>
">
                                         <input type="hidden" name="did" value="<?php echo $_GET['did'];?>
">
                                         <input type="hidden" name="type" value="<?php echo $_smarty_tpl->tpl_vars['type']->value;?>
">
                                          <?php echo $_smarty_tpl->tpl_vars['page']->value;?>

			</form>
		</div>
	</div>
	<div class="content">

		<table width="100%" border="0" cellspacing="0" cellpadding="0"
			class="m_tab">
			<tbody>

				<tr class="m_title_over_co">
					<td width="120" align="center">派彩時間</td>
					<td width="70" align="center">局號/单號</td>
					<td width="70" align="center">注单号</td>
					<td width="70" align="center">桌号</td>
                    <td width="70" align="center">游戏ID</td>
					<td  align="center">帳號</td>
					<td  align="center">视讯账号</td>
					<td width="70" align="center">游戏结果</td>
					<td width="90" align="center">視訊/游戏類別</td>
					<td width="90" align="center">总投注</td>
					<td width="90" align="center">有效投注</td>
					<td width="90" align="center">退水</td>
					<td width="90" align="center">結果</td>
				</tr>

				<?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
				<tr  class="m_cen <?php if ($_smarty_tpl->tpl_vars['key']->value%2==0) {?>even<?php }?>">
					<td width="120" align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['wagers_date'];?>
</td>
					<td width="70" align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['serial_id'];?>
</td>
					<td width="70" align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['wagers_id'];?>
</td>
					<td width="70" align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['game_code'];?>
</td>
                    <td width="70" align="center" style="color:#04711C;"><?php echo $_smarty_tpl->tpl_vars['val']->value['game_zh'];?>
</td>
					<td  align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['pkusername'];?>
</td>
					<td  align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['username'];?>
</td>
					<td width="70" align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['result'];?>
</td>
					<td width="70" align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['gamekind'];?>
</td>
					<td width="90" align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['betamount'];?>
</td>
					<td width="90" align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['commissionable'];?>
</td>
					<td width="90" align="center">0.00</td>

					<td width="90" align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['payoff'];?>
</td>
				</tr>
				<?php } ?> <?php if (empty($_smarty_tpl->tpl_vars['data']->value)) {?>
				<tr class="m_rig" style="display:;">
					<td height="70" align="center" colspan="14"><font
						color="#3B2D1B">暫無數據。</font></td>
				</tr>
				<?php } else { ?>
				<tr class="m_cen" style="background-Color: #fcdcdc; display:;">
					<td colspan="8" style="text-align: right">小計：</td>
					<td><span id="Nums" class="CountMoney"><?php echo count($_smarty_tpl->tpl_vars['data']->value);?>
</span>
						笔</td>
					<td><span id="BetMoneyAll" class="CountMoney"><?php echo $_smarty_tpl->tpl_vars['sum_list']->value[0];?>
</span></td>
					<td><span id="ValidBetMoneyAll" class="CountMoney"><?php echo $_smarty_tpl->tpl_vars['sum_list']->value[1];?>
</span></td>
					<td><span id="BackMoneyAll" class="CountMoney">0.00</span></td>
					<td><span id="ResultMoneyAll" class="CountMoney"><?php echo $_smarty_tpl->tpl_vars['sum_list']->value[2];?>
</span></td>
				</tr>
				<tr class="m_cen" style="background-Color: #fcdcdc; display:;">
					<td colspan="8" style="text-align: right">总計：</td>
					<td><span id="NumsAll" class="CountMoney"><?php echo $_smarty_tpl->tpl_vars['countN']->value;?>
</span>
						笔</td>
					<td><span id="BetMoneyAll_" class="CountMoney"><?php echo $_smarty_tpl->tpl_vars['sum_list']->value[3];?>
</span></td>
					<td><span id="ValidBetMoneyAll_" class="CountMoney"><?php echo $_smarty_tpl->tpl_vars['sum_list']->value[4];?>
</span></td>
					<td><span id="BackMoneyAll_" class="CountMoney"><?php echo number_format($_smarty_tpl->tpl_vars['data']->value['data']['BackMoneyAll_'],2);?>
</span></td>
					<td><span id="ResultMoneyAll_" class="CountMoney"><?php echo $_smarty_tpl->tpl_vars['sum_list']->value[5];?>
</span></td>
				</tr>
				<?php }?>


			</tbody>
		</table>
	</div>

	<?php echo $_smarty_tpl->getSubTemplate ("web_footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>

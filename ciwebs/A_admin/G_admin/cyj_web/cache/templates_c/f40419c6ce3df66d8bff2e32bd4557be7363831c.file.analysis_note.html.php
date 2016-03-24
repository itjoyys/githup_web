<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-11-10 13:17:03
         compiled from "D:\phpStudy\WWW\A_admin_ci\G_admin\cyj_web\views\cash\analysis\analysis_note.html" */ ?>
<?php /*%%SmartyHeaderCode:2272356417dcf458746-23753493%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f40419c6ce3df66d8bff2e32bd4557be7363831c' => 
    array (
      0 => 'D:\\phpStudy\\WWW\\A_admin_ci\\G_admin\\cyj_web\\views\\cash\\analysis\\analysis_note.html',
      1 => 1447083647,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2272356417dcf458746-23753493',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'site_url' => 0,
    'start_date' => 0,
    'end_date' => 0,
    'data' => 0,
    'key' => 0,
    'val' => 0,
    'wtype' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_56417dcf5277f2_12065821',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56417dcf5277f2_12065821')) {function content_56417dcf5277f2_12065821($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("web_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<body>
	<div id="con_wrap">
		<div class="input_002">
			下注分析
		</div>
		<div class="con_menu">
	<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/cash/member_analysis/effective_index">有效会员列表</a>
	<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/cash/member_analysis/analysis_user">会员查询</a>
    <a style="color:red;" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/cash/member_analysis/analysis_note">下注分析</a>
    <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/cash/member_analysis/analysis_dis">优惠分析</a>
			<form id="myFORM" action="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/cash/member_analysis/analysis_note" method="get" name="FrmData">
             类型：<select name="type">
             <option value="fc" <?php echo select_check('fc',$_GET['type']);?>
>彩票</option>
             <option value="sp" <?php echo select_check('sp',$_GET['type']);?>
>体育</option>
             <option value="ag" <?php echo select_check('ag',$_GET['type']);?>
>AG视讯</option>
             <option value="og" <?php echo select_check('og',$_GET['type']);?>
>OG视讯</option>
             <option value="mg" <?php echo select_check('mg',$_GET['type']);?>
>MG视讯</option>
            <!--  <option value="mgdz" <?php echo select_check('mgdz',$_GET['type']);?>
>MG电子</option> -->
             <option value="ct" <?php echo select_check('vd',$_GET['type']);?>
>CT视讯</option>
             <option value="lebo" <?php echo select_check('lebo',$_GET['type']);?>
>LEBO视讯</option>
             <option value="bbin" <?php echo select_check('bbin',$_GET['type']);?>
>BB视讯</option>
            <!--  <option value="bbdz" <?php echo select_check('bbdz',$_GET['type']);?>
>BB电子</option> -->
             </select>&nbsp;
             <select name="atype">
             <option value="0" <?php echo select_check('0',$_GET['atype']);?>
>会员账号</option>
             <option value="1" <?php echo select_check('1',$_GET['atype']);?>
>代理账号</option>
             </select>
             <input name="username" type="text" value="<?php echo $_GET['username'];?>
"> 日期：<input
					type="text" name="start_date"
					value="<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
" id="start_date" class="za_text Wdate"
					onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})"> --
				<input type="text" name="end_date"
					value="<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
" id="end_date"
					class="za_text Wdate"
					onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})"> &nbsp;
				排序：<select name="order">
			 <option value="1" <?php echo select_check('1',$_GET['order']);?>
>总下注</option>
             <option value="2" <?php echo select_check('2',$_GET['order']);?>
>总笔数</option>
             <option value="3" <?php echo select_check('3',$_GET['order']);?>
>总派彩</option>
             </select>
			<input type="SUBMIT" value="確定" class="za_button">
            <font color="red">【查询区间最大两个月,胜率50%以上为高,30%到50%为中,30%以下为低】</font>
			</form>
		</div>
	</div>
	<div class="content">

		<table width="100%" border="0" cellspacing="0" cellpadding="0"
			class="m_tab">
			<tbody>

				<tr class="m_title_over_co">
					<td  align="center">会员账号</td>
					<td  align="center">代理商</td>
					<td  align="center">总投注</td>
					<td  align="center">有效投注</td>
					<td  align="center">总派彩</td>
					<td  align="center">总笔数</td>
					<td  align="center">赢(笔)</td>
					<td  align="center">输(笔)</td>
					<td  align="center">胜率</td>
					<td  align="center">等级</td>
					<td  align="center">結果</td>
				</tr>

				<?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
				<tr class="m_cen <?php if ($_smarty_tpl->tpl_vars['key']->value%2==0) {?>even<?php }?>">
					<td  align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['username'];?>
</td>
					<td  align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['agent_user'];?>
</td>
					<td  align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['all_bet'];?>
</td>
					<td  align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['bet'];?>
</td>
					<td  align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['win'];?>
</td>
					<td  align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['num'];?>
</td>
					<td  align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['win_num'];?>
</td>
					<td  align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['lose_num'];?>
</td>
					<td  align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['win_lose'];?>
</td>
					<td  align="center"><?php echo $_smarty_tpl->tpl_vars['val']->value['level'];?>
</td>
					<td  align="center"><?php if ($_smarty_tpl->tpl_vars['wtype']->value==1) {
echo $_smarty_tpl->tpl_vars['val']->value['win'];
} else {
echo $_smarty_tpl->tpl_vars['val']->value['win']-$_smarty_tpl->tpl_vars['val']->value['bet'];
}?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>

<?php echo $_smarty_tpl->getSubTemplate ("web_footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>

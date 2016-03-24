<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-11-10 13:16:18
         compiled from "D:\phpStudy\WWW\A_admin_ci\G_admin\cyj_web\views\cash\cash_count.html" */ ?>
<?php /*%%SmartyHeaderCode:1180656417da258ad39-97849964%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd3bbb210b198538204a80cc763ea26bdf8824d89' => 
    array (
      0 => 'D:\\phpStudy\\WWW\\A_admin_ci\\G_admin\\cyj_web\\views\\cash\\cash_count.html',
      1 => 1445843132,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1180656417da258ad39-97849964',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'index_id' => 0,
    'site_url' => 0,
    'now_date' => 0,
    'now_date_12' => 0,
    'sites_str' => 0,
    'timearea' => 0,
    's_date' => 0,
    'e_date' => 0,
    'now_week' => 0,
    'now_week_l' => 0,
    'now_week_n' => 0,
    'bank_line_inurl' => 0,
    'income_line_data' => 0,
    'out_url' => 0,
    'user_out_money' => 0,
    'bank_ol_inurl' => 0,
    'income_ol_data' => 0,
    'discount_fav' => 0,
    'catm_url' => 0,
    'income_catm_data' => 0,
    'catmM_url' => 0,
    'income_catm_odata' => 0,
    'take_off_data' => 0,
    'dis_count_data' => 0,
    'yinkui_money' => 0,
    'all_own_money' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_56417da2607d52_56158169',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56417da2607d52_56158169')) {function content_56417da2607d52_56158169($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("web_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>
<body>
<?php echo '<script'; ?>
>
$(document).ready(function(){
	$("#index_id").val('<?php echo $_smarty_tpl->tpl_vars['index_id']->value;?>
');
    $("#index_id").change(function(event) {
	      //$("#myform").attr("action","<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/cash/discount/index");
	      $("#queryform").submit();
	});
})

function chg_date(range,num1,num2){
if(range=='t'){
	queryform.date_start.value ='<?php echo $_smarty_tpl->tpl_vars['now_date']->value;?>
';
	queryform.date_end.value =queryform.date_start.value;
}

if(range!='t'){
 if(queryform.date_start.value!=queryform.date_end.value){ FrmData.date_start.value ='<?php echo $_smarty_tpl->tpl_vars['now_date_12']->value;?>
'; queryform.date_end.value =queryform.date_start.value;}
 var aStartDate = queryform.date_start.value.split('-');
 var newStartDate = new Date(parseInt(aStartDate[0], 10),parseInt(aStartDate[1], 10) - 1,parseInt(aStartDate[2], 10) + num1);

    if (newStartDate.getMonth() < 9) {
         tmp_date = newStartDate.getFullYear()+ '-0' + (newStartDate.getMonth() + 1);
    }else{
        tmp_date = newStartDate.getFullYear()+ '-' + (newStartDate.getMonth() + 1);
    }
      if (newStartDate.getDate() < 9) {
        queryform.date_start.value = tmp_date + '-0' + (newStartDate.getDate());
    }else{
        queryform.date_start.value = tmp_date+ '-' + (newStartDate.getDate());
    }

 var aEndDate = queryform.date_end.value.split('-');
 var end_tmp_date;
 var newEndDate = new Date(parseInt(aEndDate[0], 10),parseInt(aEndDate[1], 10) - 1,parseInt(aEndDate[2], 10) + num2);

    if (newEndDate.getMonth() < 9) {
         end_tmp_date = newEndDate.getFullYear()+ '-0' + (newEndDate.getMonth() + 1);
    }else{
         end_tmp_date = newEndDate.getFullYear()+ '-' + (newEndDate.getMonth() + 1);
    }
      if (newEndDate.getDate() < 9) {
        queryform.date_end.value = end_tmp_date + '-0' + (newEndDate.getDate());
    }else{
        queryform.date_end.value = end_tmp_date+ '-' + (newEndDate.getDate());
    }
}
}

 function getthis(){

	  var d_now = Date(today);
	  var year = today.split('-')[0];

	  for(i=1;i<=13;i++){
		  target_str = $("#"+year+'_'+i).html();
		  var data_arr = target_str.split('~');
		  var start = $.trim(data_arr[0].toString());
		  var end = $.trim(data_arr[1].toString());

		  var d1 = new Date(start);
		  var d2 = new Date(end);
		  if((Date.parse(d_now) - Date.parse(d1))>=0 && (Date.parse(d_now) -86400000 - Date.parse(d2))<=0){
			  return(year+'_'+i);
		  }
	  }
  }
 /**
   * 此方法计算本期或者上个期的第一天和最后一天并返回相应的日期格式
 */
function changMonth(timea)
{
	var   month = getthis();

	var target = '';
	var target_str = '';
	if(timea == 'last'){
		var month_arr = month.split('_');
		if(parseInt(month_arr[1])=='1'){
			var y = parseInt(month_arr[0])-1;
			target = y +'_13';
		}else{
			var m = parseInt(month_arr[1])-1;
			target = month_arr[0]+'_'+ m;
		}
	}else{
		target = month;
	}
	target_str = $("#"+target).html();
	var data_arr = target_str.split('~');
	var start = $.trim(data_arr[0].replace(/\//g,'-'));
	var end = $.trim(data_arr[1].replace(/\//g,'-'));
	document.getElementById('startdate').value = start;
	document.getElementById('enddate').value = end;
}


<?php echo '</script'; ?>
>
<div id="con_wrap">
<div class="input_002">出入账目汇总</div>
<div class="con_menu" style="width:90%">
<form name="queryform" id="queryform" action="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/cash/Cash_count/index" method="get">
<?php echo $_smarty_tpl->tpl_vars['sites_str']->value;?>

    時區:
<select name="timearea">
  <option value="0" <?php echo select_check(0,$_smarty_tpl->tpl_vars['timearea']->value);?>
 >美東</option>
  <option value="12" <?php echo select_check(12,$_smarty_tpl->tpl_vars['timearea']->value);?>
 >北京</option>
</select>
	日期：
	 <input class="za_text Wdate" id="date_start" onClick="WdatePicker()" value="<?php echo $_smarty_tpl->tpl_vars['s_date']->value;?>
" name="date_start">
	 ~
	 <input class="za_text Wdate" id="date_end" onClick="WdatePicker()" value="<?php echo $_smarty_tpl->tpl_vars['e_date']->value;?>
" name="date_end">
	<input type="Button" style="margin-top:5px" class="za_button" onclick="chg_date('t',0,0)" value="今日">
    <input type="Button" style="margin-top:5px" class="za_button" onclick="chg_date('l',-1,-1)" value="昨日">
    <input type="Button" style="margin-top:5px" class="za_button" onclick="queryform.date_start.value='<?php echo $_smarty_tpl->tpl_vars['now_week']->value;?>
';queryform.date_end.value='<?php echo $_smarty_tpl->tpl_vars['now_date']->value;?>
'" value="本周">
    <input type="Button" style="margin-top:5px" class="za_button" onclick="queryform.date_start.value='<?php echo $_smarty_tpl->tpl_vars['now_week_l']->value;?>
';queryform.date_end.value='<?php echo $_smarty_tpl->tpl_vars['now_week_n']->value;?>
'" value="上周">
    &nbsp;&nbsp;&nbsp;
    <input type="submit" name="subbtn" value="查詢" class="button_d">

</form>
</div>
</div>
<div class="content" style="width:880px;">
	<table border="0" width="100%" cellspacing="0" cellpadding="0" class="m_tab">
		<tbody><tr class="m_title_over_co">
			<td>收入</td>
			<td>收入金额</td>
			<td>支出</td>
			<td>支出明细</td>
		</tr>

		<tr class="m_cen even">
			<td>公司入款</td>
			<td><a href="<?php echo $_smarty_tpl->tpl_vars['bank_line_inurl']->value;?>
"><font class="in_font"><?php echo $_smarty_tpl->tpl_vars['income_line_data']->value['deposit_num']+0;?>
</font></a>(<?php echo $_smarty_tpl->tpl_vars['income_line_data']->value['countNum']+0;?>
笔)(<?php echo $_smarty_tpl->tpl_vars['income_line_data']->value['userNum']+0;?>
人)</td>
			<td>会员出款</td>
			<td><a href="<?php echo $_smarty_tpl->tpl_vars['out_url']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['user_out_money']->value['outward_money']+0;?>
</a>(<?php echo $_smarty_tpl->tpl_vars['user_out_money']->value['outNum']+0;?>
笔)(<?php echo $_smarty_tpl->tpl_vars['user_out_money']->value['userNum']+0;?>
人)</td>
		</tr>
		<tr class="m_cen">
			<td>线上支付</td>
			<td><a href="<?php echo $_smarty_tpl->tpl_vars['bank_ol_inurl']->value;?>
"><font class="in_font"><?php echo $_smarty_tpl->tpl_vars['income_ol_data']->value['deposit_num']+0;?>
</font></a>(<?php echo $_smarty_tpl->tpl_vars['income_ol_data']->value['countNum']+0;?>
笔)(<?php echo $_smarty_tpl->tpl_vars['income_ol_data']->value['userNum']+0;?>
人)</td>
			<td>给予优惠</td>
			<td><?php echo $_smarty_tpl->tpl_vars['discount_fav']->value['fav_money']+0;?>
(<?php echo $_smarty_tpl->tpl_vars['discount_fav']->value['fav_num']+0;?>
笔)(<?php echo $_smarty_tpl->tpl_vars['discount_fav']->value['userNum']+0;?>
人)</td>
		</tr>
		<tr class="m_cen even">
			<td>人工存入</td>
			<td><a href="<?php echo $_smarty_tpl->tpl_vars['catm_url']->value;?>
"><font class="in_font"><?php echo $_smarty_tpl->tpl_vars['income_catm_data']->value['catm_money']+0;?>
</font></a>(<?php echo $_smarty_tpl->tpl_vars['income_catm_data']->value['catmNum']+0;?>
笔)(<?php echo $_smarty_tpl->tpl_vars['income_catm_data']->value['userNum']+0;?>
人)</td>
			<td>人工提出</td>
			<td><a href="<?php echo $_smarty_tpl->tpl_vars['catmM_url']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['income_catm_odata']->value['catm_money']+0;?>
</a>(<?php echo $_smarty_tpl->tpl_vars['income_catm_odata']->value['catmNum']+0;?>
笔)(<?php echo $_smarty_tpl->tpl_vars['income_catm_odata']->value['userNum']+0;?>
人)</td>
		</tr>
		<tr class="m_cen">
			<td>会员出款被扣金额</td>
			<td><font class="in_font"><?php echo $_smarty_tpl->tpl_vars['take_off_data']->value['charge']+$_smarty_tpl->tpl_vars['take_off_data']->value['expenese_num']+$_smarty_tpl->tpl_vars['take_off_data']->value['favourable_num']+0;?>
</font>(<?php echo $_smarty_tpl->tpl_vars['take_off_data']->value['outNum']+0;?>
笔) (<?php echo $_smarty_tpl->tpl_vars['take_off_data']->value['userNum']+0;?>
人)</td>
			<td>给予反水</td>
			<td><?php echo $_smarty_tpl->tpl_vars['dis_count_data']->value[0]['money']+0;?>
(<?php echo $_smarty_tpl->tpl_vars['dis_count_data']->value[0]['people_num']+0;?>
笔)(<?php echo $_smarty_tpl->tpl_vars['dis_count_data']->value[1]['userNum']+0;?>
人)
			</td>
		</tr>
		<tr class="m_cen even"><td colspan="15" class="table_bg1" align="center">实际盈亏：<?php echo $_smarty_tpl->tpl_vars['yinkui_money']->value;?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;账目统计：<?php echo $_smarty_tpl->tpl_vars['all_own_money']->value;?>
</td></tr>
	</tbody></table>
</div>
<?php echo $_smarty_tpl->getSubTemplate ("web_footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>

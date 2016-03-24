<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-11-10 13:17:51
         compiled from "D:\phpStudy\WWW\A_admin_ci\G_admin\cyj_web\views\cash\cash_system.html" */ ?>
<?php /*%%SmartyHeaderCode:1464656417dff6ad262-43484934%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6bbd90fda0468bc2abbf95fcf24d78df27961f1f' => 
    array (
      0 => 'D:\\phpStudy\\WWW\\A_admin_ci\\G_admin\\cyj_web\\views\\cash\\cash_system.html',
      1 => 1447057801,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1464656417dff6ad262-43484934',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'index_id' => 0,
    'sites_str' => 0,
    'username' => 0,
    'timearea' => 0,
    's_date' => 0,
    'e_date' => 0,
    'deptype' => 0,
    'page' => 0,
    'record' => 0,
    'i' => 0,
    'val' => 0,
    'count_c' => 0,
    'all_count' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_56417dff745811_61577892',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56417dff745811_61577892')) {function content_56417dff745811_61577892($_smarty_tpl) {?><title>现金系统</title>
<?php echo $_smarty_tpl->getSubTemplate ("web_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>

<body>
<?php echo '<script'; ?>
>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myFORM').submit()
    }
  }
var indexid = "<?php echo $_smarty_tpl->tpl_vars['index_id']->value;?>
";
$(document).ready(function(){
	$("#index_id").val(indexid);
	$("#index_id option[index='0']").remove();

	$("#index_id").change(function(event) {
      $("#myFORM").submit();
    });
})
<?php echo '</script'; ?>
>
<div id="con_wrap">
<div class="input_002">現金系統</div>
<div class="con_menu" style="width:1080px;">
<form id="myFORM" method="get" name="myFORM">
<?php echo $_smarty_tpl->tpl_vars['sites_str']->value;?>

帳號:
<input type="text" name="username" style="min-width:100px;width:100px;"class="za_text" value="<?php echo $_smarty_tpl->tpl_vars['username']->value;?>
">
時區:
<select name="timearea" id="area">
    <option value="0" <?php echo select_check(0,$_smarty_tpl->tpl_vars['timearea']->value);?>
>美东</option>
    <option value="12" <?php echo select_check(12,$_smarty_tpl->tpl_vars['timearea']->value);?>
>北京</option>
    </select>
存入日期:
 <input class="za_text Wdate" onClick="WdatePicker()" value="<?php echo $_smarty_tpl->tpl_vars['s_date']->value;?>
"  name="start_date">至
 <input class="za_text Wdate" onClick="WdatePicker()" value="<?php echo $_smarty_tpl->tpl_vars['e_date']->value;?>
"  name="end_date">
方式:
 <select name="deptype" class="za_select" onchange="document.getElementById('myFORM').submit()">
  <option value="" >全部方式</option>
   <option value="1" <?php echo select_check(1,$_smarty_tpl->tpl_vars['deptype']->value);?>
>額度轉換</option>
  <option value="2" <?php echo select_check(2,$_smarty_tpl->tpl_vars['deptype']->value);?>
>体育下注</option>
  <option value="15" <?php echo select_check(15,$_smarty_tpl->tpl_vars['deptype']->value);?>
>体育派彩</option>
  <option value="3" <?php echo select_check(3,$_smarty_tpl->tpl_vars['deptype']->value);?>
>彩票下注</option>
  <option value="14" <?php echo select_check(14,$_smarty_tpl->tpl_vars['deptype']->value);?>
>彩票派彩</option>
  <option value="wx" <?php echo select_check('wx',$_smarty_tpl->tpl_vars['deptype']->value);?>
>注单无效</option>
  <option value="cel" <?php echo select_check('cel',$_smarty_tpl->tpl_vars['deptype']->value);?>
>注单取消</option>
  <option value="10" <?php echo select_check(10,$_smarty_tpl->tpl_vars['deptype']->value);?>
>线上入款</option>
  <option value="11" <?php echo select_check(11,$_smarty_tpl->tpl_vars['deptype']->value);?>
>公司入款</option>
  <option value="xsqk" <?php echo select_check('xsqk',$_smarty_tpl->tpl_vars['deptype']->value);?>
>线上取款</option>
  <option value="9" <?php echo select_check(9,$_smarty_tpl->tpl_vars['deptype']->value);?>
>优惠退水</option>
  <option value="ot" <?php echo select_check('ot',$_smarty_tpl->tpl_vars['deptype']->value);?>
>优惠活动</option>
  <option value="1-12-3" <?php echo select_check('1-12-3',$_smarty_tpl->tpl_vars['deptype']->value);?>
>人工存入</option>
  <option value="2-12-4" <?php echo select_check('2-12-4',$_smarty_tpl->tpl_vars['deptype']->value);?>
>人工取出</option>
  <option value="12" <?php echo select_check('12',$_smarty_tpl->tpl_vars['deptype']->value);?>
>人工存款與取款</option>
  <option value="in" <?php echo select_check('in',$_smarty_tpl->tpl_vars['deptype']->value);?>
>入款明细</option>
  <option value="out" <?php echo select_check('out',$_smarty_tpl->tpl_vars['deptype']->value);?>
>出款明细</option>
</select>
  <input type="SUBMIT" name="SUBMIT" value="查詢" class="za_button">
 <?php echo $_smarty_tpl->tpl_vars['page']->value;?>
&nbsp;
 </div>
</form>
</div>

<div class="content">
      <table width="100%" cellpadding="0" class="m_tab">
        <tbody><tr class="m_title_over_co">
          <td>會員帳號</td>
          <td>幣別</td>
          <td>类型</td>
          <td>交易别</td>
          <td>交易金額</td>
          <td>余额</td>
          <td>交易日期</td>
          <td>備註</td>
        </tr>
     <?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['record']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
        <tr class="m_cen <?php if ($_smarty_tpl->tpl_vars['i']->value%2==0) {?>even<?php }?>">
          <td><?php echo $_smarty_tpl->tpl_vars['val']->value['username'];?>
</td>
          <td>人民幣</td>
          <td><?php echo $_smarty_tpl->tpl_vars['val']->value['cash_type_zh'];?>
</td>
          <td><?php echo $_smarty_tpl->tpl_vars['val']->value['cash_do_type_zh'];?>
</td>
          <td style="text-align:right"><?php echo $_smarty_tpl->tpl_vars['val']->value['cash_num'];?>

          <?php if ($_smarty_tpl->tpl_vars['val']->value['cash_type']==12||$_smarty_tpl->tpl_vars['val']->value['cash_type']==11||$_smarty_tpl->tpl_vars['val']->value['cash_type']==10||$_smarty_tpl->tpl_vars['val']->value['cash_type']==6||$_smarty_tpl->tpl_vars['val']->value['cash_type']==9) {?>(+<?php echo $_smarty_tpl->tpl_vars['val']->value['discount_num'];?>
)<?php }?>

          </td>
          <td><?php echo $_smarty_tpl->tpl_vars['val']->value['cash_balance'];?>
</td>
          <td><?php echo $_smarty_tpl->tpl_vars['val']->value['cash_date'];?>
</td>
          <td><?php echo $_smarty_tpl->tpl_vars['val']->value['remark'];?>
</td>
        </tr>
        <?php } ?>
        <tr class="m_rig2">
          <td colspan="3" class="count_td"></td>
          <td class="count_td">小計</td>
          <td colspan="5"><?php echo $_smarty_tpl->tpl_vars['count_c']->value;?>
</td>
        </tr>
         <tr class="m_rig2">
          <td colspan="3" class="count_td"></td>
          <td class="count_td">总计</td>
          <td colspan="5"><?php echo ($_smarty_tpl->tpl_vars['all_count']->value['Cnum']+$_smarty_tpl->tpl_vars['all_count']->value['Dnum']);?>
&nbsp;(<?php echo $_smarty_tpl->tpl_vars['all_count']->value['allnum'];?>
笔)</td>
        </tr>
      </tbody></table>
</div>

<?php echo $_smarty_tpl->getSubTemplate ("web_footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>

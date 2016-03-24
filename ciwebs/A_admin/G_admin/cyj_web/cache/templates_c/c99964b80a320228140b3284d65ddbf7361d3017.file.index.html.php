<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-11-10 13:46:21
         compiled from "D:\phpStudy\WWW\A_admin_ci\G_admin\cyj_web\views\account\member_index\index.html" */ ?>
<?php /*%%SmartyHeaderCode:1481956417cecb69373-67356961%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c99964b80a320228140b3284d65ddbf7361d3017' => 
    array (
      0 => 'D:\\phpStudy\\WWW\\A_admin_ci\\G_admin\\cyj_web\\views\\account\\member_index\\index.html',
      1 => 1447134377,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1481956417cecb69373-67356961',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_56417cecc38426_38411314',
  'variables' => 
  array (
    'todayReg_count' => 0,
    'sum_title' => 0,
    'site_url' => 0,
    'i' => 0,
    'totalPage' => 0,
    'list' => 0,
    'rows' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56417cecc38426_38411314')) {function content_56417cecc38426_38411314($_smarty_tpl) {?><title>会员管理</title>
<?php echo $_smarty_tpl->getSubTemplate ("web_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<body>
<?php echo '<script'; ?>
 type="text/javascript">
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
<div  id="con_wrap">
<div  class="input_002" >會員管理(今日注册:<?php echo $_smarty_tpl->tpl_vars['todayReg_count']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['sum_title']->value;?>
)</div>
<div  class="con_menu" style="width:980px;">
<form  name="myFORM"  id="myFORM"  action="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/account/member_index/index"  method="GET">
    状态:
    <select  name="mem_enable"  onchange="document.getElementById('myFORM').submit()" id="mem_type_select"   class="za_select">
      <option  value="">全部</option>
      <option <?php echo select_check('2',$_GET['mem_enable']);?>
 value="2">停用</option>
    </select>
    在线:
     <select name="mem_status" onchange="document.getElementById('myFORM').submit()" id="status_select" class="za_select">
          <option  value="0">全部</option>
          <option <?php echo select_check('1',$_GET['mem_status']);?>
 value="1">在线</option>
     </select>
  &nbsp;注册日期：
              <input class="za_text Wdate" onClick="WdatePicker()" value="<?php echo $_GET['start_date'];?>
" size="10" name="start_date"> - <input  type="text"  name="end_date" id="end_date" readonly  value="<?php echo $_GET['end_date'];?>
" class="za_text Wdate" onClick="WdatePicker()">
              <select  name="search_type" id="utype"  class="za_select">
              <option  value="0" <?php echo select_check(0,$_GET['search_type']);?>
>帳號</option>
              <option  value="4" <?php echo select_check(4,$_GET['search_type']);?>
>注册IP</option>
              <option  value="5" <?php echo select_check(5,$_GET['search_type']);?>
>登陆IP</option>
              <option  value="1" <?php echo select_check(1,$_GET['search_type']);?>
>姓名</option>
              <option  value="2" <?php echo select_check(2,$_GET['search_type']);?>
>手机</option>
              <option  value="3" <?php echo select_check(3,$_GET['search_type']);?>
>银行卡</option>
            </select>
              <input  type="text"  name="search_name"  value="<?php echo $_GET['search_name'];?>
"  class="za_text"  >
              <input  type="submit" value="搜索"   class="za_button">
              頁數：
 <select id="page" name="page" class="za_select">
    <?php $_smarty_tpl->tpl_vars[$_smarty_tpl->tpl_vars['i']->value] = new Smarty_variable(0, null, 0);?>
       <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['total'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['total']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['total']['name'] = 'total';
$_smarty_tpl->tpl_vars['smarty']->value['section']['total']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['totalPage']->value) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['total']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['total']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['total']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['total']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['total']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['total']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['total']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['total']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['total']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['total']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['total']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['total']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['total']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['total']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['total']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['total']['total']);
?>
       <option value="<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable($_smarty_tpl->tpl_vars['i']->value+1, null, 0);
echo $_smarty_tpl->tpl_vars['i']->value;?>
"<?php echo select_check($_smarty_tpl->tpl_vars['i']->value,$_GET['page']);?>
 ><?php echo $_smarty_tpl->tpl_vars['i']->value;?>
</option>
       <?php endfor; endif; ?>
  </select> <?php echo $_smarty_tpl->tpl_vars['totalPage']->value;?>
頁&nbsp;


</form>
<?php echo '<script'; ?>
>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myFORM').submit()
    }
  }
<?php echo '</script'; ?>
>
</div>
</div>
<div  class="content" style="overflow">
  <table  style="table-layout: fixed;width:100%" border="0"  cellspacing="0"  cellpadding="0"  class="m_tab">
    <tbody><tr  class="m_title_over_co">
      <td>状态</td>
      <td>姓名</td>
      <td style="width:95px;">帳號</td>
      <td>系統額度</td>
      <td>LEBO額度</td>
      <td>BBIN額度</td>
      <td>AG額度</td>
      <td>OG額度</td>
      <td>MG額度</td>
      <td>CT額度</td>
      <td style="width:130px;">新增日期</td>
      <td>狀況</td>
      <td style="width:150px;">功能</td>
    </tr>
	<?php  $_smarty_tpl->tpl_vars['rows'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['rows']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['rows']->key => $_smarty_tpl->tpl_vars['rows']->value) {
$_smarty_tpl->tpl_vars['rows']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['rows']->key;
?>
    <tr class="m_cen <?php if ($_smarty_tpl->tpl_vars['i']->value%2==0) {?>even<?php }?>" >
      <td><a  style="color:red"  title="登陸時間:<?php echo $_smarty_tpl->tpl_vars['rows']->value['login_time'];?>
 登陸IP:<?php echo $_smarty_tpl->tpl_vars['rows']->value['login_ip'];?>
"><?php echo $_smarty_tpl->tpl_vars['rows']->value['Online_state'];?>
</a></td>
      <td><?php echo $_smarty_tpl->tpl_vars['rows']->value["pay_name"];?>
</td>
      <td><?php echo $_smarty_tpl->tpl_vars['rows']->value["username"];?>
</td>
      <td  style="text-align:left"  nowrap=""><?php echo $_smarty_tpl->tpl_vars['rows']->value["money"];?>
 </td>
      <td  style="text-align:left"  nowrap=""><?php echo $_smarty_tpl->tpl_vars['rows']->value["lebo_money"];?>
</td>
      <td  style="text-align:left"  nowrap=""><?php echo $_smarty_tpl->tpl_vars['rows']->value["bbin_money"];?>
</td>
      <td  style="text-align:left"  nowrap=""><?php echo $_smarty_tpl->tpl_vars['rows']->value["ag_money"];?>
</td>
      <td  style="text-align:left"  nowrap=""><?php echo $_smarty_tpl->tpl_vars['rows']->value["og_money"];?>
</td>
      <td  style="text-align:left"  nowrap=""><?php echo $_smarty_tpl->tpl_vars['rows']->value["mg_money"];?>
</td>
      <td  style="text-align:left"  nowrap=""><?php echo $_smarty_tpl->tpl_vars['rows']->value["ct_money"];?>
</td>
      <td><?php echo $_smarty_tpl->tpl_vars['rows']->value["reg_date"];?>
</td>
      <td>
         <?php if ($_smarty_tpl->tpl_vars['rows']->value['is_delete']==2) {?>
           <span style="color:#FF00FF;">停用</span>
         <?php } else { ?>
          <span style="color:##1E20CA;">正常</span>
         <?php }?>
      </td>
      <td  align="center">
        <a  href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/note/bet_record/sp_bet_record?username=<?php echo $_smarty_tpl->tpl_vars['rows']->value['username'];?>
&itype=1">下注</a>&nbsp;/&nbsp;
        <a  href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/cash/cash_system/index?username=<?php echo $_smarty_tpl->tpl_vars['rows']->value['username'];?>
&uid=<?php echo $_smarty_tpl->tpl_vars['rows']->value['uid'];?>
">現金</a>
      </td>
    </tr>
    <?php } ?>
  </tbody></table>
</div>
 <?php echo $_smarty_tpl->getSubTemplate ("web_footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>

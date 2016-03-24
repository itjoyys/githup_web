<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-11-10 13:29:35
         compiled from "D:\phpStudy\WWW\A_admin_ci\G_admin\cyj_web\views\account\account_search\index.html" */ ?>
<?php /*%%SmartyHeaderCode:23239564180bf3c0267-23345657%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '39e84a0d46ff19db3af3178143a313a5b07c3c30' => 
    array (
      0 => 'D:\\phpStudy\\WWW\\A_admin_ci\\G_admin\\cyj_web\\views\\account\\account_search\\index.html',
      1 => 1442751576,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '23239564180bf3c0267-23345657',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'site_url' => 0,
    'k' => 0,
    'v' => 0,
    'user' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_564180bf40a5f0_01854975',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_564180bf40a5f0_01854975')) {function content_564180bf40a5f0_01854975($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("web_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<body>
<div  id="con_wrap">
<div  class="input_002">會員體系管理</div>
<div  class="con_menu">
<form  name="myFORM"  id="myFORM" action="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/account/account_search"  method="get">
層級查詢：&nbsp;
<select  name="search_type" id="search_type">
	<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = get_account_level(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
	<option value="<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
" <?php echo select_check($_smarty_tpl->tpl_vars['k']->value,$_GET['search_type']);?>
 ><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
 </option>
	<?php } ?>
</select>

帳號:
<input  type="text"  name="account"  class="za_text"  value="<?php echo $_GET['account'];?>
">&nbsp;&nbsp;
<input  type="submit"  name="subbtn"  class="za_button"  value="查 詢">
</form>

</div>
</div>
<div  class="content">

  <table  width="1024"  border="0"  cellspacing="0"  cellpadding="0"  bgcolor="E3D46E"  class="m_tab">
  <tbody><tr  class="m_title_over_co">
    <td>序號</td>
    <td>股东</td>
    <td>总代理</td>
    <td>代理商</td>
    <td>会员</td>
    </tr>
    <?php if (!empty($_smarty_tpl->tpl_vars['user']->value)) {?>
    <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['user']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
	<tr>
	    <td><?php echo $_smarty_tpl->tpl_vars['k']->value+1;?>
</td>
    <td><?php echo $_smarty_tpl->tpl_vars['v']->value['s_h'];?>
</td>
    <td><?php echo $_smarty_tpl->tpl_vars['v']->value['u_a'];?>
</td>
    <td><?php echo $_smarty_tpl->tpl_vars['v']->value['a_t'];?>
</td>
    <td><?php echo $_smarty_tpl->tpl_vars['v']->value['username'];?>
</td>
	</tr>
	<?php } ?>
	<?php } else { ?>
	  <tr class="m_rig" style="display:;">
        <td height="70" align="center" colspan="16"><font color="#3B2D1B">暫無數據。</font></td>
      </tr>
	<?php }?>
    </tbody>
  </table>
</div>
 <?php echo $_smarty_tpl->getSubTemplate ("web_footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>

<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-06-21 03:58:49
         compiled from "D:\WWW\jpk\web_20156\A_index\website\templates\about.html" */ ?>
<?php /*%%SmartyHeaderCode:207125585c5f9bfb9e5-64126355%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c448ba3f8950727562db3b1bc12dc2105358cc7a' => 
    array (
      0 => 'D:\\WWW\\jpk\\web_20156\\A_index\\website\\templates\\about.html',
      1 => 1200347104,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '207125585c5f9bfb9e5-64126355',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'header' => 0,
    'notice_html' => 0,
    'bottom' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5585c5f9cf98a4_27082889',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5585c5f9cf98a4_27082889')) {function content_5585c5f9cf98a4_27082889($_smarty_tpl) {?><?php echo $_smarty_tpl->tpl_vars['header']->value;?>

<?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>

<!--banner-->
<div class="PageBnner"></div>
<!--banner end-->
<!--new-->
<?php echo $_smarty_tpl->tpl_vars['notice_html']->value;?>

<!--new end-->
<div class="CenterMain"  style=" margin-top:-60px;">
<div class="PCenter">
   <div class="navLeft">
   		<ul>
        	<li class="borderweight"><a href="index.php?a=about">关于我们</a></li>
            <li class="hasHover"><a href="index.php?a=about">联系我们</a></li>
            <li><a href="index.php?a=cooperation">合作伙伴</a></li>
            <li><a href="index.php?a=cunkuan">存款帮助</a></li>
            <li><a href="index.php?a=qukuan">取款帮助</a></li>
            <li><a href="index.php?a=changjian">常见问题</a></li>
        </ul>
   </div>
   <div class="textRight">
         <span>联系我们</span>
       <p>时尚娱乐城客服中心全年无休，提供全年7天*24小时的优质服务。<P>
<p>
如果您对本网站的使用有任何疑问，可以通过下列任一方式与客服人员联系，享受最实时的服务。<p>
<p>
1. 点击"在线客服"链接，即可进入在线客服系统与客服人员联系。
<p><p>
2. 您亦可使用以下Email与客服人员取得联系：</p>
       <p>邮箱：pkbet888@163.com （意见建议）</p>
       <p>邮箱：pkbet888@gmail.com （商务合作）</p>

   </div>
</div>    
</div>
<!--footer-->
<?php echo $_smarty_tpl->tpl_vars['bottom']->value;?>
<?php }} ?>

<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-06-29 04:13:07
         compiled from "/home/wwwuser/public_html/t/website/templates/bottom.html" */ ?>
<?php /*%%SmartyHeaderCode:7706322745590fe13831d44-12535857%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1420b36ff0fcec34162a653fcb04b7eec362ca56' => 
    array (
      0 => '/home/wwwuser/public_html/t/website/templates/bottom.html',
      1 => 1200347104,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7706322745590fe13831d44-12535857',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'copy_right' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5590fe1383cf43_27237280',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5590fe1383cf43_27237280')) {function content_5590fe1383cf43_27237280($_smarty_tpl) {?><?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>
<!--footer-->
<div class="footer">
  <div class="footer_content mid">
  <div class="footer_content_1">
   <img src="<?php echo $_smarty_tpl->getConfigVariable('images');?>
/index/ty.png" />
   <img src="<?php echo $_smarty_tpl->getConfigVariable('images');?>
/index/ty2 .png" />
  </div>
  
  <div class="footer_content_2 fr">
<ul>
	 <li><a href="index.php?a=about" target="mem_index">关于我们</a><span>|</span></li>
	 <li><a href="index.php?a=about" target="mem_index">联系我们</a><span>|</span></li>
	 <li><a href="index.php?a=cooperation" target="mem_index">合作伙伴</a><span>|</span></li>
	 <li><a href="index.php?a=cunkuan" target="mem_index">存款帮助</a><span>|</span></li>
	 <li><a href="index.php?a=qukuan" target="mem_index">取款帮助</a><span>|</span></li>
	 <li><a href="index.php?a=changjian" target="mem_index">常见问题</a></li>

	</ul>

   </div>
  <span  style=" width:300px; height:15px; float:right; display:block; margin-top:-40px; color:#c3c585;">CopyRight <?php echo $_smarty_tpl->tpl_vars['copy_right']->value;?>
版权所有 Reserved</span>

  </div>
  
  </div>
<!--footer end-->
</body>
</html>
<?php }} ?>

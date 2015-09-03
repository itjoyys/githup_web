<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-06-29 04:13:07
         compiled from "/home/wwwuser/public_html/t/website/templates/notice.html" */ ?>
<?php /*%%SmartyHeaderCode:16170199375590fe138247c2-86103198%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '46e5ca5b36060e62208478bf81858d9f63bfe14d' => 
    array (
      0 => '/home/wwwuser/public_html/t/website/templates/notice.html',
      1 => 1434833753,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16170199375590fe138247c2-86103198',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'notice' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5590fe1382f663_84665653',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5590fe1382f663_84665653')) {function content_5590fe1382f663_84665653($_smarty_tpl) {?><?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>
<div class="CenterMain" style=" margin-top:-100px;">
    <div class="PCenter">
        <div class="new">
          <div class="news mid">
           <img src="<?php echo $_smarty_tpl->getConfigVariable('images');?>
/index/tips.png" style=" float:left; margin-top:3px;" />
            	<span>系统公告:</span>
             	<marquee  scrollamount="2" scrolldelay="10" class="recent_news_scroll" direction="left" nowarp="" onMouseOver="this.stop();" onMouseOut="this.start();">
				<a href="javascript:;" onclick="notice_data();"><?php echo $_smarty_tpl->tpl_vars['notice']->value;?>
</a>
				</marquee>
           <img src="<?php echo $_smarty_tpl->getConfigVariable('images');?>
/index/left.png" style=" float:right; margin-top:3px;" />
          </div>
        </div>
    </div>
</div>

<?php }} ?>

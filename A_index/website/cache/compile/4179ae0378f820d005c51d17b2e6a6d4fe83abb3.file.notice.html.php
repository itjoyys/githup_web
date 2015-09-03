<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-07-03 05:01:08
         compiled from "/home/wwwuser/public_html/A_index/website/templates/notice.html" */ ?>
<?php /*%%SmartyHeaderCode:22659824555964f54576cf0-74519620%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4179ae0378f820d005c51d17b2e6a6d4fe83abb3' => 
    array (
      0 => '/home/wwwuser/public_html/A_index/website/templates/notice.html',
      1 => 1435671225,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '22659824555964f54576cf0-74519620',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'notice' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_55964f5458af06_87170715',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55964f5458af06_87170715')) {function content_55964f5458af06_87170715($_smarty_tpl) {?><?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>
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

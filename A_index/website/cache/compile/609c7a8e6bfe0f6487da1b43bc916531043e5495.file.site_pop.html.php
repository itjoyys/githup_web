<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-09-03 12:46:36
         compiled from "D:\WWW\web_20156\A_index\website\templates\site_pop.html" */ ?>
<?php /*%%SmartyHeaderCode:631155e7d0aca2fb12-10383463%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '609c7a8e6bfe0f6487da1b43bc916531043e5495' => 
    array (
      0 => 'D:\\WWW\\web_20156\\A_index\\website\\templates\\site_pop.html',
      1 => 1440822803,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '631155e7d0aca2fb12-10383463',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'pop' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_55e7d0acb8b5f8_20805692',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55e7d0acb8b5f8_20805692')) {function content_55e7d0acb8b5f8_20805692($_smarty_tpl) {?><?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>

<!--最新公告-->
<div id="js-notice-pop" style="display:none;">
    <?php echo $_smarty_tpl->tpl_vars['pop']->value['content'];?>

</div>

<style type="text/css">
    .ui-widget-header{
        background: <?php echo $_smarty_tpl->tpl_vars['pop']->value['pop_config']['title_bcolor'];?>
;
        color: <?php echo $_smarty_tpl->tpl_vars['pop']->value['pop_config']['title_color'];?>
;
    }
</style>
<?php echo '<script'; ?>
>
window.onload = function(){
    try{
    (function(){
        if ($.cookie('PKBET_ORG')) return;
        $.cookie('PKBET_ORG', 'Y', {path:'/', expires: ''});
        $('#js-notice-pop').dialog({
            'closeOnEscape': true,
            'bgiframe': true,
            'width' : 'auto',
            'height' : 'auto',
            'title': "<?php echo $_smarty_tpl->tpl_vars['pop']->value['title'];?>
",
            'resizable': true,
            'modal': true,
            'autoOpen': false,
            'buttons' : { 
                "关闭" : function() { $(this).dialog("close"); }
            }
        }); 
                $('#js-notice-pop').dialog('open');
            })();
    }catch(e){}
}
<?php echo '</script'; ?>
><?php }} ?>

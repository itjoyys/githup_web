<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-09-03 12:46:37
         compiled from "D:\WWW\web_20156\A_index\website\templates\notice.html" */ ?>
<?php /*%%SmartyHeaderCode:2747755b3b4eb6302a6-75075991%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '11c1e4b692f4894564b7563a7e826c1928878b40' => 
    array (
      0 => 'D:\\WWW\\web_20156\\A_index\\website\\templates\\notice.html',
      1 => 1440822803,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2747755b3b4eb6302a6-75075991',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_55b3b4eb637fa7_23181744',
  'variables' => 
  array (
    'notice' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b3b4eb637fa7_23181744')) {function content_55b3b4eb637fa7_23181744($_smarty_tpl) {?><?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>
            <!-- 最新消息 -->
        <div class="news-wrap clearfix" style="width:100%;">
            <div class="news-item" style="width:660px;">
                <div id="ele-msgNews" class="ele-news-wrap">
    <ul class="ele-news-scroll" onclick="notice_data();" style="margin-top: 0px;"> 
          <marquee  scrollamount="2" scrolldelay="10" class="recent_news_scroll" direction="left" nowarp="" onMouseOver="this.stop();" onMouseOut="this.start();">
             <li href="javascript:;" onclick="notice_data();"><?php echo $_smarty_tpl->tpl_vars['notice']->value;?>
</li>
        </marquee>
    </ul>
    </div>
<style>
.ele-news-wrap{
    position: relative;
    overflow: hidden;
    padding-right: 40px;
    height: auto;
}
.ele-news-scroll li {
    cursor: pointer;
    overflow: hidden;
    text-overflow: ellipsis;
    height: 20px;
    line-height: 20px;
}
</style>
</div>
</div>
</div>
</div>

<?php }} ?>

<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-09-03 12:46:37
         compiled from "D:\WWW\web_20156\A_index\website\templates\N_index.html" */ ?>
<?php /*%%SmartyHeaderCode:448855b3b4eb7e1c83-55101646%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '19ad4abd616c3819418ba7d1a7a96792c5c60dc6' => 
    array (
      0 => 'D:\\WWW\\web_20156\\A_index\\website\\templates\\N_index.html',
      1 => 1440822803,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '448855b3b4eb7e1c83-55101646',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_55b3b4eb847591_36008942',
  'variables' => 
  array (
    'header' => 0,
    'flash' => 0,
    'notice' => 0,
    'bottom' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b3b4eb847591_36008942')) {function content_55b3b4eb847591_36008942($_smarty_tpl) {?><?php echo $_smarty_tpl->tpl_vars['header']->value;?>

<?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>
<!--banner-->


 <div class="ad-flash clearfix">
        <?php echo '<script'; ?>
>
        var cyj = {};
        cyj.anythingSliderOpt = {
            mode : 'f', //如需漸變效果，可改成'f'
            autoPlay : true,
            buildArrows : true,
            buildStartStop : false,
            buildNavigation: true,  //頁籤
            delay : false,
            startText : '►',
            forwardText: '',
            backText: '',
            stopText : 'stop'
        }
        <?php echo '</script'; ?>
>
          <div id="js-ele-slider" class="ele-slider-wrap">
             <div style="width: 1000px; height: 480px;" class="anythingSlider anythingSlider-default activeSlider">
             <div class="anythingWindow">
                <ul style="width: 1000px; left: 0px;" class="anythingSlider js-ele-anythingSlider anythingBase fade">
                 <li style="width: 1000px; height: 480px; opacity: 0;" class="panel" rel="3000"><a style="width: 100%; height: 100%;" href="<?php echo $_smarty_tpl->tpl_vars['flash']->value['url_A'];?>
" target="mem_index"><img src="<?php echo $_smarty_tpl->tpl_vars['flash']->value['img_A'];?>
" alt=""></a></li>
                <li style="width: 1000px; height: 480px; opacity: 1;" class="panel" rel="3000"><a style="width: 100%; height: 100%;" href="<?php echo $_smarty_tpl->tpl_vars['flash']->value['url_B'];?>
" target="mem_index"><img src="<?php echo $_smarty_tpl->tpl_vars['flash']->value['img_B'];?>
" alt=""></a></li>
               <li style="width: 1000px; height: 480px; opacity: 0;" class="panel" rel="3000"><a style="width: 100%; height: 100%;" href="<?php echo $_smarty_tpl->tpl_vars['flash']->value['url_C'];?>
" target="mem_index"><img src="<?php echo $_smarty_tpl->tpl_vars['flash']->value['img_C'];?>
" alt=""></a></li>
              <li style="width: 1000px; height: 480px; opacity: 0;" class="panel" rel="3000"><a style="width: 100%; height: 100%;" href="<?php echo $_smarty_tpl->tpl_vars['flash']->value['url_D'];?>
" target="mem_index"><img src="<?php echo $_smarty_tpl->tpl_vars['flash']->value['img_D'];?>
" alt=""></a></li>
                    </ul>
                    </div>
             
                </div>
        <style>
            .ele-slider-wrap{
                width: 1000px;
                height: 480px;
            }
            .anythingSlider,
            .anythingSlider img{
                width: 1000px;
                height: 480px;
            }
            .anythingWindow{
                overflow: hidden;
            }
            .anythingSlider{
                position: relative;
                overflow: hidden;
            }
            .panel a{display: block; }
            .anythingSlider .horizontal li{
                float: left
            }
            .anythingSlider .fade li{
                position: absolute;
                top: 0;
                left: 0;
                z-index: 0;
            }
            .anythingSlider .fade .activePage {z-index: 1; }
        </style>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/jquery.easing.1.3.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/jquery.anythingslider1.9.2.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
>
            (function(cyj) {
                var slider_box = $('.js-ele-anythingSlider', '#js-ele-slider'),
                    option = {
                        mode : 'h',
                        buildArrows : false,
                        autoPlay : true,
                        buildStartStop : false,
                        delay : false,
                        navigationFormatter : function(index, panel){ return index + '';}
                    };
                $.extend(option, cyj.anythingSliderOpt);

                $('img', slider_box).each(function () {
                    var img = $(this).prop('src');
                    if (img.indexOf('.png') != -1 && navigator.userAgent.indexOf("MSIE") != -1) {
                        $(this).attr('style', 'filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src="' + img + '",sizingMethod="scale")');
                    }
                });
                slider_box.anythingSlider(option);
            })(cyj);
        <?php echo '</script'; ?>
>
            </div>
            <!-- 最新消息 -->
        <div class="news-wrap clearfix">
            <div class="news-item">
                 <div id="ele-msgNews" class="ele-news-wrap">
    <ul style="margin-top: 0px;" class="ele-news-scroll" onclick="HotNewsHistory();">
          <marquee  scrollamount="2" scrolldelay="10" class="recent_news_scroll" direction="left" nowarp="" onMouseOver="this.stop();" onMouseOut="this.start();">
             <li href="javascript:;" onclick="notice_data();"><?php echo $_smarty_tpl->tpl_vars['notice']->value;?>
</li>
        </marquee>
    </ul>
    <!--控制按鈕開關 -->
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
/*箭頭按鈕*/
.ele-scroll-wrap{
    position: absolute;
    right: 0;
    top: 0;
    width: 20px;
}
.ele-scroll-arrow{
    width: 20px;
    height: 20px;
    float: left;
    cursor: pointer;
    color: #999999;
    text-align: center;
    text-decoration: none;
}
</style>
    </div>
            <a href="javascript:void(0);" onclick="getPager('-','zhuce');" class="header-join"></a>
        </div>
    </div>
    </div>     

<div id="page-container">
   <div id="page-body" class="clearfix">
        <div class="first-game-wrap">
                                    

<style>
.ele-firstgame { position: relative; }
    .ele-firstgame-1 {
        width: 222px;
        height: 225px;
        float:left;
        _display:inline;
        position: relative;
        overflow: hidden;
                    background: url(<?php echo $_smarty_tpl->getConfigVariable('images');?>
/141031740637.png?156686) -222px bottom no-repeat;
            }
    .ele-firstgame-1 span {
        width: 222px;
        height: 225px;
        background: url(<?php echo $_smarty_tpl->getConfigVariable('images');?>
/141031740394.png?156686) left top no-repeat;
        position: absolute;
        top: 0;
        left: 0;
        cursor:pointer;
    }
    .ele-firstgame-2 {
        width: 222px;
        height: 225px;
        float:left;
        _display:inline;
        position: relative;
        overflow: hidden;
                    background: url(<?php echo $_smarty_tpl->getConfigVariable('images');?>
/141031739936.png?156686) -222px bottom no-repeat;
            }
    .ele-firstgame-2 span {
        width: 222px;
        height: 225px;
        background: url(<?php echo $_smarty_tpl->getConfigVariable('images');?>
/141031739583.png?156686) left top no-repeat;
        position: absolute;
        top: 0;
        left: 0;
        cursor:pointer;
    }
    .ele-firstgame-3 {
        width: 222px;
        height: 225px;
        float:left;
        _display:inline;
        position: relative;
        overflow: hidden;
                    background: url(<?php echo $_smarty_tpl->getConfigVariable('images');?>
/141031739162.png?156686) -222px bottom no-repeat;
            }
    .ele-firstgame-3 span {
        width: 222px;
        height: 225px;
        background: url(<?php echo $_smarty_tpl->getConfigVariable('images');?>
/141031738907.png?156686) left top no-repeat;
        position: absolute;
        top: 0;
        left: 0;
        cursor:pointer;
    }
    .ele-firstgame-4 {
        width: 222px;
        height: 225px;
        float:left;
        _display:inline;
        position: relative;
        overflow: hidden;
                    background: url(<?php echo $_smarty_tpl->getConfigVariable('images');?>
/141031738422.png?156686) -222px bottom no-repeat;
            }
    .ele-firstgame-4 span {
        width: 222px;
        height: 225px;
        background: url(<?php echo $_smarty_tpl->getConfigVariable('images');?>
/141031738167.png?156686) left top no-repeat;
        position: absolute;
        top: 0;
        left: 0;
        cursor:pointer;
    }
    #page-body{
    height: 504px;
    background: url('<?php echo $_smarty_tpl->getConfigVariable('images');?>
/container_bg.png') no-repeat center top;
}
.first .news-item {
   width: 500px;
}
</style>

<div class="ele-firstgame-wrap" onselectstart="return false">
    <div class="clearfix ele-firstgame" id="js-firstgame-slider">
            <a href="javascript:void(0);" onclick="getPager('-','livetop');" class="ele-firstgame-1 js-ele-firstgame-fade">
                <span></span>
            </a>
             <a href="javascript:void(0);" onclick="getPager('-','sports');" class="ele-firstgame-2 js-ele-firstgame-fade">
                <span></span>
            </a>
            <a href="javascript:void(0);" onclick="getPager('-','egame');" class="ele-firstgame-3 js-ele-firstgame-fade">
                <span></span>
            </a>
            <a href="javascript:void(0);" onclick="getPager('-','lottery');" class="ele-firstgame-4 js-ele-firstgame-fade">
                <span></span>
            </a>
            </div>
</div>
<?php echo '<script'; ?>
>
    $('.js-ele-firstgame-fade > span').hover(
        function(){
            $(this).css("background-position-x", "-222px")
                .parent()
                .css("background-position-x", "0");
        }, function(){
            $(this).css("background-position-x", "0")
                .parent()
                .css("background-position-x", "-222px");
        }
    );
<?php echo '</script'; ?>
>
        </div>
        <div class="first-img"></div>
        <div class="first-btn-wrap">
            <a href="javascript:void(0);" onclick="getPager('-','iword','5');" class="first-btn download hover"></a>
            <a href="javascript:void(0);" onclick="getPager('-','youhui');" class="first-btn promotions hover"></a>
            <a href="javascript:void(0);" onclick="getPager('-','zhuce');" class="first-btn join hover"></a>
                    </div>
    </div>
</div>    


<?php echo $_smarty_tpl->tpl_vars['bottom']->value;?>
<?php }} ?>

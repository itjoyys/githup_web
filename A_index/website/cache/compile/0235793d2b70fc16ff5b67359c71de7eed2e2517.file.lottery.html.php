<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-06-29 10:18:02
         compiled from "/home/wwwuser/public_html/t/website/templates/lottery.html" */ ?>
<?php /*%%SmartyHeaderCode:14556386755591539a1822d5-77584590%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0235793d2b70fc16ff5b67359c71de7eed2e2517' => 
    array (
      0 => '/home/wwwuser/public_html/t/website/templates/lottery.html',
      1 => 1434833827,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14556386755591539a1822d5-77584590',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'header' => 0,
    'notice' => 0,
    'uid' => 0,
    'bottom' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5591539a220b67_89469011',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5591539a220b67_89469011')) {function content_5591539a220b67_89469011($_smarty_tpl) {?><?php echo $_smarty_tpl->tpl_vars['header']->value;?>

<?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>

<!--banner-->
<div class="PageBnner3" style="background: url(<?php echo $_smarty_tpl->getConfigVariable('images');?>
/index/bannerPage4.jpg) no-repeat top;"></div>
<!--banner end-->
<?php echo '<script'; ?>
 type="text/javascript">
$(function(){
  $('.shade li').hover(function(){
    $('.text',this).stop().animate({
      height:'2228'
    });
  },function(){
    $('.text',this).stop().animate({
      height:'0'
    });
  });
});

    var o_state=1;
    function open_lottery(type){
          var iHeight = window.screen.height;
         // var title=$(this).attr('type');
          var iWidth = window.screen.width-8;
          if (o_state) {
               window.open("lottery_type/lottery.php?type="+type,"blank",'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width='+iWidth+',height='+iHeight); 
               
          };
    }
<?php echo '</script'; ?>
>
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


  <div id="bg">
   <div class="content">
    <ul>
      <li>
      <span class="lhc">
      <span class="block"><a href="./lottery_type/rule/rule.php?gtype=lhc" target="_blank">规则说明</a><span>/</span><a href="javascript:;" <?php if (empty($_smarty_tpl->tpl_vars['uid']->value)) {?>
      onclick="alert('你尚未登录，请先登录再进入游戏！')"
        <?php } else { ?>
        onclick="open_lottery('6')"
        <?php }?>>立即投注</a>
        </span>     
      </span>
      </li>
      
      <li>
        <span class="dd">      <span class="block"><a href="./lottery_type/rule/rule.php?gtype=bj3d" target="_blank">规则说明</a><span>/</span><a href="javascript:;" <?php if (empty($_smarty_tpl->tpl_vars['uid']->value)) {?>
      onclick="alert('你尚未登录，请先登录再进入游戏！')"
        <?php } else { ?>
        onclick="open_lottery('1')"
        <?php }?>>立即投注</a></span>  </span>
      </li>
      
      <li>
        <span class="p3">      <span class="block"><a href="./lottery_type/rule/rule.php?gtype=pl3d" target="_blank">规则说明</a><span>/</span><a href="javascript:;" <?php if (empty($_smarty_tpl->tpl_vars['uid']->value)) {?>
      onclick="alert('你尚未登录，请先登录再进入游戏！')"
        <?php } else { ?>
        onclick="open_lottery('2')"
        <?php }?>>立即投注</a></span>  </span>
      </li>
      
            <li>
        <span class="jsk3">      <span class="block"><a href="./lottery_type/rule/rule.php?gtype=jsq3" target="_blank">规则说明</a><span>/</span><a href="javascript:;" <?php if (empty($_smarty_tpl->tpl_vars['uid']->value)) {?>
      onclick="alert('你尚未登录，请先登录再进入游戏！')"
        <?php } else { ?>
        onclick="open_lottery('13')"
        <?php }?>>立即投注</a></span>  </span>
      </li>
      <li>
        <span class="jlk3">      <span class="block"><a href="./lottery_type/rule/rule.php?gtype=ahq3" target="_blank">规则说明</a><span>/</span><a href="javascript:;" <?php if (empty($_smarty_tpl->tpl_vars['uid']->value)) {?>
      onclick="alert('你尚未登录，请先登录再进入游戏！')"
        <?php } else { ?>
        onclick="open_lottery('14')"
        <?php }?>>立即投注</a></span>  </span>
      </li>
      <li>
        <span class="bj">      <span class="block"><a href="./lottery_type/rule/rule.php?gtype=bjkn" target="_blank">规则说明</a><span>/</span><a href="javascript:;" <?php if (empty($_smarty_tpl->tpl_vars['uid']->value)) {?>
      onclick="alert('你尚未登录，请先登录再进入游戏！')"
        <?php } else { ?>
        onclick="open_lottery('4')"
        <?php }?>>立即投注</a></span>  </span>
      </li>
      
            <li>
         <span class="gd">      <span class="block"><a href="./lottery_type/rule/rule.php?gtype=gdsf" target="_blank">规则说明</a><span>/</span><a href="javascript:;" <?php if (empty($_smarty_tpl->tpl_vars['uid']->value)) {?>
      onclick="alert('你尚未登录，请先登录再进入游戏！')"
        <?php } else { ?>
        onclick="open_lottery('7')"
        <?php }?>>立即投注</a></span>  </span>
      </li>
      <li>
        <span class="cq">      <span class="block"><a href="./lottery_type/rule/rule.php?gtype=cqsf" target="_blank">规则说明</a><span>/</span><a href="javascript:;" <?php if (empty($_smarty_tpl->tpl_vars['uid']->value)) {?>
      onclick="alert('你尚未登录，请先登录再进入游戏！')"
        <?php } else { ?>
        onclick="open_lottery('8')"
        <?php }?>>立即投注</a></span>  </span>
      </li>
      
            

      
      <li>
        <span class="sc">      <span class="block"><a href="./lottery_type/rule/rule.php?gtype=bjpk" target="_blank">规则说明</a><span>/</span><a href="javascript:;" <?php if (empty($_smarty_tpl->tpl_vars['uid']->value)) {?>
      onclick="alert('你尚未登录，请先登录再进入游戏！')"
        <?php } else { ?>
        onclick="open_lottery('5')"
        <?php }?>>立即投注</a></span>  </span>
      </li>

      

      
      <li>
      <span class="ssc">      <span class="block"><a href="./lottery_type/rule/rule.php?gtype=cqsc" target="_blank">规则说明</a><span>/</span><a href="javascript:;" <?php if (empty($_smarty_tpl->tpl_vars['uid']->value)) {?>
      onclick="alert('你尚未登录，请先登录再进入游戏！')"
        <?php } else { ?>
        onclick="open_lottery('3')"
        <?php }?>>立即投注</a></span>  </span>
      </li>
      
      <li>
        <span class="tjssc">      <span class="block"><a href="./lottery_type/rule/rule.php?gtype=tjsc" target="_blank">规则说明</a><span>/</span><a href="javascript:;" <?php if (empty($_smarty_tpl->tpl_vars['uid']->value)) {?>
      onclick="alert('你尚未登录，请先登录再进入游戏！')"
        <?php } else { ?>
        onclick="open_lottery('10')"
        <?php }?>>立即投注</a></span>  </span>
      </li>
      
      <li>
        <span class="jxssc">      <span class="block"><a href="./lottery_type/rule/rule.php?gtype=jxsc" target="_blank">规则说明</a><span>/</span><a href="javascript:;" <?php if (empty($_smarty_tpl->tpl_vars['uid']->value)) {?>
      onclick="alert('你尚未登录，请先登录再进入游戏！')"
        <?php } else { ?>
        onclick="open_lottery('11')"
        <?php }?>>立即投注</a></span>  </span>
      </li>
      
      <li>
        <span class="xjssc">
        <span class="block"><a href="./lottery_type/rule/rule.php?gtype=xjsc" target="_blank">规则说明</a><span>/</span><a href="javascript:;" <?php if (empty($_smarty_tpl->tpl_vars['uid']->value)) {?>
      onclick="alert('你尚未登录，请先登录再进入游戏！')"
        <?php } else { ?>
        onclick="open_lottery('12')"
        <?php }?>>立即投注</a></span>       
      </span>
      </li>
      
    </ul>
   </div>
 </div>
 <div style="height:20px"></div>
<!--footer-->
<?php echo $_smarty_tpl->tpl_vars['bottom']->value;?>

<?php }} ?>

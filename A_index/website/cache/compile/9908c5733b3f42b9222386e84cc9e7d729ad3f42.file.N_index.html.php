<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-07-04 22:50:40
         compiled from "D:\WWW\jpk\web_20156\A_index\website\templates\N_index.html" */ ?>
<?php /*%%SmartyHeaderCode:310885584600551aa00-96889476%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9908c5733b3f42b9222386e84cc9e7d729ad3f42' => 
    array (
      0 => 'D:\\WWW\\jpk\\web_20156\\A_index\\website\\templates\\N_index.html',
      1 => 1435824266,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '310885584600551aa00-96889476',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_55846005768817_34738077',
  'variables' => 
  array (
    'header' => 0,
    'uid' => 0,
    'money' => 0,
    'count' => 0,
    'notice' => 0,
    'bottom' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55846005768817_34738077')) {function content_55846005768817_34738077($_smarty_tpl) {?><?php echo $_smarty_tpl->tpl_vars['header']->value;?>

<?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>
<!--banner-->
<?php echo $_smarty_tpl->getSubTemplate ("siteAd.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
 
<div class="clean"></div>
<div class="flexslider">
      <ul class="slides">
        <li style="background:url(<?php echo $_smarty_tpl->getConfigVariable('images');?>
/index/banner1.png) 50% 0 no-repeat;"></li>
        <li style="background:url(<?php echo $_smarty_tpl->getConfigVariable('images');?>
/index/banner2.png) 50% 0 no-repeat;"></li>
        <li style="background:url(<?php echo $_smarty_tpl->getConfigVariable('images');?>
/index/banner3.png) 50% 0 no-repeat;"></li>
      </ul>       
</div>
<style type="text/css">
  .ia{
     cursor:pointer;
  }
</style>

<?php echo '<script'; ?>
 type="text/javascript">
$(document).ready(function(){
    $('.flexslider').flexslider({
        directionNav: true,
        pauseOnAction: false
    });
});
<?php echo '</script'; ?>
>
<!--banner end-->


<?php echo '<script'; ?>
 language="javascript">
//获取当前余额以及未读信息数
// function refresh_money(){
//   $.getJSON("/get_info.php?callback=?",function(json){
//     $("#user_money1").html(json.user_money);
//     $("#tz_money1").html(json.tz_money);
//     $("#user_num1").html(json.user_num);
//   });
//   window.setTimeout("refresh_money();", 10*1000); 
// }
// refresh_money();

//验证码 换一下
      $('#rmNum').live('focus',function(){
        $('#yzm').attr('src','./yzm.php?tm='+Math.random());
      })
	  $('#yzm').live('click',function(){
        $('#yzm').attr('src','./yzm.php?tm='+Math.random());
      })

<?php echo '</script'; ?>
> 


<!--login-->

<div class="login">
  <img src="<?php echo $_smarty_tpl->getConfigVariable('images');?>
/index/huodong.png" style=" position:relative; z-index:100" />
  <?php if ($_smarty_tpl->tpl_vars['uid']->value>0) {?>
    <ul class="account">
        <li>账&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号：<a class="ia" onclick="openHelp('/Member/index_main.php?url=4')"><?php echo $_SESSION['username'];?>
</a></li>
        <li>余&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;额：<span id="user_money1"><?php echo $_smarty_tpl->tpl_vars['money']->value;?>
</span></li>
        <li>未读信息：(<a onclick="openHelp('/Member/index_main.php?url=8')" href="javascript:;"id="user_num1"><?php echo $_smarty_tpl->tpl_vars['count']->value;?>
</a>)条信息</li>
    </ul>
    <ul class="accountNav">
        <li><a class="ia" href="javascript:;" onclick="openHelp('/Member/index_main.php?url=1')">线上存款</a></li>
        <li><a class="ia" href="javascript:;" onclick="openHelp('/Member/index_main.php?url=2')">线上取款</a></li>
        <li><a class="ia" href="javascript:;" onclick="openHelp('/Member/index_main.php?url=3')">额度转换</a></li>
        <li><a class="ia" href="javascript:;" onclick="openHelp('/Member/index_main.php?url=4')">会员中心</a></li>
        <li><a class="ia" href="javascript:;" onclick="openHelp('/Member/index_main.php?url=5')">投注记录</a></li>
        <li><a class="ia" href="javascript:;" onclick="openHelp('/Member/index_main.php?url=6')">投注报表</a></li>
    </ul>
    <input type="button" value="退出" class="kh" style=" width:230px; padding:0px 15px; margin-top:20px; color:#fff;" onclick="javascript:window.location.href='./index.php?a=logout'" />

    <?php } else { ?>
   <input type="button" value="立即开户" onclick="javascript:window.location.href='./index.php?a=zhuce&type=1'"  class="kh" style=" width:230px; padding:0px 15px; color:#fff;"/>
   <input type="text" value="用户名" class="zh"  id="username" onfocus="if(this.value=='用户名')this.value=''" onblur="if(this.value =='') this.value ='用户名'" />
   <input type="password" class="mm" id="password" class="pass" value="******" onfocus="if(this.value=='******')this.value=''" onblur="if(this.value =='') this.value ='******'"  style=" width:110px;"/>
   <!--<a href="#" >忘记密码</a>-->
   <input type="text" value="验证码" class="yzm" onfocus="this.value='';"  onblur="if(this.value==''){this.value='验证码'}" style=" width:110px;" id="rmNum"/>
   <span><img src="./yzm.php" style="margin-left:10px; cursor:pointer" id="yzm"></span>
   <input type="submit" value="立即登录"  onclick="aLeftForm1Sub()" id="submit" class="dl" style="width:230px; padding:0px 15px;color:#000000"/>
 <?php }?>
</div>

<!--login end-->

<!--new-->
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

<!--new end-->

<div class="CenterMain"  style=" margin-top:-60px;">
<div class="PCenter">
    <div class="main_2 mid">
     <img src="<?php echo $_smarty_tpl->getConfigVariable('images');?>
/index/1.png" style=" margin:0 auto; display:block" />
       <ul>
         <li><a href="./index.php?a=livetop" class="liveenter"></a></li>
         <li><a href="./index.php?a=lottery" class="lottery"></a></li>
         <li><a href="./sport/sport_main.php" class="sports"></a></li>
         <li><a href="./index.php?a=egame" href="javascript:;" class="electronic"></a></li>
       </ul>
    </div>
</div>    
</div>
<div class="CenterMain HasBg">
<div class="PCenter">
    <div class="main_3">
        <ul>
            <li class="right"><a href="javascript:;"><img src="<?php echo $_smarty_tpl->getConfigVariable('images');?>
/index/products.png" width="329" height="108" /></a></li>
            <li class="left"><a href="javascript:;"><img src="<?php echo $_smarty_tpl->getConfigVariable('images');?>
/index/servuce.png" width="350" height="114" /></a></li>
            <li class="right"><a href="javascript:;"><img src="<?php echo $_smarty_tpl->getConfigVariable('images');?>
/index/hours.png" width="431" height="117" /></a></li>
            <li class="left"><a href="javascript:;"><img src="<?php echo $_smarty_tpl->getConfigVariable('images');?>
/index/promotions.png" width="406" height="111" /></a></li>
        </ul>
    </div>
</div>    
</div>

<?php echo $_smarty_tpl->tpl_vars['bottom']->value;?>
<?php }} ?>

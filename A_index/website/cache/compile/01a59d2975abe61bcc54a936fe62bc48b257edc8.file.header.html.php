<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-07-21 02:47:04
         compiled from "D:\WWW\jpk\web_20156\A_index\website\templates\header.html" */ ?>
<?php /*%%SmartyHeaderCode:20740558460047d9917-35410946%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '01a59d2975abe61bcc54a936fe62bc48b257edc8' => 
    array (
      0 => 'D:\\WWW\\jpk\\web_20156\\A_index\\website\\templates\\header.html',
      1 => 1437055788,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20740558460047d9917-35410946',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_55846004c793a0_94600574',
  'variables' => 
  array (
    'title' => 0,
    'timemd' => 0,
    'uid' => 0,
    'username' => 0,
    'money' => 0,
    'csstype' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55846004c793a0_94600574')) {function content_55846004c793a0_94600574($_smarty_tpl) {?><?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
<link type="text/css" rel="stylesheet" href="<?php echo $_smarty_tpl->getConfigVariable('css');?>
/style.css" />
<link type="text/css" rel="stylesheet" href="<?php echo $_smarty_tpl->getConfigVariable('css');?>
/standard.css" />
<link type="text/css" rel="stylesheet" href="<?php echo $_smarty_tpl->getConfigVariable('css');?>
/zhuce.css" />
<link type="text/css" rel="stylesheet" href="<?php echo $_smarty_tpl->getConfigVariable('css');?>
/jquery-ui-1.9.2.custom.css" />
<SCRIPT src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/jquery-1.7.2.min.js" type="text/javascript"></SCRIPT>
<SCRIPT src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/jquery-ui-1.8.21.custom.min.js" type="text/javascript"></SCRIPT>
<SCRIPT src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/jquery.flexslider-min.js" type="text/javascript"></SCRIPT>
<SCRIPT src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/jquery.min.js" type="text/javascript"></SCRIPT>
<SCRIPT src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/jquery.validate.js" type="text/javascript"></SCRIPT>
<SCRIPT src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/nav.js" type="text/javascript"></SCRIPT>
<SCRIPT src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/top.js" type="text/javascript"></SCRIPT>
<SCRIPT src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/date/WdatePicker.js" type="text/javascript"></SCRIPT>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/nav_select.js"><?php echo '</script'; ?>
>
<style>
.hide{ width:98px; height:30px; z-index:1; float:left; position:relative; left:295px; top:-105px;}
#hide-2 .select { background-color:rgba(35,35,35,0.7); width:100px; height:100px;}
#hide-2 .select .hover_a{ height:30px; text-align:center; line-height:30px;}
#hide-2 .select .hover_a a{ color:#fff;}
#hide-2 .select .hover_a a:hover{ color:#F00;}
</style>
<?php echo '<script'; ?>
 type="text/javascript">
//设置美东时间
var mddate="<?php echo $_smarty_tpl->tpl_vars['timemd']->value;?>
";
var dd2=new Date(mddate);
$(document).ready(function () {
  setInterval("RefTime()",1000);
})



function RefTime()
{

     dd2.setSeconds(dd2.getSeconds()+1);
  var myYears = ( dd2.getYear() < 1900 ) ? ( 1900 + dd2.getYear() ) : dd2.getYear();
  $("#vlock").html('美東時間'+'：'+myYears+'年'+fixNum(dd2.getMonth()+1)+'月'+fixNum(dd2.getDate())+'日 '+time(dd2));
}
function time(vtime){
    var s='';
    var d=vtime!=null?new Date(vtime):new Date();
    with(d){
        s=fixNum(getHours())+':'+fixNum(getMinutes())+':'+fixNum(getSeconds())
    }
    return(s);
}
function fixNum(num){
    return parseInt(num)<10?'0'+num:num;
}
// function AddFavorite(sURL, sTitle)j
// {
//     try
//     {
//         window.external.addFavorite(sURL, sTitle);
//     }
//     catch (e)
//     {
//         try
//         {
//             window.sidebar.addPanel(sTitle, sURL, "");
//         }
//         catch (e)
//         {
//             alert("加入收藏失败，请使用Ctrl+D进行添加");
//         }
//     }
// }

function SetHome(obj,vrl){
        try{
                obj.style.behavior='url(#default#homepage)';obj.setHomePage(vrl);
        }
        catch(e){
                if(window.netscape) {
                        try {
                                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
                        }
                        catch (e) {
                                alert("此操作被浏览器拒绝！\n请在浏览器地址栏输入“about:config”并回车\n然后将 [signed.applets.codebase_principal_support]的值设置为'true',双击即可。");
                        }
                        var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
                        prefs.setCharPref('browser.startup.homepage',vrl);
                 }
        }
}
<?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
function openHelp(url) {
  var st = window.open(url,'帮助','height=630,width=1020,top=80,left=200,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no');

}
function notice_data() {
		//parent.mainFrame.location='./scroll_history.php?uid='+top.uid+'&langx='+top.langx;
		window.open('./index.php?a=notice_data', "History", "width=816,height=500,top=0,left=0,status=no,toolbar=no,scrollbars=yes,resizable=no,personalbar=no");
	}
function opengeme(url){
    newWin=window.open(url,'','fullscreen=1,scrollbars=0,location=no');      
      window.opener=null;//出掉关闭时候的提示窗口
      window.open('','_self'); //ie7      
      window.close();
}
<?php echo '</script'; ?>
>
</head>

<body>
<!--header-->
 <div class="header ">

    <div class="header_top">
     <div class="top mid">
      <div class="time" id="vlock"></div>
      <div class="lang fr">
        <?php if (empty($_smarty_tpl->tpl_vars['uid']->value)) {?>
       <a href="./index.php?a=zhuce"><span>注册</span></a>
       <a href="/"><span>登录</span></a>
       <?php } else { ?>
         <p><span>用户名：</span><a href="javascript:;" onclick="openHelp('/Member/index_main.php?url=4')"><?php echo $_smarty_tpl->tpl_vars['username']->value;?>
</a></p>
       <p><span>余额：</span><a href="javascript:;"><?php echo $_smarty_tpl->tpl_vars['money']->value;?>
元</a></p>
       <a href="./index.php?a=logout"><span>退出</span></a>
       <?php }?>
       <a href="#" onclick="AddFavorite(window.location,document.title)"><span class="span_r">加入收藏</span></a>
       <a href="#" onclick="SetHome(this,window.location)"><span>设为首页</span></a>
       <a href="#"><img src="<?php echo $_smarty_tpl->getConfigVariable('images');?>
/index/meiguo.png" /></a>
       <a href="#"><img src="<?php echo $_smarty_tpl->getConfigVariable('images');?>
/index/china.png" /></a>
       <a href="#"><img src="<?php echo $_smarty_tpl->getConfigVariable('images');?>
/index/hongkong.png" /></a>
      </div>
      </div>
      
     <div class="nav" id="mainnav"> 
       <ul class="mid" > 
        <li <?php if ($_smarty_tpl->tpl_vars['csstype']->value==1) {?>class="hover"<?php } else { ?>class="onNav"<?php }?> ><a href="/" 
        	 target="mem_index" id="index">首页</a></li> 
         <li <?php if ($_smarty_tpl->tpl_vars['csstype']->value==8) {?>class="hover"<?php } else { ?>class="onNav"<?php }?>><a  href="./index.php?a=sports" target="mem_index" id="sport">体育赛事</a></li>
          <li <?php if ($_smarty_tpl->tpl_vars['csstype']->value==3) {?>class="hover"<?php }?> ><a href="./index.php?a=livetop" target="mem_index" id="video">视讯直播</a></li> 
            <li class="game-ball"<?php if ($_smarty_tpl->tpl_vars['csstype']->value==7) {?>class="hover"<?php }?> ><a target="mem_index"  href="./index.php?a=egame" id="game">电子游戏</a></li>
        <span style=" float:left;"><img src="<?php echo $_smarty_tpl->getConfigVariable('images');?>
/index/logo.png" width="310" height="184" /></span>
           <li <?php if ($_smarty_tpl->tpl_vars['csstype']->value==2) {?>class="hover"<?php }?> ><a href="/index.php?a=lottery" target="mem_index" id="lottery">彩票游戏</a></li> 
        
               <li><a  href="./index.php?a=cooperation" target="mem_index" id="live">代理合作</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['csstype']->value==4) {?>class="hover"<?php }?> ><a href="./index.php?a=youhui" target="mem_index" id="preferential">优惠活动</a></li> 

           <li><a href="http://www.ddkefu.com/index.php/Icon/loadChatWindow?siteString=74e4e01163595bc8839ef921e941d4a3" target="_bank" id="bb">在线客服</a></li> 
        
        <div class="clean"></div>
      <div id="L-Sub" class="hide">
        <div class="game-ball" id="hide-2"  style="display:none; margin-left:5px; ">
              <div class="select">
                  <p class="hover_a"><a href="./index.php?a=egame" target="mem_index" style=" text-decoration:none;">MG电子</a></p>
                  <p class="hover_a"><a  href="javascript:;" <?php if (empty($_smarty_tpl->tpl_vars['uid']->value)) {?>onclick="alert('您尚未登录，请先登录再进行游戏!')"<?php } else { ?>onclick="opengeme('/video/games/login.php?g_type=ag')"<?php }?> target="mem_index" style=" text-decoration:none;">AG电子</a></p>
                  <p class="hover_a"><a href="javascript:;" <?php if (empty($_smarty_tpl->tpl_vars['uid']->value)) {?>onclick="alert('您尚未登录，请先登录再进行游戏!')"<?php } else { ?>onclick="opengeme('/video/games/login.php?g_type=bbin')"<?php }?> target="mem_index" style=" text-decoration:none; cursor:pointer;">BBIN电子</a></p>
              </div>
      </div>
    </div> 
       </ul>

          
      </div> 
    </div>

 </div>
 <!--header end-->

<!--客服浮动信息框 star--->
<div class="Customer" id="Customer">
<style>
.Customer{ position:fixed;right:2px; top:10%; width:120px;  z-index:99999;}
.Customer ul{ float:left; width:100%;}
.Customer ul li{ float:left; width:100%; padding:0px;}
.Customer ul li a{ float:left; width:100%; padding:0px; opacity:0.9;}
.Customer ul li a:hover{ float:left; width:100%; padding:0px; opacity:1;}
</style>
<ul>
	<li><a href="./index.php?a=zhuce"><img src="<?php echo $_smarty_tpl->getConfigVariable('images');?>
/index/kefu.png" width="100%" alt=""/></a></li>
    <li><a href="http://www.ddkefu.com/index.php/Icon/loadChatWindow?siteString=74e4e01163595bc8839ef921e941d4a3" target="_bank"><img src="<?php echo $_smarty_tpl->getConfigVariable('images');?>
/index/kefu2.png" width="100%" alt=""/></a></li>
    <li><a href="./index.php?a=shiwan_reg"><img src="<?php echo $_smarty_tpl->getConfigVariable('images');?>
/index/kefu3.png" width="100%" alt=""/></a></li>
    <li><a href="./index.php?a=about"><img src="<?php echo $_smarty_tpl->getConfigVariable('images');?>
/index/kefu4.png" width="100%" alt=""/></a></li>
    <li><a id="switch" onclick="mySwitch()" href="javascript:;"><img src="<?php echo $_smarty_tpl->getConfigVariable('images');?>
/index/kefu5.png" width="100%" alt=""/></a></li>
</ul>
<?php echo '<script'; ?>
 language="javascript">
function mySwitch(){
    document.getElementById('Customer').style.display = document.getElementById('Customer').style.display=='none'?'block':'none';
}
<?php echo '</script'; ?>
>
</div>
<!--客服浮动信息框 end--->
<?php }} ?>

<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-08-19 11:57:21
         compiled from "D:\WWW\web_20156\A_index\website\templates\siteAd.html" */ ?>
<?php /*%%SmartyHeaderCode:2750055b3b4eb84f296-80051500%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd54fb9e9a6b2585fe10c95841e772c8237c9f9e5' => 
    array (
      0 => 'D:\\WWW\\web_20156\\A_index\\website\\templates\\siteAd.html',
      1 => 1439343475,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2750055b3b4eb84f296-80051500',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_55b3b4eb8dfb43_16619643',
  'variables' => 
  array (
    'isSitead' => 0,
    'siteAd' => 0,
    'title' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b3b4eb8dfb43_16619643')) {function content_55b3b4eb8dfb43_16619643($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<?php if ($_smarty_tpl->tpl_vars['isSitead']->value>0) {?>
<link href="<?php echo $_smarty_tpl->getConfigVariable('css');?>
/ggstyle.css" rel="stylesheet" type="text/css" />
<?php echo '<script'; ?>
  src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/jquery.cookie.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/zxx.drag.1.0.js" type="text/javascript"><?php echo '</script'; ?>
>

<?php echo '<script'; ?>
>
window.onload = function(){
    <!--右下角-->
	<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['youxia']) {?>
	var TipBox_right = document.getElementById("tipCon_right");
	var ClickMe_right = document.getElementById("clickMe_right");
	var showPic_right = document.getElementById("showPic_right");
	var btn_close_right = document.getElementById("btn_close_right");
	var btn_min_right = document.getElementById("btn_min_right");
	
	btn_close_right.onclick = function(){
		showPic_right.style.display = 'none';
		tipCon_right.style.display = 'none';
	}
	
	btn_min_right.onclick = function(){
		 
	  if(showPic_right.style.display == 'none'){
		  
		 showPic_right.style.display = 'block';
		   
		}
	  else{
	    showPic_right.style.display = 'none';
		   } 
		};
	
	<?php }?>
		
<!--左下角-->
<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['zuoxia']) {?>		
	var TipBox_left = document.getElementById("tipCon_left");
	var ClickMe_left = document.getElementById("clickMe_left");
	var showPic_left = document.getElementById("showPic_left");
	var btn_close_left = document.getElementById("btn_close_left");
	var btn_min_left = document.getElementById("btn_min_left");
	btn_close_left.onclick = function(){
		showPic_left.style.display = 'none';
		tipCon_left.style.display = 'none';
	}
	btn_min_left.onclick = function(){
		 
	  if(showPic_left.style.display == 'none'){
		  
		 showPic_left.style.display = 'block';
		   
		}
	  else{
	    showPic_left.style.display = 'none';
		   } 
		};
	<?php }?>	
<!--左上角-->
<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['zuoshang']) {?>		
	var TipBox_left_top = document.getElementById("tipCon_left_top");
	var ClickMe_left_top = document.getElementById("clickMe_left_top");
	var showPic_left_top = document.getElementById("showPic_left_top");
	var btn_close_left_top = document.getElementById("btn_close_left_top");
	var btn_min_left_top = document.getElementById("btn_min_left_top");
	btn_close_left_top.onclick = function(){
		showPic_left_top.style.display = 'none';
		tipCon_left_top.style.display = 'none';
	}
	btn_min_left_top.onclick = function(){
		 
	  if(showPic_left_top.style.display == 'none'){
		  
		 showPic_left_top.style.display = 'block';
		   
		}
	  else{
	    showPic_left_top.style.display = 'none';
		   } 
		};
		<?php }?>
<!--中间-->	
<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['zhongbu']) {?>	
	myonload();
		var oBox = document.getElementById("tipCon_content");
	var oBar = document.getElementById("bar");
	startDrag(oBar, oBox);
	var TipBox_content = document.getElementById("tipCon_content");
	var bar = document.getElementById("bar");
	var showPic_content = document.getElementById("showPic_content");
	var btn_close_content = document.getElementById("btn_close_content");
	var btn_close_content_2 = document.getElementById("btn_close_content_2");
	var btn_min_content = document.getElementById("btn_min_content");
	btn_close_content.onclick = function(){
		showPic_content.style.display = 'none';
		tipCon_content.style.display = 'none';
	}
	btn_close_content_2.onclick = function(){
		showPic_content.style.display = 'none';
		tipCon_content.style.display = 'none';
	}
<?php }?>
};

function myonload(){
	if ($.cookie('<?php echo $_smarty_tpl->tpl_vars['title']->value;?>
')) return;
    $.cookie('<?php echo $_smarty_tpl->tpl_vars['title']->value;?>
', 'Y', {path:'/', expires: ''});
	var windowwidth = $(window).width();  
	var windowheight = $(window).height();  
	var picwidth =<?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zhongbu']['picwidth'];?>
;
	var picheight=<?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zhongbu']['picheight'];?>
;
	<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['zhongbu']['type']==1) {?>
		document.getElementById("tipCon_content").style.left = "40%";
		document.getElementById("tipCon_content").style.top = "40%";
	<?php } else { ?>
	document.getElementById("tipCon_content").style.width = picwidth+20+"px";
	document.getElementById("tipCon_content").style.left = (windowwidth-picwidth)/2+"px";
	document.getElementById("tipCon_content").style.top = (windowheight-picheight)/2+"px";
	document.getElementById("tipCon_content").style.top = (windowheight-picheight)-140+"px";
	<?php }?>
	document.getElementById("tipCon_content").style.display ="block";
	}
<?php echo '</script'; ?>
>


</head>
<body>
<!--右下角-->
<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['youxia']) {?>
<div class="<?php echo $_smarty_tpl->tpl_vars['siteAd']->value['youxia']['textbgc'];?>
 right_window" style="<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['youxia']['type']==2) {?>width:<?php echo $_smarty_tpl->tpl_vars['siteAd']->value['youxia']['picwidth'];?>
px;<?php }?>" id="tipCon_right">
  <div class="clickMe content_1" id="clickMe_content">
   <h2 id="title_h2">
		<b><?php echo $_smarty_tpl->tpl_vars['siteAd']->value['youxia']['title'];?>
</b>
		<a id="btn_min_right" href="javascript:;" class="min"></a>
		<a id="btn_close_right" href="javascript:;" class="close"></a>
	</h2>
  </div>
  <div class="content2" id="showPic_right">
    <div class="wrap2" style="<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['youxia']['type']==1) {
} else { ?>padding:5<?php }?>">
			<a class="fontcolor" href="<?php echo $_smarty_tpl->tpl_vars['siteAd']->value['youxia']['Ad_url'];?>
"><?php echo $_smarty_tpl->tpl_vars['siteAd']->value['youxia']['content'];?>
</a>
		</div>
  </div>
</div>
<?php }?>
<!--左下角-->
<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['zuoxia']) {?>
<div class="<?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zuoxia']['textbgc'];?>
 left_window" style="<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['zuoxia']['type']==2) {?>width:<?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zuoxia']['picwidth'];?>
px;<?php }?>"  id="tipCon_left">
  <div class="clickMe content_1" id="clickMe_content">
   <h2 id="title_h2">
		<b><?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zuoxia']['title'];?>
</b>
		<a id="btn_min_left" href="javascript:;" class="min"></a>
		<a id="btn_close_left" href="javascript:;" class="close"></a>
	</h2>
  </div>
  <div class="content2" id="showPic_left">
    <div class="wrap2" style="<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['zuoxia']['type']==1) {
} else { ?>padding:5<?php }?>">
			<a class="fontcolor" href="<?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zuoxia']['Ad_url'];?>
"><?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zuoxia']['content'];?>
</a>
		</div>
  </div>
</div>
<?php }?>

<!--左上角-->
<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['zuoshang']) {?>
<div class="<?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zuoshang']['textbgc'];?>
 left_top_window" style="<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['zuoshang']['type']==2) {?>width:<?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zuoshang']['picwidth'];?>
px;<?php }?>"  id="tipCon_left_top">
  <div class="clickMe content_1" id="clickMe_content">
   <h2 id="title_h2">
		<b><?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zuoshang']['title'];?>
</b>
		<a id="btn_min_left_top" href="javascript:;" class="min"></a>
		<a id="btn_close_left_top" href="javascript:;" class="close"></a>
	</h2>
  </div>
  <div class="content2" id="showPic_left_top">
    <div class="wrap2" style="<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['zuoshang']['type']==1) {?>background-color:#FFF<?php } else { ?>padding:5<?php }?>">
			<a class="fontcolor" href="<?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zuoshang']['Ad_url'];?>
"><?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zuoshang']['content'];?>
</a>
		</div>
  </div>
</div>
<?php }?>
 
<!--中间-->
<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['zhongbu']) {?>
<div style="display:none" class="<?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zhongbu']['textbgc'];?>
 content_window"  id="tipCon_content">
  <div class="title" id="bar">
   <h2 id="title_h2">
		<span><?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zhongbu']['title'];?>
</span>
		<a  href="javascript:;" id="btn_close_content" class="close"></a>
	</h2>
  </div>
  <div class="<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['zhongbu']['type']==1) {?>content2<?php }?>" id="showPic_content">
    <div class="wrap2 " style="<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['zhongbu']['type']==1) {
} else { ?>padding:5<?php }?>">
			<a class="fontcolor" href="<?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zhongbu']['Ad_url'];?>
"><?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zhongbu']['content'];?>
</a>
		</div>
  </div>
   <div class="but">
  <a href="javascript:;" id="btn_close_content_2" class="but_a"> 关闭</a>
 </div>
</div>

<?php }?>
 <?php }?>
</body>
</html>
<?php }} ?>

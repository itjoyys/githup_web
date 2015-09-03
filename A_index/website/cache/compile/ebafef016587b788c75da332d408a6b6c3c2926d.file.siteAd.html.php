<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-07-21 02:47:06
         compiled from "D:\WWW\jpk\web_20156\A_index\website\templates\siteAd.html" */ ?>
<?php /*%%SmartyHeaderCode:28415597f2c17bb1f4-48010653%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ebafef016587b788c75da332d408a6b6c3c2926d' => 
    array (
      0 => 'D:\\WWW\\jpk\\web_20156\\A_index\\website\\templates\\siteAd.html',
      1 => 1437055788,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '28415597f2c17bb1f4-48010653',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5597f2c1c52f92_82409254',
  'variables' => 
  array (
    'isSitead' => 0,
    'siteAd' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5597f2c1c52f92_82409254')) {function content_5597f2c1c52f92_82409254($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<?php if ($_smarty_tpl->tpl_vars['isSitead']->value>0) {?>
<link href="<?php echo $_smarty_tpl->getConfigVariable('css');?>
/ggstyle.css" rel="stylesheet" type="text/css" />
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
	var TipBox_content = document.getElementById("tipCon_content");
	var ClickMe_content = document.getElementById("clickMe_content");
	var showPic_content = document.getElementById("showPic_content");
	var btn_close_content = document.getElementById("btn_close_content");
	var btn_min_content = document.getElementById("btn_min_content");
	ClickMe_content.onmouseover = function(){
		  showPic_content.style.display = 'block';
		}
	btn_close_content.onclick = function(){
		showPic_content.style.display = 'none';
		tipCon_content.style.display = 'none';
	}
	
}
<?php }?>
<?php echo '</script'; ?>
>


</head>
<body>
<!--右下角-->
<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['youxia']) {?>
<div class="<?php echo $_smarty_tpl->tpl_vars['siteAd']->value['youxia']['textbgc'];?>
 right_window" style="<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['youxia']['type']==2) {?>width:<?php echo $_smarty_tpl->tpl_vars['siteAd']->value['youxia']['picwidth'];?>
px;<?php }?>" id="tipCon_right">
  <div class="clickMe" id="clickMe_content">
   <h2 id="title_h2">
		<b><?php echo $_smarty_tpl->tpl_vars['siteAd']->value['youxia']['title'];?>
</b>
		<a id="btn_min_right" href="javascript:;" class="min"></a>
		<a id="btn_close_right" href="javascript:;" class="close"></a>
	</h2>
  </div>
  <div class="content2" id="showPic_right">
    <div class="wrap2" style="<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['youxia']['type']==1) {?>background-color:#FFF<?php } else { ?>padding:0<?php }?>">
			<a href="<?php echo $_smarty_tpl->tpl_vars['siteAd']->value['youxia']['Ad_url'];?>
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
  <div class="clickMe" id="clickMe_content">
   <h2 id="title_h2">
		<b><?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zuoxia']['title'];?>
</b>
		<a id="btn_min_left" href="javascript:;" class="min"></a>
		<a id="btn_close_left" href="javascript:;" class="close"></a>
	</h2>
  </div>
  <div class="content2" id="showPic_left">
    <div class="wrap2" style="<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['zuoxia']['type']==1) {?>background-color:#FFF<?php } else { ?>padding:0<?php }?>">
			<a href="<?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zuoxia']['Ad_url'];?>
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
  <div class="clickMe" id="clickMe_content">
   <h2 id="title_h2">
		<b><?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zuoshang']['title'];?>
</b>
		<a id="btn_min_left_top" href="javascript:;" class="min"></a>
		<a id="btn_close_left_top" href="javascript:;" class="close"></a>
	</h2>
  </div>
  <div class="content2" id="showPic_left_top">
    <div class="wrap2" style="<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['zuoshang']['type']==1) {?>background-color:#FFF<?php } else { ?>padding:0<?php }?>">
			<a href="<?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zuoshang']['Ad_url'];?>
"><?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zuoshang']['content'];?>
</a>
		</div>
  </div>
</div>
<?php }?>
 
<!--中间-->
<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['zhongbu']) {?>
<div class="<?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zhongbu']['textbgc'];?>
 content_window" style="<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['zhongbu']['type']==2) {?>width:<?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zhongbu']['picwidth'];?>
px;<?php }?>"  id="tipCon_content">
  <div class="clickMe" id="clickMe_content">
   <h2 id="title_h2">
		<b><?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zhongbu']['title'];?>
</b>
		<a id="btn_min_content" href="javascript:;" class="min"></a>
		<a id="btn_close_content" href="javascript:;" class="close"></a>
	</h2>
  </div>
  <div class="content2" id="showPic_content">
    <div class="wrap2" style="<?php if ($_smarty_tpl->tpl_vars['siteAd']->value['zhongbu']['type']==1) {?>background-color:#FFF<?php } else { ?>padding:0<?php }?>">
			<a href="<?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zhongbu']['Ad_url'];?>
"><?php echo $_smarty_tpl->tpl_vars['siteAd']->value['zhongbu']['content'];?>
</a>
		</div>
  </div>
</div>
<?php }?>
 <?php }?>
</body>
</html>
<?php }} ?>

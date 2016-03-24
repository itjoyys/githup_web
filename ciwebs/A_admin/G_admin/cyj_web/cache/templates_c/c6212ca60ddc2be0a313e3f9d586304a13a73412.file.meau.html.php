<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-11-10 13:11:22
         compiled from "D:\phpStudy\WWW\A_admin_ci\G_admin\cyj_web\views\meau.html" */ ?>
<?php /*%%SmartyHeaderCode:1931756417baab2cfd6-89104622%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c6212ca60ddc2be0a313e3f9d586304a13a73412' => 
    array (
      0 => 'D:\\phpStudy\\WWW\\A_admin_ci\\G_admin\\cyj_web\\views\\meau.html',
      1 => 1447132225,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1931756417baab2cfd6-89104622',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_56417baabf4389_21749595',
  'variables' => 
  array (
    'mtime' => 0,
    'login_name_1' => 0,
    'login_name' => 0,
    'data' => 0,
    'i' => 0,
    'v' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56417baabf4389_21749595')) {function content_56417baabf4389_21749595($_smarty_tpl) {?><?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>left</title>
<link  rel="stylesheet"  media="all"  href="<?php echo $_smarty_tpl->getConfigVariable('css');?>
/styleCss.css">
<link  rel="stylesheet"  media="all"  href="<?php echo $_smarty_tpl->getConfigVariable('css');?>
/reset.css">
<?php echo '<script'; ?>
  src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/jquery-1.7.min.js"  type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
  type="text/javascript">
var newscount = "0";
var lxvar = "1";
var langx = "zh-tw";
var toflag=false;
//弹出框公告程序
newscount = 0;	//如果想开启弹出窗，只需注释本行
$(document).ready(function () {
	setInterval("RefTime()",1000);

		// Set the click
		var menuobj=$("#nav_main_box ul li span a");
		var menuSub=$(".nav_second_wrap");
		var oldMenuIndex=0;
		menuobj.click(function () {
			$(this).parents().find("a").removeClass('menu_hover');
			$(this).parents().find("a").attr('style','');
			$(this).addClass('menu_hover');
			$(this).attr('style','background-position:0 0');
			$(menuSub[oldMenuIndex]).hide();
			var index=$(this).parent().index();
			var obj=$(menuSub[index]);
			var click_i = 0;
			 if(index==5){
				click_i = 6;
				toflag = true;
			}
			obj.show();
			if(toflag){
				obj.find("a")[click_i].click();
				$(obj.find("a")[click_i]).css("color","#ff0000");
				oldMenuIndex=index;
			}
			else
				toflag=true;
		});
		menuobj[5].click();

		$(".nav_second_wrap a").click(function(){
			$(this).parent().find("a").css('color','#3B2D1B');
			$(this).css('color','#ff0000');
			});
});

//设置美东时间
var mddate="<?php echo $_smarty_tpl->tpl_vars['mtime']->value;?>
";
var dd2=new Date(mddate);
function RefTime()
{

     dd2.setSeconds(dd2.getSeconds()+1);
	var myYears = ( dd2.getYear() < 1900 ) ? ( 1900 + dd2.getYear() ) : dd2.getYear();
	$("#vlock").html('美東時間'+'：'+myYears+'年'+fixNum(dd2.getMonth()+1)+'月'+fixNum(dd2.getDate())+'日 '+time(dd2));
	if(dd2.getSeconds()%30 == 0){
	   $.ajax({
         url: "./user_online",
         type: "GET",
         dataType: "json",
         success: function(data){
             $("#user_num").html(data);
         }
     });
	}
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
<?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
$(function(){
	$("#ag_select option").click(function(){
		var ag=$(this).val();
		parent.admin_func.location.href = ag;

	})
})
<?php echo '</script'; ?>
>
</head>
<body>
<div  id="wrap_box">
	<div  id="header_wrap">
		<div  id="logo_box">
	    	<img  src="<?php echo $_smarty_tpl->getConfigVariable('images');?>
/<?php echo $_SESSION['site_id'];?>
logo.png" style="margin-top:5px;" alt="logo">
		</div>
		<div  id="nav_sub_box">
	    	<span  id="vlock"></span> |
	    	<span>登陸帳戶：<?php echo $_smarty_tpl->tpl_vars['login_name_1']->value;?>
 &nbsp;|&nbsp;原始帳戶：<?php echo $_smarty_tpl->tpl_vars['login_name']->value;?>
 </span> |
		    <a  href="set_pwd"  target="admin_func"  style="">修改密碼</a> |
	     	<a  href="login_out"  target="_top"  style="">安全退出</a>
		</div>
		<div  id="nav_main_box">
			<ul>
				<li>
                  <span><a  href="#">账号管理</a></span>
				  <span><a  href="#">即時注單</a></span>
				  <span><a  href="#">報表查詢</a></span>
				  <span><a  href="#">賽果/赔率</a></span>
				  <span><a  href="#">现金系统</a></span>
                  <span><a  href="#">其它</a></span>

				</li>
			</ul>
		</div>
	</div>

<?php  $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['i']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['i']->key => $_smarty_tpl->tpl_vars['i']->value) {
$_smarty_tpl->tpl_vars['i']->_loop = true;
?>
	<div  class="nav_second_wrap" style="display: none;">
	   <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['i']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
	    <a href="<?php echo $_smarty_tpl->tpl_vars['v']->value['url'];?>
" <?php if ($_smarty_tpl->tpl_vars['v']->value['type']=='_') {?>target="_blank"<?php } else { ?>target="admin_func"<?php }?>  style=""><?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
</a>
	   <?php } ?>
	</div>
<?php } ?>
  <diiv  id="con_wrap">

	</div>
</div>


</body>
</html><?php }} ?>

<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-06-21 04:59:32
         compiled from "D:\WWW\jpk\web_20156\A_index\website\templates\zhuce_shiwan.html" */ ?>
<?php /*%%SmartyHeaderCode:183195585d426758318-70167089%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '78831805a8e0655566b309ff4e100574b226a214' => 
    array (
      0 => 'D:\\WWW\\jpk\\web_20156\\A_index\\website\\templates\\zhuce_shiwan.html',
      1 => 1434833968,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '183195585d426758318-70167089',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5585d426a945d2_94214788',
  'variables' => 
  array (
    'header' => 0,
    'notice' => 0,
    'username' => 0,
    'pk_token' => 0,
    'copy_right' => 0,
    'bottom' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5585d426a945d2_94214788')) {function content_5585d426a945d2_94214788($_smarty_tpl) {?><?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>
<?php echo $_smarty_tpl->tpl_vars['header']->value;?>


<body>

<!--banner-->
<div class="PageBnner2"></div>
<!--banner end-->
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
   <div class="navLeft">
   		<ul>
        	<li class="borderweight"><a href="./index.php?a=about">关于我们</a></li>
            <li><a href="./index.php?a=about">联系我们</a></li>
            <li><a href="./index.php?a=cooperation">合作伙伴</a></li>
            <li><a href="./index.php?a=cunkuan">存款帮助</a></li>
            <li><a href="./index.php?a=qukuan">取款帮助</a></li>
            <li><a href="./index.php?a=changjian">常见问题</a></li>
        </ul>
   </div>
   <div class="textRight">
   <form method="post" action="" name="form"  id="myFORM">
          <!--會員資料-->
          <div class="right">
             <fieldset class="zhuce">
               <legend class="legend">免费试玩注册</legend>
               <table border="0" cellspacing="0" cellpadding="0" class="table1">
                    <tr>
                        <td class="name">试玩账号：</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['username']->value;?>
&nbsp;
                        <input name="zcname" id="zcname" value="<?php echo $_smarty_tpl->tpl_vars['username']->value;?>
" type="hidden">
                        <input type='hidden' name='pk_token' value='<?php echo $_smarty_tpl->tpl_vars['pk_token']->value;?>
'/>
                        </td>
                    </tr>
                    <tr>
                       <td class="name">所需密码：</td>
                        <td><input name="zcpwd1" id="zcpwd1" type="password" value="" class="input" />
                        <font id="tishi">* 密码由6-15位字母、数字或符号组成</font><font id="zcpwd1_error" style="color:#f00;"></font></td>
                 </tr>
                    <tr>
                      <td class="name">确认密码：</td>
                     <td><input name="zcpwd2" id="zcpwd2" type="password" value="" class="input" />
                     <font id="tishi">* 再次确认密码</font><font id="zcpwd2_error" style="color:#f00;"></font></td>
                </tr>
            </table>
             </fieldset>
            
           
             <div class="foot">
             
          <div class="buttion">
          <p><input type="checkbox" checked="checked"/><label>我已届满合法博彩年龄﹐且同意各项开户条约。<a href="javascript:void(0);" id="AGREEMENT" class="rule">【相关條款及規則】</a></label></p>
            <input type="submit" name="OK2" id="OK2" class="joinform_submit"  value="注册提交">
            <input type="reset" name="CANCEL2" id="CANCEL2" class="joinform_cancel"  value="重新填写">
        </div>
        <!-- 備註 -->
        <div class="beizhu">
        <p>备注：</p>
        <ul>
        <li>1.标记有<label style="color:#F00;">*</label>者为必填项目。</li>
        <li>2.取款密碼為取款金額時的憑證,請會員務必填寫詳細資料。</li>
        <li>3.温馨提示：注册时如遇其它问题请联系在线客服，我们会第一时间为您解决！</li>
        </ul>
        </div>
        </div>

            </div>
			 </form>
		<!--s-->
					<div id="alert" style="display:none; background:##D8D0D6;width: 750px; height:500px;" class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-draggable">
						<div class="ui-widget-header ui-corner-all ui-helper-clearfix">
						<span class="kai">"开户协议"</span>
						<span class="righta" onclick="$('#alert').fadeOut('slow')"><a href="javascript:;">close</a></span>
						</div>
						<div style="width: auto; min-height: 0px; height: 480px;" id="Dialog" class="ui-dialog-content ui-widget-content" scrolltop="0" scrollleft="0">
						<meta charset="utf-8">

							<p>立即开通<?php echo $_smarty_tpl->tpl_vars['copy_right']->value;?>
账户，享受最优惠的各项红利!</p>
						<ul>
							<li> * <?php echo $_smarty_tpl->tpl_vars['copy_right']->value;?>
只接受合法博彩年龄的客户申请。同时我们保留要求客户提供其年龄证明的权利。</li>
							<li> * 在<?php echo $_smarty_tpl->tpl_vars['copy_right']->value;?>
进行注册时所提供的全部信息必须在各个方面都是准确和完整的。在使用借记卡或信用卡时，持卡人的姓名必须与在网站上注册时的一致。</li>
							<li> * 在开户后进行一次有效存款，恭喜您成为<?php echo $_smarty_tpl->tpl_vars['copy_right']->value;?>
有效会员!</li>
							<li> * 存款免手续费，开户最低入款金额100人民币，最高单次入款金额50000人民币。</li>
							<li> * 成为<?php echo $_smarty_tpl->tpl_vars['copy_right']->value;?>
有效会员后，客户有责任以电邮、联系在线客服、在<?php echo $_smarty_tpl->tpl_vars['copy_right']->value;?>
网站上留言等方式，随时向本公司提供最新的个人资料。</li>
							<li> * 经<?php echo $_smarty_tpl->tpl_vars['copy_right']->value;?>
发现会员有重复申请账号行为时，有权将这些账户视为一个联合账户。我们保留取消、收回会员所有优惠红利，以及优惠红利所产生的盈利之权利。每位玩家、每一住址、每一电子邮箱、每一电话号码、相同支付卡/信用卡号码，以及共享计算机环境 (例如:网吧、其他公共用计算机等)只能够拥有一个会员账号，各项优惠只适用于每位客户在<?php echo $_smarty_tpl->tpl_vars['copy_right']->value;?>
唯一的账户。</li>
							<li> * <?php echo $_smarty_tpl->tpl_vars['copy_right']->value;?>
是提供互联网投注服务的机构。请会员在注册前参考当地政府的法律，在博彩不被允许的地区，如有会员在<?php echo $_smarty_tpl->tpl_vars['copy_right']->value;?>
注册、下注，为会员个人行为，<?php echo $_smarty_tpl->tpl_vars['copy_right']->value;?>
不负责、承担任何相关责任。</li>
							<li> * 无论是个人或是团体，如有任何威胁、滥用<?php echo $_smarty_tpl->tpl_vars['copy_right']->value;?>
优惠的行为，<?php echo $_smarty_tpl->tpl_vars['copy_right']->value;?>
保留杈利取消、收回由优惠产生的红利，并保留权利追讨最高50%手续费。</li>
							<li> * 所有<?php echo $_smarty_tpl->tpl_vars['copy_right']->value;?>
的优惠是特别为玩家而设，在玩家注册信息有争议时，为确保双方利益、杜绝身份盗用行为，<?php echo $_smarty_tpl->tpl_vars['copy_right']->value;?>
保留权利要求客户向我们提供充足有效的文件， 并以各种方式辨别客户是否符合资格享有我们的任何优惠。</li>
							<li>客户一经注册开户，将被视为接受所有颁布在<?php echo $_smarty_tpl->tpl_vars['copy_right']->value;?>
网站上的规则与条例。 </li>
						</ul>
						<p style="color:#FF0000;">本公司是使用BBIN所提供的在线娱乐软件，若发现您在同系统的娱乐城上开设多个会员账户，并进行套利下注；本公司有权取消您的会员账号并将所有下注营利取消！ </p>
					</div>
					</div>
					<?php echo '<script'; ?>
>
					var windowWidth = $(document).width();  
					var windowHeight = $(document).height();  
					var popupHeight = $("#alert").height();  
					var popupWidth = $("#alert").width();
					//var topa = parseFloat((windowHeight-popupHeight)/2)+parseFloat($(document).scrollTop());
					$("#AGREEMENT").click(function(){
						$("#alert").fadeIn("slow")
						$('#alert').css({position:"fixed",top: '80px',left: (windowWidth-popupWidth)/2})
					})
					<?php echo '</script'; ?>
>
					<style>
						#alert{padding:10px}
						#alert .kai{padding-left:10px;font-size:16px;}
						#alert .righta{float:right}
						#alert ul li{line-height:20px; font-size:12px;}
					</style>
					<!--e-->
	</div>
</div>    
</div>
<?php echo $_smarty_tpl->tpl_vars['bottom']->value;?>

</body>
</html>
<?php echo '<script'; ?>
>

 $.extend({

  /**/
'_BuildPrompt': function(Name, PromptText, o) {
var options = {
'showArrow': true,
'positionType': 'topRight',
'width': 100,
'top': -28,
'left': -27,
'opacity': 0.8,
'AMarginLeft': 13
};
options = $.extend(options, o);

/**/
var prompt = $('<div>');
prompt.addClass("FormError");
/**/
var promptContent = $('<div>').addClass("FormErrorC").css('width', options.width).html(PromptText).appendTo(prompt);
/**/
if (options.showArrow) {
var arrow = $('<div>').addClass("FormErrorA").css('marginLeft', options.AMarginLeft);
switch (options.positionType) {
case "bottomLeft":
case "bottomRight":
prompt.find(".FormErrorC").before(arrow);
arrow.addClass("FormErrorABottom").html('<div style="width:1px;border:none;background: #DDDDDD;"><!-- --></div><div style="width:3px;border:none;background:#DDDDDD;"><!-- --></div><div style="width:1px;border-left:2px solid #DDDDDD;border-right:2px solid #ddd;border-bottom:0 solid #ddd;"><!-- --></div><div style="width:3px;"><!-- --></div><div style="width:5px;"><!-- --></div><div style="width:7px;"><!-- --></div><div style="width:9px;"><!-- --></div><div style="width:11px;"><!-- --></div><div style="width:13px;border:none;"><!-- --></div><div style="width:15px;border:none;"><!-- --></div>');
break;
case "topLeft":
case "topRight":
arrow.html('<div style="width:15px;border:none;"><!-- --></div><div style="width:13px;border:none;"><!-- --></div><div style="width:11px;"><!-- --></div><div style="width:9px;"><!-- --></div><div style="width:7px;"><!-- --></div><div style="width:5px;"><!-- --></div><div style="width:3px;"><!-- --></div><div style="width:1px;border-left:2px solid #ddd;border-right:2px solid #ddd;border-bottom:0 solid #DDDDDD;"><!-- --></div><div style="width:3px;border:none;background:#DDDDDD;"><!-- --></div><div style="width:1px;border:none;background: #DDDDDD;"><!-- --></div>');
prompt.append(arrow);
break;
}
}
/**/
prompt.css({
"top":
options.top,
"left": options.left,
"opacity": options.opacity
});
return $('<font>').addClass("Error_" + Name).css('position', 'relative').css('vertical-align', 'top').append(prompt.css('position', 'absolute'));
}
 });
<?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
$(function(){
	
	var validator=$("#myFORM").validate({
		'errorElement': 'font',
		rules:{
			zcpwd1:{
			required:true,
			rangelength:[6,15],
			},
			zcpwd2:{
			required:true,
			rangelength:[6,15],
			equalTo: "#zcpwd1"
			}
		},
		messages:{
			zcpwd1:{
			required: $._BuildPrompt('zcpwd1', '此项必填!',{'width': 150}),
			rangelength: $._BuildPrompt('zcpwd1', '密码由6-15位字母、数字或符号组成!',{'width': 270}),
			},
			zcpwd2:{
			required: $._BuildPrompt('zcpwd2', '此项必填!',{'width': 150}),
			rangelength: $._BuildPrompt('zcpwd1', '密码由6-15位字母、数字或符号组成!',{'width': 270}),
			equalTo: $._BuildPrompt('zcpwd2', '两次输入密码不一致!',{'width': 150})
			
			}
		}
	})
})


<?php echo '</script'; ?>
>
 <style>
/*错误提示样式*/
.FormError{display: block;
    left: 300px;
    position: absolute;
    top: 300px;
    z-index: 500;}
    .FormErrorC {
    background: none repeat scroll 0 0 #ee0101;
    border: 2px solid #ddd;
    border-radius: 6px;
    box-shadow: 0 0 6px #000;
    color: #fff;
    font-family: tahoma;
    font-size: 11px;
    line-height: 14px;
    padding: 4px 10px;
    position: relative;
    width: 100%;
    z-index: 501;
}
.FormErrorA {
    margin: -2px 0 0 13px;
    position: relative;
    width: 15px;
    z-index: 506;
}
.FormErrorA div {
    background: none repeat scroll 0 0 #ee0101;
    border-left: 2px solid #ddd;
    border-right: 2px solid #ddd;
    box-shadow: 0 2px 3px #444;
    display: block;
    font-size: 0;
    height: 1px;
    line-height: 0;
    margin: 0 auto;
}


#dl_form p{margin-bottom: 10px;}
</style><?php }} ?>

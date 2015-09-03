<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-07-03 04:01:17
         compiled from "/home/wwwuser/public_html/A_index/website/templates/zhuce.html" */ ?>
<?php /*%%SmartyHeaderCode:22918193955799f017690b3-43655038%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4a6e0806eac4ae38eddfb6b419a9cd8b87be7ac0' => 
    array (
      0 => '/home/wwwuser/public_html/A_index/website/templates/zhuce.html',
      1 => 1435671225,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '22918193955799f017690b3-43655038',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_55799f01835cb3_91764101',
  'variables' => 
  array (
    'header' => 0,
    'pk_token' => 0,
    'list' => 0,
    'con' => 0,
    'copy_right' => 0,
    'bottom' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55799f01835cb3_91764101')) {function content_55799f01835cb3_91764101($_smarty_tpl) {?><?php echo $_smarty_tpl->tpl_vars['header']->value;?>

    <?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>
        <div class="CenterMain" style=" margin-top:-60px;">
            <div class="PCenter">
                <div class="navLeft">
                    <ul>
                        <li class="borderweight"><a href="index.php?a=about">关于我们</a></li>
            <li ><a href="index.php?a=about">联系我们</a></li>
            <li><a href="index.php?a=cooperation">合作伙伴</a></li>
            <li><a href="index.php?a=cunkuan">存款帮助</a></li>
            <li><a href="index.php?a=qukuan">取款帮助</a></li>
            <li><a href="index.php?a=changjian">常见问题</a></li>
                    </ul>
                </div>
                <div class="textRight">
                    <div class="right">
      <form name="form" action="index.php?a=user_reg_do" id="dl_form" method="post">
      <input type="hidden" name="intr" value="<?php echo $_SESSION['intr'];?>
" />
                        <fieldset class="zhuce">
                            <legend class="legend">账号注册</legend>
                            <table border="0" cellspacing="0" cellpadding="0" class="table1">
                                <tr>
                                    <td class="name">介绍人：</td>
                                    <td>
                                    <input type='hidden' name='pk_token' value='<?php echo $_smarty_tpl->tpl_vars['pk_token']->value;?>
'/>
                                    <input type="text" value="<?php echo $_SESSION['intr'];?>
" readOnly="true" /></td>
                                </tr>
                                <tr>
                                    <td class="name">用户帐号：</td>
                                    <td>
                                        <input id="zcname" name="zcname" type="text" value="" class="zcname" />
                                        <font id="tishi">* 请输入4-11字符, 仅可输入英文字母以及数字的组合!</font>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="name">登录密码：</td>
                                    <td>
                                        <input type="password" value="" id="zcpwd1" name="zcpwd1" />
                                        <font id="tishi">* 密码由6-12位字母、数字或符号组成</font>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="name">确认密码：</td>
                                    <td>
                                        <input type="password" value="" class="input" id="zcpwd2" name="zcpwd2" />
                                        <font id="tishi">* 再次确认密码</font>
                                    </td>
                                </tr>
                               <?php  $_smarty_tpl->tpl_vars['con'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['con']->_loop = false;
 $_smarty_tpl->tpl_vars['cid'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['con']->key => $_smarty_tpl->tpl_vars['con']->value) {
$_smarty_tpl->tpl_vars['con']->_loop = true;
 $_smarty_tpl->tpl_vars['cid']->value = $_smarty_tpl->tpl_vars['con']->key;
?>
                               <tr>
                                    <td class="name"><?php echo $_smarty_tpl->tpl_vars['con']->value['name_zh'];?>
：</td>
                                    <td>
                                        <input type="text" value="" class="input" id="<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
" />
                                        <font id="tishi"><?php echo $_smarty_tpl->tpl_vars['con']->value['status'];?>
 <?php echo $_smarty_tpl->tpl_vars['con']->value['info'];?>
 </font>
                                    </td>
                                </tr>
                               <?php } ?>
                            </table>
                        </fieldset>
                        <fieldset class="zhuce">
                            <legend class="legend">会员资料</legend>
                            <table border="0" cellspacing="0" cellpadding="0" class="table1">
                                <tr>
                                    <td class="name" width="80px">真实姓名：</td>
                                    <td>
                                        <input type="text" value="" class="input" id="zcturename" name="zcturename" />
                                        <font id="tishi">* 必须与您的银行帐户名称相同，否则不能出款!</font>
                                    </td>
                                </tr>
								<tr>
                                    <td class="name" width="80px">出生日期：</td>
                                    <td>
										<select name="birthday1" class="address">
											<option value="1960">1960</option>
											<option value="1961">1961</option>
											<option value="1962">1962</option>
											<option value="1963">1963</option>
											<option value="1964">1964</option>
											<option value="1965">1965</option>
											<option value="1966">1966</option>
											<option value="1967">1967</option>
											<option value="1968">1968</option>
											<option value="1969">1969</option>
											<option value="1970">1970</option>
											<option value="1971">1971</option>
											<option value="1972">1972</option>
											<option value="1973">1973</option>
											<option value="1974">1974</option>
											<option value="1975">1975</option>
											<option value="1976">1976</option>
											<option value="1977">1977</option>
											<option value="1978">1978</option>
											<option value="1979">1979</option>
											<option value="1980">1980</option>
											<option value="1981">1981</option>
											<option value="1982">1982</option>
											<option value="1983">1983</option>
											<option value="1984">1984</option>
											<option value="1985">1985</option>
											<option value="1986">1986</option>
											<option value="1987">1987</option>
											<option value="1988">1988</option>
											<option value="1989">1989</option>
											<option value="1990">1990</option>
											<option value="1991">1991</option>
											<option value="1992">1992</option>
											<option value="1993">1993</option>
											<option value="1994">1994</option>
											<option value="1995">1995</option>
											<option value="1996">1996</option>
											<option value="1997">1997</option>
											<option value="1998">1998</option>
											<option value="1999">1999</option>
											<option value="2000">2000</option>
											<option value="2001">2001</option>
											<option value="2002">2002</option>
											<option value="2003">2003</option>
											<option value="2004">2004</option>
											<option value="2005">2005</option>
											<option value="2006">2006</option>
											<option value="2007">2007</option>
											<option value="2008">2008</option>
											<option value="2009">2009</option>
											<option value="2010">2010</option>
										</select>
										<select name="birthday2" class="address">
											<option value="01">01</option>
											<option value="02">02</option>
											<option value="03">03</option>
											<option value="04">04</option>
											<option value="05">05</option>
											<option value="06">06</option>
											<option value="07">07</option>
											<option value="08">08</option>
											<option value="09">09</option>
											<option value="10">10</option>
											<option value="11">11</option>
											<option value="12">12</option>
										</select>
										<select name="birthday3" class="address">
											<option value="01">01</option>
											<option value="02">02</option>
											<option value="03">03</option>
											<option value="04">04</option>
											<option value="05">05</option>
											<option value="06">06</option>
											<option value="07">07</option>
											<option value="08">08</option>
											<option value="09">09</option>
											<option value="10">10</option>
											<option value="11">11</option>
											<option value="12">12</option>
											<option value="13">13</option>
											<option value="14">14</option>
											<option value="15">15</option>
											<option value="16">16</option>
											<option value="17">17</option>
											<option value="18">18</option>
											<option value="19">19</option>
											<option value="20">20</option>
											<option value="21">21</option>
											<option value="22">22</option>
											<option value="23">23</option>
											<option value="24">24</option>
											<option value="25">25</option>
											<option value="26">26</option>
											<option value="27">27</option>
											<option value="28">28</option>
											<option value="29">29</option>
											<option value="30">30</option>
											<option value="31">31</option>
										</select>
										<font id="tishi">请填写真实出生日期。会员在生日当天有相应的优惠活动!</font>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="name">取款密码：</td>
                                    <td>
                                        <select name="address1" class="address">
                                            <option value="">-</option>
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                        </select>
                                        <select name="address2" class="address">
                                            <option value="">-</option>
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                        </select>
                                        <select name="address3" class="address">
                                            <option value="">-</option>
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                        </select>
                                        <select name="address4" class="address">
                                            <option value="">-</option>
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                        </select>
                                        <font id="tishi">*提款认证必须，请务必记住!</font>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="name">验证码：</td>
                                    <td>
                                        <input type="text" id="zcyzm" name="zcyzm" value="" style="width:100px;" />
                                        <img style="margin-left:10px; cursor:pointer" id="yzm" src="./yzm.php">
                                    </td> 
                                </tr>
                            </table>
                        </fieldset>
                        <div class="foot">
                            <div class="buttion">
                                <p>
                                    <input type="checkbox" checked="checked" name="agree_form" />
                                    <label>我已届满合法博彩年龄﹐且同意各项开户条约。<a href="javascript:void(0);" id="AGREEMENT" class="rule">【相关條款及規則】</a></label>
                                </p>
                                <input type="submit" name="OK2" id="OK2" class="joinform_submit" value="注册提交">
                                <input type="reset" name="CANCEL2" id="CANCEL2" class="joinform_cancel" value="重新填写">
                            </div>
                            <!-- 備註 -->
                            <div class="beizhu">
                                <p>备注：</p>
                                <ul>
                                    <li>1.标记有
                                        <label style="color:#F00;">*</label>者为必填项目。</li>
                                    <li>2.取款密碼為取款金額時的憑證,請會員務必填寫詳細資料。</li>
                                    <li>3.温馨提示：注册时如遇其它问题请联系在线客服，我们会第一时间为您解决！</li>
                                </ul>
                            </div>
                        </div>
						</form>
						
						
                    </div>
					<!--s-->
					<div id="alert" style="display:none; background:##D8D0D6;width: 750px;" class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-draggable">
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
        <!--footer-->
        <?php echo $_smarty_tpl->tpl_vars['bottom']->value;?>

		
<?php echo '<script'; ?>
>
//验证码 换一下
      $('#zcyzm').live('focus',function(){
        $('#yzm').attr('src','./yzm.php?tm='+Math.random());
      })
	  $('#yzm').live('click',function(){
        $('#yzm').attr('src','./yzm.php?tm='+Math.random());
      })
<?php echo '</script'; ?>
>
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
'left': -35,
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

  $.validator.addMethod('equalToUsername',
  function(value, element) {
    return (value == $('#zcname').val()) ? false: true;
  },
  '帐号与密码不能相同');

   /*ajax验证用户名是否存在*/
  $.validator.addMethod('CheckDBuser',
  function(value, element) {
  var redata = false;
    $.ajax({
      'url': 'index.php?a=checkUserajax',
      'type': 'get',
      'data': {
      'ajax': 'CheckDBuser',
      'user': value
      },
      'cache': false,
      'async': false,
      'success': function(data) {
        redata = data;
      }
    });
  return redata;
  },
  '此用户名已经有人使用了！');
  
    $.validator.addMethod("isNum", function (value, element) {  
   var RegExp = /^\d+$/;  
   return RegExp.test(value);  
}, "只能为数字!");  
  $.validator.addMethod("isNum_", function (value, element) {  
  if(value=='') return true;
   var RegExp = /^\d+$/;  
   return RegExp.test(value);  
}, "只能为数字!");  

$.validator.addMethod("iscard", function (value, element) { 
	 var RegExp = /^\w+$/;  
	return RegExp.test(value); 
	}, "请输入正确的身份证号!");  
	
$.validator.addMethod("iscard_", function (value, element) { 
	if(value=='') return true;
	 var RegExp = /^\w+$/;  
	return RegExp.test(value); 
}, "请输入正确的身份证号!");  

  /*验证只允许是数字和字母*/
  $.validator.addMethod('CheckUser',
  function(value, element) {
  var Ch = /^[a-z0-9_]+$/;
    return this.optional(element) || (Ch.test(value));
  },
  '只允许是数字和字母!');
  
  	$.validator.addMethod("ische", function (value, element) {  
   var RegExp = /[^\u0000-\u00FF]$/;  
   return RegExp.test(value);  
}, "只能为中文!");  

   /*ajax验证验证码*/
  $.validator.addMethod('checkCode',
  function(value, element) {
  var redata = false;
    $.ajax({
      'url': 'index.php?a=checkCodeajax',
      'type': 'get',
      'data': {
      'ajax': 'checkCode',
      'code': value
      },
      'cache': false,
      'async': false,
      'success': function(data) {
        redata = data;
      }
    });
  return redata;
  },
  '验证码错误，请重新输入');


  //表单验证
  var validator=$("#dl_form").validate({
  'onkeyup': false,
  'focusCleanup': true,
  'focusInvalid': false,
  'errorElement': 'font',
  'rules': {
    'zcname': {
      'required': true,
      'rangelength':[4,11],
      'CheckUser':true,
      'CheckDBuser':true
    },
    'zcpwd1':{
      'required': true,
      'rangelength':[6,12],
      'CheckUser':true,
      'equalToUsername':true
    },
    'zcpwd2':{
      'required': true,
      'equalTo': '#zcpwd1'
    },
    'zcturename':{
      'required': true,
	  'ische': true,
    },
    'zcyzm':{
      'required': true,
	  'checkCode':true,
    },
	<?php  $_smarty_tpl->tpl_vars['con'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['con']->_loop = false;
 $_smarty_tpl->tpl_vars['cid'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['con']->key => $_smarty_tpl->tpl_vars['con']->value) {
$_smarty_tpl->tpl_vars['con']->_loop = true;
 $_smarty_tpl->tpl_vars['cid']->value = $_smarty_tpl->tpl_vars['con']->key;
?>
		<?php if ($_smarty_tpl->tpl_vars['con']->value['status']=='*') {?>
			<?php if ($_smarty_tpl->tpl_vars['con']->value['name']=='email') {?>
				"email":{
					'required': true,
					'email':true
				},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='passport') {?>
				"passport":{
					'required': true,
					'iscard':true
				},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='qq') {?>
				"qq":{
					'required': true,
					'isNum':true
				},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='mobile') {?>
				"mobile":{
					'required': true,
					'isNum':true
				},
			<?php }?>
		<?php } else { ?>
			<?php if ($_smarty_tpl->tpl_vars['con']->value['name']=='email') {?>
				"email":{
				'required': function() {return $("#email").val()!=""; },
					'email': true,
				},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='passport') {?>
				"passport":{
					'iscard_':true
				},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='qq') {?>
				"qq":{
					'required':false,
					'isNum_':true
				},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='mobile') {?>
				"mobile":{
					'isNum_':true
				},
			<?php }?>
		<?php }?>
	<?php } ?>
    'address1':{
      'required': true,
    },
    'address2':{
      'required': true,
    },
    'address3':{
      'required': true,
    },
    'address4':{
      'required': true,
    },
    'agree_form':{
      'required': true,
    },
  },
  'messages': {
    'zcname': {
    'required': $._BuildPrompt('zcname', '请输入帐号!',{'width': 150}),
    'rangelength': $._BuildPrompt('zcname', '请输入4-11个字元, 仅可输入英文字母以及数字的组合!',{'width': 270}),
    'CheckUser':$._BuildPrompt('zcname', '只允许是数字和字母!',{'width': 150}),
    'CheckDBuser':$._BuildPrompt('zcname', '该用户名已存在!',{'width': 150})
    },
    'zcpwd1':{
      'required': $._BuildPrompt('zcpwd1', '请输入密码!',{'width': 150}),
      'rangelength': $._BuildPrompt('zcpwd1', '须为6~12码英文或数字且符合0~9或a~z字元!',{'width': 270}),
      'CheckUser':$._BuildPrompt('zcpwd1', '只允许是数字和字母!',{'width': 150}),
      'equalToUsername':$._BuildPrompt('zcpwd1', '账号与密码不能相同!',{'width': 150}),
    },
    'zcpwd2':{
      'required': $._BuildPrompt('zcpwd2', '请再次输入密码!',{'width': 150}),
      'equalTo':  $._BuildPrompt('zcpwd2', '两次密码输入不一致!',{'width': 150})

    },
    <?php  $_smarty_tpl->tpl_vars['con'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['con']->_loop = false;
 $_smarty_tpl->tpl_vars['cid'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['con']->key => $_smarty_tpl->tpl_vars['con']->value) {
$_smarty_tpl->tpl_vars['con']->_loop = true;
 $_smarty_tpl->tpl_vars['cid']->value = $_smarty_tpl->tpl_vars['con']->key;
?>
		<?php if ($_smarty_tpl->tpl_vars['con']->value['status']=='*') {?>
			<?php if ($_smarty_tpl->tpl_vars['con']->value['name']=='email') {?>
				"email":{
					'required':  $._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", '<?php echo $_smarty_tpl->tpl_vars['con']->value['info'];?>
',{'width': 150}),
					'email': $._BuildPrompt('email', '请填写正确的邮箱格式!',{'width': 150}),
				},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='passport') {?>
				"passport":{
					'required': $._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", '请填写正确的身份证号！',{'width': 150}),
					'iscard':$._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", '请填写正确的身份证号！',{'width': 150}),
				},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='qq') {?>
				"qq":{
					'required': $._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", '请填写正确的QQ号！',{'width': 150}),
					'isNum':$._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", '请填写正确的QQ号！',{'width': 150}),
				},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='mobile') {?>
				"mobile":{
					'required': $._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", '请填写正确的手机号！',{'width': 150}),
					'isNum':$._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", '请填写正确的手机号！',{'width': 150}),
				},
			<?php }?>
		<?php } else { ?>
			<?php if ($_smarty_tpl->tpl_vars['con']->value['name']=='email') {?>
			"email":{
				'email': $._BuildPrompt('email', '请填写正确的邮箱格式!',{'width': 150}),
			},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='passport') {?>
				"passport":{
					'iscard_':$._BuildPrompt('passport', '请填写正确的身份证号!',{'width': 150}),
				},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='qq') {?>
				"qq":{
					'isNum_':$._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", '请填写正确的QQ号！',{'width': 150}),
				},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='mobile') {?>
				"mobile":{
					'isNum_':$._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", '请填写正确的手机号！',{'width': 150}),
				},
			<?php }?>
		<?php }?>
	<?php } ?>
    'zcturename':{
      'required': $._BuildPrompt('zcturename', '请输入真实姓名!',{'width': 150}),
	  'ische': $._BuildPrompt('zcturename', '请输入正确的真实姓名!',{'width': 150}),
    },
    'zcyzm':{
      'required': $._BuildPrompt('zcyzm', '请输入验证码!',{'width': 150}),
	  'checkCode':$._BuildPrompt('zcyzm', '验证码不正确!',{'width': 150}),
    },
    'address1':{
      'required': $._BuildPrompt('address4', '请选择取款密码!',{'width': 150}),
    },
    'address2':{
      'required': $._BuildPrompt('address4', '请选择取款密码!',{'width': 150}),
    },
    'address3':{
      'required': $._BuildPrompt('address4', '请选择取款密码!',{'width': 150}),
    },
    'address4':{
      'required': $._BuildPrompt('address4', '请选择取款密码!',{'width': 150}),
    },
    'agree_form':{
      'required': $._BuildPrompt('agree_form', '请勾选同意条款!',{'width': 150}),
    },
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
</style>


<?php }} ?>

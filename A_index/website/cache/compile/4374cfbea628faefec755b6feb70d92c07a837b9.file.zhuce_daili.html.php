<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-06-29 11:35:19
         compiled from "/home/wwwuser/public_html/t/website/templates/zhuce_daili.html" */ ?>
<?php /*%%SmartyHeaderCode:2090983263559165b79198b3-96231480%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4374cfbea628faefec755b6feb70d92c07a837b9' => 
    array (
      0 => '/home/wwwuser/public_html/t/website/templates/zhuce_daili.html',
      1 => 1434833912,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2090983263559165b79198b3-96231480',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'header' => 0,
    'notice' => 0,
    'config' => 0,
    'con' => 0,
    'copy_right' => 0,
    'bottom' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_559165b7a91db6_71489198',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_559165b7a91db6_71489198')) {function content_559165b7a91db6_71489198($_smarty_tpl) {?><?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>
<?php echo $_smarty_tpl->tpl_vars['header']->value;?>

<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/PCASClass.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/jquery-1.8.3.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/jquery.validate.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>

<?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
	//验证码
	function next_checkNum_img(){
		document.getElementById("checkNum_img").src = "./yzm.php?"+Math.random();
		return false;
	}
<?php echo '</script'; ?>
>

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
        	<li class="borderweight"><a href="index.php?a=about">关于我们</a></li>
            <li ><a href="index.php?a=about">联系我们</a></li>
            <li><a href="index.php?a=cooperation">合作伙伴</a></li>
            <li><a href="index.php?a=cunkuan">存款帮助</a></li>
            <li><a href="index.php?a=qukuan">取款帮助</a></li>
            <li><a href="index.php?a=changjian">常见问题</a></li>
        </ul>
   </div>
   <div class="textRight">
   	<form method="post" action="" name="form"  id="myFORM">
        <!--會員資料-->
        <div class="right">
			<fieldset class="zhuce">
				<legend class="legend">代理注册-新增代理</legend>
				<table border="0" cellspacing="0" cellpadding="0" class="table1">
					<tr>
						<td class="name">代理账号：</td>
						<td>
							<input name="r_user_form" id="r_user_form" type="text" value=""  class="input" style='float:left;'/>
							<font id="tishi">* 账号：请输入4-11个字元，仅可输入英文字母及数字组合</font><font id="zcpwd1_error" style="color:#f00;"></font>
						</td>
					</tr>

					<tr>
						<td class="name">登录密码：</td>
						<td>
							<input name="password_form" id="password_form" type="password" value="" class="input" style='float:left;' />
							<font id="tishi">* 密码由6-15位字母、数字或符号组成</font><font id="zcpwd1_error" style="color:#f00;"></font>
						</td>
					</tr>
					<tr>
						<td class="name">确认密码：</td>
						<td>
							<input name="passwd_form" id="passwd_form" type="password" value="" class="input" style='float:left;' />
							<font id="tishi">* 再次确认密码</font><font id="zcpwd2_error" style="color:#f00;"></font>
						</td>
					</tr>
					<tr>
						<td class="name">验证码：</td>
						<td><input name="yzm_form" type="text" id="yzm_form" style="width:50px;float:left;" maxlength="4" onfocus="next_checkNum_img()" />
							<img src="./yzm.php" style="cursor:pointer; position:relative; left:5px" alt="点击更换" onclick="next_checkNum_img()" id="checkNum_img" />
						</td>
					</tr>
				</table>
			</fieldset>
			<fieldset class="zhuce">
				<legend class="legend">代理基本数据</legend>
				<table border="0" cellspacing="0" cellpadding="0" class="table1">
					<?php  $_smarty_tpl->tpl_vars['con'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['con']->_loop = false;
 $_smarty_tpl->tpl_vars['cid'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['config']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['con']->key => $_smarty_tpl->tpl_vars['con']->value) {
$_smarty_tpl->tpl_vars['con']->_loop = true;
 $_smarty_tpl->tpl_vars['cid']->value = $_smarty_tpl->tpl_vars['con']->key;
?>
					<tr>
						<td class="name"><?php echo $_smarty_tpl->tpl_vars['con']->value['name_zh'];?>
:</td>
						<td><input name="<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
" id="<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
" type="text" value=""  class="input" style='float:left;'/>
						<font id="tishi"><?php echo $_smarty_tpl->tpl_vars['con']->value['status'];?>
 <?php echo $_smarty_tpl->tpl_vars['con']->value['cue'];?>
</font><font id="zcpwd1_error" style="color:#f00;"></font></td>
					</tr>
					<?php } ?>
					<tr>
						<td class="name">推广网址：</td>
						<td><input name="from_url_form" id="from_url_form" type="text" value="" class="input" style='float:left;' />
						<font id="tishi">* 输入您的网址全地址不包含http://，仅填写您的网址即可。</font><font id="zcpwd1_error" style="color:#f00;"></font></td>
					</tr>
					<tr>
						<td class="name">其他方式：</td>
						<td><input name="other_method_form" id="other_method_form" type="text" value="" class="input" style='float:left;' />
						<font id="tishi">* 若您有其他的推广平台，可以在此输入可文字描述！</font><font id="zcpwd2_error" style="color:#f00;"></font></td>
					</tr>
				</table>
			</fieldset>
             
			<fieldset class="zhuce">
				<legend class="legend">会员资料</legend>
				<table border="0" cellspacing="0" cellpadding="0" class="table1">
                    <tr>
						<td class="name">开户银行：</td>
						<td>
							<select name="bank_type_form" id="bank_type_form" class="address">
								<option value="">請選擇取款銀行</option>
								<option value="1">中國銀行</option>
								<option value="2">中國工商銀行</option>
								<option value="3">中國建設銀行</option>
								<option value="4">中國招商銀行</option>
								<option value="5">中國民生銀行</option>
								<option value="7">中國交通銀行</option>
								<option value="8">中國郵政銀行</option>
								<option value="9">中國农业銀行</option>
								<option value="10">華夏銀行</option>
								<option value="11">浦發銀行</option>
								<option value="12">廣州銀行</option>
								<option value="13">北京銀行</option>
								<option value="14">平安銀行</option>
								<option value="15">杭州銀行</option>
								<option value="16">溫州銀行</option>
								<option value="17">中國光大銀行</option>
								<option value="18">中信銀行</option>
								<option value="19">浙商銀行</option>
								<option value="20">漢口銀行</option>
								<option value="21">上海銀行</option>
								<option value="22">廣發銀行</option>
								<option value="23">农村信用社</option>
								<option value="24">深圳发展银行</option>
								<option value="25">渤海银行</option>
								<option value="26">东莞银行</option>
								<option value="27">宁波银行</option>
								<option value="28">东亚银行</option>
								<option value="29">晋商银行</option>
								<option value="30">南京银行</option>
								<option value="31">广州农商银行</option>
								<option value="32">上海农商银行</option>
								<option value="33">珠海农村信用合作联社</option>
								<option value="34">顺德农商银行</option>
								<option value="35">尧都区农村信用联社</option>
								<option value="36">浙江稠州商业银行</option>
								<option value="37">北京农商银行</option>
								<option value="38">重庆银行</option>
								<option value="39">广西农村信用社</option>
								<option value="40">江苏银行</option>
								<option value="41">吉林银行</option>
								<option value="42">成都银行</option>
								<option value="50">兴业银行</option>
								<option value="100">支付宝</option>
							</select>&nbsp;
						</td>
                    </tr>
                    <tr>
                    <td class="name">银行账号：</td>
                    <td><input name="bank_account_form" id="bank_account_form" type="text" value="" class="input" style='float:left;width:157px'  /></td>
                	</tr>
                    <tr>
                        <td class="name">银行省份：</td>
                        <td>
							<select name="bank_province_form"  id="bank_province_form" class="address" style='float:left;width:162px'>
                            </select>&nbsp;
						</td>
                    </tr>
                    <tr>
                        <td class="name">银行区域：</td>
                        <td>
							<select name="bank_county_form"  id="bank_county_form" class="address" style='float:left;width:162px'>
                            </select>&nbsp;
						</td>
                    </tr>
                    <tr>
                        <td class="name">取款密码：</td>
                        <td>
							<select name="safe_pass1"  id="safe_pass1" class="address">
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
                            <select name="safe_pass2"  id="safe_pass2" class="address">
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
                            <select name="safe_pass3"  id="safe_pass3" class="address" >
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
                            <select name="safe_pass4"  id="safe_pass4" class="address">
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
							<font id="tishi">* 帐号：请输入6-15字符, 仅可输入英文字母以及数字的组合!</font>
                        </td>
                    </tr>
					<tr>
						<td class="name">申请理由：</td>
						<td><textarea name="grounds" class="" style="width:158px; height:80px;padding-left:4px;padding-top:4px;"></textarea></td>
					</tr>
                </table>
            </fieldset>
			<?php echo '<script'; ?>
 type="text/javascript">
			new PCAS("bank_province_form","bank_county_form");
			<?php echo '</script'; ?>
>
            
			<div class="foot">
				<div class="buttion">
					<p><input name="agree_form" id="agree_form" type="checkbox" checked="checked"/><label>已经年满18岁，本人并无抵触所在国家所管辖的法律范围，且同意<a href="javascript:void(0);" id="AGREEMENT" class="rule">【相关條款及規則】</a></label></p>
					<input type="submit"  id="submit_form" class="joinform_submit"  value="注册提交">
					<input type="reset" id="reset_form" class="joinform_cancel"  value="重新填写">
				</div>
				<!-- 備註 -->
				</form>
				<div class="beizhu">
					<p>备注：</p>
					<ul>
						<li>1.标记有<label style="color:#F00;">*</label>者为必填项目。</li>
						<li>2.取款密码为取款金额时的凭证，请会员务必填写牢记。</li>
						<li>3.温馨提示：注册时如遇到注册不了等其它问题请联系在线客服，我们会第一时间为您解决好！</li>
					</ul>
				</div>
			</div>
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
		$("#AGREEMENT").click(function(){
			$("#alert").fadeIn("slow")
			$('#alert').css({position:"fixed",top: ((windowHeight-popupHeight)/2)-popupHeight,left: (windowWidth-popupWidth)/2})
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
	$.validator.addMethod('equalToUsername',
	function(value, element) {
		return (value == $('#r_user_form').val()) ? false: true;
	},
	'帐号与密码不能相同');



	 /*ajax验证用户名是否存在*/
	$.validator.addMethod('CheckDBuser',
	function(value, element) {
	var redata = false;
		$.ajax({
			'url': './index.php?a=daili_shenqing',
			'type': 'get',
			'data': {
			'ajax': 'Check_agent_user',
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

	 /*ajax验证真实姓名是否重复*/
	$.validator.addMethod('check_r_name',
	function(value, element) {
	var redata = false;
		$.ajax({
			'url': './index.php?a=daili_shenqing',
			'type': 'get',
			'data': {
			'ajax': 'check_r_name',
			'r_name': value
			},
			'cache': false,
			'async': false,
			'success': function(data) {
			redata = data;
			}
		});
	return redata;
	},
	'此姓名已经有人使用了！')

	 /*ajax验证银行卡*/
	$.validator.addMethod('checkcard',
	function(value, element) {
	var redata = false;
		$.ajax({
			'url': './index.php?a=daili_shenqing',
			'type': 'get',
			'data': {
			'ajax': 'checkcard',
			'bank_account_form': value
			},
			'cache': false,
			'async': false,
			'success': function(data) {
			redata = data;
			}
		});
	return redata;
	},
	'该银行卡已经绑定到代理账号！');
	
	$.validator.addMethod("ische", function (value, element) {  
   var RegExp = /[^\u0000-\u00FF]$/;  
   return RegExp.test(value);  
}, "只能为中文!");  
	$.validator.addMethod("ische_", function (value, element) {  
	if(value=='') return true;
   var RegExp = /[^\u0000-\u00FF]$/;  
   return RegExp.test(value);  
}, "只能为中文!");  
	
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

	/*验证只允许是数字和字母*/
	$.validator.addMethod('CheckUser',
	function(value, element) {
	var Ch = /^[A-Za-z0-9_]+$/;
		return this.optional(element) || (Ch.test(value));
	},
	'只允许是数字和字母!');

	/*验证网址*/
	$.validator.addMethod('CheckUrl',
	function(value, element) {
	var Ch = /^([a-z0-9]+([a-z0-9-]*(?:[a-z0-9]+))?\.)?[a-z0-9]+([a-z0-9-]*(?:[a-z0-9]+))?(\.us|\.tv|\.org\.cn|\.org|\.net\.cn|\.net|\.mobi|\.me|\.la|\.info|\.hk|\.gov\.cn|\.edu|\.com\.cn|\.com|\.co\.jp|\.co|\.cn|\.cc|\.biz)$/i;
	return this.optional(element) || (Ch.test(value));
	},
	'网址格式不正确!');


	//表单验证
	var validator=$("#myFORM").validate({
	  'onkeyup': false,
  'focusCleanup': true,
  'focusInvalid': false,
  'errorElement': 'font',
	'rules': {
		'r_user_form': {
			'required': true,
			'rangelength':[4,11],
			'CheckUser':true,
			'CheckDBuser':true
		},
		'password_form':{
			'required': true,
			'rangelength':[6,12],
			'CheckUser':true,
			'equalToUsername':true
		},
		'passwd_form':{
			'required': true,
			'equalTo': '#password_form'
		},
		'yzm_form':{
			'required': true,
			'checkCode':true,
		},
		'from_url_form':{
			'required': true,
			'CheckUrl':true
		},
		<?php  $_smarty_tpl->tpl_vars['con'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['con']->_loop = false;
 $_smarty_tpl->tpl_vars['cid'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['config']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['con']->key => $_smarty_tpl->tpl_vars['con']->value) {
$_smarty_tpl->tpl_vars['con']->_loop = true;
 $_smarty_tpl->tpl_vars['cid']->value = $_smarty_tpl->tpl_vars['con']->key;
?>
		<?php if ($_smarty_tpl->tpl_vars['con']->value['status']=='*') {?>
			<?php if ($_smarty_tpl->tpl_vars['con']->value['name']=='is_email') {?>
				"is_email":{
					'required': true,
					'email':true
				},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_zh_name') {?>
				"is_zh_name":{
					'required': true,
					'ische':true
				},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_en_name') {?>
				"is_en_name":{
					'required': true,
					'iscard':true
				},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_card') {?>
				"is_card":{
					'required': true,
					'iscard':true
				},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_qq') {?>
				"is_qq":{
					'required': true,
					'isNum':true
				},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_payname') {?>
				"is_payname":{
					'required': true,
					'ische':true
				},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_phone') {?>
				"is_phone":{
					'required': true,
					'isNum':true
				},
			<?php }?>
		<?php } else { ?>
			<?php if ($_smarty_tpl->tpl_vars['con']->value['name']=='is_email') {?>
				"is_email":{
				
					'email': true,
				},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_zh_name') {?>
				"is_zh_name":{
					'ische_':true
				},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_en_name') {?>
				"is_en_name":{
					
					'iscard_':true
				},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_card') {?>
				"is_card":{
					'iscard_':true
				},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_qq') {?>
				"is_qq":{
					'isNum_':true
				},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_payname') {?>
				"is_payname":{
					'ische_':true
				},
			<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_phone') {?>
				"is_phone":{
					'isNum_':true
				},
			<?php }?>
		<?php }?>
	<?php } ?>

		'bank_account_form':{
			'required': true,
			'isNum': true,
			'checkcard':true,
		},
		'bank_province_form':{
			'required': true,
		},
		'bank_county_form':{
			'required': true,
		},
		'safe_pass1':{
			'required': true,
		},
		'safe_pass2':{
			'required': true,
		},
		'safe_pass3':{
			'required': true,
		},
		'safe_pass4':{
			'required': true,
		},
		'agree_form':{
			'required': true,
		},
	},
	'messages': {
		'r_user_form': {
		'required': $._BuildPrompt('r_user_form', '请输入帐号!',{'width': 150}),
		'rangelength': $._BuildPrompt('r_user_form', '请输入4-11个字元, 仅可输入英文字母以及数字的组合!',{'width': 270}),
		'CheckUser':$._BuildPrompt('r_user_form', '只允许是数字和字母!',{'width': 150}),
		'CheckDBuser':$._BuildPrompt('r_user_form', '该用户名已存在!',{'width': 150})
		},
		'password_form':{
			'required': $._BuildPrompt('password_form', '请输入密码!',{'width': 150}),
			'rangelength': $._BuildPrompt('password_form', '须为6~12码英文或数字且符合0~9或a~z字元!',{'width': 270}),
			'CheckUser':$._BuildPrompt('password_form', '只允许是数字和字母!',{'width': 150}),
			'equalToUsername':$._BuildPrompt('password_form', '账号与密码不能相同!',{'width': 150}),
		},
		'passwd_form':{
			'required': $._BuildPrompt('passwd_form', '请再次输入密码!',{'width': 150}),
			'equalTo':  $._BuildPrompt('passwd_form', '两次密码输入不一致!',{'width': 150})

		},
		'yzm_form':{
			'required': $._BuildPrompt('yzm_form', '请输入验证码!',{'width': 150}),
			'checkCode':$._BuildPrompt('yzm_form', '验证码不正确!',{'width': 150}),
		},
		'from_url_form':{
			'required': $._BuildPrompt('from_url_form', '请输入推广网址!',{'width': 150}),
			'CheckUrl': $._BuildPrompt('from_url_form', '网址格式不正确!',{'width': 150})
		},
		
		<?php  $_smarty_tpl->tpl_vars['con'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['con']->_loop = false;
 $_smarty_tpl->tpl_vars['cid'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['config']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['con']->key => $_smarty_tpl->tpl_vars['con']->value) {
$_smarty_tpl->tpl_vars['con']->_loop = true;
 $_smarty_tpl->tpl_vars['cid']->value = $_smarty_tpl->tpl_vars['con']->key;
?>
			<?php if ($_smarty_tpl->tpl_vars['con']->value['status']=='*') {?>
				<?php if ($_smarty_tpl->tpl_vars['con']->value['name']=='is_email') {?>
					"is_email":{
						'required':  $._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", "<?php echo $_smarty_tpl->tpl_vars['con']->value['cue'];?>
",{'width': 150}),
						'email':$._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", "请输入正确的邮箱！",{'width': 150}),
					},
				<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_zh_name') {?>
					"is_zh_name":{
						'required':  $._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", "<?php echo $_smarty_tpl->tpl_vars['con']->value['cue'];?>
",{'width': 150}),
						'ische':$._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", "请输入正确的中文昵称！",{'width': 150}),
					},
				<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_en_name') {?>
					"is_en_name":{
						'required':  $._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", "<?php echo $_smarty_tpl->tpl_vars['con']->value['cue'];?>
",{'width': 150}),
						'iscard':$._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", "请输入正确的英文昵称！",{'width': 150}),
					},
				<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_card') {?>
					"is_card":{
						'required':  $._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", "<?php echo $_smarty_tpl->tpl_vars['con']->value['cue'];?>
",{'width': 150}),
						'iscard':$._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", "请输入正确的身份证号！",{'width': 150}),
					},
				<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_qq') {?>
					"is_qq":{
						'required':  $._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", "<?php echo $_smarty_tpl->tpl_vars['con']->value['cue'];?>
",{'width': 150}),
						'isNum':$._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", "请输入正确的QQ号！",{'width': 150}),
					},
				<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_payname') {?>
					"is_payname":{
						'required':  $._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", "<?php echo $_smarty_tpl->tpl_vars['con']->value['cue'];?>
",{'width': 150}),
						'ische': $._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", "请输入正确的真实姓名！",{'width': 150}),
					},
				<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_phone') {?>
					"is_phone":{
						'required':  $._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", "<?php echo $_smarty_tpl->tpl_vars['con']->value['cue'];?>
",{'width': 150}),
						'isNum':$._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", "请输入正确的手机号！",{'width': 150}),
					},
				<?php }?>
			<?php } else { ?>
				<?php if ($_smarty_tpl->tpl_vars['con']->value['name']=='is_email') {?>
					"is_email":{
						'email': true,
					},
				<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_zh_name') {?>
					"is_zh_name":{
						'ische_':$._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", "请输入正确的中文昵称！",{'width': 150}),
					},
				<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_en_name') {?>
					"is_en_name":{
						'iscard_':$._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", "请输入正确的英文昵称！",{'width': 150}),
					},
				<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_card') {?>
					"is_card":{
						'iscard_':$._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", "请输入正确的身份证号！",{'width': 150}),
					},
				<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_qq') {?>
					"is_qq":{
						'isNum_':$._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", "请输入正确的QQ号！",{'width': 150}),
					},
				<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_payname') {?>
					"is_payname":{
						'ische_':$._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", "请输入正确的真实姓名！",{'width': 150}),
					},
				<?php } elseif ($_smarty_tpl->tpl_vars['con']->value['name']=='is_phone') {?>
					"is_phone":{
						'isNum_':$._BuildPrompt("<?php echo $_smarty_tpl->tpl_vars['con']->value['name'];?>
", "请输入正确的手机号！",{'width': 150}),
					},
				<?php }?>
			<?php }?>
		<?php } ?>

		'bank_account_form':{
			'required': $._BuildPrompt('bank_account_form', '请输入银行账号!',{'width': 150}),
			'isNum':$._BuildPrompt('bank_account_form', '请输入正确的银行账号!',{'width': 150}),
			'checkcard':$._BuildPrompt('bank_account_form', '该银行卡已经绑定到代理账号！!',{'width': 150}),
		},
		'bank_province_form':{
			'required': $._BuildPrompt('bank_province_form', '请选择银行省份!',{'width': 150}),
		},
		'bank_county_form':{
			'required': $._BuildPrompt('bank_county_form', '请选择银行县市!',{'width': 150}),
		},
		'safe_pass1':{
			'required': $._BuildPrompt('safe_pass1', '请选择取款密码!',{'width': 150}),
		},
		'safe_pass2':{
			'required': $._BuildPrompt('safe_pass2', '请选择取款密码!',{'width': 150}),
		},
		'safe_pass3':{
			'required': $._BuildPrompt('safe_pass3', '请选择取款密码!',{'width': 150}),
		},
		'safe_pass4':{
			'required': $._BuildPrompt('safe_pass4', '请选择取款密码!',{'width': 150}),
		},
		'agree_form':{
			'required': $._BuildPrompt('agree_form', '请勾选同意条款!',{'width': 150}),
		},
	}
 })
})
 new PCAS("bank_province_form","bank_county_form");
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


</style>


 <?php }} ?>

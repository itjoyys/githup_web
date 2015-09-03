<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-06-29 06:40:00
         compiled from "/home/wwwuser/public_html/t/website/templates/login_info.html" */ ?>
<?php /*%%SmartyHeaderCode:2912542935591208059f600-19557947%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '094be463301897adf51dc21689e70eb96156e8f4' => 
    array (
      0 => '/home/wwwuser/public_html/t/website/templates/login_info.html',
      1 => 1434727209,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2912542935591208059f600-19557947',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'username' => 0,
    'password' => 0,
    'data_notice' => 0,
    'copy_right' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_559120805fc446_73466471',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_559120805fc446_73466471')) {function content_559120805fc446_73466471($_smarty_tpl) {?><?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php echo '<script'; ?>
 language="javascript" src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/jquery-1.8.3.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 language="javascript">

function cancelMouse(){return false;}
//-->
<?php echo '</script'; ?>
>        
<title>PKBET娱乐城</title>
		<style type="text/css">
		html {
		    margin-left: 0px;
		    margin-top: 0px;
		    margin-right: 0px;
		    margin-bottom: 0px;

		}
		body{
		    width:100%;
		    margin:0;
		    font-size:12px;
			background-color:#060912; 
		}
		#header {
			width:760px;
			height:110px;
			background: url(Member/cash/images/rule_LOGO.jpg) center 0 no-repeat;
		}
		#main{
			width:760px;
			margin:0 auto;
			font-size:12px;
		}
		#content{
		     background-color: #FFFFFF;
		}
		.head{
			 height:31px;
			 line-height:31px;
			 color:#FFFFFF;
		}
		.title {
			color:#FFFFFF;
			font-size : 15px;
			font-weight: bold;
			font-family: Verdana, Arial, Sans-serif, taipei;
			text-decoration: none;
		}
		.style1 {
			color: #ffffff;
			font-size: 13px;
			font-weight: bold;
		}
		.style2 {color: #2C7E7E}
		.style3 {color: #666666}
		.style4 {color: #339900}
		.title-style1 {
			color: #000000;
			font-size: 12px;
			line-height: 22px;
			text-align:left;
			padding: 0px 10px 0px 10px;
		}
		.title-style2 {
			color: #333399;
			font-weight:bold;
			width:760px;
		}
		.title-style3 a:link, .title-style3 a:visited{
			color: #093774;
			border-bottom: 5px solid #093774;
			text-decoration: none;
			line-height: 1.5;
			display: block;
		}
		.title-style3 a:hover{
			color: #093774;
			border-bottom: 5px dotted #093774;
		}
		#footer{
			height:52px;
			line-height:52px;
			background-color:#303;
			color:#FFFFFF;
		}
		#footer font {
			color:#FFFFFF;
		}
		#rule_all , #rule_ipl , #rule_ball , #rule_other {
			background-color:#FFFFFF;
		}	
		/*--- 按钮样式 ---*/
		.btn_001{
		cursor: pointer;
		margin: 0 1px 0 0;
		width: 85px;
		height: 26px;
		border: none;
		padding-top: 2px;
		color: #FFF;
		font-weight: bold;
		background: #3D3D3D url(Member/cash/images/order_btn.gif) no-repeat 0 -80px;
		}
		</style>
    </head>

    <body>
       <div id="main">
       <div id="header"></div>
       <table width="760" border="0" align="center" cellpadding="0" cellspacing="0">
			<tbody><tr>
				<td><div align="center" class="head"><span class="title">协议与规则</span></div></td>
			</tr>
            <tr>
                <td colspan="2" valign="top" id="content">
                    <div>
                        <table width="100%">
                            <tbody><tr>
                                <td width="50%" align="right">
                        <form action="./index.php?a=logout" method="post" name="myForm">
                          <!--  <input type="hidden" name="uid" value="2693208f2137ce90ad32d">
						  -->
						  <input type="hidden" name="loginTO" value="2">
                            <input name="submit" type="submit" class="btn_001" style="" value="我不同意">
                        </form>
                        </td>
                        <td align="left">
                        <form action="./index.php?a=login_info_do" method="post" name="myForm">
                          <input type="hidden" name="loginTO" value="1">
                    <input type="hidden" name="username" value="<?php echo $_smarty_tpl->tpl_vars['username']->value;?>
" />
					<input type="hidden" name="password" value="<?php echo $_smarty_tpl->tpl_vars['password']->value;?>
" />
                         <!--   <input type="hidden" name="gtype" value="FIRST" />	 -->
                          <input name="submit" type="submit" class="btn_001" style="" value="我同意">
                        </form>
                        </td>
                        </tr>
                        </tbody></table>
                        <table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
	<tr>
		<td width="100" class="title-style3"><div align="center"><a href="javascript:" onclick="chang_rule(&#39;rule_all&#39;);">通则</a></div></td>
		<td width="100" class="title-style3"><div align="center" id="rule_ipl_link"><a href="javascript:" onclick="chang_rule(&#39;rule_ipl&#39;);">线上娱乐场</a></div></td>
		<td width="100" height="30" class="title-style3"><div align="center"><a href="javascript:" onclick="chang_rule(&#39;rule_ball&#39;);">运动投注</a></div></td>
		<td width="100" height="30" class="title-style3"><div align="center"><a href="javascript:" onclick="chang_rule(&#39;rule_other&#39;);">其他游戏</a></div></td>
	</tr>
</tbody></table>
<div id="rule_all" style="display: none;">
<table width="655" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody><tr>
    <td height="30" class="title-style2">
    <div align="center">
	    <p align="center">通则</p>
	    	</div>
    </td>
  </tr>
  <tr>
    <td>
		<ol>
			<li class="title-style1">为避免于本网站投注时之争议，请会员务必于进入网站前详阅本娱乐场所定之各项规则，客户一经“我同意”进入本网站进行投注时，即被视为已接受本娱乐场的所有协议与规则。</li>
			<li class="title-style1">会员有责任确保自己的帐户以及登入资料的保密性，以会员帐号及密码进行的任何网上投注，将被视为有效。敬请不定时做密码变更之动作。若帐号密码被盗用，进行的投注，本公司一概不负赔偿责任。</li>
			<li class="title-style1">本公司保留不定时更改本协定或游戏规则或保密条例，更改之条款将从更改发生后指定之日起生效，并保留一切有争议事项及最后的决策权。</li>
			<li class="title-style1">用户须达到居住地国家法律规定之合法年龄方可使用线上娱乐场或网站。</li>
			<li class="title-style1">网上投注如未能成功提交，投注将被视为无效。</li>
			<li class="title-style1">凡玩家于出牌途中且尚无结果前自动或强制断线时，并不影响比赛之结果。</li>
			<li class="title-style1">如遇发生不可抗拒之灾害，骇客入侵，网络问题造成数据丢失的情况，以本公司公告为最终方案。</li>
			<li class="title-style1">特此声明，本公司将会对所有的电子交易进行记录，如有任何争议，本公司将会以注单记录为准。</li>
			<li class="title-style1">本公司保留更改、修改现有条款或增加任何适当条款的权利，而条款改动后将会在会员端跑马灯上公布。</li>
			<li class="title-style1">无论在任何情况下，本公司具有最终的解释权。开始新局。</li>
			<li class="title-style1">若经本公司发现会员以不正当手法&lt;利用外挂程式&gt;进行投注或以任何非正常方式进行的个人、团体投注有损公司利益之投注情事发生，本公司保留权利取消该类注单以及注单产生之红利，并停用该会员帐号。</li>
			<li class="title-style1">无风险投注包括在百家乐同时投注的庄闲，单双，大小。龙虎斗中同时投注龙虎，龙双龙单，虎双虎单。在轮盘同时投注黑红，单双，大小等相反的下注，本公司保留权利取消所有优惠、该注单以及单注产生之红利，并停用该会员账号之权利，PKBET娱乐城不承担对此做出任何说明及解释之责任 。</li>
			<li class="title-style1">若本公司发现会员有重复申请帐号行为时，保留取消、收回会员所有优惠红利，以及优惠红利所产生的盈利之权利。每位玩家、每一住址、每一电子邮箱、每一电话号码、相同支付卡/信用卡号码，以及共享电脑环境 (例如:网吧、其他公共用电脑等)只能够拥有一个会员帐号，各项优惠只适用于每位客户在<u>本公司</u>唯一的帐户。</li>
		</ol>    
	</td>
  </tr>
  <tr>
    <td height="30"><div align="right"><a href="#" class="title-style3">TOP</a></div></td>
  </tr>
</tbody></table>
</div>
<div id="rule_ipl" style="display: none;">
<table width="655" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody><tr>
    <td height="30" class="title-style2"><div align="center">
      <p align="center">娱乐场协议与规则</p>
    </div></td>
  </tr>
  <tr>
    <td><ol>
      <li class="title-style1">当您下注后，请等待显示“您共下注XXXX元”，这个讯息在中间的讯息窗口可以看见。</li>
      <li class="title-style1">开牌后，若您已有下注，请确认您的输赢金额，这个讯息在中间的讯息窗口可以看见。</li>
      <li class="title-style1">您的“总下注金额”及“赢得金额”亦会每局显示于右上角的视窗中，请会员详加确认。</li>
      <li class="title-style1">当会员在游戏中途发生网路问题而断线，所有已被确定的投注仍然有效。会员再重新登入时，就可以查询游戏端内的“下注记录”查询发牌结果，会员的额度也会随着当局的输赢增加或减少。</li>
      <li class="title-style1">如果您在讯息窗口看到“开牌”的话，而您的游戏画面未显示投注金额，这代表该局您的投注不成功，这有可能下注的时间太迟或是因为网路问题而没有被系统接受。</li>
      <li class="title-style1">百家乐游戏在本网内设计为每手牌局前不销牌。</li>
      <li class="title-style1">当会员进入游戏，超过5局没下注会有提示；若您连续10局未下注的时候将会被游戏弹出至首页，请会员重新登入。若会员于下注开牌期间强行登出，帐号将被系统锁住三分钟，请会员稍后再重新登入。</li>
      <li class="title-style1">本网上游戏是在现场把牌通过扫描后将牌例显示在会员端荧幕上，故若牌没扫描到必将重新扫描一遍，若还是没有感应则把牌翻开由现场公务输入牌卡数码，牌例便会显示在会员端荧幕上。</li>
      <li class="title-style1"> 当荷官不小心同时从牌靴中抽出两张牌，而扫描到的不是按顺序正确的那张牌：
        <br>
        （a）若牌局已开牌，而结果不符，系统将根据现况决定牌的先后摆放顺序之开牌结果进行手动开牌，并换上新牌靴开始新局。
        <br>
        （b）若牌局未开牌，则由现场公务决定牌的先后摆放顺序，牌局会如常继续。</li>
      <li class="title-style1">在洗牌或将牌放入牌靴过程中，有牌不慎曝光，荷官会把牌叠起并重新洗牌，牌局将重新开始。</li>
      <li class="title-style1">牌局进行中，未扬牌前(该张牌未于视讯显示点数花色前)，牌不慎离开台面，牌丢落地上，或离开视讯范围，则该牌局予以注销作废，所有下注本金退回。</li>
      <li class="title-style1">若该牌已经过扫描且已扬牌后，该牌不慎离开台面，牌丢落地上，或离开视讯范围，因其不影响游戏之正确结果，牌由现场工务摆回后，该牌局正常行其结果仍视为有效。</li>
      <li class="title-style1">派错牌例（已不须补牌，荷官仍补牌）现场工务会把多补的那张牌放到牌靴底，牌局结果依视讯显示为准，牌局将如常进行；若该张多补的牌已亮开，公务将在做完以上同样程序后换上新牌靴，牌局会重新开始。</li>
      <li class="title-style1">荷官未依闲、庄发牌正确顺序将牌放错位置，由工务将牌依正确顺序摆放回位置后牌局将照常继续。</li>
      <li class="title-style1"> 开牌过程中，牌有感应但无显示，荷官已亮牌（如: 已派出数张牌，但第一张牌有感应但未显示，至第二、三张牌显示在错误的闲、庄位置上），现场工务会依牌的正确次序输入代码，牌局将如常继续。</li>
      <li class="title-style1">同一张牌，扫描了一次出现2张（闲、庄位置各一张）
        <br>
        （a）若牌局已开牌，而结果不符，系统将根据视讯荷官完成该局之正确结果进行手动开牌，牌局也将在牌路无误的情况下如常继续。 
        <br>
        （b）若牌局未开牌，荷官避开扫描如常开牌后，工务将输入牌之正确数码，并修正不符那张牌的 花色、点数，牌局会如常继续。</li>
      <li class="title-style1">电脑、扫描器出现异常、牌局中断、牌无法扫描又无法输入牌卡数码时，那一个牌局便会作废，所有程式将被关闭，并重开程式，牌局将重新开始。但若荷官发牌视讯已有结果，则以视讯开牌为主，会由系统完成开配。</li>
      <li class="title-style1">有关例行性维护、网路问题、视讯中断、牌局作废、注销情况等事宜，皆可于会员端左上角处公告栏上得知最新讯息。</li>
      <li class="title-style1">发牌视讯仅保留三日，若有异议请于游戏当日起三日内提出，三日后恕不受理。</li>
      <li class="title-style1">本娱乐场之视讯为真人直播，故该局游戏若因国际线路传输问题出现争议，将以视讯看到牌局结果决定输赢。</li>
       <!--
      <li class="title-style1"> 本游戏城“6”点的牌为单面，所以当会员看到只有单一个数字的牌即是“6”点（详阅“百家乐游戏规则”之6、9辨识方式）。</li>
       -->
      <li class="title-style1">本娱乐场所提供之牌路仅供参考，若因国际线路问题或其他因素造成牌路显示有误，所有游戏结果将以视讯开牌及游戏记录为主。</li>
      <li class="title-style1">本公司保留一切有争议事项的修正及最后的决策。</li>
      <li class="title-style1">本娱乐场保留随时更改、修订或删除游戏、游戏规则（包括机率及赔率）及协议条款的权利而无须作事先通知。</li>
      <li class="title-style1">本娱乐场保留随时修订、撤销或中止任何投注的权利而无须作事先通知，亦无须作任何解释。</li>
      <li class="title-style1">本娱乐场记录每一项于本网站伺服器内执行的交易及投注功能。若会员认为向本娱乐场提供的资料与本网站资料库中的资料记录之间出现了任何声称的差异，一切均以本网站资料库的资料为准。</li>
      <li class="title-style1">当会员已于本娱乐场之游戏厅内下注，而电脑出现连线异常导致牌局中断时，会员最后押住仍视为有效，本娱乐场将以会员于本网站资料库的交易记录为准。</li>
      <li class="title-style1"> 会员在本娱乐场之游戏厅内任何游戏的押注记录均视为有效，会员需自行承担下注后的风险。 </li>
      <li class="title-style1">若经本公司发现会员以不正当手法&lt;利用外挂程式&gt;进行投注或以任何非正常方式进行的个人、团体投注有损公司利益之投注情事发生，本公司保留取消该类注单之权利。</li>
      <!--
      <li class="title-style1">机器手臂每桌上有两台洗牌机，内各有一靴牌，当其中一靴发完牌后，会自动换下一靴。此部份与目前金臂百家乐的双开模式不同。</li>
      <li class="title-style1">当机器手臂机器无法正常运作时，且现场无法在10分钟内修复完成，将会换上另一机台使用，并使用新的牌靴开始新局。 </li>
      <li class="title-style1">若是机器手臂在吸牌而未扫描成功之前，因为吸力不足掉落，机器手臂将会自动停下，此时将会由工作人员将牌放置牌靴最上方并扬开，以供机器手臂再次吸牌（牌尚在视讯范围之内将会以此作法；若牌掉落在视讯范围之外并未扬开，此局将作废）。</li>
      <li class="title-style1">本娱乐场机器手臂将于每天固定时间08:00、16:00、24:00做例行性牌靴更换，并使用新的牌靴开始新局。</li>
      -->
    </ol></td>
  </tr>
  <tr>
    <td height="30"><div align="right"><a href="#" class="title-style3">TOP</a></div></td>
  </tr>
</tbody></table>
</div>
<div id="rule_ball" style="display: none;">
<table width="655" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody><tr>
    <td height="30" class="title-style2"><div align="center">
      <p align="center">运动投注协议与规则</p>
    </div></td>
  </tr>
  <tr>
    <td><ol>
      <li class="title-style1">当您在下注之后，请等待显示下注成功信息。</li>
      <li class="title-style1">为了避免出现争论，您必须在下注之后检查下注状况。</li>
      <li class="title-style1">任何[体育投注]的投诉必须在开赛之前提出，我们不会受理任何开赛之后的投诉。</li>
      <li class="title-style1">所有赛事投注项目(包括滚球)，出现任何打字错误或非人为故意失误之注单，一律取消。</li>
      <li class="title-style1">除滚球外，开赛后接受的投注，将被视为“无效”。</li>
      <li class="title-style1">倘若发生不可抗拒之灾害或骇客入侵破坏行为，造成网站故障或资料毁损等情况，以本公司 网站公告讯息为最后处理模式，请各会员投注后列印资料本公司才接受投诉！</li>
      <li class="title-style1"> 即日起任何异常或争议之注单，将不计算结果；并转移至当日历史帐户于结果栏位注明"已注销" 。</li>
      <li class="title-style1">下注时出现同时同分同秒及内容相同2笔以上之注单，本公司只承认一笔有效注单，其余一律注销。</li>
      <li class="title-style1">由于走地时系统资料处理繁忙，如发现刻意于“走地进球后之交易资料一律取消，不便之处，敬请见谅！</li>
      <li class="title-style1">走地进球后，如因网页系统或线路状况..等问题而导致比分或赔率无法更新，所有相关交易资料“一律取消”。</li>
      <li class="title-style1">滚球注单下注过程中若显示[等待中]，该注单尚未被本公司确认接受；客户可至下注状况与帐户历史确认，若该滚球注单确定未被本公司接受，可至帐户历史查看该注单结果会显示[未接受]并且画横线！！</li>
      <li class="title-style1">在赛事开赛前,比赛队伍之主场或中立场若有任何更改,本公司将依据盘口与行情差距而保留更改前的注单是否有效之权利,所有判决将于走马灯公告上公布!</li>
      <li class="title-style1">早餐取消之注单可于"下注状况"查询,当日以前(含当日)取消之注单将放于"帐户历史"并于结果栏位注明"取消",敬请各会员查询后重新交易!</li>
      <li class="title-style1">若经本公司发现会员以不正当手法&lt;利用外挂程式&gt;进行投注或以任何非正常方式进行的个人、团体投注有损公司利益之投注情事发生，本公司保留取消该类注单之权利，并于注单取消时，备注此注单为『非法下注』或『系统注销』(含完赛后之注单)。</li>
    </ol></td>
  </tr>
  <tr>
    <td height="30"><div align="right"><a href="#" class="title-style3">TOP</a></div></td>
  </tr>
</tbody></table>
</div>
<div id="rule_other" style="display: none;">
<table width="655" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody><tr>
    <td height="30" class="title-style2"><div align="center">
      <p align="center">其他游戏</p>
    </div></td>
  </tr>
  <tr>
    <td>
    	<ul>
    	     		<li class="title-style1">彩票类游戏</li>
	    	<ol>
				<li class="title-style1">当您在下注之后，请等待显示“下注成功”资讯。</li>
				<li class="title-style1">为了避免出现争论，您必须在下注之后检查“下注状况”。</li>
				<li class="title-style1">任何的投诉必须在开彩之前提出，本公司不会受理任何开彩之后的投诉。</li>
				<li class="title-style1">所有投注项目，公布赔率时出现的任何打字错误或非故意人为失误，本公司保留改正错误和按正确赔率结算投注的权力。</li>
				<li class="title-style1">开彩后的投注，将被视为“无效”。所有赔率将不时浮动，派彩时的赔率将以确认投注时之赔率为准。</li>
				<li class="title-style1">所有赛果以官方网站公布的结果为依据，若因官网延迟、错误或取消；本公司保留对已下注注单的裁决权。</li>
		    	        <li class="title-style1">若发生两颗球同时吸起，将依照在结果区之号码球为主。</li>
				<li class="title-style1">在游戏尚未完成开配之前，若因机器问题而导致有结果产生，该局将一律注销。</li>
				<li class="title-style1">在下注时间尚未结束之前，若因任何因素导致有结果产生，该局将一律注销。</li>
				<li class="title-style1">若该局己有结果但与实际结果不符，系统将根据视讯完成该局之正确结果进行手动开牌。</li>
	    	</ol>
	     	     	    	<li class="title-style1">电子游艺</li>
	    	<ol>
				<li class="title-style1">电子游戏中奖画面与派彩结果不符时，本公司将以资料库最终结果为依据。</li>
				<li class="title-style1">彩池金额是以满注中奖结果显示，玩家中奖系以押注比例分配彩池金额。</li>
				<li class="title-style1">老虎机游戏过程中如遇断线情况将以资料库最终结果为依据，本公司保留对已下注注单的裁决权。</li>
				<li class="title-style1">21点游戏补牌中如遇玩家断线，则视玩家为不补牌；游戏结果将视为有效。</li>
				<li class="title-style1">红狗游戏过程中如遇断线情况将视为玩家不加注进行补牌，完成该局游戏结果。</li>
	    	</ol>
	      	           	</ul>
    </td>
  </tr>
  <tr>
    <td height="30"><div align="right"><a href="#" class="title-style3">TOP</a></div></td>
  </tr>
</tbody></table>
</div>                    </div>
                </td>
            </tr>
        </tbody></table>
        <div id="mess"><?php echo $_smarty_tpl->tpl_vars['data_notice']->value;?>
</div>
        <div id="footer" align="center">Copyright © <?php echo $_smarty_tpl->tpl_vars['copy_right']->value;?>
 Reserved</div>
       </div>
      <?php echo '<script'; ?>
 type="text/JavaScript">
           <!--
           document.getElementById('rule_all').style.display = '';
           function chang_rule(rule) {
               document.getElementById('rule_all').style.display = 'none';
               document.getElementById('rule_ipl').style.display = 'none';
               document.getElementById('rule_ball').style.display = 'none';
               document.getElementById('rule_other').style.display = 'none';
               //rule.style.display = '';
               document.getElementById(rule).style.display = '';
           }          
           //-->
           window.onload=function(){
            
            var mess =  document.getElementById("mess").innerHTML;
            if(mess){
              alert(mess);
            }
            
           }
       <?php echo '</script'; ?>
>

</body></html><?php }} ?>

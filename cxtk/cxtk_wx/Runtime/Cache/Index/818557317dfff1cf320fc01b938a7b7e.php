<?php if (!defined('THINK_PATH')) exit();?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<title>会员卡</title>
	<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<link href="__PUBLICI__/Member/css/card.css" rel="stylesheet" type="text/css">
	<script src="__PUBLICI__/Member/js/accordian.pack.js" type="text/javascript"></script>
</head>

<body id="cardnews" onload="new Accordian(&#39;basic-accordian&#39;,5,&#39;header_highlight&#39;);" class="mode_webapp2">
	<?php
 $member_card=$_SESSION['member_card']; $image_url=C('image_url'); if (empty($member_card)) { $this->error('请重新访问'); }else{ $murl=$image_url.'/index.php/Index/Member/mid/'.$member_card['mid'].'?openid='.$member_card['openid']; } ?>
<div class="menu_header">
    <div class="menu_topbar">
        <strong class="head-title">会员卡首页</strong>       
        <span class="head_btn_left">
            <a href="javascript:history.go(-1);"><span>返回</span></a>
            <b></b></span>
            <a class="head_btn_right" href="<?php echo ($murl); ?>"><span><i 
class="menu_header_home"></i></span><b></b>       </a>
   </div>
</div>
	<div class="qiandaobanner" style="margin-top:41px;">
		<a>
			<img src="__PUBLICI__/Member/images/info.jpg"></a>
	</div>

	<div id="basic-accordian">
		<div id="test-header" class="accordion_headings header_highlight">
			<div class="tab cardinfo">
				<span class="title">会员卡使用说明</span>
			</div>
			<div id="test-content" style="display: block; opacity: 1;">
				<div class="accordion_child"> <b>详情说明</b>
                    <?php echo ($member['details']); ?>

				</div>
			</div>
		</div>
		<div id="test1-header" class="accordion_headings header_highlight">
			<div class="tab integral_info">
				<span class="title">会员积分说明</span>
			</div>
			<div id="test1-content" style="display: block; opacity: 1;">
				<div class="accordion_child"> <b>详情说明</b>
                    <?php echo ($member['detailj']); ?>

				</div>
			</div>
		</div>
		<div id="test2-header" class="accordion_headings">
			<div class="tab businesses_info">
				<span class="title">商家介绍</span>
			</div>
			<div id="test2-content" style="display: none;">
				<div class="accordion_child">
					<p class="xiangqing"></p>
					<div> 
						<?php echo ($member['detailsj']); ?>
                    </div>
					<p></p>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
WeixinJSBridge.call('hideToolbar');
});
</script>
	<?php
 $member_card=$_SESSION['member_card']; if(empty($member_card)){ $this->error('请重新访问'); }else{ $murl=$image_url.'/index.php/Index/Member/mid/'.$member_card['mid'].'?openid='.$member_card['openid']; } ?>
<div class="footermenu">
    <ul>
      <li>
            <a class="active" href="<?php echo ($murl); ?>">
            <img src="__PUBLICI__/Member/images/m_1.png">
            <p>会员卡</p>
            </a>
        </li>
        <li>
            <a href="<?php echo U(GROUP_NAME . '/Index/Member/info_member');?>">
            <img src="__PUBLICI__/Member/images/m_2.png">
            <p>特权</p>
            </a>
        </li>
        <li>
            <a href="">
            <img src="__PUBLICI__/Member/images/m_3.png">
            <p>优惠券</p>
            </a>
        </li>
        <li>
            <a href="<?php echo U(GROUP_NAME .'/Index/Member/mall_member');?>">
            <img src="__PUBLICI__/Member/images/m_4.png">
            <p>兑换</p>
            </a>
        </li>
        <li>
            <a href="<?php echo U(GROUP_NAME .'/Index/Member/sign_member');?>">
            <img src="__PUBLICI__/Member/images/m_5.png">
            <p>签到</p>
            </a>
        </li>
    </ul>
</div>

</body>
</html>
<?php if (!defined('THINK_PATH')) exit();?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script type="text/javascript" src="__PUBLICI__/Member/js/accordian_pack.js"></script>
    <title>礼品卡</title>
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" type="text/css" href="__PUBLICI__/Member/css/lipin.css" media="all">
    <link href="__PUBLICI__/Member/css/msp.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="__PUBLICI__/Member/css/card.css" media="all">
</head>

<body id="cardpower" ondragstart="return false;" onselectstart="return false;" class="mode_webapp">
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
<script type="text/javascript">
    document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
        //WeixinJSBridge.call('hideToolbar');
        //WeixinJSBridge.call('showOptionMenu');
    });
</script>
<section class="body">
    <div class="qiandaobanner">
        <a>
            <img src="__PUBLICI__/Member/images/gift.jpg">
        </a>
    </div>
    <div id="basic-accordian">
        <div id="test0-header" class="accordion_headings header_highlight">
            <div class="tab  vip">
                <span class="title">积分换礼品<p>您的当前积分为：
                    <font color="#FF3300" style="font-size:14px; font-family:Arial; font-weight:bold;"><?php echo ($jifen['total_integral']); ?></font>分
                    </p>
                </span>
            </div>
        </div>
    </div>
    <ul class="round" style="margin:0 2.5%;">
        <li class="title mb"><span class="none">积分换礼</span></li>
        <li class="nob">
            <div class="beizhu">积分换大礼！</div>
        </li>

        <li class="nob">
            <div class="clearfix a-msp-index" id="tbh5v0">
                <div id="bodyCont" class="fullscreen" style="position: relative;">

                    <section class="bd list">
                        <div class="block first">
                            <ul>
                              <?php if(is_array($member_mall)): foreach($member_mall as $key=>$v): ?><li style="-webkit-box-shadow: 2px 2px 10px #909090;margin-bottom:8px;">
                                    <a href="<?php echo ($v["url"]); ?>" title="">
                                        <div><img class="loading-img" src="<?php echo ($image_url); echo ($v["img"]); ?>"></div>
                                        <p class="tit"><?php echo ($v["title"]); ?></p>
                                        <p class="price">
                                            <?php echo ($v["jifen"]); ?>分
                                          </p>
                                    </a>
                                </li><?php endforeach; endif; ?>
                            </ul>
                        </div>



                    </section>

                </div>

            </div>
        </li>

    </ul>

    <!---->
    <script>
        window.onload = function(){
            new Accordian('basic-accordian',2,'header_highlight');
        }
    </script>
</section>
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
</body></html>
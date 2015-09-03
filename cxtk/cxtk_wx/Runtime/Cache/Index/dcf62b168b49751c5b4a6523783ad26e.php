<?php if (!defined('THINK_PATH')) exit();?>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>签到赚微币</title>
    <meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="__PUBLICI__/Member/css/card.css" rel="stylesheet" type="text/css">
    <script src="__PUBLICI__/Member/js/jquery.js" type="text/javascript"></script>
    <style type="text/css">
        .window {
            width:240px;
            position:absolute;
            display:none;
            margin:-50px auto 0 -120px;
            padding:2px;
            top:0;
            left:50%;
            border-radius:0.6em;
            -webkit-border-radius:0.6em;
            -moz-border-radius:0.6em;
            background-color: rgba(255, 0, 0, 0.5);
            -webkit-box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            -moz-box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            -o-box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            font:14px/1.5 Microsoft YaHei,Helvitica,Verdana,Arial,san-serif;
            z-index:10;
            bottom: auto;
        }
        .window .content {
            /*min-height:100px;*/
            overflow:auto;
            padding:10px;
            color: #222222;
            text-shadow: 0 1px 0 #FFFFFF;
            border-radius: 0 0 0.6em 0.6em;
            -webkit-border-radius: 0 0 0.6em 0.6em;
            -moz-border-radius: 0 0 0.6em 0.6em;
        }
        .window #txt {
            min-height:30px;font-size:20px; line-height:22px; color:#FFF; text-align:center;
        }
    </style>
</head>

<body id="cardintegral" class="mode_webapp">
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
<script>
    $(document).ready(function () {
        $("#qiandao").click(function () {
            var openid = $(this).attr('oid');
            var cardid = $(this).attr('cardid');
            $.ajax({
                type: "POST",
                url: "http://localhost:8077/weipan/index.php/Index/Member/signAjax",
                data: "openid="+openid+'&cardid='+cardid,
                dataType :"json",
                success: function(msg){
                    if (msg == true){
                        alert('签到成功');
                        setTimeout('dourl()',2000);
                        return
                    }else{
                        alert('你今天已经签到了');
                        setTimeout('dourl()',2000);
                        return
                    }
                }
            });
        });
    });

</script>


<script type="text/javascript">
    document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
        //WeixinJSBridge.call('hideToolbar');
        //WeixinJSBridge.call('showOptionMenu');
    });
</script>
<div class="qiandaobanner">  <a href="javascript:history.go(-1);">
    <img src="__PUBLICI__/Member/images/qiandao.jpg"></a> </div>

<div class="cardexplain">
    <a class="receive" id="qiandao" oid="<?php echo ($openid); ?>" cardid="<?php echo ($cardid); ?>">
        <?php if($state == 1): ?><span class="red">您今天已经签到过了</span>
          <?php elseif($state == 0): ?>
             <span class="red" style="display:block;">点击这里签到赚积分</span><?php endif; ?>

        </a>
    <div class="jifen-box" style="margin-top:13px;">
        <ul class="zongjifen">
            <li><div class="fengexian"><p>会员总积分</p><span><?php echo ($integral['total_integral']); ?></span></div></li>
            <li><a href="#">
                <div class="fengexian"><p>签到积分</p><span><?php echo ($integral['in_integral']); ?></span></div></a></li>
            <li><a href="#"><p>消费积分</p><span><?php echo ($integral['xiaofei_integral']); ?></span></a></li>
        </ul>
        <div class="clr"></div>
    </div>

    <div class="jifen-box header_highlight">
        <!--<div class="tab month_sel"> <span class="title">查看每月签到-->
<!--<p>点击这里选择其他月份</p>-->
<!--</span> </div>-->
        <!--<select onchange="dourl(this.value)" class="month">-->
            <!--<option value="1">1月</option>-->
            <!--<option value="2">2月</option>-->
            <!--<option value="3">3月</option>-->
            <!--<option value="4">4月</option>-->
            <!--<option value="5">5月</option>-->
            <!--<option value="6">6月</option>-->
            <!--<option value="7">7月</option>-->
            <!--<option value="8" selected="">8月</option>-->
            <!--<option value="9">9月</option>-->
            <!--<option value="10">10月</option>-->
            <!--<option value="11">11月</option>-->
            <!--<option value="12">12月</option>-->
        <!--</select>-->
        <div class="accordion_child">
            <table class="integral_table" border="0" cellpadding="0" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>日期</th>
                    <th>签到情况</th>
                    <th>积分</th>
                </tr>
                </thead>
                <tbody>
              <?php if(is_array($dateArray)): foreach($dateArray as $key=>$v): ?><tr>
                    <td><?php echo ($v["wdate"]); ?></td>
                    <td><span class="wqian"><?php echo ($v["sign"]); ?></span></td>
                    <td><?php echo ($v["jifen"]); ?></td>
                </tr><?php endforeach; endif; ?>


                </tbody>
                <tfoot>
                <tr>
                    <td></td>
                    <td class="right">本月合计：</td>
                    <td><span class="heji">+<?php echo ($countNum); ?></span></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="window" id="windowcenter">
        <div class="content">
            <div id="txt"></div>
        </div>

    </div>
</div>

<script>
    function dourl(m){
        location.href= 'http://localhost:8077/weipan/index.php/Index/Member/sign_member';
    }
</script>



<script type="text/javascript">

    function alert(title){

        $("#windowcenter").slideToggle("slow");
        $("#txt").html(title);
        setTimeout('$("#windowcenter").slideUp(500)',2000);
    }

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

</body></html>
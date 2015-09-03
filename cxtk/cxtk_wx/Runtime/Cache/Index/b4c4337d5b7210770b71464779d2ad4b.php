<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<title>个人中心</title>
<link href="__PUBLICI__/Shop/css/mall.css" rel="stylesheet" type="text/css">
<style type="text/css">
ul,li{list-style: none;}
.mode_webapp {
 padding: 0;
}
#J_myTaobao_plugin .mb-tms {
height: 90px;
}
#J_myTaobao_plugin .mb-user dt span {
		background: url(./images/hui_bg.png) no-repeat;
		background-size: contain;
		width: 68px;
		height: 26px;
		position: absolute;
		left: 5px;
		top: 4px;
		padding-top: 64px;
		font-size: 8px;
		color: #AF3030;
}
#J_myTaobao_plugin .mb-user dt img {
width: 56px;
height: 56px;
border-radius: 56px;
}
#J_myTaobao_plugin .mb-tms img {
max-height: 90px;
width: 100%;
}
#J_myTaobao_plugin .mb-tms div {
height: 100%;
}
.viewport>.content>.wrap .active {
background: #eee;
}
#J_myTaobao_plugin .mb-user {
height: 95px;
margin-top: -45px;
overflow: hidden;
}
#J_myTaobao_plugin .mb-user dt {
float: left;
padding: 10px 11px 0;
text-align: center;
position: relative;
}
#J_myTaobao_plugin .mb-user dt img {
width: 56px;
height: 56px;
border-radius: 56px;
}
#J_myTaobao_plugin .mb-user dd {
font-size: 16px;
text-shadow: 1px 1px 4px rgba(0,0,0,.6);
color: #fff;
padding-top: 16px;
}

#J_myTaobao_plugin .mb-os {
height: 60px;
margin-top: -50px;
background: -webkit-gradient(linear,center top,center bottom,from(#f9f9f9),to(#eee));
box-shadow: 0 1px 3px rgba(0,0,0,.1);
-webkit-box-shadow: 0 1px 3px rgba(0,0,0,.1);
padding-left: 78px;
overflow: hidden;
}
#J_myTaobao_plugin .mb-os ul {
display: -webkit-box;
}
#J_myTaobao_plugin .mb-os ul li {
-webkit-box-flex: 1;
text-align: center;
position: relative;
font-size: 10px;
}
#J_myTaobao_plugin .mb-os ul li a {
display: block;
height: 46px;
padding-top: 5px;
color: #666;
}
#J_myTaobao_plugin .mb-os ul li:after {
content: ' ';
position: absolute;
top: 22px;
right: -1px;
height: 14px;
border-right: 1px #ddd solid;
}
#J_myTaobao_plugin .mb-os ul li strong {
display: block;
color: #444;
font-size: 16px;
font-weight: 700;
height: 20px;
}

</style>
</head>
<body class="mode_webapp">
<div id="J_myTaobao_plugin">
	  <div class="mb-tms">
		  <div style="background-size:contain;text-align:right;">
			<img src="__PUBLICI__/Shop/images/person_bg.jpg">
		  </div>
	 </div> 
	<dl class="mb-user"> 
	  <dt> 
	     <img id="J_myLogo" src="http://wwc.taobaocdn.com/avatar/getAvatar.do?userId=1066665898&amp;width=160&amp;height=160&amp;type=sns">
	       <span id="J_userLevel">V2会员</span> </dt> 
	       <dd id="J_nick"> 
		       <span>会员名称</span> 
               <b id="s_icon" class="c-icon-c-1-2"></b> 
            </dd> 
      </dl> 
      <div class="mb-os"> 
        <ul> 
           <li id="J_toPay"> 
              <a class="fragment" data-fragment="!/awp/mtb/olist.htm" data-status="0">
                 <strong>0</strong>待付款</a> 
           </li> 
            <li id="J_hasPaid"> 
               <a class="fragment" data-fragment="!/awp/mtb/olist.htm" data-status="6"><strong>0</strong>待发货</a> </li> 
           <li id="J_toConfirm"> 
                <a class="fragment" data-fragment="!/awp/mtb/olist.htm" data-status="1"><strong>0</strong>待收货</a> </li> 
            <li id="J_toComment">  
                 <a class="fragment" data-fragment="!/awp/mtb/olist.htm" data-status="7"><strong>0</strong>待评价</a> </li> 
           </ul> 
        </div> 
          
</div>


<div class="vgy_user">
        <div class="user_menu">
    		<ul>
            	<li>
                	<a href="">
                    	<img src="__PUBLICI__/Shop/images/tb_icon_all_pay_48.png">
                        <p>全部订单</p>
                    </a>
            		<span class="num">0</span>
                </li>
            	<li>
                	<a href="">
                    	<img src="__PUBLICI__/Shop/images/tb_icon_all_deliver_48.png">
                        <p>查物流</p>
                    </a>
            		<span class="num">0</span>
                </li>
            	<li>
                	<a href="">
                    	<img src="__PUBLICI__/Shop/images/tb_icon_all_receive_48.png">
                        <p>退款中</p>
                    </a>
            		<span class="num">0</span>
                </li>
                <li>
                	<a href="">
                		<img src="__PUBLICI__/Shop/images/tb_icon_all_refund_48.png">
                    	<p>已结束</p>
                    </a>    
                    <span class="num">0</span>
            	</li>
        	</ul>
   		</div>

   		     <div class="user_menu">
    		<ul>
    		       	<li>
                	<a href="">
                    	<img src="__PUBLICI__/Shop/images/tb_icon_all_deliver_48.png">
                        <p>收货地址</p>
                    </a>
            		<span class="num">0</span>
                </li>
            	<li>
                	<a href="">
                    	<img src="__PUBLICI__/Shop/images/tb_icon_all_pay_48.png">
                        <p>购物车</p>
                    </a>
            		<span class="num">0</span>
                </li>
        
            	<li>
                	<a href="">
                    	<img src="__PUBLICI__/Shop/images/tb_icon_all_receive_48.png">
                        <p>会员卡</p>
                    </a>
            		<span class="num">0</span>
                </li>
                <li>
                	<a href="">
                		<img src="__PUBLICI__/Shop/images/tb_icon_all_refund_48.png">
                    	<p>优惠券</p>
                    </a>    
                    <span class="num">0</span>
            	</li>
        	</ul>
   		</div>
	</div>
</body>
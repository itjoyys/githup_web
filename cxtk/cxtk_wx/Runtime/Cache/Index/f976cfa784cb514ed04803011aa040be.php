<?php if (!defined('THINK_PATH')) exit();?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content=" initial-scale=1.0, minimum-scale = 1, maximum-scale = 1">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<title>列表页</title>
<link rel="stylesheet" href="__PUBLICI__/css/list.css">

</head>
<body>
<div class="main">
<div class="banner_list">
	<img src="<?php echo ($image_url); echo ($ads); ?>" width="100%">
</div>
 <div class="goods_list_box">
     <div class="goods_list">
       <ul>
       	<?php if(is_array($goods)): foreach($goods as $key=>$v): ?><li class="goods_list_li" > 
					<a test="" href="<?php echo U(GROUP_NAME . '/Detail/Index', array('goodsid' => $v['goodsid']));?>" > 
					   <img width="100%" src="<?php echo ($image_url); echo ($v["photo1"]); ?>">
						<div class="goods_title"><?php echo ($v["name"]); ?></div>
						<div class="goods_price">¥<?php echo ($v["saleprice"]); ?></div>
					</a> 
			</li><?php endforeach; endif; ?>
		
							
       </ul>
     	

     </div>
 	
 </div>
 <div class="clear"></div>
	
</div>

	<!--尾部样式-->
		<div id="foot">
		<div class="footer_login">
		
		</div>
		<div class="copyright">
			<p>Copyright © 2013-2014 <?php echo ($title); ?></p>
			<p>
				技术支持：
				<a href="">杭州微盘信息技术有限公司</a>
			</p>
		</div>
	</div>
  <?php  $siteid=$_SESSION['siteid']; $shopsite=M('site')->where(array('sid' => $siteid))-> select(); $copyright=$shopsite[0]['copyright']; $surl=$shopsite[0]['surl']; $support=$shopsite[0]['support']; ?>


    <section>
                <div class="copyright">
                    <p>Copyright © 2013-2014 <?php echo ($copyright); ?></p>
                    
                        <p>技术支持：

                        <a href="<?php echo ($surl); ?>"><?php echo ($support); ?></a></p>
                    
                </div>
    </section>
   <!--底部导航-->
          <div class="topbar" style="-webkit-transform:translate3d(0,0,0)">
        <nav>
           <ul id="topmenu" class="topmenu">
                <li>
                    <a href="<?php echo U(GROUP_NAME . '/Index', array('siteid' => $siteid));?>">
                        <img src="__PUBLICI__/images/index.png">
                        <label>商城首页</label>
                    </a>
                </li>
                <li>
                    <a href="">
                        <img src="__PUBLICI__/images/meau.png">
                        <label>商品分类</label>
                    </a>
                 
                </li>
                <li class="home">
                    <a></a>
                </li>
               
                <li>
                    <a href="">
                        <img src="__PUBLICI__/images/gouwuche.png">
                        <label>购物车</label>
                    </a>
                </li>
                 <li>
                    <a href="">
                        <img src="__PUBLICI__/images/daili.png">
                        <label>个人中心</label>
                    </a>
                 
                </li>
            </ul>
        </nav>
    </div>

</body>
<style type="text/css">
.footer-nav {
padding-top: 10px;
text-align: center;
}
.copyright {
font-size: 12px;
text-align: center;
}
.copy_box{height: 100px; position:fixed;
    bottom:60px;
    left:0px;
    z-index:900;
background-color: #f2f2f2;
box-shadow: inset 0 1px 1px rgba(0,0,0,.2);}
.copyright {
margin-bottom: 50px;
background-color: #e2e5e6;
height: 53px;
padding-top: 10px;
}
	/*底部菜单*/
.topbar { position: fixed; z-index: 900;bottom: 0; left: 0; right: 0;height: 49px; margin-top:80px; font-family: Helvetica, Tahoma, Arial, Microsoft YaHei, sans-serif; }
.topmenu { display:-webkit-box; border-top: 1px solid #0a3182; display: block; width: 100%; background: rgba(1, 80, 155, 0.7); height: 48px; display: -webkit-box; display: box; margin:0; padding:0; -webkit-box-orient: horizontal; background: -webkit-gradient(linear, 0 0, 0 100%, from(#175EA0), to(#2D76AF), color-stop(60%, #2D66BB)); box-shadow: 0 1px 0 0 rgba(255, 255, 255, 0.1) inset; }
.topbar .topmenu>li { -webkit-box-flex:1; position:relative; text-align:center; }
.top_menu li:first-child { background:none; }
.topbar .topmenu>li>a { height:48px; margin-right: 1px; display:block; text-align:center; color:#FFF; text-decoration:none; text-shadow: 0 1px rgba(0, 0, 0, 0.3); -webkit-box-flex:1; }
.topbar .topmenu>li.home { max-width:70px }
.topbar .topmenu>li.home a { height: 66px; width: 66px; margin: auto; border-radius: 60px; position: relative; top: -22px; left: 2px; background: url(__PUBLICI__/images/home.png) no-repeat center center; background-size: 100% 100%; }
.topbar .topmenu>li>a label { overflow:hidden; margin: 0 0 0 0; font-size: 12px; display: block !important; line-height: 18px; text-align: center; }
.topbar .topmenu>li>a img { padding: 3px 0 0 0; height: 24px; width: 24px; color: #fff; line-height: 48px; vertical-align:middle; }
.topbar li:first-child a { display: block; }

</style>
</html>
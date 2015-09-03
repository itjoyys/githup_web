<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
	<title><?php echo ($title); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
	<meta name="keywords" content="<?php echo ($Keywords); ?>">
	<meta name="description" content="<?php echo ($Description); ?>">
		<script src="__PUBLICI__/js/jquery.js" type="text/javascript"></script>
<script src="__PUBLICI__/js/omHDP.js" language="javascript"></script>
<script>
$(function(){
	$(".hdp_container").OM_HDP({delay:300,time:5000,title:1,type:1});
	/*引用时，.hdp_container容器的宽度和高度即为幻灯片图片的宽和高，参数【delay:动画效果持续时间,time:图片切换时间,title:显示文字介绍--1不显示--0，type;0为淡入淡出，1为左右滑动】*/
})</script>

<style type="text/css">
		body{font: 14px/1.5 "\5FAE\8F6F\96C5\9ED1",Helvetica;
color: #333;
-webkit-text-size-adjust: none;
background-color: #eff4f6;
-webkit-backface-visibility: hidden;}
a,img{border:0;}
		a{color: #333;
text-decoration: none;padding: 0;margin: 0;}
div{display: block;}
ul,li{padding: 0;margin: 0;list-style: none;}
section,h2{display: block;padding: 0;margin: 0;}
body,p{padding: 0;margin: 0;}
footer{display: block;}
.clear{clear: both;}
.container{padding: 0 6px;height: auto;margin-bottom: 20px;}
        .box {
 margin-top: 6px;
border-radius: 2px;
background: #fff;
border: 1px solid #e0e3e7;
}

.meau{
overflow: hidden;
width: 100%;
clear: both;
}
.meau_li{float: left;
width: 25%;
height: 40px;
border: 1px solid #e9ebee;
border-width: 0 1px 1px 0;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;}
.meau_li a{height: 22px;
padding: 10px 0 8px;
line-height: 22px;display: block;
text-align: center;}

.tit_th {
height: 16px;
padding: 8px 0 8px 8px;
border-width: 1px;
border-bottom: 2px solid #c4e3fc;
color: #478ede;
}
  .tit_th i { width: 16px; height: 16px; margin-right: 5px; }
  .tit_th h2 { font-size: 16px; line-height: 16px; float: left;
font-weight: 100;}
  .tit_th .icon_11 { width: 22px; height: 21px; margin: -3px 5px 0 0; }
  .tit_th .icon_1 { background-position: 0 -20px; }
  .tit_th .icon_2 { background-position: -20px -20px; }
  .tit_th .icon_3 { background-position: -40px -20px; }
  .tit_th .icon_4 { background-position: -60px -20px; }
  .tit_th .icon_5 { background-position: -80px -20px; }
  .tit_th .icon_6 { background-position: -100px -20px; }
  .tit_th .icon_7 { background-position: 0 -40px; }


/* 列表 */
.lists{ overflow:hidden; padding-bottom:1%;}
.lists h2{padding:5px 2%;color:#fff; background:#111; margin-bottom:10px;}
.lists h2 a{ float:right; color:#111; border-radius: 50px; background:#fff; padding:0 10px; font-weight:normal;}
.lists li{ float:left; padding:3%; box-shadow:#aaa 0 0 4px;border-radius:5px; width:40%; margin:0 2%; float:left; background:#fff;	}
.lists li img{width:100%;}
.lists p{ text-align:left; height:38px; overflow:hidden; padding:5px 0 0;}
.lists span{ display:block; text-align:left; color:#b20005; font-weight:bold; font-size:16px;}


/*plugmenu*/
/****************************************************************************/
.plug-div {
    position:fixed;
    bottom:0;
    left:0px;
    z-index:900;
    -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
}
.plug-menu{
    -webkit-appearance:button;
    display:inline-block;
    width:36px;
    height:36px;
    border-radius:36px;
    position: absolute;
    bottom:15px;
    left: 15px;
    z-index:999;
    -moz-box-shadow:0 0 0 4px #FFFFFF, 0 2px 5px 4px rgba(0, 0, 0, 0.25);
    -webkit-box-shadow:0 0 0 4px #FFFFFF, 0 2px 5px 4px rgba(0, 0, 0, 0.25);
    box-shadow:0 0 0 4px #FFFFFF, 0 2px 5px 4px rgba(0, 0, 0, 0.25);
    background-color: #B70000;
    /*-webkit-transition: -webkit-transform 200ms;
    -webkit-transform:rotate(1deg);*/
    color:#fff;
}
.plug-menu:before{
    font-size:20px;
    margin:9px 0 0 9px;
}
/*.plug-menu:checked{
	-webkit-transform:rotate(45deg);
}*/

.plug-phone>div {
    width:32px;
    height:32px;
    border-radius:32px;
    -moz-box-shadow:0 0 0 3px #FFFFFF, 0 2px 5px 3px rgba(0, 0, 0, 0.25);
    -webkit-box-shadow:0 0 0 3px #FFFFFF, 0 2px 5px 3px rgba(0, 0, 0, 0.25);
    box-shadow:0 0 0 3px #FFFFFF, 0 2px 5px 3px rgba(0, 0, 0, 0.25);
    background:#B70000;
    position:absolute;
    bottom:0;
    left:0;
    margin-bottom:20px;
    margin-left:20px;
    z-index:900;
    -webkit-transition: -webkit-transform 200ms;
}

.plug-wrap{
    position:absolute;
    height:100px;
    background:rgba(0,0,0,0.1);
    top:0;
    left:0;
    width:100%;
    height:100%;
    z-index:800;
}

.plug-phone>div a{
    color:#fff;
    font-size:20px;
}
.plug-phone>div label{
    display: none;
}
.plug-phone>div a:before{
    margin:5px 0 0 5px;
}

.plug-phone>div.on:nth-of-type(1) {-webkit-transform: translate(0, -100px) rotate(720deg);}

.plug-phone>div.on:nth-of-type(2) {-webkit-transform: translate(47px, -81px) rotate(720deg);}

.plug-phone>div.on:nth-of-type(3) {-webkit-transform: translate(81px, -45px) rotate(720deg);}

.plug-phone>div.on:nth-of-type(4) {-webkit-transform: translate(100px, 0) rotate(720deg);}

/*****样式2*****/

/*.plug-div.model1{*/
    /*position:static!important;*/
    /*height:45px;*/
/*}*/
.plug-div.model1>div{
    border-top:2px solid #51515d;
    position:fixed;
    z-index:900;
    bottom:0;
    left:-1px;
    right:0;
    margin:auto;
    max-width:640px;
    display:block;
    width:100%;
    background:rgba(255,255,255,0.7);
    height:48px;
    overflow:hidden;
    display:-webkit-box;
    display:box;
    -webkit-box-orient: horizontal;
    background:-webkit-gradient(linear, 0 0, 0 100%, from(#333c45), to(#313540), color-stop(50%, #373a43));
}
.plug-div.model1 input{display:none;}

.plug-div.model1 .plug-phone>div{
    width:auto!important;
    height:100%;
    position:static!important;
    margin:0;
    border-radius:0!important;
    -webkit-box-sizing:border-box;
    box-sizing:border-box;
    -webkit-box-flex: 1;
    box-flex:1;
    -webkit-box-sizing:border-box;
    overflow:hidden;
    box-shadow: none!important;
    border-right:1px solid #0e111a;
    border-left:1px solid #666a73;
    background:none;
}

.plug-div.model1 .plug-phone>div a:before {
    width:90%;
    text-align:center;
}
.plug-div.model1 .plug-phone>div label{
    margin:0 5px;
    font-size:12px;
    display:block!important;
    line-height:18px;
    text-align:center;
    overflow:hidden;
}

.plug-div.model1+.plug-wrap{
    display:block!important;
    height:50px;

}
.plug-div.model1 .plug-phone>div a{
    line-height:20px!important;
}
.doc-ft {
height: 100px;
background-color: #f2f2f2;
box-shadow: inset 0 1px 1px rgba(0,0,0,.2);
}
.footer-nav {
padding-top: 10px;
text-align: center;
}

/*首页幻灯片样式开始*/
#banner { width: 100%; margin: 0 auto; }
.hdp_container { width: 100%; max-height: 140px; margin: 0px auto; position: relative; font-size: 12px; overflow: hidden; }
.hdp_container img { width: 100%; height: 130px; float: left; display: block; border: none;}


#header { width: 100%; }
.top { width: 100%; height: 42px; margin: 0 auto; background-color: white; }
.top .logo { width: 150px; float: left; line-height: 35px; }
.top .logo img { width: 100%; height: 40px; }

</style>


</head>

<body>
	<!--头部开始-->
<!-- <div id="header">
		<div class="top" style="height:auto;">
				<img src="<?php echo ($image_url); echo ($logo); ?>" width="100%">
		</div>
	</div> -->
	<!--首页幻灯片-->
	<div id="banner" >
		
			<div class="hdp_container">
			   <?php if(is_array($adlist)): foreach($adlist as $key=>$v): ?><a title="<?php echo ($v["title"]); ?>" href="">
					<img src="<?php echo ($image_url); echo ($v["photo"]); ?>"></a><?php endforeach; endif; ?>
				
			</div>
		
	</div>


		<!--头部开始-->
		<div class="container">
			<section class="box">
				<div class="meau_nav">
					<ul class="meau">
					   <?php if(is_array($cate)): $i = 0; $__LIST__ = $cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class="meau_li">
							<a href="<?php echo U(GROUP_NAME . '/Shop/slist', array('cid' => $v['id']));?>" target="_blank"><?php echo ($v["name"]); ?></a>
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
						
					</ul>
				</div>
			</section>
                     <!--新品上架-->
            <div class="lists">
    <h2>
      新品推荐
    </h2>
    <ul>
      <?php if(is_array($newgoods)): $i = 0; $__LIST__ = $newgoods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><a href="<?php echo U(GROUP_NAME . '/Shop/detail', array('gid' => $v['gid']));?>">
        <li>
          <img src="<?php echo ($image_url); echo ($v["photo1"]); ?>">
          <p><?php echo ($v["name"]); ?></p>
          <span>¥<?php echo ($v["saleprice"]); ?></span>
        </li>
      </a><?php endforeach; endif; else: echo "" ;endif; ?>
      </ul>
      </div>
			
			 <!--特价抢购-->
			   <div class="lists">
    <h2>
      特价抢购
    </h2>
    <ul>
      <?php if(is_array($salegoods)): $i = 0; $__LIST__ = $salegoods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><a href="<?php echo U(GROUP_NAME . '/Detail/Index', array('goodsid' => $v['goodsid']));?>">
        <li>
          <img src="<?php echo ($image_url); echo ($v["photo1"]); ?>">
          <p><?php echo ($v["name"]); ?></p>
          <span>¥<?php echo ($v["saleprice"]); ?></span>
        </li>
      </a><?php endforeach; endif; else: echo "" ;endif; ?>
      </ul>
      </div>
			
			 <!--热销排行-->
			   <div class="lists">
    <h2>
      热销排行
    </h2>
    <ul>
      <?php if(is_array($hotgoods)): $i = 0; $__LIST__ = $hotgoods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><a href="<?php echo U(GROUP_NAME . '/Shop/detail', array('gid' => $v['id']));?>">
        <li>
          <img src="<?php echo ($image_url); echo ($v["photo1"]); ?>">
          <p><?php echo ($v["name"]); ?></p>
          <span>¥<?php echo ($v["saleprice"]); ?></span>
        </li>
      </a><?php endforeach; endif; else: echo "" ;endif; ?>
      </ul>
      </div>
			
			     <!--网页底部-->  
			       <div class="clear"></div> 

		</div>
	  <?php  $shop_config=$_SESSION['shop_config']; $shopsite=M('site')->where(array('sid' => $shop_config['shop_siteid']))-> find(); $image_url = C('image_url'); $home_url = $image_url.'/index.php/Index/Shop/siteid/'.$shop_config['shop_siteid'].'?openid='.$shop_config['shop_openid']; $all_url = $image_url.'/index.php/Index/Shop/siteid/all'; $cart_url = $image_url.'/index.php/Index/Shop/Cart_index'; $person_url = $image_url.'/index.php/Index/Shop/person'; $copyright=$shopsite['copyright']; $surl=$shopsite['surl']; $support=$shopsite['support']; ?>

    <section>
                <div class="copyright">
                    <p>Copyright ©2014 <?php echo ($copyright); ?></p>
                        <p>技术支持：
                        <a href="<?php echo ($surl); ?>"><?php echo ($support); ?></a></p>
                    
                </div>
    </section>
   <!--底部导航-->
          <div class="topbar" style="-webkit-transform:translate3d(0,0,0)">
        <nav>
           <ul id="topmenu" class="topmenu">
                <li>
                    <a href="<?php echo ($home_url); ?>">
                        <img src="__PUBLICI__/images/index.png">
                        <label>商城首页</label>
                    </a>
                </li>
                <li>
                    <a href="<?php echo ($all_url); ?>">
                        <img src="__PUBLICI__/images/meau.png">
                        <label>所有商品</label>
                    </a>
                 
                </li>
                <li class="home">
                    <a></a>
                </li>
            
                <li>
                    <a href="<?php echo ($cart_url); ?>">
                        <img src="__PUBLICI__/images/gouwuche.png">
                        <label>购物车</label>
                    </a>
                </li>
                 <li>
                    <a href="<?php echo ($person_url); ?>">
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
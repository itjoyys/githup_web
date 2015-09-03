<?php if (!defined('THINK_PATH')) exit();?>
<html>
<head>
	<title><?php echo ($title); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
	<meta name="keywords" content="<?php echo ($Keywords); ?>">
	<meta name="description" content="<?php echo ($Description); ?>">
	<link rel="stylesheet" type="text/css" href="__PUBLICI__/css/index.css">
	<script src="__PUBLICI__/js/jquery.js" type="text/javascript"></script>
	<script src="__PUBLICI__/js/omHDP.js" language="javascript"></script>
	<script src="__PUBLICI__/js/zepto.min.js"></script>
	<script src="__PUBLICI__/js/index.js"></script>
	<script type="text/javascript" src="__PUBLICI__/js/common.js"></script>
	<script type="text/javascript" src="__PUBLICI__/js/spin.min.js"></script>
	<script>
$(function(){
	$(".hdp_container").OM_HDP({delay:300,time:5000,title:1,type:1});
	/*引用时，.hdp_container容器的宽度和高度即为幻灯片图片的宽和高，参数【delay:动画效果持续时间,time:图片切换时间,title:显示文字介绍--1不显示--0，type;0为淡入淡出，1为左右滑动】*/
})</script>

</head>

<body  style="width: 320px; margin: 0px auto;background-color:#edeef0;">
	<!--头部开始-->

	<div id="head">
		<div class="top" style="height:auto;">
			
				<img src="__PUBLICI__/images/logo.gif" width="100%">
		</div>
		<!-- <div class="search">
			<div class="new-header">
				<form action="" id="searchForm">
					<div class="new-srch-box new-srch-box-v2">
						<input name="keyword" type="text" id="newkeyword" class="new-srch-input" required value="玻璃杯" style="color:#999999;">
						<a href="javascript:void(0);" target="_self" onclick="cancelHotWord()" class="new-s-close"></a>
						<div class="new-srch-lst" id="shelper" style="display:none"></div>
					</div>
					<a href="" class="new-a-search">搜&nbsp;索</a>
				</form>
			</div>
		</div> -->
	</div>
	<!--首页幻灯片-->
	<div id="banner" >
		<div style="height:160px; width:310px;">
			<div class="hdp_container">
			   <?php if(is_array($adlist)): foreach($adlist as $key=>$v): ?><a title="<?php echo ($v["title"]); ?>" href="">
					<img src="<?php echo ($image_url); echo ($v["photo"]); ?>"></a><?php endforeach; endif; ?>
				
			</div>
		</div>
	</div>
	<!--主体内容-->
	<div id="main">
		<!--首页快捷栏目-->
	<!-- 	<div class="meau_box">
			<div class="meau_one">
				<a href="">
					<span class="new_icon1">
						<span></span>
						<br>商品分类</span>
				</a>
				<a href="">
					<span class="new_icon2">
						<span></span>
						<br>订单查询</span>
				</a>
				<a href="">
					<span class="new_icon3">
						<span></span>
						<br>我的关注</span>
				</a>
				<a href="">
					<span class="new_icon4">
						<span></span>
						<br>浏览记录</span>
				</a>
			</div>
		</div> -->
		<div class="clear"></div>
		<!--促销热卖专区开始-->
		<div class="content_box">

			<div class="content_title">促销专区</div>
			<div class="new-mian-cont new-mg-t10">
				<div class="new-msale-lst new-p-re" id="container" ontouchstart="tStart(event)" ontouchmove="tMove(event);" ontouchend="tEnd(event);">
					<ul class="new-tbl-type" style="position:absolute;margin-left:0px" id="slider">
					
					<?php if(is_array($hotgoods)): foreach($hotgoods as $key=>$v): ?><li class="new-tbl-cell" id="1">
							<a href="<?php echo U(GROUP_NAME . '/Detail/Index', array('goodsid' => $v['goodsid']));?>">
								<p>
									<img src="<?php echo ($image_url); echo ($v["photo1"]); ?>" width="70" height="70"></p>
								<span>¥<?php echo ($v["saleprice"]); ?>元</span>
							</a>
						</li><?php endforeach; endif; ?>
												
					</ul>
				</div>
			</div>
		</div>
		<div class="clear"></div>
		<!--细分类专区-->
		<div class="nav_box">
			<ul class="nav_box_type">
		
	
				<li class="nav_box_cell">					
					<div class="nav_a" >
					
						
						 <?php if(is_array($cate)): $i = 0; $__LIST__ = $cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 4 );++$i;?><a href="<?php echo U(GROUP_NAME . '/List/Index', array('id' => $v['id']));?>" class="nav1">
							   <span><?php echo ($v["name"]); ?></span>

						    </a>	
						    <?php if(($mod) == "3"): ?><div class="nav_banner">
									<a href="" class="nav_banner">
									  <img src="__PUBLICI__/images/banner01.jpg"></a>
							  </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
 
					</div> 
				</li>
						
			
			</ul>

		</div>
		<div class="clear"></div>
		<!--精品专区-->
		<div class="topicsTab" id="tab1">
			<h2 style="height:32px;">
				<a id="indexnewlink" class="" onclick="setTabs(&#39;indexnew&#39;);">最新上线</a>
				<a id="indexcommendlink" class="on" onclick="setTabs(&#39;indexcommend&#39;);">特价推荐</a>
				<a id="indexhotlink" class="" onclick="setTabs(&#39;indexhot&#39;);">热销排行</a>
			</h2>
		</div>
		<div style="width:310px; float:left;margin: 0 auto; " id="tabtopics">
			<div class="topicss">
				<ul class="my_maplist" id="indexnew" style="display: none;">
			 <?php if(is_array($newgoods)): $i = 0; $__LIST__ = $newgoods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class="zc" >
						<div class="zz">
							<a href="<?php echo U(GROUP_NAME . '/Detail/Index', array('goodsid' => $v['goodsid']));?>">
								<img src="<?php echo ($image_url); echo ($v["photo1"]); ?>" class="lazy" width="90" height="90">
								<span><?php echo ($v["name"]); ?></span>
								<br> <b class="p">￥<?php echo ($v["saleprice"]); ?>元</b>
							</a>
						</div>
					</li><?php endforeach; endif; else: echo "" ;endif; ?>

				</ul>
				<ul class="my_maplist" style="display: block;" id="indexcommend">
                 <?php if(is_array($salegoods)): $i = 0; $__LIST__ = $salegoods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class="zc" >
						<div class="zz">
							<a href="<?php echo U(GROUP_NAME . '/Detail/Index', array('goodsid' => $v['goodsid']));?>">
								<img  src="<?php echo ($image_url); echo ($v["photo1"]); ?>" class="lazy" width="90" height="90">

								<span><?php echo ($v["name"]); ?></span>
								<br>
								<b class="p">￥<?php echo ($v["saleprice"]); ?>元</b>
							</a>
						</div>
					</li><?php endforeach; endif; else: echo "" ;endif; ?>

				</ul>
				<ul class="my_maplist" style="display: none;" id="indexhot">
			      <?php if(is_array($hotgoods)): $i = 0; $__LIST__ = $hotgoods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class="zc" >
						<div class="zz">
							<a href="<?php echo U(GROUP_NAME . '/Detail/Index', array('goodsid' => $v['goodsid']));?>">
								<img  src="<?php echo ($image_url); echo ($v["photo1"]); ?>" class="lazy" width="90" height="90">

								<span><?php echo ($v["name"]); ?></span>
								<br>
								<b class="p">￥<?php echo ($v["saleprice"]); ?>元</b>
							</a>
						</div>
					</li><?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
			</div>
		</div>
		</div>

		<div class="clear"></div>
		<!--底部浮动菜单-->
		<div class="dhf">
			<ul>
				<li>
					<a href="" class="con" style="line-height:1;">
						<span class="shouye"></span>
						<span >首页</span>
					</a>
				</li>
				<li>
					<a href="fenlei.html" class="con" style="line-height:1;">
						<span class="fenlei"></span>
						<span>类别</span>
					</a>
				</li>
				<li>
					<a href="" class="con" style="line-height:1;">
						<span class="gouwu"></span>
						<span>购物车</span>
					</a>
				</li>
				<li>
					<a href="" class="con" style="line-height:1;">
						<span class="huiyuan"></span>
						<span>会员中心</span>
					</a>
				</li>
			</ul>
		</div>
	</div>
	<!--尾部样式-->
	<div id="foot">
		<div class="footer_login">
		
		</div>
		<div class="copyright">
			<p>Copyright © 2012-2013  奔达版权所有$copyright</p>
			<p>
				技术支持：
				<a href="">杭州微盘信息技术有限公司</a>
			</p>
		</div>
	</div>
	<script>
    function setTabs(xiid){
        var xibie=new Array('indexnew','indexcommend','indexhot');
        var a=null;
        for(var i=0;i<3;i++){
            if(xiid==xibie[i]){
                document.getElementById(xibie[i]).style.display = "block";
                document.getElementById(xibie[i]+'link').className = 'on';
                //$("#"+xibie[i]).find("img[lz_src]").attr("src",function(){var s = $(this).attr("lz_src"); $(this).removeAttr("lz_src"); return s;});
            }else{
                document.getElementById(xibie[i]).style.display = "none";
                document.getElementById(xibie[i]+'link').className = '';
            }
        }
    }
        var slider = new Swipe(document.getElementById('indexfocus'), {
            callback: function (e, pos) {
                var i = bullets.length;
                while (i--) {
                    bullets[i].className = ' ';
                }
                bullets[pos].className = 'current';
            },
            auto: 3000,
            speed: 400
        });
        bullets = document.getElementById('index-focus-num').getElementsByTagName('li');
    </script></body>

</html>
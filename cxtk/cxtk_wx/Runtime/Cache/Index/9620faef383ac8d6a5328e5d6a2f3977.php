<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="/weipan/APP/Modules/Index/Tpl/Public/Website/css/common.css" media="all" />
<link rel="stylesheet" type="text/css" href="/weipan/APP/Modules/Index/Tpl/Public/Website/css/reset.css" media="all" />
<link rel="stylesheet" type="text/css" href="/weipan/APP/Modules/Index/Tpl/Public/Website/css/home-20.css" media="all" />
<script type="text/javascript" src="/weipan/APP/Modules/Index/Tpl/Public/Website/js/jquery.js"></script>
<script type="text/javascript" src="/weipan/APP/Modules/Index/Tpl/Public/Website/js/swipe.js"></script>
<script type="text/javascript" src="/weipan/APP/Modules/Index/Tpl/Public/Website/js/zepto.js"></script>
<title>萧山牙科医院</title>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
		<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
        <meta name="Keywords" content="" />
        <meta name="Description" content="" />
        <!-- Mobile Devices Support @begin -->
            <meta content="application/xhtml+xml;charset=UTF-8" http-equiv="Content-Type">
            <meta content="no-cache,must-revalidate" http-equiv="Cache-Control">
            <meta content="no-cache" http-equiv="pragma">
            <meta content="0" http-equiv="expires">
            <meta content="telephone=no, address=no" name="format-detection">
            <meta content="width=device-width, initial-scale=1.0" name="viewport">
            <meta name="apple-mobile-web-app-capable" content="yes" /> 
            <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
        <!-- Mobile Devices Support @end -->        
        <style>
            img{width:100%!important;}
        </style>
    </head>
    <body onselectstart="return true;" ondragstart="return false;">
        <link rel="stylesheet" type="text/css" href="/weipan/APP/Modules/Index/Tpl/Public/Website/css/font-awesome.css" media="all" />
<script>
	window.addEventListener("DOMContentLoaded", function(){
		btn = document.getElementById("plug-btn");if(!btn) return;
		btn.onclick = function(){
			var divs = document.getElementById("plug-phone").querySelectorAll("div");
			var className = className=this.checked?"on":"";
			for(i = 0;i<divs.length; i++){
				divs[i].className = className;
			}
			document.getElementById("plug-wrap").style.display = "on" == className? "block":"none";
		}
	}, false);
</script>

<div class="body">
	<section>
		<div id="banner_box" class="box_swipe">
			<ul>
		
		         				</ul>
				<ol>
					<li class="on"></li>
					<li ></li>
				</ol>
			</div>
	</section>
	<section>
		<ul class="list_ul">
	                    
                   <li>
			<a  href="/weipan/index.php/Index/Website/websitelist/pid/12.html">
				<figure>
				<div>
					<img src="http://localhost:8077/weipan/Uploads/website/201407/53cdc8231d815.png" />
				</div>
				<figcaption>
					师资力量<p>关于我们</p>
				</figcaption>
				</figure>
			</a>
		</li>                    
                   <li>
			<a  href="/weipan/index.php/Index/Website/websitelist/pid/13.html">
				<figure>
				<div>
					<img src="http://localhost:8077/weipan/Uploads/website/201407/53cdcd432e5cb.jpg" />
				</div>
				<figcaption>
					<p></p>
				</figcaption>
				</figure>
			</a>
		</li>		
		</ul>
	</section>
</div>


<script>
	$(function(){
		new Swipe(document.getElementById('banner_box'), {
			speed:500,
			auto:3000,
			callback: function(){
				var lis = $(this.element).next("ol").children();
				lis.removeClass("on").eq(this.index).addClass("on");
			}
		});
	});
</script>
    

    <section>
                <div class="copyright">
                    <p>Copyright © 2013-2014 杭州发鱼服饰有限公司</p>
                    
                        <p>技术支持：

                        <a href="http://weipan.wx0571.com/">杭州微盘</a></p>
                    
                </div>
    </section>

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
    left:0px;
    z-index:900;
background-color: #f2f2f2;
box-shadow: inset 0 1px 1px rgba(0,0,0,.2);}
.copyright {
background-color: #e2e5e6;
height: 53px;
padding-top: 10px;
}
</style>
</html>
</body>
</html>
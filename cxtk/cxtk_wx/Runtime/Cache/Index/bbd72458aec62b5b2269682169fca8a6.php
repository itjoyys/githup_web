<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="__PUBLICI__/Website/css/common.css" media="all" />
<link rel="stylesheet" type="text/css" href="__PUBLICI__/Website/css/reset.css" media="all" />
<link rel="stylesheet" type="text/css" href="__PUBLICI__/Website/css/home-20.css" media="all" />
<script type="text/javascript" src="__PUBLICI__/Website/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLICI__/Website/js/swipe.js"></script>
<script type="text/javascript" src="__PUBLICI__/Website/js/zepto.js"></script>
<title><?php echo ($title); ?></title>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
		<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
        <meta name="Keywords" content="<?php echo ($Keywords); ?>" />
        <meta name="Description" content="<?php echo ($Description); ?>" />
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
        <link rel="stylesheet" type="text/css" href="__PUBLICI__/Website/css/font-awesome.css" media="all" />
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
		
		         <?php if(is_array($slide)): $i = 0; $__LIST__ = $slide;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li><a><img src="<?php echo ($image_url); echo ($v["img"]); ?>" alt="<?php echo ($v["title"]); ?>" style="width:100%;" /></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
				<ol>
					<li class="on"></li>
					<li ></li>
				</ol>
			</div>
	</section>
	<section>
		<ul class="list_ul">
	<?php if(is_array($column)): $i = 0; $__LIST__ = $column;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; if($v["classid"] == 0): ?><li>
			<a  href="<?php echo U(GROUP_NAME . '/Website/websitedetail', array('pid' => $v['id']));?>">
				<figure>
				<div>
					<img src="<?php echo ($image_url); echo ($v["img"]); ?>" />
				</div>
				<figcaption>
					<?php echo ($v["title"]); ?><p><?php echo ($v["introduce"]); ?></p>
				</figcaption>
				</figure>
			</a>
		  </li>
                  <?php else: ?>
                    
                   <li>
			<a  href="<?php echo U(GROUP_NAME . '/Website/websitelist', array('pid' => $v['id']));?>">
				<figure>
				<div>
					<img src="<?php echo ($image_url); echo ($v["img"]); ?>" />
				</div>
				<figcaption>
					<?php echo ($v["title"]); ?><p><?php echo ($v["introduce"]); ?></p>
				</figcaption>
				</figure>
			</a>
		</li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
		
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
    <?php  $siteid=$_SESSION['siteid']; $site=M('site')->where(array('sid' => $siteid))-> select(); $copyright=$site[0]['copyright']; $surl=$site[0]['surl']; $support=$site[0]['support']; ?>


    <section>
                <div class="copyright">
                    <p>Copyright © 2013-2014 <?php echo ($copyright); ?></p>
                    
                        <p>技术支持：

                        <a href="<?php echo ($surl); ?>"><?php echo ($support); ?></a></p>
                    
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
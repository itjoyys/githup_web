<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
  <title><?php echo ($title); ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
  <meta name="keywords" content="<?php echo ($Keywords); ?>">
  <meta name="description" content="<?php echo ($Description); ?>">
    <link rel="stylesheet" type="text/css" href="__PUBLICI__/website/css/w-ui-1-1.css" media="all">
    <link rel="stylesheet" type="text/css" href="__PUBLICI__/website/css/common.css" media="all">
    <link rel="stylesheet" type="text/css" href="__PUBLICI__/website/css/list-1.css" media="all">
    <link rel="stylesheet" type="text/css" href="__PUBLICI__/website/css/font-awesome.css" media="all">

    <script type="text/javascript" src="__PUBLICI__/website/js/zepto.js"></script>
    <!-- Mobile Devices Support @begin -->

    <meta content="no-cache,must-revalidate" http-equiv="Cache-Control">
    <meta content="no-cache" http-equiv="pragma">
    <meta content="0" http-equiv="expires">
    <meta content="telephone=no, address=no" name="format-detection">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <!-- Mobile Devices Support @end -->
    <style>
       .main{
margin: 0 auto;
height: auto;
background-color: #E8ECEC;
-moz-border-radius: 5px;
-webkit-border-radius: 5px;
border-radius: 5px;
padding: 2%;

    }
    .content {
font-size: 14px;
border-radius: 3px;
display: block;
line-height: 20px;
border-bottom: 1px solid #C3C3C3;
border-top: 1px solid #ffffff;
font-weight: bold;
text-shadow: 0 1px 0 #FFFFFF;
text-decoration: none;
background: #F1EFF0;
-moz-user-select: none;
-webkit-user-select: none;
-ms-user-select: none;
position: relative;
padding: 5px;
margin: 0 auto;
overflow: hidden;
}

 .content p img{
  width: 100%;
 }
 img{display: block;padding: 0;margin: 0;}
</style>
</head>

<body >
    <?php  $siteid=$_SESSION['siteid']; $openid=$_SESSION['openid']; $image_url=C("image_url"); $indexurl=$image_url.'/index.php/Index/Website/siteid'.'/'.$siteid.'?openid='.$openid; $c_meau= M('websitecolumn') ->where(array('siteid' => $siteid))-> order('sort ASC')-> select(); $config= M('websiteconfig') ->where(array('siteid' => $siteid))-> select(); $cnum=count($c_meau); for ($i=0; $i < $cnum; $i++) { if ($c_meau[$i]['url']=='' && $c_meau[$i]['classid']=='1' ) { $c_meau[$i]['url']=$image_url.'/index.php/Index/Website/websitelist/pid'.'/'.$c_meau[$i]['id']; }elseif($c_meau[$i]['url']=='' && $c_meau[$i]['classid']=='0'){ $c_meau[$i]['url']=$image_url.'/index.php/Index/Website/websitedetail/pid'.'/'.$c_meau[$i]['id']; } } $this->c_meau=$c_meau; $this->indexurl=$indexurl; $this->tel=$config[0]['tel']; ?>
 

 <div class="top_bar">
      <nav>
        <ul class="top_menu">
          <li onclick="window.history.go(-1);">
            <span class="icon-chevron-sign-left"></span>
          </li>
          <li>
            <a href="<?php echo ($indexurl); ?>">
            <span class="icon-home"></span>
          </a>
          </li>
          <li>
            <a href="tel:<?php echo ($tel); ?>">
              <span class="icon-phone"></span>
            </a>
          </li>
          <li onclick="$(&#39;#menu_font&#39;).toggleClass(&#39;hidden&#39;);">
            <span class="icon-list-ul"></span>
            <ul id="menu_font" class="menu_font hidden" onclick="$(&#39;#menu_font&#39;).toggleClass(&#39;hidden&#39;);">
           <?php if(is_array($c_meau)): $i = 0; $__LIST__ = $c_meau;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li>
                <a href="<?php echo ($v["url"]); ?>" class="icon-smile"><?php echo ($v["title"]); ?></a>
              </li><?php endforeach; endif; else: echo "" ;endif; ?>
              
            </ul>
          </li>
        </ul>
      </nav>
    </div>

	<div class="main">
  
      <div class="content">
                        
        <?php echo ($cate['content']); ?>
         
     </div>

      
    </div>


	
    <section>
                <div class="copyright">
                    <p>Copyright ©2015</p>
                        <p>技术支持：

                        <a href="#>">丽泽科技</a></p>
                    
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
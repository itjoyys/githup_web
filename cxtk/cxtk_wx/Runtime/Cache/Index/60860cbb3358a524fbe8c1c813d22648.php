<?php if (!defined('THINK_PATH')) exit();?>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>门店导航</title>
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<link href="__PUBLICI__/Member/css/card.css" rel="stylesheet" type="text/css">
</head>

<body id="cardaddr" class="mode_webapp">
  <div class="menu_header"> 
     <div class="menu_topbar">
      <strong class="head-title">门店导航</strong>
      <span class="head_btn_left"><b></b></span> 
     </div>
</div>
<div class="cardexplain">
       
<!--<h2>杭州市</h2>-->
<!-- <span style="height:30px; padding-bottom:10px;">杭州</span> -->
<?php if(is_array($store)): foreach($store as $key=>$v): ?><br>
  <ul class="round">
        <li class="shop">
         <a>
            <span style="background:url()"><?php echo ($v["name"]); ?></span></a></li>
        <li class="addr">
            <a href="<?php echo ($v["url"]); ?>"><span>地址: <?php echo ($v["address"]); ?></span></a></li>
        <li class="tel">
            <a href="tel:<?php echo ($v["tel"]); ?>"><span>电话: <?php echo ($v["tel"]); ?></span></a></li>
  </ul><?php endforeach; endif; ?>
</div>
</body></html>
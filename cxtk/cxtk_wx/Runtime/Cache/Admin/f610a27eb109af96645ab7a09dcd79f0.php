<?php if (!defined('THINK_PATH')) exit();?>﻿<?php
 $siteid=$_SESSION['siteid']; $this->modules=$modules=M('site')->field('module')->where(array('sid' => $siteid))->select(); $modules=$modules[0]['module']; $modules= explode(' ', $modules); $num=COUNT($modules); for ($i=0; $i <$num ; $i++) { $this->url=$url=M('modules')->field('url')->where(array('mid' => $modules[$i]))->select(); $url=$url[0]['url']; $reurl=$reurl.$url; } $reurl=stripslashes($reurl); $this->reurl=$reurl; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<head>
<style type="text/css"> 

*{margin:0;padding:0;border:0;} 
body { 
    font-family: arial, 宋体, serif; 
    font-size:12px; 
} 
a{color: #fff;}
#nav { 
    width:148px; 
    line-height: 24px;  
    list-style-type: none; 
    text-align:left; 
    /*定义整个ul菜单的行高和背景色*/ 
} 

/*==================一级目录===================*/ 
#nav a { 
    width: 108px;  
    display: block; 
    padding-left:20px; 
    /*Width(一定要)，否则下面的Li会变形*/ 
} 

#nav li { 
    background-image: url(../Public/images/bg_list.gif);
    border-bottom:#dcdddd 1px solid; /*下面的一条白边*/ 
    float:left; 
    width: 140px;
    /*float：left,本不应该设置，但由于在Firefox不能正常显示 
    继承Nav的width,限制宽度，li自动向下延伸*/ 
} 



#nav a:link  { 
    color:#FFF; text-decoration:none; 
} 

/*==================二级目录===================*/ 
#nav li ul { 
    list-style:none; 
    text-align:left; 
} 
#nav li ul li{ 
    border-bottom: #b9baba 1px solid;
    background-image: none;
} 

#nav li ul a{ 
         padding-left:20px; 
         width:108px; 
    /* padding-left二级目录中文字向右移动，但Width必须重新设置=(总宽度-padding-left)*/ 
} 

/*下面是二级目录的链接样式*/ 

#nav li ul a:link  { 
    color:#666; text-decoration:none; 
} 
#nav li ul a:visited  { 
    color:#666;text-decoration:none; 
} 
 

/*==============================*/ 
#nav li:hover ul { 
    left: auto; 
} 
#nav li.sfhover ul { 
    left: auto; 

} 
#content { 
    clear: left;  
} 
#nav ul.collapsed { 
    display: none; 
}

#PARENT{ 
    width:100%; 
    padding-left:8px; 
    padding-top: 5px;
} 
.left_tiete_bt {
color: #FFF;
font-size: 14px;
line-height: 25px;
padding-left: 45px;
}
#nav li ul li a{
    width: 90px;
}

.menu_a{color:#13EC9E;height: 35px;
line-height: 35px;}
.left_tiete_bt{color:#13EC9E;margin-right: 20px;}
.expanded{background-color: #044056;}
.clear{clear: both;}
#nav li ul li a:hover{
   background-color: #e6e8e8;
}
#nav li ul li a{
   background-color: #e3e3e3; /*二级目录的背景色*/ 
   width: 120px;
   height: 31px;
   line-height: 31px;
   border-radius: 3px;

}


</style> 


</head>
<body>
  <div id="left">
<div id="PARENT"> 
<ul id="nav"> 
   <li><a href="#Menu=ChildMenu1" class="menu_a" onclick="DoMenu('ChildMenu1')">基础功能<span class="left_tiete_bt">《</span></a> 
    <ul id="ChildMenu1" class="collapsed"> 
    <li><a href="<?php echo U(GROUP_NAME . '/WeChat/index');?>" target='main'>接口设置</a></li>
    <li><a href="<?php echo U(GROUP_NAME . '/WeChat/wechat_reply');?>" target='main'>微信回复</a></li>
   <!--  <li><a href="<?php echo U(GROUP_NAME . '/WeChat/keyword_reply');?>" target='main'>关键词回复</a></li> -->
    <div class="clear"></div>
    </ul> 
</li>
<!--   <li><a href="#Menu=ChildMenu23" class="menu_a" onclick="DoMenu('ChildMenu23')">微信会员<span class="left_tiete_bt">《</span></a> 
    <ul id="ChildMenu23" class="collapsed"> 
    <li><a href="<?php echo U(GROUP_NAME . '/Member/index');?>" target='main'>会员设置</a></li> 
    <li><a href="<?php echo U(GROUP_NAME . '/Member/add_member');?>" target='main' >添加会员卡</a></li> 
    <li><a href="<?php echo U(GROUP_NAME . '/Member/member_style');?>" target='main' >样式自定义</a></li> 
    <li><a href="<?php echo U(GROUP_NAME . '/Member/store');?>" target='main' >门店导航</a></li>
    <div class="clear"></div>
    </ul> 
</li>
 
 <li><a href="#Menu=ChildMenu1" class="menu_a" onclick="DoMenu('ChildMenu17')">微楼盘<span class="left_tiete_bt">《</span></a> 
    <ul id="ChildMenu17" class="collapsed"> 
    <li><a href="<?php echo U(GROUP_NAME . '/Houses/index');?>" target='main'>》微楼书</a></li> 
    <li><a href="<?php echo U(GROUP_NAME . '/Houses/housesimg');?>" target='main' >》图片上传</a></li> 
    </ul> 
</li> 
 <li><a href="#Menu=ChildMenu101" class="menu_a" onclick="DoMenu('ChildMenu101')">微信团购<span class="left_tiete_bt">《</span></a> 
    <ul id="ChildMenu101" class="collapsed"> 
    <li><a href="<?php echo U(GROUP_NAME . '/Groupbuy/index');?>" target='main'>团购设置</a></li> 
    <li><a href="<?php echo U(GROUP_NAME . '/Groupbuy/goods');?>" target='main' >团购商品</a></li> 
    <li><a href="<?php echo U(GROUP_NAME . '/Groupbuy/order');?>" target='main' >团购订单</a></li> 
    </ul> 
</li> 
<li><a href="#Menu=ChildMenu35" class="menu_a" onclick="DoMenu('ChildMenu35')">微信商城<span class="left_tiete_bt">《</span></a>
    <ul id="ChildMenu35" class="collapsed">
    <li class=""><a href="/weipan/index.php/Admin/Shop/index.html" target="main">商城设置

</a></li>
 <li class=""><a href="<?php echo U(GROUP_NAME . '/Shop/shop_index');?>" target="main">模板管理

</a></li> 
    <li class=""><a href="<?php echo U(GROUP_NAME . '/Shop/shopad');?>" target="main">广告管理
</a></li> 
    <li class=""><a href="<?php echo U(GROUP_NAME . '/Shop/shop_cate');?>" target="main">分类管理
</a></li> 
    <li class=""><a href="<?php echo U(GROUP_NAME . '/Shop/goods_index');?>" target="main">商品管理

</a></li>
    <li class=""><a href="/weipan/index.php/Admin/Shop/Order.html" target="main">订单管理

</a></li> 
    <li class=""><a href="/weipan/index.php/Admin/Shop/Pay.html" target="main">支付管理</a></li> 
    <li class=""><a href="/weipan/index.php/Admin/Shop/wuliu.html" target="main">物流管理

</a></li> 
    </ul> 
</li> -->
  <li>
      <a href="#Menu=ChildMenu2" class="menu_a" onclick="DoMenu('ChildMenu2')">CXTK<span class="left_tiete_bt">《</span></a> 
      <ul id="ChildMenu2" class="collapsed"> 
        <li><a href="<?php echo U(GROUP_NAME . '/web_site/index');?>" target='main'>基本设置</a></li> 
        <li><a href="<?php echo U(GROUP_NAME . '/web_site/web_site_flash');?>" target='main' >幻 灯 片</a></li> 
        <li><a href="<?php echo U(GROUP_NAME . '/web_site/column_index');?>" target='main' >网站栏目</a></li> 
        <li><a href="<?php echo U(GROUP_NAME . '/web_site/form_index');?>" target='main' >在线表单</a></li>
        <li><a href="<?php echo U(GROUP_NAME . '/web_site/store');?>" target='main' >门店管理</a></li>
<!--     
    <li><a href="<?php echo U(GROUP_NAME . '/Website/website_index');?>" target='main' >模板管理</a></li>  -->
    
<!--     <li><a href="<?php echo U(GROUP_NAME . '/Website/Websitecontact');?>" target='main' >联系我们</a></li> 
    <li><a href="<?php echo U(GROUP_NAME . '/Form/form_index');?>" target='main' >万能表单</a></li>
    <li><a href="<?php echo U(GROUP_NAME . '/Website/websitebooking');?>" target='main' >在线预约</a></li>
    <li><a href="<?php echo U(GROUP_NAME . '/Website/store');?>" target='main' >门店管理</a></li> -->
    </ul> 
</li>
  <!--   <li><a href="#Menu=ChildMenu20" class="menu_a" onclick="DoMenu('ChildMenu20')">微信活动<span class="left_tiete_bt">《</span></a> 
    <ul id="ChildMenu20" class="collapsed"> 
    <li><a href="<?php echo U(GROUP_NAME . '/interaction/coupon_index');?>" target='main'>优惠券</a></li> 
    <li><a href="<?php echo U(GROUP_NAME . '/interaction/vote_index');?>" target='main' >微投票</a></li> 

    </ul> 
</li> 
    <li><a href="#Menu=ChildMenu210" class="menu_a" onclick="DoMenu('ChildMenu210')">微信促销<span class="left_tiete_bt">《</span></a> 
    <ul id="ChildMenu210" class="collapsed"> 
    <li><a href="<?php echo U(GROUP_NAME . '/interaction/scratch_index');?>" target='main'>刮刮乐</a></li> 
    <li><a href="<?php echo U(GROUP_NAME . '/interaction/scratch_index');?>" target='main'>刮刮乐</a></li> 
    <li><a href="<?php echo U(GROUP_NAME . '/interaction/scratch_index');?>" target='main'>刮刮乐</a></li> 
    </ul> 
</li> 
  
<li><a href="#Menu=ChildMenu13" onclick="DoMenu('ChildMenu13')">微信订餐<span class="left_tiete_bt">《</span></a> 
    <ul id="ChildMenu13" class="collapsed"> 
    <li><a href="<?php echo U(GROUP_NAME . '/Bookdinner/index');?>" target='main'>订餐设置</a></li> 
    <li><a href="<?php echo U(GROUP_NAME . '/Bookdinner/category');?>" target='main' >分类管理</a></li> 
    <li><a href="<?php echo U(GROUP_NAME . '/Bookdinner/food');?>" target='main' >菜品管理</a></li> 
    <li><a href="<?php echo U(GROUP_NAME . '/Bookdinner/order');?>" target='main' >订单管理</a></li> 
    </ul> 
</li> 
-->
</ul> 
<div class="clear"></div>
</div> 
</div>
  <div id="right" style="width:980px;">
      <iframe name="iframe" src="" scrolling="yes"  noresize="NORESIZE"></iframe>
  </div>
  <script type=text/javascript><!-- 
var LastLeftID = ""; 

function menuFix() { 
    var obj = document.getElementById("nav").getElementsByTagName("li"); 
     
    for (var i=0; i<obj.length; i++) { 
        obj[i].onmouseover=function() { 
            this.className+=(this.className.length>0? " ": "") + "sfhover"; 
        } 
        obj[i].onMouseDown=function() { 
            this.className+=(this.className.length>0? " ": "") + "sfhover"; 
        } 
        obj[i].onMouseUp=function() { 
            this.className+=(this.className.length>0? " ": "") + "sfhover"; 
        } 
        obj[i].onmouseout=function() { 
            this.className=this.className.replace(new RegExp("( ?|^)sfhover\\b"), ""); 
        } 
    } 
} 

function DoMenu(emid) 
{ 
    var obj = document.getElementById(emid);     
    obj.className = (obj.className.toLowerCase() == "expanded"?"collapsed":"expanded"); 
    if((LastLeftID!="")&&(emid!=LastLeftID))    //关闭上一个Menu 
    { 
        document.getElementById(LastLeftID).className = "collapsed"; 
    } 
    LastLeftID = emid; 
} 

function GetMenuID() 
{ 

    var MenuID=""; 
    var _paramStr = new String(window.location.href); 

    var _sharpPos = _paramStr.indexOf("#"); 
     
    if (_sharpPos >= 0 && _sharpPos < _paramStr.length - 1) 
    { 
        _paramStr = _paramStr.substring(_sharpPos + 1, _paramStr.length); 
    } 
    else 
    { 
        _paramStr = ""; 
    } 
     
    if (_paramStr.length > 0) 
    { 
        var _paramArr = _paramStr.split("&"); 
        if (_paramArr.length>0) 
        { 
            var _paramKeyVal = _paramArr[0].split("="); 
            if (_paramKeyVal.length>0) 
            { 
                MenuID = _paramKeyVal[1]; 
            } 
        } 
         
        if (_paramArr.length>0) 
        { 
            var _arr = new Array(_paramArr.length); 
        } 
         
        //取所有#后面的，菜单只需用到Menu 
        //for (var i = 0; i < _paramArr.length; i++) 
        { 
            var _paramKeyVal = _paramArr[i].split('='); 
             
            if (_paramKeyVal.length>0) 
            { 
                _arr[_paramKeyVal[0]] = _paramKeyVal[1]; 
            }         
        } 
        
    } 
     
    if(MenuID!="") 
    { 
        DoMenu(MenuID) 
    } 
} 

GetMenuID();    //*这两个function的顺序要注意一下，不然在Firefox里GetMenuID()不起效果 
menuFix(); 
</script>
</body>
</html>
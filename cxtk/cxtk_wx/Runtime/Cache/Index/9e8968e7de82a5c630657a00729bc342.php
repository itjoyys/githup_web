<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>商品详细信息</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" type="text/css" href="__PUBLICI__/css/all_html5.css" charset="gbk">
    
    <link rel="stylesheet" href="__PUBLICI__/css/style.css">
     <link rel="stylesheet" href="__PUBLICI__/css/templates.css">
     <link rel="stylesheet" href="__PUBLICI__/css/list.css">
    <script src="__PUBLICI__/js/zepto.min.js"></script> 
     <script type="text/javascript" src="__PUBLICI__/Bookdinner/js/jquery-1.4.1.min.js"></script>
    <script type="text/javascript" src="__PUBLICI__/js/alixixi_jquery.min.js"></script>
     <script type="text/javascript" src="__PUBLICI__/js/ProductDetail.js"></script>
     <script type="text/javascript">
    $(document).ready(function(){
    $('#example1').bxCarousel({
        display_num: 4, 
        move: 1,
        prev_image: 'images/icon_arrow_left.png',
        next_image: 'images/icon_arrow_right.png',
        margin: 10 
    });
    $('#example2').bxCarousel({
        display_num: 3, 
        move: 1, 
        auto: true, 
        controls: false,
        margin: 10,
        auto_hover: true
    });
});
/*
bxCarousel v1.0
Plugin developed by: Steven Wanderski
*/
(function($){$.fn.bxCarousel=function(options){var defaults={move:4,display_num:4,speed:500,margin:0,auto:false,auto_interval:2000,auto_dir:'next',auto_hover:false,next_text:'next',next_image:'',prev_text:'prev',prev_image:'',controls:true};var options=$.extend(defaults,options);return this.each(function(){var $this=$(this);var li=$this.find('li');var first=0;var fe=0;var last=options.display_num-1;var le=options.display_num-1;var is_working=false;var j='';var clicked=false;li.css({'float':'left','listStyle':'none','marginRight':options.margin});var ow=li.outerWidth(true);wrap_width=(ow*options.display_num)-options.margin;var seg=ow*options.move;$this.wrap('<div class="bx_container"></div>').width(999999);if(options.controls){if(options.next_image!=''||options.prev_image!=''){var controls='<a href="" class="prev"><img src="'+options.prev_image+'http://localhost:8077/dingcan/index.php/Index/Detail/"/></a><a href="" class="next"><img src="'+options.next_image+'"/></a>';}
else{var controls='<a href="" class="prev">'+options.prev_text+'</a><a href="" class="next">'+options.next_text+'</a>';}}
$this.parent('.bx_container').wrap('<div class="bx_wrap"></div>').css({'position':'relative','width':wrap_width,'overflow':'hidden'}).before(controls);var w=li.slice(0,options.display_num).clone();var last_appended=(options.display_num+options.move)-1;$this.empty().append(w);get_p();get_a();$this.css({'position':'relative','left':-(seg)});$this.parent().siblings('.next').click(function(){slide_next();clearInterval(j);clicked=true;return false;});$this.parent().siblings('.prev').click(function(){slide_prev();clearInterval(j);clicked=true;return false;});if(options.auto){start_slide();if(options.auto_hover&&clicked!=true){$this.find('li').live('mouseenter',function(){if(!clicked){clearInterval(j);}});$this.find('li').live('mouseleave',function(){if(!clicked){start_slide();}});}}
function start_slide(){if(options.auto_dir=='next'){j=setInterval(function(){slide_next()},options.auto_interval);}else{j=setInterval(function(){slide_prev()},options.auto_interval);}}
function slide_next(){if(!is_working){is_working=true;set_pos('next');$this.animate({left:'-='+seg},options.speed,function(){$this.find('li').slice(0,options.move).remove();$this.css('left',-(seg));get_a();is_working=false;});}}
function slide_prev(){if(!is_working){is_working=true;set_pos('prev');$this.animate({left:'+='+seg},options.speed,function(){$this.find('li').slice(-options.move).remove();$this.css('left',-(seg));get_p();is_working=false;});}}
function get_a(){var str=new Array();var lix=li.clone();le=last;for(i=0;i<options.move;i++){le++
if(lix[le]!=undefined){str[i]=$(lix[le]);}else{le=0;str[i]=$(lix[le]);}}
$.each(str,function(index){$this.append(str[index][0]);});}
function get_p(){var str=new Array();var lix=li.clone();fe=first;for(i=0;i<options.move;i++){fe--
if(lix[fe]!=undefined){str[i]=$(lix[fe]);}else{fe=li.length-1;str[i]=$(lix[fe]);}}
$.each(str,function(index){$this.prepend(str[index][0]);});}
function set_pos(dir){if(dir=='next'){first+=options.move;if(first>=li.length){first=first%li.length;}
last+=options.move;if(last>=li.length){last=last%li.length;}}else if(dir=='prev'){first-=options.move;if(first<0){first=li.length+first;}
last-=options.move;if(last<0){last=li.length+last;}}}});}})(jQuery);
</script>
    <style type="text/css">
    .bx_wrap {
    margin-left: 0px;
    border-radius: 5px;
}
.bx_wrap ul img { border: 2px solid #DDD; }




 
    .buy{
        outline: 0;
        padding: 5px 12px;
        width: 42%;
        font-size: 12px;
        line-height: 30px;
        height: 28px;
        float: left;
        display: block;
        color: #fff;
        margin-right: auto;
        margin-left: auto;
        font-weight: bold;
        text-shadow: 1px 1px #1f272b;
        border: 1px solid #1c252b;
        border-radius: 5px;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        background: -webkit-gradient(linear, left top, left bottom, color-stop(3%,#515B62), color-stop(5%,#444E55), color-stop(100%,#394147));
        box-shadow: 1px 1px 1px rgba(0,0,0,0.2);
        -moz-box-shadow: 1px 1px 1px rgba(0,0,0,0.2);
        -webkit-box-shadow: 1px 1px 1px rgba(0,0,0,0.2);
    }
    .buynow {
outline: 0;
padding: 5px 12px;
width: 42%;
line-height: 30px;
height: 28px;
font-size: 12px;
float: right;
display: block;
color: #fff;
margin-right: auto;
margin-left: auto;
font-weight: bold;
text-shadow: 1px 1px #F50B1E;
border: 1px solid #F51C36;
border-radius: 5px;
-moz-border-radius: 5px;
-webkit-border-radius: 5px;
background: -webkit-gradient(linear, left top, left bottom, color-stop(3%,#F81430), color-stop(5%,#F31933), color-stop(100%,#D61212));
box-shadow: 1px 1px 1px rgba(0,0,0,0.2);
-moz-box-shadow: 1px 1px 1px rgba(0,0,0,0.2);
-webkit-box-shadow: 1px 1px 1px rgba(0,0,0,0.2);
}
    .info{padding: 10px;padding-left: 15px;
}
    .buynum{height: auto;
    padding: 0px 0;}
    .price{padding-left: 15px;}
    .title{padding-left: 15px;}
    .qb_hr {
    border: 0;
    border-top: 1px solid #eaeaea;
    border-bottom: 1px solid #FFF;
    clear: both;
    margin-top: 5px;
    margin-bottom: 5px;
    }
    .clear{clear: both;height: 0;}
    span{padding: 0;margin: 0;text-align: center;}

    .cartQuantity {
        width: 123px;
    float: left;
    margin-left: 5px;
-webkit-box-pack: end;
display: -webkit-box;
height: 30px;
border: #C3C3C3 1px solid;
border-radius: 5px;
overflow: hidden;
}
.cartdelQuantity {
border-radius: 5px 0 0 5px;
}
.cartQuantity_text input {
height: 2.1em;
width: 40px;
line-height: 1.9em;
border: #fff 1px solid;
border-radius: 0;
background-color: #fff;
font-size: 1em;
text-align: center;
color: #3f3937;
box-shadow: rgba(0,0,0,0.7) 1px 1px 2px inset;
margin-top: -1px;
}
.cartdelQuantity, .cartaddQuantity {
background-image: -webkit-gradient(linear, left top, left bottom, from(#EDEAE6), to(#DBDBD8));
font-size: 1.2em;
color: #FFF;
width: 2.24em;
text-align: center;
font-weight: normal;
line-height: 1.5em;
}

   </style>    


</head>

<body id="body">
    <header>
        <div class="new-header" style="background:#161616;">
            <a href="javascript:pageBack();" class="new-a-back">
                <span>返回</span>
            </a>
            <h2>商品详细</h2>
            <a href="javascript:void(0)" id="btnJdkey" class="new-a-jd">
                <span>主页</span>
            </a>
        </div>
    </header>
    <div class="lay_page page_detail current" id="page_detail">
        <div class="lay_page_wrap">
            <div class="mod_bg_lace"></div>
              <!--商品图片展示-->
                
                 <div class="bx_wrap">
                    <div class="bx_container">
                        <ul id="example2">
                      
                            <li><img height="200" width="200"  src="<?php echo ($image_url); echo ($photo1); ?>"></li>
                            <li><img height="200" width="200"  src="<?php echo ($image_url); echo ($photo2); ?>"></li>
                            <li><img height="200" width="200"  src="<?php echo ($image_url); echo ($photo3); ?>"></li>
                            <li><img height="200" width="200"  src="<?php echo ($image_url); echo ($photo4); ?>"></li>
                       
                        </ul>
                    </div>
                </div>
           
              
            <!--商品信息展示-->
           <form action="<?php echo U(GROUP_NAME . '/Cart/index');?>"  method="post">
               <div class="shop_detail_box" id="<?php echo ($goodsid); ?>" detailsurl="<?php echo ($goodsid); ?>">
                <h1 class="title"><?php echo ($name); ?></h1>
                <hr class="qb_hr">
                <div class="price">
                    现价：<strong class="mod_color_strong" id="price">￥<?php echo ($saleprice); ?></strong>                    
                    <del class="mod_color_weak qb_fs_s">￥<?php echo ($price); ?></del>                    
                    <br>
                </div>
              <!--选择 购买 数量-->
               <div style="height:30px;line-height:30px;margin-bottom:10px;margin-top:5px;">

                <span style="float:left;margin-left:15px;">数量:</span>   
                <div class="cartQuantity" itemid="169728_10359_0" volumeindex="0" tasteindex="0">
                     <div class="cartdelQuantity touchover" data-func="Decrease">-</div>
                      <div class="cartQuantity_text">
                         <input id="<?php echo ($v["goodsid"]); ?>" type="text" class="textBox" value="1" readonly=""></div>
                     <div class="cartaddQuantity touchover" data-func="Increase" gid="<?php echo ($v["goodsid"]); ?>">+</div>
                 </div>

               </div>
                <span class="buy" id="addcart" addurl="<?php echo U(GROUP_NAME . '/Detail/addcart');?>">加入购物车</span>
                <span class="buynow" type="submit" buyurl="<?php echo U(GROUP_NAME . '/Cart/index');?>">立即购买</span>    
                <div class="clear"></div>
                </div>
           </form>  

         <div id="order_tisi" class="jqPopup">     
            <header>提示</header>                         
             <div>
                <div style="width:1px;height:1px;-webkit-transform:translate3d(0,0,0);float:right"></div>
                添加商品成功
            </div>  
            <footer style="clear:both;">
                    <a href="<?php echo ($homeurl); ?>" class="button center" id="cancel">再逛逛</a>  
                    <a href="<?php echo U(GROUP_NAME . '/Cart/index');?>" class="button center" id="cancel">去购物车结算</a>                               
            </footer>                       
      </div>
 
        </div>
    </div> 
    
     <div class="shop_detail_box">
        <h2 gaevent="imt/deal/terms">
            <span class="icon-fonts">J</span>
            购买须知
        </h2>
        <ul>
            <li>商家QQ：</li>
            <li>产品咨询：商家电话：；联系时间：9:00-24:00</li>
            <li>配送范围：全国（除港澳台、新疆、西藏外）均可配送</li>
            <li>快递公司：本单使用韵达快递（400-821-6789）全峰快递（400-100-0001）</li>
            <li>配送费用：本单包邮，配送范围内无需再额外支付邮费</li>
            <li>配送时间：购买成功后3天内发货，5-7天送达</li>
            <li>查询修改：购买成功后可在我的订单中查询物流和修改订单</li>
            <li>售后保障：如需退换货，请联系商家，详情请见物流售后保障</li>
        </ul>
    </div>
    <div class="shop_detail_box">
        <h2 gaevent="imt/deal/terms">
            <span class="icon-fonts">D</span>
            商品参数：
        </h2>
        <ul>
            <?php echo ($details); ?>产品说明 产品说明 产品说明
           
        </ul>
    </div>
   
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

</body>
</html>
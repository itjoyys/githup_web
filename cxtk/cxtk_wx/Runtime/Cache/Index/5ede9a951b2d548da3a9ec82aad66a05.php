<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>下单成功</title>    
<meta charset="UTF-8">
<meta name="viewport" content=" initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" href="__PUBLICI__/Shop/css/order_min.css">
<script type="text/javascript" src="__PUBLICI__/js/jquery.min.js"></script>
<style type="text/css">
    .buy {
outline: 0;
padding: 5px 12px;
width: 90%;
text-align: center;
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
.add_address{
    width: 100%;
    height: 40px;
}
.do_a{
    width: 85px;
    color: #fff;
    background-color: red;
    border-radius: 5px;
    padding: 4px;
    display: block;
    text-align: center;
}
.do_r{
    width: 50px;
    margin-left: 15px;
    background-color:rgb(75, 87, 223);
}
.do_d{
     width: 50px;
    margin-left: 15px;
    background-color: rgb(102, 83, 83);
}
   #tjun{
  border-color: #6E9FDE #C4DEFF #C4DEFF #6E9FDE;
border-style: solid;
border-width: 1px;
margin-top: 3px;
margin-bottom: 3px;
height: 20px;
} 
  
  #index_tisi,#index_tisi2 {
margin-top: 180px;
left: 50%;
position: fixed;
margin-left: -150px;
border-radius: 10px;
padding: 10px;
opacity: 1;
-webkit-transform: scale(1);
-webkit-transition: all 0.20s ease-in-out;
transform: scale(1);
transition: all 0.20s ease-in-out;
position: absolute;
z-index: 1000000;
margin-right: auto!important;
}
#index_tisi，#index_tisi2 header{

font-weight: bold;
font-size: 12px;
color: #F0890E;
margin: 0;
padding: 0;
display: block !important;
}
.jqPopup div {
font-size: 14px;
margin: 10px 0 10px 10px;
color: #fff;
text-align: center;
}
.jqPopup footer {
width: 100%;
text-align: center;
display: block !important;
color: #fff;
}
.jqPopup .button {
background: #98B037;
height: 21px;
display: inline-block;
line-height: 21px;
font-weight: normal;
font-size: 12px;
text-shadow: none;
width: 100px;
background: #FC0B3B;
margin-right: 5px;
}
</style>
 <script type="text/javascript"> 
$(document).ready(function(){
     var id;
    $('.delete_address').click(function(){
      id=$(this).attr('aid');
      $('#index_tisi').css('display','block');
                     //对话框点击确定、删除
            $('#cance1').click(function(){
               $('#index_tisi').css("display","none");
                var home_url=$(this).attr('url');
                $.ajax({
                    type:'POST',
                    url:'/weipan/index.php/Index/Shop/delete_address',
                    dataType:'json',
                    data:'id='+id,
                    success :function(msg){
                        if (msg===true) {
                            alert('删除成功');
                        }else{
                            alert('删除失败');
                        }

                        ;
                    }

                });
            });
    });

        $('.do_address').click(function(){
          id = $(this).attr('aid');
          var nid = $(this).attr('nid');
                $.ajax({
                    type:'POST',
                    url:'/weipan/index.php/Index/Shop/do_address',
                    dataType:'json',
                    data:'aid='+id+'&nid='+nid,
                    success :function(msg){
                        if (msg===true) {
                            alert('删除成功');
                        }else{
                            alert('删除失败');
                        }
                    }
                });
    });



    //对话框点击取消、
    $('#cance2').click(function(){
       $('#index_tisi').css("display","none");
    })

}) 
</script>
</head>
<body>
    <div id="index_tisi" class="jqPopup" style="display: none;width: 300px;background:rgb(15, 21, 26);">                  
     <header style="color:#fff;">提示</header>                  
        <div>
             <div style="width:1px;height:1px;-webkit-transform:translate3d(0,0,0);float:right"></div>
             确定是否删除！
        </div>                 
      <footer style="clear:both;">                    
             <a onclick="return false;" href="#" class="button center" id="cance1" url="<?php echo ($home_url); ?>" style="
             text-shadow:none;width: 100px;background: #FC0B3B;margin-right: 5px;color:#fff;">确定</a> 
             <a class="button center" onclick="return false;" href="#" id="cance2" style="text-shadow:none;width: 100px;background: #FC0B3B;color:#fff;">取消</a>                      
     </footer>               
</div>
<!--地址列表-->

<div id="J_olist_plugin">
 <div class="mb-dti">    

            <ul class="c-list"> 
        <li> 
            <label> 商家名称 </label> 
            <span class="c-ls-multi"><?php echo ($seller_name); ?></span> 
        </li>
            <li> 
                <label> 客服电话 </label> 
                <span><a href="tel:1" class="c-ls-ar"><?php echo ($seller_tel); ?></a>
                </span>
            </li> 
        </ul> 
 </div>

</div>


<div id="J_olist_plugin">
 <div class="mb-dti">    

            <ul class="c-list"> 
        <li> 
            <label> 收货地址 </label> 
            <span class="c-ls-multi"><?php echo ($address); ?></span> 
        </li>
        <li> 
            <label> 收货人 </label> 
            <span><?php echo ($name); ?></span> 
        </li> 
            <li> 
                <label> 手机号码 </label> 
                <span><a class="c-ls-ar"><?php echo ($tel); ?></a>
           
                </span>
            </li> 
        </ul> 
 </div>

</div>
<div id="J_olist_plugin">
 <div class="mb-dti">    

            <ul class="c-list"> 
        <li> 
            <label> 订单号 </label> 
            <span class="c-ls-multi"><?php echo ($order_id); ?></span> 
        </li>
        <li> 
            <label> 订单时间 </label> 
            <span><?php echo ($date); ?></span> 
        </li> 
            <li> 
            <label> 订单金额 </label> 
            <span><?php echo ($order_price); ?></span> 
        </li> 
        </ul> 
 </div>

</div>
</body>
</html>
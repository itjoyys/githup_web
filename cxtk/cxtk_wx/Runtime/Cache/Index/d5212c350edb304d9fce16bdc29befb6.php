<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>购物车</title>
    <meta charset="UTF-8">
    <meta name="viewport" content=" initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="__PUBLICI__/Shop/css/style.css" rel="stylesheet">
    <link href="__PUBLICI__/Shop/css/templates.css" rel="stylesheet">
    <link rel="stylesheet" href="__PUBLICI__/css/list.css">
    <script type="text/javascript" src="__PUBLICI__/js/jquery.min.js"></script>
    <script type="text/javascript" src="__PUBLICI__/js/ProductDetail.js"></script>
    <style type="text/css">
     .order_now{
        width: 80px;
        border-radius: 5px;
     }
     .icon_delete{
        float: left;
     }
     .ui_p10 {
        padding-top: 5px;
        padding-bottom: 5px;
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
.center{
    border-radius: 5px;
}

    </style>
          <script type="text/javascript"> 
$(document).ready(function(){
     var id;
    $('.icon_delete').click(function(){
      id=$(this).attr('id');
      $('#index_tisi').css('display','block');

                     //对话框点击确定、删除
            $('#cance1').click(function(){
               $('#index_tisi').css("display","none");
                var home_url=$(this).attr('url');
                var totalprice = $('#total_span').text();
                var div_id='#item_'+id;
                $.ajax({
                    type:'POST',
                    url:'/weipan/index.php/Index/Shop/deleteCart',
                    dataType:'json',
                    data:'gid='+id+'&totalprice='+totalprice,
                    success :function(data){
                        if (data.state===true) {
                            $(div_id).remove();  
                            $('#total_span').html(data.totalprice);
                        }else{
                             $(div_id).remove();  
                             location.href = home_url;
                        }

                        ;
                    }

                });
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
<div class="lay_page current">
    <div class="lay_page_wrap">

              <form action="<?php echo U(GROUP_NAME . '/Shop/Order');?>" method="post">
              <?php if(is_array($cart)): foreach($cart as $key=>$c): ?><div class="mod_cell ui_plr0 ui_mt15 qb_mb15 item" id="item_<?php echo ($c["gid"]); ?>">
                    <div class="mod_celltitle qb_fs_s ui_plr10">
                      <!--   <h3 class="qb_fl">
                            <i class="qb_icon icon_checkbox icon_checkbox_checked" index="0" name="cart_checkbox"></i>
                        </h3> -->
                    </div>
                    <div class="ui_plr10">
                        <ul class="mod_list mod_list_hr qb_fs_s">
                            <li class="list_item qb_mb10 qb_bfc">
                                <a href="<?php echo U(GROUP_NAME . '/Detail/Index', array('gid' => $c['gid']));?>" class="bfc_f">
                                 <img src="<?php echo ($image_url); echo ($c["img"]); ?>"></a>
                                <div class="bfc_c">
                                    <p><?php echo ($c["goodsname"]); ?></p>
                                    <p>
                                        单价：
                                        <span class="ui_color_strong">￥<?php echo ($c["price"]); ?>元</span>
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="qb_tar ui_p10 qb_fs_s ui_bg_color_weak ui-bt">
                        <i class="qb_icon icon_trash ui_mr30 icon_delete" id="<?php echo ($c["gid"]); ?>"></i>
                        数量：<?php echo ($c["goodsnum"]); ?>
                    </div>
                    <input type="hidden" name="siteid" value="<?php echo ($siteid); ?>">
                    <input type="hidden" name="openid" value="<?php echo ($openid); ?>">
                    <input type="hidden" name="gid[]" value="<?php echo ($c["gid"]); ?>">
                    <input type="hidden" name="goodsnum[]" value="<?php echo ($c["goodsnum"]); ?>">
                    </div><?php endforeach; endif; ?>
                    <div class="mod_cell ui_mt15 qb_mb15 ui_pt10">
                        <div class="ui_pb10 ui_clearfix qb_fs_s">
                         <span class="qb_tof" style="width: 100px">
                            共计：
                            <span class="ui_color_strong single-total" id="total_span">￥<?php echo ($totalprice); ?>元</span>
                        </span>
                        <input type="submit" class="mod_btn btn_strong qb_fr confirm_order order_now" style="font-size:12px;" value="提交订单">
                        </div>
                     </div>
            </form>

</div>
</div>
</body>
</html>
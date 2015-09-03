<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content=" initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="__PUBLICI__/Shop/css/style.css" rel="stylesheet">
    <link href="__PUBLICI__/Shop/css/templates.css" rel="stylesheet">
    <link rel="stylesheet" href="__PUBLICI__/css/list.css">
    <link href="__PUBLICI__/Shop/css/st.css" rel="stylesheet">
    <script type="text/javascript" src="__PUBLICI__/js/jquery.min.js"></script>
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
.buy_order{
  margin: 10px 0;
font-size: 18px;
line-height: 40px;
height: 40px;
display: inline-block;
width: 80%;
max-width: 300px;

}
    </style>

 <script>
   function checkfun(){
        var aid = document.getElementById("address_id").value;
         if(aid==""){
               alert('地址不能为空');
               return false;
          }else{
             $('form').submit();
          }            
   }
                        </script>
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
                var div_id='#item_'+id;
                $.ajax({
                    type:'POST',
                    url:'/weipan/index.php/Index/Shop/deleteCart',
                    dataType:'json',
                    data:'gid='+id,
                    success :function(msg){
                        if (msg===true) {
                            $(div_id).remove();  
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
<section data-role="body" class="body">
             <form action="<?php echo U(GROUP_NAME . '/Shop/run_order');?>" method="post" name="form">
                <div class="section_address">
                    <div id="wrap_address">
                       <a href="<?php echo U(GROUP_NAME . '/Shop/address_index');?>" class="tbox arrow" onclick="myChoice(this, event, 'address'); return false;">         
                           <div>           
                               <span class="icon_wrap icon_address">&nbsp;</span>          
                          </div>          
                          <div>           
                               <p>
                               <span>
                                  <label>收货人：</label><?php echo ($name); ?></span>
                               <span class="fr"><?php echo ($tel); ?></span></p>            
                               <p><?php echo ($address); ?></p>         
                          </div>        
                        </a>
                      </div>
                </div>
                <!---->
                <div class="section_detail">
                    <div>
                        <header>
                            <p>
                                <label class="h8">购物清单</label>
                                <label class="label_amount_goods fr">共<?php echo ($goods_num); ?>件</label>
                            </p>
                        </header>
                        <ul class="list_goods">
                                        <?php if(is_array($goods)): foreach($goods as $key=>$c): ?><div class="mod_cell ui_plr0 ui_mt15 qb_mb15 item" id="item_<?php echo ($c["gid"]); ?>">
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
                        数量：<?php echo ($c["goodsnum"]); ?>
                    </div>
                    <input type="hidden" name="siteid" value="<?php echo ($siteid); ?>">
                    <input type="hidden" name="address_id" value="<?php echo ($address_id); ?>" id="address_id">
                    <input type="hidden" name="openid" value="<?php echo ($openid); ?>">
                    <input type="hidden" name="gid[]" value="<?php echo ($c["gid"]); ?>">
                    <input type="hidden" name="goodsnum[]" value="<?php echo ($c["goodsnum"]); ?>">
                    </div><?php endforeach; endif; ?>
                        </ul>
                        <div class="mark_msg">
                            <input type="text" id="remark" name="remark" placeholder="备注" maxlength="300">
                        </div>
                        <header class="header_pay" style="display: none;">
                            <a href="javascript:;">
                                <label class="h8">支付方式</label>
                            </a>
                        </header>
                        <ul id="pay_list" class="pay_list">
                     <!--        <li>
                                <label class="tbox">
                                    <div>
                                        <span class="pay_alipay">&nbsp;</span>
                                    </div>
                                    <div>
                                        <p>支付宝</p>
                                        <p>推荐已开通支付宝的用户使用</p>
                                    </div>
                                    <div>
                                        <input type="radio" name="paytype" value="8" onclick="changePayment(this);" class="radio" checked="checked">
                                    </div>
                                </label>
                            </li> -->
                            <li>
                                <label class="tbox">
                                    <div>
                                        <span class="pay_offlinepay">&nbsp;</span>
                                    </div>
                                    <div>
                                        <p>货到付款</p>
                                        <p>推荐线下支付的用户使用</p>
                                    </div>
                                    <div>
                                        <input type="radio" name="pay_type" value="0" class="radio" checked="checked" onclick="changePayment(this);">
                                    </div>
                                </label>
                            </li>
                        </ul>
                    </div>
                    <script>
                        function changePayment(thi)
                        {
                            if(thi.checked)
                                data.payment=JSON.parse(thi.value);
                            else
                                data.payment=null;
                        }
                    </script>
                </div>
                <!---->
                <div>
                    <div id="order_submit_1" class="section_price_and_pay align_center order_submit_1">
                        
                        <p>总共支付   <span class="label_amount_total">￥<?php echo ($totalprice); ?></span></p>
                        <p class="p_delivery_fee">（<?php echo ($freight); ?>）</p>
                        <p>
                        <span>
                            <input type="hidden" value="<?php echo ($totalprice); ?>" name="totalprice">
                            <button onclick="checkfun();" class="btn red buy_order" type="button">立即支付</button>
                         </span>
                            
                        </p>
                
                    </div>
                </div>
            </form>
            <!---->
        </section>
</body>
</html>
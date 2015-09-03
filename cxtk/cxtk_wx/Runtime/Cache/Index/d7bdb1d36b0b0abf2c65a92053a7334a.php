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
    <link href="http://x.wx0571.com/Show/css/shop/style.css" rel="stylesheet">
    <link href="http://x.wx0571.com/Show/css/shop/templates.css" rel="stylesheet">
    <link rel="stylesheet" href="__PUBLICI__/css/list.css">
    <script type="text/javascript" src="__PUBLICI__/js/jquery.min.js"></script>

<script type="text/javascript" src="__PUBLICI__/js/ProductDetail.js"></script>
</head>
<body>

<div class="lay_header" style="height: 45px">

    <div class="lay_toptab mod_tab fixed qb_none" id="lay_head_fixed">
        <div class="tab_item go_back">
            <i class="qb_icon icon_goback"></i>
        </div>
        <a class="tab_item" href="">
            <i class="qb_icon icon_icenter"></i>
        </a>
        <a class="tab_item" href="">
            <i class="qb_icon icon_cart"></i>
            <i class="qb_icon icon_number_bubble qb_none"></i>
        </a>
    </div>
</div>

<div class="lay_page current">
    <div class="lay_page_wrap">
        <input type="hidden" id="items-len" value="2">
        <input type="hidden" id="problem-Len" value="0">
        <section id="content">
            <section id="item-sec">
              <?php if(is_array($Cart)): $i = 0; $__LIST__ = $Cart;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="mod_cell ui_plr0 ui_mt15 qb_mb15 item" id="item_<?php echo ($v["goodsid"]); ?>" last-count="6" price="10">
                    <div class="mod_celltitle qb_fs_s ui_plr10">
                        <h3 class="qb_fl">
                            <i price="10" class="qb_icon icon_checkbox icon_checkbox_checked" index="0" name="cart_checkbox"></i>
                        </h3>
                    </div>
                    <div class="ui_plr10">
                        <ul class="mod_list mod_list_hr qb_fs_s">
                            <li class="list_item qb_mb10 qb_bfc">
                                <a href="<?php echo U(GROUP_NAME . '/Detail/Index', array('goodsid' => $v['goodsid']));?>" class="bfc_f">
                                    <img src="<?php echo ($image_url); echo ($v["img"]); ?>"></a>
                                <div class="bfc_c">
                                    <p><?php echo ($v["goodsname"]); ?></p>
                                    <p>
                                        单价：
                                        <span class="ui_color_strong">￥<?php echo ($v["goodsprice"]); ?>元</span>
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="qb_tar ui_p10 qb_fs_s ui_bg_color_weak ui-bt">
                        <i class="qb_icon icon_trash ui_mr30 icon_delete" url="<?php echo U(GROUP_NAME . '/Cart/deleteCart');?>" id="<?php echo ($v["goodsid"]); ?>"></i>
                        数量：
                        <input type="tel" class="ui_textinput ui_textinput_tiny count" value="<?php echo ($v["goodsnum"]); ?>">
                        <span class="qb_tof" style="width: 100px">
                            共计：
                            <span class="ui_color_strong single-total">￥<?php echo ($v["tailprice"]); ?>元</span>
                        </span>
                    </div>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>

                <form action="" method="get" name="cartForm">
                    <input type="hidden" value="130" id="siteid" name="siteid">
                    <input type="hidden" value=",o7-66uJ0vwxucpNj2Q6o7D9nc0ro" id="openid" name="openid">
                    <input type="hidden" value="0" id="payType" name="payType">
                    <input type="hidden" value="3" id="totleNum" name="payType">
                    <input type="hidden" value="297.00" id="totlePrice" name="payType">
                    <input type="hidden" value="18|99.00|3," id="itemList" name="itemList">
                    <input type="hidden" value="1" id="cart" name="cart">
                    <input type="hidden" id="trandom" name="t">
                    <input type="hidden" id="SourceUrl" name="SourceUrl" value="/show/shop/Cart.aspx">
                    <div class="mod_cell ui_mt15 qb_mb15 ui_pt10">
                        <div class="ui_pb10 ui_clearfix qb_fs_s">
                            <div class="qb_fl">
                                <span class="ui_mr30 ui_pt10">
                                    <i class="qb_icon icon_checkbox icon_checkbox_checked" id="choose-all"></i>
                                   
                                </span>
                               
                                <span class="ui_color_strong">
                                  
                                    <span id="total"></span>
                                </span>
                            </div>
                            <!--<a class="mod_btn btn_strong qb_fr confirm_order" href="/show/shop/ConfirmOrder.aspx?siteid=130&openid=,o7-66uJ0vwxucpNj2Q6o7D9nc0ro">去结算</a>
                        -->
                        <a href="<?php echo U(GROUP_NAME . '/Cart/clearCart');?>" class="mod_btn btn_strong qb_fr confirm_order" style="font-size:12px;" >清空购物车</a>
                        <a href="<?php echo U(GROUP_NAME . '/Order/index');?>" class="mod_btn btn_strong qb_fr confirm_order " style="font-size:12px;" >去结算</a>

                    </div>
                </div>
            </form>

        </section>

    </section>
</div>
</div>
<div class="qb_quick_tip qb_none" id="bubble"></div>
<div class="mod_dialog qb_none" id="message-notice">
<div class="dialog_mask"></div>
<div class="dialog_main qb_br qb_tac">
    <div class="dialog_bd" id="notice-content"></div>
    <div class="dialog_ft qb_flex">
        <a href="javascript:void(0);" class="flex_box" id="notice-cancel">取消</a>
        <a href="javascript:void(0);" class="flex_box" id="notice-sure">确定</a>
    </div>
</div>
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
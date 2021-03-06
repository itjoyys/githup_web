<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>新增收货地址</title>    
<meta charset="UTF-8">
<meta name="viewport" content=" initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<link href="__PUBLICI__/css/style.css" rel="stylesheet">
<link href="http://x.wx0571.com/Show/css/shop/templates.css" rel="stylesheet">
<link rel="stylesheet" href="__PUBLICI__/css/list.css">
<script type="text/javascript" src="__PUBLICI__/js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLICI__/js/area.js"></script>
</head>
<body>
    <form name="ctl00" method="post" action="<?php echo U(GROUP_NAME . '/Shop/run_add_address');?>" id="ctl00">
        <div class="lay_page page_address_add" id="page_address_add">
            <div class="lay_page_wrap">
                <div class="qb_gap qb_pt10">

                    <div class="qb_mb10 qb_flex">
                          <span>姓  名：</span>
                          <input name="name" type="text" id="name" class="mod_input flex_box" placeholder="收件人姓名">
                    </div>
                    <div class="qb_mb10 mod_color_strong qb_fs_s qb_none" id="name_msg"></div>
                    <div>
                     <span class="mod_select qb_mr10 qb_mb10" id="sprovince" data-url="">
                            <span>选择省：</span>
                            <select id="s_province" name="s_province">
                                    <option value="省份">省份</option>
                                    <option value="北京市">北京市</option>
                                    <option value="天津市">天津市</option>
                                    <option value="上海市">上海市</option>
                                    <option value="重庆市">重庆市</option>
                                    <option value="河北省">河北省</option>
                                    <option value="山西省">山西省</option>
                                    <option value="内蒙古">内蒙古</option>
                                    <option value="辽宁省">辽宁省</option>
                                    <option value="吉林省">吉林省</option>
                                    <option value="黑龙江省">黑龙江省</option>
                                    <option value="江苏省">江苏省</option>
                                    <option value="浙江省">浙江省</option>
                                    <option value="安徽省">安徽省</option>
                                    <option value="福建省">福建省</option>
                                    <option value="江西省">江西省</option>
                                    <option value="山东省">山东省</option>
                                    <option value="河南省">河南省</option>
                                    <option value="湖北省">湖北省</option>
                                    <option value="湖南省">湖南省</option>
                                    <option value="广东省">广东省</option>
                                    <option value="广西">广西</option>
                                    <option value="海南省">海南省</option>
                                    <option value="四川省">四川省</option>
                                    <option value="贵州省">贵州省</option>
                                    <option value="云南省">云南省</option>
                                    <option value="西藏">西藏</option>
                                    <option value="陕西省">陕西省</option>
                                    <option value="甘肃省">甘肃省</option>
                                    <option value="青海省">青海省</option>
                                    <option value="宁夏">宁夏</option>
                                    <option value="新疆">新疆</option>
                                    <option value="香港">香港</option>
                                    <option value="澳门">澳门</option>
                                    <option value="台湾省">台湾省</option>
                             </select>
                         </span>
                          <br>
                        <span class="mod_select qb_mr10" id="scity" data-url="">
                            <span>选择市：</span>
                            <select id="s_city" name="s_city">
                             <option value="地级市">地级市</option>
                            </select>
                       
                        </span>
                        <br>
                        <span class="mod_select qb_mb10">
                           <span>选择区：</span>
                           <select id="s_county" name="s_county">
                             <option value="市、县级市">市、县级市</option>
                            </select>
                         </span>
                         <script type="text/javascript">_init_area();</script>
                    </div>
                    <div class="qb_mb10 mod_color_strong qb_fs_s qb_none" id="region_msg"></div>
                    <div class="qb_mb10 qb_flex">
                        <input name="address" type="text" id="address" class="mod_input flex_box" placeholder="详细地址">
                    </div>
                    <div class="qb_mb10 mod_color_strong qb_fs_s qb_none" id="address_msg"></div>
                    <div class="qb_mb10 qb_flex">
                        <input name="tel" type="text" id="mobile" placeholder="联系电话" class="mod_input flex_box">
                    </div>
                    <div class="qb_mb10 mod_color_strong qb_fs_s qb_none" id="mobile_msg"></div>
                    <div class="qb_mb10">
                         <input type="hidden" name="openid" value="<?php echo ($openid); ?>">
                         <input type="submit" name="Button1" value="保存收货地址" id="Button1" class="mod_btn btn_em btn_block" style="width:100%">
                    </div>
                </div>
            </div>
        </div>
    </form>
    



<script type="text/javascript">
var Gid  = document.getElementById ;
var showArea = function(){
    Gid('show').innerHTML = "<h3>省" + Gid('s_province').value + " - 市" +    
    Gid('s_city').value + " - 县/区" + 
    Gid('s_county').value + "</h3>"
                            }
Gid('s_county').setAttribute('onchange','showArea()');
</script>

    <style>button{
    border:1px solid #ccc;
    cursor:pointer;
  display:block;
  margin:auto;
  position:relative;
  top:100px;
}</style>
</body>
</html>
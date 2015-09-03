<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="__PUBLICI__/website/css/onlinebooking.css" media="all">
    <link rel="stylesheet" type="text/css" href="__PUBLICI__/website/css/datepicker.css" media="all">
    <link rel="stylesheet" type="text/css" href="__PUBLICI__/website/css/weimob-ui-1-1.css" media="all">
    <link rel="stylesheet" type="text/css" href="__PUBLICI__/website/css/common.css" media="all">
    <title><?php echo ($field['title']); ?></title>
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <!-- Mobile Devices Support @begin -->

    <meta content="no-cache,must-revalidate" http-equiv="Cache-Control">
    <meta content="no-cache" http-equiv="pragma">
    <meta content="0" http-equiv="expires">
    <meta content="telephone=no, address=no" name="format-detection">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <!-- apple devices fullscreen -->
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <!-- Mobile Devices Support @end -->
    <style>img{max-width:100%!important;}</style>
</head>
<body onselectstart="return true;" ondragstart="return false;" id="onlinebooking">
    <div class="qiandaobanner">
        <img src="<?php echo ($image_url); echo ($form['img']); ?>"></div>
    <div class="cardexplain">
        <!--后台可控制是否显示-->
        <?php if(!empty($$form['intro'])): ?><ul class="round">
            <li>
                <h2>预约说明</h2>
                <div class="text"><?php echo ($form['intro']); ?></div>
            </li>
        </ul><?php endif; ?>
        <!--后台可控制是否显示-->
        <ul class="round">
            <li class="addr">
                <a href="<?php echo ($aurl); ?>">
                    <span style="font-size: 14px;">地址：<?php echo ($form['address']); ?></span>
                </a>
            </li>
            <li class="tel">
                <a href="tel:<?php echo ($form['tel']); ?>">
                    <span style="font-size: 14px;">电话：<?php echo ($form['tel']); ?></span>
                </a>
            </li>
        </ul>

        <ul class="round">
            <form action="<?php echo U(GROUP_NAME . '/Form/run_add_form');?>" method="post" enctype="multipart/form-data">
                <li class="title mb">
                    <span class="none"><?php echo ($form['title']); ?></span>
                </li>
            <?php if(is_array($fields)): $i = 0; $__LIST__ = $fields;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class="nob">
                    <table class="kuang" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tbody>

                          <?php if($v['cate'] == 1): ?><tr>
                                <th><?php echo ($v["name"]); ?></th>
                                <td>
                                    <input name="<?php echo ($v["field_name"]); ?>" class="px" id="truename" value="" placeholder="请输入<?php echo ($v["name"]); ?>" type="text"></td>
                            </tr>
                            <?php elseif($v['cate'] == 2): ?>
                              <tr>
                                <th><?php echo ($v["name"]); ?></th>
                                <td>
                                    <select name="<?php echo ($v["field_name"]); ?>"  class="dropdown-select">
                                        <?php if(is_array($v['f_content'])): foreach($v['f_content'] as $key=>$vo): ?><option value="<?php echo ($vo); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; ?>                    
                                    </select>
                                </td>
                            </tr>
                              <?php elseif($v['cate'] == 3): ?>
                              <tr>
                                <th><?php echo ($v["name"]); ?></th>
                                <td>
                                    <select name="<?php echo ($v["field_name"]); ?>"  class="dropdown-select">
                                        <?php if(is_array($v['f_content'])): foreach($v['f_content'] as $key=>$vo): ?><option value="<?php echo ($vo); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; ?>                    
                                   
                                </td>
                            </tr>
                              <?php elseif($v['cate'] == 4): ?>
                              <tr>
                                <th><?php echo ($v["name"]); ?></th>
                                <td>
                                    <select name="<?php echo ($v["fieldname"]); ?>"  class="dropdown-select">
                                        <?php if(is_array($v['content'])): foreach($v['content'] as $key=>$vo): ?><option value="<?php echo ($vo); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; ?>                    
                                    </select>
                                </td>
                            </tr>  
                              <?php elseif($v['cate'] == 5): ?>
                              <tr>
                                <th><?php echo ($v["name"]); ?></th>
                                <td>
                                    <input type="file" name="<?php echo ($v["field_name"]); ?>" size="2000000" />
                                </td>
                            </tr>  
                            <?php elseif($v['cate'] == 6 ): ?> 
                            <tr>
                                <th class="thtop" valign="top"><?php echo ($v["name"]); ?></th>
                                <td>
                                    <textarea name="<?php echo ($v["field_name"]); ?>" class="pxtextarea" style=" height:99px;overflow-y:visible" id="info" placeholder="请输入<?php echo ($v["name"]); ?>"></textarea>
                                </td>
                            </tr><?php endif; ?>
                        </tbody>
                    </table>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
             <div class="footReturn">
               <input name="form_id" class="px" id="truename" value="<?php echo ($form_id); ?>" type="hidden">
               <input name="openid" class="px" id="truename" value="<?php echo ($openid); ?>" type="hidden">
               <input name="is_img" class="px" id="truename" value="<?php echo ($is_img); ?>" type="hidden">
             <input id="showcard" class="submit" value="提交消息" type="submit" style="width:100%;">
            </div>
            </form>
        </ul>
       
    </div>
    <footer style="text-align:center; color:#ffd800;margin-right:20px;margin-top:0px;">
        <a href="">©--杭州微盘技术支持</a>
    </footer>

</body>
</html>
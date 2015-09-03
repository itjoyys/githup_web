<?php 
include_once("../../include/config.php");
include_once("../../common/login_check.php");
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Document</title>
        <link rel="stylesheet" href="../public/css/index_main.css" />
        <link rel="stylesheet" href="../public/css/standard.css" />
    </head>
    <body  style="BACKGROUND: url(../public/images/content_bg.jpg) repeat-y left top;" >
        <div id="MAMain" style="width:767px">
            <div id="MACenter-content">
                <div id="MACenterContent">
                    <div id="MNav">
                        <span class="mbtn">最新信息</span>
                        <div class="navSeparate"></div>
                        <a target="k_memr" class="mbtn" href="histiry_news.php">历史信息</a>
                    </div>
                    <div id="MMainData">
                        <table class="MMain" border="1">
                            <thead>
                                <tr>
                                    <th style="width: 20%;" nowrap="">日期</th>
                                    <th nowrap="">内容</th>
                                </tr>
                            </thead>
                            <?php 
                            $time=time()-60*60*24*7;
                            $time=date('Y-m-d H:i:s',$time);
                            $k_message=M('k_message',$db_config);
                            $info=$k_message->field('add_time,chn_simplified')->where("is_delete ='0' and site_id='".SITEID."' and show_type='2' and add_time>'".$time."'")->order('add_time desc')->limit(1)->select();

                            if(!empty($info)){
                            foreach($info as $K=>$v){
                            ?>
                            <tbody>
                                <tr class="MColor1" align="center">
                                <td class="mouseenter"><?=$v['add_time'] ?></td>
                                <td class="MContent mouseenter"><?=$v['chn_simplified'] ?></td>
                                </tr>
                            </tbody> 
                            <?php }}else{ ?>
                            <tbody>
                                <tr class="MColor1" align="center">
                                <td class="mouseenter" align="center" colspan="2">暂无最新消息</td>
                                </tr>
                            </tbody> 
                            <?php }?>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
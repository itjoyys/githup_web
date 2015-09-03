<?
include_once("../../include/config.php");
include_once("../../common/login_check.php");

$time=time()-30*24*60*60;
$date=date('Y-m-d H:i:s',$time);
$sql  = "sid = '0' and notice_statu = '1'";
$sql .=" and (notice_cate='3' or notice_cate='2')";
$sql .=" and notice_date>'$date'";
$sql .= " ORDER BY notice_date DESC limit 0,8";

$data = M('site_notice',$db_config)->where($sql)->select();
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
                        <a target="k_memr" class="mbtn" href="sports_news.php">体育公告</a>
                        <div class="navSeparate"></div>
                        <a target="k_memr" class="mbtn" href="tv_news.php">视讯公告</a>
                        <div class="navSeparate"></div>
                        <span class="mbtn">彩票公告</span>        
                    </div>
                    <div id="MMainData">
                        <h2 class="MSubTitle">彩票公告</h2>
                        <table class="MMain" border="1">
                            <thead>
                                <tr>
                                    <th style="width: 87px;" nowrap="">日期</th>
                                    <th nowrap="">内容</th>
                                </tr>
                            </thead>
                            <tbody>
                                <? if(!empty($data)){
                                foreach($data as $k=>$v){
                                ?>
                                    <tr class="MColor1" align="center">
                                        <td class=""><?=$v['notice_date']?></td>
                                        <td class="MContent"><?=$v['notice_content']?></td>
                                    </tr>
                                <?}}else{?>
                                    <tr><td colspan="2" align="center">暂无公告讯息</td></tr>
                                <?php }?> 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
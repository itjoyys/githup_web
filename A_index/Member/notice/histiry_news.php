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
                        <a  target="k_memr" class="mbtn" href="news.php">最新信息</a>
                        <div class="navSeparate"></div>
                        <span class="mbtn">历史信息</span>
                    </div>
                    <div id="MMainData" style="overflow-y:scroll; height:330px">
                        <table class="MMain" border="1">
                            <thead>
                                <tr>
                                    <th style="width:20%;" nowrap="">日期</th>
                                    <th nowrap="">内容</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $perNumber=10; //每页显示的记录数
                            $page=$_GET['page']; //获得当前的页面值
                            $sql="select id from k_message where is_delete='0' and site_id='".SITEID."' and show_type='2'";
                            $query=$mysqlt->query($sql); //获得记录总数
                            $count=0;
                            while($rows = $query->fetch_array()){
                                $count++;
                            }

                            $totalPage=ceil($count/$perNumber); //计算出总页数


                            if (!isset($page)) {
                            $page=1;
                            } //如果没有值,则赋值1
                            $startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录

                            $sql="SELECT add_time,chn_simplified from k_message where is_delete='0' and site_id='".SITEID."' and show_type='2' order by add_time desc limit $startCount,$perNumber";
                            $query  =   $mysqlt->query($sql);
                            while($rows = $query->fetch_array()){
                            ?>

                            <tr class="MColor1" align="center">
                            <td class="mouseenter"><?=$rows['add_time'] ?></td>
                            <td class="MContent"><?=$rows['chn_simplified'] ?></td>
                            </tr>
                            <?php } ?>

                            </tbody>
                        </table>
                        <div style="text-align:center;width:100%;height:30px;line-height:30px;">
                            <?php if ($page > 1) { //页数不等于1 ?>
                            <a href="histiry_news.php?page=<?php echo $page - 1;?>">上一页</a> <!--显示上一页-->
                            <?php
                            }
                            for ($i=1;$i<=$totalPage;$i++) {  //循环显示出页面

                            if($i>$page-4 && $i<$page+4){
                            ?>
                            <a href="histiry_news.php?page=<?php echo $i;?>"><?php echo $i ;?></a>
                            <?php 
                            }               

                            }
                            if ($page<$totalPage) { //如果page小于总页数,显示下一页链接
                            ?>
                            <a href="histiry_news.php?page=<?php echo $page + 1;?>">下一页</a>
                            <?php
                            } 
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
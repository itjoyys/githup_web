<?php
include_once("../../include/config.php");
include_once("../../common/login_check.php");
include_once("../../class/user.php");
$userinfo=user::getinfo($_SESSION["uid"]);
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>线上取款</title>
        <link rel="stylesheet" href="../public/css/index_main.css" />
        <link rel="stylesheet" href="../public/css/standard.css" />
    </head>
    <body  style="BACKGROUND: url(../public/images/content_bg.jpg) repeat-y left top;" >
        <div id="MAMain" style="width:767px">
            <div id="MACenter-content">
                <div id="MACenterContent">    
                	<div id="MNav">
                        <a  target="k_memr"   href="zr_money.php" class="mbtn">额度转换</a>     
                        <div class="navSeparate"></div>
                      <a  target="k_memr"   href="set_money.php" class="mbtn">线上存款</a>
                        <div class="navSeparate"></div>
                          <span class="mbtn"><a   target="k_memr"   href="get_money.php" class="mbtn">线上取款</a></span>
                    </div>

                    <?php 
                        if($userinfo['shiwan'] == 1){
                            echo "<script>";
                            echo 'alert("试玩账号不能存取款，请注册正式账号！")';
                            echo "</script>";
                            exit();
                        }
                    ?>
                    <div id="MMainData" style="margin-top: 8px;">
                        <table class="MMain" border="1">
                            <thead>
                                <tr>
                                    <th>帐户</th>
                                    <th>余额</th>
                                    <th>取款</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="text-align: center;">
                                    <td class=""><?=$userinfo['username']?></td>
                                    <td class=""><?=$userinfo['money']?>(RMB)</td>
                                    <td class=""><a href="#" onclick="window.open('show.php','newwindow','height=580,width=800,top=20,left=200,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no')">取款</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
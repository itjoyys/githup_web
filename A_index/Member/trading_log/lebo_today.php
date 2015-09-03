<?php 
include_once "../../include/config.php";
include_once("../../common/login_check.php");

?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="../public/css/index_main.css" />
        <link rel="stylesheet" href="../public/css/standard.css" />
    </head>
    <body style="BACKGROUND: url(../public/images/content_bg.jpg) repeat-y left top;">
        <div id="MAMain" style="width:767px">
            <div id="MACenter-content">
                <div id="MACenterContent">
                    <?php include( "common.php") ?>
                    <div id="MMainData" style="overflow-y:scroll; height:330px;overflow-x:hidden">
                        <div class="MControlNav">
                            <input type="hidden" id="Company" value="lebo">
                            <input type="hidden" id="VideoType" value="all">
                            <input type="hidden" id="username" value="<?=$_SESSION['username']?>">
                            <!-- <a href="report_xh.php?uid=9614d2a631b5269b23707&langx=zh-cn">小费明细</a> -->
                            订单号：
                            <input type="text" name="OrderId" id="OrderId" class="za_text" value="" onKeyUp="value=value.replace(/[^\w]/ig,'')">
                            <!-- &nbsp;&nbsp;游戏类型:<select id="gtype" name="gtype" class="za_select"></select>
                            &nbsp;&nbsp;游戏<select class="gkind" id="gkind" name="gkind" ><option value=''>请选择</option></select> -->
                            投注时间：從
                            <input id="s_time" class=" Wdate" type="text" onclick="WdatePicker()" readonly="readonly" size="10" maxlength="20"
                            value="<?=date('Y-m-d')?>" name="starttime">
                            至
                            <input id="e_time" class=" Wdate" type="text" onclick="WdatePicker()" readonly="readonly" size="10" maxlength="20"
                            value="<?=date('Y-m-d')?>" name="endtime">
                            
                            <input type="submit" name="subbtn" class="button_a" onclick="GetVideoList()" value=" 查 询 ">
                            
                            <select name="page_num" id="page_num" class="za_select" onchange="GetVideoList()">
                                <option value="20">20条</option>
                                <option value="30">30条</option>
                                <option value="50" >50条</option>
                                <option value="100">100条</option>
                            </select>
                            頁 ：
                            <select id="page" name="page" onchange="GetVideoList()" class="za_select">
                                <option value="1">1</option>
                            </select>
                        </div>
                        <div class="MPanel" style="display: block;">
                            <table class="MMain" border="1">
                                <thead>
                                    <tr class="m_title">
                                        <td class="" width="146px">时间</td>
                                        <!-- <td>游戏ID</td> -->
                                        <td class="" width="108px">订单号</td>
                                        <td class="" width="101px">局号</td>
                                        <td class="" width="34px">桌号</td>
                                        <!-- <td>场次</td> -->
                                        <td class="" width="64px">视讯类别</td>
                                        <td class="" width="49px">总投注</td>
                                        <td class="" width="64px">有效投注</td>
                                        <!-- <td>下注点</td> -->
                                        <td class="" width="35px">盈利</td>
                                    </tr>
                                </thead>
                           
                                <tbody id="ListData">
                                    
                                </tbody>
                                 <tfoot>
                                    <tr class="m_rig" style="background-Color:#EBF0F1">
                                        <td colspan="4" align="right" class="">&nbsp;小计：</td>
                                        <td class=""><span id="Nums">0</span>筆</td>
                                        <td class=""><span id="BetMoneyAll" class="CountMoney">0</span></td>
                                        <td class=""><span id="ValidBetMoneyAll" class="CountMoney">0</span></td>
                                        <!-- <td></td> -->
                                        <td class=""><span id="ResultMoneyAll" class="CountMoney">0</span></td>
                                    </tr>
                                    <tr class="m_rig" style="background-Color:#EBF0F1">
                                        <td colspan="4" align="right" class="">&nbsp;总计：</td>
                                        <td class=""><span id="NumsAll">0</span>笔</td>
                                        <td class=""><span id="BetMoneyAll_" class="CountMoney">0</span></td>
                                        <td class=""><span id="ValidBetMoneyAll_" class="CountMoney">0</span></td>
                                        <!-- <td></td> -->
                                        <td class=""><span id="ResultMoneyAll_" class="CountMoney">0</span></td>
                                    </tr>
                                </tfoot>
                            </table>
                            <table width="780" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr><td height="15"></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

<script  type="text/javascript" src="../public/js/jquery-1.8.3.min.js"></script>
<script>
function GetVideoList(){
    var html='';
    $('.CountMoney').html(0.00)
    var Company=$('#Company').val()
    var VideoType=$('#VideoType').val()
    var s_time=$('#s_time').val();
    var e_time=$('#e_time').val();
    var username=$('#username').val()
    var page_num=$('#page_num').val()
    var Page=$('#page :selected').val()
    var OrderId=$('#OrderId').val()
    var request = 'OrderId='+OrderId+'&g_type='+Company+'&VideoType='+VideoType+'&S_Time='+s_time+'&E_Time='+e_time+'&UserName='+username+'&Page_Num='+page_num+'&Page='+Page;
    $.ajax({
        type: "GET",
        url: 'getbetrecord.php',
        data: request,
        dataType: "json",
        beforeSend: function(){
            $('#ListData').html('<tr><td colspan="8" align="center">loading.....</td></tr>');
        },
        success: function(msg){
            if(msg.data['Code']==10022){
                $('#ListData').html('<tr><td colspan="8" align="center">暂无数据</td></tr>');
                $('#BetMoneyAll').html(0);
                $('#ValidBetMoneyAll').html(0);
                $('#ResultMoneyAll').html(0);
                $('#BetMoneyAll_').html(0);
                $('#ValidBetMoneyAll_').html(0);
                $('#ResultMoneyAll_').html(0);
                $('#Nums').html(0);
                $('#NumsAll').html(0);
            }else if(msg.data['Code']==10021){
                $.each(msg.data['data'],function(i,v){
                html+='<tr class="m_title">' +
                    '<td>'+ v.OrderTime+'</td>' +
                    '<td>'+ v.OrderId+'</td>' +
                    '<td>'+ v.OrderNum+'</td>' +
                    '<td>'+ v.DeskNum+'</td>' +
                    /*'<td>'+ v.GameId+'</td>' +*/
                    /*'<td>'+ v.Account+'</td>' +*/
                    /*'<td>'+ v.Number+'</td>' +*/
                    /*'<td>'+ v.GameResult+'</td>' +*/
                    '<td>'+ v.BetType+'</td>' +
                    '<td>'+ v.BetMoney+'</td>' +
                    '<td>'+ v.ValidBetMoney+'</td>' +
                    '<td>'+ v.ResultMoney+'</td>' +
                    '</tr>';
                })
                var Page=msg.data['Page'];
                var Nums=msg.data['Nums'];
                var ThisPage=msg.data['ThisPage'];
                var PageHtml='';
                Page=Math.ceil(Nums/Page)
                if (Page<=0) Page=1;
                for(i=1;i<=Page;i++){
                    if(i==ThisPage) PageHtml+='<option selected>'+i+'</option>';
                    else PageHtml+='<option>'+i+'</option>';
                }
                $('#page').html(PageHtml);
                $('#ListData').html(html);
                $('#BetMoneyAll').html(msg.data['BetMoneyAll']);
                $('#ValidBetMoneyAll').html(msg.data['ValidBetMoneyAll']);
                $('#ResultMoneyAll').html(msg.data['ResultMoneyAll']);
                $('#BetMoneyAll_').html(msg.data['BetMoneyAll_']);
                $('#ValidBetMoneyAll_').html(msg.data['ValidBetMoneyAll_']);
                $('#ResultMoneyAll_').html(msg.data['ResultMoneyAll_']);
                $('#Nums').html(msg.data['data'].length);
                $('#NumsAll').html(msg.data['Nums']);
            }else{
                $('#ListData').html('<tr><td colspan="8" align="center">暂无数据</td></tr>');
                $('#BetMoneyAll').html(0);
                $('#ValidBetMoneyAll').html(0);
                $('#ResultMoneyAll').html(0);
                $('#BetMoneyAll_').html(0);
                $('#ValidBetMoneyAll_').html(0);
                $('#ResultMoneyAll_').html(0);
                $('#Nums').html(0);
                $('#NumsAll').html(0);
            }
        }
    });
}
GetVideoList();
</script>
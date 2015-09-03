<?php
    include_once "../../include/config.php";
    include_once "../../common/login_check.php";
    include_once "../../class/user.php";

    $userinfo = user::getinfo($_SESSION["uid"]);
    $username = @$_SESSION['username'];

    $MinInMoney = 10;
    $allmoney = $userinfo["money"] + $userinfo["ag_money"] + $userinfo["og_money"] + $userinfo["mg_money"] + $userinfo["ct_money"] + $userinfo["bbin_money"] + $userinfo["lebo_money"];
    $allmoney = number_format($allmoney, '2');

    if ($userinfo['shiwan'] == 1) {
        echo "<script>";
        echo 'alert("试玩账号不能存取款，请注册正式账号！")';
        echo "</script>";
        exit();
    }
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>额度转换</title>
        <link rel="stylesheet" href="../public/css/index_main.css" />
        <link rel="stylesheet" href="../public/css/standard.css" />
        <script language="javascript" type="text/javascript" src="../public/js/jquery-1.8.3.min.js"></script>
        <script language="javascript" type="text/javascript" src="../public/js/jquery.blockUI.min.js"></script>
    </head>
    <body  style="BACKGROUND: url(../public/images/content_bg.jpg) repeat-y left top;" >
        <div id="MAMain" style="width:767px">
            <div id="MACenter-content">
                <div id="MACenterContent">
                    <div id="MNav">
                        <span class="mbtn"><a  class="mbtn" target="k_memr"   href="./zr_money.php" >额度转换</a></span>
                        <div class="navSeparate"></div>
                        <a  target="k_memr" href="./set_money.php" class="mbtn">线上存款</a>
                        <div class="navSeparate"></div>
                        <a   target="k_memr" href="./get_money.php" class="mbtn">线上取款</a>
                    </div>
                    <div id="MMainData" style="margin-top: 8px;">
                        <h2 class="MSubTitle"><?=$userinfo["username"]?>目前额度</h2>
                        <div id="form1" method="post" name="form1" action="">
                            <input type="hidden" name="method" value="post" />
                                <table class="MMain" border="1" style="margin-bottom: 8px;">
                                <thead>
                                    <tr>
                                        <th nowrap="">币别</th>
                                        <th nowrap="">系统余额</th>
                                        <th nowrap="">AG余额</th>
                                        <th nowrap="">OG余额</th>
                                        <th nowrap="">MG余额</th>
                                        <th nowrap="">CT余额</th>
                                        <th nowrap="">BBIN余额</th>
                                        <th nowrap="">LEBO余额</th>
                                        <th>合计</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align: center;" class="">人民幣(RMB)</td>
                                        <td style="text-align: center;" id="local_money" class="MNumber"><?=$userinfo["money"]?></td>
                                        <td style="text-align: center;" id="ag_money" class=""><?=$userinfo["ag_money"]?></td>
                                        <td style="text-align: center;" id="og_money" class=""><?=$userinfo["og_money"]?></td>
                                        <td style="text-align: center;" id="mg_money"><?=$userinfo["mg_money"]?></td>
                                        <td style="text-align: center;" id="ct_money"><?=$userinfo["ct_money"]?></td>
                                        <td style="text-align: center;" id="bbin_money"><?=$userinfo["bbin_money"]?></td>
                                        <td style="text-align: center;" id="lebo_money"><?=$userinfo["lebo_money"]?></td>
                                        <td style="text-align: center;" id="allmoney" class=""><?=$allmoney?>&nbsp;RMB</td>
                                    </tr>
                                </tbody>
                            </table>
                            <h2 class="MSubTitle">额度转换</h2>
                            <table class="MMain MNoBorder" style="width: auto;">
                                <tbody>
                                    <tr>
                                        <td align="right" class="">转出：</td>
                                        <td class="">
                                            <select name="trtype1" id="trtype1">
                                                <option value="sport">系统余额</option>
                                                <option value="ag">AG游戏厅</option>
                                                <option value="og">OG游戏厅</option>
                                                <option value="mg">MG游戏厅</option>
                                                <option value="ct">CT游戏厅</option>
                                                <option value="bbin">BBIN游戏厅</option>
                                                <option value="lebo">LEBO游戏厅</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right" class="">转入：</td>
                                        <td class="">
                                            <select name="trtype2" id="trtype2">
                                                <option value="sport">系统余额</option>
                                                <option value="ag">AG游戏厅</option>
                                                <option value="og">OG游戏厅</option>
                                                <option value="mg">MG游戏厅</option>
                                                <option value="ct">CT游戏厅</option>
                                                <option value="bbin">BBIN游戏厅</option>
                                                <option value="lebo">LEBO游戏厅</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right" class="">金额：</td>
                                        <td class="">
                                            <INPUT id="p3_Amt"
                                            onkeyup="clearNoNum(this);" size="15" type="text" name="p3_Amt">&nbsp;&nbsp;<span style="color:#FF0000">转换金额不能小于<?=$MinInMoney?>元</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td nowrap="" class=""></td>
                                        <td class="">
                                            <INPUT id="SubTran" class="MBtnStyle"  value="确认转账" <?php if ($_SESSION['shiwan'] == 2) {?>type="button" onclick="alert('试玩账号不能进行额度转换，请使用正式账号！');"<?php } else {?> type="button" <?php }?> name="SubTran">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div><br>
                        <input type="hidden" id="ag" value="<?=$userinfo['ag_money']?>" />
                        <input type="hidden" id="og" value="<?=$userinfo['og_money']?>" />
                        <input type="hidden" id="mg" value="<?=$userinfo['mg_money']?>" />
                        <input type="hidden" id="ct" value="<?=$userinfo['ct_money']?>" />
                        <input type="hidden" id="lebo" value="<?=$userinfo['lebo_money']?>" />
                        <input type="hidden" id="bbin" value="<?=$userinfo['bbin_money']?>" />
                        <input type="hidden" id="sport" value="<?=$userinfo['money']?>" />

                        <input type="hidden" id="ag_info" value="<?=$userinfo['ag_money']?>" />
                        <input type="hidden" id="og_info" value="<?=$userinfo['og_money']?>" />
                        <input type="hidden" id="mg_info" value="<?=$userinfo['mg_money']?>" />
                        <input type="hidden" id="ct_info" value="<?=$userinfo['ct_money']?>" />
                        <input type="hidden" id="lebo_info" value="<?=$userinfo['lebo_money']?>" />
                        <input type="hidden" id="bbin_info" value="<?=$userinfo['bbin_money']?>" />
                        <p style="margin-left:10px;">
                            <span style="color:#ff0000;"><strong>户内转账说明</strong></span>
                        </p><br>
                        <p style="line-height:18px;margin-left:10px;">
                            <span style="color:#ff0000;"><strong>1、户内最低转帐金额<?=$MinInMoney?>人民币，最高转帐金额不限。</strong></span>
                        </p>
                        <p style="line-height:18px;margin-left:10px;">
                            <span style="color:#ff0000;"><strong>2、户内转帐不收任何手续费。</strong></span>
                        </p>
                        <p style="line-height:18px;margin-left:10px;">
                            <span style="color:#ff0000;"><strong>3、如果有任何疑问请咨询24小时在线客服。</strong></span>
                        </p>
                        <p style="line-height:18px;margin-left:10px;">
                            <span style="color:#ff0000;"><strong>提示：系统额度可以下注体育娱乐、彩票游戏。真人娱乐场需转入额度。</strong></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

    <script language="JAVAScript">
        //数字验证 过滤非法字符
        function clearNoNum(obj) {
            obj.value=obj.value.replace(/[^\d+]/g,'');
        }

        //去掉空格
        function check_null(string) {
            var i = string.length;
            var j = 0;
            var k = 0;
            var flag = true;
            while (k < i) {
                if (string.charAt(k) != " ")
                j = j + 1;
                k = k + 1;
            }
            if (j == 0) {
                flag = false;
            }
            return flag;
        }
    </script>

    <script type="text/javascript">
$(document).ready(function () {
  var local_money=$(".MMain #local_money").html();
  var og_money=$(".MMain #og_money").html();
  var ct_money=$(".MMain #ct_money").html();
  var ag_money=$(".MMain #ag_money").html();
  var mg_money=$(".MMain #mg_money").html();
  var lebo_money=$(".MMain #lebo_money").html();
  var bbin_money=$(".MMain #bbin_money").html();
//动态加载
//动态加载
$.ajax({
     type: 'GET',
     url: '/video/games/getallbalance.php?action=save',
     dataType: "json",
     beforeSend: function(){
            var nr="<img src='../public/images/load_pk.gif'/>";
            $(".MMain #local_money").html(nr);
            $(".MMain #og_money").html(nr);
            $(".MMain #ct_money").html(nr);
            $(".MMain #ag_money").html(nr);
            $(".MMain #mg_money").html(nr);
            $(".MMain #bbin_money").html(nr);
            $(".MMain #lebo_money").html(nr);
            $(".MMain #allmoney").html(nr);
            
        },
     success: function (rdata) {
      if(rdata.error){
        alert(rdata.error);
        window.location.href = 'zr_money.php';
      }else if(rdata.data.Code == '10017'){
          $(".MMain #local_money").html(parseFloat(local_money).toFixed(2));
          if(rdata.data.ogstatus){
            $(".MMain #og_money").html(parseFloat(rdata.data.ogbalance).toFixed(2));
            $("#og").val(parseFloat(rdata.data.ogbalance).toFixed(2));
          }else{
            $(".MMain #og_money").html(parseFloat(og_money).toFixed(2));
            $("#og").val(parseFloat(og_money).toFixed(2));
          }
          if(rdata.data.ctstatus){
            $(".MMain #ct_money").html(parseFloat(rdata.data.ctbalance).toFixed(2));
            $("#ct").val(parseFloat(rdata.data.ctbalance).toFixed(2));
          }else{
            $(".MMain #ct_money").html(parseFloat(ct_money).toFixed(2));
            $("#ct").val(parseFloat(ct_money).toFixed(2));
          }
          if(rdata.data.agstatus){
            $(".MMain #ag_money").html(parseFloat(rdata.data.agbalance).toFixed(2));
            $("#ag").val(parseFloat(rdata.data.agbalance).toFixed(2));
          }else{
            $(".MMain #ag_money").html(parseFloat(ag_money).toFixed(2));
            $("#ag").val(parseFloat(ag_money).toFixed(2));
          }
          if(rdata.data.mgstatus){
            $(".MMain #mg_money").html(parseFloat(rdata.data.mgbalance).toFixed(2));
            $("#mg").val(parseFloat(rdata.data.mgbalance).toFixed(2));
          }else{
            $(".MMain #mg_money").html(parseFloat(mg_money).toFixed(2));
            $("#mg").val(parseFloat(mg_money).toFixed(2));
          }
          if(rdata.data.lebostatus){
            $(".MMain #lebo_money").html(parseFloat(rdata.data.lebobalance).toFixed(2));
            $("#lebo").val(parseFloat(rdata.data.lebobalance).toFixed(2));
          }else{
            $(".MMain #lebo_money").html(parseFloat(lebo_money).toFixed(2));
            $("#lebo").val(parseFloat(lebo_money).toFixed(2));
          }
          if(rdata.data.bbinstatus){
            $(".MMain #bbin_money").html(parseFloat(rdata.data.bbinbalance).toFixed(2));
            $("#bbin").val(parseFloat(rdata.data.bbinbalance).toFixed(2));
          }else{
            $(".MMain #bbin_money").html(parseFloat(bbin_money).toFixed(2));
            $("#bbin").val(parseFloat(bbin_money).toFixed(2));
          }
          if(rdata.data.aginfo == 9999){
            $(".MMain #ag_money").html('维护');
          }
          if(rdata.data.oginfo == 9999){
            $(".MMain #og_money").html('维护');
          }
          if(rdata.data.mginfo == 9999){
            $(".MMain #mg_money").html('维护');
          }
          if(rdata.data.ctinfo == 9999){
            $(".MMain #ct_money").html('维护');
          }
          if(rdata.data.bbininfo == 9999){
            $(".MMain #bbin_money").html('维护');
          }
          if(rdata.data.leboinfo == 9999){
            $(".MMain #lebo_money").html('维护');
          }
          $('#ag_info').val(parseFloat(rdata.data.aginfo).toFixed(2));
          $('#og_info').val(parseFloat(rdata.data.oginfo).toFixed(2));
          $('#mg_info').val(parseFloat(rdata.data.mginfo).toFixed(2));
          $('#ct_info').val(parseFloat(rdata.data.ctinfo).toFixed(2));
          $('#bbin_info').val(parseFloat(rdata.data.bbininfo).toFixed(2));
          $('#lebo_info').val(parseFloat(rdata.data.leboinfo).toFixed(2));
         countallmoney();
      }
    }
});

function countallmoney(){
    allm = parseFloat($("#sport").val()) + parseFloat($("#ag").val()) + parseFloat($("#og").val());
    allm = allm  + parseFloat($("#ct").val()) + parseFloat($("#mg").val())+parseFloat($("#bbin").val())+parseFloat($("#lebo").val());
    $(".MMain #allmoney").html(parseFloat(allm).toFixed(2)+" RMB")
}
});

$("#SubTran").click(function(){
    edzh();
})
$("#form1").keypress(function(e) {  
    // 回车键事件  
       if(e.which == 13) {  
   edzh();  
       }  
   }); 
function edzh(){

            var trtype1 = $('#trtype1').val();
            var trtype2 = $('#trtype2').val();
            var p3_Amt  = parseInt($('#p3_Amt').val());
            var ag      = parseInt($('#ag').val());
            var og      = parseInt($('#og').val());
            var mg      = parseInt($('#mg').val());
            var ct      = parseInt($('#ct').val());
            var bbin    = parseInt($('#bbin').val());
            var lebo    = parseInt($('#lebo').val());
            var sport   = parseInt($('#sport').val());
            var ag_info = parseInt($('#ag_info').val());
            var og_info = parseInt($('#og_info').val());
            var mg_info = parseInt($('#mg_info').val());
            var ct_info = parseInt($('#ct_info').val());
            var bbin_info = parseInt($('#bbin_info').val());
            var lebo_info = parseInt($('#lebo_info').val());
            if(trtype1==trtype2){
                alert("转入转出平台不能相同，请重新选择！");
                return false;
            }
            if(trtype1!='sport'&&trtype2!='sport'){
                alert("额度转换,只能在系统余额和视讯余额之间转换\n 视讯余额之间不能直接转换!");
                return false;
            }
            if(p3_Amt==''){
                alert("请输入转换金额！");
                $('#p3_Amt').focus();
                return false;
            }
            if (p3_Amt != "") {
                if (p3_Amt < parseInt(<?=$MinInMoney?>))
                {
                    alert("转换金额不能小于<<?=$MinInMoney?>元！")
                    $('#p3_Amt').focus();
                    return false;
                }
            }
          
            if((trtype1 == 'ag' || trtype2 == 'ag') && ag_info == 9999){
                alert("    AG游戏正在进行例行维护！\n请您选择其他游戏！祝您游戏开心！")
                $('#p3_Amt').focus();
                return false;
            }
            if((trtype1 == 'og' || trtype2 == 'og') && og_info == 9999){
                alert("    OG游戏正在进行例行维护！\n请您选择其他游戏！祝您游戏开心！")
                $('#p3_Amt').focus();
                return false;
            }
            if((trtype1 == 'mg' || trtype2 == 'mg') && mg_info == 9999){
                alert("    MG游戏正在进行例行维护！\n请您选择其他游戏！祝您游戏开心！")
                $('#p3_Amt').focus();
                return false;
            }
            if((trtype1 == 'ct' || trtype2 == 'ct') && ct_info == 9999){
                alert("    CT游戏正在进行例行维护！\n请您选择其他游戏！祝您游戏开心！")
                $('#p3_Amt').focus();
                return false;
            }
            if((trtype1 == 'lebo' || trtype2 == 'lebo') && lebo_info == 9999){
                alert("    LEBO游戏正在进行例行维护！\n请您选择其他游戏！祝您游戏开心！")
                $('#p3_Amt').focus();
                return false;
            }
            if((trtype1 == 'bbin' || trtype2 == 'bbin') && bbin_info == 9999){
                alert("    BBIN游戏正在进行例行维护！\n请您选择其他游戏！祝您游戏开心！")
                $('#p3_Amt').focus();
                return false;
            }
            if(trtype1!='sport'){
              switch(trtype1)
                {
                case 'ag':
                  if(p3_Amt>ag){
                    alert("转换金额不能大于AG余额>"+ag+"元！")
                    $('#p3_Amt').focus();
                    return false;
                  }
                  break;
                case 'og':
                  if(p3_Amt>og){
                    alert("转换金额不能大于OG余额>"+og+"元！")
                    $('#p3_Amt').focus();
                    return false;
                  }
                  break;
                case 'mg':
                  if(p3_Amt>mg){
                    alert("转换金额不能大于MG余额>"+mg+"元！")
                    $('#p3_Amt').focus();
                    return false;
                  }
                break;
                  case 'bbin':
                  if(p3_Amt>bbin){
                    alert("转换金额不能大于BBIN余额>"+bbin+"元！")
                    $('#p3_Amt').focus();
                    return false;
                  }
                  break;
                case 'ct':
                  if(p3_Amt>ct){
                    alert("转换金额不能大于CT余额>"+ct+"元！")
                    $('#p3_Amt').focus();
                    return false;
                  }
                  break;
                case 'lebo':
                  if(p3_Amt>lebo){
                    alert("转换金额不能大于LEBO余额>"+lebo+"元！")
                    $('#p3_Amt').focus();
                    return false;
                  }
                  break;
                }
              
            }
            if(trtype2!='sport'){
              if(p3_Amt>sport){
                alert("转换金额不能大于系统额度余额>"+sport+"元！")
                $('#p3_Amt').focus();
                return false;
              }
            }

            $.ajax({
                type: "POST",
                url: "ed.php?action=save",
                beforeSend: function(){
                    $('body').prepend('<div id="xxoo"><img src="../public/images/ajax-loader-white.gif" id="xxoo1"/></div>');
                    $('#xxoo').css({ 
                        padding:        0,
                        margin:         0,
                        width:          '100%', 
                        height:         '100%', 
                        top:            '0', 
                        left:           '0', 
                        textAlign:      'center', 
                        color:          '#000', 
                        border:         'none',
                        "position":     "absolute",
                        "z-index":      1000,
                        "opacity":      0.5,
                        "background-color": "#000000"
                    }); 
                    $('#xxoo1').css({
                        'margin-top':   '23%'
                    });
                },
                data: "trtype1="+trtype1+"&trtype2="+trtype2+"&p3_Amt="+p3_Amt,
                dataType: 'json',
                success: function(msg){
                 $('#xxoo').remove();
                  if(msg.status==16){
                    alert('转换成功!');
                    window.location.href="zr_money.php";
                  }else{
                    alert(msg.info);
                  }
                }
            });
}
</script>
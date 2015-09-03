<?php //ini_set("display_errors","on");
ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(0);
header("Content-type: text/html; charset=utf-8");
include_once("../../include/config.php");
include_once("../common/login_check.php");
include("../../class/Level.class.php");
include("../../P_admin/Lottery/GetOdds.php");
$CpType= new CaiPaioType();
$CpIdName= $CpType->Cp;
?>
<?php $title="彩票詳細注單"; require("../common_html/header.php");?>
<body>
<div id="con_wrap">
    <form name="myFORM" id="myFORM" action="<?=$_SERVER["REQUEST_URI"]?>" method="get">
        <div class="input_002">彩票詳細注單</div>
        <div class="" style="height:60px; width: 1100px; border: #fff333 solid 0px;">
            類型：
            <select id="cp_type" name="gtype"  class="za_select ChangeInput" onchange="GetFcList(1)">
                <option value='0'>所有彩种</option>
                <?
                foreach($CpIdName as $k =>$r){
                    echo "<option value='$k'>$r</option>";
                }
                ?>
            </select>
            结算狀態：
            <select id="status" name="status"class="za_select  ChangeInput">
                <option value="-1">全部</option>
                <option value="0">未結算</option>
                <option value="1">已結算</option>
                <option value="2">已取消</option>
            </select>
            <span id="spanstatustype">
            输赢：
            <select id="statustype" name="statustype"class="za_select  ChangeInput">
                <option value="-1">全部</option>
                <option value="1">赢</option>
                <option value="2">输</option>
                <option value="3">和</option>
            </select></span>
            &nbsp;&nbsp;日期：
            <input id="date_start" class="za_text Wdate" type="text" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})"   value="<?if(@$_GET['date_start']){echo $_GET['date_start'];}else{echo date('Y-m-d');}?>" name="date_start">
            <input type="text" name="date_end" id="date_end" value="<?if($_GET['date_end']){echo $_GET['date_end'];}else{echo date('Y-m-d');}?>"  size="10"  class="za_text Wdate" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})">
            注单号：
            <input name="order" type="text" id="order" class="za_text" style="width:80px;min-width:80px" size="15" >

            会员帐号：
            <input name="username" type="text" id="username" class="za_text" style="width:80px;min-width:80px" size="15" value="<?if(@$_GET['username']){echo $_GET['username'];}?>">
            大於此金額：<input type="TEXT" name="money" id="money" value="" size="10" maxlength="10" class="za_text" style="width:50px;min-width:50px">
            <span  value="查詢" onclick="GetFcList()" class="button_d">查询</span>
            </br>
            重新整理：
            <select name="reload" id="reload" onchange="timeout(this.value);">
                <option value="">不自動更新</option>
                <option value="5">5秒</option>
                <option value="10">10秒</option>
                <option value="15">15秒</option>
                <option value="30">30秒</option>
                <option value="60">60秒</option>
                <option value="120">120秒</option>
            </select>
            每页记录数：
            <select name="page_num" id="page_num" class="za_select" onchange="$('#page').val(1);GetFcList()">
                <option value="20" >20条</option>
                <option value="30" >30条</option>
                <option value="50" >50条</option>
                <option value="100">100条</option>
            </select>
            &nbsp;頁數：
            <select id="page" name="page" class="za_select" onchange="GetFcList()">

            </select>
            <span id="lblTime" style="color:red"></span>
        </div>
    </form></div>
<div class="content" id="show">	<table class="m_tab" cellspacing="0" cellpadding="0" border="0">
        <tbody><tr class="m_title_over_co">
            <td align="center">共有 <span class="nums">0</span> 條記錄&nbsp;&nbsp;</td>
        </tr>
        </tbody></table>
    <table width="1090" border="0" cellspacing="0" cellpadding="0" class="m_tab" bgcolor="#000000">
        <tbody>
        <tr class="m_title_over_co">
            <td width="30">序號</td>
            <td width="70">下單時間</td>
            <td width="">所屬上線</td>
            <td width="">注單號</td>
            <td width="70">下注帐号</td>
            <td width="130">類型</td>
            <td width="330">內容</td>
            <td width="90">下注金額</td>
            <td width="60">可赢金额</td>
            <td width="90">結果</td>
        </tr>
        </tbody>
        <tbody id="fc_list">

        </tbody>
        <tbody>
        <tr class="m_cen" style="background-Color:#EBF0F1">
            <td colspan="6" style="text-align:right">&nbsp;小計：</td>
            <td align="center"><span class="IdCount"></span>笔</td>
            <td align="center"><span class="Money">0</span></td>
            <td align="center"></td>
            <td align="center"><span class="WinMoney">0</span></td>
        </tr>
        <tr class="m_cen" style="background-Color:#EBF0F1">
            <td colspan="6" style="text-align:right">&nbsp;總計：</td>
            <td align="center"><span class="nums">0</span>笔</td>
            <td align="center"><span class="AllMoney">0</span></td>
            <td align="center"> </td>
            <td align="center"><span class="AllWin">0</span></td>
        </tr>
        </tbody></table>
</div>
</body>
</html>
<script>
    var i='<?=$_GET["reload"]?>';
    if(i==''){
        var i=0;
    }
    $(document).ready(function(){
        if(i!=0){

            timeout(i);
        }
    });
    var t;
    function timeout(time){
        clearTimeout(t);
        i = time;
        refresh();
    }
    function refresh(){
        if($('#reload').val()=='') {
            $("#lblTime").hide();
            return false;
        }
        if(i <=0){
            GetFcList();
            i=$('#reload').val();
        }else{
            $('#lblTime').html('还有'+i+'秒更新');
            i--;
        }
        t=setTimeout("refresh()",1000);
    }
    function GetFcList(){
        $(".nums").html(0);
        $(".AllMoney").html(0);
        $(".AllWin").html(0);
        $(".Money").html(0);
        $(".WinMoney").html(0);
        $(".IdCount").html(0);
        $('#fc_list').html('<tr><td colspan="10" style="text-align: center">Loading</td></tr>')
        var cp_type=$("#cp_type :selected").val();
        var status=$("#status").val();
        var statustype=$("#statustype").val();
        var date_start=$("#date_start").val();
        var date_end=$("#date_end").val();
        var username=$("#username").val();
        var money=$("#money").val();
        var p = $('#page :selected').val();
        var page_num = $('#page_num :selected').val();
        var LastId = $("#LastId").html();
        var Order = $("#order").val();
        var html='';
        if(LastId==null)LastId='';
        if(status==1){
            $('#spanstatustype').show()
        }
        else{
            $('#spanstatustype').hide()
            statustype='';
        }
        //  alert(LastId)
        $.post("fc_data.php",{action:'getlist',statustype:statustype,Order:Order,p:p,money:money,nums:page_num,status:status,LastId:LastId,date_start:date_start,date_end:date_end,username:username,cp_type:cp_type},function(d){
            var Money= 0;
            var WinMoney=0;
            if(d['data']){
                var IdCount=d['data'].length;
                $.each(d['data'],function(i,v){
                    // if(v.js)
                    status='';
                    if(v.status==0) status='未结算';
                    if(v.status==1) status='<span style="color:#FF0000;">赢</span>';
                    if(v.status==2) status='<span style="color:#00CC00;">输</span>';
                    if(v.status==3) status='<span>和</span>';
                    if(v.status==4) status='<span>取消注单</span>';
                    html+='<tr class="m_cen">'+
                        '<td width="30" '+(i==(IdCount-1)?'id="LastId"':'')+'>'+ v.id+'</td>'+
                        ' <td width="70">'+ v.addtime+'</td>'+
                        ' <td width="">'+
                        '<span style="float:left;text-align:right;width:50%">股东：</span><span style="float:left;text-align:left;width:50%">'+ v.s_h+'</span>'+
                        '<span style="float:left;text-align:right;width:50%">總代理：</span><span style="float:left;text-align:left;width:50%">'+ v.u_a+'</span>'+
                        '<span style="float:left;text-align:right;width:50%">代理商：</span><span style="float:left;text-align:left;width:50%">'+ v.a_t+'</span>'+
                        '</td>'+
                        '<td width="70">'+ v.did+'</td>'+
                        '<td width="70">'+ v.username+'<Br>'+ v.balance+'</td>'+
                        '<td width="130">'+ v.type+'</td>'+
                        '<td width="330">期数：'+ v.qishu+'<Br>'+ v.mingxi_1+''+(v.mingxi_3?' - '+v.mingxi_3+'<br> ':'')+
                        ' <b><font color="#ff0000">'+ v.mingxi_2+'</font></b>@<b><font color="#ff0000">'+ v.odds+'</font></b></td>'+
                        ' <td width="90">'+ v.money+'</td>'+
                        ' <td width="60"> '+ ((v.money* (v.odds-1)).toFixed(2))+'</td>'+
                        ' <td width="90">'+status+'<Br>'+ (parseFloat(v.win).toFixed(2))+'</td>'+
                        '</tr>';
                    Money   +=   parseFloat(v.money)
                    WinMoney+=   parseFloat(v.win)
                })
            }
            $("#fc_list").html(html);
            $(".IdCount").html(IdCount);
            $("#page").html(d['page']);
            $(".nums").html(d['num']);
            $(".AllMoney").html(parseFloat(d['AllMoney']).toFixed(2));
            $(".AllWin").html(parseFloat(d['AllWin']).toFixed(2));
            $(".Money").html(Money.toFixed(2));
            $(".WinMoney").html(WinMoney.toFixed(2));

        },'json').error(function(XMLHttpRequest, textStatus, errorThrown) {

            var headers=XMLHttpRequest.getAllResponseHeaders();
            var timeout='';
            if(XMLHttpRequest.getResponseHeader("Connection")=="close")  timeout=" :加载超时,服务器连接中断!";
            html+="<tr><td colspan='10' class='m_cen'>数据加载错误"+timeout+"</td></tr>";
            html+="<tr><td colspan='10' class='m_cen'>"+headers+"</td></tr>";
            html+="<tr><td colspan='10' class='m_cen'>"+'返回状态：'+XMLHttpRequest.status+' =>'+textStatus+'： '+errorThrown+"</td></tr>";
            html+="</table>";
            $('#fc_list').html(html);
        })
    }
    $('.ChangeInput').change(function(){
        GetFcList(1);
    });
    GetFcList(1);
</script>
<?php require("../common_html/footer.php");?>

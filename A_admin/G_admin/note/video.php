<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once dirname(__FILE__) . "/../../lib/video/GameType.php";

$title="視訊詳細注單";
require("../common_html/header.php");
?>

<style>
    #ListData td{ text-align: center;}
</style>
<script>

function SeachVideoGameSelect(){
    var GameTypeData =<?=PrintJs()?>;
    var Company=$('#Company').val();
    var SeachVideoGameSelect=$('#SeachVideoGameSelect').val();
    var VideoGameTypeSelect='<option value="">全部</option>';
    $.each(GameTypeData,function(i,v){
        if(i==SeachVideoGameSelect){
            $.each(v,function(a,b){
                if(a==Company){
                    $.each(b,function(e,f){
                        VideoGameTypeSelect+='<option value="'+e+'">'+f['Name']+'</option>';
                    })
                }
            })
        }
    })
   // alert(VideoGameTypeSelect)
    if(SeachVideoGameSelect=='video') {
        $('#VideoType').show()
        $('#VideoType').html(VideoGameTypeSelect)
        $('#GameType').hide()
        $('#GameType').html('')
    }
    else if(SeachVideoGameSelect=='game') {
        $('#GameType').show()
        $('#GameType').html(VideoGameTypeSelect)
        $('#VideoType').hide()
        $('#VideoType').html('')

    }
    else{
        $('#VideoType,#GameType').hide()
        $('#GameType,#VideoType').html('')
    }

}

</script>
<body>
<div  id="con_wrap">
  <div  class="input_002">视讯下注記錄</div>
  <div  style="float: left; line-height: 30px; margin-left: 10px;">
        <span class="Company CompanyOn" data="lebo">LEBO</span>
        <span class="Company" data="bbin">BBIN</span>
        <span class="Company" data="mg">MG</span>
        <span class="Company" data="ct">CT</span>
        <span class="Company" data="ag">AG</span>
        <span class="Company" data="og">OG</span>
        <style>
            .Company{cursor: pointer;}
            .Company,.CompanyOn{  padding:3px 5px; margin: 0px 5px 0px 0px;
            }
            .CompanyOn{background: #bc5a83; color: #ffffff}
        </style>
      <input type="hidden" id="Company" value="Lebo">
      &nbsp;&nbsp;視訊/电子：
      <select id="SeachVideoGameSelect" onchange="SeachVideoGameSelect()">
          <option  value="">全部</option>
          <option  value="video">视讯</option>
          <option  value="game">电子</option>
      </select>
      <select  id="VideoType"  name="VideoType" style="display: none">

      </select>
      <select  id="GameType"  name="GameType" style="display: none">

      </select>
      <script>

    $('.Company').click(function(){
        $('.Company').removeClass('CompanyOn');
        $(this).addClass('CompanyOn');
        $('#Company').val($(this).attr('data'))
        SeachVideoGameSelect()
        $('#page').val('1')
        $('#page_num').val('20')
        GetVideoList()
    })


</script>
      </div>
    <div style="clear: both"></div>
    <div>
        每页记录数：
        <select name="page_num" id="page_num" class="za_select" onchange="$('#page').val(1)">
            <option value="20">20条</option>
            <option value="30">30条</option>
            <option value="50">50条</option>
            <option value="100">100条</option>
        </select>
        &nbsp;頁數：
        <select id="page" name="page" class="za_select" onchange="GetVideoList()">
            <option>1</option>
        </select>
	&nbsp;&nbsp;日期：
    <input onClick="WdatePicker()" value="<?=date('Y-m-d')?>" size="10" id="s_time" name="s_time">
	 -
	 <input onClick="WdatePicker()" value="<?=date('Y-m-d')?>" size="10" id="e_time" name="e_time">
        会员帐号：<input  name="username"  type="text"  id="username"    style="width:100px"  value="">
        注单号：<input  name="OrderId"  type="text"  id="OrderId"    style="width:100px"  value="">
        <span     class="za_button" onclick="GetVideoList()">確定</span>
	&nbsp;&nbsp;
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
	<span id="lblTime" style="color:red"></span>
</div>
</div>
<div  class="content"  id="show">
<table  border="0"  width="99%"  cellpadding="0"  cellspacing="0"  class="m_tab" style="margin-bottom:-1px;">
    <tbody>
      <tr  class="m_title">
	        <td  width="120">派彩時間</td>
            <td  width="70">注单号</td>
            <td  width="70">局號/单號</td>
            <td  width="70">桌號</td>
            <td  width="70">游戏ID</td>
            <td  width="70">下注帳號</td>
            <td  width="70">期数</td>
            <td  width="70">游戏结果</td>
            <td  width="90">視訊/游戏類別</td>
			<td  width="90">有效投注</td>
			<td  width="90">退水</td>
            <td  width="90">結果</td>
	</tr>
    </tbody>
    <tbody id="ListData">
    </tbody>
    <tbody>
           <tr  class="m_cen"  style="background-Color:#fcdcdc ;display:;">
            <td  colspan="8" style="text-align: right">小計：</td>
               <td><span id="Nums" class="CountMoney">10</span> 笔</td>
               <td><span id="BetMoneyAll" class="CountMoney">0.00</span></td>
               <td><span id="BackMoneyAll" class="CountMoney">0.00</span></td>
               <td><span id="ResultMoneyAll" class="CountMoney">0.00</span></td>
           </tr>
        <tr  class="m_cen"  style="background-Color:#fcdcdc ;display:;">
            <td  colspan="8"   style="text-align: right">总計：</td>
            <td><span id="NumsAll" class="CountMoney">200</span> 笔</td>
            <td><span id="BetMoneyAll_" class="CountMoney">0.00</span></td>
            <td><span id="BackMoneyAll_" class="CountMoney">0.00</span></td>
            <td><span id="ResultMoneyAll_" class="CountMoney">0.00</span></td>
        </tr>
    </tbody>
   </table></div>
	  <div>
	  </div>
</div>
</body>

</html>
<script>
    var ii=null;
    var t;
    function timeout(time){
        clearTimeout(t);
        ii = time;
        refresh();
    }
    function refresh(){

        if($('#reload').val()=='') {
            $("#lblTime").hide();
            return false;
        }
        if(ii <1){
            ii=$('#reload').val();

            GetVideoList();

        }else{
            $('#lblTime').html('还有'+ii+'秒更新');
            ii--;
        }
        t=setTimeout("refresh()",1000);
    }
   //('a.php')


    function GetVideoList(){
        var html='<tr><td colspan="12">数据加载中请等待...</td></tr>'
        $('#ListData').html(html);
        html='';
        $('.CountMoney').html(0.00);
        var Company=$('#Company').val();
        var SeachVideoGameSelect=$('#SeachVideoGameSelect').val();
        var VideoType=$('#VideoType').val();
        var GameType=$('#GameType').val();
        var s_time=$('#s_time').val();
        var e_time=$('#e_time').val();
        var username=$('#username').val();
        var page_num=$('#page_num').val();
        var Page=$('#page :selected').val();
        var OrderId=$('#OrderId').val();
        if(GameType==null) GameType='';
        if(VideoType==null) VideoType='';
        var request = 'OrderId='+OrderId+'&Company='+Company+'&VideoType='+VideoType+'&GameType='+GameType+'&S_Time='+s_time+'&E_Time='+e_time+'&UserName='+username+'&Page_Num='+page_num+'&Page='+Page;
        $.post("../video/getbetrecord.php?"+request,{},function(d){
            if(d['Error']){
                html+='<tr><td colspan="12">'+d['Error']+'</td></tr>'
            }
            else if(d['data']['Code']==10022){
                html+='<tr><td colspan="12">没有数据</td></tr>'
            }
            else if(d['data']['Code']==10006){
                html+='<tr><td colspan="12">此账号数据不存在</td></tr>'
            }
            else if(d['data']['Code']==1){
                html+='<tr><td colspan="12">您的账号已退出，请重新登录！</td></tr>'
            }
            else{
                var VideoData=d['data'];
                $.each(VideoData['data'],function(i,v){
                    html+='<tr>' +
                        '<td>'+ v.OrderTime+'</td>' +
                        '<td>'+ v.OrderNum+'</td>' +
                        '<td>'+ v.OrderId+'</td>' +
                        '<td>'+ v.DeskNum+'</td>' +
                        '<td>'+ v.GameId+'</td>' +
                        '<td>'+ v.Account+'</td>' +
                        '<td>'+ v.Number+'</td>' +
                        '<td>'+ v.GameResult+'</td>' +
                        '<td>'+ v.BetType+'</td>' +
                        '<td>'+ v.BetMoney+'</td>' +
                        '<td>'+ v.BackMoney+'</td>' +
                        '<td>'+ v.ResultMoney+'</td>' +
                        '</tr>';
                })
                var Page=VideoData['Page'];
                var Nums=VideoData['Nums'];
                var ThisPage=VideoData['ThisPage'];
                var PageHtml='';
                    Page=Math.ceil(Nums/Page)
                if (Page<=0) Page=1;
                for(i=1;i<=Page;i++){
                    if(i==ThisPage) PageHtml+='<option selected>'+i+'</option>';
                    else PageHtml+='<option>'+i+'</option>';
                }
                $('#page').html(PageHtml);

                $('#BetMoneyAll').html(VideoData['BetMoneyAll']);
                $('#BackMoneyAll').html(VideoData['BackMoneyAll']);
                $('#ResultMoneyAll').html(VideoData['ResultMoneyAll']);
                $('#BetMoneyAll_').html(VideoData['BetMoneyAll_']);
                $('#BackMoneyAll_').html(VideoData['BackMoneyAll_']);
                $('#ResultMoneyAll_').html(VideoData['ResultMoneyAll_']);
                $('#Nums').html(VideoData['data'].length);
                $('#NumsAll').html(VideoData['Nums']);
            }
            $('#ListData').html(html);

        },'json').error(function(XMLHttpRequest, textStatus, errorThrown) {

            if(XMLHttpRequest.status==200 && $('#ListData td:first').html()=='数据加载中请等待...')
            $('#ListData td:first').html('数据加载失败->'+errorThrown);
        })


          // .jqxhr.complete(function(){ alert("second complete"); })


    }
    GetVideoList();
</script>

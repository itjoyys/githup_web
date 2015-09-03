<?php

include_once("../../include/config.php");
include_once("../common/login_check.php"); 
include_once("../../class/user.php");
include("../../class/Level.class.php");
include_once("../../lib/video/Games.class.php");
include_once("../video/GameType.php");

// var_dump($_GET);
$allagent=M('k_user_agent',$db_config)->where("is_delete=0 and site_id='".SITEID."'")->select();
$ids;//所有代理ID
$user_id;//最终总ID
if(!empty($_GET['gid'])){//总代股东入口
	$idname = M('k_user_agent',$db_config)->field('agent_user')->where("id=$_GET[gid]")->find();
	$ids=implode("|",Level::getChildsId($allagent,$_GET['gid'],'a_t'));
}else if(!empty($_GET['did'])){//代理入口
	$ids = $_GET['did'];
	$idname = M('k_user_agent',$db_config)->field('agent_user')->where("id=$_GET[did]")->find();
}

$user_id;
if(!empty($_GET['uid'])){
	$user_id=$_GET['uid'];
}

if (!empty($user_id)) {
	$user=M('k_user',$db_config)->field("username")->where("uid= $user_id")->find();
	$user = $user['username'];
}

?>
<?php require("../common_html/header.php");?>
<style>
	.m_rig td{text-align: center;}
</style>
<body>
<div id="con_wrap">
  <div class="input_002"><?=$user?><?=$idname['agent_user']?> - <span id="otype">lebo</span>下注記錄</div>
  <div class="con_menu">
<input name="username" type="hidden" id="username" size="15" value="<?=$user?>">
<input name="agentid" type="hidden" id="agentid" size="15" value="<?=$ids?>">
<script>
    window.onload=function(){
        document.getElementById("ttype").onchange=function(){
        
            window.location.href=this.value;
        }
        document.getElementById("page").onchange=function(){
   
        document.getElementById('myFORM').submit();
      }
    }
</script>
<script>

function SeachVideoGameSelect(){
    var GameTypeData =<?=PrintJs()?>;
    var Company=$('#Company').val();
    var SeachVideoGameSelect=$('#SeachVideoGameSelect').val();
    var VideoGameTypeSelect='<option value="all">全部</option>';
    $.each(GameTypeData,function(i,v){
        if(i==SeachVideoGameSelect){
            $.each(v,function(a,b){
                if(a==Company){
                    $.each(b,function(e,f){
                        VideoGameTypeSelect+='<option value="'+e+'">'+f+'</option>';
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
  &nbsp;&nbsp;下注類型：
	<?php include("./common_record.php") ?>
	<span class="Company CompanyOn" data="lebo">Lebo</span>
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
      <input type="hidden" id="Company" value="<?=$_GET[ttype]?>">
	&nbsp;&nbsp;視訊/电子：
      <select id="SeachVideoGameSelect" onchange="SeachVideoGameSelect()">
          <option  value="all">全部</option>
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
        $('#otype').html($(this).attr('data'));
        SeachVideoGameSelect()
        $('#page').val('1')
        $('#page_num').val('20')
        GetVideoList()
    })


</script>
</div>
	<div style="clear: both"></div>
	<div>
		局號/单號：<input  name="OrderId"  type="text"  id="OrderId"    style="width:100px"  value="">
		&nbsp;&nbsp; 日期：
	<input type="text" name="s_time" value="<?if($_GET['s_time']){echo $_GET['s_time'];}else
	{echo date("Y-m-d",time());}?>" id="s_time" size="10" maxlength="11" class="za_text Wdate"onClick="WdatePicker()">
	--
	<input type="text" name="e_time" value="<?if($_GET['e_time']){echo $_GET['e_time'];}else
	{echo date("Y-m-d",time());}?>" id="e_time" maxlength="10" class="za_text Wdate" onClick="WdatePicker()">
	每页记录数：
	<select name="page_num" id="page_num">
			<option value="20" <?if($_GET['page_num']==20){ echo 'selected="selected"';}?>>20条</option>
			<option value="30" <?if($_GET['page_num']==30){ echo 'selected="selected"';}?>>30条</option>
			<option value="50" <?if($_GET['page_num']==50){ echo 'selected="selected"';}?>>50条</option>
			<option value="100" <?if($_GET['page_num']==100){ echo 'selected="selected"';}?>>100条</option>
		</select>
		&nbsp;頁數：
		<select id="page" name="page" class="za_select">
            <option>1</option>
        </select>
<span     class="za_button" onclick="GetVideoList()">確定</span>

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
		<input type="hidden" name="gid" value="<?=$_GET['gid']  ?>">
		<input type="hidden" name="did" value="<?=$_GET['did']  ?>">
</form></div>
<div class="content">


<table border="0" width="99%" cellpadding="0" cellspacing="0" class="m_tab">
    <tbody>
		<tr  class="m_title">
	        <td  width="120" align="center">派彩時間</td>
            <td  width="70" align="center">注单号</td>
            <td  width="70" align="center">局號/单號</td>
            <td  width="70" align="center">桌號</td>
            <td  width="70" align="center">游戏ID</td>
            <td  width="70" align="center">下注帳號</td>
            <td  width="70" align="center">期数</td>
            <td  width="70" align="center">游戏结果</td>
            <td  width="90" align="center">視訊/游戏類別</td>
            <td  width="90" align="center">总投注</td>
			<td  width="90" align="center">有效投注</td>
			<td  width="90" align="center">退水</td>
            <td  width="90" align="center">結果</td>
	</tr>
	</tbody>
	<tbody id="ListData">
    </tbody>
    <tbody>
		<tr  class="m_cen"  style="background-Color:#fcdcdc ;display:;">
			<td  colspan="8" style="text-align: right">小計：</td>
			<td><span id="Nums" class="CountMoney">0.00</span> 笔</td>
			<td><span id="BetMoneyAll" class="CountMoney">0.00</span></td>
            <td><span id="ValidBetMoneyAll" class="CountMoney">0.00</span></td>
			<td><span id="BackMoneyAll" class="CountMoney">0.00</span></td>
			<td><span id="ResultMoneyAll" class="CountMoney">0.00</span></td>
		</tr>
		<tr  class="m_cen"  style="background-Color:#fcdcdc ;display:;">
			<td  colspan="8"   style="text-align: right">总計：</td>
			<td><span id="NumsAll" class="CountMoney">0.00</span> 笔</td>
			<td><span id="BetMoneyAll_" class="CountMoney">0.00</span></td>
            <td><span id="ValidBetMoneyAll_" class="CountMoney">0.00</span></td>
			<td><span id="BackMoneyAll_" class="CountMoney">0.00</span></td>
			<td><span id="ResultMoneyAll_" class="CountMoney">0.00</span></td>
		</tr>
	</tbody>
</table>

</div>
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

    function GetVideoList(){
        var html='<tr><td colspan="13" align="center">数据加载中请等待...</td></tr>'
        $('#ListData').html(html);
        html='';
        $('.CountMoney').html(0.00);
        var Company=$('#Company').val();
        var SeachVideoGameSelect=$('#SeachVideoGameSelect').val();
        var VideoType=$('#VideoType').val();
        var GameType=$('#GameType').val();
        var s_time=$('#s_time').val();
        var e_time=$('#e_time').val();
        var agentid=$('#agentid').val();
        var username=$('#username').val();
        var page_num=$('#page_num').val();
        var Page=$('#page :selected').val();
        var OrderId=$('#OrderId').val();
        if(GameType==null) GameType='';
        if(VideoType==null) VideoType='';
        var request = 'OrderId='+OrderId+'&Company='+Company+'&VideoType='+VideoType+'&GameType='+GameType+'&S_Time='+s_time+'&E_Time='+e_time+'&UserName='+username+'&Page_Num='+page_num+'&Page='+Page+'&agentid='+agentid;
        $.post("../video/accountgetbetrecord.php?"+request,{},function(d){
            if(d['Error']){
                html+='<tr><td colspan="13"align="center">'+d['Error']+'</td></tr>'
            }
            else if(d['data']['Code']==10022){
                html+='<tr><td colspan="13" align="center">没有数据</td></tr>'
            }
            else if(d['data']['Code']==10006){
                html+='<tr><td colspan="13"align="center">此账号数据不存在</td></tr>'
            }
            else{
                var VideoData=d['data'];
                $.each(VideoData['data'],function(i,v){
                    html+='<tr>' +
                        '<td align="center">'+ v.OrderTime+'</td>' +
                        '<td align="center">'+ v.OrderNum+'</td>' +
                        '<td align="center">'+ v.OrderId+'</td>' +
                        '<td align="center">'+ v.DeskNum+'</td>' +
                        '<td align="center">'+ v.GameId+'</td>' +
                        '<td align="center">'+ v.Account+'</td>' +
                        '<td align="center">'+ v.Number+'</td>' +
                        '<td align="center">'+ v.GameResult+'</td>' +
                        '<td align="center">'+ v.BetType+'</td>' +
                        '<td align="center">'+ parseFloat(v.BetMoney).toFixed(2)+'</td>' +
                        '<td align="center">'+ parseFloat(v.ValidBetMoney).toFixed(2)+'</td>' +
                        '<td align="center">'+ parseFloat(v.BackMoney).toFixed(2)+'</td>' +
                        '<td align="center">'+ parseFloat(v.ResultMoney).toFixed(2)+'</td>' +
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
                $('#ValidBetMoneyAll').html(parseFloat(VideoData['ValidBetMoneyAll']).toFixed(2));
                $('#BetMoneyAll').html(parseFloat(VideoData['BetMoneyAll']).toFixed(2));
                $('#BackMoneyAll').html(parseFloat(VideoData['BackMoneyAll']).toFixed(2));
                $('#ResultMoneyAll').html(parseFloat(VideoData['ResultMoneyAll']).toFixed(2));
                $('#ValidBetMoneyAll_').html(parseFloat(VideoData['ValidBetMoneyAll_']).toFixed(2));
                $('#BetMoneyAll_').html(parseFloat(VideoData['BetMoneyAll_']).toFixed(2));
                $('#BackMoneyAll_').html(parseFloat(VideoData['BackMoneyAll_']).toFixed(2));
                $('#ResultMoneyAll_').html(parseFloat(VideoData['ResultMoneyAll_']).toFixed(2));
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


</div>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>

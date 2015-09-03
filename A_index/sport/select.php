<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>member_select</title>
<script type="text/javascript" language="javascript" src="public/js/jquery-1.7.2.min.js"></script>
<link  rel="stylesheet"  href="public/css/mem_order_olympics.css"  type="text/css">
<link  rel="stylesheet"  href="public/css/mem_order_sel.css"  type="text/css">
<link  rel="stylesheet"  href="public/css/standard.css"  type="text/css">
<link rel="stylesheet" href="public/css/mem_order_ft.css" type="text/css">
<script  language="JavaScript">
function showOrder() {
    try {
        bet_order_frame.resetTimer();
    } catch(e) {}
    document.getElementById('rec_frame').height = 0;
	document.getElementById('bet_order_frame').height = '';
	
    rec_frame.document.close();
    document.getElementById('order_button').className = "ord_on";
    document.getElementById('record_button').className = "record_btn";
	var betDiv = document.getElementById('bet_div');
    var rec5Div = document.getElementById('rec5_div');
    betDiv.style.display = "";
    rec5Div.style.display = "none";
    document.getElementById('pls_bet').style.display = "none";
    document.getElementById('info_div').style.display = '';
	bet_order_frame.location.replace("./jiaoyi.php?uid=" + top.uid + "&langx=" + top.langx);
   
    top.open_Rec = "";
    try {
        var gamecountHot=parent.body.getCountHOT();
        getCountHOT(countHOT);
    } catch(e) {
        document.getElementById('euro_open').style.display = "none";
    }

}
/*验证是否登录*/
function checkUid(){
	var uid='<?=$_SESSION['uid']?>';
	if(uid==""){
		alert("你还未登录，请先登录");
		return false;
	}
	return true;
}
/*最近十笔交易页面*/
function showRec() {
    try {
        bet_order_frame.clearAllTimer();
    } catch(e) {}
    try {
        close_bet();
    } catch(e) {}
	document.getElementById('rec_frame').height ='';
    document.getElementById('order_button').className = "ord_btn";
    document.getElementById('record_button').className = "record_on";
    var betDiv = document.getElementById('bet_div');
    var rec5Div = document.getElementById('rec5_div');

    betDiv.style.display = "none";
    rec5Div.style.display = "";
    rec5_div.focus();
    rec_frame.location.replace("./jiaoyis.php?uid=" + top.uid + "&langx=" + top.langx);
   document.getElementById('pls_bet').style.display = "none";

    try {
        if (tenrec_id == "") {
            top.open_Rec = "";
        } else {
            top.open_Rec = "Y";
        }
    } catch(e) {}

    try {
        var gamecountHot=parent.body.getCountHOT();
        getCountHOT(countHOT);
    } catch(e) {
        document.getElementById('euro_open').style.display = "none";
    }

    //scroll();
    //alert("showRec");
}


function showMoreMsg() {
    window.open('./help/announcement.php', "History", "width=617,height=500,top=0,left=0,status=no,toolbar=no,scrollbars=yes,resizable=no,personalbar=no");
}

</script>

</head>
<body id="OSEL" class="bodyset" onLoad="bodyLoad();">

<div id="main" class="main">



  <div id="menu">
	<div class="ord_on" id="order_button" onClick="if(checkUid())showOrder();">交易单</div>
    <div class="record_btn" id="record_button" onClick="if(checkUid())showRec();">最新十笔交易</div>
	<!--<div class="ord_on" id="order_button" onClick="showOrder();">交易单</div>
    <div class="record_btn" id="record_button" onClick="showRec();">最新十笔交易</div>-->
  </div>
	<div id="touzhudivs">
		  <div id="order_div" name="order_div" style="overflow:hidden" style="height:" >
			<img src="public/images/order_none.jpg" height="22" width="216">
			<div id="pls_bet" name="pls_bet" style="background-color:#e3cfaa; left: 0px; top: 0px; display: none; ">
				<div style="height:63px; text-align:center; padding-top:16px;">
					
					<font style="font:12px Arial, Helvetica, sans-serif; font-weight:bold;display:black">点击赔率便可将<br>选项加到交易单裡。1</font>
					<font style="font:12px Arial, Helvetica, sans-serif; font-weight:bold;">请先登录!</font>
				</div>
		  </div>
			<div style=" background-color:#e3cfaa;" id="bet_div" name="bet_div">
			  <iframe id="bet_order_frame" name="bet_order_frame" scrolling="NO" border="0" frameborder="NO" height="79" src="bet_order_frame.php" width="216"></iframe>
			</div>
			<div style="display: none;" id="rec5_div" name="rec5_div">
			  <iframe id="rec_frame" name="rec_frame" scrolling="NO" border="0" frameborder="NO" height="65" width="216"></iframe>
			</div>
		  </div>

		<!-- 奥运   Start -->
			<div style="display: none;" id="euro_banner" class="euro_btn"></div>

			<!-- 已开赛 -->
			<div style="z-index: -1;" id="euro_open">
				<!--div id="oly_title"></div-->

					<div id="oly_main">  
						<div id="RB_oly">
							<div id="FT_RB" class="oly_tr"><a href="grounder/ft_gunqiu.php" target="mainFrame" style="color:#000"  >足球(<span id="s_zqgq_">0</span>)</a></div>
							<div style="display:black;" id="BK_RB" class="oly_tr">
                                <a href="grounder/bk_gunqiu.php" target="mainFrame" style="color:#000">篮球 / 美式足球 / 橄榄球(<span id="s_lqgq_">0</span>)</a></div>

						</div>
				<script>
                    $(document).ready(function(){
                        s_zqgq=$(parent.topFrame.document).find("#s_zqgq").html()
                        s_lqgq=$(parent.topFrame.document).find("#s_lmgq").html()
                        setInterval("$('#s_zqgq_').html("+s_zqgq+")",1000);
                        setInterval("$('#s_lqgq_').html("+s_lqgq+")",1000);
                    })
				</script>
				 </div>
			</div>
<?php


include_once("../include/private_config.php");
include_once("../include/public_config.php");
include_once("../lib/class/model.class.php");
/**
*
*   上级公告信息读取
*   sid表示站点 0表示全站 3,4表示站点3和4 为3表示站点3
*   notice_cate表示公告分类  s_p体育f_c彩票v_d视讯
*
**/
$u = M('site_notice',$db_config);
$map = "(sid = '0' or sid = '".SITEID."') and notice_cate='s_p'";
$notice = $u->where($map)->order('notice_date desc')->find();
//print_r($notice);
//数据拼接
?>
			  <!--公告 Start--> 
			<div style="top: 501px;" id="info_div" name="info_div">
				<div class="msgHead">

					<h2>公告<a href="javascript://" class="btnGreen" onClick="showMoreMsg()">更多</a></h2>
					<div class="msgCon clear">
						<marquee scrollamount="1" direction="up" onMouseOver="this.stop();" onMouseOut="this.start();" height="70" width="190px">
							<a href="javascript://" onClick="showMoreMsg()"><?=@$notice['notice_content']?></a>
						</marquee>
					</div>
				</div>
			</div>
	</div>
	<!--投注 Start--> 
	<div id="xp" style="display:none;">
		<img src="public/images/order_none.jpg" height="22" width="216">
		 <div class="ord" id="ds_msg" style="margin-top:-4px;">
		<form action="../../sport/bet.php" name="form1" id="form1" method="post" onsubmit="if($('#cg_msg').css('display')!='none') {if (parseInt($('#touzhus').html(),10)>=3) {return check_bet();}else{alert('投注失败，请至少选择三场比赛后再进行投注！');return false;}}else{return check_bet();}">
            <input  type="hidden"  name="touzhutype" id="touzhutype" value="0" />
            <div class="main">


				<input type="hidden" id="user_money" value="">
				<div class="touzhu_4" id="cg_msg" style="background:#3F2F1D;color:#E9B567;">已选择 <span id="cg_num" style="color:#FF0;"></span> 场赛事</div>
				<div id="touzhu_c">
				</div>
                <script>
                    function cg_d(){
                        alert($("#touzhutype").val())
                        if($("#cg_num").val()>0) {
                            $("#touzhutype").val(1)
                           // $("#cg_msg").css("display")=='block'
                        }
                       // else  $("#touzhutype").val(0)
                    }
                   // setInterval('cg_d()',2000)
                    isnumber = function (e) {
                        //alert(event.keyCode)
                        if ($.browser.msie) {
                            if ( ((event.keyCode > 47) && (event.keyCode < 58)) ||
                                (event.keyCode == 8) ) {
                                return true;
                            } else {
                                return false;
                            }
                        } else {
                            if ( ((e.which > 47) && (e.which < 58)) ||
                                (e.which == 8) ) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    }
                </script>
				<div id="touzhudiv">


				</div>
			 <p class="auto"><input type="checkbox" id="autoOdd" name="autoOdd" onclick="onclickReloadAutoOdd()" checked="" value="Y"><span class="auto_info" title="在方格里打勾表示，如果投注单里的任何选项在确认投注时赔率变佳，系统会无提示的继续进行该下注。">自动接受较佳赔率</span></p>
			  <p class="error" style="display: none;"></p>
			  <div class="betdata">
				  <p class="amount">交易金额：<input name="bet_money" id="bet_money" type="text" class="txt tou_input" maxlength="5" onkeypress="return isnumber(event);" onkeydown="if(event.keyCode==13)return check_bet();" onpaste="return false" oncontextmenu="return false" oncopy="return false" oncut="return false"  autocomplete="off"size="8"></p>
				  <p class="mayWin"><span class="bet_txt">可赢金额：</span><font  id="win_span">0</font><input type="hidden" value="0" name="bet_win" id="bet_win"  /></p>
				  <p class="minBet"><span class="bet_txt">最低限额：</span><span id="min_point_span">110</span></p>
				  <p class="maxBet"><span class="bet_txt">单注限额：</span><span id="max_ds_point_span">220000</span></p>
				  <p class="maxBet"><span class="bet_txt">单场最高：</span><span id="max_cg_point_span">2200000</span></p>
				  
				  <div id="istz" style="display:none; color: #F00; text-align:center; line-height:25px;">
					可赢金额：<span id="win_span1">0.00</span><br>是否确定交易？
				  </div>
				  
				 <!-- <p class="amount2">确认金额：<input name="gold" type="text" class="txt" id="gold" onkeypress="return CheckKey(event)" onkeyup="return CountWinGold()" size="8" maxlength="10"> <input  type="submit" value="确认"/></p>-->
			</div>
				<div class="betBox">
					<input type="button" name="btnCancel" value="取消" style="float:left;" onclick="quxiao_bet()" class="no">
                    <input type="Submit" name="" value="确定交易" style="float:left; margin-left:2px;" id="submitid" class="yes queren">
                   </div>
				
			</div>

		</form>

	</div>
	<!--投注 end-->



	
		
</div>
	

	
<!--	<script type="text/javascript" language="javascript" src="/js/left.js"></script>-->

	<script type="text/javascript" language="javascript" src="public/js/touzhu.js"></script>
	<script type="text/javascript" language="javascript" src="/js/left_mouse.js"></script>
	<script language="javascript">
		function ResumeError() {
			return true;
		}
		window.onerror = ResumeError; 
	</script>
	
	
	</div>
</body>
</html>

var bool = auto_new = false;
var sound_off=0;
var ball_odds = cl_hao = cl_dx = cl_ds = cl_zhdx = cl_zhds = cl_lh ='';

$(function(){
	$('#cqc_sound').bind('click',function(){//绑定声音按钮
        var e=$(this);
        if(e.attr('off')=='1'){//声音开启
            e.attr('off','0');
            e.children('img').attr('src','images/on.png');
            sound_off=0;
        }else{//关闭
            e.attr('off','1');
            e.children('img').attr('src','images/off.png');
            sound_off=1;
        }
    });
});


//限制只能输入1-9纯数字 
function digitOnly ($this) {
	var n = $($this);
	var r = /^\+?[1-9][0-9]*$/;
	if (!r.test(n.val())) {
		n.val("");
	}
}
//盘口信息
function loadinfo(){
	$.post("class/odds_6.php", function(data)
		{
			if(data.opentime>0){
				$("#open_qihao").html(data.number);
				ball_odds = data.oddslist;
				loadodds(data.oddslist);
				endtime(data.opentime);
				auto(1);
				
			}else{
				$(".bian_td_odds").html("-");
				$(".bian_td_inp").html("封盘");
				$("#autoinfo").html("已经封盘，请稍后进行投注！");
				$.jBox.alert('当前彩票已经封盘，请稍后再进行下注！<br><br>排列三开盘时间为：每日08:00 - 20:00', '提示');
				return false;
	
			}
		}, "json");
}
//更新赔率
function loadodds(oddslist){
	if (oddslist == null || oddslist == "") {
			$(".bian_td_odds").html("-");
			$(".bian_td_inp").html("封盘");
			return false;
	}
	for(var i = 1; i<10; i++){
		if(i==4){
			for(var s = 1; s<8; s++){
				odds = oddslist.ball[i][s];
				$("#ball_"+i+"_h"+s).html(odds);
				loadinput(i, s);
			}
		}else if(i==5){
			for(var s = 1; s<6; s++){
				odds = oddslist.ball[i][s];
				$("#ball_"+i+"_h"+s).html(odds);
				loadinput(i, s);
			}
		}else if(i==6){
			for(var s = 1; s<11; s++){
				odds = oddslist.ball[i][s];
				$("#ball_"+i+"_h"+s).html(odds);
				loadinput(i, s);
			}
		}else if(i==1){
			for(var s = 1; s<15; s++){
				odds = oddslist.ball[i][s];
				$("#ball_"+i+"_h"+s).html(odds);
				loadinput(i , s);
			}
		}
	} 
	
}
//号码赔率
function hm_odds(ball){
	if (ball_odds == null || ball_odds == "") {
			$(".bian_td_odds").html("-");
			$(".bian_td_inp").html("封盘");
			return false;
	}
	for(var s = 1; s<15; s++){
		odds = ball_odds.ball[ball][s];
		$("#ball_1_h"+s).html(odds);
		loadinput(ball , s);
	} 
	for( var i = 0; i < 5; i++){
		if(i == ball-1){
		$('#menu_hm > li').eq(i).removeClass("current_n").addClass("current");
		}else{
		$('#menu_hm > li').eq(i).removeClass("current").addClass("current_n");
		}
	}
	
}
//三连赔率
function s_odds(ball){
	if (ball_odds == null || ball_odds == "") {
			$(".bian_td_odds").html("-");
			$(".bian_td_inp").html("封盘");
			return false;
	}
	for(var s = 1; s<6; s++){
		odds = ball_odds.ball[ball][s];
		$("#ball_4_h"+s).html(odds);
		loadinput(ball , s);
	} 
	for( var i = 0; i < 3; i++){
		if(i == ball-7){
		$('#menu_s > li').eq(i).removeClass("current_n").addClass("current");
		}else{
		$('#menu_s > li').eq(i).removeClass("current").addClass("current_n");
		}
	}
	
}
//更新投注框
function loadinput(ball , s){
	b = "ball_"+ball+"_"+s;
	n = "<input name=\""+b+"\" id=\""+b+"\" class=\"inp1\" onkeyup=\"digitOnly(this)\" onfocus=\"this.className='inp1m'\" onblur=\"this.className='inp1';\" type=\"text\" maxLength=\"5\"/>"
	if(ball<4){
		$("#ball_1_t"+s).html(n);
	}else if(ball==5){
		$("#ball_"+ball+"_t"+s).html(n);
	}else if(ball==4){
		$("#ball_"+ball+"_t"+s).html(n);
	}else if(ball==6){
		$("#ball_"+ball+"_t"+s).html(n);
	}
}

function getIS(s){
    var i=Math.floor(s/60);
    if(i < 10) i = '0'+i;
    var ss	=	s%60;
    if(ss < 10) ss = '0'+ss;
    return i+":"+ss;
}


//封盘时间
function endtime(iTime)
{
	var cqc_color=$('#cqc_time').css('color');
	var iMinute,iSecond;
    var sMinute="",sSecond="",sTime="";
    iMinute = parseInt((iTime/60)%60);
	if (iMinute == 0){
		sMinute = "00";
    }
    if (iMinute > 0 && iMinute < 10){
    	sMinute = "0" + iMinute;
    }
	if (iMinute > 10){
    	sMinute = iMinute;
    }
    iSecond = parseInt(iTime%60);
    if (iSecond >= 0 && iSecond < 10 ){
    	sSecond = "0" + iSecond;
    }
	if (iSecond >= 10 ){
    	sSecond =iSecond;
    }
    sTime= sMinute + sSecond;
    if(iTime==0){
		$("#look").html('<embed width="0" height="0" src="js/2.swf" type="application/x-shockwave-flash" hidden="true" />');
		var xnumbers = parseInt($("#numbers").html());
		//var numinfo= xnumbers+1+'正在开奖...';
		var numinfo= '<span>正在开奖...</span>';
		$("#autoinfo").html(numinfo);
		var i=0;
		$('.open_number > img ').each(function(){
			var e=$(this);
			e.prop('src','images/lhj.gif');
			i++;
		});


    }
	if(iTime==60){
		$(".bian_td_odds").html("-");
		$(".bian_td_inp").html("封盘");
		$("#look").html('<embed width="0" height="0" src="js/1.swf" type="application/x-shockwave-flash" hidden="true" />');
    }
	if(iTime<0){
		clearTimeout(fp);
		loadinfo();
    }else
    {
		iTime--;
		var t = 'time';

		if(iTime>60){
			$('#cqc_time').html(getIS(iTime-60));
			if(cqc_color!='red'){
				$('#cqc_time').css('color','red');
			}

			if(iTime == 66){//离开赛还有66秒，播放声音
				if(!sound_off){
					getSwfId('swfcontent').Pten();
				}
			}
		}

		if( iTime<=60 && iTime>0){
		     $('#cqc_time').html(getIS(iTime));
			if(cqc_color!='blue'){
				$('#cqc_time').css('color','#006600');
			}
		}

		if(iTime<60){
			t = 'times';
		}
		$("#sss").html(iTime);

		//$('.colon > img').attr('src','images/'+t+'/10.png');
		//$('.minute > span > img').eq(0).attr('src','images/'+t+'/'+sTime.substr(0,1)+'.png');
		//$('.minute > span > img').eq(1).attr('src','images/'+t+'/'+sTime.substr(1,1)+'.png');
		//$('.second > span > img').eq(0).attr('src','images/'+t+'/'+sTime.substr(2,1)+'.png');
		//$('.second > span > img').eq(1).attr('src','images/'+t+'/'+sTime.substr(3,1)+'.png');
		fp = setTimeout("endtime("+iTime+")",1000);
    }
}
//更新开奖号码
function auto(ball){
	$.post("class/auto_6.php", {ball : ball}, function(data)
		{
			$("#numbers").html(data.numbers);
			var openqihao = $("#open_qihao").html();
			if(auto_new == false || openqihao - data.numbers == 1){
				var numinfo='';
				numinfo = numinfo+'总和：<span><font>'+data.hms[0]+'</font></span>&nbsp;&nbsp;<span><font>'+data.hms[1]+'</font></span>&nbsp;&nbsp;<span><font>'+data.hms[2]+'</font></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;龙虎：<span><font>'+data.hms[3]+'</font></span><br>三连：<span><font>'+data.hms[4]+'</font></span>&nbsp;&nbsp;跨度：<span><font>'+data.hms[5]+'</font></span>';
				$("#autoinfo").html(numinfo);
				var i=0;
				var fun=5;
				$('.open_number > img ').each(function(){
					var e=$(this);
					var nu=data.hm[i];
					setTimeout(function(){e.prop('src','images/'+nu+'.png');},fun*600);
					i++;
					fun--;
				});
				auto_new = true;
				if(openqihao - data.numbers != 1){xhm = setTimeout("auto(1)",3000);}
			}else{
				xhm = setTimeout("auto(1)",3000);
			}
			var auto_top = '<table width="100%" border="0" cellspacing="1" cellpadding="0" class="clbian"><tr class="clbian_tr_title"><td colspan="2">开奖结果</td></tr><tr class="clbian_tr_title"><td>开奖期数</td><td>开奖号码</td></tr>';
				for (var key in data.hmlist){
					auto_top = auto_top+'<tr class="clbian_tr_txt"><td class="qihao">'+key+'</td><td class="haoma">'+data.hmlist[key]+'</td></tr>'
				}
			auto_top = auto_top+'</table>'
			//$("#auto_list").html(auto_top);
			//$(parent.leftFrame.document).find("#auto_list").html(auto_top);
		}, "json");
}
//投注提交
function order(){
	var tt = $("input.inp1");
	var mix = 10; cou = m = 0, txt = '', c=true;
	for (var i = 1; i < 10; i++){
		if(i==4){
			for(var s = 1; s < 8; s++){
				if ($("#ball_" + i + "_" + s).val() != "" && $("#ball_" + i + "_" + s).val() != null) {
					//判断最小下注金额
					if (parseInt($("#ball_" + i + "_" + s).val()) < mix) {
						c = false;
					}
					m = m + parseInt($("#ball_" + i + "_" + s).val());
					//获取投注项，赔率
					var odds = $("#ball_"+i+"_h" + s).html();
					var q = did(i);
					var w = wan6(s);
					txt = txt + q + '[' + w +'] @ ' + odds + ' x ￥' + parseInt($("#ball_" + i + "_" + s).val()) + '\n';
					cou++;
				}
			}
		}else if(i==5){
			for(var s = 1; s < 6; s++){
				if ($("#ball_" + i + "_" + s).val() != "" && $("#ball_" + i + "_" + s).val() != null) {
					//判断最小下注金额
					if (parseInt($("#ball_" + i + "_" + s).val()) < mix) {
						c = false;
					}
					m = m + parseInt($("#ball_" + i + "_" + s).val());
					//获取投注项，赔率
					var odds = $("#ball_"+i+"_h" + s).html();
					var q = did(i);
					var w = wan789(s);
					txt = txt + q + '[' + w +'] @ ' + odds + ' x ￥' + parseInt($("#ball_" + i + "_" + s).val()) + '\n';
					cou++;
				}
			}
		}else if(i==6){
			for(var s = 1; s < 10; s++){
				if ($("#ball_" + i + "_" + s).val() != "" && $("#ball_" + i + "_" + s).val() != null) {
					//判断最小下注金额
					if (parseInt($("#ball_" + i + "_" + s).val()) < mix) {
						c = false;
					}
					m = m + parseInt($("#ball_" + i + "_" + s).val());
					//获取投注项，赔率
					var odds = $("#ball_"+i+"_h" + s).html();
					var q = did(i);
					var w = wanK(s);
					txt = txt + q + '[' + w +'] @ ' + odds + ' x ￥' + parseInt($("#ball_" + i + "_" + s).val()) + '\n';
					cou++;
				}
			}
		}else{
			for(var s = 1; s < 15; s++){
				if ($("#ball_" + i + "_" + s).val() != "" && $("#ball_" + i + "_" + s).val() != null) {
					//判断最小下注金额
					if (parseInt($("#ball_" + i + "_" + s).val()) < mix) {
						c = false;
					}
					m = m + parseInt($("#ball_" + i + "_" + s).val());
					//获取投注项，赔率
					var odds = $("#ball_1_h" + s).html();
					var q = did(i);
					var w = wan(s);
					txt = txt + q + '[' + w +'] @ ' + odds + ' x ￥' + parseInt($("#ball_" + i + "_" + s).val()) + '\n';
					cou++;
				}
			}
		}
	}
	if (!c) {$.jBox.tip("最低下注金额："+mix+"￥");return false;}
	if (cou <= 0) {$.jBox.tip("请输入下注金额!!!");return false;}
	var t = "共 ￥"+m+" / "+cou+" 笔，确定下注吗？\n\n下注明细如下：\n\n";
	txt = t + txt;
	var ok = confirm(txt);
	if (ok)
	document.orders.submit()
	document.orders.reset()
}
//读取第几球
function did (type)
{
	var r = '';
	switch (type)
	{
		case 1 : r = '第一球'; break;
		case 2 : r = '第二球'; break;
		case 3 : r = '第三球'; break;
		case 4 : r = '總和,龍虎和'; break;
		case 5 : r = '三连'; break;
		case 6 : r = '跨度'; break;
	}
	return r;
}
//读取玩法
function wan (type)
{
	var r = '';
	switch (type)
	{
		case 1 : r = '0'; break;
		case 2 : r = '1'; break;
		case 3 : r = '2'; break;
		case 4 : r = '3'; break;
		case 5 : r = '4'; break;
		case 6 : r = '5'; break;
		case 7 : r = '6'; break;
		case 8 : r = '7'; break;
		case 9 : r = '8'; break;
		case 10 : r = '9'; break;
		case 11 : r = '大'; break;
		case 12 : r = '小'; break;
		case 13 : r = '单'; break;
		case 14 : r = '双'; break;
	}
	return r;
}
//读取玩法
function wan6 (type)
{
	var r = '';
	switch (type)
	{
		case 1 : r = '总和大'; break;
		case 2 : r = '总和小'; break;
		case 3 : r = '总和单'; break;
		case 4 : r = '总和双'; break;
		case 5 : r = '龙'; break;
		case 6 : r = '虎'; break;
		case 7 : r = '和'; break;
	}
	return r;
}
//读取玩法
function wan789 (type)
{
	var r = '';
	switch (type)
	{
		case 1 : r = '豹子'; break;
		case 2 : r = '顺子'; break;
		case 3 : r = '对子'; break;

		case 4 : r = '半顺'; break;
		case 5 : r = '杂六'; break;
	}
	return r;
}

//读取跨度
function wanK (type)
{
	var r = '';
	switch (type)
	{
		case 1 : r = '0'; break;
		case 2 : r = '1'; break;
		case 3 : r = '2'; break;
		case 4 : r = '3'; break;
		case 5 : r = '4'; break;
		case 6 : r = '5'; break;
		case 7 : r = '6'; break;
		case 8 : r = '7'; break;
		case 9 : r = '8'; break;
		case 10 : r = '9'; break;
	}
	return r;
}

function getSwfId(id) { //与as3交互 跨浏览器
    if (navigator.appName.indexOf("Microsoft") != -1) { 
        return window[id]; 
    } else { 
        return document[id]; 
    } 
} 
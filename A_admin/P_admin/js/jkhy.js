var num	=	0;
var gx	=	'';
var js	=	'';

$(window).load(function() {
	action();
});

function action(){
	clearInterval(gx);
	clearInterval(js);
	if($('#uid').val().length > 0){ //有要监控的会员
		var ds	=	$('#ds').val();
		var cg	=	$('#cg').val();
		$.getJSON(
			"jkhyDao.php?ds="+ds+"&cg="+cg+"&callback=?",
			function(json){
				var zxhy_html	=	'';
				if(json.hy != 'none'){
					zxhy_html	+=	"<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"#006600\">\r\n";
					zxhy_html	+=	"<tr>\r\n";
					var a		=	"";
					var zx		=	"";
					for(var i=0; i<json.hy.length;){
						var zxhy	=	json.hy[i];
						if(zxhy['zx'] == 1){ //在线
							a		=	"login_ip.php?username="+zxhy['username'];
							zx		=	"<div style=\"float:right; color:#FF0000;\">在线&nbsp;&nbsp;</div>";
						}else{
							a		=	"../hygl/user_show.php?id="+zxhy['uid'];
							zx		=	"<div style=\"float:right; color:#999999;\">离线&nbsp;&nbsp;</div>";
						}
						zxhy_html	+=	"<td width=\"25%\" align=\"center\" style=\"background-color:#FFFFFF;\" onMouseOver=\"this.style.backgroundColor='#C0E0F8'\" onMouseOut=\"this.style.backgroundColor='#FFFFFF'\"><div style=\"float:left;\">&nbsp;&nbsp;<a href=\""+a+"\">"+zxhy['username']+"</a></div>"+zx+"</td>\r\n";
						i++;
						if(i%4 == 0){
							zxhy_html+=	"</tr>\r\n";
							zxhy_html+=	"<tr>\r\n";
						}
					}
					while(i%4 != 0){
						zxhy_html	+=	"<td width=\"25%\" bgcolor=\"#FFFFFF\">&nbsp;</td>\r\n";
						i++;
					}
					zxhy_html	+=	"</tr>\r\n";
					zxhy_html	+=	"</table>\r\n";
				}
				$("#div_zx").html(zxhy_html);
				
				var ds_html		=	'';
				var ds_bid		=	'';
				var mp			=	0;
				if(json.ds != 'none'){
					ds_html		+=	"<table width=\"100%\" border=\"1\" bgcolor=\"#FFFFFF\" bordercolor=\"#96B697\" cellspacing=\"0\" cellpadding=\"0\" style=\"border-collapse: collapse; color: #225d9c;\" >\r\n";
					ds_html		+=	"<tr  class=\"t-title\" align=\"center\" >\r\n";
					ds_html		+=	"<td width=\"15%\"><strong>联赛名</strong></td>\r\n";
					ds_html		+=	"<td width=\"15%\"><strong>编号/主客队</strong></td>\r\n";
					ds_html		+=	"<td width=\"26%\"><strong>投注详细信息</strong></td>\r\n";
					ds_html		+=	"<td width=\"8%\"><strong>下注</strong></td>\r\n";
					ds_html		+=	"<td width=\"8%\"><strong>结果</strong></td>\r\n";
					ds_html		+=	"<td width=\"8%\"><strong>可赢</strong></td>\r\n";
					ds_html		+=	"<td width=\"10%\"><strong>投注/开赛时间</strong></td>\r\n";
					ds_html		+=	"<td width=\"10%\"><strong>投注账号</strong></td>\r\n";
					ds_html		+=	"</tr>\r\n";
	  				for(var i=0; i<json.ds.length; i++){
						var ds	=	json.ds[i];
						ds_html	+=	"<tr align=\"center\"  onMouseOver=\"this.style.backgroundColor='#EBEBEB'\" onMouseOut=\"this.style.backgroundColor='#FFFFFF'\" style=\"background-color:#FFFFFF;\">\r\n";
						ds_html	+=	"<td><a href=\"../zdgl/list.php?match_name="+encodeURIComponent(ds['match_name'])+"\">"+ds['match_name']+"</a>"+ds['www']+"</td>\r\n";
						ds_html	+=	"<td>"+ds['number']+"<br/><a href=\"../zdgl/list.php?match_id="+ds['match_id']+"\">"+ds['match_id']+"</a><br/>"+ds['master_guest']+"</td>\r\n";
						ds_html	+=	"<td><font color=\"#336600\"><b>"+ds['ball_sort']+"</b></font><br/>"+ds['match_time']+"&nbsp;<font style=\"color:#FF0033\">"+ds['bet_info']+"</font></td>\r\n";
						ds_html	+=	"<td>"+ds['bet_money']+"</td>\r\n";
						ds_html	+=	"<td>0</td>\r\n";
						ds_html	+=	"<td>"+ds['bet_win']+"</td>\r\n";
						ds_html	+=	"<td>"+ds['bet_time']+"</td>\r\n";
						ds_html	+=	"<td><span style=\"color:#999999;\">"+ds['assets']+"</span><br /><a href=\"../zdgl/list.php?uid="+ds['uid']+"\">"+ds['username']+"</a><br /><span style=\"color:#999999;\">"+ds['balance']+"</span></td>\r\n";
						ds_bid	+=	ds['bid']+",";
						ds_html	+=	"</tr>\r\n";
					}
					ds_html	+=	"</table>\r\n";
					
					mp++;
				}
				var cg_html		=	'';
				var cg_bid		=	'';
				if(json.cg != 'none'){
					if(mp > 0){
						cg_html	+=	"<br /><br />";
					}
					cg_html		+=	"<table width=\"100%\" border=\"1\" bgcolor=\"#FFFFFF\" bordercolor=\"#96B697\" cellspacing=\"0\" cellpadding=\"0\" style=\"border-collapse: collapse; color: #225d9c;\" >\r\n";
					cg_html		+=	"<tr  class=\"t-title\" align=\"center\" >\r\n";
					cg_html		+=	"<td width=\"10%\"><strong>编号</strong></td>\r\n";
					cg_html		+=	"<td width=\"10%\"><strong>模式</strong></td>\r\n";
					cg_html		+=	"<td width=\"40%\"><strong>投注详细信息</strong></td>\r\n";
					cg_html		+=	"<td width=\"10%\"><strong>交易金额</strong></td>\r\n";
					cg_html		+=	"<td width=\"10%\"><strong>可赢金额</strong></td>\r\n";
					cg_html		+=	"<td width=\"10%\"><strong>投注时间</strong></td>\r\n";
					cg_html		+=	"<td width=\"10%\"><strong>投注账号</strong></td>\r\n";
					cg_html		+=	"</tr>\r\n";
					for(var i=0; i<json.cg.length; i++){
						var cg	=	json.cg[i];
						cg_html	+=	"<tr align=\"center\"  onMouseOver=\"this.style.backgroundColor='#EBEBEB'\" onMouseOut=\"this.style.backgroundColor='#FFFFFF'\" style=\"background-color:#FFFFFF;\">\r\n";
						cg_html	+=	"<td>"+cg['gid']+"</td>\r\n";
						cg_html	+=	"<td>"+cg['cg_count']+"</td>\r\n";
						cg_html	+=	"<td>"+cg['zx']+"</td>\r\n";
						cg_html	+=	"<td>"+cg['bet_money']+"</td>\r\n";
						cg_html	+=	"<td>"+cg['bet_win']+"</td>\r\n";
						cg_html	+=	"<td>"+cg['bet_time']+"</td>\r\n";
						cg_html	+=	"<td><span style=\"color:#999999;\">"+cg['assets']+"</span><br /><a href=\"../zdgl/list.php?uid="+cg['uid']+"\">"+cg['username']+"</a><br /><span style=\"color:#999999;\">"+cg['balance']+"</span></td>\r\n";
						cg_bid	+=	cg['gid']+",";
						cg_html	+=	"</tr>\r\n";
					}
					cg_html	+=	"</table>\r\n";
					
					mp++;
				}
				
				if(mp > 0){
					$("#mp3").html(""); //先清空，再添加提示声音
					$("#mp3").html("<bgsound src='../../date/gz.mp3' loop='1'>"); //交易提示声音
					setTimeout("mp3()",5000); //5秒后更提示一次
				}
				$("#div_ds").html(ds_html+cg_html);
				$("#ds").val(ds_bid);
				$("#cg").val(cg_bid);
			}
		);
	}
	
	num	=	181;
	gx	=	setInterval("action()",180000); //180秒检测一次
	js	=	setInterval("show()",1000); //1秒检测一次
}

function show(){
	num--;
	$("#num").html("还有："+num+" 秒更新");
}

function mp3(){
	$("#mp3").html(""); //先清空，再添加提示声音
	$("#mp3").html("<bgsound src='../../date/gz.mp3' loop='1'>"); //交易提示声音
}

function check(){
	if($("#jkhy").val().length < 1){
		alert("请您输入要监控的会员编号！");
		return false;
	}
	return true;
}
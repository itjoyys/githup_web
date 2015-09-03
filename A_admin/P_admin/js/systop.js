// JavaScript Document
$(window).load(function() {
	action();
});

function action(){
    $.getJSON("systopDao.php?callback=?",function(json){
			if(json.onlylogin>0)
				{
					alert('帐号在其他地方登陆!!--登陆ip:'+json.ip+'--登陆地址:'+json.addr);
					window.parent.location.href='/';
				}
			var sum = json.sum;
			if(sum > 0){
				var html = "您有：";
				if(json.tknum > 0){
					html += "<span class=\"num\">"+json.tknum+"</span> 条<strong><A href=\"cwgl/tixian.php?status=2\"  target=\"main\">提款</a></strong> ";
					//$("hk_mp3").html(""); //先清空，再添加提示声音
					document.getElementById('hk_mp3').innerHTML= "<embed src='/date/tk.mp3' width='0' height='0'></embed>";
					//$("#hk_mp3").html("<embed src='../date/hk.mp3' width='0' height='0'></embed>");
				}
				//if(json.thnum > 0){
//					html += "<span class=\"num\">您有："+json.thnum+"</span> 条<strong><A href=\"cwgl/zhuanhuan.php?status=2\"  target=\"main\">转换真人额</a></strong> ";
//					//$("hk_mp3").html(""); //先清空，再添加提示声音
//					document.getElementById('th_mp3').innerHTML= "<embed src='/date/tk.mp3' width='0' height='0'></embed>";
//					//$("#hk_mp3").html("<embed src='../date/hk.mp3' width='0' height='0'></embed>");
//				}
				if(json.hknum > 0){
					html += "<span class=\"num\">"+json.hknum+"</span> 条<strong><A href=\"cwgl/huikuan.php?status=0\"  target=\"main\">汇款</a></strong> ";
					//$("#hk_mp3").html(""); //先清空，再添加提示声音
					//$("#hk_mp3").html("<bgsound src='/date/hk.mp3' loop='1'>"); //汇款提示声音
					document.getElementById('hk_mp3').innerHTML= "<embed src='/date/hk.mp3' width='0' height='0'></embed>";//汇款提示声音
					//$("#hk_mp3").html("<embed src='../date/hk.mp3' width='0' height='0'></embed>");
				}
				if(json.ssnum > 0){
					html += "<span class=\"num\">"+json.ssnum+"</span> 条<strong><A href=\"xxgl/ssgl.php\"  target=\"main\">申诉</a></strong> ";
				}
				if(json.dlsqnum > 0){
					html += "<span class=\"num\">"+json.dlsqnum+"</span> 条<strong><A href=\"dlgl/daili.php?1=1\"  target=\"main\">代理申请</a></strong> ";
				}
				if(json.tsjynum > 0){
					html += "<span class=\"num\">"+json.tsjynum+"</span> 条<strong><A href=\"xxgl/tsjy.php?1=1\"  target=\"main\">投诉建议</a></strong> ";
				}
				if(json.ychynum > 0){
					html += "<span class=\"num\">"+json.ychynum+"</span> 个<strong><A href=\"hygl/list.php?money=1&is_stop=0\"  target=\"main\">异常会员</a></strong> ";
				}
				if(json.cgnum > 0){
					html += "<a href=\"zdgl/cg_kjs.php\" target=\"main\"><span class=\"num\">"+json.cgnum+"</span> 条<strong>串关可结算</strong></a> ";
				}
						
				html += "信息未处理！";
				$("#m_xx").html(html);
				$("#tisi").css("display","block");
			}else{
				$("#m_xx").html("");
				$("#tisi").css("display","none");
			}
		}
	);
	
	setTimeout("action()",30000); //30秒检测一次
}

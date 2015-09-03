function _getYear(d){
	var yr=d.getYear();
	if(yr<1000) yr+=1900;
	return yr;
}

//open
function getPager(type,mo,me){
    var HID = '#page-content';
    if(type.charAt(0) == '#') {
        HID = type;
    } else if(type.charAt(0) == "_") {
        if(type == '_bank') {
        	mo = 'http://'+mo;
            window.open(mo, null);
        } else if(type == '_self') {
            location.href = mo;
        }
    } else if(type.charAt(0) == "-") {
        if (me == '') {
        	top.mem_index.location.href = './index.php?a=' + mo;
        }else{
            top.mem_index.location.href = './index.php?a='+mo+'&itype='+me;
        }
    }
}

//电子游戏
function opengeme(url){
    newWin=window.open(url,'','fullscreen=1,scrollbars=0,location=no');      
      window.opener=null;//出掉关闭时候的提示窗口
      window.open('','_self'); //ie7      
      window.close();
}

//在线客服
function OnlineService(url){
    newWin=window.open(url,'','fullscreen=1,scrollbars=0,location=no');      
    window.opener=null;//出掉关闭时候的提示窗口
    window.open('','_self'); //ie7      
    window.close();
}

//会员中心
function openmember(id){
    var url = '/Member/index_main.php?url='+id;	
    window.open(url,'帮助','height=630,width=1020,top=80,left=200,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no');
}

//前台会员中心打开
function openHelp(url) {
  var st = window.open(url,'帮助','height=630,width=1020,top=80,left=200,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no');

}	

/* 客端，加入我的最愛彈跳 */
function eleAddFavorite(title) {
	var host = window.location.host;
    if(document.all){
        //ie8~10
        window.external.AddFavorite(host,title);
    } else {
        alert('「' + "您使用的浏览器不支援此功能，请按“Ctrl+D”键手动加入收藏。" + '」');
    }
}

//验证码
function getKey() {
    $("#vPic").attr("src", "./yzm.php?type="+Math.random()+(new Date).getTime());
    $("#vPic").show();
}

function getYzm() {
    $("#vImg").attr("src", "./yzm.php?type="+Math.random()+(new Date).getTime());
    $("#vImg").show();
}

//广告弹窗
function notice_data() {
	window.open('./index.php?a=notice_data', "History", "width=816,height=500,top=0,left=0,status=no,toolbar=no,scrollbars=yes,resizable=no,personalbar=no");
}


/**
 * 頁籤切換
 * @return {null}
 */
$.fn.mtab2 = function(posType) {
    var area = this, bgTop = '', bgBottom = '';
    var posType = (typeof posType !== 'undefined'? posType: 'l');
    switch(posType) {
        case 'c':
            bgTop = 'top center';
            bgBottom = 'bottom center';
            break;
        case 'r':
            bgTop = 'top right';
            bgBottom = 'bottom right';
            break;
        default:
            bgTop = 'top left';
            bgBottom = 'bottom left'
    }
    $.each(area.find('li[id^=#]'), function(i) {
        if(i != 0) {
            area.find(this.id)[0].style.display = 'none';
        }
    });
    area.find('li[id^=#]').click(function() {
        var self = this;
        $.each(area.find('li[id^=#]'), function(i) {
            if(self.id != this.id) {
                area.find(this.id)[0].style.display = 'none';
                $(this)[0].style.backgroundPosition = bgTop;
                $(this).removeClass('mtab');
            } else {
                area.find(this.id)[0].style.display = 'block';
                $(this)[0].style.backgroundPosition = bgBottom;
                $(this).addClass('mtab');
            }
        });
    });
};


//美东时间
function RefTime()
{
	dd2.setSeconds(dd2.getSeconds()+1);
	var myYears = ( dd2.getYear() < 1900 ) ? ( 1900 + dd2.getYear() ) : dd2.getYear();
	$("#vlock").html('美東時間'+'：'+myYears+'年'+fixNum(dd2.getMonth()+1)+'月'+fixNum(dd2.getDate())+'日 '+time(dd2));
}
function time(vtime){
    var s='';
    var d=vtime!=null?new Date(vtime):new Date();
    with(d){
        s=fixNum(getHours())+':'+fixNum(getMinutes())+':'+fixNum(getSeconds())
    }
    return(s);
}

function fixNum(num){
    return parseInt(num)<10?'0'+num:num;
}

function stop(){ 
  return false; 
} 

var disnum=0;

function urlOnclick(url,i){
	if(window.parent.document.getElementById("new_left")){
		window.parent.document.getElementById("new_left").style.display="block";
	}else{
		document.getElementById("new_left").style.display="block";	
	}

if(window.parent.document.getElementById("new_right")){
		window.parent.document.getElementById("new_right").style.width="842px";
	}else{
		document.getElementById("new_right").style.width="842px";	
	}
			//if(window.parent.document.body){
//				window.parent.document.body.style.background="url(/images/bg_game.jpg) repeat-x scroll center top #1B110D";
//			}else{
//				document.body.style.background="url(/images/bg_game.jpg) repeat-x scroll center top #1B110D";
//			}
//window.parent.document.getElementById("showselect").style.display="block";

	if(window.parent.document.getElementById("new_center")){
		window.parent.document.getElementById("new_center").style.width="1040px";
		window.parent.document.getElementById("new_center").style.margin="0px auto";
	}else{
		document.getElementById("new_center").style.width="1040px";	
		document.getElementById("new_center").style.style.margin="0px auto";
	}
	
	window.open(url,"mainFrame");  
	if(i >= 0){
		disnum = i;
		var as = document.getElementById("menu").getElementsByTagName("a");
		for(var s=0; s<as.length; s++){
			if(s == i){
				as[s].className = "homemenu";
			}else{
				as[s].className = "alink";
			}
		}
	}
}
function Lottery_Go(url){
	
	if(window.parent.document.getElementById("new_left")){
		window.parent.document.getElementById("new_left").style.display="block";
	}else{
		document.getElementById("new_left").style.display="block";	
	}

	if(window.parent.document.getElementById("new_right")){
		window.parent.document.getElementById("new_right").style.width="842px";
	}else{
		document.getElementById("new_right").style.width="842px";	
	}
	
	if(url=='Jxssc' || url=='Xjssc' || url=='shssc'){
		alert("即将推出，尽情期待！");
	}else if(url=="lotto"){
		window.open('/lotto/index.php?action=k_tm',"mainFrame");  
		window.open('/lotto/left.php',"leftFrame");  
	}else{
		//window.location.href='/lottery/'+url+'.php';
		window.open('/lottery/'+url+'.php',"mainFrame");  
		window.open('/lottery/left.php',"leftFrame");  
	}
}



function topMouseEvent(mi,ty,i){
	if(ty == "o" && i != disnum){
		mi.className = "homemenua";
	}else if(ty == "t" && i != disnum){
		mi.className = "alink";
	}
}

function tick(){
	function initArray(){
		for(i=0;i<initArray.arguments.length;i++) this[i]=initArray.arguments[i];
	}
	var isnDays=new initArray("星期日","星期一","星期二","星期三","星期四","星期五","星期六","星期日");
	var today=new Date();
	var hrs=today.getHours();
	var _min=today.getMinutes();
	var sec=today.getSeconds();
	var clckh=""+((hrs>12)?hrs-12:hrs);
	var clckm=((_min<10)?"0":"")+_min;clcks=((sec<10)?"0":"")+sec;
	var clck=(hrs>=12)?"下午":"上午";
	
	//document.getElementById("t_2_1").innerHTML = _getYear(today)+"/"+(today.getMonth()+1)+"/"+today.getDate()+"&nbsp;"+clckh+":"+clckm+":"+clcks+"&nbsp;"+clck+"&nbsp;"+isnDays[today.getDay()];
	document.getElementById("t_2_1").innerHTML = _getYear(today)+"/"+(today.getMonth()+1)+"/"+today.getDate()+"&nbsp;"+clckh+":"+clckm+":"+clcks;
	
	window.setTimeout("tick()", 100); 
}

//var disnum=0;
//function urlOnclick(url,i){
//	window.open(url,"mainFrame");  
//	if(i >= 0){
//		disnum = i;
//		
//	}
//}

function turl(i){
	if(window.parent.document.getElementById("new_left")){
		window.parent.document.getElementById("new_left").style.display="block";
	}else{
		document.getElementById("new_left").style.display="block";	
	}

	if(window.parent.document.getElementById("new_right")){
		window.parent.document.getElementById("new_right").style.width="842px";
	}else{
		document.getElementById("new_right").style.width="842px";	
	}
	
	if(window.parent.document.getElementById("new_center")){
		window.parent.document.getElementById("new_center").style.width="1040px";
		window.parent.document.getElementById("new_center").style.margin="0px auto";
	}else{
		document.getElementById("new_center").style.width="1040px";	
		document.getElementById("new_center").style.style.margin="0px auto";
	}
	if(window.parent.document.body){
		//window.parent.document.body.style.background="url(/images/bg_game.jpg) repeat-x scroll center top #1B110D";
	}else{
	//	document.body.style.background="url(/images/bg_game.jpg) repeat-x scroll center top #1B110D";
	}
	//window.parent.document.getElementById("showselect").style.display="block";
	if(i==0){		//体育
		window.open("/show/FT_1_1.html","mainFrame");  
		window.open("/left.php","leftFrame");  	
	}else if(i==1){			//乐透
		window.open("/lotto/index.php?action=k_tm","mainFrame");  
		window.open("/lotto/left.php","leftFrame");  
	}else if(i==2){			//乐透

		if(window.parent.document.getElementById("new_left")){
			window.parent.document.getElementById("new_left").style.display="none";
		}else{
			document.getElementById("new_left").style.display="none";	
		}
	
		if(window.parent.document.getElementById("new_right")){
			window.parent.document.getElementById("new_right").style.width="1040px";
		}else{
			document.getElementById("new_right").style.width="1040px";	
		}

		window.open("/lottery.php","mainFrame");  
	}else if(i==3){			//重庆时时
		window.open("/lottery/cqssc.php","mainFrame");  
		window.open("/lottery/left.php","leftFrame"); 
	}else if(i==4){			//广东十分
		window.open("/lottery/gdsf.php","mainFrame");  
		window.open("/lottery/left.php","leftFrame"); 
	}else if(i==5){			//优惠活动
		window.open("/offer.php","mainFrame");  
		window.open("/left.php","leftFrame"); 
	}else{			//乐透
		window.open("/lottery.php","mainFrame");  
		window.open("/lottery/left.php","leftFrame");  	  
	}	
	
}
function turl2(i){
	if(window.parent.document.getElementById("new_left")){
		window.parent.document.getElementById("new_left").style.display="block";
	}else{
		document.getElementById("new_left").style.display="block";	
	}

	if(window.parent.document.getElementById("new_right")){
		window.parent.document.getElementById("new_right").style.width="842px";
	}else{
		document.getElementById("new_right").style.width="842px";	
	}
	
			if(window.parent.document.body){
				//window.parent.document.body.style.background="url(/images/bg_game.jpg) repeat-x scroll center top #1B110D";
			}else{
			//	document.body.style.background="url(/images/bg_game.jpg) repeat-x scroll center top #1B110D";
			}
	//window.parent.document.getElementById("showselect").style.display="block";
	if(i==0){		//体育
		window.open("/show/FT_1_1.html","mainFrame");  
		window.open("/left.php","leftFrame");  	
	}else if(i==1){			//乐透
		window.open("/lotto/index.php?action=k_tm","mainFrame");  
		window.open("/lotto/left.php","leftFrame");  
	}else if(i==2){			//乐透

		if(window.parent.document.getElementById("new_left")){
			window.parent.document.getElementById("new_left").style.display="none";
		}else{
			document.getElementById("new_left").style.display="none";	
		}
	
		if(window.parent.document.getElementById("new_right")){
			window.parent.document.getElementById("new_right").style.width="1040px";
		}else{
			document.getElementById("new_right").style.width="1040px";	
		}

		window.open("/lottery.php","mainFrame");  
	}else if(i==3){			//重庆时时
		window.open("/lottery/cqssc.php","mainFrame");  
		window.open("/lottery/left.php","leftFrame"); 
	}else if(i==4){			//广东十分
		window.open("/lottery/gdsf.php","mainFrame");  
		window.open("/lottery/left.php","leftFrame"); 
	}else if(i==5){			//优惠活动
		window.open("/offer.php","mainFrame");  
		window.open("/left.php","leftFrame"); 
	}else{			//乐透
		window.open("/lottery.php","mainFrame");  
		window.open("/lottery/left.php","leftFrame");  	  
	}	
	
}

function urlparent(url){
	window.open(url,"newFrame");
}

function topMouseEvent(mi,ty,i){
	if(ty == "o" && i != disnum){
		mi.className = "homemenua";
	}else if(ty == "t" && i != disnum){
		mi.className = "alink";
	}
}



//登陆验证
function aLeftForm1Sub(){
	var un	=	$("#username").val();
	if(un == "" || un == "帐户"){
		$("#username").focus();
		return false;
	}
	var pw = $("#password").val();
	if(pw == "" || pw == "******"){
		$("#password").focus();
		return false;
	}
	var rmNum	=	$("#rmNum").val();
	if(rmNum == ""){
		$("#rmNum").focus();
		return false;
	}
	$("#submit").attr("disabled",true); //按钮失效
	$.post("./index.php?a=logincheck",{r:Math.random(),action:"login",username:un,password:pw,vlcodes:rmNum},function(login_jg){
		var data = eval('('+login_jg+')');
		if(data['error'].indexOf("1")==0){ //验证码错误
			alert("验证码错误，请重新输入");
			$("#rmNum").select();
			//return false;
		}else if(data['error'].indexOf("2")==0){
			alert("账号错误请重新输入!");
			$("#rmNum").val(''); //清空验证码
			$("#password").val(''); //清空验证码
			$("#username").select();
			//return false;
		}else if(data['error'].indexOf("3")==0){ 
			alert("密码错误请重新输入!");
			$("#rmNum").val(''); //清空验证码
			$("#password").val(''); //清空验证码
			$("#username").select();
			//return false;
		}else if(data['error'].indexOf("5")==0){ 
			alert("对不起，账户已暂停使用,请联系在线客服！");
			//return false;
		}else if(data['error'].indexOf("4")==0){ //登陆成功
			//top.location.href='/myhome.php';
			window.location.href='./index.php?a=login_info&username='+un+'&password='+pw;
			return;
		}	
													 
		$("#submit").attr("disabled",false); //按钮有效
	});
}
function memberUrl(url) {
	art.dialog.open(url,{width:960,height:500});
}


function menu_url(i){
    var index = top.document.getElementById("index");
    
    switch (i) {
        case 1:
            index.src = "myhome.php";
            break;
        case 2:
            index.src = "sports.php";
            break;
		case 22:
            index.src = "stock.php";
            break;
        case 3:
            index.src = "six.php";
            break;
        case 4:
            index.src = "ssc.php"; //重庆时时彩
            break;
        case 18:
            index.src = "ssc.php?t=2"; //广东快乐十分
            break;
        case 19:
            index.src = "ssc.php?t=3"; //北京赛车PK拾
            break;
        case 6:
            index.src = "ssc.php?t=4"; //北京快乐8
            break;
        case 8:
            index.src = "ssc.php?t=5"; //上海时时乐
            break;
        case 5:
            index.src = "ssc.php?t=6"; //福彩3D
            break;
        case 7:
            index.src = "ssc.php?t=7"; //排列三
            break;
        case 17:
            top.location.href = "/logout.php";
            break;
        case 9:
			memberUrl("/Member/sms.php?1=1");
            break;
		case 109:
			memberUrl("/Member/userinfo.php");
            break;
		case 110:
			memberUrl("/Member/set_money.php");
            break;
		case 111:
			memberUrl("/Member/get_money.php");
            break;
        case 10:
			memberUrl("/Member/password.php");
            break;
        case 11:
			memberUrl("/Member/get_money.php");
            break;
        case 12:
			memberUrl("/Member/get_money.php");
            break;
        case 13:
			memberUrl("/Member/record_ds.php");
            break;
        case 14:
			memberUrl("/Member/report.php");
            break;
        case 15:
			memberUrl("/Member/cha_xiaji.php");
            break;
        case 16:
			memberUrl("/Member/daili_onshenqing.php");
            break;
        case 20:
			memberUrl("/Member/zr_money.php");
            break;
        case 21:
			memberUrl("/Member/zr_data_money.php");
            break;
        case 61: //关于我们
            index.src = "myabout.php?code=gywm";
            break;
        case 62: //联系我们
            //index.src = "myabout.php?code=lxwm";
            window.open("tencent://message/?uin=8361578&Site=葡京国际&Menu=yes", "_blank");
            break;
		case 162: //联系我们
            //index.src = "myabout.php?code=lxwm";
            break;
        case 63: //合作伙伴
            index.src = "myabout.php?code=hzhb";
            break;
        case 64: //存款帮助
            index.src = "myabout.php?code=ckbz";
            break;
        case 65: //取款帮助
            index.src = "myabout.php?code=qkbz";
            break;
        case 66: //常见问题
            index.src = "myabout.php?code=cjwt";
            break;
        case 67: //优惠活动
            index.src = "myhot.php";
            break;
        case 68: //彩票游戏
            index.src = "mylottery.php";
            break;
        case 69: //玩法介绍
            index.src = "myabout.php?code=wfjs";
            break;
        case 70: //会员注册
            index.src = "myreg.php";
            break;
        case 71: //手机投注
            index.src = "mywap.php";
            break;
        case 72: //负责任博彩
            index.src = "myabout.php?code=fzrbc";
            break;
        case 73: //真人娱乐
            index.src = "mylive.php";
            break;
        case 74: //底部联系我们
            index.src = "myabout.php?code=lxwm";
            break;
        case 75: //联系QQ
            index.src = "myabout.php?code=lxwm";
            break;
        case 41: //游戏规则
            index.src = "clause.html";
            break;
        default:
            index.src = "myhome.php";
    }
}

function get_dled(){
	$.getJSON("getDLED.php?callback=?",function(json){
		$("#dled").html("("+json.dled+")");
	});
}

function navfocu(i){
	
	var as = document.getElementById("top_3").getElementsByTagName("a");
		for(var s=0; s<as.length; s++){
			if(s == (i-1)){
				as[s].className = "nav"+i+"_f";
			}else{
				as[s].className = "nav"+(s+1);
			}
		}
}
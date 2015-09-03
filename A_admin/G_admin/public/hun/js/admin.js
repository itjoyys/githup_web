var serverTime=null;
//getServerTime();
function getServerTime()
{
	$.ajax({
        type: 'get',
        async:false,
        dataType: 'text',
        url: '../gettime.php?time='+(new Date()).getTime(),
        timeout: 5000,
        success: function(e){
				serverTime=new Date(e);	
			}
    });
}

function OpenMoneyCz(user,t)
{
    var DG = new J.dialog({title:"瀛樺叆 - "+user,iconTitle:false,maxBtn:false,btnBar:false,drag:true,cover:true,bgcolor:'#3B2D1B',autoSize:true,id:'ch_quick',page:"../pub/user_cz.php?u="+user+"&t="+t, width:310, height:90,
    dgOnLoad: function(){ 
            DG.iDoc('ch_quick').getElementById('money').focus();
        }}); 
    DG.ShowDialog();
}
function OpenMoneyTq(user,t)
{
    var DG = new J.dialog({title:"鎻愬彇 - "+user,iconTitle:false,maxBtn:false,btnBar:false,drag:true,cover:true,bgcolor:'#3B2D1B',autoSize:true,id:'ch_quick',page:"../pub/user_tq.php?u="+user+"&t="+t, width:310, height:90,
    dgOnLoad: function(){ 
            DG.iDoc('ch_quick').getElementById('money').focus();
        }
    }); 
    DG.ShowDialog();
}
function OpenMoneySearch(user,t)
{
    var DG = new J.dialog({title:"鐝鹃噾棰濆害鏌ヨ ["+user+"]",iconTitle:false,btnBar:false,drag:true,bgcolor:'#3B2D1B',cover:true,id:'fd_quick',page:"../pub/user_search.php?u="+user+"&t="+t, width:500,height:window.screen.height-280,lockScroll:false}); 
    DG.ShowDialog();

}
function OpenDetail(qs,lx,id,n,lx2)
{
    var DG = new J.dialog({title:qs+"鏈�-"+n+"-鏄庣窗",iconTitle:false,btnBar:false,drag:true,bgcolor:'#3B2D1B',cover:true,id:'fd_quick',page:"../ssc/admin/main.php?uid="+uid+"&langx="+langx+"&action=look&lx2="+lx2+"&act=main&kithe="+qs+"&lx="+lx+"&id="+id+"&class3="+n+(typeof($('#tm').val())!="undefined"?"&tm="+$('#tm').val():"")+"&zc="+$("#zc").val()+"&ab="+$("#ab").val(), width:window.screen.width-200,height:window.screen.height-280,lockScroll:false}); 
    DG.ShowDialog();

}
function OpenZf(class1,class2,class3,rate,money,ts)
{
		/*var DG = new J.dialog({title:"璧伴",iconTitle:false,maxBtn:false,btnBar:false,drag:true,bgcolor:'#3B2D1B',cover:true,id:'fd_quick',page:"main.php?action=d1&class1="+class1+"&class2="+class2+"&class3="+class3+"&rate="+rate+"&ts="+ts+"&money="+money, width:228,height:195,lockScroll:false}); 
    DG.ShowDialog();
    return false;*/
}
function CanceWin()
{
	var DG = frameElement.lhgDG;
  DG.cancel();   	
}
function OpenAddQs(lx,id,t)
{
    var DG = new J.dialog({title:t,iconTitle:false,btnBar:false,drag:true,bgcolor:'#3B2D1B',cover:true,id:'fd_quick',page:"/app/ssc/admin/main.php?uid="+uid+"&langx="+langx+"&action=editqs&lx="+lx+"&id="+id, width:650,height:300,lockScroll:false,
   onXclick:function(){
    			document.location.reload();
    			return true;
    		}
    }); 
    DG.ShowDialog();
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
function gametime(v,obj){ 
    $("#"+obj).js_time(v);
}

function updateRate(t,o,v,l,i){
		$.getJSON("../data/update_rate.php?time="+(new Date()).getTime()+"&t="+t+"&o="+o+"&v="+v+"&l="+l+"&i="+i,"",updateRateCallBack);
}
function updateRateCallBack(result){
	if(result!=null)
	{
		$('#'+result.o).text(result.rate);	
	}
}
function updateRate2(t,o,v)
{
		var defvalue=parseFloat($("#"+o).val());
		if(t=="jian")
			$("#"+o).val(formatnumber(defvalue-parseFloat(v),2));
		else
			$("#"+o).val(formatnumber((defvalue+parseFloat(v)),2));
}
function formatnumber(value, num) {
	var a, b, c, i

	a = value.toString();
	b = a.indexOf('.');
	c = a.length;
	if (num == 0) {
		if (b != -1)
			a = a.substring(0, b);
	} else {
		if (b == -1) {
			a = a + ".";
			for (i = 1; i <= num - 1; i++)
				a = a + "0";
		} else {
			a = a.substring(0, b + num + 1);
			for (i = c; i <= b + num - 1; i++)
				a = a + "0";
		}
	}
	return a
}
function OpenGg()
{
	var DG = new J.dialog({title:"鏈€鏂板叕鍛�",iconTitle:false,btnBar:false,drag:true,bgcolor:'#3B2D1B',cover:true,id:'fd_quick',page:"/gonggao.php", width:600,height:400,lockScroll:false}); 
	DG.ShowDialog();
}
/////琛ㄦ嫨琛ㄥ崟////_b涓哄浘鐗囧悕///////////////
function ra_select(str1){
var imageOn = "images/icon_21x21_selectboxon.gif";
var imageOff = "images/icon_21x21_selectboxoff.gif";
    if (document.all[str1].value == "鍚�" || document.all[str1].value == "") {
        document.images[str1+'_b'].src = imageOn;
        document.all[str1].value = "鏄�";
    } else {
        document.images[str1+'_b'].src = imageOff;
        document.all[str1].value = "鍚�";
    }
//alert(document.all[str1].value)
}
/*鎾斁澹伴煶*/
function PaySound()
{
	var obj=document.getElementById("sound1");
	if(obj!=null)
	{
		obj.src="../sound/msg.wav";
	}
}
(function($) { 
	$.fn.js_time = function(v) {
		var InterValObj=null;
		var SysSecond=0;	
		var timeObj=null;
		var spanObj=$(this);
		SysSecond=(((new Date(v)).getTime()- serverTime.getTime())/1000)+3;
		to();
		timeObj	=window.setInterval(function(){
			to();
		},1000);
		function to(){
			if (SysSecond > 0) {
				SysSecond = SysSecond - 1;
				var second = Math.floor(SysSecond % 60);      
				var minite = Math.floor((SysSecond / 60) % 60); 
				var hour = Math.floor((SysSecond / 3600) % 24);  
				var day = Math.floor((SysSecond / 3600) / 24);
				if (day>0){
					spanObj.html("<font color=red><b>"+day+"</b></font> "+tim_t+" <font color=red><b>"+hour+"</b></font> "+tim_x+" <font color=red><b>"+minite+"</b></font> "+tim_f+" <font color=red><b>"+second+"</b></font> "+tim_m) ; 
				}else if(hour>0){
					spanObj.html("<font color=red><b>"+hour+"</b></font> "+tim_x+" <font color=red><b>"+minite+"</b></font> "+tim_f+" <font color=red><b>"+second+"</b></font> "+tim_m); 
				}else if(minite>0){
					spanObj.html("<font color=red><b>"+minite+"</b></font> "+tim_f+" <font color=red><b>"+second+"</b></font> "+tim_m); 
				}else{
					spanObj.html("<font color=red><b>"+second+"</b></font> "+tim_m); 
					if(second<=10)
						PaySound();
					if(second<=0)
					{
						window.clearInterval(InterValObj); 	
					}
				}
			} else {
				spanObj.html("<font color=red><b>0</b></font> "+tim_m); 
				window.clearInterval(InterValObj); 
			}		
		}
	};
})(jQuery);
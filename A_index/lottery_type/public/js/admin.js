var serverTime=null;
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
getServerTime();
function getServerTime()
{
	// $.ajax({
 //        type: 'get',
 //        async:false,
 //        dataType: 'text',
 //        url: 'gettime.php?time='+(new Date()).getTime(),
 //        timeout: 5000,
 //        success: function(e){
	// 			serverTime=new Date(e);	
	// 		}
 //    });
}

function gametimeRef(v,obj)
{
	$("#"+obj).js_time(v);
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

function PaySound()
{
	try{
		var obj=thisMovie("game_sound");
		if(obj!=null){
			obj.loadMusic("sound/1.mp3");
			obj.plays(1);
		}
	}
	catch(e){}
}
function thisMovie(movieName) { 
	if (navigator.appName.indexOf("Microsoft") != -1) { 
		return window[movieName];
	} 
	else { 
		return document[movieName];
	} 
} 

(function($) { 
	$.fn.js_time = function(v) {
		var InterValObj=null;
		var SysSecond=0;	
		var timeObj=null;
		var spanObj=$(this);
		SysSecond=(((new Date(v.replace(/-/g,"/"))).getTime()- serverTime.getTime())/1000)+3;
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
					spanObj.html("<font color=#7AFF00><b>"+day+"</b></font> "+tim_t+" <font color=#7AFF00><b>"+hour+"</b></font> "+tim_x+" <font color=#7AFF00><b>"+minite+"</b></font> "+tim_f+" <font color=#7AFF00><b>"+second+"</b></font> "+tim_m) ; 
				}else if(hour>0){
					spanObj.html("<font color=#7AFF00><b>"+hour+"</b></font> "+tim_x+" <font color=#7AFF00><b>"+minite+"</b></font> "+tim_f+" <font color=#7AFF00><b>"+second+"</b></font> "+tim_m); 
				}else if(minite>0){
					spanObj.html("<font color=#7AFF00><b>"+minite+"</b></font> "+tim_f+" <font color=#7AFF00><b>"+second+"</b></font> "+tim_m); 
				}else{
					spanObj.html("<font color=#7AFF00><b>"+second+"</b></font> "+tim_m); 
					if(second<=10)
						PaySound();
					if(second<=0)
					{
						window.clearInterval(InterValObj); 
						window.setTimeout(function(){
								document.location.reload();
							},1000);	
					}
				}
			} else {
				window.clearInterval(InterValObj); 
				window.setTimeout(function(){
						document.location.reload();
					},1000);
			}		
		}
	};
})(jQuery);

function CheckInput(obj)
{
	if(obj.value!=''){
		if(parseFloat(obj.value)>dxxe)
		{
			alert('已超過單注限額['+dxxe+']');
			obj.value='';
		}	
	}
	return TotalMoney();
}
function TotalMoney()
{
	var total=0;
	$.each($("input[name^='Num_']"),function(index,v){
		if(v.value!="") total+=parseFloat(v.value);
	});
	$("#allgold").text(total);	
	return total;
}
function ssc_kj(f,p,p2)
{
	switch(f)
	{
		case "dx_d":
			$.each($("input[pid='"+p+"']"),function(index,v){
				var num=$(this).attr("num");
				if(num>=5)
					$(this).addClass("rate_sel");
			});
			break;	
		case "dx_x":
			$.each($("input[pid='"+p+"']"),function(index,v){
				var num=$(this).attr("num");
				if(num<=4)
					$(this).addClass("rate_sel");
			});
			break;
		case "ds_d":
			$.each($("input[pid='"+p+"']"),function(index,v){
				var num=$(this).attr("num");
				if((num%2)==1)
					$(this).addClass("rate_sel");
			});
			break;
		case "ds_s":
			$.each($("input[pid='"+p+"']"),function(index,v){
				var num=$(this).attr("num");
				if((num%2)==0)
					$(this).addClass("rate_sel");
			});
			break;
		case "zh_z":
			$.each($("input[pid='"+p+"']"),function(index,v){
				var num=$(this).attr("num");
				if((num==1 || num==2 || num==3 || num==5 || num==7))
					$(this).addClass("rate_sel");
			});
			break;
		case "zh_h":
			$.each($("input[pid='"+p+"']"),function(index,v){
				var num=$(this).attr("num");
				if((num==0 || num==4 || num==6 || num==8 || num==9))
					$(this).addClass("rate_sel");
			});
			break;
		case "num":
			$.each($("input[pid='"+p+"']"),function(index,v){
				var num=$(this).attr("num");
				if(num.indexOf(p2)>=0 && num.substring(0,1)!=num.substring(1,2))
					$(this).addClass("rate_sel");
			});
			break;
		case "num2":
			$.each($("input[pid='"+p+"']"),function(index,v){
				var num=$(this).attr("num");
				if(num.indexOf(p2)>=0)
					$(this).addClass("rate_sel");
			});
			break;
		case "dd":
			$.each($("input[pid='"+p+"']"),function(index,v){
				var num=$(this).attr("num");
				if(num.substring(0,1)==num.substring(1,2))
					$(this).addClass("rate_sel");
			});
			break;
		case "dd2":
			$.each($("input[pid='"+p+"']"),function(index,v){
				var num=$(this).attr("num");
				if((num.substring(0,1)==num.substring(1,2) && num.substring(0,1)!=num.substring(2,3)) 
					|| (num.substring(1,2)==num.substring(2,3) && num.substring(0,1)!=num.substring(2,3)))
					$(this).addClass("rate_sel");
			});
			break;
		case "bz":
			$.each($("input[pid='"+p+"']"),function(index,v){
				var num=$(this).attr("num");
				if(num.substring(0,1)==num.substring(1,2) && num.substring(0,1)==num.substring(2,3))
					$(this).addClass("rate_sel");
			});
			break;
		case "yb":
			$.each($("input[pid='"+p+"']"),function(index,v){
				var num=$(this).attr("num");
				if(num.substring(0,1)!=num.substring(1,2) && num.substring(0,1)!=num.substring(2,3)
					 && num.substring(1,2)!=num.substring(2,3))
					$(this).addClass("rate_sel");
			});
			break;
	}	
}
function ChangeToMoney()
{
	if($("#ChangeMoneyInput").val()=="")
	{
		alert("請輸入金額!");return false;	
	}
	$(".rate_sel").val($("#ChangeMoneyInput").val());	
	TotalMoney();
}
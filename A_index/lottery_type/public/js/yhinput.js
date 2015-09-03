var Yh_Text=new Object();
String.prototype.Trim = function(){ return Trim(this);} 
String.prototype.LTrim = function(){return LTrim(this);} 
String.prototype.RTrim = function(){return RTrim(this);} 
var OnNaN="只能输入数字";
var error_maxValue="不能超过[%s]";
var error_minValue="不能小于[%s]";
function LTrim(str) 
{ 
var i; 
for(i=0;i<str.length;i++) 
{ 
if(str.charAt(i)!=" "&&str.charAt(i)!=" ")break; 
} 
str=str.substring(i,str.length); 
return str; 
} 
function RTrim(str) 
{ 
var i; 
for(i=str.length-1;i>=0;i--) 
{ 
if(str.charAt(i)!=" "&&str.charAt(i)!=" ")break; 
} 
str=str.substring(0,i+1); 
return str; 
} 
function Trim(str) 
{ 
return LTrim(RTrim(str)); 
} 

Yh_Text.CheckNumber=function(event,obj)
{
	event = (event) ? event : ((window.event) ? window.event : "") //兼容IE和Firefox获得keyBoardEvent对象
    var key = event.keyCode?event.keyCode:event.which; //兼容IE和Firefox获得keyBoardEvent对象的键值
	
	if(event.shiftKey) return false;
	if((key!=16)&&((key>=48) && (key<=57))||((key>=95) && (key<=105))||(key==8)||(key==9)||(key==190)||(key==110)||(key==8))
	{
		return true;
	}
	else
	{	
		return false;
	}
}
Yh_Text.CheckNumber2=function(event,obj)
{
	event = (event) ? event : ((window.event) ? window.event : "") //兼容IE和Firefox获得keyBoardEvent对象
    var key = event.keyCode?event.keyCode:event.which; //兼容IE和Firefox获得keyBoardEvent对象的键值 A
	if(event.shiftKey) return false;
	if(key==188) return true;
	if((key!=16)&&((key>=48) && (key<=57))||((key>=95) && (key<=105))||(key==8)||(key==9)||(key==8)||(key==189))
	{
		return true;
	}
	else
	{	
		return false;
	}
}
Yh_Text.CheckNumber3=function(event,obj)
{
	event = (event) ? event : ((window.event) ? window.event : "") //兼容IE和Firefox获得keyBoardEvent对象
    var key = event.keyCode?event.keyCode:event.which; //兼容IE和Firefox获得keyBoardEvent对象的键值
	if(event.shiftKey) 
	{
		return false;
	}
	if(key==188) return true;
	if((key!=16)&&((key>=48) && (key<=57))||((key>=95) && (key<=105))||(key==8)||(key==9)||(key==190)||(key==110)||(key==8)||(key==189))
	{
		return true;
	}
	else
	{	
		return false;
	}
}
Yh_Text.CheckNumber4=function(event,obj)
{
	event = (event) ? event : ((window.event) ? window.event : "") //兼容IE和Firefox获得keyBoardEvent对象
    var key = event.keyCode?event.keyCode:event.which; //兼容IE和Firefox获得keyBoardEvent对象的键值
	if(event.shiftKey) 
		return false;

	if(key==187 || key==107)
	{
		if(typeof(obj.flag)!="undefined")
			settings1_jia(obj.flag);
		else
			settings1_jia();
		return false;
	}
	else if(key==189 || key==109)
	{
		if(typeof(obj.flag)!="undefined")
			settings1_jian(obj.flag);
		else
			settings1_jian();
		return false;
	}
	if((key!=16)&&((key>=48) && (key<=57))||((key>=95) && (key<=105))||(key==8)||(key==9)||(key==190)||(key==110)||(key==8)||(key==189))
	{
		return true;
	}
	else
	{	
		return false;
	}
}
Yh_Text.CheckNumber5=function(event,obj)
{
	event = (event) ? event : ((window.event) ? window.event : "") //兼容IE和Firefox获得keyBoardEvent对象
    var key = event.keyCode?event.keyCode:event.which; //兼容IE和Firefox获得keyBoardEvent对象的键值
	if(event.shiftKey) 
	{
		if(key==187 || key==107)
		{
			return true;
		}
		return false;
	}
	if(typeof(obj.onNum)!="undefined")
	{
		if(key==190) return false;
	}
	if(((key>=37) && (key<=40)) || (key!=16) && ((key>=48) && (key<=57))||((key>=95) && (key<=105))||(key==8)||(key==9)||(key==190)||(key==110)||(key==8)||(key==189)||(key==107)||(key==109))
	{
		return true;
	}
	else
	{	
		return false;
	}
}
Yh_Text.CheckNumber6=function(obj)
{
	if(isNaN(obj.value))
	{
		alert(OnNaN);obj.value="";obj.focus();return false;
	}
	if(obj.value.Trim()=="")
	{
		alert(OnNaN);obj.value="";obj.focus();return false;
	}
	if(typeof(obj.minValue)!="undefined")
	{
		if(parseFloat(obj.value)<parseFloat(obj.minValue))
		{
			alert(error_minValue.replace("%s",obj.minValue));obj.value=obj.minValue;return false;
		}
	}
	return true;
}
Yh_Text._CheckNumber6=function(obj)
{
	if(isNaN(obj.value))
	{
		document.getElementById("Dx_HTML").innerHTML=OnNaN;obj.value="";return false;
	}
	if(obj.value.Trim()=="")
	{
		document.getElementById("Dx_HTML").innerHTML=OnNaN;obj.value="";return false;
	}
	if(typeof(obj.minValue)!="undefined")
	{
		if(parseFloat(obj.value)<parseFloat(obj.minValue))
		{
			alert(error_minValue.replace("%s",obj.minValue));obj.value=obj.minValue;return false;
		}
	}
	return true;
}
Yh_Text.CheckNumber7=function(obj)
{
	if(isNaN(obj.value))
	{
		alert(OnNaN);obj.value="";obj.focus();return false;
	}
	if(obj.value.Trim()=="")
	{
		alert(OnNaN);obj.value="";obj.focus();return false;
	}
	if(parseFloat(obj.value)<=-1)
	{
		alert(parent_error1);obj.value="";obj.focus();return false;
	}
	obj.value=this.formatNumber(obj.value,2);
	return true;
}
Yh_Text.CheckNumber8=function(event,obj)
{
	event = (event) ? event : ((window.event) ? window.event : "") //兼容IE和Firefox获得keyBoardEvent对象
    var key = event.keyCode?event.keyCode:event.which; //兼容IE和Firefox获得keyBoardEvent对象的键值
	if(((key>=37) && (key<=40)) || (key!=16) && ((key>=48) && (key<=57))||((key>=95) && (key<=105))||(key==8)||(key==9)||(key==190)||(key==110)||(key==8)||(key==107)||(key==109))
	{
		return true;
	}
	else
	{	
		return false;
	}
}
Yh_Text.Change=function(obj)
{
	if(isNaN(obj.value))
	{
		alert(OnNaN);obj.value="";obj.focus();return false;
	}
	if(typeof(obj.format)!="undefined")
	{
		if(obj.format=="0.0")
			obj.value=this.formatNumber(obj.value,1);
		else if(obj.format=="0.00")
			obj.value=this.formatNumber(obj.value,2);
		else if(obj.format=="0.000")
			obj.value=this.formatNumber(obj.value,3);
		else if(parseFloat(obj.value)>=1 && obj.value.indexOf(obj.format)<=-1)
			obj.value="+"+obj.value;
		else if(obj.value.Trim()=="")
			obj.value=(typeof(obj.def)!="undefined"?obj.def:"");	
	}
	else
		obj.value=(obj.value!=""?parseFloat(obj.value):(typeof(obj.def)!="undefined"?obj.def:""));
	if(typeof(obj.maxValue)!="undefined")
	{
		if(parseFloat(obj.value)>parseFloat(obj.maxValue))
		{
			alert(error_maxValue.replace("%s",obj.maxValue));obj.value=obj.maxValue;return false;
		}
	}
	if(typeof(obj.minValue)!="undefined")
	{
		if(parseFloat(obj.value)<parseFloat(obj.minValue))
		{
			alert(error_minValue.replace("%s",obj.minValue));obj.value=obj.minValue;return false;
		}
	}
	if(typeof(obj.onFun)!="undefined")
	{
		eval(obj.onFun);
	}
	return true;
}
Yh_Text.Change2=function(obj)
{
	if(isNaN(obj.value))
	{
		alert(OnNaN);obj.value="";obj.focus();return false;
	}

	if(typeof(obj.format)!="undefined")
	{
		if(obj.format=="0.0")
			obj.value=this.formatNumber(obj.value,1);
		else if(obj.format=="0.00")
			obj.value=this.formatNumber(obj.value,2);
		else if(obj.format=="0.000")
			obj.value=this.formatNumber(obj.value,3);
		else if(parseFloat(obj.value)>=1 && obj.value.indexOf(obj.format)<=-1)
			obj.value="+"+obj.value;
		else if(obj.value.Trim()=="")
			obj.value=(typeof(obj.def)!="undefined"?obj.def:"");	
	}
	else
		obj.value=(obj.value!=""?parseFloat(obj.value):(typeof(obj.def)!="undefined"?obj.def:""));

	if(typeof(obj.maxValue)!="undefined")
	{
		if(parseFloat(obj.value)>parseFloat(obj.maxValue))
		{
			alert(error_maxValue.replace("%s",obj.maxValue));obj.value=obj.maxValue;return true;
		}
	}
	if(typeof(obj.maxValue2)!="undefined")
	{
		if((parseFloat(obj.value)<=-1) || (parseFloat(obj.value)>=1 && parseFloat(obj.value)<=9))
		{
			obj.value=parseFloat(obj.value)+"0";
		}
		else if((parseFloat(obj.value)>1000))
		{
			obj.value="1000";
		}
		else
		{
			obj.value=obj.value.substr(0,obj.value.length-1)+"0";
		}
	}
	if(typeof(obj.minValue)!="undefined")
	{
		if(parseFloat(obj.value)<parseFloat(obj.minValue))
		{
			alert(error_minValue.replace("%s",obj.minValue));obj.value=obj.minValue;return true;
		}
	}
	if(typeof(obj.onFun)!="undefined")
	{
		eval(obj.onFun);
	}
	return true;
}
Yh_Text.Change3=function(obj)
{
	if(isNaN(obj.value))
	{
		alert(OnNaN);obj.value="";obj.focus();return false;
	}
	obj.value=this.formatNumber(obj.value,1);
	if(obj.value.indexOf(".0")>=0) obj.value=parseInt(obj.value);

	return true;
}
Yh_Text.formatNumber=function (src,pos){   
	if(parseFloat(src)==0)
	{
		return src;
	}
	var result=Math.round(src*Math.pow(10, pos))/Math.pow(10, pos)+"";
    var index=result.indexOf('.');
    var res="000000000";
    if(index>=1)
    {
    	var xs=result.substr(index+1,result.length-index);
    	return result+res.substr(1,pos-xs.length);
    }
    else
    {
    	return result+"."+res.substr(1,pos);
    }   
}
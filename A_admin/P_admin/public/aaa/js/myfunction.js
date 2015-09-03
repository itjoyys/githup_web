function zc_change(flag,obj,value,p,pid,value2)
{
	if($("#"+flag+"_error").css("display")=="none" || typeof($("#"+flag+"_error").css("display"))=="undefined")
		$("#"+obj).load("../zc.php?p="+p+"&pid="+pid+"&f="+flag+"&v="+value+"&v2="+value2);	
	else
		$("#"+obj).text("");
}
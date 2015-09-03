

function un_set_bg_color()
{
$.each($("td a"),function(i,n){
//$(n).css("background-color","");
$(n).removeAttr("style");
});
}

function get_rand_name()
{
	//获取当前页的地址，产生cookie标识
	location_url=location.href;
	url_length=location_url.length;
	start_pos=location_url.lastIndexOf('/')+1;
    temp_str=location_url.substr(start_pos,url_length);
  
	temp_str=temp_str.replace('=','_');
	temp_str=temp_str.replace('.php?','_'); 
	temp_str=temp_str.replace('&','_'); 
	return temp_str;
}
 
get_rand_name();

$("document").ready(function(){

var cookie_name=get_rand_name();
 
var cookie_value="";
var temp_array=new Array();
var cookie_read=getCookie(cookie_name);

if(cookie_read != null)
{
  var temp_array=cookie_read.split('|');
  //alert(temp_array[2]);
} 

 
 //alert(cookie_read);

$.each($("td a"),function(i,n){
 
var now_point=parseFloat($(n).text());
var old_point=parseFloat(temp_array[i+1]);
 if(now_point>0)
 if(now_point>old_point)
    $(n).addClass("point_big"); 
  else if(now_point<old_point)
  $(n).addClass("point_min"); 
  
 cookie_value=cookie_value+"|"+$(n).text();
 
});
 
 SetCookie(cookie_name,cookie_value);
 setInterval("un_set_bg_color()",16000); //六秒后消息

})
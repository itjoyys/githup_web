// JavaScript Document
function setbet(typename_in,touzhuxiang_in,match_id_in,point_column_in,ben_add_in,is_lose,xx_in){
	if($(parent.topFrame.document).find("#username").html().length<=3){ //没有登录
		alert("登录后才能进行此操作");
		return ;
	}
	if(!arguments[5]) is_lose = 0;
    $.post("../ajaxleft/volleyball_match.php",{ball_sort:typename_in,match_id:match_id_in,touzhuxiang:touzhuxiang_in,point_column:point_column_in,ben_add:ben_add_in,xx:xx_in,rand:Math.random()},function (data){  parent.mem_order.bet(data); });
}

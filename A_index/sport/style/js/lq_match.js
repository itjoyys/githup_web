function setbet(typename_in,touzhuxiang_in,match_id_in,point_column_in,ben_add_in,is_lose,xx_in){
	if($(parent.topFrame.document).find("#username").html().length<=3){ //没有登录
		alert("登录后才能进行此操作");
		return ;
	}
	var touzhutype=$("#touzhutype").val();
	parent.mem_order.touzhus(touzhutype);
	if(touzhutype==1 && (point_column_in=="Match_Ao" || point_column_in=="Match_Ho")){ //让球串关
		var patrn	=	/[0-9.\/]{1,}-/;
		var pl		=	patrn.exec(touzhuxiang_in);
		patrn		=	/[0-9.\/]{1,}/;
		pl			=	patrn.exec(touzhuxiang_in);
		if(pl == "0"){
			alert("篮美标准盘不允许串关");
			return ;
		}
	}
	if(!arguments[5]) is_lose = 0;
	//var touzhutype=parent.leftFrame.touzhutype;
    $.post("../ajaxleft/lq_match.php",{ball_sort:typename_in,match_id:match_id_in,touzhuxiang:touzhuxiang_in,point_column:point_column_in,ben_add:ben_add_in,xx:xx_in,touzhutype:touzhutype,rand:Math.random()},function (data){  parent.mem_order.bet(data); });
}
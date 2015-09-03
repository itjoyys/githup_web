function setbet(typename_in,touzhuxiang_in,match_id_in,point_column_in,ben_add_in,is_lose_in,xx_in){

    if($(parent.topFrame.document).find("#username").html().length<=3){ //没有登录
        alert("登录后才能进行此操作");
        return ;
    }

  //  parent.mem_order.document.location="";
    url=(parent.mem_order.location.href)
    path=(parent.mem_order.location.pathname)
   // alert(path)
    if(path!='/sport/select.php') {
        parent.mem_order.location.href="../select.php";
    }
    if(!arguments[5]) is_lose_in = 0;
    //var touzhutype=parent.mem_order.touzhutype;

    var touzhutype=$("#touzhutype").val();
    parent.mem_order.touzhus(touzhutype);
    $.post("../ajaxleft/bet_match.php",{ball_sort:typename_in,match_id:match_id_in,touzhuxiang:touzhuxiang_in,point_column:point_column_in,ben_add:ben_add_in,is_lose:is_lose_in,xx:xx_in,touzhutype:touzhutype,rand:Math.random()},function (data){ parent.mem_order.bet(data); });
}
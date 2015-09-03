function setbet(typename_in,touzhuxiang_in,match_id_in,point_column_in,ben_add_in,is_lose_in,xx_in){

    /* var touzhutype=$("#touzhutype").val();
     parent.mem_order.touzhus(touzhutype);*/

    $.post("../sport/ajaxleft/bet_match.php",{ball_sort:typename_in,match_id:match_id_in,touzhuxiang:touzhuxiang_in,point_column:point_column_in,ben_add:ben_add_in,is_lose:is_lose_in,xx:xx_in,touzhutype:touzhutype,rand:Math.random()},function (data){
        bet(data);
    });
}
function bet(data){
    //下注函数

    //alert(ref)
    var bet_money = $("#bet_money").val();


    clear_input();
    $("#bet_money").val(bet_money);

    if(touzhutype==0){
        //quxiao_bet();
        // $("#maxmsg_div").show();
        // $("#bet_moneydiv").show();
        //$("#touzhudiv").hide();
        //$("#touzhudiv").html(data).fadeIn();
        $("#touzhudivs").hide();
        $("#touzhudiv").html(data).fadeIn();
        $("#bet_moneydiv").show();
        $("#xp").show();
        $("#ds_01_bet").hide();
        $("#kefu").hide();
        $("#left_ids").show();
        // $("#usersid").show();
        $('#bet_money').removeAttr("disabled");
        cg_count=1;
    }else{

        if(data.indexOf("滚球")>=0){
            alert("滚球未开放串关功能");
            return ;
        }
        if(data.indexOf("半全场")>=0){
            alert("半全场未开放串关功能");
            return ;
        }
        if(data.indexOf("角球數")>=0){
            alert("角球數未开放串关功能");
            return ;
        }
        if(data.indexOf("先開球")>=0){
            alert("先開球未开放串关功能");
            return ;
        }
        if(data.indexOf("入球数")>=0){
            alert("入球数未开放串关功能");
            return ;
        }
        if(data.indexOf("波胆")>=0){
            alert("波胆未开放串关功能");
            return ;
        }
        if(data.indexOf("网球")>=0){
            alert("网球未开放串关功能");
            return ;
        }
        if(data.indexOf("排球")>=0){
            alert("排球未开放串关功能");
            return ;
        }
        if(data.indexOf("棒球")>=0){
            alert("棒球未开放串关功能");
            return ;
        }
        if(data.indexOf("金融")>=0){
            alert("金融未开放串关功能");
            return ;
        }

        if(data.indexOf("主場")>=0){
            alert("同场赛事不能重复参与串关");
            return ;
        }

        for(i=0;i<cg_count;i++){
            var master_guest=$("input[name='master_guest[]']:eq("+i+")").val();
            var team=master_guest.split("VS");
            team_a=team[0].split(" -");
            team_b=team[1].split(" -");
            team_a=team_a[0].split("-");
            team_b=team_b[0].split("-");
            team_a=team_a[0].split("[");
            team_b=team_b[0].split("[");
            //alert(team_a[0]);
            //alert(team_b[0]);


        }

        cg_count++;

        $("#cg_num").html(cg_count);
        $("#cg_msg").show();
        $("#touzhudiv").fadeIn().append(data);


        if(cg_count>1)
        {
            $("#bet_moneydiv").show();
        }
        $("#maxmsg_div").show();
        //{

        $("#touzhudivs").hide();
        $("#xp").show();
        $("#cg_01_bet").show();
        $("#kefu").hide();
        $("#left_ids").hide();
        $("#usersid").hide();
    }


}
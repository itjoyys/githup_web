// JavaScript Document
var dbs  = null;
var data = null;
var window_hight	=	0; //窗口高度
var window_lsm		=	0; //窗口联赛名
function SportTrMenu(lsm){
    legimg=$('.leg_'+lsm).attr('id')
    if (legimg=='LegClose')$('.leg_'+lsm).attr('id','LegOpen')
    else $('.leg_'+lsm).attr('id','LegClose')
    $('.TR_'+lsm).toggle();
}

function loaded(league,thispage,p){
    var league = encodeURI(league);
    $.getJSON("ft_gunqiu_data.php?leaguename="+league+"&CurrPage="+thispage+"&callback=?",function(json){
        var pagecount	=	json.fy.p_page;
        var page		=	json.fy.page;
        var fenye		=	"";
        window_hight	=	json.dh;
        window_lsm		=	json.lsm;
        $('#window_lsm').html(window_lsm)
        if(dbs !=null){
            if(thispage==0 && p!='p'){
                data = dbs;
                dbs  = json.db;
            }else{
                dbs  = json.db;
                data = dbs;
            }
        }else{
            dbs  = json.db;
            data = dbs;
        }

        if(pagecount == "error1"){

            $("#datashow").html('<tr><td  colspan="9" align="center" style="background:#fff;">末登录,无法查看赛事信息.</td></tr>');
            $("#top").html("");
        }else if(pagecount == "error2"){
            $("#datashow").html('<tr><td  id=\"location\"  colspan="9" align="center" style="background:#fff;">对不起,您点击页面太快,请在60秒后进行操作</td></tr><script>check();</script>');
            $("#top").html("");
        }else if(pagecount == 0){
            $("#datashow").html('<tr><td  colspan="9" align="center" style="background:#fff;">暂无赛事</td></tr>');
            $("#top").html('');
        }else{
            //分页
            fenye+=(page+1)+"/"+pagecount+"页&nbsp;&nbsp;";
            fenye+="<select id=\"fenye\" onchange=\"fenye()\">";

            for(var i=0; i<pagecount; i++){
                if(i != page){
                    fenye+="<option  value=\""+i+"\">"+(i+1)+"</option>";
                }else{
                    fenye+="<option selected=\"selected\" value=\""+i+"\">"+(i+1)+"</option>";
                }

            }
            fenye+="</select>";
            $("#top").html(fenye);

            var zhggs=$('#touzhutype').val();
            if(zhggs==1){
                zhgg='<span style="float:right;background:#FFF0F7;">3串1</span>';
            }else{
                zhgg='';
            }


            var htmls="" +
            "<tr>" +
            "<th rowspan='2' class='gq_shijian'>时间</th>" +
           /* "<th rowspan='2' class='gq_bifen'>比分</th>" +*/
            "<th rowspan='2' class='duiwu'>主队 / 客队</th>" +
            "<th colspan='3' class='quanchang'>全场</th>" +
            "<th colspan='3' class='shangbanchang'>上半场</th>" +
                "</tr>" +
            "<tr><th class='duying'>独赢</th><th class='rangqiu'>让球</th><th class='daxiao'>大小</th><th class='duying'>独赢</th><th class='rangqiu'>让球</th><th class='daxiao'>大小</th></tr>";
            var lsm = "";
            var lsid=0;
            for(var i=0; i<dbs.length; i++){

                if(lsm!=dbs[i]["Match_Name"]){
                    lsid++;
                    lsm = dbs[i]["Match_Name"];
                    htmls+='<tr >';
                    htmls+='	<td  colspan="9"  class="b_hline">';
                    htmls+='		<table  border="0"  cellpadding="0"  cellspacing="0"  width="100%"><tbody>';
                    htmls+='			<tr>';
                    htmls+='				<td  class="legicon" >';
                    htmls+='				<span  id="'+lsm+'"  class="showleg"><span  id="LegOpen" class="leg_'+lsid+'" onclick="SportTrMenu(\''+lsid+'\')"></span> </span>';
                    htmls+='				</td>';
                    htmls+="				<td  class=\"leg_bar\" onclick=\"javascript:check_one('"+lsm+"');\">"+lsm+zhgg+"</td>";
                    htmls+='				<td  class="IN">';

                    htmls+='				</td>';
                    htmls+='			</tr>';
                    htmls+='		</tbody></table>';
                    htmls+='	</td>';
                    htmls+=' </tr>';

                }
                htmls+="<div onmouseover=\"this.className='d_over'\" onmouseout=\"this.className='d_out'\">";

                var home_let_point = (dbs[i]["Match_Ho"]!="@"?dbs[i]["Match_Ho"]:"0");
                if(home_let_point.length ==3){
                    home_let_point = home_let_point + "0";
                }
                if (home_let_point.length == 1){
                    home_let_point = home_let_point + ".00";
                }

                var home_dxp_point = (dbs[i]["Match_DxDpl"]!="@"?dbs[i]["Match_DxDpl"]:"0");
                if (home_dxp_point.length == 3){
                    home_dxp_point = home_dxp_point + "0";
                }
                if (home_dxp_point.length == 1){
                    home_dxp_point = home_dxp_point + ".00";
                }

                var sbc_home_let_point = (dbs[i]["Match_BHo"]!="@"?dbs[i]["Match_BHo"]:"0");
                if (sbc_home_let_point.length == 3){
                    sbc_home_let_point = sbc_home_let_point + "0";
                }
                if (sbc_home_let_point.length == 1){
                    sbc_home_let_point = sbc_home_let_point + ".00";
                }

                var sbc_home_dxp_point = (dbs[i]["Match_Bdpl"]!="@"?dbs[i]["Match_Bdpl"]:"0");
                if (sbc_home_dxp_point.length == 3){
                    sbc_home_dxp_point = sbc_home_dxp_point + "0";
                }
                if (sbc_home_dxp_point.length == 1){
                    sbc_home_dxp_point = sbc_home_dxp_point + ".00";
                }

                var guest_let_point = (dbs[i]["Match_Ao"]!="@"?dbs[i]["Match_Ao"]:"0");
                if (guest_let_point.length == 3){
                    guest_let_point = guest_let_point + "0";
                }
                if (guest_let_point.length == 1){
                    guest_let_point = guest_let_point + ".00";
                }

                var guest_dxp_point =(dbs[i]["Match_DxXpl"]!="0"?dbs[i]["Match_DxXpl"]:"0");
                if (guest_dxp_point.length == 3){
                    guest_dxp_point = guest_dxp_point + "0";
                }
                if (guest_dxp_point.length == 1){
                    guest_dxp_point = guest_dxp_point + ".00";
                }

                var sbc_guest_let_point = (dbs[i]["Match_BAo"]!="@"?dbs[i]["Match_BAo"]:"0");
                if (sbc_guest_let_point.length == 3){
                    sbc_guest_let_point = sbc_guest_let_point + "0";
                }
                if (sbc_guest_let_point.length == 1){
                    sbc_guest_let_point = sbc_guest_let_point + ".00";
                }

                var sbc_guest_dxp_point =(dbs[i]["Match_Bxpl"]!="@"?dbs[i]["Match_Bxpl"]:"0");
                if (sbc_guest_dxp_point.length == 3){
                    sbc_guest_dxp_point = sbc_guest_dxp_point + "0";
                }
                if (sbc_guest_dxp_point.length == 1){
                    sbc_guest_dxp_point = sbc_guest_dxp_point + ".00";
                }

                var fwin = (dbs[i]["Match_BzM"]);
                if (fwin.length == 3){
                    fwin = fwin + "0";
                }
                if (fwin.length == 1){
                    fwin = fwin + ".00";
                }

                var flose = (dbs[i]["Match_BzG"]!="@"?dbs[i]["Match_BzG"]:"0");
                if (flose.length == 3){
                    flose = flose + "0";
                }
                if (flose.length == 1){
                    flose = flose + ".00";
                }

                var fdraw = (dbs[i]["Match_BzH"]!="@"?dbs[i]["Match_BzH"]:"0");
                if (fdraw.length == 3){
                    fdraw = fdraw + "0";
                }
                if (fdraw.length == 1){
                    fdraw = fdraw + ".00";
                }

                var Hwin = (dbs[i]["Match_Bmdy"]!="@"?dbs[i]["Match_Bmdy"]:"0");
                if (Hwin.length == 3){
                    Hwin = Hwin + "0";
                }
                if (Hwin.length == 1){
                    Hwin = Hwin + ".00";
                }

                var Hlose = (dbs[i]["Match_Bgdy"]!="@"?dbs[i]["Match_Bgdy"]:"0");
                if (Hlose.length == 3){
                    Hlose = Hlose + "0";
                }
                if (Hlose.length == 1){
                    Hlose = Hlose + ".00";
                }

                var Hdraw = (dbs[i]["Match_Bhdy"]!="@"?dbs[i]["Match_Bhdy"]:"0");
                if (Hdraw.length == 3){
                    Hdraw = Hdraw + "0";
                }
                if (Hdraw.length == 1){
                    Hdraw = Hdraw + ".00";
                }
                if((dbs[i]["Match_Time"].indexOf("font")==-1 && (dbs[i]["Match_Time"].indexOf("a") !=-1 || dbs[i]["Match_Time"].indexOf("p") !=-1) && (dbs[i]["Match_RGG"] !=null?dbs[i]["Match_RGG"]==0:false) && (dbs[i]["Match_DxGG"] !=null?dbs[i]["Match_DxGG"]=="O2.5":false)) ||(dbs[i]["Match_DxGG"] =="O0" || dbs[i]["Match_Bdxpk"] =="O0")){
                    var temphrgl="";
                    var tempgrgl="";
                    var temprsgl="";
                }else{

                    var temphrgl="<a href=\"javascript:void(0)\" title=\""+dbs[i]["Match_Master"]+"\" onclick=\"javascript:setbet('足球滚球','让球-"+(dbs[i]["Match_ShowType"] =="H"?"主让":"客让")+dbs[i]["Match_RGG"]+"-"+dbs[i]["Match_Master"] + "','" + dbs[i]["Match_ID"] + "','Match_Ho','1','1','"+ dbs[i]["Match_Master"] + "');\"  style='"+(dbs[i]["Match_Ho"]!=data[i]["Match_Ho"]&& data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00":"")+"'>" + (home_let_point!="0.00"?home_let_point:"") +  "</a>";
                    var tempgrgl="<a href=\"javascript:void(0)\" title=\""+dbs[i]["Match_Guest"]+"\" onclick=\"javascript:setbet('足球滚球','让球-"+(dbs[i]["Match_ShowType"] =="H"?"主让":"客让")+dbs[i]["Match_RGG"]+"-"+dbs[i]["Match_Guest"] + "','" + dbs[i]["Match_ID"] + "','Match_Ao','1','1','"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Ao"]!=data[i]["Match_Ao"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00":"")+"'>" + (guest_let_point !="0.00"?guest_let_point:"") + "</a>";
                    var temprsgl=dbs[i]["Match_RGG"];
                    if(dbs[i]["Match_RGG"] !=null && dbs[i]["Match_Time"] !=null)
                    {
                        if(dbs[i]["Match_RGG"]=="0" && (dbs[i]["Match_Time"]=="00" || dbs[i]["Match_Time"] =="01"))
                        {
                            var temphrgl="";
                            var tempgrgl="";
                            var temprsgl="";
                        }
                    }
                    var tempshrgl="<a href=\"javascript:void(0)\" title=\""+dbs[i]["Match_Master"]+"\" onclick=\"javascript:setbet('足球滚球','上半场让球-"+(dbs[i]["Match_ShowType"] =="H"?"主让":"客让")+dbs[i]["Match_BRpk"]+"-"+dbs[i]["Match_Master"] + "','" + dbs[i]["Match_ID"] + "','Match_BHo','1','1','"+ dbs[i]["Match_Master"] + "');\" style='"+(dbs[i]["Match_BHo"]!=data[i]["Match_BHo"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00":"")+"'>" + (sbc_home_let_point!="0.00"?sbc_home_let_point:"") + "</a>";
                    var tempsgrgl="<a href=\"javascript:void(0)\" title=\""+dbs[i]["Match_Guest"]+"\" onclick=\"javascript:setbet('足球滚球','上半场让球-"+(dbs[i]["Match_ShowType"] =="H"?"主让":"客让")+dbs[i]["Match_BRpk"]+"-"+dbs[i]["Match_Guest"] + "','" + dbs[i]["Match_ID"] + "','Match_BAo','1','1','"+dbs[i]["Match_Guest"]+"');\"  style='"+(dbs[i]["Match_BAo"]!=data[i]["Match_BAo"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00":"")+"'>" + (sbc_guest_let_point !="0.00"?sbc_guest_let_point:"") + "</a>";
                    var tempsrsgl=dbs[i]["Match_BRpk"];
                    if(dbs[i]["Match_BRpk"] !=null && dbs[i]["Match_Time"] !=null)
                    {
                        if(dbs[i]["Match_BRpk"]=="0" && (dbs[i]["Match_Time"]=="00" || dbs[i]["Match_Time"] =="01"))
                        {
                            var tempshrgl="";
                            var tempsgrgl="";
                            var tempsrsgl="";
                        }
                    }
                    var tempfwin="<a href=\"javascript:void(0)\" title=\""+dbs[i]["Match_Master"]+"\" onclick=\"javascript:setbet('足球滚球','标准盘-"+ dbs[i]["Match_Master"] +"-独赢','" + dbs[i]["Match_ID"] + "','Match_BzM','0','1','"+ dbs[i]["Match_Master"] + "');\" style='"+(dbs[i]["Match_BzM"]!=data[i]["Match_BzM"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00":"")+"'>" + (fwin!="0.00"?fwin:"") + "</a>";
                    var tempflose="<a href=\"javascript:void(0)\" title=\""+dbs[i]["Match_Guest"]+"\" onclick=\"javascript:setbet('足球滚球','标准盘-"+ dbs[i]["Match_Guest"] +"-独赢','" + dbs[i]["Match_ID"] + "','Match_BzG','0','1','"+ dbs[i]["Match_Guest"] + "');\"  style='"+(dbs[i]["Match_BzG"]!=data[i]["Match_BzG"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00":"")+"'>" + (flose !="0.00"?flose:"") + "</a>";
                    var tempfdraw="<a href=\"javascript:void(0)\" title=\"和局\" onclick=\"javascript:setbet('足球滚球','标准盘-和局','" + dbs[i]["Match_ID"] + "','Match_BzH','0','1','和局');\" style='"+(dbs[i]["Match_BzH"]!=data[i]["Match_BzH"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00":"")+"'>" + (fdraw !="0.00"?fdraw:"") + "</a>";
                    var temphwin="<a href=\"javascript:void(0)\" title=\""+dbs[i]["Match_Master"]+"\" onclick=\"javascript:setbet('足球滚球','上半场标准盘-"+ dbs[i]["Match_Master"] +"-独赢','" + dbs[i]["Match_ID"] + "','Match_Bmdy','0','1','"+ dbs[i]["Match_Master"] + "');\" style='"+(dbs[i]["Match_BzM"]!=data[i]["Match_BzM"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00":"")+"'>" + (Hwin!="0.00"?Hwin:"") + "</a>";
                    var temphlose="<a href=\"javascript:void(0)\" title=\""+dbs[i]["Match_Guest"]+"\" onclick=\"javascript:setbet('足球滚球','上半场标准盘-"+ dbs[i]["Match_Guest"] +"-独赢','" + dbs[i]["Match_ID"] + "','Match_Bgdy','0','1','"+ dbs[i]["Match_Guest"] + "');\"  style='"+(dbs[i]["Match_BzG"]!=data[i]["Match_BzG"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00":"")+"'>" + (Hlose !="0.00"?Hlose:"") + "</a>";
                    var temphdraw="<a href=\"javascript:void(0)\" title=\"和局\" onclick=\"javascript:setbet('足球滚球','上半场标准盘-和局','" + dbs[i]["Match_ID"] + "','Match_Bhdy','0','1','和局');\" style='"+(dbs[i]["Match_BzH"]!=data[i]["Match_BzH"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00":"")+"'>" + (Hdraw !="0.00"?Hdraw:"") + "</a>";

                    var bf = dbs[i]["Match_Time"]; //时间
                 /*   if(bf == "45.5") {
                        bf = "中场";
                        ScoreTime="中场";
                    }
                    else  if(bf.indexOf("半场")>1) ScoreTime = "半场";
                    else  if(bf.length>4) ScoreTime = bf;
                    else if(bf < "45.5") ScoreTime = "上半场 "+bf+"'";
                    else  if(bf > "45.5") ScoreTime = "下半场 "+(bf-45)+"'";*/
                    ScoreTime=bf;
                     htmls+="<tr class='bian_out "+dbs[i]["Match_ID"]+" TR_"+lsid+"' >";
                    htmls+="    <td class='b_cen'>" +
                        "<div class='ScoreTime'>"+ScoreTime+"" +
                        "<div>" + dbs[i]["Match_NowScore"].replace(':',' - ') + "</div></div></td>";
                  /*  htmls+="    <td class='b_cen'>" + dbs[i]["Match_NowScore"] + "</td>";*/
                    htmls+="    <td class='team_name '><span class='zhu'>" + dbs[i]["Match_Master"]+ "</span>&nbsp;"+ (dbs[i]["Match_HRedCard"] !="0"?"<img src='../images/" + dbs[i]["Match_HRedCard"] + ".gif' width='12' height='13' border='0'/>":"") + "<br><span class='ke'>" + dbs[i]["Match_Guest"] + "</span>&nbsp;" + (dbs[i]["Match_GRedCard"] !="0"?"<img src='../images/" + dbs[i]["Match_GRedCard"] + ".gif' width='12' height='13' border='0' />":"")+ "<br><span class='he'>和局</span></td>";
                    htmls+="    <td class='b_cen'>"+tempfwin+"<br>"+tempflose+"<br>"+tempfdraw+"</td>";
                    htmls+="    <td class='b_cen'><div><span class='odds'  style='float: right;'>"+(data[i]["Match_Ho"] !=null?temphrgl:"")+"</span><span class='pankou'  style='float: left;'>" + (dbs[i]["Match_ShowType"]=="H" && dbs[i]["Match_Ho"] !="0"?temprsgl:"") + "</span><br><span class='odds'  style='float: right;'>"+(dbs[i]["Match_Ao"] !=null?tempgrgl:"")+"</span><span class='pankou' style='float: left;'>" + (dbs[i]["Match_ShowType"]=="C" && dbs[i]["Match_Ho"] !="0"?temprsgl:"") + "</span><br>&nbsp;</td>";
                    htmls+="    <td class='b_cen'><div><span class='odds'  style='float: right;'>"+(dbs[i]["Match_DxDpl"] !=null?"<a href=\"javascript:void(0)\" title=\"大\" onclick=\"javascript:setbet('足球滚球','大小-"+dbs[i]["Match_DxGG"]+"','" + dbs[i]["Match_ID"] + "','Match_DxDpl','1','1','"+dbs[i]["Match_DxGG"]+"');\"  style='"+(dbs[i]["Match_DxDpl"]!=data[i]["Match_DxDpl"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00":"")+"'>" + (home_dxp_point!="0.00"?home_dxp_point:"") + "</a>":"")+"</span><span class='pankou'  style='float: left;'>" + (dbs[i]["Match_DxGG"]!="O"?dbs[i]["Match_DxGG"].replace("O","大"):"") + "</span><br><span class='odds' style='float: right;'>"+(dbs[i]["Match_DxXpl"] !=null?"<a href=\"javascript:void(0)\" title=\"小\" onclick=\"javascript:setbet('足球滚球','大小-"+dbs[i]["Match_DxGG1"]+"','" + dbs[i]["Match_ID"] + "','Match_DxXpl','1','1','"+dbs[i]["Match_DxGG1"]+"');\" style='"+(dbs[i]["Match_DxXpl"]!=data[i]["Match_DxXpl"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00":"")+"'>" + (guest_dxp_point !="0.00"?guest_dxp_point:"") + "</a>":"")+"</span><span class='pankou' style='float: left;'>" +(dbs[i]["Match_DxGG1"]!="U"?dbs[i]["Match_DxGG1"].replace("U","小"):"") + "</span><br>&nbsp;</td>";
                    htmls+="    <td class='b_1st'>"+temphwin+"<br>"+temphlose+"<br>"+temphdraw+"</td>";
                    htmls+="    <td class='b_1st'><div class='rangqiu_odds'><span class='odds' style='float: right;'>"+(dbs[i]["Match_BHo"]!=null?tempshrgl:"")+"</span><span class='pankou' style='float: left;'>" +(dbs[i]["Match_Hr_ShowType"]=="H" && dbs[i]["Match_BAo"] !="0"?tempsrsgl:"") + "</span><br><span class='odds' style='float: right;'>"+(dbs[i]["Match_BAo"] !=null?tempsgrgl:"")+"</span><span class='pankou' style='float: left;'>" + (dbs[i]["Match_Hr_ShowType"]=="C" && dbs[i]["Match_BAo"] !="0"?tempsrsgl:"")+ "</span><br>&nbsp;</td>";
                    htmls+="    <td class='b_1st'><div class='rangqiu_odds'><span class='odds' style='float: right;'>"+(dbs[i]["Match_Bdpl"] !=null?"<a href=\"javascript:void(0)\" title=\"大\" onclick=\"javascript:setbet('足球滚球','上半场大小-"+dbs[i]["Match_Bdxpk"]+"','" + dbs[i]["Match_ID"] + "','Match_Bdpl','1','1','"+dbs[i]["Match_Bdxpk"]+"');\" style='"+(dbs[i]["Match_Bdpl"]!=data[i]["Match_Bdpl"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00":"")+"'>" + (sbc_home_dxp_point!="0.00"?sbc_home_dxp_point:"") + "</a>":"")+"</span><span class='pankou'   style='float: left;'>" + (dbs[i]["Match_Bdxpk"]!="O"?dbs[i]["Match_Bdxpk"].replace("O","大"):"") + "</span><br><span class='odds'style='float: right;'>"+(dbs[i]["Match_Bxpl"] !=null ?"<a href=\"javascript:void(0)\" title=\"小\" onclick=\"javascript:setbet('足球滚球','上半场大小-"+dbs[i]["Match_Bdxpk2"]+"','" + dbs[i]["Match_ID"] + "','Match_Bxpl','1','1','"+dbs[i]["Match_Bdxpk2"]+"');\" style='"+(dbs[i]["Match_Bxpl"]!=data[i]["Match_Bxpl"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00":"")+"'>" + (sbc_guest_dxp_point!="0.00"?sbc_guest_dxp_point:"") + "</a>":"")+"</span><span class='pankou' style='float: left;'>" + (dbs[i]["Match_Bdxpk2"]!="U"?dbs[i]["Match_Bdxpk2"].replace("U","小"):"")+ "</span><br>&nbsp;</td>";
                    htmls+=" </tr>";
                }
            }

         //   htmls+="</table>";
            if(htmls == "<table border='0' cellspacing='1' cellpadding='0' bgcolor='#ACACAC' class='box'><tr><th rowspan='2' class='gq_shijian'>时间</th><th rowspan='2' class='gq_bifen'>比分</th><th rowspan='2' class='duiwu'>主队 / 客队</th><th colspan='3' class='quanchang'>全场</th><th colspan='3' class='shangbanchang'>上半场</th></tr><tr><th class='duying'>1X2</th><th class='rangqiu'>让球</th><th class='daxiao'>大小</th><th class='duying'>1X2</th><th class='rangqiu'>让球</th><th class='daxiao'>大小</th></tr></table>"){
                htmls = "<table border='0' cellspacing='1' cellpadding='0' bgcolor='#ACACAC' class='box'><tr><th rowspan='2' class='gq_shijian'>时间</th><th rowspan='2' class='gq_bifen'>比分</th><th rowspan='2' class='duiwu'>主队 / 客队</th><th colspan='3' class='quanchang'>全场</th><th colspan='3' class='shangbanchang'>上半场</th></tr><tr><th class='duying'>1X2</th><th class='rangqiu'>让球</th><th class='daxiao'>大小</th><th class='duying'>1X2</th><th class='rangqiu'>让球</th><th class='daxiao'>大小</th></tr><tr><td height='100' colspan='9' align='center' bgcolor='#FFFFFF'>暂无任何赛事</td></tr></table>";
            }
            $("#datashow").html(htmls);
        }

        document.documentElement.scrollTop	=	$("#top_f5").val(); //导航标题高度
        $("#top_f5").val('0');
        gdt();
    });
}


/*
 $(document).ready(function(){
 $("#xzls").click(function(){ //选择联赛

 if(window_lsm.length > 2000){
 if(window.XMLHttpRequest){ //Mozilla, Safari, IE7
 if(!window.ActiveXObject){ // Mozilla, Safari,
 JqueryDialog.Open('足球滚球', 'dialog.php?lsm='+window_lsm, 600, window_hight);
 }else{ //IE7
 JqueryDialog.Open('足球滚球', 'dialog.php?lsm=zqds', 600, window_hight);
 }
 }else{ //IE6
 JqueryDialog.Open('足球滚球', 'dialog.php?lsm=zqds', 600, window_hight);
 }
 }else{
 JqueryDialog.Open('足球滚球', 'dialog.php?lsm='+window_lsm, 600, window_hight);
 }
 });
 });
 */


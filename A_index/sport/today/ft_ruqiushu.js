// JavaScript Document
var dbs  = null;
var data = null;
var window_hight	=	0; //窗口高度
var window_lsm		=	0; //窗口联赛名
function SportTrMenu(lsm){
    $('#TR_01-'+lsm+',#TR1_01-'+lsm+',#TR2_01-'+lsm).toggle();
}
function loaded(league,thispage,p){
	var league = encodeURI(league);
	$.getJSON("ft_ruqiushu_data.php?leaguename="+league+"&CurrPage="+thispage+"&callback=?",function(json){
		var pagecount	=	json.fy.p_page;
		var page		=	json.fy.page;
		var fenye		=	"";
		window_hight	=	json.dh;
		window_lsm		=	json.lsm;
		
		if(dbs !=null) {
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
			
			$("#datashow").html('<tr><td  colspan="6" align="center" style="background:#fff;">末登录,无法查看赛事信息.</td></tr>');
			$("#top").html("");
		}else if(pagecount == "error2"){
			$("#datashow").html('<tr><td  id=\"location\"  colspan="6" align="center" style="background:#fff;">对不起,您点击页面太快,请在60秒后进行操作</td></tr><script>check();</script>');
			$("#top").html("");
		}else if(pagecount == 0){
			$("#datashow").html('<tr><td  colspan="6" align="center" style="background:#fff;">暂无赛事</td></tr>');
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
			
			var htmls="";
			var lsm = "";
            var lsid=0;
			for(var i=0; i<dbs.length; i++){
				if(dbs[i]["Match_Bd10"] !="0" || dbs[i]["Match_Total01Pl"] !="0" || dbs[i]["Match_Total23Pl"] !="0" || dbs[i]["Match_Total46Pl"] !="0" || dbs[i]["Match_Total7upPl"] !="0"){
				if(lsm != dbs[i]["Match_Name"]){
                    lsid++;
					lsm = dbs[i]["Match_Name"];
					htmls+='<tr>';
					htmls+='	<td  colspan="6"  class="b_hline">';
					htmls+='		<table  border="0"  cellpadding="0"  cellspacing="0"  width="100%"><tbody>';
					htmls+='			<tr>';
					htmls+='				<td  class="legicon"  onclick="SportTrMenu(\''+lsid+'\')">';
					htmls+='				<span  id="'+lsm+'"  class="showleg"><span  id="LegOpen"></span> </span>';
					htmls+='				</td>';
					htmls+="				<td  class=\"leg_bar\" onclick=\"javascript:check_one('"+lsm+"');\">"+lsm+"</td>";
					htmls+='				<td  class="IN">';
										
					htmls+='				</td>';
					htmls+='			</tr>';
					htmls+='		</tbody></table>';
					htmls+='	</td>';
					htmls+=' </tr>';
				}
				
				htmls+="<tr  id=\"TR_01-"+lsid+"\"    *class*=\"\">";
				htmls+="		<td class=\"b_cen\" style=\"width:18%\">"+dbs[i]["Match_Date"]+"</td>";
				htmls+="		<td class=\"b_cen\" style=\"width:20%\">"+dbs[i]["Match_Master"]+"<br>"+dbs[i]["Match_Guest"]+"<br>和局 </td>";
				//htmls+="		<td class=\"b_cen\">"+((dbs[i]["Match_BzM"] !=null && dbs[i]["Match_BzM"] !="0")?"<a href=\"javascript:void(0)\" title=\""+dbs[i]["Match_Master"]+"\" onclick=\"setbet('足球单式','标准盘-"+ dbs[i]["Match_Master"] +"-独赢','" + dbs[i]["Match_ID"] + "','Match_BzM','0',0,'"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_BzM"]!=data[i]["Match_BzM"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+formatNumber(dbs[i]["Match_BzM"],2)+"</a>":"")+"</td>";
				htmls+="		<td class=\"b_cen\">"+((dbs[i]["Match_Total01Pl"] !=null && dbs[i]["Match_Total01Pl"] !="0")?"<a href=\"javascript:void(0)\" title=\"0~1\" onclick=\"setbet('足球单式','入球数-0~1','" + dbs[i]["Match_ID"] + "','Match_Total01Pl','0',0,'0~1');\" style='"+(dbs[i]["Match_Total01Pl"]!=data[i]["Match_Total01Pl"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+formatNumber(dbs[i]["Match_Total01Pl"],2)+"</a>":"")+"</td>";
				htmls+="		<td class=\"b_cen\">"+((dbs[i]["Match_Total23Pl"] !=null && dbs[i]["Match_Total23Pl"] !="0")?"<a href=\"javascript:void(0)\" title=\"2~3\" onclick=\"setbet('足球单式','入球数-2~3','" + dbs[i]["Match_ID"] + "','Match_Total23Pl','0',0,'2~3');\" style='"+(dbs[i]["Match_Total23Pl"]!=data[i]["Match_Total23Pl"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+formatNumber(dbs[i]["Match_Total23Pl"],2)+"</a>":"")+"</td>";
				htmls+="		<td class=\"b_cen\">"+((dbs[i]["Match_Total46Pl"] !=null && dbs[i]["Match_Total46Pl"] !="0")?"<a href=\"javascript:void(0)\" title=\"4~6\" onclick=\"setbet('足球单式','入球数-4~6','" + dbs[i]["Match_ID"] + "','Match_Total46Pl','0',0,'4~6');\" style='"+(dbs[i]["Match_Total46Pl"]!=data[i]["Match_Total46Pl"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+formatNumber(dbs[i]["Match_Total46Pl"],2)+"</a>":"")+"</td>";
				htmls+="		<td class=\"b_cen\">"+((dbs[i]["Match_Total7upPl"] !=null && dbs[i]["Match_Total7upPl"] !="0")?"<a href=\"javascript:void(0)\" title=\"7以上\" onclick=\"setbet('足球单式','入球数-7UP','" + dbs[i]["Match_ID"] + "','Match_Total7upPl','0',0,'7UP');\" style='"+(dbs[i]["Match_Total7upPl"]!=data[i]["Match_Total7upPl"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+formatNumber(dbs[i]["Match_Total7upPl"],2)+"</a>":"")+"</td>";
						
						
				htmls+="  </tr> ";
				//htmls+="  <tr> ";
				//htmls+="  <td class=\"b_cen\">"+(dbs[i]["Match_BzG"] !=null && dbs[i]["Match_BzG"] !="0"?"<a href=\"javascript:void(0)\" title=\""+dbs[i]["Match_Guest"]+"\" onclick=\"setbet('足球单式','标准盘-"+ dbs[i]["Match_Guest"] +"-独赢','" + dbs[i]["Match_ID"] + "','Match_BzG','0',0,'"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_BzG"]!=data[i]["Match_BzG"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+formatNumber(dbs[i]["Match_BzG"],2)+"</a>":"")+"</td> ";
				//htmls+="  </tr> ";
				//htmls+="  <tr> ";
				//htmls+="  <td class=\"b_cen\">"+((dbs[i]["Match_BzH"] !=null && dbs[i]["Match_BzH"] !="0")?"<a href=\"javascript:void(0)\" title=\"和局\" onclick=\"setbet('足球单式','标准盘-和局','" + dbs[i]["Match_ID"] + "','Match_BzH','0',0,'和局');\" style='"+(dbs[i]["Match_BzH"]!=data[i]["Match_BzH"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+formatNumber(dbs[i]["Match_BzH"],2)+"</a>":"")+"</td> ";
				//htmls+="  </tr> ";
				
				
				 
			}
			}
			htmls+="";
			if(htmls == ""){
				htmls = '<tr><td  colspan="6" align="center" style="background:#fff;">暂无赛事</td></tr>';
			}
			$("#datashow").html(htmls);
		}
		document.documentElement.scrollTop	=	$("#top_f5").val(); //导航标题高度
		$("#top_f5").val('0');
		gdt();
	})
}
/*
$(document).ready(function(){
	$("#xzls").click(function(){ //选择联赛
		JqueryDialog.Open('足球入球数', 'dialog.php?lsm='+window_lsm, 600, window_hight);
	});
});*/
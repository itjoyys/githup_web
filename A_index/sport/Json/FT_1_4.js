// JavaScript Document
var dbs  = null;
var data = null;
var window_hight	=	0; //窗口高度
var window_lsm		=	0; //窗口联赛名
function loaded(league,thispage,p){
	var league = encodeURI(league);
	$.getJSON("json/ft_1_4.php?leaguename="+league+"&CurrPage="+thispage+"&callback=?",function(json){
		var pagecount	=	json.fy.p_page;
		var page		=	json.fy.page;
		var fenye		=	"";
		window_hight	=	json.dh;
		window_lsm		=	json.lsm;
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
		if(pagecount == 0){
			$("#datashow").html("<table border='0' cellspacing='1' cellpadding='0' bgcolor='#ACACAC' class='box'><tr><th class='shijian'>时间</th><th class='duiwu'>主队 / 客队</th><th class='bodan'>1:0</th><th class='bodan'>2:0</th><th class='bodan'>2:1</th><th class='bodan'>3:0</th><th class='bodan'>3:1</th><th class='bodan'>3:2</th><th class='bodan'>4:0</th><th class='bodan'>4:1</th><th class='bodan'>4:2</th><th class='bodan'>4:3</th><th class='bodan'>0:0</th><th class='bodan'>1:1</th><th class='bodan'>2:2</th><th class='bodan'>3:3</th><th class='bodan'>4:4</th><th class='bodan'>UP5</th></tr><tr><td height='100' colspan='18' align='center' bgcolor='#FFFFFF' style='line-height:25px;'>暂无任何赛事</td></tr></table>");
			$("#top").html('');
		}else{
			for(var i=0; i<pagecount; i++){
				if(i != page){
					fenye+="<li><a href='javascript:NumPage(" + i + ");' id=\"page_this\">" + (i+1) + "</a></li>";
				}else{
					fenye+="<li class='i'><a href='javascript:NumPage(" + i + ");' id=\"page_this\"><font color=#FFFFFF>" + (i+1) + "</font></a></li>";
				}
			}
			$("#top").html(fenye);
			
			var htmls="<table border='0' cellspacing='1' cellpadding='0' bgcolor='#ACACAC' class='box'><tr><th class='shijian'>时间</th><th class='duiwu'>主队 / 客队</th><th class='bodan'>1:0</th><th class='bodan'>2:0</th><th class='bodan'>2:1</th><th class='bodan'>3:0</th><th class='bodan'>3:1</th><th class='bodan'>3:2</th><th class='bodan'>4:0</th><th class='bodan'>4:1</th><th class='bodan'>4:2</th><th class='bodan'>4:3</th><th class='bodan'>0:0</th><th class='bodan'>1:1</th><th class='bodan'>2:2</th><th class='bodan'>3:3</th><th class='bodan'>4:4</th><th class='bodan'>UP5</th></tr>";
			var lsm = "";
			for(var i=0; i<dbs.length; i++){
				if(lsm != dbs[i]["Match_Name"]){
					lsm = dbs[i]["Match_Name"];
					htmls+="<tr>";
					htmls+="<td colspan='18' class='liansai'><a href=\"javascript:void(0)\" title='选择 >> "+lsm+"' onclick=\"javascript:check_one('"+lsm+"');\" >"+lsm+"</a></td>";
					htmls+="</tr>";
				}
				htmls +="<tr class='bian_out' onMouseOver=\"this.className='bian_over'\" onMouseOut=\"this.className='bian_out'\">";
				htmls +="  <td class='zhong'>"+dbs[i]["Match_Date"]+"</td>";
				htmls +="  <td class='line'><span class='zhu'>"+dbs[i]["Match_Master"]+"</span><br><span class='ke'>"+dbs[i]["Match_Guest"]+"</span></td>";
				htmls +="  <td class='zhong line'>"+((dbs[i]["Match_Bd10"] !=null && dbs[i]["Match_Bd10"]!="0") ? "<a onclick=\"javascript:setbet('足球单式','波胆-1:0','" + dbs[i]["Match_ID"] + "','Match_Bd10','0','0','"+dbs[i]["Match_Master"]+"');\" href=\"javascript:void(0)\" title=\"1:0\" style='"+(dbs[i]["Match_Bd10"]!=data[i]["Match_Bd10"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd10"]+"</a>":"")+"<br>"+((dbs[i]["Match_Bdg10"] !=null && dbs[i]["Match_Bdg10"] !="0")?"<a href=\"javascript:void(0)\" title=\"0:1\" onclick=\"javascript:setbet('足球单式','波胆-0:1','" + dbs[i]["Match_ID"] + "','Match_Bdg10','0','0','"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg10"]!=data[i]["Match_Bdg10"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bdg10"]+"</a>":"")+"</td>";
				htmls +="  <td class='zhong line'>"+((dbs[i]["Match_Bd20"] !=null && dbs[i]["Match_Bd20"] !="0") ? "<a href=\"javascript:void(0)\" title=\"2:0\" onclick=\"javascript:setbet('足球单式','波胆-2:0','" + dbs[i]["Match_ID"] + "','Match_Bd20','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Bd20"]!=data[i]["Match_Bd20"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd20"]+"</a>":"")+"<br>"+((dbs[i]["Match_Bdg20"] !=null && dbs[i]["Match_Bdg20"] !="0")?"<a href=\"javascript:void(0)\" title=\"0:2\" onclick=\"javascript:setbet('足球单式','波胆-0:2','" + dbs[i]["Match_ID"] + "','Match_Bdg20','0','0','"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg20"]!=data[i]["Match_Bdg20"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bdg20"]+"</a>":"")+"</td>";
				htmls +="  <td class='zhong line'>"+((dbs[i]["Match_Bd21"] !=null && dbs[i]["Match_Bd21"] !="0")?"<a href=\"javascript:void(0)\" title=\"2:1\" onclick=\"javascript:setbet('足球单式','波胆-2:1','" + dbs[i]["Match_ID"] + "','Match_Bd21','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Bd21"]!=data[i]["Match_Bd21"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+(dbs[i]["Match_Bd21"])+"</a>":"")+"<br>"+((dbs[i]["Match_Bdg21"] !=null && dbs[i]["Match_Bdg21"] !="0")?"<a href=\"javascript:void(0)\" title=\"1:2\" onclick=\"javascript:setbet('足球单式','波胆-1:2','" + dbs[i]["Match_ID"] + "','Match_Bdg21','0','0','"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg21"]!=data[i]["Match_Bdg21"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bdg21"]+"</a>":"")+"</td>";
				htmls +="  <td class='zhong line'>"+((dbs[i]["Match_Bd30"] !=null && dbs[i]["Match_Bd30"] !="0")?"<a href=\"javascript:void(0)\" title=\"3:0\" onclick=\"javascript:setbet('足球单式','波胆-3:0','" + dbs[i]["Match_ID"] + "','Match_Bd30','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Bd30"]!=data[i]["Match_Bd30"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+(dbs[i]["Match_Bd30"])+"</a>":"")+"<br>"+((dbs[i]["Match_Bdg30"] !=null && dbs[i]["Match_Bdg30"] !="0")?"<a href=\"javascript:void(0)\" title=\"0:3\" onclick=\"javascript:setbet('足球单式','波胆-0:3','" + dbs[i]["Match_ID"] + "','Match_Bdg30','0','0','"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg30"]!=data[i]["Match_Bdg30"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bdg30"]+"</a>":"")+"</td>";
				htmls +="  <td class='zhong line'>"+((dbs[i]["Match_Bd31"] !=null && dbs[i]["Match_Bd31"] !="0")?"<a href=\"javascript:void(0)\" title=\"3:1\" onclick=\"javascript:setbet('足球单式','波胆-3:1','" + dbs[i]["Match_ID"] + "','Match_Bd31','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Bd31"]!=data[i]["Match_Bd31"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd31"]+"</a>":"")+"<br>"+((dbs[i]["Match_Bdg31"] !=null && dbs[i]["Match_Bdg31"] !="0")?"<a href=\"javascript:void(0)\" title=\"1:3\" onclick=\"javascript:setbet('足球单式','波胆-1:3','" + dbs[i]["Match_ID"] + "','Match_Bdg31','0','0','"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg31"]!=data[i]["Match_Bdg31"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bdg31"]+"</a>":"")+"</td>";
				htmls +="  <td class='zhong line'>"+((dbs[i]["Match_Bd32"] !=null && dbs[i]["Match_Bd32"] !="0")?"<a href=\"javascript:void(0)\" title=\"3:2\" onclick=\"javascript:setbet('足球单式','波胆-3:2','" + dbs[i]["Match_ID"] + "','Match_Bd32','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Bd32"]!=data[i]["Match_Bd32"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd32"]+"</a>":"")+"<br>"+((dbs[i]["Match_Bdg32"] !=null && dbs[i]["Match_Bdg32"] !="0")?"<a href=\"javascript:void(0)\" title=\"2:3\" onclick=\"javascript:setbet('足球单式','波胆-2:3','" + dbs[i]["Match_ID"] + "','Match_Bdg32','0','0','"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg32"]!=data[i]["Match_Bdg32"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bdg32"]+"</a>":"")+"</td>";
				htmls +="  <td class='zhong line'>"+((dbs[i]["Match_Bd40"] !=null && dbs[i]["Match_Bd40"] !="0")?"<a href=\"javascript:void(0)\" title=\"4:0\" onclick=\"javascript:setbet('足球单式','波胆-4:0','" + dbs[i]["Match_ID"] + "','Match_Bd40','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Bd40"]!=data[i]["Match_Bd40"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd40"]+"</a>":"")+"<br>"+((dbs[i]["Match_Bdg40"] !=null && dbs[i]["Match_Bdg40"] !="0")?"<a href=\"javascript:void(0)\" title=\"0:4\" onclick=\"javascript:setbet('足球单式','波胆-0:4','" + dbs[i]["Match_ID"] + "','Match_Bdg40','0','0','"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg40"]!=data[i]["Match_Bdg40"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bdg40"]+"</a>":"")+"</td>";
				htmls +="  <td class='zhong line'>"+((dbs[i]["Match_Bd41"] !=null && dbs[i]["Match_Bd41"] !="0")?"<a href=\"javascript:void(0)\" title=\"4:1\" onclick=\"javascript:setbet('足球单式','波胆-4:1','" + dbs[i]["Match_ID"] + "','Match_Bd41','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Bd41"]!=data[i]["Match_Bd41"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd41"]+"</a>":"")+"<br>"+((dbs[i]["Match_Bdg41"] !=null && dbs[i]["Match_Bdg41"] !="0")?"<a href=\"javascript:void(0)\" title=\"1:4\" onclick=\"javascript:setbet('足球单式','波胆-1:4','" + dbs[i]["Match_ID"] + "','Match_Bdg41','0','0','"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg41"]!=data[i]["Match_Bdg41"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bdg41"]+"</a>":"")+"</td>";
				htmls +="  <td class='zhong line'>"+((dbs[i]["Match_Bd42"] !=null && dbs[i]["Match_Bd42"] !="0")?"<a href=\"javascript:void(0)\" title=\"4:2\" onclick=\"javascript:setbet('足球单式','波胆-4:2','" + dbs[i]["Match_ID"] + "','Match_Bd42','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Bd42"]!=data[i]["Match_Bd42"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd42"]+"</a>":"")+"<br>"+((dbs[i]["Match_Bdg42"] !=null && dbs[i]["Match_Bdg42"] !="0")?"<a href=\"javascript:void(0)\" title=\"2:4\" onclick=\"javascript:setbet('足球单式','波胆-2:4','" + dbs[i]["Match_ID"] + "','Match_Bdg42','0','0','"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg42"]!=data[i]["Match_Bdg42"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bdg42"]+"</a>":"")+"</td>";
				htmls +="  <td class='zhong line'>"+((dbs[i]["Match_Bd43"] !=null && dbs[i]["Match_Bd43"] !="0")?"<a href=\"javascript:void(0)\" title=\"4:3\" onclick=\"javascript:setbet('足球单式','波胆-4:3','" + dbs[i]["Match_ID"] + "','Match_Bd43','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Bd43"]!=data[i]["Match_Bd43"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd43"]+"</a>":"")+"<br>"+((dbs[i]["Match_Bdg43"] !=null && dbs[i]["Match_Bdg43"] !="0")?"<a href=\"javascript:void(0)\" title=\"3:4\" onclick=\"javascript:setbet('足球单式','波胆-3:4','" + dbs[i]["Match_ID"] + "','Match_Bdg43','0','0','"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg43"]!=data[i]["Match_Bdg43"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bdg43"]+"</a>":"")+"</td>";
				htmls +="  <td class='zhong'>"+((dbs[i]["Match_Bd00"] !=null && dbs[i]["Match_Bd00"] !="0")?"<a href=\"javascript:void(0)\" title=\"0:0\" onclick=\"javascript:setbet('足球单式','波胆-0:0','" + dbs[i]["Match_ID"] + "','Match_Bd00','0','0','0:0');\" style='"+(dbs[i]["Match_Bd00"]!=data[i]["Match_Bd00"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd00"]+"</a>":"")+"</td>";
				htmls +="  <td class='zhong'>"+((dbs[i]["Match_Bd11"] !=null && dbs[i]["Match_Bd11"] !="0")?"<a href=\"javascript:void(0)\" title=\"1:1\" onclick=\"javascript:setbet('足球单式','波胆-1:1','" + dbs[i]["Match_ID"] + "','Match_Bd11','0','0','1:1');\" style='"+(dbs[i]["Match_Bd11"]!=data[i]["Match_Bd11"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd11"]+"</a>":"")+"</td>";
				htmls +="  <td class='zhong'>"+((dbs[i]["Match_Bd22"] !=null && dbs[i]["Match_Bd22"] !="0")?"<a href=\"javascript:void(0)\" title=\"2:2\" onclick=\"javascript:setbet('足球单式','波胆-2:2','" + dbs[i]["Match_ID"] + "','Match_Bd22','0','0','2:2');\" style='"+(dbs[i]["Match_Bd22"]!=data[i]["Match_Bd22"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd22"]+"</a>":"")+"</td>";
				htmls +="  <td class='zhong'>"+((dbs[i]["Match_Bd33"] !=null && dbs[i]["Match_Bd33"] !="0")?"<a href=\"javascript:void(0)\" title=\"3:3\" onclick=\"javascript:setbet('足球单式','波胆-3:3','" + dbs[i]["Match_ID"] + "','Match_Bd33','0','0','3:3');\" style='"+(dbs[i]["Match_Bd33"]!=data[i]["Match_Bd33"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd33"]+"</a>":"")+"</td>";
				htmls +="  <td class='zhong'>"+((dbs[i]["Match_Bd44"] !=null && dbs[i]["Match_Bd44"] !="0")?"<a href=\"javascript:void(0)\" title=\"4:4\" onclick=\"javascript:setbet('足球单式','波胆-4:4','" + dbs[i]["Match_ID"] + "','Match_Bd44','0','0','4:4');\" style='"+(dbs[i]["Match_Bd44"]!=data[i]["Match_Bd44"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd44"]+"</a>":"")+"</td>";
				htmls +="  <td class='zhong'>"+((dbs[i]["Match_Bdup5"] !=null && dbs[i]["Match_Bdup5"] !="0")?"<a href=\"javascript:void(0)\" title=\"其它比分\" onclick=\"javascript:setbet('足球单式','波胆-UP5','" + dbs[i]["Match_ID"] + "','Match_Bdup5','0','0','UP5');\" style='"+(dbs[i]["Match_Bdup5"]!=data[i]["Match_Bdup5"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bdup5"]+"</a>":"")+"</td>";
				htmls +="</tr>";
			}

			htmls+="</table>";
			if(htmls == "<table border='0' cellspacing='1' cellpadding='0' bgcolor='#ACACAC' class='box'><tr><th class='shijian'>时间</th><th class='duiwu'>主队 / 客队</th><th class='bodan'>1:0</th><th class='bodan'>2:0</th><th class='bodan'>2:1</th><th class='bodan'>3:0</th><th class='bodan'>3:1</th><th class='bodan'>3:2</th><th class='bodan'>4:0</th><th class='bodan'>4:1</th><th class='bodan'>4:2</th><th class='bodan'>4:3</th><th class='bodan'>0:0</th><th class='bodan'>1:1</th><th class='bodan'>2:2</th><th class='bodan'>3:3</th><th class='bodan'>4:4</th><th class='bodan'>UP5</th></tr></table>"){
				htmls = "<table border='0' cellspacing='1' cellpadding='0' bgcolor='#ACACAC' class='box'><tr><th class='shijian'>时间</th><th class='duiwu'>主队 / 客队</th><th class='bodan'>1:0</th><th class='bodan'>2:0</th><th class='bodan'>2:1</th><th class='bodan'>3:0</th><th class='bodan'>3:1</th><th class='bodan'>3:2</th><th class='bodan'>4:0</th><th class='bodan'>4:1</th><th class='bodan'>4:2</th><th class='bodan'>4:3</th><th class='bodan'>0:0</th><th class='bodan'>1:1</th><th class='bodan'>2:2</th><th class='bodan'>3:3</th><th class='bodan'>4:4</th><th class='bodan'>UP5</th></tr><tr><td height='100' colspan='18' align='center' bgcolor='#FFFFFF' style='line-height:25px;'>暂无任何赛事</td></tr></table>";
			}
			$("#datashow").html(htmls);
		}
	});
}

$(document).ready(function(){
	$("#xzls").click(function(){ //选择联赛
		JqueryDialog.Open('足球波胆', 'json/dialog.php?lsm='+window_lsm, 600, window_hight);
	});
});
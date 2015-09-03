// JavaScript Document
var dbs  = null;
var data = null;
var window_hight	=	0; //窗口高度
var window_lsm		=	0; //窗口联赛名
function loaded(league,thispage,p){
	var league = encodeURI(league);
	$.getJSON("ft_shangbanbodan_data.php?leaguename="+league+"&CurrPage="+thispage+"&callback=?",function(json){
		var pagecount	=	json.fy.p_page;
		var messagecount=	json.fy.count_num;
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
		if(pagecount == "error1"){
			
			$("#datashow").html('<tr><td  colspan="18" align="center" style="background:#fff;">末登录,无法查看赛事信息.</td></tr>');
			$("#top").html("");
		}else if(pagecount == "error2"){
			$("#datashow").html('<tr><td  id=\"location\"  colspan="18" align="center" style="background:#fff;">对不起,您点击页面太快,请在60秒后进行操作</td></tr><script>check();</script>');
			$("#top").html("");
		}else if(pagecount == 0){
			$("#datashow").html('<tr><td  colspan="18" align="center" style="background:#fff;">暂无赛事</td></tr>');
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
			for(var i=0; i<dbs.length; i++){
				if(lsm != dbs[i]["Match_Name"]){
					lsm = dbs[i]["Match_Name"];
					htmls+='<tr>';
					htmls+='	<td  colspan="18"  class="b_hline">';
					htmls+='		<table  border="0"  cellpadding="0"  cellspacing="0"  width="100%"><tbody>';
					htmls+='			<tr>';
					htmls+='				<td  class="legicon" >';
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
				
				htmls+="<tr  id=\"TR_01-1870256\"    *class*=\"\">";
				htmls+="	<td class=\"b_cen\" rowspan=2>"+dbs[i]["Match_Date"]+"</td>";
				htmls+="	<td class=\"b_cen\" rowspan=2 width=\"13%\"> "+dbs[i]["Match_Master"]+"<br> "+dbs[i]["Match_Guest"]+"</td>";
				htmls+="	<td class=\"b_cen\" width=\"5%\">"+((dbs[i]["Match_Bd10"] !=null && dbs[i]["Match_Bd10"]!="0") ? "<a onclick=\"javascript:setbet('足球单式','上半波胆-1:0','" + dbs[i]["Match_ID"] + "','Match_Hr_Bd10','0','0','"+dbs[i]["Match_Master"]+"');\" href=\"javascript:void(0)\" title=\"1:0\" style='"+(dbs[i]["Match_Bd10"]!=data[i]["Match_Bd10"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd10"]+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\" width=\"5%\">"+((dbs[i]["Match_Bd20"] !=null && dbs[i]["Match_Bd20"] !="0") ? "<a href=\"javascript:void(0)\" title=\"2:0\" onclick=\"javascript:setbet('足球单式','上半波胆-2:0','" + dbs[i]["Match_ID"] + "','Match_Hr_Bd20','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Bd20"]!=data[i]["Match_Bd20"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd20"]+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\" width=\"5%\">"+((dbs[i]["Match_Bd21"] !=null && dbs[i]["Match_Bd21"] !="0")?"<a href=\"javascript:void(0)\" title=\"2:1\" onclick=\"javascript:setbet('足球单式','上半波胆-2:1','" + dbs[i]["Match_ID"] + "','Match_Hr_Bd21','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Bd21"]!=data[i]["Match_Bd21"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+(dbs[i]["Match_Bd21"])+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\" width=\"5%\">"+((dbs[i]["Match_Bd30"] !=null && dbs[i]["Match_Bd30"] !="0")?"<a href=\"javascript:void(0)\" title=\"3:0\" onclick=\"javascript:setbet('足球单式','上半波胆-3:0','" + dbs[i]["Match_ID"] + "','Match_Hr_Bd30','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Bd30"]!=data[i]["Match_Bd30"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+(dbs[i]["Match_Bd30"])+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\" width=\"5%\">"+((dbs[i]["Match_Bd31"] !=null && dbs[i]["Match_Bd31"] !="0")?"<a href=\"javascript:void(0)\" title=\"3:1\" onclick=\"javascript:setbet('足球单式','上半波胆-3:1','" + dbs[i]["Match_ID"] + "','Match_Hr_Bd31','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Bd31"]!=data[i]["Match_Bd31"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd31"]+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\" width=\"5%\">"+((dbs[i]["Match_Bd32"] !=null && dbs[i]["Match_Bd32"] !="0")?"<a href=\"javascript:void(0)\" title=\"3:2\" onclick=\"javascript:setbet('足球单式','上半波胆-3:2','" + dbs[i]["Match_ID"] + "','Match_Hr_Bd32','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Bd32"]!=data[i]["Match_Bd32"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd32"]+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\" width=\"5%\">"+((dbs[i]["Match_Bd40"] !=null && dbs[i]["Match_Bd40"] !="0")?"<a href=\"javascript:void(0)\" title=\"4:0\" onclick=\"javascript:setbet('足球单式','上半波胆-4:0','" + dbs[i]["Match_ID"] + "','Match_Hr_Bd40','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Bd40"]!=data[i]["Match_Bd40"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd40"]+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\" width=\"5%\">"+((dbs[i]["Match_Bd41"] !=null && dbs[i]["Match_Bd41"] !="0")?"<a href=\"javascript:void(0)\" title=\"4:1\" onclick=\"javascript:setbet('足球单式','上半波胆-4:1','" + dbs[i]["Match_ID"] + "','Match_Hr_Bd41','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Bd41"]!=data[i]["Match_Bd41"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd41"]+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\" width=\"5%\">"+((dbs[i]["Match_Bd42"] !=null && dbs[i]["Match_Bd42"] !="0")?"<a href=\"javascript:void(0)\" title=\"4:2\" onclick=\"javascript:setbet('足球单式','上半波胆-4:2','" + dbs[i]["Match_ID"] + "','Match_Hr_Bd42','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Bd42"]!=data[i]["Match_Bd42"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd42"]+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\" width=\"5%\">"+((dbs[i]["Match_Bd43"] !=null && dbs[i]["Match_Bd43"] !="0")?"<a href=\"javascript:void(0)\" title=\"4:3\" onclick=\"javascript:setbet('足球单式','上半波胆-4:3','" + dbs[i]["Match_ID"] + "','Match_Hr_Bd43','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Bd43"]!=data[i]["Match_Bd43"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd43"]+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\" rowspan=\"2\" width=\"5%\">"+((dbs[i]["Match_Bd00"] !=null && dbs[i]["Match_Bd00"] !="0")?"<a href=\"javascript:void(0)\" title=\"0:0\" onclick=\"javascript:setbet('足球单式','上半波胆-0:0','" + dbs[i]["Match_ID"] + "','Match_Hr_Bd00','0','0','0:0');\" style='"+(dbs[i]["Match_Bd00"]!=data[i]["Match_Bd00"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd00"]+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\" rowspan=\"2\" width=\"5%\">"+((dbs[i]["Match_Bd11"] !=null && dbs[i]["Match_Bd11"] !="0")?"<a href=\"javascript:void(0)\" title=\"1:1\" onclick=\"javascript:setbet('足球单式','上半波胆-1:1','" + dbs[i]["Match_ID"] + "','Match_Hr_Bd11','0','0','1:1');\" style='"+(dbs[i]["Match_Bd11"]!=data[i]["Match_Bd11"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd11"]+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\" rowspan=\"2\" width=\"5%\">"+((dbs[i]["Match_Bd22"] !=null && dbs[i]["Match_Bd22"] !="0")?"<a href=\"javascript:void(0)\" title=\"2:2\" onclick=\"javascript:setbet('足球单式','上半波胆-2:2','" + dbs[i]["Match_ID"] + "','Match_Hr_Bd22','0','0','2:2');\" style='"+(dbs[i]["Match_Bd22"]!=data[i]["Match_Bd22"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd22"]+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\" rowspan=\"2\" width=\"5%\">"+((dbs[i]["Match_Bd33"] !=null && dbs[i]["Match_Bd33"] !="0")?"<a href=\"javascript:void(0)\" title=\"3:3\" onclick=\"javascript:setbet('足球单式','上半波胆-3:3','" + dbs[i]["Match_ID"] + "','Match_Hr_Bd33','0','0','3:3');\" style='"+(dbs[i]["Match_Bd33"]!=data[i]["Match_Bd33"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd33"]+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\" rowspan=\"2\" width=\"5%\">"+((dbs[i]["Match_Bd44"] !=null && dbs[i]["Match_Bd44"] !="0")?"<a href=\"javascript:void(0)\" title=\"4:4\" onclick=\"javascript:setbet('足球单式','上半波胆-4:4','" + dbs[i]["Match_ID"] + "','Match_Hr_Bd44','0','0','4:4');\" style='"+(dbs[i]["Match_Bd44"]!=data[i]["Match_Bd44"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bd44"]+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\" rowspan=\"2\" width=\"5%\">"+((dbs[i]["Match_Bdup5"] !=null && dbs[i]["Match_Bdup5"] !="0")?"<a href=\"javascript:void(0)\" title=\"其它比分\" onclick=\"javascript:setbet('足球单式','上半波胆-UP5','" + dbs[i]["Match_ID"] + "','Match_Hr_Bdup5','0','0','UP5');\" style='"+(dbs[i]["Match_Bdup5"]!=data[i]["Match_Bdup5"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bdup5"]+"</a>":"")+"</td>";
					
					
			 htmls+=" </tr> ";
			  htmls+="<tr  id=\"TR_01-1870256\"    *class*=\"\">";
					
					
				htmls+="	<td class=\"b_cen\">"+((dbs[i]["Match_Bdg43"] !=null && dbs[i]["Match_Bdg43"] !="0")?"<a href=\"javascript:void(0)\" title=\"3:4\" onclick=\"javascript:setbet('足球单式','波胆-3:4','" + dbs[i]["Match_ID"] + "','Match_Bdg43','0','0','"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg43"]!=data[i]["Match_Bdg43"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bdg43"]+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\">"+((dbs[i]["Match_Bdg10"] !=null && dbs[i]["Match_Bdg10"] !="0")?"<a href=\"javascript:void(0)\" title=\"0:1\" onclick=\"javascript:setbet('足球单式','波胆-0:1','" + dbs[i]["Match_ID"] + "','Match_Bdg10','0','0','"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg10"]!=data[i]["Match_Bdg10"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bdg10"]+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\">"+((dbs[i]["Match_Bdg20"] !=null && dbs[i]["Match_Bdg20"] !="0")?"<a href=\"javascript:void(0)\" title=\"0:2\" onclick=\"javascript:setbet('足球单式','波胆-0:2','" + dbs[i]["Match_ID"] + "','Match_Bdg20','0','0','"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg20"]!=data[i]["Match_Bdg20"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bdg20"]+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\">"+((dbs[i]["Match_Bdg21"] !=null && dbs[i]["Match_Bdg21"] !="0")?"<a href=\"javascript:void(0)\" title=\"1:2\" onclick=\"javascript:setbet('足球单式','波胆-1:2','" + dbs[i]["Match_ID"] + "','Match_Bdg21','0','0','"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg21"]!=data[i]["Match_Bdg21"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bdg21"]+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\">"+((dbs[i]["Match_Bdg30"] !=null && dbs[i]["Match_Bdg30"] !="0")?"<a href=\"javascript:void(0)\" title=\"0:3\" onclick=\"javascript:setbet('足球单式','波胆-0:3','" + dbs[i]["Match_ID"] + "','Match_Bdg30','0','0','"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg30"]!=data[i]["Match_Bdg30"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bdg30"]+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\">"+((dbs[i]["Match_Bdg31"] !=null && dbs[i]["Match_Bdg31"] !="0")?"<a href=\"javascript:void(0)\" title=\"1:3\" onclick=\"javascript:setbet('足球单式','波胆-1:3','" + dbs[i]["Match_ID"] + "','Match_Bdg31','0','0','"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg31"]!=data[i]["Match_Bdg31"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bdg31"]+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\">"+((dbs[i]["Match_Bdg32"] !=null && dbs[i]["Match_Bdg32"] !="0")?"<a href=\"javascript:void(0)\" title=\"2:3\" onclick=\"javascript:setbet('足球单式','波胆-2:3','" + dbs[i]["Match_ID"] + "','Match_Bdg32','0','0','"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg32"]!=data[i]["Match_Bdg32"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bdg32"]+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\">"+((dbs[i]["Match_Bdg40"] !=null && dbs[i]["Match_Bdg40"] !="0")?"<a href=\"javascript:void(0)\" title=\"0:4\" onclick=\"javascript:setbet('足球单式','波胆-0:4','" + dbs[i]["Match_ID"] + "','Match_Bdg40','0','0','"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg40"]!=data[i]["Match_Bdg40"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bdg40"]+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\">"+((dbs[i]["Match_Bdg41"] !=null && dbs[i]["Match_Bdg41"] !="0")?"<a href=\"javascript:void(0)\" title=\"1:4\" onclick=\"javascript:setbet('足球单式','波胆-1:4','" + dbs[i]["Match_ID"] + "','Match_Bdg41','0','0','"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg41"]!=data[i]["Match_Bdg41"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bdg41"]+"</a>":"")+"</td>";
				htmls+="	<td class=\"b_cen\">"+((dbs[i]["Match_Bdg42"] !=null && dbs[i]["Match_Bdg42"] !="0")?"<a href=\"javascript:void(0)\" title=\"2:4\" onclick=\"javascript:setbet('足球单式','波胆-2:4','" + dbs[i]["Match_ID"] + "','Match_Bdg42','0','0','"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg42"]!=data[i]["Match_Bdg42"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+dbs[i]["Match_Bdg42"]+"</a>":"")+"</td>";
					
					
			htmls+="  </tr>";
				
				
				
				
			}

			htmls+="";
			if(htmls == ""){
				htmls = '<tr><td  colspan="18" align="center" style="background:#fff;">暂无赛事</td></tr>';
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
		JqueryDialog.Open('足球上半波胆', 'dialog.php?lsm='+window_lsm, 600, window_hight);
	});
});*/
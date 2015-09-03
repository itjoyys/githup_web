// JavaScript Document
var dbs  = null;
var data = null;
var window_hight	=	0; //窗口高度
var window_lsm		=	0; //窗口联赛名
function loaded(league,thispage,p){
	var league = encodeURI(league);
	$.getJSON("ft_banquanchang_data.php?leaguename="+league+"&CurrPage="+thispage+"&callback=?",function(json){
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
		if(pagecount == "error1"){
			
			$("#datashow").html('<tr><td  colspan="11" align="center" style="background:#fff;">末登录,无法查看赛事信息.</td></tr>');
			$("#top").html("");
		}else if(pagecount == "error2"){
			$("#datashow").html('<tr><td  id=\"location\"  colspan="11" align="center" style="background:#fff;">对不起,您点击页面太快,请在60秒后进行操作</td></tr><script>check();</script>');
			$("#top").html("");
		}else if(pagecount == 0){
			$("#datashow").html('<tr><td  colspan="11" align="center" style="background:#fff;">暂无赛事</td></tr>');
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
				if(dbs[i]["Match_BqMM"]!="0" || dbs[i]["Match_BqMH"]!="0" || dbs[i]["Match_BqMG"]!="0" || dbs[i]["Match_BqHM"]!="0" || dbs[i]["Match_BqHH"]!="0" || dbs[i]["Match_BqHG"]!="0" || dbs[i]["Match_BqGM"]!="0" || dbs[i]["Match_BqGH"]!="0" || dbs[i]["Match_BqGG"]!="0"){
				if(lsm!=dbs[i]["Match_Name"]){
					lsm=dbs[i]["Match_Name"];
					htmls+='<tr>';
					htmls+='	<td  colspan="11"  class="b_hline">';
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
				
					htmls+="<tr>";
					htmls+="   <td  class=\"b_cen\" style=\"width:80px;\">"+dbs[i]["Match_Date"]+"</td>";
					htmls+="  <td  class=\"b_cen\" style=\"width:140px;\">"+dbs[i]["Match_Master"]+"<br>"+dbs[i]["Match_Guest"]+"</td>";
					htmls+="  <td  class=\"b_cen\">"+(dbs[i]["Match_BqMM"] !=null?"<a href=\"javascript:void(0)\" title=\"主/主\" onclick=\"setbet('足球单式','半全场-主/主','" + dbs[i]["Match_ID"] + "','Match_BqMM','0','0','主/主');\" style='"+(dbs[i]["Match_BqMM"]!=data[i]["Match_BqMM"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+(dbs[i]["Match_BqMM"]!="0"?dbs[i]["Match_BqMM"]:"")+"</a>":"")+"</td>";
					htmls+="  <td class=\"b_cen\">"+(dbs[i]["Match_BqMH"] !=null?"<a href=\"javascript:void(0)\" title=\"主/和\" onclick=\"setbet('足球单式','半全场-主/和','" + dbs[i]["Match_ID"] + "','Match_BqMH','0','0','主/和');\" style='"+(dbs[i]["Match_BqMH"]!=data[i]["Match_BqMH"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+(dbs[i]["Match_BqMH"]!="0"?dbs[i]["Match_BqMH"]:"")+"</a>":"")+"</td>";
					htmls+="  <td class=\"b_cen\">"+(dbs[i]["Match_BqMG"] !=null?"<a href=\"javascript:void(0)\" title=\"主/客\" onclick=\"setbet('足球单式','半全场-主/客','" + dbs[i]["Match_ID"] + "','Match_BqMG','0','0','主/客');\" style='"+(dbs[i]["Match_BqMG"]!=data[i]["Match_BqMG"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+(dbs[i]["Match_BqMG"]!="0"?dbs[i]["Match_BqMG"]:"")+"</a>":"")+"</td>";
					htmls+="  <td class=\"b_cen\">"+(dbs[i]["Match_BqHM"] !=null?"<a href=\"javascript:void(0)\" title=\"和/主\" onclick=\"setbet('足球单式','半全场-和/主','" + dbs[i]["Match_ID"] + "','Match_BqHM','0','0','和/主');\" style='"+(dbs[i]["Match_BqHM"]!=data[i]["Match_BqHM"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+(dbs[i]["Match_BqHM"]!="0"?dbs[i]["Match_BqHM"]:"")+"</a>":"")+"</td>";
					htmls+="  <td class=\"b_cen\">"+(dbs[i]["Match_BqHH"] !=null?"<a href=\"javascript:void(0)\" title=\"和/和\" onclick=\"setbet('足球单式','半全场-和/和','" + dbs[i]["Match_ID"] + "','Match_BqHH','0','0','和/和');\" style='"+(dbs[i]["Match_BqHH"]!=data[i]["Match_BqHH"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+(dbs[i]["Match_BqHH"]!="0"?dbs[i]["Match_BqHH"]:"")+"</a>":"")+"</td>";
					htmls+="  <td class=\"b_cen\">"+(dbs[i]["Match_BqHG"] !=null?"<a href=\"javascript:void(0)\" title=\"和/客\" onclick=\"setbet('足球单式','半全场-和/客','" + dbs[i]["Match_ID"] + "','Match_BqHG','0','0','和/客');\" style='"+(dbs[i]["Match_BqHG"]!=data[i]["Match_BqHG"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+(dbs[i]["Match_BqHG"]!="0"?dbs[i]["Match_BqHG"]:"")+"</a>":"")+"</td>";
					htmls+="  <td class=\"b_cen\">"+(dbs[i]["Match_BqGM"] !=null?"<a href=\"javascript:void(0)\" title=\"客/主\" onclick=\"setbet('足球单式','半全场-客/主','" + dbs[i]["Match_ID"] + "','Match_BqGM','0','0','客/主');\" style='"+(dbs[i]["Match_BqGM"]!=data[i]["Match_BqGM"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+(dbs[i]["Match_BqGM"]!="0"?dbs[i]["Match_BqGM"]:"")+"</a>":"")+"</td>";
					htmls+="  <td class=\"b_cen\">"+(dbs[i]["Match_BqGH"] !=null?"<a href=\"javascript:void(0)\" title=\"客/和\" onclick=\"setbet('足球单式','半全场-客/和','" + dbs[i]["Match_ID"] + "','Match_BqGH','0','0','客/和');\" style='"+(dbs[i]["Match_BqGH"]!=data[i]["Match_BqGH"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+(dbs[i]["Match_BqGH"]!="0"?dbs[i]["Match_BqGH"]:"")+"</a>":"")+"</td>";
					htmls+=" <td class=\"b_cen\">"+(dbs[i]["Match_BqGG"] !=null?"<a href=\"javascript:void(0)\" title=\"客/客\" onclick=\"setbet('足球单式','半全场-客/客','" + dbs[i]["Match_ID"] + "','Match_BqGG','0','0','客/客');\" style='"+(dbs[i]["Match_BqGG"]!=data[i]["Match_BqGG"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+(dbs[i]["Match_BqGG"]!="0"?dbs[i]["Match_BqGG"]:"")+"</a>":"")+"</td>	";	 
					htmls+="</tr>";
				
				
			}
			}
			htmls+="";
			if(htmls == ""){
				htmls = '<tr><td  colspan="11" align="center" style="background:#fff;">暂无赛事</td></tr>';
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
		JqueryDialog.Open('足球半全场', 'dialog.php?lsm='+window_lsm, 600, window_hight);
	});
});*/
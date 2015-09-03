// JavaScript Document
var dbs =null;
var data =null;
function loaded(league,thispage){
	var league = encodeURI(league);
	$.getJSON("champion_data.php?leaguename="+league+"&CurrPage="+thispage+"&callback=?",function(json){
		
		var pagecount = json.fy.p_page;
		var messagecount = json.fy.count_num;
		var page = json.fy.page;
		var fenye = "";
		var opt = json.dh;
		if(dbs !=null)
        {
			data = dbs;
			dbs  = json.db;         
		}else{
			dbs  = json.db;
			data = dbs;
		}
		var league = json.leaguename;
		var timename = json.timename;
		if(league != ""){
			$("#league").val(timename);
		}
		if(pagecount == 0){
			$("#datashow").html("<table border='0' cellspacing='1' cellpadding='0' bgcolor='#ACACAC' class='box'><tr><th width='100'>比赛时间</th><th>项目</th><th>队伍</th><th>结果</th></tr><tr><td height='100' align='center' bgcolor='#FFFFFF' colspan='4'>暂无任何赛果</td></tr></table>");
			$("#top").html("");
		}else{
			var tem_arr = new Array();
			tem_arr = opt.split("|");
			var tem_arr2 = new Array();
			var htmls = "<table border='0' cellspacing='1' cellpadding='0' bgcolor='#ACACAC' class='box'><tr><th width='100'>比赛时间</th><th>项目</th><th>队伍</th><th>结果</th></tr>";
			for(var i=0; i<dbs.length; i++){
				htmls+="<tr>";
				htmls+="<td class='zhong line'>"+dbs[i]["Match_Date"]+"</td>";
				htmls+="<td class='zhong line'>"+dbs[i]["x_title"]+"<br>"+dbs[i]["Match_Name"]+"</td>";
				htmls+="<td class='line'>";
				var team_name = dbs[i]["team_name"].split(",");
				for(var ss=0; ss<team_name.length-1; ss++){
					htmls+=team_name[ss]+'<br>';
				}
				htmls+=team_name[team_name.length-1];
				htmls+="</td>";
				htmls+="<td class='zhong line red'>"+dbs[i]["x_result"]+"</td>"
				htmls+="</tr>"
			}
			htmls+="</table>";
			$("#datashow").html(htmls);
		}
	})
}
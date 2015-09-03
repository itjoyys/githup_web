// JavaScript Document
var dbs =null;
var data =null;
function loaded(league,thispage){
	var league = encodeURI(league);
	$.getJSON("guanjun_result_data.php?leaguename="+league+"&CurrPage="+thispage+"&callback=?",function(json){
		
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
		
		var tday = json.tday
		if(pagecount!="error2"){
			if(tday[0] != "no"){
				var tdaystr = "<a href=\"javascript:void(0)\" onclick=\"return Wleague('"+tday[0]+"')\">昨日</a> / <a href=\"javascript:void(0)\" onclick=\"return Wleague('"+tday[1]+"')\">明日</a>"
			}else{
				var tdaystr = "<a href=\"javascript:void(0)\" onclick=\"return Wleague('"+league+"')\">昨日</a>"
			}
			$("#tday").html(tdaystr);
		}
		
		if(pagecount == "error1"){
			
			$("#datashow").html('<tr><td  colspan="4" align="center" style="background:#fff;">末登录,无法查看赛事信息.</td></tr>');
			$("#top").html("");
		}else if(pagecount == "error2"){
			$("#datashow").html('<tr><td  id=\"location\"  colspan="4" align="center" style="background:#fff;">对不起,您点击页面太快,请在60秒后进行操作</td></tr><script>check();</script>');
			$("#top").html("");
		}else if(pagecount == 0){
			$("#datashow").html('<tr><td  colspan="4" align="center" style="background:#fff;">暂无赛事</td></tr>');
			$("#top").html('');
		}else{
			//分页
			for(var i=0; i<pagecount; i++){
				if(i != page){
					fenye+="<a href='javascript:NumPage(" + i + ");'><div style='display:inline;' class=\"sz_0\" id=\"page_this\">" + (i+1) + "</div></a>";
				}else{
					fenye+="<a href='javascript:NumPage(" + i + ");'><div style='display:inline;' class=\"sz_0\" id=\"page_this\"  style='color:#FFFFFF;background:url(../../images/right_4.jpg);'>" + (i+1) + "</div></a>";
				}
			}
			$("#top").html(fenye);
		
		
			
			var tem_arr = new Array();
			tem_arr = opt.split("|");
			var tem_arr2 = new Array();
			
			var htmls = "";
			for(var i=0; i<dbs.length; i++){
				htmls += "<tr  id=\"TR_01-1870256\"    *class*=\"\">";
				htmls += "	<td  class=\"b_cen\">&nbsp;"+dbs[i]["Match_Date"]+"</td>";
				htmls += "	<td  class=\"b_cen\"> &nbsp;"+"冠军<br>"+dbs[i]["x_title"]+"<br>"+dbs[i]["Match_Name"]+"  </td>";
				htmls += "	<td  class=\"b_cen\">&nbsp;";
				
				var team_name = dbs[i]["team_name"].split(",");
				for(var ss=0; ss<team_name.length; ss++){
					htmls += team_name[ss]+"<br>";
				}
				htmls += "</td>";
				htmls += "	<td  class=\"b_cen\"> &nbsp;"+dbs[i]["x_result"]+"  </td>";
					
					
			  htmls += "</tr> ";
			
					
				
			}
			
			$("#datashow").html(htmls);
		}
	})
}
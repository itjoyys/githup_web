// JavaScript Document
var dbs  = null;
var data = null;
var window_hight	=	0; //窗口高度
var window_lsm		=	0; //窗口联赛名
function loaded(league,thispage,p){
	var league = encodeURI(league);
	$.getJSON("json/champion.php?leaguename="+league+"&CurrPage="+thispage+"&callback=?",function(json){
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
			$("#datashow").html("<table border='0' cellspacing='1' cellpadding='0' bgcolor='#ACACAC' class='box'><tr><td height='100' align='center' bgcolor='#FFFFFF'>暂无任何赛事</td></tr></table>");
			$("#top").html("");
		}else{
			var lsm = "";
			var htmls = "<table border='0' cellspacing='1' cellpadding='0' bgcolor='#ACACAC' class='box'>";
			for(var i=0; i<dbs.length; i++){
				if(lsm != dbs[i]["x_title"]){
					lsm = dbs[i]["x_title"];
					htmls+="<tr><th colspan='2' class='liansai_g'><span>"+dbs[i]["Match_Date"]+"</span><a href=\"javascript:void(0)\" title='选择 >> "+lsm+"' onclick=\"javascript:check_one('"+lsm+"');\">"+lsm+"</a></th></tr>";
				}
				htmls+="<tr><td colspan='2' class='saishi_g'>"+dbs[i]["Match_Name"]+"</td></tr>";
					var team_name	=	dbs[i]["team_name"].split(",");
					var point		=	dbs[i]["point"].split(",");
					var tid			=	dbs[i]["tid"].split(",");
					var point2		=	data[i]["point"].split(",");
					var tid2		=	data[i]["tid"].split(",");
					var css_bottom	=	'';
					for(var ss=0; ss<team_name.length-1; ss=ss+2){
						if(point[ss] != "0" && point[ss] != ""){
							htmls+="<tr>"
							htmls+="<td class='xiangmu_g'><span><a href=\"javascript:void(0);\" title=\""+team_name[ss]+"\" onclick=\"setbet('"+dbs[i]["Match_ID"]+"','"+tid[ss]+"')\" >"+formatNumber(point[ss],2)+"</a></span>"+team_name[ss]+"</td>"
							htmls+="<td class='xiangmu_g'><span><a href=\"javascript:void(0);\" title=\""+team_name[ss+1]+"\" onclick=\"setbet('"+dbs[i]["Match_ID"]+"','"+tid[ss+1]+"')\" >"+formatNumber(point[ss+1],2)+"</a></span>"+team_name[ss+1]+"</td>"
							htmls+="</tr>"
						}
					}
			}
			htmls+="</table>";
			$("#datashow").html(htmls);
		}
	})
}

$(document).ready(function(){
	$("#xzls").click(function(){ //选择联赛
		if(window_lsm.length > 2000){
			if(window.XMLHttpRequest){ //Mozilla, Safari, IE7 
				if(!window.ActiveXObject){ // Mozilla, Safari, 
					JqueryDialog.Open('冠军', 'json/dialog.php?lsm='+window_lsm, 600, window_hight);
				}else{ //IE7
					JqueryDialog.Open('冠军', 'json/dialog.php?lsm=gj', 600, window_hight);
				}
			}else{ //IE6
				JqueryDialog.Open('冠军', 'json/dialog.php?lsm=gj', 600, window_hight);
			}
		}else{
			JqueryDialog.Open('冠军', 'json/dialog.php?lsm='+window_lsm, 600, window_hight);
		}
	});
});
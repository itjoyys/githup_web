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
	$.getJSON("guanjun_data.php?leaguename="+league+"&CurrPage="+thispage+"&callback=?",function(json){
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
			
			var lsm = "";
			var htmls = "";
            var lsid=0
			for(var i=0; i<dbs.length; i++){
				if(lsm != dbs[i]["x_title"]){
                    lsid++;
					lsm = dbs[i]["x_title"];
					htmls+='<tr>';
					htmls+='	<td  colspan="5"  class="b_hline">';
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
				
				
				htmls+=" <tr  id=\"TR_01-"+lsid+"\"    *class*=\"\">";
				htmls+="	<td  class=\"b_cen\">"+dbs[i]["Match_Date"]+"</td>";
				htmls+="	<td  class=\"b_cen\">"+dbs[i]["Match_Name"];
				htmls+="	<td  class=\"b_cen\" colspan=2  id=\"118389861_MH\">";
				htmls+="		<table  border=\"0\"  cellpadding=\"0\"  cellspacing=\"0\"  width=\"100%\"><tbody>";
				
				var team_name	=	dbs[i]["team_name"].split(",");
					var point		=	dbs[i]["point"].split(",");
					var tid			=	dbs[i]["tid"].split(",");
					var point2		=	data[i]["point"].split(",");
					var tid2		=	data[i]["tid"].split(",");
					var css_bottom	=	'';
					for(var ss=0; ss<team_name.length-1; ss++){
						if(point[ss] != "0" && point[ss] != ""){
				
				htmls+="			<tr>";
				htmls+="				<td  class=\"b_cen\" style=\"border-top:1px solid #999;border-right:1px solid #999;\">&nbsp;"+team_name[ss]+"</td>";
				htmls+="				<td  class=\"b_cen\"  style=\"border-top:1px solid #999;\">&nbsp;<a href=\"javascript:void(0);\" title=\""+team_name[ss]+"\" onclick=\"setbet('"+dbs[i]["Match_ID"]+"','"+tid[ss]+"')\" >"+formatNumber(point[ss],2)+"</a> </td>";
				htmls+="			</tr>";
						}
					}		
				htmls+="		</tbody></table>";
					
				htmls+="	</td>";
					
			  htmls+="</tr>";
				
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
		if(window_lsm.length > 2000){
			if(window.XMLHttpRequest){ //Mozilla, Safari, IE7 
				if(!window.ActiveXObject){ // Mozilla, Safari, 
					JqueryDialog.Open('冠军', 'dialog.php?lsm='+window_lsm, 600, window_hight);
				}else{ //IE7
					JqueryDialog.Open('冠军', 'dialog.php?lsm=gj', 600, window_hight);
				}
			}else{ //IE6
				JqueryDialog.Open('冠军', 'dialog.php?lsm=gj', 600, window_hight);
			}
		}else{
			JqueryDialog.Open('冠军', 'dialog.php?lsm='+window_lsm, 600, window_hight);
		}
	});
});*/
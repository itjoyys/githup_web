// JavaScript Document
var dbs  = null;
var data = null;
var window_hight	=	0; //窗口高度
var window_lsm		=	0; //窗口联赛名
function loaded(league,thispage,p){
	var league = encodeURI(league);
	$.getJSON("tennis_bodan_data.php?leaguename="+league+"&CurrPage="+thispage+"&callback=?",function(json){
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
			
			$("#datashow").html('<tr><td  colspan="7" align="center" style="background:#fff;">末登录,无法查看赛事信息.</td></tr>');
			$("#top").html("");
		}else if(pagecount == "error2"){
			$("#datashow").html('<tr><td  id=\"location\"  colspan="9" align="center" style="background:#fff;">对不起,您点击页面太快,请在60秒后进行操作</td></tr><script>check();</script>');
			$("#top").html("");
		}else if(pagecount == 0){
			$("#datashow").html('<tr><td  colspan="7" align="center" style="background:#fff;">暂无赛事</td></tr>');
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
			var htmls="";
			var lsm = "";
			for(var i=0; i<dbs.length; i++){
				if(lsm != dbs[i]["Match_Name"]){
					lsm = dbs[i]["Match_Name"];
					htmls+='<tr>';
					htmls+='	<td  colspan="7"  class="b_hline">';
					htmls+='		<table  border="0"  cellpadding="0"  cellspacing="0"  width="100%"><tbody>';
					htmls+='			<tr>';
					htmls+='				<td  class="legicon" >';
					htmls+='				<span  id="'+lsm+'"  class="showleg"><span  id="LegOpen"></span> </span>';
					htmls+='				</td>';
					htmls+="				<td  class=\"leg_bar\" onclick=\"javascript:check_one('"+lsm+"');\">"+lsm+zhgg+"</td>";
					htmls+='				<td  class="IN">';
										
					htmls+='				</td>';
					htmls+='			</tr>';
					htmls+='		</tbody></table>';
					htmls+='	</td>';
					htmls+=' </tr>';
				}
				
				 htmls+="<tr>";
				 htmls+="		<td  rowspan=\"2\"  class=\"b_cen\">"+dbs[i]["Match_Date"]+"<br/>"+dbs[i]["Match_Time"]+"<br />"+(dbs[i]["Match_IsLose"]=1 ? "<font color='red'>滚球</font>" :"")+"</td>";
				htmls+="		<td  rowspan=\"2\"  class=\"b_cen\"> "+dbs[i]["Match_Master"]+" <br>";
				htmls+=dbs[i]["Match_Guest"]+" </td>";
				htmls+="		<td  class=\"b_cen\">&nbsp;"+(dbs[i]["Match_Bd20"]=null ? "" :("<a href=\"javascript:void(0)\" onclick=\"javascript:setbet('网球单式','波胆-2:0','"+dbs[i]["Match_ID"]+"','Match_Bd20','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Bd20"]!=data[i]["Match_Bd20"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+formatNumber(dbs[i]["Match_Bd20"],2)+"</a>"))+"</td>";
				htmls+="		<td  class=\"b_rig\">&nbsp;"+(dbs[i]["Match_Bd21"]=null ? "" :("<a href=\"javascript:void(0)\" onclick=\"javascript:setbet('网球单式','波胆-2:1','"+dbs[i]["Match_ID"]+"','Match_Bd21','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Bd21"]!=data[i]["Match_Bd21"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+formatNumber(dbs[i]["Match_Bd21"],2)+"1</a>"))+"</td>";
				htmls+="		<td  class=\"b_rig\">&nbsp;"+(dbs[i]["Match_Bd30"]=null ? "" :("<a href=\"javascript:void(0)\" onclick=\"javascript:setbet('网球单式','波胆-3:0','"+dbs[i]["Match_ID"]+"','Match_Bd30','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Bd30"]!=data[i]["Match_Bd30"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+formatNumber(dbs[i]["Match_Bd30"],2)+"1</a>"))+"</td>";
				htmls+="		<td  class=\"b_cen\">&nbsp;"+(dbs[i]["Match_Bd31"]=null ? "" :("<a href=\"javascript:void(0)\" onclick=\"javascript:setbet('网球单式','波胆-3:1','"+dbs[i]["Match_ID"]+"','Match_Bd31','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Bd31"]!=data[i]["Match_Bd31"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+formatNumber(dbs[i]["Match_Bd31"],2)+"1</a>"))+"</td>";
				htmls+="		<td  class=\"b_cen\">&nbsp;"+(dbs[i]["Match_Bd32"]=null ? "" :("<a href=\"javascript:void(0)\" onclick=\"javascript:setbet('网球单式','波胆-3:2','"+dbs[i]["Match_ID"]+"','Match_Bd32','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Bd32"]!=data[i]["Match_Bd32"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+formatNumber(dbs[i]["Match_Bd32"],2)+"1</a>"))+"</td>";
						
				htmls+="  </tr> ";
				htmls+="  <tr>";
				htmls+="		<td  class=\"b_cen\">&nbsp;"+(dbs[i]["Match_Bdg20"]=null ? "" :("<a href=\"javascript:void(0)\" onclick=\"javascript:setbet('网球单式','波胆-2:0','"+dbs[i]["Match_ID"]+"','Match_Bdg20','0',0,'"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg20"]!=data[i]["Match_Bdg20"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+formatNumber(dbs[i]["Match_Bdg20"],2)+"</a>"))+"</td>";
				htmls+="		<td  class=\"b_rig\">&nbsp;"+(dbs[i]["Match_Bdg21"]=null ? "" :("<a href=\"javascript:void(0)\" onclick=\"javascript:setbet('网球单式','波胆-2:1','"+dbs[i]["Match_ID"]+"','Match_Bdg21','0',0,'"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg21"]!=data[i]["Match_Bdg21"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+formatNumber(dbs[i]["Match_Bdg21"],2)+"</a>"))+"</td>";
				htmls+="		<td  class=\"b_rig\">&nbsp;"+(dbs[i]["Match_Bdg30"]=null ? "" :("<a href=\"javascript:void(0)\" onclick=\"javascript:setbet('网球单式','波胆-3:0','"+dbs[i]["Match_ID"]+"','Match_Bdg30','0',0,'"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg30"]!=data[i]["Match_Bdg30"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+formatNumber(dbs[i]["Match_Bdg30"],2)+"</a>"))+"</td>";
				htmls+="		<td  class=\"b_cen\">&nbsp;"+(dbs[i]["Match_Bdg31"]=null ? "" :("<a href=\"javascript:void(0)\" onclick=\"javascript:setbet('网球单式','波胆-3:1','"+dbs[i]["Match_ID"]+"','Match_Bdg31','0',0,'"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg31"]!=data[i]["Match_Bdg31"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+formatNumber(dbs[i]["Match_Bdg31"],2)+"</a>"))+"</td>";
				htmls+="		<td  class=\"b_cen\">&nbsp;"+(dbs[i]["Match_Bdg32"]=null ? "" :("<a href=\"javascript:void(0)\" onclick=\"javascript:setbet('网球单式','波胆-3:2','"+dbs[i]["Match_ID"]+"','Match_Bdg32','0',0,'"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Bdg32"]!=data[i]["Match_Bdg32"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFCC00":"")+"'>"+formatNumber(dbs[i]["Match_Bdg32"],2)+"</a>"))+"</td>";
					
				htmls+="  </tr>";
				
			}

			
			if(htmls == ""){
				htmls = '<tr><td  colspan="7" align="center" style="background:#fff;">暂无赛事</td></tr>';
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
		JqueryDialog.Open('网球波胆', 'dialog.php?lsm='+window_lsm, 600, window_hight);
	});
});*/
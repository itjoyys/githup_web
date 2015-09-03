// JavaScript Document
var dbs  = null;
var data = null;
var window_hight	=	0; //窗口高度
var window_lsm		=	0; //窗口联赛名
function loaded(league,thispage,p){
	var league = encodeURI(league);
	$.getJSON("json/ft_2_1.php?leaguename="+league+"&CurrPage="+thispage+"&callback=?",function(json){
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
			$("#datashow").html("<table border='0' cellspacing='1' cellpadding='0' bgcolor='#ACACAC' class='box'><tr><th rowspan='2' class='shijian'>时间</th><th rowspan='2' class='duiwu'>主队 / 客队</th><th colspan='4' class='quanchang'>全场</th><th colspan='3' class='shangbanchang'>上半场</th></tr><tr><th class='duying'>1X2</th><th class='rangqiu'>让球</th><th class='daxiao'>大小</th><th class='danshuang'>单双</th><th class='duying2'>1X2</th><th class='rangqiu2'>让球</th><th class='daxiao2'>大小</th></tr><tr><td height='100' colspan='9' align='center' bgcolor='#FFFFFF'>暂无任何赛事</td></tr></table>");
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
			
			var htmls="<table border='0' cellspacing='1' cellpadding='0' bgcolor='#ACACAC' class='box'><tr><th rowspan='2' class='shijian'>时间</th><th rowspan='2' class='duiwu'>主队 / 客队</th><th colspan='4' class='quanchang'>全场</th><th colspan='3' class='shangbanchang'>上半场</th></tr><tr><th class='duying'>1X2</th><th class='rangqiu'>让球</th><th class='daxiao'>大小</th><th class='danshuang'>单双</th><th class='duying2'>1X2</th><th class='rangqiu2'>让球</th><th class='daxiao2'>大小</th></tr>";
			var lsm = "";
			for(var i=0; i<dbs.length; i++){
				if(dbs[i]["Match_BzM"]!="0" || dbs[i]["Match_Ho"]!=0 || dbs[i]["Match_DxXpl"]!="0" || dbs[i]["Match_DsDpl"]!="0"){
				if(lsm != dbs[i]["Match_Name"]){
					lsm = dbs[i]["Match_Name"];
					htmls+="<tr>";
					htmls+="<td colspan='9' class='liansai'><a href=\"javascript:void(0)\" title='选择 >> "+lsm+"' onclick=\"javascript:check_one('"+lsm+"');\" >"+lsm+"</a></td>";
					htmls+="</tr>";
				}
				htmls+="<tr class='bian_out' onMouseOver=\"this.className='bian_over'\" onMouseOut=\"this.className='bian_out'\">";
				htmls+="    <td class='zhong'>"+dbs[i]["Match_Date"]+"</td>";
				htmls+="    <td class='line'><span class='zhu'>"+dbs[i]["Match_Master"]+"</span><br><span class='ke'>"+dbs[i]["Match_Guest"]+"</span><br><span class='he'>和局</span></td>";
				htmls+="    <td class='zhong line'>"+(dbs[i]["Match_BzM"]=="0" ? "" :("<a href=\"javascript:void(0)\" title=\""+dbs[i]["Match_Master"]+"\" onclick=\"javascript:setbet('足球早餐','标准盘-"+dbs[i]["Match_Master"]+"-独赢','"+dbs[i]["Match_ID"]+"','Match_BzM','0','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_BzM"]!=data[i]["Match_BzM"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00;":"")+"'>"+formatNumber(dbs[i]["Match_BzM"],2)+"</a>"))+"<br>"+(dbs[i]["Match_BzG"]=="0" ? "" :("<a href=\"javascript:void(0)\" title=\""+dbs[i]["Match_Guest"]+"\" onclick=\"javascript:setbet('足球早餐','标准盘-"+dbs[i]["Match_Guest"]+"-独赢','"+dbs[i]["Match_ID"]+"','Match_BzG','0','0','"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_BzG"]!=data[i]["Match_BzG"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00;":"")+"'>"+formatNumber(dbs[i]["Match_BzG"],2)+"</a>"))+"<br>"+((dbs[i]["Match_BzH"]==null || (dbs[i]["Match_BzH"]-0.05<=0)) ? "" :("<a href=\"javascript:void(0)\" title=\"和局\" onclick=\"javascript:setbet('足球早餐','标准盘-和局','"+dbs[i]["Match_ID"]+"','Match_BzH','0','0','和局');\" style='"+(dbs[i]["Match_BzH"]!=data[i]["Match_BzH"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00;":"")+"'>"+formatNumber(dbs[i]["Match_BzH"],2)+"</a>"))+"</td>";
				htmls+="    <td><div class='rangqiu_odds'><span class='odds'>"+(dbs[i]["Match_Ho"]==null ? "" :("<a href=\"javascript:void(0)\" title=\""+dbs[i]["Match_Master"]+"\" onclick=\"javascript:setbet('足球早餐','让球-"+(dbs[i]["Match_ShowType"]=="H" ? "主让" :"客让")+dbs[i]["Match_RGG"]+"-"+dbs[i]["Match_Master"]+"','"+dbs[i]["Match_ID"]+"','Match_Ho','1','0','"+dbs[i]["Match_Master"]+"');\" style='"+(dbs[i]["Match_Ho"]!=data[i]["Match_Ho"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00;":"")+"'>"+formatNumber(dbs[i]["Match_Ho"],2)+"</a>"))+"</span><span class='pankou'>"+((dbs[i]["Match_ShowType"]=="H" && dbs[i]["Match_Ho"]!="0") ?dbs[i]["Match_RGG"] : "")+"</span><br><span class='odds'>"+(dbs[i]["Match_Ao"]!=null ?("<a href=\"javascript:void(0)\" title=\""+dbs[i]["Match_Guest"]+"\" onclick=\"javascript:setbet('足球早餐','让球-"+(dbs[i]["Match_ShowType"]=="H" ? "主让" :"客让")+dbs[i]["Match_RGG"]+"-"+dbs[i]["Match_Guest"]+"','"+dbs[i]["Match_ID"]+"','Match_Ao','1','0','"+dbs[i]["Match_Guest"]+"');\" style='"+(dbs[i]["Match_Ao"]!=data[i]["Match_Ao"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00;":"")+"'>"+formatNumber(dbs[i]["Match_Ao"],2)+"</a>") : "")+"</span><span class='pankou'>"+((dbs[i]["Match_ShowType"]=="C" && dbs[i]["Match_Ao"]!="0") ?dbs[i]["Match_RGG"] : "")+"</span><br>&nbsp;</div></td>";
				htmls+="    <td><div class='rangqiu_odds'><span class='odds'>"+(dbs[i]["Match_DxDpl"]==null || dbs[i]["Match_DxXpl"]=="0" ? "" :("<a href=\"javascript:void(0)\" title=\"大\" onclick=\"javascript:setbet('足球早餐','大小-"+dbs[i]["Match_DxGG1"]+"','"+dbs[i]["Match_ID"]+"','Match_DxDpl','1','0','"+dbs[i]["Match_DxGG1"]+"');\" style='"+(dbs[i]["Match_DxGG1"]!=data[i]["Match_DxGG1"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00;":"")+"'>"+formatNumber(dbs[i]["Match_DxDpl"],2)+"</a>"))+"</span><span class='pankou'>"+(dbs[i]["Match_DxGG1"]!="0" ? dbs[i]["Match_DxGG1"] :"")+"</span><br><span class='odds'>"+(dbs[i]["Match_DxXpl"]==null || dbs[i]["Match_DxXpl"]=="0" ? "" :("<a href=\"javascript:void(0)\" title=\"小\" onclick=\"javascript:setbet('足球早餐','大小-"+dbs[i]["Match_DxGG2"]+"','"+dbs[i]["Match_ID"]+"','Match_DxXpl','1','0','"+dbs[i]["Match_DxGG2"]+"');\" style='"+(dbs[i]["Match_DxXpl"]!=data[i]["Match_DxXpl"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00;":"")+"'>"+formatNumber(dbs[i]["Match_DxXpl"],2)+"</a>"))+"</span><span class='pankou'>"+(dbs[i]["Match_DxGG1"]=="0" ? "" :dbs[i]["Match_DxGG2"])+"</span><br>&nbsp;</div></td>";
				htmls +="  <td class='zhong'>"+((dbs[i]["Match_DsDpl"]==null || dbs[i]["Match_DsDpl"]=="0") ? "" :("单:<a href=\"javascript:void(0)\" title=\"单\" onclick=\"javascript:setbet('足球早餐','单双-单','"+dbs[i]["Match_ID"]+"','Match_DsDpl','0','0','单');\" style='"+(dbs[i]["Match_DsDpl"]!=data[i]["Match_DsDpl"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00":"")+"'>"+formatNumber(dbs[i]["Match_DsDpl"],2)+"</a>"))+"<br>";
				htmls +="  "+((dbs[i]["Match_DsSpl"]==null || dbs[i]["Match_DsSpl"]=="0") ? "" :("双:<a href=\"javascript:void(0)\" title=\"双\" onclick=\"javascript:setbet('足球早餐','单双-双','"+dbs[i]["Match_ID"]+"','Match_DsSpl','0','0','双');\" style='"+(dbs[i]["Match_DsSpl"]!=data[i]["Match_DsSpl"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00":"")+"'>"+formatNumber(dbs[i]["Match_DsSpl"],2)+"</a>"))+"<br>		</td>";
				htmls+="    <td class='zhong line2'>"+(dbs[i]["Match_Bmdy"] !=null?"<a href=\"javascript:void(0)\" title=\""+dbs[i]["Match_Master"]+"\" onclick=\"setbet('足球早餐','上半场标准盘-"+ dbs[i]["Match_Master"] +"-独赢','" + dbs[i]["Match_ID"] + "','Match_Bmdy','0','0','"+dbs[i]["Match_Master"]+"-[上半]');\" style='"+(dbs[i]["Match_Bmdy"]!=data[i]["Match_Bmdy"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00;":"")+"'>"+(dbs[i]["Match_Bmdy"]!="0"?formatNumber(dbs[i]["Match_Bmdy"],2):"")+"</a>":"")+"</a><br>"+(dbs[i]["Match_Bgdy2"] !=null?"<a href=\"javascript:void(0)\" title=\""+dbs[i]["Match_Guest"]+"\" onclick=\"setbet('足球早餐','上半场标准盘-"+ dbs[i]["Match_Guest"] +"-独赢','" + dbs[i]["Match_ID"] + "','Match_Bgdy','0','0','"+dbs[i]["Match_Guest"]+"-[上半]');\" style='"+(dbs[i]["Match_Bgdy2"]!=data[i]["Match_Bgdy2"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00;":"")+"'>"+(dbs[i]["Match_Bgdy2"]!="0"?formatNumber(dbs[i]["Match_Bgdy2"],2):"")+"</a>":"")+"</a><br>"+(dbs[i]["Match_Bhdy2"] !=null?((dbs[i]["Match_Bhdy2"]-0.05)>0 ?"<a href=\"javascript:void(0)\"  title=\"和局\" onclick=\"setbet('足球早餐','上半场标准盘-和局','" + dbs[i]["Match_ID"] + "','Match_Bhdy','0','0','和局');\" style='"+(dbs[i]["Match_Bhdy2"]!=data[i]["Match_Bhdy2"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00;":"")+"'>"+(dbs[i]["Match_Bhdy2"]!="0"?formatNumber(dbs[i]["Match_Bhdy2"],2):"")+"</a>":""):"")+"</a></td>";
				htmls+="    <td class='zhong line3'><div class='rangqiu_odds'><span class='odds'>"+(dbs[i]["Match_BHo"] !=null?"<a href=\"javascript:void(0)\" title=\""+dbs[i]["Match_Master"]+"\" onclick=\"setbet('足球早餐','上半场让球-"+(dbs[i]["Match_Hr_ShowType"] =="H"?"主让":"客让")+dbs[i]["Match_BRpk"]+"-"+dbs[i]["Match_Master"] + "','" + dbs[i]["Match_ID"] + "','Match_BHo','1','0','"+dbs[i]["Match_Master"]+"-[上半]'); \"style='"+(dbs[i]["Match_BHo"]!=data[i]["Match_BHo"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00;":"")+"'>"+(dbs[i]["Match_BHo"]!="0"?formatNumber(dbs[i]["Match_BHo"],2):"")+"</a>":"")+"</span><span class='pankou'>"+((dbs[i]["Match_Hr_ShowType"] =="H" && dbs[i]["Match_BHo"] !=0)?dbs[i]["Match_BRpk"]:"")+"</span><br><span class='odds'>"+(dbs[i]["Match_BAo"] !=null?"<a href=\"javascript:void(0)\" title=\""+dbs[i]["Match_Guest"]+"\" onclick=\"setbet('足球早餐','上半场让球-"+(dbs[i]["Match_Hr_ShowType"] =="H"?"主让":"客让")+dbs[i]["Match_BRpk"]+"-"+dbs[i]["Match_Guest"] + "','" + dbs[i]["Match_ID"] + "','Match_BAo','1','0','"+dbs[i]["Match_Guest"]+"-[上半]');\" style='"+(dbs[i]["Match_BAo"]!=data[i]["Match_BAo"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00;":"")+"'>"+(dbs[i]["Match_BAo"]!="0"?formatNumber(dbs[i]["Match_BAo"],2):"")+"</a>":"")+"</span><span class='pankou'>"+((dbs[i]["Match_Hr_ShowType"] =="C" && dbs[i]["Match_BAo"] !="0")?dbs[i]["Match_BRpk"]:"")+"</span><br>&nbsp;</div></td>";
				htmls+="    <td class='zhong line3'><div class='rangqiu_odds'><span class='odds'>"+(dbs[i]["Match_Bdpl"] !=null?"<a href=\"javascript:void(0)\" title=\"大\" onclick=\"setbet('足球早餐','上半场大小-"+dbs[i]["Match_Bdxpk1"]+"','" + dbs[i]["Match_ID"] + "','Match_Bdpl','1','0','"+dbs[i]["Match_Bdxpk1"].replace("@","")+"');\" style='"+(dbs[i]["Match_Bdpl"]!=data[i]["Match_Bdpl"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00;":"")+"'>"+(dbs[i]["Match_Bdpl"]!="0"?formatNumber(dbs[i]["Match_Bdpl"],2):"")+"</a>":"")+"</span><span class='pankou'>"+((dbs[i]["Match_Bdxpk1"]!="O")?dbs[i]["Match_Bdxpk1"].replace("@",""):"")+"</span><br><span class='odds'>"+(dbs[i]["Match_Bxpl"] !=null?"<a href=\"javascript:void(0)\" title=\"小\" onclick=\"setbet('足球早餐','上半场大小-"+dbs[i]["Match_Bdxpk2"]+"','" + dbs[i]["Match_ID"] + "','Match_Bxpl','1','0','"+dbs[i]["Match_Bdxpk2"].replace("@","")+"');\" style='"+(dbs[i]["Match_Bxpl"]!=data[i]["Match_Bxpl"] && data[i]["Match_ID"]==dbs[i]["Match_ID"]?"background:#FFFF00;":"")+"'>"+(dbs[i]["Match_Bxpl"]!="0"?formatNumber(dbs[i]["Match_Bxpl"],2):"")+"</a>":"")+"</span><span class='pankou'>"+((dbs[i]["Match_Bdxpk2"]!="U")?dbs[i]["Match_Bdxpk2"].replace("@",""):"")+"</span><br>&nbsp;</div></td>";
				htmls+=" </tr>"; 
			}
			}

			htmls+="</table>";
			if(htmls == "<table border='0' cellspacing='1' cellpadding='0' bgcolor='#ACACAC' class='box'><tr><th rowspan='2' class='shijian'>时间</th><th rowspan='2' class='duiwu'>主队 / 客队</th><th colspan='4' class='quanchang'>全场</th><th colspan='3' class='shangbanchang'>上半场</th></tr><tr><th class='duying'>1X2</th><th class='rangqiu'>让球</th><th class='daxiao'>大小</th><th class='danshuang'>单双</th><th class='duying2'>1X2</th><th class='rangqiu2'>让球</th><th class='daxiao2'>大小</th></tr></table>"){
				htmls = "<table border='0' cellspacing='1' cellpadding='0' bgcolor='#ACACAC' class='box'><tr><th rowspan='2' class='shijian'>时间</th><th rowspan='2' class='duiwu'>主队 / 客队</th><th colspan='4' class='quanchang'>全场</th><th colspan='3' class='shangbanchang'>上半场</th></tr><tr><th class='duying'>1X2</th><th class='rangqiu'>让球</th><th class='daxiao'>大小</th><th class='danshuang'>单双</th><th class='duying2'>1X2</th><th class='rangqiu2'>让球</th><th class='daxiao2'>大小</th></tr><tr><td height='100' colspan='9' align='center' bgcolor='#FFFFFF'>暂时无赛事</td></tr></table>";
			}
			$("#datashow").html(htmls);
		}
	})
}

$(document).ready(function(){
	$("#xzls").click(function(){ //选择联赛
		if(window_lsm.length > 2000){
			if(window.XMLHttpRequest){ //Mozilla, Safari, IE7 
				if(!window.ActiveXObject){ // Mozilla, Safari, 
					JqueryDialog.Open('足球早餐单式', 'json/dialog.php?lsm='+window_lsm, 600, window_hight);
				}else{ //IE7
					JqueryDialog.Open('足球早餐单式', 'json/dialog.php?lsm=zqzcds', 600, window_hight);
				}
			}else{ //IE6
				JqueryDialog.Open('足球早餐单式', 'json/dialog.php?lsm=zqzcds', 600, window_hight);
			}
		}else{
			JqueryDialog.Open('足球早餐单式', 'json/dialog.php?lsm='+window_lsm, 600, window_hight);
		}
	});
});
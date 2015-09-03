var today_count=0;
var early_count=0;
//var changebtn="rb";



if (""+top.head_btn!="ssc"){
	top.head_btn="ssc";
}

window.onload=function() {

		try{
			$("#ssc_btn").attr("class","ssc_on");
		}catch(E){}		

}
function chg_index(a,b,c,d,early){
	top.hot_game="";
	top.swShowLoveI=false;
	top.cgTypebtn="r_class";
	
	var hot_str="";
	
	hot_str="&hot_game="+top.hot_game;
	parent.body.location.href=b+"&league_id="+c+hot_str;
	self.location.href=a;
}
function chg_type(a,b,c){
	//eval("top."+c+"_mem_index").body.location=a+"&league_id="+b;
	if(top.swShowLoveI)b=3;
	parent.body.location=a+"&league_id="+b;
}
function chg_index_head(a,b,c,d,early){
	top.hot_game="";
	top.swShowLoveI=false;
	top.cgTypebtn="r_class";
	var hot_str="";	
	hot_str="&hot_game="+top.hot_game;
	parent.body.location.href=b+"&league_id="+c+hot_str;
	self.location.href=a;
}

function chg_type(a,b,c){
	top.hot_game="";
	if(top.swShowLoveI)b=3;
	if(top.showtype=='hgft')b=3;
	var hot_str="";
	
	//加入hot_game參數值
	hot_str="&hot_game="+top.hot_game;
	parent.body.location=a+"&league_id="+b+hot_str;
}
function chg_head(a,b,c){
	top.hot_game="";
	if(top.swShowLoveI)b=3;
	if(top.showtype=='hgft')b=3;
	var hot_str="";
	
	//加入hot_game參數值
	hot_str="&hot_game="+top.hot_game;
	parent.body.location=a+"&league_id="+b+hot_str;
}
var nowTimer=0;
var stimer=0;
function autoZero(val){
	if (val<10){
		return "0"+val;
		}
		return val;
	}
	
	
/* 流程 SetRB ---> reloadRB --->  showLayer */
//-----------------------------------------------------------
function GameCount(games){
	var countgtype =new Array("FT","BK","TN","VB","BS","OP");
	var countgames=games.split(",");
	var recordHash=new Array();
	recordHash["DATE"]=countgames[0];
	//setTimeStart(recordHash["DATE"]) 
	recordHash["RB"]=0;
	for( var i=1;i<countgames.length;i++){
		var detailgame=countgames[i].split("|");
		recordHash[detailgame[0]+"_"+detailgame[1]]=detailgame[2]*1;
		}

	try{
		if (top.head_FU=="FU"){	
      document.getElementById('FT_games').innerHTML=recordHash["FT_"+top.head_FU];
			document.getElementById('BK_games').innerHTML=recordHash["BK_"+top.head_FU];
			document.getElementById('TN_games').innerHTML=recordHash["TN_"+top.head_FU];
			document.getElementById('BS_games').innerHTML=recordHash["BS_"+top.head_FU];
			document.getElementById('VB_games').innerHTML=recordHash["VB_"+top.head_FU];
			document.getElementById('OP_games').innerHTML=recordHash["OP_"+top.head_FU];
			
		}else{
			//document.getElementById('RB_games').innerHTML=recordHash[top.head_gtype+"_RB"];
			
			var RB_idstr="";
			var RB_countstr="";
			for( var i=0;i<countgtype.length;i++){
						RB_idstr="RB_"+countgtype[i];
						RB_countstr="RB_"+countgtype[i]+"_games";
						if(recordHash[countgtype[i]+"_RB"] == 0){
							document.getElementById(RB_idstr).style.display="none";
						}else{	
							document.getElementById(RB_countstr).innerHTML=recordHash[countgtype[i]+"_RB"];
						}					
			}					
			document.getElementById('subRB_games').innerHTML=recordHash[top.head_gtype+"_RB"]; 
			document.getElementById('FT_games').innerHTML=recordHash["FT_"+top.head_FU]+recordHash["FT_RB"];
			document.getElementById('BK_games').innerHTML=recordHash["BK_"+top.head_FU]+recordHash["BK_RB"];
			document.getElementById('TN_games').innerHTML=recordHash["TN_"+top.head_FU]+recordHash["TN_RB"];
			document.getElementById('BS_games').innerHTML=recordHash["BS_"+top.head_FU]+recordHash["BS_RB"];
			document.getElementById('VB_games').innerHTML=recordHash["VB_"+top.head_FU]+recordHash["VB_RB"];
			document.getElementById('OP_games').innerHTML=recordHash["OP_"+top.head_FU]+recordHash["OP_RB"];
		}
	}catch(E){}
	
	
	today_count=recordHash[top.head_gtype+"_FT"];
	early_count=recordHash[top.head_gtype+"_FU"];
	//rb_count = recordHash[top.head_gtype+"_RB"];
	
	var today_RB=0;
	for( var i=0;i<countgtype.length;i++){
		today_RB +=recordHash[countgtype[i]+"_RB"];
	}		
	
	//alert("today_count="+today_count+",early_count="+early_count+",rb_count="+rb_count);

	if (top.head_FU=="FT"){
		if (today_RB*1 > 0){
			document.getElementById("rb_btn").style.visibility = "visible";
		}else{
			document.getElementById("rb_btn").style.visibility = "hidden";
		}
	}
	/*
	if (today_count*1 > 0){
	 		document.getElementById("todayshow").style.display=''; 
      document.getElementById("todayType").style.display='none';
      document.getElementById("today_btn").className="today";
  }else{
      document.getElementById("todayshow").style.display='none';
      document.getElementById("todayType").style.display='';
      document.getElementById("today_btn").className="today_null";
  }
  
  if (early_count*1 > 0){
      document.getElementById("earlyshow").style.display='';
      document.getElementById("earlyType").style.display='none';
      document.getElementById("early_btn").className="early";  
  }else{
      document.getElementById("earlyshow").style.display='none';
      document.getElementById("earlyType").style.display='';
      document.getElementById("early_btn").className="early_null";
  } */
  // if (top.head_btn=="early"){	
    ////document.getElementById("early_btn").className="early_on";
	// $("#early_btn span:first-child").css({ "color":"#3B2D1B","background-position": "right -78px" });
	// $("#early_btn span a:first-child").css({ "color":"#3B2D1B", "background-position": "left top" });
  // }else if(top.head_btn=="gq"){
  	////document.getElementById("rb_btn").className="rb_on";
	// $("#rb_btn span:first-child").css({ "color":"#3B2D1B" ,"background-position": "right -78px" });
	// $("#rb_btn span a:first-child").css({  "color":"#3B2D1B","background-position": "left top" });
  // }else{
  	////document.getElementById("today_btn").className="today_on";
	// $("#today_btn span:first-child").css({ "color":"#3B2D1B", "background-position": "right -78px" });
	// $("#today_btn span a:first-child").css({ "color":"#3B2D1B", "background-position": "left top" });
  // }
	// chg_button_bg(top.head_gtype,top.head_FU);
	////reloadCrditFunction();
	
}
function chg_type_class(game_type){
//已選取：黃字 class="type_on"
//選取後離開：白字 class="type_out"
    if(game_type != top.cgTypebtn ){
     $("#"+game_type).attr("class","type_on");
      
	//alert("111>>>>>>>>"+top.cgTypebtn);
      $("#"+top.cgTypebtn).attr("class","type_out");
    }
    top.cgTypebtn=game_type;
	if(game_type=="re_class"){
		top.head_btn='rb';
		$("#rb_btn").attr("class","rb_on");
		$("#early_btn").attr("class","early");
		$("#today_btn").attr("class","today");
	}else{
		if(top.head_btn=='rb'){
			top.head_btn=='today';
			$("#rb_btn").attr("class","rb");
			$("#early_btn").attr("class","early");
			$("#today_btn").attr("class","today_on");
		}
	}
}
function chg_button_bg(btn){
	
	// $("#"+top.head_gtype+" span a").attr("class","type_out");
	// $("#"+gtype+" span a").attr("class","type_on");
	//top.head_gtype=gtype;
	//if (btn=="") return;
	// if (btn=="early"||btn=="today"){
		// chg_type_class("r_class"); 
	// }
	// if (btn=="rb"){
		// chg_type_class("re_class"); 	
		// $("#rb_btn").attr("class","rb_on");
	// }
	// if (btn!="rb"){
	
		// if(btn=="early"){
			// top.head_FU="FU";	
			// $("#early_btn").attr("class","early_on");
		// }else{
			// top.head_FU="FT";
			// $("#today_btn").attr("class","today_on");
		// }
	// }

	top.head_btn=btn;

	
}
/*滾球提示--將值帶進去去開啟getrecRB.php程式,去抓取伺服器是否有滾球賽程*/
var record_RB = 0;
function reloadRB(gtype,uid){
	reloadPHP.location.href="./getrecRB.php?gtype="+gtype+"&uid="+top.uid;
}

/*滾球提示--將getrecRB.php的結果帶進去,去判斷是否record_RB是否大於0,如果有會顯示滾球圖示*/

function showLayer(record_RB){
	if (record_RB > 0) {
		document.getElementById('rbyshow').style.display='block';
	}else{
		document.getElementById('rbyshow').style.display='none';
		
	}
	//document.getElementById('RB_games').innerHTML=record_RB;
	/*document.getElementById('RB_FT_games').innerHTML=0;
	document.getElementById('RB_BK_games').innerHTML=0;
	document.getElementById('RB_TN_games').innerHTML=0;
	document.getElementById('RB_BS_games').innerHTML=0;
	document.getElementById('RB_VB_games').innerHTML=0;
	document.getElementById('RB_OP_games').innerHTML=0;*/
	
}

/* 滾球提示--程式一開始值呼叫reloadRb,setInterval函式 多久會呼叫reloadRB函數預設 5分鐘 */
function SetRB(gttype,uid){
	reloadRB(gttype,uid);
	setInterval("reloadRB('"+gttype+"','"+uid+"')",5*60*1000);
}
function  getdomain(){
	var a = new Array();
	a[0]= document.domain;
	ESTime.setdomain(a);
	return a;
}
function OnMouseOverEvent() {
	document.getElementById("informaction").style.display = "block";
}
function OnMouseOutEvent() {
	document.getElementById("informaction").style.display = "none";
}

function Go_Chg_pass(){
	if (top.Go_pass == "Y") {
		Real_Win=window.open("./account/chg_passwd.php?uid="+top.uid,"Chg_pass","width=380,height=240,status=no");
	}else{
		return;
	}
}
function OpenLive(){
	if (top.liveid == undefined) {
		parent.self.location = "";
		return;
	}
	window.open("./live/live.php?langx="+top.langx+"&uid="+top.uid+"&liveid="+top.liveid,"Live","width=780,height=580,top=0,left=0,status=no,toolbar=no,scrollbars=yes,resizable=no,personalbar=no");
}

function mouseEnter_pointer(tmp){
	try{
		var tmp1 = tmp.split("_")[1];
		var txtnum = top.ShowLoveIarray[tmp1].length;
		if(txtnum !=0)
		document.getElementById(tmp).style.display ="block";
	}catch(E){}
}

function mouseOut_pointer(tmp){
	try{
		document.getElementById(tmp).style.display ="none";
	}catch(E){}
}
try{
	showGtype = top.gtypeShowLoveI;
	var xx=showGtype.length;
}catch(E){
	initDate();
	showGtype = top.gtypeShowLoveI;
}
//top.swShowLoveI=false;
//window.onscroll =chkscrollShowLoveI;
function initDate(){
	
	top.gtypeShowLoveI =new Array("FTRE","FT","FU","BKRE","BK","BU","BSRE","BS","BSFU","TNRE","TN","TU","VBRE","VB","VU","OPRE","OP","OM");
	top.ShowLoveIarray = new Array();
	top.ShowLoveIOKarray = new Array();
	for (var i=0 ; i < top.gtypeShowLoveI.length ; i++){
		top.ShowLoveIarray[top.gtypeShowLoveI[i]]= new Array();
		top.ShowLoveIOKarray[top.gtypeShowLoveI[i]]= new Array();
	}
}

function winOpen(url,width,height,left,top,name)
	{
		var temp = "menubar=no,toolbar=no,directories=no,scrollbars=yes,resizable=no";
		if (width) {
		temp += ',width=' + width;
		} else {
		width = 1024;
		}
		if (height) {
		temp += ',height=' + height;
		} else {
		height = 600;
		}
		if (left) {
		temp += ',left=' + left;
		} else {
		temp += ',left='
		+ Math.round((window.screen.width - parseInt(width)) / 2);
		}
		if (top) {
		temp += ',top=' + top;
		} else {
		temp += ',top='
		+ Math.round((window.screen.height - parseInt(height)) / 2);
		} 
		if(typeof(name)=="undefined"){
			name="";
		}
		if(name=="game")
		{
			//alert(temp);
			var obj=window.open (url,name,temp);
			obj.moveTo(0,0);
			obj.resizeTo(window.screen.availWidth,window.screen.availHeight);	
			//window.setTimeout("obj.document.location=url",3000);
		}
		else
			window.open (url,name,temp);
		if(name=="game") temp+=',fullscreen=1';
		window.open(url,name,temp);
	}
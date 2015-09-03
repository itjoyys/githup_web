var today_count=0;
var early_count=0;
//var changebtn="rb";



if (""+top.head_gtype=="undefined"){
	top.head_gtype="FT";
}
if (""+top.head_FU=="undefined"){
	top.head_FU="FT";
}
if (""+top.head_btn=="undefined"){
	top.head_btn="today";
}
if (""+top.cgTypebtn=="undefined" || top.head_btn !="rb"){
		top.cgTypebtn="r_class";
}

function onloaded() {

	if (top.casino != "SI2") {
		try{
			document.getElementById("live").style.display = "none";
			document.getElementById("QA_row").style.display = "none";
		}catch(E){}
	}
	
	/*document.getElementById(top.cgTypebtn).className="type_on";
	try{
		document.getElementById(top.head_gtype).getElementsByTagName("a")[0].className="type_on";}
	catch(e){
		
	}*/
	if(top.head_btn == "rb"){
		try{
			//document.getElementById("rb_btn").className="rb_on";
			document.getElementById("today_btn").className="today";
		}catch(E){}		
	}else if(top.head_btn == "today"){		
		try{
		document.getElementById("today_btn").className="today_on";
		}catch(E){}	
	}else if(top.head_btn == "early"){
		try{
			document.getElementById("early_btn").className="early_on";
		}catch(E){}	
	}	
	if(top.head_btn=="rb"){
		document.getElementById("RB_MENU").style.display = "none";
		document.getElementById("nav").style.display = "none";
		document.getElementById("type").style.display = "none";
		document.getElementById("nav_re").style.display = "";
		document.getElementById("type_re").style.display = "";
	}else{
		document.getElementById("RB_MENU").style.display = "none";
		document.getElementById("nav").style.display = "";
		document.getElementById("type").style.display = "";
		document.getElementById("nav_re").style.display = "none";
		document.getElementById("type_re").style.display = "none";
	}
	
	try{
	var obj= document.getElementById(top.cgTypebtn+"");
     obj.className="type_on";
	}catch(E){
		console.log(E);
	}	
	try{
		document.getElementById(top.head_btn+"_btn").className=top.head_btn+"_on";
		if(""+top.RB_id=="undefined" || top.RB_id==""){
			document.getElementById("RB_FT").className="rb_menu_on";
		}else{
			document.getElementById(top.RB_id).className="rb_menu_on";
		}
		
	}catch(E){
		console.log(E);
	}	
	
	document.getElementById("rb_btn").className="rb";
	//reloadCrditFunction();

	
}
//更新信用額度max
function reloadCrditFunction(){
	if(top.uid != "guest"){
		reCrdit_frame.location.href='/app/member/reloadCredit.php?uid='+top.uid+'&langx='+top.langx;
	}
}


function reloadCredit(cash){
	var tmp=cash.split(".");
	top.mcurrency=tmp[0];
	document.getElementById("credit").innerHTML=tmp[0]+' '+tmp[1];
	//document.getElementById("zrcredit").innerHTML=tmp[0]+' '+tmp[2];
}

function chg_index(a){
	top.RB_id="";
	top.hot_game="";
	top.swShowLoveI=false;
	top.cgTypebtn="re_class";
	document.getElementById("soprt_type_today").value=a;
	if(a=="football"){
			document.getElementById("today_1").style.display="block";
			document.getElementById("today_8").style.display="block";
			document.getElementById("today_2").style.display="block";
			document.getElementById("today_9").style.display="block";
			document.getElementById("today_3").style.display="block";
			document.getElementById("today_4").style.display="block";
			document.getElementById("today_10").style.display="block";
			document.getElementById("today_11").style.display="none";
			document.getElementById("today_12").style.display="none";
			document.getElementById("today_13").style.display="none";
			document.getElementById("today_14").style.display="none";
			document.getElementById("today_15").style.display="none";
			document.getElementById("today_16").style.display="none";
			document.getElementById("today_17").style.display="none";
			document.getElementById("today_18").style.display="none";
			document.getElementById("today_19").style.display="none";
			document.getElementById("today_20").style.display="none";
			document.getElementById("today_31").style.display="none";
			document.getElementById("today_32").style.display="none";
			document.getElementById("today_33").style.display="none";
	}else if(a=="bk_danshi"){
			document.getElementById("today_1").style.display="none";
			document.getElementById("today_8").style.display="none";
			document.getElementById("today_2").style.display="none";
			document.getElementById("today_9").style.display="none";
			document.getElementById("today_3").style.display="none";
			document.getElementById("today_4").style.display="none";
			document.getElementById("today_10").style.display="none";
			document.getElementById("today_11").style.display="block";
			document.getElementById("today_12").style.display="none";
			document.getElementById("today_13").style.display="none";
			document.getElementById("today_14").style.display="none";
			document.getElementById("today_18").style.display="none";
			document.getElementById("today_19").style.display="none";
			document.getElementById("today_20").style.display="none";
			document.getElementById("today_15").style.display="none";
			document.getElementById("today_16").style.display="none";
			document.getElementById("today_17").style.display="none";
			document.getElementById("today_31").style.display="block";
			document.getElementById("today_32").style.display="none";
			document.getElementById("today_33").style.display="none";
	}else if(a=="tennis_danshi"){
			document.getElementById("today_1").style.display="none";
			document.getElementById("today_8").style.display="none";
			document.getElementById("today_2").style.display="none";
			document.getElementById("today_9").style.display="none";
			document.getElementById("today_3").style.display="none";
			document.getElementById("today_4").style.display="none";
			document.getElementById("today_10").style.display="none";
			document.getElementById("today_11").style.display="none";
			document.getElementById("today_12").style.display="block";
			document.getElementById("today_13").style.display="block";
			document.getElementById("today_14").style.display="none";
			document.getElementById("today_15").style.display="none";
			document.getElementById("today_16").style.display="none";
			document.getElementById("today_17").style.display="none";
			document.getElementById("today_18").style.display="none";
			document.getElementById("today_19").style.display="none";
			document.getElementById("today_20").style.display="none";
			document.getElementById("today_31").style.display="none";
			
			document.getElementById("today_32").style.display="none";
			document.getElementById("today_33").style.display="none";
	}else if(a=="volleyball"){
			document.getElementById("today_1").style.display="none";
			document.getElementById("today_8").style.display="none";
			document.getElementById("today_2").style.display="none";
			document.getElementById("today_9").style.display="none";
			document.getElementById("today_3").style.display="none";
			document.getElementById("today_4").style.display="none";
			document.getElementById("today_10").style.display="none";
			document.getElementById("today_11").style.display="none";
			document.getElementById("today_12").style.display="none";
			document.getElementById("today_13").style.display="none";
			document.getElementById("today_14").style.display="block";
			document.getElementById("today_15").style.display="none";
			document.getElementById("today_16").style.display="none";
			document.getElementById("today_17").style.display="none";
			document.getElementById("today_18").style.display="none";
			document.getElementById("today_19").style.display="none";
			document.getElementById("today_20").style.display="none";
			document.getElementById("today_31").style.display="none";
			document.getElementById("today_32").style.display="block";
			document.getElementById("today_33").style.display="none";
	}else if(a=="baseball"){
			document.getElementById("today_1").style.display="none";
			document.getElementById("today_8").style.display="none";
			document.getElementById("today_2").style.display="none";
			document.getElementById("today_9").style.display="none";
			document.getElementById("today_3").style.display="none";
			document.getElementById("today_4").style.display="none";
			document.getElementById("today_10").style.display="none";
			document.getElementById("today_11").style.display="none";
			document.getElementById("today_12").style.display="none";
			document.getElementById("today_13").style.display="none";
			document.getElementById("today_14").style.display="none";
			document.getElementById("today_15").style.display="block";
			document.getElementById("today_16").style.display="none";
			document.getElementById("today_17").style.display="none";
			document.getElementById("today_18").style.display="none";
			document.getElementById("today_19").style.display="none";
			document.getElementById("today_20").style.display="none";
			document.getElementById("today_31").style.display="none";
			document.getElementById("today_32").style.display="none";
			document.getElementById("today_33").style.display="block";
	}else if(a=="guanjun"){
			document.getElementById("today_1").style.display="none";
			document.getElementById("today_8").style.display="none";
			document.getElementById("today_2").style.display="none";
			document.getElementById("today_9").style.display="none";
			document.getElementById("today_3").style.display="none";
			document.getElementById("today_4").style.display="none";
			document.getElementById("today_10").style.display="none";
			document.getElementById("today_11").style.display="none";
			document.getElementById("today_12").style.display="none";
			document.getElementById("today_13").style.display="none";
			document.getElementById("today_14").style.display="none";
			document.getElementById("today_15").style.display="none";
			document.getElementById("today_16").style.display="block";
			document.getElementById("today_17").style.display="block";
			document.getElementById("today_18").style.display="none";
			document.getElementById("today_19").style.display="none";
			document.getElementById("today_20").style.display="none";
			document.getElementById("today_31").style.display="none";
			document.getElementById("today_32").style.display="none";
			document.getElementById("today_33").style.display="none";
	}else if(a=="chuanguan"){
			document.getElementById("today_1").style.display="none";
			document.getElementById("today_8").style.display="none";
			document.getElementById("today_2").style.display="none";
			document.getElementById("today_9").style.display="none";
			document.getElementById("today_3").style.display="none";
			document.getElementById("today_4").style.display="none";
			document.getElementById("today_10").style.display="none";
			document.getElementById("today_11").style.display="none";
			document.getElementById("today_12").style.display="none";
			document.getElementById("today_13").style.display="none";
			document.getElementById("today_14").style.display="none";
			document.getElementById("today_15").style.display="none";
			document.getElementById("today_16").style.display="none";
			document.getElementById("today_17").style.display="none";
			document.getElementById("today_18").style.display="block";
			document.getElementById("today_19").style.display="block";
			document.getElementById("today_20").style.display="block";
			document.getElementById("today_31").style.display="none";
			document.getElementById("today_32").style.display="none";
			document.getElementById("today_33").style.display="none";
	}
	
	var hot_str="";
	if(top.head_gtype=="FT"){
		try{
		parent.mem_order.goEuro_HOT_btn("");
		}catch(E){}
	}
	hot_str="&hot_game="+top.hot_game;
	// ,b,c,d
	 // parent.mainFrame.location.href=b+"&league_id="+c+hot_str;
	 parent.mem_order.location.href="select.php";
	 parent.mainFrame.location.href="today/"+a+".php";
	
	// self.location.href ="today/"+a+".php";
}

function Go_RB_page(RBgtype){
	//var gtypeArr=RBgtype.split("_");
	//top.hot_game="";
	//top.RB_id="RB_"+gtypeArr[0];
	//chg_button_bg(gtypeArr[0],"rb");
	document.getElementById("soprt_type_grounder").value=RBgtype;

	top.hot_game="";
	top.RB_id="RB_"+RBgtype;
	top.head_FU="RB";
	top.viewtype = RBgtype + "_"+ RBgtype.toLowerCase();

	parent.mainFrame.location.href ="grounder/"+RBgtype +".php?rtype=re&uid="+top.uid+"&langx="+top.langx+"&mtype="+top.mtype+"&showtype=&hot_game="+top.hot_game;
	 // self.location.href = "grounder/"+RBgtype+".php?rtype=re&uid="+top.uid+"&langx="+top.langx+"&viewtype="+ top.viewtype + "&showtype=&mtype="+top.mtype;

	
	//console.log("http://"+document.domain+"/app/member/top_header.php?uid="+top.uid+"&langx="+top.langx+ "&showtype=&mtype="+top.mtype);
	//console.log(self.location.href );
	
}

function Go_RB_page_mor(RBgtype){
	//var gtypeArr=RBgtype.split("_");
	//top.hot_game="";
	//top.RB_id="RB_"+gtypeArr[0];
	//chg_button_bg(gtypeArr[0],"rb");

	top.hot_game="";
	top.RB_id="RB_"+RBgtype;
	top.head_FU="RB";
	top.viewtype = RBgtype + "_"+ RBgtype.toLowerCase();

	document.getElementById("soprt_type_morning").value=RBgtype;

	if(RBgtype=="ftz_danshi"){
			document.getElementById("morning_1").style.display="block";
			document.getElementById("morning_8").style.display="block";
			document.getElementById("morning_2").style.display="block";
			document.getElementById("morning_3").style.display="block";
			document.getElementById("morning_4").style.display="block";
			document.getElementById("morning_9").style.display="none";
	}else if(RBgtype=="bkz_danshi"){
			document.getElementById("morning_1").style.display="none";
			document.getElementById("morning_8").style.display="none";
			document.getElementById("morning_2").style.display="none";
			document.getElementById("morning_3").style.display="none";
			document.getElementById("morning_4").style.display="none";
			document.getElementById("morning_9").style.display="block";
	}
	parent.mem_order.location.href ="select.php";
	parent.mainFrame.location.href ="morning/"+RBgtype +".php?rtype=re&uid="+top.uid+"&langx="+top.langx+"&mtype="+top.mtype+"&showtype=&hot_game="+top.hot_game;
	 // self.location.href = "grounder/"+RBgtype+".php?rtype=re&uid="+top.uid+"&langx="+top.langx+"&viewtype="+ top.viewtype + "&showtype=&mtype="+top.mtype;

	

	
}


function chg_index_head(a,b){
	top.RB_id="";
	top.hot_game="";
	top.swShowLoveI=false;
	top.cgTypebtn="re_class";
	var hot_str="";	
	hot_str="&hot_game="+top.hot_game;
	if(top.head_gtype=="FT"){
		try{
		parent.mem_order.goEuro_HOT_btn("");
		}catch(E){}
	}
	parent.mem_order.location.href="select.php";
	parent.mainFrame.location.href=a+'/'+b;
	//self.location.href=a;
}

function chg_type(a,b,c){
	top.hot_game="";
	if(top.swShowLoveI)b=3;
	if(top.showtype=='hgft')b=3;
	var hot_str="";
	//a 滚球文件夹：grounder
	//b 体育类型
	//c 玩法类型 

	// 1:独赢＆让球＆大小  单 / 双 	2.全场波胆 	3.单双＆总入球 				4.半场/全场 5.综合过关  6.冠军	7.赛果 	8.上半场				9.上半场波胆	10.足球结果 	11.篮球结果	12.单式				13.网球结果	14.排球结果	15.棒球结果 16.冠军	17.冠军结果

	b=document.getElementById("soprt_type_"+a).value;
	url=a+"/"+b+".php?type="+b+"&leixing="+c;
	//加入hot_game參數值
	//alert(url);
	hot_str="&hot_game="+top.hot_game;
	parent.mainFrame.location.href=url;
}
//大分类跳转切换
function chg_head(a,b){
	top.hot_game="";
	if(top.swShowLoveI)b=3;
	if(top.showtype=='hgft')b=3;
	var hot_str="";
	
	//加入hot_game參數值
	hot_str="&hot_game="+top.hot_game;
	parent.mem_order.location.href="select.php";
	parent.mainFrame.location.href=a+'/'+b;
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
	//recordHash["DATE"]=countgames[0];
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
			//document.getElementById('subRB_games').innerHTML=recordHash[top.head_gtype+"_RB"]; 
			document.getElementById('FT_games').innerHTML=recordHash["FT_"+top.head_FU]+recordHash["FT_RB"];
			document.getElementById('BK_games').innerHTML=recordHash["BK_"+top.head_FU]+recordHash["BK_RB"];
			document.getElementById('TN_games').innerHTML=recordHash["TN_"+top.head_FU]+recordHash["TN_RB"];
			document.getElementById('BS_games').innerHTML=recordHash["BS_"+top.head_FU]+recordHash["BS_RB"];
			document.getElementById('VB_games').innerHTML=recordHash["VB_"+top.head_FU]+recordHash["VB_RB"];
			document.getElementById('OP_games').innerHTML=recordHash["OP_"+top.head_FU]+recordHash["OP_RB"];
		}
			
		var RB_idstr="";
		var RB_countstr="";
		for( var i=0;i<countgtype.length;i++){
					RB_idstr="RB_"+countgtype[i];
					RB_countstr="RB_"+countgtype[i]+"_games";
					//if(recordHash[countgtype[i]+"_RB"] == 0){
						//document.getElementById(RB_idstr).style.display="none";
					//}else{	
						document.getElementById(RB_countstr).innerHTML=recordHash[countgtype[i]+"_RB"];
					//}					
		}	
	}catch(E){
		//console.log(E)
	}
	
	
	today_count=recordHash[top.head_gtype+"_FT"];
	early_count=recordHash[top.head_gtype+"_FU"];
	//rb_count = recordHash[top.head_gtype+"_RB"];
	
	var today_RB=0;
	for( var i=0;i<countgtype.length;i++){
		today_RB +=recordHash[countgtype[i]+"_RB"];
	}		
	
	//alert("today_count="+today_count+",early_count="+early_count+",rb_count="+rb_count);

	//if (top.head_FU=="FT"){
		//if (today_RB*1 > 0){
			try{
				document.getElementById("rb_btn").style.visibility = "visible";
			}catch(e){
			
			}
		//}else{
		//	document.getElementById("rb_btn").style.visibility = "hidden";
		//}
	//}
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
	if (top.head_btn=="early"){	
		document.getElementById("early_btn").className="early_on";
	// $("#early_btn span:first-child").css({ "color":"#3B2D1B","background-position": "right -78px" });
	// $("#early_btn span a:first-child").css({ "color":"#3B2D1B", "background-position": "left top" });
	}else if(top.head_btn=="rb"){
		document.getElementById("rb_btn").className="rb_on";
	// $("#rb_btn span:first-child").css({ "color":"#3B2D1B" ,"background-position": "right -78px" });
	// $("#rb_btn span a:first-child").css({  "color":"#3B2D1B","background-position": "left top" });
	}else{
		document.getElementById("today_btn").className="today_on";
	// $("#today_btn span:first-child").css({ "color":"#3B2D1B", "background-position": "right -78px" });
	// $("#today_btn span a:first-child").css({ "color":"#3B2D1B", "background-position": "left top" });
	}
	//chg_button_bg(top.head_gtype,top.head_FU);
	reloadCrditFunction();
	
}
function chg_type_class(game_type){
//已選取：黃字 class="type_on"
//選取後離開：白字 class="type_out"
    if(game_type != top.cgTypebtn ){
     document.getElementById(game_type).className = "type_on";
      
	//alert("111>>>>>>>>"+top.cgTypebtn);
	try{
       document.getElementById(top.cgTypebtn).className = "type_out";
	   }catch(e){}
    }
    top.cgTypebtn=game_type;
	/*
	if(game_type=="r_class"){
		top.head_btn='rb';
		document.getElementById("rb_btn").className="rb_on";
		document.getElementById("early_btn").className="early";
		document.getElementById("today_btn").className="today";
	}else{
		if(top.head_btn=='rb'){
			top.head_btn=='today';
			
			document.getElementById("rb_btn").className="rb";
			document.getElementById("early_btn").className="early";
			document.getElementById("today_btn").className="today_on";

		}
	}*/
	try{
		if(game_type != top.cgTypebtn ){
		  var obj= document.getElementById(game_type+"");
		  obj.className="type_on";
		  
		  var obj_laster= document.getElementById(top.cgTypebtn+"");
		//  alert("111>>>>>>>>"+obj.className);
		  obj_laster.className="type_out";
		   top.cgTypebtn=game_type;
    }
	}catch(E){}	    

}

function chg_button_bg(gtype,btn){

	// top.head_gtype=gtype;
	if (btn=="early"){
		//chg_type_class("re_class"); 
		chg_type_class("r_class"); 
		document.getElementById("early_btn").className="today_on";
		document.getElementById("nav_re").style.display = "none";
		document.getElementById("type_re").style.display = "none";
		document.getElementById("nav_mor").style.display = "";
		document.getElementById("type_mor").style.display = "";
		document.getElementById("nav").style.display = "none";
		document.getElementById("type").style.display = "none";
		
	}
	if (btn=="today"){
		
		chg_type_class("re_class");	
		//chg_type_class("r_class");	
		document.getElementById("today_btn").className="today_on";
		document.getElementById("nav_re").style.display = "none";
		document.getElementById("type_re").style.display = "none";
		document.getElementById("nav").style.display = "";
		document.getElementById("type").style.display = "";
		document.getElementById("nav_mor").style.display = "none";
		document.getElementById("type_mor").style.display = "none";
		
		
	}
	if (btn=="rb"){
		
		chg_type_class("re_class");	
		//chg_type_class("r_class");	
		document.getElementById("rb_btn").className="today_on";
		document.getElementById("nav_re").style.display = "";
		document.getElementById("type_re").style.display = "";
		document.getElementById("nav").style.display = "none";
		document.getElementById("type").style.display = "none";
		document.getElementById("nav_mor").style.display = "none";
		document.getElementById("type_mor").style.display = "none";
		
	}
	if (btn!="rb"){
		if(btn=="early"){
			top.head_FU="FU";	
		}else{
			top.head_FU="FT";
		}
	}
	try{
		document.getElementById(top.head_btn+"_btn").className=top.head_btn;
	}catch(E){}
	top.head_btn=btn;
	try{
		document.getElementById("rb_btn").className="rb";
	}catch(E){}
	try{
		document.getElementById(btn+"_btn").className=btn+"_on";
	}catch(E){}
	
}

/*
function chg_button_bg(gtype,btn){
	//console.log(btn)
	try{
		document.getElementById(top.head_gtype).getElementsByTagName("a")[0].className="type_out";
		document.getElementById(gtype).getElementsByTagName("a")[0].className="type_on";
	 }catch(e){
	 }
	 	//console.log(gtype)
		top.head_gtype=gtype;
	//if (btn=="") return;

	if (btn=="early"||btn=="today"){
		chg_type_class("r_class"); 
	}
	if (btn=="rb"){
		chg_type_class("r_class"); 	
		document.getElementById("rb_btn").className="rb_on";
	}
	if (btn!="rb"){
	
		if(btn=="early"){
			top.head_FU="FU";	
			document.getElementById("early_btn").className="early_on";
		}else{
			top.head_FU="FT";
			document.getElementById("today_btn").className="today_on";
		}
	}
	// try{
		// alert(changebtn);
		// document.getElementById(top.head_btn+"_btn").className=top.head_btn;
		// $("#"+top.head_btn+" span:first-child").css({ "color":"#3B2D1B","background-position": "right -78px" });
		// $("#"+top.head_btn+" span a:first-child").css({ "color":"#3B2D1B", "background-position": "left top" });
	// }catch(E){}
	top.head_btn=btn;
	//alert("chg===>>>>"+top.head_btn);
	
	//if (today_count*1 > 0){
	 // alert("--0-0-0-0-0");
     // document.getElementById("todayshow").style.display=''; 
     // document.getElementById("todayType").style.display='none';
     // document.getElementById("today_btn").className="today";
  //}else{
     // document.getElementById("todayshow").style.display='none';
     // document.getElementById("todayType").style.display='';
     // document.getElementById("today_btn").className="today_null";
 // }
  
  //if (early_count*1 > 0){
      //document.getElementById("earlyshow").style.display='';
     // document.getElementById("earlyType").style.display='none';
     // document.getElementById("early_btn").className="early";
 // }else{
    //  document.getElementById("earlyshow").style.display='none';
    //  document.getElementById("earlyType").style.display='';
    //  document.getElementById("early_btn").className="early_null";
 // }   
	
	// try{
		// document.getElementById("rb_btn").className="rb";
	// }catch(E){}
	//document.getElementById("today_btn").className="today";
	//document.getElementById("early_btn").className="early";
	// try{
		// document.getElementById(btn+"_btn").className=btn+"_on";
	// }catch(E){}
	//alert(document.getElementById(btn+"_btn").className)
	
}*/
/*滾球提示--將值帶進去去開啟getrecRB.php程式,去抓取伺服器是否有滾球賽程*/
var record_RB = 0;
function reloadRB(gtype,uid){
	reloadPHP.location.href="./getrecRB.php?gtype="+gtype+"&uid="+top.uid;
}

/*滾球提示--將getrecRB.php的結果帶進去,去判斷是否record_RB是否大於0,如果有會顯示滾球圖示*/

function showLayer(record_RB){
	/*if (record_RB > 0) {
		document.getElementById('rbyshow').style.display='block';
	}else{
		document.getElementById('rbyshow').style.display='none';
		
	}
	//document.getElementById('RB_games').innerHTML=record_RB;
	document.getElementById('RB_FT_games').innerHTML=0;
	document.getElementById('RB_BK_games').innerHTML=0;
	document.getElementById('RB_TN_games').innerHTML=0;
	document.getElementById('RB_BS_games').innerHTML=0;
	document.getElementById('RB_VB_games').innerHTML=0;
	document.getElementById('RB_OP_games').innerHTML=0;*/
	document.getElementById('RB_games').innerHTML=record_RB;
	document.getElementById('FT_games').innerHTML=0;
	document.getElementById('BK_games').innerHTML=0;
	document.getElementById('TN_games').innerHTML=0;
	document.getElementById('BS_games').innerHTML=0;
	document.getElementById('VB_games').innerHTML=0;
	document.getElementById('OP_games').innerHTML=0;
	reloadCrditFunction();
}

/* 滾球提示--程式一開始值呼叫reloadRb,setInterval函式 多久會呼叫reloadRB函數預設 5分鐘 */
function SetRB(gttype,uid){
	reloadRB(gttype,uid);
	setInterval("reloadRB('"+gttype+"','"+top.uid+"')",60*1000);
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
		Real_Win=window.open("./account/chg_passwd.php?uid="+top.uid,"Chg_pass","width=490,height=300,status=no");
	}else{
		return;
	}
}
function OpenLive(){
	if (top.liveid == undefined) {
		parent.self.location = "";
		return;
	}
	//關閉主視窗 連子視窗一起關閉
	var newWinObj2=new Array();
	for(var i=0;i<top.newWinObj.length;i++){
		if(!top.newWinObj[i].closed) newWinObj2[newWinObj2.length]=top.newWinObj[i];
	}
	top.newWinObj=newWinObj2;

	var DWinObj = window.open("./live/live.php?langx="+top.langx+"&uid="+top.uid+"&liveid="+top.liveid,"Live","width=780,height=580,top=0,left=0,status=no,toolbar=no,scrollbars=no,resizable=no,personalbar=no");
	top.newWinObj[top.newWinObj.length]=DWinObj;
}

// function showTable(){
	// showGtypeTable();

// }

// function showGtypeTable(){
	// for (var i=0 ; i < showGtype.length ; i++){
		// var txtnum = StatisticsGty(top.today_gmt,showGtype[i]);
		// var gtypenum =(txtnum[0] == 0)?"_2":"";
		// document.getElementById("img_"+showGtype[i]).src ="../../../images/member/head_L"+showGtype[i]+gtypenum+".gif";
		// document.getElementById("img_"+showGtype[i]).title =	eval("top.str_"+showGtype[i]);
		// document.getElementById("img_"+showGtype[i]).style.cursor =((txtnum[0] == 0)?"":"hand");
		// document.getElementById("imp_"+showGtype[i]).title =top.str_delShowLoveI;
	// }
// }

/*
function chkLookGtypeShowLoveI(getgtype,gtype){
	var txtnum = StatisticsGty(top.today_gmt,gtype);
	if(txtnum[0]==0)return ;
	top.swShowLoveI =true;
	if(getgtype != top.getNewGtype ){
		top.getNewGtype =getgtype;
		parent.location=getgtype+"&league_id=3";
	}else{
		eval("parent."+gtype+"_lid_type='3'");
		//parent.body.ShowGameList();
		//alert(parent.body.pg);
		parent.body.pg =0;
		parent.body.body_browse.reload_var("up");
	}
}
function chkDelAllShowLoveI(getGtype){
	top.ShowLoveIarray[getGtype]= new Array();
	top.ShowLoveIOKarray[getGtype]="";
	if(top.swShowLoveI){
		top.swShowLoveI=false;
		eval("parent."+parent.body.sel_gtype+"_lid_type=top."+parent.body.sel_gtype+"_lid['"+parent.body.sel_gtype+"_lid_type']");
		parent.body.pg =0;
		parent.body.body_browse.reload_var("up");
	}else{
		parent.body.ShowGameList();
	}
	showTable();
	parent.body.body_browse.earlyShowGtypeTable();
}*/
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
/*function StatisticsGty(today,gtype){
	var array =new Array(0,0);
	var tmp =today.split("-");
	var newtoday =tmp[1]+"-"+tmp[2];
	var tmpgday = new Array(0,0);
	var bf = false;
	for (var i=0 ; i < top.ShowLoveIarray[gtype].length ; i++){
		tmpday = top.ShowLoveIarray[gtype][i][1].split("<br>")[0];
		tmpgday = tmpday.split("-");
		if(++tmpgday[0] < tmp[1]){
			bf = true;
		}else{
			bf = false;
		}
		if(bf){
			array[1]++;
		}else{
			if(newtoday >= tmpday ){
				array[0]++;	//單式
			}else if(newtoday < tmpday){
				array[1]++;	//早餐
			}
		}
	}
	return array;
}*/

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
		else{
			window.open (url,name,temp);
		}
	}
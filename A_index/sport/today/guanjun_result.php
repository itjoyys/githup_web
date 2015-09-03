
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<script language="javascript" src="../style/js/jquery.js"></script>
<script language="javascript" src="../style/js/jquery_dialog.js"></script>
<script language="javascript" src="../style/js/common.js"></script>
<script language="javascript" src="guanjun_result.js" ></script>
<script language="javascript" src="../style/js/guanjun.js"></script>
<script language="javascript" src="../style/js/mouse.js"></script>

<script type="text/javascript">
function shuaxin(league){
	time=121;
	var page = document.getElementById('aaaaa').innerHTML;
	loaded(league,page);
}
function Wleague(league){
	loaded(league);
}
function NumPage(thispage){
	var league = document.getElementById('league').value;
	document.getElementById('aaaaa').innerHTML = thispage;
	loaded(league,thispage);
}
</script>
<style>
html{overflow-x:no;}
</style>
 <script language="javascript" src="../style/js/times.js"></script>

<script  language="JavaScript">


function chg_league() {
	var legview = document.getElementById('legView');
	try {
		if(window_lsm.length > 2000){
			if(window.XMLHttpRequest){ //Mozilla, Safari, IE7 
				if(!window.ActiveXObject){ // Mozilla, Safari,
					
					legFrame.location.href = "chuangkous.php?lsm="+window_lsm;
					//JqueryDialog.Open('足球单式', 'dialog.php?lsm='+window_lsm, 600, window_hight);
				}else{ //IE7
					//JqueryDialog.Open('足球单式', 'dialog.php?lsm=zqds', 600, window_hight);
					legFrame.location.href = "chuangkous.php?lsm=zqds";
				}
			}else{ //IE6
				//JqueryDialog.Open('足球单式', 'dialog.php?lsm=zqds', 600, window_hight);
				legFrame.location.href = "chuangkous.php?lsm=zqds";
			}
		}else{
			//JqueryDialog.Open('足球单式', 'dialog.php?lsm='+window_lsm, 600, window_hight);
			legFrame.location.href = "chuangkous.php?lsm="+window_lsm;
		}
	
	
	
	
		//legFrame.location.href = "chuangkous.php?uid=" + parent.uid + "&rtype=" + parent.rtype + "&langx=" + parent.langx + "&mtype=" + parent.ltype;
	} catch(e) {
	
		if(window_lsm.length > 2000){
			if(window.XMLHttpRequest){ //Mozilla, Safari, IE7 
				if(!window.ActiveXObject){ // Mozilla, Safari,
					
					legFrame.src = "chuangkous.php?lsm="+window_lsm;
					//JqueryDialog.Open('足球单式', 'dialog.php?lsm='+window_lsm, 600, window_hight);
				}else{ //IE7
					//JqueryDialog.Open('足球单式', 'dialog.php?lsm=zqds', 600, window_hight);
					legFrame.src = "chuangkous.php?lsm=zqds";
				}
			}else{ //IE6
				//JqueryDialog.Open('足球单式', 'dialog.php?lsm=zqds', 600, window_hight);
				legFrame.src = "chuangkous.php?lsm=zqds";
			}
		}else{
			//JqueryDialog.Open('足球单式', 'dialog.php?lsm='+window_lsm, 600, window_hight);
			legFrame.src = "chuangkous.php?lsm="+window_lsm;
		}
	
	
	
	
		//legFrame.src = "chuangkous.php?uid=" + parent.uid + "&rtype=" + parent.rtype + "&langx=" + parent.langx + "&mtype=" + parent.ltype;

	}
	legview.style.display = '';
	legview.style.top = document.body.scrollTop + 82; 
	
	
	legview.style.left = document.getElementById('myTable').scrollLeft + 10;

	
}
function setleghi(leghight) {
	var legview = document.getElementById('legFrame');

	if ((leghight * 1) > 95) {
		legview.height = leghight;
	} else {

		legview.height = 95;
	}
	
	
	
}
function LegBack() {
	var legview = document.getElementById('legView');
	legview.style.display = 'none';
	reload_var("");
}

</script>

<title>body_football_r</title>

<link  rel="stylesheet"  href="../public/css/mem_body_olympics.css"  type="text/css">


</head>

<body  id="Mr"  class="bodyset" onload="loaded(document.getElementById('league').value,0)" >

<table  border="0"  cellpadding="0"  cellspacing="0"  id="myTable">
	<tbody>
		<tr>
			<td>
				<table  border="0"  cellpadding="0"  cellspacing="0"  id="box">
				    <tbody>
					<tr>
						<td  class="top"><h1><em>冠　军</em><span  class="maxbet">单注最高派彩额：  500000</span>
						</h1></td>
					</tr>
					<tr>
						<td  class="mem">
							<h2>
								<table  width="100%"  border="0"  cellpadding="0"  cellspacing="0"  id="fav_bar">
									<tbody>
										<tr>
											<td  id="page_no">
											<div  id="euro_btn"  class="euro_btn"     style=""></div><div  id="euro_up"  class="euro_up"     style="display: none;"></div>
											<div id="top" style="vertical-align:top; cursor:pointer">
											</div><!--
											时间:<span  id="show_date_opt"><select  id="g_date"  name="g_date" ><option  value="ALL">全部</option><option  value="2015-01-18">1月18日</option><option  value="2015-01-19">1月19日</option><option  value="2015-01-20">1月20日</option><option  value="2015-01-21">1月21日</option><option  value="2015-01-22">1月22日</option><option  value="2015-01-23">1月23日</option><option  value="2015-01-24">1月24日</option><option  value="2015-01-25">1月25日</option><option  value="2015-01-26">1月26日</option><option  value="2015-01-27">1月27日</option><option  value="2015-01-28">1月28日</option></select></span>-->
											</td>
											<td  id="tool_td">
							  
												<table  border="0"  cellspacing="0"  cellpadding="0"  class="tool_box">
													<tbody>
														<tr id="shuaxin">
															<td  class="refresh_btn"  id="refresh_btn" ><div >
															<a href="javascript:void(0)" onclick="javascript:shuaxin(document.getElementById('league').value);" title="点击刷新">
																<font id="sx_f5">刷新</font>
															</a>
															<input type="hidden" name="top_f5" id="top_f5" value="0"  />
															</div>
															</td>
															<td  class="leg_btn xzls">
															</td>
															<td  id="SortGame"  class="SortGame"  name="SortGame">
																<select name="league" id="league">
																<?php
																$date	=	$_GET['league'];
																for($i=0;$i<7;$i++){
																	$d	=	date('Y-m-d',time()-$i*86400);
																?>
																		<option value="<?=$d?>" <?= $d==$date ? 'selected="selected"' : ''?>><?=$d?></option>
																<?php
																}
																?>
																		</select>
															</td>
															<td  class="OrderType"  id="Ordertype"></td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
									</tbody>
								</table>
							</h2>
          		
					
  
   
          <table  id="game_table"  cellspacing="0"  cellpadding="0"  class="game">
            <tr>
              <th  class="time">时间</th>
			  <th  class="time">项目</th>
			  <th  class="h_1x2">队伍（球员）</th>
			  <th class="h_r">胜出</th>
			
            </tr>
         <tbody  id="datashow">		 
		
		  <tr>
			<td  colspan="4"  class="b_hline">
				<table  border="0"  cellpadding="0"  cellspacing="0"  width="100%"><tbody>
					<tr>
						<td  class="legicon" >
						<span  id="亚洲杯2015(在澳洲)"  class="showleg"><span  id="LegOpen"></span> </span>
						</td>
						<td  class="leg_bar" >亚洲杯2015(在澳洲) </td>
					</tr>
				</tbody></table>
			</td>
		  </tr>

		  <tr  id="TR_01-1870256"    *class*="">
				<td  class="b_cen">01-18<br>05:00<br><font  color="red">滚球</font></td>
				<td  class="b_cen"> 中国  </td>
				<td  class="b_cen">01-18<br>05:00<br><font  color="red">滚球</font></td>
				<td  class="b_cen"> 中国  </td>
				
				
		  </tr> 
		 
		  
		  
		 
		  <tr><td  colspan="4" align="center" style="background:#fff;">正在加载数据...</td></tr>
	
	</tbody></table>
  
	
	</td>
      </tr>
   
    </tbody></table>
				
				
				<div  id="refresh_down"  class="refresh_M_btn" ><span>刷新</span></div>
				

			</td>
		</tr>
	</tbody>
</table>

	



<div  id="legView"  style="display:none;"  class="legView">
    <div  class="leg_head" ></div>
	<div><iframe  id="legFrame"  scrolling="no"  frameborder="no"  border="0"  allowtransparency="true"></iframe></div>
    <div  class="leg_foot"></div>
</div>

</body></html>



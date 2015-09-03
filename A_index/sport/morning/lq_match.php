<?php
session_start();
include_once("../../include/public_config.php");
include_once("../../include/config.php");

$date	=	date('Y-m-d',strtotime("-12 hour"));
if($_GET['ymd']) $date	=	$_GET['ymd'];
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<script language="javascript" src="../style/js/jquery.js"></script>
<script language="javascript">
var i = 31;
function check(){	
	clearTimeout(aas);		
	i = i -1;
	$("#location").html("对不起,您点击页面太快,请在"+i+"秒后进行操作");
	if(i == 1){
		window.location.href ='bet_match.php'
	}
	var aas = setTimeout("check()",1000);
}
</script>


<title></title>

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
						<td  class="top"><h1><em>篮球结果</em><span  class="maxbet">单注最高派彩额：  500000</span>
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
											</div>
											
											</td>
											<td  id="tool_td">
							  
												<table  border="0"  cellspacing="0"  cellpadding="0"  class="tool_box">
													<tbody><form class="bk3" style="margin:0 0 0 0" action="" method="get">
														<tr id="shuaxin">
															<td  class="refresh_btn"  id="refresh_btn" >
															</td>
															<td  class="leg_btn xzls">
															</td>
															<td  id="SortGame"  class="SortGame"  name="SortGame">
																<select  id="ymd" name="ymd">
																	<?php
																	for($i=0;$i<7;$i++){
																		$d	=	date('Y-m-d',strtotime("-12 hour")-$i*86400);
																	?>
																			<option value="<?=$d?>" <?= $d==$date ? 'selected="selected"' : ''?>><?=$d?></option>
																	<?php
																	}
																	?>
																</select>
																<input type="submit" value="查询">
															</td>
															<td  class="OrderType"  id="Ordertype"></td>
														</tr></form>
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
			  <th  class="time">主客队伍</th>
			  <th  class="h_1x2">1</th>
			  <th class="h_1x2">2</th>
			  <th  class="h_1x2">3</th>
			  <th class="h_1x2">4</th>
			  <th  class="h_1x2">上半</th>
			  <th class="h_1x2">下半</th>
			  <th  class="h_1x2">加时</th>
			  <th class="h_1x2">全场</th>
		
            </tr>
         <tbody  id="datashow">
		 <?php
$sql	=	"select Match_Date,Match_Time, match_name,match_master,match_guest,MB_Inball_1st,TG_Inball_1st,MB_Inball_2st,TG_Inball_2st,MB_Inball_3st,TG_Inball_3st,MB_Inball_4st,TG_Inball_4st,MB_Inball_HR,	TG_Inball_HR,MB_Inball_ER,TG_Inball_ER,MB_Inball,TG_Inball,MB_Inball_Add,TG_Inball_Add from  lq_match where MB_Inball_OK is not null and  match_Date='".date('m-d',strtotime($date))."' and match_js=1 order by match_coverdate,match_id asc";
$query	=	$mysqli->query($sql);  		
$rows		=	$query->fetch_array();
if(!$rows){
	echo '<tr><td  colspan="10" align="center" style="background:#fff;">暂无赛果</td></tr>';
}else{
	do{
		if($temp_match_name != $rows["match_name"]){
			$temp_match_name = $rows["match_name"]; 
?>
			<tr>
			<td  colspan="10"  class="b_hline">
				<table  border="0"  cellpadding="0"  cellspacing="0"  width="100%"><tbody>
					<tr>
						<td  class="legicon" >
						<span  id="<?=$rows["match_name"]?>"  class="showleg"><span  id="LegOpen"></span> </span>
						</td>
						<td  class="leg_bar" ><?=$rows["match_name"]?> </td>
					</tr>
				</tbody></table>
			</td>
		  </tr>
		<?php
				}
		?>
		  <tr  id="TR_01-1870256"    *class*="">
				<td  rowspan="2"  class="b_cen" width="26%">&nbsp;<?=$rows["Match_Date"]?><br /><?=$rows["Match_Time"]?></td>
				<td  rowspan="2"  class="b_cen"  width="26%">&nbsp; <?=$rows["match_master"]?> <br>
				   <?=$rows["match_guest"]?> </td>
				
					<td  class="b_cen"  id="118389861_MH">&nbsp;<?=$rows["MB_Inball_1st"]>=0 ? $rows["MB_Inball_1st"] : '无效'?></td>
					<td  class="b_cen"  id="118389861_PRH">&nbsp;<?=$rows["MB_Inball_2st"]>=0 ? $rows["MB_Inball_2st"] : '无效'?></td>
					<td  class="b_cen"  id="118389861_MH">&nbsp;<?=$rows["MB_Inball_3st"]>=0 ? $rows["MB_Inball_3st"] : '无效'?></td>
					<td  class="b_cen"  id="118389861_PRH">&nbsp;<?=$rows["MB_Inball_4st"]>=0 ? $rows["MB_Inball_4st"] : '无效'?></td>
					<td  class="b_cen"  id="118389861_MH">&nbsp;<?=$rows["MB_Inball_HR"]>=0 ? $rows["MB_Inball_HR"] : '无效'?></td>
					<td  class="b_cen"  id="118389861_PRH">&nbsp;<?=$rows["MB_Inball_ER"]>=0 ? $rows["MB_Inball_ER"] : '无效'?></td>
					<td  class="b_cen"  id="118389861_MH">&nbsp;<?=$rows["MB_Inball_Add"]<0 ? '无效' : $rows["MB_Inball_Add"]>0 ? $rows["MB_Inball_Add"] : ''; ?></td>
					<td  class="b_cen"  id="118389861_PRH">&nbsp;<?=$rows["MB_Inball"]>=0 ? $rows["MB_Inball"] : '无效'?></td>
				
				
				
		  </tr> 
		  <tr  id="TR_01-1870256"    *class*="">
				
					<td  class="b_cen"  id="118389861_MH">&nbsp;<?=$rows["TG_Inball_1st"]>=0 ? $rows["TG_Inball_1st"] : '无效'?></td>
					<td  class="b_cen"  id="118389861_PRH">&nbsp;<?=$rows["TG_Inball_2st"]>=0 ? $rows["TG_Inball_2st"] : '无效'?></td>
					<td  class="b_cen"  id="118389861_MH">&nbsp;<?=$rows["TG_Inball_3st"]>=0 ? $rows["TG_Inball_3st"] : '无效'?></div></td>
					<td  class="b_cen"  id="118389861_PRH">&nbsp;<?=$rows["TG_Inball_4st"]>=0 ? $rows["TG_Inball_4st"] : '无效'?></td>
					<td  class="b_cen"  id="118389861_MH">&nbsp;<?=$rows["TG_Inball_HR"]>=0 ? $rows["TG_Inball_HR"] : '无效'?></td>
					<td  class="b_cen"  id="118389861_PRH">&nbsp;<?=$rows["TG_Inball_ER"]>=0 ? $rows["TG_Inball_ER"] : '无效'?></td>
					<td  class="b_cen"  id="118389861_MH">&nbsp;<?=$rows["TG_Inball_Add"]<0 ? '无效' : $rows["TG_Inball_Add"]>0 ? $rows["TG_Inball_Add"] : ''; ?></td>
					<td  class="b_cen"  id="118389861_PRH">&nbsp;<?=$rows["TG_Inball"]>=0 ? $rows["TG_Inball"] : '无效'?></td>
				
				
				
		  </tr> 
		
<?php
	}while($rows = $query->fetch_array());
}
?>
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



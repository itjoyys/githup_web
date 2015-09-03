<?
$url = $_SERVER['PHP_SELF']; 
$arr = explode( '/' , $url ); 
$filename= $arr[count($arr)-1];
$url_type=substr($filename,0,-4); 
if($url_type=="football"){
	$title='足球单式';
}elseif($url_type=="ft_shangbanchang"){
	$title='足球上半场';
}elseif($url_type=="ft_bodan"){
	$title='足球波胆';
}elseif($url_type=="ft_shangbanbodan"){
	$title='足球上半波胆';
}elseif($url_type=="ft_ruqiushu"){
	$title='足球总入球';
}elseif($url_type=="ft_banquanchang"){
	$title='足球半全场';
}elseif($url_type=="bk_danshi"){
	$title='篮美单式';
}elseif($url_type=="tennis_danshi"){
	$title='网球单式';
}elseif($url_type=="volleyball"){
	$title='排球单式';
}elseif($url_type=="guanjun"){
	$title='冠军';
}elseif($url_type=="baseball"){
	$title='棒球单式';
}elseif($url_type=="chuanguan"){
	$title='足球单式';
}elseif($url_type=="bk_danshic"){
	$title='篮美单式';
}elseif($url_type=="ft_shangbanchangc"){
	$title='足球上半场';
}elseif($url_type=="football_c"){
	$title='足球:综合过关';
}elseif($url_type=="bk_danshisc"){
	$title='篮球:综合过关';
}elseif($url_type=="bk_danshisc"){
	$title='篮球:综合过关';
}elseif($url_type=="tennis_bodan"){
	$title='网球:塞盘投注';
}elseif($url_type=="tennis_danshi_c"){
	$title='网球:综合过关';
}elseif($url_type=="volleyball_bodan"){
	$title='排球:塞盘投注';
}elseif($url_type=="volleyball_c"){
	$title='排球:综合过关';
}elseif($url_type=="baseball_c"){
	$title='棒球:综合过关';
}elseif($url_type=="qita"){
	$title='今日其他体育';
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>body_football_r</title>

<link  rel="stylesheet"  href="../public/css/mem_body_olympics.css"  type="text/css">
<script>
function fenye(){
	var thispage=$("#fenye").val();//alert(thispage);
	NumPage(thispage);
}
</script>
</head>

<body  id="Mr"  class="bodyset" onload="loaded(document.getElementById('league').value,0)" >

<table  border="0"  cellpadding="0"  cellspacing="0"  id="myTable">
	<tbody>
		<tr>
			<td>
				<table  border="0"  cellpadding="0"  cellspacing="0"  id="box">
				    <tbody>
					<tr>
						<td  class="top">
                            <h1><?=$title?></h1>
                        </td>
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
													<tbody>
														<tr id="shuaxin">
															<td  class="refresh_btn"  id="refresh_btn" onclick="javascript:shuaxin(document.getElementById('league').value);" title="点击刷新">
															
															<div >
															

																<font id="sx_f5">刷新</font>

															<div style="display:none" id="aaaaa">0</div>
															<input type="hidden" name="top_f5" id="top_f5" value="0"  />
															</div>
															</td>
															<td  class="leg_btn xzls">
															<input type="hidden" name="league" id="league" value=""  />
															<div style="margin-top:-2px;" onClick="javascript:chg_league();" id="xzls">选择联赛 (<span  id="str_num">全部</span>)</div>
                                                                <div style="display:none" id="window_lsm">0</div>
                                                            </td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
									</tbody>
								</table>
							</h2>
          		
					
  
   
      
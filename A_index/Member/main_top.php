
<html lang="en" style="height:100px;">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body style="height:100px; PADDING-TOP: 11px">
	

<link rel="stylesheet" href="./public/css/index_main.css" />

<div id="MACenter" style="position: relative;">
  <div id="MAHeader">
    <div id="MALogo"></div>
    <div id="MATime">
      <div id="timepic"></div>
      <div id="est_bg" class="time_text">美东时间：<span id="EST_reciprocal">2015/01/02 22:25:43</span>
      </div> 
      <div id="MADeposit"  target="k_memr"   style="background-position: 0px 0%; top: 50px;"><a href="./cash/set_money.php"  target="k_memr" style="color:#fff;text-decoration:none;" >线上存款</a></div>
<style type="text/css">
.time_text {
	TEXT-ALIGN: center; FILTER: progid:DXImageTransform.Microsoft.Alpha(Opacity=80); LINE-HEIGHT: 20px; BACKGROUND-COLOR: #fff; WIDTH: 200px; HEIGHT: 20px; COLOR: #000; FONT-SIZE: 12px; opacity: 0.80
}
</style>
 <script type="text/javascript">
 //设置美东时间
var mddate="<?php echo date('Y').'/'.date('m').'/'.date('d').' '.date('H').':'.date('i').':'.date('s');;?>";
var dd2=new Date(mddate);
	setInterval("RefTime()",1000);
	//設置美東時間
	function RefTime()
	{
		dd2.setSeconds(dd2.getSeconds()+1);
		var myYears = ( dd2.getYear() < 1900 ) ? ( 1900 + dd2.getYear() ) : dd2.getYear();
		document.getElementById("EST_reciprocal").innerHTML = myYears+"/"+fixNum(dd2.getMonth()+1)+"/"+fixNum(dd2.getDate())+" "+time(dd2);
	}
	function fixNum(num){
		return (parseInt(num) < 10) ? ('0'+ num) : num;
	}
	function time(vtime){
		var s='';
		var d=vtime!=null?new Date(vtime):new Date();
		with(d){
			s=fixNum(getHours())+':'+fixNum(getMinutes())+':'+fixNum(getSeconds())
		}
		return(s);
	}
</script>

    </div>
  </div>
</body>
</html>

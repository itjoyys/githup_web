<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Frameset//CN" "http://www.w3. org/TR/html4/frameset.dtd">
<html  xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>welcome</title>
<link  rel="stylesheet"  href="./public/<?=$_SESSION['site_id']?>/css/default.css">
<style>
html, body, *html {
	width:100%;
	height:100%;
	margin:0px;
	padding:0px;
}
body{
	overflow:hidden;
}
#Content {
	position:absolute;
	bottom:0;
	left:0;
	right:0;
	z-index:3;
	width:100%;
	overflow:hidden;
	height:100%;
}
</style>
<script  type="text/javascript">
function openGg(url)
{
	art.dialog.open(url, {
		title: '最新公告',
		width:750,
		height:330,
		window:'top',
		esc:true
	});	
}
function openWin(url,t,w,h)
{
	art.dialog.open(url, {
		title: t,
		width:w,
		height:h,
		window:'top',
		esc:true
	});	
}
</script>
</head>
<body>
<div  id="Content">
	<iframe  name="index"  src="admin_index_2.php"  frameborder="0"  marginheight="0"  marginwidth="0"  style="width:100%;height:100%;">
		
	</iframe> 
</div>

</body>
</html>
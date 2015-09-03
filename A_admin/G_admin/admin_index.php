<html xmlns="http://www.w3.org/1999/xhtml"><head>
<title>welcome</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="./public/<?=$_SESSION['site_id']?>/css/default.css">

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

</head>
<body>
<div id="Content">
<iframe name="index" src="./app/daili/index.php" frameborder="0" marginheight="0" marginwidth="0" style="width:100%;height:100%;"></iframe> 
</div>

</body></html>
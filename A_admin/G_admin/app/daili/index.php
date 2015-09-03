<?php 
$uid=$_SESSION['adminid'];
//echo $uid;exit;
 ?>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<title>Welcome</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
html, body, *html {
	width:100%;
	height:100%;
	margin:0px;
	padding:0px;
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
<body style="margin:0px;padding:0px;overflow:hidden;" marginwidth="0" marginheight="0">
<div id="Content">
<iframe name="IN_mem_index" src="main_index.php?uid=<?=$uid ?>&amp;langx=zh-tw" frameborder="0" marginheight="0" marginwidth="0" width="100%" height="100%"></iframe> 
</div>



</body></html>
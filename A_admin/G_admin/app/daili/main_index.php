<?php 
$uid=$_SESSION['adminid'];
 ?>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script>if(self == top) location="/app/daili/logout.php";</script>
</head>
<frameset rows="120,*" framespacing="0" style="padding:0px" frameborder="0">
	<frame name="admin_mem_index" id="admin_mem_index" src="admin_left.php?uid=<?=$uid ?>&amp;langx=zh-tw" scrolling="no" noresize="" target="admin_func">
	<frame name="admin_func" id="admin_func" src="../../other/notice.php?uid=<?=$uid ?>&amp;langx=zh-tw">
	<noframes>
	&lt;body&gt;
	&lt;/body&gt;
	</noframes>
</frameset>



</html>
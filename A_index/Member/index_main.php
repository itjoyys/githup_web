<?php 
include_once dirname(__FILE__) . '/../include/site_config.php';
include_once(dirname(__FILE__)."/../wh/site_state.php");
	GetSiteStatus($SiteStatus,1,'webhome',1);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Member Center</title>
<link rel="stylesheet" type="text/css" href="./public/css/standard.css">
<link rel="stylesheet" type="text/css" href="./public/css/jquery-ui-1.9.2.custom.css">
<script language="javascript" type="text/javascript" src="./public/js/jquery-1.8.3.min.js"></script>
<SCRIPT language="javascript" src="./public/js/jquery.js"></SCRIPT>
<SCRIPT language="javascript" src="./public/js/member.js"></SCRIPT>


</head>

	<frameset rows="100,426,*" cols="1000" frameborder="NO" border="0" framespacing="0" > 
	   <frame id="frmtop" scrolling="no"  name="frmtop" src="./main_top.php" frameborder="0"></frame>
	  <frame id="frmdown" scrolling="no"  name="frmdown" src="./main.php?url=<?=$_GET['url']?>" frameborder="0"></frame>
	  	<frameset rows="*" cols="*,1000,*" frameborder="NO" border="0" framespacing="0" > 
	  		<frame id="k_mem11" scrolling="no" name="k_meml11" src="./gray.php" frameborder="0"></frame>
	 		<frame id="frmfoot" scrolling="NO"  name="frmfoot" src="./public/footer.php" frameborder="0"></frame>
	 		<frame id="k_mem11" scrolling="no" name="k_meml11" src="./gray.php" frameborder="0"></frame>
		</frameset>
	</frameset>




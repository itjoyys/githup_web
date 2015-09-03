 <?php
include_once("../../include/config.php");
include_once("../../include/private_config.php");
include_once("../../class/user.php");
//版权查询
$copy_right = user::copy_right(SITEID,INDEX_ID);
 ?>

 <!doctype html>
 <html lang="en" style="width:1000px;">
 <head>
 	<meta charset="UTF-8">
 	<title></title>
 	<link rel="stylesheet" href="./css/index_main.css" />

 </head>
 <body>
 	

    <div class="clear"></div>
    <!--<div id="MAContentBottom"></div>-->
    <div id="MAFoot">Copyright © <?=$copy_right['copy_right']?> Reserved</div>	
  </div>
</div>
 </body>
 </html>

</body>
</html>
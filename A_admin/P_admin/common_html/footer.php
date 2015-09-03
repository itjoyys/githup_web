<?php
  include_once("../common/login_check.php");
  include_once("../../lib/class/model.class.php");
  $data = M('web_config',$db_config)->field("after_bq")->where("site_id = '".SITEID."'")
        ->find();

$starttime = explode(" ",$pagestartime); 
$endtime = explode(" ",$pageendtime); 
$totaltime = $endtime[0]-$starttime[0]+$endtime[1]-$starttime[1]; 
$timecost = sprintf("%s",$totaltime); 
?>
	<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0"  style="clear:both">
		<tbody><tr>
			<td  align="center"  height="50"> <?=$data['after_bq']?> 版权所有 建議您以 1024 X 768 以上高彩模式瀏覽本站(<?=$timecost?>)</td>
		</tr>
	</tbody></table>
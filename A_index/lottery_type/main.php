<?php
 switch ($_GET['type']) {
 	case '3':
 		$src_html = "cq_ssc.php?type=ball_1";
 		break;
 	case '8':
 		$src_html = "cq_kl10.php?type=ball_1";
 		break;
 	case '7':
 		$src_html = "gd_kl10.php?type=ball_1";
 		break;
 	case '5':
 		$src_html = "bj_pk10.php?type=ball_1";
 		break;
 	case '1':
 		$src_html = "fc_3d.php?type=ball_1";
 		break;
 	case '2':
 		$src_html = "fc_pl3.php?type=ball_1";
 		break;
 	case '4':
 		$src_html = "bj_kl8.php?type=ball_1";
 		break;
 	case '6':
 		$src_html = "liuhecai.php?action=k_tm";
 		break;
 	case '10':
 		$src_html = "tj_ssc.php?type=ball_1";
 		break;
 	case '11':
 		$src_html = "jx_ssc.php?type=ball_1";
 		break;
 	case '12':
 		$src_html = "xj_ssc.php?type=ball_1";
 		break;
 	case '13':
 		$src_html = "js_k3.php?type=ball_1";
 		break;
 	case '14':
 		$src_html = "jl_k3.php?type=ball_1";
 		break;
 	default:
 		# code...
 		break;
 }

?>
<link rel="stylesheet" href="./public/css/reset.css" type="text/css">
<link rel="stylesheet" href="./public/css/xp.css" type="text/css">

</head>
<frameset rows="75,*" cols="*" frameborder="NO" border="0" framespacing="0" style="height: 100%;">
   <frame id="frmRight" scrolling="NO" style="overflow-x: hidden;" name="right" src="main_r.php?type=<?=$_GET['type']?>" frameborder="0">
  <frameset cols="236,*" frameborder="NO" border="0" framespacing="0" style="height: 100%;">
    <frame id="k_mem" scrolling="auto" name="k_meml" src="main_left.php?type=<?=$_GET['type']?>&type_y=<?=$_GET['type']?>" frameborder="0">
        <frame id="k_memr" marginwidth="3" marginheight="3" style="z-index: 1; visibility: inherit;overflow-x:hidden;height: 100%;margin:5px;float:left" name="k_memr" src=<?=$src_html?> frameborder="0">


  </frameset>
</frameset>

</html>
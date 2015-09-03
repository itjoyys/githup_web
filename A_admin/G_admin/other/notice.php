<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
/**
*
*   上级公告信息读取
*   sid表示站点 0表示全站 3,4表示站点3和4 为3表示站点3
*   notice_cate表示公告分类  s_p体育f_c彩票v_d视讯
*
**/

$u = M('site_notice',$db_config);
$map = "sid = 0 or sid = '".SITEID."'";

$notice = $u->where($map)->order('notice_cate ASC')->select();
//数据拼接
foreach ($notice as $key => $val) {
   switch ($val['notice_cate']) {
   	case 'f_c'://彩票
   		$fc[] = $val;
   		break;
   	case 's_p'://体育
   		$sp[] = $val;
   		break;
   	case 'v_d'://视讯
   		$vd[] = $val;
   		break;
   }
}

?>

<?php require("../common_html/header.php");?>
<body>
<style type="text/css">
	.m_tab{
		width: 32%;
	}
</style>
<div id="con_wrap">
<div class="input_002">公告信息</div>
<div class="con_menu">
</div>
</div>
<div class="content">
<table border="0" cellspacing="0" cellpadding="0" class="m_tab" width="32%" style="float:left;clear:none">

	<tbody><tr class="m_title_over_co">
		<td colspan="2">體育公告</td>
		<td></td>
	</tr>
	<?php if (!empty($sp)) {
		foreach ($sp as $key => $val) {
     ?>
			<tr>
		<td align="center" width="110px;">
			<?=$val['notice_date']?>
		</td>
		<td align="center">
			<?=$val['notice_content']?>
		</td>
		</td>
	</tr>
	<?php
		}
	}else{
	?>
				<tr>
		<td align="center" height="70">
			暫無公告數據
		</td>
	</tr>
	<?php
	}
	?>
		
</tbody></table>
<table border="0" cellspacing="0" cellpadding="0" class="m_tab" width="32%" style="float:left;clear:none">

	<tbody><tr class="m_title_over_co">
		<td colspan="2">彩票公告</td>
		<td></td>
	</tr>
		<?php if (!empty($fc)) {
		foreach ($fc as $key => $val) {
     ?>
			<tr>
		<td align="center" width="110px;">
			<?=$val['notice_date']?>
		</td>
		<td align="center">
			<?=$val['notice_content']?>
		</td>
		</td>
	</tr>
	<?php
		}
	}else{
	?>
				<tr>
		<td align="center" height="70">
			暫無公告數據
		</td>
	</tr>
	<?php
	}
	?>
		
</tbody></table>
<table border="0" cellspacing="0" cellpadding="0" class="m_tab" width="32%" style="float:left;clear:none">

	<tbody><tr class="m_title_over_co">
		<td colspan="2">視訊公告</td>
		<td></td>
	</tr>
		<?php if (!empty($vd)) {
		foreach ($vd as $key => $val) {
     ?>
			<tr>
		<td align="center" width="110px;">
			<?=$val['notice_date']?>
		</td>
		<td align="center">
			<?=$val['notice_content']?>
		</td>
		</td>
	</tr>
	<?php
		}
	}else{
	?>
				<tr>
		<td align="center" height="70">
			暫無公告數據
		</td>
	</tr>
	<?php
	}
	?>

		
</tbody></table>

</div>
<?php include("../common_html/footer.php"); ?>
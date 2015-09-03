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
$map = "sid = '0' and notice_state = '1'";

$notice = $u->where($map)->order('notice_date DESC')->select();
//数据拼接
foreach ($notice as $key => $val) {
   switch ($val['notice_cate']) {
   	case '3'://彩票
   		$fc[] = $val;
   		break;
   	case '4'://体育
   		$sp[] = $val;
   		break;
   	case '5'://视讯
   		$vd[] = $val;
   		break;
   	case '2'://视讯
   		$wh[] = $val;
   		break;
   }
}
//var_dump($sp);exit;
?>

<?php $title="上级公告"; require("../common_html/header.php");?>
<body>
<style type="text/css">
	.m_tab{
		width:32%;
	}
</style>
<div id="con_wrap">
<div class="input_002">公告信息</div>
<div class="con_menu">
<!-- <input class="button_d" type="button" onclick="document.location='notice_add.php'" value="发布新公告"> -->
<!-- 
&nbsp;&nbsp;請選擇顯示的語言：&nbsp;
<select name='lang' onchange="document.location.href='main.php?uid=8d56449ed007f6cc2fea4f3a0f92&action=gonggao&langx=' + this.value;">
	<option value="zh-tw" selected="selected">繁體中文</option>
	<option value="zh-cn" >简体中文</option>
	<option value="en-us" >English</option>
</select>
-->
</div>
</div>
<div class="content">
<table border="0" cellspacing="0" cellpadding="0" class="m_tab" width="24%" style="float:left;clear:none;width:320px">

	<tbody><tr class="m_title_over_co">
		<td colspan="2">體育公告</td>
		
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
		
	</tr>
	<?php
		}
	}else{
	?>
				<tr>
		<td align="center" height="40">
			暫無公告數據
		</td>
	</tr>
	<?php
	}
	?>
		
</tbody></table>
<table border="0" cellspacing="0" cellpadding="0" class="m_tab" width="24%" style="float:left;clear:none;width:320px">

	<tbody><tr class="m_title_over_co">
		<td colspan="2">彩票公告</td>
		
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
	
	</tr>
	<?php
		}
	}else{
	?>
				<tr>
		<td align="center" height="40">
			暫無公告數據
		</td>
	</tr>
	<?php
	}
	?>
		
</tbody></table>
<table border="0" cellspacing="0" cellpadding="0" class="m_tab" width="24%" style="float:left;clear:none;width:320px">

	<tbody><tr class="m_title_over_co">
		<td colspan="2">視訊公告</td>
		
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
	
	</tr>
	<?php
		}
	}else{
	?>
				<tr>
		<td align="center" height="40">
			暫無公告數據
		</td>
	</tr>
	<?php
	}
	?>

		
</tbody></table>
<table border="0" cellspacing="0" cellpadding="0" class="m_tab" width="24%" style="float:left;clear:none;width:320px">

	<tbody><tr class="m_title_over_co">
		<td colspan="2">维护公告</td>
		
	</tr>
		<?php if (!empty($wh)) {
		foreach ($wh as $key => $val) {
     ?>
			<tr>
		<td align="center" width="110px;">
			<?=$val['notice_date']?>
		</td>
		<td align="center">
			<?=$val['notice_content']?>
		</td>
	
	</tr>
	<?php
		}
	}else{
	?>
				<tr>
		<td align="center" height="40">
			暫無公告數據
		</td>
	</tr>
	<?php
	}
	?>

		
</tbody></table>

</div>

<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>
<?php
include("../../include/config.php");
include("../common/login_check.php");

$mapN['site_id'] = SITEID;

//用户层级查询
//echo SITEID;exit;
$k_user_level = M('k_user_level', $db_config);
$user_cj = $k_user_level->field("id,level_des")->where("site_id='".SITEID."'")->select();

//时间检索
if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
	$mapN['add_time'] = array(
		                 array('>=',$_GET['date_start'].' 00:00:00'),
		                 array('<=',$_GET['date_end'].' 23:59:59')
		                );
}

//内容查询
if (!empty($_GET['lang']) && isset($_GET['query'])) {
	$mapN[$_GET['lang']] = array('like','%'.$_GET['query'].'%');
}

//停用启用
if($_GET['is_delete'] == '0'){
	$mapN['is_delete'] = '0';
	$type = 0;
}elseif($_GET['is_delete'] == '2'){
	$mapN['is_delete'] = '2';
	$type = 2;
}else{
	$type = 0;
	$mapN['is_delete'] = '0';
}

$Kmess = M('k_message',$db_config);
$DataM = $Kmess->where($mapN)->order("add_time desc")->select();

//启用停用 删除 操作
switch ($_GET['act']) {
	case 'de':
		$dataA = array();
		$dataA['is_delete'] = 1;
		$state = $Kmess->where("id = '".$_GET['id']."'")
		       ->update($dataA);
		if ($state) {
			$log = "删除公告:".$_GET['id'];
            admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$log);
			message("删除成功");
		}
		break;

	case 's1':
		$dataA = array();
		$dataA['is_delete'] = 0;
		$state = $Kmess->where("id = '".$_GET['id']."'")
		       ->update($dataA);
		if ($state) {
			$log = "启用公告:".$_GET['id'];
            admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$log);
			message("启用成功");
		}
		break;
	case 's2':
		$dataA = array();
		$dataA['is_delete'] = 2;
		$state = $Kmess->where("id = '".$_GET['id']."'")
		       ->update($dataA);
		if ($state) {
			$log = "停用公告:".$_GET['id'];
            admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$log);
			message("停用成功");
		}
		break;

}

 ?>
<?php $title="最新消息";
require("../common_html/header.php");?>
<style>
	.m_bc_ed td{padding-left: 0px;}
</style>
<body>
<div id="con_wrap">
  <div class="input_002">公告管理</div>
  <div class="con_menu">
	<form method="get" action="" id="myFORM" style="float:left">
	日期：
	<input type="text" id="date_start" name="date_start" value="<?=$start_date?>" onClick="WdatePicker()"  class="za_text Wdate">
		--
	<input type="text" id="date_end" name="date_end" value="<?=$end_date?>" onClick="WdatePicker()"  class="za_text Wdate">
	內容查詢:
	<input class="za_text" type="text" size="15" name="query" id="query" value="">
	查詢語種:
	<select name="lang" id="lang">
	    <option value="chn_simplified" <?php select_ed($_GET['lang'],'chn_simplified');?>>简体中文</option>
		<option value="chn_traditiona" <?php select_ed($_GET['lang'],'chn_traditiona');?>>繁體中文</option>
		<option value="english" <?php select_ed($_GET['lang'],'english');?>>english</option>
	</select>
	<input type="submit" class="button_a" value=" 查 詢 ">&nbsp;
	<input type="button" class="button_a" value="發佈公告" onclick="document.location.href='./new_msg_add.php'">
	</form>

  </div>
</div>
<div class="content">
<table class="m_tab" style="width:100%">
	<tbody><tr class="m_title">
		<td colspan="8">
			<span style="float:left;padding-left:50px">
			<a href="new_msg.php?is_delete=0&date_start=<?=$_GET['date_start']?>&date_end=<?=$_GET['date_end'] ?>" <?php if($type == 0){ echo "style='color:red'";}else{echo "style='color:#000'";} ?> >啟用區
			</a>&nbsp;/&nbsp;
			<a href="new_msg.php?is_delete=2&date_start=<?=$_GET['date_start']?>&date_end=<?=$_GET['date_end'] ?>" <?php if($type == 2){ echo "style='color:red'";}else{echo "style='color:#000'";} ?> >停用區</a></span>
			<span align="center">公告管理</span>
		</td>
	</tr>
	<tr class="m_title" >
		<td width="120px" >发布時間</td>
		<td width="30%">顯示内容：<!-- 顯示語系： -->
		<select name="langague" onchange="go(this.value)" id="langague">
		       <option value="chn_simplified" <?php select_ed($_GET['lan'],'chn_simplified');?> >简体中文</option>
			   <option value="chn_traditiona" <?php select_ed($_GET['lan'],'chn_traditiona');?> >繁體中文</option>
			   <option value="english" <?php select_ed($_GET['lan'],'english');?>>English</option>
			</select>
		</td>
		<td width="140px;">發佈者</td>
		<td width="60px;">顯示類型</td>
		<td width="65px;">遊戲類型</td>
		<td width="135px;">層級</td>
		<td width="60px;">顯示位置</td>
		<td width="175px" style="text-align:center;">功能</td>

	</tr>
	<?php
if(is_array($DataM)){
     foreach ($DataM as $k => $rows) {
     	if (!empty($_GET['lan'])) {
     		$content = $rows[$_GET['lan']];
     	}else{
     		$content = $rows['chn_simplified'];
     	}
 	?>
	<tr class="m_bc_ed">
		<td style="text-align:center;"><?=$rows['add_time'] ?></td>
		<td width="30%"><?=htmlspecialchars_decode($content)?></td>
		<td><?=$rows['name'] ?></td>
		<td><?php
		$leixing3=str_replace('1', '彈出', $rows['show_type']);
		$leixing3=str_replace('2', '跑馬燈', $leixing3);
		 echo $leixing3;
		?></td>
		<td><?php
		$leixing=str_replace('1', '體育', $rows['game_type']);
		$leixing=str_replace('2', '彩票', $leixing);
		$leixing=str_replace('3', '視訊',$leixing);
		echo $leixing;
		?></td>
		<td><?php
			$leixing1 = $rows['level_power'];
			$leixing1 = str_replace( '0', '代理', $leixing1 );
			$leixing1 = str_replace( '-1', '指定用户', $leixing1 );
			$leixing1 = str_replace( '-2', '全部用户', $leixing1 );
			foreach ($user_cj as $key=>$value ){
				$leixing1 = str_replace( $value['id'], $value['level_des'], $leixing1 );
			}
		 echo $leixing1;
		?></td>
		<td>
			<?php
			if($rows['show_type'] == 2){
				if($rows['state']==1){
					echo '全站顯示';
				}elseif($rows['state']==2){
					echo '指定頁面顯示';
				}
			}elseif($rows['show_type'] == 1){
				echo '登陸頁面彈出';
			}
			?>
		</td>
		<td>
		<a class="za_button" href="new_msg_add.php?active=u&id=<?=$rows['id'] ?>">修改</a>

		<?php if($rows['is_delete']==0){ ?>
		/ <a class="za_button" href="new_msg.php?act=s2&id=<?=$rows['id'] ?>" onclick="return confirm('确认停用')">停用</a>
		<?php }else{ ?>
		/ <a class="za_button" href="new_msg.php?act=s1&id=<?=$rows['id'] ?>" onclick="return confirm('确认启用')">启用</a>
		<?php } ?>
		/ <a class="za_button" href="new_msg.php?act=de&id=<?=$rows['id'] ?>" onclick="return confirm('确认删除')">删除</a>
		</td>
	</tr>
	<?php } }else{
  ?>
  <?php }?>
<?php
	if(empty($DataM)){
?>
	 <tr class="m_bc_ed"><td colspan="15" style="text-align:center;">暂无消息</td></tr>
<?php } ?>
</tbody></table>
</div>
<script>
var s= <?=$type?>;
function go(n)
{
	window.location="./new_msg.php?is_delete="+s+"&lan="+n;
}
</script>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>
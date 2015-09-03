<?php
include_once("../../include/config.php");
include_once("../common/login_check.php"); 

//获取对应前台域名
$indexUrl = M('web_config',$db_config)->where("site_id = '".SITEID."'")->getField("conf_www");

//分页
$siteAd = M('site_ad',$db_config);
$map['site_id'] = SITEID;
$map['is_delete'] = 0;

$count= $siteAd->where($map)->count();
$totalPage=ceil($count/20);
$page=isset($_GET['page'])?$_GET['page']:1;
if($totalPage<$page){
  $page = 1;
}
$startCount=($page-1)*20;
$limit=$startCount.",20";
$site_ad = $siteAd->where($map)->order("id DESC")->limit($limit)->select();
$page = $siteAd->showPage($totalPage,$page);
//读取站内广告状态
$adConfig = M('site_ad_config',$db_config);
$is_open = $adConfig ->where("site_id = '".SITEID."'")->find();

if ($is_open['is_open'] == '1') {
	$isTitle = '停用';
	$isTitlel = '启用';
}else{
	$isTitle = '启用';
	$isTitlel = '停用';
}


//p($site_id_ad);
if(!empty($_GET['id']) && $_GET['type'] == 'd1'){
	$data_d['is_delete']="1";
	if($siteAd->where("id = '".$_GET['id']."'")->update($data_d)){
		$do_log = '删除广告成功:'.$_GET['id'];
        admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
		message('删除成功');
	}else{
		$do_log = '删除广告失败:'.$_GET['id'];
        admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
		message('删除失败');
	}
}

//开启关闭
if ($_GET['type'] == 'open') {
	if (empty($_GET['id'])) {
		$dataI['site_id'] = SITEID;
		$dataI['is_open'] = 1;
		$isState = $adConfig ->add($dataI);
	}else{
		$dataI['is_open'] = 1-$_GET['is_open'];
		$isState = $adConfig->where("site_id = '".SITEID."'") ->update($dataI);
	}

	if($isState){
		$do_log = '设置站内广告成功:';
        admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
		message('设置成功','site_ad.php');
	}else{
		$do_log = '设置站内广告失败:';
        admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
		message('设置失败','site_ad.php');
	}
}
?>

<?php $title="站内廣告管理"; require("../common_html/header.php");?>
<body>
<script>
//分页跳转
	window.onload=function(){
		document.getElementById("page").onchange=function(){
			var val=this.value;
			window.location.href="?page="+val;
		}

	}
</script>
<style type="text/css">
	.is_open{
		background-color: red;
	}
</style>
<div  id="con_wrap">
<div  class="input_002">站内廣告</div>
<div  class="con_menu">
    <a  href="agent_ad.php" >代理廣告</a>
    <a  href="agent_ad_statistics.php">廣告統計</a>
    <a  href="site_ad.php"  style="color:red">站内廣告</a>
    &nbsp;&nbsp;<?=$page?>
    <input  type="button"  name="append"  value="新增站内廣告" onclick="document.location='./site_ad_add.php?type=add'"  class="za_button">

</div>
</div>
<div  class="content">
	<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0"  bgcolor="#E3D46E"  class="m_tab">
		<tbody><tr  class="m_title_over_co">
			<td>ID</td>
			<td>标题</td>
			<td>内容/图片</td>
			<td>位置</td>
			<td style="width:65px;">类型</td>
			<td style="width:140px;">新增日期</td>
			<td>創建者</td>
			<td style="width:140px;">操作</td>
		</tr>
		<?
		if(!empty($site_ad)){
		   foreach($site_ad as $v){
		   	 if ($v['type'] == '1') {
		   	 	 $Adcotent = $v['content'];
		   	 }elseif ($v['type'] == '2') {
		   	 	 $Adcotent = '<img width=150 height=70 src="'.'http://'.$indexUrl.$v['img'].'">';
		   	 }

		   	switch ($v['type']) {
				case '1':
					$type = '文字类型';
					break;
				
				case '2':
					$type = '图片类型';
					break;
			}
			
			switch ($v['Ad_position']) {
			    case '1':
			        $Ad_position = '中间广告位';
			        break;
			
			    case '2':
			       $Ad_position = '左上广告位';
			        break;
			    case '3':
			       $Ad_position = '左下广告位';
			        break;
			    case '4':
			       $Ad_position = '右下广告位';
			        break;
			}
		?>
		<tr  class="m_cen">
			<td><?=$v['id']?></td>
			<td><?=$v['title']?></td>
			<td><?=$Adcotent?></td>
			<td><?=$Ad_position?></td>
			<td><?=$type?></td>
			<td><?=$v['add_date']?></td>
			<td><?=$v['creator']?></td>
			<td  align="center">
				<a class="button_d" href="./site_ad_add.php?type=e1&id=<?=$v['id']?>">修改</a>
				<a class="button_d" onclick="return confirm('确定删除广告??')" href="./site_ad.php?type=d1&id=<?=$v['id']?>">刪除</a>
			</td>
		</tr>
		<?}	}else{?>
           <tr align="center">
			<td  class="table_bg1" height="27" colspan="15">暂无数据</td>
		</tr>
		<?php }?>				
		
	</tbody></table>
</div>
<div class="content" style="width:500px;">
	<form action="" id="addForm" name="addForm" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#E3D46E" class="m_tab">
		<tbody><tr class="m_title_over_co">
			<td>站内广告开启</td>
		</tr>
		<tr class="m_cen">
			<td>
				<a class="button_d is_open" onclick="return confirm('确定<?=$isTitle?>站内广告??')" href="./site_ad.php?type=open&id=<?=$is_open['id']?>&is_open=<?=$is_open['is_open']?>"><?=$isTitlel?>广告</a><font style="color:red;">点击按钮<?=$isTitle?></font>
			</td>
		</tr>
	</tbody></table>
	</form>
</div>
<?php require("../common_html/footer.php");?>

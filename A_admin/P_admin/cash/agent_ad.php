<?php
include_once("../../include/config.php");
include_once("../common/login_check.php"); 

//分页
$agetAd = M('k_agent_ad_view',$db_config);
$map['site_id'] = SITEID;
$map['is_delete'] = 0;
//检索条件
switch ($_GET['search_type']) {
	case '0':
		$agentId = M('k_user_agent',$db_config)
		         ->where("agent_user = '".$_GET['name']."' and site_id = '".SITEID."'")->getField('id');
		$map['agent_id'] = $agentId;
		break;
	case '1':
	    $str = '%'.$_GET['name'].'%';
		$map['ad_url'] = array('like',$str);
		break;
	case '2':
		$map['intr'] = $_GET['name'];
		break;

}
$count = $agetAd -> where($map)->count();
$perNumber=20; //每页显示的记录数
$totalPage=ceil($count/$perNumber); //计算出总页数
if (!isset($page)) {
    $page=1;
}else{
	$page=$_GET['page']; //获得当前的页面值
}
$startCount=($page-1)*$perNumber; 
$limit=$startCount.",".$perNumber;
$agent_ad = $agetAd->where($map)->limit($limit)->select();


//p($agent_ad);
if(!empty($_GET['id']) && $_GET['type'] == 'd1'){
	$data_d['is_delete']="1";
	if(M('k_agent_ad',$db_config)->where("id = '".$_GET['id']."'")->update($data_d)){
		$do_log = '删除代理广告成功:'.$_GET['id'];
        admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
		message('删除成功','agent_ad.php');
	}else{
		$do_log = '删除代理广告失败:'.$_GET['id'];
        admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
		message('删除失败','agent_ad.php');
	}
}
//print_r($agent_ad);exit;
?>

<?php $title="廣告管理"; require("../common_html/header.php");?>
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
<div  id="con_wrap">
<div  class="input_002">代理廣告</div>
<div  class="con_menu">
  <form name="addForm"  action=""  method="get">
	<a  href="agent_ad.php"  style="color:red">代理廣告</a>
    <a  href="agent_ad_statistics.php">廣告統計</a>
    &nbsp;&nbsp;
           <select  name="search_type" id="utype"  class="za_select">
              <option  value="0" <?=select_check(0,$_GET['search_type'])?>>代理帳號</option>
              <option  value="1" <?=select_check(1,$_GET['search_type'])?>>投放网址</option>
              <option  value="2" <?=select_check(2,$_GET['search_type'])?>>推广ID</option>
            </select>
              <input  type="text"  name="name"  value="<?=$_GET['name']?>"  class="za_text"  style="min-width:80px;width:80px;">
              <input  type="submit" value="搜索"   class="za_button">
               <input  type="button"  name="append"  value="新增廣告" onclick="document.location='./agent_ad_add.php?type=add'"  class="za_button">
     
     &nbsp;頁數：
 <select id="page" name="page" class="za_select"> 
  <?php  

    for($i=1;$i<=$totalPage;$i++){
      if($i==$CurrentPage){
        echo  '<option value="'.$i.'" selected>'.$i.'</option>';
      }else{
        echo  '<option value="'.$i.'">'.$i.'</option>';
      }  
    } 
   ?>
  </select> <?php echo  $totalPage ;?> 頁
   </form>
</div>
</div>
<div  class="content">
	<table  width="1024"  border="0"  cellspacing="0"  cellpadding="0"  bgcolor="#E3D46E"  class="m_tab">
		<tbody><tr  class="m_title_over_co">
			<td>推广ID</td>
			<td>網站</td>
			<td>投放網址</td>
			<td>連接網址</td>
			<td>所属代理</td>
			<td>新增日期</td>
			<td>創建者</td>
			<td>操作</td>
		</tr>
		<?
		if(!empty($agent_ad)){

		foreach($agent_ad as $v){?>
		<tr  class="m_cen">
			<td><?=$v['intr']?></td>
			<td><?=$v['web_site']?></td>
			<td><?=$v['ad_url']?></td>
			<td><a  href="http://<?=$v['connection_url'].'/?intr='?><?=$v['intr']?>"  target="_blank"><?=$v['connection_url'].'/?intr='?><?=$v['intr']?></a></td>
			<td><?=$v['agent_name']?>(<?=$v['agent_user']?>)</td>
			<td><?=$v['addtime']?></td>
			<td><?=$v['creator']?></td>
			<td  align="center">
				<a class="button_d" href="./agent_ad_add.php?type=e1&id=<?=$v['id']?>">修改</a>
				<a class="button_d" onclick="return confirm('确定删除广告代理??')" href="./agent_ad.php?type=d1&id=<?=$v['id']?>">刪除</a>
			</td>
		</tr>
		<?}	}else{?>
           <tr align="center">
			<td  class="table_bg1" height="27" colspan="15">暂无数据</td>
		</tr>
		<?php }?>				
		
	</tbody></table>
</div>
<?php require("../common_html/footer.php");?>

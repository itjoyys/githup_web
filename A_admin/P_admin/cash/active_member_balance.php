<?
include_once("../../include/config.php");
include_once("../common/login_check.php");

include("../../class/user.php");
include_once("../../class/Level.class.php");
$u = M('k_user_agent',$db_config);
$map='k_user_agent.is_demo=0 and k_user_agent.site_id="'.SITEID.'" and k_user_agent.is_delete=0';//表示非测试账号
//检索
if(!empty($_GET['action']) && $_GET['action'] == '1'){
 //  if(!empty($_GET['start_date'])){
	// $start_date = $_GET['start_date'];
 //    $map .= "add_date > '".$start_date." 00:00:00'";
 //  }
  
 //  if(!empty($_GET['end_date'])){
	// $end_date = $_GET['end_date'];
	// $map .= " and add_date < '".$_GET['end_date']."'";
 //  }
 
  if(!empty($_GET['username'])){
     $map .= " and agent_user like '%".$_GET['username']."%'";
  }
}else{
	// $start_date=$end_date=date('Y-m-d');
	// $map.= " add_date > '".$start_date." 00:00:00'";
	// $map.= " and add_date < '".$end_date."'";
}

$map .= " and k_user_agent.agent_type='a_t'";//只查询出代理

$count=count($u->where($map)->select()); //获得记录总数

//分页
$perNumber=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
$totalPage=ceil($count/$perNumber); //计算出总页数
$page=isset($_GET['page'])?$_GET['page']:1;
if($totalPage<$page){
  $page=1;
}
$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
$limit=$startCount.",".$perNumber;
$agent=$u->where($map)->limit($limit)->select();
$agent2 = M('k_user_agent',$db_config)->where("site_id='".SITEID."'")->select();//获取所有的数据，为得到当前代理的父代理及股东
$page = M('k_user_agent',$db_config)->where("site_id='".SITEID."' and is_demo=0 and is_delete=0")->showPage($totalPage,$page);
?>

<?php require("../common_html/header.php");?>
<body>
<script>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myFORM').submit()
    }
  }
</script>

<div id="con_wrap">
  <div class="input_002">代理餘額統計</div>
  <div class="con_menu">
    <form method="get" name="action_form" action="" id="myFORM">
    <input type="hidden" name="action" value="1">
<!--       日期：
     <input class="za_text Wdate" onClick="WdatePicker()" value="<?=$start_date?>" size="10" name="start_date">
      ~
        <input class="za_text Wdate" onClick="WdatePicker()" value="<?=$end_date?>" size="10" name="end_date"> -->

	&nbsp;&nbsp;代理帳號：
    <input class="za_text inpuut1" style="min-width:80px;width:80px" name="username" value="<?=$_GET['username']?>">
      <input class="button_d" value="查詢" type="submit">
         每页记录数：
  <select name="page_num" id="page_num" onchange="document.getElementById('myFORM').submit()" class="za_select">
    <option value="20" <?=select_check(20,$perNumber)?>>20条</option>
    <option value="30" <?=select_check(30,$perNumber)?>>30条</option>
    <option value="50" <?=select_check(50,$perNumber)?>>50条</option>
    <option value="100" <?=select_check(100,$perNumber)?>>100条</option>
  </select>
  &nbsp;頁數：
  <?=$page?>

    </form>
    
  </div>
</div>
<table class="m_tab" style="overflow-x: scroll;">
  <tbody>
  <!-- <tr class="m_title">
    <td class="table_bg">新增日期</td>
    <td class="table_bg">股東</td>
    <td class="table_bg">總代理</td>
    <td class="table_bg">代理</td>
    <td class="table_bg">帳號</td>
    <td class="table_bg">真實姓名</td>
    <td class="table_bg">余額</td>
    <td class="table_bg">存款次數</td>
    <td class="table_bg">提款次數</td>
    <td class="table_bg">存款總數</td>
    <td class="table_bg">提款總數</td>
    <td class="table_bg">國家</td>
    <td class="table_bg">電話號碼</td>
    <td class="table_bg">電子郵箱</td>
    <td class="table_bg">QQ</td>
    <td class="table_bg">狀態</td>
  </tr>
 -->
  <tr class="m_title">
    <td class="table_bg" rowspan="">新增日期</td>
    <td class="table_bg" rowspan="">股東</td>
    <td class="table_bg" rowspan="">總代理</td>
    <td class="table_bg" rowspan="">代理</td>
    <td class="table_bg" rowspan="">代理名稱</td>
    <td class="table_bg" rowspan="">幣別</td>
    <td class="table_bg" rowspan="">总余額</td>
    <td class="table_bg" rowspan="">系统余額</td>
    <td class="table_bg" rowspan="">MG余額</td>
    <td class="table_bg" rowspan="">BBIN余額</td>
    <td class="table_bg" rowspan="">AG余額</td>
    <td class="table_bg" rowspan="">OG余額</td>
    <td class="table_bg" rowspan="">CT余額</td>
    <td class="table_bg" rowspan="">LEBO余額</td>
    <td class="table_bg" rowspan="">狀態</td>
  </tr>
  
  <?php
	if(!empty($agent)){
  foreach($agent as $v){
    $daili = Level::getParents($agent2,$v['id']);

  ?>
	
    <tr class="m_cen">
    <td><?=$v['add_date']?></td>
    <td><?=$daili['s_h']?></td>
    <td><?=$daili['u_a']?></td>
    <td><?=$v['agent_user']?></td>
    <td><?=$v['agent_name']?></td>
    <td>人民币</td>
	<?php 
		$user = M('k_user',$db_config)->field("sum(money) as money,sum(og_money) as og,sum(mg_money) as mg,sum(ag_money) as ag,sum(bbin_money) as bbin,sum(ct_money) as ct,sum(lebo_money) as lebo")->where("is_delete='0' and shiwan=0 and site_id='".SITEID."' and agent_id=".$v['id'])->find();
		$sum=$user['money']+$user['ag']+$user['og']+$user['mg']+$user['bbin']+$user['ct']+$user['lebo'];
		
		$sum=($sum!='0')?$sum:'0.00';
		$row=!empty($user['money'])?$user['money']:'0.00';
		$ag=!empty($user['ag'])?$user['ag']:'0.00';
		$og=!empty($user['og'])?$user['og']:'0.00';
		$mg=!empty($user['mg'])?$user['mg']:'0.00';
    $ct=!empty($user['ct'])?$user['ct']:'0.00';
    $lebo=!empty($user['lebo'])?$user['lebo']:'0.00';
    $bbin=!empty($user['bbin'])?$user['bbin']:'0.00';
		
		echo "<td>".$sum."</td>";//总余额
		echo "<td>";
		echo $row;// 系统余额
		echo "</td>";
	?>
  
   <td><?=$mg?></td>
   <td><?=$bbin?></td>
   <td><?=$ag?></td>
   <td><?=$og?></td>
   <td><?=$ct?></td>
   <td><?=$lebo?></td>

 
    <td><?if($v['is_delete']=='0'){ echo '正常';}else{ echo '停用';}?></td>
  </tr>
   <?}}?>    
</tbody></table>



 </body></html>
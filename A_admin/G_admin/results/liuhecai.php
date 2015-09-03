<?php 
include_once("../../include/config.php");
include("../common/login_check.php");
include_once("../../include/public_config.php");
include("./common/function.php");

$type="ka_kithe";

if($_GET['qishu']){
	$map['nn']=$_GET['qishu'];
}
if(empty($_GET['qishu'])){
	if($_GET['date']){
		$map['nd']= array('like','%'.$_GET['date'].'%');
	}
}

$b=M("ka_kithe",$db_config);
$count=$b->field("id")->where($map)->count();//获得记录总数

//分页
$perNumber=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
$totalPage=ceil($count/$perNumber); //计算出总页数
$page=isset($_GET['page'])?$_GET['page']:1;

$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
$limit=$startCount.",".$perNumber;

$dat=M('ka_kithe',$db_config)->field("id")->where($map)->order("nn desc")->select();


// //分页
//     $perNumber=10; //每页显示的记录数
//     $count=count($dat); //获得记录总数
//     $totalPage=ceil($count/$perNumber); //计算出总页数

//     if ($_GET['page']) {
//         $page=$_GET['page'];
//     }else{
//        $page=1; //获得当前的页面值
//     } //如果没有值,则赋值1

//     $startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
//     $limit=$startCount.",".$perNumber;

	
    $data=M('ka_kithe',$db_config)->field("*")->where($map)->order("nn desc")->limit($limit)->select();
//p($data);
 ?>
<?php require("../common_html/header.php");?>

<script language="javascript">
         //下拉选项自动跳转
$(document).ready(function(){
   $("#cp_cate").change(function(){
       var cp_url=$("#cp_cate").val();
       window.location.href=cp_url; 
   });
})
</script>
  <div  id="con_wrap">
   <div  class="input_002">六合彩开奖管理</div>
   <div  class="con_menu">
          <!--  彩票开奖结果公共检索 -->
    <?php require("lottery_com_search.php");?>
  </div>
  </div>
<div class="content">
  <table width="99%" height="83" style="margin-left:0px" border="0" cellpadding="1" cellspacing="0" class="m_tab">
   <tbody><tr class="m_title_over_co">
      <td width="34" height="28" rowspan="2">序號</td>
      <td rowspan="2" align="center">期數</td>
      <td rowspan="2" align="center">開獎時間</td>
      <td colspan="7" align="center">開獎球號</td>
      <td colspan="3" rowspan="2" align="center">特碼</td>
      <td colspan="3" rowspan="2" align="center">總分</td>
      <td rowspan="2" align="center">生肖</td>
      <td colspan="2" rowspan="2" align="center">合數</td>
    </tr>
    <tr class="m_title_over_co">
      <td width="32" align="center" class="table_bg1">平1</td>
      <td width="32" align="center" class="table_bg1">平2</td>
      <td width="32" align="center" class="table_bg1">平3</td>
      <td width="32" align="center" class="table_bg1">平4</td>
      <td width="32" align="center" class="table_bg1">平5</td>
      <td width="32" align="center" class="table_bg1">平6</td>
      <td width="32" align="center" class="table_bg1">特碼</td>
    </tr>

      <?php 
      if(!empty($data)){
            foreach ($data as $k => $v) {
                  
      
       ?>
     <tr>
      <td height="25" align="center" bgcolor="#FFFFFF"><?=$k ?></td>
      <td height="25" align="center" bgcolor="#FFFFFF"><?=$v['nn'] ?></td>
      <td height="25" align="center" bgcolor="#FFFFFF"><?=$v['nd'] ?></td>
      <td height="25" align="center" bgcolor="#FFFFFF"><div class="ball_r"><?=$v['n1'] ?></div></td>
      <td height="25" align="center" bgcolor="#FFFFFF"><div class="ball_b"><?=$v['n2'] ?></div></td>
      <td height="25" align="center" bgcolor="#FFFFFF"><div class="ball_b"><?=$v['n3'] ?></div></td>
      <td height="25" align="center" bgcolor="#FFFFFF"><div class="ball_r"><?=$v['n4'] ?></div></td>
      <td height="25" align="center" bgcolor="#FFFFFF"><div class="ball_g"><?=$v['n5'] ?></div></td>
      <td height="25" align="center" bgcolor="#FFFFFF"><div class="ball_g"><?=$v['n6'] ?></div></td>
      <td height="25" align="center" bgcolor="#FFFFFF"><div class="ball_g"><?=$v['na'] ?></div></td>
      <td align="center" bgcolor="#FFFFFF"><font color="red"><?php danshuang($v['na']); ?></font></td>
      <td align="center" bgcolor="#FFFFFF"><font color="red"><?php tm_daxiao($v['na']); ?></font></td>
      <td align="center" bgcolor="#FFFFFF"><font color="green"><?php tm_sebo($v['na']); ?></font></td>
      <td align="center" bgcolor="#FFFFFF"><?php $num=$v['n1']+$v['n2']+$v['n3']+$v['n4']+$v['n5']+$v['n6']; echo "<font color='#f00;';>".$num."</font>"; ?></td>
      <td align="center" bgcolor="#FFFFFF"><font color="red"><?php danshuang($num); ?></font></td>
      <td align="center" bgcolor="#FFFFFF"><font color="blue"><?php zongfen_daxiao($num); ?></font></td>
      <td align="center" bgcolor="#FFFFFF"><?php shenxiao(substr($v['nn'],0,4),$v['na']) ?></td>
      <td align="center" bgcolor="#FFFFFF"><font color="red"><?php heshu_daxiao($v['na']) ?></font></td>
      <td align="center" bgcolor="#FFFFFF"><font color="red"><?php heshu_danshuang($v['na']) ?></font></td>
    </tr>
      <?php      
	}
echo "<tr style='background-color:#FFFFFF;'>
        <td colspan='19' align='center' valign='middle'>$pageStr</td>
    </tr>";	
}else{
?>
	<tr style="background-color:#FFFFFF;">
        <td colspan="19" align="center" valign="middle">暂无数据</td>
    </tr>
<?}?>
  </tbody></table>

</div>
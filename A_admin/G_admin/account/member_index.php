<?php 
include_once("../../include/config.php");
include_once("../common/login_check.php"); 

//会员总数
$u = M('k_user',$db_config);
$map = 'site_id ='."'".SITEID."'".'and shiwan = 0 and reg_date like "'.date('Y-m-d').'%" and agent_id="'.$_SESSION['agent_id'].'"';
$todayReg_count = $u->field('uid')->where($map)->count();
$where = "site_id = '".SITEID."' and shiwan = 0 and agent_id = '".$_SESSION['agent_id']."' ";
//检索
if (!empty($_GET['search_name'])) {
    switch ($_GET['search_type']) {
      case '0':
        $where .= "and username = '".$_GET['search_name']."' ";
        break;
      case '1':
        $where .= "and pay_name = '".$_GET['search_name']."' ";
        break;
      case '2':
        $where .= "and mobile = '".$_GET['search_name']."' ";
        break;
    }
}
//时间
if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
    $tmpS = $_GET['start_date'].' 00:00:00';
    $tmpE = $_GET['end_date'].' 23:59:59';
    $where .= " and reg_date >='".$tmpS."' and reg_date <='".$tmpE."' ";
}

$count= $u->where($where)->count();
//分页
$perNumber=50; //每页显示的记录数
$totalPage=ceil($count/$perNumber); //计算出总页数
$page=isset($_GET['page'])?$_GET['page']:1;
if($totalPage<$page){
  $page = 1;
}

$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
$limit=$startCount.",".$perNumber;

$user=$u->where($where)->order("uid DESC")->limit($limit)-> select();

$page = $u->showPage($totalPage,$page);


?>
<?php require("../common_html/header.php");?>
<body>
<script>
         //下拉选项
$(document).ready(function(){
	
   $("#mem_type_select").val('<?=$_GET['mem_enable']?>');
  // $("#mem_type_select").val('<?=$_GET['mem_type']?>');
   $("#status_select").val('<?=$_GET['mem_status']?>');
   $("#search_mem").val('<?=$_GET['search_mem']?>');
   $("#utype").val('<?=$_GET['search_type']?>');
   $("#start_date").val('<?=$_GET['start_date']?>');
   $("#end_date").val('<?=$_GET['end_date']?>');
   });

</script>
<div  id="con_wrap">
<div  class="input_002">會員帳號管理(今日注册:<?=$todayReg_count?> 总会员数:<?=$count?>)</div>
<div  class="con_menu"  style="margin-bottom:5px;width:870px;overflow:hidden;">
<form  name="myFORM"  id="myFORM"  action=""  method="GET">
        日期：
              <input class="za_text Wdate" onClick="WdatePicker()" value="<?=$_GET['start_date']?>" size="10" name="start_date"> - <input  type="text"  name="end_date" id="end_date" readonly  value="<?=$_GET['end_date']?>" class="za_text Wdate" onClick="WdatePicker()">
              <select  name="search_type" id="utype"  class="za_select">
              <option  value="0">帳號</option>
              <option  value="1">姓名</option>
              <option  value="2">手机</option>
            </select>
             <input  type="text"  name="search_name"  value="<?=$_GET['search_name']?>"  class="za_text"  style="min-width:80px;width:80px;">
              <input  type="submit" value="搜索"   class="za_button">
  &nbsp;<?=$page ?>&nbsp;
          
             
         <!--      <input  type="button"  name="append"  value="新增"   class="za_button" onclick="document.location='member_add.php'">  -->
   

</form>
<script>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myFORM').submit()
    }
  }
</script>
</div>
</div>
<div  class="content">
  <table  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab">
    <tbody><tr  class="m_title_over_co">
      <td>真實姓名</td>
      <td>帳號</td>
      <td>系統額度</td>     
      <td>LEBO額度</td>
      <td>BBIN額度</td> 
      <td>AG額度</td>
      <td>OG額度</td>
      <td>MG額度</td>
      <td>CT額度</td>
      <td>盤口</td>
      <td>新增日期</td>
      <td>狀況</td>
      <td>功能</td>
    </tr>
    <?php
    	foreach ($user as $key => $rows) {
     ?>
    <tr  class="m_cen">
      <td><?=$rows["pay_name"]?></td>
      <td><?=$rows["username"]?></td>
       <td  style="text-align:left"  nowrap=""><?=$rows["money"]?> (RMB)</td> 
      <td  style="text-align:left"  nowrap=""><?=$rows["lebo_money"]?>(RMB)</td>
      <td  style="text-align:left"  nowrap=""><?=$rows["bbin_money"]?>(RMB)</td>
      <td  style="text-align:left"  nowrap=""><?=$rows["ag_money"]?>(RMB)</td>
      <td  style="text-align:left"  nowrap=""><?=$rows["og_money"]?>(RMB)</td>
      <td  style="text-align:left"  nowrap=""><?=$rows["mg_money"]?>(RMB)</td>
      <td  style="text-align:left"  nowrap=""><?=$rows["ct_money"]?>(RMB)</td>
      <td>D</td>
      <td><?=$rows["reg_date"]?></td>
      <td><?php if ($rows['is_delete'] == 1) {
           echo "<span style=\"color:#FF00FF;\">停用</span>";
         }elseif ($rows['is_delete'] == 2) {
           echo "<span style=\"color:#FF00FF;\">暫停</span>";
         }else{
           echo "<span style=\"color:##1E20CA;\">正常</span>";
         } ?></td>
      <td  align="center">
      <!--   <a  href="../betRecord/bet_record.php?uid=<?=$rows["uid"]?>">下注</a>/ -->
        <a  href="../cash/cash_system.php?uid=<?=$rows["uid"]?>&username=<?=$rows["username"]?>">現金</a>
      </td>       
    </tr>
    <?} ?>
  </tbody></table>
</div>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>
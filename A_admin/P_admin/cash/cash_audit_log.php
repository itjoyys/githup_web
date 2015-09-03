<?php
include_once("../../include/config.php");
include_once("../common/login_check.php"); 


$obj = M('k_user_audit_log',$db_config);
$map['site_id'] = SITEID;
$map['type'] = 0;
if (!empty($_GET['username'])) {
	  $str_user = '%'.$_GET['username'].'%';
    $map['username'] = array('like',$str_user);
}
//时间检索
if(!empty($_GET['date_start']) && !empty($_GET['date_end'])){
  $start_date = $_GET['date_start'];
  $end_date = $_GET['date_end'];
}else{
  $start_date = $end_date = date('Y-m-d');
}
$map['update_date'] = array(
                          array('>=',$start_date.' 00:00:00'),
                          array('<=',$end_date.' 23:59:59')
                      );

//总数
$count = $obj->where($map)->count();
$perNumber=50;
$totalPage=ceil($count/$perNumber); //计算出总页数
$page=isset($_GET['page'])?$_GET['page']:1;
if($totalPage<$page){
  $page = 1;
}

$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
$limit=$startCount.",".$perNumber;
$audit_data=$obj->where($map)->order("id DESC")->limit($limit)-> select();

$page = $obj->showPage($totalPage,$page);

?>

<?php $title='稽核日志查询'; require("../common_html/header.php");?>
<body>
<script>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myform').submit()
    }
  }
</script>
<div id="con_wrap">
  <div class="input_002">稽核日志查询</div>
  <div class="con_menu">
     <form method="get" name="action_form" id="myform">
      日期：
      <input value="<?=$start_date?>" size="10" name="date_start" onclick="WdatePicker()" class="za_text Wdate" style="min-width:90px!important;width:90px!important">
      ~
      <input value="<?=$end_date?>" size="10" name="date_end" onclick="WdatePicker()" class="za_text Wdate" style="min-width:90px!important;width:90px!important">
      帳號：
      <input class="za_text" size="10" name="username" value="<?=$_GET['username']?>">
      &nbsp;
      <input class="button_d" value="查詢" type="submit">
      &nbsp;<?=$page?>
    </form>
  </div>
</div>
<table width="99%" class="m_tab">
  <tbody><tr class="m_title">
    <td class="table_bg" style="width:80px">会员</td>
    <td class="table_bg">内容</td>
    <td class="table_bg" style="width:120px">更改日期</td>
  </tr>
  <?php if(!empty($audit_data)){
    foreach($audit_data as $row){?>
    <tr class="m_cen">
    <td><?=$row['username']?></td>
    <td style="text-align:left;line-height:20px">
          	<?=$row['content']?>
    </td>
    <td><?=$row['update_date']?></td>
  </tr>
    <?}} ?>
  </tbody></table>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>
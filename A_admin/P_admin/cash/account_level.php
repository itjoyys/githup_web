<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");

$u = M('k_user_level',$db_config);
//获取默认未分层
$levelA = $u->where("site_id = '".SITEID."' and is_default = 1")
          ->find();
//站点所有层级
//分页
$count=$u->field("id")->where("site_id = '".SITEID."'")->count();
$perNumber=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
$totalPage=ceil($count/$perNumber); //计算出总页数
$page=isset($_GET['page'])?$_GET['page']:1;
$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
if($totalPage<$page){
      $page=1;
}
$limit=$startCount.",".$perNumber;
$level_data = $u->where("is_delete = 0 and site_id = '".SITEID."'")->limit($limit)->select();
$page = $u->showPage($totalPage,$page);

	
//读取支付平台设定
$pay_set = M('k_cash_config',$db_config)
          ->where("site_id = '".SITEID."' and is_delete = 0 and type = 0")
          ->select();

  //回归操作,全部移动到未分层
  if($_GET['action'] == 'huigui' && !empty($_GET['level_id'])){
    $data2['level_id']= $levelA['id'];//默认未分层id
    $mstate= M("k_user",$db_config)->where("is_delete = 0 and site_id = '".SITEID."' and level_id = '".$_GET['level_id']."' and is_locked = 0")->update($data2);
    $leveldata = M('k_user_level',$db_config)->field("level_name")
                ->where("id = '".$_GET['level_id']."'")->find();
     $do_str = '层级'.$leveldata['level_name'];
     $do_log = $_SESSION['login_name'].'对'.$do_str.'进行了回归操作';
     admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
     if ($mstate) {
        message('回归成功','./account_level.php');
     }
  }

//支付平台设置
if ($_POST['add_pay'] == 'pay_set' && !empty($_POST['level_id'])) {
   $data['RMB_pay_set'] = $_POST['RMB'];
   if (M('k_user_level',$db_config)->where("id = '".$_POST['level_id']."'")->update($data)) {
       $do_str = '层级'.$_POST['levelname'];
      $do_log = $_SESSION['login_name'].'对'.$do_str.'进行了支付平台设置操作';
     admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
       message('设定支付平台成功');
   }
}
?>

<?php $title="层级管理"; require("../common_html/header.php");?>
<body>
<script language="JavaScript" src="../public/js/easydialog.min.js"></script>
<link rel="stylesheet" href="../public/css/easydialog.css" type="text/css">

<script>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('query_form').submit()
    }
  }
</script>
<script>
$(function(){
  $("#edit_level_user").click(function() {
     if(confirm('確認要修改嗎?')){
      $('#edit_level_user').submit();
    }
  });
  

  //回归操作
  $(".huigui").click(function() {
    if(confirm('確認要将该层用户全部移动到‘未分层’嗎?')){
        var level_id = $(this).attr('levelid');
      window.location.href="./account_level.php?action=huigui&level_id="+level_id;
    }
  });
})
function delevel(id){
  $.get("account_level_get.php?ltype=lvset&id="+id, function(json){
    $('#context').html(json);
      easyDialog.open({
          container : 'delevel'
        });
    });
}
function show_config(level,RMB,HKD,USD,MYR,SGD,THB,GBP,JPY,EUR,IDR){
    //var content = $('#add_form').html();
    $("#level_id").val(level);
    $("#RMB").val(RMB);
    $("#HKD").val(HKD);
    $("#USD").val(USD);
    $("#MYR").val(MYR);
    $("#SGD").val(SGD);
    $("#THB").val(THB);
    $("#GBP").val(GBP);
    $("#JPY").val(JPY);
    $("#EUR").val(EUR);
    $("#IDR").val(IDR);
    easyDialog.open({
        container : 'currency_box'
      });
}


$('document').ready(function(){
  //会员币别统计
    $(".count_c").click(function(){
        var level_id = $(this).attr('cid');
        $.ajax({ 
            type: "get", 
            url : "account_level.php", 
            dataType:'json',
            data: 'action=g_l&level_id='+level_id, 
            beforeSend:function(){
              alert('232423');
            },
            success: function(data){
               // $.each(data, function(i,rs){
               //   $("#count_"+i).html(rs);
               // });
                alert('232323');
               //$("#countBox").css('display','block');
            }
        });
    })

    $(".close_rmb").click(function(){
        $("#countBox").css('display','none');
    });


    $(".pay_set").click(function(){
        $("#payBox").css('display','block');
        var level_id = $(this).attr('levelid');
        var payment_set = $(this).attr('set-panyment');
        var rmb_set = $(this).attr('l-p-set');
        var level_name = $(this).attr('level-name');
        $("#level_id").val(level_id);
        $("#level_name").val(level_name);
        $("#RMB").val(rmb_set);
    })
    $(".pay_set_1").click(function(){
       
        var level_id = $(this).attr('levelid');
        // var payment_set = $(this).attr('set-panyment');
        // var rmb_set = $(this).attr('l-p-set');
        $("#level_id_1").val(level_id);
        // $("#RMB_1").text(rmb_set);
		$.ajax({
		type: "GET",
		url: "account_level.php",
		data: "id="+level_id+"&type=ajax",
		dataType:'json',
		success: function(msg){
			//alert( "Data Saved: " + msg );
			var nr='';
			for(var i=0;i<msg.length;i++){
				nr+='<tr>';
				nr+='<td height="0" style="text-align:center;"><input type="checkbox" name="level_ids[]" value="'+msg[i].id+'"></td>';
				nr+=' <td id="RMB_1" style="text-align:center;">'+msg[i].level_name+'</td>';
				nr+=' <td style="text-align:center;">';
				nr+=msg[i].level_des;
				nr+='</td>';
				nr+='</tr>';
			}
			
			$('#level').html(nr);
			$("#payBox_1").css('display','block');
			//alert(nr);
		}
		});
    })
    $(".close_x").click(function(){
      $("#payBox").css('display','none');
      $("#payBox_1").css('display','none');
    })


  //统计层级会员币别人数


})
</script>
<div id="con_wrap">
  <div  class="input_002">层级管理</div>
  <div  class="con_menu">
  <form  name="query_form" action="" method="get" id="query_form">
  <input  type="button"  value="层级会员"   class="button_b" onclick="document.location='../cash/level_member.php'">
    <input  type="button"  name="append"  value="新增" onclick="document.location='./account_level_add.php'"  class="za_button">
    每页记录数：
  <select name="page_num" id="page_num" onchange="document.getElementById('query_form').submit()" class="za_select">
    <option value="20" <?=select_check(20,$perNumber)?>>20条</option>
    <option value="30" <?=select_check(30,$perNumber)?>>30条</option>
    <option value="50" <?=select_check(50,$perNumber)?>>50条</option>
    <option value="100" <?=select_check(100,$perNumber)?>>100条</option>
  </select>
  &nbsp;<?=$page?>
    </form>       
  </div>
</div>
<div class="content" style="overflow">
<table  class="m_tab">
    <tbody>
    <tr  class="m_title">
      <td  rowspan="2"  class="table_bg">名稱</td>
      <td  rowspan="2"  class="table_bg" style="width:120px;">描述</td>
      <td  colspan="3"  class="table_bg">加入條件</td>
      <td  colspan="6"  class="table_bg">条件限制</td>
      <td  rowspan="2"  class="table_bg">当日提款限额</td>
      <td  rowspan="2"  class="table_bg">會員人數</td>
      <td  rowspan="2"  class="table_bg" style="width:80px;">備註</td>
      <td  rowspan="2"  class="table_bg" style="width:180px;">操作</td>
      <td  rowspan="2"  class="table_bg">设定</td>
  </tr>
    <tr  class="m_title">
      <td  class="table_bg" style="width:140px;">會員加入時間</td>
      <td  class="table_bg">存款次数</td>
      <td  class="table_bg">存款总额</td>
      <td  class="table_bg">提款次數</td>
      <td  class="table_bg">提款总额</td>
      <td  class="table_bg">最大存款</td>
      <td  class="table_bg">最大提款</td>
      <td  class="table_bg">最低提款</td>
      <td  class="table_bg">最高存款額</td>
    </tr>
    <?php
      if (!empty($level_data)) {
       foreach ($level_data as $key => $val) {
        $level_count = M('k_user',$db_config)->where("level_id = '".$val['id']."' and shiwan = 0 and is_delete = '0' and site_id = '".SITEID."'")->count();
    ?>
         <tr>
                   <td><?=$val['level_name']?></td>
                   <td><?=$val['level_des']?></td>
                   <td style="text-align: center;"><?=$val['start_date']?><br><?=$val['end_date']?></td>
                   <td><?=$val['deposit_num']?></td>
                   <td><?=$val['deposit_count']?></td>
                   <td><?=$val['draw_num']?></td>
                   <td><?=$val['draw_count']?></td>
                   <td><?=$val['max_deposit_count']?></td>
                   <td><?=$val['max_draw_count']?></td>
                   <td><?=$val['min_out']?></td>
                   <td><?=$val['highest_deposit_count']?></td>
                   
                   <td><?=$val['day_draw_count']?></td>
                   <td><a class="a001" href="../cash/level_member.php?type=<?=$val['id']?>"><?=$level_count?></a></td>
                   <td><?=$val['remark']?></td>
                   <?php 
                    if($val['is_default'] != 1){
                    ?>
                   <td style="width:180px;">
                   <input onclick="document.location='account_level_add.php?action=edit&id=<?=$val['id']?>'" type="button" value="修改" class="button_d">
                    &nbsp;
                    <input type="button" onclick="delevel(<?=$val['id']?>)" type="button" value="分層" class="button_d">

                    <input type="button" class="button_b huigui" value="迴歸"  levelid="<?=$val['id']?>">
                   </td>
                   <?php  }else{
                  ?>
    
                    <td ></td><?php } ?>
                   <td>
      <input type="button" class="button_b pay_set" value="支付平台" levelid="<?=$val['id']?>" l-p-set="<?=$val['RMB_pay_set']?>" set-payment="<?=$val['pay_set']?>" level-name="<?=$val['level_name']?>">
    <!--   <input type="button" class="button_b count_c" value="會員幣別" cid="<?=$val['id']?>"> -->
      </td></tr>
<?php
       }     }
    ?>
    </tbody></table>
</div>
  <!-- 移动弹窗 -->
<div id="delevel" style="display:none;background-color:white;" class="con_menu">
    <div id="context"></div>
</div>

<!-- 设定弹窗 -->
<div id="payBox" style="margin: -216px 0px 0px -150px; padding: 0px; border: none; z-index: 10000; position: fixed; top: 50%; left: 50%; display: none;"><div id="currency_box" style="display: block; margin: 0px;" class="con_menu">
<form action="" method="post" name="add_form">
  <input name="add_pay" value="pay_set" type="hidden">
  <input name="level_id" id="level_id" value="" type="hidden">
  <input name="levelname" id="level_name" value="" type="hidden">
  <table class="m_tab" style="width:300px;margin:0;">
    <tbody><tr class="m_title">
      <td colspan="2" height="27" class="table_bg" align="left">
      <span id="title">支付平台设定</span>
      <span style="float:right;"><a style="color:000;" href="javascript:void(0)" title="关闭窗口" class="close_x">X</a></span>
      </td>
    </tr>
    <tr class="m_title">
      <td>币别</td>
      <td>设定</td>
    </tr>
        <tr>    
      <td>人民幣(RMB)</td>
      <td>
      <select name="RMB" id="RMB">
       <option value="">人民幣预设</option> 
      <?php if (!empty($pay_set)) {
        foreach ($pay_set as $key => $val) {
       ?>
         <option value="<?=$val['id']?>"><?=$val['type_name']?></option>    
       <?php }}?> 
       </select></td>
    </tr>

         <tr>    
      <td>港幣(HKD)</td>
      <td>
      <select name="HKD" id="HKD">
       <option value="">港幣预设</option>    
      <?php if (!empty($pay_set)) {
        foreach ($pay_set as $key => $val) {
       ?>
       <option value="<?=$val['id']?>"><?=$val['type_name']?></option>    
       <?php }}?> 
       </select></td>
    </tr>
         <tr>    
      <td>美金(USD)</td>
      <td>
      <select name="USD" id="USD">
      <option value="">美金预设</option> 
      <?php if (!empty($pay_set)) {
        foreach ($pay_set as $key => $val) {
       ?>
       <option value="<?=$val['id']?>"><?=$val['type_name']?></option>    
       <?php }}?> 
       </select></td>
    </tr>
             <tr>    
      <td>馬幣(MYR)</td>
      <td>
      <select name="MYR" id="MYR">
      <option value="">馬幣预设</option> 
      <?php if (!empty($pay_set)) {
        foreach ($pay_set as $key => $val) {
       ?>
       <option value="<?=$val['id']?>"><?=$val['type_name']?></option>    
       <?php }}?> 
       </select></td>
    </tr>
         <tr>    
      <td>新幣(SGD)</td>
      <td>
      <select name="SGD" id="SGD">
       <option value="">新幣预设</option> 
      <?php if (!empty($pay_set)) {
        foreach ($pay_set as $key => $val) {
       ?>
       <option value="<?=$val['id']?>"><?=$val['type_name']?></option>    
       <?php }}?> 
       </select></td>
    </tr>
         <tr>    
      <td>泰銖(THB)</td>
      <td>
      <select name="THB" id="THB">
      <option value="">泰銖预设</option> 
      <?php if (!empty($pay_set)) {
        foreach ($pay_set as $key => $val) {
       ?>
       <option value="<?=$val['id']?>"><?=$val['type_name']?></option>    
       <?php }}?> 
       </select></td>
    </tr>
         <tr>    
      <td>英磅(GBP)</td>
      <td>
      <select name="GBP" id="GBP">
       <option value="">英磅预设</option> 
      <?php if (!empty($pay_set)) {
        foreach ($pay_set as $key => $val) {
       ?>
       <option value="<?=$val['id']?>"><?=$val['type_name']?></option>    
       <?php }}?> 
       </select></td>
    </tr>
         <tr>    
      <td>日幣(JPY)</td>
      <td>
      <select name="JPY" id="JPY">
        <option value="">日幣预设</option> 
      <?php if (!empty($pay_set)) {
        foreach ($pay_set as $key => $val) {
       ?>
       <option value="<?=$val['id']?>"><?=$val['type_name']?></option>    
       <?php }}?> 
       </select></td>
    </tr>
             <tr>    
      <td>歐元(EUR)</td>
      <td>
      <select name="EUR" id="EUR">
      <option value="">歐元预设</option> 
      <?php if (!empty($pay_set)) {
        foreach ($pay_set as $key => $val) {
       ?>
       <option value="<?=$val['id']?>"><?=$val['type_name']?></option>    
       <?php }}?> 
       </select></td>
    </tr>

             <tr>    
      <td>印尼盾(IDR)</td>
      <td>
      <select name="IDR" id="IDR">
       <option value="">印尼盾预设</option> 
      <?php if (!empty($pay_set)) {
        foreach ($pay_set as $key => $val) {
       ?>
       <option value="<?=$val['id']?>"><?=$val['type_name']?></option>    
       <?php }}?> 
       </select></td>
    </tr>
        <tr>
      <td colspan="2" align="center">
        <input type="submit" value="提交" class="button_a">
        <input type="reset" value="关闭" class="button_a close_x">
      </td>
    </tr>
  </tbody></table>
</form>
</div>
</div>  
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>


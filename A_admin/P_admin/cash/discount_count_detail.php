<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once("../../class/Level.class.php");

//详细信息
if($_GET['id']){
	$sql = "kds_id=".$_GET['id']." and site_id='".SITEID."'";

  //标题
  $title = M('k_user_discount_search',$db_config)->where("id = '".$_GET['id']."'")->find();
	$datas = M('k_user_discount_count',$db_config)
         ->field("id,uid,username,site_id,date,level_des,agent_user,agent_id,username,kds_id,betall,sp_bet,fc_bet,ag_bet,og_bet,mg_bet,mgdz_bet,ct_bet,bbin_bet,bbdz_bet,lebo_bet,sp_fd,fc_fd,ag_fd,og_fd,mg_fd,mgdz_fd,ct_fd,bbin_fd,bbdz_fd,lebo_fd,total_e_fd,state")
         ->where($sql)->select();
}

?>

<?php require("../common_html/header.php");?>
<body>
<style type="text/css">
  .color_cx{
    color:red;
  }
</style>
<script>

function ckall(){
    for (var i=0;i<document.myFORM.elements.length;i++){
      var e = document.myFORM.elements[i];
    if (e.name != 'checkall' ) 
      e.checked = document.myFORM.checkall.checked;
  }
}
$(document).ready(function(){
  $("#ckzero").click( function () { 
      $(".m_cen").each(function () {
          if (parseFloat($(this).find('td:last').text())<=0) {
            if($('#ckzero').attr("checked")==undefined){
              $(this).find(':checkbox').attr("checked",false);
            }else{
              $(this).find(':checkbox').attr("checked",true);
            } 
            //$(this).css("color","red");
          }
      })
  });
  
  $("#cknozero").click( function () { 
      $(".m_cen").each(function () {
          if (parseFloat($(this).find('td:last').text())>0) {
            if($('#cknozero').attr("checked")==undefined){
              $(this).find(':checkbox').attr("checked",false);
            }else{
              $(this).find(':checkbox').attr("checked",true);
            } 
            //$(this).css("color","red");
          }
      })
  });
});
function check(){
    var len = document.myFORM.elements.length;
  var num = false;
    for(var i=0;i<len;i++){
    var e = document.myFORM.elements[i];
        if(e.checked && e.name=='id[]'){
      num = true;
      break;
    }
    }
  if(num){
  }else{
        alert("您未选中任何复选框");
        return false;
    }
}

</script>
<div id="con_wrap">
  <div class="input_002">優惠統計</div>
  <div class="con_menu">
  <a href="discount_index.php" >優惠統計</a>
  <a href="discount_search.php" style="color:red;">優惠查詢</a>
  <a href="discount_set.php">返點優惠設定</a>
  <a href="reg_discount_set.php">申請會員優惠設定</a>
   <a href="javascript:window.history.go(-1)">返回上一頁</a>
  </div>
</div>
<div class="content">
<form method="post" name="myFORM" action="./discount_count_cx.php" id="myFORM" onsubmit="return check();">
<input type="hidden" name="distype" value="dis_cx">
<input type="hidden" name="rtitle" value="<?=$title['back_time_start']?> ~ <?=$title['back_time_end']?>">
<input type="hidden" name="sid" value="<?=$_GET['id']?>">
<input type="hidden" name="zh" value="<?=$title['bet']?>">
<table width="99%" class="m_tab">        
  <tbody><tr class="m_title">
    <td colspan="29" height="27" align="center">日期：<?=$title['back_time_start']?> ~ <?=$title['back_time_end']?>  </td>
  </tr>

    <tr class="m_title">
        <td rowspan="2"><input type="checkbox" name="checkall"
              id="checkall" title="所有" onclick="return ckall();"></td>
        <td rowspan="2">代理商</td>
        <td rowspan="2">會員</td>
        <td rowspan="2">層級</td>
        <td rowspan="2">有效總<br>投注</td>
        <td colspan="8">有效投注</td>
        <td colspan="8">返點</td>
        <td rowspan="2">返點小計</td>
        <td rowspan="2">返水状态</td>
  </tr>
    <tr class="m_title">
      <td>體育</td>
            <td>彩票</td>
            <td>AG視訊</td>
            <td>MG視訊</td>
            <td>OG視訊</td>
            <td>CT視訊</td>
            <td>LEBO視訊</td>
            <td>BBIN視訊</td>
            <td>體育</td>
            <td>彩票</td>
            <td>AG視訊</td>
            <td>MG視訊</td>
            <td>OG視訊</td>
            <td>CT視訊</td>
            <td>LEBO視訊</td>
            <td>BBIN視訊</td>
    </tr>
<?php if (!empty($datas)) {
   $jNum = $iNum = 0;
   foreach ($datas as $key => $val) {
    $uJson = json_encode($val, JSON_UNESCAPED_UNICODE);
    $uJson = str_replace('"', '-', $uJson);
?>
    <tr class="m_cen">
      <td align="center">
        <font class="color_cx">
      <?php
         if ($val['state'] == 2) {
             echo "冲销";
         }else{
      ?></font>
        <input name="id[]" type="checkbox" value="<?=$uJson?>">
      <?php
        }
      ?>
     </td>
     <td nowrap="nowrap"><?=$val['agent_user']?></td>
            <td nowrap="nowrap"><?=$val['username']?></td>
            <td nowrap="nowrap"><?=$val['level_des']?></td>
            <td nowrap="nowrap"><?=$val['betall']?></td>
            <td><?=$val['sp_bet']?></td>
            <td><?=$val['fc_bet']?></td>
            <td><?=$val['ag_bet']?></td>
            <td><?=$val['mg_bet']?></td>
            <td><?=$val['og_bet']?></td>
            <td><?=$val['ct_bet']?></td>
            <td><?=$val['lebo_bet']?></td>
            <td><?=$val['bbin_bet']?></td>
            <td><?=$val['sp_fd']?></td>
            <td><?=$val['fc_fd']?></td>
            <td><?=$val['ag_fd']?></td>
            <td><?=$val['mg_fd']?></td>
            <td><?=$val['og_fd']?></td>
            <td><?=$val['ct_fd']?></td>
            <td><?=$val['lebo_fd']?></td>
            <td><?=$val['bbin_fd']?></td>
            <td><?=$val['total_e_fd']?></td>
            <td ><?=fs_state($val['state'])?></td>
    </tr>
   <?php 
       if ($val['state'] == 1) {
          $iNum ++;//优惠人数
       }elseif($val['state'] == 2){
          $jNum ++;//优惠冲销
       } 
     }
   }
   ?>
    <tr class="m_title">  
          <td align="center">總計：</td>
          <input type="hidden" name="money" value="<?=$title['total_fd']?>">
        <td colspan="3">總人數：<?=($title['people_num'] - $title['no_people_num']+0)?>人 <font class="color_cx">(冲销<?=$title['no_people_num']?>)人</font></td>
        <td><?=$title['totalbet']?></td>
        <td><?=$title['tospbet']?></td>
        <td><?=$title['tofcbet']?></td>
        <td><?=$title['toagbet']?></td>
        <td><?=$title['tomgbet']?></td>
        
        <td><?=$title['toogbet']?></td>
        <td><?=$title['toctbet']?></td>
        <td><?=$title['tolebobet']?></td>
        <td><?=$title['tobbinbet']?></td>
        <td><?=$title['tospfd']?></td>
        <td><?=$title['tofcfd']?></td>
        <td><?=$title['toagfd']?></td>
        <td><?=$title['tomgfd']?></td>
       
        <td><?=$title['toogfd']?></td>
        <td><?=$title['toctfd']?></td>
        <td><?=$title['tolebofd']?></td>
        <td><?=$title['tobbinfd']?></td>
        <td><?=$title['total_fd']?></td>
        <td></td>
  </tr>
   <tr>
    <td colspan="29" align="center" class="table_bg1"><input type="submit" name="submit" class="za_button" value="沖銷"></td>
  </tr>
</tbody></table> 
</form>
</div>

<?php 
  function fs_state($sta){
     if ($sta == 1) {
         return "返水完成";
     }elseif($sta == 2){
         return "优惠冲销";
     }
  }
?>
<?php require("../common_html/footer.php");?>
<?php 
$user = M('k_user',$db_config);
if(empty($_GET['rtype']) || $_GET['rtype'] =='sport'){
   $rtype = '體育';
}elseif($_GET['rtype'] =='caipiao'){
   $rtype = '彩票';
}else{
   $rtype = $_GET['rtype'];
}
if (!empty($_GET['uid'])) {
   $user_id = $_GET['uid'];
   $user_data = $user->field('username')->where("uid = '".$_GET['uid']."'")->find();
}
?>
<script type="text/javascript">
           //下拉选项自动跳转
$(document).ready(function(){
   $("#ttype").change(function(){
       var cp_url=$("#ttype").val();
       window.location.href=cp_url; 
   });
})
</script>
<body>
<div  id="con_wrap">
<div  class="input_002"><?=$user_data['username']?> - <?=$rtype?>下注記錄</div>
<div  class="con_menu">
<form  id="myFORM"  action=""  method="post"   name="FrmData">
  <input  name="username"  type="hidden"  id="username"  size="15"  value="e8fc1212"> 
  <input  name="mtype"  type="hidden"  id="mtype"  size="15"  value="1"> 
  &nbsp;&nbsp;視訊類型：
  <select  name="ttype"  id="ttype" >
    <option  value="member_record.php?uid=<?=$user_id?>&rtype=sport" 
     <?php select_ed($_GET['rtype'],'sport,');?> >體育</option>
    <option  value="member_record_cp.php?uid=<?=$user_id?>&rtype=caipiao" <?php select_ed($_GET['rtype'],'caipiao');?>>彩票</option>
    <option  value="member_record.php?uid=<?=$user_id?>&rtype=MG" <?php select_ed($_GET['rtype'],'MG');?>>MG視訊</option>
    <option  value="member_record.php?uid=<?=$user_id?>&rtype=AG" <?php select_ed($_GET['rtype'],'AG');?>>AG視訊</option>
    <option  value="member_record_og.php?uid=<?=$user_id?>&rtype=OG" <?php select_ed($_GET['rtype'],'OG');?>>OG視訊</option>
    <option  value="member_record.php?uid=<?=$user_id?>&rtype=BBIN" <?php select_ed($_GET['rtype'],'BBIN');?>>BBIN視訊</option>
  </select>
  &nbsp;&nbsp;日期：
  <input class="za_text Wdate" onclick="new Calendar(2008,2020).show(this);" value="" size="10" name="start_date"> -- <input  type="text"  name="end_date"  id="enddate"  readonly="readonly"  value=""   class="za_text Wdate" onclick="new Calendar(2008,2020).show(this);">

  <input  type="SUBMIT"  value="確定"  class="za_button">
  重新整理：
    <select  name="reload"  id="reload" >
      <option  value="-1">不自動更新</option>
      <option  value="5">5秒</option>
      <option  value="10">10秒</option>
      <option  value="15">15秒</option>
      <option  value="30">30秒</option>
      <option  value="60">60秒</option>
      <option  value="120">120秒</option>
    </select>
    <span  id="lblTime"  style="color:red"></span>
</form></div>
</div>
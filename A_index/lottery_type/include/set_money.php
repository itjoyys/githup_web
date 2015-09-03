<?php

include_once("../../include/config.php");

    if(!empty($_POST['Change'])){

      $map['val1']=$_POST['money1'];
      $map['val2']=$_POST['money2'];
      $map['val3']=$_POST['money3'];
      $map['val4']=$_POST['money4'];
      $map['val5']=$_POST['money5'];
      $map['is_work']=$_POST['status'];
      $data = M("ka_tan_set_money",$db_config)->where("uid = '".$_SESSION['uid']."'")->update($map);
      if(!$data){
        $map['uid']=$_SESSION['uid'];
        $map['site_id']=SITEID;
          M("ka_tan_set_money",$db_config)->add($map);
      }
      echo '<script>parent.parent.location.reload();</script>';
    }else{
       $data1 = M("ka_tan_set_money",$db_config)->where("uid = '".$_SESSION['uid']."'")->select();
    }
    // p($data1);
 ?>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>

<style>
  table.table2{font-family:Georgia,serif;font-size:11px;font-style:normal;font-weight:normal;letter-spacing:-1px;line-height:10px;border-collapse:collapse;text-align:center;clear:both;}.table2 thead th,.table2 tfoot td{padding:10px;color:#fff;font-size:12px;border:1px solid #ccc;background-color:#4682b4;font-weight:normal;-moz-box-shadow:0 -1px 4px #000;-webkit-box-shadow:0 -1px 4px #000;box-shadow:0 -1px 4px #333;text-shadow:1px 1px 1px #000;}.table2 tfoot th{padding:10px;font-size:12px;text-transform:uppercase;color:#555;}.table2 tfoot td{font-size:12px;color:#EFE70E;border-top:none;border-bottom:1px solid #666;-moz-box-shadow:0 1px 4px #333;-webkit-box-shadow:0 1px 4px #333;box-shadow:0 1px 4px #333;}.table2 thead th:empty{background:transparent;-moz-box-shadow:none;-webkit-box-shadow:none;box-shadow:none;}.table2 thead :nth-last-child(1){border-right:none;}.table2 tbody tr:nth-last-child(1) th{background-color:#333;color:#EF870E;}.table2 tbody th{text-align:right;/*padding:5px;*/color:#333;background-color:#f9f9f9;}.table2 tbody td{padding:5px;background-color:#f0f0f0;border:1px solid #ccc;border-top:none;text-transform:uppercase;color:#333;}.table2 tbody span.check::before{content:url(../images/check1.png);}.con_wrap_div{float:left;margin-right:10px;}.input_002{border:1px solid #cad8dc;padding:1px 1px 1px 2px;}.input_002{background:url(../images/sing_pic_002.gif) no-repeat 4px center;background-color:#fff;height:28px;line-height:28px;padding:0 15px 0 25px;width:70px;text-align:center;font-weight:bold;}.con_wrap{padding:2px;}
</style>
<!-- <script src="public/js/admin.js?=594" type="text/javascript"></script> -->
<!-- <script src="public/js/yhinput.js?=594" type="text/javascript"></script>
<script src="public/js/swfobject.js?=594" type="text/javascript"></script> -->
<script>
var tim_t="天";
var tim_x="小时";
var tim_f="分";
var tim_m="秒";
var uid='8fe385c47f3213d2fb83a';
var langx='zh-cn';
var lx='0';
top.cgTypebtn="r_class";

</script>

</head>
<body id="HOP" ondragstart="window.event.returnValue=false" oncontextmenu="window.event.returnValue=false" onselectstart="event.returnValue=false">

<form action="" name="form1" method="post">
<table width="100%" border="1" cellpadding="0" cellspacing="0" class="table2">
  <tbody><tr>
    <th style="background-Color:#4682B4;color:#fff;font-family:Arial, Helvetica, sans-serif">目前状态：</th>
    <td style="background-Color:#EBF0F1">
      <input type="radio" value="1" <?php if($data1 && $data1[0]['is_work']==1){echo "checked";}elseif(!$data1[0]){echo "checked";}else{echo 0;} ?> name="status">启用
      <input type="radio" value="" <?php if($data1 && $data1[0]['is_work']==0){echo "checked";}elseif(!$data1){echo 0;}else{echo 0;} ?> name="status">停用

    </td>
  </tr>
  <tr>
    <th style="background-Color:#4682B4;color:#fff;font-family:Arial, Helvetica, sans-serif;">快速金额1：</th>
    <td style="background-Color:#EBF0F1">
      <input type="text" name="money1" id="money1" class="input1" size="10" value="<?php if($data1){echo $data1[0]['val1'];}else{echo 100;} ?>">
    </td>
  </tr>
  <tr>
    <th style="background-Color:#4682B4;color:#fff;font-family:Arial, Helvetica, sans-serif">快速金额2：</th>
    <td style="background-Color:#EBF0F1">
      <input type="text" name="money2" id="money2" class="input1" size="10" value="<?php if($data1){echo $data1[0]['val2'];}else{echo 300;} ?>">
    </td>
  </tr>
  <tr>
    <th style="background-Color:#4682B4;color:#fff;font-family:Arial, Helvetica, sans-serif">快速金额3：</th>
    <td style="background-Color:#EBF0F1">
      <input type="text" name="money3" id="money3" class="input1" size="10" value="<?php if($data1){echo $data1[0]['val3'];}else{echo 500;} ?>">
    </td>
  </tr>
  <tr>
    <th style="background-Color:#4682B4;color:#fff;font-family:Arial, Helvetica, sans-serif">快速金额4：</th>
    <td style="background-Color:#EBF0F1">
      <input type="text" name="money4" id="money4" class="input1" size="10" value="<?php if($data1){echo $data1[0]['val4'];}else{echo 1000;} ?>">
    </td>
  </tr>
  <tr>
    <th style="background-Color:#4682B4;color:#fff;font-family:Arial, Helvetica, sans-serif">快速金额5：</th>
    <td style="background-Color:#EBF0F1">
      <input type="text" name="money5" id="money5" class="input1" size="10" value="<?php if($data1){echo $data1[0]['val5'];}else{echo 5000;} ?>">
    </td>
  </tr>

  <tr>
    <td colspan="2" align="center" style="background-Color:#EBF0F1">
      <input type="submit" name="Change" class="button_a" value="变更">
      <input id="reset_btn" type="reset" class="button_a" onclick="" value="重设">
    </td>
</tr></tbody></table>
</form>
</body></html>
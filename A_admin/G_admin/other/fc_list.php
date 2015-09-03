<?php
include("../../include/config.php");
include("../common/login_check.php"); 


$fcTitle = array('fc_3d'=>'福彩3D','pl_3'=>'排列三',
   'cq_ssc'=>'重庆时时彩','cq_ten'=>'重庆快乐十分',
   'gd_ten'=>'广东快乐十分','bj_8'=>'北京快乐8',
   'bj_10'=>'北京PK拾','tj_ssc'=>'天津时时彩',
   'xj_ssc'=>'新疆时时彩','jx_ssc'=>'江西时时彩','jl_k3'=>'吉林快三',
   'js_k3'=>'江苏快三','liuhecai'=>'六合彩'
  );


//站点彩票退水限额
$fcArr = array('fc_3d','pl_3','cq_ssc','cq_ten','gd_ten','bj_8','bj_10','tj_ssc','xj_ssc','jx_ssc','jl_k3','js_k3','liuhecai');
$Fcgame = M('fc_games_view',$db_config);
$Uagent = M('k_user_agent_fc_set',$db_config);
foreach ($fcArr as $key => $val) {
     $fcType[$val] = $Fcgame->join("join k_user_agent_fc_set on k_user_agent_fc_set.type_id = fc_games_view.id")->where("fc_games_view.fc_type = '".$val."' and k_user_agent_fc_set.aid = '".$_SESSION['agent_id']."'")->select();
}

$typeC = 'fc';
?>
<?php $title='限額/退水'; require("../common_html/header.php");?>
<body>
<style type="text/css">
  .m_tab{
    width: 60%;
  }
</style>
<div id="con_wrap">
<div class="input_002">限額/退水</div>
<div class="con_menu">類型：
<select onchange="document.location=this.value" name="stype">
  <option value="sp_list.php" <?php select_ed($typeC,'sp');?>>體育</option>
  <option value="fc_list.php" <?php select_ed($typeC,'fc');?>>彩票</option>

</select>
</div>
<div class="content">
<?php foreach ($fcType as $key => $fcval): ?>
   <table width="780" border="0" cellspacing="0" cellpadding="0" class="m_tab">
  <tbody>
  <tr class="m_title_over_co">
    <td height="25"><?=$fcTitle[$key]?> </td>
    <?php foreach ($fcval  as $k => $v): ?>
      <td><?=$v['name']?></td>
    <?php endforeach ?>
  </tr>
  <tr>
   <td nowrap="" align="center">退水設定</td>
       <?php foreach ($fcval  as $k => $v): ?>
      <td><?=$v['charges_a']?></td>
    <?php endforeach ?>
  <tr>
    <tr>
   <td nowrap="" align="center">單場限額:</td>
       <?php foreach ($fcval  as $k => $v): ?>
      <td><?=$v['single_field_max']?></td>
    <?php endforeach ?>
  <tr>
    <tr>
   <td nowrap="" align="center">單注限額::</td>
       <?php foreach ($fcval  as $k => $v): ?>
      <td><?=$v['single_note_max']?></td>
    <?php endforeach ?>
  <tr>
       <tr>
   <td nowrap="" align="center">最低限额::</td>
       <?php foreach ($fcval  as $k => $v): ?>
      <td><?=$v['min']?></td>
    <?php endforeach ?>
  <tr>
</tbody></table>
<?php endforeach ?>
<br>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>

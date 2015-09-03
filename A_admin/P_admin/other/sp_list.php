<?php
include("../../include/config.php");
include("../common/login_check.php");


$spTitle = array('ft'=>'足球','bk'=>'篮球','vb'=>'排球','bs'=>'棒球','tn'=>'网球');
//站点体育退水限额
$spArr = array('ft','bk','vb','bs','tn');

$Spgame = M('sp_games_view',$db_config);
$Uagent = M('k_user_agent_sport_set',$db_config);
foreach ($spArr as $key => $val) {
   $spType[$val] = $Spgame->join("join k_user_agent_sport_set on k_user_agent_sport_set.type_id = sp_games_view.id")->where("sp_games_view.type = '".$val."' and k_user_agent_sport_set.is_default = '1' and k_user_agent_sport_set.site_id ='".SITEID."'")->select();
}
$typeC = 'sp';
?>
<?php $title='限額/退水'; require("../common_html/header.php");?>
<body>
<style type="text/css">
  .m_tab{
    width: 90%;
  }
</style>
<div id="con_wrap">
<div class="input_002">限額/退水</div>
<div class="con_menu">類型：
<select onchange="document.location=this.value" name="stype">
  <option value="sp_list.php" <?php select_ed($typeC,'sp,');?>>體育</option>
  <option value="fc_list.php" <?php select_ed($typeC,'fc,');?>>彩票</option>
  <option value="video_list.php" <?php select_ed($typeC,'video,');?>>视讯</option>

</select>
</div>
<div class="content">
<?php foreach ($spType as $key => $spval): ?>

<table width="780" border="0" cellspacing="0" cellpadding="0" class="m_tab">
  <tbody>
  <tr class="m_title_over_co">
    <td height="25"><?=$spTitle[$key]?> </td>
    <?php foreach ($spval  as $k => $v): ?>
      <td><?=$v['t_name']?></td>
    <?php endforeach ?>
  </tr>
  <tr>
   <td nowrap="" align="center">退水設定</td>
       <?php foreach ($spval  as $k => $v): ?>
      <td><?=$v['water_break']?></td>
    <?php endforeach ?>
  <tr>
    <tr>
   <td nowrap="" align="center">單場限額:</td>
       <?php foreach ($spval  as $k => $v): ?>
      <td><?=$v['single_field_max']?></td>
    <?php endforeach ?>
  <tr>
    <tr>
   <td nowrap="" align="center">單注限額::</td>
       <?php foreach ($spval  as $k => $v): ?>
      <td><?=$v['single_note_max']?></td>
    <?php endforeach ?>
  <tr>
       <tr>
   <td nowrap="" align="center">最低限额::</td>
       <?php foreach ($spval  as $k => $v): ?>
      <td><?=$v['min']?></td>
    <?php endforeach ?>
  <tr>
</tbody></table>
<?php endforeach ?>
<br>

<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>

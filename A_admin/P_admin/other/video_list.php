<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once("../../class/Level.class.php");

$table = M('k_user_video_set',$db_config);
$data1 = $table->where("site_id = '".SITEID."'")->order("min ASC")->select();
$data = array();
//数组变形
foreach ($data1 as $k => $v) {
  if($v['type'] == 'OG'){
    $data['OG'][] = $v;
  }elseif ($v['type'] == 'AG') {
   $data['AG'][] = $v;
  }
}



$typeC = 'video';
?>
<?php require("../common_html/header.php");?>
<style type="text/css">
	#rs_window{
		width:380px;
		left: 50%;
		margin-left: -190px;
		position: fixed;
		top: 180px;
		display: none;
	}
  .m_tab{
    width: 60%;
  }
</style>
<script type="text/javascript">

   function show_win(title,wb,sfm,snm,min,id,type_id,aid){
      var title = '请输入'+title+'设定';
      $("#id").val(id);
      $("#aid").val(aid);
      $("#charges_a").val(wb);//退水
      $("#single_field_max").val(sfm);
      $("#single_note_max").val(snm);
      $("#min").val(min);
      $("#r_title").html(title);
      $("#type_id").val(type_id);//玩法类别id
      $("#rs_window").css("display","block");
   }

           //下拉选项自动跳转
$(document).ready(function(){
   $("#stype").change(function(){
       var cp_url=$("#stype").val();
       window.location.href=cp_url;
   });

 // 单项设置弹窗关闭
  	$("#set_close").click(function(){
		$("#rs_window").css("display","none");
	});
})
</script>
<body>
<div  id="con_wrap">
<div  class="input_002">設定-视讯</div>
<div  class="con_menu">
類型：<select onchange="document.location=this.value" name="stype">
  <option value="sp_list.php" <?php select_ed($typeC,'sp,');?>>體育</option>
  <option value="fc_list.php" <?php select_ed($typeC,'fc,');?>>彩票</option>
  <option value="video_list.php" <?php select_ed($typeC,'video,');?>>视讯</option>
		</select>
</div>
</div>
<div class="content">

<!-- og -->

<table width="780" border="0" cellspacing="0" cellpadding="0" class="m_tab" style="width:500px;">
<tbody>
  <tr class="m_title_over_co">

    <td>OG-ID</td>
    <td>下限</td>
    <td>上限</td>
    <td>筹码组</td>
  </tr>
<?php foreach ($data['OG'] as $key => $value) {
  if($value['default'] == 1){
  ?>
  <tr>

    <td><?=$key+1 ?></td>
    <td><?=$value['min'] ?></td>
    <td><?=$value['max'] ?></td>
    <td><?=$value['bet_arr'] ?></td>
  </tr>
<?php } }?>

</tbody>
</table>


<!-- ag -->

<table width="780" border="0" cellspacing="0" cellpadding="0" class="m_tab" style="width:400px;">
<tbody>
  <tr class="m_title_over_co">

    <td>AG-ID</td>
    <td>下限</td>
    <td>上限</td>

  </tr>
<?php foreach ($data['AG'] as $key => $value) {
  if($value['default'] == 1){
  ?>
  <tr>

    <td><?=$value['video_id'] ?></td>
    <td><?=$value['min'] ?></td>
    <td><?=$value['max'] ?></td>

  </tr>
<?php } }?>

</tbody>
</table>





</div>
<?php require("../common_html/footer.php");?>
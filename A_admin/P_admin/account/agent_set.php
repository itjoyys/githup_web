<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");

//标题
$spTitle = array('ft'=>'足球','bk'=>'篮球','vb'=>'排球',
                 'bs'=>'棒球','tn'=>'网球'
                 );

//站点体育退水限额
$spArr = array('ft','bk','vb','bs','tn');
$Spgame = M('sp_games_view',$db_config);
$Uagent = M('k_user_agent_sport_set',$db_config);


//单项设置处理
if ($_POST['type'] == 'setOne' && !empty($_POST['id'])) {
   $idS = $_POST['id'];
   $up_id = M('k_user_agent',$db_config)
          ->where("id = '".$_POST['aid']."'")
          ->getField('pid');//上一级id
   unset($_POST['aid']);
   unset($_POST['id']);//去掉不需要的数据
   unset($_POST['type']);
   //上一级数据上限
   $updata = array();
   if (empty($up_id)) {
      $updata = $Uagent->where("is_default = '1' and site_id = '".SITEID."' and type_id = '".$_POST['type_id']."'")
           ->find();
   }else{
      $updata = $Uagent->where("aid = '".$up_id."' and site_id = '".SITEID."' and type_id = '".$_POST['type_id']."'")
           ->find();
   }

   if ($_POST['min'] < $updata['min'] || $_POST['water_break'] > $updata['water_break'] || $_POST['single_field_max'] > $updata['single_field_max'] || $_POST['single_note_max'] > $updata['single_note_max']) {
      message("您设定的数据超出上级范围,设置上级数据!");
      exit();
   }
   $spSta = $Uagent->where("id = '".$idS."'")
          ->update($_POST);
   if ($spSta) {
       $do_log = '成功编辑体育设定:'.$_POST['type_id'];
       admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
       message("修改成功");
   }else{
      $do_log = '失败编辑体育设定:'.$_POST['type_id'];
      admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
      message("修改失败");
   }
}else{
   foreach ($spArr as $key => $val) {
     $spType[$val] = $Spgame->join("join k_user_agent_sport_set on k_user_agent_sport_set.type_id = sp_games_view.id")->where("sp_games_view.type = '".$val."' and k_user_agent_sport_set.aid = '".$_GET['aid']."'")->select();
  }
}


$typeC = 'sp';
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
    width: 90%;
  }
</style>

<script type="text/javascript">

   function show_win(title,wb,sfm,snm,min,id,type_id,aid){
      var title = '请输入'+title+'设定';
   	  $("#id").val(id);
      $("#aid").val(aid);
  		$("#water_break").val(wb);//退水
      $("#single_field_max").val(sfm);
  		$("#single_note_max").val(snm);
  		$("#min").val(min);
  		$("#r_title").html(title);
  		$("#type_id").val(type_id);//玩法类别id
  		$("#rs_window").css("display","block");
   }


           //下拉选项自动跳转
$(document).ready(function(){
 // 单项设置弹窗关闭
  	$("#set_close").click(function(){
		$("#rs_window").css("display","none");
	});
})
</script>
<body>
<div  id="con_wrap">
<div  class="input_002">設定-體育</div>
<div  class="con_menu">
設定類型：<select onchange="document.location=this.value" name="stype">
           <option value="agent_set.php?aid=<?=$_GET['aid']?>" <?php select_ed($typeC,'sp');?>>體育</option>
     <option value="agent_fc_set.php?aid=<?=$_GET['aid']?>" <?php select_ed($typeC,'fc');?>>彩票</option>
     <option value="user_video_set.php?aid=<?=$_GET['aid']?>" <?php select_ed($typeC,'video');?>>视讯</option>
</select>
</div>
</div>
<div class="content">
<?php foreach ($spType as $key => $spval): ?>
<table width="780" border="0" cellspacing="0" cellpadding="0" class="m_tab">
  <tbody>
  <tr class="m_title_over_co">
    <td height="25"><?=$spTitle[$key]?> </td>
    <?php foreach ($spval as $k => $v): ?>
      <td><?=$v['t_name']?></td>
    <?php endforeach ?>
  </tr>
  <tr>
   <td nowrap="" align="center">退水設定</td>
       <?php foreach ($spval as $k => $v): ?>
      <td><?=$v['water_break']?></td>
    <?php endforeach ?>
  <tr>
    <tr>
   <td nowrap="" align="center">單場限額:</td>
       <?php foreach ($spval as $k => $v): ?>
      <td><?=$v['single_field_max']?></td>
    <?php endforeach ?>
  <tr>
    <tr>
   <td nowrap="" align="center">單注限額::</td>
       <?php foreach ($spval as $k => $v): ?>
      <td><?=$v['single_note_max']?></td>
    <?php endforeach ?>
  <tr>
       <tr>
   <td nowrap="" align="center">最低限额::</td>
       <?php foreach ($spval as $k => $v): ?>
      <td><?=$v['min']?></td>
    <?php endforeach ?>
  <tr>
         <tr>
   <td nowrap="" align="center"><a href=""></a></td>
       <?php foreach ($spval as $k => $v): ?>
      <td><a href="javascript:void(0)" onclick="show_win('<?=$spTitle[$key]?>-<?=$v['t_name']?>','<?=$v['water_break']?>','<?=$v['single_field_max']?>','<?=$v['single_note_max']?>','<?=$v['min']?>','<?=$v['id']?>','<?=$v['type_id']?>','<?=$v['aid']?>');"> 修改</a></td>
    <?php endforeach ?>
  <tr>
</tbody></table>
<?php endforeach ?>
<br>

<!--弹框-->
<div id="rs_window">
	<form  name="rs_form" action=""  method="POST" >
		<input  type="hidden" id="type"  name="type"  value="setOne">
		<input  type="hidden" id="id"  name="id"  value="">
      <input  type="hidden" id="aid"  name="aid"  value="">
		<input  type="hidden" id="type_id"  name="type_id"  value="">
		<ul class="acc_ul_box"  style="width:355px">
			<li class="acc_li_first">
			<span  class="title_sp"  id="r_title"></span>
				<span  class="x_sp"><a id="set_close" style="display:block;" href="javascript:void(0);"><img src="../public/images/mem_icon.gif"  width="16"  height="14"></a></span>
			</li>
			<li>
				<span  class="title_sp_sub">退水設定:</span>
				<span  class="title_sp_sub2">
				<select class="z_select2" id="s_water_break"  name="water_break">
					<option vaule="0">0</option>
				</select></span>
			</li>
			<li>
				<span  class="title_sp_sub">單場限額:</span>
				<span  class="title_sp_sub2"><input  type="text"  id="single_field_max"  name="single_field_max"  value=""  size="12"  maxlength="10"  class="za_text" >人民幣</span>
			</li>

			<li>
				<span  class="title_sp_sub">單注限額:</span>
				<span  class="title_sp_sub2"><input  type="TEXT"  id="single_note_max"  name="single_note_max"  value=""  size="12"  maxlength="10"  class="za_text">人民幣</span>
			</li>
			<li>
				<span  class="title_sp_sub">最低限額:</span>
				<span  class="title_sp_sub2"><input  type="text"  id="min"  name="min"  value=""  size="12"  maxlength="8"  class="za_text">人民幣</span>
			</li>
			<li  class="acc_li_btnBox">
				<input  type="submit"  value="確定"  class="za_button">
			</li>
		</ul>
	</form>
</div>

</div>
<?php require("../common_html/footer.php");?>